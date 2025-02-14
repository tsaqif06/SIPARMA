<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string',
            'destination_id' => 'nullable|integer|exists:tbl_destinations,id',
            'place_id' => 'nullable|integer|exists:tbl_places,id',
        ]);

        Review::create([
            'user_id' => auth()->user()->id,
            'destination_id' => $request->destination_id,
            'place_id' => $request->place_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return redirect()->back()->with('success', 'Ulasan berhasil dikirim!');
    }

    public function destroy($id)
    {
        $review = Review::find($id);
        if ($review) {
            $review->delete();
            return redirect()->back()->with('success', 'Review berhasil dihapus.');
        } else {
            return redirect()->back()->with('error', 'Review tidak ditemukan.');
        }
    }
}
