<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateBookingsTable extends Migration
{
    public function up()
    {
        // Create Bookings Table
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'booking_code' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => false,
                'unique'     => true,
            ],
            'service_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => false,
            ],
            'customer_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => false,
            ],
            'tenant_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => false,
            ],
            'booking_date' => [
                'type'       => 'DATE',
                'null'       => false,
            ],
            'start_time' => [
                'type'       => 'TIME',
                'null'       => false,
            ],
            'end_time' => [
                'type'       => 'TIME',
                'null'       => false,
            ],
            'price' => [
                'type'       => 'DECIMAL',
                'constraint' => '12,2',
                'null'       => false,
                'default'    => 0.00,
            ],
            'status' => [
                'type'       => 'VARCHAR',
                'constraint' => 30,
                'null'       => false,
                'default'    => 'pending',
                'comment'    => 'pending, confirmed, completed, cancelled',
            ],
            'payment_status' => [
                'type'       => 'VARCHAR',
                'constraint' => 30,
                'null'       => false,
                'default'    => 'unpaid',
                'comment'    => 'unpaid, paid, partially_paid, refunded',
            ],
            'payment_id' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
                'comment'    => 'External payment gateway ID',
            ],
            'custom_fields' => [
                'type'       => 'JSON',
                'null'       => true,
            ],
            'notes' => [
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
            'cancelled_date' => [
                'type'       => 'DATETIME',
                'null'       => true,
            ],
            'cancelled_reason' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
        ]);
          $this->forge->addKey('id', true);
        $this->forge->addForeignKey('service_id', 'm_services', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('tenant_id', 'm_tenants', 'id', 'CASCADE', 'CASCADE');
        // Customer foreign key will be added in the UpdateForeignKeyConstraints migration
        $this->forge->createTable('m_bookings');
    }

    public function down()
    {
        // Drop table if exists
        $this->forge->dropTable('m_bookings');
    }
}
