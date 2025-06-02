<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddTenantOwnerForeignKey extends Migration
{
    public function up()
    {
        // Add foreign key constraint for intOwnerID
        $this->forge->addForeignKey('intOwnerID', 'm_user', 'intUserID', 'SET NULL', 'CASCADE', 'm_tenants');
        $sql = "ALTER TABLE m_tenants ADD CONSTRAINT `m_tenants_intOwnerID_foreign` FOREIGN KEY (`intOwnerID`) REFERENCES `m_user`(`intUserID`) ON DELETE SET NULL ON UPDATE CASCADE";
        $this->db->query($sql);
    }

    public function down()
    {
        // Remove foreign key constraint
        $sql = "ALTER TABLE m_tenants DROP FOREIGN KEY `m_tenants_intOwnerID_foreign`";
        $this->db->query($sql);
    }
}
