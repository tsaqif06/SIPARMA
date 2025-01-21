<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AdminPlace;

class AdminPlacesTableSeeder extends Seeder
{
    public function run()
    {
        AdminPlace::factory(count: 5)->create();
    }
}
