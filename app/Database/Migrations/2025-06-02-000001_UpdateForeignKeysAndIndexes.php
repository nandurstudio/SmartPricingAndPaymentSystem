<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateForeignKeysAndIndexes extends Migration
{
    public function up()
    {        
        $this->db->query('SET FOREIGN_KEY_CHECKS = 0');

        // First, drop any existing constraints to avoid duplicates
        $this->dropAllConstraints();

        // Add foreign keys for m_tenants relationships
        $this->addForeignKeyIfNotExists('m_tenants', 'fk_tenant_service_type', 
            'FOREIGN KEY (service_type_id) REFERENCES m_service_types(id) ON DELETE RESTRICT ON UPDATE CASCADE');
        $this->addForeignKeyIfNotExists('m_tenants', 'fk_tenant_owner', 
            'FOREIGN KEY (owner_id) REFERENCES m_user(intUserID) ON DELETE RESTRICT ON UPDATE CASCADE');

        // Add foreign keys for tenant isolation in user table
        $this->addForeignKeyIfNotExists('m_user', 'fk_user_tenant', 
            'FOREIGN KEY (tenant_id) REFERENCES m_tenants(id) ON DELETE SET NULL ON UPDATE CASCADE');
        $this->addForeignKeyIfNotExists('m_user', 'fk_user_role', 
            'FOREIGN KEY (intRoleID) REFERENCES m_role(intRoleID) ON DELETE RESTRICT ON UPDATE CASCADE');
        
        // Add foreign keys for m_services
        $this->addForeignKeyIfNotExists('m_services', 'fk_service_tenant', 
            'FOREIGN KEY (tenant_id) REFERENCES m_tenants(id) ON DELETE CASCADE ON UPDATE CASCADE');
        $this->addForeignKeyIfNotExists('m_services', 'fk_service_type', 
            'FOREIGN KEY (service_type_id) REFERENCES m_service_types(id) ON DELETE RESTRICT ON UPDATE CASCADE');

        // Add foreign keys for schedules
        $this->addForeignKeyIfNotExists('m_schedules', 'fk_schedule_service', 
            'FOREIGN KEY (service_id) REFERENCES m_services(id) ON DELETE CASCADE ON UPDATE CASCADE');

        // Add foreign keys for tr_bookings
        $this->addForeignKeyIfNotExists('tr_bookings', 'fk_booking_tenant', 
            'FOREIGN KEY (tenant_id) REFERENCES m_tenants(id) ON DELETE CASCADE ON UPDATE CASCADE');
        $this->addForeignKeyIfNotExists('tr_bookings', 'fk_booking_user', 
            'FOREIGN KEY (user_id) REFERENCES m_user(intUserID) ON DELETE RESTRICT ON UPDATE CASCADE');
        $this->addForeignKeyIfNotExists('tr_bookings', 'fk_booking_service', 
            'FOREIGN KEY (service_id) REFERENCES m_services(id) ON DELETE RESTRICT ON UPDATE CASCADE');
        $this->addForeignKeyIfNotExists('tr_bookings', 'fk_booking_schedule', 
            'FOREIGN KEY (schedule_id) REFERENCES m_schedules(id) ON DELETE RESTRICT ON UPDATE CASCADE');

        // Add foreign keys for tr_service_custom_values
        $this->addForeignKeyIfNotExists('tr_service_custom_values', 'fk_custom_value_booking', 
            'FOREIGN KEY (booking_id) REFERENCES tr_bookings(id) ON DELETE CASCADE ON UPDATE CASCADE');
        $this->addForeignKeyIfNotExists('tr_service_custom_values', 'fk_custom_value_service', 
            'FOREIGN KEY (service_id) REFERENCES m_services(id) ON DELETE CASCADE ON UPDATE CASCADE');

        // Add foreign keys for tr_audit_log
        $this->addForeignKeyIfNotExists('tr_audit_log', 'fk_audit_tenant', 
            'FOREIGN KEY (tenant_id) REFERENCES m_tenants(id) ON DELETE CASCADE ON UPDATE CASCADE');
        $this->addForeignKeyIfNotExists('tr_audit_log', 'fk_audit_user', 
            'FOREIGN KEY (user_id) REFERENCES m_user(intUserID) ON DELETE SET NULL ON UPDATE CASCADE');

        // Add foreign keys for m_role_menu
        $this->addForeignKeyIfNotExists('m_role_menu', 'fk_role_menu_role',
            'FOREIGN KEY (intRoleID) REFERENCES m_role(intRoleID) ON DELETE CASCADE ON UPDATE CASCADE');
        $this->addForeignKeyIfNotExists('m_role_menu', 'fk_role_menu_menu',
            'FOREIGN KEY (intMenuID) REFERENCES m_menu(intMenuID) ON DELETE CASCADE ON UPDATE CASCADE');

        $this->db->query('SET FOREIGN_KEY_CHECKS = 1');

        // Add additional indexes for commonly queried fields
        // Tenant-related indexes
        $this->createIndexIfNotExists('m_tenants', 'idx_tenant_status', 'status');
        $this->createIndexIfNotExists('m_tenants', 'idx_tenant_subscription', 'subscription_plan');
        $this->createIndexIfNotExists('m_tenants', 'idx_tenant_type_status', 'service_type_id, status');
        $this->createIndexIfNotExists('m_tenants', 'idx_tenant_owner', 'owner_id');

        // Service-related indexes
        $this->createIndexIfNotExists('m_services', 'idx_service_active', 'is_active');
        $this->createIndexIfNotExists('m_services', 'idx_service_tenant', 'tenant_id, is_active');
        $this->createIndexIfNotExists('m_services', 'idx_service_type', 'service_type_id');
        $this->createIndexIfNotExists('m_services', 'idx_service_category', 'category');

        // Schedule-related indexes
        $this->createIndexIfNotExists('m_schedules', 'idx_schedule_dates', 'start_time, end_time');
        $this->createIndexIfNotExists('m_schedules', 'idx_schedule_service', 'service_id, is_available');
        $this->createIndexIfNotExists('m_schedules', 'idx_schedule_bookings', 'current_bookings, max_bookings');

        // Booking-related indexes
        $this->createIndexIfNotExists('tr_bookings', 'idx_booking_status', 'status');
        $this->createIndexIfNotExists('tr_bookings', 'idx_booking_date', 'booking_date');
        $this->createIndexIfNotExists('tr_bookings', 'idx_booking_tenant_status', 'tenant_id, status');
        $this->createIndexIfNotExists('tr_bookings', 'idx_booking_service_date', 'service_id, booking_date');
        $this->createIndexIfNotExists('tr_bookings', 'idx_booking_user', 'user_id');

        // User-related indexes
        $this->createIndexIfNotExists('m_user', 'idx_user_tenant', 'tenant_id');
        $this->createIndexIfNotExists('m_user', 'idx_user_role', 'intRoleID');
        $this->createIndexIfNotExists('m_user', 'idx_user_email', 'txtEmail');
        $this->createIndexIfNotExists('m_user', 'idx_user_status', 'bitActive');

        // Menu-related indexes
        $this->createIndexIfNotExists('m_menu', 'idx_menu_parent', 'intParentID');
        $this->createIndexIfNotExists('m_menu', 'idx_menu_active', 'bitActive');
    }

    public function down()
    {
        // Remove indexes first
        $indexesToDrop = [
            'm_tenants' => [
                'idx_tenant_status',
                'idx_tenant_subscription',
                'idx_tenant_type_status',
                'idx_tenant_owner'
            ],
            'm_services' => [
                'idx_service_active',
                'idx_service_tenant',
                'idx_service_type',
                'idx_service_category'
            ],
            'm_schedules' => [
                'idx_schedule_dates',
                'idx_schedule_service',
                'idx_schedule_bookings'
            ],
            'tr_bookings' => [
                'idx_booking_status',
                'idx_booking_date',
                'idx_booking_tenant_status',
                'idx_booking_service_date',
                'idx_booking_user'
            ],
            'm_user' => [
                'idx_user_tenant',
                'idx_user_role',
                'idx_user_email',
                'idx_user_status'
            ],
            'm_menu' => [
                'idx_menu_parent',
                'idx_menu_active'
            ]
        ];

        foreach ($indexesToDrop as $table => $indexes) {
            foreach ($indexes as $index) {
                $this->db->query("DROP INDEX IF EXISTS {$index} ON {$table}");
            }
        }

        // Remove foreign key constraints
        $this->db->query('SET FOREIGN_KEY_CHECKS = 0');

        $constraintsToDrop = [
            'm_tenants' => ['fk_tenant_service_type', 'fk_tenant_owner'],
            'm_user' => ['fk_user_tenant', 'fk_user_role'],
            'm_services' => ['fk_service_tenant', 'fk_service_type'],
            'm_schedules' => ['fk_schedule_service'],
            'tr_bookings' => ['fk_booking_tenant', 'fk_booking_user', 'fk_booking_service', 'fk_booking_schedule'],
            'tr_service_custom_values' => ['fk_custom_value_booking', 'fk_custom_value_service'],
            'tr_audit_log' => ['fk_audit_tenant', 'fk_audit_user'],
            'm_role_menu' => ['fk_role_menu_role', 'fk_role_menu_menu']
        ];

        foreach ($constraintsToDrop as $table => $constraints) {
            foreach ($constraints as $constraint) {
                try {
                    $this->db->query("ALTER TABLE {$table} DROP FOREIGN KEY {$constraint}");
                } catch (\Exception $e) {
                    // Ignore errors for non-existent constraints
                }
            }
        }

        $this->db->query('SET FOREIGN_KEY_CHECKS = 1');
    }

    private function addForeignKeyIfNotExists(string $table, string $constraintName, string $definition) {
        try {
            $this->db->query("ALTER TABLE {$table} ADD CONSTRAINT {$constraintName} {$definition}");
        } catch (\Exception $e) {
            // If error is not about duplicate key, re-throw it
            if (!str_contains($e->getMessage(), 'Duplicate key name')) {
                throw $e;
            }
        }
    }

    private function indexExists(string $table, string $indexName): bool
    {
        try {
            $result = $this->db->query("SHOW INDEX FROM {$table} WHERE Key_name = ?", [$indexName])->getResult();
            return count($result) > 0;
        } catch (\Exception $e) {
            return false;
        }
    }

    private function createIndexIfNotExists(string $table, string $indexName, string $columns)
    {
        if (!$this->indexExists($table, $indexName)) {
            try {
                $this->db->query("CREATE INDEX {$indexName} ON {$table}({$columns})");
            } catch (\Exception $e) {
                // If error is not about duplicate index, re-throw it
                if (!str_contains($e->getMessage(), 'Duplicate key name')) {
                    throw $e;
                }
            }
        }
    }

    private function dropAllConstraints()
    {
        $tables = [
            'm_tenants',
            'm_user',
            'm_services',
            'm_schedules',
            'tr_bookings',
            'tr_service_custom_values',
            'tr_audit_log',
            'm_role_menu'
        ];

        foreach ($tables as $table) {
            // Get all foreign key constraints for the table
            $query = $this->db->query("
                SELECT CONSTRAINT_NAME 
                FROM information_schema.TABLE_CONSTRAINTS 
                WHERE CONSTRAINT_SCHEMA = DATABASE()
                AND TABLE_NAME = '{$table}'
                AND CONSTRAINT_TYPE = 'FOREIGN KEY'"
            );

            $constraints = $query->getResult();
            
            foreach ($constraints as $constraint) {
                try {
                    $this->db->query("ALTER TABLE {$table} DROP FOREIGN KEY " . $constraint->CONSTRAINT_NAME);
                } catch (\Exception $e) {
                    // Ignore errors for non-existent constraints
                }
            }
        }
    }
}
