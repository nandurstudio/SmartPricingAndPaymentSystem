<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateNotificationTemplatesTable extends Migration
{
    public function up()
    {
        // Create Notification Templates Table
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'tenant_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'comment'    => 'Null means system default template',
            ],
            'type' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => false,
                'comment'    => 'booking_confirmation, payment_received, reminder, etc.',
            ],
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => false,
            ],
            'channel' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => false,
                'comment'    => 'email, sms, whatsapp',
            ],
            'subject' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'comment'    => 'For email templates',
            ],
            'content' => [
                'type'       => 'TEXT',
                'null'       => false,
            ],
            'variables' => [
                'type'       => 'JSON',
                'null'       => true,
                'comment'    => 'Available variables for this template',
            ],
            'is_active' => [
                'type'       => 'BOOLEAN',
                'null'       => false,
                'default'    => true,
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
        $this->forge->addKey(['tenant_id', 'type', 'channel'], false, true);
        $this->forge->addForeignKey('tenant_id', 'm_tenants', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('m_notification_templates');
    }

    public function down()
    {
        // Drop table if exists
        $this->forge->dropTable('m_notification_templates');
    }
}
