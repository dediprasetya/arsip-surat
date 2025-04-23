<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('surat', function (Blueprint $table) {
            $table->date('tanggal_penerimaan_surat')->nullable()->after('tanggal_surat'); 
            $table->date('tanggal_diterima_staf')->nullable()->after('tanggal_penerimaan_surat');
            $table->date('tanggal_ditindaklanjuti_staf')->nullable()->after('tanggal_diterima_staf');
            $table->date('tanggal_disposisi')->nullable()->after('tanggal_ditindaklanjuti_staf');
            $table->renameColumn('disposisi_ke', 'isi_disposisi');
            $table->dropColumn('jenis_surat');
        });
    }

    public function down()
    {
        Schema::table('surat', function (Blueprint $table) {
            $table->dropColumn(['tanggal_penerimaan_surat', 'tanggal_diterima_staf', 'tanggal_ditindaklanjuti_staf', 'tanggal_disposisi']);
            $table->renameColumn('isi_disposisi', 'disposisi_ke');
            $table->string('jenis_surat')->nullable();
        });
    }
};

