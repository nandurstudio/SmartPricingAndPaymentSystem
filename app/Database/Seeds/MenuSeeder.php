<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class MenuSeeder extends Seeder
{
    public function run()
    {
        // Disable foreign key checks to avoid issues with parent-child relationships
        $this->db->query('SET FOREIGN_KEY_CHECKS=0');
        
        // Clear existing menu data to avoid duplicates
        $this->db->table('m_menu')->emptyTable();
        
        $currentTime = date('Y-m-d H:i:s');
        
        // Insert parent menus first
        $parentMenus = [
            [
                'intMenuID' => 1,
                'txtMenuName' => 'Dashboard',
                'txtMenuLink' => '/dashboard',
                'txtIcon' => 'activity',
                'intParentID' => null,
                'intSortOrder' => 1,
                'bitActive' => 1,
                'txtGUID' => $this->db->query('SELECT UUID() as guid')->getRow()->guid,
                'txtCreatedBy' => 'system',
                'dtmCreatedDate' => $currentTime,
                'txtUpdatedBy' => 'system',
                'dtmUpdatedDate' => $currentTime
            ],
            [
                'intMenuID' => 2,
                'txtMenuName' => 'Master Data',
                'txtMenuLink' => null,
                'txtIcon' => 'database',
                'intParentID' => null,
                'intSortOrder' => 2,
                'bitActive' => 1,
                'txtGUID' => $this->db->query('SELECT UUID() as guid')->getRow()->guid,
                'txtCreatedBy' => 'system',
                'dtmCreatedDate' => $currentTime,
                'txtUpdatedBy' => 'system',
                'dtmUpdatedDate' => $currentTime
            ],
            [
                'intMenuID' => 3,
                'txtMenuName' => 'Tenant Management',
                'txtMenuLink' => null,
                'txtIcon' => 'briefcase',
                'intParentID' => null,
                'intSortOrder' => 3,
                'bitActive' => 1,
                'txtGUID' => $this->db->query('SELECT UUID() as guid')->getRow()->guid,
                'txtCreatedBy' => 'system',
                'dtmCreatedDate' => $currentTime,
                'txtUpdatedBy' => 'system',
                'dtmUpdatedDate' => $currentTime
            ],
            [
                'intMenuID' => 4,
                'txtMenuName' => 'Service Management',
                'txtMenuLink' => null,
                'txtIcon' => 'package',
                'intParentID' => null,
                'intSortOrder' => 4,
                'bitActive' => 1,
                'txtGUID' => $this->db->query('SELECT UUID() as guid')->getRow()->guid,
                'txtCreatedBy' => 'system',
                'dtmCreatedDate' => $currentTime,
                'txtUpdatedBy' => 'system',
                'dtmUpdatedDate' => $currentTime
            ],
            [
                'intMenuID' => 5,
                'txtMenuName' => 'Booking Management',
                'txtMenuLink' => null,
                'txtIcon' => 'calendar',
                'intParentID' => null,
                'intSortOrder' => 5,
                'bitActive' => 1,
                'txtGUID' => $this->db->query('SELECT UUID() as guid')->getRow()->guid,
                'txtCreatedBy' => 'system',
                'dtmCreatedDate' => $currentTime,
                'txtUpdatedBy' => 'system',
                'dtmUpdatedDate' => $currentTime
            ],
            [
                'intMenuID' => 6,
                'txtMenuName' => 'Reports',
                'txtMenuLink' => null,
                'txtIcon' => 'bar-chart-2',
                'intParentID' => null,
                'intSortOrder' => 6,
                'bitActive' => 1,
                'txtGUID' => $this->db->query('SELECT UUID() as guid')->getRow()->guid,
                'txtCreatedBy' => 'system',
                'dtmCreatedDate' => $currentTime,
                'txtUpdatedBy' => 'system',
                'dtmUpdatedDate' => $currentTime
            ],
            [
                'intMenuID' => 7,
                'txtMenuName' => 'Settings',
                'txtMenuLink' => null,
                'txtIcon' => 'settings',
                'intParentID' => null,
                'intSortOrder' => 7,
                'bitActive' => 1,
                'txtGUID' => $this->db->query('SELECT UUID() as guid')->getRow()->guid,
                'txtCreatedBy' => 'system',
                'dtmCreatedDate' => $currentTime,
                'txtUpdatedBy' => 'system',
                'dtmUpdatedDate' => $currentTime
            ]
        ];

        $this->db->table('m_menu')->insertBatch($parentMenus);

        // Insert child menus
        $childMenus = [
            // Master Data children
            [
                'intMenuID' => 8,
                'txtMenuName' => 'Users',
                'txtMenuLink' => '/users',
                'txtIcon' => 'users',
                'intParentID' => 2,
                'intSortOrder' => 1,
                'bitActive' => 1,
                'txtGUID' => $this->db->query('SELECT UUID() as guid')->getRow()->guid,
                'txtCreatedBy' => 'system',
                'dtmCreatedDate' => $currentTime,
                'txtUpdatedBy' => 'system',
                'dtmUpdatedDate' => $currentTime
            ],
            [
                'intMenuID' => 9,
                'txtMenuName' => 'Roles',
                'txtMenuLink' => '/roles',
                'txtIcon' => 'shield',
                'intParentID' => 2,
                'intSortOrder' => 2,
                'bitActive' => 1,
                'txtGUID' => $this->db->query('SELECT UUID() as guid')->getRow()->guid,
                'txtCreatedBy' => 'system',
                'dtmCreatedDate' => $currentTime,
                'txtUpdatedBy' => 'system',
                'dtmUpdatedDate' => $currentTime
            ],
            [
                'intMenuID' => 10,
                'txtMenuName' => 'Service Types',
                'txtMenuLink' => '/service-types',
                'txtIcon' => 'tag',
                'intParentID' => 2,
                'intSortOrder' => 3,
                'bitActive' => 1,
                'txtGUID' => $this->db->query('SELECT UUID() as guid')->getRow()->guid,
                'txtCreatedBy' => 'system',
                'dtmCreatedDate' => $currentTime,
                'txtUpdatedBy' => 'system',
                'dtmUpdatedDate' => $currentTime
            ],
            
            // Tenant Management children
            [
                'intMenuID' => 11,
                'txtMenuName' => 'All Tenants',
                'txtMenuLink' => '/tenants',
                'txtIcon' => 'grid',
                'intParentID' => 3,
                'intSortOrder' => 1,
                'bitActive' => 1,
                'txtGUID' => $this->db->query('SELECT UUID() as guid')->getRow()->guid,
                'txtCreatedBy' => 'system',
                'dtmCreatedDate' => $currentTime,
                'txtUpdatedBy' => 'system',
                'dtmUpdatedDate' => $currentTime
            ],
            [
                'intMenuID' => 12,
                'txtMenuName' => 'Tenant Requests',
                'txtMenuLink' => '/tenants/requests',
                'txtIcon' => 'user-plus',
                'intParentID' => 3,
                'intSortOrder' => 2,
                'bitActive' => 1,
                'txtGUID' => $this->db->query('SELECT UUID() as guid')->getRow()->guid,
                'txtCreatedBy' => 'system',
                'dtmCreatedDate' => $currentTime,
                'txtUpdatedBy' => 'system',
                'dtmUpdatedDate' => $currentTime
            ],
            
            // Service Management children
            [
                'intMenuID' => 13,
                'txtMenuName' => 'Services',
                'txtMenuLink' => '/services',
                'txtIcon' => 'list',
                'intParentID' => 4,
                'intSortOrder' => 1,
                'bitActive' => 1,
                'txtGUID' => $this->db->query('SELECT UUID() as guid')->getRow()->guid,
                'txtCreatedBy' => 'system',
                'dtmCreatedDate' => $currentTime,
                'txtUpdatedBy' => 'system',
                'dtmUpdatedDate' => $currentTime
            ],
            [
                'intMenuID' => 14,
                'txtMenuName' => 'Schedules',
                'txtMenuLink' => '/schedules',
                'txtIcon' => 'clock',
                'intParentID' => 4,
                'intSortOrder' => 2,
                'bitActive' => 1,
                'txtGUID' => $this->db->query('SELECT UUID() as guid')->getRow()->guid,
                'txtCreatedBy' => 'system',
                'dtmCreatedDate' => $currentTime,
                'txtUpdatedBy' => 'system',
                'dtmUpdatedDate' => $currentTime
            ],
            [
                'intMenuID' => 15,
                'txtMenuName' => 'Service Attributes',
                'txtMenuLink' => '/service-attributes',
                'txtIcon' => 'sliders',
                'intParentID' => 4,
                'intSortOrder' => 3,
                'bitActive' => 1,
                'txtGUID' => $this->db->query('SELECT UUID() as guid')->getRow()->guid,
                'txtCreatedBy' => 'system',
                'dtmCreatedDate' => $currentTime,
                'txtUpdatedBy' => 'system',
                'dtmUpdatedDate' => $currentTime
            ],
            
            // Booking Management children
            [
                'intMenuID' => 16,
                'txtMenuName' => 'All Bookings',
                'txtMenuLink' => '/bookings',
                'txtIcon' => 'list',
                'intParentID' => 5,
                'intSortOrder' => 1,
                'bitActive' => 1,
                'txtGUID' => $this->db->query('SELECT UUID() as guid')->getRow()->guid,
                'txtCreatedBy' => 'system',
                'dtmCreatedDate' => $currentTime,
                'txtUpdatedBy' => 'system',
                'dtmUpdatedDate' => $currentTime
            ],
            [
                'intMenuID' => 17,
                'txtMenuName' => 'Calendar View',
                'txtMenuLink' => '/booking-calendar',
                'txtIcon' => 'calendar',
                'intParentID' => 5,
                'intSortOrder' => 2,
                'bitActive' => 1,
                'txtGUID' => $this->db->query('SELECT UUID() as guid')->getRow()->guid,
                'txtCreatedBy' => 'system',
                'dtmCreatedDate' => $currentTime,
                'txtUpdatedBy' => 'system',
                'dtmUpdatedDate' => $currentTime
            ],
            [
                'intMenuID' => 18,
                'txtMenuName' => 'Payments',
                'txtMenuLink' => '/payments',
                'txtIcon' => 'credit-card',
                'intParentID' => 5,
                'intSortOrder' => 3,
                'bitActive' => 1,
                'txtGUID' => $this->db->query('SELECT UUID() as guid')->getRow()->guid,
                'txtCreatedBy' => 'system',
                'dtmCreatedDate' => $currentTime,
                'txtUpdatedBy' => 'system',
                'dtmUpdatedDate' => $currentTime
            ],
            
            // Reports children
            [
                'intMenuID' => 19,
                'txtMenuName' => 'Booking Reports',
                'txtMenuLink' => '/reports/bookings',
                'txtIcon' => 'file-text',
                'intParentID' => 6,
                'intSortOrder' => 1,
                'bitActive' => 1,
                'txtGUID' => $this->db->query('SELECT UUID() as guid')->getRow()->guid,
                'txtCreatedBy' => 'system',
                'dtmCreatedDate' => $currentTime,
                'txtUpdatedBy' => 'system',
                'dtmUpdatedDate' => $currentTime
            ],
            [
                'intMenuID' => 20,
                'txtMenuName' => 'Revenue Reports',
                'txtMenuLink' => '/reports/revenue',
                'txtIcon' => 'dollar-sign',
                'intParentID' => 6,
                'intSortOrder' => 2,
                'bitActive' => 1,
                'txtGUID' => $this->db->query('SELECT UUID() as guid')->getRow()->guid,
                'txtCreatedBy' => 'system',
                'dtmCreatedDate' => $currentTime,
                'txtUpdatedBy' => 'system',
                'dtmUpdatedDate' => $currentTime
            ],
            [
                'intMenuID' => 21,
                'txtMenuName' => 'Service Usage',
                'txtMenuLink' => '/reports/usage',
                'txtIcon' => 'bar-chart',
                'intParentID' => 6,
                'intSortOrder' => 3,
                'bitActive' => 1,
                'txtGUID' => $this->db->query('SELECT UUID() as guid')->getRow()->guid,
                'txtCreatedBy' => 'system',
                'dtmCreatedDate' => $currentTime,
                'txtUpdatedBy' => 'system',
                'dtmUpdatedDate' => $currentTime
            ],
            
            // Settings children
            [
                'intMenuID' => 22,
                'txtMenuName' => 'System Settings',
                'txtMenuLink' => '/settings/system',
                'txtIcon' => 'settings',
                'intParentID' => 7,
                'intSortOrder' => 1,
                'bitActive' => 1,
                'txtGUID' => $this->db->query('SELECT UUID() as guid')->getRow()->guid,
                'txtCreatedBy' => 'system',
                'dtmCreatedDate' => $currentTime,
                'txtUpdatedBy' => 'system',
                'dtmUpdatedDate' => $currentTime
            ],
            [
                'intMenuID' => 23,
                'txtMenuName' => 'Profile Settings',
                'txtMenuLink' => '/settings/profile',
                'txtIcon' => 'user',
                'intParentID' => 7,
                'intSortOrder' => 2,
                'bitActive' => 1,
                'txtGUID' => $this->db->query('SELECT UUID() as guid')->getRow()->guid,
                'txtCreatedBy' => 'system',
                'dtmCreatedDate' => $currentTime,
                'txtUpdatedBy' => 'system',
                'dtmUpdatedDate' => $currentTime
            ]
        ];

        $this->db->table('m_menu')->insertBatch($childMenus);
        
        // Re-enable foreign key checks
        $this->db->query('SET FOREIGN_KEY_CHECKS=1');
    }
}
