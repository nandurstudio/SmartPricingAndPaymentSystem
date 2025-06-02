<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateNotificationsTable extends Migration
{
    public function up()
    {
        // Create Notifications Table
        $this->forge->addField([            'intNotificationID' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],            'txtGUID' => [
                'type'       => 'VARCHAR',
                'constraint' => 36,
                'null'       => false,
            ],
            'txtType' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => false,
                'comment'    => 'booking_confirmation, payment_received, reminder, etc.',
            ],
            'intRecipientID' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'intSenderID' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'intTenantID' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => false,
            ],
            'intReferenceID' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'comment'    => 'ID of relevant record (booking ID, payment ID, etc.)',
            ],
            'txtReferenceType' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
                'comment'    => 'Type of reference (booking, payment, etc.)',
            ],
            'txtTitle' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => false,
            ],
            'txtMessage' => [
                'type'       => 'TEXT',
                'null'       => false,
            ],
            'txtChannel' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => false,
                'comment'    => 'email, sms, whatsapp, push, in-app',
            ],
            'txtStatus' => [
                'type'       => 'ENUM',
                'constraint' => ['pending', 'sent', 'delivered', 'read', 'failed'],
                'default'    => 'pending',
            ],
            'dtmSentDate' => [
                'type'       => 'DATETIME',
                'null'       => true,
            ],
            'dtmReadDate' => [
                'type'       => 'DATETIME',
                'null'       => true,
            ],
            'txtResponse' => [
                'type'       => 'TEXT',
                'null'       => true,
                'comment'    => 'Response from notification service',
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
            ],'txtUpdatedBy' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'default'    => 'system',
            ],
            'dtmUpdatedDate' => [
                'type'       => 'DATETIME',
                'null'       => true,
            ],
        ]);        $this->forge->addKey('intNotificationID', true);
        $this->forge->addUniqueKey('txtGUID');
        $this->forge->addForeignKey('intRecipientID', 'm_user', 'intUserID', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('intSenderID', 'm_user', 'intUserID', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('intTenantID', 'm_tenants', 'intTenantID', 'CASCADE', 'CASCADE');
        $this->forge->createTable('tr_notifications');
    }

    public function down()
    {
        // Drop table if exists
        $this->forge->dropTable('tr_notifications');
    }
}
