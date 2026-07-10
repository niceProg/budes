@extends('layouts.app')

@section('title', 'Progress Magang | Peserta - SMART Setjen DPR RI')
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

    .progress-container {
        font-family: 'Plus Jakarta Sans', sans-serif;
        background-color: #f8fafc;
        min-height: 100vh;
        padding: 2rem 1rem;
    }

    html[data-skin="dark"] .progress-container {
        background-color: #020617;
        color: #e5e7eb;
    }

    .progress-container::before {
        content: '';
        position: fixed;
        top: 0; left: 0; width: 100%; height: 100%;
        background-image: url('{{ asset("theme/admin-dashbyte/dist/assets/img/batiknew.png") }}');
        background-size: 500px;
        opacity: 0.03;
        z-index: 0;
        pointer-events: none;
    }

    .page-header {
        max-width: 1100px;
        margin: 0 auto 20px;
        position: relative;
        z-index: 1;
    }

    .page-header-inner {
        background: var(--white);
        border-radius: 24px;
        padding: 24px 32px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
        border: 1px solid var(--border-color);
    }

    .page-header-inner::before {
        content: '';
        position: absolute;
        top: 0; left: 0;
        width: 5px; height: 100%;
        background: var(--accent-gold);
        border-radius: 4px 0 0 4px;
    }

    html[data-skin="dark"] .page-header-inner {
        background: #020617;
        border-color: #1f2937;
        box-shadow: 0 20px 40px rgba(0,0,0,0.7);
    }

    .page-header h1 {
        font-family: 'DM Sans', 'Plus Jakarta Sans', sans-serif;
        font-weight: 700;
        font-size: 1.6rem;
        color: var(--primary-dark);
        margin: 0;
        letter-spacing: -0.5px;
    }

    html[data-skin="dark"] .page-header h1 { color: #ffffff; }

    .page-header p {
        color: var(--text-muted);
        font-size: 0.9rem;
        font-weight: 500;
        margin: 4px 0 0 0;
    }

    .back-link {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        color: var(--accent-gold);
        font-size: 0.85rem;
        font-weight: 600;
        text-decoration: none;
        margin-bottom: 10px;
        transition: var(--transition);
    }

    .back-link:hover { color: #8a6d30; }

    .main-card {
        background: var(--white);
        border-radius: 20px;
        padding: 2rem;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.02);
        border: 1px solid var(--border-color);
        position: relative;
        z-index: 1;
        max-width: 1100px;
        margin: 0 auto;
    }

    html[data-skin="dark"] .main-card {
        background: #020617;
        border-color: #1f2937;
        box-shadow: 0 20px 40px rgba(0,0,0,0.7);
    }

    /* Alert notifications */
    .pg-alert {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 14px 18px;
        border-radius: 12px;
        font-size: 0.875rem;
        font-weight: 600;
        margin-bottom: 12px;
        border: 1px solid transparent;
        animation: slideDown 0.4s ease;
    }

    .pg-alert-warning {
        background: #fffbeb;
        border-color: #fef3c7;
        color: #92400e;
    }

    .pg-alert-info {
        background: #eff6ff;
        border-color: #bfdbfe;
        color: #1e40af;
    }

    .pg-alert-success {
        background: #f0fdf4;
        border-color: #bbf7d0;
        color: #166534;
    }

    .pg-alert i { font-size: 1.25rem; flex-shrink: 0; }

    html[data-skin="dark"] .pg-alert-warning {
        background: #1c1917; border-color: #78350f; color: #fbbf24;
    }
    html[data-skin="dark"] .pg-alert-info {
        background: #0c1a2e; border-color: #1e3a5f; color: #93c5fd;
    }
    html[data-skin="dark"] .pg-alert-success {
        background: #052e16; border-color: #14532d; color: #86efac;
    }

    @keyframes slideDown {
        from { opacity: 0; transform: translateY(-10px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    /* Overall progress circle */
    .overall-card {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
        border-radius: 20px;
        padding: 2rem;
        text-align: center;
        color: #ffffff;
        position: relative;
        overflow: hidden;
    }

    .overall-card::after {
        content: '';
        position: absolute;
        top: -50px; right: -50px;
        width: 150px; height: 150px;
        background: rgba(176, 141, 72, 0.15);
        border-radius: 50%;
    }

    .overall-card h3 {
        font-weight: 700;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        color: var(--accent-gold);
        margin-bottom: 1.5rem;
    }

    .progress-circle-wrap {
        position: relative;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .progress-circle-svg { transform: rotate(-90deg); }

    .progress-circle-bg {
        fill: none;
        stroke: rgba(255,255,255,0.1);
        stroke-width: 10;
    }

    .progress-circle-fill {
        fill: none;
        stroke-width: 10;
        stroke-linecap: round;
        stroke: var(--accent-gold);
        transition: stroke-dashoffset 1.5s ease-in-out;
    }

    .progress-circle-text {
        position: absolute;
        text-align: center;
    }

    .progress-circle-pct {
        font-size: 2.5rem;
        font-weight: 800;
        line-height: 1;
        display: block;
    }

    .progress-circle-label {
        font-size: 0.75rem;
        color: rgba(255,255,255,0.6);
        font-weight: 600;
        margin-top: 4px;
        display: block;
    }

    /* Stat mini cards inside overall */
    .overall-stats {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 10px;
        margin-top: 1.5rem;
    }

    .overall-stat-item {
        background: rgba(255,255,255,0.07);
        border-radius: 12px;
        padding: 12px;
        text-align: center;
    }

    .overall-stat-value {
        font-size: 1.4rem;
        font-weight: 800;
        color: var(--accent-gold);
    }

    .overall-stat-label {
        font-size: 0.72rem;
        color: rgba(255,255,255,0.55);
        font-weight: 600;
        margin-top: 2px;
    }

    /* Section blocks */
    .pg-section {
        background: #ffffff;
        border-radius: 16px;
        padding: 1.5rem;
        border: 1px solid var(--border-color);
        height: 100%;
        transition: var(--transition);
    }

    .pg-section:hover {
        border-color: var(--accent-gold);
        box-shadow: 0 8px 20px rgba(0,0,0,0.03);
    }

    html[data-skin="dark"] .pg-section {
        background: #0f172a;
        border-color: #1f2937;
    }

    html[data-skin="dark"] .pg-section:hover {
        border-color: #fbbf24;
        box-shadow: 0 8px 20px rgba(0,0,0,0.4);
    }

    .pg-section-title {
        font-weight: 700;
        font-size: 0.95rem;
        color: var(--primary-dark);
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 1.25rem;
    }

    .pg-section-title i { color: var(--accent-gold); font-size: 1.15rem; }

    html[data-skin="dark"] .pg-section-title { color: #ffffff; }

    /* Progress bar */
    .pg-bar-wrap {
        background: #f1f5f9;
        border-radius: 99px;
        height: 14px;
        overflow: hidden;
        margin-bottom: 8px;
    }

    html[data-skin="dark"] .pg-bar-wrap { background: #1e293b; }

    .pg-bar-fill {
        height: 100%;
        border-radius: 99px;
        width: 0;
        transition: width 1.2s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }

    .pg-bar-fill::after {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
        animation: shimmer 2s infinite;
    }

    @keyframes shimmer {
        0%   { transform: translateX(-100%); }
        100% { transform: translateX(100%); }
    }

    .pg-bar-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 0.8rem;
        margin-bottom: 1rem;
    }

    .pg-bar-pct { font-weight: 700; }
    .pg-bar-label { color: var(--text-muted); font-weight: 600; }

    html[data-skin="dark"] .pg-bar-label { color: #9ca3af; }

    .pg-bar-status-badge {
        display: inline-block;
        padding: 3px 10px;
        border-radius: 99px;
        font-size: 0.75rem;
        font-weight: 700;
    }

    /* Duration info grid */
    .duration-info {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 10px;
        margin-top: 1rem;
    }

    .duration-info-item {
        background: #f8fafc;
        border-radius: 10px;
        padding: 10px 12px;
        border: 1px solid #f1f5f9;
    }

    html[data-skin="dark"] .duration-info-item {
        background: #1e293b;
        border-color: #374151;
    }

    .duration-info-label {
        font-size: 0.72rem;
        color: var(--text-muted);
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    html[data-skin="dark"] .duration-info-label { color: #9ca3af; }

    .duration-info-value {
        font-size: 0.85rem;
        font-weight: 700;
        color: var(--primary-dark);
        margin-top: 2px;
    }

    html[data-skin="dark"] .duration-info-value { color: #ffffff; }

    /* Document checklist */
    .doc-checklist { list-style: none; padding: 0; margin: 0; }

    .doc-checklist-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        padding: 10px 12px;
        border-radius: 10px;
        margin-bottom: 8px;
        font-size: 0.85rem;
        font-weight: 600;
        transition: var(--transition);
    }

    .doc-checklist-item.status-lengkap {
        background: #f0fdf4;
        border: 1px solid #bbf7d0;
        color: #166534;
    }

    .doc-checklist-item.status-menunggu {
        background: #fffbeb;
        border: 1px solid #fef3c7;
        color: #92400e;
    }

    .doc-checklist-item.status-ditolak {
        background: #fef2f2;
        border: 1px solid #fecaca;
        color: #991b1b;
    }

    .doc-checklist-item.status-belum {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        color: var(--text-muted);
    }

    html[data-skin="dark"] .doc-checklist-item.status-lengkap {
        background: #052e16; border-color: #14532d; color: #86efac;
    }
    html[data-skin="dark"] .doc-checklist-item.status-menunggu {
        background: #1c1917; border-color: #78350f; color: #fbbf24;
    }
    html[data-skin="dark"] .doc-checklist-item.status-ditolak {
        background: #450a0a; border-color: #991b1b; color: #fca5a5;
    }
    html[data-skin="dark"] .doc-checklist-item.status-belum {
        background: #1e293b; border-color: #374151; color: #9ca3af;
    }

    .doc-checklist-label {
        display: flex;
        align-items: center;
        gap: 8px;
        flex: 1;
        min-width: 0;
    }

    .doc-checklist-icon { font-size: 1.1rem; flex-shrink: 0; }

    .doc-badge {
        font-size: 0.7rem;
        font-weight: 700;
        padding: 2px 8px;
        border-radius: 99px;
        white-space: nowrap;
    }

    /* Stat cards */
    .stat-card {
        background: #ffffff;
        border-radius: 14px;
        padding: 1.25rem;
        border: 1px solid var(--border-color);
        text-align: center;
        transition: var(--transition);
    }

    .stat-card:hover {
        border-color: var(--accent-gold);
        box-shadow: 0 6px 16px rgba(0,0,0,0.04);
    }

    html[data-skin="dark"] .stat-card {
        background: #0f172a;
        border-color: #1f2937;
    }

    html[data-skin="dark"] .stat-card:hover {
        border-color: #fbbf24;
        box-shadow: 0 6px 16px rgba(0,0,0,0.4);
    }

    .stat-card-icon {
        font-size: 1.75rem;
        color: var(--accent-gold);
        margin-bottom: 8px;
    }

    .stat-card-value {
        font-size: 1.75rem;
        font-weight: 800;
        color: var(--primary-dark);
    }

    html[data-skin="dark"] .stat-card-value { color: #ffffff; }

    .stat-card-label {
        font-size: 0.75rem;
        color: var(--text-muted);
        font-weight: 600;
        margin-top: 2px;
    }

    html[data-skin="dark"] .stat-card-label { color: #9ca3af; }

    @media (max-width: 768px) {
        .progress-container { padding: 1rem 0.5rem; }
        .page-header-inner { padding: 18px 20px; }
        .main-card { padding: 1.25rem; }
        .overall-stats { grid-template-columns: repeat(2, 1fr); }
        .duration-info { grid-template-columns: 1fr; }
    }
</style>

@php
    $duration   = $progress['duration'];
    $documents  = $progress['documents'];
    $attendance = $progress['attendance'];
    $logbook    = $progress['logbook'];
    $overall    = $progress['overall'];
    $overallClr = $progress['overall_color'];
    $notifs     = $progress['notifications'];

    // Circle calculations
    $circleRadius = 70;
    $circleCircumference = 2 * M_PI * $circleRadius;
    $circleOffset = $circleCircumference - ($overall / 100) * $circleCircumference;

    // Status CSS class for doc items
    $docStatusClass = function($status) {
        return match($status) {
            'Lengkap'              => 'status-lengkap',
            'Menunggu Verifikasi'  => 'status-menunggu',
            'Ditolak'              => 'status-ditolak',
            default                => 'status-belum',
        };
    };
@endphp

<div class="progress-container">
    <div class="container">
        {{-- Page Header --}}
        <div class="page-header">
            <a href="{{ route('peserta.index') }}" class="back-link">
                <i class="ri-arrow-left-line"></i> Kembali ke Dashboard
            </a>
            <div class="page-header-inner">
                <h1><i class="ri-bar-chart-grouped-fill" style="color:var(--accent-gold);"></i> Progress Magang</h1>
                <p>Pantau progres magang Anda secara real-time.</p>
            </div>
        </div>

        {{-- Notifications --}}
        @if(count($notifs) > 0)
        <div class="page-header" style="margin-bottom:14px;">
            @foreach($notifs as $n)
            <div class="pg-alert pg-alert-{{ $n['type'] }}">
                <i class="{{ $n['icon'] }}"></i>
                <span>{{ $n['message'] }}</span>
            </div>
            @endforeach
        </div>
        @endif

        {{-- Main Content --}}
        <div class="main-card">
            <div class="row g-4">

                {{-- Overall Progress Circle --}}
                <div class="col-lg-4">
                    <div class="overall-card h-100">
                        <h3>Progress Keseluruhan</h3>
                        <div class="progress-circle-wrap">
                            <svg class="progress-circle-svg" width="160" height="160" viewBox="0 0 160 160">
                                <circle class="progress-circle-bg" cx="80" cy="80" r="{{ $circleRadius }}"></circle>
                                <circle class="progress-circle-fill" cx="80" cy="80" r="{{ $circleRadius }}"
                                    stroke-dasharray="{{ $circleCircumference }}"
                                    stroke-dashoffset="{{ $circleCircumference }}"
                                    data-target-offset="{{ $circleOffset }}"
                                    id="overallCircle"></circle>
                            </svg>
                            <div class="progress-circle-text">
                                <span class="progress-circle-pct">{{ $overall }}%</span>
                                <span class="progress-circle-label">Overall</span>
                            </div>
                        </div>

                        <div class="overall-stats">
                            <div class="overall-stat-item">
                                <div class="overall-stat-value">{{ $duration['percentage'] }}%</div>
                                <div class="overall-stat-label">Durasi Magang</div>
                            </div>
                            <div class="overall-stat-item">
                                <div class="overall-stat-value">{{ $documents['percentage'] }}%</div>
                                <div class="overall-stat-label">Kelengkapan Berkas</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Duration Progress --}}
                <div class="col-lg-4">
                    <div class="pg-section h-100">
                        <div class="pg-section-title">
                            <i class="ri-calendar-schedule-fill"></i> Progress Durasi Magang
                        </div>

                        <div class="pg-bar-meta">
                            <span class="pg-bar-pct" style="color:{{ $duration['color_hex'] }};">
                                {{ $duration['percentage'] }}%
                            </span>
                            <span class="pg-bar-status-badge" style="background:{{ $duration['color_hex'] }}20; color:{{ $duration['color_hex'] }}; border:1px solid {{ $duration['color_hex'] }}55;">
                                {{ $duration['label'] }}
                            </span>
                        </div>

                        <div class="pg-bar-wrap">
                            <div class="pg-bar-fill" style="background:{{ $duration['color_hex'] }};" data-width="{{ $duration['percentage'] }}"></div>
                        </div>

                        <div class="duration-info">
                            <div class="duration-info-item">
                                <div class="duration-info-label">Tanggal Mulai</div>
                                <div class="duration-info-value">{{ $duration['start_formatted'] }}</div>
                            </div>
                            <div class="duration-info-item">
                                <div class="duration-info-label">Tanggal Selesai</div>
                                <div class="duration-info-value">{{ $duration['end_formatted'] }}</div>
                            </div>
                            <div class="duration-info-item">
                                <div class="duration-info-label">Hari Berjalan</div>
                                <div class="duration-info-value">{{ $duration['days_elapsed'] }} / {{ $duration['total_days'] }} hari</div>
                            </div>
                            <div class="duration-info-item">
                                <div class="duration-info-label">Sisa Hari</div>
                                <div class="duration-info-value" style="color:{{ $duration['days_remaining'] <= 7 && $duration['days_remaining'] > 0 ? '#ef4444' : 'inherit' }};">
                                    {{ $duration['days_remaining'] }} hari
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Document Progress --}}
                <div class="col-lg-4">
                    <div class="pg-section h-100">
                        <div class="pg-section-title">
                            <i class="ri-folder-shield-2-fill"></i> Kelengkapan Berkas
                        </div>

                        <div class="pg-bar-meta">
                            <span class="pg-bar-pct" style="color:{{ $documents['color_hex'] }};">
                                {{ $documents['percentage'] }}%
                            </span>
                            <span class="pg-bar-label">{{ $documents['completed'] }} / {{ $documents['total'] }} dokumen</span>
                        </div>

                        <div class="pg-bar-wrap">
                            <div class="pg-bar-fill" style="background:{{ $documents['color_hex'] }};" data-width="{{ $documents['percentage'] }}"></div>
                        </div>

                        <ul class="doc-checklist" style="max-height:280px; overflow-y:auto; margin-top:12px;">
                            @foreach($documents['items'] as $doc)
                            <li class="doc-checklist-item {{ $docStatusClass($doc['status']) }}">
                                <span class="doc-checklist-label">
                                    <i class="{{ $doc['meta']['icon'] }} doc-checklist-icon"></i>
                                    <span style="overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">{{ $doc['label'] }}</span>
                                </span>
                                <span class="doc-badge {{ $doc['meta']['badge'] }}">{{ $doc['status'] }}</span>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                {{-- Statistics Cards --}}
                <div class="col-12">
                    <div class="pg-section-title" style="margin-bottom:1rem; font-size:1rem;">
                        <i class="ri-pie-chart-fill"></i> Ringkasan Statistik
                    </div>
                </div>

                <div class="col-6 col-md-4 col-lg-2">
                    <div class="stat-card h-100">
                        <div class="stat-card-icon"><i class="ri-calendar-check-fill"></i></div>
                        <div class="stat-card-value">{{ $attendance['total_hadir'] }}</div>
                        <div class="stat-card-label">Total Kehadiran</div>
                    </div>
                </div>

                <div class="col-6 col-md-4 col-lg-2">
                    <div class="stat-card h-100">
                        <div class="stat-card-icon"><i class="ri-calendar-todo-fill"></i></div>
                        <div class="stat-card-value">{{ $attendance['total_izin'] }}</div>
                        <div class="stat-card-label">Total Izin</div>
                    </div>
                </div>

                <div class="col-6 col-md-4 col-lg-2">
                    <div class="stat-card h-100">
                        <div class="stat-card-icon"><i class="ri-first-aid-kit-fill"></i></div>
                        <div class="stat-card-value">{{ $attendance['total_sakit'] }}</div>
                        <div class="stat-card-label">Total Sakit</div>
                    </div>
                </div>

                <div class="col-6 col-md-4 col-lg-2">
                    <div class="stat-card h-100">
                        <div class="stat-card-icon"><i class="ri-book-open-fill"></i></div>
                        <div class="stat-card-value">{{ $logbook['filled'] }}</div>
                        <div class="stat-card-label">Logbook Terisi</div>
                    </div>
                </div>

                <div class="col-6 col-md-4 col-lg-2">
                    <div class="stat-card h-100">
                        <div class="stat-card-icon"><i class="ri-booklet-fill"></i></div>
                        <div class="stat-card-value">{{ $logbook['unfilled'] }}</div>
                        <div class="stat-card-label">Logbook Belum Diisi</div>
                    </div>
                </div>

                <div class="col-6 col-md-4 col-lg-2">
                    <div class="stat-card h-100">
                        <div class="stat-card-icon"><i class="ri-bar-chart-fill"></i></div>
                        <div class="stat-card-value" style="color:{{ \Modules\Magang\App\Services\MagangProgressService::progressColorHex($overall) }};">
                            {{ $overall }}%
                        </div>
                        <div class="stat-card-label">Overall Progress</div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Animate progress bars
    document.querySelectorAll('.pg-bar-fill').forEach(function(bar) {
        const w = bar.getAttribute('data-width');
        if (w !== null) {
            setTimeout(function() { bar.style.width = w + '%'; }, 200);
        }
    });

    // Animate overall circle
    const circle = document.getElementById('overallCircle');
    if (circle) {
        const targetOffset = parseFloat(circle.getAttribute('data-target-offset'));
        setTimeout(function() {
            circle.style.strokeDashoffset = targetOffset;
        }, 400);
    }
});
</script>
@endsection
