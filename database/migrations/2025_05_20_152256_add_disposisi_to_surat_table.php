<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDisposisiToSuratTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('surat', function (Blueprint $table) {
            $table->unsignedBigInteger('disposisi_oleh')->nullable()->after('tujuan_disposisi');
            $table->enum('status_disposisi', ['belum', 'sudah'])->default('belum')->after('disposisi_oleh');

            $table->foreign('disposisi_oleh')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('surat', function (Blueprint $table) {
            $table->dropForeign(['disposisi_oleh']);
            $table->dropColumn(['disposisi_oleh', 'status_disposisi']);
        });
    }


    
}
