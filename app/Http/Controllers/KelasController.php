<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    public function index()
    {
        // Mengambil semua data kelas dan mengurutkannya dari yang terbaru
        $kelas = Kelas::orderBy('created_at', 'desc')->get();
        return view('kelas.index', compact('kelas'));
    }

    public function create()
    {
        return view('kelas.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kelas' => 'required|string|max:255',
            'wali_kelas' => 'required|string|max:255',
        ]);

        $kelas = Kelas::create($request->all());

        ActivityLogger::log(
            'Tambah Data',
            "Menambahkan kelas baru: \"{$kelas->nama_kelas}\" dengan wali kelas \"{$kelas->wali_kelas}\""
        );

        return redirect()->route('kelas.index')->with('success', 'Data kelas berhasil ditambahkan!');
    }

    public function edit(string $id)
    {
        $kelas = Kelas::findOrFail($id);
        return view('kelas.edit', compact('kelas'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'nama_kelas' => 'required|string|max:255',
            'wali_kelas' => 'required|string|max:255',
        ]);

        $kelas = Kelas::findOrFail($id);
        $namaLama = $kelas->nama_kelas;
        $kelas->update($request->all());

        ActivityLogger::log(
            'Edit Data',
            "Memperbarui data kelas \"{$namaLama}\" → Nama: \"{$kelas->nama_kelas}\", Wali Kelas: \"{$kelas->wali_kelas}\""
        );

        return redirect()->route('kelas.index')->with('success', 'Data kelas berhasil diperbarui!');
    }

    public function destroy(string $id)
    {
        $kelas = Kelas::findOrFail($id);
        $namaKelas = $kelas->nama_kelas;
        $kelas->delete();

        ActivityLogger::log(
            'Hapus Data',
            "Menghapus kelas \"{$namaKelas}\" dari sistem"
        );

        return redirect()->route('kelas.index')->with('success', 'Data kelas berhasil dihapus!');
    }
}