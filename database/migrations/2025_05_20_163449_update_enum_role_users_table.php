<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class UpdateEnumRoleUsersTable extends Migration
{
    public function up()
    {
        DB::statement("ALTER TABLE users MODIFY role ENUM('admin', 'staff', 'kepala_bidang') NOT NULL DEFAULT 'staff'");
    }

    public function down()
    {
        DB::statement("ALTER TABLE users MODIFY role ENUM('admin', 'staff') NOT NULL DEFAULT 'staff'");
    }
}

