@extends('layouts.admin')

@section('content')
<div class="row mb-4 align-items-center">
    <div class="col-md-6">
        <h4 class="mb-0">Edit Data Kelas</h4>
        <p class="text-muted mb-0">Perbarui informasi kelas atau wali kelas.</p>
    </div>
    <div class="col-md-6 text-end">
        <a href="{{ route('kelas.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
            <i class="fa-solid fa-arrow-left me-1"></i> Kembali
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body p-4">
                <form action="{{ route('kelas.update', $kelas->id_kelas) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="nama_kelas" class="form-label fw-bold text-dark">Nama Kelas <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-lg bg-light border-0 @error('nama_kelas') is-invalid @enderror" id="nama_kelas" name="nama_kelas" value="{{ old('nama_kelas', $kelas->nama_kelas) }}" required>
                        @error('nama_kelas')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="wali_kelas" class="form-label fw-bold text-dark">Nama Wali Kelas (Opsional)</label>
                        <input type="text" class="form-control form-control-lg bg-light border-0 @error('wali_kelas') is-invalid @enderror" id="wali_kelas" name="wali_kelas" value="{{ old('wali_kelas', $kelas->wali_kelas) }}">
                        @error('wali_kelas')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-primary px-5 shadow-sm">
                            <i class="fa-solid fa-save me-1"></i> Perbarui Kelas
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection