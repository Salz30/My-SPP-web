@extends('layouts.admin')

@section('content')
<div class="row mb-4 align-items-center">
    <div class="col-md-6">
        <h4 class="mb-0">Kategori Pembayaran</h4>
        <p class="text-muted mb-0">Kelola jenis-jenis tagihan dan nominal standarnya.</p>
    </div>
    <div class="col-md-6 text-end">
        <a href="{{ route('kategori.create') }}" class="btn btn-primary shadow-sm">
            <i class="fa-solid fa-plus me-1"></i> Tambah Kategori
        </a>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-4 mb-4" role="alert">
    <i class="fa-solid fa-check-circle me-2"></i> {{ session('success') }}
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
                        <th>Nama Kategori</th>
                        <th>Nominal Default</th>
                        <th width="15%" class="text-center rounded-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kategori as $index => $item)
                    <tr>
                        <td class="text-muted">{{ $index + 1 }}</td>
                        <td class="fw-bold text-dark">{{ $item->nama_kategori }}</td>
                        <td class="text-success fw-bold">Rp {{ number_format($item->nominal_default, 0, ',', '.') }}</td>
                        <td class="text-center">
                            <a href="{{ route('kategori.edit', $item->id_kategori) }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">Edit</a>
                            <form action="{{ route('kategori.destroy', $item->id_kategori) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-3" onclick="return confirm('Yakin ingin menghapus kategori ini?')">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-4 text-muted">
                            <i class="fa-solid fa-tags fa-2x mb-2"></i><br>
                            Belum ada data kategori pembayaran.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection