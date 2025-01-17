<?php

namespace App\Http\Controllers;

use App\Models\Destination;
use Illuminate\Http\Request;

class DestinationController extends Controller
{
    public function show($id)
    {
        $destination = Destination::findOrFail($id);

        return view('destinations.show', compact('destination'));
    }
}
