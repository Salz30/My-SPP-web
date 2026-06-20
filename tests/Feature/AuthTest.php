<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Skenario 1: Mengetes Halaman Utama
     */
    public function test_halaman_utama_otomatis_diarahkan_ke_login(): void
    {
        $response = $this->get('/');

        // Berharap mendapatkan status HTTP 302 (Redirect) ke halaman login
        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    /**
     * Skenario 2: Mengetes Keamanan URL Dashboard Admin
     */
    public function test_orang_belum_login_tidak_bisa_buka_dashboard_admin(): void
    {
        $response = $this->get('/dashboard');

        // Berharap ditolak dan diarahkan ke login
        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    /**
     * Skenario 3: Mengetes Fungsionalitas Login Admin
     */
    public function test_admin_bisa_buka_dashboard_admin(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get('/dashboard');

        // Berhasil memuat halaman (Status 200 OK)
        $response->assertStatus(200);
    }

    /**
     * Skenario 4: Mengetes Fungsionalitas Login Siswa
     */
    public function test_siswa_bisa_buka_portal_siswa(): void
    {
        // Buat kelas dulu agar bisa buat siswa
        $kelas = \App\Models\Kelas::create(['nama_kelas' => 'X RPL 1']);
        
        $siswa = User::factory()->create([
            'role' => 'siswa',
            'username' => '12345'
        ]);

        \App\Models\Siswa::create([
            'nisn' => '12345',
            'nama_siswa' => 'Siswa Test',
            'id_kelas' => $kelas->id_kelas
        ]);

        $response = $this->actingAs($siswa)->get('/portal');

        // Berhasil memuat halaman portal (Status 200 OK)
        $response->assertStatus(200);
    }
}