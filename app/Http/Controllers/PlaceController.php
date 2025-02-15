<?php

namespace App\Http\Controllers;

use App\Models\Place;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PlaceController extends Controller
{
    public function show($slug)
    {
        $place = Place::where('slug', $slug)->firstOrFail();
        $reviews = $place->reviews()->paginate(5);

        return view('user.places.show', compact('place', 'reviews'));
    }

    public function browse(Request $request)
    {
        $query = Place::withAvg('reviews', 'rating')
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('tbl_admin_places')
                    ->whereColumn('tbl_admin_places.place_id', 'tbl_places.id');
            });

        if ($request->has('jenis_tempat')) {
            $jenisTempat = $request->jenis_tempat;

            if (in_array('Other', $jenisTempat)) {
                $query->whereNotIn('type', ['restoran', 'penginapan']);
            } else {
                $query->whereIn('type', $jenisTempat);
            }
        }

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->has('sort')) {
            switch ($request->sort) {
                case 'populer':
                    $query->orderBy('reviews_avg_rating', 'desc');
                    break;
                case 'rating_tertinggi':
                    $query->orderBy('reviews_avg_rating', 'desc');
                    break;
                default:
                    $query->orderBy('created_at', 'desc');
                    break;
            }
        }

        $places = $query->paginate(9);

        return view('user.places.browse', compact('places'));
    }
}
