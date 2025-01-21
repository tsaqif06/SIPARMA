<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Destination;

class DestinationsTableSeeder extends Seeder
{
    public function run()
    {
        Destination::factory(5)->create();
    }
}
