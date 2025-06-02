<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class MenuSeeder extends Seeder
{
    public function run()
    {
        // First, truncate the menu table to avoid duplicates
        $this->db->table('m_menu')->truncate();
        
        // Insert parent menus
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
                'dtmCreatedDate' => date('Y-m-d H:i:s')
            ],
            [
                'intMenuID' => 2,
                'txtMenuName' => 'Master Data',
                'txtMenuLink' => '#',
                'txtIcon' => 'database',
                'intParentID' => null,
                'intSortOrder' => 2,
                'bitActive' => 1,
                'txtGUID' => $this->db->query('SELECT UUID() as guid')->getRow()->guid,
                'txtCreatedBy' => 'system',
                'dtmCreatedDate' => date('Y-m-d H:i:s')
            ],
            [
                'intMenuID' => 3,
                'txtMenuName' => 'Tenant Management',
                'txtMenuLink' => '#',
                'txtIcon' => 'briefcase',
                'intParentID' => null,
                'intSortOrder' => 3,
                'bitActive' => 1,
                'txtGUID' => $this->db->query('SELECT UUID() as guid')->getRow()->guid,
                'txtCreatedBy' => 'system',
                'dtmCreatedDate' => date('Y-m-d H:i:s')
            ],
            [
                'intMenuID' => 4,
                'txtMenuName' => 'Service Management',
                'txtMenuLink' => '#',
                'txtIcon' => 'package',
                'intParentID' => null,
                'intSortOrder' => 4,
                'bitActive' => 1,
                'txtGUID' => $this->db->query('SELECT UUID() as guid')->getRow()->guid,
                'txtCreatedBy' => 'system',
                'dtmCreatedDate' => date('Y-m-d H:i:s')
            ],
            [
                'intMenuID' => 5,
                'txtMenuName' => 'Booking Management',
                'txtMenuLink' => '#',
                'txtIcon' => 'calendar',
                'intParentID' => null,
                'intSortOrder' => 5,
                'bitActive' => 1,
                'txtGUID' => $this->db->query('SELECT UUID() as guid')->getRow()->guid,
                'txtCreatedBy' => 'system',
                'dtmCreatedDate' => date('Y-m-d H:i:s')
            ],
            [
                'intMenuID' => 6,
                'txtMenuName' => 'Reports',
                'txtMenuLink' => '#',
                'txtIcon' => 'bar-chart-2',
                'intParentID' => null,
                'intSortOrder' => 6,
                'bitActive' => 1,
                'txtGUID' => $this->db->query('SELECT UUID() as guid')->getRow()->guid,
                'txtCreatedBy' => 'system',
                'dtmCreatedDate' => date('Y-m-d H:i:s')
            ],
            [
                'intMenuID' => 7,
                'txtMenuName' => 'Settings',
                'txtMenuLink' => '#',
                'txtIcon' => 'settings',
                'intParentID' => null,
                'intSortOrder' => 7,
                'bitActive' => 1,
                'txtGUID' => $this->db->query('SELECT UUID() as guid')->getRow()->guid,
                'txtCreatedBy' => 'system',
                'dtmCreatedDate' => date('Y-m-d H:i:s')
            ]
        ];

        $this->db->table('m_menu')->insertBatch($parentMenus);

        // Insert child menus
        $childMenus = [
            // Master Data children
            [
                'intMenuID' => 8,
                'txtMenuName' => 'Users',
                'txtMenuLink' => '/master/users',
                'txtIcon' => 'users',
                'intParentID' => 2,
                'intSortOrder' => 1,
                'bitActive' => 1,
                'txtGUID' => $this->db->query('SELECT UUID() as guid')->getRow()->guid,
                'txtCreatedBy' => 'system',
                'dtmCreatedDate' => date('Y-m-d H:i:s')
            ],
            [
                'intMenuID' => 9,
                'txtMenuName' => 'Roles',
                'txtMenuLink' => '/master/roles',
                'txtIcon' => 'shield',
                'intParentID' => 2,
                'intSortOrder' => 2,
                'bitActive' => 1,
                'txtGUID' => $this->db->query('SELECT UUID() as guid')->getRow()->guid,
                'txtCreatedBy' => 'system',
                'dtmCreatedDate' => date('Y-m-d H:i:s')
            ],
            [
                'intMenuID' => 10,
                'txtMenuName' => 'Service Types',
                'txtMenuLink' => '/master/service-types',
                'txtIcon' => 'tag',
                'intParentID' => 2,
                'intSortOrder' => 3,
                'bitActive' => 1,
                'txtGUID' => $this->db->query('SELECT UUID() as guid')->getRow()->guid,
                'txtCreatedBy' => 'system',
                'dtmCreatedDate' => date('Y-m-d H:i:s')
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
                'dtmCreatedDate' => date('Y-m-d H:i:s')
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
                'dtmCreatedDate' => date('Y-m-d H:i:s')
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
                'dtmCreatedDate' => date('Y-m-d H:i:s')
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
                'dtmCreatedDate' => date('Y-m-d H:i:s')
            ],
            [
                'intMenuID' => 15,
                'txtMenuName' => 'Special Schedules',
                'txtMenuLink' => '/special-schedules',
                'txtIcon' => 'calendar',
                'intParentID' => 4,
                'intSortOrder' => 3,
                'bitActive' => 1,
                'txtGUID' => $this->db->query('SELECT UUID() as guid')->getRow()->guid,
                'txtCreatedBy' => 'system',
                'dtmCreatedDate' => date('Y-m-d H:i:s')
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
                'dtmCreatedDate' => date('Y-m-d H:i:s')
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
                'dtmCreatedDate' => date('Y-m-d H:i:s')
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
                'dtmCreatedDate' => date('Y-m-d H:i:s')
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
                'dtmCreatedDate' => date('Y-m-d H:i:s')
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
                'dtmCreatedDate' => date('Y-m-d H:i:s')
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
                'dtmCreatedDate' => date('Y-m-d H:i:s')
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
                'dtmCreatedDate' => date('Y-m-d H:i:s')
            ],
            [
                'intMenuID' => 23,
                'txtMenuName' => 'Notification Templates',
                'txtMenuLink' => '/settings/notifications',
                'txtIcon' => 'mail',
                'intParentID' => 7,
                'intSortOrder' => 2,
                'bitActive' => 1,
                'txtGUID' => $this->db->query('SELECT UUID() as guid')->getRow()->guid,
                'txtCreatedBy' => 'system',
                'dtmCreatedDate' => date('Y-m-d H:i:s')
            ],
            [
                'intMenuID' => 24,
                'txtMenuName' => 'Profile Settings',
                'txtMenuLink' => '/settings/profile',
                'txtIcon' => 'user',
                'intParentID' => 7,
                'intSortOrder' => 3,
                'bitActive' => 1,
                'txtGUID' => $this->db->query('SELECT UUID() as guid')->getRow()->guid,
                'txtCreatedBy' => 'system',
                'dtmCreatedDate' => date('Y-m-d H:i:s')
            ]
        ];

        $this->db->table('m_menu')->insertBatch($childMenus);
    }
}
