<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateTableNames extends Migration
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

        // Handle transaction tables first
        if (in_array('m_booking_custom_values', $tables)) {
            $this->dropForeignKeyIfExists('m_booking_custom_values', 'm_booking_custom_values_booking_id_foreign');
        }
        if (in_array('m_payments', $tables)) {
            $this->dropForeignKeyIfExists('m_payments', 'm_payments_booking_id_foreign');
        }

        // Rename transaction tables
        if (in_array('m_bookings', $tables)) {
            $this->db->query('RENAME TABLE m_bookings TO tr_bookings');
        }
        if (in_array('m_payments', $tables)) {
            $this->db->query('RENAME TABLE m_payments TO tr_payments');
        }
        if (in_array('m_booking_custom_values', $tables)) {
            $this->db->query('RENAME TABLE m_booking_custom_values TO tr_booking_custom_values');
        }
        if (in_array('m_notifications', $tables)) {
            $this->db->query('RENAME TABLE m_notifications TO tr_notifications');
        }

        // Add new foreign key constraints
        if (in_array('tr_booking_custom_values', $tables)) {
            // First check if the constraint exists
            $constraint = $this->db->query("SELECT CONSTRAINT_NAME FROM information_schema.TABLE_CONSTRAINTS 
                WHERE TABLE_SCHEMA = DATABASE() 
                AND TABLE_NAME = 'tr_booking_custom_values' 
                AND CONSTRAINT_NAME = 'tr_booking_custom_values_booking_id_foreign'")->getResult();
                
            if (empty($constraint)) {
                $this->db->query('ALTER TABLE tr_booking_custom_values ADD CONSTRAINT tr_booking_custom_values_booking_id_foreign FOREIGN KEY (booking_id) REFERENCES tr_bookings(id) ON DELETE CASCADE ON UPDATE CASCADE');
            }
        }
        if (in_array('tr_payments', $tables)) {
            // First check if the constraint exists
            $constraint = $this->db->query("SELECT CONSTRAINT_NAME FROM information_schema.TABLE_CONSTRAINTS 
                WHERE TABLE_SCHEMA = DATABASE() 
                AND TABLE_NAME = 'tr_payments' 
                AND CONSTRAINT_NAME = 'tr_payments_booking_id_foreign'")->getResult();
                
            if (empty($constraint)) {
                $this->db->query('ALTER TABLE tr_payments ADD CONSTRAINT tr_payments_booking_id_foreign FOREIGN KEY (booking_id) REFERENCES tr_bookings(id) ON DELETE CASCADE ON UPDATE CASCADE');
            }
        }

        // Handle redundant tables
        // First, m_tenants vs m_tenant
        if (in_array('m_tenants', $tables) && in_array('m_tenant', $tables)) {
            // Copy unique data from m_tenants to m_tenant
            $this->db->query('INSERT IGNORE INTO m_tenant (id, guid, name, service_type_id, owner_id, domain, settings, subscription_plan, status, is_active, created_by, created_date, updated_by, updated_date)
                SELECT id, guid, name, service_type_id, owner_id, domain, settings, subscription_plan, status, is_active, created_by, created_date, updated_by, updated_date
                FROM m_tenants');

            // Drop m_tenants table
            $this->db->query('DROP TABLE m_tenants');
        }

        // Then m_service_types vs m_service_type
        if (in_array('m_service_types', $tables) && in_array('m_service_type', $tables)) {
            // Copy unique data
            $this->db->query('INSERT IGNORE INTO m_service_type (id, name, description, created_by, created_date, updated_by, updated_date)
                SELECT id, name, description, created_by, created_date, updated_by, updated_date
                FROM m_service_types');

            // Drop m_service_types table
            $this->db->query('DROP TABLE m_service_types');
        }
    }

    public function down()
    {
        $tables = $this->db->listTables();

        // First drop foreign key constraints if they exist
        if (in_array('tr_booking_custom_values', $tables)) {
            $this->dropForeignKeyIfExists('tr_booking_custom_values', 'tr_booking_custom_values_booking_id_foreign');
        }
        if (in_array('tr_payments', $tables)) {
            $this->dropForeignKeyIfExists('tr_payments', 'tr_payments_booking_id_foreign');
        }

        // Rename tables back
        if (in_array('tr_bookings', $tables)) {
            $this->db->query('RENAME TABLE tr_bookings TO m_bookings');
        }
        if (in_array('tr_payments', $tables)) {
            $this->db->query('RENAME TABLE tr_payments TO m_payments');
        }
        if (in_array('tr_booking_custom_values', $tables)) {
            $this->db->query('RENAME TABLE tr_booking_custom_values TO m_booking_custom_values');
        }
        if (in_array('tr_notifications', $tables)) {
            $this->db->query('RENAME TABLE tr_notifications TO m_notifications');
        }

        // Add back original foreign key constraints
        if (in_array('m_booking_custom_values', $tables)) {
            $this->db->query('ALTER TABLE m_booking_custom_values ADD CONSTRAINT m_booking_custom_values_booking_id_foreign FOREIGN KEY (booking_id) REFERENCES m_bookings(id) ON DELETE CASCADE ON UPDATE CASCADE');
        }
        if (in_array('m_payments', $tables)) {
            $this->db->query('ALTER TABLE m_payments ADD CONSTRAINT m_payments_booking_id_foreign FOREIGN KEY (booking_id) REFERENCES m_bookings(id) ON DELETE CASCADE ON UPDATE CASCADE');
        }
    }
}
