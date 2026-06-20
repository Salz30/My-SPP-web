<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // Jika role user saat ini tidak sama dengan role yang diizinkan di rute
        if ($request->user()->role !== $role) {
            
            // Jika dia siswa tapi mencoba masuk halaman admin, lempar ke portal siswa
            if ($request->user()->role === 'siswa') {
                return redirect()->route('siswa.dashboard');
            }
            
            // Jika dia admin tapi mencoba masuk halaman siswa, kembalikan ke dashboard admin
            return redirect()->route('dashboard');
        }

        return $next($request);
    }
}