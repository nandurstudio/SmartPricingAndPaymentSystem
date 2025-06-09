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
            return redirect()->to('/login')->with('error', 'You must be logged in to access this page.');
        }

        // Ambil roleID dari session dan menu berdasarkan role
        $roleID = session()->get('roleID');

        // Ganti MenuModel menjadi MenusModel
        $menusModel = new MenuModel();  // Menyesuaikan dengan model baru
        $menus = $menusModel->getMenusByRole($roleID);  // Memanggil method dari MenusModel

        // Ambil data lines dari model
        $rolesModel = new MRoleModel();
        $roles = $rolesModel->findAll();

        // Ambil data lines dari model
        $roleMenuAccessModel = new RoleMenuAccessModel();
        $roleMenuAccess = $roleMenuAccessModel->findAll();

        // Kirim data ke view
        return view('role_menu_access/index', [
            'menus' => $menus,
            'roles' => $roles,
            'roleMenuAccess' => $roleMenuAccess,
            'title' => 'Role Menu Access',
            'pageTitle' => 'Role Menu Access',
            'pageSubTitle' => 'Manage menu access permissions per role',
            'icon' => 'lock',
            'cardTitle' => 'Role Menu Access'
        ]);
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

        return view('role_menu_access/view', $data);
    }

    public function create()
    {
        $data = [
            'roles' => $this->roleModel->findAll(),
            'menus' => $this->menuModel->findAll(),
            'title' => 'Create Role Menu Access',
            'pageTitle' => 'Create Role Menu Access',
            'pageSubTitle' => 'Assign menu access permissions to a role',
            'icon' => 'plus-circle'
        ];
        return view('role_menu_access/create', $data);
    }

    public function store()
    {
        $data = $this->request->getPost();
        
        // Set default values for unchecked checkboxes
        $data['bitCanView'] = isset($data['bitCanView']) ? 1 : 0;
        $data['bitCanAdd'] = isset($data['bitCanAdd']) ? 1 : 0;
        $data['bitCanEdit'] = isset($data['bitCanEdit']) ? 1 : 0;
        $data['bitCanDelete'] = isset($data['bitCanDelete']) ? 1 : 0;

        // Add metadata
        $data['txtGUID'] = uniqid();
        $data['bitActive'] = 1;
        $data['txtCreatedBy'] = session()->get('userName') ?? 'system';
        $data['dtmCreatedDate'] = date('Y-m-d H:i:s');

        $this->roleMenuAccessModel->insert($data);
        return redirect()->to('/role_menu_access')->with('success', 'Role menu access created successfully');
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

        return view('role_menu_access/edit', $data);
    }

    public function update($id)
    {
        $data = $this->request->getPost();

        // Set default values for unchecked checkboxes
        $data['bitCanView'] = isset($data['bitCanView']) ? 1 : 0;
        $data['bitCanAdd'] = isset($data['bitCanAdd']) ? 1 : 0;
        $data['bitCanEdit'] = isset($data['bitCanEdit']) ? 1 : 0;
        $data['bitCanDelete'] = isset($data['bitCanDelete']) ? 1 : 0;

        // Add metadata
        $data['txtUpdatedBy'] = session()->get('userName') ?? 'system';
        $data['dtmUpdatedDate'] = date('Y-m-d H:i:s');

        if ($this->roleMenuAccessModel->update($id, $data)) {
            return redirect()->to('/role_menu_access')->with('success', 'Role menu access updated successfully');
        } else {
            return redirect()->back()->with('error', 'Failed to update role menu access');
        }
    }
    public function delete($id)
    {
        if ($this->roleMenuAccessModel->delete($id)) {
            return redirect()->to('/role_menu_access')->with('success', 'Role menu access deleted successfully');
        }
        
        return redirect()->to('/role_menu_access')->with('error', 'Failed to delete role menu access');
    }
}
