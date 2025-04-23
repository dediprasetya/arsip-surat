<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBidangTimkerjaNomoragendaToSurat extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('surat', function (Blueprint $table) {
            $table->unsignedBigInteger('bidang_id')->nullable()->after('id');
            $table->unsignedBigInteger('tim_kerja_id')->nullable()->after('bidang_id');
            $table->string('nomor_agenda_umum')->nullable()->after('tim_kerja_id');
    
            $table->foreign('bidang_id')->references('id')->on('bidangs')->onDelete('set null');
            $table->foreign('tim_kerja_id')->references('id')->on('tim_kerjas')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('surats', function (Blueprint $table) {
            $table->dropForeign(['bidang_id']);
            $table->dropForeign(['tim_kerja_id']);
            $table->dropColumn(['bidang_id', 'tim_kerja_id', 'nomor_agenda_umum']);
        });
    }
}
