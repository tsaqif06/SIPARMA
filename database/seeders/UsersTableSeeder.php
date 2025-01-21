<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@gmail.com',
            'password' => bcrypt('superadmin123'), // Ganti dengan password yang aman
            'role' => 'superadmin',
            'profile_picture' => 'https://via.placeholder.com/300', // Placeholder image
            'phone_number' => '085238288213',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        User::factory(5)->create(); // Buat 50 user
    }
}
