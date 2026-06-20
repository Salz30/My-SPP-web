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

class TagihanTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_bisa_membebankan_tagihan_ke_seluruh_siswa_di_satu_kelas(): void
    {
        Http::fake();

        $admin = User::factory()->create(['role' => 'admin']);
        $kelas = Kelas::create(['nama_kelas' => 'XI RPL']);
        
        // Buat 3 siswa di kelas tersebut
        Siswa::create(['nisn' => '111', 'nama_siswa' => 'Siswa A', 'id_kelas' => $kelas->id_kelas]);
        Siswa::create(['nisn' => '222', 'nama_siswa' => 'Siswa B', 'id_kelas' => $kelas->id_kelas]);
        Siswa::create(['nisn' => '333', 'nama_siswa' => 'Siswa C', 'id_kelas' => $kelas->id_kelas]);

        $kategori = KategoriPembayaran::create(['nama_kategori' => 'SPP', 'nominal_default' => 200000]);
        $tagihan = Tagihan::create(['id_kategori' => $kategori->id_kategori, 'nama_tagihan' => 'SPP Februari', 'tenggat_waktu' => '2026-02-28']);

        // Admin submit form pembebanan berdasarkan kelas
        $response = $this->actingAs($admin)->post('/pembayaran', [
            'id_tagihan' => $tagihan->id_tagihan,
            'tipe_pembebanan' => 'kelas',
            'id_kelas' => $kelas->id_kelas
        ]);

        // Pastikan ada 3 record pembayaran baru di database
        $this->assertDatabaseCount('pembayaran', 3);
        $response->assertRedirect('/pembayaran');
    }

    public function test_sistem_menolak_membuat_tagihan_ganda_untuk_siswa_yang_sama(): void
    {
        Http::fake();
        $admin = User::factory()->create(['role' => 'admin']);
        $kelas = Kelas::create(['nama_kelas' => 'XII RPL']);
        $siswa = Siswa::create(['nisn' => '444', 'nama_siswa' => 'Siswa D', 'id_kelas' => $kelas->id_kelas]);
        
        $kategori = KategoriPembayaran::create(['nama_kategori' => 'SPP', 'nominal_default' => 200000]);
        $tagihan = Tagihan::create(['id_kategori' => $kategori->id_kategori, 'nama_tagihan' => 'SPP Maret', 'tenggat_waktu' => '2026-03-31']);

        // Pembebanan PERTAMA (Berhasil)
        $this->actingAs($admin)->post('/pembayaran', [
            'id_tagihan' => $tagihan->id_tagihan,
            'tipe_pembebanan' => 'siswa',
            'id_siswa' => $siswa->id_siswa
        ]);

        $this->assertDatabaseCount('pembayaran', 1); // 1 tagihan masuk

        // Pembebanan KEDUA (Sengaja admin mendobel)
        $response = $this->actingAs($admin)->post('/pembayaran', [
            'id_tagihan' => $tagihan->id_tagihan,
            'tipe_pembebanan' => 'siswa',
            'id_siswa' => $siswa->id_siswa
        ]);

        // Cek database harus TETAP 1 (sistem berhasil skip tagihan dobel)
        $this->assertDatabaseCount('pembayaran', 1);
        
        // Cek ada tulisan sukses dengan (1 dilewati)
        $response->assertSessionHas('success', 'Berhasil membebankan tagihan kepada 0 siswa. (1 dilewati).');
    }
}
