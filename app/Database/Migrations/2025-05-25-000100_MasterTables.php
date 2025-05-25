<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class MasterTables extends Migration
{
    public function up()
    {
        // Drop tables if exist (to avoid error on re-run)
        $this->forge->dropTable('tr_audit_log', true);
        $this->forge->dropTable('tr_service_custom_values', true);
        $this->forge->dropTable('tr_bookings', true);
        $this->forge->dropTable('m_service_type_attributes', true);
        $this->forge->dropTable('m_schedules', true);
        $this->forge->dropTable('m_services', true);
        $this->forge->dropTable('m_role_menu', true);
        $this->forge->dropTable('m_menu', true);
        $this->forge->dropTable('m_user', true);
        $this->forge->dropTable('m_service_types', true);
        $this->forge->dropTable('m_tenants', true);
        $this->forge->dropTable('m_role', true);

        // m_role
        $this->forge->addField([
            'intRoleID' => [ 'type' => 'INT', 'constraint' => 10, 'unsigned' => true, 'auto_increment' => true ],
            'txtRoleName' => [ 'type' => 'VARCHAR', 'constraint' => 50 ],
            'txtRoleDesc' => [ 'type' => 'TEXT', 'null' => true ],
            'txtRoleNote' => [ 'type' => 'TEXT', 'null' => true ],
            'bitStatus' => [ 'type' => 'TINYINT', 'constraint' => 1, 'default' => 1 ],
            'txtCreatedBy' => [ 'type' => 'VARCHAR', 'constraint' => 50, 'default' => 'system' ],
            'dtmCreatedDate' => [ 'type' => 'TIMESTAMP', 'null' => true ],
            'txtLastUpdatedBy' => [ 'type' => 'VARCHAR', 'constraint' => 50, 'default' => 'system' ],
            'dtmLastUpdatedDate' => [ 'type' => 'TIMESTAMP', 'null' => true ],
            'txtGUID' => [ 'type' => 'VARCHAR', 'constraint' => 50, 'default' => '' ],
        ]);
        $this->forge->addKey('intRoleID', true);
        $this->forge->createTable('m_role');

        // m_tenants
        $this->forge->addField([
            'id' => [ 'type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true ],
            'guid' => [ 'type' => 'VARCHAR', 'constraint' => 36 ],
            'name' => [ 'type' => 'VARCHAR', 'constraint' => 255 ],
            'slug' => [ 'type' => 'VARCHAR', 'constraint' => 255 ],
            'domain' => [ 'type' => 'VARCHAR', 'constraint' => 255, 'null' => true ],
            'service_type_id' => [ 'type' => 'INT', 'constraint' => 11, 'unsigned' => true ],
            'owner_id' => [ 'type' => 'INT', 'constraint' => 11, 'unsigned' => true ],
            'subscription_plan' => [ 'type' => 'ENUM', 'constraint' => ['free','basic','premium','enterprise'], 'default' => 'free' ],
            'status' => [ 'type' => 'ENUM', 'constraint' => ['active','inactive','suspended','pending'], 'default' => 'pending' ],
            'settings' => [ 'type' => 'JSON', 'null' => true ],
            'payment_settings' => [ 'type' => 'JSON', 'null' => true ],
            'is_active' => [ 'type' => 'BOOLEAN', 'default' => true ],
            'created_date' => [ 'type' => 'DATETIME', 'null' => true ],
            'created_by' => [ 'type' => 'VARCHAR', 'constraint' => 255, 'null' => true ],
            'updated_date' => [ 'type' => 'DATETIME', 'null' => true ],
            'updated_by' => [ 'type' => 'VARCHAR', 'constraint' => 255, 'null' => true ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('guid');
        $this->forge->addUniqueKey('slug');
        $this->forge->addKey('service_type_id');
        $this->forge->addKey('owner_id');
        $this->forge->createTable('m_tenants');

        // m_service_types
        $this->forge->addField([
            'id' => [ 'type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true ],
            'guid' => [ 'type' => 'VARCHAR', 'constraint' => 36 ],
            'name' => [ 'type' => 'VARCHAR', 'constraint' => 255 ],
            'slug' => [ 'type' => 'VARCHAR', 'constraint' => 255 ],
            'description' => [ 'type' => 'TEXT', 'null' => true ],
            'icon' => [ 'type' => 'VARCHAR', 'constraint' => 255, 'null' => true ],
            'category' => [ 'type' => 'VARCHAR', 'constraint' => 100, 'null' => true ],
            'is_system' => [ 'type' => 'BOOLEAN', 'default' => false ],
            'is_approved' => [ 'type' => 'BOOLEAN', 'default' => false ],
            'requested_by' => [ 'type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true ],
            'approved_by' => [ 'type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true ],
            'approved_date' => [ 'type' => 'DATETIME', 'null' => true ],
            'default_attributes' => [ 'type' => 'JSON', 'null' => true ],
            'is_active' => [ 'type' => 'BOOLEAN', 'default' => true ],
            'created_date' => [ 'type' => 'DATETIME', 'null' => true ],
            'created_by' => [ 'type' => 'VARCHAR', 'constraint' => 255, 'null' => true ],
            'updated_date' => [ 'type' => 'DATETIME', 'null' => true ],
            'updated_by' => [ 'type' => 'VARCHAR', 'constraint' => 255, 'null' => true ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('guid');
        $this->forge->addUniqueKey('slug');
        $this->forge->addKey('category');
        $this->forge->createTable('m_service_types');

        // m_user
        $this->forge->addField([
            'intUserID' => [ 'type' => 'INT', 'constraint' => 10, 'unsigned' => true, 'auto_increment' => true ],
            'intRoleID' => [ 'type' => 'INT', 'constraint' => 10, 'unsigned' => true, 'default' => 5 ],
            'tenant_id' => [ 'type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true ],
            'txtUserName' => [ 'type' => 'VARCHAR', 'constraint' => 50, 'default' => 'dummy.nick' ],
            'txtFullName' => [ 'type' => 'VARCHAR', 'constraint' => 100 ],
            'txtEmail' => [ 'type' => 'VARCHAR', 'constraint' => 100, 'default' => 'dummy@email.com' ],
            'txtPassword' => [ 'type' => 'VARCHAR', 'constraint' => 255 ],
            'bitActive' => [ 'type' => 'TINYINT', 'constraint' => 1, 'default' => 1 ],
            'bitOnlineStatus' => [ 'type' => 'TINYINT', 'constraint' => 1, 'default' => 0 ],
            'dtmLastLogin' => [ 'type' => 'DATETIME', 'null' => true ],
            'txtCreatedBy' => [ 'type' => 'VARCHAR', 'constraint' => 50, 'default' => 'system' ],
            'dtmCreatedDate' => [ 'type' => 'DATETIME', 'null' => true ],
            'txtUpdatedBy' => [ 'type' => 'VARCHAR', 'constraint' => 50, 'default' => 'system' ],
            'dtmUpdatedDate' => [ 'type' => 'DATETIME', 'null' => true ],
            'txtGUID' => [ 'type' => 'VARCHAR', 'constraint' => 50, 'default' => '' ],
            'reset_token' => [ 'type' => 'VARCHAR', 'constraint' => 100, 'null' => true ],
            'token_created_at' => [ 'type' => 'DATETIME', 'null' => true ],
            'google_auth_token' => [ 'type' => 'VARCHAR', 'constraint' => 255, 'null' => true ],
            'txtPhoto' => [ 'type' => 'VARCHAR', 'constraint' => 255, 'default' => 'default.png' ],
            'dtmJoinDate' => [ 'type' => 'DATETIME', 'null' => true ],
        ]);
        $this->forge->addKey('intUserID', true);
        $this->forge->addKey('intRoleID');
        $this->forge->addKey('tenant_id');
        $this->forge->createTable('m_user');

        // m_menu
        $this->forge->addField([
            'intMenuID'      => [ 'type' => 'INT', 'constraint' => 10, 'unsigned' => true, 'auto_increment' => true ],
            'txtMenuName'    => [ 'type' => 'VARCHAR', 'constraint' => 100 ],
            'txtMenuLink'    => [ 'type' => 'VARCHAR', 'constraint' => 255 ],
            'txtIcon'        => [ 'type' => 'VARCHAR', 'constraint' => 50, 'null' => true ],
            'intParentID'    => [ 'type' => 'INT', 'constraint' => 10, 'null' => true ],
            'intSortOrder'   => [ 'type' => 'INT', 'constraint' => 10, 'default' => 0 ],
            'bitActive'      => [ 'type' => 'TINYINT', 'constraint' => 1, 'default' => 1 ],
        ]);
        $this->forge->addKey('intMenuID', true);
        $this->forge->createTable('m_menu');

        // m_role_menu
        $this->forge->addField([
            'intRoleMenuID' => [ 'type' => 'INT', 'constraint' => 10, 'unsigned' => true, 'auto_increment' => true ],
            'intRoleID'     => [ 'type' => 'INT', 'constraint' => 10, 'unsigned' => true ],
            'intMenuID'     => [ 'type' => 'INT', 'constraint' => 10, 'unsigned' => true ],
        ]);
        $this->forge->addKey('intRoleMenuID', true);
        $this->forge->addForeignKey('intRoleID', 'm_role', 'intRoleID', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('intMenuID', 'm_menu', 'intMenuID', 'CASCADE', 'CASCADE');
        $this->forge->createTable('m_role_menu');

        // m_services
        $this->forge->addField([
            'id' => [ 'type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true ],
            'guid' => [ 'type' => 'VARCHAR', 'constraint' => 36 ],
            'tenant_id' => [ 'type' => 'INT', 'constraint' => 11, 'unsigned' => true ],
            'name' => [ 'type' => 'VARCHAR', 'constraint' => 255 ],
            'slug' => [ 'type' => 'VARCHAR', 'constraint' => 255 ],
            'description' => [ 'type' => 'TEXT', 'null' => true ],
            'icon' => [ 'type' => 'VARCHAR', 'constraint' => 255, 'null' => true ],
            'category' => [ 'type' => 'VARCHAR', 'constraint' => 100, 'null' => true ],
            'is_system' => [ 'type' => 'BOOLEAN', 'default' => false ],
            'is_approved' => [ 'type' => 'BOOLEAN', 'default' => false ],
            'requested_by' => [ 'type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true ],
            'approved_by' => [ 'type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true ],
            'approved_date' => [ 'type' => 'DATETIME', 'null' => true ],
            'default_attributes' => [ 'type' => 'JSON', 'null' => true ],
            'is_active' => [ 'type' => 'BOOLEAN', 'default' => true ],
            'created_date' => [ 'type' => 'DATETIME', 'null' => true ],
            'created_by' => [ 'type' => 'VARCHAR', 'constraint' => 255, 'null' => true ],
            'updated_date' => [ 'type' => 'DATETIME', 'null' => true ],
            'updated_by' => [ 'type' => 'VARCHAR', 'constraint' => 255, 'null' => true ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('guid');
        $this->forge->addUniqueKey('slug');
        $this->forge->addKey('category');
        $this->forge->createTable('m_services');

        // m_schedules
        $this->forge->addField([
            'id' => [ 'type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true ],
            'tenant_id' => [ 'type' => 'INT', 'constraint' => 11, 'unsigned' => true ],
            'service_id' => [ 'type' => 'INT', 'constraint' => 11, 'unsigned' => true ],
            'user_id' => [ 'type' => 'INT', 'constraint' => 11, 'unsigned' => true ],
            'role_id' => [ 'type' => 'INT', 'constraint' => 11, 'unsigned' => true ],
            'start_time' => [ 'type' => 'DATETIME' ],
            'end_time' => [ 'type' => 'DATETIME' ],
            'recurrence' => [ 'type' => 'ENUM', 'constraint' => ['none', 'daily', 'weekly', 'monthly'], 'default' => 'none' ],
            'interval' => [ 'type' => 'INT', 'constraint' => 11, 'default' => 1 ],
            'duration' => [ 'type' => 'INT', 'constraint' => 11, 'default' => 60 ],
            'is_active' => [ 'type' => 'BOOLEAN', 'default' => true ],
            'created_date' => [ 'type' => 'DATETIME', 'null' => true ],
            'created_by' => [ 'type' => 'VARCHAR', 'constraint' => 255, 'null' => true ],
            'updated_date' => [ 'type' => 'DATETIME', 'null' => true ],
            'updated_by' => [ 'type' => 'VARCHAR', 'constraint' => 255, 'null' => true ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('m_schedules');

        // m_service_type_attributes
        $this->forge->addField([
            'id' => [ 'type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true ],
            'tenant_id' => [ 'type' => 'INT', 'constraint' => 11, 'unsigned' => true ],
            'service_type_id' => [ 'type' => 'INT', 'constraint' => 11, 'unsigned' => true ],
            'attribute_name' => [ 'type' => 'VARCHAR', 'constraint' => 255 ],
            'attribute_value' => [ 'type' => 'TEXT', 'null' => true ],
            'is_active' => [ 'type' => 'BOOLEAN', 'default' => true ],
            'created_date' => [ 'type' => 'DATETIME', 'null' => true ],
            'created_by' => [ 'type' => 'VARCHAR', 'constraint' => 255, 'null' => true ],
            'updated_date' => [ 'type' => 'DATETIME', 'null' => true ],
            'updated_by' => [ 'type' => 'VARCHAR', 'constraint' => 255, 'null' => true ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('m_service_type_attributes');

        // tr_bookings
        $this->forge->addField([
            'id' => [ 'type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true ],
            'tenant_id' => [ 'type' => 'INT', 'constraint' => 11, 'unsigned' => true ],
            'user_id' => [ 'type' => 'INT', 'constraint' => 11, 'unsigned' => true ],
            'service_id' => [ 'type' => 'INT', 'constraint' => 11, 'unsigned' => true ],
            'schedule_id' => [ 'type' => 'INT', 'constraint' => 11, 'unsigned' => true ],
            'status' => [ 'type' => 'ENUM', 'constraint' => ['pending', 'confirmed', 'canceled', 'completed'], 'default' => 'pending' ],
            'booking_date' => [ 'type' => 'DATETIME' ],
            'created_date' => [ 'type' => 'DATETIME', 'null' => true ],
            'created_by' => [ 'type' => 'VARCHAR', 'constraint' => 255, 'null' => true ],
            'updated_date' => [ 'type' => 'DATETIME', 'null' => true ],
            'updated_by' => [ 'type' => 'VARCHAR', 'constraint' => 255, 'null' => true ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('tr_bookings');

        // tr_service_custom_values
        $this->forge->addField([
            'id' => [ 'type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true ],
            'booking_id' => [ 'type' => 'INT', 'constraint' => 11, 'unsigned' => true ],
            'service_id' => [ 'type' => 'INT', 'constraint' => 11, 'unsigned' => true ],
            'attribute_name' => [ 'type' => 'VARCHAR', 'constraint' => 255 ],
            'attribute_value' => [ 'type' => 'TEXT', 'null' => true ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('tr_service_custom_values');

        // tr_audit_log
        $this->forge->addField([
            'id' => [ 'type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true ],
            'tenant_id' => [ 'type' => 'INT', 'constraint' => 11, 'unsigned' => true ],
            'user_id' => [ 'type' => 'INT', 'constraint' => 11, 'unsigned' => true ],
            'action' => [ 'type' => 'ENUM', 'constraint' => ['insert', 'update', 'delete'], 'default' => 'insert' ],
            'table_name' => [ 'type' => 'VARCHAR', 'constraint' => 255 ],
            'record_id' => [ 'type' => 'INT', 'constraint' => 11, 'unsigned' => true ],
            'old_values' => [ 'type' => 'JSON', 'null' => true ],
            'new_values' => [ 'type' => 'JSON', 'null' => true ],
            'created_date' => [ 'type' => 'DATETIME', 'null' => true ],
            'created_by' => [ 'type' => 'VARCHAR', 'constraint' => 255, 'null' => true ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('tr_audit_log');
    }

    public function down()
    {
        $this->forge->dropTable('tr_audit_log', true);
        $this->forge->dropTable('tr_service_custom_values', true);
        $this->forge->dropTable('tr_bookings', true);
        $this->forge->dropTable('m_service_type_attributes', true);
        $this->forge->dropTable('m_schedules', true);
        $this->forge->dropTable('m_services', true);
        $this->forge->dropTable('m_role_menu', true);
        $this->forge->dropTable('m_menu', true);
        $this->forge->dropTable('m_user', true);
        $this->forge->dropTable('m_service_types', true);
        $this->forge->dropTable('m_tenants', true);
        $this->forge->dropTable('m_role', true);
    }
}
