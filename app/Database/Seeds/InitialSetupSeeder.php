<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class InitialSetupSeeder extends Seeder
{    public function run()
    {
        // Disable foreign key checks
        $this->db->query('SET FOREIGN_KEY_CHECKS = 0');

        // Run the base system seeders
        $this->call('RoleSeeder');
        $this->call('MenuSeeder');
        $this->call('RoleMenuSeeder');
        
        // Run the booking system seeders
        $this->call('ServiceTypeSeeder');
        $this->call('NotificationTemplateSeeder');
        $this->call('TestDataSeeder');

        // Re-enable foreign key checks
        $this->db->query('SET FOREIGN_KEY_CHECKS = 1');
        
        // Add default super admin user
        $data = [
            'intRoleID' => 1, // Super Administrator
            'txtUserName' => 'admin',
            'txtFullName' => 'System Administrator',
            'txtEmail' => 'admin@system.com',
            'txtPassword' => password_hash('admin123', PASSWORD_DEFAULT),
            'bitActive' => 1,
            'txtGUID' => $this->db->query('SELECT UUID() as guid')->getRow()->guid,
            'dtmJoinDate' => date('Y-m-d H:i:s'),
            'dtmCreatedDate' => date('Y-m-d H:i:s')
        ];

        $this->db->table('m_user')->insert($data);
    }
}
