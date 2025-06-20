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
        
        // Get all menus instead of role-filtered menus
        $menus = $this->menuModel->findAll();

        return view('menu/index', [
            'title' => 'Menu Management',
            'menus' => $menus,
            'icon' => 'users',
            'pageTitle' => 'Master Menu',
            'pageSubTitle' => 'Menampilkan daftar Menu',
            'cardTitle' => 'Menu'
        ]);
    }

    public function create()
    {
        if ($redirect = checkLogin()) return $redirect;
        
        // Get parent menus for the dropdown
        $parentMenus = $this->menuModel->where('intParentID', null)
                                     ->orWhere('intParentID', 0)
                                     ->findAll();

        return view('menu/create', [
            'title' => 'Create Menu',
            'menu' => [
                'intMenuID' => null,
                'txtMenuName' => '',
                'txtMenuLink' => '',
                'txtIcon' => '',
                'intParentID' => null,
                'intSortOrder' => null,
                'bitActive' => true
            ],
            'parentMenus' => $parentMenus,
            'icon' => 'menu',
            'pageTitle' => 'Menu Management',
            'pageSubTitle' => 'Create new menu item',
            'cardTitle' => 'Create Menu'
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
            'txtIcon'        => $this->request->getPost('txtIcon'),
            'bitActive'      => $this->request->getPost('bitActive') ? 1 : 0,
            'txtInsertedBy'  => $session->get('userID'),
            'txtGUID'        => uniqid()
        ];

        $this->menuModel->save($data);
        return redirect()->to('/menu')->with('success', 'Menu successfully created.');
    }    public function edit($id)
    {
        if ($redirect = checkLogin()) return $redirect;
        $menu = $this->menuModel->find($id);

        if (!$menu) {
            return redirect()->to('/menu')->with('error', 'Menu not found');
        }

        // Set default values for nullable fields
        $menu['bitActive'] = $menu['bitActive'] ?? 1;

        // Get parent menus for the dropdown, excluding the current menu
        $parentMenus = $this->menuModel->where('intParentID', null)
                                     ->orWhere('intParentID', 0)
                                     ->where('intMenuID !=', $id)
                                     ->findAll();

        return view('menu/edit', [
            'title' => 'Edit Menu',
            'menu' => $menu,
            'parentMenus' => $parentMenus,
            'icon' => 'menu',
            'pageTitle' => 'Menu Management',
            'pageSubTitle' => 'Edit menu details',
            'cardTitle' => 'Edit Menu'
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
