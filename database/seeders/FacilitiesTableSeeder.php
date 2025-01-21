<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Facility;

class FacilitiesTableSeeder extends Seeder
{
    public function run()
    {
        Facility::factory(5)->create();
    }
}
