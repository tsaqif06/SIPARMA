<?php

namespace App\Http\Controllers;

use App\Models\Bundle;
use App\Models\Ride;
use App\Models\Destination;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

/**
 * Controller untuk mengelola destinasi wisata, termasuk menampilkan detail destinasi,
 * pencarian, pengecekan status pemesanan, dan mendapatkan destinasi populer.
 */
class DestinationController extends Controller
{
    /**
     * Menampilkan detail destinasi berdasarkan slug.
     *
     * @param string $slug
     * @return \Illuminate\View\View
     */
    public function show($slug)
    {
        $destination = Destination::with([
            'gallery',
            'facilities',
            'rides' => function ($query) {
                $query->with('gallery');
            },
            'bundles' => function ($query) {
                $query->with('items');
            },
            'places' => function ($query) {
                $query->with('gallery');
            },
            'promos',
            'reviews' => function ($query) {
                $query->with('user');
            }
        ])->where('slug', $slug)->firstOrFail();
        $reviews = $destination->reviews()->paginate(5);

        return view('user.destinations.show', compact('destination', 'reviews'));
    }

    /**
     * Menampilkan destinasi berdasarkan filter dan pencarian.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function browse(Request $request)
    {
        $query = Destination::with([
            'gallery',
            'reviews',
            'admin'
        ])
            ->withAvg('reviews', 'rating')
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('tbl_admin_destinations')
                    ->whereColumn('tbl_admin_destinations.destination_id', 'tbl_destinations.id');
            });

        if ($request->has('jenis_wisata')) {
            $query->whereIn('type', $request->jenis_wisata);
        }

        if ($request->has('harga_min') && $request->harga_min !== null) {
            $query->where('price', '>=', $request->harga_min);
        }

        if ($request->has('harga_max') && $request->harga_max !== null) {
            $query->where('price', '<=', $request->harga_max);
        }

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->has('sort')) {
            switch ($request->sort) {
                case 'populer':
                    $query->orderBy('reviews_avg_rating', 'desc');
                    break;
                case 'harga_tertinggi':
                    $query->orderBy('price', 'desc');
                    break;
                case 'harga_terendah':
                    $query->orderBy('price', 'asc');
                    break;
                case 'rating_tertinggi':
                    $query->orderBy('reviews_avg_rating', 'desc');
                    break;
                default:
                    $query->orderBy('created_at', 'desc');
                    break;
            }
        }

        $destinations = $query->paginate(9);

        return view('user.destinations.browse', compact('destinations'));
    }

    /**
     * Menampilkan halaman checkout untuk destinasi, ride, atau bundle.
     *
     * @param string $slug
     * @param string $type
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function checkout($slug, $type = 'destination')
    {
        if ($type === 'destination') {
            $item = Destination::with([
                'gallery',
                'promos',
            ])->where('slug', $slug)->firstOrFail();
        } elseif ($type === 'ride') {
            $item = Ride::with([
                'gallery',
                'destination'
            ])->where('slug', $slug)->firstOrFail();
        } elseif ($type === 'bundle') {
            $item = Bundle::with('items')->where('id', $slug)->firstOrFail();

            return view('user.destinations.checkoutbundle', [
                'item' => $item,
                'type' => $type
            ]);
        } else {
            return redirect('/');
        }

        return view('user.destinations.checkout', [
            'item' => $item,
            'type' => $type
        ]);
    }

    /**
     * Mendapatkan destinasi populer dengan rating tertinggi yang disimpan dalam cache.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getPopularDestinations()
    {
        return Cache::remember('popular_destinations', 3600, function () {
            return Destination::withAvg('reviews', 'rating')
                ->whereExists(function ($query) {
                    $query->select(DB::raw(1))
                        ->from('tbl_admin_destinations')
                        ->whereColumn('tbl_admin_destinations.destination_id', 'tbl_destinations.id');
                })
                ->orderBy('reviews_avg_rating', 'desc')
                ->take(4)
                ->get();
        });
    }
}
