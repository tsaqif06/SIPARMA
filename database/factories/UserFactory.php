<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => bcrypt('password123'),
            'role' => $this->faker->randomElement(['user', 'admin_wisata', 'admin_tempat']),
            'profile_picture' => 'https://picsum.photos/200',
            'phone_number' => $this->faker->phoneNumber(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
