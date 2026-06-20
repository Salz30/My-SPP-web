@extends('layouts.admin')

@section('content')
<div class="row mb-4 align-items-center">
    <div class="col-md-6">
        <h4 class="mb-0">Edit Data Siswa</h4>
    </div>
    <div class="col-md-6 text-end">
        <a href="{{ route('siswa.index') }}" class="btn btn-outline-secondary rounded-pill px-4">Kembali</a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card p-4">
            <form action="{{ route('siswa.update', $siswa->id_siswa) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="mb-3">
                    <label class="form-label fw-bold text-dark">NISN <span class="text-danger">*</span></label>
                    <input type="number" class="form-control form-control-lg bg-light border-0 @error('nisn') is-invalid @enderror" name="nisn" value="{{ old('nisn', $siswa->nisn) }}" required>
                    @error('nisn') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                
                <div class="mb-3">
                    <label class="form-label fw-bold text-dark">Nama Lengkap Siswa <span class="text-danger">*</span></label>
                    <input type="text" class="form-control form-control-lg bg-light border-0 @error('nama_siswa') is-invalid @enderror" name="nama_siswa" value="{{ old('nama_siswa', $siswa->nama_siswa) }}" required>
                    @error('nama_siswa') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold text-dark">No. HP Orang Tua / Wali</label>
                    <input type="text" class="form-control form-control-lg bg-light border-0 @error('no_hp') is-invalid @enderror" name="no_hp" value="{{ old('no_hp', $siswa->no_hp) }}" placeholder="Contoh: 081234567890">
                    @error('no_hp') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold text-dark">Pilih Kelas <span class="text-danger">*</span></label>
                    <select class="form-select form-select-lg bg-light border-0 @error('id_kelas') is-invalid @enderror" name="id_kelas" required>
                        @foreach($kelas as $k)
                            <option value="{{ $k->id_kelas }}" {{ old('id_kelas', $siswa->id_kelas) == $k->id_kelas ? 'selected' : '' }}>
                                {{ $k->nama_kelas }}
                            </option>
                        @endforeach
                    </select>
                    @error('id_kelas') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-primary px-5 shadow-sm">Perbarui Data</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection