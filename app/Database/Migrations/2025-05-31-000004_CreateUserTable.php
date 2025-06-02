<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUserTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'intUserID' => [
                'type' => 'INT',
                'constraint' => 10,
                'unsigned' => true,
                'auto_increment' => true,
            ],            
            'txtGUID' => [
                'type' => 'VARCHAR',
                'constraint' => 36,
                'null' => false,
            ],
            'intRoleID' => [
                'type' => 'INT',
                'constraint' => 10,
                'unsigned' => true,
                'default' => 5,
            ],
            'intTenantID' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'is_tenant_owner' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
            ],
            'intDefaultTenantID' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'txtUserName' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => false,
            ],
            'txtFullName' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => false,
            ],
            'txtEmail' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => false,
            ],
            'txtPassword' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => false,
            ],
            'bitActive' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 1,
            ],
            'bitOnlineStatus' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
            ],
            'dtmLastLogin' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'txtPhoto' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'default' => 'default.png',
                'null' => true,
            ],
            'txtResetToken' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'dtmTokenCreatedAt' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'txtGoogleAuthToken' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'dtmJoinDate' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'txtCreatedBy' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'default' => 'system',
            ],
            'dtmCreatedDate' => [
                'type' => 'DATETIME',
                'null' => true,
                'default' => date('Y-m-d H:i:s'),
            ],
            'txtUpdatedBy' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'default' => 'system',
            ],
            'dtmUpdatedDate' => [
                'type' => 'DATETIME',
                'null' => true,
            ]
        ]);        $this->forge->addKey('intUserID', true);
        $this->forge->addUniqueKey(['txtEmail', 'txtUserName', 'txtGUID']);
        $this->forge->addForeignKey('intRoleID', 'm_role', 'intRoleID', 'CASCADE', 'CASCADE');
        $this->forge->createTable('m_user');
    }

    public function down()
    {
        $this->forge->dropTable('m_user');
    }
}
