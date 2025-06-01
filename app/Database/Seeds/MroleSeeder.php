<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class MroleSeeder extends Seeder
{
    public function run()
    {
        // Delete existing records and reset auto increment
        $this->db->query('SET FOREIGN_KEY_CHECKS=0');
        $this->db->table('m_role')->emptyTable();
        $this->db->query('ALTER TABLE m_role AUTO_INCREMENT = 1');
        $this->db->query('SET FOREIGN_KEY_CHECKS=1');
        
        $currentDateTime = date('Y-m-d H:i:s');
        
        $data = [
            [
                'txtRoleName'        => 'Super Administrator',
                'txtRoleDesc'        => 'Full access to all system features',
                'txtRoleNote'        => 'Full system administrator role with unrestricted access',
                'bitStatus'          => 1,
                'txtCreatedBy'       => 'Seeder',
                'dtmCreatedDate'     => $currentDateTime,
                'txtLastUpdatedBy'   => 'Seeder',
                'dtmLastUpdatedDate' => $currentDateTime,
                'txtGUID'            => uniqid('role_', true),
            ],
            [
                'txtRoleName'        => 'Administrator',
                'txtRoleDesc'        => 'Limited administrative access',
                'txtRoleNote'        => 'Administrative role with some restrictions',
                'bitStatus'          => 1,
                'txtCreatedBy'       => 'Seeder',
                'dtmCreatedDate'     => $currentDateTime,
                'txtLastUpdatedBy'   => 'Seeder',
                'dtmLastUpdatedDate' => $currentDateTime,
                'txtGUID'            => uniqid('role_', true),
            ],
            [
                'txtRoleName'        => 'Tenant Owner',
                'txtRoleDesc'        => 'Business owner access level',
                'txtRoleNote'        => 'Full access to tenant-specific features',
                'bitStatus'          => 1,
                'txtCreatedBy'       => 'Seeder',
                'dtmCreatedDate'     => $currentDateTime,
                'txtLastUpdatedBy'   => 'Seeder',
                'dtmLastUpdatedDate' => $currentDateTime,
                'txtGUID'            => uniqid('role_', true),
            ],
            [
                'txtRoleName'        => 'Tenant Staff',
                'txtRoleDesc'        => 'Operational staff access level',
                'txtRoleNote'        => 'Limited access to tenant operations',
                'bitStatus'          => 1,
                'txtCreatedBy'       => 'Seeder',
                'dtmCreatedDate'     => $currentDateTime,
                'txtLastUpdatedBy'   => 'Seeder',
                'dtmLastUpdatedDate' => $currentDateTime,
                'txtGUID'            => uniqid('role_', true),
            ],
            [
                'txtRoleName'        => 'Customer',
                'txtRoleDesc'        => 'End-user access level',
                'txtRoleNote'        => 'Access to booking and user features',
                'bitStatus'          => 1,
                'txtCreatedBy'       => 'Seeder',
                'dtmCreatedDate'     => $currentDateTime,
                'txtLastUpdatedBy'   => 'Seeder',
                'dtmLastUpdatedDate' => $currentDateTime,
                'txtGUID'            => uniqid('role_', true),
            ],
            [
                'txtRoleName'        => 'Guest',
                'txtRoleDesc'        => 'Limited view access',
                'txtRoleNote'        => 'View-only access to public features',
                'bitStatus'          => 1,
                'txtCreatedBy'       => 'Seeder',
                'dtmCreatedDate'     => $currentDateTime,
                'txtLastUpdatedBy'   => 'Seeder',
                'dtmLastUpdatedDate' => $currentDateTime,
                'txtGUID'            => uniqid('role_', true),
            ],
        ];

        // Using insertBatch for better performance with multiple records
        $this->db->table('m_role')->insertBatch($data);
    }
}
