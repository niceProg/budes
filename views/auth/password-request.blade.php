<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Lupa Password | {{ config('app.name') }} DPR RI</title>



    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('theme/admin-dashbyte/dist/assets/css/style.min.css') }}">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <link rel="shortcut icon" href="{{ asset('theme/admin-dashbyte/dist/assets/img/favicon.ico') }}" type="image/x-icon">


    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f8fafc;
        }
        /* Dark mode: sinkron dengan halaman login & register */
        html[data-skin="dark"] body {
            background-color: #020617;
            color: #e5e7eb;
        }
        .auth-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
        }
        .auth-card {
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 15px 40px rgba(15,23,42,0.08);
            padding: 2rem 2.5rem;
            max-width: 480px;
            width: 100%;
        }
        html[data-skin="dark"] .auth-card {
            background: #0f172a;
            box-shadow: 0 15px 40px rgba(0,0,0,0.7);
            border: 1px solid #1f2937;
        }
        .auth-title {
            font-weight: 800;
            font-size: 1.5rem;
            color: #0f172a;
            margin-bottom: 0.25rem;
        }
        html[data-skin="dark"] .auth-title { color: #e5e7eb; }
        .auth-subtitle {
            font-size: 0.9rem;
            color: #64748b;
            margin-bottom: 1.5rem;
        }
        html[data-skin="dark"] .auth-subtitle { color: #9ca3af; }
        .btn-primary-gold {
            background: linear-gradient(135deg, #c5a059 0%, #8e6d2f 100%);
            border: none;
            padding: 0.8rem;
            border-radius: 10px;
            font-weight: 700;
            color: #ffffff;
            width: 100%;
        }
        .g-recaptcha {
            transform-origin: left top;
        }
        /* reCAPTCHA (304px) bisa melebihi kartu sempit di mobile -> kecilkan proporsional */
        @media (max-width: 400px) {
            .auth-card { padding: 1.5rem 1.25rem; }
            .g-recaptcha {
                transform: scale(0.85);
            }
            /* kompensasi tinggi yang berkurang akibat scale */
            .g-recaptcha > div { margin-bottom: -12px; }
        }
        /* Input dark theme seperti register2 */
        html[data-skin="dark"] .form-control {
            background: #1e293b !important;
            border-color: #374151 !important;
            color: #ffffff !important;
        }
        html[data-skin="dark"] .form-control:focus {
            background: #0f172a !important;
        }
        html[data-skin="dark"] .form-control::placeholder {
            color: #9ca3af !important;
        }
        .reset-opt {
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 12px 14px;
            display: flex;
            gap: 10px;
            align-items: flex-start;
            cursor: pointer;
            transition: border-color .2s ease, box-shadow .2s ease, background .2s ease;
        }
        .reset-opt:hover {
            border-color: #c5a059;
            box-shadow: 0 0 0 1px rgba(197, 160, 89, 0.35);
        }
        .reset-opt.selected {
            border-color: #8e6d2f;
            background: rgba(197, 160, 89, 0.08);
            box-shadow: 0 0 0 2px rgba(142, 109, 47, 0.15);
        }
        .reset-opt + .reset-opt { margin-top: 10px; }
        .reset-opt i {
            font-size: 1.2rem;
            color: #8e6d2f;
            margin-top: 2px;
            flex-shrink: 0;
        }
        .reset-opt-title { font-weight: 700; font-size: 0.9rem; color: #0f172a; }
        .reset-opt-desc { font-size: 0.8rem; color: #64748b; margin-top: 2px; line-height: 1.35; }
        html[data-skin="dark"] .reset-opt {
            border-color: #334155;
            background: rgba(30, 41, 59, 0.35);
        }
        html[data-skin="dark"] .reset-opt:hover { border-color: #c5a059; }
        html[data-skin="dark"] .reset-opt.selected {
            border-color: #c5a059;
            background: rgba(197, 160, 89, 0.12);
        }
        html[data-skin="dark"] .reset-opt-title { color: #e5e7eb; }
        html[data-skin="dark"] .reset-opt-desc { color: #94a3b8; }
    </style>
    <script>
        // Terapkan mode dark/light dari localStorage (sama dengan login)
        (function() {
            try {
                var mode = localStorage.getItem('skin-mode');
                if (mode === 'dark') {
                    document.documentElement.setAttribute('data-skin','dark');
                }
            } catch(e) {}
        })();
    </script>
</head>
<body>
    @include('partials.global_page_loader')
<div class="auth-wrapper">
    <div class="auth-card">
        <h1 class="auth-title">Lupa Password</h1>
        <p class="auth-subtitle">Untuk akun admin, peserta, atau pembimbing (guru). Pilih kirim link ke email atau verifikasi lewat aplikasi authenticator jika sudah diaktifkan di profile.</p>

        @if (session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger" role="alert">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}" id="formForgotPassword">
            @csrf
            <input type="hidden" name="reset_method" id="reset_method" value="{{ old('reset_method', 'email') }}">

            <div class="mb-3">
                <label class="form-label">Email terdaftar</label>
                <input type="email"
                       name="email"
                       class="form-control @error('email') is-invalid @enderror"
                       value="{{ old('email') }}"
                       required
                       autofocus
                       placeholder="email terdaftar Anda">
                @error('email')
                    <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                @enderror
            </div>

            <div class="mb-2">
                <label class="form-label small text-muted mb-2 d-block">Cara atur ulang password</label>
                @error('reset_method')
                    <div class="text-danger small mb-2">{{ $message }}</div>
                @enderror
                <div class="reset-opt {{ old('reset_method', 'email') === 'email' ? 'selected' : '' }}" role="button" tabindex="0" data-method="email" onclick="pickResetMethod('email')">
                    <i class="ri-mail-send-line"></i>
                    <div>
                        <div class="reset-opt-title">Kirim link ke email</div>
                        <div class="reset-opt-desc">Kami mengirim link aman ke inbox Anda. Link berlaku 30 menit.</div>
                    </div>
                </div>
                <div class="reset-opt {{ old('reset_method') === 'totp' ? 'selected' : '' }}" role="button" tabindex="0" data-method="totp" onclick="pickResetMethod('totp')">
                    <i class="ri-shield-keyhole-line"></i>
                    <div>
                        <div class="reset-opt-title">Aplikasi authenticator</div>
                        <div class="reset-opt-desc">Masukkan kode 6 digit dari aplikasi authenticator (harus sudah diaktifkan di profile). Setelah benar, langsung ke halaman buat password baru.</div>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <div class="g-recaptcha" data-sitekey="{{ env('RECAPTCHA_SITE_KEY') }}"></div>
                @error('g-recaptcha-response')
                    <span class="text-danger small d-block mt-1">{{ $message }}</span>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary-gold" id="btnSubmitForgot">
                Lanjutkan
            </button>

            <p class="text-center mt-3 mb-0">
                <a href="{{ route('login') }}" class="small text-muted">Kembali ke halaman login</a>
            </p>
        </form>
    </div>
</div>
<script>
    function pickResetMethod(v) {
        document.getElementById('reset_method').value = v;
        document.querySelectorAll('.reset-opt').forEach(function (el) {
            el.classList.toggle('selected', el.getAttribute('data-method') === v);
        });
        var btn = document.getElementById('btnSubmitForgot');
        if (btn) {
            btn.textContent = v === 'totp' ? 'Lanjut ke verifikasi authenticator' : 'Kirim link ke email';
        }
    }
    (function initResetMethod() {
        var initial = document.getElementById('reset_method');
        if (initial && initial.value) {
            pickResetMethod(initial.value);
        } else {
            pickResetMethod('email');
        }
    })();
    document.querySelectorAll('.reset-opt').forEach(function (el) {
        el.addEventListener('keydown', function (e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                pickResetMethod(el.getAttribute('data-method'));
            }
        });
    });
</script>
</body>
</html>

