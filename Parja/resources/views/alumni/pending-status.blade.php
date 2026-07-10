<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Status Akun Alumni | ePublic</title>
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('template/dist/assets/img/favicon.png') }}">
    <link rel="stylesheet" href="{{ asset('template/dist/lib/remixicon/fonts/remixicon.css') }}">
    <link rel="stylesheet" href="{{ asset('template/dist/assets/css/style.min.css') }}">
    <style>
        :root {
            --parja-magenta: #bf0050;
            --parja-purple: #41174b;
            --parja-gold: #fbac18;
        }

        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background:
                radial-gradient(circle at top right, rgba(191, 0, 80, 0.18), transparent 26%),
                radial-gradient(circle at bottom left, rgba(251, 172, 24, 0.20), transparent 30%),
                linear-gradient(135deg, #fff9fb 0%, #fffef5 45%, #f6f0fa 100%);
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
        }

        .status-card {
            width: min(100%, 540px);
            margin: 24px;
            background: rgba(255, 255, 255, 0.96);
            border-radius: 28px;
            box-shadow: 0 24px 70px rgba(65, 23, 75, 0.16);
            border: 1px solid rgba(65, 23, 75, 0.14);
            overflow: hidden;
            text-align: center;
        }

        .status-banner {
            padding: 36px 36px 28px;
            position: relative;
        }

        .status-banner.pending {
            background: linear-gradient(135deg, #fff8e1 0%, #fffde7 100%);
            border-bottom: 3px solid var(--parja-gold);
        }

        .status-banner.rejected {
            background: linear-gradient(135deg, #fdf0f5 0%, #fff5f8 100%);
            border-bottom: 3px solid var(--parja-magenta);
        }

        .status-icon {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 2.4rem;
            margin-bottom: 20px;
        }

        .status-icon.pending {
            background: rgba(251, 172, 24, 0.18);
            color: #c77d00;
            border: 2px solid rgba(251, 172, 24, 0.5);
        }

        .status-icon.rejected {
            background: rgba(191, 0, 80, 0.12);
            color: var(--parja-magenta);
            border: 2px solid rgba(191, 0, 80, 0.3);
        }

        .status-title {
            font-size: 1.5rem;
            font-weight: 800;
            margin-bottom: 8px;
        }

        .status-title.pending {
            color: #7a4f00;
        }

        .status-title.rejected {
            color: var(--parja-purple);
        }

        .status-subtitle {
            font-size: 0.95rem;
            color: #5d4f66;
            margin: 0;
        }

        .status-body {
            padding: 28px 36px 36px;
        }

        .info-row {
            display: flex;
            align-items: center;
            gap: 10px;
            background: #f8eef5;
            border-radius: 12px;
            padding: 12px 16px;
            margin-bottom: 12px;
            text-align: left;
        }

        .info-row i {
            color: var(--parja-magenta);
            font-size: 1.1rem;
            flex-shrink: 0;
        }

        .info-row span {
            font-size: 0.88rem;
            color: #3d2a47;
        }

        .rejection-box {
            background: #fff5f8;
            border: 1px solid rgba(191, 0, 80, 0.25);
            border-radius: 14px;
            padding: 18px 20px;
            margin-bottom: 20px;
            text-align: left;
        }

        .rejection-box .label {
            font-size: 0.78rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: var(--parja-magenta);
            margin-bottom: 6px;
        }

        .rejection-box .reason {
            font-size: 0.95rem;
            color: #3d2a47;
            line-height: 1.6;
            white-space: pre-wrap;
        }

        .step-list {
            text-align: left;
            margin: 20px 0;
        }

        .step-item {
            display: flex;
            gap: 12px;
            align-items: flex-start;
            margin-bottom: 14px;
        }

        .step-num {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background: var(--parja-gold);
            color: #3d2a00;
            font-weight: 800;
            font-size: 0.82rem;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .step-text {
            font-size: 0.88rem;
            color: #3d2a47;
            padding-top: 4px;
            line-height: 1.5;
        }

        .divider {
            height: 1px;
            background: rgba(65, 23, 75, 0.1);
            margin: 4px 0 20px;
        }

        .btn-action {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 24px;
            border-radius: 50px;
            font-size: 0.9rem;
            font-weight: 700;
            text-decoration: none;
            cursor: pointer;
            border: none;
        }

        .btn-logout {
            background: var(--parja-purple);
            color: #fff;
        }

        .btn-logout:hover {
            background: #2e0f36;
            color: #fff;
        }

        .btn-contact {
            background: transparent;
            color: var(--parja-magenta);
            border: 2px solid var(--parja-magenta);
        }

        .btn-contact:hover {
            background: rgba(191, 0, 80, 0.07);
            color: var(--parja-magenta);
        }

        .btn-whatsapp {
            background: #25D366;
            color: #fff;
            box-shadow: 0 4px 14px rgba(37, 211, 102, 0.35);
            transition: background 0.2s, box-shadow 0.2s, transform 0.15s;
        }

        .btn-whatsapp:hover {
            background: #1ebe5d;
            color: #fff;
            box-shadow: 0 6px 18px rgba(37, 211, 102, 0.45);
            transform: translateY(-1px);
        }

        .wa-remind-box {
            background: linear-gradient(135deg, #f0fff4 0%, #e6ffef 100%);
            border: 1px solid rgba(37, 211, 102, 0.35);
            border-radius: 14px;
            padding: 14px 18px;
            margin-top: 16px;
            text-align: left;
            display: flex;
            align-items: flex-start;
            gap: 12px;
        }

        .wa-remind-box .wa-icon {
            font-size: 1.5rem;
            color: #25D366;
            flex-shrink: 0;
            margin-top: 2px;
        }

        .wa-remind-box .wa-content {
            flex: 1;
        }

        .wa-remind-box .wa-title {
            font-size: 0.82rem;
            font-weight: 700;
            color: #1a6b35;
            margin-bottom: 4px;
        }

        .wa-remind-box .wa-desc {
            font-size: 0.82rem;
            color: #2d6b40;
            line-height: 1.5;
            margin-bottom: 10px;
        }

        .wa-remind-box .wa-desc strong {
            color: #1a6b35;
        }

        .actions {
            display: flex;
            gap: 10px;
            justify-content: center;
            flex-wrap: wrap;
            margin-top: 8px;
        }

        .footer-note {
            font-size: 0.8rem;
            color: #8a7a93;
            margin-top: 20px;
        }

        .badge-status {
            display: inline-block;
            padding: 4px 14px;
            border-radius: 999px;
            font-size: 0.78rem;
            font-weight: 700;
            margin-top: 8px;
        }

        .badge-pending {
            background: rgba(251, 172, 24, 0.2);
            color: #7a4f00;
            border: 1px solid rgba(251, 172, 24, 0.5);
        }

        .badge-rejected {
            background: rgba(191, 0, 80, 0.1);
            color: var(--parja-magenta);
            border: 1px solid rgba(191, 0, 80, 0.3);
        }
    </style>
</head>

<body>

    @php
        $status = $alumni ? (int) $alumni->approval_status : 0;
        $isRejected = $status === 2;
        $state = $pendingState ?? 'akun_pending';
        // Dua sub-state pending: 'akun_pending' (KC belum approve) dan
        // 'bukti_unverified' (KC sudah approve tapi bukti L2 belum direview admin).
        $isPending          = ! $isRejected && $state === 'akun_pending';
        $isBuktiUnverified  = ! $isRejected && $state === 'bukti_unverified';
        $catatanTolak       = $alumni?->catatan_tolak;
    @endphp

    <div class="status-card">
        @if ($isPending || $isBuktiUnverified)
            {{-- ============ PENDING STATE (dua sub-state: akun_pending vs bukti_unverified) ============ --}}
            <div class="status-banner pending">
                <div class="status-icon pending">
                    <i class="{{ $isBuktiUnverified ? 'ri-file-search-line' : 'ri-time-line' }}"></i>
                </div>
                <div class="badge-status badge-pending">
                    ⏳ {{ $isBuktiUnverified ? 'Menunggu Verifikasi Bukti' : 'Menunggu Verifikasi Akun' }}
                </div>
                <h1 class="status-title pending mt-3">
                    {{ $isBuktiUnverified ? 'Bukti Alumni Sedang Ditinjau' : 'Akun Sedang Diverifikasi' }}
                </h1>
                <p class="status-subtitle">
                    @if ($isBuktiUnverified)
                        Data alumni dan bukti kelulusan yang Anda unggah sudah masuk ke admin Parja. Mohon tunggu hasil verifikasi keanggotaan Anda.
                    @else
                        Pendaftaran Anda sudah diterima. Admin akan meninjau akun login Anda terlebih dahulu sebelum Anda dapat mengisi data alumni lengkap.
                    @endif
                </p>
            </div>

            <div class="status-body">
                <div class="info-row">
                    <i class="ri-user-line"></i>
                    <span><strong>{{ (auth()->guard('keycloak-external')->user()?->name ?? auth()->guard('keycloak')->user()?->name ?? '') }}</strong> — {{ (auth()->guard('keycloak-external')->user()?->email ?? auth()->guard('keycloak')->user()?->email ?? '') }}</span>
                </div>
                @if ($alumni?->no_anggota)
                    <div class="info-row">
                        <i class="ri-id-card-line"></i>
                        <span>No. Anggota: <strong>{{ $alumni->no_anggota }}</strong></span>
                    </div>
                @endif

                <div class="divider"></div>

                <div class="step-list">
                    <div class="step-item">
                        <div class="step-num">1</div>
                        <div class="step-text">Admin akan memeriksa data dan bukti keterangan alumni yang Anda kirimkan.
                        </div>
                    </div>
                    <div class="step-item">
                        <div class="step-num">2</div>
                        <div class="step-text">Setelah diverifikasi, akun Anda akan diaktifkan dan Anda bisa langsung masuk
                            ke portal alumni.</div>
                    </div>
                    <div class="step-item">
                        <div class="step-num">3</div>
                        <div class="step-text">Jika ada masalah, Anda akan mendapat pemberitahuan beserta alasannya di
                            halaman ini saat login.</div>
                    </div>
                </div>

                {{-- Keterangan & Tombol WA Admin --}}
                <div class="wa-remind-box">
                    <div class="wa-icon"><i class="ri-whatsapp-line"></i></div>
                    <div class="wa-content">
                        <div class="wa-title">💬 Belum diverifikasi dalam 1×24 jam?</div>
                        <div class="wa-desc">
                            Silakan hubungi <strong>Admin Parja Alumni</strong> melalui WhatsApp untuk konfirmasi status
                            pendaftaran Anda.
                        </div>
                        <a href="https://wa.me/6285156992282?text=Halo%20Admin%20Parja%20Alumni%2C%20saya%20ingin%20menanyakan%20status%20verifikasi%20akun%20alumni%20saya%20yang%20belum%20dikonfirmasi%20dalam%201x24%20jam.%20Nama%3A%20{{ urlencode((auth()->guard('keycloak-external')->user()?->name ?? auth()->guard('keycloak')->user()?->name ?? '')) }}%2C%20Email%3A%20{{ urlencode((auth()->guard('keycloak-external')->user()?->email ?? auth()->guard('keycloak')->user()?->email ?? '')) }}."
                            target="_blank" rel="noopener noreferrer" class="btn-action btn-whatsapp"
                            style="font-size:0.82rem;padding:8px 18px;">
                            <i class="ri-whatsapp-line"></i> Hubungi Admin via WhatsApp
                        </a>
                    </div>
                </div>

                <div class="actions">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn-action btn-logout">
                            <i class="ri-logout-box-r-line"></i> Keluar
                        </button>
                    </form>
                </div>

                <p class="footer-note">
                    Silakan masuk kembali nanti untuk memeriksa status akun Anda. Proses verifikasi biasanya memerlukan 1–3
                    hari kerja.
                </p>
            </div>

        @elseif ($isRejected)
            {{-- ============ REJECTED STATE ============ --}}
            <div class="status-banner rejected">
                <div class="status-icon rejected">
                    <i class="ri-close-circle-line"></i>
                </div>
                <div class="badge-status badge-rejected">✗ Pendaftaran Ditolak</div>
                <h1 class="status-title rejected mt-3">Pendaftaran Tidak Disetujui</h1>
                <p class="status-subtitle">
                    Maaf, pendaftaran akun alumni Anda tidak dapat dikonfirmasi oleh admin. Silakan baca keterangan di
                    bawah.
                </p>
            </div>

            <div class="status-body">
                <div class="info-row">
                    <i class="ri-user-line"></i>
                    <span><strong>{{ (auth()->guard('keycloak-external')->user()?->name ?? auth()->guard('keycloak')->user()?->name ?? '') }}</strong> — {{ (auth()->guard('keycloak-external')->user()?->email ?? auth()->guard('keycloak')->user()?->email ?? '') }}</span>
                </div>
                @if ($alumni?->no_anggota)
                    <div class="info-row">
                        <i class="ri-id-card-line"></i>
                        <span>No. Anggota: <strong>{{ $alumni->no_anggota }}</strong></span>
                    </div>
                @endif

                <div class="divider"></div>

                @if (!empty($catatanTolak))
                    <div class="rejection-box">
                        <div class="label"><i class="ri-error-warning-line me-1"></i> Catatan dari Admin</div>
                        <div class="reason">{{ $catatanTolak }}</div>
                    </div>
                @else
                    <div class="rejection-box">
                        <div class="label"><i class="ri-error-warning-line me-1"></i> Keterangan</div>
                        <div class="reason">Pendaftaran ditolak tanpa catatan tambahan. Silakan hubungi admin untuk informasi
                            lebih lanjut.</div>
                    </div>
                @endif

                <p style="font-size:0.88rem;color:#5d4f66;text-align:left;margin-bottom:20px;">
                    Jika akun anda ditolak saat proses verifikasi, Anda akan mendapat pemberitahuan beserta alasannya ketika
                    anda login.
                    Apabila terdapat ketidaksesuaian data, silakan lakukan pendaftaran ulang atau hubungi administrator
                    untuk bantuan lebih lanjut.
                    .
                </p>

                <div class="actions">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn-action btn-logout">
                            <i class="ri-logout-box-r-line"></i> Keluar
                        </button>
                    </form>
                    <a href="{{ route('alumni.register') }}" class="btn-action btn-contact">
                        <i class="ri-user-add-line"></i> Daftar Ulang
                    </a>
                </div>

                <p class="footer-note">
                    Jika Anda ingin mendaftar ulang, silakan gunakan email lain atau hubungi admin terlebih dahulu.
                </p>
            </div>

        @else
            {{-- Fallback: data tidak ditemukan --}}
            <div class="status-banner pending">
                <div class="status-icon pending"><i class="ri-question-line"></i></div>
                <h1 class="status-title pending mt-3">Data Alumni Tidak Ditemukan</h1>
                <p class="status-subtitle">Akun Anda tidak terhubung ke data alumni manapun. Silakan hubungi admin.</p>
            </div>
            <div class="status-body">
                <div class="actions">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn-action btn-logout">
                            <i class="ri-logout-box-r-line"></i> Keluar
                        </button>
                    </form>
                </div>
            </div>
        @endif
    </div>

    <script src="{{ asset('template/dist/lib/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
</body>

</html>