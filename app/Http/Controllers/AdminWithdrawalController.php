<?php

namespace App\Http\Controllers;

use App\Models\Balance;
use App\Models\Withdrawal;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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

    public function index()
    {
        if (auth()->user()->role !== 'admin_wisata') {
            return redirect()->route('admin.dashboard');
        }

        $destinationId = auth()->user()->adminDestinations[0]->destination_id;

        $withdrawals = Withdrawal::whereHas('balance', function ($query) use ($destinationId) {
            $query->where('destination_id', $destinationId);
        })->orderBy('created_at', 'desc')->get();


        return view('admin.withdrawal.index', compact('withdrawals'));
    }

    public function history()
    {
        if (auth()->user()->role !== 'superadmin') {
            return redirect()->route('admin.dashboard');
        }

        $withdrawals = Withdrawal::where('status', 'completed')->orderBy('created_at', 'desc')->get();

        return view('admin.withdrawal.history', compact('withdrawals'));
    }
    /**
     * Menampilkan daftar withdrawal yang perlu disetujui.
     */
    public function approval()
    {
        if (auth()->user()->role !== 'superadmin') {
            return redirect()->route('admin.dashboard')->with('error', 'Akses ditolak!');
        }

        $withdrawals = Withdrawal::where('status', 'pending')->get();

        return view('admin.withdrawal.approval', compact('withdrawals'));
    }

    public function approveForm(Withdrawal $withdrawal)
    {
        if (auth()->user()->role !== 'superadmin') {
            return redirect()->route('admin.dashboard')->with('error', 'Akses ditolak!');
        }

        return view('admin.withdrawal.approval-form', compact('withdrawal'));
    }

    public function create()
    {
        if (auth()->user()->role !== 'admin_wisata') {
            return redirect()->route('admin.dashboard');
        }

        $destinationId = auth()->user()->adminDestinations[0]->destination_id;
        $balance = Balance::where('destination_id', $destinationId)->first();

        return view('admin.withdrawal.request', compact('balance'));
    }

    public function store(Request $request)
    {
        $destinationId = auth()->user()->adminDestinations[0]->destination_id;
        $balance = Balance::where('destination_id', $destinationId)->first();

        $request->validate([
            'amount' => ['required', 'numeric', 'min:1', function ($attribute, $value, $fail) use ($balance) {
                if ($value > $balance->balance) {
                    $fail('Jumlah yang diminta melebihi saldo yang tersedia.');
                }
            }]
        ]);

        Withdrawal::create([
            'balance_id' => $balance->id,
            'amount' => $request->amount,
            'status' => 'pending',
        ]);

        return redirect()->route('admin.withdrawal.index')->with('success', 'Permintaan pencairan berhasil diajukan.');
    }

    public function edit(Withdrawal $withdrawal)
    {
        if (auth()->user()->role !== 'admin_wisata') {
            return redirect()->route('admin.dashboard');
        }

        $balance = Balance::find($withdrawal->balance_id);
        $currentBalance = $balance->balance - $withdrawal->amount;
        return view('admin.withdrawal.edit', compact('withdrawal', 'balance', 'currentBalance'));
    }

    public function update(Request $request, Withdrawal $withdrawal)
    {
        $destinationId = auth()->user()->adminDestinations[0]->destination_id;
        $balance = Balance::where('destination_id', $destinationId)->first();

        $request->validate([
            'amount' => ['required', 'numeric', 'min:1', function ($attribute, $value, $fail) use ($balance) {
                if ($value > $balance->balance) {
                    $fail('Jumlah yang diminta melebihi saldo yang tersedia.');
                }
            }]
        ]);

        $withdrawal->update(['amount' => $request->amount]);

        return redirect()->route('admin.withdrawal.index')->with('success', 'Withdrawal berhasil diupdate.');
    }

    public function destroy(Withdrawal $withdrawal)
    {
        $withdrawal->delete();
        return redirect()->route('admin.withdrawal.index')->with('success', 'Withdrawal berhasil dihapus.');
    }

    /**
     * Mengupdate status withdrawal (approve/reject).
     */
    public function updateStatus(Request $request, Withdrawal $withdrawal)
    {
        $request->validate([
            'status' => 'required|in:completed,rejected',
            'admin_note' => 'nullable|string',
            'transfer_proof' => $request->status == 'completed' ? 'required|file|mimes:jpg,jpeg,png,pdf|max:4096' : 'nullable|file|mimes:jpg,jpeg,png,pdf|max:4096',
        ]);

        $withdrawal->status = $request->status;

        if ($request->status == 'completed') {
            $withdrawal->admin_note = $request->admin_note;

            if ($request->hasFile('transfer_proof')) {
                $path = $request->file('transfer_proof')->store('transfer_proofs', 'public');
                $withdrawal->transfer_proof = 'storage/' . $path;
            }

            $balance = Balance::find($withdrawal->balance_id);
            $balance->balance -= $withdrawal->amount;
            $balance->save();
        }

        $withdrawal->updated_at = now();
        $withdrawal->save();

        return redirect()->route('admin.withdrawal.approval')->with('success', 'Status withdrawal berhasil diperbarui.');
    }
}
