<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class InitialSetupSeeder extends Seeder
{    
    public function run()
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
    }
}
