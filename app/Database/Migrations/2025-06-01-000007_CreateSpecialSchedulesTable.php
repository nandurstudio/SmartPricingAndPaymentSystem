<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSpecialSchedulesTable extends Migration
{
    public function up()
    {
        // Create Special Schedules Table for exceptions
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
            'special_date' => [
                'type'       => 'DATE',
                'null'       => false,
            ],
            'start_time' => [
                'type'       => 'TIME',
                'null'       => true,
            ],
            'end_time' => [
                'type'       => 'TIME',
                'null'       => true,
            ],
            'is_closed' => [
                'type'       => 'BOOLEAN',
                'null'       => false,
                'default'    => false,
                'comment'    => 'If true, service is closed for this date',
            ],
            'slot_duration' => [
                'type'       => 'INT',
                'constraint' => 5,
                'null'       => true,
                'comment'    => 'Duration in minutes',
            ],
            'note' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
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
        $this->forge->createTable('m_special_schedules');
    }

    public function down()
    {
        // Drop table if exists
        $this->forge->dropTable('m_special_schedules');
    }
}
