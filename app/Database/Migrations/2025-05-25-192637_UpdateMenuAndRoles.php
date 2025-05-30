<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateMenuAndRoles extends Migration
{
    public function up()
    {
        // Ensure roles exist
        $this->db->query("INSERT IGNORE INTO m_role (intRoleID, txtRoleName, txtRoleDesc, bitStatus, txtGUID) VALUES
            (1, 'Administrator', 'Full access to system', 1, 'role_admin'),
            (2, 'User', 'Standard user access', 1, 'role_user'),
            (3, 'Tenant Owner', 'Business owner access', 1, 'role_tenant')
        ");

        // Backup existing menu permissions if table exists
        // Check if m_role_menu table exists
        $tableExists = $this->db->query("SHOW TABLES LIKE 'm_role_menu'")->getNumRows() > 0;
        if ($tableExists) {
            $this->db->query("CREATE TEMPORARY TABLE IF NOT EXISTS temp_role_menu SELECT * FROM m_role_menu");
            
            // Clear existing menu data but keep the table structure
            $this->db->query("DELETE FROM m_role_menu");
            $this->db->query("DELETE FROM m_menu");
        }

        // Insert new menu structure
        $menuSql = "INSERT INTO m_menu (intMenuID, txtMenuName, txtMenuLink, txtIcon, intParentID, intSortOrder, bitActive) VALUES
            (1, 'Dashboard', '/dashboard', 'activity', NULL, 1, 1),
            (2, 'Master Data', '#', 'database', NULL, 2, 1),
            (3, 'Tenant', '#', 'home', NULL, 3, 1),
            (4, 'Services', '#', 'package', NULL, 4, 1),
            (5, 'Bookings', '#', 'book-open', NULL, 5, 1),
            (6, 'Reports', '#', 'bar-chart-2', NULL, 6, 1),
            (7, 'Settings', '#', 'settings', NULL, 7, 1)";

        $this->db->query($menuSql);

        $childMenuSql = "INSERT INTO m_menu (txtMenuName, txtMenuLink, txtIcon, intParentID, intSortOrder, bitActive) VALUES
            ('Users', '/users', 'users', 2, 1, 1),
            ('Roles', '/roles', 'shield', 2, 2, 1),
            ('Service Types', '/service-types', 'grid', 2, 3, 1),
            ('Categories', '/categories', 'folder', 2, 4, 1),
            ('Tenant List', '/tenants', 'list', 3, 1, 1),
            ('Tenant Services', '/tenant-services', 'briefcase', 3, 2, 1),
            ('Service List', '/services', 'list', 4, 1, 1),
            ('Schedules', '/schedules', 'calendar', 4, 2, 1),
            ('Service Attributes', '/service-attributes', 'sliders', 4, 3, 1),
            ('Booking List', '/bookings', 'bookmark', 5, 1, 1),
            ('Calendar View', '/booking-calendar', 'calendar', 5, 2, 1),
            ('Booking Reports', '/reports/bookings', 'file-text', 6, 1, 1),
            ('Revenue Reports', '/reports/revenue', 'dollar-sign', 6, 2, 1),
            ('Usage Reports', '/reports/usage', 'trending-up', 6, 3, 1),
            ('System Settings', '/settings/system', 'tool', 7, 1, 1),
            ('User Profile', '/settings/profile', 'user', 7, 2, 1)";

        $this->db->query($childMenuSql);

        // Super Administrator (Full Access)
        $this->db->query("INSERT INTO m_role_menu (intRoleID, intMenuID) SELECT 1, intMenuID FROM m_menu");

        // Administrator (Limited Access)
        $this->db->query("INSERT INTO m_role_menu (intRoleID, intMenuID) 
            SELECT 2, intMenuID FROM m_menu 
            WHERE txtMenuLink IN ('/dashboard', '/bookings', '/booking-calendar', '/settings/profile')");

        // Tenant Owner (Business Access)
        $this->db->query("INSERT INTO m_role_menu (intRoleID, intMenuID) 
            SELECT 3, intMenuID FROM m_menu 
            WHERE txtMenuLink IN (
                '/dashboard',
                '/tenant-services',
                '/services',
                '/schedules',
                '/service-attributes',
                '/bookings',
                '/booking-calendar',
                '/reports/bookings',
                '/reports/revenue',
                '/reports/usage',
                '/settings/profile'
            )");

        // Tenant Staff (Operational Access)
        $this->db->query("INSERT INTO m_role_menu (intRoleID, intMenuID) 
            SELECT 4, intMenuID FROM m_menu 
            WHERE txtMenuLink IN (
                '/dashboard',
                '/services',
                '/schedules',
                '/bookings',
                '/booking-calendar',
                '/settings/profile'
            )");

        // Customer (Limited Access)
        $this->db->query("INSERT INTO m_role_menu (intRoleID, intMenuID) 
            SELECT 5, intMenuID FROM m_menu 
            WHERE txtMenuLink IN (
                '/dashboard',
                '/bookings',
                '/booking-calendar',
                '/settings/profile'
            )");

        // Guest (View Only Access)
        $this->db->query("INSERT INTO m_role_menu (intRoleID, intMenuID) 
            SELECT 6, intMenuID FROM m_menu 
            WHERE txtMenuLink IN (
                '/dashboard'
            )");

        // Clean up temporary table if exists
        $this->db->query("DROP TEMPORARY TABLE IF EXISTS temp_role_menu");
    }

    public function down()
    {
        // Check if temp_role_menu temporary table exists before restoring
        $tempTableExists = $this->db->query("SHOW TABLES LIKE 'temp_role_menu'")->getNumRows() > 0;
        if ($tempTableExists) {
            // Clear the new menu data
            $this->db->query("DELETE FROM m_role_menu");
            $this->db->query("DELETE FROM m_menu");

            // Restore backed up data
            $this->db->query("INSERT INTO m_role_menu SELECT * FROM temp_role_menu");
            $this->db->query("DROP TEMPORARY TABLE temp_role_menu");
        }
        $this->db->query("DROP TEMPORARY TABLE IF EXISTS temp_role_menu");
    }
}
