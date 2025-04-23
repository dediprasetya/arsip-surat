<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('surat', function (Blueprint $table) {
            $table->dropColumn('klasifikasi'); // Menghapus kolom lama (VARCHAR)
        });

        Schema::table('surat', function (Blueprint $table) {
            $table->foreignId('klasifikasi_id')->nullable()->constrained('klasifikasi_surat')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('surat', function (Blueprint $table) {
            $table->dropForeign(['klasifikasi_id']);
            $table->dropColumn('klasifikasi_id');
            $table->string('klasifikasi')->nullable(); // Mengembalikan kolom lama jika rollback
        });
    }
};
