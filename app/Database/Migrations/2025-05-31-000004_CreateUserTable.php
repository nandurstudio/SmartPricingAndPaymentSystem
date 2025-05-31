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
                'auto_increment' => true,
            ],
            'intRoleID' => [
                'type' => 'INT',
                'constraint' => 10,
                'unsigned' => true,
                'default' => 5,
            ],
            'txtUserName' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => false,
                'default' => 'dummy.nick',
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
                'default' => 'dummy@email.com',
            ],
            'txtPassword' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => false,
            ],
            'bitActive' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'null' => true,
                'default' => 1,
            ],
            'bitOnlineStatus' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'null' => true,
                'default' => 0,
            ],
            'dtmLastLogin' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'txtCreatedBy' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
                'default' => 'system',
            ],
            'dtmCreatedDate' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'txtUpdatedBy' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
                'default' => 'system',
            ],
            'dtmUpdatedDate' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'txtGUID' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => false,
            ],
            'reset_token' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'token_created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'google_auth_token' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'txtPhoto' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'default' => 'default.png',
            ],
            'dtmJoinDate' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('intUserID', true);
        $this->forge->addForeignKey('intRoleID', 'm_role', 'intRoleID');
        $this->forge->createTable('m_user');
    }

    public function down()
    {
        $this->forge->dropTable('m_user');
    }
}
