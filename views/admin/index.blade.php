@extends('layouts.app')

@section('title', 'Dashboard | Admin - SMART Setjen DPR RI')
@section('content')
<!-- Import Google Fonts & Icons -->
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<meta name="csrf-token" content="{{ csrf_token() }}">


<style>
    :root {
        --primary-navy: #82858e;
        --accent-gold: linear-gradient(135deg, #d4af37 0%, #aa8a2e 100%);
        --gold-solid: #b08d48;
        --glass-bg: rgba(255, 255, 255, 0.9);
        --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

        /* === SWEETALERT CUSTOM THEME === */
    .swal2-popup {
        font-family: 'Plus Jakarta Sans', sans-serif;
        border-radius: 20px !important;
        padding: 2rem !important;
        border: 1px solid var(--border-color);
        box-shadow: 0 20px 40px rgba(0,0,0,0.12);
    }

    .swal2-title {
        font-size: 1.25rem !important;
        font-weight: 800 !important;
        color: var(--primary-dark) !important;
    }

    .swal2-html-container {
        margin-top: 1.2rem !important;
    }

    /* Input */
    .swal2-input {
        border-radius: 10px !important;
        border: 1px solid var(--border-color) !important;
        padding: 10px 12px !important;
        font-size: 0.9rem !important;
        background: #fcfdfe !important;
    }

    .swal2-input:focus {
        border-color: var(--accent-gold) !important;
        box-shadow: 0 0 0 3px rgba(176,141,72,.15) !important;
    }

    /* Buttons */
    .swal2-confirm {
        background: var(--primary-dark) !important;
        color: #fff !important;
        border-radius: 10px !important;
        padding: 10px 18px !important;
        font-weight: 700 !important;
    }

    .swal2-confirm:hover {
        background: #000 !important;
    }

    .swal2-cancel {
        background: transparent !important;
        color: var(--text-muted) !important;
        border: 1px solid var(--border-color) !important;
        border-radius: 10px !important;
        padding: 10px 18px !important;
    }

    /* Error text */
    .swal2-validation-message {
        background: #fffbeb !important;
        color: #92400e !important;
        border-radius: 10px !important;
        font-size: 0.8rem !important;
    }

    .swal-input-group {
        margin-bottom: 14px;
        text-align: left;
    }

    .swal-label {
        display: block;
        font-size: 0.8rem;
        font-weight: 600;
        color: var(--text-main);
        margin-bottom: 4px;
    }

    .swal-field-wrapper {
        position: relative;
    }

    .swal-field-wrapper .swal2-input {
        margin: 0;
        width: 100%;
        padding-right: 40px !important;
    }

    .swal2-input.swal-input-invalid {
        border-color: #dc2626 !important;
        box-shadow: 0 0 0 1px rgba(220,38,38,0.4) !important;
    }

    .swal-password-help {
        margin-top: 4px;
    }

    .swal-password-help-item {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 0.75rem;
        margin-top: 2px;
        color: #dc2626;
        line-height: 1.2;
    }

    .swal-password-help-item.valid {
        color: #16a34a;
    }
    .swal-password-help-item i {
        font-size: 0.95rem;
        line-height: 1;
        flex-shrink: 0;
        color: inherit;
    }
    .swal-password-help-item .hint {
        color: #64748b;
        font-size: 0.72rem;
        margin-left: 4px;
        white-space: nowrap;
    }

    .swal-eye {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        cursor: pointer;
        font-size: 1.1rem;
        color: var(--text-muted);
        transition: 0.2s;
    }

    .swal-eye:hover {
        color: var(--gold-solid);
    }

    .main-dashboard {
        font-family: 'Plus Jakarta Sans', sans-serif;
        background-color: #f8fafc;
        min-height: 100vh;
        padding-bottom: 50px;
        position: relative;
    }

    html[data-skin="dark"] .main-dashboard {
        background-color: #020617;
        color: #e5e7eb;
    }

    /* Background Pattern Batik */
    .main-dashboard::before {
        content: '';
        position: fixed;
        top: 0; left: 0;
        width: 100%; height: 100%;
        background-image: url('{{ asset("theme/admin-dashbyte/dist/assets/img/batiknew.png") }}'); 
        background-size: 500px;
        opacity: 0.03;
        z-index: 0;
        pointer-events: none;
    }

    /* Welcome Header Section */
    .welcome-banner {
        background: var(--primary-navy);
        border-radius: 30px;
        padding: 40px;
        position: relative;
        overflow: hidden;
        margin-bottom: 40px;
        box-shadow: 0 20px 40px rgba(15, 23, 42, 0.1);
        z-index: 1;
    }

    .welcome-banner::after {
        content: '';
        position: absolute;
        top: -50px; right: -50px;
        width: 250px; height: 250px;
        background: var(--accent-gold);
        border-radius: 50%;
        opacity: 0.1;
    }

    .welcome-text h1 {
        font-weight: 800;
        font-size: 2.2rem;
        color: white;
        margin-bottom: 8px;
    }

    .welcome-text p {
        color: rgba(255, 255, 255, 0.7);
        font-size: 1.1rem;
        font-weight: 500;
    }

    .welcome-name {
        color: #d4af37;
        font-weight: 700;
        border-bottom: 2px solid #d4af37;
    }

    /* Stats Grid Customization */
    .stats-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
        gap: 24px;
        margin-bottom: 50px;
        position: relative;
        z-index: 1;
    }

    .stat-card-modern {
        background: white;
        border-radius: 24px;
        padding: 24px;
        border: 1px solid rgba(226, 232, 240, 0.8);
        transition: var(--transition);
        display: flex;
        align-items: center;
        gap: 20px;
    }

    html[data-skin="dark"] .stat-card-modern {
        background: #020617;
        border-color: #1f2937;
        color: #e5e7eb;
    }
    html[data-skin="dark"] .stat-info .value {
        color: #ffffff !important;
    }
    html[data-skin="dark"] .stat-info .label {
        color: #ffffff !important;
    }
    html[data-skin="dark"] .stat-card-modern .text-muted {
        color: #ffffff !important;
    }

    .stat-card-modern:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 30px rgba(0,0,0,0.08);
        border-color: var(--gold-solid);
    }
    a.stat-card-modern { color: inherit; }

    .icon-box {
        width: 65px;
        height: 65px;
        border-radius: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
        flex-shrink: 0;
    }

    /* Colors for specific cards */
    .bg-light-gold { background: #fef9c3; color: #a16207; }
    .bg-light-green { background: #dcfce7; color: #15803d; }
    .bg-light-blue { background: #dbeafe; color: #1d4ed8; }
    .bg-light-orange { background: #ffedd5; color: #c2410c; }
    .bg-light-red { background: #fee2e2; color: #b91c1c; }

    .stat-info .value {
        font-size: 1.8rem;
        font-weight: 800;
        color: var(--primary-navy);
        line-height: 1;
    }

    .stat-info .label {
        font-size: 0.9rem;
        color: var(--text-muted);
        font-weight: 600;
        margin-top: 5px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .cursor-pointer { cursor: pointer; }

    /* Modal peserta belum verifikasi: body bisa di-scroll */
    #modalPesertaBelumVerifikasi .modal-dialog {
        max-height: calc(100vh - 2rem);
    }
    #modalPesertaBelumVerifikasi .modal-body {
        max-height: 60vh;
        overflow-y: auto;
        overflow-x: hidden;
    }

    /* Quick Action Section */
    .action-section {
        background: rgba(255, 255, 255, 0.6);
        backdrop-filter: blur(10px);
        border-radius: 30px;
        padding: 40px;
        border: 1px solid white;
        position: relative;
        z-index: 1;
    }

    .section-header {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 30px;
    }

    .section-header .line {
        flex-grow: 1;
        height: 2px;
        background: #e2e8f0;
    }

    .action-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 20px;
    }

    .action-card {
        background: white;
        padding: 20px;
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        text-decoration: none;
        color: var(--primary-navy);
        border: 1px solid transparent;
        transition: var(--transition);
        box-shadow: 0 4px 6px rgba(0,0,0,0.02);
    }

    .action-card:hover {
        border-color: var(--gold-solid);
        background: var(--primary-navy);
        color: white;
        transform: scale(1.02);
    }

    .action-content {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .action-content i {
        font-size: 1.5rem;
    }

    .action-card i.arrow {
        opacity: 0;
        transform: translateX(-10px);
        transition: var(--transition);
    }

    .action-card:hover i.arrow {
        opacity: 1;
        transform: translateX(0);
    }

    /* Dark mode: Aksi Cepat */
    html[data-skin="dark"] .action-section {
        background: rgba(15, 23, 42, 0.9);
        border-color: #1f2937;
        color: #e5e7eb;
    }
    html[data-skin="dark"] .section-header .line {
        background: #374151;
    }
    html[data-skin="dark"] .section-header h5 {
        color: #e5e7eb !important;
    }
    html[data-skin="dark"] .action-card {
        /* dibuat lebih terang dari panel aksi cepat */
        background: #1e293b;
        border-color: #1f2937;
        color: #ffffff;
        box-shadow: 0 4px 6px rgba(0,0,0,0.3);
    }
    html[data-skin="dark"] .action-card:hover {
        background: #334155;
        border-color: var(--gold-solid);
        color: #ffffff;
    }
    html[data-skin="dark"] .action-card .action-content,
    html[data-skin="dark"] .action-card i.arrow {
        color: inherit;
    }

    /* Dark mode: rapikan teks yang masih gelap/abu di dashboard admin */
    html[data-skin="dark"] .main-dashboard .text-dark,
    html[data-skin="dark"] .main-dashboard .text-muted,
    html[data-skin="dark"] .main-dashboard .swal-label,
    html[data-skin="dark"] .main-dashboard .swal-password-help-item .hint {
        color: #ffffff !important;
    }
    html[data-skin="dark"] .main-dashboard p,
    html[data-skin="dark"] .main-dashboard small,
    html[data-skin="dark"] .main-dashboard span {
        color: #ffffff;
    }
    html[data-skin="dark"] .main-dashboard .swal-password-help-item.valid {
        color: #4ade80;
    }

    @media (max-width: 768px) {
        .welcome-banner { 
            padding: 30px 20px; 
            border-radius: 20px;
        }
        .welcome-text h1 { 
            font-size: 1.6rem; 
        }
        .welcome-text p {
            font-size: 0.95rem;
        }
        .stats-container {
            grid-template-columns: 1fr;
            gap: 16px;
        }
        .stat-card-modern {
            padding: 20px;
        }
        .icon-box {
            width: 55px;
            height: 55px;
            font-size: 1.5rem;
        }
        .stat-info .value {
            font-size: 1.5rem;
        }
        .action-grid {
            grid-template-columns: 1fr;
        }
        .action-section {
            padding: 30px 20px;
        }
    }

    @media (max-width: 576px) {
        .main-dashboard {
            padding: 1rem 0.5rem;
        }
        .welcome-banner {
            padding: 20px 15px;
            margin-bottom: 30px;
        }
        .welcome-text h1 {
            font-size: 1.3rem;
        }
        .welcome-name {
            font-size: 1rem;
        }
        .stats-container {
            margin-bottom: 30px;
        }
        .stat-card-modern {
            flex-direction: column;
            text-align: center;
            padding: 15px;
        }
        .icon-box {
            width: 50px;
            height: 50px;
            font-size: 1.3rem;
        }
        .stat-info .value {
            font-size: 1.3rem;
        }
        .stat-info .label {
            font-size: 0.8rem;
        }
        .action-section {
            padding: 20px 15px;
            border-radius: 20px;
        }
        .action-card {
            padding: 15px;
        }
    }
</style>

@php $countBelumVerifikasi = ($pesertaBelumVerifikasi ?? collect())->count(); @endphp
<div class="main-dashboard p-4">
    <div class="container-fluid">
        <!-- Header Banner -->
        <div class="welcome-banner">
            <div class="welcome-text">
                <h1>Selamat Datang Kembali 👋</h1>
                <p>Admin Portal {{ config('app.name') }} | <span class="welcome-name">{{ auth()->user()->nama }}</span></p>
                <p>Hari ini: {{ \Carbon\Carbon::now()->locale('id')->translatedFormat('l, d F Y') }}</p>
                <button id="btnChangePassword" class="btn btn-sm btn-warning mt-3">
                    <i class="ri-lock-password-line"></i> Ganti Password
                </button>                
            </div>
        </div>

        @if($countBelumVerifikasi > 0)
        <!-- Warning: Peserta belum diverifikasi -->
        <div class="alert alert-warning border-0 shadow-sm d-flex align-items-center gap-3 mb-4" role="alert" style="border-radius: 16px; border-left: 4px solid #b45309;">
            <div class="flex-shrink-0 fs-4"><i class="ri-file-warning-fill text-warning"></i></div>
            <div class="flex-grow-1">
                <strong class="d-block mb-1">Peserta Belum Diverifikasi</strong>
                <span>Ada <strong>{{ $countBelumVerifikasi }}</strong> peserta yang belum melengkapi Nota Dinas (Nodin). Segera verifikasi di halaman Biodata Peserta.</span>
            </div>
            <a href="{{ route('biodata.index') }}" class="btn btn-warning btn-sm flex-shrink-0 fw-bold">
                <i class="ri-arrow-right-line me-1"></i> Ke Biodata
            </a>
        </div>
        @endif

        @if(isset($showTotpWarning) && $showTotpWarning)
        <!-- Warning: MFA Authenticator belum diaktifkan -->
        <div class="alert alert-danger border-0 shadow-sm d-flex align-items-center gap-3 mb-4" role="alert" style="border-radius: 16px; border-left: 4px solid #dc2626;">
            <div class="flex-shrink-0 fs-4"><i class="ri-shield-cross-line text-danger"></i></div>
            <div class="flex-grow-1">
                <strong class="d-block mb-1">MFA Authenticator Belum Diaktifkan</strong>
                <span>MFA wajib aktif untuk semua admin. Segera aktifkan TOTP Authenticator di halaman profil untuk keamanan akun Anda.</span>
            </div>
            <a href="{{ route('profile.index') }}" class="btn btn-danger btn-sm flex-shrink-0 fw-bold">
                <i class="ri-arrow-right-line me-1"></i> Ke Profil
            </a>
        </div>
        @endif

        @if($showTotpWarning ?? false)
        <!-- Warning: MFA Authenticator belum diaktifkan -->
        <div class="alert alert-danger border-0 shadow-sm d-flex align-items-center gap-3 mb-4" role="alert" style="border-radius: 16px; border-left: 4px solid #dc2626;">
            <div class="flex-shrink-0 fs-4"><i class="ri-shield-cross-fill text-danger"></i></div>
            <div class="flex-grow-1">
                <strong class="d-block mb-1">MFA Authenticator Belum Diaktifkan</strong>
                <span>MFA Authenticator (TOTP) <strong>WAJIB</strong> untuk admin email. Segera aktifkan di halaman Profile untuk meningkatkan keamanan akun Anda.</span>
            </div>
            <a href="{{ route('profile.index') }}" class="btn btn-danger btn-sm flex-shrink-0 fw-bold">
                <i class="ri-shield-check-line me-1"></i> Aktifkan Sekarang
            </a>
        </div>
        @endif

        <!-- Stats Cards -->
        <div class="stats-container">
            <!-- Total Lamaran -->
            <div class="stat-card-modern">
                <div class="icon-box bg-light-gold">
                    <i class="ri-file-list-3-fill"></i>
                </div>
                <div class="stat-info">
                    <div class="value">{{ $totalLamaran ?? 0 }}</div>
                    <div class="label">Total Lamaran</div>
                </div>
            </div>

            <!-- Total Peserta -->
            <div class="stat-card-modern">
                <div class="icon-box bg-light-blue">
                    <i class="ri-group-fill"></i>
                </div>
                <div class="stat-info">
                    <div class="value">{{ $totalPeserta ?? 0 }}</div>
                    <div class="label">Peserta Aktif</div>
                </div>
            </div>

            <!-- Presensi -->
            <div class="stat-card-modern">
                <div class="icon-box bg-light-green">
                    <i class="ri-calendar-check-fill"></i>
                </div>
                <div class="stat-info">
                    <div class="value">{{ $totalPresensi ?? 0 }}</div>
                    <div class="label">Presensi Hari Ini</div>
                </div>
            </div>

            <!-- Pending -->
            <div class="stat-card-modern">
                <div class="icon-box bg-light-orange">
                    <i class="ri-time-fill"></i>
                </div>
                <div class="stat-info">
                    <div class="value">{{ $lamaranPending ?? 0 }}</div>
                    <div class="label">Perlu Review</div>
                </div>
            </div>

            <!-- Diterima -->
            <div class="stat-card-modern">
                <div class="icon-box bg-light-green">
                    <i class="ri-checkbox-circle-fill"></i>
                </div>
                <div class="stat-info">
                    <div class="value">{{ $lamaranDiterima ?? 0 }}</div>
                    <div class="label">Diterima</div>
                </div>
            </div>

            <!-- Ditolak -->
            <div class="stat-card-modern">
                <div class="icon-box bg-light-red">
                    <i class="ri-close-circle-fill"></i>
                </div>
                <div class="stat-info">
                    <div class="value">{{ $lamaranDitolak ?? 0 }}</div>
                    <div class="label">Ditolak</div>
                </div>
            </div>

            <!-- Peserta Belum Diverifikasi (Nodin) → direct ke biodata-peserta -->
            <a href="{{ route('biodata.index') }}" class="stat-card-modern text-decoration-none cursor-pointer" title="Ke halaman Biodata Peserta">
                <div class="icon-box bg-light-orange">
                    <i class="ri-file-warning-fill"></i>
                </div>
                <div class="stat-info">
                    <div class="value">{{ ($pesertaBelumVerifikasi ?? collect())->count() }}</div>
                    <div class="label">Peserta Belum Diverifikasi</div>
                </div>
            </a>
        </div>

        <!-- Quick Actions -->
        <div class="action-section">
            <div class="section-header">
                <h5 class="m-0 font-weight-bold" style="color: var(--primary-navy);">AKSI CEPAT</h5>
                <div class="line"></div>
            </div>
            
            <div class="action-grid">
                <a href="{{ route('lamaran.index') }}" class="action-card">
                    <div class="action-content">
                        <i class="ri-mail-open-line"></i>
                        <span>Kelola Lamaran</span>
                    </div>
                    <i class="ri-arrow-right-line arrow"></i>
                </a>

                <a href="{{ route('biodata.index') }}" class="action-card">
                    <div class="action-content">
                        <i class="ri-folder-user-line"></i>
                        <span>Biodata Peserta</span>
                    </div>
                    <i class="ri-arrow-right-line arrow"></i>
                </a>

                <a href="{{ route('absensi.admin.index') }}" class="action-card">
                    <div class="action-content">
                        <i class="ri-calendar-event-line"></i>
                        <span>Data Absensi</span>
                    </div>
                    <i class="ri-arrow-right-line arrow"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Modal: Daftar Peserta Belum Diverifikasi (di luar main-dashboard agar tidak terpotong) -->
@php $listBelumVerifikasi = $pesertaBelumVerifikasi ?? collect(); @endphp
<div class="modal fade" id="modalPesertaBelumVerifikasi" tabindex="-1" aria-labelledby="modalPesertaBelumVerifikasiLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-800" id="modalPesertaBelumVerifikasiLabel">
                    <i class="ri-file-warning-line text-warning me-2"></i> Peserta Belum Diverifikasi
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pt-2">
                <p class="text-muted small mb-3">Peserta dengan Nota Dinas (Nodin) belum lengkap. Klik nama untuk membuka halaman biodata ke bagian Nota Dinas.</p>
                @if($listBelumVerifikasi->isEmpty())
                    <p class="text-muted text-center py-4 mb-0">Tidak ada peserta yang belum diverifikasi.</p>
                @else
                    <ul class="list-group list-group-flush">
                        @foreach($listBelumVerifikasi as $p)
                        <li class="list-group-item d-flex align-items-center px-0 border-0 border-bottom py-2">
                            <a href="{{ route('biodata.show', $p->id) }}#section-lengkapi-nodin" class="text-decoration-none text-dark fw-600 d-flex align-items-center gap-2 flex-grow-1">
                                <i class="ri-user-line text-muted"></i>
                                {{ ucwords(strtolower($p->nama ?? '')) }}
                            </a>
                            <i class="ri-arrow-right-s-line text-muted"></i>
                        </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Pindahkan modal ke body agar tampil di atas (hindari overflow/stacking context)
        var modalEl = document.getElementById('modalPesertaBelumVerifikasi');
        if (modalEl && modalEl.parentNode !== document.body) {
            document.body.appendChild(modalEl);
        }

        // Greeting berdasarkan jam
        const hour = new Date().getHours();
        const welcomeH1 = document.querySelector('.welcome-text h1');
        if (welcomeH1) {
            let greeting = "Selamat Datang Kembali 👋";
            if (hour < 12) greeting = "Selamat Pagi ☀️";
            else if (hour < 15) greeting = "Selamat Siang 🌤️";
            else if (hour < 18) greeting = "Selamat Sore 🌅";
            else greeting = "Selamat Malam 🌙";
            welcomeH1.innerHTML = greeting;
        }
    });

    document.getElementById('btnChangePassword').addEventListener('click', function () {
        Swal.fire({
            title: 'Ganti Password',
            html: `
                <div style="text-align:left; margin-bottom:10px; font-size:0.85rem; color:#64748b;">
                    Silakan masukkan password lama dan buat password baru minimal <strong>12 karakter</strong>.
                </div>

                <div class="swal-input-group">
                    <label class="swal-label">Password Lama</label>
                    <div class="swal-field-wrapper">
                        <input type="password" id="current_password" class="swal2-input" placeholder="Masukkan password lama">
                        <i class="ri-eye-off-line swal-eye" data-target="current_password"></i>
                    </div>
                </div>

                <div class="swal-input-group">
                    <label class="swal-label">Password Baru</label>
                    <div class="swal-field-wrapper">
                        <input type="password" id="new_password" class="swal2-input" placeholder="Minimal 12 karakter">
                        <i class="ri-eye-off-line swal-eye" data-target="new_password"></i>
                    </div>
                    <div id="swal-password-help" class="swal-password-help">
                        <div id="swal-pass-rule-length" class="swal-password-help-item"><i class="ri-checkbox-circle-line"></i><span>Password minimal 12 karakter</span></div>
                        <div id="swal-pass-rule-lower" class="swal-password-help-item"><i class="ri-checkbox-circle-line"></i><span>Harus mengandung huruf kecil</span><span class="hint">(contoh: a-z)</span></div>
                        <div id="swal-pass-rule-upper" class="swal-password-help-item"><i class="ri-checkbox-circle-line"></i><span>Harus mengandung huruf besar</span><span class="hint">(contoh: A-Z)</span></div>
                        <div id="swal-pass-rule-digit" class="swal-password-help-item"><i class="ri-checkbox-circle-line"></i><span>Harus mengandung angka</span><span class="hint">(contoh: 0-9)</span></div>
                        <div id="swal-pass-rule-symbol" class="swal-password-help-item"><i class="ri-checkbox-circle-line"></i><span>Harus mengandung karakter khusus</span><span class="hint">(contoh: ! @ # $ % ^ &amp; * )</span></div>
                    </div>
                </div>

                <div class="swal-input-group">
                    <label class="swal-label">Konfirmasi Password Baru</label>
                    <div class="swal-field-wrapper">
                        <input type="password" id="new_password_confirmation" class="swal2-input" placeholder="Ulangi password baru">
                        <i class="ri-eye-off-line swal-eye" data-target="new_password_confirmation"></i>
                    </div>
                </div>
            `,
            showCancelButton: true,
            confirmButtonText: 'Perbarui Password',
            cancelButtonText: 'Batal',
            focusConfirm: false,
            showLoaderOnConfirm: true,
            allowOutsideClick: () => !Swal.isLoading(),

            didOpen: () => {
                // Toggle visibility
                document.querySelectorAll('.swal-eye').forEach(icon => {
                    icon.addEventListener('click', () => {
                        const input = document.getElementById(icon.dataset.target);
                        if (!input) return;

                        const isPassword = input.type === 'password';
                        input.type = isPassword ? 'text' : 'password';

                        icon.classList.toggle('ri-eye-line', isPassword);
                        icon.classList.toggle('ri-eye-off-line', !isPassword);
                    });
                });

                // Live validation untuk password baru
                const newPasswordInput = document.getElementById('new_password');
                const confirmInput = document.getElementById('new_password_confirmation');
                const ruleLength = document.getElementById('swal-pass-rule-length');
                const ruleLower  = document.getElementById('swal-pass-rule-lower');
                const ruleUpper  = document.getElementById('swal-pass-rule-upper');
                const ruleDigit  = document.getElementById('swal-pass-rule-digit');
                const ruleSymbol = document.getElementById('swal-pass-rule-symbol');
                const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z0-9]).+$/;

                function validateNewPassword() {
                    if (!newPasswordInput) return;
                    const val = newPasswordInput.value || '';
                    const hasLength = val.length >= 12;
                    const hasLower  = /[a-z]/.test(val);
                    const hasUpper  = /[A-Z]/.test(val);
                    const hasDigit  = /\d/.test(val);
                    const hasSymbol = /[^A-Za-z0-9]/.test(val);

                    const allValid = hasLength && hasLower && hasUpper && hasDigit && hasSymbol;

                    if (ruleLength) ruleLength.classList.toggle('valid', hasLength);
                    if (ruleLower)  ruleLower.classList.toggle('valid', hasLower);
                    if (ruleUpper)  ruleUpper.classList.toggle('valid', hasUpper);
                    if (ruleDigit)  ruleDigit.classList.toggle('valid', hasDigit);
                    if (ruleSymbol) ruleSymbol.classList.toggle('valid', hasSymbol);

                    if (val.length > 0 && !allValid) {
                        newPasswordInput.classList.add('swal-input-invalid');
                    } else {
                        newPasswordInput.classList.remove('swal-input-invalid');
                    }

                    if (confirmInput && confirmInput.value.length > 0) {
                        validateConfirmPassword();
                    }
                }

                function validateConfirmPassword() {
                    if (!confirmInput || !newPasswordInput) return;
                    const match = confirmInput.value === newPasswordInput.value;

                    if (match || confirmInput.value.length === 0) {
                        confirmInput.classList.remove('swal-input-invalid');
                    } else {
                        confirmInput.classList.add('swal-input-invalid');
                    }
                }

                if (newPasswordInput) {
                    newPasswordInput.addEventListener('input', validateNewPassword);
                }
                if (confirmInput) {
                    confirmInput.addEventListener('input', validateConfirmPassword);
                }
            },

            preConfirm: async () => {
                const current_password = document.getElementById('current_password').value;
                const new_password = document.getElementById('new_password').value;
                const new_password_confirmation = document.getElementById('new_password_confirmation').value;

                if (!current_password || !new_password || !new_password_confirmation) {
                    Swal.showValidationMessage('Semua field wajib diisi');
                    return false;
                }

                if (new_password.length < 12) {
                    Swal.showValidationMessage('Password baru minimal 12 karakter');
                    return false;
                }

                if (new_password !== new_password_confirmation) {
                    Swal.showValidationMessage('Konfirmasi password tidak cocok');
                    return false;
                }

                try {
                    const res = await fetch("{{ route('admin.change-password') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            current_password,
                            new_password,
                            new_password_confirmation
                        })
                    });

                    const data = await res.json();
                    if (!res.ok) throw new Error(data.message || 'Gagal mengganti password');
                    return data;
                } catch (error) {
                    Swal.showValidationMessage(error.message);
                }
            }
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: result.value.message,
                    timer: 2000,
                    showConfirmButton: false
                });
            }
        });
    });

    // Jika datang dengan query ?open_password=1 → langsung buka dialog ganti password
    (function autoOpenChangePasswordFromQuery() {
        const params = new URLSearchParams(window.location.search || '');
        if (params.get('open_password') === '1') {
            const btn = document.getElementById('btnChangePassword');
            if (btn) btn.click();
        }
    })();

    // Warning peserta belum diverifikasi — tampil sekali per sesi (saat pertama buka dashboard setelah login)
    @if(!empty($showPesertaBelumVerifikasiPopup))
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            icon: 'warning',
            title: 'Peserta Belum Diverifikasi',
            html: 'Ada <strong>{{ $countBelumVerifikasi }}</strong> peserta yang belum melengkapi Nota Dinas (Nodin).<br><br>Silakan verifikasi di halaman <strong>Biodata Peserta</strong>.',
            confirmButtonText: 'Ke Biodata Peserta',
            confirmButtonColor: '#b45309',
            showCloseButton: true,
            allowOutsideClick: true
        }).then(function(result) {
            if (result.isConfirmed) {
                window.location.href = "{{ route('biodata.index') }}";
            }
        });
    });
    @endif
</script>
@endsection