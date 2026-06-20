<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\Pembayaran;
use Illuminate\Support\Facades\Auth;

class PortalController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Cari data siswa asli di database berdasarkan NISN (username user yang login)
        $siswa = Siswa::query()->with('kelas')->where('nisn', $user->username)->first();

        // Jika data siswa tidak ditemukan (mencegah error)
        if (!$siswa) {
            return abort(403, 'Data profil siswa tidak ditemukan.');
        }

        // Ambil daftar tagihan yang belum lunas
        $tagihan_aktif = Pembayaran::query()
                            ->with('tagihan.kategori')
                            ->where('id_siswa', $siswa->id_siswa)
                            ->where('status_bayar', 'Belum Lunas')
                            ->orderBy('created_at', 'desc')
                            ->get();

        // Ambil riwayat tagihan yang sudah lunas
        $riwayat = Pembayaran::query()
                            ->with('tagihan.kategori')
                            ->where('id_siswa', $siswa->id_siswa)
                            ->where('status_bayar', 'Lunas')
                            ->orderBy('tanggal_bayar', 'desc')
                            ->get();

        return view('portal.index', compact('siswa', 'tagihan_aktif', 'riwayat'));
    }
    public function caraPembayaran()
    {
        // Ambil data siswa untuk ditampilkan di Navbar
        $siswa = Siswa::query()->with('kelas')->where('nisn', Auth::user()->username)->first();
        return view('portal.cara-pembayaran', compact('siswa'));
    }

    public function bantuan()
    {
        $siswa = Siswa::query()->with('kelas')->where('nisn', Auth::user()->username)->first();
        return view('portal.bantuan', compact('siswa'));
    }

    public function profil()
    {
        $user = Auth::user();
        $siswa = Siswa::query()->with('kelas')->where('nisn', $user->username)->first();
        if (!$siswa) return abort(403, 'Data profil siswa tidak ditemukan.');

        return view('portal.profil', compact('siswa', 'user'));
    }

    public function updateProfil(Request $request)
    {
        $request->validate([
            'no_hp' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $user = Auth::user();
        $siswa = Siswa::query()->where('nisn', $user->username)->first();

        // Update no_hp
        if ($siswa) {
            $siswa->no_hp = $request->no_hp;
            $siswa->save();
        }

        // Update password if provided
        if ($request->filled('password')) {
            // Karena auth user mengembalikan class User, kita update user-nya
            $user->password = \Illuminate\Support\Facades\Hash::make($request->password);
            $user->save();
        }

        return redirect()->route('siswa.profil')->with('success', 'Profil dan data kontak berhasil diperbarui!');
    }
}