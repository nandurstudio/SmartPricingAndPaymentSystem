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
            'txtGUID' => [
                'type' => 'VARCHAR',
                'constraint' => 36,
                'null' => false,
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
                'constraint' => 5,
                'null' => false,
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
                'null' => false,
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
                'null' => false,
                'default' => 'system',
            ],
            'dtmUpdatedDate' => [
                'type' => 'DATETIME',
                'null' => true,
            ]
        ]);

        $this->forge->addKey('intMenuID', true);
        $this->forge->addForeignKey('intParentID', 'm_menu', 'intMenuID', 'CASCADE', 'SET NULL');
        $this->forge->createTable('m_menu');
    }

    public function down()
    {
        $this->forge->dropTable('m_menu');
    }
}
