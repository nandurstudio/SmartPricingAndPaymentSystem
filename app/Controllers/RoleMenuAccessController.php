<?php

namespace App\Controllers;

use App\Models\RoleMenuAccessModel;
use App\Models\MenuModel; // Model untuk menu
use App\Models\RoleModel; // Model untuk role

class RoleMenuAccessController extends BaseController
{
    protected $roleMenuAccessModel;
    protected $menuModel;
    protected $roleModel;

    public function __construct()
    {
        $this->roleMenuAccessModel = new RoleMenuAccessModel();
        $this->menuModel = new MenuModel();
        $this->roleModel = new RoleModel();
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
        $rolesModel = new RoleModel();
        $roles = $rolesModel->findAll();

        // Ambil data lines dari model
        $roleMenuAccessModel = new RoleMenuAccessModel();
        $roleMenuAccess = $roleMenuAccessModel->findAll();

        // Kirim data ke view
        return view('role_menu_access/index', [
            'menus' => $menus,
            'roles' => $roles,
            'roleMenuAccess' => $roleMenuAccess,
            'icon' => 'users',
            'pageSubTitle' => 'Menampilkan daftar Role Menu Access', // Mengirimkan sub-judul ke view
            'cardTitle' => 'Role Menu Access',
            'pageTitle' => 'Master Role Menu Access', // Mengirimkan judul ke view
            // 'scripts' => 'assets/js/pages/competencies.js' // Kirim nama file script
        ]);
    }

    public function view($id)
    {
        $data['roleMenuAccess'] = $this->roleMenuAccessModel->find($id);

        // Ambil data role dan menu untuk ditampilkan
        $data['roles'] = $this->roleModel->findAll();
        $data['menus'] = $this->menuModel->findAll();

        return view('role_menu_access/view', $data);
    }

    public function create()
    {
        $roles = $this->roleModel->findAll();
        $menus = $this->menuModel->findAll();
        return view('role_menu_access/create', ['roles' => $roles, 'menus' => $menus]);
    }

    public function store()
    {
        $data = $this->request->getPost();
        // Pastikan untuk menambahkan validasi jika diperlukan

        // Simpan data ke dalam database
        $data['txtGUID'] = uniqid();
        $this->roleMenuAccessModel->insert($data);

        return redirect()->to('/role_menu_access');
    }

    public function edit($id)
    {
        $roleMenuAccess = $this->roleMenuAccessModel->find($id);

        // Ambil data role dan menu
        $roles = $this->roleModel->findAll();
        $menus = $this->menuModel->findAll();

        return view('role_menu_access/edit', [
            'roleMenuAccess' => $roleMenuAccess,
            'roles' => $roles,
            'menus' => $menus,
        ]);
    }

    public function update($id)
    {
        // Mengambil data dari request POST
        $data = $this->request->getPost();

        // Cek jika checkbox tidak dicentang, set nilai ke 0
        $data['bitCanView'] = isset($data['bitCanView']) ? 1 : 0;
        $data['bitCanAdd'] = isset($data['bitCanAdd']) ? 1 : 0;
        $data['bitCanEdit'] = isset($data['bitCanEdit']) ? 1 : 0;
        $data['bitCanDelete'] = isset($data['bitCanDelete']) ? 1 : 0;

        // Mengupdate data ke dalam database
        if ($this->roleMenuAccessModel->update($id, $data)) {
            // Redirect setelah berhasil
            return redirect()->to('/role_menu_access')->with('message', 'Data updated successfully');
        } else {
            // Jika ada kesalahan saat menyimpan, kembalikan dengan pesan error
            return redirect()->back()->with('error', 'Failed to update data');
        }
    }
}
