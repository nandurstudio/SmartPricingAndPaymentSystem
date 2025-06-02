<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTenantsTable extends Migration
{
    public function up()
    {
        // Create Tenants Table
        $this->forge->addField([
            'intTenantID' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],            
            'txtGUID' => [
                'type'       => 'VARCHAR',
                'constraint' => 36,
                'null'       => false,
            ],
            'txtTenantName' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => false,
            ],
            'txtSlug' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => false,
            ],
            'txtDomain' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'intServiceTypeID' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],            'intOwnerID' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true, // Changed to allow null initially
            ],
            'txtSubscriptionPlan' => [
                'type'       => 'ENUM',
                'constraint' => ['free', 'basic', 'premium', 'enterprise'],
                'default'    => 'free',
            ],
            'txtStatus' => [
                'type'       => 'ENUM',
                'constraint' => ['active', 'inactive', 'suspended', 'pending'],
                'default'    => 'pending',
            ],
            'jsonSettings' => [
                'type'       => 'JSON',
                'null'       => true,
            ],
            'jsonPaymentSettings' => [
                'type'       => 'JSON',
                'null'       => true,
            ],
            'bitActive' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 1,
            ],
            'txtCreatedBy' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => false,
                'default'    => 'system',
            ],
            'dtmCreatedDate' => [
                'type'       => 'DATETIME',
                'null'       => true,
                'default'    => date('Y-m-d H:i:s'),
            ],
            'txtUpdatedBy' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => false,
                'default'    => 'system',
            ],
            'dtmUpdatedDate' => [
                'type'       => 'DATETIME',
                'null'       => true,
            ]
        ]);
        
        $this->forge->addKey('intTenantID', true);
        $this->forge->addUniqueKey(['txtGUID', 'txtSlug', 'txtDomain']);
        $this->forge->createTable('m_tenants');
    }

    public function down()
    {
        // Drop table if exists
        $this->forge->dropTable('m_tenants');
    }
}
