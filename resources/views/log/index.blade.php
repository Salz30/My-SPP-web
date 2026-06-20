@extends('layouts.admin') @section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold"><i class="fa-solid fa-user-secret me-2"></i> Audit Trail (Log Aktivitas)</h2>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Waktu</th>
                            <th>Admin / Kasir</th>
                            <th>Tindakan</th>
                            <th>Deskripsi Detail</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $log)
                        <tr>
                            <td class="ps-4">
                                <span class="badge bg-secondary">
                                    {{ $log->created_at->format('d/m/Y H:i:s') }}
                                </span>
                            </td>
                            <td class="fw-medium text-primary">
                                <i class="fa-solid fa-user-tie me-1"></i> {{ $log->user->name ?? 'Sistem' }}
                            </td>
                            <td><span class="badge bg-success bg-opacity-10 text-success border border-success">{{ $log->tindakan }}</span></td>
                            <td class="text-muted">{{ $log->deskripsi }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted">Belum ada aktivitas yang terekam.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection