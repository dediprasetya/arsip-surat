<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('tim_kerjas', function (Blueprint $table) {
            $table->id();
            $table->string('nama_tim_kerja')->unique();
            $table->foreignId('bidang_id')->constrained('bidangs')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tim_kerjas');
    }
};
