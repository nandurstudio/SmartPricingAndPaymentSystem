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

        $roleId = session()->get('roleID');
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

    public function store()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'You must be logged in to access this page.');
        }

        // Validation rules
        $rules = [
            'txtTenantName' => 'required|min_length[3]|max_length[100]',
            'intServiceTypeID' => 'required|numeric'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $userId = session()->get('userID');
        
        // Prepare data
        $data = [
            'txtTenantName' => $this->request->getPost('txtTenantName'),
            'intServiceTypeID' => $this->request->getPost('intServiceTypeID'),
            'txtSlug' => $this->tenantModel->generateTenantSlug($this->request->getPost('txtTenantName')),
            'intOwnerID' => $userId,
            'txtSubscriptionPlan' => 'basic', // Default plan
            'txtStatus' => 'active',
            'bitActive' => 1,
            'txtCreatedBy' => session()->get('userName'),
            'txtGUID' => uniqid('tenant_', true)
        ];

        // Insert data
        if ($this->tenantModel->insert($data)) {
            return redirect()->to('/tenants')->with('success', 'Tenant created successfully.');
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
        }

        $menus = $this->menuModel->getMenusByRole($roleId);

        return view('tenants/edit', [
            'title' => 'Edit Tenant',
            'pageTitle' => 'Edit Tenant',
            'pageSubTitle' => 'Update tenant settings',
            'icon' => 'edit',
            'menus' => $menus,
            'tenant' => $tenant,
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
            'intServiceTypeID' => 'required|numeric'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Prepare update data
        $data = [
            'txtTenantName' => $this->request->getPost('txtTenantName'),
            'intServiceTypeID' => $this->request->getPost('intServiceTypeID'),
            'txtUpdatedBy' => session()->get('userName')
        ];

        // Update data
        if ($this->tenantModel->update($id, $data)) {
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
        $tenant = $this->tenantModel->find($id);

        // Check permissions
        if (!$tenant || ($tenant['intOwnerID'] != $userId && $roleId != 1)) {
            return redirect()->to('/tenants')->with('error', 'You do not have permission to view this tenant.');
        }

        $menus = $this->menuModel->getMenusByRole($roleId);

        return view('tenants/view', [
            'title' => 'Tenant Details',
            'pageTitle' => 'Tenant Details',
            'pageSubTitle' => 'View tenant information',
            'icon' => 'info',
            'menus' => $menus,
            'tenant' => $tenant
        ]);
    }
}
