<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kwitansi Pembayaran - {{ $pembayaran->siswa->nama_siswa }}</title>
    <style>
        body { font-family: 'Courier New', Courier, monospace; color: #333; margin: 0; padding: 20px; font-size: 14px; }
        .receipt-container { max-width: 600px; margin: 0 auto; border: 2px dashed #ccc; padding: 30px; border-radius: 10px; background: #fff; }
        .header { text-align: center; border-bottom: 2px solid #333; padding-bottom: 15px; margin-bottom: 20px; }
        .header h2 { margin: 0; font-size: 22px; text-transform: uppercase; }
        .header p { margin: 5px 0 0; font-size: 14px; color: #555; }
        .info-table { width: 100%; margin-bottom: 20px; }
        .info-table td { padding: 5px 0; vertical-align: top; }
        .info-table td:first-child { width: 30%; font-weight: bold; }
        .amount-box { background-color: #f8f9fa; border: 1px solid #ddd; padding: 15px; text-align: center; border-radius: 8px; margin-bottom: 20px; }
        .amount-box h3 { margin: 0; font-size: 24px; color: #198754; }
        .footer { display: flex; justify-content: space-between; margin-top: 40px; }
        .signature { text-align: center; width: 200px; }
        .signature p { margin-bottom: 60px; }
        .watermark { text-align: center; font-size: 24px; font-weight: bold; color: rgba(25, 135, 84, 0.1); text-transform: uppercase; letter-spacing: 5px; margin-top: -150px; z-index: -1; position: relative;}
        
        /* CSS Khusus Print */
        @media print { 
            @page { margin: 0; } /* Menghilangkan URL dan Tanggal bawaan browser */
            body { background-color: white; padding: 2cm; } /* Memberi jarak aman pada kertas */
            .receipt-container { border: none; padding: 0; }
            .no-print { display: none; } 
        }
    </style>
</head>
<body onload="window.print()">

    <div class="no-print" style="text-align: center; margin-bottom: 20px;">
        <button onclick="window.print()" style="padding: 10px 20px; cursor: pointer; background: #0d6efd; color: white; border: none; border-radius: 5px; font-weight: bold;">Cetak Bukti Transaksi</button>
        <button onclick="window.close()" style="padding: 10px 20px; cursor: pointer; background: #6c757d; color: white; border: none; border-radius: 5px; font-weight: bold;">Tutup</button>
    </div>

    <div class="receipt-container">
        <div class="header">
            <h2>BUKTI PEMBAYARAN SAH</h2>
            <p>Aplikasi Keuangan My-SPP</p>
        </div>

        <table class="info-table">
            <tr>
                <td>No. Transaksi</td>
                <td>: #TRX-{{ str_pad($pembayaran->id_pembayaran, 5, '0', STR_PAD_LEFT) }}</td>
            </tr>
            <tr>
                <td>Tanggal Bayar</td>
                <td>: {{ \Carbon\Carbon::parse($pembayaran->tanggal_bayar)->format('d F Y') }}</td>
            </tr>
            <tr>
                <td>Nama Siswa</td>
                <td>: <strong>{{ $pembayaran->siswa->nama_siswa }}</strong></td>
            </tr>
            <tr>
                <td>NISN / Kelas</td>
                <td>: {{ $pembayaran->siswa->nisn }} / {{ $pembayaran->siswa->kelas->nama_kelas ?? '-' }}</td>
            </tr>
            <tr>
                <td>Untuk Pembayaran</td>
                <td>: {{ $pembayaran->tagihan->nama_tagihan }}</td>
            </tr>
        </table>

        <div class="amount-box">
            <span style="display: block; font-size: 12px; color: #666; margin-bottom: 5px;">Total Nominal Pembayaran</span>
            <h3>Rp {{ number_format($pembayaran->tagihan->kategori->nominal_default ?? 0, 0, ',', '.') }}</h3>
        </div>

        <div class="watermark">L U N A S</div>

        <div class="footer">
            <div class="signature">
                <p>Penyetor / Siswa</p>
                <p><strong>{{ $pembayaran->siswa->nama_siswa }}</strong></p>
                <p>___________________</p>
            </div>
            <div class="signature">
                <p>Penerima / Bendahara</p>
                <p><strong>Sistem Tata Usaha</strong></p>
                <p>___________________</p>
            </div>
        </div>
    </div>

</body>
</html>
