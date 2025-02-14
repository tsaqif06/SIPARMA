<?php

namespace App\Http\Controllers;

use App\Models\Place;
use Illuminate\Http\Request;

class PlaceController extends Controller
{
    public function show($slug)
    {
        $place = Place::where('slug', $slug)->firstOrFail();
        $reviews = $place->reviews()->paginate(5);

        return view('user.places.show', compact('place', 'reviews'));
    }
}
