<?php

namespace App\Controllers;

use App\Models\TenantModel;

class TenantController extends BaseController
{
    protected $tenantModel;

    public function __construct()
    {
        $this->tenantModel = new TenantModel();
    }

    public function index()
    {
        // Check if user is logged in
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'You must be logged in to access this page.');
        }

        $data = [
            'title' => 'Tenant Settings',
            'pageTitle' => 'Tenant Settings',
            'pageSubTitle' => 'Manage your tenant settings and configuration',
            'icon' => 'building'
        ];

        // For admin: show all tenants
        // For tenant owner: show only their tenant
        if (session()->get('roleID') == 1) { // Assuming roleID 1 is for admin
            $data['tenants'] = $this->tenantModel->findAll();
        } else {
            $userId = session()->get('userID');
            $data['tenants'] = $this->tenantModel->where('owner_id', $userId)->findAll();
        }

        return view('tenant/index', $data);
    }

    public function create()
    {
        // Check if user is logged in and has proper permissions
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'You must be logged in to access this page.');
        }

        $data = [
            'title' => 'Create Tenant',
            'pageTitle' => 'Create New Tenant',
            'pageSubTitle' => 'Register a new tenant in the system',
            'icon' => 'plus-circle',
            'validation' => \Config\Services::validation()
        ];

        return view('tenant/create', $data);
    }

    public function store()
    {
        // Check if user is logged in
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'You must be logged in to access this page.');
        }

        // Validate form input
        $validation = \Config\Services::validation();
        $validation->setRules([
            'name' => 'required|min_length[3]|max_length[100]',
            'type' => 'required',
            'description' => 'required',
            'contact_email' => 'required|valid_email',
            'contact_phone' => 'required'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        // Prepare data for tenant creation
        $userId = session()->get('userID');
        $data = [
            'name' => $this->request->getPost('name'),
            'type' => $this->request->getPost('type'),
            'description' => $this->request->getPost('description'),
            'contact_email' => $this->request->getPost('contact_email'),
            'contact_phone' => $this->request->getPost('contact_phone'),
            'owner_id' => $userId,
            'is_active' => 1,
            'created_by' => $userId,
            'created_date' => date('Y-m-d H:i:s')
        ];

        // Generate a unique tenant code
        $data['tenant_code'] = $this->generateTenantCode($data['name']);

        // Insert tenant data
        $this->tenantModel->insert($data);

        return redirect()->to('/tenant')->with('success', 'Tenant created successfully.');
    }

    public function edit($id)
    {
        // Check if user is logged in
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'You must be logged in to access this page.');
        }

        $userId = session()->get('userID');
        $roleId = session()->get('roleID');

        // Fetch the tenant
        $tenant = $this->tenantModel->find($id);

        // Check permissions - only admin or tenant owner can edit
        if (!$tenant || ($tenant['owner_id'] != $userId && $roleId != 1)) {
            return redirect()->to('/tenant')->with('error', 'You do not have permission to edit this tenant.');
        }

        $data = [
            'title' => 'Edit Tenant',
            'pageTitle' => 'Edit Tenant',
            'pageSubTitle' => 'Update your tenant settings',
            'icon' => 'edit',
            'tenant' => $tenant,
            'validation' => \Config\Services::validation()
        ];

        return view('tenant/edit', $data);
    }

    public function update($id)
    {
        // Check if user is logged in
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'You must be logged in to access this page.');
        }

        $userId = session()->get('userID');
        $roleId = session()->get('roleID');

        // Fetch the tenant
        $tenant = $this->tenantModel->find($id);

        // Check permissions - only admin or tenant owner can update
        if (!$tenant || ($tenant['owner_id'] != $userId && $roleId != 1)) {
            return redirect()->to('/tenant')->with('error', 'You do not have permission to update this tenant.');
        }

        // Validate form input
        $validation = \Config\Services::validation();
        $validation->setRules([
            'name' => 'required|min_length[3]|max_length[100]',
            'type' => 'required',
            'description' => 'required',
            'contact_email' => 'required|valid_email',
            'contact_phone' => 'required'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        // Prepare data for update
        $data = [
            'name' => $this->request->getPost('name'),
            'type' => $this->request->getPost('type'),
            'description' => $this->request->getPost('description'),
            'contact_email' => $this->request->getPost('contact_email'),
            'contact_phone' => $this->request->getPost('contact_phone'),
            'updated_by' => $userId,
            'updated_date' => date('Y-m-d H:i:s')
        ];

        // Update tenant
        $this->tenantModel->update($id, $data);

        return redirect()->to('/tenant')->with('success', 'Tenant updated successfully.');
    }

    public function view($id)
    {
        // Check if user is logged in
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'You must be logged in to access this page.');
        }

        $userId = session()->get('userID');
        $roleId = session()->get('roleID');

        // Fetch the tenant
        $tenant = $this->tenantModel->find($id);

        // Check permissions - only admin or tenant owner can view
        if (!$tenant || ($tenant['owner_id'] != $userId && $roleId != 1)) {
            return redirect()->to('/tenant')->with('error', 'You do not have permission to view this tenant.');
        }

        $data = [
            'title' => 'Tenant Details',
            'pageTitle' => 'Tenant Details',
            'pageSubTitle' => 'View tenant information and settings',
            'icon' => 'info-circle',
            'tenant' => $tenant
        ];

        return view('tenant/view', $data);
    }

    /**
     * Generate a unique tenant code based on the tenant name
     */
    private function generateTenantCode($name)
    {
        // Create a base code from the name (first 3 chars + random number)
        $baseCode = substr(strtoupper(preg_replace('/[^A-Za-z0-9]/', '', $name)), 0, 3);
        $baseCode .= rand(1000, 9999);

        // Check if the code exists
        $existing = $this->tenantModel->where('tenant_code', $baseCode)->first();
        
        // If exists, regenerate until we get a unique one
        while ($existing) {
            $baseCode = substr(strtoupper(preg_replace('/[^A-Za-z0-9]/', '', $name)), 0, 3);
            $baseCode .= rand(1000, 9999);
            $existing = $this->tenantModel->where('tenant_code', $baseCode)->first();
        }

        return $baseCode;
    }
}