<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use App\Models\Tagihan;
use App\Models\Kelas;
use App\Models\Siswa;
use Illuminate\Http\Request;
use App\Models\LogAktivitas;
use App\Services\ActivityLogger;
use Illuminate\Support\Facades\Auth;

class PembayaranController extends Controller
{
    public function index()
    {
        // Tambahkan query() agar VS Code paham
        $pembayaran = Pembayaran::query()->with(['siswa', 'tagihan.kategori'])->orderBy('created_at', 'desc')->get();
        return view('pembayaran.index', compact('pembayaran'));
    }

    public function create(Request $request)
    {
        $kelas = Kelas::query()->orderBy('nama_kelas', 'asc')->get();
        
        $kelas_dengan_siswa = Kelas::query()->with(['siswa' => function($query) {
            $query->orderBy('nama_siswa', 'asc');
        }])->orderBy('nama_kelas', 'asc')->get();

        $tagihan = Tagihan::query()->with('kategori')->orderBy('created_at', 'desc')->get();

        // Jika ada query param id_tagihan (dari tombol Bebankan di Data Tagihan),
        // kirim ke view agar tagihan tersebut langsung ter-select otomatis
        $selected_tagihan = $request->query('id_tagihan');
        
        return view('pembayaran.create', compact('kelas_dengan_siswa', 'tagihan', 'kelas', 'selected_tagihan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_tagihan' => 'required|exists:tagihan,id_tagihan',
            'tipe_pembebanan' => 'required|in:siswa,kelas,semua',
        ]);

        $id_tagihan = $request->id_tagihan;
        $siswa_ids = []; 

        if ($request->tipe_pembebanan == 'siswa') {
            $request->validate(['id_siswa' => 'required|exists:siswa,id_siswa']);
            $siswa_ids[] = $request->id_siswa; 
            
        } elseif ($request->tipe_pembebanan == 'kelas') {
            $request->validate(['id_kelas' => 'required|exists:kelas,id_kelas']);
            $siswa_ids = Siswa::query()->where('id_kelas', $request->id_kelas)->pluck('id_siswa')->toArray();
            
        } elseif ($request->tipe_pembebanan == 'semua') {
            $siswa_ids = Siswa::query()->pluck('id_siswa')->toArray();
        }

        if (empty($siswa_ids)) {
            return redirect()->back()->withErrors(['id_siswa' => 'Tidak ditemukan data siswa.'])->withInput();
        }

        $count_success = 0;
        $count_skip = 0;

        $siswas = Siswa::query()->with('kelas')->whereIn('id_siswa', $siswa_ids)->get();
        $tagihan = Tagihan::query()->with('kategori')->findOrFail($id_tagihan);

        foreach ($siswas as $siswa) {
            $cekDobel = Pembayaran::query()->where('id_siswa', $siswa->id_siswa)
                                  ->where('id_tagihan', $id_tagihan)
                                  ->first();
            
            if (!$cekDobel) {
                Pembayaran::create([
                    'id_siswa' => $siswa->id_siswa,
                    'id_tagihan' => $id_tagihan,
                    'status_bayar' => 'Belum Lunas',
                    'tanggal_bayar' => null
                ]);
                $count_success++;

                // Kirim notifikasi WA tagihan baru jika nomor HP terisi
                if (!empty($siswa->no_hp)) {
                    $nominal = number_format($tagihan->kategori->nominal_default ?? 0, 0, ',', '.');
                    $tenggat = \Carbon\Carbon::parse($tagihan->tenggat_waktu)->format('d/m/Y');
                    $pesan = "Halo Bapak/Ibu Wali Murid dari *{$siswa->nama_siswa}*,\n\n" .
                             "Kami menginformasikan bahwa terdapat tagihan baru untuk siswa *{$siswa->nama_siswa}* (Kelas: " . ($siswa->kelas->nama_kelas ?? '-') . "):\n" .
                             "- *Tagihan*: {$tagihan->nama_tagihan}\n" .
                             "- *Kategori*: " . ($tagihan->kategori->nama_kategori ?? '-') . "\n" .
                             "- *Nominal*: Rp {$nominal}\n" .
                             "- *Tenggat Waktu*: {$tenggat}\n\n" .
                             "Harap lakukan pembayaran di loket sekolah atau via Portal Siswa sebelum tanggal tenggat waktu. Terima kasih.";
                    \App\Services\WhatsappService::send($siswa->no_hp, $pesan);
                }
            } else {
                $count_skip++;
            }
        }

        $tipe = $request->tipe_pembebanan === 'kelas' ? 'Kelas' : ($request->tipe_pembebanan === 'semua' ? 'Semua Siswa' : 'Individu');
        ActivityLogger::log(
            'Beban Tagihan',
            "Membebankan tagihan \"{$tagihan->nama_tagihan}\" ke {$count_success} siswa (metode: {$tipe}). {$count_skip} siswa dilewati karena sudah ada."
        );

        return redirect()->route('pembayaran.index')->with('success', "Berhasil membebankan tagihan kepada $count_success siswa. ($count_skip dilewati).");
    }

