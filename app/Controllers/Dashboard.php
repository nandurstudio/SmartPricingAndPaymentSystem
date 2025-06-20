<?php

namespace App\Controllers;

use App\Models\MenuModel;
use App\Models\MTenantModel;
use App\Models\MUserModel;

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

        // Ambil jumlah tenant
        $tenantModel = new MTenantModel();
        $tenantCount = $tenantModel->countAll();

        // Ambil jumlah user
        $userModel = new MUserModel();
        $userCount = $userModel->countAll();

        // Tampilkan halaman Home dengan menu dan statistik
        return view('dashboard', [
            'menus' => $menus,
            'tenantCount' => $tenantCount,
            'userCount' => $userCount
        ]);
    }
}
