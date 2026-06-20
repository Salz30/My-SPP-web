<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pembayaran extends Model
{
    use SoftDeletes;

    protected $table = 'pembayaran';
    protected $primaryKey = 'id_pembayaran';
    protected $fillable = ['id_siswa', 'id_tagihan', 'status_bayar', 'tanggal_bayar'];

    // Relasi: 1 Pembayaran dilakukan oleh 1 Siswa
    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'id_siswa', 'id_siswa')->withTrashed();
    }

    // Relasi: 1 Pembayaran ditujukan untuk 1 Tagihan
    public function tagihan()
    {
        return $this->belongsTo(Tagihan::class, 'id_tagihan', 'id_tagihan')->withTrashed();
    }
}