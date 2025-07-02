<?php

namespace App\Controllers;

use App\Models\RoleMenuAccessModel;
use App\Models\MenuModel; // Model untuk menu
use App\Models\MRoleModel; // Model untuk role

class RoleMenuAccessController extends BaseController
{
    protected $roleMenuAccessModel;
    protected $menuModel;
    protected $roleModel;

    public function __construct()
    {
        $this->roleMenuAccessModel = new RoleMenuAccessModel();
        $this->menuModel = new MenuModel();
        $this->roleModel = new MRoleModel();
    }

    // INDEX - Menampilkan daftar user
    public function index()
    {
        // Cek apakah user sudah login
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')
                ->with('error', 'You must be logged in to access this page.');
        }

        // Ambil roleID dari session
        $roleID = session()->get('roleID');

        try {
            // Ambil data menu untuk navigasi
            $menus = $this->menuModel->getMenusByRole($roleID);
            
            // Ambil data lengkap role menu access dengan join
            $roleMenuAccess = $this->roleMenuAccessModel->getAllRoleMenuAccess();

            // Debug log
            log_message('debug', 'Role Menu Access Data: ' . print_r($roleMenuAccess, true));

            // Kirim data ke view
            return view('role-menu-access/index', [
                'menus' => $menus,
                'roles' => $this->roleModel->findAll(),
                'roleMenuAccess' => $roleMenuAccess,
                'title' => 'Role Menu Access',
                'pageTitle' => 'Role Menu Access Management',
                'pageSubTitle' => 'Manage role menu access permissions',
                'icon' => 'shield-lock'
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error in RoleMenuAccess/index: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Failed to load role menu access data. Please try again.');
        }
    }

    public function view($id)
    {
        $data = [
            'roleMenuAccess' => $this->roleMenuAccessModel->find($id),
            'roles' => $this->roleModel->findAll(),
            'menus' => $this->menuModel->findAll(),
            'title' => 'View Role Menu Access',
            'pageTitle' => 'View Role Menu Access', 
            'pageSubTitle' => 'View menu access permissions',
            'icon' => 'eye'
        ];

        return view('role-menu-access/view', $data);
    }

    public function create()
    {
        if ($redirect = checkLogin()) return $redirect;

        // Get pre-selected menu from URL if any
        $selectedMenuId = $this->request->getGet('menu');
        
        return view('role-menu-access/create', [
            'title' => 'Create Role Menu Access',
            'roles' => $this->roleModel->findAll(),
            'menus' => $this->menuModel->findAll(),
            'selectedMenuId' => $selectedMenuId,
            'icon' => 'lock',
            'pageTitle' => 'Role Menu Access',
            'pageSubTitle' => 'Assign menu access permissions to roles',
            'cardTitle' => 'Create Role Menu Access'
        ]);
    }

    public function store()
    {
        $session = session();
        $intRoleID = $this->request->getPost('intRoleID');
        $intMenuID = $this->request->getPost('intMenuID');

        // Check if role-menu combination already exists
        $existing = $this->roleMenuAccessModel->where([
            'intRoleID' => $intRoleID,
            'intMenuID' => $intMenuID
        ])->first();

        if ($existing) {
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'This role already has access to the selected menu. Please edit the existing access instead.');
        }
        
        $data = [
            'intRoleID' => $intRoleID,
            'intMenuID' => $intMenuID,
            'bitActive' => $this->request->getPost('bitActive') ? 1 : 0,
            'txtCreatedBy' => session()->get('userName') ?? 'system',
            'dtmCreatedDate' => date('Y-m-d H:i:s'),
            'txtGUID' => uniqid()
        ];

        if ($this->roleMenuAccessModel->save($data)) {
            return redirect()->to('role-menu-access')->with('success', 'Role Menu Access created successfully');
        }

        return redirect()->back()->withInput()->with('error', 'Failed to create Role Menu Access');
    }

    public function edit($id)
    {
        $data = [
            'roleMenuAccess' => $this->roleMenuAccessModel->find($id),
            'roles' => $this->roleModel->findAll(),
            'menus' => $this->menuModel->findAll(),
            'title' => 'Edit Role Menu Access',
            'pageTitle' => 'Edit Role Menu Access',
            'pageSubTitle' => 'Modify menu access permissions',
            'icon' => 'edit'
        ];

        return view('role-menu-access/edit', $data);
    }

    public function update($id)
    {
        $data = $this->request->getPost();

        // Set default value for unchecked checkbox
        $data['bitActive'] = isset($data['bitActive']) ? 1 : 0;

        // Add metadata
        $data['txtUpdatedBy'] = session()->get('userName') ?? 'system';
        $data['dtmUpdatedDate'] = date('Y-m-d H:i:s');

        if ($this->roleMenuAccessModel->update($id, $data)) {
            return redirect()->to('/role-menu-access')->with('success', 'Role menu access updated successfully');
        } else {
            return redirect()->back()->with('error', 'Failed to update role menu access');
        }
    }
    public function delete($id)
    {
        if ($this->roleMenuAccessModel->delete($id)) {
            return redirect()->to('/role-menu-access')->with('success', 'Role menu access deleted successfully');
        }
        
        return redirect()->to('/role-menu-access')->with('error', 'Failed to delete role menu access');
    }
}
