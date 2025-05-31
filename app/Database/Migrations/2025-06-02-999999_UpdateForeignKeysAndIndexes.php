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

        // Define constraints for each table
        $tableConstraints = [
            'm_tenants' => [
                [
                    'name' => 'fk_tenant_service_type',
                    'definition' => 'FOREIGN KEY (service_type_id) REFERENCES m_service_types(id) ON DELETE RESTRICT ON UPDATE CASCADE'
                ],
                [
                    'name' => 'fk_tenant_owner',
                    'definition' => 'FOREIGN KEY (owner_id) REFERENCES m_user(intUserID) ON DELETE RESTRICT ON UPDATE CASCADE'
                ]
            ],
            'm_user' => [
                [
                    'name' => 'fk_user_tenant',
                    'definition' => 'FOREIGN KEY (tenant_id) REFERENCES m_tenants(id) ON DELETE SET NULL ON UPDATE CASCADE'
                ],
                [
                    'name' => 'fk_user_role',
                    'definition' => 'FOREIGN KEY (intRoleID) REFERENCES m_role(intRoleID) ON DELETE RESTRICT ON UPDATE CASCADE'
                ]
            ],
            'm_services' => [
                [
                    'name' => 'fk_service_tenant',
                    'definition' => 'FOREIGN KEY (tenant_id) REFERENCES m_tenants(id) ON DELETE CASCADE ON UPDATE CASCADE'
                ],
                [
                    'name' => 'fk_service_type',
                    'definition' => 'FOREIGN KEY (service_type_id) REFERENCES m_service_types(id) ON DELETE RESTRICT ON UPDATE CASCADE'
                ]
            ],
            'm_schedules' => [
                [
                    'name' => 'fk_schedule_service',
                    'definition' => 'FOREIGN KEY (service_id) REFERENCES m_services(id) ON DELETE CASCADE ON UPDATE CASCADE'
                ]
            ],
            'm_bookings' => [
                [
                    'name' => 'fk_booking_tenant',
                    'definition' => 'FOREIGN KEY (tenant_id) REFERENCES m_tenants(id) ON DELETE CASCADE ON UPDATE CASCADE'
                ],
                [
                    'name' => 'fk_booking_user',
                    'definition' => 'FOREIGN KEY (user_id) REFERENCES m_user(intUserID) ON DELETE RESTRICT ON UPDATE CASCADE'
                ],
                [
                    'name' => 'fk_booking_service',
                    'definition' => 'FOREIGN KEY (service_id) REFERENCES m_services(id) ON DELETE RESTRICT ON UPDATE CASCADE'
                ],
                [
                    'name' => 'fk_booking_schedule',
                    'definition' => 'FOREIGN KEY (schedule_id) REFERENCES m_schedules(id) ON DELETE RESTRICT ON UPDATE CASCADE'
                ]
            ],
            'm_booking_custom_values' => [
                [
                    'name' => 'fk_custom_value_booking',
                    'definition' => 'FOREIGN KEY (booking_id) REFERENCES m_bookings(id) ON DELETE CASCADE ON UPDATE CASCADE'
                ],
                [
                    'name' => 'fk_custom_value_service',
                    'definition' => 'FOREIGN KEY (service_id) REFERENCES m_services(id) ON DELETE CASCADE ON UPDATE CASCADE'
                ]
            ],
            'm_notifications' => [
                [
                    'name' => 'fk_notification_tenant',
                    'definition' => 'FOREIGN KEY (tenant_id) REFERENCES m_tenants(id) ON DELETE CASCADE ON UPDATE CASCADE'
                ],
                [
                    'name' => 'fk_notification_user',
                    'definition' => 'FOREIGN KEY (user_id) REFERENCES m_user(intUserID) ON DELETE SET NULL ON UPDATE CASCADE'
                ]
            ],
            'm_role_menu' => [
                [
                    'name' => 'fk_role_menu_role',
                    'definition' => 'FOREIGN KEY (intRoleID) REFERENCES m_role(intRoleID) ON DELETE CASCADE ON UPDATE CASCADE'
                ],
                [
                    'name' => 'fk_role_menu_menu',
                    'definition' => 'FOREIGN KEY (intMenuID) REFERENCES m_menu(intMenuID) ON DELETE CASCADE ON UPDATE CASCADE'
                ]
            ],
            'm_service_custom_values' => [
                [
                    'name' => 'fk_service_custom_tenant',
                    'definition' => 'FOREIGN KEY (tenant_id) REFERENCES m_tenants(id) ON DELETE CASCADE ON UPDATE CASCADE'
                ],
                [
                    'name' => 'fk_service_custom_service',
                    'definition' => 'FOREIGN KEY (service_id) REFERENCES m_services(id) ON DELETE CASCADE ON UPDATE CASCADE'
                ]
            ],
            'm_service_type_attributes' => [
                [
                    'name' => 'fk_attribute_service_type',
                    'definition' => 'FOREIGN KEY (service_type_id) REFERENCES m_service_types(id) ON DELETE CASCADE ON UPDATE CASCADE'
                ]
            ],
            'm_special_schedules' => [
                [
                    'name' => 'fk_special_schedule_service',
                    'definition' => 'FOREIGN KEY (service_id) REFERENCES m_services(id) ON DELETE CASCADE ON UPDATE CASCADE'
                ]
            ],
            'm_payments' => [
                [
                    'name' => 'fk_payment_booking',
                    'definition' => 'FOREIGN KEY (booking_id) REFERENCES m_bookings(id) ON DELETE RESTRICT ON UPDATE CASCADE'
                ],
                [
                    'name' => 'fk_payment_tenant',
                    'definition' => 'FOREIGN KEY (tenant_id) REFERENCES m_tenants(id) ON DELETE CASCADE ON UPDATE CASCADE'
                ],
                [
                    'name' => 'fk_payment_user',
                    'definition' => 'FOREIGN KEY (user_id) REFERENCES m_user(intUserID) ON DELETE RESTRICT ON UPDATE CASCADE'
                ]
            ]
        ];

        // Define indexes for each table
        $tableIndexes = [
            'm_tenants' => [
                ['name' => 'idx_tenant_status', 'columns' => ['status']],
                ['name' => 'idx_tenant_subscription', 'columns' => ['subscription_plan']],
                ['name' => 'idx_tenant_type_status', 'columns' => ['service_type_id', 'status']],
                ['name' => 'idx_tenant_owner', 'columns' => ['owner_id']]
            ],
            'm_services' => [
                ['name' => 'idx_service_active', 'columns' => ['is_active']],
                ['name' => 'idx_service_tenant', 'columns' => ['tenant_id', 'is_active']],
                ['name' => 'idx_service_type', 'columns' => ['service_type_id']],
                ['name' => 'idx_service_category', 'columns' => ['category']]
            ],
            'm_schedules' => [
                ['name' => 'idx_schedule_dates', 'columns' => ['start_time', 'end_time']],
                ['name' => 'idx_schedule_service', 'columns' => ['service_id', 'is_available']],
                ['name' => 'idx_schedule_bookings', 'columns' => ['current_bookings', 'max_bookings']]
            ],
            'm_bookings' => [
                ['name' => 'idx_booking_status', 'columns' => ['status']],
                ['name' => 'idx_booking_date', 'columns' => ['booking_date']],
                ['name' => 'idx_booking_tenant_status', 'columns' => ['tenant_id', 'status']],
                ['name' => 'idx_booking_service_date', 'columns' => ['service_id', 'booking_date']],
                ['name' => 'idx_booking_user', 'columns' => ['user_id']]
            ],
            'm_user' => [
                ['name' => 'idx_user_tenant', 'columns' => ['tenant_id']],
                ['name' => 'idx_user_role', 'columns' => ['intRoleID']],
                ['name' => 'idx_user_email', 'columns' => ['txtEmail']],
                ['name' => 'idx_user_status', 'columns' => ['bitActive']]
            ],
            'm_menu' => [
                ['name' => 'idx_menu_parent', 'columns' => ['intParentID']],
                ['name' => 'idx_menu_active', 'columns' => ['bitActive']]
            ]
        ];

        // Add constraints for each table
        foreach ($tableConstraints as $table => $constraints) {
            $this->addConstraintsForTable($table, $constraints);
        }

        // Add indexes for each table
        foreach ($tableIndexes as $table => $indexes) {
            $this->addIndexesForTable($table, $indexes);
        }

        $this->db->query('SET FOREIGN_KEY_CHECKS = 1');
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
            'm_bookings' => [
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
            'm_bookings' => ['fk_booking_tenant', 'fk_booking_user', 'fk_booking_service', 'fk_booking_schedule'],
            'm_booking_custom_values' => ['fk_custom_value_booking', 'fk_custom_value_service'],
            'm_notifications' => ['fk_notification_tenant', 'fk_notification_user'],
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
            'm_bookings',
            'm_booking_custom_values',
            'm_notifications',
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

    private function tableExists(string $table): bool
    {
        try {
            $result = $this->db->query("SHOW TABLES LIKE '{$table}'")->getResult();
            return count($result) > 0;
        } catch (\Exception $e) {
            return false;
        }
    }

    private function addConstraintsForTable(string $table, array $constraints)
    {
        if (!$this->tableExists($table)) {
            return;
        }

        foreach ($constraints as $constraint) {
            try {
                $this->addForeignKeyIfNotExists(
                    $table,
                    $constraint['name'],
                    $constraint['definition']
                );
            } catch (\Exception $e) {
                // Log error and continue with next constraint
                log_message('error', "Error adding constraint {$constraint['name']} to {$table}: " . $e->getMessage());
            }
        }
    }

    private function addIndexesForTable(string $table, array $indexes)
    {
        if (!$this->tableExists($table)) {
            return;
        }

        foreach ($indexes as $index) {
            try {
                $this->createIndexIfNotExists(
                    $table,
                    $index['name'],
                    implode(',', (array)$index['columns'])
                );
            } catch (\Exception $e) {
                // Log error and continue with next index
                log_message('error', "Error adding index {$index['name']} to {$table}: " . $e->getMessage());
            }
        }
    }
}
