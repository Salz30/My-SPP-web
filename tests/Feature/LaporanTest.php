<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\Tagihan;
use App\Models\KategoriPembayaran;
use App\Models\Pembayaran;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LaporanTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_bisa_mencetak_laporan_layar(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        
        $response = $this->actingAs($admin)->post('/laporan/cetak', [
            'status_bayar' => 'Lunas'
        ]);

        $response->assertStatus(200);
        $response->assertViewIs('laporan.cetak');
    }

    public function test_admin_bisa_export_laporan_excel(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        
        $response = $this->actingAs($admin)->post('/laporan/excel', [
            'status_bayar' => 'Lunas'
        ]);

        $response->assertStatus(200);
        // Memastikan tipe file yang didownload adalah CSV (Excel)
        $response->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
    }

    public function test_admin_bisa_export_laporan_pdf(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        
        $response = $this->actingAs($admin)->post('/laporan/pdf', [
            'status_bayar' => 'Lunas'
        ]);

        $response->assertStatus(200);
        // Memastikan tipe file yang didownload adalah PDF
        $response->assertHeader('Content-Type', 'application/pdf');
    }
}
