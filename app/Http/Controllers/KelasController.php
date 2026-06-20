<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
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
        // Validasi input dari user
        $request->validate([
            'nama_kelas' => 'required|string|max:255',
            'wali_kelas' => 'nullable|string|max:255',
        ]);

        // Simpan data ke database
        Kelas::create($request->all());

        // Arahkan kembali ke halaman index dengan pesan sukses
        return redirect()->route('kelas.index')->with('success', 'Data kelas berhasil ditambahkan!');
    }

    public function edit(string $id)
    {
        // Mencari data kelas berdasarkan primary key (id_kelas)
        $kelas = Kelas::findOrFail($id);
        
        return view('kelas.edit', compact('kelas'));
    }

    public function update(Request $request, string $id)
    {
        // Validasi input
        $request->validate([
            'nama_kelas' => 'required|string|max:255',
            'wali_kelas' => 'nullable|string|max:255',
        ]);

        // Cari data dan perbarui
        $kelas = Kelas::findOrFail($id);
        $kelas->update($request->all());

        return redirect()->route('kelas.index')->with('success', 'Data kelas berhasil diperbarui!');
    }

    public function destroy(string $id)
    {
        // Cari data dan hapus
        $kelas = Kelas::findOrFail($id);
        $kelas->delete();

        return redirect()->route('kelas.index')->with('success', 'Data kelas berhasil dihapus!');
    }
}