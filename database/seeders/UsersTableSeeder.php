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
            'email' => 'superadmin@example.com',
            'password' => bcrypt('superadminpassword'), // Ganti dengan password yang aman
            'role' => 'superadmin',
            'profile_picture' => 'https://via.placeholder.com/300', // Placeholder image
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        User::factory(9)->create(); // Buat 50 user
    }
}
