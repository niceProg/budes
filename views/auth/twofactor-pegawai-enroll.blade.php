<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Aktivasi MFA Pegawai - {{ config('app.name') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="{{ asset('theme/admin-dashbyte/dist/assets/img/favicon.ico') }}" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            margin: 0; min-height: 100vh; background: #f9fafb; color: #111827;
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, BlinkMacSystemFont, sans-serif;
            display: flex; align-items: center; justify-content: center;
        }
        .bg-pattern {
            position: fixed; inset: 0;
            background-image: url("{{ asset('theme/admin-dashbyte/dist/assets/img/batiknew.png') }}");
            background-size: cover; opacity: 0.05; z-index: -1; pointer-events: none;
        }
        .tf-container { width: 100%; max-width: 480px; padding: 32px 20px; }
        .tf-title { font-size: 1.6rem; font-weight: 800; color: #111827; margin-bottom: 4px; }
        .tf-subtitle { font-size: 0.9rem; color: #6b7280; margin-bottom: 20px; }
        .tf-panel { background: #ffffff; border-radius: 18px; border: 1px solid #e5e7eb; padding: 22px; box-shadow: 0 10px 30px rgba(15,23,42,0.08); }
        .tf-label { font-size: 0.85rem; color: #6b7280; margin-bottom: 8px; }
        .tf-email { font-size: 0.9rem; color: #111827; font-weight: 600; margin-bottom: 14px; }
        .tf-notice {
            background: #fffbeb; border: 1px solid #fde68a; border-radius: 12px;
            padding: 12px 14px; font-size: 0.82rem; color: #92400e; line-height: 1.5; margin-bottom: 16px;
        }
        .step-title { font-weight: 800; font-size: .95rem; color: #111827; margin: 14px 0 6px; }
        .step-text { font-size: .82rem; color: #6b7280; line-height: 1.5; margin-bottom: 10px; }
        .qr-wrap { display: flex; justify-content: center; margin-bottom: 12px; }
        .qr-box { background:#fff; padding:12px; border-radius:14px; border:1px solid #e5e7eb; }
        .key-box {
            border: 1px dashed #94a3b8; border-radius: 10px; background: #f8fafc; padding: .55rem .7rem;
            display: flex; align-items: center; justify-content: space-between; gap: .5rem; margin-bottom: 14px;
        }
        .key-text { font-family: ui-monospace, Menlo, Consolas, monospace; font-size: .82rem; font-weight: 700; color:#111827; word-break: break-all; }
        .key-copy { border:1px solid #d1d5db; background:#fff; border-radius:8px; padding:4px 10px; font-size:.75rem; cursor:pointer; }
        .tf-inputs { display: flex; gap: 8px; margin: 8px 0 16px; width: 100%; justify-content: center; }
        .tf-otp {
            flex: 1 1 0; min-width: 0; max-width: 56px; box-sizing: border-box; height: 52px; border-radius: 10px; border: 1px solid #d1d5db; background: #ffffff;
            color: #111827; text-align: center; font-size: 1.4rem; font-weight: 600; outline: none;
        }
        .tf-otp:focus { border-color: #eab308; box-shadow: 0 0 0 1px #eab308; }
        .tf-error { font-size: 0.8rem; color: #b91c1c; margin-bottom: 10px; }
        .tf-button {
            width: 100%; border: none; border-radius: 999px; padding: 11px 0;
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
        html[data-skin="dark"] .tf-title, html[data-skin="dark"] .tf-email, html[data-skin="dark"] .step-title { color:#ffffff; }
        html[data-skin="dark"] .tf-subtitle, html[data-skin="dark"] .tf-label, html[data-skin="dark"] .step-text { color:#cbd5e1; }
        html[data-skin="dark"] .tf-otp { background:#020617; border-color:#374151; color:#fbbf24; }
        html[data-skin="dark"] .key-box { background:#111827; border-color:#4b5563; }
        html[data-skin="dark"] .key-text { color:#f8fafc; }
        html[data-skin="dark"] .tf-back { background:rgba(15,23,42,.9); border-color:#1f2937; color:#e5e7eb; }
    </style>
    <script>
        (function() { try { if (localStorage.getItem('skin-mode') === 'dark') document.documentElement.setAttribute('data-skin','dark'); } catch(e) {} })();
    </script>
</head>
<body>
    @include('partials.global_page_loader')
    <div class="bg-pattern" aria-hidden="true"></div>
    <a href="{{ route('admin.login.form') }}" class="tf-back"><span>&larr;</span><span>Kembali</span></a>

    <div class="tf-container">
        <div class="tf-title">Aktivasi Authenticator Wajib</div>
        <div class="tf-subtitle">Administrator mewajibkan verifikasi dua langkah. Aktifkan aplikasi authenticator untuk melanjutkan login.</div>

        <div class="tf-panel">
            <div class="tf-label">Akun Pegawai</div>
            <div class="tf-email">{{ $username }}</div>

            <div class="tf-notice">
                <i class="ri-shield-keyhole-line"></i>
                MFA Authenticator kini diwajibkan untuk seluruh pengguna internal. Selesaikan aktivasi sekali, lalu Anda akan diminta kode 6 digit setiap login.
            </div>

            @if ($errors->has('code'))
                <div class="tf-error">{{ $errors->first('code') }}</div>
            @endif

            <div class="step-title">1) Pindai QR code</div>
            <div class="step-text">Buka aplikasi authenticator (Google Authenticator, Microsoft Authenticator, Authy, dsb), pilih tambah akun, lalu scan QR berikut.</div>

            @if(!empty($qrSvg))
                <div class="qr-wrap"><div class="qr-box">{!! $qrSvg !!}</div></div>
            @else
                <div class="tf-error">QR tidak dapat ditampilkan. Gunakan setup key manual di bawah.</div>
            @endif

            @if(!empty($setupKey))
                <div class="step-text" style="margin-bottom:6px;">Tidak bisa scan? Masukkan setup key manual:</div>
                <div class="key-box">
                    <span class="key-text" id="setupKey">{{ $setupKey }}</span>
                    <button type="button" class="key-copy" id="btnCopyKey">Salin</button>
                </div>
            @endif

            <div class="step-title">2) Masukkan kode 6 digit</div>
            <div class="step-text">Ketik kode yang muncul di aplikasi authenticator untuk menyelesaikan aktivasi.</div>

            <form method="POST" action="{{ route('admin.twofactor.enroll.confirm') }}" id="enroll-form" data-no-page-loader>
                @csrf
                <input type="hidden" name="code" id="code">
                <div class="tf-inputs">
                    @for ($i = 0; $i < 6; $i++)
                        <input type="text" maxlength="1" inputmode="numeric" class="tf-otp" data-index="{{ $i }}">
                    @endfor
                </div>

                <button type="submit" class="tf-button" id="btnConfirm">
                    <span class="btn-text">Aktifkan &amp; Lanjutkan</span>
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
            const form = document.getElementById('enroll-form');
            const btnConfirm = document.getElementById('btnConfirm');

            inputs.forEach((input, idx) => {
                input.addEventListener('input', (e) => {
                    const value = e.target.value.replace(/[^0-9]/g, '');
                    e.target.value = value;
                    if (value && idx < inputs.length - 1) inputs[idx + 1].focus();
                    syncCode();
                });
                input.addEventListener('keydown', (e) => {
                    if (e.key === 'Backspace' && !e.target.value && idx > 0) inputs[idx - 1].focus();
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

            function syncCode() { hidden.value = inputs.map(i => i.value || '').join(''); }

            form.addEventListener('submit', (e) => {
                syncCode();
                if (hidden.value.length !== 6) {
                    e.preventDefault();
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({ icon: 'warning', title: 'Kode belum lengkap', text: 'Silakan masukkan 6 digit kode.', confirmButtonColor: '#b08d48' });
                    } else { alert('Silakan masukkan 6 digit kode.'); }
                } else {
                    btnConfirm.classList.add('loading');
                    btnConfirm.querySelector('.btn-text').classList.add('d-none');
                    btnConfirm.querySelector('.btn-loading').classList.remove('d-none');
                    var overlay = document.getElementById('globalPageLoadingOverlay');
                    if (overlay) { overlay.style.display = 'flex'; overlay.style.opacity = '1'; overlay.classList.add('show'); overlay.setAttribute('aria-hidden', 'false'); void overlay.offsetHeight; }
                }
            });

            const copyBtn = document.getElementById('btnCopyKey');
            const keyEl = document.getElementById('setupKey');
            if (copyBtn && keyEl) {
                copyBtn.addEventListener('click', async function () {
                    try {
                        await navigator.clipboard.writeText(keyEl.textContent.trim());
                        copyBtn.textContent = 'Tersalin';
                        setTimeout(() => { copyBtn.textContent = 'Salin'; }, 1200);
                    } catch (e) {
                        const range = document.createRange();
                        range.selectNode(keyEl);
                        window.getSelection().removeAllRanges();
                        window.getSelection().addRange(range);
                        document.execCommand('copy');
                        window.getSelection().removeAllRanges();
                        copyBtn.textContent = 'Tersalin';
                        setTimeout(() => { copyBtn.textContent = 'Salin'; }, 1200);
                    }
                });
            }

            if (inputs.length) inputs[0].focus();
        })();
    </script>

    @if ($errors->has('code'))
        <script>
            (function() {
                if (typeof Swal === 'undefined') return;
                Swal.fire({ icon: 'error', title: 'Aktivasi gagal', text: @json($errors->first('code')), confirmButtonColor: '#b08d48' });
            })();
        </script>
    @endif
</body>
</html>
