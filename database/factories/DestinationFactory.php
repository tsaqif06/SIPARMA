<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class DestinationFactory extends Factory
{
    public function definition()
    {
        return [
            'name' => $this->faker->city(),
            'description' => $this->faker->paragraph(),
            'location' => $this->faker->address(),
            'price' => $this->faker->randomFloat(2, 10, 100),
            'open_time' => $this->faker->time(),
            'close_time' => $this->faker->time(),
            'operational_status' => $this->faker->randomElement(['open', 'closed', 'holiday']),
            'weekday_price' => $this->faker->randomFloat(2, 10, 50),
            'weekend_price' => $this->faker->randomFloat(2, 20, 100),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
