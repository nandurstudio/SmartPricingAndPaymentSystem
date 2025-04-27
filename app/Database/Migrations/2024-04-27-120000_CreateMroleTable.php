<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateMroleTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'intRoleID' => [
                'type'           => 'INT',
                'constraint'     => 10,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'txtRoleName' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
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
                'type'    => 'TINYINT',
                'constraint' => 1,
                'null'    => true,
                'default' => 1,
            ],
            'txtCreatedBy' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
                'default'    => 'system',
            ],
            'dtmCreatedDate' => [
                'type' => 'TIMESTAMP',
                'null' => true,
                'default' => new \CodeIgniter\Database\RawSql('CURRENT_TIMESTAMP'),
            ],
            'txtLastUpdatedBy' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
                'default'    => 'system',
            ],
            'dtmLastUpdatedDate' => [
                'type' => 'TIMESTAMP',
                'null' => true,
                'default' => new \CodeIgniter\Database\RawSql('CURRENT_TIMESTAMP'),
                'on_update' => new \CodeIgniter\Database\RawSql('CURRENT_TIMESTAMP'),
            ],
            'txtGUID' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
            ],
        ]);

        $this->forge->addKey('intRoleID', true);
        $this->forge->createTable('mrole');
    }

    public function down()
    {
        $this->forge->dropTable('mrole');
    }
}
