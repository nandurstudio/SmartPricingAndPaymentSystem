<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateMenuTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'intMenuID' => [
                'type' => 'INT',
                'constraint' => 10,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'txtMenuName' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => false,
            ],
            'txtMenuLink' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'txtIcon' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
            ],
            'intParentID' => [
                'type' => 'INT',
                'constraint' => 10,
                'unsigned' => true,
                'null' => true,
            ],
            'intSortOrder' => [
                'type' => 'INT',
                'constraint' => 10,
                'default' => 0,
            ],
            'bitActive' => [
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
        ]);

        $this->forge->addKey('intMenuID', true);
        $this->forge->addForeignKey('intParentID', 'm_menu', 'intMenuID', 'SET NULL', 'CASCADE');
        $this->forge->createTable('m_menu');
    }

    public function down()
    {
        $this->forge->dropTable('m_menu');
    }
}
