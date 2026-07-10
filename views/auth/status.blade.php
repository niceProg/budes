<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cek Status Pendaftaran | {{ config('app.name') }} DPR RI</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    {{-- Remix Icon: dipakai oleh tombol toggle dark mode (ri-sun-fill / ri-moon-fill) di header bersama --}}
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    
    <!-- reCAPTCHA v2 -->
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        /* Reset box-sizing agar padding header (width:100% + padding 6%) tidak meluap melebihi viewport */
        *, *::before, *::after { box-sizing: border-box; }
        html, body { max-width: 100%; overflow-x: hidden; }

        :root {
            --primary-dark: #0f172a;
            --accent-gold: linear-gradient(135deg, #c5a059 0%, #8e6d2f 100%);
            --gold-solid: #b08d48;
            --text-main: #1e293b;
            --text-muted: #64748b;
            --white: #ffffff;
            --transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
            --success: #10b981;
            --danger: #ef4444;
            --warning: #f59e0b;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            margin: 0; padding: 0;
            background-color: #f8fafc;
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            line-height: 1.7;
        }

        /* Background Motif Batik */
        .bg-pattern {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('{{ asset("theme/admin-dashbyte/dist/assets/img/batiknew.png") }}'); 
            background-size: 400px;
            opacity: 0.05;
            z-index: -1;
            pointer-events: none;
        }

        header {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            padding: 0.8rem 6%;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
            border-bottom: 1px solid rgba(0,0,0,0.06);
            transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
        }

        .logo-container {
            display: flex;
            align-items: center;
            gap: -12px;
            text-decoration: none;
        }

        .logo-container img {
            height: 45px;
            width: auto;
        }

        .logo-text {
            font-weight: 800;
            font-size: 1.2rem;
            color: var(--primary-dark);
            letter-spacing: -0.5px;
            line-height: 1.2;
        }

        .logo-text span {
            display: block;
            font-size: 0.7rem;
            color: var(--gold-solid);
            text-transform: uppercase;
            letter-spacing: 1.5px;
        }

        .container {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 100px 20px 40px;
        }

        /* Main Card */
        .main-content {
            width: 100%;
            max-width: 550px;
            background: white;
            border-radius: 24px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.08);
            padding: 40px;
            position: relative;
            overflow: hidden;
        }

        .main-content::before {
            content: '';
            position: absolute;
            top: 0; left: 0; width: 100%; height: 5px;
            background: var(--accent-gold);
        }

        h2 {
            font-weight: 800;
            text-align: center;
            margin-bottom: 30px;
            color: var(--primary-dark);
            font-size: 1.8rem;
        }

        .form-group { margin-bottom: 20px; }
        
        .form-group label {
            display: block;
            font-weight: 700;
            font-size: 0.85rem;
            margin-bottom: 8px;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Information Styling */
        .info-box {
            background: #f1f5f9;
            padding: 12px 18px;
            border-radius: 12px;
            font-weight: 600;
            color: var(--primary-dark);
            border: 1px solid #e2e8f0;
        }

        .status-badge {
            padding: 15px;
            border-radius: 12px;
            text-align: center;
            font-weight: 800;
            font-size: 1.1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        /* Status Variants */
        .status-approved { background: #ecfdf5; color: var(--success); border: 1px solid #a7f3d0; }
        .status-rejected { background: #fef2f2; color: var(--danger); border: 1px solid #fecaca; }
        .status-in-process { background: #fffbeb; color: var(--warning); border: 1px solid #fde68a; }

        /* Input Styling */
        input[type="text"] {
            width: 100%;
            padding: 14px 18px;
            border: 1.5px solid #e2e8f0;
            border-radius: 12px;
            font-size: 1rem;
            transition: all 0.3s;
            box-sizing: border-box;
        }

        input[type="text"]:focus {
            outline: none;
            border-color: var(--gold-solid);
            box-shadow: 0 0 0 4px rgba(176, 141, 72, 0.1);
        }

        .btn-submit {
            background: var(--accent-gold);
            color: white;
            border: none;
            width: 100%;
            padding: 15px;
            border-radius: 12px;
            font-weight: 700;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 10px 20px rgba(142, 109, 47, 0.2);
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 30px rgba(142, 109, 47, 0.3);
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
            transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
            margin-top: 25px;
            margin-bottom: 20px;
        }

        .btn-back:hover {
            background: var(--accent-gold);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(142, 109, 47, 0.2);
        }

        /* Credentials Box */
        .creds-container {
            background: var(--primary-dark);
            color: white;
            padding: 20px;
            border-radius: 15px;
            margin-top: 20px;
        }

        .creds-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
            font-size: 0.9rem;
        }

        .creds-item span:first-child { color: rgba(255,255,255,0.5); }
        .creds-item span:last-child { color: var(--gold-solid); font-weight: 700; }

        /* === Dark mode: cek status === */
        html[data-skin="dark"] body { background: #020617; color: #ffffff; }
        html[data-skin="dark"] .bg-pattern { opacity: 0.03; }
        html[data-skin="dark"] header {
            background: rgba(15, 23, 42, 0.95) !important;
            border-color: #1f2937;
        }
        html[data-skin="dark"] .logo-text { color: #ffffff !important; }
        html[data-skin="dark"] .logo-text span { color: #fbbf24 !important; }
        html[data-skin="dark"] .main-content {
            background: #0f172a !important;
            border-color: #1f2937;
            box-shadow: 0 20px 40px rgba(0,0,0,0.5);
        }
        html[data-skin="dark"] h2,
        html[data-skin="dark"] .form-group label { color: #ffffff !important; }
        html[data-skin="dark"] .info-box {
            background: #1e293b !important;
            border-color: #374151 !important;
            color: #ffffff !important;
        }
        html[data-skin="dark"] .status-approved { background: #052e16 !important; color: #4ade80 !important; border-color: #166534 !important; }
        html[data-skin="dark"] .status-rejected { background: #450a0a !important; color: #f87171 !important; border-color: #991b1b !important; }
        html[data-skin="dark"] .status-in-process { background: #431407 !important; color: #fdba74 !important; border-color: #9a3412 !important; }
        html[data-skin="dark"] input[type="text"] {
            background: #1e293b !important;
            border-color: #374151 !important;
            color: #ffffff !important;
        }
        html[data-skin="dark"] input[type="text"]:focus {
            background: #0f172a !important;
        }
        html[data-skin="dark"] .btn-back {
            background: #1e293b !important;
            border-color: #fbbf24 !important;
            color: #fbbf24 !important;
        }
        html[data-skin="dark"] .btn-back:hover {
            background: #0f172a !important;
            color: #ffffff !important;
        }

    </style>

    {{-- Header & footer publik bersama dengan beranda/FAQ (CSS + skin toggle) --}}
    @include('auth.partials.public-chrome-head')
</head>
<body>
    @include('partials.global_page_loader')
    <div class="bg-pattern"></div>
    
    @include('auth.partials.public-header')

    <div class="container">
        <div class="main-content">
            <h2>Cek Status</h2>

            @if ($errors->any())
                <div style="color: #ef4444; background: #fef2f2; border: 1px solid #fecaca; padding: 12px; border-radius: 10px; margin-bottom: 16px;">
                    @foreach ($errors->all() as $error)
                        <div>- {{ $error }}</div>
                    @endforeach
                </div>
            @endif

            @if (isset($no_pendaftaran))
                <div class="form-group">
                    <label>No Pendaftaran</label>
                    <div class="info-box" style="font-size: 1.1rem; font-weight: 900; color: #000000; letter-spacing: 1px;">{{ $no_pendaftaran }}</div>
                </div>

                <div class="form-group">
                    <label>Nama Peserta</label>
                    <div class="info-box">{{ $nama }}</div>
                </div>

                <div class="form-group">
                    <label>Jenis Program</label>
                    <div class="info-box" style="background: linear-gradient(135deg, #e0e7ff 0%, #f0f4ff 100%); color: #3730a3; border: 1px solid #a5b4fc;">
                        <i class="fa-solid fa-bookmark" style="margin-right: 8px;"></i>
                        <strong>{{ ucfirst($kategori ?? 'Tidak Ditentukan') }}</strong>
                    </div>
                </div>

                <div class="form-group">
                    <label>Status Saat Ini</label>
                    <div class="status-badge 
                        {{ $status === 'Diterima' ? 'status-approved' : ($status === 'Ditolak' ? 'status-rejected' : 'status-in-process') }}">
                        @if($status === 'Diterima') <i class="fa-solid fa-circle-check"></i> 
                        @elseif($status === 'Ditolak') <i class="fa-solid fa-circle-xmark"></i>
                        @else <i class="fa-solid fa-clock"></i> @endif
                        {{ $status === 'Diterima' ? 'LOLOS ✓' : ($status === 'Ditolak' ? 'TIDAK LOLOS ✗' : $status) }}
                    </div>
                </div>

                @if($status === 'Ditolak' && !empty($catatan_penolakan))
                <div class="form-group">
                    <label>Catatan</label>
                    <div style="background: #fef2f2; border: 1px solid #fecaca; border-radius: 12px; padding: 14px 18px; color: #991b1b; font-weight: 600; line-height: 1.6; white-space: pre-line;">
                        <i class="fa-solid fa-message" style="margin-right: 8px;"></i>{{ $catatan_penolakan }}
                    </div>
                </div>
                @endif

                @if ($status === 'Diterima')
                    <div class="creds-container">
                        <p style="margin-top:0; font-weight: 700; font-size: 0.9rem; color: var(--gold-solid); margin-bottom: 10px;">
                            <i class="fa-solid fa-envelope-circle-check" style="margin-right: 8px;"></i>
                            INFORMASI LOGIN
                        </p>
                        <p style="margin: 0; font-size: 0.95rem; line-height: 1.6; color: rgba(255,255,255,0.9);">
                            Silakan cek email Anda untuk mendapatkan informasi lebih lanjut, termasuk kredensial login (email dan password) yang telah dikirim ke alamat email Anda.
                        </p>
                        <p style="margin: 10px 0 0 0; font-size: 0.85rem; line-height: 1.6; color: rgba(255,255,255,0.7);">
                            <i class="fa-solid fa-info-circle" style="margin-right: 5px;"></i>
                            Email: <strong>{{ $email }}</strong>
                        </p>
                    </div>
                @endif

                <a href="{{ route('Halaman awal') }}" class="btn-back"><i class="fa-solid fa-arrow-left"></i> Kembali ke Beranda</a>

            @else
                <p style="text-align: center; color: var(--text-muted); margin-bottom: 30px;">
                    Masukkan nomor pendaftaran yang Anda terima untuk melihat status seleksi.
                </p>
                
                <form action="{{ url('cek_status') }}" method="POST" id="cekStatusForm">
                    @csrf
                    <div class="form-group">
                        <label>Nomor Pendaftaran</label>
                        <input type="text" name="no_pendaftaran" placeholder="Masukkan nomor pendaftaran Anda" value="{{ old('no_pendaftaran') }}" required>
                    </div>

                    <div class="form-group">
                        <div class="g-recaptcha" data-sitekey="{{ env('RECAPTCHA_SITE_KEY') }}" id="recaptchaContainer"
                             data-callback="cekStatusCaptchaSolved"
                             data-expired-callback="cekStatusCaptchaReset"
                             data-error-callback="cekStatusCaptchaReset"></div>
                        @error('g-recaptcha-response')
                            <div style="color: #ef4444; font-size: 0.85rem; margin-top: 8px;">
                                <i class="fa-solid fa-exclamation-circle"></i> {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <button type="submit" class="btn-submit" id="submitBtn">
                        <span id="submitText">Periksa Status Sekarang</span>
                        <span id="submitLoader" style="display: none;">
                            <i class="fa-solid fa-spinner fa-spin"></i> Memproses...
                        </span>
                    </button>
                    
                    <a href="{{ route('Halaman awal') }}" class="btn-back"><i class="fa-solid fa-arrow-left"></i> Kembali ke Beranda</a>
                </form>
                
                <script>
                    var cekStatusForm = document.getElementById('cekStatusForm');
                    var cekStatusBtn = document.getElementById('submitBtn');
                    var cekStatusText = document.getElementById('submitText');
                    var cekStatusLoader = document.getElementById('submitLoader');

                    // Aktif/nonaktifkan tombol (dipakai untuk gate reCAPTCHA)
                    function cekStatusSetEnabled(enabled) {
                        if (!cekStatusBtn) return;
                        cekStatusBtn.disabled = !enabled;
                        cekStatusBtn.style.opacity = enabled ? '1' : '0.6';
                        cekStatusBtn.style.cursor = enabled ? 'pointer' : 'not-allowed';
                    }

                    // Tampilkan/sembunyikan loader pada tombol
                    function cekStatusSetLoading(isLoading) {
                        if (!cekStatusBtn) return;
                        cekStatusBtn.disabled = isLoading;
                        cekStatusText.style.display = isLoading ? 'none' : 'inline';
                        cekStatusLoader.style.display = isLoading ? 'inline' : 'none';
                        cekStatusBtn.style.opacity = isLoading ? '0.7' : '1';
                        cekStatusBtn.style.cursor = isLoading ? 'not-allowed' : 'pointer';
                    }

                    // Callback reCAPTCHA (dipanggil otomatis oleh widget via data-callback)
                    window.cekStatusCaptchaSolved = function() { cekStatusSetEnabled(true); };
                    window.cekStatusCaptchaReset = function() { cekStatusSetEnabled(false); };

                    // Tombol nonaktif sampai reCAPTCHA diselesaikan
                    cekStatusSetEnabled(false);

                    // Validasi reCAPTCHA & submit form (lapis kedua, sebagai pengaman)
                    cekStatusForm.addEventListener('submit', function(e) {
                        e.preventDefault();

                        var token = (typeof grecaptcha !== 'undefined' && grecaptcha.getResponse)
                            ? grecaptcha.getResponse() : '';

                        if (!token) {
                            cekStatusSetEnabled(false);
                            Swal.fire({
                                icon: 'warning',
                                title: 'Verifikasi Diperlukan',
                                text: 'Silakan lengkapi verifikasi reCAPTCHA terlebih dahulu.',
                                confirmButtonColor: '#b08d48',
                                confirmButtonText: 'Mengerti'
                            });
                            return false;
                        }

                        cekStatusSetLoading(true);
                        Swal.fire({
                            title: 'Memproses...',
                            html: 'Sedang memverifikasi dan memproses data Anda',
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            showConfirmButton: false,
                            didOpen: () => { Swal.showLoading(); }
                        });

                        setTimeout(() => { this.submit(); }, 300);
                    });

                    // Saat halaman ditampilkan ulang (mis. tombol Back / error), pastikan
                    // tombol mengikuti status reCAPTCHA terkini (bukan otomatis aktif).
                    window.addEventListener('pageshow', function() {
                        cekStatusText.style.display = 'inline';
                        cekStatusLoader.style.display = 'none';
                        var token = (typeof grecaptcha !== 'undefined' && grecaptcha.getResponse)
                            ? grecaptcha.getResponse() : '';
                        cekStatusSetEnabled(!!token);
                    });
                </script>
            @endif
        </div>
    </div>

    @include('auth.partials.public-footer')
    @include('auth.partials.public-chrome-scripts')
</body>
</html>