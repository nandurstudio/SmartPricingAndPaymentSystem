<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateCustomerMenuPermissions extends Migration
{
    public function up()
    {
        // First clear any existing menu permissions for customer role
        $this->db->query("DELETE FROM m_role_menu WHERE intRoleID = 5");

        // Insert menu permissions for customer role
        // This includes only the essential menus that customers should see:
        // - Dashboard
        // - Booking Management section
        // - Settings
        $this->db->query("INSERT INTO m_role_menu (intRoleID, intMenuID, bitActive, txtCreatedBy, dtmCreatedDate) 
            SELECT 5, intMenuID, 1, 'system', NOW() FROM m_menu 
            WHERE txtMenuLink IN (
                '/dashboard',
                '/bookings',
                '/booking-calendar',
                '/settings/profile'
            )
            OR txtMenuName IN (
                'Dashboard',
                'Booking Management',
                'Settings'
            )"
        );
    }

    public function down()
    {
        // Remove all menu permissions for customer role
        $this->db->query("DELETE FROM m_role_menu WHERE intRoleID = 5");
    }
}
