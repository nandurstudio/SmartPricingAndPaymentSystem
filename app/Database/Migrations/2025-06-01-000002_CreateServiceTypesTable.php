<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateServiceTypesTable extends Migration
{
    public function up()
    {
        // Create Service Types Table
        $this->forge->addField([
            'intServiceTypeID' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],            
            'txtGUID' => [
                'type'       => 'VARCHAR',
                'constraint' => 36,
                'null'       => false,
            ],
            'txtName' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => false,
            ],
            'txtSlug' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => false,
            ],
            'txtDescription' => [
                'type'       => 'TEXT',
                'null'       => true,
            ],
            'txtIcon' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'txtCategory' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],
            'bitIsSystem' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
            ],
            'bitIsApproved' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
            ],
            'intRequestedBy' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'intApprovedBy' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'dtmApprovedDate' => [
                'type'       => 'DATETIME',
                'null'       => true,
            ],
            'jsonDefaultAttributes' => [
                'type'       => 'JSON',
                'null'       => true,
            ],
            'bitActive' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 1,
            ],
            'txtCreatedBy' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => false,
                'default'    => 'system',
            ],
            'dtmCreatedDate' => [
                'type'       => 'DATETIME',
                'null'       => true,
                'default'    => date('Y-m-d H:i:s'),
            ],
            'txtUpdatedBy' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => false,
                'default'    => 'system',
            ],
            'dtmUpdatedDate' => [
                'type'       => 'DATETIME',
                'null'       => true,
            ]
        ]);
          
        $this->forge->addKey('intServiceTypeID', true);
        $this->forge->addUniqueKey(['txtGUID', 'txtSlug']);
        $this->forge->addForeignKey('intRequestedBy', 'm_user', 'intUserID', 'SET NULL', 'CASCADE');
        $this->forge->addForeignKey('intApprovedBy', 'm_user', 'intUserID', 'SET NULL', 'CASCADE');
        $this->forge->createTable('m_service_types');
    }

    public function down()
    {
        // Drop table if exists
        $this->forge->dropTable('m_service_types');
    }
}
