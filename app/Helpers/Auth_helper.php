<?php

use App\Models\MenuModel;

if (!function_exists('checkLogin')) {
    function checkLogin()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'You must be logged in to access this page.');
        }
        return null; // Tidak ada redirect, lanjutkan
    }
}

if (!function_exists('getRoleMenus')) {
    function getRoleMenus()
    {
        $roleID = session()->get('roleID');
        $menuModel = new MenuModel();
        return $menuModel->getMenusByRole($roleID);
    }
}
