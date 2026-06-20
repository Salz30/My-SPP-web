<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KategoriPembayaran extends Model
{
    use SoftDeletes;

    protected $table = 'kategori_pembayaran';
    protected $primaryKey = 'id_kategori';
    protected $fillable = ['nama_kategori', 'nominal_default'];

    // Relasi: 1 Kategori memiliki BANYAK Tagihan
    public function tagihan()
    {
        return $this->hasMany(Tagihan::class, 'id_kategori', 'id_kategori');
    }
}