<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class RoleMenuSeeder extends Seeder
{
    public function run()
    {        // First, truncate the role_menu table to avoid duplicates
        $this->db->table('m_role_menu')->truncate();

        // Get all menu IDs
        $allMenus = $this->db->table('m_menu')->select('intMenuID')->get()->getResultArray();
        $allMenuIds = array_column($allMenus, 'intMenuID');

        // Super Administrator (Full Access)
        $superAdminAccess = array_map(function($menuId) {
            return [
                'intRoleID' => 1,
                'intMenuID' => $menuId
            ];
        }, $allMenuIds);
        $this->db->table('m_role_menu')->insertBatch($superAdminAccess);

        // Administrator (Limited Access)
        $adminMenuPaths = ['/dashboard', '/master/users', '/master/roles', '/settings/profile'];
        $adminMenus = $this->db->table('m_menu')
            ->select('intMenuID')
            ->whereIn('txtMenuLink', $adminMenuPaths)
            ->get()
            ->getResultArray();
        
        $adminAccess = array_map(function($menu) {
            return [
                'intRoleID' => 2,
                'intMenuID' => $menu['intMenuID']
            ];
        }, $adminMenus);
        if (!empty($adminAccess)) {
            $this->db->table('m_role_menu')->insertBatch($adminAccess);
        }

        // Tenant Owner Access
        $tenantOwnerPaths = [
            '/dashboard',
            '/services',
            '/services/schedule',
            '/services/attributes',
            '/bookings',
            '/bookings/calendar',
            '/reports/bookings',
            '/reports/revenue',
            '/reports/usage',
            '/settings/profile'
        ];
        $tenantOwnerMenus = $this->db->table('m_menu')
            ->select('intMenuID')
            ->whereIn('txtMenuLink', $tenantOwnerPaths)
            ->get()
            ->getResultArray();

        $tenantOwnerAccess = array_map(function($menu) {
            return [
                'intRoleID' => 3,
                'intMenuID' => $menu['intMenuID']
            ];
        }, $tenantOwnerMenus);
        if (!empty($tenantOwnerAccess)) {
            $this->db->table('m_role_menu')->insertBatch($tenantOwnerAccess);
        }

        // Tenant Staff Access
        $tenantStaffPaths = [
            '/dashboard',
            '/services',
            '/services/schedule',
            '/bookings',
            '/bookings/calendar',
            '/settings/profile'
        ];
        $tenantStaffMenus = $this->db->table('m_menu')
            ->select('intMenuID')
            ->whereIn('txtMenuLink', $tenantStaffPaths)
            ->get()
            ->getResultArray();

        $tenantStaffAccess = array_map(function($menu) {
            return [
                'intRoleID' => 4,
                'intMenuID' => $menu['intMenuID']
            ];
        }, $tenantStaffMenus);
        if (!empty($tenantStaffAccess)) {
            $this->db->table('m_role_menu')->insertBatch($tenantStaffAccess);
        }

        // Customer Access
        $customerPaths = [
            '/dashboard',
            '/services/list',
            '/bookings/list',
            '/bookings/calendar',
            '/bookings/payments',
            '/settings/profile'
        ];
        $customerMenus = $this->db->table('m_menu')
            ->select('intMenuID')
            ->whereIn('txtMenuLink', $customerPaths)
            ->get()
            ->getResultArray();

        $customerAccess = array_map(function($menu) {
            return [
                'intRoleID' => 5,
                'intMenuID' => $menu['intMenuID']
            ];
        }, $customerMenus);
        if (!empty($customerAccess)) {
            $this->db->table('m_role_menu')->insertBatch($customerAccess);
        }

        // Guest Access
        $guestPaths = [
            '/services/list',
            '/bookings/calendar'
        ];
        $guestMenus = $this->db->table('m_menu')
            ->select('intMenuID')
            ->whereIn('txtMenuLink', $guestPaths)
            ->get()
            ->getResultArray();

        $guestAccess = array_map(function($menu) {
            return [
                'intRoleID' => 6,
                'intMenuID' => $menu['intMenuID']
            ];
        }, $guestMenus);
        if (!empty($guestAccess)) {
            $this->db->table('m_role_menu')->insertBatch($guestAccess);
        }
    }
}
