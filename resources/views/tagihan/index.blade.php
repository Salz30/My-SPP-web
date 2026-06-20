@extends('layouts.admin')

@section('content')
<div class="row mb-4 align-items-center">
    <div class="col-md-6">
        <h4 class="mb-0">Data Tagihan</h4>
        <p class="text-muted mb-0">Kelola periode tagihan dan tenggat waktunya.</p>
    </div>
    <div class="col-md-6 text-end">
        <a href="{{ route('tagihan.create') }}" class="btn btn-primary shadow-sm">
            <i class="fa-solid fa-plus me-1"></i> Buat Tagihan
        </a>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-4 mb-4" role="alert">
    <i class="fa-solid fa-check-circle me-2"></i> {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

{{-- Info callout cara kerja alur --}}
<div class="alert border-0 rounded-4 mb-4 d-flex align-items-start gap-3"
    style="background: linear-gradient(135deg, #ede9fe, #dbeafe); color: #1e1b4b;">
    <i class="fa-solid fa-circle-info fa-lg mt-1" style="color: #6366f1;"></i>
    <div>
        <strong>Cara Membebankan Tagihan ke Siswa:</strong>
        Setelah tagihan dibuat, klik tombol <span class="badge rounded-pill" style="background:#6366f1;color:#fff"><i class="fa-solid fa-bolt me-1"></i> Bebankan ke Siswa</span>
        pada baris tagihan yang ingin Anda tetapkan. Sistem akan langsung membuka halaman pembebanan dengan tagihan tersebut sudah terpilih otomatis.
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th width="5%" class="rounded-start">No</th>
                        <th>Nama Tagihan</th>
                        <th>Kategori & Nominal</th>
                        <th>Tenggat Waktu</th>
                        <th width="28%" class="text-center rounded-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tagihan as $index => $item)
                    <tr>
                        <td class="text-muted">{{ $index + 1 }}</td>
                        <td class="fw-bold text-dark">{{ $item->nama_tagihan }}</td>
                        <td>
                            <span class="d-block">{{ $item->kategori->nama_kategori ?? 'Kategori Dihapus' }}</span>
                            <small class="text-success fw-bold">Rp {{ number_format($item->kategori->nominal_default ?? 0, 0, ',', '.') }}</small>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border px-3 py-2 rounded-pill">
                                {{ \Carbon\Carbon::parse($item->tenggat_waktu)->format('d M Y') }}
                            </span>
                        </td>
                        <td class="text-center">
                            {{-- Tombol utama: Bebankan ke Siswa --}}
                            <a href="{{ route('pembayaran.create', ['id_tagihan' => $item->id_tagihan]) }}"
                                class="btn btn-sm btn-primary rounded-pill px-3 me-1"
                                title="Bebankan tagihan ini ke siswa">
                                <i class="fa-solid fa-bolt me-1"></i> Bebankan
                            </a>
                            <a href="{{ route('tagihan.edit', $item->id_tagihan) }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">Edit</a>
                            <form action="{{ route('tagihan.destroy', $item->id_tagihan) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-3" onclick="return confirm('Yakin ingin menghapus tagihan ini?')">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">
                            <i class="fa-solid fa-file-invoice fa-2x mb-2 d-block opacity-30"></i>
                            Belum ada data tagihan. Klik <strong>+ Buat Tagihan</strong> untuk memulai.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection