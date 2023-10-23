<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUkuranKertasNotaToSettingGlobal extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('setting_global', function (Blueprint $table) {
            $table->double('ukuran_kertas_nota')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('setting_global', function (Blueprint $table) {
            $table->dropColumn('ukuran_kertas_nota');
        });
    }
}
