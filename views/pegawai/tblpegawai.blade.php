@extends('layouts.app')

@section('title', 'Pegawai External | Admin - SMART Setjen DPR RI')
@section('content')
@include('partials.select2_satker_assets')
<meta name="csrf-token" content="{{ csrf_token() }}">
@include('partials.table_skin_lamaran_css')
<style>
    .status-badge { padding: 0.25rem 0.75rem; border-radius: 8px; font-size: 0.75rem; font-weight: 700; }
    .btn-kembali-gold { background: #ffffff !important; border: 1.5px solid #b08d48 !important; color: #b08d48 !important; font-weight: 600; border-radius: 12px; padding: 0.5rem 1.25rem; display: inline-flex; align-items: center; gap: 0.5rem; transition: all 0.25s ease; box-shadow: 0 1px 3px rgba(0,0,0,0.04); }
    .btn-kembali-gold:hover { background: #fdf6e9 !important; color: #8e6d2f !important; border-color: #b08d48 !important; transform: translateX(-3px); }
    .btn-kembali-gold:focus,
    .btn-kembali-gold:active {
        outline: none !important;
        box-shadow: 0 0 0 2px rgba(176,141,72,0.35) !important;
        color: #8e6d2f !important;
        border-color: #b08d48 !important;
    }
    .status-active { background: #d1fae5; color: #065f46; }
    .status-inactive { background: #fee2e2; color: #991b1b; }
    .status-pending { background: #fef3c7; color: #b45309; }
    
    /* ========== Modal Umum (backdrop & dialog) ========== */
    .modal.fade .modal-dialog {
        transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
    }
    .modal.show .modal-dialog {
        transform: scale(1);
    }
    .modal.fade .modal-dialog {
        transform: scale(0.9);
    }
    .modal-backdrop.show {
        opacity: 0.5;
        backdrop-filter: blur(4px);
    }

    /* ========== Assign Role Modal ========== */
    #assignRoleModal .modal-dialog {
        max-width: 560px;
    }
    @media (min-width: 992px) {
        #assignRoleModal .modal-dialog { max-width: 640px; }
    }
    #assignRoleModal .modal-content {
        border-radius: 24px;
        overflow: hidden;
        box-shadow: 0 32px 64px rgba(15, 23, 42, 0.2), 0 0 0 1px rgba(0,0,0,0.04);
        border: none;
    }
    #assignRoleModal .modal-header {
        background: linear-gradient(135deg, #c5a059 0%, #8e6d2f 100%);
        color: white;
        border: none;
        padding: 1.5rem 2rem;
        position: relative;
    }
    #assignRoleModal .modal-header::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: rgba(255,255,255,0.25);
    }
    #assignRoleModal .modal-title {
        font-weight: 800;
        font-size: 1.25rem;
        letter-spacing: -0.02em;
    }
    #assignRoleModal .modal-body {
        padding: 2rem;
        background: #f8fafc;
    }
    #assignRoleModal .modal-footer {
        border-top: 1px solid #e2e8f0;
        padding: 1.25rem 2rem;
        background: #fff;
    }
    #assignRoleModal .form-label {
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 0.5rem;
        font-size: 0.9rem;
    }
    #assignRoleModal .form-select,
    #assignRoleModal .form-control {
        border-radius: 10px;
        border: 1px solid #e2e8f0;
        padding: 0.65rem 0.85rem;
        transition: all 0.2s;
    }
    #assignRoleModal .form-select:focus,
    #assignRoleModal .form-control:focus {
        border-color: #b08d48;
        box-shadow: 0 0 0 3px rgba(176, 141, 72, 0.12);
    }
    #assignRoleModal #modal-roles-container {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 1rem;
    }
    #assignRoleModal .form-check {
        padding: 0.5rem 0.75rem;
        border-radius: 8px;
        transition: background 0.2s;
    }
    #assignRoleModal .form-check:hover {
        background: rgba(176, 141, 72, 0.06);
    }
    #assignRoleModal .form-check-input:checked {
        background-color: #b08d48;
        border-color: #b08d48;
    }
    #assignRoleModal .btn-primary {
        background: linear-gradient(135deg, #c5a059 0%, #8e6d2f 100%);
        border: none;
        border-radius: 10px;
        padding: 0.65rem 1.5rem;
        font-weight: 700;
        transition: all 0.2s;
    }
    #assignRoleModal .btn-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(176, 141, 72, 0.35);
    }
    #assignRoleModal .btn-secondary {
        border-radius: 10px;
        padding: 0.65rem 1.5rem;
        font-weight: 700;
        border: 1px solid #e2e8f0;
        color: #64748b;
    }
    #assignRoleModal .btn-danger {
        border-radius: 10px;
        padding: 0.65rem 1.25rem;
        font-weight: 700;
    }

    /* ========== Modal Konfirmasi Hapus ========== */
    #confirmDeleteRoleModal .modal-dialog {
        max-width: 420px;
    }
    #confirmDeleteRoleModal .modal-content {
        border: none;
        border-radius: 24px;
        overflow: hidden;
        box-shadow: 0 32px 64px rgba(15, 23, 42, 0.2), 0 0 0 1px rgba(0,0,0,0.04);
    }
    #confirmDeleteRoleModal .modal-body {
        padding: 2rem 2rem 1.75rem;
        text-align: center;
    }
    #confirmDeleteRoleModal .modal-icon-wrap {
        width: 72px;
        height: 72px;
        margin: 0 auto 1.25rem;
        background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 3px solid rgba(220, 38, 38, 0.2);
    }
    #confirmDeleteRoleModal .modal-icon-wrap i {
        font-size: 2.25rem;
        color: #dc2626;
    }
    #confirmDeleteRoleModal .modal-title {
        font-size: 1.2rem;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 0.5rem;
    }
    #confirmDeleteRoleModal .modal-body p {
        color: #64748b;
        font-size: 0.95rem;
        line-height: 1.5;
    }
    #confirmDeleteRoleModal .btn-danger {
        background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
        border: none;
        border-radius: 10px;
        padding: 0.65rem 1.5rem;
        font-weight: 700;
        transition: all 0.2s;
    }
    #confirmDeleteRoleModal .btn-danger:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(220, 38, 38, 0.35);
    }
    #confirmDeleteRoleModal .btn-secondary {
        border-radius: 10px;
        padding: 0.65rem 1.5rem;
        font-weight: 700;
    }

    /* ========== Modal Validasi (Perhatian) ========== */
    #validationRoleModal .modal-dialog {
        max-width: 420px;
    }
    #validationRoleModal .modal-content {
        border: none;
        border-radius: 24px;
        overflow: hidden;
        box-shadow: 0 32px 64px rgba(15, 23, 42, 0.2), 0 0 0 1px rgba(0,0,0,0.04);
    }
    #validationRoleModal .modal-body {
        padding: 2rem 2rem 1.75rem;
        text-align: center;
    }
    #validationRoleModal .modal-icon-wrap {
        width: 72px;
        height: 72px;
        margin: 0 auto 1.25rem;
        background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 3px solid rgba(176, 141, 72, 0.25);
    }
    #validationRoleModal .modal-icon-wrap i {
        font-size: 2.25rem;
        color: #b45309;
    }
    #validationRoleModal .modal-title {
        font-size: 1.2rem;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 0.5rem;
    }
    #validationRoleModal .modal-body p {
        color: #64748b;
        font-size: 0.95rem;
        line-height: 1.5;
    }
    #validationRoleModal .btn-primary {
        background: linear-gradient(135deg, #c5a059 0%, #8e6d2f 100%);
        border: none;
        border-radius: 10px;
        padding: 0.65rem 1.5rem;
        font-weight: 700;
        transition: all 0.2s;
    }
    #validationRoleModal .btn-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(176, 141, 72, 0.35);
    }

    /* ========== Modal Sukses ========== */
    #successRoleModal .modal-dialog {
        max-width: 420px;
    }
    #successRoleModal .modal-content {
        border: none;
        border-radius: 24px;
        overflow: hidden;
        box-shadow: 0 32px 64px rgba(15, 23, 42, 0.2), 0 0 0 1px rgba(0,0,0,0.04);
    }
    #successRoleModal .modal-body {
        padding: 2rem 2rem 1.75rem;
        text-align: center;
    }
    #successRoleModal .modal-icon-wrap {
        width: 72px;
        height: 72px;
        margin: 0 auto 1.25rem;
        background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 3px solid rgba(34, 197, 94, 0.25);
    }
    #successRoleModal .modal-icon-wrap i {
        font-size: 2.25rem;
        color: #059669;
    }
    #successRoleModal .modal-title {
        font-size: 1.2rem;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 0.5rem;
    }
    #successRoleModal .modal-body p {
        color: #64748b;
        font-size: 0.95rem;
        line-height: 1.5;
    }
    #successRoleModal .btn-primary {
        background: linear-gradient(135deg, #059669 0%, #047857 100%);
        border: none;
        border-radius: 10px;
        padding: 0.65rem 1.5rem;
        font-weight: 700;
        transition: all 0.2s;
    }
    #successRoleModal .btn-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(5, 150, 105, 0.35);
    }

    /* Tombol Kembali di modal: teks putih */
    .btn-kembali-modal {
        background: #0f172a !important;
        border-color: #0f172a !important;
        color: #fff !important;
    }
    .btn-kembali-modal:hover {
        background: #1e293b !important;
        border-color: #1e293b !important;
        color: #fff !important;
    }
    /* SweetAlert tombol Kembali: teks putih */
    .swal2-cancel,
    .swal2-cancel-white {
        color: #fff !important;
    }

    /* === Dark mode: /pegawai-admin/external konsisten === */
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
    html[data-skin="dark"] .page-title { color: #ffffff !important; }
    html[data-skin="dark"] .page-title-area p.mb-0 { color: #ffffff !important; }
    html[data-skin="dark"] .card-toolbar h5.text-dark,
    html[data-skin="dark"] .card-toolbar .fw-800 { color: #ffffff !important; }
    html[data-skin="dark"] .card-toolbar .d-flex[style*="background"] {
        background: rgba(15, 23, 42, 0.5) !important;
    }
    html[data-skin="dark"] .card-toolbar .text-muted,
    html[data-skin="dark"] .card-toolbar .text-dark { color: #ffffff !important; }
    html[data-skin="dark"] .search-container .input-group {
        background: #1e293b !important;
        border-color: #374151 !important;
    }
    html[data-skin="dark"] .search-container input {
        color: #ffffff !important;
    }
    html[data-skin="dark"] .search-container .ri-search-2-line { color: #fbbf24 !important; }
    html[data-skin="dark"] .form-select.form-select-sm {
        background: #1e293b !important;
        border-color: #374151 !important;
        color: #ffffff !important;
    }
    html[data-skin="dark"] .status-active {
        background: #064e3b !important;
        color: #a7f3d0 !important;
    }
    html[data-skin="dark"] .status-inactive {
        background: #7f1d1d !important;
        color: #fecaca !important;
    }
    html[data-skin="dark"] .status-pending {
        background: #78350f !important;
        color: #fef3c7 !important;
    }
    html[data-skin="dark"] .badge.bg-warning.text-dark { background: #78350f !important; color: #fef3c7 !important; }
    html[data-skin="dark"] .badge.bg-info.text-dark { background: #1e3a5f !important; color: #bfdbfe !important; }
    html[data-skin="dark"] .custom-table tbody td { color: #ffffff !important; }
    html[data-skin="dark"] .custom-table thead th { color: #ffffff !important; }
    html[data-skin="dark"] #assignRoleModal .modal-header {
        background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%) !important;
        border-color: #1f2937 !important;
        color: #ffffff !important;
    }
    html[data-skin="dark"] #assignRoleModal .modal-body {
        background: #0f172a !important;
        color: #ffffff !important;
    }
    html[data-skin="dark"] #assignRoleModal .modal-footer {
        background: #0f172a !important;
        border-top-color: #1f2937 !important;
        color: #ffffff !important;
    }
    html[data-skin="dark"] #assignRoleModal .form-label { color: #ffffff !important; }
    html[data-skin="dark"] #assignRoleModal .form-select,
    html[data-skin="dark"] #assignRoleModal .form-control,
    html[data-skin="dark"] #assignRoleModal #modal-roles-container {
        background: #0f172a !important;
        border-color: #374151 !important;
        color: #ffffff !important;
    }
    html[data-skin="dark"] #assignRoleModal .form-check-label { color: #ffffff !important; }
    html[data-skin="dark"] #assignRoleModal .btn-secondary {
        background: #1e293b !important;
        border-color: #475569 !important;
        color: #ffffff !important;
    }
    html[data-skin="dark"] #confirmDeleteRoleModal .modal-content,
    html[data-skin="dark"] #confirmDeleteRoleModal .modal-body {
        background: #0f172a !important;
        color: #ffffff !important;
    }
    html[data-skin="dark"] #confirmDeleteRoleModal .modal-title { color: #ffffff !important; }
    html[data-skin="dark"] #confirmDeleteRoleModal .modal-body p { color: #ffffff !important; }
    html[data-skin="dark"] #confirmDeleteRoleModal .modal-icon-wrap {
        background: #450a0a !important;
        border-color: #7f1d1d !important;
    }
    html[data-skin="dark"] #confirmDeleteRoleModal .modal-icon-wrap i { color: #fca5a5 !important; }
    html[data-skin="dark"] #validationRoleModal .modal-content,
    html[data-skin="dark"] #validationRoleModal .modal-body {
        background: #0f172a !important;
        color: #ffffff !important;
    }
    html[data-skin="dark"] #validationRoleModal .modal-title { color: #ffffff !important; }
    html[data-skin="dark"] #validationRoleModal .modal-body p { color: #ffffff !important; }
    html[data-skin="dark"] #validationRoleModal .modal-icon-wrap {
        background: #78350f !important;
        border-color: #92400e !important;
    }
    html[data-skin="dark"] #validationRoleModal .modal-icon-wrap i { color: #fef3c7 !important; }
    html[data-skin="dark"] #successRoleModal .modal-content,
    html[data-skin="dark"] #successRoleModal .modal-body {
        background: #0f172a !important;
        color: #ffffff !important;
    }
    html[data-skin="dark"] #successRoleModal .modal-title { color: #ffffff !important; }
    html[data-skin="dark"] #successRoleModal .modal-body p { color: #ffffff !important; }
    html[data-skin="dark"] #successRoleModal .modal-icon-wrap {
        background: #064e3b !important;
        border-color: #065f46 !important;
    }
    html[data-skin="dark"] #successRoleModal .modal-icon-wrap i { color: #6ee7b7 !important; }
    html[data-skin="dark"] .breadcrumb-item,
    html[data-skin="dark"] .breadcrumb-item a { color: #ffffff !important; }
    html[data-skin="dark"] .breadcrumb-item.active { color: #fbbf24 !important; }
    html[data-skin="dark"] .fw-700.text-muted { color: #ffffff !important; }
</style>

<!-- Loading Overlay -->
<div class="loading-overlay" id="loadingOverlay">
    <div class="loading-spinner"></div>
    <div class="loading-text">Memuat data pegawai...</div>
</div>

<div class="content-wrapper">
    <div class="container-fluid px-md-5">
            <div class="page-title-area d-flex flex-wrap align-items-end justify-content-between gap-3">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-1">
                        <li class="breadcrumb-item"><a href="{{ route('pegawai.internal.index') }}">Pegawai</a></li>
                        <li class="breadcrumb-item active">Tambah Pegawai</li>
                    </ol>
                </nav>
                <h1 class="page-title">Tambah Pegawai</h1>
                <p class="mb-0" style="font-size: 0.95rem; font-weight: 600; color: #1e293b;">Pilih pegawai dari data eksternal untuk ditambahkan ke daftar Pegawai Internal.</p>
            </div>
            <a href="{{ route('pegawai.internal.index') }}" 
               onclick="sessionStorage.removeItem('accessingPegawaiExternal');"
               class="btn btn-sm flex-shrink-0 btn-kembali-gold"
               style="font-weight: 700; padding: 0.6rem 1.25rem; border-radius: 12px; display: inline-flex; align-items: center; gap: 0.5rem;">
                <i class="ri-arrow-left-line"></i> Kembali ke Pegawai Internal
            </a>
        </div>

        @if(isset($error))
        <div class="alert alert-danger alert-dismissible fade show" role="alert" style="margin-bottom: 1.5rem; border-radius: 12px;">
            <i class="ri-error-warning-line me-2"></i>{{ $error }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        <div class="modern-card">
            <div class="card-toolbar flex-wrap gap-4">
                <div class="d-flex align-items-center gap-2">
                    <div style="width: 5px; height: 25px; background: var(--gold-gradient); border-radius: 10px;"></div>
                    <h5 class="m-0 fw-800 text-dark">Daftar Pegawai Eksternal</h5>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <form method="GET" action="{{ request()->url() }}" class="search-container" id="searchForm">
                        <div class="input-group">
                            <i class="ri-search-2-line" style="color: var(--accent-gold); font-size: 1.2rem;"></i>
                            <input type="text" name="search" id="search" class="form-control shadow-none"
                                   placeholder="Cari nama, NIP, atau username..."
                                   value="{{ request('search', '') }}">
                            @if(request('search'))
                                <a href="{{ request()->url() }}?{{ http_build_query(request()->except(['search','page'])) }}" class="text-muted ms-2"><i class="ri-close-circle-fill"></i></a>
                            @endif
                        </div>
                        <input type="hidden" name="per_page" value="{{ request('per_page', 5) }}">
                    </form>
                </div>
            </div>

            <div class="px-4 py-3 d-flex align-items-center justify-content-between" style="background: rgba(248, 250, 252, 0.5);">
                <div class="text-muted fw-700" style="font-size: 0.85rem;">
                    Total Entri: <span class="text-dark">{{ $pagination['total'] ?? count($data) }}</span>
                </div>
                <form method="GET" action="{{ request()->url() }}" class="d-flex align-items-center gap-2">
                    <span class="fw-700 text-muted" style="font-size: 0.8rem;">Baris:</span>
                    <select name="per_page" class="form-select form-select-sm border-0 fw-800 shadow-sm"
                            style="border-radius: 8px; width: 80px; cursor: pointer;"
                            id="perPageSelect">
                        @foreach([5, 10, 15, 25, 50, 100] as $size)
                            <option value="{{ $size }}" {{ request('per_page', 5) == $size ? 'selected' : '' }}>{{ $size }}</option>
                        @endforeach
                    </select>
                    <input type="hidden" name="search" value="{{ request('search') }}">
                </form>
            </div>

            <div class="table-container table-responsive">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>NIP</th>
                            <th>Nama</th>
                            <th>Username</th>
                            <th class="text-center">Status Pegawai</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($data as $index => $item)
                        @php
                            $item = is_array($item) ? (object)$item : $item;
                            $currentPage = $pagination['current_page'] ?? 1;
                            $perPage = $pagination['per_page'] ?? 15;
                            $no = ($currentPage - 1) * $perPage + $index + 1;
                        @endphp
                        <tr>
                            <td>{{ $no }}</td>
                            <td class="fw-semibold">{{ $item->nip ?? '-' }}</td>
                            <td>{{ $item->nama ?? '-' }}</td>
                            <td>{{ $item->pengguna ?? '-' }}</td>
                            <td class="text-center">
                                @php
                                    $statusPegawai = $item->status_pegawai ?? null;
                                    $statusPegawaiText = $statusPegawai ?? '-';
                                    $statusPegawaiClass = 'badge bg-secondary';
                                    if ($statusPegawai === 'PNS') {
                                        $statusPegawaiClass = 'badge bg-primary';
                                    } elseif ($statusPegawai === 'PPPK') {
                                        $statusPegawaiClass = 'badge bg-info text-dark';
                                    } elseif ($statusPegawai === 'CPNS') {
                                        $statusPegawaiClass = 'badge bg-warning text-dark';
                                    }
                                @endphp
                                @if($statusPegawai)
                                    <span class="{{ $statusPegawaiClass }}" style="font-size: 0.75rem; padding: 0.25rem 0.5rem;">
                                        {{ $statusPegawaiText }}
                                    </span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <button type="button" class="btn-action" 
                                        data-pegawai='@json($item)' 
                                        onclick="showAssignRoleModalFromButton(this)" 
                                        title="Assign Role">
                                    <i class="ri-user-settings-line"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <img src="https://illustrations.popsy.co/white/abstract-art-4.svg" style="height: 150px; opacity: 0.5;">
                                <p class="text-muted mt-3 fw-700">Tidak ada data yang ditemukan.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($pagination && ($pagination['total'] ?? 0) > 0)
            @php
                $currentPage = $pagination['current_page'] ?? 1;
                $lastPage = $pagination['last_page'] ?? 1;
                $perPage = $pagination['per_page'] ?? 5;
                $total = $pagination['total'] ?? 0;
                $from = $pagination['from'] ?? 0;
                $to = $pagination['to'] ?? 0;

                // Build base URL for pagination links
                $baseParams = request()->except(['page']);
                $buildUrl = function($p) use ($baseParams) {
                    $params = array_merge($baseParams, ['page' => $p]);
                    return request()->url() . '?' . http_build_query($params);
                };

                // Calculate page range (show 2 on each side of current page)
                $startPage = max(1, $currentPage - 2);
                $endPage = min($lastPage, $currentPage + 2);
            @endphp
            <div class="footer-container">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-4">
                    <div class="text-muted fw-700" style="font-size: 0.85rem;">
                        Menampilkan <span class="text-dark">{{ $from }}</span> - <span class="text-dark">{{ $to }}</span> dari <span class="text-dark">{{ $total }}</span> Data
                    </div>
                    @if($lastPage > 1)
                    <div class="pagination-wrapper">
                        <nav aria-label="Pagination">
                            <ul class="pagination pagination-sm mb-0">
                                {{-- Previous --}}
                                @if($currentPage > 1)
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $buildUrl($currentPage - 1) }}" rel="prev" aria-label="Previous">&lsaquo;</a>
                                    </li>
                                @else
                                    <li class="page-item disabled"><span class="page-link">&lsaquo;</span></li>
                                @endif

                                {{-- First page + dots --}}
                                @if($startPage > 1)
                                    <li class="page-item"><a class="page-link" href="{{ $buildUrl(1) }}">1</a></li>
                                    @if($startPage > 2)
                                        <li class="page-item disabled"><span class="page-link">...</span></li>
                                    @endif
                                @endif

                                {{-- Page numbers --}}
                                @for($p = $startPage; $p <= $endPage; $p++)
                                    @if($p == $currentPage)
                                        <li class="page-item active"><span class="page-link">{{ $p }}</span></li>
                                    @else
                                        <li class="page-item"><a class="page-link" href="{{ $buildUrl($p) }}">{{ $p }}</a></li>
                                    @endif
                                @endfor

                                {{-- Last page + dots --}}
                                @if($endPage < $lastPage)
                                    @if($endPage < $lastPage - 1)
                                        <li class="page-item disabled"><span class="page-link">...</span></li>
                                    @endif
                                    <li class="page-item"><a class="page-link" href="{{ $buildUrl($lastPage) }}">{{ $lastPage }}</a></li>
                                @endif

                                {{-- Next --}}
                                @if($currentPage < $lastPage)
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $buildUrl($currentPage + 1) }}" rel="next" aria-label="Next">&rsaquo;</a>
                                    </li>
                                @else
                                    <li class="page-item disabled"><span class="page-link">&rsaquo;</span></li>
                                @endif
                            </ul>
                        </nav>
                    </div>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal Assign Role -->
