@extends('layouts.admin')

@section('content')
<div class="row mb-4 align-items-center">
    <div class="col-md-6">
        <h4 class="mb-0">Data Siswa</h4>
        <p class="text-muted mb-0">Kelola identitas siswa dan penempatan kelas.</p>
    </div>
    <div class="col-md-6 text-end">
        <button type="button" class="btn btn-danger shadow-sm d-none me-2" id="btn-bulk-delete">
            <i class="fa-solid fa-trash me-1"></i> Hapus Terpilih
        </button>
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
        <div class="row mb-3">
            <div class="col-md-4">
                <div class="input-group shadow-sm">
                    <span class="input-group-text bg-white border-end-0"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                    <input type="text" id="searchSiswa" class="form-control border-start-0 ps-0" placeholder="Cari NISN, Nama, atau Kelas...">
                </div>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th width="3%" class="rounded-start text-center">
                            <input type="checkbox" class="form-check-input" id="check-all">
                        </th>
                        <th width="5%">No</th>
                        <th>NISN</th>
                        <th>Nama Siswa</th>
                        <th>Kelas</th>
                        <th width="15%" class="text-center rounded-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($siswa as $index => $item)
                    <tr>
                        <td class="text-center">
                            <input type="checkbox" class="form-check-input check-item" name="ids[]" value="{{ $item->id_siswa }}">
                        </td>
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
                        <td colspan="6" class="text-center py-4 text-muted">
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

<form id="bulk-delete-form" action="{{ route('siswa.bulk-destroy') }}" method="POST" class="d-none">
    @csrf
    <div id="hidden-inputs"></div>
</form>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const checkAll = document.getElementById('check-all');
    const checkboxes = document.querySelectorAll('.check-item');
    const btnBulk = document.getElementById('btn-bulk-delete');
    const form = document.getElementById('bulk-delete-form');
    const hiddenInputs = document.getElementById('hidden-inputs');

    function toggleBtn() {
        const checkedCount = document.querySelectorAll('.check-item:checked').length;
        if(checkedCount > 0) {
            btnBulk.classList.remove('d-none');
        } else {
            btnBulk.classList.add('d-none');
        }
    }

    if(checkAll) {
        checkAll.addEventListener('change', function() {
            checkboxes.forEach(cb => {
                cb.checked = checkAll.checked;
            });
            toggleBtn();
        });
    }

    checkboxes.forEach(cb => {
        cb.addEventListener('change', function() {
            if (!this.checked) checkAll.checked = false;
            if (document.querySelectorAll('.check-item:checked').length === checkboxes.length && checkboxes.length > 0) checkAll.checked = true;
            toggleBtn();
        });
    });

    if(btnBulk) {
        btnBulk.addEventListener('click', function() {
            if(confirm('Yakin ingin menghapus massal data yang dipilih?')) {
                hiddenInputs.innerHTML = '';
                document.querySelectorAll('.check-item:checked').forEach(cb => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'ids[]';
                    input.value = cb.value;
                    hiddenInputs.appendChild(input);
                });
                form.submit();
            }
        });
    }

    // Fitur Pencarian Tabel Data Siswa
    const searchInput = document.getElementById('searchSiswa');
    if (searchInput) {
        searchInput.addEventListener('keyup', function() {
            const filter = this.value.toLowerCase();
            const rows = document.querySelectorAll('table tbody tr');
            
            rows.forEach(row => {
                // Jangan sembunyikan baris pesan kosong (td colspan)
                if (row.querySelector('td[colspan]')) return;
                
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(filter) ? '' : 'none';
            });
        });
    }
});
</script>
@endpush
@endsection