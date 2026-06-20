@extends('layouts.admin')

@section('content')
<div class="row mb-4 align-items-center">
    <div class="col-md-6">
        <h4 class="mb-0">Transaksi Pembayaran</h4>
        <p class="text-muted mb-0">Kelola pembebanan tagihan dan pelunasan.</p>
    </div>
    <div class="col-md-6 text-end">
        <a href="{{ route('pembayaran.create') }}" class="btn btn-primary shadow-sm">
            <i class="fa-solid fa-plus me-1"></i> Bebankan Tagihan Baru
        </a>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-4 mb-4" role="alert">
    <i class="fa-solid fa-check-circle me-2"></i> {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-4 mb-4" role="alert">
    <i class="fa-solid fa-circle-exclamation me-2"></i> {{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th width="5%" class="rounded-start">No</th>
                        <th>Siswa</th>
                        <th>Tagihan & Nominal</th>
                        <th>Status</th>
                        <th width="20%" class="text-center rounded-end">Aksi / Bayar</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pembayaran as $index => $item)
                    <tr>
                        <td class="text-muted">{{ $index + 1 }}</td>
                        <td>
                            <span class="fw-bold text-dark d-block">{{ $item->siswa->nama_siswa ?? 'Siswa Dihapus' }}</span>
                            <small class="text-muted font-monospace">{{ $item->siswa->nisn ?? '-' }}</small>
                        </td>
                        <td>
                            <span class="d-block">{{ $item->tagihan->nama_tagihan ?? 'Tagihan Dihapus' }}</span>
                            <small class="text-dark fw-bold">Rp {{ number_format($item->tagihan->kategori->nominal_default ?? 0, 0, ',', '.') }}</small>
                        </td>
                        <td>
                            @if($item->status_bayar == 'Lunas')
                                <span class="badge bg-success bg-opacity-10 text-success border border-success px-3 py-2 rounded-pill">Lunas</span>
                                <small class="d-block text-muted mt-1" style="font-size: 11px;">{{ \Carbon\Carbon::parse($item->tanggal_bayar)->format('d/m/Y') }}</small>
                            @else
                                <span class="badge bg-danger bg-opacity-10 text-danger border border-danger px-3 py-2 rounded-pill">Belum Lunas</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($item->status_bayar == 'Lunas')
                                <a href="{{ route('pembayaran.kwitansi', $item->id_pembayaran) }}" target="_blank" class="btn btn-sm btn-outline-success rounded-pill px-3 mb-1" title="Cetak Kwitansi">
                                    <i class="fa-solid fa-print"></i>
                                </a>
                            @endif

                            <a href="{{ route('pembayaran.edit', $item->id_pembayaran) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3 mb-1">Update</a>
                            <form action="{{ route('pembayaran.destroy', $item->id_pembayaran) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-3 mb-1" onclick="return confirm('Hapus transaksi ini?')">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">
                            <i class="fa-solid fa-wallet fa-2x mb-2"></i><br>
                            Belum ada transaksi pembayaran.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection