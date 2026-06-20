<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Siswa - MySPP</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Source+Serif+4:ital,opsz,wght@1,8..60,500&display=swap" rel="stylesheet">
    <style>
        :root {
            /* Parker Token Colors */
            --color-electric-blue: #5196fe;
            --color-ember-orange: #f9754e;
            --color-ink-black: #1b1d20;
            --color-paper-white: #ffffff;
            --color-parchment: #f2f1ec;
            --color-sand: #e1dfd8;
            --color-steel: #6e6e6e;
            
            /* Shapes */
            --radius-cards: 24px;
            --radius-buttons: 9999px;
            
            --font-inter: 'Inter', sans-serif;
            --font-serif: 'Source Serif 4', serif;
        }

        body {
            font-family: var(--font-inter);
            background-color: var(--color-parchment);
            color: var(--color-ink-black);
            margin: 0;
            padding: 0;
            line-height: 1.5;
        }

        /* Top Navigation */
        .header-nav {
            background-color: var(--color-paper-white);
            padding: 16px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid var(--color-sand);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .nav-left {
            display: flex;
            align-items: center;
            gap: 48px;
        }

        .brand-logo {
            font-weight: 600;
            font-size: 20px;
            color: var(--color-ink-black);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .brand-logo svg {
            width: 24px;
            height: 24px;
            fill: var(--color-electric-blue);
        }

        .brand-logo span {
            font-family: var(--font-serif);
            font-style: italic;
            color: var(--color-electric-blue);
        }

        /* Menu Text Horizontal */
        .nav-links {
            display: flex;
            gap: 24px;
        }

        .nav-links a {
            text-decoration: none;
            color: var(--color-steel);
            font-weight: 500;
            font-size: 15px;
            transition: color 0.2s;
        }

        .nav-links a.active {
            color: var(--color-ink-black);
        }

        .nav-links a:hover {
            color: var(--color-electric-blue);
        }

        /* Ghost Pill Button */
        .btn-ghost {
            background-color: transparent;
            border: 1px solid var(--color-sand);
            color: var(--color-ink-black);
            border-radius: var(--radius-buttons);
            padding: 8px 24px;
            font-size: 14px;
            font-weight: 500;
            font-family: var(--font-inter);
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-ghost:hover {
            background-color: var(--color-ink-black);
            color: var(--color-paper-white);
            border-color: var(--color-ink-black);
        }

        /* Layout */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 64px 40px;
        }

        /* Hero Section dengan Background Pattern */
        .hero-section {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 48px;
            position: relative;
        }

        .hero-text {
            position: relative;
            z-index: 2;
        }

        .hero-title {
            font-size: 48px;
            font-weight: 600;
            letter-spacing: -2.88px;
            line-height: 1.13;
            margin: 0 0 8px 0;
        }

        .hero-subtitle {
            font-size: 18px;
            color: var(--color-steel);
            letter-spacing: -0.36px;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .badge-kelas {
            background-color: var(--color-paper-white);
            border: 1px solid var(--color-sand);
            padding: 4px 12px;
            border-radius: var(--radius-buttons);
            font-size: 14px;
            font-weight: 500;
            color: var(--color-ink-black);
        }

        /* Ilustrasi Geometris Besar */
        .hero-graphic {
            position: absolute;
            right: 0;
            top: -30px;
            z-index: 1;
            opacity: 0.08; /* Sangat transparan seperti watermark */
            pointer-events: none;
        }

        .grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 24px;
        }

        /* Cards */
        .feature-card {
            background-color: var(--color-paper-white);
            border-radius: var(--radius-cards);
            padding: 24px;
            display: flex;
            flex-direction: column;
            position: relative;
            overflow: hidden;
        }

        /* Background Logo dalam Card */
        .card-bg-logo {
            position: absolute;
            right: -20px;
            bottom: -20px;
            opacity: 0.03;
            width: 150px;
            height: 150px;
            transform: rotate(-15deg);
        }

        /* Icon Tile */
        .icon-tile {
            width: 48px;
            height: 48px;
            background-color: rgba(81, 150, 254, 0.1);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 16px;
        }

        .icon-tile svg {
            width: 24px;
            height: 24px;
            stroke: var(--color-electric-blue);
            stroke-width: 2;
            fill: none;
        }

        .icon-tile-ember {
            background-color: rgba(249, 117, 78, 0.1);
        }
        .icon-tile-ember svg {
            stroke: var(--color-ember-orange);
        }

        .card-title {
            font-size: 20px;
            font-weight: 600;
            letter-spacing: -0.46px;
            margin: 0 0 4px 0;
            position: relative;
            z-index: 2;
        }

        .card-meta {
            font-size: 16px;
            color: var(--color-steel);
            margin: 0 0 16px 0;
            position: relative;
            z-index: 2;
        }

        .card-amount {
            font-size: 32px;
            font-weight: 700;
            letter-spacing: -0.96px;
            margin: auto 0 0 0;
            color: var(--color-ink-black);
            position: relative;
            z-index: 2;
        }

        .section-label {
            font-size: 14px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--color-steel);
            margin-bottom: 16px;
            display: block;
        }

        /* Empty State */
        .empty-state {
            border: 1px dashed var(--color-sand);
            border-radius: var(--radius-cards);
            padding: 60px 24px;
            text-align: center;
            color: var(--color-steel);
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 16px;
        }

        /* Mobile Responsiveness */
        @media (max-width: 768px) {
            .header-nav {
                flex-direction: column;
                padding: 16px;
                gap: 16px;
            }
            .nav-left {
                flex-direction: column;
                gap: 16px;
            }
            .nav-links {
                flex-wrap: wrap;
                justify-content: center;
                gap: 12px;
                font-size: 14px;
            }
            .grid-2 {
                grid-template-columns: 1fr;
            }
            .hero-section {
                padding: 24px 16px;
                text-align: center;
                align-items: center;
                display: flex;
                flex-direction: column;
            }
            .hero-subtitle {
                flex-direction: column;
                align-items: center;
                gap: 8px;
            }
            .hero-graphic {
                display: none;
            }
            .container {
                padding: 16px;
            }
        }
    </style>
</head>
<body>

    <header class="header-nav">
        <div class="nav-left">
            <a href="#" class="brand-logo">
                <svg viewBox="0 0 24 24"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
                My<span>SPP</span>
            </a>
            
            <nav class="nav-links">
                <a href="{{ route('siswa.dashboard') }}" class="{{ request()->routeIs('siswa.dashboard') ? 'active' : '' }}">Ringkasan</a>
                <a href="{{ route('siswa.cara-pembayaran') }}" class="{{ request()->routeIs('siswa.cara-pembayaran') ? 'active' : '' }}">Cara Pembayaran</a>
                <a href="{{ route('siswa.bantuan') }}" class="{{ request()->routeIs('siswa.bantuan') ? 'active' : '' }}">Bantuan</a>
            </nav>
        </div>
        
        <div style="display: flex; gap: 12px;">
            <a href="{{ route('siswa.profil') }}" class="btn-ghost" style="text-decoration: none; display: inline-flex; align-items: center; justify-content: center;">Pengaturan</a>
            <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                @csrf
                <button type="submit" class="btn-ghost">Keluar</button>
            </form>
        </div>
    </header>

    <main class="container">
        <div class="hero-section">
            <div class="hero-text">
                <h1 class="hero-title">Halo, {{ explode(' ', trim($siswa->nama_siswa))[0] }}</h1>
                <p class="hero-subtitle">
                    NISN: {{ $siswa->nisn }} 
                    <span class="badge-kelas">Kelas {{ $siswa->kelas->nama_kelas ?? '-' }}</span>
                </p>
            </div>
            
            <svg class="hero-graphic" width="200" height="200" viewBox="0 0 24 24" fill="none" stroke="var(--color-electric-blue)" stroke-width="0.5">
                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                <circle cx="12" cy="7" r="4"></circle>
            </svg>
        </div>


        <div class="grid-2">
            <div>
                <span class="section-label">Aksi Diperlukan</span>
                
                @forelse($tagihan_aktif as $item)
                <div class="feature-card" style="margin-bottom: 16px;">
                    <svg class="card-bg-logo" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/></svg>
                    
                    <div class="icon-tile icon-tile-ember">
                        <svg viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                    </div>
                    <h3 class="card-title">{{ $item->tagihan->nama_tagihan }}</h3>
                    <p class="card-meta">Diterbitkan: {{ $item->created_at->format('d/m/Y') }}</p>
                    <div class="card-amount">Rp {{ number_format($item->tagihan->kategori->nominal_default ?? 0, 0, ',', '.') }}</div>
                </div>
                @empty
                <div class="empty-state">
                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="var(--color-sand)" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                    <p style="margin:0;">Hore! Semua tagihan Anda sudah dilunasi.</p>
                </div>
                @endforelse
            </div>

            <div>
                <span class="section-label">Riwayat Transaksi</span>
                
                @forelse($riwayat as $item)
                <div class="feature-card" style="margin-bottom: 16px; background-color: transparent; border: 1px solid var(--color-sand);">
                    
                    <svg class="card-bg-logo" style="opacity:0.02;" viewBox="0 0 24 24" fill="currentColor"><path d="M12 22C6.477 22 2 17.523 2 12S6.477 2 12 2s10 4.477 10 10-4.477 10-10 10zm-1.177-7.86l-2.765-2.767L7 12.431l3.118 3.121a1 1 0 0 0 1.414 0l5.952-5.95-1.062-1.062-5.6 5.6z"/></svg>

                    <div class="icon-tile">
                        <svg viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                    </div>
                    <h3 class="card-title">{{ $item->tagihan->nama_tagihan }}</h3>
                    <p class="card-meta">Lunas pada: {{ \Carbon\Carbon::parse($item->tanggal_bayar)->format('d M Y') }}</p>
                    <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-top: auto;">
                        <div class="card-amount" style="font-size: 24px; color: var(--color-steel); margin:0;">
                            Rp {{ number_format($item->tagihan->kategori->nominal_default ?? 0, 0, ',', '.') }}
                        </div>
                        <a href="{{ route('siswa.kwitansi', $item->id_pembayaran) }}" target="_blank" class="btn-ghost" style="text-decoration: none; display: inline-flex; align-items: center; justify-content: center; font-size: 14px; padding: 6px 16px; border: 1px solid var(--color-sand); z-index:10; position:relative;">Cetak Bukti</a>
                    </div>
                </div>
                @empty
                <div class="empty-state">
                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="var(--color-sand)" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                    <p style="margin:0;">Belum ada riwayat pelunasan tercatat.</p>
                </div>
                @endforelse
            </div>
        </div>
    </main>

</body>
</html>