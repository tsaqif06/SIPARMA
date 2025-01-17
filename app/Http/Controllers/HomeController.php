<?php

namespace App\Http\Controllers;

use App\Models\Destination;
use App\Models\Restaurant;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Menampilkan halaman wisata alam.
     */
    public function wisataAlam()
    {
        // Destinasi alam yang direkomendasikan
        $recommendedDestinations = Destination::where('type', 'alam')->limit(4)->get();

        // Destinasi alam dengan promo yang valid
        $promoDestinations = Destination::where('type', 'alam')
            ->whereHas('promo', function ($query) {
                $query->where('discount', '>', 0)
                    ->whereDate('valid_from', '<=', now())
                    ->whereDate('valid_until', '>=', now());
            })
            ->limit(4)
            ->get();

        return view('home.alam', compact('recommendedDestinations', 'promoDestinations'));
    }

    /**
     * Menampilkan halaman wisata wahana.
     */
    public function wisataWahana()
    {
        // Destinasi wahana yang direkomendasikan
        $recommendedDestinations = Destination::where('type', 'wahana')->limit(4)->get();

        // Destinasi wahana dengan promo yang valid
        $promoDestinations = Destination::where('type', 'wahana')
            ->whereHas('promo', function ($query) {
                $query->where('discount', '>', 0)
                    ->whereDate('valid_from', '<=', now())
                    ->whereDate('valid_until', '>=', now());
            })
            ->limit(4)
            ->get();

        return view('home.wahana', compact('recommendedDestinations', 'promoDestinations'));
    }

    /**
     * Menampilkan halaman restoran.
     */
    public function restoran()
    {
        // Restoran dengan rating tertinggi berdasarkan rata-rata rating dari review
        $topRatedRestaurants = Restaurant::withAvg('reviews', 'rating')  // Menghitung rata-rata rating
            ->orderBy('reviews_avg_rating', 'desc')  // Urutkan berdasarkan rata-rata rating
            ->limit(4)
            ->get();

        // Restoran dengan promo yang valid
        $promoRestaurants = Restaurant::whereHas('promo', function ($query) {
            $query->where('discount', '>', 0)
                ->whereDate('valid_from', '<=', now())
                ->whereDate('valid_until', '>=', now());
        })
            ->limit(4)
            ->get();

        return view('home.restoran', compact('topRatedRestaurants', 'promoRestaurants'));
    }
}
