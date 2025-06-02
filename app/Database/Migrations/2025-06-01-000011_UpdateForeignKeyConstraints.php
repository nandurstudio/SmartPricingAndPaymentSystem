<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateForeignKeyConstraints extends Migration
{
    private function tableExists($tableName)
    {
        return $this->db->query("SHOW TABLES LIKE '$tableName'")->getNumRows() > 0;
    }

    private function constraintExists($table, $column, $referencedTable)
    {
        return $this->db->query("SELECT CONSTRAINT_NAME FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
            WHERE TABLE_SCHEMA = DATABASE() 
            AND TABLE_NAME = '$table' 
            AND COLUMN_NAME = '$column' 
            AND REFERENCED_TABLE_NAME = '$referencedTable'")->getNumRows() > 0;
    }

    public function up()
    {
        // Check if constraints already exist
        $constraints = [
            'm_tenants' => [
                'intServiceTypeID' => [
                    'referenced_table' => 'm_service_types',
                    'referenced_column' => 'intServiceTypeID',
                    'constraint_name' => 'fk_tenant_service_type',
                    'on_delete' => 'SET NULL',
                    'on_update' => 'CASCADE'
                ],
                'intOwnerID' => [
                    'referenced_table' => 'm_user',
                    'referenced_column' => 'intUserID',
                    'constraint_name' => 'fk_tenant_owner',
                    'on_delete' => 'CASCADE', 
                    'on_update' => 'CASCADE'
                ]
            ]
        ];

        foreach ($constraints as $table => $columns) {
            foreach ($columns as $column => $config) {
                if (!$this->constraintExists($table, $column, $config['referenced_table'])) {
                    $this->db->query("ALTER TABLE $table ADD CONSTRAINT {$config['constraint_name']} 
                        FOREIGN KEY ($column) REFERENCES {$config['referenced_table']}({$config['referenced_column']}) 
                        ON DELETE {$config['on_delete']} ON UPDATE {$config['on_update']}");
                }
            }
        }
    }

    public function down()
    {
        // Drop all added foreign key constraints
        $constraints = [
            ['table' => 'm_tenants', 'constraint' => 'fk_tenant_service_type'],
            ['table' => 'm_tenants', 'constraint' => 'fk_tenant_owner']
        ];

        foreach ($constraints as $constraint) {
            if ($this->constraintExists($constraint['table'], '', $constraint['constraint'])) {
                $this->db->query("ALTER TABLE {$constraint['table']} DROP FOREIGN KEY {$constraint['constraint']}");
            }
        }
    }
}
