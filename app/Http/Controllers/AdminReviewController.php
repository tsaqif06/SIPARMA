<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Review;
use App\Models\AdminPlace;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

/**
 * Controller untuk mengelola review oleh admin wisata.
 */
class AdminReviewController extends Controller
{
    /**
     * Menampilkan daftar review berdasarkan destinasi atau tempat yang dikelola oleh admin.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $reviews = auth()->user()->adminDestinations[0]->destination->reviews
            ?? AdminPlace::where('user_id', auth()->user()->id)
            ->where('approval_status', 'approved')
            ->latest('created_at')
            ->first()?->place->reviews;

        return view('admin.review.index', compact('reviews'));
    }

    /**
     * Menampilkan detail dari review tertentu.
     *
     * @param \App\Models\Review $review
     * @return \Illuminate\View\View
     */
    public function show(Review $review)
    {
        return view('admin.review.show', compact('review'));
    }
}
