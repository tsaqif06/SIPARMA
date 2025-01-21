<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class FacilityFactory extends Factory
{
    public function definition()
    {
        return [
            'item_type' => $this->faker->randomElement(['destination', 'place']),
            'item_id' => $this->faker->numberBetween(1, 5),
            'name' => $this->faker->company(),
            'description' => $this->faker->paragraph(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
