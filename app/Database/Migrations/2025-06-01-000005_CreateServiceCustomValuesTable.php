<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateServiceCustomValuesTable extends Migration
{
    public function up()
    {
        // Create Service Custom Values Table
        $this->forge->addField([
            'intCustomValueID' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'intServiceID' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => false,
            ],
            'intAttributeID' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => false,
            ],
            'txtValue' => [
                'type'       => 'TEXT',
                'null'       => true,
            ],
            'txtCreatedBy' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'default'    => 'system',
            ],
            'dtmCreatedDate' => [
                'type'       => 'DATETIME',
                'null'       => true,
            ],
            'txtUpdatedBy' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'default'    => 'system',
            ],
            'dtmUpdatedDate' => [
                'type'       => 'DATETIME',
                'null'       => true,
            ]
        ]);
        
        $this->forge->addKey('intCustomValueID', true);
        $this->forge->addForeignKey('intServiceID', 'm_services', 'intServiceID', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('intAttributeID', 'm_service_type_attributes', 'intAttributeID', 'CASCADE', 'CASCADE');
        $this->forge->createTable('m_service_custom_values');
    }

    public function down()
    {
        // Drop table if exists
        $this->forge->dropTable('m_service_custom_values');
    }
}
