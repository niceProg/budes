<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Verifikasi Login Pegawai - {{ config('app.name') }}</title>
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
        .tf-container { width: 100%; max-width: 460px; padding: 32px 20px; }
        .tf-title { font-size: 1.8rem; font-weight: 800; color: #111827; margin-bottom: 4px; }
        .tf-subtitle { font-size: 0.9rem; color: #6b7280; margin-bottom: 24px; }
        .tf-panel { background: #ffffff; border-radius: 18px; border: 1px solid #e5e7eb; padding: 20px 22px 18px; box-shadow: 0 10px 30px rgba(15,23,42,0.08); }
        .tf-label { font-size: 0.85rem; color: #6b7280; margin-bottom: 8px; }
        .tf-email { font-size: 0.9rem; color: #111827; font-weight: 600; }
        .tf-inputs { display: flex; gap: 8px; margin: 18px 0 16px; width: 100%; justify-content: center; }
        .tf-otp {
            flex: 1 1 0; min-width: 0; max-width: 56px; box-sizing: border-box; height: 52px; border-radius: 10px; border: 1px solid #d1d5db; background: #ffffff;
            color: #111827; text-align: center; font-size: 1.4rem; font-weight: 600; outline: none;
        }
        .tf-otp:focus { border-color: #eab308; box-shadow: 0 0 0 1px #eab308; }
        .tf-helper { font-size: 0.78rem; color: #6b7280; margin-bottom: 14px; }
        .tf-error { font-size: 0.8rem; color: #b91c1c; margin-bottom: 10px; }
        .tf-button {
            width: 100%; border: none; border-radius: 999px; padding: 10px 0;
            background: linear-gradient(135deg, #eab308, #b45309); color: #020617; font-weight: 700;
            font-size: 0.9rem; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px; min-height: 44px;
        }
        .tf-button:hover { filter: brightness(1.05); }
        .tf-back {
            position: fixed; top: 18px; left: 20px; display: inline-flex; align-items: center; gap: 6px;
            font-size: 0.85rem; color: #4b5563; text-decoration: none; padding: 6px 10px;
            border-radius: 999px; background: rgba(255,255,255,0.9); border: 1px solid #e5e7eb;
        }
        .tf-back:hover { background: #f3f4f6; }

        .d-none { display: none !important; }
        .loading { opacity: 0.7; cursor: not-allowed; pointer-events: none; }
        .spinner-border-sm {
            width: 1rem; height: 1rem; border: 2px solid currentColor; border-right-color: transparent;
            border-radius: 50%; display: inline-block; animation: spinner-border .75s linear infinite;
        }
        @keyframes spinner-border { to { transform: rotate(360deg); } }

        html[data-skin="dark"] body { background:#020617; color:#e5e7eb; }
        html[data-skin="dark"] .bg-pattern { opacity:.03; }
        html[data-skin="dark"] .tf-panel { background:#020617; border-color:#1f2937; box-shadow:0 20px 40px rgba(0,0,0,.7); }
        html[data-skin="dark"] .tf-title { color:#ffffff; }
        html[data-skin="dark"] .tf-subtitle, html[data-skin="dark"] .tf-label, html[data-skin="dark"] .tf-helper { color:#e5e7eb; }
        html[data-skin="dark"] .tf-email { color:#ffffff; }
        html[data-skin="dark"] .tf-otp { background:#020617; border-color:#374151; color:#fbbf24; }
        html[data-skin="dark"] .tf-otp:focus { border-color:#fbbf24; box-shadow:0 0 0 1px #fbbf24; }
        html[data-skin="dark"] .tf-button { background:linear-gradient(135deg,#facc15,#b45309); color:#020617; }
        html[data-skin="dark"] .tf-back { background:rgba(15,23,42,.9); border-color:#1f2937; color:#e5e7eb; }
        html[data-skin="dark"] .tf-back:hover { background:#020617; }
        .tf-lockout {
            background: #fef2f2; border: 1px solid #fecaca; border-radius: 12px;
            padding: 14px 16px; margin-bottom: 14px; font-size: 0.85rem; color: #991b1b; line-height: 1.5;
        }
        .tf-lockout strong { display: block; margin-bottom: 4px; font-size: 0.9rem; }
        .tf-lockout-timer { font-size: 1.1rem; font-weight: 800; color: #b91c1c; }
        .tf-attempts { font-size: 0.75rem; color: #9ca3af; margin-bottom: 10px; }
        .tf-attempts.warn { color: #d97706; font-weight: 600; }
        .tf-attempts.danger { color: #dc2626; font-weight: 700; }
        html[data-skin="dark"] .tf-lockout { background:#1f0a0a; border-color:#7f1d1d; color:#fca5a5; }
        html[data-skin="dark"] .tf-lockout strong { color:#fca5a5; }
        html[data-skin="dark"] .tf-lockout-timer { color:#f87171; }
        html[data-skin="dark"] .tf-attempts { color:#6b7280; }
        html[data-skin="dark"] .tf-attempts.warn { color:#fbbf24; }
        html[data-skin="dark"] .tf-attempts.danger { color:#f87171; }
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
    <a href="{{ route('admin.login.form') }}" class="tf-back"><span>&larr;</span><span>Kembali</span></a>

    <div class="tf-container">
        <div class="tf-title">Verifikasi Login Pegawai</div>
        <div class="tf-subtitle">Masukkan kode 6 digit dari aplikasi authenticator Anda untuk menyelesaikan login.</div>

        <div class="tf-panel">
            <div class="tf-label">Akun Pegawai</div>
            <div class="tf-email">{{ $username }}</div>

            @if ($errors->has('code'))
                <div class="tf-error">{{ $errors->first('code') }}</div>
            @endif

            @if(!empty($isLockedOut))
                <div class="tf-lockout">
                    <strong>🔒 Akses sementara diblokir</strong>
                    Terlalu banyak percobaan kode yang salah. Silakan tunggu sebelum mencoba kembali.<br>
                    Sisa waktu tunggu: <span class="tf-lockout-timer" id="lockoutTimer">--:--</span>
                </div>
            @elseif(!empty($attemptsLeft) && $attemptsLeft <= 3)
                @php $attemptClass = $attemptsLeft <= 1 ? 'danger' : 'warn'; @endphp
                <div class="tf-attempts {{ $attemptClass }}">
                    ⚠ Sisa percobaan: {{ $attemptsLeft }} dari 10
                </div>
            @endif

            <form method="POST" action="{{ route('admin.twofactor.totp.verify') }}" id="twofactor-totp-form" data-no-page-loader>
                @csrf
                <input type="hidden" name="code" id="code">

                <div class="tf-inputs">
                    @for ($i = 0; $i < 6; $i++)
                        <input type="text" maxlength="1" inputmode="numeric" class="tf-otp" data-index="{{ $i }}"
                               {{ !empty($isLockedOut) ? 'disabled' : '' }}>
                    @endfor
                </div>

                <div class="tf-helper">Pastikan waktu di HP Anda sudah sinkron otomatis. Maksimal 10 kali percobaan, lalu tunggu 30 menit.</div>

                <button type="submit" class="tf-button" id="btnVerifyTotp" {{ !empty($isLockedOut) ? 'disabled style="opacity:.5;cursor:not-allowed;"' : '' }}>
                    <span class="btn-text">Verifikasi</span>
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
            const form = document.getElementById('twofactor-totp-form');
            const btnVerify = document.getElementById('btnVerifyTotp');

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
                input.addEventListener('paste', (e) => {
                    const text = (e.clipboardData || window.clipboardData).getData('text') || '';
                    const digits = text.replace(/[^0-9]/g, '').slice(0, inputs.length);
                    if (!digits) return;
                    e.preventDefault();
                    digits.split('').forEach((d, i) => { if (inputs[i]) inputs[i].value = d; });
                    if (digits.length < inputs.length) inputs[digits.length].focus();
                    else inputs[inputs.length - 1].focus();
                    syncCode();
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

    @if(!empty($isLockedOut) && !empty($lockoutSeconds))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let remaining = {{ (int) $lockoutSeconds }};
            const timerEl = document.getElementById('lockoutTimer');
            if (!timerEl) return;
            function fmt(s) {
                const m = Math.floor(s / 60);
                const sec = s % 60;
                return String(m).padStart(2, '0') + ':' + String(sec).padStart(2, '0');
            }
            timerEl.textContent = fmt(remaining);
            const interval = setInterval(function() {
                remaining--;
                if (remaining <= 0) {
                    clearInterval(interval);
                    window.location.reload();
                } else {
                    timerEl.textContent = fmt(remaining);
                }
            }, 1000);
        });
    </script>
    @endif
</body>
</html>
