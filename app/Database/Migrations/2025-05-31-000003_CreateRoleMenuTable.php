<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateRoleMenuTable extends Migration
{    public function up()
    {
        $this->forge->addField([            'intRoleMenuID' => [
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
                'null' => false,
            ],
            'intMenuID' => [
                'type' => 'INT',
                'constraint' => 10,
                'unsigned' => true,
                'null' => false,
            ],
            'bitActive' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 1,
            ],
            'txtCreatedBy' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => false,
                'default' => 'system',
            ],            'dtmCreatedDate' => [
                'type' => 'DATETIME',
                'null' => true,
                'default' => date('Y-m-d H:i:s'),
            ],
            'txtUpdatedBy' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => false,
                'default' => 'system',
            ],
            'dtmUpdatedDate' => [
                'type' => 'DATETIME',
                'null' => true,
            ]
        ]);

        $this->forge->addKey('intRoleMenuID', true);
        $this->forge->addUniqueKey(['intRoleID', 'intMenuID']);
        $this->forge->addForeignKey('intRoleID', 'm_role', 'intRoleID', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('intMenuID', 'm_menu', 'intMenuID', 'CASCADE', 'CASCADE');
        $this->forge->createTable('m_role_menu');
    }

    public function down()
    {
        $this->forge->dropTable('m_role_menu');
    }
}
