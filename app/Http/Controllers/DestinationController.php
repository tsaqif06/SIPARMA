<?php

namespace App\Http\Controllers;

use App\Models\Ride;
use App\Models\Destination;
use Illuminate\Http\Request;

class DestinationController extends Controller
{
    public function show($slug)
    {
        $destination = Destination::where('slug', $slug)->firstOrFail();
        $reviews = $destination->reviews()->paginate(5);

        return view('user.destinations.show', compact('destination', 'reviews'));
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
