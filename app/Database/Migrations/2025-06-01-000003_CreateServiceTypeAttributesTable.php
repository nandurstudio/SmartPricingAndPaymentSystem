<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateServiceTypeAttributesTable extends Migration
{
    public function up()
    {
        // Create Service Type Attributes Table
        $this->forge->addField([
            'intAttributeID' => [
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
            'intServiceTypeID' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => false,
            ],
            'txtName' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => false,
            ],
            'txtLabel' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => false,
            ],
            'txtFieldType' => [
                'type'       => 'ENUM',
                'constraint' => ['text', 'number', 'boolean', 'select', 'date', 'time', 'datetime'],
                'null'       => false,
            ],
            'jsonOptions' => [
                'type'       => 'JSON',
                'null'       => true,
                'comment'    => 'For select, radio, checkbox types',
            ],
            'bitRequired' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
            ],
            'txtDefaultValue' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'txtValidation' => [
                'type'       => 'TEXT',
                'null'       => true,
                'comment'    => 'Validation rules',
            ],
            'intDisplayOrder' => [
                'type'       => 'INT',
                'constraint' => 5,
                'default'    => 0,
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
        
        $this->forge->addKey('intAttributeID', true);
        $this->forge->addForeignKey('intServiceTypeID', 'm_service_types', 'intServiceTypeID', 'CASCADE', 'CASCADE');
        $this->forge->createTable('m_service_type_attributes');
    }

    public function down()
    {
        // Drop table if exists
        $this->forge->dropTable('m_service_type_attributes');
    }
}
