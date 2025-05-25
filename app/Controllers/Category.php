<?php

namespace App\Controllers;

use App\Models\CategoryModel;
use App\Models\TenantModel;
use App\Models\ServiceTypeModel;

class Category extends BaseController
{
    protected $categoryModel;
    protected $tenantModel;
    protected $serviceTypeModel;

    public function __construct()
    {
        $this->categoryModel = new CategoryModel();
        $this->tenantModel = new TenantModel();
        $this->serviceTypeModel = new ServiceTypeModel();
    }

    public function index()
    {
        // Check if user is logged in
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/auth/login');
        }

        $user = session()->get('user');
        $tenant = session()->get('tenant');

        // Fallback if user session structure is from old system
        if (!$user && session()->get('userID')) {
            $user = [
                'id' => session()->get('userID'),
                'role' => $this->mapRoleIdToName(session()->get('roleID'))
            ];
        }

        // Get categories based on user role and tenant
        if ($user['role'] === 'admin') {
            // Admin sees all categories
            $categories = $this->categoryModel->getCategoriesWithTenantInfo();
        } else {
            // Tenant users see only their categories
            $categories = $this->categoryModel->getCategoriesByTenant($tenant['id'] ?? 0);
        }

        $data = [
            'title' => 'Category Management',
            'categories' => $categories,
            'user' => $user,
            'tenant' => $tenant
        ];

