<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateBookingCustomValuesTable extends Migration
{
    public function up()
    {
        // Create Booking Custom Values Table
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'booking_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => false,
            ],
            'attribute_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => false,
            ],
            'value' => [
                'type'       => 'TEXT',
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
        $this->forge->addForeignKey('booking_id', 'm_bookings', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('attribute_id', 'm_service_type_attributes', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('m_booking_custom_values');
    }

    public function down()
    {
        // Drop table if exists
        $this->forge->dropTable('m_booking_custom_values');
    }
}
