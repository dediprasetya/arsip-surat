<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('surat', function (Blueprint $table) {
            $table->id();
            $table->string('klasifikasi')->default('Umum');
            $table->string('nomor_surat')->unique();
            $table->date('tanggal_surat');
            $table->string('perihal');
            $table->text('isi_surat');
            $table->string('file_surat')->nullable();
            $table->enum('jenis_surat', ['masuk', 'keluar'])->default('masuk');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('surat');
    }
};
