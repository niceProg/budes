@extends('layouts.app')

@section('title', 'Detail Lamaran | Admin - SMART Setjen DPR RI')
@section('content')
@include('partials.select2_satker_assets')
<link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

<style>
    :root {
        --primary-dark: #0f172a;
        --accent-gold: #b08d48;
        --bg-light: #f1f5f9;
        --border-color: #e2e8f0;
        --text-main: #334155;
        --text-muted: #64748b;
        --white: #ffffff;
        --gold-light: #fdfaf3;
    }

    body {
        background-color: var(--bg-light);
        font-family: 'Plus Jakarta Sans', sans-serif;
        color: var(--text-main);
        line-height: 1.6;
    }

    .content-wrapper {
        padding: 3rem 0;
        position: relative;
    }

    /* Background Pattern */
    .content-wrapper::before {
        content: '';
        position: fixed;
        top: 0; left: 0; width: 100%; height: 100%;
        background-image: url('{{ asset("theme/admin-dashbyte/dist/assets/img/batiknew.png") }}');
        background-size: 350px;
        opacity: 0.04;
        z-index: -1;
    }

    /* Modern Card Styling */
    .modern-card {
        background: var(--white);
        border-radius: 20px;
        border: 1px solid rgba(226, 232, 240, 0.8);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.04);
        margin-bottom: 1.5rem;
        overflow: hidden;
        transition: transform 0.2s ease;
    }

    .card-header-custom {
        padding: 1.5rem;
        border-bottom: 1px solid var(--border-color);
        display: flex;
        align-items: center;
        gap: 12px;
        background: linear-gradient(to right, #ffffff, #fafafa);
    }

    .card-header-custom h6 {
        margin: 0;
        font-weight: 800;
        color: var(--primary-dark);
        font-size: 0.95rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .card-header-custom i {
        background: #fef3c7;
        color: var(--accent-gold);
        padding: 8px;
        border-radius: 10px;
        font-size: 1.2rem;
    }

    .card-body-custom {
        padding: 1.5rem;
    }

    /* Avatar Circle */
    .profile-section {
        display: flex;
        align-items: center;
        gap: 20px;
        margin-bottom: 2rem;
        background: white;
        padding: 20px;
        border-radius: 20px;
        border: 1px solid var(--border-color);
    }

    .avatar-placeholder {
        position: relative;
        width: 70px;
        height: 70px;
        background: var(--primary-dark);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 18px;
        font-size: 1.8rem;
        font-weight: 700;
        box-shadow: 0 4px 12px rgba(15, 23, 42, 0.2);
        overflow: hidden;
        cursor: pointer;
    }

    .avatar-placeholder img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 18px;
        display: block;
    }

    .avatar-initials {
        position: absolute;
        inset: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 1.8rem;
        color: #ffffff;
        text-transform: uppercase;
        pointer-events: none;
    }

    /* Hide initials when image is loaded successfully */
    .avatar-placeholder:has(img:not([style*="display: none"])) .avatar-initials {
        display: none;
    }

    /* Info Display Grid */
    .info-row {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1.5rem;
    }

    .info-item {
        padding: 10px;
        border-radius: 12px;
        transition: background 0.2s;
    }

    .info-item:hover {
        background: #f8fafc;
    }

    .info-label {
        font-size: 0.7rem;
        font-weight: 700;
        color: var(--text-muted);
        text-transform: uppercase;
        margin-bottom: 6px;
        display: block;
    }

    .info-value {
        font-size: 1rem;
        font-weight: 600;
        color: var(--primary-dark);
    }

    /* Status Badges */
    .status-pill {
        padding: 10px 20px;
        border-radius: 14px;
        font-weight: 800;
        font-size: 0.85rem;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        width: 100%;
        justify-content: center;
    }

    .category-badge {
        font-size: 1rem; font-weight: 600;
        padding: 1.5px 12px; background: var(--gold-light);
        color: var(--accent-gold); border-radius: 10px;
        border: 2.5px solid rgba(176, 141, 72, 0.2);
    }

    .pill-warning { background: #fffbeb; color: #9a3412; border: 1px solid #fed7aa; }
    .pill-success { background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; }
    .pill-danger { background: #fef2f2; color: #991b1b; border: 1px solid #fecaca; }

    /* File List */
    .file-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 0.75rem;
    }

    .file-card {
        background: #ffffff;
        border: 1px solid var(--border-color);
        border-radius: 14px;
        padding: 0.85rem 1.25rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .file-card:hover {
        border-color: var(--accent-gold);
        transform: translateX(5px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }

    .btn-action {
        padding: 12px 24px;
        border-radius: 14px;
        font-weight: 700;
        font-size: 0.95rem;
        border: none;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        gap: 10px;
        justify-content: center;
    }

    .btn-approve { background: #10b981; color: #fff; box-shadow: 0 4px 14px rgba(16, 185, 129, 0.3); }
    .btn-approve:hover { background: #059669; transform: translateY(-2px); }
    .btn-reject { background: #ef4444; color: #fff; box-shadow: 0 4px 14px rgba(248, 113, 113, 0.35); }
    .btn-reject:hover { background: #dc2626; transform: translateY(-2px); }
    .btn-resend-email { background: #3b82f6; color: #fff; box-shadow: 0 4px 14px rgba(59, 130, 246, 0.3); }
    .btn-resend-email:hover { background: #2563eb; transform: translateY(-2px); }
    .btn-resend-email:disabled { background: #94a3b8; box-shadow: none; cursor: not-allowed; transform: none; }

    .breadcrumb-item a { color: var(--text-muted); transition: color 0.2s; }
    .breadcrumb-item a:hover { color: var(--accent-gold); }

    @media (max-width: 768px) {
        .info-row { grid-template-columns: 1fr; }
        .profile-section { flex-direction: column; text-align: center; }
    }

    /* Lowongan Summary Card */
    .lowongan-summary-card {
        background: #ffffff;
        border-radius: 24px;
        border: 1px solid var(--border-color);
        padding: 1.75rem 1.75rem 1.5rem;
        margin-bottom: 1.75rem;
    }

    .lowongan-summary-header {
        display: grid;
        grid-template-columns: 120px 1.5fr 1fr;
        gap: 1.5rem;
        align-items: center;
    }

    .lowongan-summary-image {
        width: 120px;
        height: 120px;
        border-radius: 18px;
        overflow: hidden;
        border: 1px solid #e2e8f0;
        background: #f8fafc;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .lowongan-summary-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .lowongan-summary-placeholder {
        font-size: 2.2rem;
        color: var(--accent-gold);
    }

    .lowongan-summary-main h3 {
        font-size: 1.3rem;
        font-weight: 800;
        color: var(--primary-dark);
        margin-bottom: 0.4rem;
    }

    .lowongan-summary-meta {
        font-size: 0.9rem;
        color: var(--text-muted);
        margin-bottom: 0.25rem;
    }

    .lowongan-summary-meta span.label {
        font-weight: 700;
        color: var(--text-main);
        margin-right: 4px;
    }

    .lowongan-summary-deadline {
        text-align: right;
        font-size: 0.9rem;
    }

    .lowongan-summary-deadline-title {
        font-size: 0.75rem;
        text-transform: uppercase;
        font-weight: 700;
        color: var(--text-muted);
        letter-spacing: 0.6px;
        margin-bottom: 4px;
    }

    .lowongan-summary-deadline-date {
        font-weight: 700;
        color: #b91c1c;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .lowongan-summary-divider {
        border-top: 5px solid var(--gold-solid);
        margin: 1.25rem 0 1rem 0;
    }

    .lowongan-summary-detail-title {
        font-size: 0.8rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        color: var(--text-muted);
        margin-bottom: 0.4rem;
    }

    .lowongan-summary-detail-content {
        font-size: 0.95rem;
        color: var(--text-main);
        max-height: 180px;
        overflow-y: auto;
        padding-right: 6px;
    }

    .lowongan-summary-detail-content::-webkit-scrollbar {
        width: 6px;
    }

    .lowongan-summary-detail-content::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 999px;
    }

    .lowongan-summary-detail-content::-webkit-scrollbar-thumb {
        background: #cbd5f5;
        border-radius: 999px;
    }

    .lowongan-summary-detail-content::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }

    .lowongan-summary-detail-content p:last-child {
        margin-bottom: 0;
    }

    @media (max-width: 992px) {
        .lowongan-summary-header {
            grid-template-columns: 100px 1fr;
            grid-template-rows: auto auto;
        }
        .lowongan-summary-deadline {
            text-align: left;
        }
    }

    @media (max-width: 768px) {
        .lowongan-summary-header {
            grid-template-columns: 1fr;
            text-align: left;
        }
        .lowongan-summary-image {
            margin: 0 auto;
        }
        .lowongan-summary-deadline {
            text-align: left;
        }
    }

    /* Loading setelah konfirmasi Setujui/Tolak (sama dengan register) */
    .loading-modal {
        display: none;
        position: fixed;
        z-index: 3000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background: rgba(15, 23, 42, 0.75);
        backdrop-filter: blur(5px);
        align-items: center;
        justify-content: center;
    }
    .loading-modal.show {
        display: flex;
    }
    .loading-modal-content {
        background: white;
        padding: 2rem 1.75rem;
        border-radius: 20px;
        max-width: 420px;
        width: 90%;
        text-align: center;
        box-shadow: 0 20px 40px rgba(17, 0, 0, 0.18);
        animation: slideUp 0.25s ease-out;
    }
    .loading-spinner {
        width: 54px;
        height: 54px;
        border-radius: 50%;
        border: 5px solid rgba(176, 141, 72, 0.25);
        border-top-color: #b08d48;
        margin: 0 auto 14px;
        animation: spin 0.9s linear infinite;
    }
    @keyframes spin {
        to { transform: rotate(360deg); }
    }
    @keyframes slideUp {
        from { opacity: 0; transform: translateY(12px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .loading-title {
        color: var(--primary-dark);
        font-weight: 800;
        font-size: 1.05rem;
        margin: 0 0 6px 0;
    }
    .loading-subtitle {
        color: var(--text-muted);
        font-weight: 600;
        font-size: 0.9rem;
        margin: 0;
        line-height: 1.45;
    }

    /* === Dark mode: override agar /lamaran/{id} konsisten === */
    html[data-skin="dark"] .content-wrapper { background: transparent !important; }
    html[data-skin="dark"] .content-wrapper::before { opacity: 0.03 !important; }
    html[data-skin="dark"] .modern-card {
        background: #0f172a !important;
        border-color: #1f2937 !important;
        color: #ffffff !important;
    }
    html[data-skin="dark"] .card-header-custom {
        background: #0f172a !important;
        border-bottom-color: #1f2937 !important;
        color: #ffffff !important;
    }
    html[data-skin="dark"] .card-header-custom h6 { color: #ffffff !important; }
    html[data-skin="dark"] .card-header-custom i {
        background: #1e293b !important;
        color: #fbbf24 !important;
    }
    html[data-skin="dark"] .card-body-custom {
        background: #0f172a !important;
        color: #ffffff !important;
    }
    html[data-skin="dark"] .profile-section {
        background: #0f172a !important;
        border-color: #1f2937 !important;
        color: #ffffff !important;
    }
    html[data-skin="dark"] .profile-section h4,
    html[data-skin="dark"] .profile-section .text-muted { color: #ffffff !important; }
    html[data-skin="dark"] .info-label,
    html[data-skin="dark"] .info-value { color: #ffffff !important; }
    html[data-skin="dark"] .info-value.text-primary { color: #93c5fd !important; }
    html[data-skin="dark"] .info-item:hover { background: rgba(30, 41, 59, 0.6) !important; }
    html[data-skin="dark"] .category-badge {
        background: #1e293b !important;
        border-color: #475569 !important;
        color: #fbbf24 !important;
    }
    html[data-skin="dark"] .lowongan-summary-card {
        background: #0f172a !important;
        border-color: #1f2937 !important;
        color: #ffffff !important;
    }
    html[data-skin="dark"] .lowongan-summary-image {
        background: #1e293b !important;
        border-color: #374151 !important;
    }
    html[data-skin="dark"] .lowongan-summary-placeholder { color: #fbbf24 !important; }
    html[data-skin="dark"] .lowongan-summary-main h3 { color: #ffffff !important; }
    html[data-skin="dark"] .lowongan-summary-meta,
    html[data-skin="dark"] .lowongan-summary-meta .label { color: #ffffff !important; }
    html[data-skin="dark"] .lowongan-summary-deadline-title { color: #ffffff !important; }
    html[data-skin="dark"] .lowongan-summary-deadline-date { color: #fca5a5 !important; }
    html[data-skin="dark"] .lowongan-summary-detail-title,
    html[data-skin="dark"] .lowongan-summary-detail-content { color: #ffffff !important; }
    html[data-skin="dark"] .lowongan-summary-detail-content::-webkit-scrollbar-track { background: #1e293b !important; }
    html[data-skin="dark"] .lowongan-summary-detail-content::-webkit-scrollbar-thumb { background: #475569 !important; }
    html[data-skin="dark"] .lowongan-summary-stats span {
        background: #1e293b !important;
        border: 1px solid #374151 !important;
        color: #ffffff !important;
    }
    html[data-skin="dark"] .lowongan-summary-divider { border-top-color: #475569 !important; }
    html[data-skin="dark"] .file-card {
        background: #0f172a !important;
        border-color: #1f2937 !important;
        color: #ffffff !important;
    }
    html[data-skin="dark"] .file-card .text-dark,
    html[data-skin="dark"] .file-card .small.fw-bold { color: #ffffff !important; }
    html[data-skin="dark"] .file-card [style*="background: #fffcf0"] {
        background: #1e293b !important;
        color: #fbbf24 !important;
    }
    html[data-skin="dark"] .file-card .badge.bg-light { background: #1e293b !important; color: #ffffff !important; }
    html[data-skin="dark"] .bg-light.p-3 {
        background: #1e293b !important;
        border-color: #374151 !important;
        color: #ffffff !important;
    }
    html[data-skin="dark"] .bg-light.p-3 .text-muted,
    html[data-skin="dark"] .bg-light.p-3 .fw-bold { color: #ffffff !important; }
    html[data-skin="dark"] .btn-kembali-gold {
        background: #1e293b !important;
        border-color: #475569 !important;
        color: #ffffff !important;
    }
    html[data-skin="dark"] .btn-kembali-gold:hover {
        background: #0f172a !important;
        color: #fbbf24 !important;
        border-color: #fbbf24 !important;
    }
    html[data-skin="dark"] .breadcrumb-item.active.text-dark { color: #ffffff !important; }
    html[data-skin="dark"] .loading-modal-content {
        background: #0f172a !important;
        border: 1px solid #1f2937 !important;
        color: #ffffff !important;
    }
    html[data-skin="dark"] .loading-title,
    html[data-skin="dark"] .loading-subtitle { color: #ffffff !important; }
    html[data-skin="dark"] #filePreviewModal .modal-header { background: #0f172a !important; color: #ffffff !important; }
    html[data-skin="dark"] #filePreviewModal .modal-title { color: #ffffff !important; }
    html[data-skin="dark"] #filePreviewModal .modal-body { background: #1e293b !important; }
    html[data-skin="dark"] .pill-warning { background: #78350f !important; color: #fef3c7 !important; border-color: #92400e !important; }
    html[data-skin="dark"] .pill-success { background: #064e3b !important; color: #a7f3d0 !important; border-color: #065f46 !important; }
    html[data-skin="dark"] .pill-danger { background: #7f1d1d !important; color: #fecaca !important; border-color: #991b1b !important; }
    html[data-skin="dark"] hr.opacity-50 { border-color: #374151 !important; }
    html[data-skin="dark"] .modern-card.border-primary { border-color: #1f2937 !important; border-top-color: #475569 !important; }
    html[data-skin="dark"] .lowongan-summary-stats span i { color: #fbbf24 !important; }
    /* Dark mode: catatan penolakan */
    html[data-skin="dark"] #catatanPenolakan {
        background: #1e293b !important;
        border-color: #374151 !important;
        color: #ffffff !important;
    }
    html[data-skin="dark"] #catatanPenolakan::placeholder { color: #64748b !important; }
</style>

<style>.btn-kembali-gold { background: #ffffff !important; border: 1.5px solid #b08d48 !important; color: #b08d48 !important; font-weight: 600; border-radius: 12px; padding: 0.5rem 1.25rem; display: inline-flex; align-items: center; gap: 0.5rem; transition: all 0.25s ease; box-shadow: 0 1px 3px rgba(0,0,0,0.04); }.btn-kembali-gold:hover { background: #fdf6e9 !important; color: #8e6d2f !important; border-color: #b08d48 !important; transform: translateX(-3px); }.btn-kembali-gold:focus, .btn-kembali-gold:active { outline: none !important; box-shadow: 0 0 0 2px rgba(176,141,72,0.35) !important; color: #8e6d2f !important; border-color: #b08d48 !important; }</style>
@php
    use Illuminate\Support\Facades\Route;

    // Tentukan asal halaman dari nama route saat ini (tanpa query di URL)
    $currentRoute = Route::currentRouteName();
    $isFromAtasan = str_starts_with($currentRoute, 'atasan.lamaran.');
    $backRouteName = $isFromAtasan ? 'atasan.lamaran.index' : 'lamaran.index';
    $isPegawai = session('auth_type') === 'pegawai';
    $pegawaiRoles = (array) (session('role_as.roles_internal') ?? []);
    $normalizedPegawaiRoles = collect($pegawaiRoles)->map(function ($role) {
        return preg_replace('/[^a-z0-9]/', '', mb_strtolower(trim((string) ($role['nama'] ?? ''))));
    })->filter()->values()->all();
    $hasAtasanRole = collect($normalizedPegawaiRoles)->contains(fn ($roleName) => str_starts_with($roleName, 'atasan'));
    $hasAdminInternalRole = collect($normalizedPegawaiRoles)->contains(function ($roleName) {
        return str_starts_with($roleName, 'admin') && !str_starts_with($roleName, 'superadmin');
    });
    $hasSuperadminInternalRole = collect($normalizedPegawaiRoles)->contains(fn ($roleName) => str_starts_with($roleName, 'superadmin'));
    $pegawaiSatkerId = (int) (session('role_as.id_satker') ?? 0);
    $lamaranUnitKerjaId = (int) ($data->id_satker ?? 0);
    $isSameUnitKerja = $pegawaiSatkerId > 0 && $lamaranUnitKerjaId > 0 && $pegawaiSatkerId === $lamaranUnitKerjaId;
    $canStage2Action = $isPegawai && ($hasAtasanRole || $hasAdminInternalRole) && $isSameUnitKerja;
    $authUserRole = (!$isPegawai && auth()->check()) ? (auth()->user()->roles ?? null) : null;
    $canStage1Action = ($isPegawai && $hasSuperadminInternalRole)
        || (!$isPegawai && in_array($authUserRole, ['admin', 'superadmin'], true));
@endphp

<div class="container content-wrapper">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <nav aria-label="breadcrumb" class="mb-0">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item">
                    <a href="#" class="text-decoration-none small fw-bold">
                        {{ $isFromAtasan ? 'Menu Atasan' : 'Admin Panel' }}
                    </a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route($backRouteName) }}" class="text-decoration-none small fw-bold">
                        Manajemen Pelamar
                    </a>
                </li>
                <li class="breadcrumb-item active small fw-bold text-dark" aria-current="page">Detail</li>
            </ol>
        </nav>
        <a href="{{ route($backRouteName) }}" class="btn btn-sm flex-shrink-0 btn-kembali-gold text-decoration-none">
            <i class="ri-arrow-left-line"></i> Kembali
        </a>
    </div>

    @php
        $lowongan = $data->lowongan;
        $lowonganFoto = optional($lowongan)->foto;
        $lowonganTitle = optional($lowongan)->title ?? 'Lowongan Umum';
        $lowonganUnit = optional(optional($lowongan)->satker)->nama ?? 'Umum';
        $lowonganKebutuhan = optional($lowongan)->jumlah_posisi;
        $lowonganDeadline = optional($lowongan)->deadline;
        $lowonganCreatedAt = optional($lowongan)->created_at;
        $lowonganDetail = optional($lowongan)->detail;
        $kuotaTercukupi = $data->id_lowongan && $lowonganKebutuhan > 0 && ($totalDisetujui ?? 0) >= $lowonganKebutuhan;

        // Jika lamaran berasal dari lowongan tertentu yang punya satker penerbit,
        // satuan kerja tujuan DIKUNCI -> superadmin tidak perlu memilih manual di stage 1.
        $lockedSatker = ($data->id_lowongan && $lowongan && $lowongan->id_satker) ? $lowongan->satker : null;
        $satkerLocked = (bool) $lockedSatker;
    @endphp

        <div class="lowongan-summary-card">
            <div class="lowongan-summary-header">
                <div class="lowongan-summary-image">
                    @if($lowonganFoto)
                        <img src="{{ file_url('lowongan/' . $lowonganFoto) }}"
                             alt="{{ $lowonganTitle }}"
                             onerror="this.style.display='none'; this.parentElement.innerHTML='<div class=&quot;lowongan-summary-placeholder&quot;><i class=&quot;ri-briefcase-line&quot;></i></div>';">
                    @else
                        <div class="lowongan-summary-placeholder">
                            <i class="ri-briefcase-line"></i>
                        </div>
                    @endif
                </div>
                <div class="lowongan-summary-main">
                    <h3>{{ $lowonganTitle }}</h3>
                    <div class="lowongan-summary-meta">
                        <span class="label">Satuan Kerja :</span>
                        {{ $lowonganUnit }}
                    </div>
                    <div class="lowongan-summary-meta">
                        <span class="label">Kebutuhan :</span>
                        {{ $lowonganKebutuhan ? $lowonganKebutuhan . ' Posisi' : '-' }}
                    </div>
                    <div class="lowongan-summary-meta">
                        <span class="label">Batas Lamaran :</span>
                        @if($lowonganDeadline)
                            {{ \Carbon\Carbon::parse($lowonganDeadline)->locale('id')->translatedFormat('d F Y') }}
                        @else
                            -
                        @endif
                    </div>
                    @if($data->id_lowongan)
                    <div class="lowongan-summary-stats" style="display: flex; gap: 1rem; margin-top: 10px; flex-wrap: wrap;">
                        <span style="display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px; background: #f1f5f9; border-radius: 10px; font-size: 0.85rem; font-weight: 700; color: var(--text-main);">
                            <i class="ri-user-add-line" style="color: var(--accent-gold);"></i>
                            {{ $totalPelamar ?? 0 }} Pelamar
                        </span>
                        <span style="display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px; background: #ecfdf5; border-radius: 10px; font-size: 0.85rem; font-weight: 700; color: #166534;">
                            <i class="ri-checkbox-circle-line"></i>
                            {{ $totalDisetujui ?? 0 }} Disetujui
                        </span>
                    </div>
                    @endif
                </div>
                <div class="lowongan-summary-deadline">
                    <div class="lowongan-summary-deadline-title">Dipublikasikan</div>
                    <div class="lowongan-summary-deadline-date">
                        <i class="ri-time-line"></i>
                        @if($lowonganCreatedAt)
                            {{ $lowonganCreatedAt->locale('id')->translatedFormat('d F Y') }}
                        @else
                            -
                        @endif
                    </div>
                </div>
            </div>

            <hr class="lowongan-summary-divider">

            <div class="lowongan-summary-detail">
                <div class="lowongan-summary-detail-title">Detail Lowongan</div>
                <div class="lowongan-summary-detail-content">
                    {!! clean($lowonganDetail ?? '<em>Detail lowongan tidak tersedia.</em>') !!}
                </div>
            </div>
        </div>

    <div class="profile-section">
        <div class="avatar-placeholder js-preview-file"
             @if($data->pas_foto)
                 data-file-url="{{ file_url($data->pas_foto) }}"
                 data-file-name="Pas Foto - {{ $data->nama }}"
             @endif
        >
            <span class="avatar-initials">{{ strtoupper(substr($data->nama, 0, 1)) }}</span>
            @if($data->pas_foto)
                <img src="{{ file_url($data->pas_foto) }}"
                     alt="{{ $data->nama }}"
                     onload="this.parentElement.querySelector('.avatar-initials').style.display='none';"
                     onerror="this.style.display='none'; this.parentElement.querySelector('.avatar-initials').style.display='flex';">
            @endif
        </div>
        <div>
            <h4 class="fw-800 mb-1" style="color: var(--primary-dark)">{{ ucwords(strtolower($data->nama)) }}</h4>
            <p class="text-muted mb-0 fw-bold"><i class="ri-map-pin-user-line me-1"></i> No. Pendaftaran: {{ $data->no_pendaftaran }}</p>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">

            <div class="modern-card">
                <div class="card-header-custom">
                    <i class="ri-fingerprint-line"></i>
                    <h6>Informasi Identitas</h6>
                </div>
                <div class="card-body-custom">
                    <div class="info-row">
                        <div class="info-item">
                            <span class="info-label">NIK (Nomor Induk Kependudukan)</span>
                            <span class="info-value">{{ $data->nik }}</span>
                        </div>
                        <div class="info-item">
                            @php
                                $labelNim = (strtoupper($data->kategori ?? '') === 'PKL') ? 'NISN' : 'NIM';
                            @endphp
                            <span class="info-label">{{ $labelNim }}</span>
                            <span class="info-value">{{ $data->nim_sn ?? '-' }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Alamat Email</span>
                            <span class="info-value text-primary">{{ $data->email }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">WhatsApp / No. HP</span>
                            <span class="info-value">{{ $data->kontak }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Jenis Kelamin</span>
                            <span class="info-value">{{ $data->gender == 'L' ? 'Laki-Laki' : 'Perempuan' }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Tempat, Tanggal Lahir</span>
                            <span class="info-value">{{ ucwords(strtolower($data->tempat_lahir)) }}, {{ \Carbon\Carbon::parse($data->tanggal_lahir)->locale('id')->translatedFormat('d F Y') }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Agama</span>
                            <span class="info-value">{{ optional($data->agama)->agama ?? '-' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modern-card">
                <div class="card-header-custom">
                    <i class="ri-contacts-line"></i>
                    <h6>Kontak Darurat &amp; Catatan</h6>
                </div>
                <div class="card-body-custom">
                    <div class="info-row">
                        <div class="info-item">
                            <span class="info-label">Nama Kontak Darurat</span>
                            <span class="info-value">{{ ucwords(strtolower($data->nama_kontak_darurat ?? '')) ?: '-' }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Hubungan</span>
                            <span class="info-value">{{ ucwords(strtolower($data->hubungan_kontak_darurat ?? '')) ?: '-' }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Nomor Kontak Darurat</span>
                            <span class="info-value">{{ $data->nomor_kontak_darurat ?? '-' }}</span>
                        </div>
                    </div>
                    <hr class="my-4 opacity-50">
                    <div class="info-item">
                        <span class="info-label">Catatan Khusus</span>
                        <span class="info-value" style="white-space: pre-line; font-weight: 500;">{{ $data->catatan_khusus ?: '-' }}</span>
                    </div>
                </div>
            </div>

            <div class="modern-card">
                <div class="card-header-custom">
                    <i class="ri-building-line"></i>
                    <h6>Latar Belakang Pendidikan</h6>
                </div>
                <div class="card-body-custom">
                    <div class="info-row">
                        <div class="info-item">
                            <span class="info-label">Asal Institusi</span>
                            <span class="info-value">{{ ucwords($data->instansi) }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Alamat Institusi</span>
                            <span class="info-value">{{ $data->alamat_instansi ?? '-' }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Program Studi</span>
                            <span class="info-value">{{ ucwords($data->jurusan) }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Minat</span>
                            <span class="info-value">{{ $data->minat ?? '-' }}</span>
                        </div>
                        @if(($data->kategori ?? '') !== 'PKL')
                            <div class="info-item">
                                <span class="info-label">Fakultas</span>
                                <span class="info-value">{{ $data->fakultas ?? '-' }}</span>
                            </div>
                        @endif
                        <div class="info-item">
                            <span class="info-label">Level Pendidikan</span>
                            <span class="info-value">
                                {{ optional($data->pendidikan)->strata ?? '-' }}
                                @if(optional($data->pendidikan)->pendidikan)
                                    ({{ optional($data->pendidikan)->pendidikan }})
                                @endif
                            </span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Kategori Magang</span>
                            <span class="info-value"><span class="category-badge">
                                @if($data->kategori === 'Lainnya' && !empty($data->kategori_lainnya))
                                    Lainnya - {{ ucwords($data->kategori_lainnya) }}
                                @else
                                    {{ ucwords($data->kategori) }}
                                @endif
                            </span></span>
                        </div>
                    </div>
                    <hr class="my-4 opacity-50">
                    <div class="info-item">
                        <span class="info-label">Durasi Magang Yang Diajukan</span>
                        <div class="d-flex align-items-center gap-2 mt-2">
                            <div class="bg-light p-3 rounded-3 flex-fill text-center border">
                                <span class="d-block small text-muted">Mulai</span>
                                <span class="fw-bold">{{ \Carbon\Carbon::parse($data->tanggal_mulai)->locale('id')->translatedFormat('j F Y') }}</span>
                            </div>
                            <i class="ri-arrow-right-line text-muted"></i>
                            <div class="bg-light p-3 rounded-3 flex-fill text-center border">
                                <span class="d-block small text-muted">Selesai</span>
                                <span class="fw-bold">{{ \Carbon\Carbon::parse($data->tanggal_selesai)->locale('id')->translatedFormat('j F Y') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modern-card">
                <div class="card-header-custom">
                    <i class="ri-shield-user-line"></i>
                    <h6>Kontak Pembimbing Lapangan</h6>
                </div>
                <div class="card-body-custom">
                    <div class="info-row">
                        <div class="info-item">
                            <span class="info-label">Nama Lengkap</span>
                            <span class="info-value">{{ ucwords(strtolower($data->nama_guru)) }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Email / Kontak</span>
                            <span class="info-value">{{ $data->email_guru }} / {{ $data->kontak_guru }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modern-card">
                <div class="card-header-custom">
                    <i class="ri-file-text-line"></i>
                    <h6>Surat Pengantar</h6>
                </div>
                <div class="card-body-custom">
                    <div class="info-row">
                        <div class="info-item">
                            <span class="info-label">Nomor Surat Pengantar</span>
                            <span class="info-value">{{ $data->nomor_surat_pengantar ?? '-' }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Jabatan Penandatangan Surat</span>
                            <span class="info-value">{{ $data->jabatan_penandatangan_surat ?? '-' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">

            <div class="modern-card shadow-sm border-primary" style="border-top: 4px solid var(--primary-dark)">
                <div class="card-header-custom">
                    <i class="ri-government-line"></i>
                    <h6>Panel Keputusan</h6>
                </div>
                <div class="card-body-custom">
                    <div class="mb-4">
                        <span class="info-label mb-2 text-center">Status Saat Ini</span>
                        @if($data->status == 1)
                            <span class="status-pill pill-warning"><i class="ri-loader-2-line ri-spin"></i> Diproses (Review Superadmin/Admin)</span>
                        @elseif($data->status == 2)
                            <span class="status-pill pill-warning"><i class="ri-loader-2-line ri-spin"></i> Diproses (Menunggu Approval Satuan Kerja)</span>
                        @elseif($data->status == 9)
                            <span class="status-pill pill-danger"><i class="ri-close-circle-fill"></i> Aplikasi Ditolak</span>
                            @if(!empty($data->catatan_penolakan))
                                <div class="mt-3 p-3 rounded-3 border" style="background: #fef2f2; border-color: #fecaca !important;">
                                    <span class="info-label mb-1" style="color: #991b1b;"><i class="ri-message-3-line me-1"></i>Catatan Penolakan</span>
                                    <p class="mb-0 fw-600" style="color: #991b1b; font-size: 0.9rem; line-height: 1.6; white-space: pre-line;">{{ $data->catatan_penolakan }}</p>
                                </div>
                            @endif
                        @else
                            <span class="status-pill pill-success"><i class="ri-checkbox-circle-fill"></i> Aplikasi Disetujui</span>
                        @endif
                    </div>

                    @if($data->id_satker)
                        <div class="mb-3 p-2 rounded-3 border" style="background: #f8fafc;">
                            <span class="info-label mb-1">Satuan Kerja Saat Ini</span>
                            <span class="fw-bold">{{ optional($satkerOptions->firstWhere('id', $data->id_satker))->nama ?? ('ID: ' . $data->id_satker) }}</span>
                        </div>
                    @endif

                    @if($data->status == 1 && $canStage1Action)
                        <div class="d-grid gap-3">
                            @if($satkerLocked)
                                @php
                                    $lockKuota = $lockedSatker->kuota;
                                    $lockPenuh = $lockedSatker->isKuotaPenuh(false);
                                    $lockAktif = $lockedSatker->pesertaAktifCount();
                                @endphp
                                <div class="p-3 rounded-3 border" style="background: #f0fdf4; border-color: #bbf7d0 !important;">
                                    <span class="info-label mb-1"><i class="ri-lock-2-line me-1"></i> Satuan Kerja Tujuan (Terkunci dari Lowongan)</span>
                                    <span class="fw-bold d-block">{{ $lockedSatker->nama }}</span>
                                    <small class="text-muted d-block mt-1" style="font-size: 0.75rem;">
                                        @if($lockKuota === null)
                                            Kuota: Unlimited
                                        @elseif($lockKuota === 0)
                                            Kuota: Tidak dibuka
                                        @elseif($lockPenuh)
                                            Kuota penuh ({{ $lockAktif }}/{{ $lockKuota }})
                                        @else
                                            Sisa kuota: {{ $lockedSatker->sisaKuota() }}/{{ $lockKuota }}
                                        @endif
                                    </small>
                                </div>
                                <small class="text-muted" style="font-size: 0.75rem;">
                                    <i class="ri-information-line"></i> Lamaran ini melamar lowongan milik {{ $lockedSatker->nama }}, sehingga penempatan otomatis terkunci ke satuan kerja tersebut.
                                </small>
                                <button type="button" class="btn-action btn-approve" onclick="lamaranUpdate('stage1_approve');">
                                    <i class="ri-check-double-line"></i> Setujui Tahap 1
                                </button>
                            @else
                            <div>
                                <label for="stage1Satker" class="info-label mb-2">Pilih Satuan Kerja Tujuan</label>
                                <select id="stage1Satker" class="form-select">
                                    <option value="">-- Pilih Satuan Kerja --</option>
                                    @foreach(($satkerOptions ?? collect()) as $unit)
                                        @php
                                            $sisaKuota = $unit->sisaKuota();
                                            $isPenuh = $unit->isKuotaPenuh(false);
                                            $pipelineCount = $unit->lamaranPipelineCount();
                                            $kuotaLabel = '';
                                            
                                            if ($unit->kuota === null) {
                                                $kuotaLabel = ' (Unlimited)';
                                            } elseif ($unit->kuota === 0) {
                                                $kuotaLabel = ' (Tidak dibuka)';
                                            } else {
                                                $aktif = $unit->pesertaAktifCount();
                                                if ($isPenuh) {
                                                    $kuotaLabel = " (Penuh: {$aktif}/{$unit->kuota})";
                                                } else {
                                                    $kuotaLabel = " (Sisa: {$sisaKuota}/{$unit->kuota}";
                                                    if ($pipelineCount > 0) {
                                                        $kuotaLabel .= ", {$pipelineCount} pending";
                                                    }
                                                    $kuotaLabel .= ")";
                                                }
                                            }
                                        @endphp
                                        <option value="{{ $unit->id }}" 
                                                {{ (int) $data->id_satker === (int) $unit->id ? 'selected' : '' }}
                                                {{ $isPenuh ? 'disabled' : '' }}
                                                data-penuh="{{ $isPenuh ? '1' : '0' }}"
                                                data-pipeline="{{ $pipelineCount }}"
                                                data-sisa="{{ $sisaKuota ?? 'unlimited' }}">
                                            {{ $unit->nama }}{{ $kuotaLabel }}
                                        </option>
                                    @endforeach
                                </select>
                                @if($canStage1Action)
                                    <small class="text-muted d-block mt-2" style="font-size: 0.75rem;">
                                        <i class="ri-information-line"></i> Satuan kerja yang penuh tidak dapat dipilih. "Pending" menunjukkan jumlah lamaran yang menunggu approval atasan.
                                    </small>
                                @endif
                            </div>
                            <button type="button" class="btn-action btn-approve" onclick="lamaranUpdate('stage1_approve');">
                                <i class="ri-check-double-line"></i> Setujui & Pilih Satuan Kerja
                            </button>
                            @endif
                            <div>
                                <label for="catatanPenolakan" class="info-label mb-2">Catatan Penolakan <span style="font-weight: 400; text-transform: none;">(opsional)</span></label>
                                <textarea id="catatanPenolakan" class="form-control" rows="3" maxlength="1000" placeholder="Tulis catatan penolakan jika diperlukan..." style="border-radius: 12px; font-size: 0.9rem; resize: vertical;"></textarea>
                                <small class="text-muted" style="font-size: 0.75rem;">Catatan ini akan ditampilkan ke pelamar dan dikirim via email.</small>
                            </div>
                            <button type="button" class="btn-action btn-reject" onclick="lamaranUpdate('stage1_reject');">
                                <i class="ri-close-line"></i> Tolak Final
                            </button>
                        </div>
                    @elseif($data->status == 2 && $canStage2Action)
                        <div class="d-grid gap-3">
                            <button type="button" class="btn-action btn-approve" onclick="lamaranUpdate('stage2_approve');">
                                <i class="ri-check-double-line"></i> Setujui Final
                            </button>
                            <div>
                                <label for="catatanPenolakan" class="info-label mb-2">Catatan Penolakan <span style="font-weight: 400; text-transform: none;">(opsional)</span></label>
                                <textarea id="catatanPenolakan" class="form-control" rows="3" maxlength="1000" placeholder="Tulis catatan penolakan jika diperlukan..." style="border-radius: 12px; font-size: 0.9rem; resize: vertical;"></textarea>
                                <small class="text-muted" style="font-size: 0.75rem;">Catatan ini akan ditampilkan ke pelamar dan dikirim via email.</small>
                            </div>
                            <button type="button" class="btn-action btn-reject" onclick="lamaranUpdate('stage2_reject');">
                                <i class="ri-close-line"></i> Tolak
                            </button>
                        </div>
                    @elseif($data->status == 3)
                        <div class="d-grid gap-3">
                            @if(isset($peserta))
                                <a href="{{ route('biodata.show', $peserta->id) }}" class="btn-action btn-approve text-decoration-none">
                                    <i class="ri-external-link-line"></i> Profil Peserta Magang
                                </a>
                            @endif
                            <button type="button" class="btn-action btn-resend-email" id="btnResendEmail" onclick="resendLamaranEmail();" data-type="accept">
                                <i class="ri-mail-send-line"></i> Kirim Ulang Email Penerimaan
                            </button>
                        </div>
                    @elseif($data->status == 9)
                        <div class="d-grid gap-3">
                            <div>
                                <label for="resendCatatanPenolakan" class="info-label mb-2">Edit Catatan Penolakan <span style="font-weight: 400; text-transform: none;">(opsional)</span></label>
                                <textarea id="resendCatatanPenolakan" class="form-control" rows="3" maxlength="1000" placeholder="Edit catatan penolakan jika diperlukan..." style="border-radius: 12px; font-size: 0.9rem; resize: vertical;">{{ $data->catatan_penolakan ?? '' }}</textarea>
                                <small class="text-muted" style="font-size: 0.75rem;">Kosongkan untuk menggunakan catatan yang sudah ada. Perubahan akan disimpan dan dikirim via email.</small>
                            </div>
                            <button type="button" class="btn-action btn-resend-email" id="btnResendEmail" onclick="resendLamaranEmail();" data-type="reject">
                                <i class="ri-mail-send-line"></i> Kirim Ulang Email Penolakan
                            </button>
                        </div>
                    @elseif($data->status == 2 && $isPegawai && ($hasAtasanRole || $hasAdminInternalRole) && !$isSameUnitKerja)
                        <div class="alert alert-secondary mb-0">
                            Menunggu approval dari satuan kerja ini.
                        </div>
                    @elseif(in_array($data->status, [1,2], true))
                        <div class="alert alert-secondary mb-0">
                            Menunggu diproses oleh role yang berwenang pada tahap ini.
                        </div>
                    @endif
                </div>
            </div>

            <div class="modern-card">
                <div class="card-header-custom">
                    <i class="ri-attachment-2"></i>
                    <h6>Lampiran Berkas</h6>
                </div>
                <div class="card-body-custom">
                    <div class="file-grid">
                        @php
                            $docs = [
                                'Pas Foto (3x4, Latar Merah)' => 'pas_foto',
                                'Curriculum Vitae (CV)' => 'cv',
                                'Surat Pengantar Institusi' => 'surat',
                                'KTM / Kartu Pelajar' => 'ktm',
                                'Surat Rekomendasi' => 'surat_rekomendasi',
                                'Proposal Magang / Motivation Letter' => 'motivation'
                            ];
                        @endphp

                        @foreach($docs as $label => $key)
                        <div class="file-card">
                            <div class="d-flex align-items-center gap-3">
                                <div style="color: var(--accent-gold); background: #fffcf0; padding: 6px; border-radius: 8px;">
                                    <i class="ri-file-pdf-2-line ri-lg"></i>
                                </div>
                                <span class="small fw-bold text-dark">{{ $label }}</span>
                            </div>
                            @if($data->$key)
                                <button type="button"
                                        class="btn btn-sm btn-link text-decoration-none fw-bold p-0 js-preview-file"
                                        data-file-url="{{ file_url($data->$key) }}"
                                        data-file-name="{{ $label }}"
                                        style="color: var(--accent-gold); font-size: 0.75rem;">
                                    <i class="ri-eye-line me-1"></i> Lihat
                                </button>
                            @else
                                <span class="badge bg-light text-muted fw-normal" style="font-size: 0.65rem;">N/A</span>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

                    </div>
                </div>
            </div>

<!-- Loading setelah klik Setujui/Tolak Lamaran -->
<div class="loading-modal" id="loadingModal" aria-hidden="true">
    <div class="loading-modal-content">
        <div class="loading-spinner" aria-hidden="true"></div>
        <p class="loading-title">Memproses...</p>
        <p class="loading-subtitle">Mohon tunggu. Jangan tutup atau refresh halaman.</p>
    </div>
</div>

<div class="modal fade" id="filePreviewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content overflow-hidden border-0 shadow-lg" style="border-radius: 20px;">
            <div class="modal-header border-0 bg-white px-4 py-3">
                <h5 class="modal-title fw-800" id="filePreviewModalLabel">Pratinjau Dokumen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0 d-flex justify-content-center align-items-center bg-secondary bg-opacity-10" style="min-height: 75vh;">
                <iframe id="filePreviewFrame" src="" style="width: 100%; height: 75vh; border: 0; display: none;"></iframe>
                <img id="filePreviewImage" src="" alt="Preview" style="max-width: 100%; max-height: 75vh; display: none; object-fit: contain;" />
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Logic Javascript Anda tetap utuh karena saya hanya mengubah class CSS dan struktur HTML pendukung
    function doSubmit(action, unitKerjaId = null) {
        const loadingEl = document.getElementById('loadingModal');
        if (loadingEl) {
            loadingEl.classList.add('show');
            document.body.style.overflow = 'hidden';
        }

            const form = document.createElement('form');
            form.method = 'POST';
            form.action = "{{ route('lamaran.update', $data->id) }}";
            const inputs = [
                { name: '_token', value: '{{ csrf_token() }}' },
                { name: '_method', value: 'PUT' },
                { name: 'action', value: action },
            ];
            if (unitKerjaId) {
                inputs.push({ name: 'id_satker', value: unitKerjaId });
            }
            // Include catatan penolakan if available
            const catatanEl = document.getElementById('catatanPenolakan');
            if (catatanEl && catatanEl.value.trim()) {
                inputs.push({ name: 'catatan_penolakan', value: catatanEl.value.trim() });
            }
            inputs.forEach(data => {
                const input = document.createElement('input');
                input.type = 'hidden'; input.name = data.name; input.value = data.value;
                form.appendChild(input);
            });
            document.body.appendChild(form);

        // Jeda singkat supaya loading sempat ter-render sebelum submit
        setTimeout(() => form.submit(), 50);
    }

    function lamaranUpdate(action) {
        const kuotaTercukupi = @json($kuotaTercukupi ?? false);
        const satkerLocked = @json($satkerLocked ?? false);
        const unitSelectEl = document.getElementById('stage1Satker');
        const unitKerjaId = unitSelectEl ? (unitSelectEl.value || '').trim() : '';

        // Jika satker terkunci dari lowongan, superadmin tidak perlu memilih manual
        // (backend menetapkan satker dari satker penerbit lowongan secara otomatis).
        if (action === 'stage1_approve' && !satkerLocked && !unitKerjaId) {
            Swal.fire({
                title: 'Satuan Kerja Wajib Dipilih',
                text: 'Pilih satuan kerja terlebih dahulu sebelum melanjutkan.',
                icon: 'warning',
                confirmButtonText: 'OK',
                confirmButtonColor: '#f59e0b',
                border: '0',
                borderRadius: '15px'
            });
            return;
        }

        // Warning untuk superadmin jika ada pipeline yang melampaui kuota di stage1
        if (action === 'stage1_approve' && unitSelectEl) {
            const selectedOption = unitSelectEl.options[unitSelectEl.selectedIndex];
            const pipelineCount = parseInt(selectedOption.getAttribute('data-pipeline') || '0', 10);
            const sisaKuota = selectedOption.getAttribute('data-sisa');
            
            if (sisaKuota !== 'unlimited' && pipelineCount > 0) {
                const sisaKuotaNum = parseInt(sisaKuota, 10);
                if (pipelineCount >= sisaKuotaNum) {
                    Swal.fire({
                        title: 'Perhatian: Pipeline Melampaui Kuota',
                        html: `Satuan kerja ini memiliki <strong>${pipelineCount} lamaran pending</strong> yang menunggu approval atasan, sementara sisa kuota hanya <strong>${sisaKuotaNum}</strong>.<br><br>Beberapa lamaran mungkin akan ditolak oleh atasan karena kuota penuh. Lanjutkan?`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#10b981',
                        confirmButtonText: 'Ya, Lanjut',
                        cancelButtonText: 'Batal',
                        border: '0',
                        borderRadius: '15px'
                    }).then((result) => {
                        if (!result.isConfirmed) return;
                        proceedWithStage1Confirm(action, unitKerjaId);
                    });
                    return;
                }
            }
        }

        if (action === 'stage2_approve' && kuotaTercukupi) {
            Swal.fire({
                title: 'Kuota Tercukupi',
                text: 'Kuota sudah tercukupi. Apakah Anda tetap ingin menambah peserta?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#10b981',
                confirmButtonText: 'Ya, Lanjut',
                cancelButtonText: 'Batal',
                border: '0',
                borderRadius: '15px'
            }).then((result) => {
                if (!result.isConfirmed) return;
                Swal.fire({
                    title: 'Konfirmasi',
                    text: 'Setujui permohonan magang ini?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#10b981',
                    confirmButtonText: 'Ya, Setujui',
                    cancelButtonText: 'Batal',
                    border: '0',
                    borderRadius: '15px'
                }).then((r) => {
                    if (r.isConfirmed) doSubmit(action, unitKerjaId);
                });
            });
            return;
        }

        const actionMap = {
            stage1_approve: { text: 'Setujui tahap 1 dan teruskan ke approval satuan kerja?', confirm: 'Ya, Setujui Tahap 1', color: '#10b981' },
            stage1_reject: { text: 'Tolak final permohonan magang ini?', confirm: 'Ya, Tolak Final', color: '#ef4444' },
            stage2_approve: { text: 'Setujui final permohonan magang ini?', confirm: 'Ya, Setujui Final', color: '#10b981' },
            stage2_reject: { text: 'Tolak lamaran ini? Lamaran akan ditolak secara permanen dan email penolakan akan dikirim ke pelamar.', confirm: 'Ya, Tolak', color: '#ef4444' },
        };
        const actionMeta = actionMap[action] || { text: 'Lanjutkan aksi ini?', confirm: 'Ya, Lanjut', color: '#10b981' };

        Swal.fire({
            title: 'Konfirmasi',
            text: actionMeta.text,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: actionMeta.color,
            confirmButtonText: actionMeta.confirm,
            cancelButtonText: 'Batal',
            border: '0',
            borderRadius: '15px'
        }).then((result) => {
            if (result.isConfirmed) doSubmit(action, unitKerjaId);
        });
    }

    // Helper function untuk konfirmasi stage1 setelah warning pipeline
    function proceedWithStage1Confirm(action, unitKerjaId) {
        const actionMap = {
            stage1_approve: { text: 'Setujui tahap 1 dan teruskan ke approval satuan kerja?', confirm: 'Ya, Setujui Tahap 1', color: '#10b981' },
        };
        const actionMeta = actionMap[action] || { text: 'Lanjutkan aksi ini?', confirm: 'Ya, Lanjut', color: '#10b981' };

        Swal.fire({
            title: 'Konfirmasi',
            text: actionMeta.text,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: actionMeta.color,
            confirmButtonText: actionMeta.confirm,
            cancelButtonText: 'Batal',
            border: '0',
            borderRadius: '15px'
        }).then((result) => {
            if (result.isConfirmed) doSubmit(action, unitKerjaId);
        });
    }

    // Resend Email (acceptance or rejection)
    function resendLamaranEmail() {
        const btn = document.getElementById('btnResendEmail');
        const emailType = btn ? btn.getAttribute('data-type') : 'email';
        const typeLabel = emailType === 'accept' ? 'penerimaan' : 'penolakan';

        Swal.fire({
            title: 'Kirim Ulang Email',
            html: 'Kirim ulang email ' + typeLabel + ' ke <strong>{{ e($data->email) }}</strong>?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#0d6efd',
            confirmButtonText: 'Ya, Kirim',
            cancelButtonText: 'Batal',
            border: '0',
            borderRadius: '15px'
        }).then((result) => {
            if (!result.isConfirmed) return;

            // Disable button & show loading
            if (btn) { btn.disabled = true; btn.innerHTML = '<i class="ri-loader-2-line ri-spin"></i> Mengirim...'; }

            // Build request body
            const body = {};
            if (emailType === 'reject') {
                const catatanEl = document.getElementById('resendCatatanPenolakan');
                body.catatan_penolakan = catatanEl ? catatanEl.value : '';
            }

            fetch('{{ route("lamaran.resend-email", $data->id) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(body)
            })
            .then(response => response.json().then(data => ({ ok: response.ok, status: response.status, data: data })))
            .then(({ ok, status, data }) => {
                if (ok) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Email Terkirim',
                        text: data.message,
                        confirmButtonColor: '#0d6efd',
                        border: '0',
                        borderRadius: '15px'
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal Mengirim',
                        text: data.message || 'Terjadi kesalahan saat mengirim email.',
                        confirmButtonColor: '#ef4444',
                        border: '0',
                        borderRadius: '15px'
                    });
                }
            })
            .catch(err => {
                console.error('Resend email error:', err);
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal Mengirim',
                    text: 'Terjadi kesalahan jaringan. Silakan coba lagi.',
                    confirmButtonColor: '#ef4444',
                    border: '0',
                    borderRadius: '15px'
                });
            })
            .finally(() => {
                // Re-enable button
                if (btn) {
                    btn.disabled = false;
                    btn.innerHTML = btn.getAttribute('data-type') === 'accept'
                        ? '<i class="ri-mail-send-line"></i> Kirim Ulang Email Penerimaan'
                        : '<i class="ri-mail-send-line"></i> Kirim Ulang Email Penolakan';
                }
            });
        });
    }

    // Preview Script (gunakan modal Bootstrap 5, support gambar & PDF)
    document.addEventListener('DOMContentLoaded', function () {
        const buttons = document.querySelectorAll('.js-preview-file');
        const frame = document.getElementById('filePreviewFrame');
        const img = document.getElementById('filePreviewImage');
        const modalEl = document.getElementById('filePreviewModal');
        const labelEl = document.getElementById('filePreviewModalLabel');

        // Pastikan modal selalu berada langsung di body untuk z-index yang rapi
        document.addEventListener('show.bs.modal', function (event) {
            const m = event.target;
            if (m && m.parentElement !== document.body) {
                document.body.appendChild(m);
            }
        });

        buttons.forEach(btn => {
            btn.addEventListener('click', function () {
                const fileUrl = (this.getAttribute('data-file-url') || '').trim();
                const fileName = this.getAttribute('data-file-name') || 'File';
                if (!fileUrl) return;

                const lowerUrl = fileUrl.toLowerCase();
                const isImage = /\.(jpg|jpeg|png|gif|webp)(\?|#|$)/.test(lowerUrl);

                if (labelEl) {
                    // Build the label without injecting the (user-controlled) file name as
                    // HTML — use a text node so a malicious applicant name cannot run script.
                    labelEl.innerHTML = '<i class="ri-file-search-line me-2"></i> ';
                    labelEl.appendChild(document.createTextNode(fileName));
                }

                if (!frame || !img) {
                    window.open(fileUrl, '_blank');
                    return;
                }

                // Reset state
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

        if (modalEl) {
            modalEl.addEventListener('hidden.bs.modal', function () {
                if (frame) { frame.src = ''; frame.style.display = 'none'; }
                if (img) { img.src = ''; img.style.display = 'none'; img.onerror = null; }
            });
        }
    });
</script>
@push('script')
<script>
document.addEventListener('DOMContentLoaded', function () {
    if (window.jQuery && $.fn.select2 && document.getElementById('stage1Satker')) {
        $('#stage1Satker').select2({
            placeholder: '-- Pilih Satuan Kerja --',
            allowClear: true,
            width: '100%'
        });
    }
});
</script>
@endpush
@endsection
