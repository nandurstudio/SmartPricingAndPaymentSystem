<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class UpdateMenuCommand extends BaseCommand
{
    protected $group       = 'Database';
    protected $name        = 'db:update-menu';
    protected $description = 'Update menu structure without losing existing data';

    public function run(array $params)
    {
        $db = \Config\Database::connect();

        try {
            CLI::write("\n[UPDATE MENU] Updating menu structure...", 'yellow');
            
            // Start transaction
            $db->transStart();

            // Backup existing permissions
            $db->query("CREATE TEMPORARY TABLE IF NOT EXISTS temp_role_menu SELECT * FROM m_role_menu");

            // Ensure roles exist
            $db->query("INSERT IGNORE INTO m_role (intRoleID, txtRoleName, txtRoleDesc, bitStatus, txtGUID) VALUES
                (1, 'Administrator', 'Full access to system', 1, 'role_admin'),
                (2, 'User', 'Standard user access', 1, 'role_user'),
                (3, 'Tenant Owner', 'Business owner access', 1, 'role_tenant')
            ");

            // Clear existing menu data
            $db->query("DELETE FROM m_role_menu");
            $db->query("DELETE FROM m_menu");

            // Insert new menu structure
            $db->query("INSERT INTO m_menu (txtMenuName, txtMenuLink, txtIcon, intParentID, intSortOrder, bitActive) VALUES
                ('Dashboard', '/dashboard', 'activity', NULL, 1, 1),
                ('Master Data', NULL, 'database', NULL, 2, 1),
                ('Users', '/users', 'users', 2, 1, 1),
                ('Roles', '/roles', 'shield', 2, 2, 1),
                ('Service Types', '/service-types', 'grid', 2, 3, 1),
                ('Categories', '/categories', 'folder', 2, 4, 1),
                ('Tenant', NULL, 'home', NULL, 3, 1),
                ('Tenant List', '/tenants', 'list', 7, 1, 1),
                ('Tenant Services', '/tenant-services', 'briefcase', 7, 2, 1),
                ('Services', NULL, 'package', NULL, 4, 1),
                ('Service List', '/services', 'list', 10, 1, 1),
                ('Schedules', '/schedules', 'calendar', 10, 2, 1),
                ('Service Attributes', '/service-attributes', 'sliders', 10, 3, 1),
                ('Bookings', NULL, 'book-open', NULL, 5, 1),
                ('Booking List', '/bookings', 'bookmark', 14, 1, 1),
                ('Calendar View', '/booking-calendar', 'calendar', 14, 2, 1),
                ('Reports', NULL, 'bar-chart-2', NULL, 6, 1),
                ('Booking Reports', '/reports/bookings', 'file-text', 17, 1, 1),
                ('Revenue Reports', '/reports/revenue', 'dollar-sign', 17, 2, 1),
                ('Usage Reports', '/reports/usage', 'trending-up', 17, 3, 1),
                ('Settings', NULL, 'settings', NULL, 7, 1),
                ('System Settings', '/settings/system', 'tool', 20, 1, 1),
                ('User Profile', '/settings/profile', 'user', 20, 2, 1)
            ");

            // Map menus to roles
            // Administrator (Full Access)
            $db->query("INSERT INTO m_role_menu (intRoleID, intMenuID) SELECT 1, intMenuID FROM m_menu");

            // Standard User (Limited Access)
            $db->query("INSERT INTO m_role_menu (intRoleID, intMenuID) 
                SELECT 2, intMenuID FROM m_menu 
                WHERE txtMenuLink IN ('/dashboard', '/bookings', '/booking-calendar', '/settings/profile')");

            // Tenant Owner (Business Access)
            $db->query("INSERT INTO m_role_menu (intRoleID, intMenuID) 
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

            // Commit transaction
            $db->transComplete();

            if ($db->transStatus() === false) {
                throw new \Exception('Transaction failed');
            }

            CLI::write('Menu structure updated successfully!', 'green');

        } catch (\Exception $e) {
            CLI::error('Error: ' . $e->getMessage());
            
            // Rollback transaction
            if ($db->transStatus() === false) {
                $db->transRollback();
            }

            // Try to restore backup if available
            try {
                if ($db->tableExists('temp_role_menu')) {
                    $db->query("INSERT INTO m_role_menu SELECT * FROM temp_role_menu");
                }
            } catch (\Exception $e) {
                CLI::error('Error during rollback: ' . $e->getMessage());
            }
        } finally {
            // Clean up
            try {
                $db->query("DROP TEMPORARY TABLE IF EXISTS temp_role_menu");
            } catch (\Exception $e) {
                CLI::error('Error cleaning up: ' . $e->getMessage());
            }
        }
    }
}
