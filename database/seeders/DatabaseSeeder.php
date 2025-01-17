<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
            RestaurantsTableSeeder::class,
            AdminDestinationsTableSeeder::class,
            AdminRestaurantsTableSeeder::class,
            // RestaurantMenusTableSeeder::class,
            // GalleryDestinationsTableSeeder::class,
            // GalleryRestaurantsTableSeeder::class,
            // MidtransTableSeeder::class,
            // TransactionsTableSeeder::class,
            // ReviewsTableSeeder::class,
            // ComplaintsTableSeeder::class,
            // PromosTableSeeder::class,
            // RecommendationsTableSeeder::class,
            // RecommendationImagesTableSeeder::class,
        ]);
    }
}
