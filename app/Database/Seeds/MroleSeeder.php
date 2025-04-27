<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class MroleSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'txtRoleName'        => 'Administrator',
                'txtRoleDesc'        => 'Full access to the system',
                'txtRoleNote'        => 'Initial system administrator role',
                'bitStatus'          => 1,
                'txtCreatedBy'       => 'system',
                'dtmCreatedDate'     => date('Y-m-d H:i:s'),
                'txtLastUpdatedBy'   => 'system',
                'dtmLastUpdatedDate' => date('Y-m-d H:i:s'),
                'txtGUID'            => uniqid('role_', true),
            ],
            [
                'txtRoleName'        => 'User',
                'txtRoleDesc'        => 'Standard user role',
                'txtRoleNote'        => 'Default user permissions',
                'bitStatus'          => 1,
                'txtCreatedBy'       => 'system',
                'dtmCreatedDate'     => date('Y-m-d H:i:s'),
                'txtLastUpdatedBy'   => 'system',
                'dtmLastUpdatedDate' => date('Y-m-d H:i:s'),
                'txtGUID'            => uniqid('role_', true),
            ],
        ];

        // Insert multiple records
        $this->db->table('mrole')->insertBatch($data);
    }
}
