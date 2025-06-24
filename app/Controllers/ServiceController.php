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
        $this->tenantModel = new \App\Models\MTenantModel();
    }    public function index()
    {
        // Check if user is logged in
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'You must be logged in to access this page.');
        }

        $roleID = session()->get('roleID');
        $userID = session()->get('userID');

        // Initialize data array
        $data = [
            'title' => 'Services',
            'pageTitle' => 'Services Management',
            'pageSubTitle' => 'Create and manage your booking services',
            'icon' => 'briefcase',
            'roleID' => $roleID // Pass role ID to view for permission checking
        ];

        // Get tenant ID based on role
        $tenantId = $this->getTenantId();

        // Load services based on role
        if ($roleID == 1) { // Super Admin
            // Get all services with tenant information
            $data['services'] = $this->serviceModel->getServicesWithTypeAndTenant();
            // Load tenants for admin filter
            $data['tenants'] = $this->tenantModel->findAll();
            $data['canManageServices'] = true;
        } else {
            if (!$tenantId && $roleID == 2) { // Tenant Owner
                return redirect()->to('/tenants/create')
                    ->with('info', 'Please create a tenant first to manage services.');
            }
            
            // Get services for specific tenant
            $data['services'] = $this->serviceModel->getServicesWithType($tenantId);
            // Only tenant owner can manage services
            $data['canManageServices'] = ($roleID == 2);
            
            // Get tenant details
            if ($tenantId) {
                $data['tenant'] = $this->tenantModel->find($tenantId);
            }
        }

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

        // Get tenant ID from query string or getTenantId() method
        $tenantId = $this->request->getGet('tenant_id') ?? $this->getTenantId();
        if (!$tenantId) {
            return redirect()->to('/tenants/create')->with('info', 'Please create a tenant first to manage services.');
        }

        // Check if user has access to this tenant
        if (session()->get('roleID') != 1) { // Skip check for admin
            if (!$this->tenantModel) {
                $this->tenantModel = new \App\Models\MTenantModel();
            }
            $tenant = $this->tenantModel->where('intTenantID', $tenantId)
                                    ->where('intOwnerID', session()->get('userID'))
                                    ->first();
            if (!$tenant) {
                return redirect()->to('/services')->with('error', 'You do not have permission to create services for this tenant.');
            }
        }

        // Validate form input
        $validation = \Config\Services::validation();
        $validation->setRules([
            'txtName' => 'required|min_length[3]|max_length[100]',
            'intServiceTypeID' => 'required|numeric',
            'decPrice' => 'required|numeric',
            'intDuration' => 'required|numeric',
            'txtDescription' => 'required',
            'txtImage' => 'permit_empty|is_image[txtImage]|max_size[txtImage,2048]'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        try {
            // Build service data
            $serviceData = [
                'intTenantID' => $tenantId,
                'txtName' => $this->request->getPost('txtName'),
                'intServiceTypeID' => $this->request->getPost('intServiceTypeID'),
                'decPrice' => $this->request->getPost('decPrice'),
                'intDuration' => $this->request->getPost('intDuration'),
                'intCapacity' => $this->request->getPost('intCapacity') ?? 1,
                'txtDescription' => $this->request->getPost('txtDescription'),
                'bitActive' => $this->request->getPost('bitActive') ?? 1,
                'txtCreatedBy' => session()->get('username'),
                'txtGUID' => $this->generateUuidV4()
            ];

            // Handle image upload if present
            $image = $this->request->getFile('txtImage');
            if ($image && $image->isValid() && !$image->hasMoved()) {
                $newName = $image->getRandomName();
                $image->move(ROOTPATH . 'public/uploads/services', $newName);
                $serviceData['txtImage'] = $newName;
            }

            // Insert service
            if (!$this->serviceModel->insert($serviceData)) {
                log_message('error', 'Failed to insert service: ' . json_encode($this->serviceModel->errors()));
                return redirect()->back()->withInput()
                    ->with('error', 'Failed to create service. Database error occurred.');
            }

            return redirect()->to('/services')
                ->with('success', 'Service created successfully.');
                
        } catch (\Exception $e) {
            log_message('error', 'Exception while creating service: ' . $e->getMessage());
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
            'txtDescription' => 'required',
            'txtImage' => 'permit_empty|is_image[txtImage]|max_size[txtImage,2048]'
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
            'intCapacity' => $this->request->getPost('intCapacity') ?? 1,
            'txtDescription' => $this->request->getPost('txtDescription'),
            'bitActive' => $this->request->getPost('bitActive') ?? 0,
            'txtUpdatedBy' => session()->get('username'),
            'dtmUpdatedDate' => date('Y-m-d H:i:s')
        ];

        // Handle image upload
        $image = $this->request->getFile('txtImage');
        if ($image && $image->isValid() && !$image->hasMoved()) {
            $newName = $image->getRandomName();
            $image->move('uploads/services', $newName);
            $data['txtImage'] = $newName;
            
            // Remove old image if exists
            if (!empty($service['txtImage'])) {
                $oldImagePath = 'uploads/services/' . $service['txtImage'];
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }
        } elseif ($this->request->getPost('remove_image') && !empty($service['txtImage'])) {
            // Remove image if checkbox is checked
            $imagePath = 'uploads/services/' . $service['txtImage'];
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
            $data['txtImage'] = null;
        }

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
        $roleID = session()->get('roleID');

        // 1. For admin users, get from URL parameter first
        if ($roleID == 1) {
            $tenantId = $this->request->getGet('tenant_id');
            if ($tenantId) {
                return $tenantId;
            }
            $selectedTenant = session()->get('selectedTenantID');
            if ($selectedTenant) {
                return $selectedTenant;
            }
        }

        // 2. Check if we're on a tenant subdomain
        $host = $_SERVER['HTTP_HOST'] ?? '';
        if (strpos($host, '.') !== false) {
            // Extract subdomain from host
            $baseDomain = 'smartpricingandpaymentsystem.localhost.com';
            $subdomain = str_replace('.' . $baseDomain, '', $host);
            
            if (!$this->tenantModel) {
                $this->tenantModel = new \App\Models\MTenantModel();
            }
            
            $tenant = $this->tenantModel->where('txtDomain', $subdomain)
                                      ->where('bitActive', 1)
                                      ->where('txtStatus', 'active')
                                      ->first();
            if ($tenant) {
                return $tenant['intTenantID'];
            }
        }
        
        // 3. For tenant owners, get their tenant
        $userId = session()->get('userID');
        if ($roleID == 2) { // Tenant owner role
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

        // 4. Get from session tenant selection
        $sessionTenantId = session()->get('currentTenantID');
        if ($sessionTenantId) {
            return $sessionTenantId;
        }
        
        // 5. For customers or other users, get their default tenant
        if ($userId) {
            if (!$this->userModel) {
                $this->userModel = new \App\Models\MUserModel();
            }
            
            $user = $this->userModel->find($userId);
            if ($user && !empty($user['intDefaultTenantID'])) {
                return $user['intDefaultTenantID'];
            }
        }

        // 6. If admin and no tenant selected, get first active tenant
        if ($roleID == 1) {
            if (!$this->tenantModel) {
                $this->tenantModel = new \App\Models\MTenantModel();
            }

            $tenant = $this->tenantModel->where('bitActive', 1)
                                      ->where('txtStatus', 'active')
                                      ->first();
            if ($tenant) {
                return $tenant['intTenantID'];
            }
        }
        
        return null;
    }

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
                'message' => 'Service ID is required'
            ]);
        }

        try {
            // Get new status from POST data
            $status = $this->request->getPost('status');
            
            // Update service status
            $success = $this->serviceModel->update($id, [
                'bitActive' => $status,
                'txtUpdatedBy' => session()->get('username')
            ]);

            if ($success) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Service status updated successfully'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to update service status'
                ]);
            }
        } catch (\Exception $e) {
            log_message('error', 'Error toggling service status: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'An error occurred while updating service status'
            ]);
        }
    }

    /**
     * Toggle service status (active/inactive)
     */
    public function toggleStatus($id)
    {
        // Check if user is logged in
        if (!session()->get('isLoggedIn')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'You must be logged in to perform this action.'
            ]);
        }

        $roleID = session()->get('roleID');
        
        // Get service details
        $service = $this->serviceModel->find($id);
        if (!$service) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Service not found.'
            ]);
        }

        // Check permissions
        if ($roleID != 1) { // Not super admin
            $tenantId = $this->getTenantId();
            if ($service['intTenantID'] != $tenantId) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'You do not have permission to modify this service.'
                ]);
            }
        }

        // Toggle status
        $newStatus = !$service['bitActive'];
        try {
            $this->serviceModel->update($id, [
                'bitActive' => $newStatus,
                'txtUpdatedBy' => session()->get('username')
            ]);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Service status updated successfully.'
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error toggling service status: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to update service status.'
            ]);
        }
    }

    /**
     * Generate UUID v4 string
     */
    private function generateUuidV4()
    {
        $data = random_bytes(16);
        $data[6] = chr((ord($data[6]) & 0x0f) | 0x40); // version 4
        $data[8] = chr((ord($data[8]) & 0x3f) | 0x80); // variant
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }
}
