<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Complaint;

/**
 * Controller untuk mengelola keluhan yang diajukan oleh pengguna, termasuk menampilkan,
 * membuat, memperbarui status keluhan, serta melakukan pengelolaan akses keluhan hanya
 * untuk superadmin.
 */
class ComplaintController extends Controller
{
    /**
     * Menampilkan daftar keluhan yang ada.
     * Hanya dapat diakses oleh pengguna dengan peran 'superadmin'.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function index()
    {
        if (auth()->user()->role !== 'superadmin') {
            return redirect()->route('admin.dashboard')->with('error', 'Akses ditolak!');
        }

        $complaints = Complaint::with(['user', 'destination', 'place'])->get();

        return view('admin.complaints.index', compact('complaints'));
    }

    /**
     * Menyimpan keluhan baru yang diajukan oleh pengguna.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
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

        return back()->with('success', __('flasher.laporan_dikirim'));
    }

    /**
     * Menampilkan detail keluhan berdasarkan ID.
     * Hanya dapat diakses oleh pengguna dengan peran 'superadmin'.
     *
     * @param int $id
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function show($id)
    {
        if (auth()->user()->role !== 'superadmin') {
            return redirect()->route('admin.dashboard')->with('error', 'Akses ditolak!');
        }

        $complaint = Complaint::findOrFail($id);
        return view('admin.complaints.show', compact('complaint'));
    }

    /**
     * Menampilkan form untuk mengedit status keluhan.
     * Hanya dapat diakses oleh pengguna dengan peran 'superadmin'.
     *
     * @param int $id
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function edit($id)
    {
        if (auth()->user()->role !== 'superadmin') {
            return redirect()->route('admin.dashboard')->with('error', 'Akses ditolak!');
        }

        $complaint = Complaint::findOrFail($id);
        return view('admin.complaints.edit', compact('complaint'));
    }

    /**
     * Memperbarui status keluhan.
     * Hanya dapat diakses oleh pengguna dengan peran 'superadmin'.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
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
