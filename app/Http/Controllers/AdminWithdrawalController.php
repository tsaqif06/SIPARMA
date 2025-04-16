<?php

namespace App\Http\Controllers;

use App\Models\Balance;
use App\Models\Withdrawal;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

/**
 * Controller untuk mengelola pencairan dana (withdrawal) oleh admin_wisata dan superadmin.
 */
class AdminWithdrawalController extends Controller
{
    /**
     * Konstruktor: Atur middleware autentikasi dan pembatasan akses berdasarkan role.
     */
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
     * Menampilkan daftar withdrawal untuk admin_wisata berdasarkan destinasi.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
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

    /**
     * Menampilkan histori withdrawal yang telah disetujui oleh superadmin.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function history()
    {
        if (auth()->user()->role !== 'superadmin') {
            return redirect()->route('admin.dashboard');
        }

        $withdrawals = Withdrawal::where('status', 'completed')->orderBy('created_at', 'desc')->get();

        return view('admin.withdrawal.history', compact('withdrawals'));
    }

    /**
     * Menampilkan daftar withdrawal dengan status pending (menunggu persetujuan).
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function approval()
    {
        if (auth()->user()->role !== 'superadmin') {
            return redirect()->route('admin.dashboard')->with('error', 'Akses ditolak!');
        }

        $withdrawals = Withdrawal::where('status', 'pending')->get();

        return view('admin.withdrawal.approval', compact('withdrawals'));
    }

    /**
     * Menampilkan form persetujuan withdrawal untuk superadmin.
     *
     * @param Withdrawal $withdrawal
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function approveForm(Withdrawal $withdrawal)
    {
        if (auth()->user()->role !== 'superadmin') {
            return redirect()->route('admin.dashboard')->with('error', 'Akses ditolak!');
        }

        return view('admin.withdrawal.approval-form', compact('withdrawal'));
    }

    /**
     * Menampilkan form pengajuan withdrawal untuk admin_wisata.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function create()
    {
        if (auth()->user()->role !== 'admin_wisata') {
            return redirect()->route('admin.dashboard');
        }

        $destinationId = auth()->user()->adminDestinations[0]->destination_id;
        $balance = Balance::where('destination_id', $destinationId)->first();

        return view('admin.withdrawal.request', compact('balance'));
    }

    /**
     * Menyimpan permintaan withdrawal baru oleh admin_wisata.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $destinationId = auth()->user()->adminDestinations[0]->destination_id;
        $balance = Balance::where('destination_id', $destinationId)->first();

        $request->validate([
            'amount' => [
                'required',
                'numeric',
                'min:20000',
                function ($attribute, $value, $fail) use ($balance) {
                    if ($value > $balance->balance) {
                        $fail('Jumlah yang diminta melebihi saldo yang tersedia.');
                    }
                }
            ]
        ], [
            'amount.required' => 'Jumlah pencairan wajib diisi.',
            'amount.numeric' => 'Jumlah pencairan harus berupa angka.',
            'amount.min' => 'Jumlah minimal pencairan adalah Rp 20.000.'
        ]);

        Withdrawal::create([
            'balance_id' => $balance->id,
            'amount' => $request->amount,
            'status' => 'pending',
        ]);

        return redirect()->route('admin.withdrawal.index')->with('success', 'Permintaan pencairan berhasil diajukan.');
    }

    /**
     * Menampilkan form edit withdrawal yang belum diproses.
     *
     * @param Withdrawal $withdrawal
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function edit(Withdrawal $withdrawal)
    {
        if (auth()->user()->role !== 'admin_wisata') {
            return redirect()->route('admin.dashboard');
        }

        $balance = Balance::find($withdrawal->balance_id);
        $currentBalance = $balance->balance - $withdrawal->amount;

        return view('admin.withdrawal.edit', compact('withdrawal', 'balance', 'currentBalance'));
    }

    /**
     * Memperbarui jumlah withdrawal yang diajukan oleh admin_wisata.
     *
     * @param Request $request
     * @param Withdrawal $withdrawal
     * @return \Illuminate\Http\RedirectResponse
     */
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

    /**
     * Menghapus permintaan withdrawal.
     *
     * @param Withdrawal $withdrawal
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Withdrawal $withdrawal)
    {
        $withdrawal->delete();

        return redirect()->route('admin.withdrawal.index')->with('success', 'Withdrawal berhasil dihapus.');
    }

    /**
     * Mengupdate status withdrawal (completed / rejected) oleh superadmin.
     * Jika disetujui, saldo akan dikurangi dan bukti transfer dapat diunggah.
     *
     * @param Request $request
     * @param Withdrawal $withdrawal
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateStatus(Request $request, Withdrawal $withdrawal)
    {
        $request->validate([
            'status' => 'required|in:completed,rejected',
            'admin_note' => 'nullable|string',
            'transfer_proof' => $request->status == 'completed'
                ? 'required|file|mimes:jpg,jpeg,png,pdf|max:4096'
                : 'nullable|file|mimes:jpg,jpeg,png,pdf|max:4096',
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
