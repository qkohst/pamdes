<?php

use App\TarifAir;
use App\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UserSeeder::class);

        User::create([
            'nama' => 'Admin',
            'username' => 'adminpamdes@mail.com',
            'password' => bcrypt('admin123456'),
            'role' => '1',
            'avatar' => 'default.png',
        ]);

        TarifAir::create([
            'tarif_per_meter' => 4000,
            'biaya_pemeliharaan' => 5000,
            'biaya_administrasi' => 6000,
        ]);
    }
}
