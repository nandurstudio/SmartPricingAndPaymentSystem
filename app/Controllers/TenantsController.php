<?php

namespace App\Controllers;

use App\Models\MTenantModel;
use App\Models\MSubscriptionPlanModel;

class TenantsController extends BaseController
{
    protected $tenantModel;
    protected $menuModel;
    protected $serviceTypeModel;
    protected $subscriptionPlanModel;
    protected $baseDomain;
    protected $db;

    public function __construct()
    {
        $this->tenantModel = new MTenantModel();
        $this->menuModel = new \App\Models\MenuModel();
        $this->serviceTypeModel = new \App\Models\ServiceTypeModel();
        $this->subscriptionPlanModel = new MSubscriptionPlanModel();
        $this->baseDomain = env('BASE_DOMAIN', 'smartpricingandpaymentsystem.localhost.com');
        $this->db = \Config\Database::connect();
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
        $serviceTypes = $this->serviceTypeModel->where('bitActive', 1)->findAll();
        $plans = $this->subscriptionPlanModel->getAllActivePlans();
        return view('tenants/create', [
            'title' => 'Create Tenant',
            'pageTitle' => 'Create New Tenant',
            'pageSubTitle' => 'Register a new business tenant',
            'icon' => 'plus-circle',
            'menus' => $menus,
            'serviceTypes' => $serviceTypes,
            'plans' => $plans,
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
            'dtmTrialEndsAt' => $this->calculateTrialEndDate('basic'),
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
            'txtTenantName' => 'required|min_length[3]|max_length[255]',
            'intServiceTypeID' => 'required|numeric',
            'txtDomain' => 'permit_empty|max_length[255]',
            'txtSubscriptionPlan' => 'required|in_list[free,basic,premium,enterprise]',
            'txtStatus' => 'required|in_list[active,inactive,suspended,pending,pending_verification,pending_payment,payment_failed]',
            'txtTheme' => 'permit_empty|in_list[default,dark,light]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $normalizedSubdomain = $this->tenantModel->normalizeSubdomain($this->request->getPost('txtDomain'));

        // Check if subdomain is available
        if (!$this->tenantModel->isSubdomainAvailable($normalizedSubdomain)) {
            return redirect()->back()->withInput()
                ->with('error', 'The selected subdomain is already taken. Please choose another one.');
        }

        // Prepare settings and payment settings
        $description = $this->request->getPost('description');
        $settings = [
            'description' => $description,
            'theme' => $this->request->getPost('txtTheme') ?? 'default',
            'customCSS' => ''
        ];

        $paymentSettings = [
            'currency' => 'IDR',
            'last_payment_id' => null,
            'last_payment_status' => null,
            'last_payment_date' => null
        ];

        // Prepare tenant data
        $data = [
            'txtTenantName' => $this->request->getPost('txtTenantName'),
            'intServiceTypeID' => $this->request->getPost('intServiceTypeID'),
            'txtSlug' => $this->tenantModel->generateTenantSlug($this->request->getPost('txtTenantName')),
            'txtDomain' => $normalizedSubdomain,
            'txtTenantCode' => strtoupper(substr(md5(time()), 0, 8)),
            'intOwnerID' => $userId,
            'txtSubscriptionPlan' => $this->request->getPost('txtSubscriptionPlan'),
            'txtSubscriptionStatus' => $this->request->getPost('txtSubscriptionPlan') === 'free' ? 'active' : 'inactive',
            'txtStatus' => $this->request->getPost('txtStatus'),
            'dtmTrialEndsAt' => $this->request->getPost('txtSubscriptionPlan') === 'free' ? null : $this->calculateTrialEndDate($this->request->getPost('txtSubscriptionPlan')),
            'dtmSubscriptionStartDate' => $this->request->getPost('txtSubscriptionPlan') === 'free' ? date('Y-m-d H:i:s') : null,
            'dtmSubscriptionEndDate' => $this->request->getPost('txtSubscriptionPlan') === 'free' ? null : null, // Free plan has no end date
            'jsonSettings' => json_encode($settings),
            'jsonPaymentSettings' => json_encode($paymentSettings),
            'txtTheme' => $this->request->getPost('txtTheme') ?? 'default',
            'bitActive' => $this->request->getPost('bitActive') ? 1 : 0,
            'txtCreatedBy' => session()->get('userName'),
            'txtGUID' => uniqid('tenant_', true)
        ];

        try {
            // Start transaction
            $this->db->transStart();
            // Insert tenant
            $tenantId = $this->tenantModel->insert($data);

            if (!$tenantId) {
                throw new \Exception('Failed to create tenant');
            }

            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                throw new \Exception('Failed to complete tenant creation');
            }

            return redirect()->to('/tenants')->with('message', 'Tenant created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()
                ->with('error', 'Failed to create tenant: ' . $e->getMessage());
        }
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
        }
        $menus = $this->menuModel->getMenusByRole($roleId);
        $serviceTypes = $this->serviceTypeModel->where('bitActive', 1)->findAll();
        $plans = $this->subscriptionPlanModel->getAllActivePlans();
        return view('tenants/edit', [
            'title' => 'Edit Tenant',
            'pageTitle' => 'Edit Tenant',
            'pageSubTitle' => 'Update tenant settings',
            'icon' => 'edit',
            'menus' => $menus,
            'tenant' => $tenant,
            'serviceTypes' => $serviceTypes,
            'plans' => $plans,
            'validation' => \Config\Services::validation()
        ]);
    }
    public function update($id)
    {
        if (!session()->get('isLoggedIn')) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'error' => 'You must be logged in to access this page.'
                ]);
            }
            return redirect()->to('/login')->with('error', 'You must be logged in to access this page.');
        }        // Validation rules
        $rules = [
            'txtTenantName' => 'required|min_length[3]|max_length[255]',
            'intServiceTypeID' => 'required|numeric',
            'txtSubscriptionPlan' => 'required|in_list[free,basic,premium,enterprise]',
            'txtStatus' => 'required|in_list[active,inactive,suspended,pending,pending_verification,pending_payment,payment_failed]',
            'txtTheme' => 'permit_empty|in_list[default,dark,light]'
        ];

        if (!$this->validate($rules)) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'errors' => $this->validator->getErrors()
                ]);
            }
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Get existing tenant data for comparison
        $tenant = $this->tenantModel->find($id);
        if (!$tenant) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'error' => 'Tenant not found.'
                ]);
            }
            return redirect()->to('/tenants')->with('error', 'Tenant not found.');
        }

        // Check role permissions for status changes
        $userRole = session()->get('role');
        if (!in_array($userRole, ['admin', 'super_admin'])) {
            // Non-admin users can't change status
            $data['txtStatus'] = $tenant['txtStatus'];
        }

        // Prepare settings
        $currentSettings = json_decode($tenant['jsonSettings'] ?? '{}', true);
        $description = $this->request->getPost('description');
        $settings = array_merge($currentSettings, [
            'description' => $description,
            'theme' => $this->request->getPost('txtTheme') ?? 'default'
        ]);

        // Get current payment settings
        $currentPaymentSettings = json_decode($tenant['jsonPaymentSettings'] ?? '{}', true);
        try {
            try {
                // Handle logo upload
                $logo = $this->request->getFile('txtLogo');

                // Debug logs for request info
                log_message('info', 'Request info:');
                log_message('info', '- Files: ' . json_encode($_FILES));
                log_message('info', '- Logo upload request received: ' . ($logo ? 'File present' : 'No file'));
                log_message('info', '- Remove logo flag: ' . $this->request->getPost('removeLogo'));

                // Handle logo removal
                if ($this->request->getPost('removeLogo') == '1') {
                    log_message('info', 'Logo removal requested');
                    if (!empty($tenant['txtLogo'])) {
                        $oldLogoPath = FCPATH . 'uploads/tenants/' . $tenant['txtLogo'];
                        if (file_exists($oldLogoPath)) {
                            unlink($oldLogoPath);
                            log_message('info', 'Old logo deleted: ' . $oldLogoPath);
                        }
                    }
                    $data['txtLogo'] = null;
                    log_message('info', 'Logo field set to null');
                }
                // Handle new logo upload only if a file was actually uploaded
                elseif ($logo && $logo->getError() !== UPLOAD_ERR_NO_FILE && $logo->getSize() > 0) {
                    if (!$logo->isValid()) {
                        log_message('error', 'Invalid file upload. Error: ' . $logo->getError());
                        if ($logo->getError() !== UPLOAD_ERR_NO_FILE) {
                            throw new \RuntimeException('Invalid file upload: ' . $logo->getErrorString());
                        }
                    } else {
                        log_message('info', 'Valid logo file detected: ' . $logo->getName());
                        log_message('info', 'File details:');
                        log_message('info', '- Size: ' . $logo->getSize() . ' bytes');
                        log_message('info', '- Type: ' . $logo->getClientMimeType());
                        log_message('info', '- Temp name: ' . $logo->getTempName());

                        // Validate file type
                        $validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
                        if (!in_array($logo->getClientMimeType(), $validTypes)) {
                            throw new \RuntimeException('Invalid file type. Only JPG, PNG and GIF are allowed.');
                        }

                        // Validate file size (max 2MB)
                        if ($logo->getSize() > 2097152) {
                            throw new \RuntimeException('File size exceeds 2MB limit.');
                        }

                        // Delete old logo if exists
                        if (!empty($tenant['txtLogo'])) {
                            $oldLogoPath = FCPATH . 'uploads/tenants/' . $tenant['txtLogo'];
                            if (file_exists($oldLogoPath)) {
                                unlink($oldLogoPath);
                                log_message('info', 'Old logo deleted before new upload');
                            }
                        }

                        // Create uploads directory if it doesn't exist
                        $uploadPath = FCPATH . 'uploads/tenants';
                        if (!is_dir($uploadPath)) {
                            if (!mkdir($uploadPath, 0777, true)) {
                                throw new \RuntimeException('Failed to create upload directory.');
                            }
                            log_message('info', 'Created upload directory: ' . $uploadPath);
                        }

                        // Check if directory is writable
                        if (!is_writable($uploadPath)) {
                            throw new \RuntimeException('Upload directory is not writable.');
                        }

                        // Generate new filename
                        $newName = $id . '_' . $logo->getRandomName();
                        log_message('info', 'Generated new filename: ' . $newName);

                        // Move file to uploads directory
                        if ($logo->move($uploadPath, $newName)) {
                            log_message('info', 'File moved successfully to: ' . $uploadPath . '/' . $newName);
                            $data['txtLogo'] = $newName;

                            // Verify file was actually saved
                            if (!file_exists($uploadPath . '/' . $newName)) {
                                throw new \RuntimeException('File was not saved properly.');
                            }
                        } else {
                            log_message('error', 'Failed to move uploaded file. Error: ' . $logo->getError());
                            throw new \RuntimeException('Failed to move uploaded file: ' . $logo->getErrorString());
                        }
                    }
                }
                // If no new file and no remove flag, keep the existing logo
                else {
                    log_message('info', 'No new logo uploaded and no removal requested. Keeping existing logo.');
                    // Don't set txtLogo in data, which will keep the existing value
                }
            } catch (\Exception $e) {
                log_message('error', 'Error in logo processing: ' . $e->getMessage());
                if ($this->request->isAJAX()) {
                    return $this->response->setJSON([
                        'success' => false,
                        'error' => 'Logo upload failed: ' . $e->getMessage()
                    ]);
                }
                return redirect()->back()->withInput()->with('error', 'Logo upload failed: ' . $e->getMessage());
            }
        } catch (\Exception $e) {
            log_message('error', 'Error in logo processing: ' . $e->getMessage());
            // Add error to validation errors
            $this->validator->setError('txtLogo', $e->getMessage());
        }        // Get the new tenant name
        $newTenantName = $this->request->getPost('txtTenantName');
        $newSubscriptionPlan = $this->request->getPost('txtSubscriptionPlan');

        // Check if subscription plan has changed
        $planChanged = $tenant['txtSubscriptionPlan'] !== $newSubscriptionPlan;

        // Prepare tenant data
        $data = [
            'txtTenantName' => $newTenantName,
            'intServiceTypeID' => $this->request->getPost('intServiceTypeID'),
            'txtStatus' => $this->request->getPost('txtStatus'),
            'txtSubscriptionPlan' => $newSubscriptionPlan,
            'txtTheme' => $this->request->getPost('txtTheme') ?? 'default',
            'bitActive' => $this->request->getPost('bitActive') ? 1 : 0,
            'jsonSettings' => json_encode($settings),
            'jsonPaymentSettings' => json_encode($currentPaymentSettings),
            'txtUpdatedBy' => session()->get('userName'),
            'txtLogo' => isset($newName) ? $newName : ($this->request->getPost('removeLogo') == '1' ? null : $tenant['txtLogo']),
            // Update slug only if name has changed
            'txtSlug' => $this->updateSlugIfNameChanged($tenant, $newTenantName)
        ];        // Handle subscription plan changes
        if ($planChanged) {
            if ($newSubscriptionPlan === 'free') {
                // If changing to free plan, make it active immediately
                $data['txtSubscriptionStatus'] = 'active';
                $data['dtmTrialEndsAt'] = null;
                $data['dtmSubscriptionStartDate'] = date('Y-m-d H:i:s');
                $data['dtmSubscriptionEndDate'] = null; // Free plan has no end date
            } else if ($tenant['txtSubscriptionStatus'] !== 'active') {
                // If changing to paid plan and not currently active, set trial
                $data['txtSubscriptionStatus'] = 'inactive';
                $data['dtmTrialEndsAt'] = $this->calculateTrialEndDate($newSubscriptionPlan);
                $data['dtmSubscriptionStartDate'] = null;
                $data['dtmSubscriptionEndDate'] = null;
            }
        }

        // Update midtrans keys if provided
        if ($this->request->getPost('txtMidtransClientKey')) {
            $data['txtMidtransClientKey'] = $this->request->getPost('txtMidtransClientKey');
        }
        if ($this->request->getPost('txtMidtransServerKey')) {
            $data['txtMidtransServerKey'] = $this->request->getPost('txtMidtransServerKey');
        }

        try {
            // Start transaction
            $this->db->transStart();

            if (!$this->tenantModel->update($id, $data)) {
                throw new \Exception('Failed to update tenant');
            }

            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                throw new \Exception('Failed to complete tenant update');
            }

            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Tenant updated successfully.',
                    'redirect' => base_url('tenants')
                ]);
            }

            return redirect()->to('/tenants')->with('message', 'Tenant updated successfully.');
        } catch (\Exception $e) {
            log_message('error', '[TenantsController::update] Error: ' . $e->getMessage());

            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'error' => 'Failed to update tenant: ' . $e->getMessage()
                ]);
            }

            return redirect()->back()->withInput()
                ->with('error', 'Failed to update tenant: ' . $e->getMessage());
        }
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

        // Get booking statistics
        $bookingModel = new \App\Models\BookingModel();
        $bookingStats = [
            'total' => $bookingModel->where('intTenantID', $id)->countAllResults(),
            'pending' => $bookingModel->where('intTenantID', $id)->where('txtStatus', 'pending')->countAllResults(),
            'confirmed' => $bookingModel->where('intTenantID', $id)->where('txtStatus', 'confirmed')->countAllResults(),
            'completed' => $bookingModel->where('intTenantID', $id)->where('txtStatus', 'completed')->countAllResults()
        ];

        // Get recent bookings
        $recentBookings = $bookingModel->getRecentBookings($id, 5);

        $data = [
            'title' => 'View Tenant',
            'pageTitle' => 'Tenant Details',
            'pageSubTitle' => 'View tenant information and services',
            'icon' => 'info-circle',
            'tenant' => $tenant,
            'services' => $services,
            'bookingStats' => $bookingStats,
            'recentBookings' => $recentBookings
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
        }        // Free plan doesn't need activation
        if ($tenant['txtSubscriptionPlan'] === 'free') {
            return redirect()->to('/tenants/view/' . $id)->with('info', 'Free plan is already active and does not require payment.');
        }

        // Check if subscription is already active
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
                    'credit_card',
                    'mandiri_clickpay',
                    'cimb_clicks',
                    'bca_klikbca',
                    'bca_klikpay',
                    'bri_epay',
                    'echannel',
                    'permata_va',
                    'bca_va',
                    'bni_va',
                    'bri_va',
                    'other_va',
                    'gopay',
                    'indomaret',
                    'alfamart',
                    'danamon_online',
                    'akulaku'
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
                ($transactionData['transaction_status'] ?? '') === 'capture'
            ) {

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

    /**
     * Calculate subscription dates based on plan
     */
    protected function calculateSubscriptionDates($plan)
    {
        $duration = [
            'free' => '0 months',
            'basic' => '1 month',
            'premium' => '1 month',
            'enterprise' => '1 month'
        ];

        $startDate = date('Y-m-d H:i:s');
        $endDate = date('Y-m-d H:i:s', strtotime('+' . ($duration[$plan] ?? '1 month'), strtotime($startDate)));

        return [
            'start' => $startDate,
            'end' => $endDate
        ];
    }

    /**
     * Calculate trial end date based on subscription plan
     */    protected function calculateTrialEndDate($plan)
    {
        // Free plan doesn't have trial period
        if ($plan === 'free') {
            return null;
        }

        $trialDays = [
            'basic' => 14,
            'premium' => 14,
            'enterprise' => 30
        ];

        return date('Y-m-d H:i:s', strtotime('+' . ($trialDays[$plan] ?? 14) . ' days'));
    }

    /**
     * Update slug if tenant name changes
     */
    protected function updateSlugIfNameChanged($tenant, $newName)
    {
        if ($tenant['txtTenantName'] !== $newName) {
            return $this->tenantModel->generateTenantSlug($newName);
        }
        return $tenant['txtSlug'];
    }

    /**
     * Handle plan change request
     */    public function changePlan($id, $newPlan)
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'You must be logged in to access this page.');
        }

        $userId = session()->get('userID');
        $roleId = session()->get('roleID');
        $tenant = $this->tenantModel->find($id);

        // Check permissions
        if (!$tenant || ($tenant['intOwnerID'] != $userId && $roleId != 1)) {
            return redirect()->to('/tenants')->with('error', 'You do not have permission to modify this tenant.');
        }

        // Validate plan
        if (!in_array($newPlan, ['basic', 'premium', 'enterprise'])) {
            return redirect()->to('/tenants/view/' . $id)->with('error', 'Invalid plan selected.');
        }

        try {
            // Store original plan details in session for rollback if payment fails
            session()->set('plan_change_original', [
                'id' => $id,
                'plan' => $tenant['txtSubscriptionPlan'],
                'status' => $tenant['txtSubscriptionStatus'],
                'trial_ends_at' => $tenant['dtmTrialEndsAt']
            ]);

            $data = [
                'txtSubscriptionPlan' => $newPlan,
                'txtSubscriptionStatus' => 'pending_payment',
                'dtmTrialEndsAt' => $this->calculateTrialEndDate($newPlan),
                'txtUpdatedBy' => session()->get('userName')
            ];

            if (!$this->tenantModel->update($id, $data)) {
                throw new \Exception('Failed to update subscription plan');
            }

            // Set session timeout for payment
            session()->set('plan_change_timeout', time() + (30 * 60)); // 30 minutes timeout

            return redirect()->to('/tenants/activate-subscription/' . $id);
        } catch (\Exception $e) {
            log_message('error', '[TenantsController::changePlan] Error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Failed to update plan: ' . $e->getMessage());
        }
    }

    /**
     * Rollback plan change if payment fails or times out
     */
    protected function rollbackPlanChange($id)
    {
        $originalPlan = session()->get('plan_change_original');
        if ($originalPlan && $originalPlan['id'] == $id) {
            try {
                $data = [
                    'txtSubscriptionPlan' => $originalPlan['plan'],
                    'txtSubscriptionStatus' => $originalPlan['status'],
                    'dtmTrialEndsAt' => $originalPlan['trial_ends_at'],
                    'txtUpdatedBy' => session()->get('userName')
                ];

                if (!$this->tenantModel->update($id, $data)) {
                    log_message('error', '[TenantsController::rollbackPlanChange] Failed to rollback plan for tenant ' . $id);
                }

                // Clear session data
                session()->remove('plan_change_original');
                session()->remove('plan_change_timeout');
            } catch (\Exception $e) {
                log_message('error', '[TenantsController::rollbackPlanChange] Error: ' . $e->getMessage());
            }
        }
    }

    /**
     * Check payment timeout and rollback if needed
     */
    protected function checkPaymentTimeout($tenant)
    {
        $timeout = session()->get('plan_change_timeout');
        if ($timeout && time() > $timeout && $tenant['txtSubscriptionStatus'] === 'pending_payment') {
            $this->rollbackPlanChange($tenant['intTenantID']);
            return redirect()->to('/tenants/view/' . $tenant['intTenantID'])
                ->with('warning', 'Payment session has expired. Your plan has been reverted.');
        }
        return false;
    }
    /**
     * Check current payment status for a subscription change or activation
     */
    public function checkPaymentStatus($id)
    {
        if (!session()->get('isLoggedIn')) {
            return $this->response->setJSON([
                'success' => false,
                'error' => 'Authentication required'
            ]);
        }

        $tenant = $this->tenantModel->find($id);

        // Check permissions
        if (!$tenant || ($tenant['intOwnerID'] != session()->get('userID') && session()->get('roleID') != 1)) {
            return $this->response->setJSON([
                'success' => false,
                'error' => 'Permission denied'
            ]);
        }

        // Check for timeout
        $timeout = session()->get('plan_change_timeout');
        if ($timeout && time() > $timeout && $tenant['txtSubscriptionStatus'] === 'pending_payment') {
            // Payment has timed out
            return $this->response->setJSON([
                'status' => 'failed',
                'message' => 'Payment session has expired'
            ]);
        }

        // Check current status
        $status = $tenant['txtSubscriptionStatus'];
        $message = '';

        switch ($status) {
            case 'active':
                $message = 'Payment completed successfully';
                break;
            case 'pending_payment':
                $message = 'Awaiting payment confirmation';
                break;
            case 'payment_failed':
                $message = 'Payment failed';
                break;
            default:
                $message = 'Unknown status';
        }

        return $this->response->setJSON([
            'status' => $status === 'active' ? 'completed' : ($status === 'pending_payment' ? 'pending' : 'failed'),
            'message' => $message,
            'updated_at' => $tenant['dtmUpdatedDate']
        ]);
    }
    /**
     * Handle plan rollback to original when payment fails or times out
     */
    public function rollbackPlanToOriginal($id, $originalPlan)
    {
        if (!session()->get('isLoggedIn')) {
            return $this->response->setJSON([
                'success' => false,
                'error' => 'Authentication required'
            ]);
        }

        $tenant = $this->tenantModel->find($id);

        // Check permissions
        if (!$tenant || ($tenant['intOwnerID'] != session()->get('userID') && session()->get('roleID') != 1)) {
            return $this->response->setJSON([
                'success' => false,
                'error' => 'Permission denied'
            ]);
        }

        try {
            // Get the original plan details from session
            $originalPlanData = session()->get('plan_change_original');

            if (!$originalPlanData || $originalPlanData['id'] != $id) {
                throw new \Exception('Original plan data not found');
            }

            $data = [
                'txtSubscriptionPlan' => $originalPlan,
                'txtSubscriptionStatus' => $originalPlanData['status'],
                'dtmTrialEndsAt' => $originalPlanData['trial_ends_at'],
                'txtUpdatedBy' => session()->get('userName')
            ];

            if (!$this->tenantModel->update($id, $data)) {
                throw new \Exception('Failed to rollback plan');
            }

            // Clear session data
            session()->remove('plan_change_original');
            session()->remove('plan_change_timeout');

            // Log the rollback
            log_message('info', "[TenantsController::rollbackPlanToOriginal] Successfully rolled back plan for tenant {$id} to {$originalPlan}");

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Plan rolled back successfully'
            ]);
        } catch (\Exception $e) {
            log_message('error', '[TenantsController::rollbackPlanToOriginal] Error: ' . $e->getMessage());

            return $this->response->setJSON([
                'success' => false,
                'error' => 'Failed to rollback plan: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Initiate plan change process and store original plan data
     */
    public function initiatePlanChange($id, $newPlan)
    {
        if (!session()->get('isLoggedIn')) {
            return $this->response->setJSON([
                'success' => false,
                'error' => 'Authentication required'
            ]);
        }

        $tenant = $this->tenantModel->find($id);

        // Check permissions
        if (!$tenant || ($tenant['intOwnerID'] != session()->get('userID') && session()->get('roleID') != 1)) {
            return $this->response->setJSON([
                'success' => false,
                'error' => 'Permission denied'
            ]);
        }

        try {
            // Store original plan details in session for rollback if payment fails
            session()->set('plan_change_original', [
                'id' => $id,
                'plan' => $tenant['txtSubscriptionPlan'],
                'status' => $tenant['txtSubscriptionStatus'],
                'trial_ends_at' => $tenant['dtmTrialEndsAt']
            ]);

            $data = [
                'txtSubscriptionPlan' => $newPlan,
                'txtSubscriptionStatus' => 'pending_payment',
                'dtmTrialEndsAt' => $this->calculateTrialEndDate($newPlan),
                'txtUpdatedBy' => session()->get('userName')
            ];

            if (!$this->tenantModel->update($id, $data)) {
                throw new \Exception('Failed to update subscription plan');
            }

            // Set session timeout for payment
            session()->set('plan_change_timeout', time() + (30 * 60)); // 30 minutes timeout

            return $this->response->setJSON([
                'success' => true,
                'redirect' => rtrim(base_url(), '/') . '/tenants/activate-subscription/' . $id
            ]);

        } catch (\Exception $e) {
            log_message('error', '[TenantsController::initiatePlanChange] Error: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'error' => 'Failed to initiate plan change: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Toggle tenant status
     */
    public function toggle($id = null)
    {
        // Check if request is AJAX
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Invalid request method'
            ]);
        }

        // Validate ID
        if (!$id) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Tenant ID is required'
            ]);
        }

        try {
            // Get current tenant status
            $tenant = $this->tenantModel->find($id);
            if (!$tenant) {
                log_message('error', 'Tenant not found for ID: ' . $id);
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Tenant not found'
                ]);
            }

            // Update tenant status (toggle it)
            $newStatus = $tenant['bitActive'] == 1 ? 0 : 1;
            $data = [
                'bitActive' => $newStatus,
                'txtUpdatedBy' => session()->get('userName'),  // Set username instead of ID
                'dtmUpdatedDate' => date('Y-m-d H:i:s')
            ];

            if ($this->tenantModel->update($id, $data)) {
                log_message('info', 'Tenant status updated successfully for ID: ' . $id);
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Tenant status updated successfully'
                ]);
            } else {
                log_message('error', 'Failed to update tenant status for ID: ' . $id);
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to update tenant status'
                ]);
            }
        } catch (\Exception $e) {
            log_message('error', 'Error toggling tenant status: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'An error occurred while updating tenant status'
            ]);
        }
    }
}
