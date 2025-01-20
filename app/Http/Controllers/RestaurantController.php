<?php

namespace App\Http\Controllers;

use App\Models\Restaurant;
use Illuminate\Http\Request;

class RestaurantController extends Controller
{
    public function show($slug)
    {
        $restaurant = Restaurant::where('slug', $slug)->firstOrFail();

        return view('restaurants.show', compact('restaurant'));
    }
}
