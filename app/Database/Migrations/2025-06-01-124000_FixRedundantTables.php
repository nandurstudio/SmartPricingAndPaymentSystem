<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class FixRedundantTables extends Migration
{
    private function dropForeignKeyIfExists($table, $constraint)
    {
        $db = db_connect();
        $query = $db->query("SELECT CONSTRAINT_NAME FROM information_schema.TABLE_CONSTRAINTS 
            WHERE TABLE_SCHEMA = DATABASE() 
            AND TABLE_NAME = '{$table}' 
            AND CONSTRAINT_NAME = '{$constraint}'");
        
        if ($query->getNumRows() > 0) {
            $this->db->query("ALTER TABLE {$table} DROP FOREIGN KEY {$constraint}");
        }
    }

    public function up()
    {
        $tables = $this->db->listTables();

        // First, handle m_tenants vs m_tenant
        if (in_array('m_tenants', $tables) && in_array('m_tenant', $tables)) {            // Copy any missing data from m_tenants to m_tenant
            $this->db->query('INSERT IGNORE INTO m_tenant (id, guid, name, service_type_id, owner_id, domain, description, logo, theme, settings, subscription_plan, status, trial_ends_at, is_active, created_by, created_date, updated_by, updated_date)
                SELECT id, guid, name, service_type_id, owner_id, domain, NULL, NULL, NULL, settings, subscription_plan, status, NULL, is_active, created_by, created_date, updated_by, updated_date 
                FROM m_tenants');
            
            // Update foreign keys to point to m_tenant
            $foreignKeys = $this->db->query("SELECT TABLE_NAME, CONSTRAINT_NAME 
                FROM information_schema.KEY_COLUMN_USAGE 
                WHERE REFERENCED_TABLE_NAME = 'm_tenants'")->getResult();
            
            foreach ($foreignKeys as $fk) {
                $this->dropForeignKeyIfExists($fk->TABLE_NAME, $fk->CONSTRAINT_NAME);
                $this->db->query("ALTER TABLE {$fk->TABLE_NAME} ADD CONSTRAINT {$fk->CONSTRAINT_NAME} 
                    FOREIGN KEY (tenant_id) REFERENCES m_tenant(id) ON DELETE CASCADE ON UPDATE CASCADE");
            }
            
            // Drop m_tenants table
            $this->db->query('DROP TABLE m_tenants');
        }

        // Then handle m_service_types vs m_service_type
        if (in_array('m_service_types', $tables) && in_array('m_service_type', $tables)) {
            // Copy any missing data
            $this->db->query('INSERT IGNORE INTO m_service_type (id, name, description, created_date, created_by, updated_date, updated_by)
                SELECT id, name, description, created_date, created_by, updated_date, updated_by 
                FROM m_service_types');
            
            // Update foreign keys
            $foreignKeys = $this->db->query("SELECT TABLE_NAME, CONSTRAINT_NAME 
                FROM information_schema.KEY_COLUMN_USAGE 
                WHERE REFERENCED_TABLE_NAME = 'm_service_types'")->getResult();
            
            foreach ($foreignKeys as $fk) {
                $this->dropForeignKeyIfExists($fk->TABLE_NAME, $fk->CONSTRAINT_NAME);
                $this->db->query("ALTER TABLE {$fk->TABLE_NAME} ADD CONSTRAINT {$fk->CONSTRAINT_NAME} 
                    FOREIGN KEY (service_type_id) REFERENCES m_service_type(id) ON DELETE CASCADE ON UPDATE CASCADE");
            }
            
            // Drop m_service_types table
            $this->db->query('DROP TABLE m_service_types');
        }
    }

    public function down()
    {
        // No down migration provided as this would require recreating redundant tables
    }
}
