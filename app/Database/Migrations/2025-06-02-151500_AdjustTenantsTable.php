<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AdjustTenantsTable extends Migration
{
    public function up()
    {
        // Temporarily disable foreign key checks
        $this->db->query('SET FOREIGN_KEY_CHECKS = 0');

        // Drop existing table if exists
        if ($this->db->tableExists('m_tenants')) {
            $this->forge->dropTable('m_tenants', true);
        }
        if ($this->db->tableExists('m_tenant')) {
            $this->forge->dropTable('m_tenant', true);
        }

        // Create new table structure
        $fields = [
            'intTenantID' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'txtGUID' => [
                'type' => 'VARCHAR',
                'constraint' => 36,
                'null' => false,
            ],
            'txtTenantName' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => false,
            ],
            'txtSlug' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => false,
            ],
            'txtDomain' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'txtTenantCode' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
            ],
            'intServiceTypeID' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'intOwnerID' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'txtSubscriptionPlan' => [
                'type' => 'ENUM',
                'constraint' => ['free','basic','premium','enterprise'],
                'default' => 'free',
                'null' => true,
            ],
            'txtSubscriptionStatus' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
                'default' => 'inactive',
            ],
            'dtmSubscriptionStartDate' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'dtmSubscriptionEndDate' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'dtmTrialEndsAt' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'jsonSettings' => [
                'type' => 'JSON',
                'null' => true,
            ],
            'jsonPaymentSettings' => [
                'type' => 'JSON',
                'null' => true,
            ],
            'txtMidtransClientKey' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'txtMidtransServerKey' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'txtLogo' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'txtTheme' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
                'default' => 'default',
            ],
            'txtStatus' => [
                'type' => 'ENUM',
                'constraint' => ['active','inactive','suspended','pending','pending_verification','pending_payment','payment_failed'],
                'default' => 'pending',
                'null' => true,
            ],
            'bitActive' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 1,
            ],
            'txtCreatedBy' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => false,
                'default' => 'system',
            ],
            'dtmCreatedDate' => [
                'type' => 'DATETIME',
                'null' => true,
                'default' => date('Y-m-d H:i:s'),
            ],
            'txtUpdatedBy' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
            ],
            'dtmUpdatedDate' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ];

        // Create the table
        $this->forge->addField($fields);
        $this->forge->addKey('intTenantID', true);
        $this->forge->addUniqueKey(['txtGUID', 'txtSlug', 'txtDomain']);
        $this->forge->createTable('m_tenants', true);

        // Add foreign keys
        $this->db->query("ALTER TABLE m_tenants ADD CONSTRAINT fk_tenant_service_type FOREIGN KEY (intServiceTypeID) REFERENCES m_service_types(intServiceTypeID) ON DELETE SET NULL ON UPDATE CASCADE");
        $this->db->query("ALTER TABLE m_tenants ADD CONSTRAINT fk_tenant_owner FOREIGN KEY (intOwnerID) REFERENCES m_user(intUserID) ON DELETE SET NULL ON UPDATE CASCADE");

        // Re-enable foreign key checks
        $this->db->query('SET FOREIGN_KEY_CHECKS = 1');
    }

    public function down()
    {
        // Disable foreign key checks
        $this->db->query('SET FOREIGN_KEY_CHECKS = 0');

        // Drop the table
        $this->forge->dropTable('m_tenants', true);

        // Re-enable foreign key checks
        $this->db->query('SET FOREIGN_KEY_CHECKS = 1');
    }
}