<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\Pembayaran;
use App\Models\Tagihan;

class DashboardController extends Controller
{
    public function index()
    {
        // Mengambil semua data statistik yang dibutuhkan oleh dashboard
        $total_siswa = Siswa::query()->count();
        $total_kelas = Kelas::query()->count();
        $total_tagihan = Tagihan::query()->count(); 
        
        $pembayaran_lunas = Pembayaran::query()->where('status_bayar', 'Lunas')->count();
        $pembayaran_belum_lunas = Pembayaran::query()->where('status_bayar', 'Belum Lunas')->count();

        // ==========================================
        // Data Grafik Pemasukan per Bulan Tahun Ini
        // ==========================================
        $tahun_sekarang = date('Y');
        $data_pemasukan = array_fill(1, 12, 0); // Array [1=>0, 2=>0, ..., 12=>0]

        $pemasukan_db = Pembayaran::query()
            ->join('tagihan', 'pembayaran.id_tagihan', '=', 'tagihan.id_tagihan')
            ->join('kategori_pembayaran', 'tagihan.id_kategori', '=', 'kategori_pembayaran.id_kategori')
            ->where('pembayaran.status_bayar', 'Lunas')
            ->whereYear('pembayaran.tanggal_bayar', $tahun_sekarang)
            ->selectRaw('MONTH(pembayaran.tanggal_bayar) as bulan, SUM(kategori_pembayaran.nominal_default) as total')
            ->groupBy('bulan')
            ->get();

        foreach ($pemasukan_db as $item) {
            $data_pemasukan[$item->bulan] = (int) $item->total;
        }
        
        $chart_data = array_values($data_pemasukan); // Buat menjadi index dari 0

        // Mengirimkan semua data ke view dashboard
        return view('dashboard', compact(
            'total_siswa', 
            'total_kelas', 
            'total_tagihan', 
            'pembayaran_lunas', 
            'pembayaran_belum_lunas',
            'chart_data'
        ));
    }
}