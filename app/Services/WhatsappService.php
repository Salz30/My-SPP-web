<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsappService
{
    /**
     * Kirim Notifikasi Pembayaran Berhasil
     */
    public static function sendPaymentReceipt($no_hp, $data_pembayaran)
    {
        if (empty($no_hp)) return false;

        // Contoh format pesan
        $pesan = "Halo, ini dari Admin MySPP.\n";
        $pesan .= "Pembayaran untuk tagihan bulan *" . $data_pembayaran['bulan'] . "* tahun *" . $data_pembayaran['tahun'] . "* ";
        $pesan .= "sebesar *Rp" . number_format($data_pembayaran['nominal'], 0, ',', '.') . "* telah *LUNAS* pada " . $data_pembayaran['tanggal'] . ".\n\n";
        $pesan .= "Terima kasih.";

        return self::send($no_hp, $pesan);
    }

    /**
     * Kirim Notifikasi Tagihan Baru
     */
    public static function sendNewBillNotification($no_hp, $data_tagihan)
    {
        if (empty($no_hp)) return false;

        $pesan = "Halo, ini dari Admin MySPP.\n";
        $pesan .= "Ada tagihan baru untuk bulan *" . $data_tagihan['bulan'] . "* tahun *" . $data_tagihan['tahun'] . "* ";
        $pesan .= "sebesar *Rp" . number_format($data_tagihan['nominal'], 0, ',', '.') . "*.\n\n";
        $pesan .= "Mohon segera dilunasi. Terima kasih.";

        return self::send($no_hp, $pesan);
    }

    /**
     * Base function untuk mengirim pesan ke API Provider (misal: Fonnte/Wablas)
     */
    public static function send($no_hp, $pesan)
    {
        // ==========================================
        // TODO: Sesuaikan dengan Provider Pilihan
        // ==========================================
        
        $provider = env('WA_PROVIDER', 'fonnte'); // default fonnte

        try {
            // Pastikan format nomor hp dimulai dari 08 atau 62 (sesuai provider)
            // if(substr($no_hp, 0, 1) == '0') $no_hp = '62' . substr($no_hp, 1);

            if ($provider === 'fonnte') {
                // Contoh implementasi Fonnte
                $token = env('FONNTE_TOKEN', 'TOKEN_ANDA_DISINI');
                $response = Http::withHeaders([
                    'Authorization' => $token,
                ])->post('https://api.fonnte.com/send', [
                    'target' => $no_hp,
                    'message' => $pesan,
                ]);

                Log::info("WA Service response: " . $response->body());
                return $response->successful();
            }

            // Bisa ditambah provider lain seperti Wablas, dll

            return true;
        } catch (\Exception $e) {
            Log::error("WA Service Error: " . $e->getMessage());
            return false;
        }
    }
}
