<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Keuangan</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        .header { text-align: center; border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 20px; }
        .header h1 { margin: 0; font-size: 24px; }
        .header p { margin: 5px 0 0; font-size: 14px; color: #555; }
        .filter-info { margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        table th, table td { border: 1px solid #000; padding: 8px; text-align: left; }
        table th { background-color: #f2f2f2; font-weight: bold; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
    </style>
</head>
<body>

    <div class="header">
        <h1>MySPP - Sistem Pembayaran Sekolah</h1>
        <p>Laporan Keuangan dan Rekapitulasi Tagihan Siswa</p>
    </div>

    <div class="filter-info">
        <strong>Periode:</strong> {{ $tanggal_awal ? \Carbon\Carbon::parse($tanggal_awal)->format('d M Y') : 'Awal' }} 
        s.d. 
        {{ $tanggal_akhir ? \Carbon\Carbon::parse($tanggal_akhir)->format('d M Y') : 'Akhir' }} <br>
        <strong>Status:</strong> {{ $status_bayar ?? 'Semua Status' }}
    </div>

    <table>
        <thead>
            <tr>
                <th class="text-center" width="5%">No</th>
                <th>Nama Siswa</th>
                <th>NISN</th>
                <th>Kelas</th>
                <th>Tagihan</th>
                <th class="text-center">Tanggal Bayar</th>
                <th class="text-right">Nominal (Rp)</th>
                <th class="text-center">Status</th>
            </tr>
        </thead>
        <tbody>
            @php $total_nominal = 0; @endphp
            @forelse ($pembayaran as $index => $item)
                @php 
                    $nominal = $item->tagihan->kategori->nominal_default ?? 0;
                    if($item->status_bayar == 'Lunas') {
                        $total_nominal += $nominal;
                    }
                @endphp
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $item->siswa->nama_siswa ?? '-' }}</td>
                    <td>{{ $item->siswa->nisn ?? '-' }}</td>
                    <td>{{ $item->siswa->kelas->nama_kelas ?? '-' }}</td>
                    <td>{{ $item->tagihan->nama_tagihan ?? '-' }}</td>
                    <td class="text-center">
                        {{ $item->tanggal_bayar ? \Carbon\Carbon::parse($item->tanggal_bayar)->format('d/m/Y') : '-' }}
                    </td>
                    <td class="text-right">{{ number_format($nominal, 0, ',', '.') }}</td>
                    <td class="text-center">
                        @if ($item->status_bayar == 'Lunas')
                            <span style="color: green; font-weight: bold;">Lunas</span>
                        @else
                            <span style="color: red; font-weight: bold;">Belum Lunas</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center">Tidak ada data transaksi yang ditemukan.</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <th colspan="6" class="text-right">TOTAL PEMASUKAN (LUNAS)</th>
                <th class="text-right" colspan="2">Rp {{ number_format($total_nominal, 0, ',', '.') }}</th>
            </tr>
        </tfoot>
    </table>

    <div style="text-align: right; margin-top: 50px;">
        <p>Dicetak pada: {{ date('d M Y H:i') }}</p>
        <br><br><br>
        <p>_______________________</p>
        <p>Admin / Bendahara</p>
    </div>

</body>
</html>
