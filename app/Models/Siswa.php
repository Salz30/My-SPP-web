<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Siswa extends Model
{
    use SoftDeletes;

    protected $table = 'siswa';
    protected $primaryKey = 'id_siswa';
    protected $fillable = ['nisn', 'nama_siswa', 'no_hp', 'id_kelas', 'id_user'];

    // Relasi: 1 Siswa terhubung ke 1 User
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    // Relasi: 1 Siswa DIMILIKI oleh 1 Kelas (BelongsTo)
    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'id_kelas', 'id_kelas')->withTrashed();
    }

    // Relasi: 1 Siswa memiliki BANYAK riwayat Pembayaran
    public function pembayaran()
    {
        return $this->hasMany(Pembayaran::class, 'id_siswa', 'id_siswa');
    }
}