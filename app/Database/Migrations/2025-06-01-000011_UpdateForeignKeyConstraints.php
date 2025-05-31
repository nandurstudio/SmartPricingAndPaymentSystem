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
        // Check the column types of intUserID in m_user table
        $userIdType = $this->db->query("SHOW COLUMNS FROM m_user WHERE Field = 'intUserID'")->getRow();
        $userIdIsUnsigned = strpos($userIdType->Type, 'unsigned') !== false;
          // Add foreign key constraint from tenants to service_types if it doesn't exist
        $checkExists = $this->db->query("SELECT CONSTRAINT_NAME FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
            WHERE TABLE_SCHEMA = DATABASE() 
            AND TABLE_NAME = 'm_tenants' 
            AND COLUMN_NAME = 'service_type_id' 
            AND REFERENCED_TABLE_NAME = 'm_service_types'")->getNumRows();
            
        if ($checkExists === 0) {
            $this->db->query("ALTER TABLE m_tenants ADD CONSTRAINT fk_tenant_service_type FOREIGN KEY (service_type_id) REFERENCES m_service_types(id) ON DELETE SET NULL ON UPDATE CASCADE");
        }        // Add foreign key from tenants to users (owner_id)
        $checkOwnerFkExists = $this->db->query("SELECT CONSTRAINT_NAME FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
            WHERE TABLE_SCHEMA = DATABASE() 
            AND TABLE_NAME = 'm_tenants' 
            AND COLUMN_NAME = 'owner_id' 
            AND REFERENCED_TABLE_NAME = 'm_user'")->getNumRows();
            
        if ($checkOwnerFkExists === 0) {
            if ($userIdIsUnsigned) {
                $this->db->query("ALTER TABLE m_tenants ADD CONSTRAINT fk_tenant_owner FOREIGN KEY (owner_id) REFERENCES m_user(intUserID) ON DELETE CASCADE ON UPDATE CASCADE");
            } else {
                // If intUserID is not unsigned, we need to change owner_id to match
                $this->db->query("ALTER TABLE m_tenants MODIFY owner_id INT(11) NOT NULL");
                $this->db->query("ALTER TABLE m_tenants ADD CONSTRAINT fk_tenant_owner FOREIGN KEY (owner_id) REFERENCES m_user(intUserID) ON DELETE CASCADE ON UPDATE CASCADE");
            }
        }
          // Add foreign keys to service_types for requested_by and approved_by
        $checkRequestorFkExists = $this->db->query("SELECT CONSTRAINT_NAME FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
            WHERE TABLE_SCHEMA = DATABASE() 
            AND TABLE_NAME = 'm_service_types' 
            AND COLUMN_NAME = 'requested_by' 
            AND REFERENCED_TABLE_NAME = 'm_user'")->getNumRows();
            
        $checkApproverFkExists = $this->db->query("SELECT CONSTRAINT_NAME FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
            WHERE TABLE_SCHEMA = DATABASE() 
            AND TABLE_NAME = 'm_service_types' 
            AND COLUMN_NAME = 'approved_by' 
            AND REFERENCED_TABLE_NAME = 'm_user'")->getNumRows();
            
        if ($checkRequestorFkExists === 0 || $checkApproverFkExists === 0) {
            if ($userIdIsUnsigned) {
                if ($checkRequestorFkExists === 0) {
                    $this->db->query("ALTER TABLE m_service_types ADD CONSTRAINT fk_service_type_requestor FOREIGN KEY (requested_by) REFERENCES m_user(intUserID) ON DELETE SET NULL ON UPDATE CASCADE");
                }
                if ($checkApproverFkExists === 0) {
                    $this->db->query("ALTER TABLE m_service_types ADD CONSTRAINT fk_service_type_approver FOREIGN KEY (approved_by) REFERENCES m_user(intUserID) ON DELETE SET NULL ON UPDATE CASCADE");
                }
            } else {
                // Change column types if needed
                $this->db->query("ALTER TABLE m_service_types MODIFY requested_by INT(11) NULL");
                $this->db->query("ALTER TABLE m_service_types MODIFY approved_by INT(11) NULL");
                if ($checkRequestorFkExists === 0) {
                    $this->db->query("ALTER TABLE m_service_types ADD CONSTRAINT fk_service_type_requestor FOREIGN KEY (requested_by) REFERENCES m_user(intUserID) ON DELETE SET NULL ON UPDATE CASCADE");
                }
                if ($checkApproverFkExists === 0) {
                    $this->db->query("ALTER TABLE m_service_types ADD CONSTRAINT fk_service_type_approver FOREIGN KEY (approved_by) REFERENCES m_user(intUserID) ON DELETE SET NULL ON UPDATE CASCADE");
                }
            }
        }
          // Add foreign key from bookings to users (customer_id)
        $checkBookingCustomerFkExists = $this->db->query("SELECT CONSTRAINT_NAME FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
            WHERE TABLE_SCHEMA = DATABASE() 
            AND TABLE_NAME = 'm_bookings' 
            AND COLUMN_NAME = 'customer_id' 
            AND REFERENCED_TABLE_NAME = 'm_user'")->getNumRows();
            
        if ($checkBookingCustomerFkExists === 0) {
            if ($userIdIsUnsigned) {
                $this->db->query("ALTER TABLE m_bookings ADD CONSTRAINT fk_booking_customer FOREIGN KEY (customer_id) REFERENCES m_user(intUserID) ON DELETE CASCADE ON UPDATE CASCADE");
            } else {
                // Change column type if needed
                $this->db->query("ALTER TABLE m_bookings MODIFY customer_id INT(11) NOT NULL");
                $this->db->query("ALTER TABLE m_bookings ADD CONSTRAINT fk_booking_customer FOREIGN KEY (customer_id) REFERENCES m_user(intUserID) ON DELETE CASCADE ON UPDATE CASCADE");
            }
        }        // Add foreign key from notifications to users (recipient_id) if table exists
        $checkNotificationTableExists = $this->db->query("SHOW TABLES LIKE 'm_notifications'")->getNumRows();
        
        if ($checkNotificationTableExists > 0) {
            $checkNotificationRecipientFkExists = $this->db->query("SELECT CONSTRAINT_NAME FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
                WHERE TABLE_SCHEMA = DATABASE() 
                AND TABLE_NAME = 'm_notifications' 
                AND COLUMN_NAME = 'recipient_id' 
                AND REFERENCED_TABLE_NAME = 'm_user'")->getNumRows();
                
            if ($checkNotificationRecipientFkExists === 0) {
                if ($userIdIsUnsigned) {
                    $this->db->query("ALTER TABLE m_notifications ADD CONSTRAINT fk_notification_recipient FOREIGN KEY (recipient_id) REFERENCES m_user(intUserID) ON DELETE SET NULL ON UPDATE CASCADE");
                } else {
                    // Change column type if needed
                    $this->db->query("ALTER TABLE m_notifications MODIFY recipient_id INT(11) NULL");
                    $this->db->query("ALTER TABLE m_notifications ADD CONSTRAINT fk_notification_recipient FOREIGN KEY (recipient_id) REFERENCES m_user(intUserID) ON DELETE SET NULL ON UPDATE CASCADE");
                }
            }
        }

        // Update users table to add role field if not exists
        if (!$this->db->fieldExists('role', 'm_user')) {
            $this->forge->addColumn('m_user', [
                'role' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 50,
                    'null'       => false,
                    'default'    => 'customer',
                    'after'      => 'intUserID'
                ]
            ]);
        }

        // Add tenant_id to user table if not exists
        if (!$this->db->fieldExists('tenant_id', 'm_user')) {
            $this->forge->addColumn('m_user', [
                'tenant_id' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                    'unsigned'   => true,
                    'null'       => true,
                    'after'      => 'role'
                ]
            ]);            // Add foreign key if it doesn't exist yet
            $checkUserTenantFkExists = $this->db->query("SELECT CONSTRAINT_NAME FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
                WHERE TABLE_SCHEMA = DATABASE() 
                AND TABLE_NAME = 'm_user' 
                AND COLUMN_NAME = 'tenant_id' 
                AND REFERENCED_TABLE_NAME = 'm_tenants'")->getNumRows();
                
            if ($checkUserTenantFkExists === 0) {
                $this->db->query("ALTER TABLE m_user ADD CONSTRAINT fk_user_tenant FOREIGN KEY (tenant_id) REFERENCES m_tenants(id) ON DELETE SET NULL ON UPDATE CASCADE");
            }
        }

        // Add phone to user table if not exists
        if (!$this->db->fieldExists('phone', 'm_user')) {
            $this->forge->addColumn('m_user', [
                'phone' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 20,
                    'null'       => true,
                    'after'      => 'txtEmail'
                ]
            ]);
        }
    }

    public function down()
    {
        // Remove all foreign key constraints
        $this->db->query("ALTER TABLE m_tenants DROP FOREIGN KEY IF EXISTS fk_tenant_service_type");
        $this->db->query("ALTER TABLE m_tenants DROP FOREIGN KEY IF EXISTS fk_tenant_owner");
        $this->db->query("ALTER TABLE m_service_types DROP FOREIGN KEY IF EXISTS fk_service_type_requestor");
        $this->db->query("ALTER TABLE m_service_types DROP FOREIGN KEY IF EXISTS fk_service_type_approver");
        $this->db->query("ALTER TABLE m_bookings DROP FOREIGN KEY IF EXISTS fk_booking_customer");
        $this->db->query("ALTER TABLE m_notifications DROP FOREIGN KEY IF EXISTS fk_notification_recipient");
        
        // Remove added columns from user table
        if ($this->db->fieldExists('role', 'm_user')) {
            $this->forge->dropColumn('m_user', 'role');
        }

        if ($this->db->fieldExists('tenant_id', 'm_user')) {
            $this->db->query("ALTER TABLE m_user DROP FOREIGN KEY IF EXISTS fk_user_tenant");
            $this->forge->dropColumn('m_user', 'tenant_id');
        }

        if ($this->db->fieldExists('phone', 'm_user')) {
            $this->forge->dropColumn('m_user', 'phone');
        }
    }
}
