<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class GenerateAkunSiswa extends Command
{
    // Nama perintah yang akan diketik di terminal
    protected $signature = 'spp:generate-akun';
    protected $description = 'Generate akun user secara otomatis untuk semua siswa yang belum memiliki akun';

    public function handle()
    {
        // Ambil semua siswa
        $allSiswa = Siswa::query()->get();
        $count = 0;

        $this->info('Memulai sinkronisasi akun siswa...');

        foreach ($allSiswa as $siswa) {
            // Cek apakah user dengan username (NISN) ini sudah ada di tabel users
            $userExist = User::query()->where('username', $siswa->nisn)->exists();

            if (!$userExist) {
                // Generate password acak 8 karakter yang aman
                $plainPassword = Str::random(8);

                // Buat akun di tabel users
                User::create([
                    'name' => $siswa->nama_siswa,
                    'username' => $siswa->nisn,
                    'email' => $siswa->nisn . '@myspp.internal', // Email buatan untuk memenuhi syarat database
                    'role' => 'siswa',
                    'password' => Hash::make($plainPassword),
                ]);

                // Tampilkan password di terminal agar bisa dicatat/diberikan ke siswa
                $this->line("Siswa: {$siswa->nama_siswa} | NISN: {$siswa->nisn} | Password: {$plainPassword}");
                $count++;
            }
        }

        $this->info("Selesai! Berhasil membuat $count akun siswa baru.");
    }
}