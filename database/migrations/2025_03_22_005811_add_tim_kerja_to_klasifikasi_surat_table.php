<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('klasifikasi_surat', function (Blueprint $table) {
            if (!Schema::hasColumn('klasifikasi_surat', 'tim_kerja_id')) {
                $table->foreignId('tim_kerja_id')->after('id')->constrained('tim_kerjas')->onDelete('cascade');
            }
        });
    }

    public function down()
    {
        Schema::table('klasifikasi_surat', function (Blueprint $table) {
            if (Schema::hasColumn('klasifikasi_surat', 'tim_kerja_id')) {
                $table->dropForeign(['tim_kerja_id']);
                $table->dropColumn('tim_kerja_id');
            }
        });
    }
};

