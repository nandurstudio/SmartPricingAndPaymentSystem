<?php

namespace App\Controllers;

use App\Models\MTenantModel;

class TenantsController extends BaseController
{
    protected $tenantModel;
    protected $menuModel;
    protected $serviceTypeModel;
    protected $baseDomain;

    public function __construct()
    {
        $this->tenantModel = new MTenantModel();
        $this->menuModel = new \App\Models\MenuModel();
        $this->serviceTypeModel = new \App\Models\ServiceTypeModel();
        $this->baseDomain = env('BASE_DOMAIN', 'smartpricingandpaymentsystem.localhost.com');
    }

    public function index()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'You must be logged in to access this page.');
        }

        $userId = session()->get('userID');
        $roleId = session()->get('roleID');

        // Get menus by role
        $menus = $this->menuModel->getMenusByRole($roleId);
        
        // Get tenants based on role
        $tenants = $this->tenantModel->getUserTenants($userId, $roleId);

        return view('tenants/index', [
            'title' => 'My Tenants',
            'pageTitle' => 'My Tenants',
            'pageSubTitle' => 'Manage your business tenants',
            'icon' => 'briefcase',
            'menus' => $menus,
            'tenants' => $tenants
        ]);
    }

    public function create()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'You must be logged in to access this page.');
        }

        $userId = session()->get('userID');
        $roleId = session()->get('roleID');
        
        // Check if this is a first-time tenant creation
        $existingTenant = $this->tenantModel->where('intOwnerID', $userId)->first();
        if (!$existingTenant && $roleId == 4) { // Regular user role
            return redirect()->to('/onboarding/setup-tenant');
        }

        $menus = $this->menuModel->getMenusByRole($roleId);
        
        // Get all active service types
        $serviceTypes = $this->serviceTypeModel->where('bitActive', 1)->findAll();

        return view('tenants/create', [
            'title' => 'Create Tenant',
            'pageTitle' => 'Create New Tenant',
            'pageSubTitle' => 'Register a new business tenant',
            'icon' => 'plus-circle',
            'menus' => $menus,
            'serviceTypes' => $serviceTypes,
            'validation' => \Config\Services::validation()
        ]);
    }

    public function checkSubdomain()
    {
        $subdomain = $this->request->getGet('subdomain');
        
        if (empty($subdomain)) {
            return $this->response->setJSON([
                'available' => false,
                'message' => 'Subdomain is required'
            ]);
        }

        $normalizedSubdomain = $this->tenantModel->normalizeSubdomain($subdomain);
        $isAvailable = $this->tenantModel->isSubdomainAvailable($normalizedSubdomain);

        return $this->response->setJSON([
            'available' => $isAvailable,
            'normalized' => $normalizedSubdomain,
            'message' => $isAvailable ? 'Subdomain is available' : 'Subdomain is already taken'
        ]);
    }

    protected function prepareNewTenant($userId, $normalizedSubdomain)
    {
        return [
            'txtTenantName' => $this->request->getPost('txtTenantName'),
            'intServiceTypeID' => $this->request->getPost('intServiceTypeID'),
            'txtSlug' => $this->tenantModel->generateTenantSlug($this->request->getPost('txtTenantName')),
            'intOwnerID' => $userId,
            'txtDomain' => $normalizedSubdomain,
            'txtTenantCode' => strtoupper(substr(md5(time()), 0, 8)),
            'txtSubscriptionPlan' => 'basic',
            'txtSubscriptionStatus' => 'inactive',
            'txtStatus' => 'active',
            'dtmTrialEndsAt' => date('Y-m-d H:i:s', strtotime('+14 days')),
            'jsonSettings' => json_encode(['theme' => 'default']),
            'jsonPaymentSettings' => json_encode(['currency' => 'IDR']),
            'txtTheme' => 'default',
            'bitActive' => 1,
            'txtCreatedBy' => session()->get('userName'),
            'txtGUID' => uniqid('tenant_', true)
        ];
    }

    public function store()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'You must be logged in to access this page.');
        }

        $userId = session()->get('userID');

        // Validation rules
        $rules = [
            'txtTenantName' => 'required|min_length[3]|max_length[100]',
            'intServiceTypeID' => 'required|numeric',
            'txtDomain' => 'permit_empty|max_length[255]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Handle logo upload
        $data = ['txtLogo' => null];
        $logo = $this->request->getFile('txtLogo');
        if ($logo && $logo->isValid() && !$logo->hasMoved()) {
            $newName = $logo->getRandomName();
            $logo->move(ROOTPATH . 'public/uploads/tenants', $newName);
            $data['txtLogo'] = $newName;
        }

        // Get and normalize subdomain
        $subdomain = $this->request->getPost('txtDomain');
        $normalizedSubdomain = $this->tenantModel->normalizeSubdomain($subdomain ? $subdomain : $this->request->getPost('txtTenantName'));

        if (!$this->tenantModel->isSubdomainAvailable($normalizedSubdomain)) {
            return redirect()->back()->withInput()
                ->with('error', 'The subdomain is already taken. Please choose another one.');
        }

        // Prepare data and insert tenant
        $data = array_merge($data, $this->prepareNewTenant($userId, $normalizedSubdomain));
        if ($tenantId = $this->tenantModel->insert($data)) {
            // Generate default CSS for the new tenant
            $this->tenantModel->generateDefaultCSS($tenantId);
            
            // Generate tenant URL with subdomain format and redirect
            $tenantUrl = generate_tenant_url($normalizedSubdomain);

            if ($data['txtStatus'] === 'active') {
                return redirect()->to($tenantUrl)
                    ->with('success', 'Tenant created successfully. Redirecting to your website...');
            }

            return redirect()->to('/tenants')
                ->with('success', 'Tenant created successfully. Please wait for approval.');
        }

        return redirect()->back()->withInput()
            ->with('errors', $this->tenantModel->errors());
    }

    public function edit($id)
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'You must be logged in to access this page.');
        }

        $userId = session()->get('userID');
        $roleId = session()->get('roleID');
        $tenant = $this->tenantModel->find($id);

        // Check permissions
        if (!$tenant || ($tenant['intOwnerID'] != $userId && $roleId != 1)) {
            return redirect()->to('/tenants')->with('error', 'You do not have permission to edit this tenant.');
        }        $menus = $this->menuModel->getMenusByRole($roleId);
        
        // Get all active service types
        $serviceTypes = $this->serviceTypeModel->where('bitActive', 1)->findAll();

        return view('tenants/edit', [
            'title' => 'Edit Tenant',
            'pageTitle' => 'Edit Tenant',
            'pageSubTitle' => 'Update tenant settings',
            'icon' => 'edit',
            'menus' => $menus,
            'tenant' => $tenant,
            'serviceTypes' => $serviceTypes,
            'validation' => \Config\Services::validation()
        ]);
    }

    public function update($id)
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'You must be logged in to access this page.');
        }

        $userId = session()->get('userID');
        $roleId = session()->get('roleID');
        $tenant = $this->tenantModel->find($id);
        
        // Check permissions
        if (!$tenant || ($tenant['intOwnerID'] != $userId && $roleId != 1)) {
            return redirect()->to('/tenants')->with('error', 'You do not have permission to update this tenant.');
        }

        // Validation rules
        $rules = [
            'txtTenantName' => 'required|min_length[3]|max_length[100]',
            'intServiceTypeID' => 'required|numeric',
            'txtDomain' => 'permit_empty|max_length[255]',
            'txtStatus' => 'required|in_list[active,inactive,suspended,pending,pending_verification,pending_payment,payment_failed]',
            'txtSubscriptionPlan' => 'required|in_list[free,basic,premium,enterprise]',
            'txtTheme' => 'permit_empty|in_list[default,dark,light]',
            'bitActive' => 'permit_empty|in_list[0,1]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [];

        // Handle logo removal
        if ($this->request->getPost('removeLogo') && $tenant['txtLogo']) {
            $logoPath = ROOTPATH . 'public/uploads/tenants/' . $tenant['txtLogo'];
            if (file_exists($logoPath)) {
                unlink($logoPath);
            }
            $data['txtLogo'] = null;
        }
        // Handle logo upload
        else {
            $logo = $this->request->getFile('txtLogo');
            if ($logo && $logo->isValid() && !$logo->hasMoved()) {
                $newName = $logo->getRandomName();
                $logo->move(ROOTPATH . 'public/uploads/tenants', $newName);
                
                // Delete old logo if exists
                if ($tenant['txtLogo'] && file_exists(ROOTPATH . 'public/uploads/tenants/' . $tenant['txtLogo'])) {
                    unlink(ROOTPATH . 'public/uploads/tenants/' . $tenant['txtLogo']);
                }
                
                $data['txtLogo'] = $newName;
            }
        }

        // Get and normalize subdomain
        $subdomain = $this->request->getPost('txtDomain');
        if ($subdomain !== $tenant['txtDomain']) {
            $normalizedSubdomain = $this->tenantModel->normalizeSubdomain($subdomain);
            // Check if subdomain is available (exclude current tenant)
            if (!empty($normalizedSubdomain) && $this->tenantModel->where('txtDomain', $normalizedSubdomain)
                                                             ->where('intTenantID !=', $id)
                                                             ->first()) {
                return redirect()->back()->withInput()
                    ->with('error', 'The subdomain is already taken. Please choose another one.');
            }
            $data['txtDomain'] = $normalizedSubdomain;
        }

        // Prepare update data
        $data = array_merge($data, [
            'txtTenantName' => $this->request->getPost('txtTenantName'),
            'intServiceTypeID' => $this->request->getPost('intServiceTypeID'),
            'txtStatus' => $this->request->getPost('txtStatus'),
            'txtSubscriptionPlan' => $this->request->getPost('txtSubscriptionPlan'),
            'txtTheme' => $this->request->getPost('txtTheme'),
            'bitActive' => $this->request->getPost('bitActive') ?? 0,
            'jsonSettings' => $this->request->getPost('jsonSettings'),
            'jsonPaymentSettings' => $this->request->getPost('jsonPaymentSettings'),
            'txtMidtransClientKey' => $this->request->getPost('txtMidtransClientKey'),
            'txtMidtransServerKey' => $this->request->getPost('txtMidtransServerKey'),
            'txtUpdatedBy' => session()->get('userName')
        ]);

        // Update data
        if ($this->tenantModel->update($id, $data)) {
            // Regenerate CSS if theme changed
            if (isset($data['txtTheme']) && $data['txtTheme'] !== $tenant['txtTheme']) {
                $this->tenantModel->generateDefaultCSS($id);
            }

            // If subdomain changed and tenant is active, redirect to new subdomain
            if (isset($data['txtDomain']) && 
                $data['txtDomain'] !== $tenant['txtDomain'] && 
                $data['txtStatus'] === 'active') {
                $tenantUrl = generate_tenant_url($data['txtDomain']);
                return redirect()->to($tenantUrl)
                    ->with('success', 'Tenant updated successfully. Redirecting to new domain...');
            }

            return redirect()->to('/tenants')
                ->with('success', 'Tenant updated successfully.');
        }

        return redirect()->back()->withInput()
            ->with('errors', $this->tenantModel->errors());
    }

    public function view($id)
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'You must be logged in to access this page.');
        }

        $userId = session()->get('userID');
        $roleId = session()->get('roleID');

        // Get tenant with service type details
        $tenant = $this->tenantModel->getTenantDetails($id);
        
        if (!$tenant || ($tenant['intOwnerID'] != $userId && $roleId != 1)) {
            return redirect()->to('/tenants')->with('error', 'You do not have permission to view this tenant.');
        }

        // Get services for this tenant
        $serviceModel = new \App\Models\ServiceModel();
        $services = $serviceModel->getServicesWithType($id);

        $data = [
            'title' => 'View Tenant',
            'pageTitle' => 'Tenant Details',
            'pageSubTitle' => 'View tenant information and services',
            'icon' => 'info-circle',
            'tenant' => $tenant,
            'services' => $services
        ];

        return view('tenants/view', $data);
    }

    /**
     * Initialize payment for tenant subscription activation
     */
    public function activateSubscription($id)
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'You must be logged in to access this page.');
        }

        $userId = session()->get('userID');
        $roleId = session()->get('roleID');
        $tenant = $this->tenantModel->find($id);

        // Check permissions
        if (!$tenant || ($tenant['intOwnerID'] != $userId && $roleId != 1)) {
            return redirect()->to('/tenants')->with('error', 'You do not have permission to activate this tenant.');
        }

        if ($tenant['txtSubscriptionStatus'] === 'active') {
            return redirect()->to('/tenants/view/' . $id)->with('info', 'Subscription is already active.');
        }

        try {
            // Get subscription pricing for the tenant's plan
            $pricing = $this->getSubscriptionPricing($tenant['txtSubscriptionPlan']);            // Update tenant status to pending_payment
            $this->tenantModel->update($id, [
                'txtStatus' => 'pending_payment',
                'txtUpdatedBy' => session()->get('userID'),
                'dtmUpdatedDate' => date('Y-m-d H:i:s')
            ]);
            
            // Setup Midtrans payment
            $orderId = 'TENANT-' . $tenant['intTenantID'] . '-' . time();
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
                        'id' => 'SUBSCRIPTION-' . strtoupper($tenant['txtSubscriptionPlan']),
                        'price' => $pricing['amount'],
                        'quantity' => 1,
                        'name' => ucfirst($tenant['txtSubscriptionPlan']) . ' Plan Subscription'
                    ]
                ],
                'enabled_payments' => [
                    'credit_card', 'mandiri_clickpay', 'cimb_clicks',
                    'bca_klikbca', 'bca_klikpay', 'bri_epay', 'echannel', 'permata_va',
                    'bca_va', 'bni_va', 'bri_va', 'other_va', 'gopay', 'indomaret',
                    'alfamart', 'danamon_online', 'akulaku'
                ]
            ];

            // Initialize Midtrans configuration
            \Midtrans\Config::$serverKey = getenv('MIDTRANS_SERVER_KEY');
            \Midtrans\Config::$isProduction = getenv('MIDTRANS_IS_PRODUCTION') == 'true';
            \Midtrans\Config::$isSanitized = true;
            \Midtrans\Config::$is3ds = true;

            // Get Snap Token
            $snapToken = \Midtrans\Snap::getSnapToken($params);

            return view('tenants/payment', [
                'title' => 'Complete Subscription Payment',
                'pageTitle' => 'Activate Subscription',
                'pageSubTitle' => 'Complete payment to activate your subscription',
                'tenant' => $tenant,
                'snapToken' => $snapToken,
                'plan' => $tenant['txtSubscriptionPlan'],
                'amount' => $pricing['amount']
            ]);

        } catch (\Exception $e) {
            log_message('error', '[TenantsController::activateSubscription] Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to setup payment. ' . $e->getMessage());
        }
    }

    /**
     * Handle successful payment callback
     */
    public function paymentSuccess($id)
    {
        try {
            $transactionId = $this->request->getGet('transaction_id');
            if (!$transactionId) {
                throw new \Exception('Transaction ID is required');
            }

            // Verify transaction status with Midtrans
            \Midtrans\Config::$serverKey = getenv('MIDTRANS_SERVER_KEY');
            \Midtrans\Config::$isProduction = getenv('MIDTRANS_IS_PRODUCTION') == 'true';
            
            $transactionStatus = \Midtrans\Transaction::status($transactionId);
            $transactionData = json_decode(json_encode($transactionStatus), true);
            
            if (($transactionData['transaction_status'] ?? '') === 'settlement' || 
                ($transactionData['transaction_status'] ?? '') === 'capture') {
                
                // Activate tenant subscription
                $this->tenantModel->activateSubscription($id, $transactionData);
                
                return view('tenants/payment_success', [
                    'title' => 'Payment Successful',
                    'pageTitle' => 'Payment Successful',
                    'pageSubTitle' => 'Your subscription has been activated',
                    'tenantId' => $id,
                    'transaction' => $transactionData
                ]);
            }
            
            throw new \Exception('Payment verification failed. Status: ' . ($transactionData['transaction_status'] ?? 'unknown'));
            
        } catch (\Exception $e) {
            log_message('error', '[Payment Verification] Error: ' . $e->getMessage());
            return redirect()->to('/tenants/payment-failed/' . $id)
                ->with('error', 'Payment verification failed. Please contact support.');
        }
    }

    /**
     * Handle pending payment status
     */
    public function paymentPending($id)
    {
        $tenant = $this->tenantModel->find($id);
        
        if (!$tenant) {
            return redirect()->to('/tenants')->with('error', 'Tenant not found');
        }

        return view('tenants/payment_pending', [
            'title' => 'Payment Pending',
            'pageTitle' => 'Payment Pending',
            'pageSubTitle' => 'Waiting for payment confirmation',
            'tenant' => $tenant,
            'transaction_id' => $this->request->getGet('transaction_id')
        ]);
    }

    /**
     * Handle failed payment
     */
    public function paymentFailed($id)
    {
        $tenant = $this->tenantModel->find($id);
        
        if (!$tenant) {
            return redirect()->to('/tenants')->with('error', 'Tenant not found');
        }

        return view('tenants/payment_failed', [
            'title' => 'Payment Failed',
            'pageTitle' => 'Payment Failed',
            'pageSubTitle' => 'There was a problem with your payment',
            'tenant' => $tenant,
            'error_message' => $this->request->getGet('message')
        ]);
    }

    /**
     * Get subscription pricing details
     */
    private function getSubscriptionPricing($plan)
    {
        $pricing = [
            'basic' => [
                'amount' => 99000,
                'duration' => 1 // months
            ],
            'premium' => [
                'amount' => 199000,
                'duration' => 1
            ],
            'enterprise' => [
                'amount' => 499000,
                'duration' => 1
            ]
        ];

        if (!isset($pricing[$plan])) {
            throw new \Exception('Invalid subscription plan');
        }

        return $pricing[$plan];
    }
}