    public function edit(string $id)
    {
        $pembayaran = Pembayaran::query()->with(['siswa', 'tagihan.kategori'])->findOrFail($id);
        return view('pembayaran.edit', compact('pembayaran'));
    }

    public function update(Request $request, string $id)
    {
        // 1. Validasi input dari form
        $request->validate([
            'status_bayar'  => 'required|in:Lunas,Belum Lunas',
            'tanggal_bayar' => 'nullable|date', // Izinkan input tanggal dari kasir
        ]);

        // 2. Cari data pembayaran berdasarkan ID
        $pembayaran = Pembayaran::with(['siswa.kelas', 'tagihan.kategori'])->findOrFail($id);

        // 3. Simpan status lama sebelum diubah (Mencegah spam pencatatan log ganda)
        $statusLama = $pembayaran->status_bayar;

        // 4. Update status pembayaran sesuai input form
        $pembayaran->status_bayar = $request->status_bayar;

        // 5. Catat tanggal bayar:
        //    - Gunakan tanggal yang diisi kasir jika ada
        //    - Fallback ke tanggal hari ini jika kasir tidak mengisi tanggal
        //    - Kosongkan kembali jika status dikembalikan ke Belum Lunas
        if ($request->status_bayar === 'Lunas' && $statusLama !== 'Lunas') {
            $pembayaran->tanggal_bayar = $request->filled('tanggal_bayar')
                ? $request->tanggal_bayar
                : now();
        } elseif ($request->status_bayar === 'Belum Lunas') {
            $pembayaran->tanggal_bayar = null; // Hapus tanggal jika status dibatalkan
        }

        // 6. Simpan perubahan ke database
        $pembayaran->save();
        // Cek apakah statusnya benar-benar BERUBAH menjadi Lunas (bukan cuma di-save ulang)
        if ($pembayaran->status_bayar === 'Lunas' && $statusLama !== 'Lunas') {
            LogAktivitas::create([
                'id_user'   => Auth::id(), // Mendeteksi ID Admin/Kasir yang sedang login
                'tindakan'  => 'Penerimaan Pembayaran',
                'deskripsi' => 'Mengonfirmasi pelunasan tagihan ' . ($pembayaran->tagihan->nama_tagihan ?? 'N/A') . 
                               ' untuk siswa NISN: ' . ($pembayaran->siswa->nisn ?? 'N/A')
            ]);

            // Kirim notifikasi WA Lunas
            $siswa = $pembayaran->siswa;
            if ($siswa && !empty($siswa->no_hp)) {
                $nominal = number_format($pembayaran->tagihan->kategori->nominal_default ?? 0, 0, ',', '.');
                $tanggalBayar = \Carbon\Carbon::parse($pembayaran->tanggal_bayar)->format('d/m/Y H:i');
                $pesan = "Yth. Bapak/Ibu Wali Murid dari *{$siswa->nama_siswa}*,\n\n" .
                         "Terima kasih, pembayaran untuk tagihan berikut telah kami terima secara SAH & LUNAS:\n" .
                         "- *Siswa*: {$siswa->nama_siswa} (Kelas: " . ($siswa->kelas->nama_kelas ?? '-') . ")\n" .
                         "- *Pembayaran*: " . ($pembayaran->tagihan->nama_tagihan ?? '-') . "\n" .
                         "- *Nominal*: Rp {$nominal}\n" .
                         "- *Tanggal Bayar*: {$tanggalBayar}\n\n" .
                         "Bukti pembayaran ini adalah tanda terima resmi. Terima kasih.";
                \App\Services\WhatsappService::send($siswa->no_hp, $pesan);
            }
        }
        // 7. Kembalikan ke halaman sebelumnya dengan pesan sukses
        return redirect()->route('pembayaran.index')->with('success', 'Status pembayaran berhasil diperbarui!');
    }

    public function destroy(string $id)
    {
        $pembayaran = Pembayaran::query()->with(['siswa', 'tagihan'])->findOrFail($id);
        $namaSiswa  = $pembayaran->siswa->nama_siswa ?? '-';
        $namaTagihan = $pembayaran->tagihan->nama_tagihan ?? '-';
        $status     = $pembayaran->status_bayar;
        $pembayaran->delete();

        ActivityLogger::log(
            'Hapus Data',
            "Menghapus transaksi tagihan \"{$namaTagihan}\" milik siswa \"{$namaSiswa}\" (Status: {$status}) dari sistem"
        );

        return redirect()->route('pembayaran.index')->with('success', 'Data transaksi berhasil dihapus!');
    }

