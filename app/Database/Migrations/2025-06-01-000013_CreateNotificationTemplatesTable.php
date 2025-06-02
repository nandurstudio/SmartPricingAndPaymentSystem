<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateNotificationTemplatesTable extends Migration
{
    public function up()
    {
        // Create Notification Templates Table
        $this->forge->addField([            'intNotificationTemplateID' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],            'txtGUID' => [
                'type'       => 'VARCHAR',
                'constraint' => 36,
                'null'       => false,
            ],
            'intTenantID' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'comment'    => 'Null means system default template',
            ],
            'txtType' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => false,
                'comment'    => 'booking_confirmation, payment_received, reminder, etc.',
            ],
            'txtName' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => false,
            ],
            'txtChannel' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => false,
                'comment'    => 'email, sms, whatsapp',
            ],
            'txtSubject' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'comment'    => 'For email templates',
            ],
            'txtContent' => [
                'type'       => 'TEXT',
                'null'       => false,
            ],
            'jsonVariables' => [
                'type'       => 'JSON',
                'null'       => true,
                'comment'    => 'Available variables for this template',
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
        ]);
          $this->forge->addKey('intNotificationTemplateID', true);
        $this->forge->addUniqueKey('txtGUID');
        $this->forge->addKey(['intTenantID', 'txtType', 'txtChannel'], false, true);
        $this->forge->addForeignKey('intTenantID', 'm_tenants', 'intTenantID', 'CASCADE', 'CASCADE');
        $this->forge->createTable('m_notification_templates');
    }

    public function down()
    {
        // Drop table if exists
        $this->forge->dropTable('m_notification_templates');
    }
}
