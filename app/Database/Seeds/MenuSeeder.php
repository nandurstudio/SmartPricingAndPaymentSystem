<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class MenuSeeder extends Seeder
{
    public function run()
    {
        // Start a transaction to ensure data consistency
        $this->db->transStart();
        
        try {
            // Disable foreign key checks temporarily
            $this->db->query('SET FOREIGN_KEY_CHECKS=0');

            // Clear existing menu data to avoid duplicates
            $this->db->table('m_menu')->emptyTable();

            $currentTime = date('Y-m-d H:i:s');

            // Define all menu data first
            $allMenus = [
                // Parent menus
                [
                    'intMenuID' => 1,
                    'txtMenuName' => 'Dashboard',
                    'txtMenuLink' => '/dashboard',
                    'txtIcon' => 'house',
                    'intParentID' => null,
                    'intSortOrder' => 1,
                ],
                [
                    'intMenuID' => 2,
                    'txtMenuName' => 'Master Data',
                    'txtMenuLink' => null,
                    'txtIcon' => 'database',
                    'intParentID' => null,
                    'intSortOrder' => 2,
                ],
                [
                    'intMenuID' => 3,
                    'txtMenuName' => 'Tenant Management',
                    'txtMenuLink' => null,
                    'txtIcon' => 'building',
                    'intParentID' => null,
                    'intSortOrder' => 3,
                ],
                [
                    'intMenuID' => 4,
                    'txtMenuName' => 'Service Management',
                    'txtMenuLink' => null,
                    'txtIcon' => 'box-seam',
                    'intParentID' => null,
                    'intSortOrder' => 4,
                ],
                [
                    'intMenuID' => 5,
                    'txtMenuName' => 'Booking Management',
                    'txtMenuLink' => null,
                    'txtIcon' => 'calendar-event',
                    'intParentID' => null,
                    'intSortOrder' => 5,
                ],
                [
                    'intMenuID' => 6,
                    'txtMenuName' => 'Reports',
                    'txtMenuLink' => null,
                    'txtIcon' => 'bar-chart',
                    'intParentID' => null,
                    'intSortOrder' => 6,
                ],
                [
                    'intMenuID' => 7,
                    'txtMenuName' => 'Settings',
                    'txtMenuLink' => null,
                    'txtIcon' => 'gear',
                    'intParentID' => null,
                    'intSortOrder' => 7,
                ],
                // Master Data children
                [
                    'intMenuID' => 8,
                    'txtMenuName' => 'Users',
                    'txtMenuLink' => '/users',
                    'txtIcon' => 'people',
                    'intParentID' => 2,
                    'intSortOrder' => 1,
                ],
                [
                    'intMenuID' => 9,
                    'txtMenuName' => 'Roles',
                    'txtMenuLink' => '/roles',
                    'txtIcon' => 'shield-lock',
                    'intParentID' => 2,
                    'intSortOrder' => 2,
                ],
                [
                    'intMenuID' => 10,
                    'txtMenuName' => 'Service Types',
                    'txtMenuLink' => '/service-types',
                    'txtIcon' => 'tag',
                    'intParentID' => 2,
                    'intSortOrder' => 3,
                ],
                // Tenant Management children
                [
                    'intMenuID' => 11,
                    'txtMenuName' => 'All Tenants',
                    'txtMenuLink' => '/tenants',
                    'txtIcon' => 'grid-3x3-gap',
                    'intParentID' => 3,
                    'intSortOrder' => 1,
                ],
                [
                    'intMenuID' => 12,
                    'txtMenuName' => 'Tenant Requests',
                    'txtMenuLink' => '/tenants/requests',
                    'txtIcon' => 'person-plus',
                    'intParentID' => 3,
                    'intSortOrder' => 2,
                ],
                // Service Management children
                [
                    'intMenuID' => 13,
                    'txtMenuName' => 'All Services',
                    'txtMenuLink' => '/services',
                    'txtIcon' => 'collection',
                    'intParentID' => 4,
                    'intSortOrder' => 1,
                ],
                [
                    'intMenuID' => 14,
                    'txtMenuName' => 'Service Schedules',
                    'txtMenuLink' => '/schedules',
                    'txtIcon' => 'calendar-week',
                    'intParentID' => 4,
                    'intSortOrder' => 2,
                ],
                [
                    'intMenuID' => 15,
                    'txtMenuName' => 'Service Attributes',
                    'txtMenuLink' => '/service-attributes',
                    'txtIcon' => 'card-checklist',
                    'intParentID' => 4,
                    'intSortOrder' => 3,
                ],
                // Booking Management children
                [
                    'intMenuID' => 16,
                    'txtMenuName' => 'All Bookings',
                    'txtMenuLink' => '/bookings',
                    'txtIcon' => 'list-ul',
                    'intParentID' => 5,
                    'intSortOrder' => 1,
                ],
                [
                    'intMenuID' => 17,
                    'txtMenuName' => 'Calendar View',
                    'txtMenuLink' => '/booking-calendar',
                    'txtIcon' => 'calendar-week',
                    'intParentID' => 5,
                    'intSortOrder' => 2,
                ],
                [
                    'intMenuID' => 18,
                    'txtMenuName' => 'Payments',
                    'txtMenuLink' => '/payments',
                    'txtIcon' => 'credit-card-2-front',
                    'intParentID' => 5,
                    'intSortOrder' => 3,
                ],
                // Reports children
                [
                    'intMenuID' => 19,
                    'txtMenuName' => 'Booking Reports',
                    'txtMenuLink' => '/reports/bookings',
                    'txtIcon' => 'journal-text',
                    'intParentID' => 6,
                    'intSortOrder' => 1,
                ],
                [
                    'intMenuID' => 20,
                    'txtMenuName' => 'Revenue Reports',
                    'txtMenuLink' => '/reports/revenue',
                    'txtIcon' => 'cash',
                    'intParentID' => 6,
                    'intSortOrder' => 2,
                ],
                [
                    'intMenuID' => 21,
                    'txtMenuName' => 'Usage Reports',
                    'txtMenuLink' => '/reports/usage',
                    'txtIcon' => 'graph-up',
                    'intParentID' => 6,
                    'intSortOrder' => 3,
                ],
                // Settings children
                [
                    'intMenuID' => 22,
                    'txtMenuName' => 'System Settings',
                    'txtMenuLink' => '/settings/system',
                    'txtIcon' => 'sliders2',
                    'intParentID' => 7,
                    'intSortOrder' => 1,
                ],
                [
                    'intMenuID' => 23,
                    'txtMenuName' => 'Profile Settings',
                    'txtMenuLink' => '/settings/profile',
                    'txtIcon' => 'person-gear',
                    'intParentID' => 7,
                    'intSortOrder' => 2,
                ],
                [
                    'intMenuID' => 24,
                    'txtMenuName' => 'Menu Management',
                    'txtMenuLink' => '/menu',
                    'txtIcon' => 'list-check',
                    'intParentID' => 7,
                    'intSortOrder' => 3,
                ],
                [
                    'intMenuID' => 25,
                    'txtMenuName' => 'Role Menu Access',
                    'txtMenuLink' => '/role-menu-access',
                    'txtIcon' => 'shield-check',
                    'intParentID' => 7,
                    'intSortOrder' => 4,
                ]
            ];

            // Insert all menus in a single batch
            foreach ($allMenus as $menu) {
                $this->db->table('m_menu')->insert([
                    'intMenuID' => $menu['intMenuID'],
                    'txtMenuName' => $menu['txtMenuName'],
                    'txtMenuLink' => $menu['txtMenuLink'],
                    'txtIcon' => $menu['txtIcon'],
                    'intParentID' => $menu['intParentID'],
                    'intSortOrder' => $menu['intSortOrder'],
                    'bitActive' => 1,
                    'txtGUID' => $this->db->query('SELECT UUID() as guid')->getRow()->guid,
                    'txtCreatedBy' => 'system',
                    'dtmCreatedDate' => $currentTime,
                    'txtUpdatedBy' => 'system',
                    'dtmUpdatedDate' => $currentTime
                ]);
            }

            // Re-enable foreign key checks
            $this->db->query('SET FOREIGN_KEY_CHECKS=1');

            // Commit transaction if everything is successful
            $this->db->transCommit();
            
        } catch (\Exception $e) {
            // Roll back the transaction on error
            $this->db->transRollback();
            
            // Re-enable foreign key checks
            $this->db->query('SET FOREIGN_KEY_CHECKS=1');
            
            // Re-throw the exception
            throw $e;
        }
    }
}
