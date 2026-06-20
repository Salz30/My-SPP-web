<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SiswaController extends Controller
{
    public function index()
    {
        // Mengambil data siswa beserta relasi kelasnya (Eager Loading)
        $siswa = Siswa::with('kelas')->orderBy('created_at', 'desc')->get();
        return view('siswa.index', compact('siswa'));
    }

    public function create()
    {
        // Mengambil semua data kelas untuk ditampilkan di dropdown form
        $kelas = Kelas::all();
        return view('siswa.create', compact('kelas'));
    }

    public function store(Request $request)
    {
        // Validasi ketat: NISN harus berformat angka (numeric) dan unik
        $request->validate([
            'nisn' => 'required|numeric|unique:siswa,nisn',
            'nama_siswa' => 'required|string|max:255',
            'no_hp' => 'nullable|string|max:20',
            'id_kelas' => 'required|exists:kelas,id_kelas',
        ], [
            'nisn.numeric' => 'Format NISN harus berupa angka.',
            'nisn.unique' => 'NISN ini sudah terdaftar di sistem.',
        ]);

        // 1. Buat Akun User untuk siswa
        $user = User::create([
            'name' => $request->nama_siswa,
            'username' => $request->nisn,
            'email' => $request->nisn . '@siswa.myspp.com', // Dummy email karena email required & unique
            'password' => Hash::make($request->nisn), // Default password adalah NISN
            'role' => 'siswa',
        ]);

        // 2. Simpan Data Siswa beserta relasi id_user
        $dataSiswa = $request->all();
        $dataSiswa['id_user'] = $user->id;
        Siswa::create($dataSiswa);

        return redirect()->route('siswa.index')->with('success', 'Data siswa beserta akun login berhasil ditambahkan!');
    }

    public function edit(string $id)
    {
        $siswa = Siswa::findOrFail($id);
        $kelas = Kelas::all();
        return view('siswa.edit', compact('siswa', 'kelas'));
    }

    public function update(Request $request, string $id)
    {
        // Pengecualian validasi unique untuk data yang sedang diedit
        $request->validate([
            'nisn' => 'required|numeric|unique:siswa,nisn,'.$id.',id_siswa',
            'nama_siswa' => 'required|string|max:255',
            'no_hp' => 'nullable|string|max:20',
            'id_kelas' => 'required|exists:kelas,id_kelas',
        ]);

        $siswa = Siswa::findOrFail($id);
        
        // 1. Update data User jika siswa ini punya akun
        if ($siswa->user) {
            $siswa->user->update([
                'name' => $request->nama_siswa,
                'username' => $request->nisn,
                'email' => $request->nisn . '@siswa.myspp.com',
            ]);
        }

        // 2. Update data Siswa
        $siswa->update($request->all());

        return redirect()->route('siswa.index')->with('success', 'Data siswa berhasil diperbarui!');
    }

    public function destroy(string $id)
    {
        $siswa = Siswa::findOrFail($id);
        
        // 1. Hapus akun User terkait (soft delete)
        if ($siswa->user) {
            $siswa->user->delete();
        }

        // 2. Hapus data Siswa (soft delete)
        $siswa->delete();

        return redirect()->route('siswa.index')->with('success', 'Data siswa beserta akun login berhasil dihapus!');
    }
}