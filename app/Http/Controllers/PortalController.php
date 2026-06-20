<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\Pembayaran;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PortalController extends Controller
{
    /**
     * Ambil data siswa berdasarkan user yang login.
     * Dipakai oleh semua method agar tidak ada duplikasi kode.
     */
    private function getSiswa()
    {
        return Siswa::query()->with('kelas')->where('nisn', Auth::user()->username)->first();
    }

    public function index()
    {
        $siswa = $this->getSiswa();

        if (!$siswa) {
            return abort(403, 'Data profil siswa tidak ditemukan. Hubungi administrator.');
        }

        $tagihan_aktif = Pembayaran::query()
                            ->with('tagihan.kategori')
                            ->where('id_siswa', $siswa->id_siswa)
                            ->where('status_bayar', 'Belum Lunas')
                            ->orderBy('created_at', 'desc')
                            ->get();

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
        $siswa = $this->getSiswa();
        if (!$siswa) return abort(403, 'Data profil siswa tidak ditemukan.');
        return view('portal.cara-pembayaran', compact('siswa'));
    }

    public function bantuan()
    {
        $siswa = $this->getSiswa();
        if (!$siswa) return abort(403, 'Data profil siswa tidak ditemukan.');
        return view('portal.bantuan', compact('siswa'));
    }

    public function profil()
    {
        $user = Auth::user();
        $siswa = $this->getSiswa();
        if (!$siswa) return abort(403, 'Data profil siswa tidak ditemukan.');

        return view('portal.profil', compact('siswa', 'user'));
    }

    public function updateProfil(Request $request)
    {
        $request->validate([
            'no_hp'    => 'nullable|string|max:20|regex:/^[0-9+\-\s]+$/',
            'password' => 'nullable|string|min:8|confirmed',
        ], [
            'no_hp.regex' => 'Format nomor HP tidak valid. Gunakan angka, +, atau -.',
        ]);

        $user  = Auth::user();
        $siswa = $this->getSiswa();

        // Update no_hp
        if ($siswa) {
            $siswa->no_hp = $request->no_hp;
            $siswa->save();
        }

        // Update password jika diisi
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
            $user->save();
        }

        return redirect()->route('siswa.profil')->with('success', 'Profil dan data kontak berhasil diperbarui!');
    }
}