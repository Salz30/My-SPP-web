<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KasirTest extends TestCase
{
    use RefreshDatabase;

    public function test_halaman_kasir_cepat_hanya_bisa_diakses_admin(): void
    {
        // 1. Tes Akses Guest (Belum Login)
        $responseGuest = $this->get('/kasir-cepat');
        $responseGuest->assertRedirect('/login');

        // 2. Tes Akses Siswa (Harusnya diarahkan ke portal siswa)
        $siswa = User::factory()->create(['role' => 'siswa']);
        $responseSiswa = $this->actingAs($siswa)->get('/kasir-cepat');
        $responseSiswa->assertRedirect('/portal'); // Diarahkan ke portal

        // 3. Tes Akses Admin (Harusnya Boleh)
        $admin = User::factory()->create(['role' => 'admin']);
        $responseAdmin = $this->actingAs($admin)->get('/kasir-cepat');
        $responseAdmin->assertStatus(200); // 200 OK
    }

    public function test_halaman_laporan_dapat_dibuka_oleh_admin(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        
        $response = $this->actingAs($admin)->get('/laporan');
        
        $response->assertStatus(200);
    }
}
