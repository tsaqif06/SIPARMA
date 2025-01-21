<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Place;

class PlacesTableSeeder extends Seeder
{
    public function run()
    {
        Place::factory(5)->create();
    }
}
