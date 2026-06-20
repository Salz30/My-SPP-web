<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\User;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class SiswaController extends Controller
{
    public function index()
    {
        $siswa = Siswa::with('kelas')->orderBy('created_at', 'desc')->get();
        return view('siswa.index', compact('siswa'));
    }

    public function create()
    {
        $kelas = Kelas::all();
        return view('siswa.create', compact('kelas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nisn'      => [
                'required',
                'numeric',
                // Hanya cek duplikat pada data yang BELUM dihapus (deleted_at IS NULL)
                Rule::unique('siswa', 'nisn')->whereNull('deleted_at'),
            ],
            'nama_siswa' => 'required|string|max:255',
            'no_hp'      => 'nullable|string|max:20',
            'id_kelas'   => 'required|exists:kelas,id_kelas',
        ], [
            'nisn.numeric' => 'Format NISN harus berupa angka.',
            'nisn.unique'  => 'NISN ini sudah terdaftar di sistem.',
        ]);

        // Cek apakah akun User dengan NISN ini pernah ada (soft-deleted)
        // Jika ada, pulihkan akun lama daripada membuat duplikat yang bentrok
        $existingUser = User::withTrashed()
            ->where('username', $request->nisn)
            ->first();

        if ($existingUser) {
            // Pulihkan & perbarui akun lama
            $existingUser->restore();
            $existingUser->update([
                'name'     => $request->nama_siswa,
                'email'    => $request->nisn . '@siswa.myspp.com',
                'password' => Hash::make($request->nisn),
                'role'     => 'siswa',
            ]);
            $user = $existingUser;
        } else {
            // Buat akun User baru
            $user = User::create([
                'name'     => $request->nama_siswa,
                'username' => $request->nisn,
                'email'    => $request->nisn . '@siswa.myspp.com',
                'password' => Hash::make($request->nisn),
                'role'     => 'siswa',
            ]);
        }

        // Cek juga apakah data Siswa dengan NISN ini pernah ada (soft-deleted)
        $existingSiswa = Siswa::withTrashed()
            ->where('nisn', $request->nisn)
            ->first();

        if ($existingSiswa) {
            // Pulihkan & perbarui data siswa lama
            $existingSiswa->restore();
            $existingSiswa->update([
                'nama_siswa' => $request->nama_siswa,
                'no_hp'      => $request->no_hp,
                'id_kelas'   => $request->id_kelas,
                'id_user'    => $user->id,
            ]);
            $siswa = $existingSiswa;
        } else {
            // Buat data Siswa baru
            $dataSiswa = $request->all();
            $dataSiswa['id_user'] = $user->id;
            $siswa = Siswa::create($dataSiswa);
        }

        $kelas = Kelas::find($request->id_kelas);
        ActivityLogger::log(
            'Tambah Data',
            "Mendaftarkan siswa baru: \"{$siswa->nama_siswa}\" (NISN: {$siswa->nisn}) ke kelas " . ($kelas->nama_kelas ?? '-')
        );

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
        $request->validate([
            'nisn'      => [
                'required',
                'numeric',
                // Pengecualian validasi unique untuk data yang sedang diedit,
                // dan hanya cek data yang belum dihapus
                Rule::unique('siswa', 'nisn')->ignore($id, 'id_siswa')->whereNull('deleted_at'),
            ],
            'nama_siswa' => 'required|string|max:255',
            'no_hp'      => 'nullable|string|max:20',
            'id_kelas'   => 'required|exists:kelas,id_kelas',
        ]);

        $siswa = Siswa::with('kelas')->findOrFail($id);
        $namaLama = $siswa->nama_siswa;

        // Update data User jika siswa ini punya akun
        if ($siswa->user) {
            $siswa->user->update([
                'name'     => $request->nama_siswa,
                'username' => $request->nisn,
                'email'    => $request->nisn . '@siswa.myspp.com',
            ]);
        }

        $siswa->update($request->all());

        $kelasLama = $siswa->kelas->nama_kelas ?? '-';
        ActivityLogger::log(
            'Edit Data',
            "Memperbarui data siswa \"{$namaLama}\" → Nama: \"{$request->nama_siswa}\", NISN: {$request->nisn}, Kelas: {$kelasLama}"
        );

        return redirect()->route('siswa.index')->with('success', 'Data siswa berhasil diperbarui!');
    }

    public function destroy(string $id)
    {
        $siswa = Siswa::with('kelas')->findOrFail($id);
        $namaSiswa  = $siswa->nama_siswa;
        $nisnSiswa  = $siswa->nisn;
        $kelasNama  = $siswa->kelas->nama_kelas ?? '-';

        // Hapus akun User terkait (soft delete)
        if ($siswa->user) {
            $siswa->user->delete();
        }

        // Hapus data Siswa (soft delete)
        $siswa->delete();

        ActivityLogger::log(
            'Hapus Data',
            "Menghapus data siswa \"{$namaSiswa}\" (NISN: {$nisnSiswa}, Kelas: {$kelasNama}) beserta akun loginnya"
        );

        return redirect()->route('siswa.index')->with('success', 'Data siswa beserta akun login berhasil dihapus!');
    }
}