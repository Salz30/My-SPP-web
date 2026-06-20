<?php

namespace App\Http\Controllers;

use App\Models\KategoriPembayaran;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

class KategoriPembayaranController extends Controller
{
    public function index()
    {
        $kategori = KategoriPembayaran::query()->orderBy('created_at', 'desc')->get();
        return view('kategori.index', compact('kategori'));
    }

    public function create()
    {
        return view('kategori.create');
    }

    public function store(Request $request)
    {
        $request->merge([
            'nominal_default' => str_replace('.', '', $request->nominal_default)
        ]);

        $request->validate([
            'nama_kategori'   => 'required|string|max:255',
            'nominal_default' => 'required|numeric|min:0',
        ]);

        $kategori = KategoriPembayaran::create($request->all());

        ActivityLogger::log(
            'Tambah Data',
            "Menambahkan kategori pembayaran baru: \"{$kategori->nama_kategori}\" dengan nominal Rp " . number_format($kategori->nominal_default, 0, ',', '.')
        );

        return redirect()->route('kategori.index')->with('success', 'Kategori pembayaran berhasil ditambahkan!');
    }

    public function edit(string $id)
    {
        $kategori = KategoriPembayaran::query()->findOrFail($id);
        return view('kategori.edit', compact('kategori'));
    }

    public function update(Request $request, string $id)
    {
        $request->merge([
            'nominal_default' => str_replace('.', '', $request->nominal_default)
        ]);

        $request->validate([
            'nama_kategori'   => 'required|string|max:255',
            'nominal_default' => 'required|numeric|min:0',
        ]);

        $kategori = KategoriPembayaran::query()->findOrFail($id);
        $namaLama = $kategori->nama_kategori;
        $nominalLama = number_format($kategori->nominal_default, 0, ',', '.');
        $kategori->update($request->all());

        ActivityLogger::log(
            'Edit Data',
            "Memperbarui kategori \"{$namaLama}\" (Rp {$nominalLama}) → Nama: \"{$kategori->nama_kategori}\", Nominal: Rp " . number_format($kategori->nominal_default, 0, ',', '.')
        );

        return redirect()->route('kategori.index')->with('success', 'Kategori pembayaran berhasil diperbarui!');
    }

    public function destroy(string $id)
    {
        $kategori = KategoriPembayaran::query()->findOrFail($id);
        $namaKategori = $kategori->nama_kategori;
        $nominalKategori = number_format($kategori->nominal_default, 0, ',', '.');
        $kategori->delete();

        ActivityLogger::log(
            'Hapus Data',
            "Menghapus kategori pembayaran \"{$namaKategori}\" (nominal Rp {$nominalKategori}) dari sistem"
        );

        return redirect()->route('kategori.index')->with('success', 'Kategori pembayaran berhasil dihapus!');
    }

    public function bulkDestroy(Request $request)
    {
        $request->validate(['ids' => 'required|array']);
        $kategori = KategoriPembayaran::whereIn('id_kategori', $request->ids)->get();
        $count = $kategori->count();

        foreach ($kategori as $k) {
            $k->delete();
        }

        ActivityLogger::log('Hapus Massal', "Menghapus massal {$count} data kategori pembayaran.");
        return redirect()->route('kategori.index')->with('success', "{$count} data kategori berhasil dihapus secara massal!");
    }
}