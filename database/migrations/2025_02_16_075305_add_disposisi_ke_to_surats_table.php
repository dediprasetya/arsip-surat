<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDisposisiKeToSuratsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('surat', function (Blueprint $table) {
            $table->string('disposisi_ke')->nullable()->after('isi_surat');
        });
    }

    public function down()
    {
        Schema::table('surat', function (Blueprint $table) {
            $table->dropColumn('disposisi_ke');
        });
    }
}
