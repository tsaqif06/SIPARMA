<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Facility;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Panggil semua seeder tabel yang sudah dibuat
        $this->call([
            UsersTableSeeder::class,
            DestinationsTableSeeder::class,
            PlacesTableSeeder::class,
            AdminDestinationsTableSeeder::class,
            AdminPlacesTableSeeder::class,
            FacilitiesTableSeeder::class,
            RidesTableSeeder::class,
            // RestaurantMenusTableSeeder::class,
            // GalleryDestinationsTableSeeder::class,
            // GalleryRestaurantsTableSeeder::class,
            // TransactionsTableSeeder::class,
            // ReviewsTableSeeder::class,
            // ComplaintsTableSeeder::class,
            // PromosTableSeeder::class,
            // RecommendationsTableSeeder::class,
            // RecommendationImagesTableSeeder::class,
        ]);
    }
}
