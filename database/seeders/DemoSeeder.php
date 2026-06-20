<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\KategoriPembayaran;
use App\Models\Tagihan;
use App\Models\Pembayaran;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat Kelas
        $kelas1 = Kelas::create(['nama_kelas' => 'X PPLG 1', 'wali_kelas' => 'Bpk. Budi Santoso']);
        $kelas2 = Kelas::create(['nama_kelas' => 'XI PPLG 2', 'wali_kelas' => 'Ibu Siti Aminah']);
        $kelas3 = Kelas::create(['nama_kelas' => 'XII PPLG 3', 'wali_kelas' => 'Bpk. Ahmad Dahlan']);

        // 2. Data Siswa Dummy
        $dataSiswa = [
            ['nisn' => '0011223301', 'nama' => 'Agnes Monica', 'kelas_id' => $kelas1->id_kelas],
            ['nisn' => '0011223302', 'nama' => 'Bima Sakti', 'kelas_id' => $kelas1->id_kelas],
            ['nisn' => '0011223303', 'nama' => 'Citra Kirana', 'kelas_id' => $kelas2->id_kelas],
            ['nisn' => '0011223304', 'nama' => 'Dedy Corbuzier', 'kelas_id' => $kelas2->id_kelas],
            ['nisn' => '0011223305', 'nama' => 'Eka Gustiwana', 'kelas_id' => $kelas3->id_kelas],
        ];

        $siswaModels = [];
        foreach ($dataSiswa as $data) {
            // Buat User untuk siswa
            $user = User::create([
                'name' => $data['nama'],
                'username' => $data['nisn'],
                'email' => $data['nisn'] . '@siswa.myspp.com',
                'role' => 'siswa',
                'password' => Hash::make($data['nisn']), // Password default = NISN
            ]);

            // Buat Data Siswa tersambung ke User
            $siswaModels[] = Siswa::create([
                'id_user' => $user->id,
                'id_kelas' => $data['kelas_id'],
                'nisn' => $data['nisn'],
                'nama_siswa' => $data['nama'],
                'no_hp' => '0812345678' . rand(10, 99)
            ]);
        }

        // 3. Buat Kategori Pembayaran
        $katSpp = KategoriPembayaran::create(['nama_kategori' => 'SPP Bulanan', 'nominal_default' => 250000]);
        $katGedung = KategoriPembayaran::create(['nama_kategori' => 'Uang Pembangunan (DSP)', 'nominal_default' => 1500000]);

        // 4. Buat Tagihan
        $tagihanBulanLalu = Tagihan::create([
            'id_kategori' => $katSpp->id_kategori,
            'nama_tagihan' => 'SPP Bulan Mei 2026',
            'tenggat_waktu' => '2026-05-10',
            'created_at' => Carbon::now()->subDays(40)
        ]);
        $tagihanBulanIni = Tagihan::create([
            'id_kategori' => $katSpp->id_kategori,
            'nama_tagihan' => 'SPP Bulan Juni 2026',
            'tenggat_waktu' => '2026-06-10',
            'created_at' => Carbon::now()->subDays(10)
        ]);
        $tagihanGedung = Tagihan::create([
            'id_kategori' => $katGedung->id_kategori,
            'nama_tagihan' => 'Cicilan Gedung Semester 1',
            'tenggat_waktu' => '2026-07-30',
            'created_at' => Carbon::now()->subDays(5)
        ]);

        // 5. Buat Data Pembayaran (Transaksinya)
        foreach ($siswaModels as $index => $siswa) {
            // Semua siswa dibebankan 3 tagihan tersebut
            $p1 = Pembayaran::create([
                'id_siswa' => $siswa->id_siswa,
                'id_tagihan' => $tagihanBulanLalu->id_tagihan,
                'status_bayar' => 'Belum Lunas'
            ]);
            $p2 = Pembayaran::create([
                'id_siswa' => $siswa->id_siswa,
                'id_tagihan' => $tagihanBulanIni->id_tagihan,
                'status_bayar' => 'Belum Lunas'
            ]);
            $p3 = Pembayaran::create([
                'id_siswa' => $siswa->id_siswa,
                'id_tagihan' => $tagihanGedung->id_tagihan,
                'status_bayar' => 'Belum Lunas'
            ]);

            // Simulasi: Beberapa siswa sudah lunas, beberapa belum
            if ($index % 2 == 0) { // Siswa genap lunas tagihan bulan lalu
                $p1->update([
                    'status_bayar' => 'Lunas',
                    'tanggal_bayar' => Carbon::now()->subDays(30)
                ]);
            }
            if ($index == 0) { // Siswa pertama lunas bulan ini juga
                $p2->update([
                    'status_bayar' => 'Lunas',
                    'tanggal_bayar' => Carbon::now()->subDays(2)
                ]);
            }
        }
    }
}
