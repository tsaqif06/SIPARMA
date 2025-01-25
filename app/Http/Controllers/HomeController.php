<?php

namespace App\Http\Controllers;

use App\Models\Destination;
use App\Models\Place;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Menampilkan halaman utama (Home).
     */
    public function index()
    {
        $categories = [
            'alams' => Destination::withAvg('reviews', 'rating')->where('type', 'alam')->limit(4)->get(),
            'wahanas' => Destination::withAvg('reviews', 'rating')->where('type', 'wahana')->limit(4)->get(),
            'places' => Place::withAvg('reviews', 'rating')->limit(4)->get(),
        ];

        $promos = [
            'destinations' => Destination::with('promos')
                ->withAvg('reviews', 'rating')
                ->whereHas('promos', function ($query) {
                    $query->where('discount', '>', 0)
                        ->whereDate('valid_from', '<=', now())
                        ->whereDate('valid_until', '>=', now());
                })
                ->limit(4)
                ->get(),
            'places' => Place::with('promos')
                ->withAvg('reviews', 'rating')
                ->whereHas('promos', function ($query) {
                    $query->where('discount', '>', 0)
                        ->whereDate('valid_from', '<=', now())
                        ->whereDate('valid_until', '>=', now());
                })
                ->limit(4)
                ->get(),
        ];

        return view('user.home.index', compact('categories', 'promos'));
    }

    public function indexAdmin()
    {
        return view('admin.dashboard.index');
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
        $promoPlaces = Place::with('promo')
            ->whereHas('promo', function ($query) {
                $query->where('discount', '>', 0)
                    ->whereDate('valid_from', '<=', now())
                    ->whereDate('valid_until', '>=', now());
            })
            ->get();

        return view('user.home.promo', compact('promoDestinations', 'promoRestaurants'));
    }
}
