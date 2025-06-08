<?php

namespace App\Controllers;

class ServiceController extends BaseController
{
    protected $serviceModel;
    protected $serviceTypeModel;
    protected $serviceAttributeModel;
    protected $tenantModel;
    protected $userModel;

    public function __construct()
    {
        helper(['form', 'url']);
        $this->serviceModel = new \App\Models\ServiceModel();
        $this->serviceTypeModel = new \App\Models\ServiceTypeModel();
    }

    public function index()
    {
        // Check if we're on a tenant subdomain
        $host = $_SERVER['HTTP_HOST'] ?? '';
        $baseDomain = env('app.baseURL') ?: 'smartpricingandpaymentsystem.localhost.com';
        $baseDomain = rtrim(preg_replace('#^https?://#', '', $baseDomain), '/');
        
        if (strpos($host, '.') !== false && $host !== $baseDomain) {
            // We're on a tenant subdomain, redirect to tenant website controller
            return redirect()->to(current_url());
        }

        // Check if user is logged in
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'You must be logged in to access this page.');
        }

        $data = [
            'title' => 'Services',
            'pageTitle' => 'Services Management',
            'pageSubTitle' => 'Create and manage your booking services',
            'icon' => 'briefcase'
        ];

        // Get tenant ID and check access
        $tenantId = $this->getTenantId();
        if (!$tenantId) {
            return redirect()->to('/tenants/create')->with('info', 'Please create a tenant first to manage services.');
        }

        // Get services for this tenant
        $data['services'] = $this->serviceModel->getServicesWithType($tenantId);

        return view('services/index', $data);
    }

    public function create()
    {
        // Check if user is logged in
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'You must be logged in to access this page.');
        }

        $tenantId = $this->getTenantId();
        if (!$tenantId) {
            return redirect()->to('/tenants/create')->with('info', 'Please create a tenant first to manage services.');
        }

        $data = [
            'title' => 'Create Service',
            'pageTitle' => 'Create New Service',
            'pageSubTitle' => 'Add a new service to your booking system',
            'icon' => 'plus-circle',
            'validation' => \Config\Services::validation()
        ];

        // Get service types for dropdown
        $data['serviceTypes'] = $this->serviceTypeModel->getApprovedTypes();

        return view('services/create', $data);
    }

    public function store()
    {
        // Check if user is logged in
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'You must be logged in to access this page.');
        }

        $tenantId = $this->getTenantId();
        if (!$tenantId) {
            return redirect()->to('/tenants/create')->with('info', 'Please create a tenant first to manage services.');
        }

        // Validate form input
        $validation = \Config\Services::validation();
        $validation->setRules([
            'txtName' => 'required|min_length[3]|max_length[100]',
            'intServiceTypeID' => 'required|numeric',
            'decPrice' => 'required|numeric',
            'intDuration' => 'required|numeric|greater_than[0]',
            'intCapacity' => 'required|numeric|greater_than[0]',
            'txtDescription' => 'required'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $userId = session()->get('userID');
        $data = [
            'txtGUID' => service('uuid')->uuid4()->toString(),
            'txtName' => $this->request->getPost('txtName'),
            'intServiceTypeID' => $this->request->getPost('intServiceTypeID'),
            'decPrice' => $this->request->getPost('decPrice'),
            'intDuration' => $this->request->getPost('intDuration'),
            'intCapacity' => $this->request->getPost('intCapacity'),
            'txtDescription' => $this->request->getPost('txtDescription'),
            'intTenantID' => $tenantId,
            'bitActive' => $this->request->getPost('bitActive') ? 1 : 0,
            'txtCreatedBy' => session()->get('userName'),
            'dtmCreatedDate' => date('Y-m-d H:i:s')
        ];

        try {
            $this->serviceModel->insert($data);
            return redirect()->to('/services')->with('success', 'Service created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()
                ->with('error', 'Failed to create service: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        // Check if user is logged in
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'You must be logged in to access this page.');
        }

        $tenantId = $this->getTenantId();
        if (!$tenantId) {
            return redirect()->to('/tenants/create')->with('info', 'Please create a tenant first to manage services.');
        }

        $service = $this->serviceModel->getServiceDetails($id);
        if (!$service || $service['intTenantID'] != $tenantId) {
            return redirect()->to('/services')
                ->with('error', 'Service not found or you do not have permission to edit it.');
        }

        $data = [
            'title' => 'Edit Service',
            'pageTitle' => 'Edit Service',
            'pageSubTitle' => 'Update your service details',
            'icon' => 'edit',
            'service' => $service,
            'validation' => \Config\Services::validation(),
            'serviceTypes' => $this->serviceTypeModel->getApprovedTypes()
        ];

        return view('services/edit', $data);
    }

    public function update($id)
    {
        // Check if user is logged in
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'You must be logged in to access this page.');
        }

        $tenantId = $this->getTenantId();
        if (!$tenantId) {
            return redirect()->to('/tenants/create')->with('info', 'Please create a tenant first to manage services.');
        }

        $service = $this->serviceModel->find($id);
        if (!$service || $service['intTenantID'] != $tenantId) {
            return redirect()->to('/services')
                ->with('error', 'Service not found or you do not have permission to update it.');
        }

        // Validate form input
        $validation = \Config\Services::validation();
        $validation->setRules([
            'txtName' => 'required|min_length[3]|max_length[100]',
            'intServiceTypeID' => 'required|numeric',
            'decPrice' => 'required|numeric',
            'intDuration' => 'required|numeric',
            'txtDescription' => 'required'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $data = [
            'txtName' => $this->request->getPost('txtName'),
            'txtSlug' => url_title($this->request->getPost('txtName'), '-', true),
            'intServiceTypeID' => $this->request->getPost('intServiceTypeID'),
            'decPrice' => $this->request->getPost('decPrice'),
            'intDuration' => $this->request->getPost('intDuration'),
            'txtDescription' => $this->request->getPost('txtDescription'),
            'txtUpdatedBy' => session()->get('userName'),
            'dtmUpdatedDate' => date('Y-m-d H:i:s')
        ];

        try {
            $this->serviceModel->update($id, $data);
            return redirect()->to('/services')->with('success', 'Service updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()
                ->with('error', 'Failed to update service: ' . $e->getMessage());
        }
    }

    public function view($id)
    {
        // Check if user is logged in
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'You must be logged in to access this page.');
        }

        $tenantId = $this->getTenantId();
        if (!$tenantId) {
            return redirect()->to('/tenants/create')->with('info', 'Please create a tenant first to manage services.');
        }

        $service = $this->serviceModel->getServiceDetails($id);
        if (!$service || $service['intTenantID'] != $tenantId) {
            return redirect()->to('/services')
                ->with('error', 'Service not found or you do not have permission to view it.');
        }

        $data = [
            'title' => 'Service Details',
            'pageTitle' => 'Service Details',
            'pageSubTitle' => 'View service information',
            'icon' => 'info-circle',
            'service' => $service
        ];

        return view('services/view', $data);
    }

    /**
     * Get the tenant ID for the current user
     */
    private function getTenantId()
    {
        // 1. First check if we're on a tenant subdomain
        $host = $_SERVER['HTTP_HOST'] ?? '';
        if (strpos($host, '.') !== false) {
            // Extract subdomain from host
            $baseDomain = env('app.baseURL') ?: 'smartpricingandpaymentsystem.localhost.com';
            $baseDomain = rtrim(preg_replace('#^https?://#', '', $baseDomain), '/');
            $subdomain = str_replace('.' . $baseDomain, '', $host);
            
            // Load tenant model if not already initialized
            if (!$this->tenantModel) {
                $this->tenantModel = new \App\Models\MTenantModel();
            }
            
            // Get tenant by subdomain
            $tenant = $this->tenantModel->where('txtDomain', $subdomain)
                                      ->where('bitActive', 1)
                                      ->where('txtStatus', 'active')
                                      ->first();
            if ($tenant) {
                return $tenant['intTenantID'];
            }
        }

        // 2. For admin users, check URL parameter
        if (session()->get('roleID') == 1) {
            $tenantId = $this->request->getGet('tenant_id');
            if ($tenantId) {
                return $tenantId;
            }
            $selectedTenant = session()->get('selectedTenantID');
            if ($selectedTenant) {
                return $selectedTenant;
            }
        }
        
        // 3. For tenant owners, get their tenant
        $userId = session()->get('userID');
        if (session()->get('roleID') == 2) { // Tenant owner role
            if (!$this->tenantModel) {
                $this->tenantModel = new \App\Models\MTenantModel();
            }
            
            $tenant = $this->tenantModel->where('intOwnerID', $userId)
                                      ->where('bitActive', 1)
                                      ->where('txtStatus', 'active')
                                      ->first();
            
            if ($tenant) {
                return $tenant['intTenantID'];
            }
        }
        
        // 4. For customers or other users, get their default tenant
        if ($userId) {
            if (!$this->userModel) {
                $this->userModel = new \App\Models\MUserModel();
            }
            
            $user = $this->userModel->find($userId);
            if ($user && !empty($user['intDefaultTenantID'])) {
                return $user['intDefaultTenantID'];
            }
        }
        
        return null;
    }
}
