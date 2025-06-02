<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class GoogleUserMenuSeeder extends Seeder
{
    public function run()
    {
        // First ensure we have the required menus
        $requiredMenus = [
            [
                'txtMenuName' => 'Dashboard',
                'txtMenuLink' => '/dashboard',
                'txtIcon' => 'activity',
                'intSortOrder' => 1,
            ],
            [
                'txtMenuName' => 'My Tenants',
                'txtMenuLink' => '/tenants',
                'txtIcon' => 'briefcase',
                'intSortOrder' => 2,
            ],
            [
                'txtMenuName' => 'Create Tenant',
                'txtMenuLink' => '/tenants/create',
                'txtIcon' => 'plus-square',
                'intSortOrder' => 3,
            ],
            [
                'txtMenuName' => 'My Profile',
                'txtMenuLink' => '/settings/profile',
                'txtIcon' => 'user',
                'intSortOrder' => 4,
            ]
        ];

        foreach ($requiredMenus as $menu) {
            // Check if menu exists
            $existingMenu = $this->db->table('m_menu')
                                   ->where('txtMenuName', $menu['txtMenuName'])
                                   ->get()
                                   ->getRow();

            if (!$existingMenu) {
                // Insert new menu
                $this->db->table('m_menu')->insert([
                    'txtGUID' => uniqid('menu_', true),
                    'txtMenuName' => $menu['txtMenuName'],
                    'txtMenuLink' => $menu['txtMenuLink'],
                    'txtIcon' => $menu['txtIcon'],
                    'intParentID' => null,
                    'intSortOrder' => $menu['intSortOrder'],
                    'bitActive' => 1,
                    'txtCreatedBy' => 'system',
                    'dtmCreatedDate' => date('Y-m-d H:i:s')
                ]);
            } else {
                // Update existing menu
                $this->db->table('m_menu')
                         ->where('intMenuID', $existingMenu->intMenuID)
                         ->update([
                             'txtMenuLink' => $menu['txtMenuLink'],
                             'txtIcon' => $menu['txtIcon'],
                             'intSortOrder' => $menu['intSortOrder'],
                             'txtUpdatedBy' => 'system',
                             'dtmUpdatedDate' => date('Y-m-d H:i:s')
                         ]);
            }
        }

        // Remove existing role menu entries for Google users (role 5)
        $this->db->table('m_role_menu')
                 ->where('intRoleID', 5)
                 ->delete();

        // Add menu permissions for Google users
        $menuItems = $this->db->table('m_menu')
                            ->whereIn('txtMenuName', array_column($requiredMenus, 'txtMenuName'))
                            ->get()
                            ->getResultArray();

        foreach ($menuItems as $menu) {
            $this->db->table('m_role_menu')->insert([
                'intRoleID' => 5, // Google user role
                'intMenuID' => $menu['intMenuID'],
                'bitActive' => 1,
                'txtCreatedBy' => 'system',
                'dtmCreatedDate' => date('Y-m-d H:i:s')
            ]);
        }
    }
}
