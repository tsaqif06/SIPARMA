<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AdminDestination;

class AdminDestinationsTableSeeder extends Seeder
{
    public function run()
    {
        AdminDestination::factory(5)->create();
    }
}
