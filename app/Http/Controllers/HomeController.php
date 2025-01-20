<?php

namespace App\Http\Controllers;

use App\Models\Destination;
use App\Models\Restaurant;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Menampilkan halaman utama (Home).
     */
    public function index()
    {
        // Ambil destinasi untuk setiap kategori
        $categories = [
            'alams' => Destination::withAvg('reviews', 'rating')->where('type', 'alam')->limit(4)->get(),
            'wahanas' => Destination::withAvg('reviews', 'rating')->where('type', 'wahana')->limit(4)->get(),
            'restaurants' => Destination::withAvg('reviews', 'rating')->where('type', 'sejarah')->limit(4)->get(),
        ];

        // Ambil promo destinasi yang valid
        $promoDestinations = Destination::with('promo')
            ->withAvg('reviews', 'rating')  // Mendapatkan rata-rata rating
            ->whereHas('promo', function ($query) {
                $query->where('discount', '>', 0)
                    ->whereDate('valid_from', '<=', now())
                    ->whereDate('valid_until', '>=', now());
            })
            ->limit(4)
            ->get();

        $promoRestaurants = Restaurant::with('promo')
            ->withAvg('reviews', 'rating')  // Mendapatkan rata-rata rating
            ->whereHas('promo', function ($query) {
                $query->where('discount', '>', 0)
                    ->whereDate('valid_from', '<=', now())
                    ->whereDate('valid_until', '>=', now());
            })
            ->limit(4)
            ->get();

        return view('home.index', compact('categories', 'promoDestinations', 'promoRestaurants'));
    }

    /**
     * Menampilkan halaman Promo (destinasi dan restoran dengan promo).
     */
    public function promo()
    {
        // Promo destinasi yang valid
        $promoDestinations = Destination::with('promo')
            ->whereHas('promo', function ($query) {
                $query->where('discount', '>', 0)
                    ->whereDate('valid_from', '<=', now())
                    ->whereDate('valid_until', '>=', now());
            })
            ->get();

        // Promo restoran yang valid
        $promoRestaurants = Restaurant::with('promo')
            ->whereHas('promo', function ($query) {
                $query->where('discount', '>', 0)
                    ->whereDate('valid_from', '<=', now())
                    ->whereDate('valid_until', '>=', now());
            })
            ->get();

        return view('home.promo', compact('promoDestinations', 'promoRestaurants'));
    }
}
