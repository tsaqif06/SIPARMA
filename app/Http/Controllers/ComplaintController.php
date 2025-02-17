<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Complaint;

class ComplaintController extends Controller
{
    public function index()
    {
        if (auth()->user()->role !== 'superadmin') {
            return redirect()->route('admin.dashboard')->with('error', 'Akses ditolak!');
        }

        $complaints = Complaint::all();

        return view('admin.complaints.index', compact('complaints'));
    }

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

    public function show($id)
    {
        if (auth()->user()->role !== 'superadmin') {
            return redirect()->route('admin.dashboard')->with('error', 'Akses ditolak!');
        }

        $complaint = Complaint::findOrFail($id);
        return view('admin.complaints.show', compact('complaint'));
    }

    public function edit($id)
    {
        if (auth()->user()->role !== 'superadmin') {
            return redirect()->route('admin.dashboard')->with('error', 'Akses ditolak!');
        }

        $complaint = Complaint::findOrFail($id);
        return view('admin.complaints.edit', compact('complaint'));
    }

    public function update(Request $request, $id)
    {
        if (auth()->user()->role !== 'superadmin') {
            return redirect()->route('admin.dashboard')->with('error', 'Akses ditolak!');
        }

        $complaint = Complaint::findOrFail($id);
        $request->validate(['status' => 'required|in:new,resolved,closed']);
        $complaint->status = $request->status;
        $complaint->save();

        return redirect()->route('admin.complaints.index')->with('success', 'Status keluhan diperbarui');
    }
}
