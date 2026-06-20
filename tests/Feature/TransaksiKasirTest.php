<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\Tagihan;
use App\Models\KategoriPembayaran;
use App\Models\Pembayaran;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class TransaksiKasirTest extends TestCase
{
    use RefreshDatabase;

    public function test_kasir_bisa_memproses_pelunasan_tagihan(): void
    {
        // Fake HTTP req ke server WA Fonnte agar tidak betul-betul dikirim saat test
        Http::fake();

        // 1. Setup Data Awal
        $admin = User::factory()->create(['role' => 'admin']);
        $kelas = Kelas::create(['nama_kelas' => 'X RPL']);
        $siswa = Siswa::create(['nisn' => '9999', 'nama_siswa' => 'Budi', 'id_kelas' => $kelas->id_kelas, 'no_hp' => '08123']);
        $kategori = KategoriPembayaran::create(['nama_kategori' => 'SPP', 'nominal_default' => 150000]);
        $tagihan = Tagihan::create(['id_kategori' => $kategori->id_kategori, 'nama_tagihan' => 'SPP Januari', 'tenggat_waktu' => '2026-01-31']);
        
        // Buat tagihan yang belum lunas
        $pembayaran = Pembayaran::create([
            'id_siswa' => $siswa->id_siswa,
            'id_tagihan' => $tagihan->id_tagihan,
            'status_bayar' => 'Belum Lunas'
        ]);

        // 2. Eksekusi: Admin mengirim form update status ke Lunas
        $response = $this->actingAs($admin)->put("/pembayaran/{$pembayaran->id_pembayaran}", [
            'status_bayar' => 'Lunas'
        ]);

        // 3. Verifikasi Database
        $this->assertDatabaseHas('pembayaran', [
            'id_pembayaran' => $pembayaran->id_pembayaran,
            'status_bayar' => 'Lunas',
        ]);

        // Verifikasi bahwa tanggal_bayar diisi setelah lunas (bukan null)
        $pembayaranTerupdate = Pembayaran::find($pembayaran->id_pembayaran);
        $this->assertNotNull($pembayaranTerupdate->tanggal_bayar);

        // Verifikasi Log Aktivitas otomatis tercatat
        $this->assertDatabaseHas('log_aktivitas', [
            'id_user' => $admin->id,
            'tindakan' => 'Penerimaan Pembayaran'
        ]);

        $response->assertRedirect('/pembayaran');
    }
}
