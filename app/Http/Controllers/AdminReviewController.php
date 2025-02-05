<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Review;
use Illuminate\Support\Str;

class AdminReviewController extends Controller
{
    public function index()
    {
        $reviews = auth()->user()->adminDestinations[0]->destination->reviews ?? auth()->user()->adminPlaces[0]->place->reviews;
// dd($reviews);die;
        return view('admin.review.index', compact('reviews'));
    }

    public function show(Review $review)
    {
        return view('admin.review.show', compact('review'));
    }
}
