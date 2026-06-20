@extends('layouts.admin')

@section('content')
<div class="row mb-4 align-items-center">
    <div class="col-md-6">
        <h4 class="mb-0">Edit Tagihan</h4>
    </div>
    <div class="col-md-6 text-end">
        <a href="{{ route('tagihan.index') }}" class="btn btn-outline-secondary rounded-pill px-4">Kembali</a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card p-4">
            <form action="{{ route('tagihan.update', $tagihan->id_tagihan) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="mb-3">
                    <label class="form-label fw-bold text-dark">Nama Tagihan <span class="text-danger">*</span></label>
                    <input type="text" class="form-control form-control-lg bg-light border-0 @error('nama_tagihan') is-invalid @enderror" name="nama_tagihan" value="{{ old('nama_tagihan', $tagihan->nama_tagihan) }}" required>
                    @error('nama_tagihan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                
                <div class="mb-3">
                    <label class="form-label fw-bold text-dark">Kategori Pembayaran <span class="text-danger">*</span></label>
                    <select class="form-select form-select-lg bg-light border-0 @error('id_kategori') is-invalid @enderror" name="id_kategori" required>
                        @foreach($kategori as $k)
                            <option value="{{ $k->id_kategori }}" {{ old('id_kategori', $tagihan->id_kategori) == $k->id_kategori ? 'selected' : '' }}>
                                {{ $k->nama_kategori }} - Rp {{ number_format($k->nominal_default, 0, ',', '.') }}
                            </option>
                        @endforeach
                    </select>
                    @error('id_kategori') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold text-dark">Tenggat Waktu <span class="text-danger">*</span></label>
                    <input type="date" class="form-control form-control-lg bg-light border-0 @error('tenggat_waktu') is-invalid @enderror" name="tenggat_waktu" value="{{ old('tenggat_waktu', $tagihan->tenggat_waktu) }}" required>
                    @error('tenggat_waktu') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-primary px-5 shadow-sm">Perbarui Tagihan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection