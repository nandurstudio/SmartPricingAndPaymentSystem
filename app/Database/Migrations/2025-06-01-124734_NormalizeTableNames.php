<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class NormalizeTableNames extends Migration
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

    private function addForeignKeyIfNotExists($table, $constraint, $column, $refTable, $refColumn)
    {
        $db = db_connect();
        $query = $db->query("SELECT CONSTRAINT_NAME FROM information_schema.TABLE_CONSTRAINTS 
            WHERE TABLE_SCHEMA = DATABASE() 
            AND TABLE_NAME = '{$table}' 
            AND CONSTRAINT_NAME = '{$constraint}'");
        
        if ($query->getNumRows() == 0) {
            $this->db->query("ALTER TABLE {$table} ADD CONSTRAINT {$constraint} 
                FOREIGN KEY ({$column}) REFERENCES {$refTable}({$refColumn}) 
                ON DELETE CASCADE ON UPDATE CASCADE");
        }
    }

    public function up()
    {
        $tables = $this->db->listTables();

        // 1. Handle transaction tables
        $transactionTables = [
            ['old' => 'm_bookings', 'new' => 'tr_bookings'],
            ['old' => 'm_payments', 'new' => 'tr_payments'],
            ['old' => 'm_booking_custom_values', 'new' => 'tr_booking_custom_values'],
            ['old' => 'm_notifications', 'new' => 'tr_notifications']
        ];

        foreach ($transactionTables as $table) {
            if (in_array($table['old'], $tables)) {
                // Drop foreign keys if they exist
                if ($table['old'] === 'm_booking_custom_values') {
                    $this->dropForeignKeyIfExists('m_booking_custom_values', 'm_booking_custom_values_booking_id_foreign');
                } elseif ($table['old'] === 'm_payments') {
                    $this->dropForeignKeyIfExists('m_payments', 'm_payments_booking_id_foreign');
                }

                // Rename table
                $this->db->query("RENAME TABLE {$table['old']} TO {$table['new']}");
            }
        }

        // Add new foreign key constraints
        if (in_array('tr_booking_custom_values', $tables)) {
            $this->addForeignKeyIfNotExists(
                'tr_booking_custom_values',
                'tr_booking_custom_values_booking_id_foreign',
                'booking_id',
                'tr_bookings',
                'id'
            );
        }
        if (in_array('tr_payments', $tables)) {
            $this->addForeignKeyIfNotExists(
                'tr_payments',
                'tr_payments_booking_id_foreign',
                'booking_id',
                'tr_bookings',
                'id'
            );
        }

        // 2. Handle redundant tables
        if (in_array('m_tenants', $tables) && in_array('m_tenant', $tables)) {
            // Copy unique data from m_tenants to m_tenant
            $this->db->query('INSERT IGNORE INTO m_tenant (id, guid, name, service_type_id, owner_id, domain, settings, subscription_plan, status, is_active, created_by, created_date, updated_by, updated_date)
                SELECT id, guid, name, service_type_id, owner_id, domain, settings, subscription_plan, status, is_active, created_by, created_date, updated_by, updated_date
                FROM m_tenants');

            // Drop m_tenants table
            $this->db->query('DROP TABLE IF EXISTS m_tenants');
        }

        if (in_array('m_service_types', $tables) && in_array('m_service_type', $tables)) {
            // Copy unique data
            $this->db->query('INSERT IGNORE INTO m_service_type (id, name, description, created_by, created_date, updated_by, updated_date)
                SELECT id, name, description, created_by, created_date, updated_by, updated_date
                FROM m_service_types');

            // Drop m_service_types table
            $this->db->query('DROP TABLE IF EXISTS m_service_types');
        }
    }

    public function down()
    {
        $tables = $this->db->listTables();

        // 1. Restore transaction tables
        $transactionTables = [
            ['old' => 'tr_bookings', 'new' => 'm_bookings'],
            ['old' => 'tr_payments', 'new' => 'm_payments'],
            ['old' => 'tr_booking_custom_values', 'new' => 'm_booking_custom_values'],
            ['old' => 'tr_notifications', 'new' => 'm_notifications']
        ];

        foreach ($transactionTables as $table) {
            if (in_array($table['old'], $tables)) {
                // Drop foreign keys if they exist
                if ($table['old'] === 'tr_booking_custom_values') {
                    $this->dropForeignKeyIfExists('tr_booking_custom_values', 'tr_booking_custom_values_booking_id_foreign');
                } elseif ($table['old'] === 'tr_payments') {
                    $this->dropForeignKeyIfExists('tr_payments', 'tr_payments_booking_id_foreign');
                }

                // Rename table
                $this->db->query("RENAME TABLE {$table['old']} TO {$table['new']}");
            }
        }

        // Add back original foreign key constraints
        if (in_array('m_booking_custom_values', $tables)) {
            $this->addForeignKeyIfNotExists(
                'm_booking_custom_values',
                'm_booking_custom_values_booking_id_foreign',
                'booking_id',
                'm_bookings',
                'id'
            );
        }
        if (in_array('m_payments', $tables)) {
            $this->addForeignKeyIfNotExists(
                'm_payments',
                'm_payments_booking_id_foreign',
                'booking_id',
                'm_bookings',
                'id'
            );
        }
    }
}
