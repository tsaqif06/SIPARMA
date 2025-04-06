<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Review;
use App\Models\AdminPlace;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class AdminReviewController extends Controller
{
    public function index()
    {
        $reviews = auth()->user()->adminDestinations[0]->destination->reviews ?? AdminPlace::where('user_id', auth()->user()->id)
            ->where('approval_status', 'approved')
            ->latest('created_at')
            ->first()?->place->reviews;
        // dd($reviews);die;
        return view('admin.review.index', compact('reviews'));
    }

    public function show(Review $review)
    {
        return view('admin.review.show', compact('review'));
    }
}
