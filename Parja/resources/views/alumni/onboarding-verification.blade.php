<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Verifikasi Alumni | {{ config('app.name', 'ePublic') }}</title>

    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('template/dist/assets/img/favicon.png') }}">
    <link rel="stylesheet" href="{{ asset('template/dist/lib/remixicon/fonts/remixicon.css') }}">
    <link rel="stylesheet" href="{{ asset('template/dist/assets/css/style.min.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('template/dist/lib/select2/css/select2.min.css') }}">
    
    <style>
        /* Komponen Searchable Select */
        .searchable-select { position: relative; }
        .searchable-select-search { position: relative; }
        .searchable-select-search .ri-search-line {
            position: absolute; left: 16px; top: 50%; transform: translateY(-50%);
            color: rgba(55, 65, 81, 0.55); font-size: 1.05rem; pointer-events: none;
        }
        .searchable-select-input { padding-left: 44px; padding-right: 44px; }
        .searchable-select-clear {
            position: absolute; right: 10px; top: 50%; transform: translateY(-50%);
            width: 30px; height: 30px; border: 0; border-radius: 999px;
            background: rgba(191, 0, 80, 0.1); color: var(--auth-primary, #bf0050);
            display: inline-flex; align-items: center; justify-content: center;
        }
        .searchable-select-clear:hover { background: rgba(191, 0, 80, 0.16); }
        .searchable-select-dropdown {
            margin-top: 10px; border: 1px solid rgba(65, 23, 75, 0.14);
            border-radius: 20px; background: rgba(255, 255, 255, 0.98);
            box-shadow: 0 18px 36px rgba(65, 23, 75, 0.12); overflow: hidden;
            position: relative; z-index: 20;
        }
        .searchable-select-status {
            padding: 12px 16px 10px; font-size: 0.78rem; font-weight: 700;
            letter-spacing: 0.04em; text-transform: uppercase; color: #6a4500;
            background: linear-gradient(180deg, rgba(251, 172, 24, 0.16), rgba(251, 172, 24, 0.06));
            border-bottom: 1px solid rgba(65, 23, 75, 0.1);
        }
        .searchable-select-options { max-height: 240px; overflow-y: auto; padding: 8px; }
        .searchable-select-option, .searchable-select-empty {
            width: 100%; border: 0; border-radius: 14px; background: transparent;
            text-align: left; padding: 12px 14px;
        }
        .searchable-select-option {
            display: flex; align-items: center; justify-content: space-between;
            gap: 12px; color: #1f2937; transition: background-color 0.18s ease, color 0.18s ease, transform 0.18s ease;
        }
        .searchable-select-option:hover, .searchable-select-option.is-active {
            background: rgba(191, 0, 80, 0.08); color: var(--auth-primary-dark, #41174b); transform: translateX(2px);
        }
        .searchable-select-option.is-selected {
            background: linear-gradient(135deg, rgba(191, 0, 80, 0.12), rgba(251, 172, 24, 0.2));
            color: var(--auth-primary-dark, #41174b); font-weight: 700;
        }
        .searchable-select-option-mark { flex-shrink: 0; color: var(--auth-primary, #bf0050); opacity: 0; }
        .searchable-select-option.is-selected .searchable-select-option-mark { opacity: 1; }
        .searchable-select-empty { color: var(--auth-muted, #6b7280); cursor: default; }
        .searchable-select-meta { margin-top: 8px; display: flex; align-items: center; justify-content: space-between; gap: 10px; flex-wrap: wrap; }
        .searchable-select-selection {
            display: inline-flex; align-items: center; gap: 8px; padding: 7px 12px;
            border-radius: 999px; background: rgba(251, 172, 24, 0.22);
            color: var(--auth-primary-dark, #41174b); font-size: 0.84rem; font-weight: 700;
        }
        .searchable-select-selection[hidden] { display: none !important; }
        .searchable-select-selection i { color: var(--auth-primary, #bf0050); }
        .searchable-select-selection-label { opacity: 0.8; }
        .searchable-select-match { padding: 0 2px; border-radius: 4px; background: rgba(251, 172, 24, 0.35); color: #5b3900; }
        .searchable-select-input.is-invalid { border-color: var(--bs-danger, #dc3545); }
    </style>

    <style>
        :root {
            --parja-magenta: #bf0050;
            --parja-purple: #41174b;
            --parja-gold: #fbac18;
            --parja-soft: #f8eef5;
            --parja-white: #ffffff;
            --parja-text: #241a2b;
            --parja-muted: #695a72;
            --parja-border: rgba(65, 23, 75, 0.16);
        }

        *, *::before, *::after { box-sizing: border-box; }

        body {
            min-height: 100vh;
            margin: 0;
            padding: 28px 16px;
            font-family: 'Plus Jakarta Sans', 'Segoe UI', sans-serif;
            font-size: 0.9rem;
            color: var(--parja-text);
            background:
                radial-gradient(circle at 5% 8%, rgba(191, 0, 80, 0.18), transparent 28%),
                radial-gradient(circle at 92% 14%, rgba(251, 172, 24, 0.18), transparent 26%),
                radial-gradient(circle at 50% 95%, rgba(65, 23, 75, 0.12), transparent 30%),
                linear-gradient(130deg, #fffafb 0%, #fffef8 46%, #f5eef9 100%);
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        /* ── Header bar ──────────────────────────────────────────── */
        .ob-header {
            width: 100%;
            max-width: 860px;
            display: flex;
            align-items: center;
            gap: 14px;
            padding-bottom: 24px;
        }

        .ob-logo-ring {
            width: 46px;
            height: 46px;
            border-radius: 14px;
            background: linear-gradient(135deg, var(--parja-purple), var(--parja-magenta));
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            box-shadow: 0 6px 18px rgba(191, 0, 80, 0.28);
        }

        .ob-logo-ring i {
            color: #fff;
            font-size: 1.4rem;
        }

        .ob-header-text h1 {
            margin: 0;
            font-size: 1.05rem;
            font-weight: 800;
            color: var(--parja-purple);
            line-height: 1.2;
        }

        .ob-header-text p {
            margin: 2px 0 0;
            font-size: 0.78rem;
            color: var(--parja-muted);
        }

        .ob-header-logout {
            margin-left: auto;
        }

        .ob-header-logout form button {
            background: none;
            border: 1px solid var(--parja-border);
            border-radius: 8px;
            padding: 6px 14px;
            font-size: 0.78rem;
            color: var(--parja-muted);
            cursor: pointer;
            font-family: inherit;
            display: flex;
            align-items: center;
            gap: 5px;
            transition: all 0.18s;
        }

        .ob-header-logout form button:hover {
            border-color: var(--parja-magenta);
            color: var(--parja-magenta);
            background: rgba(191, 0, 80, 0.04);
        }

        /* ── Welcome banner ──────────────────────────────────────── */
        .ob-welcome {
            width: 100%;
            max-width: 860px;
            border-radius: 18px;
            background: linear-gradient(135deg, var(--parja-purple) 0%, var(--parja-magenta) 100%);
            padding: 22px 28px;
            display: flex;
            align-items: flex-start;
            gap: 16px;
            margin-bottom: 20px;
            box-shadow: 0 10px 32px rgba(65, 23, 75, 0.22);
            position: relative;
            overflow: hidden;
        }

        .ob-welcome::after {
            content: '';
            position: absolute;
            right: -60px;
            top: -60px;
            width: 220px;
            height: 220px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.08);
            pointer-events: none;
        }

        .ob-welcome-icon {
            font-size: 2.4rem;
            color: var(--parja-gold);
            flex-shrink: 0;
            line-height: 1;
            margin-top: 2px;
        }

        .ob-welcome-body h2 {
            margin: 0 0 4px;
            font-size: 1.05rem;
            font-weight: 700;
            color: #fff;
        }

        .ob-welcome-body p {
            margin: 0;
            font-size: 0.82rem;
            color: rgba(255, 255, 255, 0.85);
            line-height: 1.5;
        }

        /* ── Main card ───────────────────────────────────────────── */
        .ob-card {
            width: 100%;
            max-width: 860px;
            background: #fff;
            border-radius: 22px;
            border: 1px solid var(--parja-border);
            box-shadow: 0 16px 48px rgba(65, 23, 75, 0.10);
            overflow: hidden;
        }

        .ob-card-body {
            padding: 28px;
        }

        /* ── Alert flash ─────────────────────────────────────────── */
        .ob-alert {
            border-radius: 10px;
            padding: 12px 16px;
            margin-bottom: 18px;
            font-size: 0.83rem;
            display: flex;
            align-items: flex-start;
            gap: 10px;
        }

        .ob-alert.error {
            background: #fff1f1;
            border: 1px solid rgba(180, 35, 24, 0.22);
            color: #b42318;
        }

        .ob-alert i { font-size: 1rem; margin-top: 1px; flex-shrink: 0; }

        /* ── Stepper ─────────────────────────────────────────────── */
        .parja-stepper-wrap {
            border: 1px solid rgba(65, 23, 75, 0.2);
            border-radius: 14px;
            background: #fffaf2;
            padding: 14px;
            margin-bottom: 16px;
        }

        .parja-stepper-head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
            margin-bottom: 10px;
        }

        .parja-stepper-title {
            margin: 0;
            font-weight: 700;
            color: var(--parja-purple);
            font-size: 1rem;
        }

        .parja-stepper-subtitle {
            margin: 2px 0 0;
            color: var(--parja-muted);
            font-size: 0.85rem;
        }

        .parja-stepper-count {
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--parja-purple);
            background: #fff;
            border: 1px solid rgba(65, 23, 75, 0.24);
            border-radius: 999px;
            padding: 4px 10px;
        }

        .parja-stepper-track {
            width: 100%;
            height: 8px;
            border-radius: 999px;
            background: rgba(65, 23, 75, 0.12);
            overflow: hidden;
            margin-bottom: 12px;
        }

        .parja-stepper-track > span {
            display: block;
            height: 100%;
            width: 0;
            border-radius: 999px;
            background: linear-gradient(90deg, var(--parja-purple), var(--parja-magenta));
            transition: width 0.28s ease;
        }

        .parja-stepper-list {
            display: grid;
            grid-template-columns: repeat(5, minmax(120px, 1fr));
            gap: 8px;
        }

        .parja-step-item {
            border: 1px solid rgba(65, 23, 75, 0.18);
            border-radius: 10px;
            padding: 8px;
            background: #fff;
            color: var(--parja-muted);
            cursor: pointer;
            text-align: left;
            transition: all 0.2s ease;
            font-family: inherit;
        }

        .parja-step-item small {
            display: block;
            color: #8d7f95;
            font-size: 0.72rem;
        }

        .parja-step-item.active {
            border-color: var(--parja-magenta);
            background: #fff;
            color: var(--parja-magenta);
            box-shadow: 0 3px 10px rgba(65, 23, 75, 0.14);
        }

        .parja-step-item.done {
            border-color: rgba(251, 172, 24, 0.58);
            background: #fff8ea;
            color: var(--parja-purple);
        }

        .parja-step-pane { display: none; }

        .parja-step-pane.active {
            display: block;
            animation: parjaFadeIn 0.18s ease;
        }

        @keyframes parjaFadeIn {
            from { opacity: 0; transform: translateY(4px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .parja-step-note {
            border-left: 3px solid var(--parja-magenta);
            background: #fff8ef;
            padding: 10px 12px;
            border-radius: 8px;
            color: var(--parja-muted);
            font-size: 0.85rem;
            margin-bottom: 14px;
        }

        .parja-step-actions {
            margin-top: 20px;
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        /* ── Form controls ───────────────────────────────────────── */
        .form-label { font-weight: 600; font-size: 0.82rem; color: var(--parja-purple); margin-bottom: 4px; }
        .form-label .req { color: var(--parja-magenta); margin-left: 2px; }
        .form-control, .form-select { font-size: 0.875rem; border-radius: 9px; border-color: var(--parja-border); }
        .form-control:focus, .form-select:focus { border-color: var(--parja-magenta); box-shadow: 0 0 0 3px rgba(191, 0, 80, 0.12); }
        .form-text { font-size: 0.75rem; color: var(--parja-muted); }

        /* ── Buttons ─────────────────────────────────────────────── */
        .btn-parja-primary {
            background: linear-gradient(135deg, var(--parja-purple), var(--parja-magenta));
            color: #fff;
            border: none;
            border-radius: 10px;
            padding: 9px 22px;
            font-weight: 600;
            font-size: 0.875rem;
            font-family: inherit;
            cursor: pointer;
            transition: opacity 0.18s, transform 0.15s;
        }

        .btn-parja-primary:hover { opacity: 0.89; transform: translateY(-1px); }

        .btn-parja-outline {
            background: none;
            border: 1px solid var(--parja-border);
            border-radius: 10px;
            padding: 9px 18px;
            font-weight: 500;
            font-size: 0.875rem;
            font-family: inherit;
            color: var(--parja-muted);
            cursor: pointer;
            transition: all 0.18s;
        }

        .btn-parja-outline:hover { border-color: var(--parja-purple); color: var(--parja-purple); }

        /* ── Photo preview ───────────────────────────────────────── */
        #fotoProfilPreview {
            width: 90px; height: 90px;
            object-fit: cover;
            border-radius: 14px;
            border: 1px solid var(--parja-border);
        }

        /* ── Toast ───────────────────────────────────────────────── */
        .parja-mini-toast {
            position: fixed;
            right: 18px; bottom: 18px;
            z-index: 1090;
            min-width: 260px; max-width: 360px;
            border-radius: 12px;
            padding: 10px 14px;
            color: #fff;
            font-size: 0.85rem;
            box-shadow: 0 10px 22px rgba(0,0,0,0.2);
            opacity: 0; transform: translateY(10px);
            pointer-events: none;
            transition: opacity 0.2s ease, transform 0.2s ease;
            font-family: inherit;
        }
        .parja-mini-toast .toast-content { display: inline-flex; align-items: center; gap: 8px; }
        .parja-mini-toast.show { opacity: 1; transform: translateY(0); }
        .parja-mini-toast.error { background: #b42318; }
        .parja-mini-toast.info  { background: var(--parja-magenta); }

        /* ── Footer ──────────────────────────────────────────────── */
        .ob-footer {
            margin-top: 20px;
            font-size: 0.75rem;
            color: var(--parja-muted);
            text-align: center;
        }

        /* ── Responsive ──────────────────────────────────────────── */
        @media (max-width: 991px) {
            .parja-stepper-list { grid-template-columns: repeat(2, minmax(120px, 1fr)); }
        }
        @media (max-width: 575px) {
            .parja-stepper-list { grid-template-columns: 1fr; }
            .ob-card-body { padding: 18px; }
            .ob-welcome { padding: 18px; }
            body { padding: 16px 10px; }
        }
    </style>
</head>
<body>

    {{-- ── Header ──────────────────────────────────────────────────── --}}
    <header class="ob-header">
        <div class="ob-logo-ring">
            <i class="ri-user-star-line"></i>
        </div>
        <div class="ob-header-text">
            <h1>Portal Alumni Parja</h1>
            <p>Selamat datang, <strong>{{ (auth()->guard('keycloak-external')->user()?->name ?? auth()->guard('keycloak')->user()?->name ?? '') }}</strong></p>
        </div>
        <div class="ob-header-logout">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit"><i class="ri-logout-box-r-line"></i> Keluar</button>
            </form>
        </div>
    </header>

    {{-- ── Welcome Banner ──────────────────────────────────────────── --}}
    <div class="ob-welcome">
        <div class="ob-welcome-icon"><i class="ri-shield-user-line"></i></div>
        <div class="ob-welcome-body">
            <h2>Verifikasi Keanggotaan Alumni (Tahap 1 dari 2)</h2>
            <p>
                Akun login Anda sudah aktif lewat SSO DPR. Tahap selanjutnya: kirim data dasar +
                <strong>bukti kelulusan PDF</strong> untuk diverifikasi admin. Setelah disetujui, Anda akan diminta
                melengkapi profil lebih detail (pekerjaan, pendidikan, dst).
            </p>
        </div>
    </div>

    {{-- ── Main card ───────────────────────────────────────────────── --}}
    <div class="ob-card">
        <div class="ob-card-body">

            {{-- Validation errors --}}
            @if ($errors->any())
                <div class="ob-alert error">
                    <i class="ri-error-warning-line"></i>
                    <div>
                        <strong>Terdapat kesalahan pada formulir:</strong>
                        <ul class="mb-0 mt-1 ps-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            @if (!$alumni)
                <div class="ob-alert error">
                    <i class="ri-error-warning-line"></i>
                    <div>Data alumni Anda belum terhubung. Hubungi admin untuk sinkronisasi akun.</div>
                </div>
            @else
                <form id="onboardingForm" method="POST" action="{{ route('alumni.onboarding.submit') }}" enctype="multipart/form-data">
                    @csrf

                    {{-- ── Stepper nav (2 step: Identitas + Keanggotaan) ─── --}}
                    <div class="parja-stepper-wrap">
                        <div class="parja-stepper-head">
                            <div>
                                <p class="parja-stepper-title mb-0">Verifikasi Akun Alumni</p>
                                <p id="parjaStepDescription" class="parja-stepper-subtitle">Lengkapi identitas utama terlebih dahulu.</p>
                            </div>
                            <span id="parjaStepCount" class="parja-stepper-count">Tahap 1 dari 2</span>
                        </div>

                        <div class="parja-stepper-track">
                            <span id="parjaStepProgress"></span>
                        </div>

                        <div class="parja-stepper-list" role="tablist" aria-label="Tahapan verifikasi alumni">
                            <button type="button" class="parja-step-item active" data-step-target="1" aria-current="step">
                                1. Identitas Dasar
                                <small>Nama, NIK, kontak</small>
                            </button>
                            <button type="button" class="parja-step-item" data-step-target="2">
                                2. Keanggotaan & Bukti
                                <small>Dapil, angkatan, sertifikat</small>
                            </button>
                        </div>
                    </div>

                    {{-- ── Step 1: Data Dasar ───────────────────────── --}}
                    <div class="parja-step-pane active" data-step="1">
                        <div class="parja-step-note">
                            Tahap 1 fokus pada identitas inti. <span style="color:var(--parja-magenta);font-weight:600;">Asal Sekolah Parja dan Nomor Telepon wajib diisi.</span> Nama dan Asal Sekolah diambil dari data pendaftaran dan tidak dapat diubah di sini.
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Nama</label>
                                <input type="text" name="nama" class="form-control"
                                    value="{{ $alumni->nama ?? (auth()->guard('keycloak-external')->user()?->name ?? auth()->guard('keycloak')->user()?->name ?? '') }}"
                                    readonly style="background:#fafafa;color:var(--parja-muted);">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email Login</label>
                                <input type="text" class="form-control"
                                    value="{{ (auth()->guard('keycloak-external')->user()?->email ?? auth()->guard('keycloak')->user()?->email ?? '') }}" readonly
                                    style="background:#fafafa;color:var(--parja-muted);">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">NIK KTP <span class="req">*</span></label>
                                <input type="text" name="nik_ktp" id="nik_ktp" class="form-control"
                                    inputmode="numeric"
                                    pattern="[0-9]{16}"
                                    maxlength="16"
                                    minlength="16"
                                    placeholder="16 digit NIK sesuai KTP"
                                    value="{{ old('nik_ktp', $alumni->nik_ktp ?? '') }}"
                                    oninput="this.value=this.value.replace(/[^0-9]/g,'').slice(0,16)"
                                    required>
                                <div class="form-text">16 digit angka tanpa spasi. Dipakai admin untuk validasi data kependudukan.</div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Nomor Telepon / WhatsApp <span class="req">*</span></label>
                                <input type="tel" name="notelp" id="notelp" class="form-control"
                                    inputmode="numeric"
                                    pattern="[0-9]{6,15}"
                                    maxlength="15"
                                    minlength="6"
                                    placeholder="Contoh: 08123456789"
                                    value="{{ old('notelp', $profile?->notelp ?? '') }}"
                                    oninput="this.value=this.value.replace(/[^0-9]/g,'')"
                                    required>
                                <div class="form-text">Hanya digit angka, 6–15 karakter. Tidak ditampilkan publik.</div>
                            </div>

                            <div class="col-md-12">
                                <label class="form-label">Asal Sekolah Parja <span class="req">*</span></label>
                                <input type="text" name="asal_sekolah" class="form-control"
                                    value="{{ old('asal_sekolah', $alumni->asal_sekolah ?? '') }}"
                                    placeholder="Mis: SMAN 4 Banjar"
                                    maxlength="255"
                                    required>
                                <div class="form-text">Sekolah asal saat mengikuti Parja. Wajib sesuai bukti kelulusan yang diunggah.</div>
                            </div>
                        </div>
                    </div>

                    {{-- ── Step 2: Keanggotaan ───────────────────────── --}}
                    <div class="parja-step-pane" data-step="2">
                        <div class="parja-step-note">
                            Tahap 2 mengatur data keanggotaan. <span style="color:var(--parja-magenta);font-weight:600;">Dapil dan Tahun Angkatan wajib dipilih/diisi.</span>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-4">
                                @php
                                    $dapilOptions = [];
                                    foreach ($dapils as $d) {
                                        $dapilOptions[] = [
                                            'value' => (string) $d->id,
                                            'label' => trim($d->dapil),
                                        ];
                                    }
                                @endphp
                                @include('components.searchable-select', [
                                    'name' => 'id_dapil',
                                    'id' => 'id_dapil',
                                    'label' => 'Dapil',
                                    'options' => $dapilOptions,
                                    'value' => old('id_dapil', $selectedDapilId),
                                    'required' => true,
                                    'placeholder' => 'Cari dan pilih dapil...',
                                    'helper' => 'Cari berdasarkan nama dapil, lalu pilih dari daftar referensi yang tersedia di sistem.',
                                    'emptyText' => 'Dapil yang Anda cari belum ditemukan di referensi.',
                                    'statusText' => 'Pilih salah satu dapil',
                                    'resultsSuffix' => 'dapil ditemukan',
                                    'selectionLabel' => 'Dapil terpilih',
                                    'errorKey' => 'id_dapil',
                                ])
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Tahun Angkatan <span class="req">*</span></label>
                                @php
                                    $selectedTahun = old('tahun_angkatan', $alumni->tahun_angkatan ?? '');
                                    $currentYear = (int) date('Y');
                                @endphp
                                <select name="tahun_angkatan" id="tahun_angkatan_select" class="form-control" required>
                                    <option value="">-- Pilih Tahun --</option>
                                    @for ($y = $currentYear; $y >= 2008; $y--)
                                        <option value="{{ $y }}" {{ (string) $selectedTahun === (string) $y ? 'selected' : '' }}>{{ $y }}</option>
                                    @endfor
                                </select>
                            </div>
                            {{-- No Anggota: tidak perlu diisi untuk angkatan di bawah tahun 2018
                                 (field disembunyikan otomatis lewat JS sesuai pilihan tahun). --}}
                            <div class="col-md-4" id="no_anggota_wrap">
                                <label class="form-label">No Anggota <span class="req">*</span></label>
                                <input type="text" name="no_anggota" class="form-control"
                                    value="{{ old('no_anggota', $alumni->no_anggota ?? '') }}"
                                    placeholder="Contoh: 2024/001">
                                <div class="form-text">Tidak perlu diisi untuk angkatan di bawah tahun 2018.</div>
                            </div>

                            {{-- Bukti Kelulusan: dipakai admin untuk verifikasi keanggotaan alumni --}}
                            <div class="col-md-12">
                                <label class="form-label">
                                    Bukti Kelulusan Parja
                                    @if (empty($alumni?->bukti_alumni))
                                        <span class="req">*</span>
                                    @endif
                                </label>
                                <input type="file" name="sertifikat" class="form-control"
                                    accept=".pdf,.jpg,.jpeg,.png"
                                    @if (empty($alumni?->bukti_alumni)) required @endif>
                                <div class="form-text">
                                    File PDF/JPG/PNG, maks 2&nbsp;MB. Berkas ini digunakan admin untuk verifikasi keanggotaan alumni Anda.
                                    @if (! empty($alumni?->bukti_alumni))
                                        <span class="d-block mt-1 text-success">
                                            <i class="ri-check-line"></i> Sudah ada berkas tersimpan. Unggah file baru hanya jika ingin mengganti.
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Disclaimer (recover dari register lama, posisi: bawah bukti) --}}
                        <div class="row g-3 mt-1">
                            <div class="col-md-12">
                                <div class="p-3 rounded" style="background:#fffdf2;border:1px solid #f2e7c3;">
                                    <h6 class="fw-bold mb-2" style="color:var(--parja-purple);font-size:0.92rem;">Pernyataan Persetujuan</h6>
                                    <p class="mb-3" style="font-size:0.82rem;color:var(--parja-muted);line-height:1.55;">
                                        Data pribadi yang Anda kirim dalam formulir ini hanya digunakan untuk keperluan
                                        keanggotaan Parja Alumni, dan dikelola sesuai ketentuan peraturan perundang-undangan
                                        mengenai perlindungan data pribadi. DPR RI berkomitmen menjaga kerahasiaan dan
                                        keamanan data Anda.
                                    </p>
                                    <div class="form-check">
                                        <input class="form-check-input @error('agree_disclaimer') is-invalid @enderror"
                                               type="checkbox" id="agree_disclaimer" name="agree_disclaimer" value="1"
                                               {{ old('agree_disclaimer') ? 'checked' : '' }} required>
                                        <label class="form-check-label fw-bold" for="agree_disclaimer" style="font-size:0.86rem;color:var(--parja-purple);">
                                            Saya telah membaca dan menyetujui pernyataan di atas <span class="req">*</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ── Step actions ─────────────────────────────── --}}
                    <div class="parja-step-actions">
                        <button type="button" id="parjaStepPrev" class="btn-parja-outline" style="display:none;">
                            <i class="ri-arrow-left-line"></i> Sebelumnya
                        </button>
                        <button type="button" id="parjaStepNext" class="btn-parja-primary">
                            Lanjut ke Keanggotaan <i class="ri-arrow-right-line"></i>
                        </button>
                        <button type="submit" id="parjaStepSubmit" class="btn-parja-primary" style="display:none;">
                            <i class="ri-send-plane-line"></i> Kirim untuk Verifikasi Admin
                        </button>
                    </div>
                </form>
            @endif
        </div>
    </div>

    <p class="ob-footer">
        ePublic - PUSTEKINFO - DPR RI &copy; 2026
    </p>

    <script src="{{ asset('template/dist/lib/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('template/dist/lib/select2/js/select2.min.js') }}"></script>

<script>
// Stepper navigasi 2-step. Tidak validasi per-step (validasi final di submit
// + server-side); stepper cuma untuk UX progressive disclosure.
(function () {
    'use strict';

    var form = document.getElementById('onboardingForm');
    if (!form) { return; }

    var panes       = [].slice.call(document.querySelectorAll('.parja-step-pane'));
    var stepButtons = [].slice.call(document.querySelectorAll('.parja-step-item'));
    var stepCount   = document.getElementById('parjaStepCount');
    var stepDesc    = document.getElementById('parjaStepDescription');
    var stepProg    = document.getElementById('parjaStepProgress');
    var stepPrev    = document.getElementById('parjaStepPrev');
    var stepNext    = document.getElementById('parjaStepNext');
    var stepSubmit  = document.getElementById('parjaStepSubmit');

    var totalSteps = panes.length;
    var current    = 1;

    var stepDescriptions = {
        1: 'Identitas dasar — nama, NIK, kontak.',
        2: 'Data keanggotaan + bukti kelulusan PDF.'
    };

    function activate(step) {
        current = Math.max(1, Math.min(totalSteps, step));

        panes.forEach(function (p) {
            p.classList.toggle('active', parseInt(p.getAttribute('data-step'), 10) === current);
        });

        stepButtons.forEach(function (btn) {
            var target = parseInt(btn.getAttribute('data-step-target'), 10);
            btn.classList.toggle('active', target === current);
            btn.classList.toggle('done', target < current);
        });

        if (stepCount) stepCount.textContent = 'Tahap ' + current + ' dari ' + totalSteps;
        if (stepDesc) stepDesc.textContent  = stepDescriptions[current] || '';
        if (stepProg) stepProg.style.width   = ((current / totalSteps) * 100) + '%';

        if (stepPrev)   stepPrev.style.display   = current > 1 ? '' : 'none';
        if (stepNext)   stepNext.style.display   = current < totalSteps ? '' : 'none';
        if (stepSubmit) stepSubmit.style.display = current === totalSteps ? '' : 'none';
    }

    if (stepNext) stepNext.addEventListener('click', function () { activate(current + 1); });
    if (stepPrev) stepPrev.addEventListener('click', function () { activate(current - 1); });

    stepButtons.forEach(function (btn) {
        btn.addEventListener('click', function () {
            activate(parseInt(btn.getAttribute('data-step-target'), 10));
        });
    });

    activate(1);

    // Browser warning kalau user upload file besar (bukti_alumni > 5 MB)
    var buktiInput = form.querySelector('input[name="sertifikat"]');
    if (buktiInput) {
        buktiInput.addEventListener('change', function () {
            var file = buktiInput.files[0];
            if (file && file.size > 2 * 1024 * 1024) {
                alert('Berkas terlalu besar. Maksimum 2 MB.');
                buktiInput.value = '';
            }
        });
    }
})();

// Tahun Angkatan: select2 + kebijakan angkatan < 2018 tidak perlu No Anggota.
(function () {
    'use strict';

    var sel = document.getElementById('tahun_angkatan_select');
    if (!sel) { return; }

    var hasSelect2 = window.jQuery && window.jQuery.fn && window.jQuery.fn.select2;
    if (hasSelect2) {
        window.jQuery(sel).select2({
            width: '100%',
            placeholder: '-- Pilih Tahun --',
            allowClear: true
        });
    }

    function toggleNoAnggota() {
        var wrap = document.getElementById('no_anggota_wrap');
        if (!wrap) { return; }
        var input = wrap.querySelector('input[name="no_anggota"]');
        var year = parseInt(sel.value, 10);

        if (year && year < 2018) {
            // Angkatan di bawah 2018: sembunyikan & jangan wajibkan No Anggota.
            wrap.style.display = 'none';
            if (input) { input.removeAttribute('required'); input.value = ''; }
        } else {
            wrap.style.display = '';
            if (input) { input.setAttribute('required', 'required'); }
        }
    }

    if (hasSelect2) {
        window.jQuery(sel).on('change', toggleNoAnggota);
    } else {
        sel.addEventListener('change', toggleNoAnggota);
    }

    toggleNoAnggota();
})();
</script>

@stack('scripts')
</body>
</html>