    public function searchSiswa(Request $request)
    {
        $search = $request->q;
        $siswa = Siswa::query()->where('nama_siswa', 'LIKE', "%$search%")
                      ->orWhere('nisn', 'LIKE', "%$search%")
                      ->limit(10)
                      ->get(['id_siswa', 'nama_siswa', 'nisn']);

        $results = [];
        foreach ($siswa as $s) {
            $results[] = [
                'id' => $s->id_siswa,
                'text' => $s->nisn . ' - ' . $s->nama_siswa
            ];
        }
        return response()->json($results);
    }

    public function kwitansi(string $id)
    {
        // Mengambil data transaksi spesifik beserta relasinya
        $pembayaran = Pembayaran::query()->with(['siswa.kelas', 'tagihan.kategori'])->findOrFail($id);

        // Keamanan tambahan: Cegah admin mencetak kwitansi jika belum lunas
        if ($pembayaran->status_bayar !== 'Lunas') {
            return redirect()->route('pembayaran.index')->with('error', 'Kwitansi hanya bisa dicetak untuk tagihan yang sudah Lunas!');
        }

        // Tampilkan halaman cetak
        return view('pembayaran.kwitansi', compact('pembayaran'));
    }

    public function kasirCepat()
    {
        return view('pembayaran.kasir_cepat');
    }

    public function getTunggakanSiswa(string $id)
    {
        // Cari siswa
        $siswa = Siswa::query()->with('kelas')->findOrFail($id);

        // Cari tagihan belum lunas
        $tunggakan = Pembayaran::query()
            ->with(['tagihan.kategori'])
            ->where('id_siswa', $id)
            ->where('status_bayar', 'Belum Lunas')
            ->orderBy('created_at', 'asc')
            ->get();

        $dataTunggakan = [];
        foreach ($tunggakan as $t) {
            $dataTunggakan[] = [
                'id_pembayaran' => $t->id_pembayaran,
                'nama_tagihan' => $t->tagihan->nama_tagihan,
                'nominal' => $t->tagihan->kategori->nominal_default ?? 0,
                'nominal_format' => number_format($t->tagihan->kategori->nominal_default ?? 0, 0, ',', '.'),
                'tanggal_tagihan' => $t->created_at->format('d/m/Y')
            ];
        }

        return response()->json([
            'siswa' => [
                'nama' => $siswa->nama_siswa,
                'nisn' => $siswa->nisn,
                'kelas' => $siswa->kelas->nama_kelas ?? '-'
            ],
            'tunggakan' => $dataTunggakan
        ]);
    }

    public function prosesKasirCepat(Request $request)
    {
        $request->validate([
            'id_siswa' => 'required|exists:siswa,id_siswa',
            'pembayaran_ids' => 'required|array',
            'pembayaran_ids.*' => 'exists:pembayaran,id_pembayaran',
        ]);

        $pembayaranIds = $request->pembayaran_ids;
        $count = 0;

        foreach ($pembayaranIds as $id_pembayaran) {
            $pembayaran = Pembayaran::query()->with(['siswa.kelas', 'tagihan.kategori'])->findOrFail($id_pembayaran);
            
            if ($pembayaran->status_bayar !== 'Lunas') {
                $pembayaran->status_bayar = 'Lunas';
                $pembayaran->tanggal_bayar = now();
                $pembayaran->save();

                LogAktivitas::create([
                    'id_user'   => Auth::id(),
                    'tindakan'  => 'Penerimaan Pembayaran',
                    'deskripsi' => 'Kasir Cepat: Pelunasan tagihan ' . ($pembayaran->tagihan->nama_tagihan ?? 'N/A') . 
                                   ' untuk siswa NISN: ' . ($pembayaran->siswa->nisn ?? 'N/A')
                ]);

                // Kirim notifikasi WA Lunas
                $siswa = $pembayaran->siswa;
                if ($siswa && !empty($siswa->no_hp)) {
                    $nominal = number_format($pembayaran->tagihan->kategori->nominal_default ?? 0, 0, ',', '.');
                    $tanggalBayar = \Carbon\Carbon::parse($pembayaran->tanggal_bayar)->format('d/m/Y H:i');
                    $pesan = "Yth. Bapak/Ibu Wali Murid dari *{$siswa->nama_siswa}*,\n\n" .
                             "Terima kasih, pembayaran untuk tagihan berikut telah kami terima secara SAH & LUNAS:\n" .
                             "- *Siswa*: {$siswa->nama_siswa} (Kelas: " . ($siswa->kelas->nama_kelas ?? '-') . ")\n" .
                             "- *Pembayaran*: " . ($pembayaran->tagihan->nama_tagihan ?? '-') . "\n" .
                             "- *Nominal*: Rp {$nominal}\n" .
                             "- *Tanggal Bayar*: {$tanggalBayar}\n\n" .
                             "Bukti pembayaran ini adalah tanda terima resmi. Terima kasih.";
                    \App\Services\WhatsappService::send($siswa->no_hp, $pesan);
                }

                $count++;
            }
        }

        return redirect()->route('pembayaran.kasir-cepat')->with('success', "Berhasil melunasi $count tagihan siswa!");
    }
}