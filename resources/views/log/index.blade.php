@extends('layouts.admin')

@section('content')
<div class="row mb-4 align-items-center">
    <div class="col-md-7">
        <h4 class="mb-0"><i class="fa-solid fa-user-secret me-2 text-primary"></i> Audit Trail — Log Aktivitas Sistem</h4>
        <p class="text-muted mb-0">Rekam jejak setiap tindakan yang dilakukan oleh Admin dan Siswa di dalam sistem.</p>
    </div>
    <div class="col-md-5 text-end">
        <span class="badge bg-primary bg-opacity-10 text-primary border border-primary px-3 py-2 rounded-pill fs-6">
            <i class="fa-solid fa-database me-1"></i> Total: {{ $logs->count() }} aktivitas
        </span>
    </div>
</div>

{{-- Filter Cepat --}}
<div class="card mb-4 border-0 shadow-sm rounded-4">
    <div class="card-body py-3">
        <form method="GET" action="{{ route('log.index') }}" class="row g-2 align-items-center">
            <div class="col-md-3">
                <select name="filter_tindakan" class="form-select form-select-sm bg-light border-0" onchange="this.form.submit()">
                    <option value="">— Semua Tindakan —</option>
                    @foreach(['Login','Logout','Tambah Data','Edit Data','Hapus Data','Beban Tagihan','Penerimaan Pembayaran'] as $t)
                        <option value="{{ $t }}" {{ request('filter_tindakan') == $t ? 'selected' : '' }}>{{ $t }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <input type="date" name="filter_tanggal" class="form-control form-control-sm bg-light border-0"
                    value="{{ request('filter_tanggal') }}" onchange="this.form.submit()">
            </div>
            <div class="col-md-4">
                <input type="text" name="filter_user" class="form-control form-control-sm bg-light border-0"
                    placeholder="🔍 Cari nama pengguna..." value="{{ request('filter_user') }}">
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary btn-sm w-100">Filter</button>
                <a href="{{ route('log.index') }}" class="btn btn-outline-secondary btn-sm w-100">Reset</a>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4" style="width:12%">Waktu</th>
                        <th style="width:16%">Pengguna</th>
                        <th style="width:13%">Tindakan</th>
                        <th>Deskripsi Detail</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                    <tr>
                        <td class="ps-4">
                            <span class="d-block fw-medium text-dark" style="font-size:0.85rem">
                                {{ $log->created_at->format('d/m/Y') }}
                            </span>
                            <small class="text-muted">{{ $log->created_at->format('H:i:s') }}</small>
                        </td>
                        <td>
                            @if($log->user)
                                @php
                                    $isAdmin = $log->user->role === 'admin';
                                @endphp
                                <div class="d-flex align-items-center gap-2">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold"
                                        style="width:34px;height:34px;font-size:0.75rem;background:{{ $isAdmin ? '#6366f1' : '#0ea5e9' }};flex-shrink:0">
                                        {{ strtoupper(substr($log->user->name, 0, 2)) }}
                                    </div>
                                    <div>
                                        <span class="d-block fw-medium text-dark" style="font-size:0.88rem">{{ $log->user->name }}</span>
                                        <small class="badge rounded-pill" style="font-size:0.7rem;background:{{ $isAdmin ? '#ede9fe' : '#e0f2fe' }};color:{{ $isAdmin ? '#6366f1' : '#0284c7' }}">
                                            {{ $isAdmin ? 'Admin' : 'Siswa' }}
                                        </small>
                                    </div>
                                </div>
                            @else
                                <span class="text-muted fst-italic">Pengguna dihapus</span>
                            @endif
                        </td>
                        <td>
                            @php
                                $badgeMap = [
                                    'Login'                 => ['bg' => '#dcfce7', 'color' => '#16a34a', 'icon' => 'fa-right-to-bracket'],
                                    'Logout'                => ['bg' => '#f3f4f6', 'color' => '#6b7280', 'icon' => 'fa-right-from-bracket'],
                                    'Tambah Data'           => ['bg' => '#dbeafe', 'color' => '#2563eb', 'icon' => 'fa-plus-circle'],
                                    'Edit Data'             => ['bg' => '#fef9c3', 'color' => '#ca8a04', 'icon' => 'fa-pen-to-square'],
                                    'Hapus Data'            => ['bg' => '#fee2e2', 'color' => '#dc2626', 'icon' => 'fa-trash-can'],
                                    'Beban Tagihan'         => ['bg' => '#ede9fe', 'color' => '#7c3aed', 'icon' => 'fa-file-invoice-dollar'],
                                    'Penerimaan Pembayaran' => ['bg' => '#d1fae5', 'color' => '#059669', 'icon' => 'fa-money-bill-wave'],
                                ];
                                $badge = $badgeMap[$log->tindakan] ?? ['bg' => '#f3f4f6', 'color' => '#374151', 'icon' => 'fa-circle-info'];
                            @endphp
                            <span class="badge px-3 py-2 rounded-pill d-inline-flex align-items-center gap-1"
                                style="background:{{ $badge['bg'] }};color:{{ $badge['color'] }};font-size:0.78rem;font-weight:600">
                                <i class="fa-solid {{ $badge['icon'] }}"></i>
                                {{ $log->tindakan }}
                            </span>
                        </td>
                        <td class="text-dark" style="font-size:0.88rem">
                            {{ $log->deskripsi }}
                            <div class="text-muted" style="font-size:0.78rem">
                                <i class="fa-regular fa-clock me-1"></i>{{ $log->created_at->diffForHumans() }}
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-5 text-muted">
                            <i class="fa-solid fa-folder-open fa-2x mb-2 d-block opacity-30"></i>
                            Belum ada aktivitas yang terekam atau tidak ada hasil yang cocok dengan filter.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection