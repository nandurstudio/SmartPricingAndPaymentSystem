<?php

namespace App\Controllers;

use App\Models\MUserModel;
use App\Models\TenantModel;
use App\Models\ServiceTypeModel;

class OnboardingController extends BaseController
{
    protected $userModel;
    protected $tenantModel;
    protected $serviceTypeModel;
    protected $db;
    protected $menuModel;

    public function __construct()
    {
        $this->userModel = new MUserModel();
        $this->tenantModel = new TenantModel();
        $this->serviceTypeModel = new ServiceTypeModel();
        $this->db = \Config\Database::connect();
        $this->menuModel = new \App\Models\MenuModel();
    }

    /**
     * Tampilkan form pembuatan tenant setelah login Google
     */
    public function setupTenant()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        // Cek apakah user sudah punya tenant
        $userId = session()->get('userID');
        $existingTenant = $this->tenantModel->where('owner_id', $userId)->first();

        if ($existingTenant) {
            return redirect()->to('/dashboard');
        }        // Get menus based on user role
        $roleId = session()->get('roleID');
        $menus = $this->menuModel->getMenusByRole($roleId);
        
        $data = [
            'title' => 'Setup Your Business',
            'serviceTypes' => $this->serviceTypeModel->where('is_active', 1)->findAll(),
            'validation' => \Config\Services::validation(),
            'menus' => $menus
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

        // Validasi input
        $validation = \Config\Services::validation();
        $validation->setRules([
            'name' => 'required|min_length[3]|max_length[100]',
            'service_type_id' => 'required|numeric',
            'subscription_plan' => 'required|in_list[free,basic,premium,enterprise]',
            'domain' => 'permit_empty|valid_url',
            'description' => 'required'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $userId = session()->get('userID');

        // Generate tenant data
        $tenantData = [
            'name' => $this->request->getPost('name'),
            'service_type_id' => $this->request->getPost('service_type_id'),
            'subscription_plan' => $this->request->getPost('subscription_plan'),
            'domain' => $this->request->getPost('domain'),
            'description' => $this->request->getPost('description'),
            'owner_id' => $userId,
            'status' => 'pending',
            'guid' => uniqid('tenant_', true),
            'created_by' => $userId,
            'created_date' => date('Y-m-d H:i:s'),
            'trial_ends_at' => date('Y-m-d H:i:s', strtotime('+14 days')),
            'is_active' => 1
        ];

        try {
            // Start transaction
            $this->db->transStart();

            // Insert tenant
            $tenantId = $this->tenantModel->insert($tenantData);

            // Update user role to tenant owner (role_id = 3) and link to tenant
            $this->userModel->update($userId, [
                'intRoleID' => 3,
                'tenant_id' => $tenantId,
                'is_tenant_owner' => 1,
                'default_tenant_id' => $tenantId
            ]);

            // Commit transaction
            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                throw new \Exception('Failed to create tenant and update user role');
            }            // Redirect berdasarkan tipe subscription
            if ($tenantData['subscription_plan'] === 'free') {
                // Tampilkan halaman sukses untuk free plan
                return view('onboarding/free_plan_success', [
                    'title' => 'Free Plan Activated',
                    'tenantId' => $tenantId
                ]);
            } else {
                // Redirect ke halaman pembayaran untuk plan berbayar
                return redirect()->to("/subscription/checkout/{$tenantId}");
            }

        } catch (\Exception $e) {
            log_message('error', '[OnboardingController::createTenant] Error: ' . $e->getMessage());
            return redirect()->back()->withInput()
                ->with('error', 'Failed to create tenant. Please try again or contact support.');
        }
    }

    /**
     * Setup tenant branding dan pengaturan lanjutan
     */
    public function setupBranding($tenantId)
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $tenant = $this->tenantModel->find($tenantId);
        
        // Validasi akses
        if (!$tenant || $tenant['owner_id'] != session()->get('userID')) {
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
        }

        $tenant = $this->tenantModel->find($tenantId);
        
        // Validasi akses
        if (!$tenant || $tenant['owner_id'] != session()->get('userID')) {
            return redirect()->to('/dashboard');
        }

        // Handle logo upload
        $logo = $this->request->getFile('logo');
        if ($logo && $logo->isValid() && !$logo->hasMoved()) {
            $newName = $logo->getRandomName();
            $logo->move(ROOTPATH . 'public/uploads/tenants', $newName);
            
            // Delete old logo if exists
            if ($tenant['logo'] && file_exists(ROOTPATH . 'public/uploads/tenants/' . $tenant['logo'])) {
                unlink(ROOTPATH . 'public/uploads/tenants/' . $tenant['logo']);
            }
            
            // Update tenant dengan logo baru
            $this->tenantModel->update($tenantId, [
                'logo' => $newName,
                'theme' => $this->request->getPost('theme'),
                'settings' => json_encode([
                    'primary_color' => $this->request->getPost('primary_color'),
                    'secondary_color' => $this->request->getPost('secondary_color')
                ])
            ]);
        }

        return redirect()->to('/dashboard')
            ->with('success', 'Brand settings updated successfully!');
    }
}
