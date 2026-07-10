<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pilih Verifikasi - {{ config('app.name') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="{{ asset('theme/admin-dashbyte/dist/assets/img/favicon.ico') }}" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body { margin:0; min-height:100vh; background:#f9fafb; color:#111827; font-family:'Plus Jakarta Sans',system-ui,-apple-system,BlinkMacSystemFont,sans-serif; display:flex; align-items:center; justify-content:center; }
        .bg-pattern { position:fixed; inset:0; background-image:url("{{ asset('theme/admin-dashbyte/dist/assets/img/batiknew.png') }}"); background-size:cover; opacity:.05; z-index:-1; pointer-events:none; }
        .tf-container { width:100%; max-width:560px; padding:32px 20px; }
        .tf-title { font-size:1.8rem; font-weight:800; color:#111827; margin-bottom:4px; }
        .tf-subtitle { font-size:.9rem; color:#6b7280; margin-bottom:24px; }
        .panel { background:#fff; border-radius:18px; border:1px solid #e5e7eb; padding:20px 22px 18px; box-shadow:0 10px 30px rgba(15,23,42,.08); }
        .opt { border:2px solid #dbdcdf; border-radius:14px; padding:14px 14px; display:flex; gap:12px; align-items:flex-start; cursor:pointer; transition: all .2s ease; }
        .opt:hover { border-color:#eab308; box-shadow:0 0 0 1px #eab308; }
        .opt.selected { border-color:#eab308; box-shadow:0 0 0 2px rgba(234,179,8,.25); background: rgba(234,179,8,.06); }
        .opt.selected .opt-title { color:#111827; }
        .opt + .opt { margin-top:12px; }
        .opt i { font-size:1.25rem; color:#b45309; margin-top:2px; }
        .opt-title { font-weight:800; }
        .opt-desc { font-size:.85rem; color:#6b7280; margin-top:2px; }
        .email { font-weight:700; color:#111827; }
        .err { font-size:.85rem; color:#b91c1c; margin-bottom:10px; }
        .btn { width:100%; border:none; border-radius:999px; padding:10px 0; background:linear-gradient(135deg,#eab308,#b45309); color:#020617; font-weight:800; font-size:.95rem; cursor:pointer; min-height:44px; margin-top:14px; }
        .back { position:fixed; top:18px; left:20px; display:inline-flex; align-items:center; gap:6px; font-size:.85rem; color:#4b5563; text-decoration:none; padding:6px 10px; border-radius:999px; background:rgba(255,255,255,.9); border:1px solid #e5e7eb; }
        .back:hover { background:#f3f4f6; }

        html[data-skin="dark"] body { background:#020617; color:#e5e7eb; }
        html[data-skin="dark"] .bg-pattern { opacity:.03; }
        html[data-skin="dark"] .panel { background:#020617; border-color:#1f2937; box-shadow:0 20px 40px rgba(0,0,0,.7); }
        html[data-skin="dark"] .tf-title, html[data-skin="dark"] .email { color:#fff; }
        html[data-skin="dark"] .tf-subtitle, html[data-skin="dark"] .opt-desc { color:#e5e7eb; }
        html[data-skin="dark"] .opt { border-color:#1f2937; background:rgba(15,23,42,.3); }
        html[data-skin="dark"] .opt:hover { border-color:#fbbf24; box-shadow:0 0 0 1px #fbbf24; }
        html[data-skin="dark"] .opt.selected { border-color:#fbbf24; box-shadow:0 0 0 2px rgba(251,191,36,.25); background: rgba(251,191,36,.08); }
        html[data-skin="dark"] .opt.selected .opt-title { color:#ffffff; }
        html[data-skin="dark"] .back { background:rgba(15,23,42,.9); border-color:#1f2937; color:#e5e7eb; }
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
    <a href="{{ route('login') }}" class="back"><span>&larr;</span><span>Kembali</span></a>

    <div class="tf-container">
        <div class="tf-title">Pilih metode verifikasi</div>
        <div class="tf-subtitle">Untuk keamanan akun, selesaikan login dengan salah satu metode berikut.</div>

        <div class="panel">
            <div style="margin-bottom:10px; font-size:.85rem; color:#6b7280;">Akun</div>
            <div class="email" style="margin-bottom:14px;">{{ $email }}</div>

            @if ($errors->has('method'))
                <div class="err">{{ $errors->first('method') }}</div>
            @endif

            <form method="POST" action="{{ route('user.twofactor.method.choose') }}" id="chooseForm" data-no-page-loader>
                @csrf
                <input type="hidden" name="method" id="method">

                <div class="opt" role="button" tabindex="0" aria-selected="false" data-method="email" onclick="pick('email')">
                    <i class="ri-mail-send-line"></i>
                    <div>
                        <div class="opt-title">Kirim kode ke Email</div>
                        <div class="opt-desc">Kami kirim kode 6 digit ke email Anda. Berlaku 10 menit.</div>
                    </div>
                </div>

                @if(!empty($totpEnabled))
                    <div class="opt" role="button" tabindex="0" aria-selected="false" data-method="totp" onclick="pick('totp')">
                        <i class="ri-shield-keyhole-line"></i>
                        <div>
                            <div class="opt-title">Aplikasi authenticator</div>
                            <div class="opt-desc">Masukkan kode 6 digit dari aplikasi authenticator Anda (misalnya Google Authenticator, Microsoft Authenticator, Authy, dan sejenisnya).</div>
                        </div>
                    </div>
                @endif

                <button type="submit" class="btn">Lanjutkan</button>
            </form>
        </div>
    </div>

    <script>
        function pick(v) {
            document.getElementById('method').value = v;
            document.querySelectorAll('.opt').forEach(function(el) {
                el.classList.remove('selected');
                el.setAttribute('aria-selected', 'false');
            });
            var selected = document.querySelector('.opt[data-method="' + v + '"]');
            if (selected) {
                selected.classList.add('selected');
                selected.setAttribute('aria-selected', 'true');
            }
        }

        // Keyboard support + make first click feel responsive
        document.querySelectorAll('.opt').forEach(function(el) {
            el.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    var v = el.getAttribute('data-method');
                    if (v) pick(v);
                }
            });
        });

        document.getElementById('chooseForm').addEventListener('submit', function(e) {
            var v = document.getElementById('method').value;
            if (!v) {
                e.preventDefault();
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Pilih metode verifikasi',
                        text: 'Silakan pilih salah satu metode verifikasi.',
                        confirmButtonColor: '#b08d48'
                    });
                } else {
                    alert('Pilih salah satu metode verifikasi.');
                }
                return;
            }

            // valid -> show global loader manually
            var overlay = document.getElementById('globalPageLoadingOverlay');
            if (overlay) {
                overlay.style.display = 'flex';
                overlay.style.opacity = '1';
                overlay.classList.add('show');
                overlay.setAttribute('aria-hidden', 'false');
                void overlay.offsetHeight;
            }
        });
    </script>

    @if ($errors->has('method'))
        <script>
            (function() {
                if (typeof Swal === 'undefined') return;
                Swal.fire({
                    icon: 'error',
                    title: 'Tidak bisa dilanjutkan',
                    text: @json($errors->first('method')),
                    confirmButtonColor: '#b08d48'
                });
            })();
        </script>
    @endif
</body>
</html>

