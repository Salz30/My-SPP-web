<?php

namespace App\Http\Controllers;

use App\Models\KategoriPembayaran;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

class KategoriPembayaranController extends Controller
{
    public function index()
    {
        // Tambahan query() untuk menghilangkan peringatan VS Code
        $kategori = KategoriPembayaran::query()->orderBy('created_at', 'desc')->get();
        return view('kategori.index', compact('kategori'));
    }

    public function create()
    {
        return view('kategori.create');
    }

    public function store(Request $request)
    {
        // Bersihkan titik dari input nominal_default
        $request->merge([
            'nominal_default' => str_replace('.', '', $request->nominal_default)
        ]);

        $request->validate([
            'nama_kategori' => 'required|string|max:255',
            'nominal_default' => 'required|numeric|min:0',
        ]);

        KategoriPembayaran::create($request->all());

        return redirect()->route('kategori.index')->with('success', 'Kategori pembayaran berhasil ditambahkan!');
    }

    public function edit(string $id)
    {
        // Tambahan query() sebelum findOrFail
        $kategori = KategoriPembayaran::query()->findOrFail($id);
        return view('kategori.edit', compact('kategori'));
    }

    public function update(Request $request, string $id)
    {
        // Bersihkan titik dari input nominal_default
        $request->merge([
            'nominal_default' => str_replace('.', '', $request->nominal_default)
        ]);

        $request->validate([
            'nama_kategori' => 'required|string|max:255',
            'nominal_default' => 'required|numeric|min:0',
        ]);

        // Tambahan query()
        $kategori = KategoriPembayaran::query()->findOrFail($id);
        $kategori->update($request->all());

        return redirect()->route('kategori.index')->with('success', 'Kategori pembayaran berhasil diperbarui!');
    }

    public function destroy(string $id)
    {
        // Tambahan query()
        $kategori = KategoriPembayaran::query()->findOrFail($id);
        $kategori->delete();

        return redirect()->route('kategori.index')->with('success', 'Kategori pembayaran berhasil dihapus!');
    }
}