@extends('layouts.app')

@section('title', 'Dashboard | Peserta - SMART Setjen DPR RI')
@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap');
    @import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&display=swap');

    :root {
        --primary-dark: #0f172a;
        --accent-gold: #b08d48;
        --gold-light: #fdfae9;
        --text-main: #334155;
        --text-muted: #64748b;
        --white: #ffffff;
        --border-color: #e2e8f0;
        --transition: all 0.3s ease;
    }

    .main-container {
        font-family: 'Plus Jakarta Sans', sans-serif;
        background-color: #f8fafc;
        min-height: 100vh;
        padding: 2rem 1rem;
    }

    html[data-skin="dark"] .main-container {
        background-color: #020617;
        color: #e5e7eb;
    }

    /* Background Pattern Minimalis */
    .main-container::before {
        content: '';
        position: fixed;
        top: 0; left: 0; width: 100%; height: 100%;
        background-image: url('{{ asset("theme/admin-dashbyte/dist/assets/img/batiknew.png") }}');
        background-size: 500px;
        opacity: 0.03;
        z-index: 0;
        pointer-events: none;
    }

    /* Card Utama */
    .welcome-card {
        background: var(--white);
        border-radius: 20px;
        padding: 3rem 2rem;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.02);
        border: 1px solid var(--border-color);
        position: relative;
        z-index: 1;
        max-width: 1000px;
        margin: 0 auto;
    }

    html[data-skin="dark"] .welcome-card {
        background: #020617;
        border-color: #1f2937;
        box-shadow: 0 20px 40px rgba(0,0,0,0.7);
    }

    /* Style Tambahan untuk Welcome Section */
    /* === Welcome Header: simple & menarik === */
    .welcome-outer {
        max-width: 1000px;
        margin: 0 auto 18px;
        position: relative;
        z-index: 1;
    }

    .welcome-banner {
        background: var(--white);
        border-radius: 24px;
        padding: 28px 32px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
        border: 1px solid var(--border-color);
    }

    html[data-skin="dark"] .welcome-banner {
        background: #020617;
        border-color: #1f2937;
        box-shadow: 0 20px 40px rgba(0,0,0,0.7);
    }

    .welcome-banner::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 5px;
        height: 100%;
        background: var(--accent-gold);
        border-radius: 4px 0 0 4px;
    }

    .welcome-text h1 {
        font-family: 'DM Sans', 'Plus Jakarta Sans', sans-serif;
        font-weight: 700;
        font-size: 1.75rem;
        color: var(--primary-dark);
        margin: 0 0 6px 0;
        letter-spacing: -0.5px;
        line-height: 1.25;
    }

    .welcome-text p {
        color: var(--text-muted);
        font-family: 'DM Sans', 'Plus Jakarta Sans', sans-serif;
        font-size: 0.95rem;
        font-weight: 500;
        margin: 0;
        line-height: 1.5;
    }

    .welcome-name {
        color: var(--accent-gold);
        font-weight: 700;
    }

    .welcome-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 10px 14px;
        align-items: center;
        margin-top: 12px;
        position: relative;
        z-index: 1;
    }

    .welcome-email {
        color: var(--text-muted);
        font-size: 0.9rem;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .welcome-quote {
        margin-top: 14px;
        display: inline-flex;
        align-items: flex-start;
        gap: 10px;
        padding: 12px 16px;
        background: var(--gold-light);
        border: 1px solid rgba(176, 141, 72, 0.2);
        border-radius: 14px;
        color: var(--text-main);
        font-size: 0.9rem;
        font-weight: 600;
        max-width: 760px;
        position: relative;
        z-index: 1;
    }

    html[data-skin="dark"] .welcome-quote {
        background: #111827;
        border-color: #374151;
        color: #e5e7eb;
    }

    .welcome-quote i {
        color: var(--accent-gold);
        font-size: 1.1rem;
        margin-top: 1px;
    }

    /* Profil foto / avatar di banner - bisa diklik popup */
    .banner-avatar {
        width: 110px;
        height: 110px;
        border-radius: 22px;
        background: #f1f5f9;
        border: 2px solid var(--border-color);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: var(--accent-gold);
        font-family: 'DM Sans', 'Plus Jakarta Sans', sans-serif;
        font-weight: 700;
        font-size: 2.2rem;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
        position: relative;
        z-index: 1;
        overflow: hidden;
        cursor: pointer;
        transition: var(--transition);
    }

    html[data-skin="dark"] .banner-avatar {
        background: #020617;
        border-color: #1f2937;
        box-shadow: 0 8px 20px rgba(0,0,0,0.6);
    }

    .banner-avatar:hover {
        border-color: var(--accent-gold);
        box-shadow: 0 8px 20px rgba(176, 141, 72, 0.15);
    }

    .banner-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    /* Modal popup foto profil */
    .photo-popup-backdrop {
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.6);
        backdrop-filter: blur(4px);
        z-index: 9999;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }

    .photo-popup-backdrop.show {
        display: flex;
    }

    .photo-popup-content {
        background: var(--white);
        border-radius: 20px;
        padding: 16px;
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.2);
        max-width: 90vw;
        max-height: 90vh;
        overflow: auto;
    }

    html[data-skin="dark"] .photo-popup-content {
        background: #020617;
        border-radius: 20px;
        box-shadow: 0 25px 50px rgba(0,0,0,0.9);
    }

    html[data-skin="dark"] .photo-popup-close {
        background: #1e293b !important;
        border: 1px solid #374151;
        color: #ffffff !important;
    }

    /* === Dark mode: peserta konsisten === */
    html[data-skin="dark"] .welcome-text h1,
    html[data-skin="dark"] .display-name { color: #ffffff !important; }
    html[data-skin="dark"] .welcome-text p,
    html[data-skin="dark"] .welcome-email,
    html[data-skin="dark"] .greeting-text { color: #9ca3af !important; }
    html[data-skin="dark"] .welcome-name { color: #fbbf24 !important; }
    html[data-skin="dark"] .quote-badge {
        background: #111827 !important;
        border-color: #374151 !important;
    }
    html[data-skin="dark"] .quote-badge span { color: #fbbf24 !important; }
    html[data-skin="dark"] .section-card {
        background: #0f172a !important;
        border-color: #1f2937 !important;
    }
    html[data-skin="dark"] .section-card:hover {
        border-color: #fbbf24 !important;
        box-shadow: 0 8px 20px rgba(0,0,0,0.4) !important;
    }
    html[data-skin="dark"] .section-title,
    html[data-skin="dark"] .data-label,
    html[data-skin="dark"] .data-value,
    html[data-skin="dark"] .doc-item .doc-label { color: #ffffff !important; }
    html[data-skin="dark"] .data-row { border-color: #1f2937 !important; color: #ffffff !important; }
    html[data-skin="dark"] .doc-item {
        background: #1e293b !important;
        border-color: #374151 !important;
        color: #ffffff !important;
    }
    html[data-skin="dark"] .doc-item.nodin-empty {
        background: #450a0a !important;
        border-color: #dc2626 !important;
    }
    html[data-skin="dark"] .doc-item.nodin-filled {
        background: #052e16 !important;
        border-color: #22c55e !important;
    }
    html[data-skin="dark"] .doc-item:not(.nodin-empty):not(.nodin-filled):hover {
        background: #0f172a !important;
        border-color: #fbbf24 !important;
    }
    html[data-skin="dark"] .btn-download {
        background: #1e293b !important;
        border-color: #475569 !important;
        color: #ffffff !important;
    }
    html[data-skin="dark"] .btn-download:hover {
        background: #0f172a !important;
        color: #fbbf24 !important;
    }
    html[data-skin="dark"] .btn-view {
        background: #1e3a5f !important;
        color: #93c5fd !important;
    }
    html[data-skin="dark"] .btn-view:hover {
        background: #2563eb !important;
        color: #ffffff !important;
    }

    .photo-popup-content img {
        max-width: 100%;
        max-height: 80vh;
        border-radius: 12px;
        display: block;
    }

    .photo-popup-close {
        position: absolute;
        top: 16px;
        right: 16px;
        width: 40px;
        height: 40px;
        border: none;
        background: rgba(255,255,255,0.9);
        color: var(--text-main);
        border-radius: 50%;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        z-index: 10;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    .profile-shell {
        display: flex;
        align-items: center;
        gap: 25px;
        text-align: left;
    }

    .avatar-wrapper {
        position: relative;
        flex: 0 0 auto;
    }

    .avatar-main {
        width: 90px;
        height: 90px;
        background: linear-gradient(135deg, var(--primary-dark), #1e293b);
        color: var(--accent-gold);
        border-radius: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.2rem;
        font-weight: 800;
        box-shadow: 0 15px 35px rgba(15, 23, 42, 0.15);
        border: 2px solid white;
        position: relative;
        z-index: 2;
        animation: float 6s ease-in-out infinite;
    }

    .avatar-ring {
        position: absolute;
        top: -8px; left: -8px; right: -8px; bottom: -8px;
        border: 2px dashed var(--accent-gold);
        border-radius: 30px;
        opacity: 0.3;
        animation: rotate 20s linear infinite;
    }

    .greeting-text {
        font-size: 1rem;
        font-weight: 600;
        color: var(--text-muted);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .greeting-text .dot {
        width: 8px; height: 8px;
        background: #22c55e;
        border-radius: 50%;
        display: inline-block;
        box-shadow: 0 0 10px #22c55e;
    }

    .display-name {
        font-size: 2.25rem;
        font-weight: 800;
        color: var(--primary-dark);
        letter-spacing: -1px;
        line-height: 1.2;
        margin: 5px 0;
    }

    .quote-badge {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 10px 18px;
        background: var(--gold-light);
        border-left: 4px solid var(--accent-gold);
        border-radius: 12px;
        margin-top: 15px;
        max-width: 560px;
    }

    .quote-badge i { color: var(--accent-gold); font-size: 1.1rem; }
    .quote-badge span { font-size: 0.88rem; font-weight: 600; color: #856404; }

    /* Keyframes */
    @keyframes float {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-10px); }
    }
    @keyframes rotate {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }

    @media (max-width: 768px) {
        .welcome-banner { padding: 22px; }
        .welcome-text h1 { font-size: 1.5rem; }
        .welcome-actions { justify-content: center; }
        .welcome-quote { margin-left: auto; margin-right: auto; }
    }

    .welcome-name {
        color: var(--accent-gold);
        font-weight: 700;
        font-size: 1.5rem;
    }

    /* Section Cards */
    .section-card {
        background: #ffffff;
        border-radius: 16px;
        padding: 1.5rem;
        border: 1px solid var(--border-color);
        height: 100%;
        transition: var(--transition);
    }

    .section-card:hover {
        border-color: var(--accent-gold);
        box-shadow: 0 8px 20px rgba(0,0,0,0.03);
    }

    .section-title {
        font-weight: 700;
        font-size: 1rem;
        color: var(--primary-dark);
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 1.5rem;
    }

    .section-title i {
        color: var(--accent-gold);
    }

    /* Data Table-like Rows */
    .data-row {
        display: flex;
        justify-content: space-between;
        gap: 4px 16px;
        flex-wrap: wrap;
        padding: 12px 0;
        border-bottom: 1px solid #f1f5f9;
        font-size: 0.9rem;
    }

    .data-row:last-child { border-bottom: none; }

    .data-label {
        color: var(--text-muted);
        font-weight: 500;
        flex-shrink: 0;
    }

    /* nilai panjang (tgl lahir, periode) tidak menabrak label di layar sempit */
    .data-row > :last-child {
        text-align: right;
        word-break: break-word;
        min-width: 0;
    }

    /* Nodin doc-item (sama seperti biodata) */
    .doc-item {
        padding: 14px;
        background: #f8fafc;
        border: 1px solid var(--border-color);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 10px;
        transition: all 0.2s ease-in-out;
    }
    .doc-item:hover {
        transform: translateY(-1px);
    }
    .doc-item.nodin-empty {
        border: 2px solid #dc2626;
        background: #fef2f2;
    }
    .doc-item.nodin-empty:hover {
        border-color: #b91c1c;
        background: #fee2e2;
    }
    .doc-item.nodin-filled {
        border: 2px solid #10b981;
        background: #f0fdf4;
    }
    .doc-item.nodin-filled:hover {
        border-color: #059669;
        background: #d1fae5;
    }
    .doc-item:not(.nodin-empty):not(.nodin-filled):hover {
        border-color: var(--accent-gold);
        background: #fff;
    }
    .doc-item .doc-label {
        color: var(--primary-dark);
        font-weight: 600;
        font-size: 0.85rem;
    }

    .data-value {
        color: var(--primary-dark);
        font-weight: 600;
        text-align: right;
    }

    /* Minimalist Buttons */
    .btn-action {
        width: 32px; height: 32px;
        border-radius: 8px;
        display: inline-flex; align-items: center; justify-content: center;
        transition: var(--transition);
        text-decoration: none;
        font-size: 0.9rem;
    }

    .btn-view { background: #eff6ff; color: #2563eb; }
    .btn-view:hover { background: #2563eb; color: white; }

    .btn-download { background: #f8fafc; color: var(--text-main); border: 1px solid var(--border-color); }
    .btn-download:hover { background: var(--primary-dark); color: white; }

    /* --- MODAL MINIMALIS & RESPONSIF --- */
    .password-modal-backdrop {
        position: fixed;
        top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(15, 23, 42, 0.5);
        z-index: 9998;
        backdrop-filter: blur(4px);
        display: none;
        align-items: flex-start; /* Biar kalau di-zoom, modal mulai dari atas */
        justify-content: center;
        padding: 2rem 1rem;
        overflow-y: auto;
    }

    .password-modal {
        background: #ffffff;
        border-radius: 20px;
        padding: 2rem;
        width: 100%;
        max-width: 400px;
        position: relative;
        box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        margin-top: 2rem;
        border: 1px solid var(--border-color);
    }

    html[data-skin="dark"] .password-modal {
        background: #020617;
        border-color: #1f2937;
        box-shadow: 0 20px 40px rgba(0,0,0,0.7);
        color: #e5e7eb;
    }

    .password-modal-backdrop.show { display: flex; }

    .btn-close-modal {
        position: absolute; top: 1.5rem; right: 1.5rem;
        background: none; border: none; color: var(--text-muted);
        font-size: 1.25rem; cursor: pointer; transition: var(--transition);
    }

    .btn-close-modal:hover { color: #ef4444; }

    .password-modal-title {
        font-weight: 700; font-size: 1.25rem; color: var(--primary-dark);
        margin-bottom: 0.5rem;
    }

    html[data-skin="dark"] .password-modal-title {
        color: #e5e7eb;
    }

    .password-modal-subtitle {
        font-size: 0.85rem; color: var(--text-muted); margin-bottom: 1.5rem;
    }

    html[data-skin="dark"] .password-modal-subtitle {
        color: #9ca3af;
    }

    .password-warning {
        background: #fffbeb; border: 1px solid #fef3c7; color: #92400e;
        padding: 10px 15px; border-radius: 10px; font-size: 0.8rem;
        margin-bottom: 1.5rem; line-height: 1.4;
    }

    html[data-skin="dark"] .password-warning {
        background: #111827;
        border-color: #374151;
        color: #facc15;
    }

    .password-form-group { margin-bottom: 1rem; }
    .password-form-label {
        font-weight: 600; font-size: 0.8rem; color: var(--text-main);
        display: block; margin-bottom: 6px;
    }

    .password-form-input {
        width: 100%; padding: 10px 12px; border-radius: 10px;
        border: 1px solid var(--border-color); background: #fcfdfe;
        transition: var(--transition); font-size: 0.9rem;
    }

    html[data-skin="dark"] .password-form-input {
        background: #020617;
        border-color: #374151;
        color: #e5e7eb;
    }

    html[data-skin="dark"] .password-form-input::placeholder {
        color: #6b7280;
    }

    .password-form-input:focus {
        outline: none; border-color: var(--accent-gold);
        box-shadow: 0 0 0 3px rgba(176, 141, 72, 0.1);
    }

    .password-help { margin-top: 4px; }
    .password-help-item {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 0.75rem;
        margin-top: 2px;
        color: #dc2626;
        line-height: 1.2;
    }
    .password-help-item i {
        font-size: 0.95rem;
        line-height: 1;
        flex-shrink: 0;
        color: inherit;
    }
    .password-help-item .hint {
        color: #64748b;
        font-size: 0.72rem;
        margin-left: 4px;
        white-space: nowrap;
    }

    html[data-skin="dark"] .password-help-item {
        color: #f97316;
    }

    html[data-skin="dark"] .password-help-item .hint {
        color: #9ca3af;
    }

    .password-btn {
        background: var(--primary-dark); color: white;
        width: 100%; border: none; padding: 12px; border-radius: 10px;
        font-weight: 700; margin-top: 1rem; cursor: pointer; transition: var(--transition);
    }

    .password-btn:hover { background: #000; }

    .btn-trigger-password {
        background: transparent; color: var(--accent-gold);
        border: 1px solid var(--accent-gold); padding: 8px 20px;
        border-radius: 10px; font-weight: 600; font-size: 0.85rem;
        margin-top: 1.5rem; cursor: pointer; transition: var(--transition);
    }

    .btn-trigger-password:hover { background: var(--accent-gold); color: white; }

    .alert-success-custom {
        background: #f0fdf4; color: #166534; padding: 12px;
        border-radius: 10px; margin-bottom: 1.5rem; font-size: 0.85rem;
        font-weight: 600; text-align: center;
    }

    /* Modal preview dokumen di atas password backdrop (z 9998) */
    #filePreviewModal.modal { z-index: 10050 !important; }
</style>

@php
    $passwordModalMandatory = (isset($needPasswordChange) && $needPasswordChange);
    $passwordModalHasErrors = $errors->has('current_password') || $errors->has('new_password') || $errors->has('new_password_confirmation');

    // Helpers tampilan
    $capWords = function ($v) {
        $v = (string) ($v ?? '');
        $v = trim($v);
        if ($v === '') return '';
        // ucwords untuk multibyte (Indonesia) secara aman
        $v = mb_strtolower($v, 'UTF-8');
        return mb_convert_case($v, MB_CASE_TITLE, 'UTF-8');
    };

    $fmtTanggalId = function ($date) {
        if (empty($date)) return '-';
        try {
            return \Carbon\Carbon::parse($date)->locale('id')->translatedFormat('j F Y'); // 1 Januari 2026
        } catch (\Exception $e) {
            return (string) $date;
        }
    };

    $greeting = function () {
        $hour = (int) \Carbon\Carbon::now()->format('H');
        if ($hour >= 4 && $hour <= 10) return 'Selamat Pagi';
        if ($hour >= 11 && $hour <= 14) return 'Selamat Siang';
        if ($hour >= 15 && $hour <= 18) return 'Selamat Sore';
        return 'Selamat Malam';
    };

    // Tanggal selesai magang (untuk info nonaktif akun peserta)
    $tanggalSelesaiCarbon = null;
    if (!empty($data->tanggal_selesai)) {
        try {
            $tanggalSelesaiCarbon = \Carbon\Carbon::parse($data->tanggal_selesai);
        } catch (\Exception $e) {
            $tanggalSelesaiCarbon = null;
        }
    }
@endphp

<div class="main-container">
    <!-- Modal Ganti Password -->
    <div class="password-modal-backdrop" id="passwordModalBackdrop">
        <div class="password-modal" id="passwordModal">
            <button type="button" class="btn-close-modal" onclick="closePasswordModal()">
                <i class="ri-close-line"></i>
            </button>

            <h2 class="password-modal-title">Ganti Password</h2>
            <p class="password-modal-subtitle">Pastikan akun Anda aman dengan password baru.</p>

            @if($passwordModalMandatory)
                <div class="password-warning">
                    <i class="ri-information-line"></i> Anda menggunakan password bawaan. Harap segera ganti.
                </div>
            @endif

            <form action="{{ route('peserta.change-password') }}" method="POST">
                @csrf
                <div class="password-form-group">
                    <label class="password-form-label">Password Saat Ini</label>
                    <div style="position: relative;">
                        <input type="password" name="current_password" class="password-form-input" id="current_password" required>
                        <i class="ri-eye-off-line" style="position:absolute; right:12px; top:11px; cursor:pointer; color:var(--text-muted)" onclick="toggleVisibility('current_password', this)"></i>
                    </div>
                    @error('current_password') <div style="color:#ef4444; font-size:0.75rem; margin-top:4px;">{{ $message }}</div> @enderror
                </div>

                <div class="password-form-group">
                    <label class="password-form-label">Password Baru</label>
                    <div style="position: relative;">
                        <input type="password" name="new_password" class="password-form-input" id="new_password" required minlength="12" placeholder="Minimal 12 karakter">
                        <i class="ri-eye-off-line" style="position:absolute; right:12px; top:11px; cursor:pointer; color:var(--text-muted)" onclick="toggleVisibility('new_password', this)"></i>
                    </div>
                    <div id="peserta-password-help" class="password-help">
                        <div id="peserta-pass-rule-length" class="password-help-item">
                            <i class="ri-checkbox-circle-line"></i>
                            <span>Password minimal 12 karakter</span>
                        </div>
                        <div id="peserta-pass-rule-lower" class="password-help-item">
                            <i class="ri-checkbox-circle-line"></i>
                            <span>Harus mengandung huruf kecil</span>
                            <span class="hint">(contoh: a-z)</span>
                        </div>
                        <div id="peserta-pass-rule-upper" class="password-help-item">
                            <i class="ri-checkbox-circle-line"></i>
                            <span>Harus mengandung huruf besar</span>
                            <span class="hint">(contoh: A-Z)</span>
                        </div>
                        <div id="peserta-pass-rule-digit" class="password-help-item">
                            <i class="ri-checkbox-circle-line"></i>
                            <span>Harus mengandung angka</span>
                            <span class="hint">(contoh: 0-9)</span>
                        </div>
                        <div id="peserta-pass-rule-symbol" class="password-help-item">
                            <i class="ri-checkbox-circle-line"></i>
                            <span>Harus mengandung karakter khusus</span>
                            <span class="hint">(contoh: ! @ # $ % ^ &amp; * )</span>
                        </div>
                    </div>
                    @error('new_password') <div style="color:#ef4444; font-size:0.75rem; margin-top:4px;">{{ $message }}</div> @enderror
                </div>

                <div class="password-form-group">
                    <label class="password-form-label">Konfirmasi Password Baru</label>
                    <div style="position: relative;">
                        <input type="password" name="new_password_confirmation" class="password-form-input" id="new_password_confirmation" required>
                        <i class="ri-eye-off-line" style="position:absolute; right:12px; top:11px; cursor:pointer; color:var(--text-muted)" onclick="toggleVisibility('new_password_confirmation', this)"></i>
                    </div>
                </div>

                <button type="submit" class="password-btn">Perbarui Password</button>
            </form>
        </div>
    </div>

    <div class="container">
        <div class="welcome-outer">
            <div class="welcome-banner">
                <div class="d-flex align-items-start gap-3 flex-wrap">
                    @if(!empty($data->pas_foto))
                    <div class="banner-avatar" id="bannerAvatarPhoto" onclick="openPhotoPopup()" role="button" tabindex="0" title="Klik untuk memperbesar">
                        <img src="{{ file_url($data->pas_foto) }}" alt="Pas Foto">
                    </div>
                    @else
                    <div class="banner-avatar">
                        {{ strtoupper(substr(auth()->user()->nama, 0, 1)) }}
                    </div>
                    @endif
                    <div class="welcome-text flex-grow-1">
                        <h1>
                            {{ $greeting() }}, <span class="welcome-name">{{ $capWords(auth()->user()->nama) }}</span>
                        </h1>
                        <p>Selamat bergabung di {{ config('app.name') }} Setjen DPR RI. Yuk mulai hari ini dengan progres kecil yang konsisten.</p>

                        <div class="welcome-actions">
                            <span class="welcome-email">
                                <i class="ri-mail-send-line"></i> {{ auth()->user()->email }}
                            </span>
                            <button type="button"
                                    class="btn-trigger-password m-0"
                                    style="padding: 6px 14px; font-size: 0.78rem;"
                                    onclick="openPasswordModal()">
                                <i class="ri-lock-password-line"></i> Ganti Password
                            </button>
                            <a href="{{ route('peserta.progress') }}"
                               class="btn-trigger-password m-0"
                               style="padding: 6px 14px; font-size: 0.78rem; background:var(--accent-gold); color:white; border-color:var(--accent-gold); text-decoration:none; display:inline-flex; align-items:center; gap:6px;">
                                <i class="ri-bar-chart-grouped-fill"></i> Lihat Progress Magang
                            </a>
                        </div>

                        <div class="welcome-quote">
                            <i class="ri-chat-quote-fill"></i>
                            <span>“Fokus pada progres kecil hari ini, catat kegiatan, dan jangan lupa simpan draft sebelum checkout.”</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if(isset($peserta) && (int) $peserta->status === 3 && $tanggalSelesaiCarbon)
            <div class="alert alert-warning border-0 shadow-sm mt-3" role="alert">
                <i class="ri-alert-line me-2"></i>
                <strong>Perhatian:</strong>
                Akun peserta ini akan dinonaktifkan otomatis pada tanggal
                <strong>{{ $tanggalSelesaiCarbon->locale('id')->translatedFormat('d F Y') }}</strong>.
            </div>
        @endif

        @if(!empty($data->pas_foto))
        <!-- Modal popup foto profil (pas foto) -->
        <div class="photo-popup-backdrop" id="photoPopupBackdrop">
            <div class="photo-popup-content position-relative">
                <button type="button" class="photo-popup-close" onclick="closePhotoPopup()" aria-label="Tutup">
                    <i class="ri-close-line"></i>
                </button>
                <img src="{{ file_url($data->pas_foto) }}" alt="Pas Foto">
            </div>
        </div>
        @endif

        <div class="welcome-card">
            @if(session('success'))
                <div class="alert-success-custom">
                    <i class="ri-checkbox-circle-fill"></i> {{ session('success') }}
                </div>
            @endif

            <div class="row g-4 mt-4">
                <!-- Profil -->
                <div class="col-md-6">
                    <div class="section-card">
                        <div class="section-title"><i class="ri-user-line"></i> Profil Peserta</div>
                        <div class="data-row"><span class="data-label">NIK</span><span class="data-value">{{ $data->nik }}</span></div>
                        <div class="data-row"><span class="data-label">Kontak</span><span class="data-value">{{ $data->kontak }}</span></div>
                        <div class="data-row"><span class="data-label">Jenis Kelamin</span><span class="data-value">{{ $data->gender == 'L' ? 'Laki-laki' : 'Perempuan' }}</span></div>
                        <div class="data-row">
                            <span class="data-label">Tempat/Tanggal Lahir</span>
                            <span class="data-value">{{ $capWords($data->tempat_lahir) }}, {{ $fmtTanggalId($data->tanggal_lahir) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Magang -->
                <div class="col-md-6">
                    <div class="section-card">
                        <div class="section-title"><i class="ri-briefcase-line"></i> Informasi Magang</div>
                        <div class="data-row"><span class="data-label">Kategori</span><span class="data-value">{{ strtoupper((string) $data->kategori) }}</span></div>
                        <div class="data-row"><span class="data-label">Jurusan</span><span class="data-value">{{ $capWords($data->jurusan) }}</span></div>
                        <div class="data-row"><span class="data-label">Institusi</span><span class="data-value">{{ $capWords($data->instansi) }}</span></div>
                        <div class="data-row">
                            <span class="data-label">Periode</span>
                            <span class="data-value">{{ $fmtTanggalId($data->tanggal_mulai) }} - {{ $fmtTanggalId($data->tanggal_selesai) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Peraturan Magang -->
                <div class="col-12">
                    <div class="section-card">
                        <div class="section-title" style="font-size: 1.1rem;"><i class="ri-file-text-line"></i> Peraturan Magang</div>
                        <div class="peraturan-pkl-content" style="font-size: 1rem; font-weight: 600; color: var(--text-main); line-height: 1.75; text-align: justify;">{!! clean($peraturanPkl ?? '') !!}</div>
                        @if(empty($peraturanPkl))
                            <p class="text-muted mb-0 small">Belum ada peraturan magang. Admin dapat mengisi konten ini di menu Master Data &rarr; Peraturan Magang.</p>
                        @endif
                    </div>
                </div>

                <!-- Berkas Internal: Pra-Magang (peserta dapat mengunggah) -->
                <div class="col-12">
                    <div class="section-card">
                        <div class="section-title"><i class="ri-folder-shield-2-line"></i> Berkas Internal &mdash; Pra-Magang</div>
                        <p class="text-muted small mb-3">Unggah berkas berikut (PDF, 200&nbsp;KB&ndash;1&nbsp;MB). Berkas yang Anda unggah akan diverifikasi terlebih dahulu oleh verifikator satuan kerja.</p>
                        @php
                            $praDocs = $peserta ? $peserta->praMagangDocs() : [];
                        @endphp
                        @if(! $peserta)
                            <p class="text-muted mb-0 small">Data peserta belum tersedia.</p>
                        @else
                        <div class="row">
                            @foreach($praDocs as $doc)
                                @php
                                    $key = $doc['key'];
                                    $file = $doc['file'];
                                    $url = $file ? (str_contains($file, $doc['storage_dir'].'/') ? file_url($file) : file_url($doc['storage_dir'].'/'.$file)) : null;
                                    $meta = \Modules\Magang\App\Models\Magang\Peserta::praMagangStatusMeta($doc['status']);
                                    $isApproved = $doc['status'] === 3;
                                @endphp
                                <div class="col-md-4 mb-3">
                                    <div class="doc-item flex-column align-items-stretch {{ $url ? 'nodin-filled' : 'nodin-empty' }}">
                                        <div class="d-flex justify-content-between align-items-start gap-2 mb-2">
                                            <span class="doc-label">{{ $doc['no'] }}. {{ $doc['label'] }}</span>
                                            <span class="badge {{ $meta['class'] }}">{{ $meta['text'] }}</span>
                                        </div>
                                        @if($url)
                                            <div class="btn-group mb-2">
                                                <button type="button" class="btn btn-sm btn-outline-primary js-preview-file" data-file-url="{{ $url }}" data-file-name="{{ $doc['label'] }}"><i class="ri-eye-line"></i></button>
                                                <a href="{{ $url }}" download class="btn btn-sm btn-outline-secondary"><i class="ri-download-line"></i></a>
                                            </div>
                                        @endif
                                        @if($doc['status'] === 9 && $doc['catatan'])
                                            <div class="text-danger small mb-2"><i class="ri-information-line"></i> Ditolak: {{ $doc['catatan'] }}</div>
                                        @endif
                                        @if($isApproved)
                                            <div class="text-success small"><i class="ri-checkbox-circle-line"></i> Sudah disetujui verifikator.</div>
                                        @else
                                            <form action="{{ route('berkas.pra-magang.upload') }}" method="post" enctype="multipart/form-data" class="d-flex flex-column gap-2">
                                                @csrf
                                                <input type="hidden" name="type" value="{{ $key }}">
                                                <input type="file" name="file" accept="application/pdf" class="form-control form-control-sm" required>
                                                <button type="submit" class="btn btn-sm btn-success"><i class="ri-upload-cloud-line"></i> {{ $url ? 'Unggah Ulang' : 'Unggah' }}</button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Berkas -->
                <div class="col-12">
                    <div class="section-card">
                        <div class="section-title"><i class="ri-file-text-line"></i> Dokumen Pendukung</div>
                        <div class="row">
                            @php
                                $files = [
                                    'Pas Foto' => $data->pas_foto,
                                    'CV' => $data->cv,
                                    'Surat Pengantar' => $data->surat,
                                    'KTM / Kartu Pelajar' => $data->ktm,
                                    'Surat Rekomendasi' => $data->surat_rekomendasi,
                                    'Surat Lamaran' => $data->motivation,
                                ];
                            @endphp

                            @foreach($files as $label => $path)
                            <div class="col-sm-6">
                                <div class="data-row">
                                    <span class="data-label">{{ $label }}</span>
                                    <div style="display:flex; gap:8px;">
                                        @if($path)
                                            <button type="button" class="btn-action btn-view js-preview-file" data-file-url="{{ file_url($path) }}" data-file-name="{{ $label }}">
                                                <i class="ri-eye-line"></i>
                                            </button>
                                            <a href="{{ file_url($path) }}" download class="btn-action btn-download">
                                                <i class="ri-download-line"></i>
                                            </a>
                                        @else
                                            <span style="font-size:0.75rem; color:#cbd5e1;">File Tidak Tersedia</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Preview File (Global) -->
<div class="modal fade" id="filePreviewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content overflow-hidden" style="border-radius: 16px; border: 1px solid var(--border-color);">
            <div class="modal-header bg-light">
                <h5 class="modal-title" id="filePreviewModalLabel">Pratinjau Dokumen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0 d-flex justify-content-center align-items-center bg-dark" style="min-height: 70vh;">
                <iframe id="filePreviewFrame" src="" style="width: 100%; height: 80vh; border: 0; display: none;"></iframe>
                <img id="filePreviewImage" src="" alt="Preview" style="max-width: 100%; max-height: 80vh; display: none; object-fit: contain;" />
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const PASSWORD_MODAL_MANDATORY = {{ $passwordModalMandatory ? 'true' : 'false' }};
    const PASSWORD_MODAL_HAS_ERRORS = {{ $passwordModalHasErrors ? 'true' : 'false' }};

    function openPasswordModal() {
        document.getElementById('passwordModalBackdrop').classList.add('show');
        document.body.style.overflow = 'hidden';
    }

    function closePasswordModal() {
        document.getElementById('passwordModalBackdrop').classList.remove('show');
        document.body.style.overflow = 'auto';
    }

    function toggleVisibility(inputId, icon) {
        const input = document.getElementById(inputId);
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.replace('ri-eye-off-line', 'ri-eye-line');
        } else {
            input.type = 'password';
            icon.classList.replace('ri-eye-line', 'ri-eye-off-line');
        }
    }

    document.getElementById('passwordModalBackdrop').addEventListener('click', function(e) {
        if (e.target === this) closePasswordModal();
    });

    // Kontrol kapan modal otomatis muncul:
    // - Muncul otomatis hanya sekali per user per browser saat login pertama (localStorage)
    // - Selanjutnya hanya muncul ketika ada error, atau ketika tombol "Ganti Password" diklik manual
    (function setupInitialPasswordModal() {
        const userId = {{ (int) (auth()->id() ?? 0) }};
        const key = 'peserta_password_forced_' + userId;
        const alreadyForced = window.localStorage && localStorage.getItem(key) === '1';

        document.addEventListener('DOMContentLoaded', function () {
            const params = new URLSearchParams(window.location.search || '');

            // Jika ada error validasi password sebelumnya → langsung buka
            if (PASSWORD_MODAL_HAS_ERRORS) {
                openPasswordModal();
                return;
            }

            // Jika diminta eksplisit via query (?open_password=1) → buka modal
            if (params.get('open_password') === '1') {
                openPasswordModal();
                return;
            }

            // Jika password masih default & belum pernah dipaksa ganti di browser ini
            if (PASSWORD_MODAL_MANDATORY && !alreadyForced) {
                openPasswordModal();
                if (window.localStorage) {
                    localStorage.setItem(key, '1');
                }
            }
        });
    })();

    // Live validation password baru (peserta) saat mengetik
    (function setupPesertaPasswordValidation() {
        const pwdInput = document.getElementById('new_password');
        const confirmInput = document.getElementById('new_password_confirmation');
        if (!pwdInput) return;

        const ruleLength = document.getElementById('peserta-pass-rule-length');
        const ruleLower  = document.getElementById('peserta-pass-rule-lower');
        const ruleUpper  = document.getElementById('peserta-pass-rule-upper');
        const ruleDigit  = document.getElementById('peserta-pass-rule-digit');
        const ruleSymbol = document.getElementById('peserta-pass-rule-symbol');

        const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z0-9]).+$/;

        function validateNewPassword() {
            const val = pwdInput.value || '';
            const hasLength = val.length >= 12;
            const hasLower  = /[a-z]/.test(val);
            const hasUpper  = /[A-Z]/.test(val);
            const hasDigit  = /\d/.test(val);
            const hasSymbol = /[^A-Za-z0-9]/.test(val);

            const allValid = hasLength && hasLower && hasUpper && hasDigit && hasSymbol;

            if (ruleLength) ruleLength.style.color = hasLength ? '#16a34a' : '#dc2626';
            if (ruleLower)  ruleLower.style.color  = hasLower  ? '#16a34a' : '#dc2626';
            if (ruleUpper)  ruleUpper.style.color  = hasUpper  ? '#16a34a' : '#dc2626';
            if (ruleDigit)  ruleDigit.style.color  = hasDigit  ? '#16a34a' : '#dc2626';
            if (ruleSymbol) ruleSymbol.style.color = hasSymbol ? '#16a34a' : '#dc2626';

            if (val.length > 0 && !allValid) {
                pwdInput.style.borderColor = '#dc2626';
                pwdInput.style.boxShadow = '0 0 0 1px rgba(220,38,38,0.4)';
            } else {
                pwdInput.style.borderColor = '';
                pwdInput.style.boxShadow = '';
            }

            if (confirmInput && confirmInput.value.length > 0) {
                validateConfirmPassword();
            }
        }

        function validateConfirmPassword() {
            if (!confirmInput) return;
            const match = confirmInput.value === pwdInput.value;

            if (match || confirmInput.value.length === 0) {
                confirmInput.style.borderColor = '';
                confirmInput.style.boxShadow = '';
            } else {
                confirmInput.style.borderColor = '#dc2626';
                confirmInput.style.boxShadow = '0 0 0 1px rgba(220,38,38,0.4)';
            }
        }

        pwdInput.addEventListener('input', validateNewPassword);
        if (confirmInput) {
            confirmInput.addEventListener('input', validateConfirmPassword);
        }
    })();

    function openPhotoPopup() {
        var el = document.getElementById('photoPopupBackdrop');
        if (el) { el.classList.add('show'); document.body.style.overflow = 'hidden'; }
    }

    function closePhotoPopup() {
        var el = document.getElementById('photoPopupBackdrop');
        if (el) { el.classList.remove('show'); document.body.style.overflow = 'auto'; }
    }

    var photoBackdrop = document.getElementById('photoPopupBackdrop');
    if (photoBackdrop) {
        photoBackdrop.addEventListener('click', function(e) {
            if (e.target === this) closePhotoPopup();
        });
    }

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            if (document.getElementById('photoPopupBackdrop') && document.getElementById('photoPopupBackdrop').classList.contains('show')) {
                closePhotoPopup();
            } else {
                closePasswordModal();
            }
        }
    });

    // Pastikan modal Bootstrap berada di body (agar z-index/stacking benar)
    document.addEventListener('show.bs.modal', function (e) {
        var el = e.target;
        if (el && el.parentElement !== document.body) document.body.appendChild(el);
    });

    // Preview file/foto (image = img, selain itu = iframe untuk PDF/dokumen)
    document.addEventListener('DOMContentLoaded', function () {
        // Pop up welcome (muncul sekali per user di browser ini)
        try {
            const userId = {{ (int) (auth()->id() ?? 0) }};
            const userName = @json($capWords(auth()->user()->nama));
            const key = 'peserta_welcome_shown_' + userId;
            const alreadyShown = window.localStorage && localStorage.getItem(key) === '1';

            if (userId && !alreadyShown && window.Swal) {
                Swal.fire({
                    title: 'Selamat Datang!',
                    html: `
                        <div style="text-align:center;">
                            <div style="font-weight:800; color:#0f172a; font-size:1.05rem; margin-bottom:6px;">
                                Selamat bergabung, ${userName}.
                            </div>
                            <div style="color:#64748b; font-weight:600; font-size:0.9rem; line-height:1.5;">
                                Semangat menjalani magang hari ini. Yuk mulai dari progres kecil yang konsisten.
                            </div>
                        </div>
                    `,
                    icon: 'success',
                    confirmButtonText: 'Siap!',
                    confirmButtonColor: '#b08d48',
                    customClass: { container: 'swal-nav-safe' }
                }).then(() => {
                    if (window.localStorage) localStorage.setItem(key, '1');
                });
            }
        } catch (e) {
            // no-op
        }

        const buttons = document.querySelectorAll('.js-preview-file');
        const frame = document.getElementById('filePreviewFrame');
        const img = document.getElementById('filePreviewImage');
        const modalEl = document.getElementById('filePreviewModal');
        const labelEl = document.getElementById('filePreviewModalLabel');

        buttons.forEach(btn => {
            btn.addEventListener('click', function () {
                const fileUrl = (this.getAttribute('data-file-url') || '').trim();
                const fileName = this.getAttribute('data-file-name') || 'File';
                if (!fileUrl) return;

                const lowerUrl = fileUrl.toLowerCase();
                // Gambar: jpg, jpeg, png, gif, webp (dengan atau tanpa ? # di belakang)
                const isImage = /\.(jpg|jpeg|png|gif|webp)(\?|#|$)/.test(lowerUrl);

                if (labelEl) labelEl.innerHTML = '<i class="ri-eye-line me-2"></i> ' + fileName;

                if (!frame || !img) {
                    window.open(fileUrl, '_blank');
                    return;
                }

                // Reset dulu
                frame.src = '';
                frame.style.display = 'none';
                img.src = '';
                img.style.display = 'none';

                if (isImage) {
                    img.onerror = function () { window.open(fileUrl, '_blank'); };
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

        // Backdrop modal preview di atas password-backdrop (9998)
        if (modalEl) {
            modalEl.addEventListener('shown.bs.modal', function () {
                var backdrops = document.querySelectorAll('.modal-backdrop');
                if (backdrops.length) backdrops[backdrops.length - 1].style.zIndex = '10049';
            });
        }

        // Clear preview saat modal ditutup
        if (modalEl) {
            modalEl.addEventListener('hidden.bs.modal', function () {
                if (frame) { frame.src = ''; frame.style.display = 'none'; }
                if (img) { img.src = ''; img.style.display = 'none'; img.onerror = null; }
            });
        }
    });
</script>
@endsection
