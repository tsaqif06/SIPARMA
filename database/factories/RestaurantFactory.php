<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class RestaurantFactory extends Factory
{
    public function definition()
    {
        return [
            'name' => $this->faker->company(),
            'slug' => function (array $attributes) {
                return Str::slug($attributes['name']);
            },
            'description' => $this->faker->paragraph(),
            'location' => $this->faker->address(),
            'destination_id' => $this->faker->numberBetween(1, 20), // Foreign key ke tbl_destinations
            'status' => $this->faker->randomElement(['active', 'inactive']),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
