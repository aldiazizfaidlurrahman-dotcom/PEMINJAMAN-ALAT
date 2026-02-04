<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Peminjaman Alat</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        :root {
            /* Background luar (abu-abu sangat muda) */
            --bg-page: #f1f5f9; 
            /* Background kartu (Putih murni agar kontras) */
            --bg-card: #ffffff;
            /* Background input (Abu-abu lembut agar beda dengan kartu) */
            --bg-input: #f8fafc;
            
            --neutral-200: #e2e8f0;
            --neutral-400: #94a3b8;
            --neutral-600: #475569;
            --neutral-900: #0f172a;
            --white: #ffffff;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg-page); /* Warna luar lebih gelap sedikit */
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 24px;
        }

        .login-card {
            background: var(--bg-card);
            width: 100%;
            max-width: 420px;
            padding: 3.5rem 2.5rem;
            border-radius: 24px;
            /* Shadow dibuat lebih tebal agar kartu terlihat 'mengambang' */
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            /* Border luar kartu diperjelas */
            border: 1px solid var(--neutral-200);
        }

        .header { text-align: center; margin-bottom: 2.5rem; }

        .brand-logo {
            width: 52px; height: 52px;
            background: var(--neutral-900);
            color: white;
            border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 1.25rem;
        }

        .header h1 {
            font-size: 1.6rem;
            font-weight: 800;
            color: var(--neutral-900);
            letter-spacing: -0.02em;
        }

        .header p { color: var(--neutral-400); font-size: 0.9rem; margin-top: 4px; }

        .form-group { margin-bottom: 1.5rem; }

        .form-group label {
            display: block;
            font-size: 0.8rem;
            font-weight: 700;
            text-transform: uppercase;
            color: var(--neutral-600);
            margin-bottom: 8px;
            padding-left: 4px;
        }

        .input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-wrapper i.prefix-icon {
            position: absolute;
            left: 16px;
            color: var(--neutral-400);
            width: 18px;
        }

        /* Styling Input agar beda dengan background kartu */
        .form-group input {
            width: 100%;
            padding: 1rem 1rem 1rem 48px;
            background-color: var(--bg-input); /* Warna input beda dengan kartu */
            border: 2px solid var(--neutral-200); /* Border dipertebal jadi 2px */
            border-radius: 16px;
            font-size: 1rem;
            color: var(--neutral-900);
            transition: all 0.2s ease;
        }

        /* Efek saat input diklik */
        .form-group input:focus {
            outline: none;
            background-color: var(--white); /* Berubah putih saat fokus */
            border-color: var(--neutral-900);
            box-shadow: 0 0 0 4px rgba(15, 23, 42, 0.08);
        }

        .toggle-password {
            position: absolute;
            right: 16px;
            color: var(--neutral-400);
            cursor: pointer;
            padding: 4px;
            display: flex;
            transition: color 0.2s;
        }

        .toggle-password:hover { color: var(--neutral-900); }

        .login-button {
            width: 100%;
            padding: 1.1rem;
            background-color: var(--neutral-900);
            color: white;
            border: none;
            border-radius: 16px;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s;
            display: flex; align-items: center; justify-content: center;
            gap: 12px;
            margin-top: 2rem;
        }

        .login-button:hover {
            background-color: #000000;
            transform: translateY(-2px);
            box-shadow: 0 12px 20px -8px rgba(0, 0, 0, 0.3);
        }

        .alert {
            padding: 1rem;
            border-radius: 14px;
            font-size: 0.85rem;
            margin-bottom: 2rem;
            background-color: #fff1f2;
            color: #9f1239;
            border: 1px solid #fecdd3;
            display: flex; align-items: center; gap: 10px;
            font-weight: 500;
        }

        .footer {
            margin-top: 3rem;
            text-align: center;
            font-size: 0.8rem;
            color: var(--neutral-400);
        }
    </style>
</head>
<body>

    <div class="login-card">
        <div class="header">
            <div class="brand-logo">
                <i data-lucide="lock"></i>
            </div>
            <h1>Akses Sistem</h1>
            <p>Masukkan akun untuk melanjutkan</p>
        </div>

        @if ($errors->any() || session('error'))
            <div class="alert">
                <i data-lucide="info" style="width: 18px;"></i>
                <span>{{ session('error') ?? 'Gagal masuk. Cek kembali data Anda.' }}</span>
            </div>
        @endif

        <form action="{{ route('login.process') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="username">ID Pengguna</label>
                <div class="input-wrapper">
                    <i data-lucide="user" class="prefix-icon"></i>
                    <input 
                        type="text" id="username" name="username" 
                        placeholder="Username"
                        value="{{ old('username') }}" 
                        required autofocus
                    >
                </div>
            </div>

            <div class="form-group">
                <label for="password">Kata Sandi</label>
                <div class="input-wrapper">
                    <i data-lucide="key-round" class="prefix-icon"></i>
                    <input 
                        type="password" id="password" name="password" 
                        placeholder="••••••••"
                        required
                    >
                    <div class="toggle-password" id="eye-btn">
                        <i data-lucide="eye" id="eye-icon" style="width: 20px;"></i>
                    </div>
                </div>
            </div>

            <button type="submit" class="login-button">
                <span>Konfirmasi Masuk</span>
                <i data-lucide="arrow-right" style="width: 20px;"></i>
            </button>
        </form>

        <div class="footer">
            &copy; 2026 <b>SIPEDAL</b>. Authorized Access Only.
        </div>
    </div>

    <script>
        lucide.createIcons();

        const eyeBtn = document.getElementById('eye-btn');
        const passwordInput = document.getElementById('password');

        eyeBtn.addEventListener('click', () => {
            const isPassword = passwordInput.type === 'password';
            passwordInput.type = isPassword ? 'text' : 'password';
            
            eyeBtn.innerHTML = isPassword 
                ? '<i data-lucide="eye-off" style="width: 20px;"></i>' 
                : '<i data-lucide="eye" style="width: 20px;"></i>';
            
            lucide.createIcons();
        });
    </script>
</body>
</html>