<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('username');
            $table->string('password');
            $table->enum('role', ['1', '2']);
            $table->boolean('status')->default(true);
            $table->string('avatar');
            $table->boolean('is_delete')->default(false);
            $table->rememberToken();
            $table->timestamps();
        });

        // Role 
        // 1 = Admin 
        // 2 = Pelanggan

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
        Schema::dropIfExists('user');
    }
}
