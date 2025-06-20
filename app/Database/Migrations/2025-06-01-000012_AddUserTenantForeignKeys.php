<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddUserTenantForeignKeys extends Migration
{
    public function up()
    {
        // Add foreign key constraints for tenant relationships in m_user table
        $sql = "ALTER TABLE m_user 
                ADD CONSTRAINT `m_user_intTenantID_foreign` 
                FOREIGN KEY (`intTenantID`) 
                REFERENCES `m_tenants`(`intTenantID`) 
                ON DELETE SET NULL ON UPDATE CASCADE";
        $this->db->query($sql);

        $sql = "ALTER TABLE m_user 
                ADD CONSTRAINT `m_user_intDefaultTenantID_foreign` 
                FOREIGN KEY (`intDefaultTenantID`) 
                REFERENCES `m_tenants`(`intTenantID`) 
                ON DELETE SET NULL ON UPDATE CASCADE";
        $this->db->query($sql);
    }

    public function down()
    {
        // Remove foreign key constraints
        $sql = "ALTER TABLE m_user DROP FOREIGN KEY `m_user_intTenantID_foreign`";
        $this->db->query($sql);

        $sql = "ALTER TABLE m_user DROP FOREIGN KEY `m_user_intDefaultTenantID_foreign`";
        $this->db->query($sql);
    }
}
