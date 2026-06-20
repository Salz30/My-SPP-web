@extends('layouts.admin')

@section('content')
<div class="row mb-4 align-items-center">
    <div class="col-md-6">
        <h4 class="mb-0">Data Siswa</h4>
        <p class="text-muted mb-0">Kelola identitas siswa dan penempatan kelas.</p>
    </div>
    <div class="col-md-6 text-end">
        <a href="{{ route('siswa.create') }}" class="btn btn-primary shadow-sm">
            <i class="fa-solid fa-plus me-1"></i> Tambah Siswa
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
                        <th>NISN</th>
                        <th>Nama Siswa</th>
                        <th>Kelas</th>
                        <th width="15%" class="text-center rounded-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($siswa as $index => $item)
                    <tr>
                        <td class="text-muted">{{ $index + 1 }}</td>
                        <td class="font-monospace text-muted">{{ $item->nisn }}</td>
                        <td class="fw-bold text-dark">{{ $item->nama_siswa }}</td>
                        <td>
                            <span class="badge bg-light text-dark border px-3 py-2 rounded-pill">
                                {{ $item->kelas->nama_kelas ?? 'Tanpa Kelas' }}
                            </span>
                        </td>
                        <td class="text-center">
                            <a href="{{ route('siswa.edit', $item->id_siswa) }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">Edit</a>
                            <form action="{{ route('siswa.destroy', $item->id_siswa) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-3" onclick="return confirm('Yakin ingin menghapus data siswa ini?')">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">
                            <i class="fa-solid fa-users-slash fa-2x mb-2"></i><br>
                            Belum ada data siswa.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection