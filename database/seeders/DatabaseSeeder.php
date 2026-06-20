<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Memanggil seeder khusus untuk membuat akun Master Admin
        $this->call([
            AdminSeeder::class,
        ]);
    }
}