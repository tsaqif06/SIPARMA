<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class DestinationFactory extends Factory
{
    public function definition()
    {
        return [
            'name' => $this->faker->city(),
            'slug' => function (array $attributes) {
                return Str::slug($attributes['name']);
            },
            'type' => $this->faker->randomElement(['alam', 'wahana']),
            'description' => $this->faker->paragraph(),
            'location' => $this->faker->address(),
            'open_time' => $this->faker->time(),
            'close_time' => $this->faker->time(),
            'operational_status' => $this->faker->randomElement(['open', 'closed', 'holiday']),
            'price' => $this->faker->randomFloat(2, 10, 100),
            'weekend_price' => $this->faker->randomFloat(2, 20, 100),
            'children_price' => $this->faker->randomFloat(2, 20, 100),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
