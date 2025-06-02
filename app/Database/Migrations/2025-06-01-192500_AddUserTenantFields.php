<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddUserTenantFields extends Migration
{    public function up()
    {
        // Fields are now handled in CreateUserTable migration
    }

    public function down()
    {
        // No action needed as fields are handled in CreateUserTable migration
    }
}
