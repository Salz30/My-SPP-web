@extends('layouts.admin')

@section('content')
<div class="container py-4" style="max-width: 800px;">
    
    <!-- Header Halaman Profil -->
    <div class="d-flex align-items-center gap-3 mb-4">
        <div class="bg-primary bg-opacity-10 text-primary p-3 rounded-4">
            <i class="fa-solid fa-user-gear fa-xl"></i>
        </div>
        <div>
            <h2 class="fw-bold mb-0">Pengaturan Akun</h2>
            <p class="text-muted mb-0">Kelola informasi profil dan keamanan akun sistem Anda.</p>
        </div>
    </div>

    <!-- 1. Form Informasi Profil -->
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4">
            <h5 class="fw-bold mb-1">Informasi Profil</h5>
            <p class="text-muted small mb-4">Perbarui nama tampilan dan alamat email akun Anda.</p>

            <form method="post" action="{{ route('profile.update') }}">
                @csrf
                @method('patch')

                <div class="mb-3">
                    <label class="form-label fw-medium">Nama Lengkap</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name">
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label fw-medium">Alamat Email</label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required autocomplete="username">
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex align-items-center gap-3 border-top pt-3">
                    <button type="submit" class="btn btn-primary px-4 rounded-pill fw-medium">Simpan Perubahan</button>
                    
                    @if (session('status') === 'profile-updated')
                        <span class="text-success small fw-medium"><i class="fa-solid fa-check-circle"></i> Berhasil disimpan.</span>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- 2. Form Ubah Kata Sandi -->
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4">
            <h5 class="fw-bold mb-1">Ubah Kata Sandi</h5>
            <p class="text-muted small mb-4">Pastikan akun Anda menggunakan kata sandi acak yang panjang agar tetap aman.</p>

            <form method="post" action="{{ route('password.update') }}">
                @csrf
                @method('put')

                <div class="mb-3">
                    <label class="form-label fw-medium">Kata Sandi Saat Ini</label>
                    <input type="password" name="current_password" class="form-control @error('current_password', 'updatePassword') is-invalid @enderror" autocomplete="current-password">
                    @error('current_password', 'updatePassword')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-medium">Kata Sandi Baru</label>
                    <input type="password" name="password" class="form-control @error('password', 'updatePassword') is-invalid @enderror" autocomplete="new-password">
                    @error('password', 'updatePassword')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label fw-medium">Konfirmasi Kata Sandi</label>
                    <input type="password" name="password_confirmation" class="form-control @error('password_confirmation', 'updatePassword') is-invalid @enderror" autocomplete="new-password">
                    @error('password_confirmation', 'updatePassword')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex align-items-center gap-3 border-top pt-3">
                    <button type="submit" class="btn btn-dark px-4 rounded-pill fw-medium">Perbarui Sandi</button>
                    
                    @if (session('status') === 'password-updated')
                        <span class="text-success small fw-medium"><i class="fa-solid fa-check-circle"></i> Sandi berhasil diperbarui.</span>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- 3. Hapus Akun (Danger Zone) -->
    <div class="card border-0 shadow-sm rounded-4 border-danger border-opacity-25">
        <div class="card-body p-4">
            <h5 class="fw-bold text-danger mb-1">Hapus Akun</h5>
            <p class="text-muted small mb-4">Setelah akun dihapus, semua sumber daya dan data akan terhapus secara permanen.</p>

            <button type="button" class="btn btn-outline-danger px-4 rounded-pill fw-medium" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
                Hapus Akun Permanen
            </button>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Hapus Akun -->
<div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4">
            <form method="post" action="{{ route('profile.destroy') }}">
                @csrf
                @method('delete')
                
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold text-danger">Apakah Anda yakin?</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body border-0">
                    <p class="text-muted">Masukkan kata sandi Anda untuk mengonfirmasi penghapusan akun ini secara permanen.</p>
                    <div class="mb-2">
                        <input type="password" name="password" class="form-control @error('password', 'userDeletion') is-invalid @enderror" placeholder="Kata Sandi Anda" required>
                        @error('password', 'userDeletion')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4 fw-medium" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger rounded-pill px-4 fw-medium">Hapus Akun</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection