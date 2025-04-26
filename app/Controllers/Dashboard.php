<?php

namespace App\Controllers;

use App\Models\MenuModel;

class Dashboard extends BaseController
{
    public function index()
    {
        // Cek apakah user sudah login
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'You must be logged in to access this page.');
        }

        // Ambil roleID dari session dan menu berdasarkan role
        $roleID = session()->get('roleID');
        $menuModel = new MenuModel();
        $menus = $menuModel->getMenuByRole($roleID);

        // Tampilkan halaman Home dengan menu
        return view('/', ['menus' => $menus]);
    }
}
