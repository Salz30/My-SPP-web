<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tagihan extends Model
{
    use SoftDeletes;

    protected $table = 'tagihan';
    protected $primaryKey = 'id_tagihan';
    protected $fillable = ['nama_tagihan', 'id_kategori', 'tenggat_waktu'];

    // Relasi: 1 Tagihan DIMILIKI oleh 1 Kategori
    public function kategori()
    {
        return $this->belongsTo(KategoriPembayaran::class, 'id_kategori', 'id_kategori')->withTrashed();
    }

    // Relasi: 1 Tagihan memiliki BANYAK Pembayaran dari berbagai siswa
    public function pembayaran()
    {
        return $this->hasMany(Pembayaran::class, 'id_tagihan', 'id_tagihan');
    }
}