@extends('parja::layouts.app')

@section('title', 'Dashboard Alumni')
@section('page-title', 'Dashboard Alumni')

@section('content')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
    integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />

<style>
    .custom-tooltip {
        background: rgba(17, 24, 39, 0.95) !important;
        border-radius: 8px !important;
        border: none !important;
        padding: 10px 12px !important;
        font-size: 11px !important;
        color: #fff !important;
        box-shadow: 0 6px 20px rgba(15, 23, 42, 0.24) !important;
    }

    .custom-tooltip strong {
        color: #ff4d8d;
        display: block;
        margin-bottom: 4px;
    }

    .sticky-note-label {
        background: transparent;
        border: 0;
    }

    .sticky-note-card {
        background: rgba(255, 255, 255, 0.97);
        border-radius: 3px;
        border-bottom: 2px solid #d00063;
        padding: 6px 9px 5px;
        min-width: 92px;
        text-align: center;
        box-shadow: 0 5px 14px rgba(15, 23, 42, 0.16);
        color: #111827;
        backdrop-filter: blur(1px);
    }

    .sticky-note-name {
        display: block;
        font-size: 10px;
        line-height: 1.2;
        font-weight: 500;
        color: #1f2937;
        margin-bottom: 3px;
    }

    .sticky-note-value {
        display: block;
        font-size: 31px;
        line-height: 0.92;
        font-weight: 500;
        letter-spacing: -0.02em;
        color: #0f172a;
    }

    .sticky-anchor-dot {
        background: #d00063;
        border: 1px solid rgba(255, 255, 255, 0.95);
    }

    .province-name-label {
        background: transparent;
        border: 0;
    }

    .province-name-chip {
        display: inline-block;
        padding: 1px 6px;
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.78);
        color: #334155;
        font-size: 10px;
        font-weight: 600;
        line-height: 1.25;
        box-shadow: 0 1px 5px rgba(30, 41, 59, 0.15);
        border: 1px solid rgba(148, 163, 184, 0.45);
        white-space: nowrap;
        text-shadow: 0 1px 0 rgba(255, 255, 255, 0.7);
    }

    .island-name-label {
        background: transparent;
        border: 0;
    }

    .island-name-chip {
        display: inline-block;
        padding: 3px 9px;
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.52);
        color: #0f172a;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 0.02em;
        border: 1px solid rgba(148, 163, 184, 0.5);
        backdrop-filter: blur(1px);
        text-shadow: 0 1px 0 rgba(255, 255, 255, 0.8);
    }

    @media (max-width: 992px) {
        .sticky-note-card {
            min-width: 76px;
            padding: 5px 7px 4px;
        }

        .sticky-note-name {
            font-size: 9px;
            margin-bottom: 2px;
        }

        .sticky-note-value {
            font-size: 24px;
        }

        .province-name-chip {
            font-size: 9px;
            padding: 1px 5px;
        }

        .island-name-chip {
            font-size: 10px;
            padding: 2px 7px;
        }
    }

    #alumni-map .leaflet-control-attribution {
        display: none;
    }

    #province-detail-body h5 {
        font-weight: 700;
        color: #0f172a;
    }

    #province-detail-body .row.g-2.mb-2 > [class*='col-'] .border.rounded {
        height: 100%;
        border-color: #dbe4ef !important;
    }

    #province-detail-body .fw-semibold.mb-1 {
        margin-bottom: 0.45rem !important;
    }

    #province-detail-body .form-select-sm,
    #province-detail-body .btn-sm {
        min-height: 36px;
    }

    #province-detail-body .table thead th {
        font-size: 12px;
        color: #334155;
    }

    #province-detail-body .table tbody td {
        vertical-align: middle;
    }

    #province-detail-body .btn-group .btn {
        min-width: 102px;
    }

    .explorer-toolbar {
        background: linear-gradient(165deg, #f8fafc 0%, #eef2f7 100%);
        border: 1px solid #d9e2ee;
        border-radius: 14px;
        padding: 14px;
        box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.75);
    }

    .explorer-toolbar .form-control,
    .explorer-toolbar .form-select {
        border-color: #cbd5e1;
    }

    .explorer-toolbar .form-control:focus,
    .explorer-toolbar .form-select:focus {
        border-color: #0b5ed7;
        box-shadow: 0 0 0 0.2rem rgba(11, 94, 215, 0.15);
    }

    .explorer-summary-pill {
        font-size: 12px;
        color: #334155;
        background: #f8fafc;
        border: 1px solid #dbe4ef;
        border-radius: 999px;
        padding: 4px 10px;
    }

    .explorer-card {
        border: 1px solid #dbe4ef;
        border-radius: 14px;
        background: linear-gradient(180deg, #ffffff 0%, #fbfdff 100%);
        padding: 14px;
        box-shadow: 0 6px 16px rgba(15, 23, 42, 0.06);
        transition: transform 0.16s ease, box-shadow 0.16s ease, border-color 0.16s ease;
    }

    .explorer-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 22px rgba(15, 23, 42, 0.1);
        border-color: #c6d3e4;
    }

    .explorer-avatar {
        width: 76px;
        height: 76px;
        border-radius: 12px;
        object-fit: cover;
        background: #e2e8f0;
        border: 1px solid #dbe4ef;
        box-shadow: 0 3px 10px rgba(15, 23, 42, 0.08);
    }

    .explorer-name {
        font-size: 16px;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 2px;
        letter-spacing: -0.01em;
    }

    .explorer-meta {
        font-size: 12px;
        color: #475569;
        line-height: 1.45;
    }

    .explorer-meta strong {
        color: #1e293b;
    }

    .explorer-badge {
        display: inline-block;
        border: 1px solid rgba(65, 23, 75, 0.12);
        background: linear-gradient(180deg, rgba(255, 255, 255, 0.95), rgba(248, 244, 252, 0.95));
        color: var(--parja-purple);
        border-radius: 999px;
        padding: 4px 10px;
        font-size: 10.5px;
        margin-right: 6px;
        margin-top: 4px;
        font-weight: 800;
        box-shadow: 0 1px 0 rgba(255, 255, 255, 0.72) inset, 0 4px 12px rgba(65, 23, 75, 0.08);
    }

    .explorer-badge-status-approved {
        background: linear-gradient(180deg, rgba(236, 253, 243, 0.98), rgba(220, 252, 231, 0.98));
        border-color: rgba(34, 197, 94, 0.22);
        color: #166534;
    }

    .explorer-badge-status-pending {
        background: linear-gradient(180deg, rgba(255, 251, 235, 0.98), rgba(254, 243, 199, 0.98));
        border-color: rgba(245, 158, 11, 0.22);
        color: #92400e;
    }

    .explorer-badge-status-rejected {
        background: linear-gradient(180deg, rgba(254, 242, 242, 0.98), rgba(254, 226, 226, 0.98));
        border-color: rgba(239, 68, 68, 0.22);
        color: #991b1b;
    }

    .explorer-badge-profile-complete {
        background: linear-gradient(180deg, rgba(238, 242, 255, 0.98), rgba(224, 231, 255, 0.98));
        border-color: rgba(99, 102, 241, 0.22);
        color: #3730a3;
    }

    .explorer-badge-profile-incomplete {
        background: linear-gradient(180deg, rgba(241, 245, 249, 0.98), rgba(226, 232, 240, 0.98));
        border-color: rgba(148, 163, 184, 0.22);
        color: #334155;
    }

    .explorer-bio {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .explorer-card-trigger {
        cursor: pointer;
    }

    .explorer-detail-btn {
        margin-top: 8px;
        padding: 4px 10px;
        border-radius: 999px;
            radial-gradient(circle at 12% -20%, rgba(251, 172, 24, 0.18), transparent 42%),
            radial-gradient(circle at 88% 120%, rgba(191, 0, 80, 0.18), transparent 44%),
            linear-gradient(135deg, rgba(65, 23, 75, 0.97) 0%, rgba(191, 0, 80, 0.92) 100%);
        font-size: 12px;
        box-shadow: 0 14px 34px rgba(65, 23, 75, 0.14);
        box-shadow: 0 2px 8px rgba(65, 23, 75, 0.06);
    }
        color: #fff;

    .explorer-detail-btn:hover {
        border-color: rgba(191, 0, 80, 0.32);
        color: var(--parja-magenta);
        background: linear-gradient(135deg, rgba(191, 0, 80, 0.12), rgba(65, 23, 75, 0.08));
    }

    .explorer-detail-avatar {
        width: 92px;
        height: 92px;
        border-radius: 14px;
        object-fit: cover;
        border: 1px solid var(--parja-border);
        background: var(--parja-soft);
        box-shadow: 0 8px 18px rgba(65, 23, 75, 0.08);
    }

    .explorer-detail-title {
        font-size: 24px;
        line-height: 1.2;
        color: var(--parja-purple);
        margin-bottom: 4px;
    }

    .explorer-detail-subtitle {
        color: var(--parja-muted);
        font-size: 14px;
    }

    .explorer-detail-chip {
        border: 1px solid rgba(255, 255, 255, 0.28);
        background: rgba(255, 255, 255, 0.14);
        border-radius: 999px;
        padding: 3px 9px;
        font-size: 12px;
        color: #fff;
        display: inline-block;
        margin-right: 6px;
        margin-bottom: 6px;
        box-shadow: 0 2px 8px rgba(65, 23, 75, 0.06);
    }

    .explorer-detail-chip--soft {
        border: 1px solid rgba(191, 0, 80, 0.18);
        background: rgba(191, 0, 80, 0.08);
        color: var(--parja-purple);
    }

    .explorer-detail-hero {
        border: 1px solid var(--parja-border);
        border-radius: 16px;
        background:
            radial-gradient(circle at 12% -20%, rgba(251, 172, 24, 0.18), transparent 42%),
            radial-gradient(circle at 88% 120%, rgba(191, 0, 80, 0.18), transparent 44%),
            linear-gradient(135deg, rgba(65, 23, 75, 0.97) 0%, rgba(191, 0, 80, 0.92) 100%);
        padding: 18px;
        box-shadow: 0 14px 34px rgba(65, 23, 75, 0.14);
        position: relative;
        overflow: hidden;
        color: #fff;
    }

    .explorer-detail-hero::after {
        content: '';
        position: absolute;
        inset: auto -42px -54px auto;
        width: 180px;
        height: 180px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(251, 172, 24, 0.24), rgba(251, 172, 24, 0));
        pointer-events: none;
    }

    .explorer-detail-hero .explorer-detail-title,
    .explorer-detail-hero .explorer-detail-subtitle,
    .explorer-detail-hero .explorer-meta,
    .explorer-detail-hero .explorer-meta strong,
    .explorer-detail-hero .explorer-detail-contact-label,
    .explorer-detail-hero .explorer-detail-contact-value {
        color: #fff;
    }

    .explorer-detail-hero .explorer-detail-contact {
        border-left: 1px solid rgba(255, 255, 255, 0.22);
    }

    .explorer-detail-contact {
        border-left: 1px solid var(--parja-border);
        padding-left: 18px;
    }

    .explorer-detail-contact-label {
        font-size: 12px;
        color: var(--parja-purple);
        text-transform: uppercase;
        letter-spacing: 0.04em;
        font-weight: 700;
        margin-bottom: 3px;
    }

    .explorer-detail-contact-value {
        font-size: 14px;
        color: #334155;
        margin-bottom: 10px;
        word-break: break-word;
    }

    .explorer-detail-card {
        border: 1px solid var(--parja-border);
        border-radius: 0;
        background: linear-gradient(180deg, #ffffff 0%, #fffdfd 100%);
        box-shadow: 0 6px 14px rgba(65, 23, 75, 0.05);
        position: relative;
        overflow: hidden;
        transition: background 0.3s ease, border-color 0.3s ease;
    }

    .explorer-detail-card::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 3px;
        background: linear-gradient(180deg, var(--parja-magenta), var(--parja-purple));
        opacity: 0.7;
        transition: background 0.3s ease, opacity 0.3s ease;
    }

    /* Beri jarak (gap) konten dari garis aksen kiri (::before) agar teks
       tidak menempel ke garis. Padding kiri lebih besar dari lebar garis 3px. */
    .explorer-detail-card .card-body {
        padding: 16px 18px 16px 20px;
    }

    .explorer-detail-card .explorer-detail-section-title {
        margin-left: 2px;
    }

    .explorer-detail-card-profile.is-section-active {
        background: linear-gradient(180deg, #ffffff 0%, rgba(191, 0, 80, 0.05) 100%);
        border-color: rgba(191, 0, 80, 0.25);
    }
    .explorer-detail-card-profile.is-section-active::before {
        background: linear-gradient(180deg, #bf0050, #41174b);
        opacity: 1;
    }

    .explorer-detail-card-pendidikan.is-section-active {
        background: linear-gradient(180deg, #ffffff 0%, rgba(14, 165, 233, 0.08) 100%);
        border-color: rgba(14, 165, 233, 0.35);
    }
    .explorer-detail-card-pendidikan.is-section-active::before {
        background: linear-gradient(180deg, #0ea5e9, #0284c7);
        opacity: 1;
    }

    .explorer-detail-card-pekerjaan.is-section-active {
        background: linear-gradient(180deg, #ffffff 0%, rgba(16, 185, 129, 0.08) 100%);
        border-color: rgba(16, 185, 129, 0.35);
    }
    .explorer-detail-card-pekerjaan.is-section-active::before {
        background: linear-gradient(180deg, #10b981, #059669);
        opacity: 1;
    }

    .explorer-detail-card-organisasi.is-section-active {
        background: linear-gradient(180deg, #ffffff 0%, rgba(245, 158, 11, 0.08) 100%);
        border-color: rgba(245, 158, 11, 0.35);
    }
    .explorer-detail-card-organisasi.is-section-active::before {
        background: linear-gradient(180deg, #f59e0b, #d97706);
        opacity: 1;
    }

    .explorer-detail-tabbar {
        display: flex;
        justify-content: center;
        flex-wrap: wrap;
        gap: 8px;
        margin: 0;
    }

    .explorer-detail-tabbar-wrap {
        position: sticky;
        top: 0;
        z-index: 8;
        margin-top: 0;
        margin-bottom: 4px;
        padding: 8px 0 8px;
        background: linear-gradient(180deg, rgba(255, 255, 255, 0.98) 0%, rgba(255, 236, 244, 0.98) 100%);
        backdrop-filter: blur(2px);
        border-bottom: 1px solid rgba(191, 0, 80, 0.12);
    }

    .explorer-detail-tab {
        border: 1px solid rgba(191, 0, 80, 0.24);
        background: linear-gradient(180deg, rgba(255, 255, 255, 0.95), rgba(255, 243, 248, 0.95));
        color: var(--parja-magenta);
        border-radius: 999px;
        padding: 6px 12px;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 0.01em;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .explorer-detail-tab.is-active {
        background: linear-gradient(135deg, rgba(65, 23, 75, 0.96), rgba(191, 0, 80, 0.94));
        border-color: rgba(65, 23, 75, 0.55);
        color: #fff;
        box-shadow: 0 8px 18px rgba(65, 23, 75, 0.16), inset 0 -2px 0 rgba(251, 172, 24, 0.72);
    }

    /* Pane detail alumni: hanya satu yang tampil sesuai tab aktif (full per-tab). */
    .explorer-detail-pane {
        display: none;
    }

    .explorer-detail-pane.is-active {
        display: block;
        animation: explorerPaneIn 0.25s ease;
    }

    @keyframes explorerPaneIn {
        from {
            opacity: 0;
            transform: translateY(6px);
        }

        to {
            opacity: 1;
            transform: none;
        }
    }

    .explorer-detail-section-title {
        border-left: 4px solid var(--parja-magenta);
        padding-left: 9px;
        margin-bottom: 10px;
        display: inline-flex;
        align-items: center;
        gap: 7px;
        color: var(--parja-purple);
        font-weight: 800;
    }

    .explorer-detail-section-title i {
        color: var(--parja-magenta);
        font-size: 16px;
    }

    [id^='detail-section-'] {
        scroll-margin-top: 118px;
    }

    .explorer-detail-focus {
        animation: explorerDetailPulse 0.9s ease;
        box-shadow: 0 0 0 2px rgba(191, 0, 80, 0.18);
    }

    .modal-content:has(#explorer-detail-modal-body) {
        border: 1px solid rgba(65, 23, 75, 0.12);
        box-shadow: 0 20px 60px rgba(65, 23, 75, 0.18);
        overflow: hidden;
    }

    #explorer-detail-modal .modal-header {
        border-bottom: 1px solid rgba(65, 23, 75, 0.08);
        background: linear-gradient(180deg, rgba(255, 255, 255, 0.98), rgba(255, 247, 250, 0.94));
    }

    #explorer-detail-modal .modal-title {
        color: var(--parja-purple);
        font-weight: 800;
        letter-spacing: -0.02em;
    }

    #explorer-detail-modal .btn-close {
        opacity: 0.75;
    }

    #explorer-detail-modal .modal-body {
        background: linear-gradient(180deg, rgba(255, 255, 255, 0.98) 0%, rgba(255, 247, 250, 0.98) 100%);
    }

    @keyframes explorerDetailPulse {
        0% {
            box-shadow: 0 0 0 0 rgba(191, 0, 80, 0.22);
            background-color: rgba(255, 244, 248, 0.95);
        }

        100% {
            box-shadow: 0 0 0 0 rgba(244, 63, 94, 0);
            background-color: transparent;
        }
    }

    @media (max-width: 992px) {
        .explorer-detail-contact {
            border-left: 0;
            border-top: 1px solid #e2e8f0;
            padding-left: 0;
            padding-top: 14px;
            margin-top: 12px;
        }
    }

    .explorer-timeline {
        position: relative;
        padding-left: 18px;
    }

    .explorer-timeline::before {
        content: '';
        position: absolute;
        left: 6px;
        top: 3px;
        bottom: 3px;
        width: 2px;
        background: #f2a8bf;
    }

    .explorer-timeline-item {
        position: relative;
        padding-left: 14px;
        margin-bottom: 14px;
    }

    .explorer-timeline-item:last-child {
        margin-bottom: 0;
    }

    .explorer-timeline-item::before {
        content: '';
        position: absolute;
        left: -18px;
        top: 4px;
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: #ef4444;
        box-shadow: 0 0 0 3px #fee2e2;
    }

    .explorer-timeline-period {
        font-size: 12px;
        color: #334155;
        font-weight: 700;
    }

    .explorer-timeline-title {
        font-size: 15px;
        color: #0f172a;
        font-weight: 600;
    }

    .explorer-timeline-subtitle {
        font-size: 13px;
        color: #475569;
    }

    @media (max-width: 992px) {
        .explorer-toolbar {
            padding: 12px;
        }

        .explorer-summary-pill {
            margin-bottom: 8px;
        }

        .explorer-avatar {
            width: 68px;
            height: 68px;
        }
    }
    /* ===== Survey Stats Styles ===== */
    .survey-kpi-card {
        background: linear-gradient(180deg, #ffffff 0%, #f8faff 100%);
        border: 1px solid #dbe4ef;
        border-radius: 14px;
        padding: 16px;
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        gap: 8px;
        box-shadow: 0 4px 14px rgba(15, 23, 42, 0.06);
        transition: transform 0.18s ease, box-shadow 0.18s ease;
        height: 100%;
    }

    .survey-kpi-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 24px rgba(15, 23, 42, 0.1);
    }

    .survey-kpi-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        color: #fff;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        flex-shrink: 0;
    }

    .survey-kpi-value {
        font-size: 28px;
        font-weight: 800;
        line-height: 1;
        color: #0f172a;
        letter-spacing: -0.02em;
    }

    .survey-kpi-label {
        font-size: 12px;
        color: #64748b;
        font-weight: 500;
        line-height: 1.3;
    }

    .survey-chart-card {
        background: linear-gradient(180deg, #fff 0%, #f8fafc 100%);
        border: 1px solid #dbe4ef;
        border-radius: 14px;
        padding: 18px;
        box-shadow: 0 4px 14px rgba(15, 23, 42, 0.05);
        height: 100%;
    }

    .survey-chart-title {
        font-size: 13px;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 14px;
        display: flex;
        align-items: center;
    }

    .survey-chart-title i {
        color: #6366f1;
        font-size: 16px;
    }

    @media (max-width: 767px) {
        .survey-kpi-value {
            font-size: 22px;
        }

        .survey-kpi-card {
            padding: 12px;
        }
    }

</style>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <div>
        <ol class="breadcrumb fs-sm mb-1">
            <li class="breadcrumb-item">Dashboard Alumni</li>
        </ol>
        <h4 class="main-title mb-0">Dashboard Alumni</h4>
    </div>
</div>

<div class="row g-3">
    <div class="col-xl-8">
        <div class="card card-one h-100">
            <div class="card-header d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="card-title mb-0">Peta Sebaran Alumni per Provinsi</h6>
                    <small class="text-secondary">Klik provinsi untuk melihat detail statistik alumni</small>
                </div>
                <span class="badge bg-primary-subtle text-primary border" id="summary-generated-at">Memuat data...</span>
            </div>
            <div class="card-body p-2 p-md-3">
                <div id="map-status" class="alert alert-info py-2 px-3 mb-2">Menyiapkan peta dan data...</div>
                <div id="alumni-map" style="height: 520px; border-radius: 12px; background: #d6dee9;"></div>
                <div class="d-flex align-items-center flex-wrap gap-2 mt-3">
                    <span class="fs-xs text-secondary">Intensitas:</span>
                    <span class="badge bg-light text-dark border">0</span>
                    <span class="badge border" style="background:#fbe6ef;color:#8f0040">Rendah</span>
                    <span class="badge border" style="background:#f6c4da;color:#8f0040">Sedang</span>
                    <span class="badge border" style="background:#ee8eb8;color:#7c0037">Tinggi</span>
                    <span class="badge border" style="background:#bf0050;color:#fff">Sangat Tinggi</span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4">
        <div class="card card-one mb-3">
            <div class="card-header">
                <h6 class="card-title mb-0">Detail Provinsi</h6>
            </div>
            <div class="card-body" id="province-detail-body">
                <div class="text-secondary">Pilih provinsi pada peta untuk menampilkan statistik detail.</div>
            </div>
        </div>
    </div>
</div>

{{-- Card "Cari Alumni per Wilayah" dipindah ke Data Direktori Alumni
     per atasan 18-05-2026 #8. Fungsionalitas ada di /parja/direktori-alumni
     dengan filter lengkap (Nama, Provinsi, Dapil, Tahun, Domisili, Jurusan, Profesi, Ketertarikan)
     + indikator Approved & Profile Lengkap.

     JS terkait (loadExplorer, setExplorerProvinceOptions, dll) dibiarkan apa adanya
     karena sudah defensive (if (!element) return) — no-op saat HTML hilang.
     Restore: uncomment block di bawah jika atasan ingin kembalikan di dashboard.
--}}
{{--
<div class="row g-3 mt-1">
    <div class="col-12">
        <div class="card card-one">
            <div class="card-header">
                <h6 class="card-title mb-0">Cari Alumni per Wilayah</h6>
            </div>
            <div class="card-body">
                <div class="explorer-toolbar mb-3">
                    <div class="row g-2 align-items-end">
                        <div class="col-12 col-lg-5">
                            <label class="form-label mb-1" for="explorer-search">Cari Alumni</label>
                            <input type="text" class="form-control" id="explorer-search" placeholder="Cari nama, email, dapil, pekerjaan, pendidikan...">
                        </div>
                        <div class="col-6 col-lg-3">
                            <label class="form-label mb-1" for="explorer-province">Provinsi</label>
                            <select class="form-select" id="explorer-province">
                                <option value="">Semua Provinsi</option>
                            </select>
                        </div>
                        <div class="col-6 col-lg-2">
                            <label class="form-label mb-1" for="explorer-dapil">Dapil</label>
                            <select class="form-select" id="explorer-dapil">
                                <option value="">Semua Dapil</option>
                            </select>
                        </div>
                        <div class="col-6 col-lg-1 d-grid">
                            <button type="button" class="btn btn-primary" id="explorer-filter-btn">Cari</button>
                        </div>
                        <div class="col-6 col-lg-1 d-grid">
                            <button type="button" class="btn btn-outline-secondary" id="explorer-reset-btn">Reset</button>
                        </div>
                        <div class="col-12 col-lg-5">
                            <label class="form-label mb-1" for="explorer-domisili">Domisili Terakhir</label>
                            <input type="text" class="form-control" id="explorer-domisili" placeholder="Cari kota/kabupaten domisili...">
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-2">
                    <div class="explorer-summary-pill" id="explorer-summary">Atur filter lalu klik Cari.</div>
                    <div class="btn-group btn-group-sm" role="group" aria-label="Navigasi halaman alumni explorer">
                        <button type="button" class="btn btn-outline-secondary" id="explorer-prev" disabled>Sebelumnya</button>
                        <button type="button" class="btn btn-outline-secondary" id="explorer-next" disabled>Berikutnya</button>
                    </div>
                </div>

                <div id="explorer-card-list" class="row g-2">
                    <div class="col-12 text-center text-secondary py-3 border rounded">Pilih filter lalu klik tombol Cari untuk menampilkan alumni.</div>
                </div>
            </div>
        </div>
    </div>
</div>
--}}

<div class="card card-one mb-3">
    <div class="card-body py-3">
        <div class="row g-2 align-items-end">
            <div class="col-sm-6 col-md-4 col-xl-3">
                <label for="filter-tahun-angkatan" class="form-label mb-1">Tahun Angkatan</label>
                <select class="form-select" id="filter-tahun-angkatan">
                    <option value="">Semua Tahun</option>
                </select>
            </div>
            <div class="col-sm-6 col-md-4 col-xl-3">
                <label for="filter-approval-status" class="form-label mb-1">Status Approval</label>
                <select class="form-select" id="filter-approval-status">
                    <option value="all">Semua Status</option>
                    <option value="1">Approved</option>
                    <option value="0">Pending</option>
                    <option value="2">Rejected</option>
                </select>
            </div>
            <div class="col-sm-12 col-md-4 col-xl-6 d-flex gap-2 justify-content-md-end">
                <button type="button" class="btn btn-primary" id="btn-apply-filter">
                    <i class="ri-filter-3-line"></i> Terapkan Filter
                </button>
                <button type="button" class="btn btn-outline-secondary" id="btn-reset-filter">
                    <i class="ri-refresh-line"></i> Reset
                </button>
                <button type="button" class="btn btn-success" id="btn-export-csv">
                    <i class="ri-download-line"></i> Export CSV
                </button>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-6 col-xl-3">
        <div class="card card-one h-100">
            <div class="card-body py-3">
                <div class="fs-xs text-secondary mb-1">Total Alumni Aktif</div>
                <h3 class="mb-0" id="stat-total-alumni">0</h3>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="card card-one h-100">
            <div class="card-body py-3">
                <div class="fs-xs text-secondary mb-1">Approved</div>
                <h3 class="mb-0 text-success" id="stat-approved">0</h3>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="card card-one h-100">
            <div class="card-body py-3">
                <div class="fs-xs text-secondary mb-1">Pending</div>
                <h3 class="mb-0 text-warning" id="stat-pending">0</h3>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="card card-one h-100">
            <div class="card-body py-3">
                <div class="fs-xs text-secondary mb-1">Ditolak</div>
                <h3 class="mb-0 text-danger" id="stat-rejected">0</h3>
            </div>
        </div>
    </div>
</div>

{{-- ===== CHART ROW 1: Distribusi Approval, Kelengkapan Profil, Tren Angkatan ===== --}}
<div class="row g-3 mb-3" id="chart-section-row1">
    <div class="col-12 col-md-6 col-xl-4">
        <div class="card card-one h-100">
            <div class="card-header">
                <h6 class="card-title mb-0"><i class="ri-pie-chart-2-line me-1 text-primary"></i>Distribusi Status Approval</h6>
            </div>
            <div class="card-body d-flex flex-column align-items-center justify-content-center" style="min-height:220px;">
                <div id="chart-approval-loading" class="text-secondary">Memuat data...</div>
                <div id="chart-approval-wrap" style="display:none;width:100%;max-width:260px;">
                    <canvas id="chart-approval" height="220"></canvas>
                </div>
                <div id="chart-approval-legend" class="d-flex flex-wrap justify-content-center gap-2 mt-2" style="font-size:12px;"></div>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-6 col-xl-4">
        <div class="card card-one h-100">
            <div class="card-header">
                <h6 class="card-title mb-0"><i class="ri-user-settings-line me-1 text-success"></i>Kelengkapan Profil Alumni</h6>
            </div>
            <div class="card-body d-flex flex-column align-items-center justify-content-center" style="min-height:220px;">
                <div id="chart-profile-loading" class="text-secondary">Memuat data...</div>
                <div id="chart-profile-wrap" style="display:none;width:100%;max-width:260px;">
                    <canvas id="chart-profile" height="220"></canvas>
                </div>
                <div id="chart-profile-legend" class="d-flex flex-wrap justify-content-center gap-2 mt-2" style="font-size:12px;"></div>
            </div>
        </div>
    </div>
    <div class="col-12 col-xl-4">
        <div class="card card-one h-100">
            <div class="card-header">
                <h6 class="card-title mb-0"><i class="ri-line-chart-line me-1 text-warning"></i>Tren Alumni per Tahun Angkatan</h6>
            </div>
            <div class="card-body d-flex flex-column justify-content-center" style="min-height:220px;">
                <div id="chart-angkatan-loading" class="text-secondary">Memuat data...</div>
                <div id="chart-angkatan-wrap" style="display:none;width:100%;">
                    <canvas id="chart-angkatan" height="195"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ===== CHART ROW 2: Post Statistik + Top Pekerjaan + Top Pendidikan ===== --}}
<div class="row g-3 mb-3" id="chart-section-row2">
    <div class="col-12 col-md-6 col-xl-4">
        <div class="card card-one h-100">
            <div class="card-header d-flex align-items-center gap-2">
                <h6 class="card-title mb-0 me-auto"><i class="ri-article-line me-1 text-danger"></i>Statistik Konten / Post</h6>
                <div id="stat-post-total-badge" class="badge bg-secondary">0 Post</div>
            </div>
            <div class="card-body">
                <div id="chart-post-loading" class="text-secondary">Memuat data...</div>
                <div id="chart-post-wrap" style="display:none;">
                    <div class="row g-2 mb-3">
                        <div class="col-4">
                            <div class="border rounded p-2 text-center">
                                <div class="fs-xs text-secondary">Disetujui</div>
                                <div class="fw-bold text-success fs-5" id="stat-post-approved">0</div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="border rounded p-2 text-center">
                                <div class="fs-xs text-secondary">Pending</div>
                                <div class="fw-bold text-warning fs-5" id="stat-post-pending">0</div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="border rounded p-2 text-center">
                                <div class="fs-xs text-secondary">Ditolak</div>
                                <div class="fw-bold text-danger fs-5" id="stat-post-rejected">0</div>
                            </div>
                        </div>
                    </div>
                    <canvas id="chart-post" height="160"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-6 col-xl-4">
        <div class="card card-one h-100">
            <div class="card-header">
                <h6 class="card-title mb-0"><i class="ri-briefcase-4-line me-1 text-info"></i>Top 10 Pekerjaan Alumni</h6>
            </div>
            <div class="card-body" style="min-height:220px;">
                <div id="chart-pekerjaan-loading" class="text-secondary">Memuat data...</div>
                <div id="chart-pekerjaan-wrap" style="display:none;width:100%;">
                    <canvas id="chart-pekerjaan" height="260"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-xl-4">
        <div class="card card-one h-100">
            <div class="card-header">
                <h6 class="card-title mb-0"><i class="ri-graduation-cap-line me-1 text-primary"></i>Top 10 Pendidikan Alumni</h6>
            </div>
            <div class="card-body" style="min-height:220px;">
                <div id="chart-pendidikan-loading" class="text-secondary">Memuat data...</div>
                <div id="chart-pendidikan-wrap" style="display:none;width:100%;">
                    <canvas id="chart-pendidikan" height="260"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ===== CUPLIKAN ARTIKEL TERBARU (feed alumni) =====
     Per atasan 18-05-2026 #9: di bawah statistic konten, tampilkan cuplikan artikel terbaru
     dari feed alumni (alumni_posts approved). Empty state aktif saat 0 post.
--}}
<div class="row g-3 mb-3" id="latest-articles-row">
    <div class="col-12">
        <div class="card card-one">
            <div class="card-header d-flex align-items-center gap-2">
                <h6 class="card-title mb-0 me-auto">
                    <i class="ri-news-line me-1 text-danger"></i>
                    Cuplikan Artikel Terbaru
                </h6>
                <span class="badge bg-light text-dark border">{{ ($latestPosts ?? collect())->count() }} artikel</span>
            </div>
            <div class="card-body">
                @php $posts = $latestPosts ?? collect(); @endphp
                @if ($posts->isEmpty())
                    <div class="text-center py-4">
                        <i class="ri-article-line" style="font-size:2.5rem; color:#cbd5e1;"></i>
                        <p class="mb-1 mt-2 text-secondary">Belum ada artikel dari feed alumni.</p>
                        <p class="fs-xs text-secondary mb-0">
                            Konten akan otomatis tampil di sini ketika alumni mulai membuat postingan.
                        </p>
                    </div>
                @else
                    <div class="row g-3">
                        @foreach ($posts as $post)
                            @php
                                $rawKonten = (string) ($post->konten ?? '');
                                $snippet   = trim(strip_tags($rawKonten));
                                if (mb_strlen($snippet) > 160) {
                                    $snippet = mb_substr($snippet, 0, 157) . '…';
                                }
                                $judul = trim((string) ($post->judul ?? '')) ?: '(Tanpa Judul)';
                                $author = trim((string) ($post->author_name ?? 'Alumni'));
                                $dapil  = trim((string) ($post->author_dapil ?? ''));
                                try { $createdAt = \Carbon\Carbon::parse($post->created_at)->diffForHumans(); }
                                catch (\Throwable $e) { $createdAt = '-'; }
                            @endphp
                            <div class="col-12 col-md-6 col-xl-4">
                                <div class="border rounded p-3 h-100" style="background:#fdfdfe;">
                                    <p class="fw-semibold mb-1" style="color:#1e293b; font-size:0.95rem;">
                                        {{ $judul }}
                                    </p>
                                    <p class="text-secondary fs-xs mb-2">
                                        <i class="ri-user-line"></i> {{ $author }}
                                        @if ($dapil !== '') <span class="mx-1">•</span> {{ $dapil }} @endif
                                        <span class="mx-1">•</span> {{ $createdAt }}
                                    </p>
                                    <p class="mb-0 text-body fs-sm" style="line-height:1.55;">
                                        {{ $snippet ?: '(Tidak ada cuplikan tersedia.)' }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- ===== CHART ROW 3: Top Domisili + Top Bidang Ketertarikan + Top Provinsi tabel ===== --}}
