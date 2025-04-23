<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSuratKeluarTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('surat_keluar', function (Blueprint $table) {
            $table->id();
            $table->foreignId('klasifikasi_id')->constrained('klasifikasi_surat')->onDelete('cascade');
            $table->string('nomor_surat');
            $table->date('tanggal_surat');
            $table->string('perihal');
            $table->string('tujuan_surat');
            $table->text('isi_surat');
            $table->text('catatan')->nullable();
            $table->string('file_surat')->nullable();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // pembuat surat
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('surat_keluar');
    }
}
