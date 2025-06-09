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
}