<div class="row g-3 mb-3" id="chart-section-row3">
    <div class="col-12 col-md-6 col-xl-4">
        <div class="card card-one h-100">
            <div class="card-header">
                <h6 class="card-title mb-0"><i class="ri-map-pin-2-line me-1 text-success"></i>Top 10 Domisili Alumni</h6>
            </div>
            <div class="card-body" style="min-height:220px;">
                <div id="chart-domisili-loading" class="text-secondary">Memuat data...</div>
                <div id="chart-domisili-wrap" style="display:none;width:100%;">
                    <canvas id="chart-domisili" height="260"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-6 col-xl-4">
        <div class="card card-one h-100">
            <div class="card-header">
                <h6 class="card-title mb-0"><i class="ri-lightbulb-line me-1 text-warning"></i>Top Bidang Ketertarikan</h6>
            </div>
            <div class="card-body" style="min-height:220px;">
                <div id="chart-bidang-loading" class="text-secondary">Memuat data...</div>
                <div id="chart-bidang-wrap" style="display:none;width:100%;">
                    <canvas id="chart-bidang" height="260"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-xl-4">
        <div class="card card-one h-100">
            <div class="card-header">
                <h6 class="card-title mb-0"><i class="ri-building-line me-1 text-secondary"></i>Top Provinsi (Alumni Aktif)</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th class="ps-3">Provinsi</th>
                                <th class="text-end pe-3">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody id="top-province-body">
                            <tr>
                                <td colspan="2" class="text-center text-secondary py-3">Memuat data...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- ===== SURVEY STATISTICS SECTION ===== --}}
