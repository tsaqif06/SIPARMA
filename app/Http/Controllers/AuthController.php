<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

/**
 * Controller untuk mengelola otentikasi pengguna, termasuk login, register, dan logout.
 * Fungsionalitas lainnya termasuk mengelola peran pengguna, seperti mengubah peran menjadi admin.
 */
class AuthController extends Controller
{
    /**
     * Menampilkan form login untuk user.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function showLoginForm()
    {
        if (Auth::check()) {
            if (in_array(Auth::user()->role, ['superadmin', 'admin_wisata', 'admin_tempat'])) {
                return redirect('/admin');
            }
            return redirect('/');
        }

        return view('user.auth.login');
    }

    /**
     * Menampilkan form login untuk admin.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function showLoginFormAdmin()
    {
        if (Auth::check()) {
            if (in_array(Auth::user()->role, ['superadmin', 'admin_wisata', 'admin_tempat'])) {
                return redirect('/admin');
            }
            return redirect('/');
        }

        return view('admin.auth.login');
    }

    /**
     * Menampilkan form registrasi untuk user.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function showRegisterForm()
    {
        if (Auth::check()) {
            if (in_array(Auth::user()->role, ['superadmin', 'admin_wisata', 'admin_tempat'])) {
                return redirect('/admin');
            }
            return redirect('/');
        }

        return view('user.auth.register');
    }

    /**
     * Proses login untuk user.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        if (Auth::attempt(['email' => $validated['email'], 'password' => $validated['password']])) {
            return redirect()->route('home.index')->with('success', __('flasher.login_berhasil'));
        }

        return back()->with('error', __('flasher.email_password_salah'));
    }

    /**
     * Proses login untuk admin.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function loginAdmin(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        $user = User::where('email', $validated['email'])->first();

        if ($user && in_array($user->role, ['superadmin', 'admin_wisata', 'admin_tempat'])) {
            if (Auth::attempt(['email' => $validated['email'], 'password' => $validated['password']])) {
                return redirect()
                    ->route('admin.dashboard')
                    ->with('success', __('flasher.login_admin_berhasil', ['name' => $user->name]));
            } else {
                return redirect()
                    ->route('admin.dashboard')
                    ->with('error', __('flasher.email_password_salah'));
            }
        }

        return back()->with('error', $user ? __('flasher.tidak_ada_akses_admin') : __('flasher.email_password_salah'));
    }

    /**
     * Mengubah peran pengguna menjadi 'admin_tempat' dan login.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function convertRoleAndLogin()
    {
        $user = User::find(Auth::id());

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User tidak ditemukan']);
        }

        if ($user->role !== 'admin_tempat') {
            $user->role = 'admin_tempat';
            $user->save();
        }

        return response()->json(['success' => true]);
    }

    /**
     * Proses registrasi pengguna baru.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:tbl_users,email',
            'password' => 'required|min:6|confirmed',
            'phone_number' => 'required|regex:/^[0-9]{10,30}$/|max:30',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'phone_number' => $validated['phone_number'],
        ]);

        return redirect('/login')->with('success', __('flasher.daftar_berhasil'));
    }

    /**
     * Logout pengguna dan redirect sesuai dengan peran.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout()
    {
        $redirect = auth()->user()->role === 'user' ? '/' : '/admin';

        Auth::logout();
        return redirect($redirect);
    }
}
