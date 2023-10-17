<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePelanggansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pelanggan', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 7);
            $table->string('nama_lengkap', 100);
            $table->string('nomor_hp_wa', 13)->nullable();
            $table->string('alamat');
            $table->boolean('status')->default(true);
            $table->boolean('is_delete')->default(false);
            $table->timestamps();
        });

        // Status 
        // true = Aktif 
        // false = Non Aktif 
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pelanggan');
    }
}