<div class="row g-3 mb-3 mt-1" id="survey-stats-section">
    <div class="col-12">
        <div class="card card-one">
            <div class="card-header d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="card-title mb-0"><i class="ri-survey-line me-1 text-primary"></i>Statistik Data Survei</h6>
                    <small class="text-secondary">Ringkasan partisipasi dan aktivitas survei alumni</small>
                </div>
                <span class="badge bg-primary-subtle text-primary border" id="survey-generated-at">Memuat...</span>
            </div>
            <div class="card-body">
                {{-- KPI Row --}}
                <div class="row g-3 mb-4" id="survey-kpi-row">
                    <div class="col-6 col-md-3">
                        <div class="survey-kpi-card" id="survey-kpi-total">
                            <div class="survey-kpi-icon" style="background:linear-gradient(135deg,#6366f1,#4f46e5)">
                                <i class="ri-file-list-3-line"></i>
                            </div>
                            <div class="survey-kpi-value" id="kpi-total-survei">—</div>
                            <div class="survey-kpi-label">Total Survei</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="survey-kpi-card" id="survey-kpi-active">
                            <div class="survey-kpi-icon" style="background:linear-gradient(135deg,#10b981,#059669)">
                                <i class="ri-checkbox-circle-line"></i>
                            </div>
                            <div class="survey-kpi-value text-success" id="kpi-survei-terbit">—</div>
                            <div class="survey-kpi-label">Survei Aktif (Terbit)</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="survey-kpi-card" id="survey-kpi-peserta">
                            <div class="survey-kpi-icon" style="background:linear-gradient(135deg,#f59e0b,#d97706)">
                                <i class="ri-group-line"></i>
                            </div>
                            <div class="survey-kpi-value text-warning" id="kpi-total-peserta">—</div>
                            <div class="survey-kpi-label">Total Peserta Terdaftar</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="survey-kpi-card" id="survey-kpi-responden">
                            <div class="survey-kpi-icon" style="background:linear-gradient(135deg,#ef4444,#dc2626)">
                                <i class="ri-chat-check-line"></i>
                            </div>
                            <div class="survey-kpi-value text-danger" id="kpi-total-responden">—</div>
                            <div class="survey-kpi-label">Total Responden Mengisi</div>
                        </div>
                    </div>
                </div>

                {{-- Additional KPI row --}}
                <div class="row g-3 mb-4">
                    <div class="col-6 col-md-3">
                        <div class="survey-kpi-card">
                            <div class="survey-kpi-icon" style="background:linear-gradient(135deg,#8b5cf6,#7c3aed)">
                                <i class="ri-question-answer-line"></i>
                            </div>
                            <div class="survey-kpi-value" id="kpi-total-kuesioner">—</div>
                            <div class="survey-kpi-label">Total Pertanyaan Kuesioner</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="survey-kpi-card">
                            <div class="survey-kpi-icon" style="background:linear-gradient(135deg,#0ea5e9,#0284c7)">
                                <i class="ri-draft-line"></i>
                            </div>
                            <div class="survey-kpi-value text-info" id="kpi-survei-draft">—</div>
                            <div class="survey-kpi-label">Survei Draft</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="survey-kpi-card">
                            <div class="survey-kpi-icon" style="background:linear-gradient(135deg,#64748b,#475569)">
                                <i class="ri-flag-2-line"></i>
                            </div>
                            <div class="survey-kpi-value text-secondary" id="kpi-survei-selesai">—</div>
                            <div class="survey-kpi-label">Survei Selesai</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="survey-kpi-card">
                            <div class="survey-kpi-icon" style="background:linear-gradient(135deg,#f97316,#ea580c)">
                                <i class="ri-bar-chart-grouped-line"></i>
                            </div>
                            <div class="survey-kpi-value" id="kpi-partisipasi-pct">—</div>
                            <div class="survey-kpi-label">Tingkat Partisipasi</div>
                        </div>
                    </div>
                </div>

                {{-- Charts Row --}}
                <div class="row g-3">
                    {{-- Donut: Distribusi Status Survei --}}
                    <div class="col-12 col-md-4">
                        <div class="survey-chart-card">
                            <div class="survey-chart-title"><i class="ri-pie-chart-line me-1"></i>Distribusi Status Survei</div>
                            <div id="chart-survey-status-loading" class="text-secondary text-center py-3" style="font-size:13px;">Memuat data...</div>
                            <div id="chart-survey-status-wrap" style="display:none;text-align:center;">
                                <canvas id="chart-survey-status" height="220" style="max-width:240px;margin:0 auto;"></canvas>
                                <div id="chart-survey-status-legend" class="d-flex flex-wrap justify-content-center gap-2 mt-2" style="font-size:12px;"></div>
                            </div>
                        </div>
                    </div>

                    {{-- Bar: Top Survei by Peserta --}}
                    <div class="col-12 col-md-8">
                        <div class="survey-chart-card">
                            <div class="survey-chart-title"><i class="ri-trophy-line me-1"></i>Top Survei Berdasarkan Jumlah Peserta</div>
                            <div id="chart-top-survei-loading" class="text-secondary text-center py-3" style="font-size:13px;">Memuat data...</div>
                            <div id="chart-top-survei-wrap" style="display:none;width:100%;">
                                <canvas id="chart-top-survei" height="240"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Trend Chart Row --}}
                <div class="row g-3 mt-1">
                    <div class="col-12">
                        <div class="survey-chart-card">
                            <div class="survey-chart-title"><i class="ri-line-chart-line me-1"></i>Tren Pembuatan Survei (12 Bulan Terakhir)</div>
                            <div id="chart-trend-survei-loading" class="text-secondary text-center py-3" style="font-size:13px;">Memuat data...</div>
                            <div id="chart-trend-survei-wrap" style="display:none;width:100%;">
                                <canvas id="chart-trend-survei" height="120"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="explorer-detail-modal" tabindex="-1" aria-labelledby="explorer-detail-modal-label" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="explorer-detail-modal-label">Detail Alumni</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="explorer-detail-modal-body">
                <div class="text-secondary">Pilih alumni untuk melihat detail.</div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
    integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
    (function () {
        const summaryUrl = "{{ route('parja.alumni-dashboard.data.summary') }}";
        const detailBaseUrl = "{{ url('/parja/alumni-dashboard/data/provinsi') }}";
        const geojsonUrl = "{{ route('parja.alumni-dashboard.geojson') }}";
        const explorerUrl = "{{ route('parja.alumni-dashboard.data.alumni-explorer') }}";
        const explorerDetailBaseUrl = "{{ url('/parja/alumni-dashboard/data/alumni-explorer') }}";
        const explorerDapilOptionsUrl = "{{ route('parja.alumni-dashboard.data.alumni-explorer.dapil-options') }}";
        const filterYearEl = document.getElementById('filter-tahun-angkatan');
        const filterApprovalEl = document.getElementById('filter-approval-status');
        const applyFilterBtn = document.getElementById('btn-apply-filter');
        const resetFilterBtn = document.getElementById('btn-reset-filter');
        const mapStatusEl = document.getElementById('map-status');
        const generatedAtEl = document.getElementById('summary-generated-at');
        const topProvinceBody = document.getElementById('top-province-body');
        const detailBody = document.getElementById('province-detail-body');
        const indonesiaBounds = L.latLngBounds([
            [-12.5, 93.0],
            [8.5, 142.0]
        ]);

        let map;
        let geojsonLayer;
        let notesLayer;
        let provinceNameLayer;
        let islandLabelLayer;
        let selectedLayer;
        let selectedProvinceSlug = null;
        let selectedProvinceName = null;
        let activeDetailProvinceSlug = null;
        let explorerCurrentPage = 1;
        let explorerLastPage = 1;
        let explorerLoaded = false;
        let maxAlumni = 0;
        let summaryBySlug = {};
        let noteEligibleSlugs = new Set();
        let mapClickHandler = null;
        let colorQuantiles = { q1: 0, q2: 0, q3: 0, q4: 0 };

        const noteOffsets = {
            'aceh': { lat: 1.1, lng: -1.3 },
            'sumatra-utara': { lat: 0.9, lng: -0.9 },
            'sumatra-barat': { lat: -0.2, lng: -1.6 },
            'sumatra-selatan': { lat: -1.1, lng: -1.0 },
            'riau': { lat: 1.0, lng: 0.2 },
            'jambi': { lat: 0.8, lng: 0.8 },
            'bengkulu': { lat: -0.6, lng: -0.9 },
            'lampung': { lat: -1.0, lng: 1.0 },
            'kep-riau': { lat: 1.2, lng: 1.6 },
            'kep-bangka-belitung': { lat: 0.2, lng: 1.6 },
            'dki-jakarta': { lat: -0.9, lng: 1.3 },
            'banten': { lat: -0.8, lng: 0.8 },
            'jawa-barat': { lat: -0.9, lng: 1.7 },
            'jawa-tengah': { lat: -1.0, lng: 2.2 },
            'di-yogyakarta': { lat: -1.1, lng: 2.5 },
            'jawa-timur': { lat: -0.8, lng: 2.9 },
            'bali': { lat: -0.5, lng: 1.4 },
            'nusa-tenggara-barat': { lat: -0.6, lng: 1.8 },
            'nusa-tenggara-timur': { lat: -0.8, lng: 2.0 },
            'kalimantan-barat': { lat: 0.5, lng: -1.1 },
            'kalimantan-tengah': { lat: -0.5, lng: 0.1 },
            'kalimantan-selatan': { lat: -0.9, lng: 0.8 },
            'kalimantan-timur': { lat: 0.3, lng: 1.6 },
            'kalimantan-utara': { lat: 1.1, lng: 1.1 },
            'sulawesi-utara': { lat: 1.0, lng: 1.0 },
            'gorontalo': { lat: 0.8, lng: 0.4 },
            'sulawesi-tengah': { lat: 0.2, lng: 1.4 },
            'sulawesi-barat': { lat: -0.4, lng: -0.5 },
            'sulawesi-selatan': { lat: -0.9, lng: 0.8 },
            'sulawesi-tenggara': { lat: -0.8, lng: 1.8 },
            'maluku': { lat: 0.5, lng: 1.2 },
            'maluku-utara': { lat: 0.9, lng: 1.2 },
            'papua-barat': { lat: 0.6, lng: -1.2 },
            'papua-barat-daya': { lat: 0.2, lng: -1.4 },
            'papua': { lat: 0.2, lng: 1.8 },
            'papua-selatan': { lat: -0.8, lng: 1.6 },
            'papua-pegunungan': { lat: 0.1, lng: 0.8 },
            'papua-tengah': { lat: -0.3, lng: 0.4 }
        };

        const islandLabels = [
            { name: 'Sumatra', lat: -0.6, lng: 101.1 },
            { name: 'Jawa', lat: -7.3, lng: 110.0 },
            { name: 'Kalimantan', lat: 0.9, lng: 114.8 },
            { name: 'Sulawesi', lat: -1.1, lng: 121.6 },
            { name: 'Papua', lat: -4.3, lng: 138.2 },
            { name: 'Bali - Nusa Tenggara', lat: -8.5, lng: 118.2 },
            { name: 'Maluku', lat: -3.1, lng: 129.6 }
        ];

        const provinceAliases = {
            'daerah-istimewa-yogyakarta': 'di-yogyakarta',
            'd-i-yogyakarta': 'di-yogyakarta',
            'yogyakarta': 'di-yogyakarta',
            'jakarta': 'dki-jakarta',
            'kepulauan-riau': 'kep-riau',
            'kepulauan-bangka-belitung': 'kep-bangka-belitung',
            'bangka-belitung': 'kep-bangka-belitung',
            'papua-barat-daya': 'papua-barat-daya'
        };

        function slugify(value) {
            return String(value || '')
                .trim()
                .toLowerCase()
                .replace(/[^a-z0-9]+/g, '-')
                .replace(/^-+|-+$/g, '');
        }

        function canonicalProvinceSlug(rawName) {
            const slug = slugify(rawName);
            return provinceAliases[slug] || slug;
        }

        function activeFilters() {
            const filters = new URLSearchParams();
            if (filterYearEl.value) {
                filters.set('tahun_angkatan', filterYearEl.value);
            }
            if (filterApprovalEl.value && filterApprovalEl.value !== 'all') {
                filters.set('approval_status', filterApprovalEl.value);
            }
            return filters;
        }

        function buildSummaryUrl() {
            const params = activeFilters();
            return params.toString() ? `${summaryUrl}?${params.toString()}` : summaryUrl;
        }

        function buildDetailUrl(slug) {
            const params = activeFilters();
            const base = `${detailBaseUrl}/${encodeURIComponent(slug)}`;
            return params.toString() ? `${base}?${params.toString()}` : base;
        }

        function buildDapilDetailUrl(provinceSlug, dapilSlug, page = 1, perPage = 10) {
            const params = activeFilters();
            params.set('page', String(page));
            params.set('per_page', String(perPage));
            const base = `${detailBaseUrl}/${encodeURIComponent(provinceSlug)}/dapil/${encodeURIComponent(dapilSlug)}`;
            return `${base}?${params.toString()}`;
        }

        function buildExplorerUrl(payload) {
            const params = activeFilters();
            const safePayload = payload || {};

            Object.keys(safePayload).forEach(key => {
                const value = safePayload[key];
                if (value !== null && value !== undefined && String(value).trim() !== '') {
                    params.set(key, String(value));
                }
            });

            return `${explorerUrl}?${params.toString()}`;
        }

        function buildExplorerDapilOptionsUrl(provinceSlug) {
            const params = activeFilters();
            const safeProvinceSlug = String(provinceSlug || '').trim();
            if (safeProvinceSlug !== '') {
                params.set('province_slug', safeProvinceSlug);
            }

            return params.toString() ? `${explorerDapilOptionsUrl}?${params.toString()}` : explorerDapilOptionsUrl;
        }

        function buildExplorerDetailUrl(id) {
            return `${explorerDetailBaseUrl}/${encodeURIComponent(id)}`;
        }

        function showExplorerDetailModal(modalEl) {
            if (!modalEl) {
                return null;
            }

            if (window.bootstrap && window.bootstrap.Modal) {
                return window.bootstrap.Modal.getOrCreateInstance(modalEl);
            }

            modalEl.classList.add('show');
            modalEl.style.display = 'block';
            modalEl.removeAttribute('aria-hidden');
            modalEl.setAttribute('aria-modal', 'true');
            document.body.classList.add('modal-open');

            const backdrop = document.createElement('div');
            backdrop.className = 'modal-backdrop fade show';
            backdrop.dataset.explorerFallbackBackdrop = 'true';
            document.body.appendChild(backdrop);

            modalEl._explorerFallbackHide = function () {
                modalEl.classList.remove('show');
                modalEl.style.display = 'none';
                modalEl.setAttribute('aria-hidden', 'true');
                modalEl.removeAttribute('aria-modal');
                document.body.classList.remove('modal-open');
                const existingBackdrop = document.querySelector('[data-explorer-fallback-backdrop="true"]');
                if (existingBackdrop) {
                    existingBackdrop.remove();
                }
            };

            return {
                show: function () {},
                hide: function () {
                    if (typeof modalEl._explorerFallbackHide === 'function') {
                        modalEl._explorerFallbackHide();
                    }
                }
            };
        }

        function formatNumber(value) {
            return new Intl.NumberFormat('id-ID').format(Number(value || 0));
        }

        function escapeHtml(value) {
            return String(value || '')
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#39;');
        }

        function getProvinceNameFromFeature(feature) {
            const props = feature && feature.properties ? feature.properties : {};
            const candidates = [
                props.provinsi,
                props.PROVINSI,
                props.Propinsi,
                props.WADMPR,
                props.NAMOBJ,
                props.NAME_1,
                props.name,
                props.NAME
            ];

            for (const candidate of candidates) {
                if (candidate && String(candidate).trim() !== '') {
                    return String(candidate).trim();
                }
            }

            return 'Tanpa Nama';
        }

        function setGlobalStats(totals) {
            document.getElementById('stat-total-alumni').textContent = formatNumber(totals.alumni_aktif);
            document.getElementById('stat-approved').textContent = formatNumber(totals.alumni_approved);
            document.getElementById('stat-pending').textContent = formatNumber(totals.alumni_pending);
            document.getElementById('stat-rejected').textContent = formatNumber(totals.alumni_rejected);
        }

        function setTopProvince(rows) {
            const topRows = [...rows]
                .sort((a, b) => Number(b.total_alumni_aktif) - Number(a.total_alumni_aktif))
                .slice(0, 7);

            if (topRows.length === 0) {
                topProvinceBody.innerHTML = '<tr><td colspan="2" class="text-center text-secondary py-3">Belum ada data.</td></tr>';
                return;
            }

            topProvinceBody.innerHTML = topRows.map(row => `
                <tr>
                    <td class="ps-3">${escapeHtml(row.provinsi)}</td>
                    <td class="text-end pe-3 fw-semibold">${formatNumber(row.total_alumni_aktif)}</td>
                </tr>
            `).join('');
        }

        // Calculate quantile boundaries for fair color distribution
        function calculateQuantiles(values) {
            if (!values || values.length === 0) {
                return { q1: 0, q2: 0, q3: 0, q4: 0 };
            }
            const sorted = [...values].sort((a, b) => a - b);
            const len = sorted.length;
            const q1Index = Math.floor(len * 0.25);
            const q2Index = Math.floor(len * 0.5);
            const q3Index = Math.floor(len * 0.75);
            return {
                q1: sorted[q1Index],
                q2: sorted[q2Index],
                q3: sorted[q3Index],
                q4: sorted[len - 1]
            };
        }

        function fillColor(value) {
            if (value <= 0) return '#c7d4e5';  // Gray (no data)
            if (value <= colorQuantiles.q1) return '#f7dbe8';   // Lightest pink (Q1)
            if (value <= colorQuantiles.q2) return '#f3b8d0';   // Light-medium pink (Q2)
            if (value <= colorQuantiles.q3) return '#ea85b0';   // Medium-dark pink (Q3)
            return '#bf0050';  // Darkest red (Q4/max)
        }

        function createMapLegend() {
            const legend = L.control({ position: 'bottomright' });
            
            legend.onAdd = function (map) {
                const div = L.DomUtil.create('div', 'alumni-map-legend');
                div.innerHTML = `
                    <style>
                        .alumni-map-legend {
                            background: white;
                            padding: 12px 14px;
                            border-radius: 5px;
                            box-shadow: 0 0 8px rgba(0,0,0,0.2);
                            font-size: 12px;
                            line-height: 1.5;
                        }
                        .alumni-map-legend-title {
                            font-weight: bold;
                            margin-bottom: 8px;
                            font-size: 13px;
                        }
                        .alumni-map-legend-item {
                            display: flex;
                            align-items: center;
                            gap: 8px;
                            margin-bottom: 6px;
                        }
                        .alumni-map-legend-color {
                            width: 18px;
                            height: 18px;
                            border: 1px solid #ccc;
                            border-radius: 2px;
                        }
                        .alumni-map-legend-label {
                            color: #374151;
                        }
                    </style>
                    <div class="alumni-map-legend-title">Intensitas Alumni</div>
                    <div class="alumni-map-legend-item">
                        <div class="alumni-map-legend-color" style="background-color: #c7d4e5;"></div>
                        <div class="alumni-map-legend-label">Tidak ada data</div>
                    </div>
                    <div class="alumni-map-legend-item">
                        <div class="alumni-map-legend-color" style="background-color: #f7dbe8;"></div>
                        <div class="alumni-map-legend-label">0 - ${colorQuantiles.q1}</div>
                    </div>
                    <div class="alumni-map-legend-item">
                        <div class="alumni-map-legend-color" style="background-color: #f3b8d0;"></div>
                        <div class="alumni-map-legend-label">${colorQuantiles.q1 + 1} - ${colorQuantiles.q2}</div>
                    </div>
                    <div class="alumni-map-legend-item">
                        <div class="alumni-map-legend-color" style="background-color: #ea85b0;"></div>
                        <div class="alumni-map-legend-label">${colorQuantiles.q2 + 1} - ${colorQuantiles.q3}</div>
                    </div>
                    <div class="alumni-map-legend-item">
                        <div class="alumni-map-legend-color" style="background-color: #bf0050;"></div>
                        <div class="alumni-map-legend-label">${colorQuantiles.q3 + 1}+</div>
                    </div>
                `;
                return div;
            };
            
            return legend;
        }

        function styleFeature(feature) {
            const provinceName = getProvinceNameFromFeature(feature);
            const slug = canonicalProvinceSlug(provinceName);
            const value = Number((summaryBySlug[slug] || {}).total_alumni_aktif || 0);

            return {
                fillColor: fillColor(value),
                weight: 1,
                opacity: 1,
                color: '#94a3b8',
                dashArray: '',
                fillOpacity: 0.95
            };
        }

        function getProvinceCentroid(feature) {
            return L.geoJSON(feature).getBounds().getCenter();
        }

        function shouldRenderProvinceName(total) {
            return true;
        }

        function renderProvinceName(provinceName, total, centroid) {
            if (!provinceNameLayer || !shouldRenderProvinceName(total)) {
                return;
            }

            const labelIcon = L.divIcon({
                className: 'province-name-label',
                html: `<span class="province-name-chip">${provinceName}</span>`,
                iconSize: null
            });

            L.marker(centroid, {
                icon: labelIcon,
                interactive: false,
                keyboard: false,
                zIndexOffset: -200
            }).addTo(provinceNameLayer);
        }

        function renderIslandLabels() {
            if (!islandLabelLayer) {
                return;
            }

            islandLabels.forEach(item => {
                const icon = L.divIcon({
                    className: 'island-name-label',
                    html: `<span class="island-name-chip">${item.name}</span>`,
                    iconSize: null
                });

                L.marker([item.lat, item.lng], {
                    icon,
                    interactive: false,
                    keyboard: false,
                    zIndexOffset: -300
                }).addTo(islandLabelLayer);
            });
        }

        function isMobileViewport() {
            return window.matchMedia('(max-width: 992px)').matches;
        }

        function hashCode(value) {
            let hash = 0;
            const text = String(value || '');
            for (let i = 0; i < text.length; i += 1) {
                hash = ((hash << 5) - hash) + text.charCodeAt(i);
                hash |= 0;
            }
            return Math.abs(hash);
        }

        function getNoteOffset(slug) {
            if (noteOffsets[slug]) {
                return noteOffsets[slug];
            }

            // Fallback offset untuk menjaga label tetap tersebar saat tidak ada mapping khusus.
            const seed = hashCode(slug);
            const angle = (seed % 360) * (Math.PI / 180);
            const latRadius = 0.55 + ((seed % 7) * 0.06);
            const lngRadius = 0.95 + ((seed % 5) * 0.09);

            return {
                lat: Math.sin(angle) * latRadius,
                lng: Math.cos(angle) * lngRadius
            };
        }

        function shouldRenderStickyNote(slug) {
            if (!noteEligibleSlugs.has(slug)) {
                return false;
            }

            if (!isMobileViewport()) {
                return true;
            }

            return noteEligibleSlugs.has(slug);
        }

        function renderStickyNote(provinceName, slug, total, centroid) {
            if (!notesLayer || !shouldRenderStickyNote(slug)) {
                return;
            }

            const offset = getNoteOffset(slug);
            const noteLatLng = L.latLng(centroid.lat + offset.lat, centroid.lng + offset.lng);

            L.polyline([centroid, noteLatLng], {
                color: '#d00063',
                weight: 1.6,
                opacity: 0.9
            }).addTo(notesLayer);

            L.circleMarker(centroid, {
                radius: 4,
                color: '#ffffff',
                weight: 1,
                fillColor: '#d00063',
                fillOpacity: 1,
                className: 'sticky-anchor-dot'
            }).addTo(notesLayer);

            const noteIcon = L.divIcon({
                className: 'sticky-note-label',
                html: `<div class="sticky-note-card"><span class="sticky-note-name">${provinceName}</span><span class="sticky-note-value">${formatNumber(total)}</span></div>`,
                iconSize: null
            });

            L.marker(noteLatLng, {
                icon: noteIcon,
                interactive: false,
                keyboard: false
            }).addTo(notesLayer);
        }

        function renderDetailLoading(provinceName) {
            detailBody.innerHTML = `
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <h5 class="mb-0">${provinceName}</h5>
                    <span class="spinner-border spinner-border-sm text-primary" role="status" aria-hidden="true"></span>
                </div>
                <div class="text-secondary">Mengambil statistik provinsi...</div>
            `;
        }

        function renderDetail(payload) {
            const stats = payload.stats || {};
            const dapilBreakdown = Array.isArray(payload.dapil_breakdown) ? payload.dapil_breakdown : [];
            activeDetailProvinceSlug = payload.slug || null;
            const pekerjaanRows = (payload.top_pekerjaan || []).map(item =>
                `<li class="d-flex justify-content-between"><span>${escapeHtml(item.label)}</span><span class="fw-semibold">${formatNumber(item.total)}</span></li>`
            ).join('') || '<li class="text-secondary">Belum ada data pekerjaan.</li>';

            const pendidikanRows = (payload.top_pendidikan || []).map(item =>
                `<li class="d-flex justify-content-between"><span>${escapeHtml(item.label)}</span><span class="fw-semibold">${formatNumber(item.total)}</span></li>`
            ).join('') || '<li class="text-secondary">Belum ada data pendidikan.</li>';

            const dapilOptions = dapilBreakdown.map((item, idx) => {
                const label = `${item.dapil || '-'} (${formatNumber(item.total_alumni || 0)})`;
                const selected = idx === 0 ? 'selected' : '';
                return `<option value="${escapeHtml(item.slug || '')}" ${selected}>${escapeHtml(label)}</option>`;
            }).join('');

            const hasDapil = dapilBreakdown.length > 0;

            detailBody.innerHTML = `
                <div class="mb-2">
                    <h5 class="mb-1">${escapeHtml(payload.provinsi)}</h5>
                    <div class="fs-sm fw-medium">${escapeHtml(payload.provinsi)} : ${formatNumber(stats.total_alumni_aktif)} Alumni (Total jumlah Alumni)</div>
                    <div class="fs-xs text-secondary">Cakupan dapil: ${(payload.dapil || []).length} wilayah</div>
                </div>

                <div class="row g-2 mb-2">
                    <div class="col-6"><div class="border rounded p-2"><div class="fs-xs text-secondary">Total Alumni</div><div class="fw-bold">${formatNumber(stats.total_alumni_aktif)}</div></div></div>
                    <div class="col-6"><div class="border rounded p-2"><div class="fs-xs text-secondary">Approved</div><div class="fw-bold text-success">${formatNumber(stats.approved)}</div></div></div>
                    <div class="col-6"><div class="border rounded p-2"><div class="fs-xs text-secondary">Pending</div><div class="fw-bold text-warning">${formatNumber(stats.pending)}</div></div></div>
                    <div class="col-6"><div class="border rounded p-2"><div class="fs-xs text-secondary">Rejected</div><div class="fw-bold text-danger">${formatNumber(stats.rejected)}</div></div></div>
                    <div class="col-6"><div class="border rounded p-2"><div class="fs-xs text-secondary">Profil Lengkap</div><div class="fw-bold">${formatNumber(stats.profile_completed)}</div></div></div>
                    <div class="col-6"><div class="border rounded p-2"><div class="fs-xs text-secondary">Profil Belum Lengkap</div><div class="fw-bold">${formatNumber(stats.profile_incomplete)}</div></div></div>
                    <div class="col-4"><div class="border rounded p-2"><div class="fs-xs text-secondary">Post OK</div><div class="fw-bold text-success">${formatNumber(stats.post_approved)}</div></div></div>
                    <div class="col-4"><div class="border rounded p-2"><div class="fs-xs text-secondary">Post Pending</div><div class="fw-bold text-warning">${formatNumber(stats.post_pending)}</div></div></div>
                    <div class="col-4"><div class="border rounded p-2"><div class="fs-xs text-secondary">Post Tolak</div><div class="fw-bold text-danger">${formatNumber(stats.post_rejected)}</div></div></div>
                </div>

                <div class="mb-2">
                    <div class="fw-semibold mb-1">Top 5 Pekerjaan</div>
                    <ul class="list-unstyled mb-0 fs-sm">${pekerjaanRows}</ul>
                </div>

                <div>
                    <div class="fw-semibold mb-1">Top 5 Pendidikan</div>
                    <ul class="list-unstyled mb-0 fs-sm">${pendidikanRows}</ul>
                </div>

                <hr class="my-2">

                <div>
                    <div class="fw-semibold mb-1">Daftar Alumni per Dapil</div>
                    ${hasDapil ? `
                        <div class="row g-2 align-items-end mb-2">
                            <div class="col-md-8 col-12">
                                <label class="form-label fs-xs text-secondary mb-1" for="detail-dapil-select">Pilih Dapil</label>
                                <select class="form-select form-select-sm" id="detail-dapil-select">
                                    ${dapilOptions}
                                </select>
                            </div>
                            <div class="col-md-4 col-12 d-grid">
                                <button type="button" class="btn btn-sm btn-primary" id="detail-dapil-filter-btn">Filter Alumni</button>
                            </div>
                        </div>
                        <div class="table-responsive border rounded">
                            <table class="table table-sm mb-0">
                                <thead>
                                    <tr>
                                        <th style="width: 70%">Nama Alumni</th>
                                        <th class="text-end" style="width: 18%">Tahun</th>
                                        <th class="text-end pe-3" style="width: 12%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="detail-dapil-table-body">
                                    <tr><td colspan="3" class="text-center text-secondary py-3">Pilih dapil lalu klik <strong>Filter Alumni</strong>.</td></tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mt-2">
                            <small class="text-secondary" id="detail-dapil-page-info">-</small>
                            <div class="btn-group btn-group-sm" role="group" aria-label="Navigasi halaman alumni per dapil">
                                <button type="button" class="btn btn-outline-secondary" id="detail-dapil-prev" disabled>Sebelumnya</button>
                                <button type="button" class="btn btn-outline-secondary" id="detail-dapil-next" disabled>Berikutnya</button>
                            </div>
                        </div>
                    ` : '<div class="text-secondary">Belum ada data dapil.</div>'}
                </div>
            `;

            if (!hasDapil || !activeDetailProvinceSlug) {
                return;
            }

            const dapilSelectEl = document.getElementById('detail-dapil-select');
            const dapilFilterBtnEl = document.getElementById('detail-dapil-filter-btn');
            const tableBodyEl = document.getElementById('detail-dapil-table-body');
            const pageInfoEl = document.getElementById('detail-dapil-page-info');
            const prevBtnEl = document.getElementById('detail-dapil-prev');
            const nextBtnEl = document.getElementById('detail-dapil-next');
            const perPage = 5;
            let currentPage = 1;
            let hasLoaded = false;

            async function loadDapilAlumni(page) {
                const dapilSlug = dapilSelectEl.value;

                dapilFilterBtnEl.disabled = true;
                dapilFilterBtnEl.textContent = 'Memuat...';
                tableBodyEl.innerHTML = '<tr><td colspan="3" class="text-center text-secondary py-3">Memuat data alumni...</td></tr>';

                try {
                    const response = await fetch(buildDapilDetailUrl(activeDetailProvinceSlug, dapilSlug, page, perPage), {
                        headers: { 'Accept': 'application/json' }
                    });
                    const result = await response.json();

                    if (!response.ok || !result.success) {
                        tableBodyEl.innerHTML = '<tr><td colspan="3" class="text-center text-warning py-3">Gagal memuat data alumni dapil.</td></tr>';
                        pageInfoEl.textContent = '-';
                        prevBtnEl.disabled = true;
                        nextBtnEl.disabled = true;
                        return;
                    }

                    const rows = (result.data && result.data.rows) || [];
                    const pagination = (result.data && result.data.pagination) || {};
                    currentPage = Number(pagination.current_page || 1);
                    const lastPage = Number(pagination.last_page || 1);
                    const total = Number(pagination.total || 0);

                    if (rows.length === 0) {
                        tableBodyEl.innerHTML = '<tr><td colspan="3" class="text-center text-secondary py-3">Belum ada data alumni.</td></tr>';
                    } else {
                        tableBodyEl.innerHTML = rows.map(row => `
                            <tr>
                                <td>${escapeHtml(row.nama || '-')}</td>
                                <td class="text-end">${escapeHtml(row.tahun_angkatan || '-')}</td>
                                <td class="text-end pe-3">
                                    <button type="button" class="btn btn-sm btn-outline-primary explorer-detail-btn" data-explorer-id="${Number(row.id || 0)}">
                                        Lihat
                                    </button>
                                </td>
                            </tr>
                        `).join('');

                        tableBodyEl.querySelectorAll('[data-explorer-id]').forEach(button => {
                            button.addEventListener('click', function (event) {
                                event.preventDefault();
                                event.stopPropagation();

                                const alumniId = Number(button.getAttribute('data-explorer-id') || 0);
                                if (alumniId > 0) {
                                    openExplorerDetail(alumniId);
                                }
                            });
                        });
                    }

                    pageInfoEl.textContent = `Halaman ${currentPage} dari ${lastPage} • Total ${formatNumber(total)} alumni`;
                    prevBtnEl.disabled = currentPage <= 1;
                    nextBtnEl.disabled = currentPage >= lastPage;
                    hasLoaded = true;
                } catch (error) {
                    tableBodyEl.innerHTML = '<tr><td colspan="3" class="text-center text-warning py-3">Gagal memuat data alumni dapil.</td></tr>';
                    pageInfoEl.textContent = '-';
                    prevBtnEl.disabled = true;
                    nextBtnEl.disabled = true;
                } finally {
                    dapilFilterBtnEl.disabled = false;
                    dapilFilterBtnEl.textContent = 'Filter Alumni';
                }
            }

            dapilSelectEl.addEventListener('change', function () {
                currentPage = 1;
                hasLoaded = false;
                tableBodyEl.innerHTML = '<tr><td colspan="3" class="text-center text-secondary py-3">Pilih dapil lalu klik <strong>Filter Alumni</strong>.</td></tr>';
                pageInfoEl.textContent = '-';
                prevBtnEl.disabled = true;
                nextBtnEl.disabled = true;
            });

            dapilFilterBtnEl.addEventListener('click', function () {
                currentPage = 1;
                loadDapilAlumni(currentPage);
            });

            prevBtnEl.addEventListener('click', function () {
                if (hasLoaded && currentPage > 1) {
                    loadDapilAlumni(currentPage - 1);
                }
            });

            nextBtnEl.addEventListener('click', function () {
                if (hasLoaded) {
                    loadDapilAlumni(currentPage + 1);
                }
            });
        }

        function renderDetailFallback(provinceName) {
            renderDetail({
                provinsi: provinceName,
                slug: canonicalProvinceSlug(provinceName),
                dapil: [],
                stats: {
                    total_alumni_aktif: 0,
                    approved: 0,
                    pending: 0,
                    rejected: 0,
                    profile_completed: 0,
                    profile_incomplete: 0,
                    post_approved: 0,
                    post_pending: 0,
                    post_rejected: 0
                },
                top_pekerjaan: [],
                top_pendidikan: [],
                dapil_breakdown: []
            });
        }

        function renderDetailError(message) {
            detailBody.innerHTML = `<div class="alert alert-warning mb-0">${message}</div>`;
        }

        async function loadDetail(slug, provinceName) {
            renderDetailLoading(provinceName);

            try {
                const response = await fetch(buildDetailUrl(slug), {
                    headers: { 'Accept': 'application/json' }
                });

                const payload = await response.json();

                if (!response.ok || !payload.success) {
                    renderDetailFallback(provinceName);
                    return;
                }

                renderDetail(payload.data || {});
            } catch (error) {
                renderDetailError('Gagal mengambil detail provinsi. Silakan coba kembali.');
            }
        }

        function highlightLayer(layer, provinceName, slug) {
            if (selectedLayer) {
                geojsonLayer.resetStyle(selectedLayer);
            }

            selectedLayer = layer;
            selectedProvinceSlug = slug;
            selectedProvinceName = provinceName;
            layer.setStyle({
                weight: 2.6,
                color: '#7c0037',
                fillOpacity: 1
            });

            loadDetail(slug, provinceName);
        }

        function initMap() {
            map = L.map('alumni-map', {
                zoomControl: true,
                attributionControl: false,
                minZoom: 4,
                maxZoom: 8,
                maxBounds: indonesiaBounds,
                maxBoundsViscosity: 1.0,
                zoomSnap: 0.5,
                zoomDelta: 0.5
            }).setView([-2.2, 118], 4.5);

            notesLayer = L.layerGroup().addTo(map);
            provinceNameLayer = L.layerGroup().addTo(map);
            islandLabelLayer = L.layerGroup().addTo(map);
        }

        function applyFilterOptions(summaryData) {
            const years = (summaryData.filter_options && summaryData.filter_options.tahun_angkatan) || [];
            const activeYear = (summaryData.filters && summaryData.filters.tahun_angkatan) || '';
            const activeStatus = summaryData.filters && summaryData.filters.approval_status !== null
                ? String(summaryData.filters.approval_status)
                : 'all';

            const options = ['<option value="">Semua Tahun</option>'];
            years.forEach(item => {
                const value = String(item.value || '');
                const selected = value === activeYear ? 'selected' : '';
                options.push(`<option value="${value}" ${selected}>${item.label}</option>`);
            });
            filterYearEl.innerHTML = options.join('');
            filterApprovalEl.value = activeStatus;
        }

        function resetDetailPanel() {
            detailBody.innerHTML = '<div class="text-secondary">Pilih provinsi pada peta untuk menampilkan statistik detail.</div>';
            selectedLayer = null;
            selectedProvinceSlug = null;
            selectedProvinceName = null;
            activeDetailProvinceSlug = null;
        }

        function approvalText(status) {
            const value = Number(status);
            if (value === 1) return 'Approved';
            if (value === 2) return 'Rejected';
            return 'Pending';
        }

        function approvalBadgeClass(status) {
            const value = Number(status);
            if (value === 1) return 'explorer-badge-status-approved';
            if (value === 2) return 'explorer-badge-status-rejected';
            return 'explorer-badge-status-pending';
        }

        function profileBadgeClass(flag) {
            return Number(flag) === 1 ? 'explorer-badge-profile-complete' : 'explorer-badge-profile-incomplete';
        }

        function explorerFallbackAvatar() {
            const svg = `
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 96 96" role="img" aria-label="Default Avatar">
                    <defs>
                        <linearGradient id="avatarBg" x1="0" y1="0" x2="1" y2="1">
                            <stop offset="0%" stop-color="#e2e8f0" />
                            <stop offset="100%" stop-color="#cbd5e1" />
                        </linearGradient>
                    </defs>
                    <rect width="96" height="96" rx="14" fill="url(#avatarBg)"/>
                    <circle cx="48" cy="35" r="14" fill="#94a3b8"/>
                    <path d="M22 80c2-15 12-24 26-24s24 9 26 24" fill="#94a3b8"/>
                </svg>
            `;

            return `data:image/svg+xml;charset=UTF-8,${encodeURIComponent(svg)}`;
        }

        function resolveExplorerPhoto(path) {
            const value = String(path || '').trim().replace(/\\/g, '/');
            if (!value) {
                return explorerFallbackAvatar();
            }
            if (/^https?:\/\//i.test(value) || value.startsWith('data:')) {
                return value;
            }

            if (value.startsWith('/storage/')) {
                return value;
            }

            if (value.startsWith('storage/')) {
                return `/${value}`;
            }

            if (value.startsWith('/')) {
                return value;
            }

            const normalizedPath = value.startsWith('public/') ? value.slice(7) : value;
            return `/storage/${normalizedPath.replace(/^\/+/, '')}`;
        }

        function renderExplorerCards(rows) {
            const listEl = document.getElementById('explorer-card-list');
            if (!listEl) {
                return;
            }

            const fallbackAvatar = explorerFallbackAvatar();

            if (!Array.isArray(rows) || rows.length === 0) {
                listEl.innerHTML = '<div class="col-12 text-center text-secondary py-3 border rounded">Belum ada alumni sesuai filter.</div>';
                return;
            }

            listEl.innerHTML = rows.map(row => `
                <div class="col-12 col-xl-6">
                    <div class="explorer-card explorer-card-trigger h-100" data-explorer-id="${Number(row.id || 0)}" role="button" tabindex="0" aria-label="Buka detail alumni ${escapeHtml(row.nama || '')}">
                        <div class="d-flex gap-2">
                            <img src="${escapeHtml(resolveExplorerPhoto(row.foto_profil))}" class="explorer-avatar" alt="${escapeHtml(row.nama || 'Alumni')}" onerror="this.onerror=null;this.src='${escapeHtml(fallbackAvatar)}'">
                            <div class="flex-grow-1">
                                <div class="explorer-name">${escapeHtml(row.nama || '-')}</div>
                                <div class="explorer-meta mb-1">${escapeHtml(row.dapil || '-')} • Angkatan ${escapeHtml(row.tahun_angkatan || '-')}</div>
                                <span class="explorer-badge ${approvalBadgeClass(row.approval_status)}">${escapeHtml(approvalText(row.approval_status))}</span>
                                <span class="explorer-badge ${profileBadgeClass(row.profile_completed)}">${Number(row.profile_completed) === 1 ? 'Profil Lengkap' : 'Profil Belum Lengkap'}</span>
                                <div class="explorer-meta mt-1"><strong>Pekerjaan:</strong> ${escapeHtml(row.pekerjaan_saat_ini || '-')}</div>
                                <div class="explorer-meta"><strong>Pendidikan:</strong> ${escapeHtml(row.pendidikan_saat_ini || '-')}</div>
                                <div class="explorer-meta"><strong>Domisili:</strong> ${escapeHtml(row.domisili_terakhir || '-')}</div>
                                <div class="explorer-meta"><strong>Email:</strong> ${escapeHtml(row.email || '-')}</div>
                                <div class="explorer-meta mt-1 explorer-bio">${escapeHtml(row.bio || '-')}</div>
                                <button type="button" class="explorer-detail-btn" data-explorer-id="${Number(row.id || 0)}">Lihat Selengkapnya</button>
                            </div>
                        </div>
                    </div>
                </div>
            `).join('');
        }

        function renderTimelineItems(items) {
            if (!Array.isArray(items) || items.length === 0) {
                return '<div class="text-secondary">Belum ada riwayat organisasi.</div>';
            }

            return `<div class="explorer-timeline">${items.map(item => `
                <div class="explorer-timeline-item">
                    <div class="explorer-timeline-period">${escapeHtml(item.period || '-')}</div>
                    <div class="explorer-timeline-title">${escapeHtml(item.title || '-')}</div>
                    <div class="explorer-timeline-subtitle">${escapeHtml(item.subtitle || '-')}</div>
                </div>
            `).join('')}</div>`;
        }

        function renderSingleTimeline(period, title, subtitle) {
            return `
                <div class="explorer-timeline">
                    <div class="explorer-timeline-item mb-0">
                        <div class="explorer-timeline-period">${escapeHtml(period || '-')}</div>
                        <div class="explorer-timeline-title">${escapeHtml(title || '-')}</div>
                        <div class="explorer-timeline-subtitle">${escapeHtml(subtitle || '-')}</div>
                    </div>
                </div>
            `;
        }

        function normalizeExplorerList(value) {
            if (value == null) {
                return [];
            }

            if (typeof value === 'string') {
                const raw = value.trim();
                if (!raw) {
                    return [];
                }

                try {
                    const parsed = JSON.parse(raw);
                    return normalizeExplorerList(parsed);
                } catch (error) {
                    return raw
                        .split(/\s*[,;|]\s*/)
                        .map(item => item.trim())
                        .filter(Boolean);
                }
            }

            if (Array.isArray(value)) {
                return value.flatMap(item => {
                    if (item == null) {
                        return [];
                    }

                    if (typeof item === 'string') {
                        const text = item.trim();
                        if (!text) {
                            return [];
                        }

                        try {
                            const parsed = JSON.parse(text);
                            return normalizeExplorerList(parsed);
                        } catch (error) {
                            return [text];
                        }
                    }

                    if (typeof item === 'object') {
                        const candidate = item.label ?? item.name ?? item.judul ?? item.value ?? item.text;
                        if (candidate != null && String(candidate).trim() !== '') {
                            return [String(candidate).trim()];
                        }

                        return normalizeExplorerList(Object.values(item));
                    }

                    const text = String(item).trim();
                    return text ? [text] : [];
                }).filter(Boolean);
            }

            if (typeof value === 'object') {
                return normalizeExplorerList(Object.values(value));
            }

            const text = String(value).trim();
            return text ? [text] : [];
        }

        function renderDetailModalBody(data) {
            const modalBody = document.getElementById('explorer-detail-modal-body');
            if (!modalBody) {
                return;
            }

            const medsos = Array.isArray(data.akun_medsos) ? data.akun_medsos : [];
            const ketertarikan = normalizeExplorerList(data.ketertarikan_bidang);

            const medsosHtml = medsos.length > 0
                ? medsos.map(item => `<a href="${escapeHtml(item.url)}" target="_blank" rel="noopener" class="explorer-detail-chip">${escapeHtml(item.label)}</a>`).join('')
                : '<span class="text-secondary">Belum ada akun media sosial.</span>';

            const ketertarikanHtml = ketertarikan.length > 0
                ? ketertarikan.map(item => `<span class="explorer-detail-chip explorer-detail-chip--soft">${escapeHtml(item)}</span>`).join('')
                : '<span class="text-secondary">Belum ada bidang ketertarikan.</span>';

            const statusBadge = `<span class="explorer-badge ${approvalBadgeClass(data.approval_status)}">${escapeHtml(approvalText(data.approval_status))}</span>`;
            const profileBadge = `<span class="explorer-badge ${profileBadgeClass(data.profile_completed)}">${Number(data.profile_completed) === 1 ? 'Profil Lengkap' : 'Profil Belum Lengkap'}</span>`;

            modalBody.innerHTML = `
                <div class="row g-3">
                    <div class="col-12">
                        <div class="explorer-detail-hero">
                            <div class="row g-3 align-items-start">
                                <div class="col-12 col-lg-8">
                                    <div class="d-flex gap-3 align-items-start">
                                        <img src="${escapeHtml(resolveExplorerPhoto(data.foto_profil))}" class="explorer-detail-avatar" alt="${escapeHtml(data.nama || 'Alumni')}" onerror="this.onerror=null;this.src='${escapeHtml(explorerFallbackAvatar())}'">
                                        <div class="flex-grow-1">
                                            <h4 class="explorer-detail-title">${escapeHtml(data.nama || '-')}</h4>
                                            <div class="explorer-detail-subtitle mb-2">${escapeHtml(data.dapil || '-')} • Angkatan ${escapeHtml(data.tahun_angkatan || '-')}</div>
                                            ${statusBadge}
                                            ${profileBadge}
                                            <div class="explorer-meta mt-2"><strong>Bio Singkat:</strong> ${escapeHtml(data.bio || '-')}</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 col-lg-4">
                                    <div class="explorer-detail-contact">
                                        <div class="explorer-detail-contact-label">Kontak</div>
                                        <div class="explorer-detail-contact-value">${escapeHtml(data.email || '-')}</div>

                                        <div class="explorer-detail-contact-label">Domisili</div>
                                        <div class="explorer-detail-contact-value">${escapeHtml(data.domisili_terakhir || '-')}</div>

                                        <div class="explorer-detail-contact-label">Media Sosial</div>
                                        <div>${medsosHtml}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 explorer-detail-tabbar-wrap">
                        <div class="explorer-detail-tabbar" role="tablist" aria-label="Navigasi Detail Alumni">
                            <button type="button" class="explorer-detail-tab is-active" data-detail-tab="profile"><i class="ri-user-3-line"></i>Profile & Riwayat</button>
                            <button type="button" class="explorer-detail-tab" data-detail-tab="pendidikan"><i class="ri-graduation-cap-line"></i>Pendidikan</button>
                            <button type="button" class="explorer-detail-tab" data-detail-tab="pekerjaan"><i class="ri-briefcase-4-line"></i>Pekerjaan</button>
                            <button type="button" class="explorer-detail-tab" data-detail-tab="organisasi"><i class="ri-team-line"></i>Organisasi</button>
                        </div>
                    </div>

                    <div class="col-12 explorer-detail-panes">
                        <!-- Pane: Profile & Riwayat -->
                        <div class="explorer-detail-pane is-active" data-detail-pane="profile">
                            <div class="row g-3">
                                <div class="col-12 col-lg-6" id="detail-section-profile">
                                    <div class="explorer-detail-card explorer-detail-card-profile is-section-active h-100">
                                        <div class="card-body">
                                            <h6 class="explorer-detail-section-title"><i class="ri-id-card-line"></i>Profile & Riwayat</h6>
                                            <div class="explorer-meta mb-1"><strong>Pendidikan:</strong> ${escapeHtml(data.pendidikan_saat_ini || '-')}</div>
                                            <div class="explorer-meta mb-1"><strong>Pekerjaan:</strong> ${escapeHtml(data.pekerjaan_saat_ini || '-')}</div>
                                            <div class="explorer-meta mb-1"><strong>Domisili:</strong> ${escapeHtml(data.domisili_terakhir || '-')}</div>
                                            <div class="explorer-meta mb-1"><strong>Email:</strong> ${escapeHtml(data.email || '-')}</div>
                                            <div class="explorer-meta mb-0"><strong>Bio:</strong> ${escapeHtml(data.bio || '-')}</div>
                                            <hr>
                                            <div class="explorer-meta mb-0"><strong>Prestasi Terbaru:</strong> ${escapeHtml(data.prestasi_terbaru || '-')}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-lg-6">
                                    <div class="explorer-detail-card h-100">
                                        <div class="card-body">
                                            <h6 class="mb-2">Bidang Ketertarikan</h6>
                                            ${ketertarikanHtml}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Pane: Pendidikan -->
                        <div class="explorer-detail-pane" data-detail-pane="pendidikan">
                            <div class="explorer-detail-card explorer-detail-card-pendidikan is-section-active" id="detail-section-pendidikan">
                                <div class="card-body">
                                    <h6 class="explorer-detail-section-title"><i class="ri-book-open-line"></i>Riwayat Pendidikan</h6>
                                    ${renderSingleTimeline(data.tahun_angkatan, data.pendidikan_saat_ini, 'Data pendidikan dari profil alumni')}
                                </div>
                            </div>
                        </div>

                        <!-- Pane: Pekerjaan -->
                        <div class="explorer-detail-pane" data-detail-pane="pekerjaan">
                            <div class="explorer-detail-card explorer-detail-card-pekerjaan is-section-active" id="detail-section-pekerjaan">
                                <div class="card-body">
                                    <h6 class="explorer-detail-section-title"><i class="ri-building-2-line"></i>Riwayat Pekerjaan</h6>
                                    ${renderSingleTimeline(data.tahun_angkatan, data.pekerjaan_saat_ini, 'Data pekerjaan dari profil alumni')}
                                </div>
                            </div>
                        </div>

                        <!-- Pane: Organisasi -->
                        <div class="explorer-detail-pane" data-detail-pane="organisasi">
                            <div class="explorer-detail-card explorer-detail-card-organisasi is-section-active" id="detail-section-organisasi">
                                <div class="card-body">
                                    <h6 class="explorer-detail-section-title"><i class="ri-community-line"></i>Riwayat Pengalaman Organisasi</h6>
                                    ${renderTimelineItems(data.timeline_organisasi || [])}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }

        async function openExplorerDetail(alumniId) {
            const modalEl = document.getElementById('explorer-detail-modal');
            const modalBody = document.getElementById('explorer-detail-modal-body');

            if (!modalEl || !modalBody || !alumniId) {
                return;
            }

            const bsModal = showExplorerDetailModal(modalEl);

            modalBody.innerHTML = '<div class="text-secondary">Memuat detail alumni...</div>';
            if (bsModal && typeof bsModal.show === 'function') {
                bsModal.show();
            }

            try {
                const response = await fetch(buildExplorerDetailUrl(alumniId), {
                    headers: { 'Accept': 'application/json' }
                });
                const payload = await response.json();

                if (!response.ok || !payload.success) {
                    modalBody.innerHTML = '<div class="alert alert-warning mb-0">Gagal memuat detail alumni.</div>';
                    return;
                }

                renderDetailModalBody(payload.data || {});
                modalBody.scrollTop = 0;
                setActiveDetailTab('profile');
            } catch (error) {
                modalBody.innerHTML = '<div class="alert alert-warning mb-0">Gagal memuat detail alumni.</div>';
            }
        }

        window.openExplorerDetail = openExplorerDetail;

        function setExplorerIdleState(message) {
            const summaryEl = document.getElementById('explorer-summary');
            const listEl = document.getElementById('explorer-card-list');
            const prevBtnEl = document.getElementById('explorer-prev');
            const nextBtnEl = document.getElementById('explorer-next');

            const text = message || 'Atur filter lalu klik Cari.';
            if (summaryEl) {
                summaryEl.textContent = text;
            }

            if (listEl) {
                listEl.innerHTML = '<div class="col-12 text-center text-secondary py-3 border rounded">Pilih filter lalu klik tombol Cari untuk menampilkan alumni.</div>';
            }

            if (prevBtnEl) {
                prevBtnEl.disabled = true;
            }
            if (nextBtnEl) {
                nextBtnEl.disabled = true;
            }

            explorerCurrentPage = 1;
            explorerLastPage = 1;
            explorerLoaded = false;
        }

        function setExplorerProvinceOptions(rows) {
            const provinceEl = document.getElementById('explorer-province');
            if (!provinceEl) {
                return;
            }

            const currentValue = String(provinceEl.value || '');
            const options = ['<option value="">Semua Provinsi</option>'];

            (rows || []).forEach(item => {
                const slug = String(item.slug || '');
                const selected = slug === currentValue ? 'selected' : '';
                options.push(`<option value="${escapeHtml(slug)}" ${selected}>${escapeHtml(item.provinsi || '-')} (${formatNumber(item.total_alumni_aktif || 0)})</option>`);
            });

            provinceEl.innerHTML = options.join('');
        }

        function renderExplorerDapilOptions(dapils) {
            const dapilEl = document.getElementById('explorer-dapil');
            if (!dapilEl) {
                return;
            }

            const currentValue = String(dapilEl.value || '');
            const options = ['<option value="">Semua Dapil</option>'];

            (dapils || []).forEach(item => {
                const slug = String(item.slug || '');
                const total = item.total_alumni;
                const label = total === null || total === undefined
                    ? `${item.dapil}`
                    : `${item.dapil} (${formatNumber(total)})`;
                const selected = slug === currentValue ? 'selected' : '';
                options.push(`<option value="${escapeHtml(slug)}" ${selected}>${escapeHtml(label)}</option>`);
            });

            dapilEl.innerHTML = options.join('');
        }

        async function loadExplorerDapilOptions(provinceSlug) {
            const dapilEl = document.getElementById('explorer-dapil');
            if (!dapilEl) {
                return;
            }

            dapilEl.innerHTML = '<option value="">Memuat dapil...</option>';

            try {
                const response = await fetch(buildExplorerDapilOptionsUrl(provinceSlug), {
                    headers: { 'Accept': 'application/json' }
                });
                const payload = await response.json();

                if (!response.ok || !payload.success) {
                    renderExplorerDapilOptions([]);
                    return;
                }

                const data = payload.data || {};
                renderExplorerDapilOptions(data.dapil_options || []);
            } catch (error) {
                renderExplorerDapilOptions([]);
            }
        }

        async function loadExplorer(page = 1) {
            const searchEl = document.getElementById('explorer-search');
            const provinceEl = document.getElementById('explorer-province');
            const dapilEl = document.getElementById('explorer-dapil');
            const filterBtnEl = document.getElementById('explorer-filter-btn');
            const prevBtnEl = document.getElementById('explorer-prev');
            const nextBtnEl = document.getElementById('explorer-next');
            const summaryEl = document.getElementById('explorer-summary');
            const listEl = document.getElementById('explorer-card-list');

            if (!searchEl || !provinceEl || !dapilEl || !filterBtnEl || !prevBtnEl || !nextBtnEl || !summaryEl || !listEl) {
                return;
            }

            filterBtnEl.disabled = true;
            filterBtnEl.textContent = 'Memuat...';
            listEl.innerHTML = '<div class="col-12 text-center text-secondary py-3 border rounded">Memuat data alumni...</div>';

            try {
                const response = await fetch(buildExplorerUrl({
                    q: searchEl.value,
                    province_slug: provinceEl.value,
                    dapil_slug: dapilEl.value,
                    domisili: (document.getElementById('explorer-domisili') || {}).value || '',
                    page: page,
                    per_page: 10,
                }), {
                    headers: { 'Accept': 'application/json' }
                });

                const payload = await response.json();

                if (!response.ok || !payload.success) {
                    listEl.innerHTML = '<div class="col-12 text-center text-warning py-3 border rounded">Gagal memuat data alumni.</div>';
                    summaryEl.textContent = '-';
                    prevBtnEl.disabled = true;
                    nextBtnEl.disabled = true;
                    return;
                }

                const data = payload.data || {};
                renderExplorerCards(data.rows || []);

                const pagination = data.pagination || {};
                explorerCurrentPage = Number(pagination.current_page || 1);
                explorerLastPage = Number(pagination.last_page || 1);
                const total = Number(pagination.total || 0);

                summaryEl.textContent = `Menampilkan ${formatNumber(total)} alumni • Halaman ${explorerCurrentPage}/${explorerLastPage}`;
                prevBtnEl.disabled = explorerCurrentPage <= 1;
                nextBtnEl.disabled = explorerCurrentPage >= explorerLastPage;
                explorerLoaded = true;
            } catch (error) {
                listEl.innerHTML = '<div class="col-12 text-center text-warning py-3 border rounded">Gagal memuat data alumni.</div>';
                summaryEl.textContent = '-';
                prevBtnEl.disabled = true;
                nextBtnEl.disabled = true;
            } finally {
                filterBtnEl.disabled = false;
                filterBtnEl.textContent = 'Cari';
            }
        }

        async function loadSummaryAndMap() {
            try {
                mapStatusEl.className = 'alert alert-info py-2 px-3 mb-2';
                mapStatusEl.textContent = 'Memuat ringkasan dashboard alumni...';

                const summaryResponse = await fetch(buildSummaryUrl(), {
                    headers: { 'Accept': 'application/json' }
                });
                const summaryPayload = await summaryResponse.json();

                if (!summaryResponse.ok || !summaryPayload.success) {
                    mapStatusEl.className = 'alert alert-warning py-2 px-3 mb-2';
                    mapStatusEl.textContent = summaryPayload.message || 'Gagal memuat ringkasan dashboard.';
                    return;
                }

                const summaryData = summaryPayload.data || {};
                const summaryRows = summaryData.rows || [];

                setExplorerProvinceOptions(summaryRows);
                await loadExplorerDapilOptions((document.getElementById('explorer-province') || {}).value || '');

                applyFilterOptions(summaryData);

                summaryBySlug = {};

                summaryRows.forEach(row => {
                    const canonicalSlug = canonicalProvinceSlug(row.slug || row.provinsi);
                    summaryBySlug[canonicalSlug] = row;
                });

                // Per atasan 18-05-2026 #2: tampilkan SEMUA provinsi/dapil dari master DB
                // termasuk yang belum punya alumni (count=0). Sebelumnya filter > 0 menyembunyikan
                // sticky note untuk provinsi kosong; sekarang semua eligible, sticky note akan
                // menampilkan "0" untuk provinsi tanpa alumni.
                const sortedByTotal = [...summaryRows]
                    .sort((a, b) => Number(b.total_alumni_aktif || 0) - Number(a.total_alumni_aktif || 0));
                const stickyLimit = isMobileViewport() ? 14 : sortedByTotal.length;
                noteEligibleSlugs = new Set(
                    sortedByTotal
                        .slice(0, stickyLimit)
                        .map(row => canonicalProvinceSlug(row.slug || row.provinsi))
                );

                maxAlumni = summaryRows.reduce((max, row) => Math.max(max, Number(row.total_alumni_aktif || 0)), 0);

                // Calculate quantiles from non-zero alumni counts for fair color distribution
                const nonZeroValues = summaryRows
                    .map(row => Number(row.total_alumni_aktif || 0))
                    .filter(val => val > 0);
                colorQuantiles = calculateQuantiles(nonZeroValues);

                setGlobalStats(summaryData.totals || {});
                setTopProvince(summaryRows);

                if (summaryData.generated_at) {
                    const generated = new Date(summaryData.generated_at);
                    generatedAtEl.textContent = `Update: ${generated.toLocaleString('id-ID')}`;
                } else {
                    generatedAtEl.textContent = 'Update: -';
                }

                if (geojsonLayer) {
                    map.removeLayer(geojsonLayer);
                    geojsonLayer = null;
                }

                if (notesLayer) {
                    notesLayer.clearLayers();
                }

                if (provinceNameLayer) {
                    provinceNameLayer.clearLayers();
                }

                if (islandLabelLayer) {
                    islandLabelLayer.clearLayers();
                }

                selectedLayer = null;

                const geojsonResponse = await fetch(geojsonUrl);
                const geojsonData = await geojsonResponse.json();

                const layersBySlug = {};
                const layersByFeature = [];

                geojsonLayer = L.geoJSON(geojsonData, {
                    style: styleFeature,
                    onEachFeature: function (feature, layer) {
                        const provinceName = getProvinceNameFromFeature(feature);
                        const slug = canonicalProvinceSlug(provinceName);
                        const stats = summaryBySlug[slug] || {};
                        const total = Number(stats.total_alumni_aktif || 0);

                        // Store layer untuk lookups
                        layersBySlug[slug] = layer;
                        layersByFeature.push({
                            layer: layer,
                            feature: feature,
                            provinceName: provinceName,
                            slug: slug,
                            bounds: layer.getBounds()
                        });

                        // Tooltip untuk hover
                        layer.bindTooltip(
                            `<strong>${provinceName}</strong><br>Total Alumni: ${formatNumber(total)}<br><em>Klik untuk detail</em>`,
                            { sticky: true, direction: 'top', className: 'custom-tooltip' }
                        );

                        // Layer click is the primary interaction path for province detail.
                        layer.on('click', function (event) {
                            if (event && event.originalEvent) {
                                L.DomEvent.stopPropagation(event);
                            }
                            highlightLayer(layer, provinceName, slug);
                        });

                        const centroid = getProvinceCentroid(feature);

                        renderProvinceName(provinceName, total, centroid);

                        // Kotak info total per wilayah dinonaktifkan agar tampilan peta lebih bersih.

                    }
                }).addTo(map);

                // Map-level click handler untuk deteksi semua feature di lokasi klik
                if (mapClickHandler) {
                    map.off('click', mapClickHandler);
                }

                mapClickHandler = function (e) {
                    const clickLatlng = e.latlng;
                    const matchedLayers = [];

                    function boundsArea(bounds) {
                        const northEast = bounds.getNorthEast();
                        const southWest = bounds.getSouthWest();
                        const latSpan = Math.abs(northEast.lat - southWest.lat);
                        const lngSpan = Math.abs(northEast.lng - southWest.lng);
                        return latSpan * lngSpan;
                    }

                    // Cari semua layer yang berisi titik klik
                    layersByFeature.forEach(item => {
                        if (item.bounds.contains(clickLatlng)) {
                            // Validasi lebih ketat: cek apakah point benar-benar dalam polygon
                            const geom = item.feature.geometry;
                            if (geom.type === 'Polygon' && isPointInPolygon(clickLatlng, geom.coordinates[0])) {
                                matchedLayers.push(item);
                            } else if (geom.type === 'MultiPolygon' && isPointInMultiPolygon(clickLatlng, geom.coordinates)) {
                                matchedLayers.push(item);
                            }
                        }
                    });

                    // Jika ada matched layer(s), pilih yang pertama atau terbaik
                    if (matchedLayers.length > 0) {
                        // Prioritaskan berdasarkan luas (area terkecil = paling spesifik)
                        const bestMatch = matchedLayers.reduce((best, current) =>
                            boundsArea(current.bounds) < boundsArea(best.bounds)
                                ? current
                                : best
                        );
                        highlightLayer(bestMatch.layer, bestMatch.provinceName, bestMatch.slug);
                    }
                };

                map.on('click', mapClickHandler);

                // Helper: Cek apakah point dalam polygon
                function isPointInPolygon(point, polygon) {
                    const x = point.lng;
                    const y = point.lat;
                    let inside = false;

                    for (let i = 0, j = polygon.length - 1; i < polygon.length; j = i++) {
                        const xi = polygon[i][0], yi = polygon[i][1];
                        const xj = polygon[j][0], yj = polygon[j][1];

                        const intersect = ((yi > y) !== (yj > y)) && (x < (xj - xi) * (y - yi) / (yj - yi) + xi);
                        if (intersect) inside = !inside;
                    }
                    return inside;
                }

                // Helper: Cek apakah point dalam MultiPolygon
                function isPointInMultiPolygon(point, polygons) {
                    return polygons.some(polygon => isPointInPolygon(point, polygon[0]));
                }

                const dataBounds = geojsonLayer.getBounds();
                map.fitBounds(dataBounds, { padding: [16, 16] });
                map.setMaxBounds(indonesiaBounds);
                
                // Add map legend with quantile-based intensity bands
                createMapLegend().addTo(map);
                
                renderIslandLabels();

                if (selectedProvinceSlug && selectedProvinceName) {
                    loadDetail(selectedProvinceSlug, selectedProvinceName);
                } else {
                    resetDetailPanel();
                }

                mapStatusEl.className = 'alert alert-success py-2 px-3 mb-2';
                mapStatusEl.textContent = 'Data siap. Klik salah satu provinsi untuk melihat detail statistik.';
            } catch (error) {
                mapStatusEl.className = 'alert alert-danger py-2 px-3 mb-2';
                mapStatusEl.textContent = 'Gagal memuat peta Indonesia. Periksa koneksi internet atau coba refresh halaman.';
                if (notesLayer) {
                    notesLayer.clearLayers();
                }
                if (provinceNameLayer) {
                    provinceNameLayer.clearLayers();
                }
                if (islandLabelLayer) {
                    islandLabelLayer.clearLayers();
                }
                resetDetailPanel();
            }
        }

        initMap();

        const explorerFilterBtn = document.getElementById('explorer-filter-btn');
        const explorerResetBtn = document.getElementById('explorer-reset-btn');
        const explorerPrevBtn = document.getElementById('explorer-prev');
        const explorerNextBtn = document.getElementById('explorer-next');
        const explorerProvinceSelect = document.getElementById('explorer-province');
        const explorerSearchInput = document.getElementById('explorer-search');

        if (explorerFilterBtn) {
            explorerFilterBtn.addEventListener('click', function () {
                loadExplorer(1);
            });
        }

        if (explorerResetBtn) {
            explorerResetBtn.addEventListener('click', function () {
                const searchEl = document.getElementById('explorer-search');
                const provinceEl = document.getElementById('explorer-province');
                const dapilEl = document.getElementById('explorer-dapil');
                const domisiliEl = document.getElementById('explorer-domisili');
                if (searchEl) {
                    searchEl.value = '';
                }
                if (provinceEl) {
                    provinceEl.value = '';
                }
                if (dapilEl) {
                    dapilEl.value = '';
                }
                if (domisiliEl) {
                    domisiliEl.value = '';
                }
                loadExplorerDapilOptions('');
                setExplorerIdleState('Filter direset. Klik Cari untuk menampilkan alumni.');
            });
        }

        if (explorerPrevBtn) {
            explorerPrevBtn.addEventListener('click', function () {
                if (explorerLoaded && explorerCurrentPage > 1) {
                    loadExplorer(explorerCurrentPage - 1);
                }
            });
        }

        if (explorerNextBtn) {
            explorerNextBtn.addEventListener('click', function () {
                if (explorerLoaded && explorerCurrentPage < explorerLastPage) {
                    loadExplorer(explorerCurrentPage + 1);
                }
            });
        }

        if (explorerProvinceSelect) {
            explorerProvinceSelect.addEventListener('change', function () {
                loadExplorerDapilOptions(explorerProvinceSelect.value || '');
                setExplorerIdleState('Provinsi diperbarui. Klik Cari untuk menampilkan alumni.');
            });
        }

        if (explorerSearchInput) {
            explorerSearchInput.addEventListener('keydown', function (event) {
                if (event.key === 'Enter') {
                    event.preventDefault();
                    loadExplorer(1);
                }
            });
        }

        const explorerDomisiliInput = document.getElementById('explorer-domisili');
        if (explorerDomisiliInput) {
            explorerDomisiliInput.addEventListener('keydown', function (event) {
                if (event.key === 'Enter') {
                    event.preventDefault();
                    loadExplorer(1);
                }
            });
        }

        // Tab detail alumni: full per-tab (satu pane tampil penuh, sisanya disembunyikan).
        // Bukan scroll-spy lagi — klik tab langsung ganti konten yang ditampilkan.
        function setActiveDetailTab(activeKey) {
            const modalBody = document.getElementById('explorer-detail-modal-body');
            if (!modalBody) {
                return;
            }

            modalBody.querySelectorAll('[data-detail-tab]').forEach(node => {
                node.classList.toggle('is-active', String(node.getAttribute('data-detail-tab') || '').trim() === activeKey);
            });

            modalBody.querySelectorAll('[data-detail-pane]').forEach(pane => {
                pane.classList.toggle('is-active', String(pane.getAttribute('data-detail-pane') || '').trim() === activeKey);
            });

            // Tiap ganti tab, balik ke atas supaya konten tab tampil penuh dari awal.
            modalBody.scrollTop = 0;
        }

        const detailModalBodyEl = document.getElementById('explorer-detail-modal-body');
        if (detailModalBodyEl) {
            detailModalBodyEl.addEventListener('click', function (event) {
                const tabEl = event.target.closest('[data-detail-tab]');
                if (!tabEl) {
                    return;
                }

                const key = String(tabEl.getAttribute('data-detail-tab') || '').trim();
                if (!key) {
                    return;
                }

                setActiveDetailTab(key);
            });
        }

        document.addEventListener('click', function (event) {
            const trigger = event.target.closest('[data-explorer-id]');
            if (!trigger) {
                return;
            }

            const parentCard = trigger.closest('.explorer-card-trigger');
            if (!parentCard && !event.target.classList.contains('explorer-detail-btn')) {
                return;
            }

            const alumniId = Number(trigger.getAttribute('data-explorer-id') || 0);
            if (alumniId > 0) {
                openExplorerDetail(alumniId);
            }
        });

        document.addEventListener('keydown', function (event) {
            if (event.key !== 'Enter' && event.key !== ' ') {
                return;
            }

            const activeEl = document.activeElement;
            if (!activeEl || !activeEl.classList || !activeEl.classList.contains('explorer-card-trigger')) {
                return;
            }

            event.preventDefault();
            const alumniId = Number(activeEl.getAttribute('data-explorer-id') || 0);
            if (alumniId > 0) {
                openExplorerDetail(alumniId);
            }
        });

        applyFilterBtn.addEventListener('click', function () {
            loadSummaryAndMap();
            if (typeof window.loadChartStats === 'function') window.loadChartStats();
            setExplorerIdleState('Filter utama diperbarui. Klik Cari untuk memuat alumni.');
        });

        resetFilterBtn.addEventListener('click', function () {
            filterYearEl.value = '';
            filterApprovalEl.value = 'all';
            loadSummaryAndMap();
            if (typeof window.loadChartStats === 'function') window.loadChartStats();
            setExplorerIdleState('Filter utama direset. Klik Cari untuk memuat alumni.');
        });

        document.getElementById('btn-export-csv').addEventListener('click', function () {
            const filters = activeFilters();
            const exportUrl = "{{ route('parja.alumni-dashboard.export') }}" + '?' + filters.toString();
            window.location.href = exportUrl;
        });

        loadSummaryAndMap();
        // loadChartStats is defined in the Chart.js IIFE below; it will auto-fire after Chart.js loads
        setExplorerIdleState();
    })();
</script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>
<script>
(function () {
    'use strict';

    const chartStatsUrl = "{{ route('parja.alumni-dashboard.data.chart-stats') }}";

    // Reuse the same active-filters helper defined in the main IIFE via global
    // We rebuild it here for isolation.
    function activeChartFilters() {
        const filters = new URLSearchParams();
        const yearEl = document.getElementById('filter-tahun-angkatan');
        const statusEl = document.getElementById('filter-approval-status');
        if (yearEl && yearEl.value) filters.set('tahun_angkatan', yearEl.value);
        if (statusEl && statusEl.value && statusEl.value !== 'all') filters.set('approval_status', statusEl.value);
        return filters;
    }

    function buildChartStatsUrl() {
        const params = activeChartFilters();
        return params.toString() ? `${chartStatsUrl}?${params.toString()}` : chartStatsUrl;
    }

    function formatNum(v) {
        return new Intl.NumberFormat('id-ID').format(Number(v || 0));
    }

    // Chart instances registry
    const chartInstances = {};

    function destroyChart(id) {
        if (chartInstances[id]) {
            chartInstances[id].destroy();
            delete chartInstances[id];
        }
    }

    function setChartLoading(loadingId, wrapId, loading) {
        const loadEl = document.getElementById(loadingId);
        const wrapEl = document.getElementById(wrapId);
        if (loadEl) loadEl.style.display = loading ? '' : 'none';
        if (wrapEl) wrapEl.style.display = loading ? 'none' : '';
    }

    /* ---- Palettes ---- */
    const PALETTE_STATUS = [
        'rgba(34,197,94,0.85)',    // approved green
        'rgba(245,158,11,0.85)',   // pending amber
        'rgba(239,68,68,0.85)',    // rejected red
    ];
    const PALETTE_PROFILE = [
        'rgba(99,102,241,0.85)',   // complete indigo
        'rgba(148,163,184,0.85)',  // incomplete slate
    ];
    const PALETTE_BAR = [
        'rgba(191,0,80,0.75)',
        'rgba(65,23,75,0.75)',
        'rgba(251,172,24,0.75)',
        'rgba(14,165,233,0.75)',
        'rgba(16,185,129,0.75)',
        'rgba(239,68,68,0.75)',
        'rgba(245,158,11,0.75)',
        'rgba(99,102,241,0.75)',
        'rgba(20,184,166,0.75)',
        'rgba(168,85,247,0.75)',
    ];

    /* ---- Doughnut/Pie chart helper ---- */
    function renderDoughnut(canvasId, loadingId, wrapId, legendId, data) {
        destroyChart(canvasId);
        setChartLoading(loadingId, wrapId, false);

        const canvas = document.getElementById(canvasId);
        if (!canvas) return;

        const total = data.labels.reduce((s, _, i) => s + (data.values[i] || 0), 0);

        if (total === 0) {
            canvas.parentElement.innerHTML = '<div class="text-secondary" style="font-size:13px;">Belum ada data.</div>';
            return;
        }

        chartInstances[canvasId] = new Chart(canvas, {
            type: 'doughnut',
            data: {
                labels: data.labels,
                datasets: [{
                    data: data.values,
                    backgroundColor: data.colors,
                    borderWidth: 2,
                    borderColor: '#fff',
                    hoverOffset: 6,
                }],
            },
            options: {
                responsive: true,
                cutout: '65%',
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(ctx) {
                                const pct = total > 0 ? ((ctx.parsed / total) * 100).toFixed(1) : 0;
                                return ` ${ctx.label}: ${formatNum(ctx.parsed)} (${pct}%)`;
                            }
                        }
                    }
                }
            }
        });

        // Custom legend
        if (legendId) {
            const legendEl = document.getElementById(legendId);
            if (legendEl) {
                legendEl.innerHTML = data.labels.map((label, i) => {
                    const val = data.values[i] || 0;
                    const pct = total > 0 ? ((val / total) * 100).toFixed(1) : 0;
                    return `<span style="display:inline-flex;align-items:center;gap:4px;">
                        <span style="width:10px;height:10px;border-radius:50%;background:${data.colors[i]};display:inline-block;"></span>
                        <span>${label}: <strong>${formatNum(val)}</strong> (${pct}%)</span>
                    </span>`;
                }).join('');
            }
        }
    }

    /* ---- Horizontal bar chart helper ---- */
    function renderHorizBar(canvasId, loadingId, wrapId, rows, colorPalette) {
        destroyChart(canvasId);
        setChartLoading(loadingId, wrapId, false);

        const canvas = document.getElementById(canvasId);
        if (!canvas) return;

        if (!rows || rows.length === 0) {
            canvas.parentElement.innerHTML = '<div class="text-secondary" style="font-size:13px;">Belum ada data.</div>';
            return;
        }

        const labels = rows.map(r => r.label);
        const values = rows.map(r => r.total);
        const palette = colorPalette || PALETTE_BAR;
        const colors = labels.map((_, i) => palette[i % palette.length]);

        chartInstances[canvasId] = new Chart(canvas, {
            type: 'bar',
            data: {
                labels,
                datasets: [{
                    label: 'Alumni',
                    data: values,
                    backgroundColor: colors,
                    borderRadius: 4,
                    borderSkipped: false,
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: ctx => ` ${formatNum(ctx.parsed.x)} alumni`
                        }
                    }
                },
                scales: {
                    x: {
                        beginAtZero: true,
                        ticks: { precision: 0, font: { size: 11 } },
                        grid: { color: 'rgba(0,0,0,0.05)' }
                    },
                    y: {
                        ticks: {
                            font: { size: 11 },
                            callback: function(val) {
                                const label = this.getLabelForValue(val);
                                return label.length > 28 ? label.substring(0, 26) + '…' : label;
                            }
                        },
                        grid: { display: false }
                    }
                }
            }
        });
    }

    /* ---- Line/Bar chart for angkatan trend ---- */
    function renderAngkatanTrend(canvasId, loadingId, wrapId, rows) {
        destroyChart(canvasId);
        setChartLoading(loadingId, wrapId, false);

        const canvas = document.getElementById(canvasId);
        if (!canvas) return;

        if (!rows || rows.length === 0) {
            canvas.parentElement.innerHTML = '<div class="text-secondary" style="font-size:13px;">Belum ada data tahun angkatan.</div>';
            return;
        }

        const labels = rows.map(r => r.tahun);
        const values = rows.map(r => r.total);

        chartInstances[canvasId] = new Chart(canvas, {
            type: 'bar',
            data: {
                labels,
                datasets: [{
                    label: 'Jumlah Alumni',
                    data: values,
                    backgroundColor: 'rgba(191,0,80,0.7)',
                    borderColor: 'rgba(191,0,80,1)',
                    borderWidth: 1.5,
                    borderRadius: 4,
                    borderSkipped: false,
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: ctx => ` ${formatNum(ctx.parsed.y)} alumni`
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { precision: 0, font: { size: 11 } },
                        grid: { color: 'rgba(0,0,0,0.05)' }
                    },
                    x: {
                        ticks: { font: { size: 11 } },
                        grid: { display: false }
                    }
                }
            }
        });
    }

    /* ---- Post stats doughnut ---- */
    function renderPostStats(postStats) {
        const loadingEl = document.getElementById('chart-post-loading');
        const wrapEl    = document.getElementById('chart-post-wrap');
        if (loadingEl) loadingEl.style.display = 'none';
        if (wrapEl)    wrapEl.style.display = '';

        const totalBadge = document.getElementById('stat-post-total-badge');
        const approvedEl = document.getElementById('stat-post-approved');
        const pendingEl  = document.getElementById('stat-post-pending');
        const rejectedEl = document.getElementById('stat-post-rejected');

        const total    = Number(postStats.total || 0);
        const approved = Number(postStats.approved || 0);
        const pending  = Number(postStats.pending || 0);
        const rejected = Number(postStats.rejected || 0);

        if (totalBadge) totalBadge.textContent = `${formatNum(total)} Post`;
        if (approvedEl) approvedEl.textContent = formatNum(approved);
        if (pendingEl)  pendingEl.textContent  = formatNum(pending);
        if (rejectedEl) rejectedEl.textContent = formatNum(rejected);

        destroyChart('chart-post');
        const canvas = document.getElementById('chart-post');
        if (!canvas) return;

        if (total === 0) {
            canvas.style.display = 'none';
            const msg = document.createElement('div');
            msg.className = 'text-secondary text-center mt-2';
            msg.style.fontSize = '13px';
            msg.textContent = 'Belum ada post alumni.';
            canvas.parentElement.appendChild(msg);
            return;
        }

        chartInstances['chart-post'] = new Chart(canvas, {
            type: 'doughnut',
            data: {
                labels: ['Disetujui', 'Pending', 'Ditolak'],
                datasets: [{
                    data: [approved, pending, rejected],
                    backgroundColor: PALETTE_STATUS,
                    borderWidth: 2,
                    borderColor: '#fff',
                    hoverOffset: 6,
                }]
            },
            options: {
                responsive: true,
                cutout: '60%',
                plugins: {
                    legend: { position: 'bottom', labels: { font: { size: 11 }, boxWidth: 10, padding: 8 } },
                    tooltip: {
                        callbacks: {
                            label: ctx => {
                                const pct = total > 0 ? ((ctx.parsed / total) * 100).toFixed(1) : 0;
                                return ` ${ctx.label}: ${formatNum(ctx.parsed)} (${pct}%)`;
                            }
                        }
                    }
                }
            }
        });
    }

    /* ---- Main load function ---- */
    async function loadChartStats() {
        // Show loading states
        ['chart-approval', 'chart-profile', 'chart-angkatan', 'chart-post', 'chart-pekerjaan', 'chart-pendidikan', 'chart-domisili', 'chart-bidang'].forEach(id => {
            const loadEl = document.getElementById(id + '-loading');
            const wrapEl = document.getElementById(id + '-wrap');
            if (loadEl) { loadEl.style.display = ''; loadEl.textContent = 'Memuat data...'; }
            if (wrapEl) wrapEl.style.display = 'none';
        });

        try {
            const res = await fetch(buildChartStatsUrl(), { headers: { 'Accept': 'application/json' } });
            const payload = await res.json();

            if (!res.ok || !payload.success) {
                ['chart-approval', 'chart-profile', 'chart-angkatan', 'chart-post', 'chart-pekerjaan', 'chart-pendidikan', 'chart-domisili', 'chart-bidang'].forEach(id => {
                    const loadEl = document.getElementById(id + '-loading');
                    if (loadEl) loadEl.textContent = 'Gagal memuat data.';
                });
                return;
            }

            const d = payload.data || {};

            // 1. Approval distribution doughnut
            renderDoughnut('chart-approval', 'chart-approval-loading', 'chart-approval-wrap', 'chart-approval-legend', {
                labels: ['Approved', 'Pending', 'Rejected'],
                values: [
                    Number((d.approval_distribution || {}).approved || 0),
                    Number((d.approval_distribution || {}).pending  || 0),
                    Number((d.approval_distribution || {}).rejected || 0),
                ],
                colors: PALETTE_STATUS,
            });

            // 2. Profile completeness doughnut
            renderDoughnut('chart-profile', 'chart-profile-loading', 'chart-profile-wrap', 'chart-profile-legend', {
                labels: ['Profil Lengkap', 'Belum Lengkap'],
                values: [
                    Number((d.profile_completeness || {}).completed  || 0),
                    Number((d.profile_completeness || {}).incomplete || 0),
                ],
                colors: PALETTE_PROFILE,
            });

            // 3. Angkatan trend
            renderAngkatanTrend('chart-angkatan', 'chart-angkatan-loading', 'chart-angkatan-wrap', d.alumni_per_angkatan || []);

            // 4. Post stats
            renderPostStats(d.post_stats || {});

            // 5. Top pekerjaan
            renderHorizBar('chart-pekerjaan', 'chart-pekerjaan-loading', 'chart-pekerjaan-wrap', d.top_pekerjaan || []);

            // 6. Top pendidikan
            renderHorizBar('chart-pendidikan', 'chart-pendidikan-loading', 'chart-pendidikan-wrap', d.top_pendidikan || []);

            // 7. Top domisili
            renderHorizBar('chart-domisili', 'chart-domisili-loading', 'chart-domisili-wrap', d.top_domisili || []);

            // 8. Top bidang ketertarikan
            renderHorizBar('chart-bidang', 'chart-bidang-loading', 'chart-bidang-wrap', d.top_bidang || []);

        } catch (err) {
            ['chart-approval', 'chart-profile', 'chart-angkatan', 'chart-post', 'chart-pekerjaan', 'chart-pendidikan', 'chart-domisili', 'chart-bidang'].forEach(id => {
                const loadEl = document.getElementById(id + '-loading');
                if (loadEl) loadEl.textContent = 'Gagal memuat data.';
            });
        }
    }

    // Expose globally so filter buttons can call it after filter changes
    window.loadChartStats = loadChartStats;

    // Auto-fire on initial page load
    loadChartStats();
})();
</script>
<script>
(function () {
    'use strict';

    const surveyStatsUrl = "{{ route('parja.alumni-dashboard.data.survey-stats') }}";

    function fmtNum(v) {
        return new Intl.NumberFormat('id-ID').format(Number(v || 0));
    }

    function fmtPct(num, den) {
        if (!den || den === 0) return '0%';
        return ((num / den) * 100).toFixed(1) + '%';
    }

    function labelBulan(yearMonth) {
        const [year, month] = String(yearMonth).split('-');
        const bulanNames = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'];
        const idx = parseInt(month, 10) - 1;
        return `${bulanNames[idx] || month} ${year}`;
    }

    const surveyChartInstances = {};

    function destroySurveyChart(id) {
        if (surveyChartInstances[id]) {
            surveyChartInstances[id].destroy();
            delete surveyChartInstances[id];
        }
    }

    function setKpi(id, value) {
        const el = document.getElementById(id);
        if (el) el.textContent = value;
    }

    function renderSurveyStatusDonut(summary) {
        const loadingEl = document.getElementById('chart-survey-status-loading');
        const wrapEl    = document.getElementById('chart-survey-status-wrap');
        const legendEl  = document.getElementById('chart-survey-status-legend');
        if (loadingEl) loadingEl.style.display = 'none';
        if (wrapEl)    wrapEl.style.display = '';

        destroySurveyChart('chart-survey-status');
        const canvas = document.getElementById('chart-survey-status');
        if (!canvas) return;

        const labels = ['Aktif (Terbit)', 'Draft', 'Selesai'];
        const values = [
            Number(summary.terbit  || 0),
            Number(summary.draft   || 0),
            Number(summary.selesai || 0),
        ];
        const colors = [
            'rgba(16,185,129,0.85)',
            'rgba(14,165,233,0.85)',
            'rgba(100,116,139,0.85)',
        ];
        const total = values.reduce((a, b) => a + b, 0);

        if (total === 0) {
            canvas.parentElement.innerHTML = '<div class="text-secondary text-center py-3" style="font-size:13px;">Belum ada data survei.</div>';
            return;
        }

        surveyChartInstances['chart-survey-status'] = new Chart(canvas, {
            type: 'doughnut',
            data: {
                labels,
                datasets: [{
                    data: values,
                    backgroundColor: colors,
                    borderWidth: 2,
                    borderColor: '#fff',
                    hoverOffset: 8,
                }],
            },
            options: {
                responsive: true,
                cutout: '65%',
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(ctx) {
                                const pct = total > 0 ? ((ctx.parsed / total) * 100).toFixed(1) : 0;
                                return ` ${ctx.label}: ${fmtNum(ctx.parsed)} (${pct}%)`;
                            }
                        }
                    }
                }
            }
        });

        if (legendEl) {
            legendEl.innerHTML = labels.map((label, i) => {
                const val = values[i] || 0;
                const pct = total > 0 ? ((val / total) * 100).toFixed(1) : 0;
                return `<span style="display:inline-flex;align-items:center;gap:4px;">
                    <span style="width:10px;height:10px;border-radius:50%;background:${colors[i]};display:inline-block;"></span>
                    <span>${label}: <strong>${fmtNum(val)}</strong> (${pct}%)</span>
                </span>`;
            }).join('');
        }
    }

    function renderTopSurveiBar(rows) {
        const loadingEl = document.getElementById('chart-top-survei-loading');
        const wrapEl    = document.getElementById('chart-top-survei-wrap');
        if (loadingEl) loadingEl.style.display = 'none';
        if (wrapEl)    wrapEl.style.display = '';

        destroySurveyChart('chart-top-survei');
        const canvas = document.getElementById('chart-top-survei');
        if (!canvas) return;

        if (!rows || rows.length === 0) {
            canvas.parentElement.innerHTML = '<div class="text-secondary text-center py-3" style="font-size:13px;">Belum ada data survei.</div>';
            return;
        }

        const STATUS_COLOR = {
            'terbit':  'rgba(16,185,129,0.8)',
            'draft':   'rgba(14,165,233,0.8)',
            'selesai': 'rgba(100,116,139,0.8)',
        };

        const labels = rows.map(r => r.judul.length > 36 ? r.judul.substring(0, 34) + '…' : r.judul);
        const values = rows.map(r => r.total_peserta);
        const bgColors = rows.map(r => STATUS_COLOR[r.status_publikasi] || 'rgba(99,102,241,0.8)');

        surveyChartInstances['chart-top-survei'] = new Chart(canvas, {
            type: 'bar',
            data: {
                labels,
                datasets: [{
                    label: 'Jumlah Peserta',
                    data: values,
                    backgroundColor: bgColors,
                    borderRadius: 5,
                    borderSkipped: false,
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: ctx => ` ${fmtNum(ctx.parsed.x)} peserta`,
                            afterLabel: function(ctx) {
                                const rawRow = rows[ctx.dataIndex];
                                const statusMap = { terbit: 'Aktif', draft: 'Draft', selesai: 'Selesai' };
                                return `Status: ${statusMap[rawRow?.status_publikasi] || rawRow?.status_publikasi || '-'}`;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        beginAtZero: true,
                        ticks: { precision: 0, font: { size: 11 } },
                        grid: { color: 'rgba(0,0,0,0.04)' },
                    },
                    y: {
                        ticks: { font: { size: 11 } },
                        grid: { display: false },
                    }
                }
            }
        });
    }

    function renderTrendSurvei(rows) {
        const loadingEl = document.getElementById('chart-trend-survei-loading');
        const wrapEl    = document.getElementById('chart-trend-survei-wrap');
        if (loadingEl) loadingEl.style.display = 'none';
        if (wrapEl)    wrapEl.style.display = '';

        destroySurveyChart('chart-trend-survei');
        const canvas = document.getElementById('chart-trend-survei');
        if (!canvas) return;

        if (!rows || rows.length === 0) {
            canvas.parentElement.innerHTML = '<div class="text-secondary text-center py-3" style="font-size:13px;">Belum ada data tren survei dalam 12 bulan terakhir.</div>';
            return;
        }

        const labels = rows.map(r => labelBulan(r.bulan));
        const values = rows.map(r => r.total);

        surveyChartInstances['chart-trend-survei'] = new Chart(canvas, {
            type: 'line',
            data: {
                labels,
                datasets: [{
                    label: 'Survei Dibuat',
                    data: values,
                    borderColor: 'rgba(99,102,241,1)',
                    backgroundColor: 'rgba(99,102,241,0.1)',
                    borderWidth: 2.5,
                    pointBackgroundColor: 'rgba(99,102,241,1)',
                    pointRadius: 5,
                    pointHoverRadius: 7,
                    fill: true,
                    tension: 0.4,
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: ctx => ` ${fmtNum(ctx.parsed.y)} survei dibuat`
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { precision: 0, font: { size: 11 } },
                        grid: { color: 'rgba(0,0,0,0.05)' }
                    },
                    x: {
                        ticks: { font: { size: 11 } },
                        grid: { display: false }
                    }
                }
            }
        });
    }

    async function loadSurveyStats() {
        try {
            const res = await fetch(surveyStatsUrl, { headers: { 'Accept': 'application/json' } });
            const payload = await res.json();

            if (!res.ok || !payload.success) {
                ['chart-survey-status', 'chart-top-survei', 'chart-trend-survei'].forEach(id => {
                    const el = document.getElementById(id + '-loading');
                    if (el) el.textContent = 'Gagal memuat data survei.';
                });
                return;
            }

            const d = payload.data || {};
            const summary = d.survei_summary || {};

            // Update KPI cards
            setKpi('kpi-total-survei',     fmtNum(summary.total));
            setKpi('kpi-survei-terbit',    fmtNum(summary.terbit));
            setKpi('kpi-total-peserta',    fmtNum(d.total_peserta));
            setKpi('kpi-total-responden',  fmtNum(d.total_responden));
            setKpi('kpi-total-kuesioner',  fmtNum(d.total_kuesioner));
            setKpi('kpi-survei-draft',     fmtNum(summary.draft));
            setKpi('kpi-survei-selesai',   fmtNum(summary.selesai));

            // Tingkat partisipasi = survei dengan responden / total survei
            const partisipasiPct = fmtPct(d.survei_dengan_responden, summary.total);
            setKpi('kpi-partisipasi-pct', partisipasiPct);

            // Update generated at badge
            const genAt = document.getElementById('survey-generated-at');
            if (genAt && d.generated_at) {
                const dt = new Date(d.generated_at);
                genAt.textContent = `Update: ${dt.toLocaleString('id-ID')}`;
            }

            // Render charts
            renderSurveyStatusDonut(summary);
            renderTopSurveiBar(d.top_survei || []);
            renderTrendSurvei(d.trend_survei || []);

        } catch (err) {
            ['chart-survey-status', 'chart-top-survei', 'chart-trend-survei'].forEach(id => {
                const el = document.getElementById(id + '-loading');
                if (el) el.textContent = 'Gagal memuat data survei.';
            });
        }
    }

    // Auto-fire
    window.loadSurveyStats = loadSurveyStats;
    loadSurveyStats();
})();
</script>
@endpush
