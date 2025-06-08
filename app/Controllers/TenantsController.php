<?php

namespace App\Controllers;

use App\Models\MTenantModel;

class TenantsController extends BaseController
{
    protected $tenantModel;
    protected $menuModel;
    protected $serviceTypeModel;

    public function __construct()
    {
        $this->tenantModel = new MTenantModel();
        $this->menuModel = new \App\Models\MenuModel();
        $this->serviceTypeModel = new \App\Models\ServiceTypeModel();
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
        $logo = $this->request->getFile('txtLogo');
        if ($logo && $logo->isValid() && !$logo->hasMoved()) {
            $newName = $logo->getRandomName();
            $logo->move(ROOTPATH . 'public/uploads/tenants', $newName);
            $data['txtLogo'] = $newName;
        }

        // Get and normalize subdomain
        $subdomain = $this->request->getPost('txtDomain');
        $normalizedSubdomain = $this->tenantModel->normalizeSubdomain($subdomain);

        if (!$this->tenantModel->isSubdomainAvailable($normalizedSubdomain)) {
            return redirect()->back()->withInput()->with('error', 'The subdomain is already taken. Please choose another one.');
        }

        // Prepare data
        $data = [
            'txtTenantName' => $this->request->getPost('txtTenantName'),
            'intServiceTypeID' => $this->request->getPost('intServiceTypeID'),
            'txtSlug' => $this->tenantModel->generateTenantSlug($this->request->getPost('txtTenantName')),
            'intOwnerID' => $userId,
            'txtDomain' => $normalizedSubdomain,
            'txtWebsiteUrl' => generate_tenant_url($normalizedSubdomain),
            'txtTenantCode' => $this->request->getPost('txtTenantCode') ?? strtoupper(substr(md5(time()), 0, 8)),
            'txtSubscriptionPlan' => $this->request->getPost('txtSubscriptionPlan') ?? 'free',
            'txtSubscriptionStatus' => 'inactive',
            'txtStatus' => $this->request->getPost('txtStatus') ?? 'pending',
            'dtmTrialEndsAt' => date('Y-m-d H:i:s', strtotime('+14 days')),
            'jsonSettings' => json_encode([
                'description' => $this->request->getPost('description'),
                'theme' => $this->request->getPost('txtTheme') ?? 'default'
            ]),
            'jsonPaymentSettings' => json_encode(['currency' => 'IDR']),
            'txtTheme' => $this->request->getPost('txtTheme') ?? 'default',
            'bitActive' => $this->request->getPost('bitActive') ?? 1,
            'txtCreatedBy' => session()->get('userName'),
            'txtGUID' => uniqid('tenant_', true)
        ];

        // Insert data
        if ($tenantId = $this->tenantModel->insert($data)) {
            // Generate default CSS for the new tenant
            $this->tenantModel->generateDefaultCSS($tenantId);

            // Redirect to tenant website or listing depending on status
            if ($data['txtStatus'] === 'active') {
                return redirect()->to(generate_tenant_url($normalizedSubdomain))->with('success', 'Tenant created successfully. Redirecting to your website...');
            }
            return redirect()->to('/tenants')->with('success', 'Tenant created successfully. Please wait for approval.');
        }

        return redirect()->back()->withInput()->with('errors', $this->tenantModel->errors());
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

        // Handle logo removal
        if ($this->request->getPost('removeLogo') && $tenant['txtLogo']) {
            $logoPath = ROOTPATH . 'public/uploads/tenants/' . $tenant['txtLogo'];
            if (file_exists($logoPath)) {
                unlink($logoPath);
            }
            $data['txtLogo'] = null;
        }
        // Handle logo upload if any
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
                return redirect()->back()->withInput()->with('error', 'The subdomain is already taken. Please choose another one.');
            }
            $data['txtDomain'] = $normalizedSubdomain;
            $data['txtWebsiteUrl'] = generate_tenant_url($normalizedSubdomain);
        }

        // Prepare update data
        $data['txtTenantName'] = $this->request->getPost('txtTenantName');
        $data['intServiceTypeID'] = $this->request->getPost('intServiceTypeID');
        $data['txtStatus'] = $this->request->getPost('txtStatus');
        $data['txtSubscriptionPlan'] = $this->request->getPost('txtSubscriptionPlan');
        $data['txtTheme'] = $this->request->getPost('txtTheme');
        $data['bitActive'] = $this->request->getPost('bitActive') ?? 0;
        $data['jsonSettings'] = $this->request->getPost('jsonSettings');
        $data['jsonPaymentSettings'] = $this->request->getPost('jsonPaymentSettings');
        $data['txtMidtransClientKey'] = $this->request->getPost('txtMidtransClientKey');
        $data['txtMidtransServerKey'] = $this->request->getPost('txtMidtransServerKey');
        $data['txtUpdatedBy'] = session()->get('userName');

        // Update data
        if ($this->tenantModel->update($id, $data)) {
            // Regenerate CSS if theme changed
            if ($data['txtTheme'] !== $tenant['txtTheme']) {
                $this->tenantModel->generateDefaultCSS($id);
            }
            return redirect()->to('/tenants')->with('success', 'Tenant updated successfully.');
        }

        return redirect()->back()->withInput()->with('errors', $this->tenantModel->errors());
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
