<?php

namespace App\Controllers;

use App\Models\MUserModel;
use App\Models\MTenantModel;
use App\Models\ServiceTypeModel;
use App\Services\MidtransService;
use App\Entities\MidtransNotification;
use App\Models\MSubscriptionPlanModel;

class OnboardingController extends BaseController
{
    protected $userModel;
    protected $tenantModel;
    protected $serviceTypeModel;
    protected $db;
    protected $menuModel;
    protected $baseDomain;
    protected $midtransService;
    protected $subscriptionPlanModel;

    public function __construct()
    {
        $this->userModel = new MUserModel();
        $this->tenantModel = new MTenantModel();
        $this->serviceTypeModel = new ServiceTypeModel();
        $this->db = \Config\Database::connect();
        $this->menuModel = new \App\Models\MenuModel();
        $this->baseDomain = env('BASE_DOMAIN', 'smartpricingandpaymentsystem.localhost.com');
        $this->midtransService = new MidtransService();
        $this->subscriptionPlanModel = new MSubscriptionPlanModel();
    }

    /**
     * Tampilkan form pembuatan tenant setelah login Google
     */
    public function setupTenant()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }        // Cek apakah user sudah punya tenant
        $userId = session()->get('userID');
        $existingTenant = $this->tenantModel->where('intOwnerID', $userId)->first();

        if ($existingTenant) {
            return redirect()->to('/dashboard');
        }        // Get menus based on user role
        $roleId = session()->get('roleID');
        $menus = $this->menuModel->getMenusByRole($roleId);
        
        $plans = $this->subscriptionPlanModel->getAllActivePlans();
        $data = [
            'title' => 'Setup Your Business',
            'serviceTypes' => $this->serviceTypeModel->where('bitActive', 1)->findAll(),
            'validation' => \Config\Services::validation(),
            'menus' => $menus,
            'plans' => $plans
        ];

        return view('onboarding/setup_tenant', $data);
    }

    /**
     * Proses pembuatan tenant dan upgrade role user
     */
    public function createTenant()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $validation = \Config\Services::validation();
        $validation->setRules([
            'name' => 'required',
            'service_type_id' => 'required|numeric',
            'subscription_plan' => 'required|in_list[free,basic,premium,enterprise]',
            'domain' => 'permit_empty|valid_url',
            'description' => 'required'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $userId = session()->get('userID');
        $subscriptionPlan = $this->request->getPost('subscription_plan');
        $planData = $this->subscriptionPlanModel->getPlanByCode($subscriptionPlan);
        if (!$planData) {
            return redirect()->back()->withInput()->with('error', 'Invalid subscription plan selected.');
        }

        try {
            // Generate normalized subdomain from business name
            $normalizedSubdomain = $this->tenantModel->normalizeSubdomain($this->request->getPost('name'));
            if (!$this->tenantModel->isSubdomainAvailable($normalizedSubdomain)) {
                $normalizedSubdomain = $normalizedSubdomain . '-' . strtolower(substr(md5(time()), 0, 4));
            }

            // Generate tenant data
            $tenantData = [
                'txtTenantName' => $this->request->getPost('name'),
                'intServiceTypeID' => $this->request->getPost('service_type_id'),
                'txtSubscriptionPlan' => $subscriptionPlan,
                'txtDomain' => $normalizedSubdomain,
                'txtSlug' => $this->tenantModel->generateTenantSlug($this->request->getPost('name')),
                'txtTenantCode' => strtoupper(substr(md5(time()), 0, 8)),
                'intOwnerID' => $userId,
                'txtStatus' => $subscriptionPlan === 'free' ? 'active' : 'pending',
                'txtGUID' => uniqid('tenant_', true),
                'txtCreatedBy' => $userId,
                'dtmCreatedDate' => date('Y-m-d H:i:s'),
                'jsonSettings' => json_encode([
                    'description' => $this->request->getPost('description'),
                    'theme' => 'default'
                ]),
                'jsonPaymentSettings' => json_encode(['currency' => 'IDR']),
                'dtmTrialEndsAt' => date('Y-m-d H:i:s', strtotime('+14 days')),
                'bitActive' => 1
            ];

            // Start transaction
            $this->db->transStart();

            // Insert tenant
            $tenantId = $this->tenantModel->insert($tenantData);
            
            // Generate tenant URL with subdomain format
            $tenantUrl = generate_tenant_url($normalizedSubdomain);

            // Update user role to tenant owner
            $this->userModel->update($userId, [
                'intRoleID' => 3, // Tenant Owner role
                'intTenantID' => $tenantId,
                'bitIsTenantOwner' => 1,
                'intDefaultTenantID' => $tenantId
            ]);

            // Commit transaction
            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                throw new \Exception('Failed to create tenant and update user role');
            }

            // Handle different subscription plans
            if ($subscriptionPlan === 'free') {
                // Redirect to tenant subdomain since free plan is automatically activated
                return redirect()->to($tenantUrl)->with('success', 'Business tenant created successfully!');
            } else {
                // Get subscription pricing from DB
                $pricing = [
                    'amount' => $planData['decAmount'],
                    'name' => $planData['txtName']
                ];
                  // Setup Midtrans payment
                try {
                    $orderId = 'TENANT-' . $tenantId . '-' . time();
                    
                    $params = [
                        'transaction_details' => [
                            'order_id' => $orderId,
                            'gross_amount' => $pricing['amount']
                        ],
                        'customer_details' => [
                            'first_name' => session()->get('userFullName'),
                            'email' => session()->get('userEmail')
                        ],
                        'item_details' => [
                            [
                                'id' => 'SUBSCRIPTION-' . strtoupper($subscriptionPlan),
                                'price' => $pricing['amount'],
                                'quantity' => 1,
                                'name' => $pricing['name'] . ' Subscription'
                            ]
                        ]
                    ];

                    $result = $this->midtransService->createPaymentToken($params);
                    if (!$result['success']) {
                        throw new \Exception($result['message']);
                    }

                    return view('onboarding/payment', [
                        'title' => 'Complete Subscription Payment',
                        'tenantId' => $tenantId,
                        'snapToken' => $result['token'],
                        'plan' => $subscriptionPlan,
                        'amount' => $pricing['amount'],
                        'planName' => $pricing['name'],
                        'tenantUrl' => $tenantUrl // Pass tenant URL to view for post-payment redirection
                    ]);
                } catch (\Exception $e) {
                    log_message('error', '[Midtrans Payment] Error: ' . $e->getMessage());
                    throw new \Exception('Failed to setup payment. Please try again or contact support.');
                }
            }
        } catch (\Exception $e) {
            log_message('error', '[OnboardingController::createTenant] Error: ' . $e->getMessage());
            return redirect()->back()->withInput()
                ->with('error', 'Failed to create tenant. ' . $e->getMessage());
        }
    }

    private function getSubscriptionPricing($plan)
    {
        $planData = $this->subscriptionPlanModel->getPlanByCode($plan);
        if ($planData) {
            return ['amount' => $planData['decAmount']];
        }
        return ['amount' => 0];
    }

    /**
     * Setup tenant branding dan pengaturan lanjutan
     */
    public function setupBranding($tenantId)
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }        $tenant = $this->tenantModel->find($tenantId);
        
        // Validasi akses
        if (!$tenant || $tenant['intOwnerID'] != session()->get('userID')) {
            return redirect()->to('/dashboard');
        }

        $data = [
            'title' => 'Setup Your Brand',
            'tenant' => $tenant,
            'validation' => \Config\Services::validation()
        ];

        return view('onboarding/setup_branding', $data);
    }

    /**
     * Update branding tenant
     */
    public function updateBranding($tenantId)
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }        $tenant = $this->tenantModel->find($tenantId);
        
        // Validasi akses
        if (!$tenant || $tenant['intOwnerID'] != session()->get('userID')) {
            return redirect()->to('/dashboard');
        }

        // Handle logo upload
        $logo = $this->request->getFile('logo');
        if ($logo && $logo->isValid() && !$logo->hasMoved()) {
            $newName = $logo->getRandomName();
            $logo->move(ROOTPATH . 'public/uploads/tenants', $newName);
              // Delete old logo if exists
            if ($tenant['txtLogo'] && file_exists(ROOTPATH . 'public/uploads/tenants/' . $tenant['txtLogo'])) {
                unlink(ROOTPATH . 'public/uploads/tenants/' . $tenant['txtLogo']);
            }
            
            // Update tenant dengan logo baru
            $this->tenantModel->update($tenantId, [
                'txtLogo' => $newName,
                'txtTheme' => $this->request->getPost('theme'),
                'jsonSettings' => json_encode([
                    'primary_color' => $this->request->getPost('primary_color'),
                    'secondary_color' => $this->request->getPost('secondary_color')
                ])
            ]);
        }

        return redirect()->to('/dashboard')
            ->with('success', 'Brand settings updated successfully!');
    }

    public function paymentSuccess($tenantId)
    {
        $transactionId = $this->request->getGet('transaction_id');
        
        try {
            // Verify transaction status with Midtrans
            \Midtrans\Config::$serverKey = getenv('MIDTRANS_SERVER_KEY');
            \Midtrans\Config::$isProduction = getenv('MIDTRANS_IS_PRODUCTION') == 'true';
            
            $transactionData = \Midtrans\Transaction::status($transactionId);
            $notification = new MidtransNotification((array)$transactionData);
            
            if ($notification->transaction_status === 'settlement' || 
                $notification->transaction_status === 'capture') {
                
                // Activate tenant subscription
                $this->tenantModel->activateSubscription($tenantId, $notification->toArray());
                
                return view('onboarding/payment_success', [
                    'title' => 'Payment Successful',
                    'tenantId' => $tenantId,
                    'transaction' => $notification->toArray()
                ]);
            } else {
                throw new \Exception('Payment verification failed. Status: ' . $notification->transaction_status);
            }
            
        } catch (\Exception $e) {
            log_message('error', '[Payment Verification] Error: ' . $e->getMessage());
            return redirect()->to('/onboarding/payment-failed/' . $tenantId)
                ->with('error', 'Payment verification failed. Please contact support.');
        }
    }

    public function paymentPending($tenantId)
    {
        return view('onboarding/payment_pending', [
            'title' => 'Payment Pending',
            'tenantId' => $tenantId,
            'transaction_id' => $this->request->getGet('transaction_id')
        ]);
    }

    public function paymentFailed($tenantId)
    {
        return view('onboarding/payment_failed', [
            'title' => 'Payment Failed',
            'tenantId' => $tenantId,
            'error_message' => $this->request->getGet('message')
        ]);
    }

    public function midtransNotification()
    {
        try {
            \Midtrans\Config::$serverKey = getenv('MIDTRANS_SERVER_KEY');
            \Midtrans\Config::$isProduction = getenv('MIDTRANS_IS_PRODUCTION') == 'true';

            $midtransNotification = new \Midtrans\Notification();
            $notification = new MidtransNotification((array)$midtransNotification);
            
            $transactionStatus = $notification->transaction_status;
            $orderId = $notification->order_id;
            $fraudStatus = $notification->fraud_status;

            // Extract tenant ID from order ID (format: TENANT-{id}-timestamp)
            preg_match('/TENANT-(\d+)-/', $orderId, $matches);
            $tenantId = $matches[1] ?? null;

            if (!$tenantId) {
                throw new \Exception('Invalid order ID format');
            }

            if ($transactionStatus == 'capture') {
                if ($fraudStatus == 'challenge') {
                    // Do nothing, wait for manual verification
                    $this->tenantModel->update($tenantId, ['txtStatus' => 'pending_verification']);
                } else if ($fraudStatus == 'accept') {
                    $this->tenantModel->activateSubscription($tenantId, $notification->toArray());
                }
            } else if ($transactionStatus == 'settlement') {
                $this->tenantModel->activateSubscription($tenantId, $notification->toArray());
            } else if ($transactionStatus == 'cancel' || 
                      $transactionStatus == 'deny' || 
                      $transactionStatus == 'expire') {
                $this->tenantModel->update($tenantId, ['txtStatus' => 'payment_failed']);
            } else if ($transactionStatus == 'pending') {
                $this->tenantModel->update($tenantId, ['txtStatus' => 'pending_payment']);
            }

            // Return 200 OK
            $this->response->setStatusCode(200);
            return $this->response->setJSON(['status' => 'OK']);
            
        } catch (\Exception $e) {
            log_message('error', '[Midtrans Notification] Error: ' . $e->getMessage());
            
            // Return 500 Error
            $this->response->setStatusCode(500);
            return $this->response->setJSON(['error' => $e->getMessage()]);
        }
    }
    
    protected function setupPaymentGateway($orderId, $amount, $customer)
    {
        try {
            $params = [
                'transaction_details' => [
                    'order_id' => $orderId,
                    'gross_amount' => $amount
                ],
                'customer_details' => $customer,
                'enabled_payments' => [
                    'credit_card', 'mandiri_clickpay', 'cimb_clicks',
                    'bca_klikbca', 'bca_klikpay', 'bri_epay', 'echannel', 'permata_va',
                    'bca_va', 'bni_va', 'bri_va', 'other_va', 'gopay', 'indomaret',
                    'alfamart', 'danamon_online', 'akulaku'
                ]
            ];

            $result = $this->midtransService->createPaymentToken($params);
            
            if (!$result['success']) {
                throw new \Exception($result['message']);
            }

            return $result['token'];

        } catch (\Exception $e) {
            log_message('error', '[OnboardingController::setupPaymentGateway] Error: ' . $e->getMessage());
            throw $e;
        }
    }
}