<div class="modal fade" id="assignRoleModal" tabindex="-1" aria-labelledby="assignRoleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="assignRoleModalLabel">
                    <i class="ri-user-settings-line me-2"></i>Assign Role ke Pegawai
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close" style="opacity: 1;"></button>
            </div>
            <div class="modal-body">
                <form id="assignRoleForm">
                    <input type="hidden" id="modal-role-as-id" name="role_as_id">
                    <input type="hidden" id="modal-nip" name="nip">
                    <input type="hidden" id="modal-id-pegawai" name="id_pegawai">
                    <input type="hidden" id="modal-username" name="username">
                    <input type="hidden" id="modal-nama" name="nama">
                    
                    <div class="mb-4">
                        <label class="form-label">Pegawai:</label>
                        <div class="p-3 bg-light rounded" style="background: #f8fafc !important; border: 1px solid #e2e8f0;">
                            <p class="mb-0 fw-semibold text-dark" id="modal-pegawai-info">-</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Username:</label>
                            <div class="p-3 bg-light rounded" style="background: #f8fafc !important; border: 1px solid #e2e8f0;">
                                <p class="mb-0 text-dark" id="modal-username-display">-</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Nama:</label>
                            <div class="p-3 bg-light rounded" style="background: #f8fafc !important; border: 1px solid #e2e8f0;">
                                <p class="mb-0 text-dark" id="modal-nama-display">-</p>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="modal-satker" class="form-label">Satuan Kerja: <span class="text-danger">*</span></label>
                        <select id="modal-satker" name="id_satker" class="form-select" required>
                            <option value="">Pilih Satuan Kerja</option>
                        </select>
                        <div class="invalid-feedback">Satuan Kerja wajib dipilih</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Roles Internal: <span class="text-danger">*</span></label>
                        <div id="modal-roles-container">
                            <p class="text-muted mb-0">Memuat data...</p>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-kembali-modal" data-bs-dismiss="modal">
                    <i class="ri-close-line me-1"></i>Kembali
                </button>
                <button type="button" class="btn btn-danger" id="btn-delete-role" style="display: none;">
                    <i class="ri-delete-bin-line me-1"></i>Hapus
                </button>
                <button type="button" class="btn btn-primary" id="btn-save-role">
                    <i class="ri-save-line me-1"></i>Simpan
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Hapus Role -->
<div class="modal fade" id="confirmDeleteRoleModal" tabindex="-1" aria-labelledby="confirmDeleteRoleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div class="modal-icon-wrap">
                    <i class="ri-delete-bin-line"></i>
                </div>
                <h5 class="modal-title" id="confirmDeleteRoleModalLabel">Hapus Role Pegawai?</h5>
                <p class="mb-0">Apakah Anda yakin ingin menghapus role pegawai ini? Semua role internal akan dihapus.</p>
                <div class="mt-4 d-flex justify-content-center gap-3 flex-wrap">
                    <button type="button" class="btn btn-secondary btn-kembali-modal" data-bs-dismiss="modal">
                        <i class="ri-close-line me-1"></i> Kembali
                    </button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteRoleModalBtn">
                        <i class="ri-delete-bin-line me-1"></i> Ya, Hapus
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Validasi (Satuan Kerja / Role belum dipilih) -->
<div class="modal fade" id="validationRoleModal" tabindex="-1" aria-labelledby="validationRoleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div class="modal-icon-wrap">
                    <i class="ri-error-warning-fill"></i>
                </div>
                <h5 class="modal-title" id="validationRoleModalLabel">Perhatian</h5>
                <p class="mb-0" id="validationRoleModalMessage">Satuan Kerja wajib dipilih.</p>
                <div class="mt-4">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">
                        <i class="ri-check-line me-1"></i> Mengerti
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Sukses Simpan Role -->
<div class="modal fade" id="successRoleModal" tabindex="-1" aria-labelledby="successRoleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div class="modal-icon-wrap">
                    <i class="ri-checkbox-circle-fill"></i>
                </div>
                <h5 class="modal-title" id="successRoleModalLabel">Berhasil</h5>
                <p class="mb-0" id="successRoleModalMessage">Role pegawai berhasil disimpan.</p>
                <div class="mt-4">
                    <button type="button" class="btn btn-primary" id="successRoleModalBtn" data-bs-dismiss="modal">
                        <i class="ri-check-line me-1"></i> OK
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const PAGE_CAN_ASSIGN_SUPERADMIN = @json((bool)($canAssignSuperadmin ?? false));
    const SUPERADMIN_ALLOWED_SATKER_IDS = @json(\Modules\Magang\App\Models\Smart\Satker::SUPERADMIN_ALLOWED_SATKER_IDS);
    const SUPERADMIN_MAX_LIMIT = @json(\Modules\Magang\App\Models\Smart\Satker::SUPERADMIN_MAX_ASSIGNMENTS);
    // Helper function untuk set access flag
    // Loading overlay helpers
    function showLoading() {
        document.getElementById('loadingOverlay').classList.add('active');
    }
    function hideLoading() {
        document.getElementById('loadingOverlay').classList.remove('active');
    }

    async function setAccessFlagBeforeNavigate(callback) {
        showLoading();
        try {
            await fetch('{{ route("pegawai.set-access-flag") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            callback();
        } catch (error) {
            console.error('Error setting access flag:', error);
            callback(); // Continue anyway
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Search form submit
        const searchForm = document.getElementById('searchForm');
        if (searchForm) {
            searchForm.addEventListener('submit', function(e) {
                e.preventDefault();
                setAccessFlagBeforeNavigate(() => {
                    searchForm.submit();
                });
            });
        }

        var si = document.getElementById('search');
        if (si) {
            var t = null;
            si.addEventListener('keyup', function() {
                clearTimeout(t);
                t = setTimeout(function() {
                    if (this.value.length > 2 || this.value.length === 0) {
                        setAccessFlagBeforeNavigate(() => {
                            document.getElementById('searchForm').submit();
                        });
                    }
                }.bind(this), 800);
            });
        }

        // Clear search button (X button)
        const clearSearchLink = document.querySelector('a[href*="search"] .ri-close-circle-fill, a[href*="search"]');
        if (clearSearchLink) {
            clearSearchLink.addEventListener('click', function(e) {
                e.preventDefault();
                const href = this.getAttribute('href');
                if (href) {
                    setAccessFlagBeforeNavigate(() => {
                        window.location.href = href;
                    });
                }
            });
        }

        // Pagination links
        document.querySelectorAll('.page-link').forEach(function(l) { 
            l.addEventListener('click', function(e) {
                e.preventDefault();
                const href = this.getAttribute('href');
                if (href && href !== '#') {
                    setAccessFlagBeforeNavigate(() => {
                        window.location.href = href;
                    });
                }
                window.scrollTo({ top: 0, behavior: 'smooth' }); 
            }); 
        });

        // Per page select change
        const perPageSelect = document.getElementById('perPageSelect') || document.querySelector('select[name="per_page"]');
        if (perPageSelect) {
            perPageSelect.addEventListener('change', function(e) {
                e.preventDefault();
                const form = this.closest('form');
                if (form) {
                    setAccessFlagBeforeNavigate(() => {
                        form.submit();
                    });
                }
            });
        }

        // Clear search button (X button) - attach event listener langsung
        function attachClearSearchListener() {
            const clearSearchLink = document.querySelector('a .ri-close-circle-fill')?.closest('a');
            if (clearSearchLink) {
                clearSearchLink.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    const href = this.getAttribute('href');
                    if (href) {
                        setAccessFlagBeforeNavigate(() => {
                            window.location.href = href;
                        });
                    }
                });
            }
        }
        
        // Attach saat DOM ready
        attachClearSearchListener();
        
        // Juga gunakan event delegation sebagai backup
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('ri-close-circle-fill')) {
                const link = e.target.closest('a');
                if (link && link.href && !link.hasAttribute('data-flag-set')) {
                    e.preventDefault();
                    e.stopPropagation();
                    link.setAttribute('data-flag-set', 'true');
                    const href = link.getAttribute('href');
                    setAccessFlagBeforeNavigate(() => {
                        window.location.href = href;
                    });
                }
            }
        });

        // Fix for Bootstrap Modals Z-Index (sama seperti admin-absensi)
        document.addEventListener('show.bs.modal', function(event) {
            const modalEl = event.target;
            if (modalEl && modalEl.parentElement !== document.body) {
                document.body.appendChild(modalEl);
            }
        });

        ['assignRoleModal', 'confirmDeleteRoleModal', 'validationRoleModal', 'successRoleModal'].forEach((id) => {
            const modalEl = document.getElementById(id);
            if (modalEl) {
                modalEl.addEventListener('hidden.bs.modal', cleanupModalArtifacts);
            }
        });

        const assignRoleModalEl = document.getElementById('assignRoleModal');
        if (assignRoleModalEl) {
            assignRoleModalEl.addEventListener('hidden.bs.modal', function () {
                if (window.destroySelect2SatkerIfAny) {
                    destroySelect2SatkerIfAny('#modal-satker');
                }
            });
        }
    });

    let rolesInternalData = [];
    let satkerData = [];
    let assignRoleMeta = {
        can_assign_superadmin: false,
        superadmin_assigned_count: 0,
        superadmin_limit: SUPERADMIN_MAX_LIMIT,
        superadmin_limit_reached: false
    };

    function cleanupModalArtifacts() {
        setTimeout(() => {
            const hasOpenModal = document.querySelectorAll('.modal.show').length > 0;
            if (!hasOpenModal) {
                document.querySelectorAll('.modal-backdrop').forEach((el) => el.remove());
                document.body.classList.remove('modal-open');
                document.body.style.removeProperty('padding-right');
                document.body.style.removeProperty('overflow');
            }
        }, 80);
    }

    function applyAdminSuperadminExclusiveRule(container) {
        if (!container) return;
        const normalizeRoleName = (name) => String(name || '')
            .toLowerCase()
            .replace(/\s+/g, '')
            .trim();
        const checkboxes = Array.from(container.querySelectorAll('input[name="roles_internal_ids[]"]'));
        const adminCb = checkboxes.find(cb => normalizeRoleName(cb.dataset.roleName) === 'admin');
        const superadminCb = checkboxes.find(cb => normalizeRoleName(cb.dataset.roleName) === 'superadmin');
        if (!adminCb || !superadminCb) return;

        const onChange = (changed) => {
            if (!changed.checked) return;
            if (changed === adminCb) {
                superadminCb.checked = false;
            } else if (changed === superadminCb) {
                adminCb.checked = false;
            }
        };

        adminCb.addEventListener('change', () => onChange(adminCb));
        superadminCb.addEventListener('change', () => onChange(superadminCb));

        // Normalisasi awal bila data lama sempat menyimpan keduanya terpilih.
        if (adminCb.checked && superadminCb.checked) {
            superadminCb.checked = false;
        }
    }

    function normalizeRoleName(name) {
        return String(name || '').toLowerCase().replace(/\s+/g, '').trim();
    }

    function applySuperadminUnitRule(form, options = {}) {
        if (!form) return;
        const unitSelect = form.querySelector('select[name="id_satker"]');
        const selectedUnitId = parseInt(unitSelect?.value || '0', 10);
        const allowByUnit = SUPERADMIN_ALLOWED_SATKER_IDS.includes(selectedUnitId);
        const keepExistingSuperadmin = !!options.keepExistingSuperadmin;
        const canAssignSuperadmin = !!assignRoleMeta.can_assign_superadmin;

        const roleCheckboxes = Array.from(form.querySelectorAll('input[name="roles_internal_ids[]"]'));
        const superadminCb = roleCheckboxes.find(cb => normalizeRoleName(cb.dataset.roleName) === 'superadmin');
        if (!superadminCb) return;

        const superadminWrap = superadminCb.closest('.form-check');
        const hintId = 'superadmin-role-hint';
        let hintEl = form.querySelector('#' + hintId);
        if (!hintEl) {
            hintEl = document.createElement('small');
            hintEl.id = hintId;
            hintEl.className = 'd-block mt-2 text-muted';
            const rolesContainer = form.querySelector('#modal-roles-container');
            if (rolesContainer) {
                rolesContainer.appendChild(hintEl);
            }
        }

        let shouldShow = canAssignSuperadmin && allowByUnit;
        if (assignRoleMeta.superadmin_limit_reached && !keepExistingSuperadmin && !superadminCb.checked) {
            shouldShow = false;
        }

        if (superadminWrap) {
            superadminWrap.style.display = shouldShow ? '' : 'none';
        }
        if (!shouldShow) {
            superadminCb.checked = false;
        }

        if (!allowByUnit) {
            hintEl.textContent = 'Role superadmin hanya tersedia untuk satuan kerja ID ' + SUPERADMIN_ALLOWED_SATKER_IDS.join(', ') + '.';
        } else if (assignRoleMeta.superadmin_limit_reached && !keepExistingSuperadmin) {
            hintEl.textContent = 'Role superadmin sudah mencapai batas maksimal (' + SUPERADMIN_MAX_LIMIT + ' pegawai).';
        } else {
            hintEl.textContent = '';
        }
    }

    // Load data untuk form assign role
    async function loadAssignRoleData() {
        try {
            const response = await fetch('{{ route("pegawai.assign-role-data") }}');
            const result = await response.json();
            if (result.success) {
                rolesInternalData = result.data.roles_internal || [];
                satkerData = result.data.satker || [];
                assignRoleMeta = result.data.meta || assignRoleMeta;
                assignRoleMeta.can_assign_superadmin = !!(assignRoleMeta.can_assign_superadmin && PAGE_CAN_ASSIGN_SUPERADMIN);
            }
        } catch (error) {
            console.error('Error loading assign role data:', error);
        }
    }

    // Load data saat halaman dimuat
    loadAssignRoleData();

    // Function untuk dipanggil dari button onclick
    window.showAssignRoleModalFromButton = function(button) {
        try {
            const pegawaiData = button.getAttribute('data-pegawai');
            if (!pegawaiData) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Data tidak ditemukan',
                        text: 'Data pegawai tidak ditemukan',
                    });
                } else {
                alert('Data pegawai tidak ditemukan');
                }
                return;
            }
            const pegawai = JSON.parse(pegawaiData);
            showAssignRoleModal(pegawai);
        } catch (error) {
            console.error('Error parsing pegawai data:', error);
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'error',
                    title: 'Terjadi kesalahan',
                    text: 'Terjadi kesalahan saat memuat data pegawai',
                });
            } else {
            alert('Terjadi kesalahan saat memuat data pegawai');
            }
        }
    };

    // Function untuk menampilkan modal
    window.showAssignRoleModal = async function(pegawai) {
        if (!pegawai || !pegawai.nip) {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'error',
                    title: 'Data tidak valid',
                    text: 'Data pegawai tidak valid',
                });
            } else {
            alert('Data pegawai tidak valid');
            }
            return;
        }

        // Set data pegawai di modal (read-only)
        document.getElementById('modal-nip').value = pegawai.nip || '';
        document.getElementById('modal-id-pegawai').value = pegawai.id?.toString() || '';
        document.getElementById('modal-username').value = pegawai.pengguna || '';
        document.getElementById('modal-nama').value = pegawai.nama || '';
        
        document.getElementById('modal-pegawai-info').textContent = `${pegawai.nama || '-'} (NIP: ${pegawai.nip || '-'})`;
        document.getElementById('modal-username-display').textContent = pegawai.pengguna || '-';
        document.getElementById('modal-nama-display').textContent = pegawai.nama || '-';

        // Load data jika belum ada
        if (rolesInternalData.length === 0 || satkerData.length === 0) {
            await loadAssignRoleData();
        }

        // Cek apakah pegawai sudah punya role
        let existingRole = null;
        try {
            if (pegawai && pegawai.nip) {
                const nip = encodeURIComponent(pegawai.nip);
                const response = await fetch(`{{ url('admin/pegawai-admin/role') }}/${nip}`);
                const result = await response.json();
                if (result.success && result.data) {
                    existingRole = result.data;
                }
            }
        } catch (error) {
            console.error('Error fetching existing role:', error);
        }

        // Set role_as_id jika sudah ada
        if (existingRole && existingRole.id) {
            document.getElementById('modal-role-as-id').value = existingRole.id;
        } else {
            document.getElementById('modal-role-as-id').value = '';
        }

        // Update modal title dan tombol
        const modalTitle = document.getElementById('assignRoleModalLabel');
        const btnSave = document.getElementById('btn-save-role');
        const btnDelete = document.getElementById('btn-delete-role');
        if (existingRole) {
            modalTitle.innerHTML = '<i class="ri-user-settings-line me-2"></i>Perbarui Role Pegawai';
            btnSave.innerHTML = '<i class="ri-save-line me-1"></i>Perbarui';
            btnDelete.style.display = 'inline-block';
            btnDelete.setAttribute('data-role-as-id', existingRole.id);
        } else {
            modalTitle.innerHTML = '<i class="ri-user-settings-line me-2"></i>Assign Role ke Pegawai';
            btnSave.innerHTML = '<i class="ri-save-line me-1"></i>Simpan';
            btnDelete.style.display = 'none';
        }

        // Populate satuan kerja select
        const unitKerjaSelect = document.getElementById('modal-satker');
        if (window.destroySelect2SatkerIfAny) {
            destroySelect2SatkerIfAny('#modal-satker');
        }

        // Remove any previous locked hidden input
        const existingLockedInput = document.getElementById('modal-satker-locked');
        if (existingLockedInput) existingLockedInput.remove();

        // Satuan Kerja TIDAK dikunci: admin/superadmin boleh memilih & mengubah satker
        // (sebagian data id_satker pegawai dari API ternyata tidak sesuai). id_satker
        // pegawai (atau satker role lama) hanya dipakai sebagai nilai default terpilih.
        const pegawaiIdSatker = pegawai.id_satker ? parseInt(pegawai.id_satker) : null;
        const preselectSatkerId = (existingRole && existingRole.id_satker)
            ? parseInt(existingRole.id_satker)
            : pegawaiIdSatker;

        unitKerjaSelect.disabled = false;
        unitKerjaSelect.innerHTML = '<option value="">Pilih Satuan Kerja</option>';

        let hasPreselectOption = false;
        satkerData.forEach(uk => {
            const option = document.createElement('option');
            option.value = uk.id;
            option.textContent = typeof window.satkerLabel === 'function'
                ? window.satkerLabel(uk)
                : `${uk.nama_satker || uk.nama || '-'} (${uk.kode_satker || uk.kode || '-'})`;
            if (preselectSatkerId && uk.id == preselectSatkerId) {
                option.selected = true;
                hasPreselectOption = true;
            }
            unitKerjaSelect.appendChild(option);
        });

        // id_satker default tidak ada di daftar satker lokal — tetap tampilkan sebagai opsi terpilih
        if (preselectSatkerId && !hasPreselectOption) {
            const option = document.createElement('option');
            option.value = preselectSatkerId;
            option.textContent = `Satuan Kerja (ID: ${preselectSatkerId})`;
            option.selected = true;
            unitKerjaSelect.appendChild(option);
        }

        // Populate roles internal checkboxes
        const rolesContainer = document.getElementById('modal-roles-container');
        rolesContainer.innerHTML = '';
        if (rolesInternalData.length === 0) {
            rolesContainer.innerHTML = '<p class="text-muted mb-0">Tidak ada role internal tersedia</p>';
        } else {
            // Simpan role lama untuk perbandingan saat update
            window.oldRoles = existingRole && existingRole.roles_internal ? 
                existingRole.roles_internal.map(r => ({ id: r.id, nama: r.nama })) : [];
            
            rolesInternalData.forEach(role => {
                if (normalizeRoleName(role.nama) === 'superadmin' && !assignRoleMeta.can_assign_superadmin) {
                    return;
                }
                const isChecked = existingRole && existingRole.roles_internal && 
                    existingRole.roles_internal.some(r => r.id === role.id);
                
                const div = document.createElement('div');
                div.className = 'form-check mb-2';
                div.innerHTML = `
                    <input class="form-check-input" type="checkbox" name="roles_internal_ids[]" 
                           value="${role.id}" id="role_${role.id}" data-role-name="${role.nama}" ${isChecked ? 'checked' : ''}>
                    <label class="form-check-label" for="role_${role.id}">
                        ${role.nama}
                    </label>
                `;
                rolesContainer.appendChild(div);
            });
            applyAdminSuperadminExclusiveRule(rolesContainer);
            const existingHasSuperadmin = !!(existingRole && existingRole.roles_internal && existingRole.roles_internal.some(r => normalizeRoleName(r.nama) === 'superadmin'));
            applySuperadminUnitRule(document.getElementById('assignRoleForm'), {
                keepExistingSuperadmin: existingHasSuperadmin
            });
        }

        // Satuan Kerja selalu bisa diubah → selalu pasang handler & select2
        unitKerjaSelect.onchange = () => {
            const existingHasSuperadmin = !!(existingRole && existingRole.roles_internal && existingRole.roles_internal.some(r => normalizeRoleName(r.nama) === 'superadmin'));
            applySuperadminUnitRule(document.getElementById('assignRoleForm'), {
                keepExistingSuperadmin: existingHasSuperadmin
            });
        };

        if (window.initSelect2Satker) {
            initSelect2Satker('#modal-satker', {
                dropdownParent: $('#assignRoleModal'),
                placeholder: 'Pilih Satuan Kerja',
                allowClear: true
            });
        }

        // Show modal
        cleanupModalArtifacts();
        const modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('assignRoleModal'));
        modal.show();
    };

    // Handle save button
    document.getElementById('btn-save-role').addEventListener('click', function() {
        const form = document.getElementById('assignRoleForm');
        const formData = new FormData(form);
        
        const nip = document.getElementById('modal-nip').value;
        const idPegawai = document.getElementById('modal-id-pegawai').value;
        const username = document.getElementById('modal-username').value;
        const nama = document.getElementById('modal-nama').value;
        const idUnitKerja = document.getElementById('modal-satker').value;
        const rolesCheckboxes = document.querySelectorAll('#modal-roles-container input[type="checkbox"]:checked');
        const rolesIds = Array.from(rolesCheckboxes).map(cb => parseInt(cb.value));

        // Validasi: tampilkan modal peringatan
        if (!idUnitKerja) {
            document.getElementById('validationRoleModalMessage').textContent = 'Satuan Kerja wajib dipilih sebelum menyimpan role.';
            (new bootstrap.Modal(document.getElementById('validationRoleModal'))).show();
            return;
        }

        if (rolesIds.length === 0) {
            document.getElementById('validationRoleModalMessage').textContent = 'Anda minimal wajib memilih 1 role sebelum menyimpan.';
            (new bootstrap.Modal(document.getElementById('validationRoleModal'))).show();
            return;
        }

        const roleAsId = document.getElementById('modal-role-as-id').value;
        const isUpdate = roleAsId && roleAsId !== '';

        const data = {
            nip: nip,
            username: username,
            id_pegawai: idPegawai,
            nama: nama,
            id_satker: parseInt(idUnitKerja),
            roles_internal_ids: rolesIds
        };

        if (isUpdate) {
            // Bandingkan role lama vs baru untuk popup konfirmasi
            const oldRoleIds = window.oldRoles ? window.oldRoles.map(r => r.id) : [];
            const newRoleIds = rolesIds;
            
            const addedRoles = newRoleIds.filter(id => !oldRoleIds.includes(id));
            const removedRoles = oldRoleIds.filter(id => !newRoleIds.includes(id));
            
            // Ambil nama role untuk ditampilkan
            const addedRoleNames = addedRoles.map(id => {
                const role = rolesInternalData.find(r => r.id === id);
                return role ? role.nama : '';
            }).filter(n => n);
            
            const removedRoleNames = removedRoles.map(id => {
                const role = window.oldRoles.find(r => r.id === id);
                return role ? role.nama : '';
            }).filter(n => n);
            
            // Konfirmasi perbarui role pakai SweetAlert (tanpa nama pegawai, icon warning, Batal bukan abu-abu)
            let confirmMessage = '';
            if (addedRoleNames.length > 0 || removedRoleNames.length > 0) {
                confirmMessage = '<strong>Perubahan role:</strong><br>';
                if (addedRoleNames.length > 0) {
                    confirmMessage += `<span style="color: #059669;">✓ Ditambahkan: ${addedRoleNames.join(', ')}</span><br>`;
                }
                if (removedRoleNames.length > 0) {
                    confirmMessage += `<span style="color: #dc2626;">✗ Dikurangi: ${removedRoleNames.join(', ')}</span>`;
                }
            } else {
                confirmMessage = 'Tidak ada perubahan role. Yakin ingin menyimpan?';
            }

            Swal.fire({
                title: 'Simpan Perubahan Role?',
                html: confirmMessage || 'Yakin ingin memperbarui role?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Simpan',
                cancelButtonText: 'Kembali',
                confirmButtonColor: '#b08d48',
                cancelButtonColor: '#0f172a',
                customClass: { popup: 'rounded-4', cancelButton: 'swal2-cancel-white' }
            }).then((result) => {
                if (result.isConfirmed) {
                    data.role_as_id = parseInt(roleAsId);
                    updateRoleToPegawai(data);
                }
            });
            return;
        } else {
            assignRoleToPegawai(data);
        }
    });

    function assignRoleToPegawai(data) {
        const btnSave = document.getElementById('btn-save-role');
        btnSave.disabled = true;
        btnSave.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Menyimpan...';

        fetch('{{ route("pegawai.store-role") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(result => {
            btnSave.disabled = false;
            btnSave.innerHTML = '<i class="ri-save-line"></i> Simpan';
            
            if (result.success) {
                const assignModal = bootstrap.Modal.getInstance(document.getElementById('assignRoleModal'));
                assignModal.hide();
                document.getElementById('successRoleModalMessage').textContent = result.message || 'Role pegawai berhasil disimpan.';
                const successModal = new bootstrap.Modal(document.getElementById('successRoleModal'));
                window._successRoleModalCallback = function() {
                    fetch('{{ route("pegawai.set-access-flag") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(() => { setTimeout(() => location.reload(), 100); })
                    .catch(() => location.reload());
                };
                document.getElementById('successRoleModal').addEventListener('hidden.bs.modal', function onHidden() {
                    document.getElementById('successRoleModal').removeEventListener('hidden.bs.modal', onHidden);
                    if (window._successRoleModalCallback) window._successRoleModalCallback();
                    window._successRoleModalCallback = null;
                }, { once: true });
                successModal.show();
            } else {
                let errorMsg = result.message || 'Terjadi kesalahan';
                if (result.errors) {
                    errorMsg = Object.values(result.errors).flat().join(', ');
                }
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal menyimpan',
                        text: errorMsg || 'Terjadi kesalahan saat menyimpan data.'
                    });
                } else {
                alert('Error! ' + errorMsg);
                }
            }
        })
        .catch(error => {
            btnSave.disabled = false;
            btnSave.innerHTML = '<i class="ri-save-line"></i> Simpan';
            console.error('Error:', error);
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'error',
                    title: 'Terjadi kesalahan',
                    text: 'Terjadi kesalahan saat menyimpan data'
                });
            } else {
            alert('Terjadi kesalahan saat menyimpan data');
            }
        });
    }

    function updateRoleToPegawai(data) {
        const btnSave = document.getElementById('btn-save-role');
        btnSave.disabled = true;
        btnSave.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Memperbarui...';

        const roleAsId = data.role_as_id;
        delete data.role_as_id; // Hapus dari data karena tidak perlu di body

        const updateUrl = `{{ url('admin/pegawai-admin/role') }}/${roleAsId}`;
        fetch(updateUrl, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(result => {
            btnSave.disabled = false;
            btnSave.innerHTML = '<i class="ri-save-line me-1"></i>Perbarui';
            
            if (result.success) {
                const assignModal = bootstrap.Modal.getInstance(document.getElementById('assignRoleModal'));
                assignModal.hide();
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: result.message || 'Role pegawai berhasil diperbarui.',
                    confirmButtonColor: '#b08d48',
                    customClass: { popup: 'rounded-4' }
                }).then(() => {
                    fetch('{{ route("pegawai.set-access-flag") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(() => { setTimeout(() => location.reload(), 100); })
                    .catch(() => location.reload());
                });
            } else {
                let errorMsg = result.message || 'Terjadi kesalahan';
                if (result.errors) {
                    errorMsg = Object.values(result.errors).flat().join(', ');
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: errorMsg,
                    confirmButtonColor: '#b08d48'
                });
            }
        })
        .catch(error => {
            btnSave.disabled = false;
            btnSave.innerHTML = '<i class="ri-save-line me-1"></i>Perbarui';
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Kesalahan',
                text: 'Terjadi kesalahan saat memperbarui data',
                confirmButtonColor: '#b08d48'
            });
        });
    }

    // Handle delete button: tampilkan modal konfirmasi
    document.getElementById('btn-delete-role').addEventListener('click', function() {
        const roleAsId = this.getAttribute('data-role-as-id');
        if (!roleAsId) {
            document.getElementById('validationRoleModalLabel').textContent = 'Perhatian';
            document.getElementById('validationRoleModalMessage').textContent = 'ID role tidak ditemukan.';
            (new bootstrap.Modal(document.getElementById('validationRoleModal'))).show();
            return;
        }

        // Tampilkan opsi: Hapus Role atau Hapus Permanen
        Swal.fire({
            title: 'Hapus Role Pegawai',
            text: 'Pilih aksi yang ingin dilakukan:',
            icon: 'question',
            showCancelButton: true,
            showDenyButton: true,
            confirmButtonText: 'Hapus Role',
            denyButtonText: 'Hapus Permanen',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#f59e0b',
            denyButtonColor: '#dc2626',
            reverseButtons: true
        }).then((result) => {
            if (!result.isConfirmed && !result.isDenied) return; // User membatalkan
            
            const btnDelete = this;
            btnDelete.disabled = true;
            
            if (result.isDenied) {
                // Hapus permanen
                Swal.fire({
                    title: 'Hapus Permanen?',
                    html: 'Pegawai akan dihapus permanen dari sistem.<br><br>Apakah Anda yakin?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Hapus Permanen',
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#dc2626'
                }).then((confirmResult) => {
                    if (!confirmResult.isConfirmed) {
                        btnDelete.disabled = false;
                        return;
                    }
                    
                    btnDelete.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Menghapus Permanen...';
                    performDelete(roleAsId, btnDelete, true);
                });
            } else {
                // Hapus role (ubah status jadi 0)
                Swal.fire({
                    title: 'Hapus Role?',
                    html: 'Semua role internal akan dihapus dan status pegawai akan diubah menjadi tidak aktif.',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Hapus Role',
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#f59e0b'
                }).then((confirmResult) => {
                    if (!confirmResult.isConfirmed) {
                        btnDelete.disabled = false;
                        return;
                    }
                    
                    btnDelete.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Menghapus...';
                    performDelete(roleAsId, btnDelete, false);
                });
            }
        });
        
        function performDelete(roleAsId, btnDelete, isPermanent) {
            const deleteUrl = `{{ url('admin/pegawai-admin/role') }}/${roleAsId}`;
            fetch(deleteUrl, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(result => {
                btnDelete.disabled = false;
                btnDelete.innerHTML = '<i class="ri-delete-bin-line me-1"></i>Hapus Role';
                
                if (result.success) {
                    const modal = bootstrap.Modal.getInstance(document.getElementById('assignRoleModal'));
                    if (modal) modal.hide();
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: result.message || 'Operasi berhasil dilakukan.',
                        confirmButtonColor: '#10b981'
                    }).then(() => {
                        // Set flag lagi sebelum reload agar tetap di halaman external
                        fetch('{{ route("pegawai.set-access-flag") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            }
                        })
                        .then(() => {
                            setTimeout(() => location.reload(), 100);
                        })
                        .catch(() => location.reload());
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: result.message || 'Terjadi kesalahan saat menghapus data.'
                    });
                }
            })
            .catch(error => {
                btnDelete.disabled = false;
                btnDelete.innerHTML = '<i class="ri-delete-bin-line me-1"></i>Hapus Role';
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Terjadi kesalahan saat menghapus data.'
                });
            });
        }
    });
</script>
@endsection
