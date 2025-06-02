<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSpecialSchedulesTable extends Migration
{
    public function up()
    {
        // Create Special Schedules Table for exceptions
        $fields = [
            'intSpecialScheduleID' => [
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
            'dtmSpecialDate' => [
                'type' => 'DATE',
                'null' => false
            ],
            'dtmStartTime' => [
                'type' => 'TIME',
                'null' => true
            ],
            'dtmEndTime' => [
                'type' => 'TIME',
                'null' => true
            ],
            'bitIsClosed' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
                'comment' => 'If true, service is closed for this date'
            ],
            'intSlotDuration' => [
                'type' => 'INT',
                'constraint' => 5,
                'default' => 60,
                'comment' => 'Duration in minutes'
            ],
            'txtNote' => [
                'type' => 'TEXT',
                'null' => true
            ],
            'txtGUID' => [
                'type' => 'VARCHAR',
                'constraint' => 36,
                'null' => false
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
        $this->forge->addKey('intSpecialScheduleID', true);
        $this->forge->addForeignKey('intServiceID', 'm_services', 'intServiceID', 'CASCADE', 'CASCADE');
        $this->forge->createTable('m_special_schedules');
    }

    public function down()
    {
        // Drop table if exists
        $this->forge->dropTable('m_special_schedules');
    }
}
