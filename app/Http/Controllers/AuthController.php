<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Tampilkan halaman login pengguna.
    public function showLogin()
    {
        return view('auth.login');
    }

    // Periksa kredensial dan buat sesi pengguna.
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $remember = $request->boolean('remember');

        $user = User::where('username', $credentials['username'])->first();

        if (! $user || ! Hash::check($credentials['password'], (string) $user->password)) {
            return back()->withErrors([
                'username' => 'Username atau password tidak valid.',
            ])->withInput();
        }

        if (! $user->is_active) {
            return back()->withErrors([
                'username' => 'Akun ini sedang dinonaktifkan. Silakan hubungi admin.',
            ])->withInput();
        }

        $guard = match ($user->role) {
            'admin' => 'admin',
            'guru' => 'guru',
            'kepala_sekolah' => 'kepala_sekolah',
            'orang_tua' => 'orang_tua',
            default => 'web',
        };

        Auth::guard($guard)->login($user, $remember);
        $request->session()->regenerate();
        $request->session()->put('active_guard', $guard);

        return redirect()->route('dashboard')->with('success', 'Login berhasil. Selamat menggunakan sistem.');
    }

    // Akhiri sesi pengguna yang sedang aktif.
    public function logout(Request $request)
    {
        $guard = (string) $request->input('guard', '');
        $allowedGuards = ['admin', 'guru', 'kepala_sekolah', 'orang_tua', 'web'];

        if (! in_array($guard, $allowedGuards, true)) {
            $guard = collect(['admin', 'guru', 'kepala_sekolah', 'orang_tua', 'web'])
                ->first(fn ($g) => Auth::guard($g)->check()) ?? 'web';
        }

        Auth::guard($guard)->logout();
        $nextGuard = collect(['admin', 'guru', 'kepala_sekolah', 'orang_tua', 'web'])
            ->reject(fn ($candidate) => $candidate === $guard)
            ->first(fn ($candidate) => Auth::guard($candidate)->check());

        if ($nextGuard) {
            $request->session()->put('active_guard', $nextGuard);
        } else {
            $request->session()->forget('active_guard');
        }

        // Jangan invalidate session, supaya guard lain tetap login (multi-session).
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
