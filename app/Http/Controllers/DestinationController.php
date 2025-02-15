<?php

namespace App\Http\Controllers;

use App\Models\Ride;
use App\Models\Destination;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DestinationController extends Controller
{
    public function show($slug)
    {
        $destination = Destination::where('slug', $slug)->firstOrFail();
        $reviews = $destination->reviews()->paginate(5);

        return view('user.destinations.show', compact('destination', 'reviews'));
    }

    public function browse(Request $request)
    {
        $query = Destination::withAvg('reviews', 'rating')
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

    public function checkout($slug, $type = 'destination')
    {
        if ($type === 'destination') {
            $item = Destination::where('slug', $slug)->firstOrFail();
        } elseif ($type === 'ride') {
            $item = Ride::where('slug', $slug)->firstOrFail();
        } else {
            abort(404);
        }

        return view('user.destinations.checkout', [
            'item' => $item,
            'type' => $type
        ]);
    }
}
