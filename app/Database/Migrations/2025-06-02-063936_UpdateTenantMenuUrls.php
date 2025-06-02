<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateTenantMenuUrls extends Migration
{
    public function up()
    {
        // First update main tenant links
        $this->db->query("UPDATE m_menu SET txtMenuLink = '/tenants', txtIcon = 'briefcase' WHERE txtMenuName = 'My Tenants'");
        
        // Update Create Tenant link
        $this->db->query("UPDATE m_menu SET txtMenuLink = '/tenants/create', txtIcon = 'plus-square' WHERE txtMenuName = 'Create Tenant'");
        
        // Check if My Tenants menu exists, if not create it
        $myTenantsExists = $this->db->query("SELECT COUNT(*) as count FROM m_menu WHERE txtMenuName = 'My Tenants'")->getRow()->count;
        if ($myTenantsExists == 0) {
            // Add My Tenants menu item
            $this->db->query("INSERT INTO m_menu (txtGUID, txtMenuName, txtMenuLink, txtIcon, intParentID, intSortOrder, bitActive, txtCreatedBy, dtmCreatedDate) 
                VALUES (UUID(), 'My Tenants', '/tenants', 'briefcase', NULL, 2, 1, 'system', NOW())");
        }

        // Check if tenant menu permissions exist for role 5 (Google users)
        $roleMenuExists = $this->db->query("SELECT COUNT(*) as count FROM m_role_menu WHERE intRoleID = 5 AND intMenuID IN (SELECT intMenuID FROM m_menu WHERE txtMenuName = 'My Tenants')")->getRow()->count;
        if ($roleMenuExists == 0) {
            // Add menu permissions for Google users (role 5)
            $this->db->query("INSERT INTO m_role_menu (intRoleID, intMenuID, bitActive, txtCreatedBy, dtmCreatedDate)
                SELECT 5, intMenuID, 1, 'system', NOW()
                FROM m_menu 
                WHERE txtMenuName IN ('My Tenants', 'Dashboard', 'Settings')"
            );
        }
    }

    public function down()
    {
        // Remove tenant menu permissions for Google users
        $this->db->query("DELETE FROM m_role_menu WHERE intRoleID = 5 AND intMenuID IN (
            SELECT intMenuID FROM m_menu WHERE txtMenuName = 'My Tenants'
        )");
        
        // Remove My Tenants menu if we created it
        $this->db->query("DELETE FROM m_menu WHERE txtMenuName = 'My Tenants'");
    }
}
