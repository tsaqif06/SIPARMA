<?php

namespace App\Http\Controllers;

use App\Models\Place;
use Illuminate\Http\Request;

class PlaceController extends Controller
{
    public function show($slug)
    {
        $places = Place::where('slug', $slug)->firstOrFail();

        return view('places.show', compact('places'));
    }
}
