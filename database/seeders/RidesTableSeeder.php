<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ride;

class RidesTableSeeder extends Seeder
{
    public function run()
    {
        Ride::factory(5)->create();
    }
}
