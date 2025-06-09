<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSubscriptionAndPricingTables extends Migration
{
    public function up()
    {
        // Subscription Plans Table
        $this->forge->addField([
            'intPlanID' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'txtGUID' => [
                'type'       => 'VARCHAR',
                'constraint' => 36,
            ],
            'txtName' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
            ],
            'txtCode' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
            ],
            'decAmount' => [
                'type'       => 'DECIMAL',
                'constraint' => '12,2',
                'default'    => 0.00,
            ],
            'intDuration' => [
                'type'       => 'INT',
                'default'    => 1,
                'comment'    => 'Duration in months',
            ],
            'jsonFeatures' => [
                'type'    => 'JSON',
                'null'    => true,
                'comment' => 'Features included in the plan',
            ],
            'txtDescription' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'bitActive' => [
                'type'    => 'TINYINT',
                'constraint' => 1,
                'default' => 1,
            ],
            'txtCreatedBy' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'default'    => 'system',
            ],
            'dtmCreatedDate' => [
                'type'    => 'DATETIME',
                'null'    => true,
                'default' => 'CURRENT_TIMESTAMP',
            ],
            'txtUpdatedBy' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'      => true,
            ],
            'dtmUpdatedDate' => [
                'type' => 'DATETIME',
                'null' => true,
                'default' => 'CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
            ],
        ]);
        $this->forge->addKey('intPlanID', true);
        $this->forge->addUniqueKey('txtGUID');
        $this->forge->addUniqueKey('txtCode');
        $this->forge->createTable('m_subscription_plans');

        // Subscription Features Table
        $this->forge->addField([
            'intFeatureID' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'txtGUID' => [
                'type'       => 'VARCHAR',
                'constraint' => 36,
            ],
            'txtName' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'txtCode' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
            ],
            'txtDescription' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'bitActive' => [
                'type'    => 'TINYINT',
                'constraint' => 1,
                'default' => 1,
            ],
            'txtCreatedBy' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'default'    => 'system',
            ],
            'dtmCreatedDate' => [
                'type'    => 'DATETIME',
                'null'    => true,
                'default' => 'CURRENT_TIMESTAMP',
            ],
            'txtUpdatedBy' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'      => true,
            ],
            'dtmUpdatedDate' => [
                'type' => 'DATETIME',
                'null' => true,
                'default' => 'CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
            ],
        ]);
        $this->forge->addKey('intFeatureID', true);
        $this->forge->addUniqueKey('txtGUID');
        $this->forge->addUniqueKey('txtCode');
        $this->forge->createTable('m_subscription_features');

        // Plan Features Mapping Table
        $this->forge->addField([
            'intPlanFeatureID' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'intPlanID' => [
                'type'     => 'INT',
                'unsigned' => true,
            ],
            'intFeatureID' => [
                'type'     => 'INT',
                'unsigned' => true,
            ],
            'jsonLimits' => [
                'type'    => 'JSON',
                'null'    => true,
                'comment' => 'Feature limits/quotas specific to this plan',
            ],
            'bitActive' => [
                'type'    => 'TINYINT',
                'constraint' => 1,
                'default' => 1,
            ],
            'txtCreatedBy' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'default'    => 'system',
            ],
            'dtmCreatedDate' => [
                'type'    => 'DATETIME',
                'null'    => true,
                'default' => 'CURRENT_TIMESTAMP',
            ],
            'txtUpdatedBy' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'      => true,
            ],
            'dtmUpdatedDate' => [
                'type' => 'DATETIME',
                'null' => true,
                'default' => 'CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
            ],
        ]);
        $this->forge->addKey('intPlanFeatureID', true);
        $this->forge->addKey('intPlanID');
        $this->forge->addKey('intFeatureID');
        $this->forge->addForeignKey('intPlanID', 'm_subscription_plans', 'intPlanID', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('intFeatureID', 'm_subscription_features', 'intFeatureID', 'CASCADE', 'CASCADE');
        $this->forge->createTable('m_plan_features');
    }

    public function down()
    {
        // Drop tables in reverse order to avoid foreign key constraints
        $this->forge->dropTable('m_plan_features', true);
        $this->forge->dropTable('m_subscription_features', true);
        $this->forge->dropTable('m_subscription_plans', true);
    }
}
