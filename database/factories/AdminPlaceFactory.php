<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class AdminPlaceFactory extends Factory
{
    public function definition()
    {
        return [
            'user_id' => $this->faker->numberBetween(2, 5), // Foreign key ke tbl_users
            'place_id' => $this->faker->numberBetween(1, 5), // Foreign key ke tbl_restaurants
            'approval_status' => $this->faker->randomElement(['pending', 'approved', 'rejected']),
            'ownership_docs' => $this->faker->text(100),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
