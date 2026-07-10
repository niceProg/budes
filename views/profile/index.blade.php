@extends('layouts.app')

@section('title', 'Profile | SMART Setjen DPR RI')
@section('content')
    <style>
        :root {
            --primary-dark: #0f172a;
            --accent-gold: linear-gradient(135deg, #c5a059 0%, #8e6d2f 100%);
            --gold-solid: #b08d48;
            --gold-light: #fdfaf3;
            --text-main: #1e293b;
            --text-muted: #64748b;
            --bg-light: #f8fafc;
            --border-color: #e2e8f0;
        }

        body {
            background-color: var(--bg-light);
            color: var(--text-main);
        }

        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(15, 23, 42, 0.05);
            margin-bottom: 1.5rem;
            overflow: hidden;
            background: #ffffff;
        }

        .card-header {
            background-color: #fff !important;
            border-bottom: 1px solid var(--border-color) !important;
            padding: 1.25rem;
        }

        .card-header h6 {
            color: var(--primary-dark) !important;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 0;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.025em;
        }

        .card-header h6 i {
            color: var(--gold-solid);
            font-size: 1.25rem;
        }

        .info-label {
            font-size: 0.725rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--text-muted);
            margin-bottom: 0.35rem;
            font-weight: 700;
        }

        .info-value {
            color: var(--primary-dark);
            font-weight: 600;
            font-size: 0.95rem;
            line-height: 1.5;
        }

        .btn-gold-gradient {
            background: var(--accent-gold);
            border: none;
            color: white;
            font-weight: 600;
        }

        .btn-gold-gradient:hover {
            opacity: 0.92;
            color: white;
        }

        .text-gold-solid {
            color: var(--gold-solid) !important;
        }

        .form-text-hint {
            font-size: 0.8rem;
            color: var(--text-muted);
        }

        /* Password UI (sesuai contoh) */
        .pw-field {
            position: relative;
        }

        .pw-field .pw-toggle {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #94a3b8;
            font-size: 1.15rem;
            user-select: none;
        }

        .pw-rules {
            margin-top: 10px;
        }

        .pw-rule {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 0.82rem;
            margin-top: 6px;
            color: #dc2626;
            /* red by default */
            line-height: 1.25;
        }

        .pw-rule i {
            font-size: 1rem;
            line-height: 1;
            flex-shrink: 0;
            color: inherit;
        }

        .pw-rule .hint {
            color: #64748b;
            font-size: 0.78rem;
            margin-left: 4px;
            white-space: nowrap;
        }

        .totp-setup-box {
            border: 1px solid var(--border-color);
            border-radius: 14px;
            padding: 1rem;
            background: #f8fafc;
        }

        .totp-step-title {
            font-size: 1.1rem;
            font-weight: 800;
            margin-bottom: .25rem;
            color: var(--primary-dark);
        }

        .totp-step-text {
            color: var(--text-muted);
            margin-bottom: 1rem;
            line-height: 1.55;
        }

        .totp-subtitle {
            font-size: .95rem;
            font-weight: 700;
            margin-bottom: .4rem;
            color: var(--primary-dark);
        }

        .totp-key-box {
            border: 1px dashed #94a3b8;
            border-radius: 10px;
            background: #ffffff;
            padding: .6rem .75rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: .5rem;
            margin-bottom: .75rem;
        }

        .totp-key-text {
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
            font-size: .9rem;
            font-weight: 700;
            color: var(--primary-dark);
            word-break: break-all;
        }

        html[data-skin="dark"] body {
            background: transparent !important;
            color: #ffffff !important;
        }

        html[data-skin="dark"] .card,
        html[data-skin="dark"] .card-header,
        html[data-skin="dark"] .card-body {
            background: #0f172a !important;
            color: #ffffff !important;
            border-color: #1f2937 !important;
        }

        html[data-skin="dark"] .info-label,
        html[data-skin="dark"] .info-value {
            color: #ffffff !important;
        }

        html[data-skin="dark"] .text-gold-solid {
            color: #fbbf24 !important;
        }

        html[data-skin="dark"] .form-control {
            background: #1e293b !important;
            border-color: #374151 !important;
            color: #ffffff !important;
        }

        html[data-skin="dark"] .pw-rule .hint {
            color: #9ca3af !important;
        }

        html[data-skin="dark"] .totp-setup-box {
            background: #020617;
            border-color: #374151;
        }

        html[data-skin="dark"] .totp-step-title,
        html[data-skin="dark"] .totp-subtitle {
            color: #ffffff;
        }

        html[data-skin="dark"] .totp-step-text {
            color: #cbd5e1;
        }

        html[data-skin="dark"] .totp-key-box {
            background: #111827;
            border-color: #4b5563;
        }

        html[data-skin="dark"] .totp-key-text {
            color: #f8fafc;
        }
    </style>

    @php
        // Determine route prefix: user (peserta/guru) get 'user.' prefix; admin/pegawai use '' (admin prefix)
        $rp = (!empty($isPegawai) || !auth()->check() || !in_array(auth()->user()->roles, ['peserta', 'guru'])) ? '' : 'user.';
    @endphp

    @if (session('success'))
        <div class="alert alert-success border-0 shadow-sm mb-4" role="alert">
            <i class="ri-checkbox-circle-line me-2"></i>{{ session('success') }}
        </div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger border-0 shadow-sm mb-4" role="alert">
            <i class="ri-error-warning-line me-2"></i>Periksa kembali input Anda.
        </div>
    @endif

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <ol class="breadcrumb fs-sm mb-1">
                <li class="breadcrumb-item"><a href="#" class="text-decoration-none text-muted">Menu</a></li>
                <li class="breadcrumb-item active" aria-current="page">Profile</li>
            </ol>
            <h4 class="mb-0" style="font-weight:800; color: var(--primary-dark);">Profile Saya</h4>
        </div>
    </div>

    @if (empty($isPegawai))
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h6><i class="ri-shield-keyhole-line"></i> Verifikasi Dua Langkah (2FA)</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-2" style="font-weight:700; color: var(--primary-dark);">Aplikasi authenticator
                        </div>
                        <div class="form-text-hint mb-3">
                            Aktifkan verifikasi lewat aplikasi authenticator untuk login menggunakan kode OTP 6 digit (bisa
                            pakai Google Authenticator, Microsoft Authenticator, Authy, dan sejenisnya).
                        </div>

                        @if (!empty($totpEnabled))
                            <div class="alert alert-success border-0 shadow-sm">
                                <i class="ri-checkbox-circle-line me-2"></i>
                                Aplikasi authenticator <strong>aktif</strong>.
                            </div>
                            <form method="POST" action="{{ route($rp . 'profile.totp.disable') }}">
                                @csrf
                                <button type="submit" class="btn btn-outline-danger" style="border-radius:12px;">
                                    Nonaktifkan authenticator
                                </button>
                            </form>
                        @else
                            @if (!empty($totpPending))
                                <div class="alert alert-warning border-0 shadow-sm">
                                    <i class="ri-error-warning-line me-2"></i>
                                    Setup belum selesai. Scan QR lalu masukkan kode untuk konfirmasi.
                                </div>

                                <div class="totp-setup-box">
                                    <div class="totp-step-title">Atur aplikasi authenticator</div>
                                    <div class="totp-step-text">
                                        Gunakan aplikasi authenticator apa pun yang mendukung TOTP, misalnya Google
                                        Authenticator, Microsoft Authenticator, atau Authy
                                        untuk menghasilkan kode OTP 6 digit sebagai lapisan keamanan tambahan saat login.
                                    </div>

                                    <div class="totp-subtitle">1) Pindai QR code</div>
                                    <div class="totp-step-text mb-2">
                                        Buka aplikasi authenticator Anda, pilih tambah akun, lalu scan QR code berikut.
                                    </div>

                                    @if (!empty($totpQrSvg))
                                        <div class="d-flex justify-content-center mb-3">
                                            <div
                                                style="background:#fff; padding:12px; border-radius:14px; border:1px solid var(--border-color);">
                                                {!! $totpQrSvg !!}
                                            </div>
                                        </div>
                                    @else
                                        <div class="alert alert-info border-0 shadow-sm">
                                            <i class="ri-information-line me-2"></i>
                                            QR tidak dapat ditampilkan. Silakan klik mulai ulang aktivasi.
                                        </div>
                                    @endif

                                    @if (!empty($totpSetupKey))
                                        <div class="totp-subtitle">Tidak bisa scan? Masukkan setup key manual</div>
                                        <div class="totp-key-box">
                                            <span class="totp-key-text" id="totpSetupKey">{{ $totpSetupKey }}</span>
                                            <button type="button" class="btn btn-sm btn-outline-secondary"
                                                id="btnCopyTotpKey">Salin</button>
                                        </div>
                                    @endif

                                    <div class="totp-subtitle">2) Verifikasi kode dari aplikasi</div>
                                    <div class="totp-step-text mb-2">
                                        Setelah akun berhasil ditambahkan ke aplikasi authenticator, masukkan kode 6 digit
                                        yang tampil.
                                    </div>

                                    <form method="POST" action="{{ route($rp . 'profile.totp.confirm') }}" id="totpConfirmForm">
                                        @csrf
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Kode dari aplikasi authenticator</label>
                                            <input type="text" name="code" inputmode="numeric" maxlength="6"
                                                class="form-control @error('code') is-invalid @enderror"
                                                placeholder="6 digit" required>
                                            @error('code')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </form>

                                    <div class="d-flex align-items-center gap-2">
                                        <button type="submit" form="totpConfirmForm" class="btn btn-gold-gradient"
                                            style="border-radius:12px;">
                                            Konfirmasi & Aktifkan
                                        </button>
                                        <form method="POST" action="{{ route($rp . 'profile.totp.disable') }}" class="m-0">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-secondary"
                                                style="border-radius:12px;">
                                                Batal
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @else
                                <form method="POST" action="{{ route($rp . 'profile.totp.start') }}">
                                    @csrf
                                    <button type="submit" class="btn btn-gold-gradient" style="border-radius:12px;">
                                        Aktifkan authenticator
                                    </button>
                                </form>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if (!empty($isPegawai))
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h6><i class="ri-shield-keyhole-line"></i> Verifikasi Dua Langkah (MFA Pegawai)</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-2" style="font-weight:700; color: var(--primary-dark);">Aplikasi authenticator
                        </div>
                        <div class="form-text-hint mb-3">
                            Jika diaktifkan, setiap login akan diminta kode 6 digit dari aplikasi authenticator
                            (Google Authenticator, Microsoft Authenticator, Authy, dsb).
                            Centang <em>Ingat Saya</em> pada saat halaman login untuk mempercayai perangkat ini selama 30
                            hari.
                        </div>

                        @if (!empty($pegawaiTotpEnabled))
                            <div class="alert alert-success border-0 shadow-sm">
                                <i class="ri-checkbox-circle-line me-2"></i>
                                MFA authenticator pegawai <strong>aktif</strong>.
                            </div>

                            @if (!empty($pegawaiTrustVisible))
                                <div class="alert alert-info border-0 shadow-sm d-flex align-items-start gap-2">
                                    <i class="ri-device-line mt-1"></i>
                                    <div class="flex-grow-1">
                                        Perangkat ini sedang dipercaya selama 30 hari. Login berikutnya tidak akan minta
                                        kode authenticator dari perangkat ini.
                                    </div>
                                </div>
                                <form method="POST" action="{{ route('profile.pegawai.forget-remember-device') }}"
                                    class="d-inline-block me-2">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-secondary" style="border-radius:12px;">
                                        Lupakan perangkat ini
                                    </button>
                                </form>
                            @endif

                            <form method="POST" action="{{ route('profile.pegawai.totp.disable') }}"
                                class="d-inline-block"
                                onsubmit="return confirm('Yakin nonaktifkan MFA authenticator? Trust device 30 hari juga akan dihapus.');">
                                @csrf
                                <button type="submit" class="btn btn-outline-danger" style="border-radius:12px;">
                                    Nonaktifkan authenticator
                                </button>
                            </form>
                        @else
                            @if (!empty($pegawaiTotpPending))
                                <div class="alert alert-warning border-0 shadow-sm">
                                    <i class="ri-error-warning-line me-2"></i>
                                    Setup belum selesai. Scan QR lalu masukkan kode untuk konfirmasi.
                                </div>

                                <div class="totp-setup-box">
                                    <div class="totp-step-title">Atur aplikasi authenticator</div>
                                    <div class="totp-step-text">
                                        Gunakan aplikasi authenticator apa pun yang mendukung TOTP (Google Authenticator,
                                        Microsoft Authenticator, Authy, dsb)
                                        untuk menghasilkan kode 6 digit sebagai lapisan keamanan tambahan saat login.
                                    </div>

                                    <div class="totp-subtitle">1) Pindai QR code</div>
                                    <div class="totp-step-text mb-2">
                                        Buka aplikasi authenticator Anda, pilih tambah akun, lalu scan QR code berikut.
                                    </div>

                                    @if (!empty($pegawaiTotpQrSvg))
                                        <div class="d-flex justify-content-center mb-3">
                                            <div
                                                style="background:#fff; padding:12px; border-radius:14px; border:1px solid var(--border-color);">
                                                {!! $pegawaiTotpQrSvg !!}
                                            </div>
                                        </div>
                                    @else
                                        <div class="alert alert-info border-0 shadow-sm">
                                            <i class="ri-information-line me-2"></i>
                                            QR tidak dapat ditampilkan. Silakan klik mulai ulang aktivasi.
                                        </div>
                                    @endif

                                    @if (!empty($pegawaiTotpSetupKey))
                                        <div class="totp-subtitle">Tidak bisa scan? Masukkan setup key manual</div>
                                        <div class="totp-key-box">
                                            <span class="totp-key-text"
                                                id="pegawaiTotpSetupKey">{{ $pegawaiTotpSetupKey }}</span>
                                            <button type="button" class="btn btn-sm btn-outline-secondary"
                                                id="btnCopyPegawaiTotpKey">Salin</button>
                                        </div>
                                    @endif

                                    <div class="totp-subtitle">2) Verifikasi kode dari aplikasi</div>
                                    <div class="totp-step-text mb-2">
                                        Setelah akun berhasil ditambahkan, masukkan kode 6 digit yang tampil.
                                    </div>

                                    <form method="POST" action="{{ route('profile.pegawai.totp.confirm') }}"
                                        id="pegawaiTotpConfirmForm">
                                        @csrf
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Kode dari aplikasi authenticator</label>
                                            <input type="text" name="code" inputmode="numeric" maxlength="6"
                                                class="form-control @error('code') is-invalid @enderror"
                                                placeholder="6 digit" required>
                                            @error('code')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </form>

                                    <div class="d-flex align-items-center gap-2">
                                        <button type="submit" form="pegawaiTotpConfirmForm"
                                            class="btn btn-gold-gradient" style="border-radius:12px;">
                                            Konfirmasi & Aktifkan
                                        </button>
                                        <form method="POST" action="{{ route('profile.pegawai.totp.disable') }}"
                                            class="m-0">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-secondary"
                                                style="border-radius:12px;">
                                                Batal
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @else
                                <form method="POST" action="{{ route('profile.pegawai.totp.start') }}">
                                    @csrf
                                    <button type="submit" class="btn btn-gold-gradient" style="border-radius:12px;">
                                        Aktifkan authenticator
                                    </button>
                                </form>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="row align-items-stretch mb-4">
        <div class="col-lg-6 d-flex">
            <div class="card h-100 flex-fill">
                <div class="card-header">
                    <h6><i class="ri-user-3-line"></i> Informasi Akun</h6>
                </div>
                <div class="card-body">
                    @if (!empty($isPegawai))
                        @php
                            $pegawaiNama = is_array($pegawai) ? $pegawai['nama'] ?? null : $pegawai->nama ?? null;
                            $pegawaiNip = is_array($pegawai) ? $pegawai['nip'] ?? null : $pegawai->nip ?? null;
                            $pegawaiUsername =
                                session('pegawai_username') ??
                                (is_array($pegawai) ? $pegawai['pengguna'] ?? '-' : $pegawai->pengguna ?? '-');
                            // Fallback: jika nama / nip kosong di session pegawai, ambil dari role_as
                            if (!$pegawaiNama && is_array($roleAs)) {
                                $pegawaiNama = $roleAs['nama'] ?? $pegawaiNama;
                            }
                            if (!$pegawaiNip && is_array($roleAs)) {
                                $pegawaiNip = $roleAs['nip'] ?? $pegawaiNip;
                            }

                            $rolesInternal = is_array($roleAs) ? $roleAs['roles_internal'] ?? [] : [];
                            $roleNames = [];
                            if (is_array($rolesInternal)) {
                                foreach ($rolesInternal as $r) {
                                    $roleNames[] = strtoupper($r['nama'] ?? '');
                                }
                            }
                        @endphp
                        <div class="mb-3">
                            <div class="info-label">Nama</div>
                            <div class="info-value text-gold-solid">{{ $pegawaiNama ?? '-' }}</div>
                        </div>
                        <div class="mb-3">
                            <div class="info-label">NIP</div>
                            <div class="info-value">{{ $pegawaiNip ?? '-' }}</div>
                        </div>
                        <div class="mb-3">
                            <div class="info-label">Username</div>
                            <div class="info-value">{{ $pegawaiUsername }}</div>
                        </div>
                        <div class="mb-3">
                            <div class="info-label">Roles</div>
                            <div class="info-value">{{ !empty($roleNames) ? implode(', ', $roleNames) : '-' }}</div>
                        </div>
                    @else
                        @if (($user->roles ?? null) === 'peserta' && $peserta)
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <div class="info-label">Nama</div>
                                        <div class="info-value text-gold-solid">{{ $user->nama ?? ($user->name ?? '-') }}
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <div class="info-label">Email</div>
                                        <div class="info-value">{{ $user->email ?? '-' }}</div>
                                    </div>
                                    <div class="mb-0">
                                        <div class="info-label">Role</div>
                                        <div class="info-value">{{ strtoupper($user->roles ?? '-') }}</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <div class="info-label">Satuan Kerja</div>
                                        <div class="info-value">{{ $peserta->satker->nama ?? '-' }}</div>
                                    </div>
                                    <div class="mb-0">
                                        <div class="info-label">Status Magang</div>
                                        @php
                                            $statusVal = (int) ($peserta->status ?? -1);
                                            $statusLabel = match ($statusVal) {
                                                1 => 'Belum Mulai',
                                                2 => 'Aktif Magang',
                                                3, 0 => 'Selesai Magang',
                                                9 => 'BANNED',
                                                default => 'Tidak Diketahui',
                                            };
                                        @endphp
                                        <div class="info-value">{{ $statusLabel }}</div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="mb-3">
                                <div class="info-label">Nama</div>
                                <div class="info-value text-gold-solid">{{ $user->nama ?? ($user->name ?? '-') }}</div>
                            </div>
                            <div class="mb-3">
                                <div class="info-label">Email</div>
                                <div class="info-value">{{ $user->email ?? '-' }}</div>
                            </div>
                            <div class="mb-0">
                                <div class="info-label">Role</div>
                                <div class="info-value">{{ strtoupper($user->roles ?? '-') }}</div>
                            </div>
                        @endif

                        @if (!empty($forgetRememberThisDeviceVisible))
                            <hr class="my-4">
                            <div class="mb-2" style="font-weight:700; color: var(--primary-dark);">Ingat saya di
                                perangkat ini</div>
                            <p class="form-text-hint small mb-3">
                                Hapus pengaturan login &quot;Ingat saya&quot; dan trust verifikasi dua langkah hanya untuk
                                browser ini. Perangkat lain tidak terpengaruh.
                            </p>
                            <form method="POST" action="{{ route($rp . 'profile.forget-remember-device') }}"
                                data-no-page-loader>
                                @csrf
                                <button type="submit" class="btn btn-outline-secondary" style="border-radius:12px;">
                                    Hapus ingat saya di perangkat ini
                                </button>
                            </form>
                        @endif
                    @endif

                    @if (!empty($isPegawai) && ($user->roles ?? null) === 'peserta' && $peserta)
                        <hr class="my-4">
                        <div class="mb-3">
                            <div class="info-label">Satuan Kerja</div>
                            <div class="info-value">{{ $peserta->satker->nama ?? '-' }}</div>
                        </div>
                        <div class="mb-0">
                            <div class="info-label">Status Magang</div>
                            @php
                                $statusValMagang = (int) ($peserta->status ?? -1);
                                $statusLabelMagang = match ($statusValMagang) {
                                    1 => 'Belum Mulai',
                                    2 => 'Aktif Magang',
                                    3, 0 => 'Selesai Magang',
                                    9 => 'BANNED',
                                    default => 'Tidak Diketahui',
                                };
                            @endphp
                            <div class="info-value">{{ $statusLabelMagang }}</div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        @if (empty($isPegawai))
            <div class="col-lg-6 d-flex">
                <div class="card h-100 flex-fill">
                    <div class="card-header">
                        <h6><i class="ri-lock-password-line"></i> Ganti Password</h6>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info border-0 shadow-sm mb-0">
                            <i class="ri-information-line me-2"></i>
                            Pegawai tidak dapat mengganti password di aplikasi ini. Silakan gunakan sistem SSO.
                        </div>
                        <form id="formChangePassword" method="POST" action="{{ route($rp . 'profile.change-password') }}">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label fw-bold">Password Lama</label>
                                <div class="pw-field">
                                    <input type="password" id="current_password" name="current_password"
                                        class="form-control @error('current_password') is-invalid @enderror"
                                        placeholder="Masukkan password lama" required>
                                    <span class="pw-toggle" data-target="current_password"><i
                                            class="ri-eye-off-line"></i></span>
                                    @error('current_password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Password Baru</label>
                                <div class="pw-field">
                                    <input type="password" id="new_password" name="new_password"
                                        class="form-control @error('new_password') is-invalid @enderror"
                                        placeholder="Minimal 12 karakter" required minlength="12">
                                    <span class="pw-toggle" data-target="new_password"><i
                                            class="ri-eye-off-line"></i></span>
                                    @error('new_password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="pw-rules" id="pwRules">
                                    <div class="pw-rule" id="rule-length"><i
                                            class="ri-close-circle-line"></i><span>Password minimal 12 karakter</span>
                                    </div>
                                    <div class="pw-rule" id="rule-lower"><i class="ri-close-circle-line"></i><span>Harus
                                            mengandung huruf kecil</span><span class="hint">(contoh: a-z)</span></div>
                                    <div class="pw-rule" id="rule-upper"><i class="ri-close-circle-line"></i><span>Harus
                                            mengandung huruf besar</span><span class="hint">(contoh: A-Z)</span></div>
                                    <div class="pw-rule" id="rule-digit"><i class="ri-close-circle-line"></i><span>Harus
                                            mengandung angka</span><span class="hint">(contoh: 0-9)</span></div>
                                    <div class="pw-rule" id="rule-symbol"><i class="ri-close-circle-line"></i><span>Harus
                                            mengandung karakter khusus</span><span class="hint">(contoh: ! @ # $ % ^
                                            &amp; * )</span></div>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold">Konfirmasi Password Baru</label>
                                <div class="pw-field">
                                    <input type="password" id="new_password_confirmation"
                                        name="new_password_confirmation" class="form-control"
                                        placeholder="Ulangi password baru" required minlength="12">
                                    <span class="pw-toggle" data-target="new_password_confirmation"><i
                                            class="ri-eye-off-line"></i></span>
                                </div>
                            </div>

                            <div class="d-flex align-items-center gap-3">
                                <button type="submit" class="btn btn-gold-gradient flex-grow-1 py-2"
                                    style="border-radius:12px;">
                                    Perbarui Password
                                </button>
                                <button type="button" class="btn btn-link text-muted fw-bold"
                                    id="btnCancelChangePassword" style="text-decoration:none;">
                                    Batal
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    </div>

    @if (empty($isPegawai) &&
            ($user->roles ?? null) === 'peserta' &&
            !empty($riwayatPesertaByEmail) &&
            $riwayatPesertaByEmail->isNotEmpty())
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h6><i class="ri-history-line"></i> Riwayat Biodata Peserta</h6>
                    </div>
                    <div class="card-body">
                        <p class="form-text-hint mb-3">
                            Data berikut adalah riwayat biodata peserta Anda dari periode sebelumnya (berdasarkan email
                            akun).
                        </p>
                        <div class="table-responsive">
                            <table class="table table-sm align-middle">
                                <thead>
                                    <tr>
                                        <th>Nama</th>
                                        <th>NIK</th>
                                        <th>Satuan Kerja</th>
                                        <th>Status</th>
                                        <th>Periode</th>
                                        <th>Instansi</th>
                                        <th>No. Dinas 1</th>
                                        <th>No. Dinas 2</th>
                                        <th>Sertifikat</th>
                                        <th>Nilai</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($riwayatPesertaByEmail as $riwayat)
                                        @php
                                            $statusRiwayat = match ((int) ($riwayat->status ?? -1)) {
                                                1 => 'Belum Mulai',
                                                2 => 'Aktif Magang',
                                                3, 0 => 'Selesai Magang',
                                                9 => 'BANNED',
                                                default => 'Tidak Diketahui',
                                            };
                                            $tanggalMulai = $riwayat->lamaran?->tanggal_mulai
                                                ? \Carbon\Carbon::parse($riwayat->lamaran->tanggal_mulai)->locale('id')->translatedFormat(
                                                    'd M Y',
                                                )
                                                : '-';
                                            $tanggalSelesai = $riwayat->lamaran?->tanggal_selesai
                                                ? \Carbon\Carbon::parse($riwayat->lamaran->tanggal_selesai)->locale('id')->translatedFormat(
                                                    'd M Y',
                                                )
                                                : '-';
                                            $urlNodin1 = $riwayat->nodin_1
                                                ? (str_contains($riwayat->nodin_1, 'nodin_1/')
                                                    ? file_url($riwayat->nodin_1)
                                                    : file_url('nodin_1/' . $riwayat->nodin_1))
                                                : null;
                                            $urlNodin2 = $riwayat->nodin_2
                                                ? (str_contains($riwayat->nodin_2, 'nodin_2/')
                                                    ? file_url($riwayat->nodin_2)
                                                    : file_url('nodin_2/' . $riwayat->nodin_2))
                                                : null;
                                            $urlSertifikat = $riwayat->sertifikat
                                                ? (str_contains($riwayat->sertifikat, 'sertifikat/')
                                                    ? file_url($riwayat->sertifikat)
                                                    : file_url('sertifikat/' . $riwayat->sertifikat))
                                                : null;
                                        @endphp
                                        <tr>
                                            <td>{{ $riwayat->nama ?? '-' }}</td>
                                            <td>{{ $riwayat->nik ?? '-' }}</td>
                                            <td>{{ $riwayat->satker->nama ?? '-' }}</td>
                                            <td>{{ $statusRiwayat }}</td>
                                            <td>{{ $tanggalMulai }} - {{ $tanggalSelesai }}</td>
                                            <td>{{ $riwayat->lamaran?->instansi ?? '-' }}</td>
                                            <td>
                                                @if (!empty($urlNodin1))
                                                    <small>
                                                        <button type="button"
                                                            class="btn btn-sm btn-link text-gold-solid p-0 js-preview-file"
                                                            data-file-url="{{ $urlNodin1 }}"
                                                            data-file-name="Nodin 1 - {{ $riwayat->nama ?? 'Peserta' }}">
                                                            Lihat file
                                                        </button>
                                                    </small>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>
                                                @if (!empty($urlNodin2))
                                                    <small>
                                                        <button type="button"
                                                            class="btn btn-sm btn-link text-gold-solid p-0 js-preview-file"
                                                            data-file-url="{{ $urlNodin2 }}"
                                                            data-file-name="Nodin 2 - {{ $riwayat->nama ?? 'Peserta' }}">
                                                            Lihat file
                                                        </button>
                                                    </small>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>
                                                @if (!empty($urlSertifikat))
                                                    <small>
                                                        <button type="button"
                                                            class="btn btn-sm btn-link text-gold-solid p-0 js-preview-file"
                                                            data-file-url="{{ $urlSertifikat }}"
                                                            data-file-name="Sertifikat - {{ $riwayat->nama ?? 'Peserta' }}">
                                                            Lihat file
                                                        </button>
                                                    </small>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>
                                                {{ $riwayat->nilai !== null ? number_format((float) $riwayat->nilai, 2) : '-' }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Modal Preview File -->
    <div class="modal fade" id="filePreviewModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content overflow-hidden border-0 shadow-lg" style="border-radius: 20px;">
                <div class="modal-header border-0 bg-white px-4 py-3">
                    <h5 class="modal-title fw-800" id="filePreviewModalLabel">Pratinjau Dokumen</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0 d-flex justify-content-center align-items-center bg-secondary bg-opacity-10"
                    style="min-height: 75vh;">
                    <iframe id="filePreviewFrame" src=""
                        style="width: 100%; height: 75vh; border: 0; display: none;"></iframe>
                    <img id="filePreviewImage" src="" alt="Preview"
                        style="max-width: 100%; max-height: 75vh; display: none; object-fit: contain;" />
                </div>
            </div>
        </div>
    </div>

    <script>
        (function() {
            const toggles = document.querySelectorAll('.pw-toggle');
            toggles.forEach(tg => {
                tg.addEventListener('click', function() {
                    const id = this.getAttribute('data-target');
                    const input = document.getElementById(id);
                    if (!input) return;
                    const icon = this.querySelector('i');
                    if (input.type === 'password') {
                        input.type = 'text';
                        if (icon) {
                            icon.classList.remove('ri-eye-off-line');
                            icon.classList.add('ri-eye-line');
                        }
                    } else {
                        input.type = 'password';
                        if (icon) {
                            icon.classList.remove('ri-eye-line');
                            icon.classList.add('ri-eye-off-line');
                        }
                    }
                });
            });

            const newPw = document.getElementById('new_password');
            const confirmPw = document.getElementById('new_password_confirmation');
            const cancelBtn = document.getElementById('btnCancelChangePassword');
            const form = document.getElementById('formChangePassword');

            function setRule(elId, ok) {
                const el = document.getElementById(elId);
                if (!el) return;
                el.style.color = ok ? '#16a34a' : '#dc2626';
                const icon = el.querySelector('i');
                if (!icon) return;
                icon.classList.remove('ri-close-circle-line', 'ri-checkbox-circle-line');
                icon.classList.add(ok ? 'ri-checkbox-circle-line' : 'ri-close-circle-line');
            }

            function validateRules() {
                if (!newPw) return;
                const v = newPw.value || '';
                setRule('rule-length', v.length >= 12);
                setRule('rule-lower', /[a-z]/.test(v));
                setRule('rule-upper', /[A-Z]/.test(v));
                setRule('rule-digit', /\d/.test(v));
                setRule('rule-symbol', /[^A-Za-z0-9]/.test(v));

                // simple confirm indicator (bootstrap invalid class)
                if (confirmPw && confirmPw.value.length > 0) {
                    const match = confirmPw.value === v;
                    confirmPw.classList.toggle('is-invalid', !match);
                }
            }

            if (newPw) newPw.addEventListener('input', validateRules);
            if (confirmPw) confirmPw.addEventListener('input', validateRules);
            validateRules();

            if (cancelBtn) {
                cancelBtn.addEventListener('click', function() {
                    if (form) form.reset();
                    if (newPw) validateRules();
                });
            }

            const copyBtn = document.getElementById('btnCopyTotpKey');
            const setupKeyEl = document.getElementById('totpSetupKey');
            if (copyBtn && setupKeyEl) {
                copyBtn.addEventListener('click', async function() {
                    try {
                        await navigator.clipboard.writeText(setupKeyEl.textContent.trim());
                        copyBtn.textContent = 'Tersalin';
                        setTimeout(() => {
                            copyBtn.textContent = 'Salin';
                        }, 1200);
                    } catch (e) {
                        // fallback sederhana jika clipboard API tidak tersedia
                        const range = document.createRange();
                        range.selectNode(setupKeyEl);
                        window.getSelection().removeAllRanges();
                        window.getSelection().addRange(range);
                        document.execCommand('copy');
                        window.getSelection().removeAllRanges();
                        copyBtn.textContent = 'Tersalin';
                        setTimeout(() => {
                            copyBtn.textContent = 'Salin';
                        }, 1200);
                    }
                });
            }

            const copyBtnPegawai = document.getElementById('btnCopyPegawaiTotpKey');
            const setupKeyPegawaiEl = document.getElementById('pegawaiTotpSetupKey');
            if (copyBtnPegawai && setupKeyPegawaiEl) {
                copyBtnPegawai.addEventListener('click', async function() {
                    try {
                        await navigator.clipboard.writeText(setupKeyPegawaiEl.textContent.trim());
                        copyBtnPegawai.textContent = 'Tersalin';
                        setTimeout(() => {
                            copyBtnPegawai.textContent = 'Salin';
                        }, 1200);
                    } catch (e) {
                        const range = document.createRange();
                        range.selectNode(setupKeyPegawaiEl);
                        window.getSelection().removeAllRanges();
                        window.getSelection().addRange(range);
                        document.execCommand('copy');
                        window.getSelection().removeAllRanges();
                        copyBtnPegawai.textContent = 'Tersalin';
                        setTimeout(() => {
                            copyBtnPegawai.textContent = 'Salin';
                        }, 1200);
                    }
                });
            }

            // Preview File Script (mengikuti pola biodata)
            const buttons = document.querySelectorAll('.js-preview-file');
            const frame = document.getElementById('filePreviewFrame');
            const img = document.getElementById('filePreviewImage');
            const modalEl = document.getElementById('filePreviewModal');
            const labelEl = document.getElementById('filePreviewModalLabel');

            document.addEventListener('show.bs.modal', function(event) {
                const m = event.target;
                if (m && m.parentElement !== document.body) {
                    document.body.appendChild(m);
                }
            });

            buttons.forEach(btn => {
                btn.addEventListener('click', function() {
                    const fileUrl = (this.getAttribute('data-file-url') || '').trim();
                    const fileName = this.getAttribute('data-file-name') || 'File';
                    if (!fileUrl) return;

                    const lowerUrl = fileUrl.toLowerCase();
                    const isImage = /\.(jpg|jpeg|png|gif|webp)(\?|#|$)/.test(lowerUrl);

                    if (labelEl) {
                        labelEl.innerHTML = '<i class="ri-file-search-line me-2"></i> ' + fileName;
                    }

                    if (!frame || !img) {
                        window.open(fileUrl, '_blank');
                        return;
                    }

                    frame.src = '';
                    frame.style.display = 'none';
                    img.src = '';
                    img.style.display = 'none';

                    if (isImage) {
                        img.onerror = function() {
                            window.open(fileUrl, '_blank');
                        };
                        img.src = fileUrl;
                        img.style.display = 'block';
                    } else {
                        img.onerror = null;
                        frame.src = fileUrl;
                        frame.style.display = 'block';
                    }

                    if (window.bootstrap && bootstrap.Modal) {
                        const modal = new bootstrap.Modal(modalEl);
                        modal.show();
                    } else {
                        window.open(fileUrl, '_blank');
                    }
                });
            });

            if (modalEl) {
                modalEl.addEventListener('hidden.bs.modal', function() {
                    if (frame) {
                        frame.src = '';
                        frame.style.display = 'none';
                    }
                    if (img) {
                        img.src = '';
                        img.style.display = 'none';
                        img.onerror = null;
                    }
                });
            }
        })();
    </script>
@endsection
