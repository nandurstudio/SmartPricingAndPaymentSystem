<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateNotificationsTable extends Migration
{
    public function up()
    {
        // Create Notifications Table
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'type' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => false,
                'comment'    => 'booking_confirmation, payment_received, reminder, etc.',
            ],
            'recipient_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'sender_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'tenant_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => false,
            ],
            'reference_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'comment'    => 'ID of relevant record (booking ID, payment ID, etc.)',
            ],
            'reference_type' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
                'comment'    => 'Type of reference (booking, payment, etc.)',
            ],
            'title' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => false,
            ],
            'message' => [
                'type'       => 'TEXT',
                'null'       => false,
            ],
            'channel' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => false,
                'comment'    => 'email, sms, whatsapp, push, in-app',
            ],
            'status' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => false,
                'default'    => 'pending',
                'comment'    => 'pending, sent, delivered, read, failed',
            ],
            'sent_date' => [
                'type'       => 'DATETIME',
                'null'       => true,
            ],
            'read_date' => [
                'type'       => 'DATETIME',
                'null'       => true,
            ],
            'response' => [
                'type'       => 'TEXT',
                'null'       => true,
                'comment'    => 'Response from notification service',
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
        $this->forge->addForeignKey('tenant_id', 'm_tenants', 'id', 'CASCADE', 'CASCADE');
        // Recipient foreign key will be added in the UpdateForeignKeyConstraints migration
        $this->forge->createTable('m_notifications');
    }

    public function down()
    {
        // Drop table if exists
        $this->forge->dropTable('m_notifications');
    }
}
