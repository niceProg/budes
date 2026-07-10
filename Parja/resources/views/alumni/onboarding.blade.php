<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Daftar Ulang Alumni | {{ config('app.name', 'ePublic') }}</title>

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
        <div class="ob-welcome-icon"><i class="ri-quill-pen-ai-line"></i></div>
        <div class="ob-welcome-body">
            <h2>Lengkapi Profil untuk Daftar Ulang</h2>
            <p>
                Akun Anda telah diverifikasi oleh admin. Sebelum mengakses portal alumni, mohon lengkapi data
                daftar ulang berikut. Data ini digunakan untuk direktori dan pendataan resmi alumni Parja.
                Pastikan semua <strong>kolom wajib</strong> terisi dengan benar.
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

                    {{-- ── Stepper nav ──────────────────────────────── --}}
                    <div class="parja-stepper-wrap">
                        <div class="parja-stepper-head">
                            <div>
                                <p class="parja-stepper-title mb-0">Roadmap Daftar Ulang</p>
                                <p id="parjaStepDescription" class="parja-stepper-subtitle">Lengkapi identitas utama terlebih dahulu.</p>
                            </div>
                            <span id="parjaStepCount" class="parja-stepper-count">Tahap 1 dari 5</span>
                        </div>

                        <div class="parja-stepper-track">
                            <span id="parjaStepProgress"></span>
                        </div>

                        <div class="parja-stepper-list" role="tablist" aria-label="Tahapan daftar ulang alumni">
                            <button type="button" class="parja-step-item active" data-step-target="1" aria-current="step">
                                1. Data Dasar
                                <small>Identitas utama</small>
                            </button>
                            <button type="button" class="parja-step-item" data-step-target="2">
                                2. Keanggotaan
                                <small>Dapil dan angkatan</small>
                            </button>
                            <button type="button" class="parja-step-item" data-step-target="3">
                                3. Aktivitas
                                <small>Pendidikan, kerja, domisili</small>
                            </button>
                            <button type="button" class="parja-step-item" data-step-target="4">
                                4. Foto Profil
                                <small>Upload foto</small>
                            </button>
                            <button type="button" class="parja-step-item" data-step-target="5">
                                5. Media Sosial
                                <small>Akun publik alumni</small>
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
                                <label class="form-label">Asal Sekolah Parja <span class="req">*</span></label>
                                <input type="text" name="asal_sekolah" class="form-control"
                                    value="{{ old('asal_sekolah', $alumni->asal_sekolah ?? '') }}"
                                    placeholder="Mis: SMAN 4 Banjar"
                                    maxlength="255"
                                    required>
                                <div class="form-text">Sekolah asal saat mengikuti Parja. Wajib sesuai bukti kelulusan yang diunggah.</div>
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
                                <input type="text" name="tahun_angkatan" class="form-control"
                                    value="{{ old('tahun_angkatan', $alumni->tahun_angkatan ?? '') }}"
                                    placeholder="Contoh: 2024"
                                    maxlength="4" inputmode="numeric"
                                    required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">No Anggota <span class="req">*</span></label>
                                <input type="text" name="no_anggota" class="form-control"
                                    value="{{ old('no_anggota', $alumni->no_anggota ?? '') }}"
                                    placeholder="Contoh: 2024/001"
                                    required>
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
                    </div>

                    {{-- ── Step 3: Aktivitas ─────────────────────────── --}}
                    <div class="parja-step-pane" data-step="3">
                        <div class="parja-step-note">
                            Tahap 3 menampilkan kondisi terkini. <span style="color:var(--parja-magenta);font-weight:600;">Pendidikan Saat Ini wajib dipilih melalui dropdown.</span>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-12">
                                <label class="form-label">Pendidikan Saat Ini <span class="req">*</span></label>
                                <div class="row g-2">
                                    <div class="col-md-4">
                                        <label for="tingkatan_pendidikan" class="form-label small text-muted mb-1">Tingkat Pendidikan</label>
                                        <select id="tingkatan_pendidikan" class="form-select">
                                            <option value="">-- Pilih Tingkat --</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="jurusan_kampus" class="form-label small text-muted mb-1">Jurusan</label>
                                        <select id="jurusan_kampus" class="form-select" disabled>
                                            <option value="">-- Pilih Jurusan --</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="kampus_indonesia" class="form-label small text-muted mb-1">Nama Sekolah / Kampus</label>
                                        <div id="kampus_select_wrapper">
                                            <select id="kampus_indonesia" class="form-select" disabled>
                                                <option value="">-- Pilih Sekolah/Kampus --</option>
                                            </select>
                                        </div>
                                        <input type="text" id="kampus_manual" class="form-control d-none"
                                            placeholder="Ketik nama sekolah/kampus" disabled>
                                    </div>
                                </div>
                                <input type="hidden" name="pendidikan_saat_ini" id="pendidikan_saat_ini"
                                    value="{{ old('pendidikan_saat_ini', $profile?->pendidikan_saat_ini ?? '') }}" required>
                                <div id="pendidikanSaatIniFeedback" class="small text-danger mt-1 d-none">
                                    Pendidikan saat ini wajib dipilih melalui dropdown.
                                </div>
                            </div>

                            <div class="col-md-12">
                                <label class="form-label d-flex justify-content-between align-items-center">
                                    <span>Pekerjaan Saat Ini</span>
                                    <button type="button" class="btn-parja-outline" style="padding: 2px 8px; font-size: 0.8rem;" onclick="window.addRepeater('pekerjaan', 'pekerjaan_saat_ini', 'Pekerjaan (Contoh: Mahasiswa / Karyawan PT X)')">
                                        <i class="ri-add-line"></i> Tambah
                                    </button>
                                </label>
                                <div id="repeater_pekerjaan">
                                    @php
                                        $pekData = old('pekerjaan_saat_ini', $profile?->pekerjaan_saat_ini);
                                        if (is_string($pekData) && !empty($pekData)) {
                                            $decoded = json_decode($pekData, true);
                                            $pekData = $decoded ?: [['judul' => $pekData, 'periode' => '']];
                                        }
                                        $pekData = is_array($pekData) && count($pekData) > 0 ? $pekData : [];
                                        $currYear = date('Y');
                                    @endphp
                                    @foreach($pekData as $i => $item)
                                        @php
                                            $tMulai = $item['tahun_mulai'] ?? '';
                                            $tSelesai = $item['tahun_selesai'] ?? '';
                                            if (empty($tMulai) && empty($tSelesai) && !empty($item['periode'])) {
                                                $parts = explode('-', str_replace(' ', '', $item['periode']));
                                                $tMulai = $parts[0] ?? '';
                                                $tSelesai = $parts[1] ?? '';
                                                if (stripos($tSelesai, 'sekarang') !== false || stripos($tSelesai, 'saatini') !== false) {
                                                    $tSelesai = 'Saat Ini';
                                                }
                                            }
                                        @endphp
                                        <div class="d-flex gap-2 mb-2 repeater-item">
                                            <input type="text" name="pekerjaan_saat_ini[{{ $i }}][judul]" class="form-control" value="{{ $item['judul'] ?? '' }}" placeholder="Pekerjaan (Contoh: Mahasiswa / Karyawan PT X)">
                                            <select name="pekerjaan_saat_ini[{{ $i }}][tahun_mulai]" class="form-select" style="width: 130px; flex-shrink: 0;">
                                                <option value="">-- Mulai --</option>
                                                @for($y = $currYear; $y >= 1990; $y--)
                                                    <option value="{{ $y }}" {{ $tMulai == $y ? 'selected' : '' }}>{{ $y }}</option>
                                                @endfor
                                            </select>
                                            <div class="d-flex align-items-center">-</div>
                                            <select name="pekerjaan_saat_ini[{{ $i }}][tahun_selesai]" class="form-select" style="width: 130px; flex-shrink: 0;">
                                                <option value="">-- Selesai --</option>
                                                <option value="Saat Ini" {{ $tSelesai == 'Saat Ini' ? 'selected' : '' }}>Saat Ini</option>
                                                @for($y = $currYear; $y >= 1990; $y--)
                                                    <option value="{{ $y }}" {{ $tSelesai == $y ? 'selected' : '' }}>{{ $y }}</option>
                                                @endfor
                                            </select>
                                            <button type="button" class="btn btn-outline-danger px-2" onclick="this.closest('.repeater-item').remove()"><i class="ri-subtract-line"></i></button>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="col-md-12">
                                <label class="form-label d-flex justify-content-between align-items-center">
                                    <span>Pengalaman Organisasi</span>
                                    <button type="button" class="btn-parja-outline" style="padding: 2px 8px; font-size: 0.8rem;" onclick="window.addRepeater('organisasi', 'pengalaman_organisasi', 'Nama Organisasi / Jabatan')">
                                        <i class="ri-add-line"></i> Tambah
                                    </button>
                                </label>
                                <div id="repeater_organisasi">
                                    @php
                                        $orgData = old('pengalaman_organisasi', $profile?->pengalaman_organisasi);
                                        if (is_string($orgData) && !empty($orgData)) {
                                            $decoded = json_decode($orgData, true);
                                            $orgData = $decoded ?: [['judul' => $orgData, 'periode' => '']];
                                        }
                                        $orgData = is_array($orgData) && count($orgData) > 0 ? $orgData : [];
                                        $currYear = date('Y');
                                    @endphp
                                    @foreach($orgData as $i => $item)
                                        @php
                                            $tMulai = $item['tahun_mulai'] ?? '';
                                            $tSelesai = $item['tahun_selesai'] ?? '';
                                            if (empty($tMulai) && empty($tSelesai) && !empty($item['periode'])) {
                                                $parts = explode('-', str_replace(' ', '', $item['periode']));
                                                $tMulai = $parts[0] ?? '';
                                                $tSelesai = $parts[1] ?? '';
                                                if (stripos($tSelesai, 'sekarang') !== false || stripos($tSelesai, 'saatini') !== false) {
                                                    $tSelesai = 'Saat Ini';
                                                }
                                            }
                                        @endphp
                                        <div class="d-flex gap-2 mb-2 repeater-item">
                                            <input type="text" name="pengalaman_organisasi[{{ $i }}][judul]" class="form-control" value="{{ $item['judul'] ?? '' }}" placeholder="Nama Organisasi / Jabatan">
                                            <select name="pengalaman_organisasi[{{ $i }}][tahun_mulai]" class="form-select" style="width: 130px; flex-shrink: 0;">
                                                <option value="">-- Mulai --</option>
                                                @for($y = $currYear; $y >= 1990; $y--)
                                                    <option value="{{ $y }}" {{ $tMulai == $y ? 'selected' : '' }}>{{ $y }}</option>
                                                @endfor
                                            </select>
                                            <div class="d-flex align-items-center">-</div>
                                            <select name="pengalaman_organisasi[{{ $i }}][tahun_selesai]" class="form-select" style="width: 130px; flex-shrink: 0;">
                                                <option value="">-- Selesai --</option>
                                                <option value="Saat Ini" {{ $tSelesai == 'Saat Ini' ? 'selected' : '' }}>Saat Ini</option>
                                                @for($y = $currYear; $y >= 1990; $y--)
                                                    <option value="{{ $y }}" {{ $tSelesai == $y ? 'selected' : '' }}>{{ $y }}</option>
                                                @endfor
                                            </select>
                                            <button type="button" class="btn btn-outline-danger px-2" onclick="this.closest('.repeater-item').remove()"><i class="ri-subtract-line"></i></button>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="col-md-12">
                                <label class="form-label d-flex justify-content-between align-items-center">
                                    <span>Prestasi Terbaru</span>
                                    <button type="button" class="btn-parja-outline" style="padding: 2px 8px; font-size: 0.8rem;" onclick="window.addRepeater('prestasi', 'prestasi_terbaru', 'Penghargaan atau pencapaian')">
                                        <i class="ri-add-line"></i> Tambah
                                    </button>
                                </label>
                                <div id="repeater_prestasi">
                                    @php
                                        $presData = old('prestasi_terbaru', $profile?->prestasi_terbaru);
                                        if (is_string($presData) && !empty($presData)) {
                                            $decoded = json_decode($presData, true);
                                            $presData = $decoded ?: [['judul' => $presData, 'periode' => '']];
                                        }
                                        $presData = is_array($presData) && count($presData) > 0 ? $presData : [];
                                    @endphp
                                    @foreach($presData as $i => $item)
                                        <div class="d-flex gap-2 mb-2 repeater-item">
                                            <input type="text" name="prestasi_terbaru[{{ $i }}][judul]" class="form-control" value="{{ $item['judul'] ?? '' }}" placeholder="Penghargaan atau pencapaian">
                                            <input type="text" name="prestasi_terbaru[{{ $i }}][periode]" class="form-control" value="{{ $item['periode'] ?? '' }}" placeholder="Periode Tahun (Cth: 2024)" style="width: 210px; flex-shrink: 0;">
                                            <button type="button" class="btn btn-outline-danger px-2" onclick="this.closest('.repeater-item').remove()"><i class="ri-subtract-line"></i></button>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="col-md-12">
                                <label class="form-label">Domisili Terakhir<span class="req">*</span></label>
                                @php
                                    $selectedDomisili = old('domisili_terakhir', $profile?->domisili_terakhir ?? '');
                                    $selDomParts   = $selectedDomisili ? array_map('trim', explode(',', $selectedDomisili, 2)) : [];
                                    $selDomCity    = $selDomParts[0] ?? '';
                                    $selDomProv    = $selDomParts[1] ?? '';
                                    $domisiliGroups = $domisiliGroups ?? [];
                                    $isDomLegacyOb = !empty($selectedDomisili) && !isset($domisiliGroups[$selDomProv]);
                                @endphp
                                {{-- Province selector --}}
                                <select id="domisili_provinsi_ob" class="form-select mb-2">
                                    <option value="">-- Pilih Provinsi --</option>
                                    @foreach(array_keys($domisiliGroups) as $prov)
                                        <option value="{{ $prov }}" {{ $selDomProv === $prov ? 'selected' : '' }}>{{ $prov }}</option>
                                    @endforeach
                                </select>
                                {{-- City / Regency selector --}}
                                <select id="domisili_kota_ob" class="form-select"
                                        {{ empty($selDomProv) && !$isDomLegacyOb ? 'disabled' : '' }}>
                                    <option value="">-- Pilih Kota/Kabupaten --</option>
                                    @if($isDomLegacyOb && !empty($selectedDomisili))
                                        <option value="{{ $selectedDomisili }}" selected>{{ $selectedDomisili }} (data lama)</option>
                                    @endif
                                </select>
                                {{-- Actual submitted value --}}
                                <input type="hidden" name="domisili_terakhir" id="domisili_terakhir"
                                       value="{{ $selectedDomisili }}">
                                @error('domisili_terakhir')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- ── Step 4: Foto Profil ──────────────────────── --}}
                    <div class="parja-step-pane" data-step="4">
                        <div class="parja-step-note">
                            Unggah foto profil terbaik Anda. Opsional, namun sangat disarankan agar profil terlihat
                            lengkap di direktori alumni.
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Foto Profil</label>
                                <input type="file" id="fotoProfilInput" name="foto_profil"
                                    class="form-control" accept=".jpg,.jpeg,.png,.webp">
                                <small class="form-text">Format: JPG, JPEG, PNG, WEBP. Maks 2 MB. Idealnya min 512×512 px.</small>
                                <div id="fotoProfilMeta" class="small text-muted mt-1"></div>
                                <button type="button" id="fotoProfilReset"
                                    class="btn-parja-outline mt-2" style="font-size:0.78rem;padding:6px 12px;">
                                    Reset Pilihan Foto
                                </button>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label d-block">Preview</label>
                                @php $existingPhoto = !empty($profile?->foto_profil) ? asset('storage/' . $profile->foto_profil) : ''; @endphp
                                <img id="fotoProfilPreview" src="{{ $existingPhoto }}" alt="Preview"
                                    style="{{ $existingPhoto === '' ? 'display:none;' : '' }}">
                                <span id="fotoProfilEmptyText" class="text-muted small"
                                    style="{{ $existingPhoto !== '' ? 'display:none;' : '' }}">Belum ada foto profil.</span>
                            </div>
                        </div>
                    </div>

                    {{-- ── Step 5: Media Sosial ─────────────────────── --}}
                    <div class="parja-step-pane" data-step="5">
                        <div class="parja-step-note">
                            Tahap terakhir. Isian media sosial bersifat opsional — dapat diisi atau dilewati.
                            Klik <strong>Selesai & Masuk</strong> untuk menyelesaikan daftar ulang.
                        </div>

                        @php $medsos = is_array($profile?->akun_medsos ?? null) ? $profile->akun_medsos : []; @endphp
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label">Instagram</label>
                                <input type="text" name="medsos_instagram" class="form-control"
                                    value="{{ old('medsos_instagram', $medsos['instagram'] ?? '') }}"
                                    placeholder="URL atau @username">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Twitter / X</label>
                                <input type="text" name="medsos_twitter" class="form-control"
                                    value="{{ old('medsos_twitter', $medsos['twitter'] ?? '') }}"
                                    placeholder="URL atau @username">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">LinkedIn</label>
                                <input type="text" name="medsos_linkedin" class="form-control"
                                    value="{{ old('medsos_linkedin', $medsos['linkedin'] ?? '') }}"
                                    placeholder="URL atau @username">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Facebook</label>
                                <input type="text" name="medsos_facebook" class="form-control"
                                    value="{{ old('medsos_facebook', $medsos['facebook'] ?? '') }}"
                                    placeholder="URL atau @username">
                            </div>
                        </div>
                    </div>

                    {{-- ── Step actions ─────────────────────────────── --}}
                    <div class="parja-step-actions">
                        <button type="button" id="parjaStepPrev" class="btn-parja-outline" style="display:none;">
                            <i class="ri-arrow-left-line"></i> Sebelumnya
                        </button>
                        <button type="button" id="parjaStepNext" class="btn-parja-primary">
                            Lanjut Tahap Berikutnya <i class="ri-arrow-right-line"></i>
                        </button>
                        <button type="submit" id="parjaStepSubmit" class="btn-parja-primary" style="display:none;">
                            <i class="ri-check-double-line"></i> Selesai &amp; Masuk ke Portal
                        </button>
                    </div>
                </form>
            @endif
        </div>
    </div>

    <p class="ob-footer">
        ePublic - PUSTEKINFO - DPR RI &copy; 2026
    </p>

    {{-- Toast notification --}}
    <div id="fotoProfilToast" class="parja-mini-toast" role="status" aria-live="polite"></div>

    <script src="{{ asset('template/dist/lib/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('template/dist/lib/select2/js/select2.min.js') }}"></script>

