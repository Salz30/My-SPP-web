# 🎓 Aplikasi Pembayaran My-SPP

**My-SPP** adalah sebuah Sistem Informasi Manajemen Pembayaran Sumbangan Pembinaan Pendidikan (SPP) berbasis web. Aplikasi ini dirancang untuk mendigitalisasi proses pencatatan, pelaporan, dan penagihan iuran SPP di sekolah secara terintegrasi, transparan, dan otomatis.

Proyek ini dibangun sebagai pemenuhan Tugas Besar / Ujian Akhir Semester (UAS) pada mata kuliah Pemrograman Web 2.

---

## ✨ Fitur Utama

Aplikasi ini memiliki 2 hak akses utama: **Admin/Kasir** dan **Siswa**.

### 👨‍💼 Modul Admin & Kasir
- **Dasbor Pintar**: Dilengkapi dengan grafik (Chart.js) interaktif yang menampilkan tren pemasukan sekolah secara *real-time* setiap bulannya.
- **Manajemen Data Induk**: Pengelolaan data Siswa, Kelas, dan Kategori Pembayaran (nominal tagihan).
- **Kasir Cepat & Otomatis**: Fitur pemrosesan pembayaran SPP dengan satu klik.
- **Log Aktivitas Keamanan**: Semua transaksi pembayaran diawasi dan dicatat otomatis (siapa kasirnya dan jam berapa) untuk mencegah kecurangan (*fraud*).
- **Laporan Keuangan**: Fitur filter transaksi berdasarkan status dan tanggal, dilengkapi kemampuan **Cetak Layar**, **Export Excel (.csv)**, dan **Export PDF**.
- **Notifikasi WhatsApp (Fonnte API)**: Mengirimkan pesan resi pembayaran otomatis ke nomor WhatsApp orang tua/wali murid segera setelah tagihan dilunasi oleh Kasir.

### 👨‍🎓 Modul Portal Siswa
- **Dasbor Siswa**: Siswa dapat login secara mandiri untuk melihat sisa tunggakan SPP mereka.
- **Riwayat Pembayaran**: Transparansi data di mana siswa bisa melihat tagihan mana saja yang sudah Lunas dan kapan dibayarkan.
- **Profil & Bantuan**: Fitur untuk mengubah *password* dan memutakhirkan nomor WhatsApp orang tua untuk menerima notifikasi.

---

## 🛠️ Teknologi yang Digunakan

Aplikasi ini dikembangkan menggunakan tumpukan teknologi modern:
- **Framework**: Laravel 11 (PHP 8.2+)
- **Database**: MySQL / MariaDB
- **Frontend**: Blade Templating Engine & CSS Framework
- **Library Tambahan**: 
  - `barryvdh/laravel-dompdf` (Cetak Dokumen PDF)
  - `Chart.js` (Visualisasi Data Grafik)
  - *Fonnte API* (Gateway Notifikasi WhatsApp)
  - *Automated QA Testing* (PHPUnit / Laravel Feature Testing)

---

## 🚀 Panduan Instalasi (Untuk Dosen / Penilai)

Ikuti langkah-langkah berikut untuk menjalankan aplikasi ini di komputer lokal Anda (disarankan menggunakan **Laragon** atau **XAMPP**):

### 1. Persiapan Awal
Pastikan Anda sudah menginstal **PHP (Minimal versi 8.2)**, **Composer**, dan **MySQL**.

Buka terminal/Command Prompt, lalu _clone_ repositori ini:
```bash
git clone https://github.com/USERNAME-ANDA/NAMA-REPO-ANDA.git
cd my-spp
```

### 2. Instalasi Dependensi
Jalankan perintah berikut untuk mengunduh semua pustaka Laravel:
```bash
composer install
```

### 3. Konfigurasi Environment (.env)
Salin file *template* environment bawaan menjadi file utama:
```bash
cp .env.example .env
```
Buka file `.env` yang baru saja dibuat, lalu sesuaikan konfigurasi database Anda:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=my_spp
DB_USERNAME=root
DB_PASSWORD=
```
*(Catatan: Buat database kosong terlebih dahulu di MySQL dengan nama `my_spp`)*

### 4. Generate Key & Migrasi Database
Buat kunci keamanan aplikasi dan struktur database peserta datanya:
```bash
php artisan key:generate
php artisan migrate --seed
```
*(Perintah `--seed` sangat penting agar akun Admin default otomatis terbuat)*

### 5. Menjalankan Aplikasi
Nyalakan *server* lokal Laravel:
```bash
php artisan serve
```
Buka browser Anda dan kunjungi: **http://127.0.0.1:8000**

---

## 🔑 Akun Demo (Testing)

Untuk keperluan pengujian aplikasi, silakan gunakan akun berikut yang telah terisi secara otomatis melalui *Database Seeder*:

**Hak Akses Admin / Kasir:**
- **Email:** `admin@myspp.test`
- **Username:** `admin_utama`
- **Password:** `AdminRahasia123!`

**Hak Akses Siswa:**
- Jika membuat user siswa baru maka harus membuat user nya di halaman admin.
- **Password Default Siswa:** `Sesuai nomer NISN siswa`
- **Contoh:**
- `NISN: 00001`
- `Password: 00001`
- User siswa dapat mengganti passwordnya setelah login sebagai user (masuk menu pengaturan)

---

## 🛡️ Jaminan Kualitas (QA Testing)

Proyek ini telah menerapkan sistem pengujian perangkat lunak terotomasi (*Automated QA Testing*) menggunakan standar industri. Untuk melihat bukti pengujian integritas aplikasi, jalankan:
```bash
php artisan test
```
*Sistem akan mengeksekusi puluhan skenario uji keamanan hak akses, filter laporan, logika pembayaran, dan jaminan keamanan tagihan dalam waktu kurang dari 5 detik.*

---
*Dikembangkan oleh Developer sebagai Tugas Besar Pemograman Web 2.*
