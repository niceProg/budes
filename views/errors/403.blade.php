<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>403 - Akses Ditolak | {{ config('app.name') }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('theme/admin-dashbyte/dist/assets/img/favicon.png') }}">
    <link rel="stylesheet" href="{{ asset('theme/admin-dashbyte/dist/assets/css/style.min.css') }}">
    
    <style>
        :root {
            --primary-color: #b08d48;
            --text-main: #0f172a;
            --text-muted: #64748b;
            --bg-body: #f8fafc;
        }

        [data-skin="dark"] {
            --bg-body: #0b1120;
            --text-main: #f1f5f9;
            --text-muted: #94a3b8;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg-body);
            color: var(--text-main);
            margin: 0;
            transition: background-color 0.3s ease;
        }
        .bg-pattern {
            position: fixed;
            inset: 0;
            background-image: url('theme/admin-dashbyte/dist/assets/img/batiknew.png');
            background-size: cover;
            opacity: 0.05;
            z-index: -1;
            pointer-events: none;
        }
        [data-skin="dark"] .bg-pattern { opacity: 0.03; }

        .error-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
            text-align: center;
        }

        .error-content {
            max-width: 500px;
            width: 100%;
        }

        .error-code {
            font-size: clamp(8rem, 20vw, 12rem);
            font-weight: 800;
            line-height: 1;
            margin-bottom: 0;
            background: linear-gradient(135deg, var(--primary-color), #d4af37);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            letter-spacing: -5px;
            filter: drop-shadow(0 10px 20px rgba(176, 141, 72, 0.2));
        }

        .error-title {
            font-size: 1.75rem;
            font-weight: 800;
            margin-top: -10px;
            margin-bottom: 12px;
            letter-spacing: -0.5px;
        }

        .error-message {
            font-size: 1.1rem;
            color: var(--text-muted);
            margin-bottom: 32px;
            line-height: 1.6;
        }

        .btn-back {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background-color: var(--primary-color);
            color: white;
            padding: 14px 32px;
            border-radius: 12px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s ease;
            box-shadow: 0 10px 15px -3px rgba(176, 141, 72, 0.3);
        }

        .btn-back:hover {
            transform: translateY(-2px);
            box-shadow: 0 20px 25px -5px rgba(176, 141, 72, 0.4);
            background-color: #9a7b3e;
            color: white;
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .animate-up {
            animation: fadeInUp 0.6s ease forwards;
        }
    </style>
</head>
<body>
    @include('partials.global_page_loader')
    <div class="bg-pattern" aria-hidden="true"></div>
    <script>
        (function () {
            try {
                var mode = localStorage.getItem('skin-mode');
                if (mode === 'dark') document.documentElement.setAttribute('data-skin', 'dark');
                else document.documentElement.setAttribute('data-skin', '');
            } catch (e) {}
        })();
    </script>

    @php
        // Tentukan halaman balik:
        // - Jika sudah login -> ke dashboard sesuai role.
        // - Jika belum login -> arahkan ke halaman login.
        $returnUrl = route('login');

        if (auth()->check()) {
            $isPegawai = session('auth_type') === 'pegawai';

            if ($isPegawai) {
                $returnUrl = route('pegawai.dashboard');
            } else {
                $role = auth()->user()->roles ?? null;

                if ($role === 'admin') {
                    $returnUrl = route('admin.index');
                } elseif ($role === 'peserta') {
                    $returnUrl = route('peserta.index');
                } elseif ($role === 'guru') {
                    $returnUrl = route('pembimbing.index');
                } elseif ($role === 'mentor') {
                    $returnUrl = route('absensi.mentor.index');
                } else {
                    $returnUrl = route('admin.index');
                }
            }
        }
    @endphp

    <div style="position:fixed; top:14px; left:18px; display:flex; align-items:center; gap:8px; z-index:10;">
        <img src="{{ asset('theme/admin-dashbyte/dist/assets/img/SMART.png') }}" alt="Logo {{ config('app.name') }}" style="height:50px;">
        <img src="{{ asset('theme/admin-dashbyte/dist/assets/img/logo.png') }}" alt="DPR RI" style="height:50px; margin-right:5px;">
        <div style="margin-left:4px;">
            <div style="font-weight:800; font-size:1.25rem; letter-spacing:-0.5px; color:var(--text-main);">
                {{ config('app.name') }}
            </div>
            <div style="font-weight:700; font-size:0.8rem; letter-spacing:1.5px; text-transform:uppercase; color:#b08d48;">
                SETJEN DPR RI
            </div>
        </div>
    </div>

    <div class="error-wrapper">
        <div class="error-content animate-up">
            <div class="error-code">403</div>
            <h2 class="error-title">Anda tidak memiliki akses ke halaman ini.</h2>

            <p class="error-message">
                Maaf, Anda tidak memiliki izin untuk mengakses halaman ini. Silakan klik <a href="{{ $returnUrl }}" style="color: var(--primary-color); font-weight: 800; text-decoration:none;">Kembali</a>.
            </p>
        </div>
    </div>
</body>
</html>

