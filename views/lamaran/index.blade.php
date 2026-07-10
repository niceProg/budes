@extends('layouts.app')

@section('title', 'Lamaran Magang | Admin - SMART Setjen DPR RI')
@section('content')
<link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

@include('partials.table_skin_lamaran_css')
<style>
    :root {
        --primary-dark: #0f172a;
        --accent-gold: #b08d48;
        --gold-light: #fdfaf3;
        --gold-gradient: linear-gradient(135deg, #c5a059 0%, #917234 100%);
        --text-main: #334155;
        --text-muted: #64748b;
        --glass-white: rgba(255, 255, 255, 0.98);
        --transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }

    body, .content-wrapper {
        background-color: #f8fafc;
        background-image: url('{{ asset("theme/admin-dashbyte/dist/assets/img/batiknew.png") }}');
        background-size: 600px;
        background-attachment: fixed;
        min-height: 100vh;
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    .content-wrapper { position: relative; z-index: 1; padding: 2.5rem 0; }

    /* Overlay untuk batik agar lebih subtle */
    .content-wrapper::before {
        content: ''; position: fixed; top: 0; left: 0; width: 100%; height: 100%;
        background: linear-gradient(135deg, rgba(248, 250, 252, 0.97) 0%, rgba(241, 245, 249, 0.95) 100%);
        z-index: -1;
    }

    /* Dark mode: serahkan background ke layout (gelap bertekstur) */
    html[data-skin="dark"] body,
    html[data-skin="dark"] .content-wrapper {
        background-color: transparent !important;
        background-image: none !important;
    }
    html[data-skin="dark"] .content-wrapper::before {
        background: transparent !important;
    }
    html[data-skin="dark"] .lamaran-lowongan-umum {
        color: #ffffff !important;
    }

    /* Header Styling */
    .page-title-area { margin-bottom: 2.5rem; }
    .breadcrumb-item { font-size: 0.7rem; font-weight: 800; text-transform: uppercase; letter-spacing: 1.5px; color: var(--accent-gold); }
    .page-title { font-weight: 800; font-size: 2.2rem; color: var(--primary-dark); letter-spacing: -1px; margin-top: 5px; }

    /* Modern Card */
    .modern-card {
        background: var(--glass-white);
        border-radius: 30px;
        border: 1px solid rgba(226, 232, 240, 0.8);
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.04);
        overflow: hidden;
        backdrop-filter: blur(10px);
    }

    /* Toolbar Enhancements */
    .card-toolbar {
        padding: 1.8rem 2.2rem;
        background: white;
        display: flex; justify-content: space-between; align-items: center;
        border-bottom: 1px solid #f1f5f9;
    }

    .search-container .input-group {
        background: #f1f5f9;
        border-radius: 15px;
        padding: 5px 15px;
        border: 1.5px solid transparent;
        transition: var(--transition);
    }

    .search-container .input-group:focus-within {
        background: white;
        border-color: var(--accent-gold);
        box-shadow: 0 0 0 4px rgba(176, 141, 72, 0.1);
    }

    .search-container input {
        background: transparent !important;
        border: none !important;
        font-size: 0.9rem;
        font-weight: 600;
        color: var(--primary-dark);
    }

    /* Table Styling */
    .table-container { padding: 10px 20px; }
    .custom-table { border-collapse: separate; border-spacing: 0 12px; width: 100%; }
    .custom-table thead th {
        background: transparent;
        padding: 1rem 1.5rem;
        font-size: 0.75rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: var(--text-muted);
        border: none;
    }

    .custom-table tbody tr {
        background: white;
        transition: var(--transition);
        box-shadow: 0 2px 4px rgba(0,0,0,0.02);
    }

    .custom-table tbody tr:hover {
        transform: translateY(-3px) scale(1.002);
        box-shadow: 0 12px 25px rgba(0,0,0,0.05);
        z-index: 2;
    }

    .custom-table tbody td {
        padding: 1.5rem 1rem;
        vertical-align: middle;
        border: none;
        background: white;
    }

    .custom-table tbody td:first-child { border-radius: 18px 0 0 18px; text-align: center; font-weight: 800; color: var(--text-muted); }
    .custom-table tbody td:last-child { border-radius: 0 18px 18px 0; }

    /* Component Styling */
    .applicant-avatar {
        position: relative;
        width: 48px; height: 48px;
        background: var(--gold-gradient);
        border-radius: 16px;
        display: flex; align-items: center; justify-content: center;
        color: white; font-weight: 800; font-size: 1rem;
        box-shadow: 0 8px 16px rgba(176, 141, 72, 0.25);
        cursor: pointer;
        overflow: hidden;
    }

    .applicant-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 16px;
        display: block;
    }

    .avatar-initials {
        position: absolute;
        inset: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 1rem;
        color: #ffffff;
        text-transform: uppercase;
        pointer-events: none;
    }

    /* Hide initials when image is loaded successfully */
    .applicant-avatar:has(img:not([style*="display: none"])) .avatar-initials {
        display: none;
    }

    .applicant-name { font-weight: 700; font-size: 1.05rem; color: var(--primary-dark); letter-spacing: -0.3px; }

    .category-badge {
        font-size: 0.7rem; font-weight: 800;
        padding: 6px 14px; background: var(--gold-light);
        color: var(--accent-gold); border-radius: 10px;
        border: 1px solid rgba(176, 141, 72, 0.2);
    }

    .status-pill {
        padding: 8px 16px; border-radius: 12px;
        font-weight: 800; font-size: 0.75rem;
        display: inline-flex; align-items: center; gap: 8px;
        letter-spacing: 0.5px;
    }
    .pill-process { background: #eff6ff; color: #2563eb; }
    .pill-success { background: #ecfdf5; color: #059669; }
    .pill-danger { background: #fef2f2; color: #dc2626; }

    .btn-action {
        width: 45px; height: 45px;
        border-radius: 15px;
        display: inline-flex; align-items: center; justify-content: center;
        background: white; color: var(--primary-dark);
        transition: var(--transition);
        border: 1.5px solid #f1f5f9;
        text-decoration: none;
    }
    .btn-action:hover {
        background: var(--primary-dark);
        color: white;
        transform: rotate(10deg);
        box-shadow: 0 10px 20px rgba(15, 23, 42, 0.2);
    }

    .date-badge {
        background: #f8fafc; border: 1px solid #e2e8f0;
        padding: 8px 12px; border-radius: 12px;
        font-size: 0.85rem; font-weight: 700; display: flex; align-items: center; gap: 8px;
    }

    /* Footer Pagination */
    .footer-container {
        padding: 2rem;
        background: #fcfdfe;
        border-top: 1px solid #f1f5f9;
    }

    .pagination .page-link {
        border: none; padding: 10px 18px; margin: 0 3px;
        border-radius: 12px; font-weight: 700; color: var(--text-muted);
        background: #f1f5f9; transition: var(--transition);
    }
    .pagination .page-item.active .page-link {
        background: var(--gold-gradient);
        color: white;
        box-shadow: 0 8px 15px rgba(176, 141, 72, 0.3);
    }
    .pagination .page-item.disabled .page-link { background: transparent; opacity: 0.4; }

    /* ========== FILTER STYLING - SERAGAM ========== */
    .filter-section {
        background: rgba(248, 250, 252, 0.5);
        border-bottom: 1px solid #e2e8f0;
        padding: 1.25rem 1.5rem;
    }

    /* Filter Dropdown Button */
    .filter-dropdown .btn {
        background: white;
        border: 1.5px solid #e2e8f0;
        color: var(--text-main);
        font-weight: 700;
        font-size: 0.85rem;
        padding: 0.6rem 1.2rem;
        border-radius: 12px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.04);
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .filter-dropdown .btn:hover,
    .filter-dropdown .btn:focus,
    .filter-dropdown .btn.show {
        background: var(--gold-gradient);
        color: white;
        border-color: var(--accent-gold);
        transform: translateY(-2px);
        box-shadow: 0 8px 16px rgba(176, 141, 72, 0.25);
    }

    .filter-dropdown .btn i {
        font-size: 1rem;
    }

    /* Filter Badge */
    .filter-badge {
        background: rgba(255, 255, 255, 0.95);
        color: var(--accent-gold);
        font-weight: 800;
        font-size: 0.7rem;
        padding: 3px 8px;
        border-radius: 8px;
        margin-left: 4px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .filter-dropdown .btn:hover .filter-badge,
    .filter-dropdown .btn.show .filter-badge {
        background: rgba(255, 255, 255, 0.2);
        color: white;
    }

    /* Dropdown Menu */
    .filter-dropdown .dropdown-menu {
        min-width: 220px;
        max-height: 320px;
        overflow-y: auto;
        border: none;
        border-radius: 16px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.12);
        padding: 0.75rem;
        margin-top: 0.5rem;
        background: white;
    }

    .filter-dropdown .dropdown-menu::-webkit-scrollbar {
        width: 6px;
    }

    .filter-dropdown .dropdown-menu::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 10px;
    }

    .filter-dropdown .dropdown-menu::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 10px;
    }

    .filter-dropdown .dropdown-menu::-webkit-scrollbar-thumb:hover {
        background: var(--accent-gold);
    }

    /* Form Check (Checkbox Items) */
    .filter-dropdown .form-check {
        padding: 0.65rem 1rem;
        margin: 0;
        border-radius: 10px;
        transition: all 0.2s ease;
        cursor: pointer;
        display: flex;
        align-items: center;
    }

    .filter-dropdown .form-check:hover {
        background: linear-gradient(135deg, rgba(176, 141, 72, 0.08) 0%, rgba(176, 141, 72, 0.04) 100%);
        transform: translateX(4px);
    }

    .filter-dropdown .form-check-input {
        width: 1.1rem;
        height: 1.1rem;
        margin-top: 0;
        margin-right: 0.75rem;
        margin-bottom: 0;
        border: 2px solid #cbd5e1;
        border-radius: 6px;
        cursor: pointer;
        transition: all 0.2s ease;
        flex-shrink: 0;
        align-self: center;
    }

    .filter-dropdown .form-check-input:checked {
        background-color: var(--accent-gold);
        border-color: var(--accent-gold);
        box-shadow: 0 0 0 3px rgba(176, 141, 72, 0.15);
    }

    .filter-dropdown .form-check-input:focus {
        border-color: var(--accent-gold);
        box-shadow: 0 0 0 3px rgba(176, 141, 72, 0.15);
    }

    .filter-dropdown .form-check-label {
        font-weight: 600;
        font-size: 0.875rem;
        color: var(--text-main);
        cursor: pointer;
        user-select: none;
        margin-bottom: 0;
        display: flex;
        align-items: center;
        line-height: 1.5;
    }

    /* Filter Action Buttons */
    .btn-filter-apply {
        background: var(--gold-gradient);
        color: white;
        border: none;
        font-weight: 700;
        font-size: 0.85rem;
        padding: 0.6rem 1.5rem;
        border-radius: 12px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 4px 12px rgba(176, 141, 72, 0.25);
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-filter-apply:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(176, 141, 72, 0.35);
        color: white;
    }

    .btn-filter-reset {
        background: white;
        color: #dc2626;
        border: 1.5px solid #fecaca;
        font-weight: 700;
        font-size: 0.85rem;
        padding: 0.6rem 1.5rem;
        border-radius: 12px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-filter-reset:hover {
        background: #dc2626;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(220, 38, 38, 0.25);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .filter-section {
            padding: 1rem;
        }

        .filter-dropdown .btn {
            font-size: 0.8rem;
            padding: 0.5rem 1rem;
        }

        .btn-filter-apply,
        .btn-filter-reset {
            font-size: 0.8rem;
            padding: 0.5rem 1.2rem;
        }
    }

    /* ========== BULK ACTION STYLING ========== */
    .bulk-action-bar {
        padding: 1rem 1.5rem;
        background: linear-gradient(135deg, rgba(176, 141, 72, 0.06) 0%, rgba(176, 141, 72, 0.02) 100%);
        border-bottom: 1px solid rgba(176, 141, 72, 0.15);
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 1rem;
        justify-content: space-between;
    }

    .bulk-action-info {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .bulk-action-info .bulk-icon {
        width: 38px;
        height: 38px;
        border-radius: 12px;
        background: var(--gold-gradient);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.1rem;
        box-shadow: 0 4px 12px rgba(176, 141, 72, 0.3);
    }

    .bulk-action-info .bulk-text {
        font-weight: 700;
        font-size: 0.9rem;
        color: var(--primary-dark);
    }

    .bulk-action-info .bulk-count {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 26px;
        height: 26px;
        padding: 0 8px;
        background: var(--gold-gradient);
        color: white;
        border-radius: 9px;
        font-weight: 800;
        font-size: 0.8rem;
        box-shadow: 0 2px 8px rgba(176, 141, 72, 0.3);
    }

    .bulk-action-buttons {
        display: flex;
        gap: 0.75rem;
        flex-wrap: wrap;
    }

    .btn-bulk-approve {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        border: none;
        font-weight: 700;
        font-size: 0.85rem;
        padding: 0.6rem 1.5rem;
        border-radius: 12px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        cursor: pointer;
    }

    .btn-bulk-approve:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(16, 185, 129, 0.4);
        color: white;
    }

    .btn-bulk-approve:active {
        transform: translateY(0);
    }

    .btn-bulk-reject {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
        border: none;
        font-weight: 700;
        font-size: 0.85rem;
        padding: 0.6rem 1.5rem;
        border-radius: 12px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        cursor: pointer;
    }

    .btn-bulk-reject:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(239, 68, 68, 0.4);
        color: white;
    }

    .btn-bulk-reject:active {
        transform: translateY(0);
    }

    .btn-bulk-approve:disabled,
    .btn-bulk-reject:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none !important;
        box-shadow: none !important;
    }

    /* Bulk modal styling */
    .bulk-modal .modal-content {
        border-radius: 24px;
        border: none;
        overflow: hidden;
    }

    .bulk-modal .modal-header {
        border-bottom: 1px solid #f1f5f9;
        padding: 1.5rem 2rem;
    }

    .bulk-modal .modal-body {
        padding: 1.5rem 2rem;
    }

    .bulk-modal .modal-footer {
        border-top: 1px solid #f1f5f9;
        padding: 1rem 2rem;
    }

    .bulk-modal .modal-title {
        font-weight: 800;
        font-size: 1.1rem;
    }

    .bulk-warning-box {
        background: #fffbeb;
        border: 1.5px solid #fbbf24;
        border-radius: 14px;
        padding: 1rem 1.25rem;
        display: flex;
        gap: 0.75rem;
        align-items: flex-start;
    }

    .bulk-warning-box i {
        color: #f59e0b;
        font-size: 1.3rem;
        flex-shrink: 0;
        margin-top: 2px;
    }

    .bulk-warning-box p {
        margin: 0;
        font-size: 0.88rem;
        color: #92400e;
        font-weight: 600;
        line-height: 1.5;
    }

    .bulk-textarea {
        border: 1.5px solid #e2e8f0;
        border-radius: 14px;
        padding: 1rem;
        font-size: 0.9rem;
        font-weight: 600;
        color: var(--primary-dark);
        resize: vertical;
        min-height: 100px;
        transition: all 0.3s ease;
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    .bulk-textarea:focus {
        border-color: var(--accent-gold);
        box-shadow: 0 0 0 4px rgba(176, 141, 72, 0.1);
        outline: none;
    }

    @media (max-width: 576px) {
        .bulk-action-bar {
            flex-direction: column;
            align-items: stretch;
        }

        .bulk-action-buttons {
            justify-content: stretch;
        }

        .btn-bulk-approve,
        .btn-bulk-reject {
            flex: 1;
            justify-content: center;
        }
    }

</style>

<div class="content-wrapper">
    <div class="container-fluid px-md-5">
        <div class="page-title-area">
                <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1">
                    <li class="breadcrumb-item">Management System</li>
                    <li class="breadcrumb-item active">Verification Center</li>
                    </ol>
                </nav>
            <h1 class="page-title">Verifikasi Peserta Magang</h1>
            </div>

        <div class="modern-card">
            <div class="card-toolbar flex-wrap gap-4">
                <div class="d-flex align-items-center gap-2">
                    <div style="width: 5px; height: 25px; background: var(--gold-gradient); border-radius: 10px;"></div>
                    <h5 class="m-0 fw-800 text-dark">Daftar Tunggu Verifikasi</h5>
                </div>

                <div class="d-flex align-items-center gap-3">
                    <form method="GET" action="{{ request()->url() }}" class="search-container" id="searchForm">
                        <div class="input-group">
                            <i class="ri-search-2-line" style="color: var(--accent-gold); font-size: 1.2rem;"></i>
                            <input type="text" name="search" id="search" class="form-control shadow-none"
                                   placeholder="Cari nama atau instansi..."
                                   value="{{ request('search', '') }}">
                            @if(request('search'))
                                <a href="{{ request()->url() }}" class="text-muted ms-2"><i class="ri-close-circle-fill"></i></a>
                            @endif
                        </div>
                        <input type="hidden" name="per_page" value="{{ request('per_page', 10) }}">
                    </form>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-secondary rounded-pill px-3 dropdown-toggle"
                                type="button"
                                data-bs-toggle="dropdown"
                                aria-expanded="false">
                            <i class="ri-download-2-line me-1"></i> Export Data
                        </button>
                        @php
                            // Default: admin export route
                            $exportRouteName = $lamaranExportRoute ?? 'lamaran.export';
                        @endphp
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><h6 class="dropdown-header">Semua Data</h6></li>
                            <li>
                                <a class="dropdown-item" href="{{ route($exportRouteName, array_merge(request()->query(), ['scope' => 'all', 'format' => 'pdf'])) }}">
                                    PDF (Semua)
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route($exportRouteName, array_merge(request()->query(), ['scope' => 'all', 'format' => 'xlsx'])) }}">
                                    Excel (Semua)
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li><h6 class="dropdown-header">Sesuai Pagination</h6></li>
                            <li>
                                <a class="dropdown-item" href="{{ route($exportRouteName, array_merge(request()->query(), ['scope' => 'page', 'page' => request('page', 1), 'format' => 'pdf'])) }}">
                                    PDF (Halaman ini)
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route($exportRouteName, array_merge(request()->query(), ['scope' => 'page', 'page' => request('page', 1), 'format' => 'xlsx'])) }}">
                                    Excel (Halaman ini)
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Filter Section -->
            <div class="filter-section d-flex flex-wrap align-items-center gap-3">
                <form method="GET" action="{{ request()->url() }}" id="filterForm" class="d-flex flex-wrap align-items-center gap-3 flex-grow-1">
                    <!-- Filter Kategori -->
                    <div class="dropdown filter-dropdown">
                        <button class="btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="ri-filter-3-line"></i> Kategori
                            @if(request()->has('filter_kategori'))
                                <span class="badge filter-badge">{{ count((array)request('filter_kategori')) }}</span>
                            @endif
                        </button>
                        <ul class="dropdown-menu p-2" style="min-width: 200px;">
                            @foreach($kategoris ?? [] as $kat)
                                <li>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="filter_kategori[]" value="{{ $kat }}" id="kat_{{ $loop->index }}"
                                               {{ in_array($kat, (array)request('filter_kategori', [])) ? 'checked' : '' }}>
                                        <label class="form-check-label w-100" for="kat_{{ $loop->index }}">{{ ucwords($kat) }}</label>
                                    </div>
                            </li>
                            @endforeach
                        </ul>
                    </div>

                    <!-- Filter Status -->
                    <div class="dropdown filter-dropdown">
                        <button class="btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="ri-flag-line"></i> Status
                            @if(request()->has('filter_status'))
                                <span class="badge filter-badge">{{ count((array)request('filter_status')) }}</span>
                            @endif
                        </button>
                        <ul class="dropdown-menu p-2" style="min-width: 180px;">
                            <li>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="filter_status[]" value="1" id="stat_1"
                                           {{ in_array('1', (array)request('filter_status', [])) ? 'checked' : '' }}>
                                    <label class="form-check-label w-100" for="stat_1">Lamaran Diproses</label>
                                </div>
                            </li>
                            <li>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="filter_status[]" value="2" id="stat_2"
                                           {{ in_array('2', (array)request('filter_status', [])) ? 'checked' : '' }}>
                                    <label class="form-check-label w-100" for="stat_2">Menunggu Persetujuan</label>
                                </div>
                            </li>
                            <li>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="filter_status[]" value="3" id="stat_3"
                                           {{ in_array('3', (array)request('filter_status', [])) ? 'checked' : '' }}>
                                    <label class="form-check-label w-100" for="stat_3">Lolos</label>
                                </div>
                            </li>
                            <li>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="filter_status[]" value="9" id="stat_9"
                                           {{ in_array('9', (array)request('filter_status', [])) ? 'checked' : '' }}>
                                    <label class="form-check-label w-100" for="stat_9">Tolak</label>
                                </div>
                            </li>
                        </ul>
                    </div>

                    @if($isSuperadmin ?? false)
                        <!-- Filter Lowongan (superadmin) -->
                        <div class="dropdown filter-dropdown">
                            <button class="btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="ri-briefcase-4-line"></i> Lowongan
                                @if(request()->has('filter_lowongan'))
                                    <span class="badge filter-badge">{{ count((array)request('filter_lowongan')) }}</span>
                                @endif
                            </button>
                            <ul class="dropdown-menu p-2" style="min-width: 240px; max-height: 300px; overflow-y: auto;">
                                <li>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="filter_lowongan[]" value="umum" id="low_umum"
                                               {{ in_array('umum', (array)request('filter_lowongan', [])) ? 'checked' : '' }}>
                                        <label class="form-check-label w-100" for="low_umum">Umum</label>
                                    </div>
                                </li>
                                @foreach($lowonganList ?? [] as $low)
                                    <li>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="filter_lowongan[]" value="{{ $low->id }}" id="low_{{ $low->id }}"
                                                   {{ in_array((string) $low->id, array_map('strval', (array)request('filter_lowongan', []))) ? 'checked' : '' }}>
                                            <label class="form-check-label w-100" for="low_{{ $low->id }}">{{ $low->title }}</label>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <button type="submit" class="btn-filter-apply">
                        <i class="ri-filter-line"></i> Terapkan
                    </button>
                    @if(request()->has('filter_kategori') || request()->has('filter_status') || request()->has('filter_lowongan'))
                        <a href="{{ request()->url() }}?{{ http_build_query(request()->except(['filter_kategori', 'filter_status', 'filter_lowongan', 'page'])) }}" class="btn-filter-reset">
                            <i class="ri-close-line"></i> Reset
                        </a>
                    @endif
                    <input type="hidden" name="search" value="{{ request('search') }}">
                    <input type="hidden" name="per_page" value="{{ request('per_page', 10) }}">
                </form>
            </div>

            {{-- Bulk Action Bar (Only for atasan view with pending lamaran) --}}
            @if(($isAtasanView ?? false) && ($pendingCount ?? 0) > 0)
            <div class="bulk-action-bar" id="bulkActionBar">
                <div class="bulk-action-info">
                    <div class="bulk-icon">
                        <i class="ri-stack-line"></i>
                    </div>
                    <div>
                        <div class="bulk-text">
                            <span class="bulk-count">{{ $pendingCount }}</span>
                            lamaran menunggu persetujuan
                        </div>
                    </div>
                </div>
                <div class="bulk-action-buttons">
                    <button type="button" class="btn-bulk-approve" id="btnBulkApprove" onclick="showBulkModal('approve')">
                        <i class="ri-checkbox-circle-line"></i> Terima Semua
                    </button>
                    <button type="button" class="btn-bulk-reject" id="btnBulkReject" onclick="showBulkModal('reject')">
                        <i class="ri-close-circle-line"></i> Tolak Semua
                    </button>
                </div>
            </div>
            @endif

            {{-- Bulk Action Bar (admin/superadmin: aksi massal mengikuti filter yang diterapkan) --}}
            @if(($canBulkStage1 ?? false) || ($canBulkStage2 ?? false))
            <div class="bulk-action-bar" id="adminBulkActionBar">
                <div class="bulk-action-info">
                    <div class="bulk-icon">
                        <i class="ri-checkbox-multiple-line"></i>
                    </div>
                    <div>
                        <div class="bulk-text">Aksi massal — mengikuti filter yang sedang diterapkan</div>
                    </div>
                </div>
                <div class="bulk-action-buttons">
                    <button type="button" class="btn-bulk-approve" onclick="adminBulkAction('approve')">
                        <i class="ri-checkbox-circle-line"></i> Setuju Semua
                    </button>
                    <button type="button" class="btn-bulk-reject" onclick="adminBulkAction('reject')">
                        <i class="ri-close-circle-line"></i> Tolak Semua
                    </button>
                </div>
            </div>
            @endif

            <div class="px-4 py-3 d-flex align-items-center justify-content-between" style="background: rgba(248, 250, 252, 0.5);">
                <div class="text-muted fw-700" style="font-size: 0.85rem;">
                    Total Entri: <span class="text-dark">{{ $data->total() }}</span>
                </div>
                <form method="GET" action="{{ request()->url() }}" class="d-flex align-items-center gap-2">
                    <span class="fw-700 text-muted" style="font-size: 0.8rem;">Baris:</span>
                    <select name="per_page" class="form-select form-select-sm border-0 fw-800 shadow-sm"
                            style="border-radius: 8px; width: 80px; cursor: pointer;"
                            onchange="this.form.submit()">
                        @foreach([10, 25, 50, 100] as $size)
                            <option value="{{ $size }}" {{ request('per_page') == $size ? 'selected' : '' }}>{{ $size }}</option>
                        @endforeach
                    </select>
                    <input type="hidden" name="search" value="{{ request('search') }}">
                    @foreach((array)request('filter_kategori', []) as $kat)
                        <input type="hidden" name="filter_kategori[]" value="{{ $kat }}">
                    @endforeach
                    @foreach((array)request('filter_status', []) as $stat)
                        <input type="hidden" name="filter_status[]" value="{{ $stat }}">
                    @endforeach
                    @foreach((array)request('filter_lowongan', []) as $low)
                        <input type="hidden" name="filter_lowongan[]" value="{{ $low }}">
                    @endforeach
                </form>
            </div>

            <div class="table-container table-responsive">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Peserta</th>
                            <th>Kategori</th>
                            <th>Institusi & Jurusan</th>
                            <th>Periode Magang</th>
                            <th class="text-center">Status</th>
                            <th class="text-end">Detail</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($data as $index => $val)
                        @php
                            $words = explode(" ", $val->nama);
                            $initials = (count($words) > 1) ? $words[0][0].$words[1][0] : substr($val->nama, 0, 2);
                        @endphp
                        <tr>
                            <td>{{ ($data->currentPage() - 1) * $data->perPage() + $index + 1 }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="applicant-avatar js-preview-file"
                                         @if($val->pas_foto)
                                             data-file-url="{{ file_url($val->pas_foto) }}"
                                             data-file-name="Pas Foto - {{ $val->nama }}"
                                         @endif
                                    >
                                        <span class="avatar-initials">{{ strtoupper($initials) }}</span>
                                        @if($val->pas_foto)
                                            <img src="{{ file_url($val->pas_foto) }}"
                                                 alt="{{ $val->nama }}"
                                                 onload="this.parentElement.querySelector('.avatar-initials').style.display='none';"
                                                 onerror="this.style.display='none'; this.parentElement.querySelector('.avatar-initials').style.display='flex';">
                                        @endif
                                    </div>
                                    <div>
                                        <div class="applicant-name">{{ ucwords(strtolower($val->nama)) }}</div>
                                        <small class="fw-800 d-block d-md-none lamaran-lowongan-text {{ !$val->lowongan ? 'lamaran-lowongan-umum' : '' }}" style="{{ $val->lowongan ? 'color: var(--accent-gold)!important; font-weight: bolder!important; font-size: 0.85rem!important;' : 'color: var(--text-muted)!important;' }}">
                                            <i class="ri-briefcase-line"></i>
                                            {{ $val->lowongan ? $val->lowongan->title : 'Lowongan Umum' }}
                                        </small>
                                        <small class="fw-800 d-none d-md-block lamaran-lowongan-text {{ !$val->lowongan ? 'lamaran-lowongan-umum' : '' }}" style="{{ $val->lowongan ? 'color: var(--accent-gold)!important; font-weight: bolder!important; font-size: 0.85rem!important;' : 'color: var(--text-muted)!important;' }}">
                                            <i class="ri-briefcase-line"></i>
                                            {{ $val->lowongan ? Str::limit($val->lowongan->title, 35) : 'Lowongan Umum' }}
                                        </small>
                                        <div class="d-md-none mt-1">
                                            <span class="category-badge">{{ ucwords($val->kategori) }}</span>
                                        </div>
                                        <div class="d-lg-none mt-1">
                                            <div class="fw-800 text-dark" style="font-size: 0.85rem;">{{ ucwords(strtolower(Str::limit($val->instansi, 25))) }}</div>
                                            <div class="text-muted fw-600" style="font-size: 0.75rem;"><i class="ri-git-branch-line"></i> {{ ucwords(strtolower(Str::limit($val->jurusan, 20))) }}</div>
                                        </div>
                                        <div class="d-xl-none mt-1">
                                            <div class="date-badge" style="font-size: 0.75rem; padding: 4px 8px;">
                                                <span class="text-primary">{{ \Carbon\Carbon::parse($val->tanggal_mulai)->locale('id')->translatedFormat('d M Y') }}</span>
                                                <i class="ri-arrow-right-s-line text-muted"></i>
                                                <span class="text-danger">{{ \Carbon\Carbon::parse($val->tanggal_selesai)->locale('id')->translatedFormat('d M Y') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="d-none d-md-table-cell">
                                <span class="category-badge">{{ ucwords($val->kategori) }}</span>
                            </td>
                            <td class="d-none d-lg-table-cell">
                                <div class="fw-800 text-dark" style="font-size: 0.9rem;">{{ ucwords(strtolower($val->instansi)) }}</div>
                                <div class="text-muted fw-600" style="font-size: 0.8rem;"><i class="ri-git-branch-line"></i> {{ ucwords(strtolower($val->jurusan)) }}</div>
                            </td>
                            <td>
                                <div class="date-badge">
                                    <span class="text-primary">{{ \Carbon\Carbon::parse($val->tanggal_mulai)->locale('id')->translatedFormat('d M Y') }}</span>
                                    <i class="ri-arrow-right-s-line text-muted"></i>
                                    <span class="text-danger">{{ \Carbon\Carbon::parse($val->tanggal_selesai)->locale('id')->translatedFormat('d M Y') }}</span>
                                </div>
                            </td>
                            <td class="text-center">
                                @if($val->status == 1)
                                    <span class="status-pill pill-process"><i class="ri-loader-4-line ri-spin"></i> Lamaran Diproses</span>
                                @elseif($val->status == 2)
                                    <span class="status-pill pill-process"><i class="ri-loader-4-line ri-spin"></i> Menunggu Persetujuan</span>
                                @elseif($val->status == 3)
                                    <span class="status-pill pill-success"><i class="ri-checkbox-circle-line"></i> LOLOS</span>
                                @elseif($val->status == 9)
                                    <span class="status-pill pill-danger"><i class="ri-close-circle-line"></i> TOLAK</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <a href="{{ route($lamaranShowRoute ?? 'lamaran.show', $val->id) }}" class="btn-action" title="Detail Lamaran">
                                    <i class="ri-eye-line" style="font-size: 1.2rem;"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <img src="https://illustrations.popsy.co/white/abstract-art-4.svg" style="height: 150px; opacity: 0.5;">
                                <p class="text-muted mt-3 fw-700">Tidak ada data pendaftar yang ditemukan.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="footer-container">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-4">
                    <div class="text-muted fw-700" style="font-size: 0.85rem;">
                        Menampilkan <span class="text-dark">{{ $data->firstItem() ?? 0 }}</span> - <span class="text-dark">{{ $data->lastItem() ?? 0 }}</span> dari <span class="text-dark">{{ $data->total() }}</span> Peserta
                    </div>

                    <div class="pagination-wrapper">
                        {{ $data->appends(request()->query())->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Preview File (Global) -->
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

{{-- Bulk Approve Modal --}}
@if(($isAtasanView ?? false) && ($pendingCount ?? 0) > 0)
<div class="modal fade bulk-modal" id="bulkApproveModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg">
            <div class="modal-header bg-white">
                <h5 class="modal-title">
                    <i class="ri-checkbox-circle-line me-2" style="color: #10b981;"></i>Setujui Semua Lamaran
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="bulk-warning-box mb-3">
                    <i class="ri-alarm-warning-line"></i>
                    <p>Anda akan <strong>menyetujui {{ $pendingCount }} lamaran</strong> sekaligus. Setiap pelamar akan dibuatkan akun peserta dan menerima email penerimaan. Aksi ini tidak dapat dibatalkan.</p>
                </div>
                <div class="text-center mt-3">
                    <div class="d-inline-flex align-items-center gap-2 px-4 py-2" style="background: #ecfdf5; border-radius: 12px;">
                        <i class="ri-mail-send-line" style="color: #059669; font-size: 1.1rem;"></i>
                        <span class="fw-700" style="color: #059669; font-size: 0.9rem;">Email penerimaan akan dikirim ke {{ $pendingCount }} pelamar</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm" data-bs-dismiss="modal" style="border-radius: 10px; font-weight: 700; padding: 0.5rem 1.2rem;">Batal</button>
                <button type="button" class="btn-bulk-approve" id="btnConfirmBulkApprove" onclick="executeBulkAction('stage2_approve')">
                    <i class="ri-checkbox-circle-line"></i> Ya, Setujui Semua
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Bulk Reject Modal --}}
<div class="modal fade bulk-modal" id="bulkRejectModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg">
            <div class="modal-header bg-white">
                <h5 class="modal-title">
                    <i class="ri-close-circle-line me-2" style="color: #ef4444;"></i>Tolak Semua Lamaran
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="bulk-warning-box mb-3" style="background: #fef2f2; border-color: #fca5a5;">
                    <i class="ri-alarm-warning-line" style="color: #ef4444;"></i>
                    <p style="color: #991b1b;">Anda akan <strong>menolak {{ $pendingCount }} lamaran</strong> sekaligus. Setiap pelamar akan menerima email penolakan beserta catatan di bawah. Aksi ini tidak dapat dibatalkan.</p>
                </div>
                <div class="mb-3 mt-3">
                    <label for="bulkCatatanPenolakan" class="form-label fw-700" style="font-size: 0.9rem;">
                        <i class="ri-edit-2-line me-1"></i>Catatan Penolakan <span class="text-muted fw-600">(akan dikirim ke semua pelamar)</span>
                    </label>
                    <textarea class="form-control bulk-textarea" id="bulkCatatanPenolakan" rows="4"
                              placeholder="Tuliskan alasan penolakan yang akan disampaikan ke seluruh pelamar..."
                              maxlength="1000"></textarea>
                    <div class="d-flex justify-content-between mt-1">
                        <small class="text-muted fw-600">Catatan bersifat opsional</small>
                        <small class="text-muted fw-600"><span id="catatanCharCount">0</span>/1000</small>
                    </div>
                </div>
                <div class="text-center">
                    <div class="d-inline-flex align-items-center gap-2 px-4 py-2" style="background: #fef2f2; border-radius: 12px;">
                        <i class="ri-mail-send-line" style="color: #dc2626; font-size: 1.1rem;"></i>
                        <span class="fw-700" style="color: #dc2626; font-size: 0.9rem;">Email penolakan akan dikirim ke {{ $pendingCount }} pelamar</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm" data-bs-dismiss="modal" style="border-radius: 10px; font-weight: 700; padding: 0.5rem 1.2rem;">Batal</button>
                <button type="button" class="btn-bulk-reject" id="btnConfirmBulkReject" onclick="executeBulkAction('stage2_reject')">
                    <i class="ri-close-circle-line"></i> Ya, Tolak Semua
                </button>
            </div>
        </div>
    </div>
</div>
@endif

@if(($isAtasanView ?? false) && ($pendingCount ?? 0) > 0)
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endif

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Debounce Search
        const searchInput = document.getElementById('search');
        let timeout = null;

        searchInput.addEventListener('keyup', function() {
            clearTimeout(timeout);
            timeout = setTimeout(() => {
                if(this.value.length > 2 || this.value.length == 0) {
                    document.getElementById('searchForm').submit();
                }
            }, 800);
        });

        // Hover Effect on Pagination
        const pageLinks = document.querySelectorAll('.page-link');
        pageLinks.forEach(link => {
            link.addEventListener('click', () => {
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });
        });

        // Prevent dropdown from closing when clicking inside
        document.querySelectorAll('.filter-dropdown .dropdown-menu').forEach(menu => {
            menu.addEventListener('click', function(e) {
                e.stopPropagation();
            });
        });

        // Preview Script (gunakan modal Bootstrap 5, support gambar & PDF)
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
                    labelEl.innerHTML = '<i class="ri-file-search-line me-2"></i> ' + fileName;
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

    // ========== BULK ACTION FUNCTIONS ==========
    @if(($isAtasanView ?? false) && ($pendingCount ?? 0) > 0)
    function showBulkModal(type) {
        if (type === 'approve') {
            const modal = new bootstrap.Modal(document.getElementById('bulkApproveModal'));
            modal.show();
        } else {
            const modal = new bootstrap.Modal(document.getElementById('bulkRejectModal'));
            modal.show();
        }
    }

    // Character counter for catatan penolakan
    const catatanTextarea = document.getElementById('bulkCatatanPenolakan');
    const catatanCounter = document.getElementById('catatanCharCount');
    if (catatanTextarea && catatanCounter) {
        catatanTextarea.addEventListener('input', function() {
            catatanCounter.textContent = this.value.length;
        });
    }

    function executeBulkAction(action) {
        // Disable buttons to prevent double-click
        const btnApprove = document.getElementById('btnConfirmBulkApprove');
        const btnReject = document.getElementById('btnConfirmBulkReject');
        const activeBtn = action === 'stage2_approve' ? btnApprove : btnReject;

        if (activeBtn) {
            activeBtn.disabled = true;
            activeBtn.innerHTML = '<i class="ri-loader-4-line ri-spin"></i> Memproses...';
        }

        // Close modal
        const modalId = action === 'stage2_approve' ? 'bulkApproveModal' : 'bulkRejectModal';
        const modalEl = document.getElementById(modalId);
        if (modalEl) {
            const modal = bootstrap.Modal.getInstance(modalEl);
            if (modal) modal.hide();
        }

        // Build request body
        const body = { action: action };
        if (action === 'stage2_reject') {
            const catatan = document.getElementById('bulkCatatanPenolakan');
            if (catatan && catatan.value.trim()) {
                body.catatan_penolakan = catatan.value.trim();
            }
        }

        // Show loading indicator
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Memproses...',
                html: action === 'stage2_approve'
                    ? 'Sedang menyetujui semua lamaran dan mengirim email penerimaan...'
                    : 'Sedang menolak semua lamaran dan mengirim email penolakan...',
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => { Swal.showLoading(); }
            });
        }

        fetch('{{ route("lamaran.bulk-update") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}',
                'Accept': 'application/json',
            },
            body: JSON.stringify(body),
        })
        .then(response => response.json())
        .then(data => {
            if (typeof Swal !== 'undefined') {
                let html = `<p class="mb-2">${data.message}</p>`;

                if (data.data && data.data.success_count > 0) {
                    html += `<div class="d-flex justify-content-center gap-3 mb-2">`;
                    html += `<span class="badge" style="background: #ecfdf5; color: #059669; padding: 8px 16px; border-radius: 10px; font-size: 0.85rem; font-weight: 700;"><i class="ri-checkbox-circle-line me-1"></i>Berhasil: ${data.data.success_count}</span>`;
                    if (data.data.fail_count > 0) {
                        html += `<span class="badge" style="background: #fef2f2; color: #dc2626; padding: 8px 16px; border-radius: 10px; font-size: 0.85rem; font-weight: 700;"><i class="ri-close-circle-line me-1"></i>Gagal: ${data.data.fail_count}</span>`;
                    }
                    html += `</div>`;
                }

                if (data.data && data.data.failed_names && data.data.failed_names.length > 0) {
                    html += `<div class="text-start mt-3" style="max-height: 200px; overflow-y: auto; background: #fef2f2; border-radius: 12px; padding: 1rem;">`;
                    html += `<p class="fw-700 mb-2" style="font-size: 0.85rem; color: #dc2626;"><i class="ri-error-warning-line me-1"></i>Detail kegagalan:</p>`;
                    html += `<ul style="margin: 0; padding-left: 1.2rem; font-size: 0.83rem; color: #991b1b;">`;
                    data.data.failed_names.forEach(name => {
                        html += `<li class="mb-1">${name}</li>`;
                    });
                    html += `</ul></div>`;
                }

                Swal.fire({
                    icon: data.success ? 'success' : 'warning',
                    title: data.success ? 'Berhasil!' : 'Perhatian',
                    html: html,
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#b08d48',
                }).then(() => {
                    window.location.reload();
                });
            } else {
                alert(data.message);
                window.location.reload();
            }
        })
        .catch(error => {
            console.error('Bulk action error:', error);
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'error',
                    title: 'Terjadi Kesalahan',
                    text: 'Gagal memproses permintaan. Silakan coba lagi.',
                    confirmButtonColor: '#b08d48',
                });
            } else {
                alert('Terjadi kesalahan. Silakan coba lagi.');
            }

            // Re-enable buttons
            if (activeBtn) {
                activeBtn.disabled = false;
                if (action === 'stage2_approve') {
                    activeBtn.innerHTML = '<i class="ri-checkbox-circle-line"></i> Ya, Setujui Semua';
                } else {
                    activeBtn.innerHTML = '<i class="ri-close-circle-line"></i> Ya, Tolak Semua';
                }
            }
        });
    }
    @endif
