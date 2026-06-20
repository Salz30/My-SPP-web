<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MySPP - Dashboard Keuangan</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Source+Serif+4:ital,wght@1,600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- SUNTIKAN 1: Select2 CSS & Bootstrap 5 Theme -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
    
    <style>
        :root {
            --sidebar-width: 260px;
            --sidebar-collapsed-width: 78px;
            --color-primary: #5196fe;
            --color-bg: #f2f1ec;
            --color-sidebar-bg: #1b1d20;
            --color-sidebar-text: #a3a3a3;
            --color-text-dark: #1b1d20;
            --radius-custom: 16px;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--color-bg);
            color: var(--color-text-dark);
            overflow-x: hidden;
            margin: 0;
            padding: 0;
        }

        /* =========================================
           SIDEBAR STYLING
           ========================================= */
        #sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            background-color: var(--color-sidebar-bg);
            z-index: 1000;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            flex-direction: column;
        }

        #sidebar.collapsed { width: var(--sidebar-collapsed-width); }

        .brand-area {
            height: 76px;
            display: flex;
            align-items: center;
            padding: 0 24px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            overflow: hidden;
            white-space: nowrap;
        }

        .brand-logo {
            font-weight: 700;
            font-size: 22px;
            color: #ffffff;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .brand-logo svg { width: 28px; height: 28px; fill: var(--color-primary); flex-shrink: 0; }
        .brand-logo span { font-family: 'Source Serif 4', serif; font-style: italic; color: var(--color-primary); }

        .sidebar-menu {
            padding: 24px 12px;
            display: flex;
            flex-direction: column;
            gap: 8px;
            flex-grow: 1;
            overflow-y: auto;
        }

        .menu-link {
            display: flex;
            align-items: center;
            padding: 12px 16px;
            color: var(--color-sidebar-text);
            text-decoration: none;
            border-radius: 12px;
            font-weight: 500;
            font-size: 15px;
            transition: all 0.2s;
            white-space: nowrap;
        }

        .menu-link i.main-icon {
            font-size: 18px;
            width: 24px;
            margin-right: 16px;
            flex-shrink: 0;
            text-align: center;
        }

        .menu-link:hover { color: #ffffff; background-color: rgba(255, 255, 255, 0.05); }
        .menu-link.active { color: #ffffff; background-color: var(--color-primary); box-shadow: 0 4px 12px rgba(81, 150, 254, 0.25); }

        #sidebar.collapsed .menu-text,
        #sidebar.collapsed .brand-name,
        #sidebar.collapsed .dropdown-icon {
            opacity: 0;
            pointer-events: none;
            display: none;
        }

        #sidebar.collapsed .menu-link i.main-icon { margin-right: 0; }
        #sidebar.collapsed .menu-link { justify-content: center; padding: 12px 0; }

        .submenu-link {
            display: block;
            padding: 10px 16px 10px 56px;
            color: var(--color-sidebar-text);
            text-decoration: none;
            font-size: 14px;
            transition: all 0.2s;
        }
        .submenu-link:hover, .submenu-link.active { color: #ffffff; }
        #sidebar.collapsed .submenu-container { display: none !important; }

        /* =========================================
           CONTENT AREA & TOP HEADER
           ========================================= */
        #main-wrapper {
            margin-left: var(--sidebar-width);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        #main-wrapper.expanded { margin-left: var(--sidebar-collapsed-width); }

        .top-navbar {
            height: 76px;
            background-color: #ffffff;
            border-bottom: 1px solid #e1dfd8;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 32px;
            position: sticky;
            top: 0;
            z-index: 999;
        }

        .toggle-btn {
            background: none;
            border: none;
            color: var(--color-text-dark);
            font-size: 20px;
            cursor: pointer;
            padding: 8px;
            border-radius: 8px;
            transition: background 0.2s;
        }
        .toggle-btn:hover { background-color: #f2f1ec; }

        .content-body { padding: 32px; flex-grow: 1; }

        @media (max-width: 768px) {
            #sidebar { left: calc(var(--sidebar-width) * -1); }
            #sidebar.mobile-open { left: 0; width: var(--sidebar-width); }
            #sidebar.mobile-open .menu-text, #sidebar.mobile-open .brand-name { display: inline-block !important; opacity: 1 !important; }
            #sidebar.mobile-open .menu-link i.main-icon { margin-right: 16px !important; }
            #sidebar.mobile-open .menu-link { justify-content: flex-start !important; padding: 12px 16px !important; }
            #main-wrapper { margin-left: 0 !important; }
        }
    </style>
</head>
<body>

    <aside id="sidebar">
        <div class="brand-area">
            <a href="{{ route('dashboard') }}" class="brand-logo">
                <svg viewBox="0 0 24 24"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
                <span class="brand-name">My<span>SPP</span></span>
            </a>
        </div>

        <nav class="sidebar-menu">
            <a href="{{ route('dashboard') }}" class="menu-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="fa-solid fa-house main-icon"></i>
                <span class="menu-text">Dashboard</span>
            </a>
            
            <a href="{{ route('pembayaran.index') }}" class="menu-link {{ request()->routeIs('pembayaran.*') && !request()->routeIs('pembayaran.kasir-cepat') ? 'active' : '' }}">
                <i class="fa-solid fa-money-bill-transfer main-icon"></i>
                <span class="menu-text">Transaksi SPP</span>
            </a>

            <a href="{{ route('pembayaran.kasir-cepat') }}" class="menu-link {{ request()->routeIs('pembayaran.kasir-cepat') ? 'active' : '' }}">
                <i class="fa-solid fa-bolt main-icon"></i>
                <span class="menu-text">Kasir Cepat</span>
            </a>

            <a href="{{ route('laporan.index') }}" class="menu-link {{ request()->routeIs('laporan.*') ? 'active' : '' }}">
                <i class="fa-solid fa-chart-pie main-icon"></i>
                <span class="menu-text">Laporan Keuangan</span>
            </a>

            <a data-bs-toggle="collapse" href="#masterDataMenu" role="button" aria-expanded="{{ request()->routeIs('siswa.*', 'kelas.*', 'kategori.*', 'tagihan.*') ? 'true' : 'false' }}" class="menu-link mt-2" style="background-color: rgba(255,255,255,0.03);">
                <i class="fa-solid fa-database main-icon"></i>
                <span class="menu-text d-flex justify-content-between align-items-center w-100">
                    Master Data <i class="fa-solid fa-chevron-down dropdown-icon" style="font-size: 12px;"></i>
                </span>
            </a>
            <div class="collapse submenu-container {{ request()->routeIs('siswa.*', 'kelas.*', 'kategori.*', 'tagihan.*') ? 'show' : '' }}" id="masterDataMenu">
                <div class="d-flex flex-column gap-1 pt-1 pb-2">
                    <a href="{{ route('siswa.index') }}" class="submenu-link {{ request()->routeIs('siswa.*') ? 'active fw-bold' : '' }}">Data Siswa</a>
                    <a href="{{ route('kelas.index') }}" class="submenu-link {{ request()->routeIs('kelas.*') ? 'active fw-bold' : '' }}">Data Kelas</a>
                    <a href="{{ route('kategori.index') }}" class="submenu-link {{ request()->routeIs('kategori.*') ? 'active fw-bold' : '' }}">Kategori SPP</a>
                    <a href="{{ route('tagihan.index') }}" class="submenu-link {{ request()->routeIs('tagihan.*') ? 'active fw-bold' : '' }}">Data Tagihan</a>
                </div>
            </div>
            
            <hr style="border-color: rgba(255,255,255,0.1); margin: 12px 0;">

            <a href="{{ route('profile.edit') }}" class="menu-link {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                <i class="fa-solid fa-user-gear main-icon"></i>
                <span class="menu-text">Profil Admin</span>
            </a>
        </nav>

        <div class="p-3 border-top" style="border-color: rgba(255,255,255,0.08) !important;">
            <form method="POST" action="{{ route('logout') }}" class="m-0">
                @csrf
                <button type="submit" class="menu-link text-danger w-100 border-0 text-start" style="background-color: rgba(239, 68, 68, 0.1);">
                    <i class="fa-solid fa-power-off main-icon text-danger"></i>
                    <span class="menu-text">Keluar Sistem</span>
                </button>
            </form>
        </div>
    </aside>

    <div id="main-wrapper">
        
        <header class="top-navbar">
            <div class="d-flex align-items-center gap-3">
                <button class="toggle-btn" id="sidebarCollapse">
                    <i class="fa-solid fa-bars-staggered"></i>
                </button>
                <h5 class="mb-0 fw-semibold d-none d-md-block text-muted">Panel Administrator</h5>
            </div>

            <div class="d-flex align-items-center gap-3">
                <div class="text-end d-none d-sm-block">
                    <div class="fw-semibold" style="font-size: 14px; line-height: 1.2;">{{ Auth::user()->name }}</div>
                    <small class="text-muted" style="font-size: 12px;">Hak Akses: Admin</small>
                </div>
                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 40px; height: 40px; font-weight: bold; font-size: 14px;">
                    {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                </div>
            </div>
        </header>

        <main class="content-body">
            @yield('content')
        </main>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Script Sidebar Animasi -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const sidebar = document.getElementById('sidebar');
            const mainWrapper = document.getElementById('main-wrapper');
            const collapseBtn = document.getElementById('sidebarCollapse');

            collapseBtn.addEventListener('click', function() {
                if (window.innerWidth > 768) {
                    sidebar.classList.toggle('collapsed');
                    mainWrapper.classList.toggle('expanded');
                    
                    if(sidebar.classList.contains('collapsed')){
                        const openSubmenus = document.querySelectorAll('.submenu-container.show');
                        openSubmenus.forEach(menu => {
                            let bsCollapse = bootstrap.Collapse.getInstance(menu);
                            if(bsCollapse) bsCollapse.hide();
                        });
                    }
                } else {
                    sidebar.classList.toggle('mobile-open');
                }
            });
        });
    </script>

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            // Mengaktifkan fitur pencarian pada dropdown form
            $('.select2').select2({
                theme: 'bootstrap-5',
                width: '100%',
                placeholder: '-- Ketik untuk mencari --'
            });
        });
    </script>

    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: '{{ session('success') }}',
                    showConfirmButton: false,
                    timer: 2500, 
                    customClass: { popup: 'rounded-4 shadow-sm' }
                });
            @endif

            @if(session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Aksi Gagal!',
                    text: '{{ session('error') }}',
                    confirmButtonColor: '#5196fe',
                    customClass: { popup: 'rounded-4 shadow-sm' }
                });
            @endif
        });
    </script>

    @stack('scripts')
</body>
</html>