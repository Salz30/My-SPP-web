@extends('layouts.admin')

@section('content')
<div class="row mb-4 align-items-center">
    <div class="col-md-6">
        <h4 class="mb-0">Edit Kategori</h4>
    </div>
    <div class="col-md-6 text-end">
        <a href="{{ route('kategori.index') }}" class="btn btn-outline-secondary rounded-pill px-4">Kembali</a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card p-4">
            <form action="{{ route('kategori.update', $kategori->id_kategori) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="mb-3">
                    <label class="form-label fw-bold text-dark">Nama Kategori <span class="text-danger">*</span></label>
                    <input type="text" class="form-control form-control-lg bg-light border-0 @error('nama_kategori') is-invalid @enderror" name="nama_kategori" value="{{ old('nama_kategori', $kategori->nama_kategori) }}" required>
                    @error('nama_kategori') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                
                <div class="mb-4">
                    <label class="form-label fw-bold text-dark">Nominal Default (Rp) <span class="text-danger">*</span></label>
                    <input type="text" class="form-control form-control-lg bg-light border-0 @error('nominal_default') is-invalid @enderror" name="nominal_default" value="{{ old('nominal_default', number_format($kategori->nominal_default, 0, '', '.')) }}" required oninput="formatAngka(this)">
                    @error('nominal_default') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-primary px-5 shadow-sm">Perbarui Kategori</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function formatAngka(input) {
        let angka = input.value.replace(/[^0-9]/g, '');
        
        if(angka) {
            input.value = parseInt(angka, 10).toLocaleString('id-ID');
        } else {
            input.value = '';
        }
    }
</script>
@endsection