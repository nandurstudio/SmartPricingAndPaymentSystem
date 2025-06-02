<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddTenantMenuForCustomers extends Migration
{
    public function up()
    {
        // Add new menu items for tenant management
        $this->db->query("INSERT INTO m_menu (txtGUID, txtMenuName, txtMenuLink, txtIcon, intParentID, intSortOrder, bitActive, txtCreatedBy, dtmCreatedDate) VALUES 
            (UUID(), 'My Tenants', '/my-tenants', 'briefcase', NULL, 2, 1, 'system', NOW()),
            (UUID(), 'Create Tenant', '/onboarding/setup-tenant', 'plus-square', NULL, 3, 1, 'system', NOW())
        ");

        // Get the menu IDs we just inserted
        $myTenantsMenu = $this->db->query("SELECT intMenuID FROM m_menu WHERE txtMenuLink = '/my-tenants'")->getRow();
        $createTenantMenu = $this->db->query("SELECT intMenuID FROM m_menu WHERE txtMenuLink = '/onboarding/setup-tenant'")->getRow();

        // Add menu permissions for customer role (role ID 5)
        if ($myTenantsMenu && $createTenantMenu) {
            $this->db->query("INSERT INTO m_role_menu (intRoleID, intMenuID, bitActive, txtCreatedBy, dtmCreatedDate) VALUES 
                (5, ?, 1, 'system', NOW()),
                (5, ?, 1, 'system', NOW())",
                [$myTenantsMenu->intMenuID, $createTenantMenu->intMenuID]
            );
        }
    }

    public function down()
    {
        // Remove menu permissions first
        $this->db->query("DELETE FROM m_role_menu WHERE intRoleID = 5 AND intMenuID IN (
            SELECT intMenuID FROM m_menu WHERE txtMenuLink IN ('/my-tenants', '/onboarding/setup-tenant')
        )");

        // Then remove menu items
        $this->db->query("DELETE FROM m_menu WHERE txtMenuLink IN ('/my-tenants', '/onboarding/setup-tenant')");
    }
}
