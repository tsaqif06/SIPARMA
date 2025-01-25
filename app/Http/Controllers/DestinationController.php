<?php

namespace App\Http\Controllers;

use App\Models\Destination;
use Illuminate\Http\Request;

class DestinationController extends Controller
{
    public function show($slug)
    {
        $destination = Destination::where('slug', $slug)->firstOrFail();

        return view('user.destination.show', compact('destination'));
    }
}
