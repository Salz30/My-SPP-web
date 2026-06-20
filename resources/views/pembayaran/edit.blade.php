@extends('layouts.admin')

@section('content')
<div class="row mb-4 align-items-center">
    <div class="col-md-6">
        <h4 class="mb-0">Proses Pembayaran Kasir</h4>
    </div>
    <div class="col-md-6 text-end">
        <a href="{{ route('pembayaran.index') }}" class="btn btn-outline-secondary rounded-pill px-4">Kembali</a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card p-4">
            <div class="bg-light p-3 rounded-4 mb-4 border">
                <h6 class="text-muted mb-1">Informasi Tagihan:</h6>
                <h5 class="fw-bold text-dark">{{ $pembayaran->siswa->nama_siswa }} - {{ $pembayaran->tagihan->nama_tagihan }}</h5>
                <h4 class="text-success mb-0 fw-bold">Rp {{ number_format($pembayaran->tagihan->kategori->nominal_default ?? 0, 0, ',', '.') }}</h4>
            </div>

            <form action="{{ route('pembayaran.update', $pembayaran->id_pembayaran) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="mb-3">
                    <label class="form-label fw-bold text-dark">Status Pembayaran <span class="text-danger">*</span></label>
                    <select class="form-select form-select-lg bg-light border-0" name="status_bayar" id="status_bayar" required onchange="toggleTanggal(this)">
                        <option value="Belum Lunas" {{ $pembayaran->status_bayar == 'Belum Lunas' ? 'selected' : '' }}>Belum Lunas</option>
                        <option value="Lunas" {{ $pembayaran->status_bayar == 'Lunas' ? 'selected' : '' }}>Lunas</option>
                    </select>
                </div>

                <div class="mb-4" id="div_tanggal" style="display: {{ $pembayaran->status_bayar == 'Lunas' ? 'block' : 'none' }};">
                    <label class="form-label fw-bold text-dark">Tanggal Bayar <span class="text-danger">*</span></label>
                    <input type="date" class="form-control form-control-lg bg-light border-0" name="tanggal_bayar"
                        value="{{ old('tanggal_bayar', $pembayaran->tanggal_bayar ? \Carbon\Carbon::parse($pembayaran->tanggal_bayar)->format('Y-m-d') : now()->format('Y-m-d')) }}">
                    <small class="text-muted mt-1 d-block"><i class="fa-solid fa-info-circle"></i> Isi sesuai tanggal siswa membayar secara fisik (boleh tanggal lampau).</small>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-primary px-5 shadow-sm">Simpan Transaksi</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // JS sederhana agar input tanggal hanya muncul jika status Lunas dipilih
    function toggleTanggal(select) {
        document.getElementById('div_tanggal').style.display = select.value === 'Lunas' ? 'block' : 'none';
    }
</script>
@endsection