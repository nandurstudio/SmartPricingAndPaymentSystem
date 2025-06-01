<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddUserTenantFields extends Migration
{
    public function up()
    {
        // Tambah kolom di tabel m_user
        $fields = [
            'is_tenant_owner' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
                'after' => 'tenant_id'
            ],
            'default_tenant_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
                'after' => 'is_tenant_owner'
            ]
        ];

        $this->forge->addColumn('m_user', $fields);
    }

    public function down()
    {
        // Hapus kolom dari m_user
        $this->forge->dropColumn('m_user', ['is_tenant_owner', 'default_tenant_id']);
    }
}
