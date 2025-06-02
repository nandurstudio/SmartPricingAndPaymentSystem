<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateBookingsTable extends Migration
{
    public function up()
    {
        // Create Bookings Table
        $this->forge->addField([
            'intBookingID' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'txtBookingCode' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => false,
            ],
            'intServiceID' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => false,
            ],
            'intCustomerID' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => false,
            ],
            'intTenantID' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => false,
            ],
            'dtmBookingDate' => [
                'type'       => 'DATE',
                'null'       => false,
            ],
            'dtmStartTime' => [
                'type'       => 'TIME',
                'null'       => false,
            ],
            'dtmEndTime' => [
                'type'       => 'TIME',
                'null'       => false,
            ],
            'decPrice' => [
                'type'       => 'DECIMAL',
                'constraint' => '12,2',
                'default'    => 0.00,
            ],
            'txtStatus' => [
                'type'       => 'ENUM',
                'constraint' => ['pending', 'confirmed', 'completed', 'cancelled'],
                'default'    => 'pending',
            ],
            'txtPaymentStatus' => [
                'type'       => 'ENUM',
                'constraint' => ['unpaid', 'paid', 'partially_paid', 'refunded'],
                'default'    => 'unpaid',
            ],
            'txtPaymentID' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
                'comment'    => 'External payment gateway ID',
            ],            'txtGUID' => [
                'type'       => 'VARCHAR',
                'constraint' => 36,
                'null'       => false,
                'unique'     => true,
            ],
            'dtmCancelledDate' => [
                'type'       => 'DATETIME',
                'null'       => true,
            ],
            'txtCancelledReason' => [
                'type'       => 'TEXT',
                'null'       => true,
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
            'bitActive' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 1,
            ],
            'dtmUpdatedDate' => [
                'type'       => 'DATETIME',
                'null'       => true,
            ]
        ]);        $this->forge->addKey('intBookingID', true);
        $this->forge->addUniqueKey('txtBookingCode');
        $this->forge->addForeignKey('intServiceID', 'm_services', 'intServiceID', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('intTenantID', 'm_tenants', 'intTenantID', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('intCustomerID', 'm_user', 'intUserID', 'CASCADE', 'CASCADE');
        $this->forge->createTable('tr_bookings');
    }

    public function down()
    {
        // Drop table if exists
        $this->forge->dropTable('tr_bookings');
    }
}
