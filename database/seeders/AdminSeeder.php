<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Cek agar tidak terjadi duplikasi jika seeder dijalankan 2 kali
        $adminExist = User::query()->where('username', 'admin_utama')->exists();

        if (!$adminExist) {
            User::create([
                'name' => 'Administrator Utama',
                'username' => 'admin_utama', // Username untuk login
                'email' => 'admin@myspp.test',
                'role' => 'admin',           // Kunci masuk ke ruangan Admin
                'password' => Hash::make('AdminRahasia123!'), // Password default
            ]);
            
            $this->command->info('Akun Master Admin berhasil dibuat!');
        } else {
            $this->command->info('Akun Master Admin sudah ada, diabaikan.');
        }
    }
}