<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransaksisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaksi', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pelanggan_id')->unsigned();
            $table->string('kode', 11);
            $table->string('bulan_tahun', 7);
            $table->double('pemakaian_sebelumnya');
            $table->double('pemakaian_saat_ini');
            $table->double('tarif_per_meter')->nullable();
            $table->double('biaya_pemeliharaan')->nullable();
            $table->double('biaya_administrasi')->nullable();
            $table->boolean('status')->default(false);
            $table->boolean('is_delete')->default(false);
            $table->timestamps();

            $table->foreign('pelanggan_id')->references('id')->on('pelanggan');
        });

        // status 
        // true = Lunas 
        // false = Belum Lunas
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transaksi');
    }
}
