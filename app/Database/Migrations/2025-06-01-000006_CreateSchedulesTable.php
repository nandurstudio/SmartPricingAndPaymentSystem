<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSchedulesTable extends Migration
{
    public function up()
    {
        // Create Schedules Table
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'service_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => false,
            ],
            'day' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => false,
                'comment'    => 'Monday, Tuesday, etc.',
            ],
            'start_time' => [
                'type'       => 'TIME',
                'null'       => false,
            ],
            'end_time' => [
                'type'       => 'TIME',
                'null'       => false,
            ],
            'slot_duration' => [
                'type'       => 'INT',
                'constraint' => 5,
                'null'       => false,
                'default'    => 60,
                'comment'    => 'Duration in minutes',
            ],
            'is_available' => [
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
        $this->forge->addForeignKey('service_id', 'm_services', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('m_schedules');
    }

    public function down()
    {
        // Drop table if exists
        $this->forge->dropTable('m_schedules');
    }
}
