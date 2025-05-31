<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateRoleTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'intRoleID' => [
                'type' => 'INT',
                'constraint' => 10,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'txtRoleName' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => false,
            ],
            'txtRoleDesc' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'txtRoleNote' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'bitStatus' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 1,
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
            'txtGUID' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
            ],
        ]);

        $this->forge->addKey('intRoleID', true);
        $this->forge->createTable('m_role');
    }

    public function down()
    {
        $this->forge->dropTable('m_role');
    }
}
