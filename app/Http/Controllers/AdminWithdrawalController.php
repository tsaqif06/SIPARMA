<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Withdrawal;
use Illuminate\Http\Request;

class AdminWithdrawalController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');

        $this->middleware(function ($request, $next) {
            if (auth()->user()->role === 'admin_tempat') {
                return redirect()->route('admin.dashboard')->with('error', 'Akses ditolak!');
            }
            return $next($request);
        });
    }
    /**
     * Menampilkan daftar withdrawal yang perlu disetujui.
     */
    public function approval()
    {
        $withdrawals = Withdrawal::where('status', 'pending')->get();

        return view('admin.withdrawal.approval', compact('withdrawals'));
    }

    public function approveForm(Withdrawal $withdrawal)
    {
        return view('admin.withdrawal.approval-form', compact('withdrawal'));
    }

    /**
     * Mengupdate status withdrawal (approve/reject).
     */
    public function updateStatus(Request $request, Withdrawal $withdrawal)
    {
        $request->validate([
            'status' => 'required|in:completed,rejected',
            'admin_note' => 'nullable|string',
            'transfer_proof' => $request->status == 'completed'
                ? 'required|file|mimes:jpg,jpeg,png,pdf|max:2048'
                : 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $withdrawal->status = $request->status;

        if ($request->status == 'completed') {
            $withdrawal->admin_note = $request->admin_note;

            if ($request->hasFile('transfer_proof')) {
                $path = $request->file('transfer_proof')->store('transfer_proofs', 'public');

                $withdrawal->transfer_proof = 'storage/' . $path;
            }
        }

        $withdrawal->save();

        return redirect()->route('admin.withdrawal.approval')->with('success', 'Status withdrawal berhasil diperbarui.');
    }
}
