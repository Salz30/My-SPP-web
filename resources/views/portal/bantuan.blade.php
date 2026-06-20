<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Bantuan - MySPP</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Source+Serif+4:ital,wght@1,500&display=swap" rel="stylesheet">
    <style>
        :root { --color-electric-blue: #5196fe; --color-ink-black: #1b1d20; --color-paper-white: #ffffff; --color-parchment: #f2f1ec; --color-sand: #e1dfd8; --color-steel: #6e6e6e; --radius-cards: 24px; --radius-buttons: 9999px; --font-inter: 'Inter', sans-serif; --font-serif: 'Source Serif 4', serif; }
        body { font-family: var(--font-inter); background-color: var(--color-parchment); color: var(--color-ink-black); margin: 0; line-height: 1.5; }
        .header-nav { background-color: var(--color-paper-white); padding: 16px 40px; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--color-sand); position: sticky; top: 0; z-index: 100; }
        .nav-left { display: flex; align-items: center; gap: 48px; }
        .brand-logo { font-weight: 600; font-size: 20px; color: var(--color-ink-black); text-decoration: none; display: flex; align-items: center; gap: 8px; }
        .brand-logo svg { width: 24px; height: 24px; fill: var(--color-electric-blue); }
        .brand-logo span { font-family: var(--font-serif); font-style: italic; color: var(--color-electric-blue); }
        .nav-links { display: flex; gap: 24px; }
        .nav-links a { text-decoration: none; color: var(--color-steel); font-weight: 500; font-size: 15px; transition: color 0.2s; }
        .nav-links a.active { color: var(--color-ink-black); }
        .nav-links a:hover { color: var(--color-electric-blue); }
        .btn-ghost { background-color: transparent; border: 1px solid var(--color-sand); color: var(--color-ink-black); border-radius: var(--radius-buttons); padding: 8px 24px; font-size: 14px; font-weight: 500; cursor: pointer; transition: all 0.2s; }
        .btn-ghost:hover { background-color: var(--color-ink-black); color: var(--color-paper-white); }
        .container { max-width: 800px; margin: 0 auto; padding: 64px 40px; }
        .feature-card { background-color: var(--color-paper-white); border-radius: var(--radius-cards); padding: 40px; margin-bottom: 24px; text-align: center;}
        .hero-title { font-size: 40px; font-weight: 600; letter-spacing: -1.5px; margin: 0 0 24px 0; text-align: center;}
    </style>
</head>
<body>
    <header class="header-nav">
        <div class="nav-left">
            <a href="#" class="brand-logo"><svg viewBox="0 0 24 24"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>My<span>SPP</span></a>
            <nav class="nav-links">
                <a href="{{ route('siswa.dashboard') }}" class="{{ request()->routeIs('siswa.dashboard') ? 'active' : '' }}">Ringkasan</a>
                <a href="{{ route('siswa.cara-pembayaran') }}" class="{{ request()->routeIs('siswa.cara-pembayaran') ? 'active' : '' }}">Cara Pembayaran</a>
                <a href="{{ route('siswa.bantuan') }}" class="{{ request()->routeIs('siswa.bantuan') ? 'active' : '' }}">Bantuan</a>
            </nav>
        </div>
        <div style="display: flex; gap: 12px;">
            <a href="{{ route('siswa.profil') }}" class="btn-ghost" style="text-decoration: none; display: inline-flex; align-items: center; justify-content: center;">Pengaturan</a>
            <form method="POST" action="{{ route('logout') }}" style="margin: 0;">@csrf<button type="submit" class="btn-ghost">Keluar</button></form>
        </div>
    </header>

    <main class="container">
        <h1 class="hero-title">Pusat Bantuan</h1>
        <div class="feature-card">
            <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="var(--color-electric-blue)" stroke-width="2" style="margin-bottom: 16px;"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
            <h3 style="margin-top:0;">Butuh Bantuan Lebih Lanjut?</h3>
            <p style="color: var(--color-steel); font-size: 16px; margin-bottom: 24px;">Jika terdapat ketidaksesuaian data tagihan atau Anda lupa password akun, silakan hubungi Administrator sekolah.</p>
            <p style="font-weight: bold; font-size: 18px;">Email: admin@myspp.test</p>
            <p style="font-weight: bold; font-size: 18px;">Ruangan: Ruang Tata Usaha (Lantai 1)</p>
        </div>
    </main>
</body>
</html>