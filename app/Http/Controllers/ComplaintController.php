<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Complaint;

class ComplaintController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'complaint_text' => 'required|string|max:1000',
        ]);

        Complaint::create([
            'user_id' => auth()->id(),
            'destination_id' => $request->destination_id ?? null,
            'place_id' => $request->place_id ?? null,
            'complaint_text' => $request->complaint_text,
            'status' => 'new',
        ]);

        return back()->with('success', 'Laporan berhasil dikirim!');
    }
}
