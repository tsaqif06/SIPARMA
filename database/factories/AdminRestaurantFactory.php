<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class AdminRestaurantFactory extends Factory
{
    public function definition()
    {
        return [
            'user_id' => $this->faker->numberBetween(2, 10), // Foreign key ke tbl_users
            'restaurant_id' => $this->faker->numberBetween(1, 20), // Foreign key ke tbl_restaurants
            'approval_status' => $this->faker->randomElement(['pending', 'approved', 'rejected']),
            'ownership_docs' => $this->faker->text(100),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
