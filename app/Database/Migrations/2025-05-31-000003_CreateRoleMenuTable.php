<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateRoleMenuTable extends Migration
{    public function up()
    {
        $this->forge->addField([
            'intRoleMenuID' => [
                'type' => 'INT',
                'constraint' => 10,
                'unsigned' => true,
                'auto_increment' => true,
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
            'txtCreatedBy' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'default' => 'system',
            ],
            'dtmCreatedDate' => [
                'type' => 'TIMESTAMP',
                'null' => true,
            ],
            'txtLastUpdatedBy' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'default' => 'system',
            ],
            'dtmLastUpdatedDate' => [
                'type' => 'TIMESTAMP',
                'null' => true,
            ],
        ]);        $this->forge->addKey('intRoleMenuID', true);
        $this->forge->addUniqueKey(['intRoleID', 'intMenuID'], 'uk_role_menu');
        $this->forge->addForeignKey('intRoleID', 'm_role', 'intRoleID', 'CASCADE', 'CASCADE', 'fk_role_menu_role');
        $this->forge->addForeignKey('intMenuID', 'm_menu', 'intMenuID', 'CASCADE', 'CASCADE', 'fk_role_menu_menu');
        
        $this->forge->createTable('m_role_menu');
    }

    public function down()
    {
        $this->forge->dropTable('m_role_menu');
    }
}
