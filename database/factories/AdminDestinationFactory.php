<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class AdminDestinationFactory extends Factory
{
    public function definition()
    {
        return [
            'user_id' => $this->faker->numberBetween(2, 5), // Foreign key ke tbl_users
            'destination_id' => $this->faker->numberBetween(1, int2: 5), // Foreign key ke tbl_destinations
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
