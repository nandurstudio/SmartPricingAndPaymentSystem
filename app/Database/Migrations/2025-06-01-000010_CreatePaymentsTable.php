<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePaymentsTable extends Migration
{
    public function up()
    {
        // Create Payments Table
        $this->forge->addField([            'intPaymentID' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],            'txtGUID' => [
                'type'       => 'VARCHAR',
                'constraint' => 36,
                'null'       => false,
            ],
            'intBookingID' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => false,
            ],            'txtPaymentCode' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => false,
            ],
            'decAmount' => [
                'type'       => 'DECIMAL',
                'constraint' => '12,2',
                'null'       => false,
                'default'    => 0.00,
            ],
            'txtPaymentMethod' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => false,
                'comment'    => 'midtrans, transfer, cash, etc.',
            ],
            'dtmPaymentDate' => [
                'type'       => 'DATETIME',
                'null'       => true,
            ],
            'txtStatus' => [
                'type'       => 'ENUM',
                'constraint' => ['pending', 'success', 'failed', 'refunded'],
                'default'    => 'pending',
            ],
            'txtTransactionID' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'comment'    => 'External payment gateway transaction ID',
            ],
            'jsonPaymentDetails' => [
                'type'       => 'JSON',
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
        ]);          $this->forge->addKey('intPaymentID', true);
        $this->forge->addUniqueKey('txtPaymentCode');
        $this->forge->addForeignKey('intBookingID', 'tr_bookings', 'intBookingID', 'CASCADE', 'CASCADE');
        $this->forge->createTable('tr_payments');
    }

    public function down()
    {
        // Drop table if exists
        $this->forge->dropTable('tr_payments');
    }
}