</script>

@if(($canBulkStage1 ?? false) || ($canBulkStage2 ?? false))
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
(function () {
    const CAN_STAGE1 = @json($canBulkStage1 ?? false);
    const CAN_STAGE2 = @json($canBulkStage2 ?? false);
    const BULK_URL = "{{ route('lamaran.bulk-update') }}";
    const CSRF = "{{ csrf_token() }}";

    // Ambil filter yang SEDANG diterapkan (dari query string URL), bukan checkbox yang belum disubmit.
    function appliedFilters() {
        const p = new URLSearchParams(window.location.search);
        const body = new URLSearchParams();
        p.getAll('filter_kategori[]').forEach(v => body.append('filter_kategori[]', v));
        p.getAll('filter_lowongan[]').forEach(v => body.append('filter_lowongan[]', v));
        const s = p.get('search');
        if (s) body.append('search', s);
        return body;
    }

    // Simpulkan tahap dari filter Status yang SEDANG diterapkan (agar mengikuti filter).
    // Mengembalikan 'stage1' / 'stage2' bila filter menunjuk satu status jelas, atau null bila ambigu.
    function stageFromStatusFilter() {
        const statuses = new URLSearchParams(window.location.search).getAll('filter_status[]');
        const hasS1 = statuses.includes('1'); // Lamaran Diproses
        const hasS2 = statuses.includes('2'); // Menunggu Persetujuan
        if (hasS1 && !hasS2) return 'stage1';
        if (hasS2 && !hasS1) return 'stage2';
        return null;
    }

    async function chooseStage(kind) {
        // Hanya satu kapabilitas -> pakai itu langsung.
        if (CAN_STAGE1 && !CAN_STAGE2) return 'stage1';
        if (CAN_STAGE2 && !CAN_STAGE1) return 'stage2';

        // Punya dua kapabilitas: ikuti filter Status bila jelas menunjuk satu tahap.
        const inferred = stageFromStatusFilter();
        if (inferred) return inferred;

        // Ambigu (tidak memfilter status, atau memilih keduanya) -> minta pilih.
        const arrow1 = kind === 'approve' ? '&rarr; Menunggu Persetujuan' : '&rarr; Tidak Lolos';
        const arrow2 = kind === 'approve' ? '&rarr; Lolos' : '&rarr; Tidak Lolos';
        const res = await Swal.fire({
            title: kind === 'approve' ? 'Setujui lamaran tahap mana?' : 'Tolak lamaran tahap mana?',
            input: 'radio',
            inputOptions: {
                stage1: 'Tahap 1 — Lamaran Diproses (' + arrow1 + ')',
                stage2: 'Tahap 2 — Menunggu Persetujuan (' + arrow2 + ')',
            },
            inputValue: 'stage1',
            inputValidator: (v) => (!v ? 'Pilih tahap terlebih dahulu' : undefined),
            showCancelButton: true,
            confirmButtonText: 'Lanjut',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#b08d48',
        });
        return res.isConfirmed ? res.value : null;
    }

    function stageLabel(action) {
        return action.startsWith('stage1')
            ? 'Tahap 1 (Lamaran Diproses &rarr; Menunggu Persetujuan)'
            : 'Tahap 2 (Menunggu Persetujuan &rarr; Lolos)';
    }

    window.adminBulkAction = async function (kind) {
        const stage = await chooseStage(kind);
        if (!stage) return;
        const action = stage + '_' + kind;

        if (kind === 'reject') {
            const res = await Swal.fire({
                title: 'Tolak Semua',
                html: 'Menolak semua lamaran <strong>' + (stage === 'stage1' ? 'Lamaran Diproses' : 'Menunggu Persetujuan') + '</strong> yang sesuai filter. Email penolakan akan dikirim ke pelamar.',
                input: 'textarea',
                inputLabel: 'Pesan penolakan (opsional, dikirim ke pelamar)',
                inputPlaceholder: 'Tuliskan alasan penolakan...',
                inputAttributes: { maxlength: 1000 },
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                confirmButtonText: 'Ya, Tolak Semua',
                cancelButtonText: 'Batal',
            });
            if (!res.isConfirmed) return;
            submitBulk(action, res.value || '');
        } else {
            const note = action === 'stage1_approve'
                ? '<br><small style="color:#64748b;">Pendaftar Umum (tanpa lowongan) akan dilewati karena belum ada satuan kerja.</small>'
                : '';
            const res = await Swal.fire({
                title: 'Setuju Semua',
                html: 'Menyetujui semua lamaran <strong>' + stageLabel(action) + '</strong> yang sesuai filter.' + note,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#10b981',
                confirmButtonText: 'Ya, Setujui Semua',
                cancelButtonText: 'Batal',
            });
            if (!res.isConfirmed) return;
            submitBulk(action, null);
        }
    };

    function submitBulk(action, catatan) {
        const body = appliedFilters();
        body.append('action', action);
        if (catatan !== null && catatan !== undefined) body.append('catatan_penolakan', catatan);

        Swal.fire({
            title: 'Memproses...',
            html: 'Sedang memproses lamaran dan mengirim email bila perlu...',
            allowOutsideClick: false,
            allowEscapeKey: false,
            didOpen: () => Swal.showLoading(),
        });

        fetch(BULK_URL, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-CSRF-TOKEN': CSRF,
                'Accept': 'application/json',
            },
            body: body.toString(),
        })
        .then(res => res.json().then(d => ({ ok: res.ok, d })))
        .then(({ ok, d }) => {
            const sc = d.data ? (d.data.success_count || 0) : 0;
            let html = '<p class="mb-2">' + (d.message || '') + '</p>';
            if (d.data && d.data.failed_names && d.data.failed_names.length) {
                html += '<div class="text-start mt-2" style="max-height:200px;overflow:auto;background:#fef2f2;border-radius:12px;padding:0.75rem;">';
                html += '<p class="fw-700 mb-1" style="font-size:0.82rem;color:#dc2626;">Detail kegagalan:</p>';
                html += '<ul style="margin:0;padding-left:1.2rem;font-size:0.82rem;color:#991b1b;">';
                d.data.failed_names.forEach(n => { html += '<li>' + n + '</li>'; });
                html += '</ul></div>';
            }
            Swal.fire({
                icon: sc > 0 ? 'success' : 'warning',
                title: sc > 0 ? 'Selesai' : 'Perhatian',
                html: html,
                confirmButtonColor: '#b08d48',
            }).then(() => { if (sc > 0) window.location.reload(); });
        })
        .catch(() => {
            Swal.fire({ icon: 'error', title: 'Gagal', text: 'Terjadi kesalahan jaringan. Silakan coba lagi.', confirmButtonColor: '#b08d48' });
        });
    }
})();
</script>
@endif
@endsection
