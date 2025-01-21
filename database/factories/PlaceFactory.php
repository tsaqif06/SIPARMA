<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class PlaceFactory extends Factory
{
    public function definition()
    {
        return [
            'name' => $this->faker->company(),
            'slug' => function (array $attributes) {
                return Str::slug($attributes['name']);
            },
            'description' => $this->faker->paragraph(),
            'open_time' => $this->faker->time(),
            'close_time' => $this->faker->time(),
            'operational_status' => $this->faker->randomElement(['open', 'closed']),
            'location' => $this->faker->address(),
            'type' => $this->faker->randomElement(['restoran', 'penginapan']),
            'destination_id' => $this->faker->numberBetween(1, 5), // Foreign key ke tbl_destinations
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
