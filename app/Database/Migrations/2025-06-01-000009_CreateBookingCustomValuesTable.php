<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateBookingCustomValuesTable extends Migration
{
    public function up()
    {
        // Create Booking Custom Values Table
        $this->forge->addField([            'intBookingCustomValueID' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'txtGUID' => [
                'type'       => 'VARCHAR',
                'constraint' => 36,
                'null'       => false,
                'unique'     => true,
            ],
            'intBookingID' => [
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
            'bitActive' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 1,
            ],
            'txtCreatedBy' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'default'    => 'system',
            ],            'dtmCreatedDate' => [
                'type'       => 'DATETIME',
                'null'       => true,
                'default'    => date('Y-m-d H:i:s'),
            ],
            'txtUpdatedBy' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'default'    => 'system',
            ],
            'dtmUpdatedDate' => [
                'type'       => 'DATETIME',
                'null'       => true,
            ],
        ]);          $this->forge->addKey('intBookingCustomValueID', true);
        $this->forge->addForeignKey('intBookingID', 'tr_bookings', 'intBookingID', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('intAttributeID', 'm_service_type_attributes', 'intAttributeID', 'CASCADE', 'CASCADE');
        $this->forge->createTable('tr_booking_custom_values');
    }

    public function down()
    {
        // Drop table if exists
        $this->forge->dropTable('tr_booking_custom_values');
    }
}
