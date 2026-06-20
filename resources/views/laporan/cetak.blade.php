<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Laporan Keuangan</title>
    <style>
        body { font-family: 'Times New Roman', Times, serif; font-size: 14px; color: #000; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #000; padding-bottom: 10px; }
        .header h2 { margin: 0; padding: 0; font-size: 24px; }
        .header p { margin: 5px 0 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        table, th, td { border: 1px solid #000; }
        th, td { padding: 8px 12px; text-align: left; }
        th { background-color: #f2f2f2; text-align: center; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .footer { margin-top: 40px; width: 100%; }
        .footer-ttd { float: right; text-align: center; width: 250px; }
        /* Perintah CSS Khusus Print */
        @media print { 
            @page { margin: 0; } /* Menghilangkan URL dan Tanggal bawaan browser */
            body { padding: 1.5cm; } /* Memberi jarak tepi kertas */
            .no-print { display: none; } 
        }
    </style>
</head>
<body onload="window.print()">

    <div class="no-print" style="margin-bottom: 20px;">
        <button onclick="window.print()" style="padding: 10px 20px; cursor: pointer;">Cetak Sekarang</button>
        <button onclick="window.close()" style="padding: 10px 20px; cursor: pointer;">Tutup</button>
    </div>

    <div class="header">
        <h2>LAPORAN PEMBAYARAN SPP & KEUANGAN</h2>
        <p>Aplikasi My-SPP | Dicetak pada: {{ \Carbon\Carbon::now()->format('d F Y H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th>Tanggal Bayar</th>
                <th>Siswa & Kelas</th>
                <th>Jenis Tagihan</th>
                <th>Status</th>
                <th>Nominal</th>
            </tr>
        </thead>
        <tbody>
            @php $total_nominal = 0; @endphp
            @forelse($laporan as $index => $item)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td class="text-center">{{ $item->tanggal_bayar ? \Carbon\Carbon::parse($item->tanggal_bayar)->format('d/m/Y') : '-' }}</td>
                <td>
                    <strong>{{ $item->siswa->nama_siswa ?? '-' }}</strong><br>
                    <small>Kelas: {{ $item->siswa->kelas->nama_kelas ?? '-' }}</small>
                </td>
                <td>{{ $item->tagihan->nama_tagihan ?? '-' }}</td>
                <td class="text-center">{{ $item->status_bayar }}</td>
                <td class="text-right">
                    @if($item->status_bayar == 'Lunas')
                        @php $total_nominal += ($item->tagihan->kategori->nominal_default ?? 0); @endphp
                        Rp {{ number_format($item->tagihan->kategori->nominal_default ?? 0, 0, ',', '.') }}
                    @else
                        -
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center">Tidak ada data transaksi yang sesuai dengan filter.</td>
            </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <th colspan="5" class="text-right">Total Pemasukan (Lunas):</th>
                <th class="text-right">Rp {{ number_format($total_nominal, 0, ',', '.') }}</th>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        <div class="footer-ttd">
            <p>Mengetahui,<br>Admin Keuangan</p>
            <br><br><br>
            <p><strong>{{ Auth::user()->name }}</strong></p>
        </div>
    </div>

</body>
</html>