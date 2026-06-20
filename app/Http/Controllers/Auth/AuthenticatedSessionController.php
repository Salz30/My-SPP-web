<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\LogAktivitas;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        $request->session()->regenerate();

        // Catat log login setelah autentikasi berhasil
        $user = $request->user();
        $peran = $user->role === 'admin' ? 'Admin' : 'Siswa';
        LogAktivitas::create([
            'id_user'  => $user->id,
            'tindakan' => 'Login',
            'deskripsi'=> "{$peran} \"{$user->name}\" (Username: {$user->username}) berhasil masuk ke sistem",
        ]);

        // Cek siapa yang barusan login
        if ($user->role === 'siswa') {
            return redirect()->route('siswa.dashboard');
        }

        // Jika admin, arahkan ke dashboard utama
        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        // Catat log logout sebelum sesi dihancurkan
        if (Auth::check()) {
            $user = Auth::user();
            $peran = $user->role === 'admin' ? 'Admin' : 'Siswa';
            LogAktivitas::create([
                'id_user'  => $user->id,
                'tindakan' => 'Logout',
                'deskripsi'=> "{$peran} \"{$user->name}\" keluar dari sistem",
            ]);
        }

        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
