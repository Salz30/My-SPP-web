<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KelasController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\KategoriPembayaranController;
use App\Http\Controllers\TagihanController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\LaporanController;
use App\Http\Middleware\CheckRole;

Route::get('/', function () {
    if (auth()->check()) {
        if (auth()->user()->role === 'admin') {
            return redirect()->route('dashboard');
        } elseif (auth()->user()->role === 'siswa') {
            return redirect()->route('siswa.dashboard');
        }
    }
    return redirect()->route('login');
});

// Kelompok Induk: Hanya bisa diakses oleh user yang sudah Login
Route::middleware(['auth'])->group(function () {
    
    // ==========================================
    // RUTE BERSAMA (Admin & Siswa bisa akses profil)
    // ==========================================
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ==========================================
    // AREA KHUSUS ADMIN / KASIR
    // ==========================================
    Route::middleware([CheckRole::class.':admin'])->group(function () {
        
        // Dashboard Admin
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        
        // Master Data
        Route::post('kelas/bulk-destroy', [KelasController::class, 'bulkDestroy'])->name('kelas.bulk-destroy');
        Route::resource('kelas', KelasController::class);
        
        Route::post('siswa/bulk-destroy', [SiswaController::class, 'bulkDestroy'])->name('siswa.bulk-destroy');
        Route::resource('siswa', SiswaController::class);
        
        Route::post('kategori/bulk-destroy', [KategoriPembayaranController::class, 'bulkDestroy'])->name('kategori.bulk-destroy');
        Route::resource('kategori', KategoriPembayaranController::class);

        Route::post('tagihan/bulk-destroy', [TagihanController::class, 'bulkDestroy'])->name('tagihan.bulk-destroy');
        Route::resource('tagihan', TagihanController::class);
        
        // Transaksi Pembayaran
        Route::post('pembayaran/bulk-destroy', [PembayaranController::class, 'bulkDestroy'])->name('pembayaran.bulk-destroy');
        Route::resource('pembayaran', PembayaranController::class);
        Route::get('/pembayaran/{id}/kwitansi', [PembayaranController::class, 'kwitansi'])->name('pembayaran.kwitansi');
        Route::get('/api/siswa/search', [PembayaranController::class, 'searchSiswa'])->name('api.siswa.search');
        Route::get('/kasir-cepat', [PembayaranController::class, 'kasirCepat'])->name('pembayaran.kasir-cepat');
        Route::get('/api/siswa/{id}/tunggakan', [PembayaranController::class, 'getTunggakanSiswa'])->name('api.siswa.tunggakan');
        Route::post('/kasir-cepat/bayar', [PembayaranController::class, 'prosesKasirCepat'])->name('pembayaran.proses-kasir-cepat');

        // Laporan Keuangan
        Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
        Route::post('/laporan/cetak', [LaporanController::class, 'cetak'])->name('laporan.cetak');
        Route::post('/laporan/excel', [LaporanController::class, 'exportExcel'])->name('laporan.excel');
        Route::post('/laporan/pdf', [LaporanController::class, 'exportPdf'])->name('laporan.pdf');

        // Route Log Aktivitas
        Route::get('/log-aktivitas', function () {
            $query = \App\Models\LogAktivitas::with('user')->orderBy('created_at', 'desc');

            // Filter berdasarkan jenis tindakan
            if (request('filter_tindakan')) {
                $query->where('tindakan', request('filter_tindakan'));
            }
            // Filter berdasarkan tanggal
            if (request('filter_tanggal')) {
                $query->whereDate('created_at', request('filter_tanggal'));
            }
            // Filter berdasarkan nama pengguna
            if (request('filter_user')) {
                $query->whereHas('user', function ($q) {
                    $q->where('name', 'LIKE', '%' . request('filter_user') . '%');
                });
            }

            $logs = $query->get();
            return view('log.index', compact('logs'));
        })->name('log.index');
    });

    // ==========================================
    // AREA KHUSUS SISWA
    // ==========================================
    Route::middleware([CheckRole::class.':siswa'])->group(function () {
        
        Route::get('/portal', [App\Http\Controllers\PortalController::class, 'index'])->name('siswa.dashboard');
        Route::get('/portal/cara-pembayaran', [App\Http\Controllers\PortalController::class, 'caraPembayaran'])->name('siswa.cara-pembayaran');
        Route::get('/portal/bantuan', [App\Http\Controllers\PortalController::class, 'bantuan'])->name('siswa.bantuan');
        Route::get('/portal/profil', [App\Http\Controllers\PortalController::class, 'profil'])->name('siswa.profil');
        Route::post('/portal/profil', [App\Http\Controllers\PortalController::class, 'updateProfil'])->name('siswa.profil.update');
        Route::get('/portal/kwitansi/{id}', [App\Http\Controllers\PortalController::class, 'kwitansi'])->name('siswa.kwitansi');
    });

});

// Memanggil rute-rute autentikasi bawaan Breeze (Login, Register, Logout)
require __DIR__.'/auth.php';