<script>
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

    var tingkatanSelect    = document.getElementById('tingkatan_pendidikan');
    var jurusanSelect      = document.getElementById('jurusan_kampus');
    var kampusSelectWrapper = document.getElementById('kampus_select_wrapper');
    var kampusSelect       = document.getElementById('kampus_indonesia');
    var kampusManualInput  = document.getElementById('kampus_manual');
    var pendidikanHidden   = document.getElementById('pendidikan_saat_ini');
    var pendidikanFeedback = document.getElementById('pendidikanSaatIniFeedback');

    // ── Chained Domisili (Provinsi → Kota/Kabupaten) ──────────────────
    (function () {
        var groups  = @json($domisiliGroups ?? []);
        var provSel = document.getElementById('domisili_provinsi_ob');
        var kotaSel = document.getElementById('domisili_kota_ob');
        var hidden  = document.getElementById('domisili_terakhir');

        function populateCities(province, selectedVal) {
            kotaSel.innerHTML = '<option value="">-- Pilih Kota/Kabupaten --</option>';
            var cities = groups[province] || [];
            cities.forEach(function (city) {
                var opt = document.createElement('option');
                opt.value = city + ', ' + province;
                opt.textContent = city;
                if (opt.value === selectedVal) opt.selected = true;
                kotaSel.appendChild(opt);
            });
            kotaSel.disabled = cities.length === 0;
        }

        var initVal = hidden ? hidden.value : '';
        if (initVal && provSel) {
            var parts = initVal.split(', ');
            var prov  = parts.length > 1 ? parts[parts.length - 1] : '';
            if (prov && groups[prov]) {
                provSel.value = prov;
                populateCities(prov, initVal);
            }
        }

        if (provSel) {
            provSel.addEventListener('change', function () {
                populateCities(this.value, '');
                if (hidden) hidden.value = '';
            });
        }
        if (kotaSel) {
            kotaSel.addEventListener('change', function () {
                if (hidden) hidden.value = this.value;
            });
        }
    })();
    // ─────────────────────────────────────────────────────────────────────

    var toast      = document.getElementById('fotoProfilToast');
    var toastTimer = null;

    var totalSteps = panes.length;
    var activeStep = 1;

    var firstErrorField = @json($errors->keys()[0] ?? null);
    var fieldStepMap    = {
        asal_sekolah_parja: 1, notelp: 1,
        id_dapil: 2, tahun_angkatan: 2, no_anggota: 2, angkatan: 2,
        pendidikan_saat_ini: 3, pekerjaan_saat_ini: 3, domisili_terakhir: 3, prestasi_terbaru: 3,
        foto_profil: 4,
        medsos_instagram: 5, medsos_twitter: 5, medsos_linkedin: 5, medsos_facebook: 5
    };

    var stepMeta = {
        1: 'Lengkapi identitas utama terlebih dahulu.',
        2: 'Sesuaikan data keanggotaan agar terdaftar dengan benar.',
        3: 'Tambahkan informasi perkembangan dan aktivitas terkini.',
        4: 'Unggah foto profil agar tampil di direktori alumni.',
        5: 'Opsional: tambahkan media sosial lalu selesaikan daftar ulang.'
    };

    if (firstErrorField && fieldStepMap[firstErrorField]) {
        activeStep = fieldStepMap[firstErrorField];
    }

    // ── step render ───────────────────────────────────────────────
    function renderStep(step) {
        activeStep = step;

        panes.forEach(function (pane) {
            pane.classList.toggle('active', Number(pane.dataset.step) === step);
        });
        stepButtons.forEach(function (btn) {
            var s = Number(btn.dataset.stepTarget);
            btn.classList.toggle('active', s === step);
            btn.classList.toggle('done',   s < step);
            if (s === step) { btn.setAttribute('aria-current', 'step'); }
            else            { btn.removeAttribute('aria-current'); }
        });

        if (stepCount) { stepCount.textContent = 'Tahap ' + step + ' dari ' + totalSteps; }
        if (stepDesc)  { stepDesc.textContent  = stepMeta[step] || ''; }
        if (stepProg)  { stepProg.style.width  = ((step / totalSteps) * 100) + '%'; }

        if (stepPrev)   { stepPrev.style.display   = step === 1          ? 'none'         : 'inline-block'; }
        if (stepNext)   { stepNext.style.display   = step === totalSteps ? 'none'         : 'inline-block'; }
        if (stepSubmit) { stepSubmit.style.display = step === totalSteps ? 'inline-block' : 'none'; }
    }

    // ── step validation ───────────────────────────────────────────
    function showPendidikanFeedback(show) {
        if (pendidikanFeedback) { pendidikanFeedback.classList.toggle('d-none', !show); }
    }

    function isStepValid(step) {
        var pane = panes.find(function (p) { return Number(p.dataset.step) === step; });
        if (!pane) { return true; }

        var required = [].slice.call(pane.querySelectorAll('input[required], select[required], textarea[required]'));
        for (var i = 0; i < required.length; i++) {
            if (!required[i].reportValidity()) { return false; }
        }

        if (step === 3 && pendidikanHidden) {
            var filled = pendidikanHidden.value.trim() !== '';
            showPendidikanFeedback(!filled);
            if (!filled) {
                if (tingkatanSelect) {
                    tingkatanSelect.setCustomValidity('Pendidikan saat ini wajib dipilih melalui dropdown.');
                    tingkatanSelect.reportValidity();
                    tingkatanSelect.setCustomValidity('');
                }
                return false;
            }
        }

        if (step === 3) {
            var domHidden = document.getElementById('domisili_terakhir');
            var kotaSel   = document.getElementById('domisili_kota_ob');
            if (domHidden && domHidden.value.trim() === '') {
                if (kotaSel) {
                    kotaSel.setCustomValidity('Domisili terakhir wajib dipilih.');
                    kotaSel.reportValidity();
                    kotaSel.setCustomValidity('');
                }
                return false;
            }
        }

        return true;
    }

    // ── wire nav buttons ──────────────────────────────────────────
    if (stepButtons.length && panes.length) {
        renderStep(activeStep);

        stepButtons.forEach(function (btn) {
            btn.addEventListener('click', function () {
                var target = Number(btn.dataset.stepTarget || 1);
                if (target > activeStep && !isStepValid(activeStep)) { return; }
                renderStep(target);
            });
        });

        if (stepPrev) {
            stepPrev.addEventListener('click', function () { renderStep(Math.max(1, activeStep - 1)); });
        }
        if (stepNext) {
            stepNext.addEventListener('click', function () {
                if (!isStepValid(activeStep)) { return; }
                renderStep(Math.min(totalSteps, activeStep + 1));
            });
        }
    }

    // ── education cascading dropdowns ─────────────────────────────
    var educationApi = {
        levels:   '{{ route('alumni.profile.education.levels') }}',
        majors:   '{{ route('alumni.profile.education.majors') }}',
        campuses: '{{ route('alumni.profile.education.campuses') }}'
    };

    function isSchoolLevel() {
        return tingkatanSelect && tingkatanSelect.value === 'SMA/SMK Sederajat';
    }

    function setCampusInputMode(useManual) {
        if (!kampusSelectWrapper || !kampusManualInput || !kampusSelect) { return; }
        if (useManual) {
            kampusSelectWrapper.classList.add('d-none');
            kampusManualInput.classList.remove('d-none');
            kampusManualInput.disabled = false;
            kampusSelect.disabled = true;
            kampusSelect.value = '';
            if (window.jQuery && window.jQuery(kampusSelect).hasClass('select2-hidden-accessible')) { window.jQuery(kampusSelect).select2('destroy'); }
        } else {
            kampusSelectWrapper.classList.remove('d-none');
            kampusManualInput.classList.add('d-none');
            kampusManualInput.disabled = true;
            kampusManualInput.value = '';
        }
    }

    function renderTingkatan(levels) {
        if (!tingkatanSelect) { return; }
        tingkatanSelect.innerHTML = '<option value="">-- Pilih Tingkat --</option>';
        levels.forEach(function (t) {
            tingkatanSelect.insertAdjacentHTML('beforeend', '<option value="' + t + '">' + t + '</option>');
        });
    }

    function renderJurusan(majors, sel) {
        if (!jurusanSelect || !kampusSelect) { return; }
        jurusanSelect.innerHTML = '<option value="">-- Pilih Jurusan --</option>';
        kampusSelect.innerHTML  = '<option value="">-- Pilih Sekolah/Kampus --</option>';
        kampusSelect.disabled   = true;
        if (!Array.isArray(majors) || !majors.length) { 
            jurusanSelect.disabled = true; 
            if (window.jQuery && window.jQuery(jurusanSelect).hasClass('select2-hidden-accessible')) { window.jQuery(jurusanSelect).select2('destroy'); }
            return; 
        }
        majors.forEach(function (j) {
            jurusanSelect.insertAdjacentHTML('beforeend',
                '<option value="' + j + '" ' + (sel === j ? 'selected' : '') + '>' + j + '</option>');
        });
        jurusanSelect.disabled = false;
        if (window.jQuery && window.jQuery(jurusanSelect).select2) {
            $(jurusanSelect).select2({ width: '100%', placeholder: '-- Pilih Jurusan --' });
        }
    }

    function renderKampus(campuses, sel) {
        if (!kampusSelect) { return; }
        kampusSelect.innerHTML = '<option value="">-- Pilih Sekolah/Kampus --</option>';
        if (!Array.isArray(campuses) || !campuses.length) { 
            kampusSelect.disabled = true; 
            if (window.jQuery && window.jQuery(kampusSelect).hasClass('select2-hidden-accessible')) { window.jQuery(kampusSelect).select2('destroy'); }
            return; 
        }
        campuses.forEach(function (k) {
            kampusSelect.insertAdjacentHTML('beforeend',
                '<option value="' + k + '" ' + (sel === k ? 'selected' : '') + '>' + k + '</option>');
        });
        kampusSelect.disabled = false;
        if (window.jQuery && window.jQuery(kampusSelect).select2) {
            $(kampusSelect).select2({ width: '100%', placeholder: '-- Pilih Sekolah/Kampus --' });
        }
    }

    async function fetchEdu(url, params) {
        var qs = new URLSearchParams(params || {});
        var res = await fetch(qs.toString() ? url + '?' + qs : url, {
            headers: { 'X-Requested-With': 'XMLHttpRequest', Accept: 'application/json' }
        });
        if (!res.ok) { throw new Error('Gagal memuat data pendidikan.'); }
        var data = await res.json();
        return Array.isArray(data.data) ? data.data : [];
    }

    function syncPendidikanHidden() {
        if (!pendidikanHidden || !tingkatanSelect || !jurusanSelect) { return; }
        var kampusVal = isSchoolLevel()
            ? (kampusManualInput ? kampusManualInput.value.trim() : '')
            : (kampusSelect ? kampusSelect.value : '');
        pendidikanHidden.value = (tingkatanSelect.value && jurusanSelect.value && kampusVal)
            ? tingkatanSelect.value + ' - ' + jurusanSelect.value + ' - ' + kampusVal
            : '';
        showPendidikanFeedback(false);
    }

    async function hydrateEducation() {
        if (!pendidikanHidden || !tingkatanSelect) { return; }
        var saved = pendidikanHidden.value || '';
        var parts = saved.split(' - ');
        if (parts.length !== 3) { return; }
        var sT = parts[0].trim(), sJ = parts[1].trim(), sK = parts[2].trim();
        tingkatanSelect.value = sT;
        var majors = await fetchEdu(educationApi.majors, { tingkatan: sT });
        renderJurusan(majors, sJ);
        if (sT === 'SMA/SMK Sederajat') {
            setCampusInputMode(true);
            if (kampusManualInput) { kampusManualInput.value = sK; }
            syncPendidikanHidden();
            return;
        }
        setCampusInputMode(false);
        var campuses = await fetchEdu(educationApi.campuses, { tingkatan: sT, jurusan: sJ });
        renderKampus(campuses, sK);
    }

    (async function initPendidikanDropdown() {
        if (!tingkatanSelect || !jurusanSelect || !kampusSelect || !pendidikanHidden) { return; }
        try {
            var levels = await fetchEdu(educationApi.levels);
            renderTingkatan(levels);
            await hydrateEducation();

            if (window.jQuery) {
                $(jurusanSelect).on('select2:select', function () { jurusanSelect.dispatchEvent(new Event('change')); });
                $(kampusSelect).on('select2:select', function () { kampusSelect.dispatchEvent(new Event('change')); });
            }

            tingkatanSelect.addEventListener('change', async function () {
                renderJurusan([], '');
                renderKampus([], '');
                setCampusInputMode(this.value === 'SMA/SMK Sederajat');
                syncPendidikanHidden();
                if (!this.value) { return; }
                var majors = await fetchEdu(educationApi.majors, { tingkatan: this.value });
                renderJurusan(majors, '');
            });

            jurusanSelect.addEventListener('change', async function () {
                renderKampus([], '');
                syncPendidikanHidden();
                if (!tingkatanSelect.value || !this.value || isSchoolLevel()) { return; }
                var campuses = await fetchEdu(educationApi.campuses, {
                    tingkatan: tingkatanSelect.value, jurusan: this.value
                });
                renderKampus(campuses, '');
            });

            kampusSelect.addEventListener('change', syncPendidikanHidden);
            if (kampusManualInput) { kampusManualInput.addEventListener('input', syncPendidikanHidden); }
        } catch (err) {
            [tingkatanSelect, jurusanSelect, kampusSelect].forEach(function (el) { if (el) { el.disabled = true; } });
            if (kampusManualInput) { kampusManualInput.disabled = true; }
            showPendidikanFeedback(true);
            if (pendidikanFeedback) {
                pendidikanFeedback.textContent = 'Data pendidikan tidak dapat dimuat. Coba refresh halaman.';
            }
        }
    })();

    // ── photo preview ─────────────────────────────────────────────
    var photoInput   = document.getElementById('fotoProfilInput');
    var photoPreview = document.getElementById('fotoProfilPreview');
    var photoEmpty   = document.getElementById('fotoProfilEmptyText');
    var photoMeta    = document.getElementById('fotoProfilMeta');
    var photoReset   = document.getElementById('fotoProfilReset');

    // ── repeater ─────────────────────────────────────────────
    window.addRepeater = function(type, namePrefix, placeholderJudul) {
        var container = document.getElementById('repeater_' + type);
        var index = Date.now();
        var currentYear = new Date().getFullYear();
        var optsMulai = '<option value="">-- Mulai --</option>';
        var optsSelesai = '<option value="">-- Selesai --</option><option value="Saat Ini">Saat Ini</option>';
        for (var y = currentYear; y >= 1990; y--) {
            optsMulai += '<option value="' + y + '">' + y + '</option>';
            optsSelesai += '<option value="' + y + '">' + y + '</option>';
        }
        
        var html = `
            <div class="d-flex gap-2 mb-2 repeater-item">
                <input type="text" name="${namePrefix}[${index}][judul]" class="form-control" placeholder="${placeholderJudul}">
                <select name="${namePrefix}[${index}][tahun_mulai]" class="form-select" style="width: 130px; flex-shrink: 0;">${optsMulai}</select>
                <div class="d-flex align-items-center">-</div>
                <select name="${namePrefix}[${index}][tahun_selesai]" class="form-select" style="width: 130px; flex-shrink: 0;">${optsSelesai}</select>
                <button type="button" class="btn btn-outline-danger px-2" onclick="this.closest('.repeater-item').remove()"><i class="ri-subtract-line"></i></button>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', html);
    };

    function showToast(msg, type) {
        if (!toast) { return; }
        var icon = type === 'error' ? 'ri-error-warning-line' : 'ri-information-line';
        toast.innerHTML = '<span class="toast-content"><i class="' + icon + '"></i><span>' + msg + '</span></span>';
        toast.className = 'parja-mini-toast show ' + (type || 'info');
        if (toastTimer) { clearTimeout(toastTimer); }
        toastTimer = setTimeout(function () { toast.className = 'parja-mini-toast ' + (type || 'info'); }, 2500);
    }

    if (photoInput && photoPreview && photoEmpty) {
        var initialSrc = photoPreview.getAttribute('src') || '';

        photoInput.addEventListener('change', function () {
            var file = photoInput.files[0];
            if (!file) { return; }
            if (file.size > 2 * 1024 * 1024) {
                showToast('Foto terlalu besar. Maksimal 2 MB.', 'error');
                photoInput.value = '';
                return;
            }
            var reader = new FileReader();
            reader.onload = function (e) {
                photoPreview.src = e.target.result;
                photoPreview.style.display = '';
                photoEmpty.style.display   = 'none';
            };
            reader.readAsDataURL(file);
            if (photoMeta) {
                photoMeta.textContent = file.name + ' (' + (file.size / 1024).toFixed(1) + ' KB)';
            }
        });

        if (photoReset) {
            photoReset.addEventListener('click', function () {
                photoInput.value = '';
                photoPreview.src = initialSrc;
                photoPreview.style.display = initialSrc ? '' : 'none';
                photoEmpty.style.display   = initialSrc ? 'none' : '';
                if (photoMeta) { photoMeta.textContent = ''; }
            });
        }
    }
})();
</script>

@stack('scripts')
</body>
</html>
