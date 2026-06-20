<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Kelas;
use App\Models\Siswa;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SiswaTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_bisa_melihat_daftar_siswa(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        // Akses halaman data siswa
        $response = $this->actingAs($admin)->get('/siswa');

        $response->assertStatus(200);
    }

    public function test_admin_bisa_menambah_siswa_baru(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $kelas = Kelas::create(['nama_kelas' => 'XII RPL 1']);

        $response = $this->actingAs($admin)->post('/siswa', [
            'nisn' => '1234567890',
            'nama_siswa' => 'Budi Santoso',
            'id_kelas' => $kelas->id_kelas,
            'alamat' => 'Jl. Merdeka No 1',
            'no_hp' => '08123456789',
        ]);

        // Cek apakah data siswa berhasil masuk ke database
        $this->assertDatabaseHas('siswa', [
            'nisn' => '1234567890',
            'nama_siswa' => 'Budi Santoso'
        ]);

        // Cek apakah otomatis dibuatkan user akun untuk siswa tersebut
        $this->assertDatabaseHas('users', [
            'username' => '1234567890',
            'role' => 'siswa'
        ]);
        
        $response->assertRedirect('/siswa');
    }

    public function test_validasi_error_saat_admin_kosongkan_nama_siswa(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        
        $response = $this->actingAs($admin)->post('/siswa', [
            'nisn' => '1234567890',
            'nama_siswa' => '', // Sengaja dikosongkan untuk tes validasi
        ]);

        // Berharap Laravel mengembalikan error validasi pada kolom nama_siswa
        $response->assertSessionHasErrors(['nama_siswa']);
    }
}
