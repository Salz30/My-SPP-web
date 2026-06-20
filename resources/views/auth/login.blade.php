<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk ke My-SPP</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Source+Serif+4:ital,opsz,wght@1,8..60,500&display=swap" rel="stylesheet">
    <style>
        :root {
            --color-electric-blue: #5196fe;
            --color-ink-black: #1b1d20;
            --color-paper-white: #ffffff;
            --color-parchment: #f2f1ec;
            --color-steel: #6e6e6e;
            --color-fog: #a3a3a3;
            --color-danger: #f9754e;
            
            --font-inter: 'Inter', sans-serif;
            --font-serif: 'Source Serif 4', serif;
        }

        body {
            font-family: var(--font-inter);
            background-color: var(--color-parchment);
            color: var(--color-ink-black);
            margin: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-container {
            background-color: var(--color-paper-white);
            border-radius: 24px;
            padding: 48px;
            width: 100%;
            max-width: 440px;
            /* Diangkat murni menggunakan kontras warna, bukan shadow besar */
            box-shadow: rgba(0, 0, 0, 0.05) 0px 4px 20px;
        }

        .login-header {
            margin-bottom: 40px;
        }

        .login-header h1 {
            font-size: 32px;
            font-weight: 600;
            margin: 0 0 8px 0;
            letter-spacing: -0.96px;
            line-height: 1.16;
        }

        .login-header h1 span {
            font-family: var(--font-serif);
            font-style: italic;
            font-weight: 500;
            color: var(--color-electric-blue);
        }

        .login-header p {
            color: var(--color-steel);
            font-size: 16px;
            margin: 0;
        }

        .form-group {
            margin-bottom: 24px;
        }

        .form-group label {
            display: block;
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 8px;
        }

        .form-control {
            width: 100%;
            padding: 16px;
            border: 1px solid var(--color-fog);
            border-radius: 12.8px;
            font-size: 16px;
            font-family: var(--font-inter);
            box-sizing: border-box;
            transition: border-color 0.2s;
            background-color: var(--color-paper-white);
        }

        .form-control:focus {
            outline: none;
            border-color: var(--color-electric-blue);
        }

        .btn-primary {
            width: 100%;
            background-color: var(--color-electric-blue);
            color: white;
            border: none;
            border-radius: 9999px;
            padding: 16px 24px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: opacity 0.2s;
            margin-top: 8px;
            font-family: var(--font-inter);
        }

        .btn-primary:hover {
            opacity: 0.9;
        }

        .error-message {
            color: var(--color-danger);
            font-size: 14px;
            margin-top: 6px;
            display: block;
        }
    </style>
</head>
<body>

    <div class="login-container">
        <div class="login-header">
            <h1>Masuk ke <span>My-SPP</span></h1>
            <p>Masukkan NISN atau Username Anda.</p>
        </div>

        @if (session('status'))
            <div style="color: #198754; margin-bottom: 20px; font-size: 14px;">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="form-group">
                <label for="username">NISN / Username</label>
                <input id="username" class="form-control" type="text" name="username" value="{{ old('username') }}" required autofocus autocomplete="username">
                @error('username')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">Kata Sandi</label>
                <input id="password" class="form-control" type="password" name="password" required autocomplete="current-password">
                @error('password')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <button type="submit" class="btn-primary">
                Masuk Sekarang
            </button>
        </form>
    </div>

</body>
</html>