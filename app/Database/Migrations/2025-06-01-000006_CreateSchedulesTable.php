<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSchedulesTable extends Migration
{
    public function up()
    {
        // Create Schedules Table
        $fields = [
            'intScheduleID' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true
            ],
            'intServiceID' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => false
            ],
            'txtDay' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => false,
                'comment' => 'Monday, Tuesday, etc.'
            ],
            'dtmStartTime' => [
                'type' => 'TIME',
                'null' => false
            ],
            'dtmEndTime' => [
                'type' => 'TIME',
                'null' => false
            ],
            'intSlotDuration' => [
                'type' => 'INT',
                'constraint' => 5,
                'default' => 60,
                'comment' => 'Duration in minutes'
            ],
            'txtGUID' => [
                'type' => 'VARCHAR',
                'constraint' => 36,
                'null' => false
            ],            'bitIsAvailable' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 1
            ],
            'bitActive' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 1
            ],
            'txtCreatedBy' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => false,
                'default' => 'system'
            ],
            'dtmCreatedDate' => [
                'type' => 'DATETIME',
                'null' => true,
                'default' => date('Y-m-d H:i:s')
            ],
            'txtUpdatedBy' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => false,
                'default' => 'system'
            ],
            'dtmUpdatedDate' => [
                'type' => 'DATETIME',
                'null' => true
            ]
        ];

        $this->forge->addField($fields);
        $this->forge->addKey('intScheduleID', true);
        $this->forge->addForeignKey('intServiceID', 'm_services', 'intServiceID', 'CASCADE', 'CASCADE');
        $this->forge->createTable('m_schedules');
    }

    public function down()
    {
        // Drop table if exists
        $this->forge->dropTable('m_schedules');
    }
}
