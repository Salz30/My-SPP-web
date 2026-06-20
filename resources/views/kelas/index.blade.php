@extends('layouts.admin')

@section('content')
<div class="row mb-4 align-items-center">
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-4 mb-4" role="alert">
    <i class="fa-solid fa-check-circle me-2"></i> {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif
    <div class="col-md-6">
        <h4 class="mb-0">Data Kelas</h4>
        <p class="text-muted mb-0">Kelola informasi kelas dan wali kelas.</p>
    </div>
    <div class="col-md-6 text-end">
        <a href="{{ route('kelas.create') }}" class="btn btn-primary shadow-sm">
            <i class="fa-solid fa-plus me-1"></i> Tambah Kelas
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th width="5%" class="rounded-start">No</th>
                        <th>Nama Kelas</th>
                        <th>Wali Kelas</th>
                        <th width="15%" class="text-center rounded-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kelas as $index => $item)
                    <tr>
                        <td class="text-muted">{{ $index + 1 }}</td>
                        <td class="fw-bold text-dark">{{ $item->nama_kelas }}</td>
                        <td>{{ $item->wali_kelas ?? '-' }}</td>
                        <td class="text-center">
                            <a href="{{ route('kelas.edit', $item->id_kelas) }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">Edit</a>
                            <form action="{{ route('kelas.destroy', $item->id_kelas) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-3" onclick="return confirm('Yakin ingin menghapus kelas ini?')">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-4 text-muted">
                            <i class="fa-solid fa-folder-open fa-2x mb-2"></i><br>
                            Belum ada data kelas.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection