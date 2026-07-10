<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Verifikasi Authenticator - Lupa Password | {{ config('app.name') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="{{ asset('theme/admin-dashbyte/dist/assets/img/favicon.ico') }}" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            margin: 0;
            min-height: 100vh;
            background: #f9fafb;
            color: #111827;
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, BlinkMacSystemFont, sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .bg-pattern {
            position: fixed;
            inset: 0;
            background-image: url("{{ asset('theme/admin-dashbyte/dist/assets/img/batiknew.png') }}");
            background-size: cover;
            opacity: 0.05;
            z-index: -1;
            pointer-events: none;
        }
        .tf-container {
            width: 100%;
            max-width: 460px;
            padding: 32px 20px;
        }
        .tf-title {
            font-size: 1.8rem;
            font-weight: 800;
            color: #111827;
            margin-bottom: 4px;
        }
        .tf-subtitle {
            font-size: 0.9rem;
            color: #6b7280;
            margin-bottom: 24px;
        }
        .tf-panel {
            background: #ffffff;
            border-radius: 18px;
            border: 1px solid #e5e7eb;
            padding: 20px 22px 18px;
            box-shadow: 0 10px 30px rgba(15,23,42,0.08);
        }
        .tf-label {
            font-size: 0.85rem;
            color: #6b7280;
            margin-bottom: 8px;
        }
        .tf-email {
            font-size: 0.9rem;
            color: #111827;
            font-weight: 600;
        }
        .tf-inputs {
            display: flex;
            gap: 8px;
            margin: 18px 0 16px;
            width: 100%;
            justify-content: center;
        }
        .tf-otp {
            flex: 1 1 0;
            min-width: 0;
            max-width: 56px;
            box-sizing: border-box;
            height: 52px;
            border-radius: 10px;
            border: 1px solid #d1d5db;
            background: #ffffff;
            color: #111827;
            text-align: center;
            font-size: 1.4rem;
            font-weight: 600;
            outline: none;
        }
        .tf-otp:focus {
            border-color: #eab308;
            box-shadow: 0 0 0 1px #eab308;
        }
        .tf-helper {
            font-size: 0.78rem;
            color: #6b7280;
            margin-bottom: 14px;
        }
        .tf-error {
            font-size: 0.8rem;
            color: #b91c1c;
            margin-bottom: 10px;
        }
        .tf-button {
            width: 100%;
            border: none;
            border-radius: 999px;
            padding: 10px 0;
            background: linear-gradient(135deg, #eab308, #b45309);
            color: #020617;
            font-weight: 700;
            font-size: 0.9rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            min-height: 44px;
        }
        .tf-button:hover {
            filter: brightness(1.05);
        }
        .tf-back {
            position: fixed;
            top: 18px;
            left: 20px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 0.85rem;
            color: #4b5563;
            text-decoration: none;
            padding: 6px 10px;
            border-radius: 999px;
            background: rgba(255,255,255,0.9);
            border: 1px solid #e5e7eb;
        }
        .tf-back:hover {
            background: #f3f4f6;
        }

        .d-none { display: none !important; }
        .loading {
            opacity: 0.7;
            cursor: not-allowed;
            pointer-events: none;
        }
        .spinner-border-sm {
            width: 1rem;
            height: 1rem;
            border: 2px solid currentColor;
            border-right-color: transparent;
            border-radius: 50%;
            display: inline-block;
            animation: spinner-border .75s linear infinite;
        }
        @keyframes spinner-border { to { transform: rotate(360deg); } }

        html[data-skin="dark"] body { background:#020617; color:#e5e7eb; }
        html[data-skin="dark"] .bg-pattern { opacity:.03; }
        html[data-skin="dark"] .tf-panel { background:#020617; border-color:#1f2937; box-shadow:0 20px 40px rgba(0,0,0,.7); }
        html[data-skin="dark"] .tf-title { color:#ffffff; }
        html[data-skin="dark"] .tf-subtitle,
        html[data-skin="dark"] .tf-label,
        html[data-skin="dark"] .tf-helper { color:#e5e7eb; }
        html[data-skin="dark"] .tf-email { color:#ffffff; }
        html[data-skin="dark"] .tf-otp { background:#020617; border-color:#374151; color:#fbbf24; }
        html[data-skin="dark"] .tf-otp:focus { border-color:#fbbf24; box-shadow:0 0 0 1px #fbbf24; }
        html[data-skin="dark"] .tf-button { background:linear-gradient(135deg,#facc15,#b45309); color:#020617; }
        html[data-skin="dark"] .tf-back { background:rgba(15,23,42,.9); border-color:#1f2937; color:#e5e7eb; }
        html[data-skin="dark"] .tf-back:hover { background:#020617; }
    </style>
    <script>
        (function() {
            try { if (localStorage.getItem('skin-mode') === 'dark') document.documentElement.setAttribute('data-skin','dark'); } catch(e) {}
        })();
    </script>
</head>
<body>
    @include('partials.global_page_loader')
    <div class="bg-pattern" aria-hidden="true"></div>
    <a href="{{ route('password.request') }}" class="tf-back"><span>&larr;</span><span>Kembali</span></a>

    <div class="tf-container">
        <div class="tf-title">Atur ulang password</div>
        <div class="tf-subtitle">Masukkan kode 6 digit dari aplikasi authenticator Anda. Jika benar, Anda akan langsung ke halaman buat password baru.</div>

        <div class="tf-panel">
            <div class="tf-label">Akun</div>
            <div class="tf-email">{{ $email }}</div>

            @if ($errors->has('code'))
                <div class="tf-error">{{ $errors->first('code') }}</div>
            @endif

            <form method="POST" action="{{ route('password.reset.totp.verify') }}" id="password-reset-totp-form" data-no-page-loader>
                @csrf
                <input type="hidden" name="code" id="code">

                <div class="tf-inputs">
                    @for ($i = 0; $i < 6; $i++)
                        <input type="text" maxlength="1" inputmode="numeric" class="tf-otp" data-index="{{ $i }}" autocomplete="one-time-code">
                    @endfor
                </div>

                <div class="tf-helper">Pastikan waktu perangkat sudah sinkron otomatis. Authenticator harus sudah diaktifkan di profile untuk akun ini.</div>

                <button type="submit" class="tf-button" id="btnVerifyTotpReset">
                    <span class="btn-text">Lanjutkan</span>
                    <span class="btn-loading d-none">
                        <span class="spinner-border-sm" role="status" aria-hidden="true"></span>
                        <span class="ms-2">Memproses...</span>
                    </span>
                </button>
            </form>
        </div>
    </div>

    <script>
        (function() {
            const inputs = Array.from(document.querySelectorAll('.tf-otp'));
            const hidden = document.getElementById('code');
            const form = document.getElementById('password-reset-totp-form');
            const btnVerify = document.getElementById('btnVerifyTotpReset');

            inputs.forEach((input, idx) => {
                input.addEventListener('input', (e) => {
                    const value = e.target.value.replace(/[^0-9]/g, '');
                    e.target.value = value;
                    if (value && idx < inputs.length - 1) {
                        inputs[idx + 1].focus();
                    }
                    syncCode();
                });
                input.addEventListener('keydown', (e) => {
                    if (e.key === 'Backspace' && !e.target.value && idx > 0) {
                        inputs[idx - 1].focus();
                    }
                });
            });

            function syncCode() {
                hidden.value = inputs.map(i => i.value || '').join('');
            }

            form.addEventListener('submit', (e) => {
                syncCode();
                if (hidden.value.length !== 6) {
                    e.preventDefault();
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Kode belum lengkap',
                            text: 'Silakan masukkan 6 digit kode.',
                            confirmButtonColor: '#b08d48'
                        });
                    } else {
                        alert('Silakan masukkan 6 digit kode.');
                    }
                } else {
                    btnVerify.classList.add('loading');
                    btnVerify.querySelector('.btn-text').classList.add('d-none');
                    btnVerify.querySelector('.btn-loading').classList.remove('d-none');

                    var overlay = document.getElementById('globalPageLoadingOverlay');
                    if (overlay) {
                        overlay.style.display = 'flex';
                        overlay.style.opacity = '1';
                        overlay.classList.add('show');
                        overlay.setAttribute('aria-hidden', 'false');
                        void overlay.offsetHeight;
                    }
                }
            });

            if (inputs.length) {
                inputs[0].focus();
            }
        })();
    </script>

    @if ($errors->has('code'))
        <script>
            (function() {
                if (typeof Swal === 'undefined') return;
                Swal.fire({
                    icon: 'error',
                    title: 'Verifikasi gagal',
                    text: @json($errors->first('code')),
                    confirmButtonColor: '#b08d48'
                });
            })();
        </script>
    @endif
</body>
</html>
