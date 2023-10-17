<?php

use App\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'nama' => 'Admin',
            'username' => 'adminpamdes@mail.com',
            'password' => bcrypt('admin123456'),
            'role' => '1',
            'avatar' => 'default.png',
        ]);
    }
}
