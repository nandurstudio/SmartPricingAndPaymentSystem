<?php

namespace App\Controllers;

use App\Models\MenuModel;
use CodeIgniter\Controller;

class MenuController extends Controller
{
    protected $menuModel;
    
    public function __construct()
    {
        $this->menuModel = new MenuModel(); // Inisialisasi model
        helper('Auth'); // Pastikan helper dipanggil
    }

    // INDEX - Menampilkan daftar user
    public function index()
    {
        if ($redirect = checkLogin()) return $redirect;  // Cek login
        $menus = getRoleMenus();  // Ambil menu berdasarkan role

        return view('menu/index', [
            'menus' => $menus,
            'icon' => 'users',
            'pageTitle' => 'Master Menu',
            'pageSubTitle' => 'Menampilkan daftar Menu',
            'cardTitle' => 'Menu'
        ]);
    }

    public function create()
    {
        if ($redirect = checkLogin()) return $redirect;  // Cek login
        $menus = getRoleMenus();  // Ambil menu berdasarkan role

        return view('menu/create', [
            'menus' => $menus,
            'menu' => [
                'intMenuID' => null,
                'txtMenuName' => '',
                'txtMenuLink' => '',
                'txtIcon' => '',
                'intParentID' => null,
                'intSortOrder' => null,
                'txtDesc' => '',
                'bitActive' => false
            ],
            'icon' => 'users',
            'pageTitle' => 'Master Menu',
            'pageSubTitle' => 'Menampilkan daftar Menu',
            'cardTitle' => 'Menu'
        ]);
    }

    public function store()
    {
        $session = session();
        $data = [
            'txtMenuName'    => $this->request->getPost('txtMenuName'),
            'txtMenuLink'    => $this->request->getPost('txtMenuLink'),
            'intParentID'    => $this->request->getPost('intParentID'),
            'intSortOrder'   => $this->request->getPost('intSortOrder'),
            'txtIcon'        => $this->request->getPost('txtIcon'),   // Field Icon
            'txtDesc'        => $this->request->getPost('txtDesc'),   // Field Deskripsi
            'bitActive'      => $this->request->getPost('bitActive') ? 1 : 0, // Update bitActive dengan pengecekan
            'txtInsertedBy'  => $session->get('userID'),
            'txtGUID'        => uniqid()
        ];

        $this->menuModel->save($data);
        return redirect()->to('/menu')->with('success', 'Menu successfully created.');
    }

    public function edit($id)
    {
        if ($redirect = checkLogin()) return $redirect;  // Cek login
        $menus = getRoleMenus();  // Ambil menu berdasarkan role
        $data['menu'] = $this->menuModel->find($id); // Ambil data menu berdasarkan ID

        return view('menu/edit', [
            'menus' => $menus,
            'menu' => $data['menu'], // Memastikan hanya data menu yang dikirim
            'icon' => 'users',
            'pageTitle' => 'Master Menu',
            'pageSubTitle' => 'Menampilkan daftar Menu',
            'cardTitle' => 'Menu'
        ]);
    }

    public function update($id)
    {
        $data = [
            'txtMenuName'  => $this->request->getPost('txtMenuName'),
            'txtMenuLink'  => $this->request->getPost('txtMenuLink'),
            'txtIcon'      => $this->request->getPost('txtIcon'),
            'intParentID'  => $this->request->getPost('intParentID'),
            'intSortOrder' => $this->request->getPost('intSortOrder'),
            'txtDesc'      => $this->request->getPost('txtDesc'),
            'txtUpdatedBy' => session()->get('userID'),
            'bitActive'    => $this->request->getPost('bitActive') ? 1 : 0, // Update bitActive dengan pengecekan
        ];

        // Update data di database
        $this->menuModel->update($id, $data);

        return redirect()->to('/menu')->with('success', 'Menu updated successfully');
    }

    public function view($id)
    {
        $menu = $this->menuModel->find($id);
        if (!$menu) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Menu dengan ID $id tidak ditemukan.");
        }
        $menus = getRoleMenus();  // Ambil menu berdasarkan role

        return view('menu/view', [
            'menu' => $menu,
            'menus' => $menus,
            'icon' => 'users',
            'pageTitle' => 'Master Menu',
            'pageSubTitle' => 'Menampilkan daftar Menu',
            'cardTitle' => 'Menu'
        ]);
    }

    public function getMenu()
    {
        $menuModel = new \App\Models\MenuModel();
        $menus = $menuModel->where('bitActive', 1)->orderBy('intSortOrder', 'ASC')->findAll();
        return $menus;
    }
}
