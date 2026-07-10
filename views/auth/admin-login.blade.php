<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Masuk Pegawai | {{ config('app.name') }} DPR RI</title>

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('theme/admin-dashbyte/dist/assets/css/style.min.css') }}">
    <link rel="shortcut icon" href="{{ asset('theme/admin-dashbyte/dist/assets/img/favicon.ico') }}" type="image/x-icon">

    <style>
        :root {
            --primary-dark: #0f172a;
            --accent-gold: linear-gradient(135deg, #c5a059 0%, #8e6d2f 100%);
            --gold-solid: #b08d48;
            --text-main: #1e293b;
            --text-muted: #64748b;
            --white: #ffffff;
            --transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
        }

        html[data-skin="dark"] {
            --primary-dark: #e5e7eb;
            --text-main: #e5e7eb;
            --text-muted: #9ca3af;
            --white: #020617;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f8fafc;
            margin: 0;
            overflow: hidden;
        }

        html[data-skin="dark"] body {
            background-color: #020617;
            color: var(--text-main);
        }

        body::before {
            content: '';
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background-image: url("{{ asset('theme/admin-dashbyte/dist/assets/img/batiknew.png') }}");
            background-size: 400px;
            opacity: 0.04;
            z-index: -1;
        }

        .row-full {
            height: 100vh;
            margin: 0;
        }

        .login-column {
            background: linear-gradient(135deg, #f8fafc 0%, #eef2f7 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 3rem;
            box-shadow: 20px 0 50px rgba(0,0,0,0.05);
            z-index: 10;
            position: relative;
            overflow: hidden;
        }

        html[data-skin="dark"] .login-column {
            background: radial-gradient(circle at top, rgba(15,23,42,0.9) 0%, #020617 55%, #020617 100%);
            box-shadow: 20px 0 50px rgba(0,0,0,0.7);
        }

        .login-column::before {
            content: '';
            position: absolute;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(197, 160, 89, 0.1) 0%, transparent 70%);
            border-radius: 50%;
            top: -100px;
            right: -50px;
            z-index: 0;
        }

        .login-column::after {
            content: '';
            position: absolute;
            width: 200px;
            height: 200px;
            background: radial-gradient(circle, rgba(197, 160, 89, 0.05) 0%, transparent 70%);
            border-radius: 50%;
            bottom: -50px;
            left: -30px;
            z-index: 0;
        }

        .login-box {
            width: 100%;
            max-width: 400px;
        }

        .brand-wrapper {
            display: flex;
            align-items: center;
            gap: 0px;
            margin-bottom: 1.5rem;
            text-decoration: none;
        }

        .brand-logo {
            height: 80px;
            width: auto;
        }

        .brand-text {
            color: var(--primary-dark);
            font-weight: 800;
            font-size: 1.5rem;
            letter-spacing: -0.5px;
            line-height: 1;
        }

        .brand-text span {
            display: block;
            font-size: 0.75rem;
            color: var(--gold-solid);
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-top: 5px;
        }

        .card-title {
            font-weight: 800;
            color: var(--primary-dark);
            font-size: 1.75rem;
            margin-bottom: 0.5rem;
        }

        .card-text {
            color: var(--text-muted);
            margin-bottom: 1.5rem;
        }

        html[data-skin="dark"] .card-title {
            color: #e5e7eb;
        }

        html[data-skin="dark"] .card-text {
            color: #9ca3af;
        }

        .form-label {
            font-weight: 700;
            font-size: 0.85rem;
            color: var(--primary-dark);
        }

        .form-control {
            padding: 0.8rem 1.2rem;
            border-radius: 12px;
            border: 1.5px solid #e2e8f0;
            transition: var(--transition);
            background-color: #ffffff;
            color: var(--text-main);
        }

        .form-control:focus {
            border-color: var(--gold-solid);
            box-shadow: 0 0 0 4px rgba(176, 141, 72, 0.1);
        }

        html[data-skin="dark"] .form-label {
            color: #e5e7eb;
        }

        html[data-skin="dark"] .form-control {
            background-color: #1e293b;
            border-color: #374151;
            color: #ffffff;
        }
        html[data-skin="dark"] .form-control:focus {
            background-color: #0f172a;
        }

        .btn-sign {
            background: var(--accent-gold);
            border: none;
            padding: 0.9rem;
            border-radius: 12px;
            font-weight: 700;
            color: white;
            width: 100%;
            margin-top: 1rem;
            transition: var(--transition);
            box-shadow: 0 10px 20px rgba(142, 109, 47, 0.2);
            position: relative;
            overflow: hidden;
        }

        .btn-sign:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 30px rgba(142, 109, 47, 0.3);
            filter: brightness(1.1);
        }

        .btn-sign.loading {
            pointer-events: none;
            opacity: 0.85;
        }
        .btn-sign .spinner-border {
            width: 1rem;
            height: 1rem;
            border-width: 2px;
        }

        .image-column {
            position: relative;
            padding: 0;
            overflow: hidden;
        }

        .auth-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .overlay-gold {
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            background: linear-gradient(225deg, rgba(15, 23, 42, 0.1) 0%, rgba(15, 23, 42, 0.8) 100%);
        }

        .overlay-content {
            position: absolute;
            bottom: 6rem;
            left: 5rem;
            right: 5rem;
            color: white;
        }

        .overlay-content h2 {
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 1rem;
        }

        .quote-line {
            width: 60px;
            height: 4px;
            background: var(--gold-solid);
            margin-bottom: 1.5rem;
        }

        .btn-back {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 0.6rem 1.2rem;
            background: white;
            border: 1.5px solid var(--gold-solid);
            color: var(--gold-solid);
            border-radius: 10px;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.85rem;
            transition: var(--transition);
            margin-bottom: 1rem;
        }

        .btn-back:hover {
            background: var(--accent-gold);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(142, 109, 47, 0.2);
        }

        .alert {
            border-radius: 12px;
            border: none;
            padding: 1rem 1.25rem;
            margin-bottom: 1.5rem;
        }

        .alert-danger {
            background-color: #fee2e2;
            color: #991b1b;
            border-left: 4px solid #dc2626;
        }

        .alert i {
            margin-right: 0.5rem;
        }

        .login-badge {
            display: inline-block;
            background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
            color: #e2e8f0;
            font-size: 0.7rem;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            padding: 0.3rem 0.8rem;
            border-radius: 6px;
            margin-bottom: 1rem;
        }

        @media (max-width: 991px) {
            .row-full {
                height: auto;
                min-height: 100vh;
            }

            .login-column {
                padding: 2rem 1.5rem;
                min-height: 100vh;
            }

            .login-box {
                max-width: 100%;
            }

            .brand-logo {
                height: 60px;
            }

            .brand-text {
                font-size: 1rem;
            }

            .brand-text span {
                font-size: 0.6rem;
            }
        }

        @media (max-width: 576px) {
            .login-column {
                padding: 1.5rem 1rem;
            }

            .brand-logo {
                height: 50px;
            }

            .brand-text {
                font-size: 0.9rem;
            }

            .login-title {
                font-size: 1.5rem;
            }

            .login-subtitle {
                font-size: 0.85rem;
            }

            .form-control {
                padding: 0.7rem 1rem;
            }

            .btn-sign {
                padding: 0.9rem;
            }
        }
    </style>
    <script>
        (function() {
            try {
                var mode = localStorage.getItem('skin-mode');
                if (mode === 'dark') {
                    document.documentElement.setAttribute('data-skin', 'dark');
                }
            } catch (e) {}
        })();
    </script>
</head>
<body>
    @include('partials.global_page_loader')

    <div class="row g-0 row-full">
        <div class="col-lg-5 col-xl-4 login-column">
            <div class="login-box">

                <a href="{{ url('/') }}" class="brand-wrapper">
                    <img src="{{ asset('theme/admin-dashbyte/dist/assets/img/SMART.png') }}" alt="Logo {{ config('app.name') }}" style="height: 50px;">
                    <img src="{{ asset('theme/admin-dashbyte/dist/assets/img/logo.png') }}" class="brand-logo" alt="Logo DPR" style="height: 50px; margin-right: 5px;">
                    <div class="brand-text">
                        {{ config('app.name') }}
                        <span>Setjen DPR RI</span>
                    </div>
                </a>

                <div class="login-badge">Portal Pegawai</div>
                <h1 class="card-title">Masuk Pegawai</h1>
                <p class="card-text">Gunakan username dan password pegawai Anda untuk mengakses portal internal.</p>

                <a href="{{ route('Halaman awal') }}" class="btn-back">
                    Kembali ke Beranda
                </a>

                <form method="POST" action="{{ route('admin.login') }}" style="margin-top: 0;" id="loginForm">
                    @csrf

                    @if($errors->has('login_error') || $errors->has('status'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="ri-error-warning-line"></i>
                            <strong>{{ $errors->first('login_error') ?? $errors->first('status') }}</strong>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <input type="text" class="form-control @error('username') is-invalid @enderror"
                               name="username" value="{{ old('username') }}" required
                               placeholder="Masukkan username" autofocus>
                        @error('username')
                            <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <label class="form-label">Kata Sandi</label>
                        </div>
                        <div style="position: relative;">
                            <input type="password" id="passwordField" class="form-control @error('password') is-invalid @enderror"
                                   name="password" required placeholder="Masukkan kata sandi">
                            <i class="ri-eye-line" id="togglePasswordIcon"
                               style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); cursor: pointer; color: var(--gold-solid);"
                               onclick="togglePasswordVisibility()"></i>
                        </div>
                        @error('password')
                            <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>

                    <div class="mb-3 d-flex justify-content-between align-items-center">
                        <div class="form-check mb-0">
                            <input type="checkbox" class="form-check-input" id="remember" name="remember" value="1">
                            <label class="form-check-label small text-muted" for="remember">Ingat Saya</label>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-sign" id="btnLogin">
                        <span class="btn-text">Masuk Sekarang</span>
                        <span class="btn-loading d-none">
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            <span class="ms-2">Memproses...</span>
                        </span>
                    </button>

                    <p class="text-center mt-4 text-muted small">
                        <a href="{{ route('login') }}" style="color: var(--gold-solid); font-weight: 700; text-decoration: none;">Login Peserta Magang</a>
                    </p>
                </form>
            </div>
        </div>

        <div class="col-lg-7 col-xl-8 image-column d-none d-lg-block">
            <img class="auth-img" src="{{ asset('theme/admin-dashbyte/dist/assets/img/bg-dpr.jpg') }}" alt="Gedung DPR RI">
            <div class="overlay-gold"></div>
            <div class="overlay-content">
                <div class="quote-line"></div>
                <h2>Portal Internal <br>Setjen DPR RI.</h2>
                <p class="lead opacity-75">Kelola program magang legislatif Indonesia dengan integritas dan profesionalisme.</p>
            </div>
        </div>
    </div>

    <script src="{{ asset('theme/admin-dashbyte/dist/lib/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('theme/admin-dashbyte/dist/lib/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script>
        function togglePasswordVisibility() {
            const passwordField = document.getElementById('passwordField');
            const toggleIcon = document.getElementById('togglePasswordIcon');

            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                toggleIcon.classList.remove('ri-eye-line');
                toggleIcon.classList.add('ri-eye-off-line');
            } else {
                passwordField.type = 'password';
                toggleIcon.classList.remove('ri-eye-off-line');
                toggleIcon.classList.add('ri-eye-line');
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('loginForm');
            const btn = document.getElementById('btnLogin');
            if (!form || !btn) return;

            const textEl = btn.querySelector('.btn-text');
            const loadingEl = btn.querySelector('.btn-loading');

            form.addEventListener('submit', function() {
                btn.classList.add('loading');
                if (textEl) textEl.classList.add('d-none');
                if (loadingEl) loadingEl.classList.remove('d-none');
            });
        });
    </script>
</body>
</html>
