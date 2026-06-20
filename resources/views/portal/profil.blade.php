<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pengaturan Akun - MySPP</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Source+Serif+4:ital,wght@1,500&display=swap" rel="stylesheet">
    <style>
        :root { --color-electric-blue: #5196fe; --color-ink-black: #1b1d20; --color-paper-white: #ffffff; --color-parchment: #f2f1ec; --color-sand: #e1dfd8; --color-steel: #6e6e6e; --color-danger: #e3342f; --color-success: #38c172; --radius-cards: 24px; --radius-buttons: 9999px; --font-inter: 'Inter', sans-serif; --font-serif: 'Source Serif 4', serif; }
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
        .btn-ghost { background-color: transparent; border: 1px solid var(--color-sand); color: var(--color-ink-black); border-radius: var(--radius-buttons); padding: 8px 24px; font-size: 14px; font-weight: 500; cursor: pointer; transition: all 0.2s; text-decoration: none; display: inline-flex; align-items: center; justify-content: center;}
        .btn-ghost:hover { background-color: var(--color-ink-black); color: var(--color-paper-white); }
        .btn-primary { background-color: var(--color-electric-blue); border: none; color: white; border-radius: var(--radius-buttons); padding: 12px 24px; font-size: 14px; font-weight: 600; cursor: pointer; transition: opacity 0.2s; }
        .btn-primary:hover { opacity: 0.9; }
        .container { max-width: 600px; margin: 0 auto; padding: 64px 20px; }
        .feature-card { background-color: var(--color-paper-white); border-radius: var(--radius-cards); padding: 40px; margin-bottom: 24px; }
        .hero-title { font-size: 32px; font-weight: 600; letter-spacing: -1px; margin: 0 0 24px 0; }
        .form-group { margin-bottom: 20px; }
        .form-label { display: block; font-size: 14px; font-weight: 600; margin-bottom: 8px; color: var(--color-ink-black); }
        .form-control { width: 100%; box-sizing: border-box; padding: 12px 16px; border: 1px solid var(--color-sand); border-radius: 12px; font-size: 15px; font-family: var(--font-inter); outline: none; transition: border-color 0.2s; background-color: var(--color-paper-white); color: var(--color-ink-black); }
        .form-control:focus { border-color: var(--color-electric-blue); }
        .text-error { color: var(--color-danger); font-size: 13px; margin-top: 4px; display: block; }
        .alert-success { background-color: rgba(56, 193, 114, 0.1); color: var(--color-success); padding: 16px; border-radius: 12px; margin-bottom: 24px; font-weight: 500; font-size: 14px; border: 1px solid rgba(56, 193, 114, 0.2); }
        .read-only-text { font-size: 15px; color: var(--color-steel); padding: 12px 16px; background-color: var(--color-parchment); border-radius: 12px; border: 1px solid var(--color-sand); }
    </style>
</head>
<body>
    <header class="header-nav">
        <div class="nav-left">
            <a href="#" class="brand-logo"><svg viewBox="0 0 24 24"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>My<span>SPP</span></a>
            <nav class="nav-links">
                <a href="{{ route('siswa.dashboard') }}">Ringkasan</a>
                <a href="{{ route('siswa.cara-pembayaran') }}">Cara Pembayaran</a>
                <a href="{{ route('siswa.bantuan') }}">Bantuan</a>
            </nav>
        </div>
        <div style="display: flex; gap: 12px;">
            <a href="{{ route('siswa.profil') }}" class="btn-ghost" style="background-color: var(--color-ink-black); color: var(--color-paper-white);">Pengaturan Akun</a>
            <form method="POST" action="{{ route('logout') }}" style="margin: 0;">@csrf<button type="submit" class="btn-ghost">Keluar</button></form>
        </div>
    </header>

    <main class="container">
        <h1 class="hero-title">Pengaturan Akun</h1>
        
        @if(session('success'))
        <div class="alert-success">
            {{ session('success') }}
        </div>
        @endif

        <form action="{{ route('siswa.profil.update') }}" method="POST">
            @csrf
            <div class="feature-card">
                <h3 style="margin-top:0; margin-bottom: 24px; font-size: 20px;">Informasi Pribadi</h3>
                
                <div class="form-group">
                    <label class="form-label">Nama Siswa</label>
                    <div class="read-only-text">{{ $siswa->nama_siswa }}</div>
                </div>

                <div class="form-group">
                    <label class="form-label">NISN (Username)</label>
                    <div class="read-only-text">{{ $siswa->nisn }}</div>
                </div>

                <div class="form-group">
                    <label class="form-label">Kelas</label>
                    <div class="read-only-text">{{ $siswa->kelas->nama_kelas ?? '-' }}</div>
                </div>

                <div class="form-group">
                    <label class="form-label">Nomor WhatsApp (Aktif)</label>
                    <input type="text" name="no_hp" class="form-control" value="{{ old('no_hp', $siswa->no_hp) }}" placeholder="Contoh: 08123456789">
                    <p style="font-size: 13px; color: var(--color-steel); margin-top: 6px;">Nomor ini akan menerima struk pembayaran otomatis dari sekolah.</p>
                    @error('no_hp')
                        <span class="text-error">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="feature-card">
                <h3 style="margin-top:0; margin-bottom: 24px; font-size: 20px;">Keamanan (Opsional)</h3>
                <p style="color: var(--color-steel); font-size: 14px; margin-bottom: 20px;">Kosongkan jika tidak ingin mengubah password Anda.</p>

                <div class="form-group">
                    <label class="form-label">Password Baru</label>
                    <input type="password" name="password" class="form-control" placeholder="Minimal 8 karakter">
                    @error('password')
                        <span class="text-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Konfirmasi Password Baru</label>
                    <input type="password" name="password_confirmation" class="form-control" placeholder="Ulangi password baru">
                </div>
            </div>

            <div style="text-align: right;">
                <button type="submit" class="btn-primary">Simpan Perubahan</button>
            </div>
        </form>
    </main>
</body>
</html>
