<?php

namespace App\Http\Controllers;

use App\Models\Tagihan;
use App\Models\KategoriPembayaran;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\Pembayaran;
use App\Services\ActivityLogger;
use App\Services\WhatsappService;
use Illuminate\Http\Request;

class TagihanController extends Controller
{
    public function index()
    {
        $tagihan = Tagihan::with('kategori')->orderBy('created_at', 'desc')->get();
        return view('tagihan.index', compact('tagihan'));
    }

    public function create()
    {
        $kategori = KategoriPembayaran::all();
        return view('tagihan.create', compact('kategori'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_tagihan' => 'required|string|max:255',
            'id_kategori'  => 'required|exists:kategori_pembayaran,id_kategori',
            'tenggat_waktu'=> 'required|date',
        ]);

        $tagihan = Tagihan::create($request->all());
        $kategori = KategoriPembayaran::find($request->id_kategori);

        ActivityLogger::log(
            'Tambah Data',
            "Membuat tagihan baru: \"{$tagihan->nama_tagihan}\" (Kategori: " . ($kategori->nama_kategori ?? '-') . ", Tenggat: " . \Carbon\Carbon::parse($tagihan->tenggat_waktu)->format('d/m/Y') . ")"
        );

        return redirect()->route('tagihan.index')->with('success', 'Data tagihan berhasil ditambahkan!');
    }

    public function edit(string $id)
    {
        $tagihan = Tagihan::findOrFail($id);
        $kategori = KategoriPembayaran::all();
        return view('tagihan.edit', compact('tagihan', 'kategori'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'nama_tagihan' => 'required|string|max:255',
            'id_kategori'  => 'required|exists:kategori_pembayaran,id_kategori',
            'tenggat_waktu'=> 'required|date',
        ]);

        $tagihan = Tagihan::findOrFail($id);
        $namaLama = $tagihan->nama_tagihan;
        $tagihan->update($request->all());

        ActivityLogger::log(
            'Edit Data',
            "Memperbarui tagihan \"{$namaLama}\" → Nama: \"{$tagihan->nama_tagihan}\", Tenggat: " . \Carbon\Carbon::parse($tagihan->tenggat_waktu)->format('d/m/Y')
        );

        return redirect()->route('tagihan.index')->with('success', 'Data tagihan berhasil diperbarui!');
    }

    public function destroy(string $id)
    {
        $tagihan = Tagihan::findOrFail($id);
        $namaTagihan = $tagihan->nama_tagihan;
        $tagihan->delete();

        ActivityLogger::log(
            'Hapus Data',
            "Menghapus tagihan \"{$namaTagihan}\" dari sistem"
        );

        return redirect()->route('tagihan.index')->with('success', 'Data tagihan berhasil dihapus!');
    }
}