<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class RideFactory extends Factory
{
    public function definition()
    {
        return [
            'destination_id' => $this->faker->numberBetween(1, 5),
            'name' => $this->faker->city(),
            'slug' => function (array $attributes) {
                return Str::slug($attributes['name']);
            },
            'description' => $this->faker->paragraph(),
            'open_time' => $this->faker->time(),
            'close_time' => $this->faker->time(),
            'operational_status' => $this->faker->randomElement(['open', 'closed']),
            'price' => $this->faker->randomFloat(2, 10, 100),
            'weekend_price' => $this->faker->randomFloat(2, 20, 100),
            'children_price' => $this->faker->randomFloat(2, 20, 100),
            'min_age' => 5,
            'min_height' => 120,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
