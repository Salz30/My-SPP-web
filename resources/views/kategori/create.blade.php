@extends('layouts.admin')

@section('content')
<div class="row mb-4 align-items-center">
    <div class="col-md-6">
        <h4 class="mb-0">Tambah Kategori</h4>
    </div>
    <div class="col-md-6 text-end">
        <a href="{{ route('kategori.index') }}" class="btn btn-outline-secondary rounded-pill px-4">Kembali</a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card p-4">
            <form action="{{ route('kategori.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-bold text-dark">Nama Kategori <span class="text-danger">*</span></label>
                    <input type="text" class="form-control form-control-lg bg-light border-0 @error('nama_kategori') is-invalid @enderror" name="nama_kategori" value="{{ old('nama_kategori') }}" required placeholder="Contoh: SPP Bulanan">
                    @error('nama_kategori') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                
                <div class="mb-4">
                    <label class="form-label fw-bold text-dark">Nominal Default (Rp) <span class="text-danger">*</span></label>
                    <input type="text" class="form-control form-control-lg bg-light border-0 @error('nominal_default') is-invalid @enderror" name="nominal_default" value="{{ old('nominal_default') }}" required placeholder="Contoh: 150.000" oninput="formatAngka(this)">
                    @error('nominal_default') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-primary px-5 shadow-sm">Simpan Kategori</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function formatAngka(input) {
        // Hapus semua karakter selain angka (0-9)
        let angka = input.value.replace(/[^0-9]/g, '');
        
        if(angka) {
            // Format angka dengan pemisah ribuan (titik) standar Indonesia
            input.value = parseInt(angka, 10).toLocaleString('id-ID');
        } else {
            input.value = '';
        }
    }
</script>
@endsection