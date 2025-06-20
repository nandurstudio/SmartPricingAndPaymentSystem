<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class FixServiceTypesAndMenus extends Migration
{
    private function ensureRolesExist()
    {
        $roles = [
            ['intRoleID' => 1, 'txtRoleName' => 'Super Administrator', 'txtRoleDesc' => 'Full system access'],
            ['intRoleID' => 2, 'txtRoleName' => 'Administrator', 'txtRoleDesc' => 'Limited administrative access'],
            ['intRoleID' => 3, 'txtRoleName' => 'Tenant Owner', 'txtRoleDesc' => 'Business owner access'],
            ['intRoleID' => 4, 'txtRoleName' => 'Tenant Staff', 'txtRoleDesc' => 'Operational staff access'],
            ['intRoleID' => 5, 'txtRoleName' => 'Customer', 'txtRoleDesc' => 'Basic customer access'],
            ['intRoleID' => 6, 'txtRoleName' => 'Guest', 'txtRoleDesc' => 'View only access']
        ];

        foreach ($roles as $role) {
            $exists = $this->db->table('m_role')
                              ->where('intRoleID', $role['intRoleID'])
                              ->countAllResults();
            
            if ($exists == 0) {
                $this->db->table('m_role')->insert([
                    'intRoleID' => $role['intRoleID'],
                    'txtRoleName' => $role['txtRoleName'],
                    'txtRoleDesc' => $role['txtRoleDesc'],
                    'bitActive' => 1,
                    'txtGUID' => $this->db->query('SELECT UUID() as guid')->getRow()->guid,
                    'txtCreatedBy' => 'system',
                    'dtmCreatedDate' => date('Y-m-d H:i:s')
                ]);
            }
        }
    }    private function ensureMenusExist()
    {
        $menus = [
            [
                'name' => 'Pricing',
                'link' => '/pricing',
                'icon' => 'cash-stack',
                'sort' => 4,
                'roles' => [5] // Customer role
            ]
        ];

        foreach ($menus as $menu) {
            $exists = $this->db->table('m_menu')
                              ->where('txtMenuLink', $menu['link'])
                              ->countAllResults();
            
            if ($exists == 0) {
                $this->db->table('m_menu')->insert([
                    'txtGUID' => $this->db->query('SELECT UUID() as guid')->getRow()->guid,
                    'txtMenuName' => $menu['name'],
                    'txtMenuLink' => $menu['link'],
                    'txtIcon' => $menu['icon'],
                    'intParentID' => null,
                    'intSortOrder' => $menu['sort'],
                    'bitActive' => 1,
                    'txtCreatedBy' => 'system',
                    'dtmCreatedDate' => date('Y-m-d H:i:s')
                ]);

                $menuId = $this->db->table('m_menu')
                                  ->where('txtMenuLink', $menu['link'])
                                  ->get()
                                  ->getRow()
                                  ->intMenuID;

                // Add menu permissions for specified roles
                foreach ($menu['roles'] as $roleId) {
                    // Check if the role-menu mapping already exists
                    $existingMapping = $this->db->table('m_role_menu')
                                              ->where('intRoleID', $roleId)
                                              ->where('intMenuID', $menuId)
                                              ->countAllResults();

                    if ($existingMapping == 0) {
                        $this->db->table('m_role_menu')->insert([
                            'intRoleID' => $roleId,
                            'intMenuID' => $menuId,
                            'bitActive' => 1,
                            'txtCreatedBy' => 'system',
                            'dtmCreatedDate' => date('Y-m-d H:i:s')
                        ]);
                    }
                }
            }
        }
    }

    public function up()
    {
        // 1. Fix service_types data structure if needed
        try {
            $this->db->query("SELECT txtName FROM m_service_types LIMIT 1");
        } catch (\Exception $e) {
            $this->forge->addColumn('m_service_types', [
                'txtName' => [
                    'type' => 'VARCHAR',
                    'constraint' => 255,
                    'null' => false,
                    'after' => 'txtGUID'
                ]
            ]);

            try {
                $this->db->query("SELECT txtServiceTypeName FROM m_service_types LIMIT 1");
                $this->db->query('UPDATE m_service_types SET txtName = txtServiceTypeName');
                $this->forge->dropColumn('m_service_types', 'txtServiceTypeName');
            } catch (\Exception $e) {
                // Column doesn't exist, nothing to do
            }        }

        // 2. Ensure basic roles exist before adding menu permissions
        $this->ensureRolesExist();

        // 3. Now safe to add menu items and permissions
        $this->ensureMenusExist();
    }

    public function down()
    {
        // 1. Remove menu permissions for customer role menus
        $this->db->query("DELETE FROM m_role_menu WHERE intRoleID = 5 AND intMenuID IN (
            SELECT intMenuID FROM m_menu WHERE txtMenuLink IN ('/my-tenants', '/onboarding/setup-tenant')
        )");

        // 2. Remove menu items
        $this->db->query("DELETE FROM m_menu WHERE txtMenuLink IN ('/my-tenants', '/onboarding/setup-tenant')");

        // Note: We don't revert service_types column changes or remove roles as that could be destructive
    }
}
