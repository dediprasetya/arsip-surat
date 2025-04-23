<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('disposisi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('surat_id')->constrained('surat')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('tujuan');
            $table->text('isi_disposisi');
            $table->enum('status', ['pending', 'diterima', 'ditolak'])->default('pending');
            $table->dateTime('tanggal_disposisi');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('disposisi');
    }
};
