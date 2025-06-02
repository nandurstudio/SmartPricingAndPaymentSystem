<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateServicesTable extends Migration
{
    public function up()
    {
        // Create Services Table
        $this->forge->addField([
            'intServiceID' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'intTenantID' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => false,
            ],
            'intServiceTypeID' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => false,
            ],
            'txtName' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => false,
            ],
            'txtDescription' => [
                'type'       => 'TEXT',
                'null'       => true,
            ],
            'decPrice' => [
                'type'       => 'DECIMAL',
                'constraint' => '12,2',
                'default'    => 0,
            ],
            'intDuration' => [
                'type'       => 'INT',
                'constraint' => 5,
                'default'    => 60,
                'comment'    => 'Duration in minutes',
            ],
            'intCapacity' => [
                'type'       => 'INT',
                'constraint' => 5,
                'default'    => 1,
                'comment'    => 'Number of people/slots per booking',
            ],
            'txtImage' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'txtGUID' => [
                'type'       => 'VARCHAR',
                'constraint' => 36,
                'null'       => false,
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
        
        $this->forge->addKey('intServiceID', true);
        $this->forge->addForeignKey('intTenantID', 'm_tenants', 'intTenantID', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('intServiceTypeID', 'm_service_types', 'intServiceTypeID', 'CASCADE', 'CASCADE');
        $this->forge->createTable('m_services');
    }

    public function down()
    {
        // Drop table if exists
        $this->forge->dropTable('m_services');
    }
}
