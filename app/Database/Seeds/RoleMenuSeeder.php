<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class RoleMenuSeeder extends Seeder
{
    public function run()
    {
        // Clear existing role menu assignments first
        $this->db->table('m_role_menu')->truncate();

        // Get all menu IDs first
        $menuIds = [];
        $query = $this->db->table('m_menu')->select('intMenuID, txtMenuLink, intParentID')->get();
        foreach ($query->getResultArray() as $menu) {
            $menuIds[$menu['txtMenuLink']] = $menu['intMenuID'];
            if ($menu['intParentID']) {
                $parentMenus[$menu['intMenuID']] = $menu['intParentID'];
            }
        }

        $roleMenus = [
            // Super Administrator - Full Access
            ['intRoleID' => 1, 'menuLinks' => [
                '/dashboard', '/users', '/roles', '/service-types',
                '/tenants', '/tenant-services', '/services', '/schedules',
                '/service-attributes', '/bookings', '/booking-calendar',
                '/reports/bookings', '/reports/revenue', '/reports/usage',
                '/settings/system', '/settings/profile', '/menu',
                '/role-menu-access'
            ]],
            
            // Administrator - Limited Access
            ['intRoleID' => 2, 'menuLinks' => [
                '/dashboard', '/users', '/services', '/schedules',
                '/service-attributes', '/bookings', '/booking-calendar',
                '/reports/bookings', '/settings/profile'
            ]],
            
            // Tenant Owner - Business Access
            ['intRoleID' => 3, 'menuLinks' => [
                '/dashboard', '/tenant-services', '/services', '/schedules',
                '/service-attributes', '/bookings', '/booking-calendar',
                '/reports/bookings', '/reports/revenue', '/settings/profile'
            ]],
            
            // Tenant Staff - Operational Access
            ['intRoleID' => 4, 'menuLinks' => [
                '/dashboard', '/services', '/schedules', '/bookings',
                '/booking-calendar', '/settings/profile'
            ]],
            
            // Customer - Basic Access
            ['intRoleID' => 5, 'menuLinks' => [
                '/dashboard', '/bookings', '/settings/profile'
            ]],
            
            // Guest - View Only
            ['intRoleID' => 6, 'menuLinks' => [
                '/dashboard'
            ]]
        ];

        $data = [];
        foreach ($roleMenus as $role) {
            // Add menus based on menuLinks
            foreach ($role['menuLinks'] as $menuLink) {
                if (isset($menuIds[$menuLink])) {
                    $menuId = $menuIds[$menuLink];
                    $data[] = [
                        'intRoleID' => $role['intRoleID'],
                        'intMenuID' => $menuId,
                        'bitActive' => 1,
                        'txtCreatedBy' => 'system',
                        'dtmCreatedDate' => date('Y-m-d H:i:s')
                    ];
                    
                    // Add parent menu if exists
                    if (isset($parentMenus[$menuId])) {
                        $parentId = $parentMenus[$menuId];
                        $data[] = [
                            'intRoleID' => $role['intRoleID'],
                            'intMenuID' => $parentId,
                            'bitActive' => 1,
                            'txtCreatedBy' => 'system',
                            'dtmCreatedDate' => date('Y-m-d H:i:s')
                        ];
                    }
                }
            }
        }

        // Insert data in batches
        if (!empty($data)) {
            // Remove duplicate entries
            $data = array_map('unserialize', array_unique(array_map('serialize', $data)));
            $this->db->table('m_role_menu')->insertBatch($data);
        }
    }
}
