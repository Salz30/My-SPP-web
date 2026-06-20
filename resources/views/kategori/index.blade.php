@extends('layouts.admin')

@section('content')
<div class="row mb-4 align-items-center">
    <div class="col-md-6">
        <h4 class="mb-0">Kategori Pembayaran</h4>
        <p class="text-muted mb-0">Kelola jenis-jenis tagihan dan nominal standarnya.</p>
    </div>
    <div class="col-md-6 text-end">
        <button type="button" class="btn btn-danger shadow-sm d-none me-2" id="btn-bulk-delete">
            <i class="fa-solid fa-trash me-1"></i> Hapus Terpilih
        </button>
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
                        <th width="3%" class="rounded-start text-center">
                            <input type="checkbox" class="form-check-input" id="check-all">
                        </th>
                        <th width="5%">No</th>
                        <th>Nama Kategori</th>
                        <th>Nominal Default</th>
                        <th width="15%" class="text-center rounded-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kategori as $index => $item)
                    <tr>
                        <td class="text-center">
                            <input type="checkbox" class="form-check-input check-item" name="ids[]" value="{{ $item->id_kategori }}">
                        </td>
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
                        <td colspan="5" class="text-center py-4 text-muted">
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

<form id="bulk-delete-form" action="{{ route('kategori.bulk-destroy') }}" method="POST" class="d-none">
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
});
</script>
@endpush
@endsection