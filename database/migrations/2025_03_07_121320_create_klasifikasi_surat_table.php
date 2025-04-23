<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('klasifikasi_surat', function (Blueprint $table) {
            $table->id();
            $table->string('kode')->unique();
            $table->string('nama_klasifikasi');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('klasifikasi_surat');
    }
};
