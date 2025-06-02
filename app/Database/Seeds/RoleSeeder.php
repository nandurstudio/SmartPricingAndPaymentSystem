<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class RoleSeeder extends Seeder
{    
    public function run()
    {
        // Disable foreign key checks
        $this->db->query('SET FOREIGN_KEY_CHECKS=0');
        
        // Delete existing records instead of truncate
        $this->db->table('m_role')->emptyTable();
        
        $currentTime = date('Y-m-d H:i:s');
        
        $data = [
            [
                'intRoleID' => 1,
                'txtRoleName' => 'Super Administrator',
                'txtRoleDesc' => 'Full access to all system features including multi-tenant management',
                'txtRoleNote' => 'Highest level system administrator',
                'bitActive' => 1,
                'txtGUID' => $this->db->query('SELECT UUID() as guid')->getRow()->guid,
                'txtCreatedBy' => 'Seeder',
                'dtmCreatedDate' => $currentTime,
                'txtUpdatedBy' => 'Seeder',
                'dtmUpdatedDate' => $currentTime
            ],
            [
                'intRoleID' => 2,
                'txtRoleName' => 'Administrator',
                'txtRoleDesc' => 'System administrator with limited tenant management access',
                'txtRoleNote' => 'System administrator for specific operations',
                'bitActive' => 1,
                'txtGUID' => $this->db->query('SELECT UUID() as guid')->getRow()->guid,
                'txtCreatedBy' => 'Seeder',
                'dtmCreatedDate' => $currentTime,
                'txtUpdatedBy' => 'Seeder',
                'dtmUpdatedDate' => $currentTime
            ],
            [
                'intRoleID' => 3,
                'txtRoleName' => 'Tenant Owner',
                'txtRoleDesc' => 'Business owner with full access to their tenant services',
                'txtRoleNote' => 'Can manage their own tenant services and bookings',
                'bitActive' => 1,
                'txtGUID' => $this->db->query('SELECT UUID() as guid')->getRow()->guid,
                'txtCreatedBy' => 'Seeder',
                'dtmCreatedDate' => $currentTime,
                'txtUpdatedBy' => 'Seeder',
                'dtmUpdatedDate' => $currentTime
            ],
            [
                'intRoleID' => 4,
                'txtRoleName' => 'Tenant Staff',
                'txtRoleDesc' => 'Staff member of a tenant with limited access',
                'txtRoleNote' => 'Can manage bookings and basic service operations',
                'bitActive' => 1,
                'txtGUID' => $this->db->query('SELECT UUID() as guid')->getRow()->guid,
                'txtCreatedBy' => 'Seeder',
                'dtmCreatedDate' => $currentTime,
                'txtUpdatedBy' => 'Seeder',
                'dtmUpdatedDate' => $currentTime
            ],
            [
                'intRoleID' => 5,
                'txtRoleName' => 'Customer',
                'txtRoleDesc' => 'End user who can make bookings',
                'txtRoleNote' => 'Regular user with booking capabilities',
                'bitActive' => 1,
                'txtGUID' => $this->db->query('SELECT UUID() as guid')->getRow()->guid,
                'txtCreatedBy' => 'Seeder',
                'dtmCreatedDate' => $currentTime,
                'txtUpdatedBy' => 'Seeder',
                'dtmUpdatedDate' => $currentTime
            ],
            [
                'intRoleID' => 6,
                'txtRoleName' => 'Guest',
                'txtRoleDesc' => 'Unregistered user with view-only access',
                'txtRoleNote' => 'Limited to viewing public information',
                'bitActive' => 1,
                'txtGUID' => $this->db->query('SELECT UUID() as guid')->getRow()->guid,
                'txtCreatedBy' => 'Seeder',
                'dtmCreatedDate' => $currentTime,
                'txtUpdatedBy' => 'Seeder',
                'dtmUpdatedDate' => $currentTime
            ],
        ];

        $this->db->table('m_role')->insertBatch($data);

        // Re-enable foreign key checks
        $this->db->query('SET FOREIGN_KEY_CHECKS=1');
    }
}
