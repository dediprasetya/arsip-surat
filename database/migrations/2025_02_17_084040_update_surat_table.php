<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('surat', function (Blueprint $table) {
            $table->string('asal_surat')->nullable()->after('isi_surat');
            $table->enum('status_surat', ['belum diterima', 'sudah diterima', 'sudah ditindaklanjuti'])->default('belum diterima')->after('asal_surat');
            $table->unsignedBigInteger('tujuan_disposisi')->nullable()->after('status_surat');

            // Menambahkan foreign key ke tabel users (anggap bahwa admin membuat daftar user yang bisa menerima disposisi)
            $table->foreign('tujuan_disposisi')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('surat', function (Blueprint $table) {
            $table->dropColumn('asal_surat');
            $table->dropColumn('status_surat');
            $table->dropForeign(['tujuan_disposisi']);
            $table->dropColumn('tujuan_disposisi');
        });
    }
};

