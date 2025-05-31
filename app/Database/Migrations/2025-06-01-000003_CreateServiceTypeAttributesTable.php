<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateServiceTypeAttributesTable extends Migration
{
    public function up()
    {
        // Create Service Type Attributes Table
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'service_type_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => false,
            ],
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => false,
            ],
            'label' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => false,
            ],
            'field_type' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => false,
                'comment'    => 'text, number, date, select, checkbox, etc.',
            ],
            'is_required' => [
                'type'       => 'BOOLEAN',
                'null'       => false,
                'default'    => false,
            ],
            'default_value' => [
                'type'       => 'TEXT',
                'null'       => true,
            ],
            'options' => [
                'type'       => 'JSON',
                'null'       => true,
                'comment'    => 'For select, radio, checkbox types',
            ],
            'validation' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'comment'    => 'Validation rules',
            ],
            'display_order' => [
                'type'       => 'INT',
                'constraint' => 3,
                'null'       => false,
                'default'    => 0,
            ],
            'is_active' => [
                'type'       => 'BOOLEAN',
                'null'       => false,
                'default'    => true,
            ],
            'created_date' => [
                'type'       => 'DATETIME',
                'null'       => false,
            ],
            'created_by' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'updated_date' => [
                'type'       => 'DATETIME',
                'null'       => true,
            ],
            'updated_by' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
        ]);
        
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('service_type_id', 'm_service_types', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('m_service_type_attributes');
    }

    public function down()
    {
        // Drop table if exists
        $this->forge->dropTable('m_service_type_attributes');
    }
}