        return view('category/index', $data);
    }

    // Helper method to map role ID to role name
    private function mapRoleIdToName($roleId)
    {
        $roleMap = [
            1 => 'admin',
            2 => 'customer',
            3 => 'tenant_owner',
            4 => 'tenant_staff'
        ];

        return $roleMap[$roleId] ?? 'customer';
    }

    public function create()
    {
        // Check if user is logged in
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/auth/login');
        }

        $user = session()->get('user');
        $tenant = session()->get('tenant');

        // Fallback if user session structure is from old system
        if (!$user && session()->get('userID')) {
            $user = [
                'id' => session()->get('userID'),
                'role' => $this->mapRoleIdToName(session()->get('roleID'))
            ];
        }

        if ($this->request->getMethod() === 'POST') {
            return $this->store();
        }

        $data = [
            'title' => 'Create Category',
            'serviceTypes' => $this->serviceTypeModel->where('is_approved', true)->findAll(),
            'user' => $user,
            'tenant' => $tenant
        ];

        return view('category/create', $data);
    }

    public function store()
    {
        // Check if user is logged in
        if (!session()->get('isLoggedIn')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Authentication required'
            ]);
        }

        $user = session()->get('user');
        $tenant = session()->get('tenant');

        // Fallback if user session structure is from old system
        if (!$user && session()->get('userID')) {
            $user = [
                'id' => session()->get('userID'),
                'role' => $this->mapRoleIdToName(session()->get('roleID'))
            ];
        }

        $validation = \Config\Services::validation();
        
        $rules = [
            'txtCategoryName' => 'required|min_length[3]|max_length[255]',
            'txtDesc' => 'permit_empty|max_length[500]',
            'icon' => 'permit_empty|max_length[255]'
        ];

        if (!$this->validate($rules)) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'errors' => $validation->getErrors()
                ]);
            }
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        try {
            $categoryData = [
                'txtCategoryName' => $this->request->getPost('txtCategoryName'),
                'txtDesc' => $this->request->getPost('txtDesc'),
                'icon' => $this->request->getPost('icon'),
                'bitActive' => 1,
                'dtmCreatedDate' => date('Y-m-d H:i:s'),
                'txtCreatedBy' => session()->get('userName') ?? 'system'
            ];

            // Add tenant_id if user is not admin
            if ($user['role'] !== 'admin' && $tenant) {
                $categoryData['tenant_id'] = $tenant['id'];
                $categoryData['service_type_id'] = $tenant['service_type_id'];
            }

            $categoryId = $this->categoryModel->insert($categoryData);

            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Category created successfully',
                    'data' => ['id' => $categoryId]
                ]);
            }

            return redirect()->to('/category')->with('success', 'Category created successfully');

        } catch (\Exception $e) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to create category: ' . $e->getMessage()
                ]);
            }
            return redirect()->back()->withInput()->with('error', 'Failed to create category');
        }
    }

    public function edit($id)
    {
        // Check if user is logged in
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/auth/login');
        }

        $user = session()->get('user');
        $tenant = session()->get('tenant');

        $category = $this->categoryModel->find($id);
        
        if (!$category) {
            return redirect()->to('/category')->with('error', 'Category not found');
        }

        // Check tenant access
        if ($user['role'] !== 'admin' && $category['tenant_id'] != $tenant['id']) {
            return redirect()->to('/category')->with('error', 'Access denied');
        }

        if ($this->request->getMethod() === 'POST') {
            return $this->update($id);
        }

        $data = [
            'title' => 'Edit Category',
            'category' => $category,
            'serviceTypes' => $this->serviceTypeModel->where('is_approved', true)->findAll(),
            'user' => $user,
            'tenant' => $tenant
        ];

        return view('category/edit', $data);
    }

    public function update($id)
    {
        // Check if user is logged in
        if (!session()->get('isLoggedIn')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Authentication required'
            ]);
        }

        $user = session()->get('user');
        $tenant = session()->get('tenant');

        $category = $this->categoryModel->find($id);
        
        if (!$category) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Category not found'
            ]);
        }

        // Check tenant access
        if ($user['role'] !== 'admin' && $category['tenant_id'] != $tenant['id']) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Access denied'
            ]);
        }

        $validation = \Config\Services::validation();
        
        $rules = [
            'txtCategoryName' => 'required|min_length[3]|max_length[255]',
            'txtDesc' => 'permit_empty|max_length[500]',
            'icon' => 'permit_empty|max_length[255]'
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'success' => false,
                'errors' => $validation->getErrors()
            ]);
        }

        try {
            $categoryData = [
                'txtCategoryName' => $this->request->getPost('txtCategoryName'),
                'txtDesc' => $this->request->getPost('txtDesc'),
                'icon' => $this->request->getPost('icon')
            ];

            $this->categoryModel->update($id, $categoryData);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Category updated successfully'
            ]);

        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to update category: ' . $e->getMessage()
            ]);
        }
    }

    public function delete($id)
    {
        // Check if user is logged in
        if (!session()->get('isLoggedIn')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Authentication required'
            ]);
        }

        $user = session()->get('user');
        $tenant = session()->get('tenant');

        $category = $this->categoryModel->find($id);
        
        if (!$category) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Category not found'
            ]);
        }

        // Check tenant access
        if ($user['role'] !== 'admin' && $category['tenant_id'] != $tenant['id']) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Access denied'
            ]);
        }

        try {
            // Soft delete by setting bitActive to 0
            $this->categoryModel->update($id, ['bitActive' => 0]);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Category deleted successfully'
            ]);

        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to delete category: ' . $e->getMessage()
            ]);
        }
    }

    public function view($id)
    {
        // Check if user is logged in
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/auth/login');
        }

        $user = session()->get('user');
        $tenant = session()->get('tenant');

        $category = $this->categoryModel->getCategoryWithDetails($id);
        
        if (!$category) {
            return redirect()->to('/category')->with('error', 'Category not found');
        }

        // Check tenant access
        if ($user['role'] !== 'admin' && $category['tenant_id'] != $tenant['id']) {
            return redirect()->to('/category')->with('error', 'Access denied');
        }

        $data = [
            'title' => 'View Category',
            'category' => $category,
            'user' => $user,
            'tenant' => $tenant
        ];

        return view('category/view', $data);
    }

    public function checkUpdates()
    {
        // Simple endpoint to check for updates
        return $this->response->setJSON([
            'hasUpdates' => false // You can implement actual update checking logic here
        ]);
    }
}
