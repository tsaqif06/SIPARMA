<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AdminRestaurant;

class AdminRestaurantsTableSeeder extends Seeder
{
    public function run()
    {
        AdminRestaurant::factory(3)->create();
    }
}
