<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom sticky-top shadow-sm" style="padding: 12px 40px; z-index: 1000; font-family: 'Inter', sans-serif;">
    <div class="container-fluid">
        <a class="navbar-brand d-flex align-items-center gap-2 fw-bold" href="{{ route('dashboard') }}" style="font-size: 22px; color: #1b1d20;">
            <svg viewBox="0 0 24 24" style="width: 28px; height: 28px; fill: #5196fe;"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
            My<span style="font-family: 'Source Serif 4', serif; font-style: italic; color: #5196fe;">SPP</span>
        </a>

        <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#adminNavbar" aria-controls="adminNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="adminNavbar">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-5 gap-1">
                <li class="nav-item">
                    <a class="nav-link px-3 rounded-pill fw-medium {{ request()->routeIs('dashboard') ? 'bg-primary bg-opacity-10 text-primary' : 'text-secondary hover-bg-light' }}" href="{{ route('dashboard') }}">
                        <i class="fa-solid fa-house me-1"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-3 rounded-pill fw-medium {{ request()->routeIs('pembayaran.*') ? 'bg-primary bg-opacity-10 text-primary' : 'text-secondary hover-bg-light' }}" href="{{ route('pembayaran.index') }}">
                        <i class="fa-solid fa-cash-register me-1"></i> Transaksi
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-3 rounded-pill fw-medium {{ request()->routeIs('laporan.*') ? 'bg-primary bg-opacity-10 text-primary' : 'text-secondary hover-bg-light' }}" href="{{ route('laporan.index') }}">
                        <i class="fa-solid fa-chart-pie me-1"></i> Laporan
                    </a>
                </li>
                
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle px-3 rounded-pill fw-medium text-secondary hover-bg-light" href="#" id="masterDataDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa-solid fa-database me-1"></i> Master Data
                    </a>
                    <ul class="dropdown-menu border-0 shadow rounded-4 mt-2 p-2" aria-labelledby="masterDataDropdown">
                        <li><a class="dropdown-item py-2 rounded-3 fw-medium text-secondary" href="{{ route('siswa.index') }}">Data Siswa</a></li>
                        <li><a class="dropdown-item py-2 rounded-3 fw-medium text-secondary" href="{{ route('kelas.index') }}">Data Kelas</a></li>
                        <li><a class="dropdown-item py-2 rounded-3 fw-medium text-secondary" href="{{ route('kategori.index') }}">Kategori SPP</a></li>
                        <li><a class="dropdown-item py-2 rounded-3 fw-medium text-secondary" href="{{ route('tagihan.index') }}">Data Tagihan</a></li>
                    </ul>
                </li>
            </ul>

            <div class="d-flex align-items-center gap-3 mt-3 mt-lg-0 border-start ps-lg-4">
                <div class="dropdown">
                    <button class="btn btn-light bg-transparent border-0 d-flex align-items-center gap-2 fw-medium px-2 py-1" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false" style="color: #1b1d20;">
                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 36px; height: 36px; font-size: 14px;">
                            {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                        </div>
                        <div class="text-start d-none d-md-block">
                            <div class="lh-1">{{ Auth::user()->name }}</div>
                            <small class="text-muted" style="font-size: 11px;">Administrator</small>
                        </div>
                        <i class="fa-solid fa-chevron-down text-muted ms-2" style="font-size: 12px;"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end border-0 shadow rounded-4 mt-2 p-2" aria-labelledby="userDropdown">
                        <li>
                            <a class="dropdown-item py-2 rounded-3 d-flex align-items-center gap-2 fw-medium text-secondary" href="{{ route('profile.edit') }}">
                                <i class="fa-regular fa-id-card"></i> Profil Saya
                            </a>
                        </li>
                        <li><hr class="dropdown-divider my-1"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}" class="m-0">
                                @csrf
                                <button type="submit" class="dropdown-item py-2 rounded-3 d-flex align-items-center gap-2 fw-medium text-danger">
                                    <i class="fa-solid fa-arrow-right-from-bracket"></i> Keluar Sistem
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</nav>

<style>
    /* Sedikit sentuhan interaktif untuk link navigasi */
    .hover-bg-light:hover {
        background-color: #f8f9fa;
        color: #1b1d20 !important;
    }
    .dropdown-item:hover {
        background-color: #f2f1ec;
        color: #5196fe !important;
    }
</style>