<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run()
    {
        // First, truncate the role table to avoid duplicates
        $this->db->table('m_role')->truncate();
        $data = [
            [
                'intRoleID' => 1,
                'txtRoleName' => 'Super Administrator',
                'txtRoleDesc' => 'Full access to all system features including multi-tenant management',
                'txtRoleNote' => 'Highest level system administrator',
                'bitActive' => 1,
                'txtGUID' => $this->db->query('SELECT UUID() as guid')->getRow()->guid
            ],
            [
                'intRoleID' => 2,
                'txtRoleName' => 'Administrator',
                'txtRoleDesc' => 'System administrator with limited tenant management access',
                'txtRoleNote' => 'System administrator for specific operations',
                'bitActive' => 1,
                'txtGUID' => $this->db->query('SELECT UUID() as guid')->getRow()->guid
            ],
            [
                'intRoleID' => 3,
                'txtRoleName' => 'Tenant Owner',
                'txtRoleDesc' => 'Business owner with full access to their tenant services',
                'txtRoleNote' => 'Can manage their own tenant services and bookings',
                'bitActive' => 1,
                'txtGUID' => $this->db->query('SELECT UUID() as guid')->getRow()->guid
            ],
            [
                'intRoleID' => 4,
                'txtRoleName' => 'Tenant Staff',
                'txtRoleDesc' => 'Staff member of a tenant with limited access',
                'txtRoleNote' => 'Can manage bookings and basic service operations',
                'bitActive' => 1,
                'txtGUID' => $this->db->query('SELECT UUID() as guid')->getRow()->guid
            ],
            [
                'intRoleID' => 5,
                'txtRoleName' => 'Customer',
                'txtRoleDesc' => 'End user who can make bookings',
                'txtRoleNote' => 'Regular user with booking capabilities',
                'bitActive' => 1,
                'txtGUID' => $this->db->query('SELECT UUID() as guid')->getRow()->guid
            ],
            [
                'intRoleID' => 6,
                'txtRoleName' => 'Guest',
                'txtRoleDesc' => 'Unregistered user with view-only access',
                'txtRoleNote' => 'Limited to viewing public information',
                'bitActive' => 1,
                'txtGUID' => $this->db->query('SELECT UUID() as guid')->getRow()->guid
            ],
        ];

        $this->db->table('m_role')->insertBatch($data);
    }
}
