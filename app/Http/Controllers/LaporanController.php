<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pembayaran;
use App\Models\Kelas;

class LaporanController extends Controller
{
    public function index()
    {
        $kelas = Kelas::query()->orderBy('nama_kelas', 'asc')->get();
        return view('laporan.index', compact('kelas'));
    }

    public function cetak(Request $request)
    {
        // 1. Bangun fondasi pencarian
        $query = Pembayaran::query()->with(['siswa.kelas', 'tagihan.kategori']);

        // 2. Filter Rentang Tanggal (Gunakan filled agar kebal dari input kosong)
        if ($request->filled('tanggal_awal') && $request->filled('tanggal_akhir')) {
            // Jika mencari yang Belum Lunas, filter berdasarkan kapan tagihan itu dibuat di sistem
            if ($request->status_bayar == 'Belum Lunas') {
                $query->whereDate('created_at', '>=', $request->tanggal_awal)
                      ->whereDate('created_at', '<=', $request->tanggal_akhir);
            } else {
                // Jika mencari Lunas atau Semua, filter berdasarkan kapan uang dibayarkan
                $query->whereDate('tanggal_bayar', '>=', $request->tanggal_awal)
                      ->whereDate('tanggal_bayar', '<=', $request->tanggal_akhir);
            }
        }

        // 3. Filter Status Pembayaran
        if ($request->filled('status_bayar')) {
            $query->where('status_bayar', $request->status_bayar);
        }

        // 4. Filter Berdasarkan Kelas
        if ($request->filled('id_kelas')) {
            $query->whereHas('siswa', function ($q) use ($request) {
                $q->where('id_kelas', $request->id_kelas);
            });
        }

        // 5. Eksekusi query (Urutkan dari data terbaru DIBUAT, bukan DIBAYAR agar data tidak hilang)
        $laporan = $query->orderBy('created_at', 'desc')->get();

        // Tangkap kembali variabel untuk dilempar ke view cetak
        $tanggal_awal = $request->tanggal_awal;
        $tanggal_akhir = $request->tanggal_akhir;
        $status_bayar = $request->status_bayar;
        $id_kelas = $request->id_kelas;

        return view('laporan.cetak', compact('laporan', 'tanggal_awal', 'tanggal_akhir', 'status_bayar', 'id_kelas'));
    }
    public function exportExcel(Request $request)
    {
        // 1. Ambil kriteria filter dari form
        $tanggal_awal = $request->tanggal_awal;
        $tanggal_akhir = $request->tanggal_akhir;
        $status_bayar = $request->status_bayar;
        $id_kelas = $request->id_kelas;

        // 2. Lakukan Query persis seperti fungsi cetak
        $query = \App\Models\Pembayaran::query()->with(['siswa.kelas', 'tagihan.kategori']);

        if (filled($tanggal_awal) && filled($tanggal_akhir)) {
            $query->whereBetween('tanggal_bayar', [$tanggal_awal, $tanggal_akhir]);
        }
        if (filled($status_bayar)) {
            $query->where('status_bayar', $status_bayar);
        }
        if (filled($id_kelas)) {
            $query->whereHas('siswa', function ($q) use ($id_kelas) {
                $q->where('id_kelas', $id_kelas);
            });
        }

        $pembayaran = $query->orderBy('tanggal_bayar', 'desc')->get();

        // 3. Konfigurasi Header File Excel (CSV)
        $fileName = 'Laporan_Keuangan_MySPP_' . date('Y-m-d') . '.csv';
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        // 4. Proses Stream Data (Sangat ringan untuk server)
        $callback = function() use($pembayaran) {
            $file = fopen('php://output', 'w');
            
            // Tambahkan BOM (Byte Order Mark) agar Excel membaca karakter dengan benar
            fputs($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // Tulis Baris Judul Kolom
            fputcsv($file, ['No', 'Nama Siswa', 'NISN', 'Kelas', 'Nama Tagihan', 'Nominal (Rp)', 'Tanggal Bayar', 'Status'], ';');

            // Tulis Isi Data
            $no = 1;
            foreach ($pembayaran as $row) {
                fputcsv($file, [
                    $no++,
                    $row->siswa->nama_siswa ?? '-',
                    $row->siswa->nisn ?? '-',
                    $row->siswa->kelas->nama_kelas ?? '-',
                    $row->tagihan->nama_tagihan ?? '-',
                    $row->tagihan->kategori->nominal_default ?? 0,
                    $row->tanggal_bayar ? \Carbon\Carbon::parse($row->tanggal_bayar)->format('d-m-Y') : '-',
                    $row->status_bayar
                ], ';'); // Menggunakan titik koma (;) sebagai pemisah standar Excel regional Indonesia
            }

            fclose($file);
        };

        // Kembalikan file unduhan ke browser
        return response()->stream($callback, 200, $headers);
    }

    public function exportPdf(Request $request)
    {
        // 1. Ambil kriteria filter dari form
        $tanggal_awal = $request->tanggal_awal;
        $tanggal_akhir = $request->tanggal_akhir;
        $status_bayar = $request->status_bayar;
        $id_kelas = $request->id_kelas;

        // 2. Lakukan Query
        $query = \App\Models\Pembayaran::query()->with(['siswa.kelas', 'tagihan.kategori']);

        if (filled($tanggal_awal) && filled($tanggal_akhir)) {
            $query->whereBetween('tanggal_bayar', [$tanggal_awal, $tanggal_akhir]);
        }
        if (filled($status_bayar)) {
            $query->where('status_bayar', $status_bayar);
        }
        if (filled($id_kelas)) {
            $query->whereHas('siswa', function ($q) use ($id_kelas) {
                $q->where('id_kelas', $id_kelas);
            });
        }

        $pembayaran = $query->orderBy('tanggal_bayar', 'desc')->get();

        // 3. Generate PDF
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('laporan.pdf', compact(
            'pembayaran', 'tanggal_awal', 'tanggal_akhir', 'status_bayar'
        ));

        // Format kertas dan orientasi
        $pdf->setPaper('A4', 'landscape');

        // Download otomatis
        return $pdf->download('Laporan_Keuangan_MySPP_' . date('Y-m-d') . '.pdf');
    }
}