@extends('layouts.app')

@section('title', 'Dashboard | Pembimbing - SMART Setjen DPR RI')
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

    /* Table Styling */
    .table {
        background: white;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }

    .table thead {
        background: var(--primary-navy);
        color: white;
    }

    .table thead th {
        font-weight: 700;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 16px;
        border: none;
    }

    .table tbody td {
        padding: 16px;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
    }

    .table tbody tr:hover {
        background: #f8fafc;
    }

    .table tbody tr:last-child td {
        border-bottom: none;
    }

    .badge {
        padding: 6px 12px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.75rem;
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

<div class="main-dashboard p-4">
    <div class="container-fluid">
        <!-- Header Banner -->
        <div class="welcome-banner">
            <div class="welcome-text">
                <h1>Selamat Datang Kembali 👋</h1>
                <p>Pembimbing Portal {{ config('app.name') }} | <span class="welcome-name">{{ auth()->user()->nama }}</span></p>
                <p>Hari ini: {{ \Carbon\Carbon::now()->locale('id')->translatedFormat('l, d F Y') }}</p>
                <button id="btnChangePassword" class="btn btn-sm btn-warning mt-3">
                    <i class="ri-lock-password-line"></i> Ganti Password
                </button>                
            </div>
        </div>

        @php
            $activeUntil = $guru->active_until ? \Carbon\Carbon::parse($guru->active_until) : null;
        @endphp
        @if($activeUntil && $activeUntil->lte(\Carbon\Carbon::today()))
            <div class="alert alert-warning border-0 shadow-sm mt-3" role="alert">
                <i class="ri-alert-line me-2"></i>
                <strong>Perhatian:</strong>
                Akun pembimbing ini dijadwalkan untuk dinonaktifkan otomatis.
                Masa aktif bimbingan terakhir berakhir pada
                <strong>{{ $activeUntil->locale('id')->translatedFormat('d F Y') }}</strong>.
            </div>
        @endif

        <!-- Stats Cards -->
        <div class="stats-container">
            <a href="#" class="stat-card-modern text-decoration-none">
                <div class="icon-box bg-light-gold">
                    <i class="ri-user-star-line"></i>
                </div>
                <div class="stat-info">
                    <div class="value">{{ $totalPesertaAktif }}</div>
                    <div class="label">Peserta Aktif</div>
                </div>
            </a>
            <a href="#" class="stat-card-modern text-decoration-none">
                <div class="icon-box bg-light-green">
                    <i class="ri-graduation-cap-line"></i>
                </div>
                <div class="stat-info">
                    <div class="value">{{ $totalPesertaSelesai }}</div>
                    <div class="label">Peserta Selesai</div>
                </div>
            </a>
            <a href="#" class="stat-card-modern text-decoration-none">
                <div class="icon-box bg-light-blue">
                    <i class="ri-time-line"></i>
                </div>
                <div class="stat-info">
                    <div class="value">{{ $pesertaBelumMulai }}</div>
                    <div class="label">Belum Mulai</div>
                </div>
            </a>
            <a href="#" class="stat-card-modern text-decoration-none">
                <div class="icon-box bg-light-orange">
                    <i class="ri-briefcase-line"></i>
                </div>
                <div class="stat-info">
                    <div class="value">{{ $pesertaSedangMagang }}</div>
                    <div class="label">Sedang Magang</div>
                </div>
            </a>
        </div>

        <!-- Quick Actions -->
        <div class="action-section">
            <div class="section-header">
                <h5 class="m-0 font-weight-bold" style="color: var(--primary-navy);">Peserta Bimbingan Anda</h5>
                <div class="line"></div>
            </div>
            
            @if($pesertaAktif->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>NIK</th>
                                <th>Kategori</th>
                                <th>Satuan Kerja</th>
                                <th>Status</th>
                                <th>Tanggal Mulai</th>
                                <th>Tanggal Selesai</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pesertaAktif as $index => $peserta)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $peserta->nama }}</td>
                                    <td>{{ $peserta->nik }}</td>
                                    <td>
                                        <span class="badge bg-info">{{ $peserta->kategori }}</span>
                                        @if($peserta->kategori_lainnya)
                                            <small class="text-muted">({{ $peserta->kategori_lainnya }})</small>
                                        @endif
                                    </td>
                                    <td>{{ $peserta->satker->nama ?? 'Umum' }}</td>
                                    <td>
                                        @if($peserta->status == 1)
                                            <span class="badge bg-warning">Belum Mulai</span>
                                        @elseif($peserta->status == 2)
                                            <span class="badge bg-success">Aktif Magang</span>
                                        @endif
                                    </td>
                                    <td>{{ $peserta->lamaran->tanggal_mulai ? $peserta->lamaran->tanggal_mulai->locale('id')->translatedFormat('d F Y') : '-' }}</td>
                                    <td>{{ $peserta->lamaran->tanggal_selesai ? $peserta->lamaran->tanggal_selesai->locale('id')->translatedFormat('d F Y') : '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-info">
                    <i class="ri-information-line"></i> Belum ada peserta aktif yang sedang Anda bimbing.
                </div>
            @endif

            @if($pesertaSelesai->count() > 0)
                <div class="section-header mt-5">
                    <h5 class="m-0 font-weight-bold" style="color: var(--primary-navy);">Peserta yang Sudah Selesai</h5>
                    <div class="line"></div>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>NIK</th>
                                <th>Kategori</th>
                                <th>Satuan Kerja</th>
                                <th>Tanggal Selesai</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pesertaSelesai as $index => $peserta)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $peserta->nama }}</td>
                                    <td>{{ $peserta->nik }}</td>
                                    <td>
                                        <span class="badge bg-info">{{ $peserta->kategori }}</span>
                                        @if($peserta->kategori_lainnya)
                                            <small class="text-muted">({{ $peserta->kategori_lainnya }})</small>
                                        @endif
                                    </td>
                                    <td>{{ $peserta->satker->nama ?? 'Umum' }}</td>
                                    <td>{{ $peserta->lamaran->tanggal_selesai ? $peserta->lamaran->tanggal_selesai->locale('id')->translatedFormat('d F Y') : '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {

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

                    // Toggle checklist warna
                    if (ruleLength) ruleLength.classList.toggle('valid', hasLength);
                    if (ruleLower)  ruleLower.classList.toggle('valid', hasLower);
                    if (ruleUpper)  ruleUpper.classList.toggle('valid', hasUpper);
                    if (ruleDigit)  ruleDigit.classList.toggle('valid', hasDigit);
                    if (ruleSymbol) ruleSymbol.classList.toggle('valid', hasSymbol);

                    // Border merah jika sudah mulai mengetik tapi belum semua terpenuhi
                    if (val.length > 0 && !allValid) {
                        newPasswordInput.classList.add('swal-input-invalid');
                    } else {
                        newPasswordInput.classList.remove('swal-input-invalid');
                    }

                    // Sekalian cek kecocokan konfirmasi jika sudah diisi
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
                    const res = await fetch("{{ route('pembimbing.change-password') }}", {
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
</script>
@endsection