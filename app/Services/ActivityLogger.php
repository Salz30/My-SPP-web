<?php

namespace App\Services;

use App\Models\LogAktivitas;
use Illuminate\Support\Facades\Auth;

class ActivityLogger
{
    /**
     * Catat satu baris log aktivitas.
     *
     * @param string $tindakan  Kategori tindakan (Tambah Data, Edit Data, Hapus Data, dst.)
     * @param string $deskripsi Penjelasan detail tindakan yang dilakukan
     */
    public static function log(string $tindakan, string $deskripsi): void
    {
        LogAktivitas::create([
            'id_user'   => Auth::id(),
            'tindakan'  => $tindakan,
            'deskripsi' => $deskripsi,
        ]);
    }
}
