<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTenantTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'guid' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'unique' => true,
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'service_type_id' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'owner_id' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'domain' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'logo' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'theme' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
            ],
            'settings' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'subscription_plan' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => true,
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['pending', 'active', 'suspended', 'cancelled'],
                'default' => 'pending',
            ],
            'trial_ends_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'is_active' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 1,
            ],
            'created_by' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'created_date' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
            'updated_by' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
            ],
            'updated_date' => [
                'type' => 'DATETIME',
                'null' => true,
            ]
        ]);        $this->forge->addKey('id', true);
        $this->forge->addKey('owner_id');
        $this->forge->addKey('service_type_id');

        $this->forge->createTable('m_tenant');
    }

    public function down()
    {
        $this->forge->dropTable('m_tenant');
    }
}
