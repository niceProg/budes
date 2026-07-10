@extends('layouts.app')

@section('title', 'Absensi | Peserta - SMART Setjen DPR RI')
@section('content')
<!-- Import Remix Icon & Google Fonts -->
<link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

<style>
    :root {
        --primary-dark: #0f172a;
        --accent-gold: linear-gradient(135deg, #d4af37 0%, #aa8a2e 100%);
        --gold-solid: #b08d48;
        --gold-light: #f59e0b;
        --text-main: #1e293b;
        --text-muted: #64748b;
        --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    body {
        background-color: #f8fafc;
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    .content-wrapper {
        position: relative;
        min-height: 100vh;
        z-index: 1;
        padding-bottom: 50px;
    }

    .content-wrapper::before {
        content: '';
        position: fixed;
        top: 0; left: 0; width: 100%; height: 100%;
        background-image: url('{{ asset("theme/admin-dashbyte/dist/assets/img/batiknew.png") }}');
        background-size: 500px;
        opacity: 0.03;
        z-index: -1;
        pointer-events: none;
    }

    /* Header & Clock */
    .page-header {
        text-align: center;
        margin-bottom: 3rem;
        padding-top: 2rem;
    }

    .system-title {
        font-size: 2.8rem;
        font-weight: 800;
        color: var(--primary-dark);
        letter-spacing: -1.5px;
        margin-bottom: 0.2rem;
    }

    .system-subtitle {
        color: var(--gold-solid);
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 4px;
        font-size: 0.85rem;
    }

    .live-datetime-container {
        display: inline-flex;
        background: white;
        padding: 0.8rem 2rem;
        border-radius: 50px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.05);
        border: 1px solid rgba(176, 141, 72, 0.2);
        margin-top: 1.5rem;
        align-items: center;
        gap: 15px;
    }

    .date-display {
        color: var(--text-main);
        font-weight: 700;
        font-size: 1rem;
        border-right: 2px solid #f1f5f9;
        padding-right: 15px;
    }

    .time-display {
        color: var(--gold-solid);
        font-size: 2rem;
        font-weight: 800;
    }

    /* Attendance Cards */
    .card-attendance {
        background: white;
        border-radius: 24px;
        border: 1px solid #e2e8f0;
        padding: 2.5rem 1.5rem;
        transition: var(--transition);
        height: 100%;
        position: relative;
        text-align: center;
    }

    .card-attendance:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.08);
        border-color: var(--gold-solid);
    }

    .log-label {
        font-weight: 800;
        font-size: 0.7rem;
        text-transform: uppercase;
        color: var(--text-muted);
        letter-spacing: 1.5px;
        margin-bottom: 1rem;
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 6px;
    }

    .log-time {
        font-size: 3.2rem;
        font-weight: 800;
        color: var(--primary-dark);
        line-height: 1;
        margin-bottom: 1rem;
    }

    /* Duration Summary Banner */
    .duration-banner {
        background: var(--primary-dark);
        color: white;
        border-radius: 20px;
        padding: 1.5rem;
        margin-top: -20px;
        position: relative;
        z-index: 2;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 15px;
        border: 2px solid var(--gold-solid);
    }

    .duration-label {
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        opacity: 0.8;
    }

    .duration-value {
        font-size: 1.5rem;
        font-weight: 800;
        color: #fbbf24;
    }

    /* Form & UI */
    .attendance-selector-box {
        background: white;
        border-radius: 30px;
        padding: 3rem;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.08);
        border: 1px solid #e2e8f0;
    }

    .btn-gold-luxury {
        background: var(--accent-gold);
        color: white;
        border: none;
        padding: 1.2rem;
        border-radius: 15px;
        font-weight: 800;
        font-size: 1.1rem;
        transition: var(--transition);
        box-shadow: 0 10px 20px rgba(176, 141, 72, 0.25);
    }

    .btn-checkout-pulang {
        background: #10b981;
        color: white;
        border: none;
        padding: 1.2rem;
        border-radius: 15px;
        font-weight: 800;
        font-size: 1.1rem;
        transition: var(--transition);
        box-shadow: 0 10px 20px rgba(16, 185, 129, 0.25);
    }

    .btn-checkout-pulang:hover {
        background: #059669;
        transform: translateY(-2px);
        box-shadow: 0 15px 30px rgba(16, 185, 129, 0.35);
    }

    .badge-status {
        padding: 8px 16px;
        border-radius: 10px;
        font-weight: 800;
        font-size: 0.75rem;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .badge-ontime { background: #f0fdf4; color: #16a34a; }
    .badge-late { background: #fef2f2; color: #dc2626; }

    /* Upload Box Styling */
    .upload-box.has-file {
        border: 2.5px solid #10b981 !important;
        background: #f0fdf4 !important;
    }

    .upload-box:hover {
        border-color: var(--gold-solid) !important;
        background: #fffbeb !important;
        transform: translateY(-3px);
    }

    /* === RESPONSIVE === */
    @media (max-width: 991px) {
        .page-header {
            margin-bottom: 2rem;
            padding-top: 1rem;
        }

        .system-title {
            font-size: 2.2rem;
        }

        .live-datetime-container {
            padding: 0.6rem 1.5rem;
            flex-wrap: wrap;
            gap: 10px;
        }

        .time-display {
            font-size: 1.5rem;
        }

        .date-display {
            font-size: 0.9rem;
            padding-right: 10px;
        }

        .map-section {
            margin-bottom: 2rem;
        }

        #absensiMap {
            height: 300px;
        }

        .card-attendance {
            padding: 2rem 1.25rem;
        }

        .log-time {
            font-size: 2.5rem;
        }

        .attendance-selector-box {
            padding: 2rem 1.5rem;
        }
    }

    @media (max-width: 768px) {
        .content-wrapper {
            padding-bottom: 30px;
        }

        .system-title {
            font-size: 1.8rem;
        }

        .system-subtitle {
            font-size: 0.75rem;
            letter-spacing: 2px;
        }

        .live-datetime-container {
            flex-direction: column;
            padding: 0.8rem 1.25rem;
            border-radius: 30px;
        }

        .date-display {
            border-right: none;
            border-bottom: 2px solid #f1f5f9;
            padding-right: 0;
            padding-bottom: 10px;
            margin-bottom: 10px;
        }

        .time-display {
            font-size: 1.8rem;
        }

        .map-section {
            padding: 1.5rem !important;
        }

        #absensiMap {
            height: 250px;
        }

        .card-attendance {
            padding: 1.5rem 1rem;
        }

        .log-time {
            font-size: 2rem;
        }

        .duration-banner {
            padding: 1.25rem;
            flex-direction: column;
            text-align: center;
        }

        .duration-value {
            font-size: 1.3rem;
        }

        .attendance-selector-box {
            padding: 1.5rem 1.25rem;
        }

        .btn-gold-luxury {
            padding: 1rem;
            font-size: 1rem;
        }

        .btn-checkout-pulang {
            padding: 1rem;
            font-size: 1rem;
        }
    }

    @media (max-width: 576px) {
        .system-title {
            font-size: 1.5rem;
        }

        .live-datetime-container {
            padding: 0.6rem 1rem;
        }

        .time-display {
            font-size: 1.5rem;
        }

        .date-display {
            font-size: 0.85rem;
        }

        .map-section {
            padding: 1rem !important;
        }

        #absensiMap {
            height: 200px;
        }

        .card-attendance {
            padding: 1.25rem 0.75rem;
        }

        .log-time {
            font-size: 1.8rem;
        }

        .log-label {
            font-size: 0.65rem;
        }

        .duration-banner {
            padding: 1rem;
        }

        .duration-value {
            font-size: 1.1rem;
        }

        .attendance-selector-box {
            padding: 1.25rem 1rem;
            border-radius: 20px;
        }

        .btn-gold-luxury {
            padding: 0.9rem;
            font-size: 0.95rem;
        }

        .btn-checkout-pulang {
            padding: 0.9rem;
            font-size: 0.95rem;
        }

        .upload-box {
            padding: 1.5rem 1rem;
            min-height: 100px;
        }

        .upload-box .main-icon {
            font-size: 2rem !important;
        }

        /* Modal Responsive */
        #filePreviewModal .modal-dialog {
            margin: 1rem;
            max-width: 95%;
        }

        #filePreviewModal .modal-body {
            min-height: 50vh;
        }

        #filePreviewImage {
            max-height: 60vh;
        }

        #filePreviewFrame {
            height: 60vh;
        }
    }

    /* Modal Styles */
    #filePreviewModal .modal-content {
        border-radius: 20px;
        overflow: hidden;
    }

    #filePreviewModal .modal-header {
        border-bottom: 1px solid #f1f5f9;
        padding: 1.5rem;
    }

    #filePreviewModal .modal-title {
        font-weight: 800;
        color: var(--primary-dark);
    }

    #filePreviewModal .modal-body {
        background: #000;
    }

    #filePreviewImage {
        max-width: 100%;
        max-height: 80vh;
        object-fit: contain;
    }

    #filePreviewFrame {
        width: 100%;
        height: 80vh;
        border: 0;
    }

    /* === Dark mode: absensi konsisten === */
    html[data-skin="dark"] body,
    html[data-skin="dark"] .content-wrapper {
        background-color: #020617 !important;
        color: #ffffff !important;
    }
    html[data-skin="dark"] .content-wrapper::before { opacity: 0.03 !important; }
    html[data-skin="dark"] .system-title { color: #ffffff !important; }
    html[data-skin="dark"] .system-subtitle { color: #fbbf24 !important; }
    html[data-skin="dark"] .live-datetime-container {
        background: #0f172a !important;
        border-color: #374151 !important;
    }
    html[data-skin="dark"] .date-display {
        color: #9ca3af !important;
        border-color: #374151 !important;
    }
    html[data-skin="dark"] .time-display { color: #fbbf24 !important; }
    html[data-skin="dark"] .card-attendance {
        background: #0f172a !important;
        border-color: #1f2937 !important;
    }
    html[data-skin="dark"] .card-attendance:hover {
        border-color: #fbbf24 !important;
        box-shadow: 0 20px 40px rgba(0,0,0,0.4) !important;
    }
    html[data-skin="dark"] .log-label { color: #9ca3af !important; }
    html[data-skin="dark"] .log-time { color: #ffffff !important; }
    html[data-skin="dark"] .duration-banner {
        background: #0f172a !important;
        border-color: #374151 !important;
    }
    html[data-skin="dark"] .duration-value { color: #fbbf24 !important; }
    html[data-skin="dark"] .attendance-selector-box {
        background: #0f172a !important;
        border-color: #1f2937 !important;
        color: #ffffff !important;
    }
    html[data-skin="dark"] .attendance-selector-box h5,
    html[data-skin="dark"] .attendance-selector-box .form-label { color: #ffffff !important; }
    html[data-skin="dark"] .form-control,
    html[data-skin="dark"] .form-select {
        background: #1e293b !important;
        border-color: #374151 !important;
        color: #ffffff !important;
    }
    html[data-skin="dark"] .form-control:focus,
    html[data-skin="dark"] .form-select:focus {
        background: #0f172a !important;
    }
    html[data-skin="dark"] .form-control::placeholder { color: #9ca3af !important; }
    html[data-skin="dark"] .upload-box,
    html[data-skin="dark"] [class*="upload-box"] {
        background: #1e293b !important;
        border-color: #374151 !important;
    }
    html[data-skin="dark"] .upload-box:hover,
    html[data-skin="dark"] .upload-box.has-file {
        border-color: #fbbf24 !important;
        background: #0f172a !important;
    }
    html[data-skin="dark"] .upload-text,
    html[data-skin="dark"] .upload-box .main-icon { color: #fbbf24 !important; }
    html[data-skin="dark"] .file-info,
    html[data-skin="dark"] .text-muted { color: #9ca3af !important; }
    html[data-skin="dark"] .border.rounded-4.p-3,
    html[data-skin="dark"] [style*="background: #f8fafc"],
    html[data-skin="dark"] [style*="background: #f9fafb"] {
        background: #1e293b !important;
        border-color: #374151 !important;
        color: #ffffff !important;
    }
    html[data-skin="dark"] .badge-ontime { background: #052e16 !important; color: #4ade80 !important; }
    html[data-skin="dark"] .badge-late { background: #450a0a !important; color: #f87171 !important; }
    html[data-skin="dark"] .btn-outline-primary {
        background: #1e293b !important;
        border-color: #475569 !important;
        color: #fbbf24 !important;
    }
    html[data-skin="dark"] .btn-outline-primary:hover {
        background: #0f172a !important;
        border-color: #fbbf24 !important;
        color: #ffffff !important;
    }
    html[data-skin="dark"] .btn-dark { background: #1e293b !important; border-color: #475569 !important; }
    html[data-skin="dark"] .btn-dark:hover { background: #0f172a !important; border-color: #fbbf24 !important; }
    html[data-skin="dark"] #filePreviewModal .modal-header { background: #0f172a !important; color: #ffffff !important; }
    html[data-skin="dark"] #filePreviewModal .modal-title { color: #ffffff !important; }
    html[data-skin="dark"] #filePreviewModal .modal-content { border-color: #1f2937 !important; }
    /* Keterangan, catatan, bukti kehadiran - font putih */
    html[data-skin="dark"] .attendance-selector-box div[style*="var(--text-main)"],
    html[data-skin="dark"] .attendance-selector-box strong[style*="var(--text-main)"] {
        color: #ffffff !important;
    }
</style>

<div class="content-wrapper mt-4">
    <div class="container">

        <!-- Page Header -->
        <div class="page-header" style="background: transparent !important;">
            <h1 class="system-title">{{ config('app.name') }}</h1>
            <p class="system-subtitle">Sekretariat Jenderal DPR RI</p>

            <div class="live-datetime-container">
                <div class="date-display">
                    <i class="ri-calendar-todo-fill text-warning"></i>
                    <span id="liveDate">{{ \Carbon\Carbon::now()->locale('id')->translatedFormat('l, d F Y') }}</span>
                </div>
                <div class="time-display">
                    <span id="liveTime">00:00</span>
                </div>
            </div>
        </div>


        <!-- Map Section -->
        {{-- <div class="map-section bg-white p-4 rounded-5 shadow-sm mb-5 mx-auto" style="max-width: 900px; border: 1px solid #e2e8f0;">
            <p class="fw-bold mb-3 d-flex align-items-center gap-2" style="color: var(--primary-dark)">
                <i class="ri-focus-3-line ri-lg text-primary"></i>
                <span>Titik Presensi Terdeteksi</span>
            </p>
            <div id="absensiMap" style="height: 350px; border-radius: 20px;"></div>
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div id="locationStatus" class="small fw-bold text-muted">
                    <i class="ri-loader-5-line ri-spin"></i> Memvalidasi Posisi...
                </div>
                <button type="button" class="btn btn-sm btn-light rounded-pill px-3" id="retryLocationBtn">
                    <i class="ri-refresh-line"></i> Perbarui Lokasi
                </button>
            </div>
        </div> --}}

        <!-- Attendance Results (Check In & Out) -->
        @if(isset($attendance) && in_array($attendance, ['WFO', 'WFH']))
        <div class="row g-4 mb-4 justify-content-center">
            <!-- Check In Card -->
            <div class="col-md-4">
                <div class="card-attendance">
                    <div class="log-label"><i class="ri-login-box-line"></i> Waktu Masuk</div>
                    @if(isset($waktu_masuk))
                        <span class="log-time">{{ \Carbon\Carbon::parse($waktu_masuk)->format('H:i') }}</span>
                        @php $isLate = \Carbon\Carbon::parse($waktu_masuk)->format('H') >= 10; @endphp
                        <div class="badge-status {{ $isLate ? 'badge-late' : 'badge-ontime' }}">
                            <i class="ri-{{ $isLate ? 'error-warning' : 'checkbox-circle' }}-line"></i>
                            {{ $isLate ? 'Terlambat' : 'Tepat Waktu' }}
                        </div>
                    @else
                        <span class="text-muted d-block py-4">Menunggu Presensi...</span>
                    @endif
                </div>
            </div>

            <!-- Check Out Card -->
            <div class="col-md-4">
                <div class="card-attendance">
                    <div class="log-label"><i class="ri-logout-box-line"></i> Waktu Pulang</div>
                    @if(isset($waktu_keluar))
                        <span class="log-time">{{ \Carbon\Carbon::parse($waktu_keluar)->format('H:i') }}</span>
                        <div class="badge-status badge-ontime">
                            <i class="ri-check-double-line"></i> Selesai Bekerja
                        </div>
                    @else
                        <span class="text-muted d-block py-4">Belum Checkout</span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Work Duration Calculation -->
        @if(isset($waktu_masuk) && isset($waktu_keluar))
            @php
                $start = \Carbon\Carbon::parse($waktu_masuk);
                $end = \Carbon\Carbon::parse($waktu_keluar);
                $diff = $start->diff($end);
            @endphp
            <div class="row justify-content-center mb-5 mt-5">
                <div class="col-md-8">
                    <div class="duration-banner">
                        <i class="ri-timer-2-line ri-2x text-warning"></i>
                        <div>
                            <div class="duration-label">Total Durasi Kerja Hari Ini</div>
                            <div class="duration-value">
                                {{ $diff->h }} Jam {{ $diff->i }} Menit
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Keterangan dan Bukti Kehadiran -->
            @php
                // Ambil dari session flash atau dari variabel yang sudah ada
                $displayKeterangan = session('keterangan') ?? ($keterangan ?? '');
                $displayBukti = session('bukti_kehadiran') ?? ($bukti_kehadiran ?? null);
                $displayBuktiType = session('bukti_kehadiran_type') ?? ($bukti_kehadiran_type ?? null);
                $displayBuktiSize = session('bukti_kehadiran_size') ?? ($bukti_kehadiran_size ?? null);
            @endphp
            @if($displayKeterangan || $displayBukti)
            <div class="row justify-content-center mb-5">
                <div class="col-md-10">
                    <div class="attendance-selector-box">
                        <h5 class="fw-bold mb-4 d-flex align-items-center gap-2">
                            <i class="ri-file-list-3-line text-warning"></i> Detail Laporan Harian
                        </h5>

                        @if($displayKeterangan)
                        <div class="mb-4">
                            <label class="form-label fw-bold small text-muted mb-2">
                                <i class="ri-booklet-line"></i> KETERANGAN / CATATAN
                            </label>
                            <div class="border rounded-4 p-3" style="background: #f8fafc; min-height: 100px; max-height: 300px; overflow-y: auto;">
                                <div style="white-space: pre-wrap; color: var(--text-main); line-height: 1.8;">{{ $displayKeterangan }}</div>
                            </div>
                        </div>
                        @endif

                        @if($displayBukti)
                        <div class="mb-4">
                            <label class="form-label fw-bold small text-muted mb-2">
                                <i class="ri-file-paper-line"></i> BUKTI KEHADIRAN
                            </label>
                            <div class="border rounded-4 p-3" style="background: #f8fafc;">
                                @php
                                    $filePath = file_url($displayBukti);
                                    $isImage = in_array(strtolower($displayBuktiType ?? ''), ['jpg', 'jpeg', 'png', 'gif']);
                                    $fileSizeMB = isset($displayBuktiSize) ? number_format($displayBuktiSize / 1024 / 1024, 2) : '0';
                                @endphp

                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong style="color: var(--text-main);">
                                            <i class="ri-file-line"></i>
                                            {{ basename($displayBukti) }}
                                        </strong>
                                        <div class="small text-muted mt-1">
                                            Tipe: {{ strtoupper($displayBuktiType ?? 'Unknown') }} |
                                            Ukuran: {{ $fileSizeMB }} MB
                                        </div>
                                    </div>
                                    <button type="button"
                                            class="btn btn-sm btn-outline-primary rounded-pill px-4"
                                            onclick="openFileModal('{{ $filePath }}', '{{ basename($displayBukti) }}', '{{ $isImage ? 'image' : 'file' }}', '{{ strtoupper($displayBuktiType ?? 'Unknown') }}', '{{ $fileSizeMB }}')">
                                        <i class="ri-eye-line"></i> Lihat File
                                    </button>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif
        @endif
        @endif

        <!-- Attendance Results (Izin / Sakit) -->
        @if(isset($attendance) && in_array($attendance, ['Izin', 'Sakit']))
        <div class="row g-4 mb-4 justify-content-center">
            <!-- Check In Card -->
            <div class="col-md-4">
                <div class="card-attendance">
                    <div class="log-label"><i class="ri-login-box-line"></i> Waktu Masuk</div>
                    @if(isset($waktu_masuk))
                        <span class="log-time">{{ \Carbon\Carbon::parse($waktu_masuk)->format('H:i') }}</span>
                        <div class="badge-status badge-ontime">
                            <i class="ri-checkbox-circle-line"></i> Tercatat
                        </div>
                    @else
                        <span class="text-muted d-block py-4">Menunggu Presensi...</span>
                    @endif
                </div>
            </div>

            <!-- Check Out Card -->
            <div class="col-md-4">
                <div class="card-attendance">
                    <div class="log-label"><i class="ri-logout-box-line"></i> Waktu Selesai</div>
                    @if(isset($waktu_keluar))
                        <span class="log-time">{{ \Carbon\Carbon::parse($waktu_keluar)->format('H:i') }}</span>
                        <div class="badge-status badge-ontime">
                            <i class="ri-check-double-line"></i> Selesai
                        </div>
                    @else
                        <span class="text-muted d-block py-4">Belum Submit</span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Form Izin / Sakit -->
        @if(!$waktu_keluar)
        <div class="row justify-content-center mb-5">
            <div class="col-md-10">
                <div class="attendance-selector-box border-top border-warning border-4">
                    <h5 class="fw-bold mb-4 d-flex align-items-center gap-2">
                        <i class="ri-file-edit-line text-warning"></i>
                        {{ $attendance === 'Izin' ? 'Form Izin' : 'Form Sakit' }}
                    </h5>

                    <form action="{{ $attendance === 'Izin' ? route('presensiIzin') : route('presensiSakit') }}"
                          method="POST"
                          enctype="multipart/form-data">
                        @csrf

                        <div class="mb-4">
                            <label class="form-label fw-bold small text-muted">
                                <i class="ri-booklet-line"></i> KETERANGAN <span class="text-danger">*</span>
                            </label>
                            <textarea class="form-control border-2 p-3"
                                      name="keterangan"
                                      rows="5"
                                      minlength="5"
                                      required
                                      placeholder="{{ $attendance === 'Izin' ? 'Tuliskan alasan izin Anda (minimal 5 karakter)...' : 'Tuliskan alasan sakit Anda (minimal 5 karakter)...' }}"
                                      style="border-radius: 15px; resize: none;">{{ old('keterangan', $keterangan ?? '') }}</textarea>
                            @error('keterangan')
                                <div class="text-danger mt-2" style="font-size: 0.9rem;">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold small text-muted">
                                <i class="ri-attachment-2"></i> BUKTI (OPSIONAL)
                            </label>

                            @php
                                $izinSakitBukti = $bukti_kehadiran ?? null;
                                $izinSakitBuktiType = $bukti_kehadiran_type ?? null;
                                $izinSakitBuktiSize = $bukti_kehadiran_size ?? null;
                            @endphp

                            <div class="upload-box {{ $izinSakitBukti ? 'has-file' : '' }}"
                                 style="position: relative; border: 2px dashed #e2e8f0; border-radius: 15px; padding: 2rem; text-align: center; background: #f9fafb; cursor: pointer; transition: all 0.3s ease; min-height: 120px; display: flex; flex-direction: column; align-items: center; justify-content: center;">
                                <div class="success-badge"
                                     style="position: absolute; top: 15px; right: 15px; background: #10b981; color: white; width: 35px; height: 35px; border-radius: 50%; display: {{ $izinSakitBukti ? 'flex' : 'none' }}; align-items: center; justify-content: center; font-size: 1.1rem; z-index: 5; box-shadow: 0 4px 10px rgba(16, 185, 129, 0.3);">
                                    <i class="ri-check-line"></i>
                                </div>
                                <i class="ri-file-upload-line main-icon"
                                   style="font-size: 3rem; color: var(--gold-solid); margin-bottom: 1rem; {{ $izinSakitBukti ? 'display: none;' : '' }}"></i>

                                <span class="upload-text"
                                      style="font-weight: 700; font-size: 0.9rem; color: var(--text-main); display: block; margin-bottom: 0.5rem;">
                                    {{ $izinSakitBukti ? basename($izinSakitBukti) : 'Unggah Bukti (Opsional)' }}
                                </span>

                                @if($izinSakitBukti)
                                    @php
                                        $fileSizeMB = isset($izinSakitBuktiSize) ? number_format($izinSakitBuktiSize / 1024 / 1024, 2) : '0';
                                    @endphp
                                    <span class="file-info" style="font-size: 0.75rem; color: var(--text-muted);">
                                        Tipe: {{ strtoupper($izinSakitBuktiType ?? 'Unknown') }} | Ukuran: {{ $fileSizeMB }} MB
                                    </span>
                                @else
                                    <span class="file-info" style="font-size: 0.75rem; color: var(--text-muted);">
                                        Format: PDF, JPG, PNG, DOC, DOCX, XLS, XLSX, CSV (Max 1MB)
                                    </span>
                                @endif

                                <input type="file"
                                       name="bukti_kehadiran"
                                       accept="image/*,application/pdf,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.ms-excel,text/csv,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document"
                                       onchange="handleBuktiUpload(this)"
                                       style="position: absolute; inset: 0; opacity: 0; z-index: 10; cursor: pointer;">
                            </div>

                            @error('bukti_kehadiran')
                                <div class="text-danger mt-2" style="font-size: 0.9rem;">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-dark w-100 fw-bold py-3 rounded-4">
                            Submit {{ $attendance }} <i class="ri-arrow-right-up-line ms-2"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endif

        <!-- Detail Izin / Sakit setelah submit -->
        @if($waktu_keluar)
        @php
            $displayKeterangan = session('keterangan') ?? ($keterangan ?? '');
            $displayBukti = session('bukti_kehadiran') ?? ($bukti_kehadiran ?? null);
        @endphp
        <div class="row justify-content-center mb-5">
            <div class="col-md-10">
                <div class="attendance-selector-box">
                    <h5 class="fw-bold mb-4 d-flex align-items-center gap-2">
                        <i class="ri-file-list-3-line text-warning"></i> Detail {{ $attendance }}
                    </h5>

                    @if(!empty($displayKeterangan))
                    <div class="mb-4">
                        <label class="form-label fw-bold small text-muted mb-2">
                            <i class="ri-booklet-line"></i> KETERANGAN
                        </label>
                        <div class="border rounded-4 p-3" style="background: #f8fafc; min-height: 100px; max-height: 300px; overflow-y: auto;">
                            <div style="white-space: pre-wrap; color: var(--text-main); line-height: 1.8;">{{ $displayKeterangan }}</div>
                        </div>
                    </div>
                    @endif

                    <div class="mb-2">
                        <label class="form-label fw-bold small text-muted mb-2">
                            <i class="ri-file-paper-line"></i> BUKTI (OPSIONAL)
                        </label>
                        @if(!empty($displayBukti))
                            @php
                                $fileUrl = file_url($displayBukti);
                                $isImageIzin = in_array(strtolower(pathinfo($displayBukti, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'gif']);
                            @endphp
                            <button type="button"
                                    class="btn btn-sm btn-outline-primary rounded-pill px-4"
                                    onclick="openFileModal('{{ $fileUrl }}', '{{ basename($displayBukti) }}', '{{ $isImageIzin ? 'image' : 'file' }}', '{{ strtoupper(pathinfo($displayBukti, PATHINFO_EXTENSION)) }}', '0')">
                                <i class="ri-eye-line"></i> Lihat File
                            </button>
                        @else
                            <div class="text-muted" style="font-size: 0.95rem;">Tidak ada file bukti yang diunggah.</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endif
        @endif

        <!-- Attendance Selection Form -->
        @if(!isset($attendance))
            @if(isset($pesertaStatus) && $pesertaStatus == 1)
                {{-- Status 1: Belum Mulai Magang — tampilkan info, blokir check-in --}}
                <div class="row justify-content-center mb-5">
                    <div class="col-lg-8">
                        <div class="attendance-selector-box" style="border: 2px solid #f59e0b; background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%);">
                            <div class="text-center">
                                <div style="font-size: 4rem; margin-bottom: 1rem;">⏳</div>
                                <h5 class="fw-bold mb-3" style="color: #92400e;">Periode Magang Belum Dimulai</h5>
                                <p class="mb-3" style="color: #78350f; font-size: 0.95rem; line-height: 1.7;">
                                    Anda belum dapat melakukan <strong>Check-In</strong> karena periode Magang/PKL Anda belum dimulai.
                                    <br>Fitur absensi akan aktif secara otomatis saat status Anda berubah menjadi <strong>"Aktif Magang"</strong>.
                                </p>
                                @php
                                    $pesertaData = \Modules\Magang\App\Models\Magang\Peserta::where('id_user', auth()->id())->with('lamaran')->first();
                                    $tanggalMulai = $pesertaData && $pesertaData->lamaran && $pesertaData->lamaran->tanggal_mulai
                                        ? \Carbon\Carbon::parse($pesertaData->lamaran->tanggal_mulai)->locale('id')->translatedFormat('l, d F Y')
                                        : null;
                                @endphp
                                @if($tanggalMulai)
                                    <div class="d-inline-block px-4 py-2 rounded-pill mt-2" style="background: rgba(146, 64, 14, 0.1); border: 1px solid rgba(146, 64, 14, 0.2);">
                                        <i class="ri-calendar-event-line" style="color: #b45309;"></i>
                                        <span style="color: #92400e; font-weight: 700;">Tanggal Mulai: {{ $tanggalMulai }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                icon: 'info',
                                title: 'Belum Bisa Check-In',
                                html: 'Status Anda saat ini masih <strong>"Belum Mulai Magang"</strong>.<br><br>Fitur absensi (Check-In, Check-Out, Izin, Sakit) <strong>belum dapat digunakan</strong> sampai periode Magang/PKL Anda dimulai dan status berubah menjadi <strong>"Aktif Magang"</strong>.'
                                    @if(isset($tanggalMulai))
                                    + '<br><br><div style="background:#fffbeb;padding:10px 15px;border-radius:10px;border:1px solid #fcd34d;margin-top:5px;"><i class="ri-calendar-event-line"></i> <strong>Tanggal Mulai:</strong> {{ $tanggalMulai }}</div>'
                                    @endif
                                    ,
                                confirmButtonText: 'Saya Mengerti',
                                confirmButtonColor: '#b08d48',
                                allowOutsideClick: true,
                            });
                        }
                    });
                </script>
            @else
                {{-- Status 2 atau 3: tampilkan form check-in normal --}}
                <div class="row justify-content-center mb-5">
                    <div class="col-lg-6">
                        <div class="attendance-selector-box">
                            <h5 class="fw-bold text-center mb-4">Mulai Aktivitas Presensi</h5>
                            <form id="attendanceForm" method="POST" action="{{ route('Absensi Form') }}">
                                @csrf
                                <input type="hidden" name="latitude" id="absensiLatitude">
                                <input type="hidden" name="longitude" id="absensiLongitude">

                                <div class="mb-4">
                                    <label class="form-label fw-bold text-muted small">STATUS KEHADIRAN</label>
                                    <select class="form-select border-2 p-3 rounded-4 shadow-sm" name="jenis_kehadiran" required>
                                        <option value="">-- Pilih Status --</option>
                                        <option value="WFO">🏢 Work From Office (WFO)</option>
                                        <option value="WFH">🏠 Work From Home (WFH)</option>
                                        <option value="Izin">📝 Izin</option>
                                        <option value="Sakit">🤒 Sakit</option>
                                    </select>
                                </div>

                                <button type="submit" class="btn-gold-luxury w-100 py-3">
                                    Check-In Sekarang <i class="ri-arrow-right-up-line ms-2"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endif
        @endif

        @php
            // Definisikan variabel di scope yang lebih luas agar bisa digunakan di JavaScript
            $existingBukti = $bukti_kehadiran ?? null;
            $existingBuktiType = $bukti_kehadiran_type ?? null;
            $existingBuktiSize = $bukti_kehadiran_size ?? null;
            $filePath = $existingBukti ? file_url($existingBukti) : null;
            $isImage = $existingBukti && in_array(strtolower($existingBuktiType ?? ''), ['jpg', 'jpeg', 'png', 'gif']);
            // Checkout harus berdasarkan data yang SUDAH tersimpan di database (bukan isi input)
            $canCheckoutDb = !empty(trim($keterangan ?? '')) && !empty($existingBukti);
        @endphp

        <!-- Daily Report Form -->
        @if(isset($attendance) && ($attendance == 'WFO' || $attendance == 'WFH') && !$waktu_keluar)
            <div class="attendance-selector-box mb-5 border-top border-warning border-4">
                <h5 class="fw-bold mb-4 d-flex align-items-center gap-2">
                    <i class="ri-booklet-line text-warning"></i> Laporan Kegiatan Harian
                </h5>
                <form action="{{ route('presensiPulang') }}" method="POST" id="formPresensiPulang">
                    @csrf
                    <input type="hidden" name="jenis_kehadiran" value="{{ $attendance }}">
                    <div class="mb-4">
                        <textarea class="form-control border-2 p-3" name="keterangan" id="keterangan" rows="5"
                        placeholder="Keterangan Laporan Kegiatan Harian (contoh: Membuat Presentasi, Membuat Dokumentasi, dll)" style="border-radius: 15px; resize: none;">{{ old('keterangan', $keterangan ?? '') }}</textarea>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold small text-muted">DOKUMENTASI / BUKTI</label>
                        <div class="upload-box {{ $existingBukti ? 'has-file' : '' }}" id="box-buktiKeberadaan" style="position: relative; border: 2px dashed #e2e8f0; border-radius: 15px; padding: 2rem; text-align: center; background: #f9fafb; cursor: pointer; transition: all 0.3s ease; min-height: 120px; display: flex; flex-direction: column; align-items: center; justify-content: center;">
                            <div class="success-badge" style="position: absolute; top: 15px; right: 15px; background: #10b981; color: white; width: 35px; height: 35px; border-radius: 50%; display: {{ $existingBukti ? 'flex' : 'none' }}; align-items: center; justify-content: center; font-size: 1.1rem; z-index: 5; box-shadow: 0 4px 10px rgba(16, 185, 129, 0.3);">
                                <i class="ri-check-line"></i>
                            </div>
                            <i class="ri-file-upload-line main-icon" style="font-size: 3rem; color: var(--gold-solid); margin-bottom: 1rem; {{ $existingBukti ? 'display: none;' : '' }}"></i>
                            <span class="upload-text" style="font-weight: 700; font-size: 0.9rem; color: var(--text-main); display: block; margin-bottom: 0.5rem;">{{ $existingBukti ? basename($existingBukti) : 'Unggah Bukti Kehadiran' }}</span>
                            @if($existingBukti)
                                @php
                                    $fileSizeMB = isset($existingBuktiSize) ? number_format($existingBuktiSize / 1024 / 1024, 2) : '0';
                                @endphp
                                <span class="file-info" style="font-size: 0.75rem; color: var(--text-muted);">Tipe: {{ strtoupper($existingBuktiType ?? 'Unknown') }} | Ukuran: {{ $fileSizeMB }} MB</span>
                            @else
                                <span class="file-info" style="font-size: 0.75rem; color: var(--text-muted);">Format: PDF, JPG, PNG, DOC, DOCX, XLS, XLSX, CSV (Max 1MB)</span>
                            @endif
                            <input type="file" name="bukti_kehadiran" id="buktiKeberadaan" accept="image/*,application/pdf,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.ms-excel,text/csv,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document" onchange="handleBuktiUpload(this)" style="position: absolute; inset: 0; opacity: 0; z-index: 10; cursor: pointer;">
                        </div>
                    </div>
                    <div class="d-flex flex-wrap gap-3">
                        <button type="button" class="btn-gold-luxury flex-grow-1" onclick="simpanSemua()">
                            <i class="ri-save-3-line"></i> Simpan Draft Laporan
                        </button>
                        <button type="submit" class="btn-checkout-pulang flex-grow-1" onclick="return validateCheckout(event)">
                            <i class="ri-logout-circle-r-line me-2"></i> Checkout Pulang
                        </button>
                    </div>
                </form>
            </div>
        @endif

    </div>
</div>

<!-- Modal Preview File (Global) -->
<div class="modal fade" id="filePreviewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content overflow-hidden" style="border-radius: 20px; border: 1px solid #e2e8f0;">
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

<script>
    // Premium Clock: Only Hours and Minutes
    function updateClock() {
        const now = new Date();
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        document.getElementById('liveTime').textContent = `${hours}:${minutes}`;
    }

    setInterval(updateClock, 10000); // Check every 10 seconds
    updateClock();

    // Function to open file preview modal
    function openFileModal(filePath, fileName, fileType, fileTypeExt, fileSizeMB) {
        const lowerUrl = String(filePath || '').toLowerCase();
        // Cek ekstensi pada bagian path saja — abaikan query string presigned URL S3 (mis. ?X-Amz-Signature=...)
        const isImage = lowerUrl.match(/\.(jpg|jpeg|png|gif|webp)(\?|#|$)/);

        document.getElementById('filePreviewModalLabel').innerHTML = `<i class="ri-file-search-line me-2"></i> ${fileName}`;

        const frame = document.getElementById('filePreviewFrame');
        const img = document.getElementById('filePreviewImage');

        if (isImage) {
            frame.style.display = 'none';
            frame.src = '';
            img.src = filePath;
            img.style.display = 'block';
        } else {
            img.style.display = 'none';
            img.src = '';
            frame.src = filePath;
            frame.style.display = 'block';
        }

        const previewModal = new bootstrap.Modal(document.getElementById('filePreviewModal'));
        previewModal.show();
    }

    // Clean up modal when closed
    document.addEventListener('DOMContentLoaded', function() {
        const modalEl = document.getElementById('filePreviewModal');
        if (modalEl) {
            // Fix for Bootstrap Modals Z-Index
            document.addEventListener('show.bs.modal', function(event) {
                const modal = event.target;
                if (modal && modal.parentElement !== document.body) {
                    document.body.appendChild(modal);
                }
            });

            // Clear preview on modal close
            modalEl.addEventListener('hidden.bs.modal', function () {
                document.getElementById('filePreviewFrame').src = '';
                document.getElementById('filePreviewImage').src = '';
            });
        }
    });

    // Flag checkout berdasarkan data DB saat halaman dirender.
    // Akan di-set menjadi true setelah "Simpan Draft" sukses (karena itu berarti DB sudah ter-update).
    let canCheckoutDb = {{ $canCheckoutDb ? 'true' : 'false' }};

    // Handle file upload untuk bukti kehadiran (tanpa preview) + validasi ukuran (maks 1 MB)
    function handleBuktiUpload(input) {
        const box = input.closest('.upload-box');
        const mainIcon = box.querySelector('.main-icon');
        const successBadge = box.querySelector('.success-badge');
        const uploadText = box.querySelector('.upload-text');
        const fileInfo = box.querySelector('.file-info');

        if (input.files && input.files[0]) {
            const file = input.files[0];
            const maxMb = 1;
            const sizeMb = file.size / 1024 / 1024;
            if (sizeMb > maxMb) {
                const message = 'Ukuran file maksimal ' + maxMb + ' MB. File ini: ' + sizeMb.toFixed(2) + ' MB.';
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'error',
                        title: 'File terlalu besar',
                        text: message
                    });
                } else {
                    alert(message);
                }
                input.value = '';
                box.classList.remove('has-file');
                if (mainIcon) mainIcon.style.display = 'block';
                if (successBadge) successBadge.style.display = 'none';
                if (uploadText) uploadText.textContent = box.id === 'box-buktiKeberadaan' ? 'Unggah Bukti Kehadiran' : 'Unggah Bukti (Opsional)';
                if (fileInfo) {
                    fileInfo.textContent = 'Format: PDF, JPG, PNG, DOC, DOCX, XLS, XLSX, CSV (Max 1MB)';
                }
                return;
            }
            const fileSizeMB = (file.size / 1024 / 1024).toFixed(2);
            const fileType = file.name.split('.').pop().toUpperCase();

            box.classList.add('has-file');
            if (mainIcon) mainIcon.style.display = 'none';
            if (successBadge) successBadge.style.display = 'flex';
            if (uploadText) uploadText.textContent = file.name;
            if (fileInfo) {
                fileInfo.textContent = `Tipe: ${fileType} | Ukuran: ${fileSizeMB} MB`;
            }
        } else {
            box.classList.remove('has-file');
            if (mainIcon) mainIcon.style.display = 'block';
            if (successBadge) successBadge.style.display = 'none';
            if (uploadText) uploadText.textContent = 'Unggah Bukti Kehadiran';
            if (fileInfo) {
                fileInfo.textContent = 'Format: PDF, JPG, PNG, DOC, DOCX, XLS, XLSX, CSV (Max 1MB)';
            }
        }
    }

    function simpanSemua() {
        const ket = document.getElementById('keterangan').value.trim();
        const buktiFileInput = document.getElementById('buktiKeberadaan');
        const buktiFile = buktiFileInput ? buktiFileInput.files[0] : null;
        const hasExistingFile = {{ isset($existingBukti) && !empty($existingBukti) ? 'true' : 'false' }};

        if(!ket) {
            Swal.fire({
                icon: 'warning',
                title: 'Perhatian',
                text: 'Silahkan isi laporan kegiatan terlebih dahulu.',
                confirmButtonColor: '#b08d48',
                confirmButtonText: 'OK'
            });
            return;
        }

        // Validasi: karakter pertama laporan kerja tidak boleh simbol
        const firstChar = ket.charAt(0);
        const isSymbol = !/[a-zA-Z0-9]/.test(firstChar);

        if (isSymbol) {
            Swal.fire({
                icon: 'error',
                title: 'Laporan Kerja Tidak Valid',
                html: 'Laporan kerja tidak boleh dimulai dengan simbol atau karakter khusus.<br><br>Silakan perbaiki laporan kerja Anda.',
                confirmButtonColor: '#b08d48',
                confirmButtonText: 'OK'
            });
            return;
        }

        // Jika belum ada file di database dan belum upload file baru, minta upload
        if(!hasExistingFile && !buktiFile) {
            Swal.fire({
                icon: 'warning',
                title: 'Perhatian',
                text: 'Silahkan pilih file bukti kehadiran terlebih dahulu.',
                confirmButtonColor: '#b08d48',
                confirmButtonText: 'OK'
            });
            return;
        }

        // Simpan keterangan dan bukti kehadiran sekaligus
        const formData = new FormData();
        formData.append('keterangan', ket);
        // Hanya kirim file jika ada file baru yang diupload
        if (buktiFile) {
            formData.append('bukti_kehadiran', buktiFile);
        }
        formData.append('_token', '{{ csrf_token() }}');

        Swal.fire({
            title: 'Menyimpan...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        fetch('{{ route("saveDailyNotes") }}', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(err => {
                    throw new Error(err.message || 'Terjadi kesalahan saat menyimpan');
                });
            }
            return response.json();
        })
        .then(data => {
            if (data.success === true || data.success === 'true') {
                // Jika saveDailyNotes sukses, artinya data sudah tersimpan di DB => checkout boleh.
                canCheckoutDb = true;

                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    html: '<div style="text-align: center;"><strong>Draft Laporan tersimpan.</strong><br><br>Jangan lupa klik Checkout Pulang untuk mengakhiri shift.</div>',
                    confirmButtonColor: '#b08d48',
                    confirmButtonText: 'OK',
                    width: '600px'
                });
                if (document.getElementById('keterangan_hidden')) {
                    document.getElementById('keterangan_hidden').value = ket;
                }
            } else {
                let errorMsg = 'Terjadi kesalahan saat menyimpan draft laporan.';
                if (data.errors) {
                    const errorList = Object.values(data.errors).flat().join('<br>');
                    errorMsg = errorList;
                } else if (data.message) {
                    errorMsg = data.message;
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal Menyimpan',
                    html: errorMsg,
                    confirmButtonColor: '#b08d48',
                    confirmButtonText: 'OK'
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Terjadi Kesalahan',
                html: error.message || 'Terjadi kesalahan saat menyimpan draft laporan. Silakan coba lagi.',
                confirmButtonColor: '#b08d48',
                confirmButtonText: 'OK'
            });
        });
    }

    function validateCheckout(event) {
        event.preventDefault();
        const buktiFileInput = document.getElementById('buktiKeberadaan');
        const buktiFile = buktiFileInput ? buktiFileInput.files[0] : null;
        const hasExistingFile = {{ isset($existingBukti) && !empty($existingBukti) ? 'true' : 'false' }};

        // Tombol checkout harus mengecek DATABASE: keterangan & bukti sudah tersimpan atau belum.
        // Jika user baru isi input tapi belum klik "Simpan Draft", maka tetap ditolak.
        if (!canCheckoutDb) {
            Swal.fire({
                icon: 'warning',
                title: 'Perhatian',
                html: 'Checkout hanya bisa dilakukan jika <strong>keterangan</strong> dan <strong>bukti kehadiran</strong> sudah tersimpan.<br><br>Silakan klik <strong>"Simpan Draft Laporan"</strong> terlebih dahulu.',
                confirmButtonColor: '#b08d48',
                confirmButtonText: 'OK'
            });
            return false;
        }

        // Validasi: karakter pertama laporan kerja tidak boleh simbol
        // Ambil keterangan dari database yang sudah tersimpan (bukan dari input)
        @php
            $keteranganDb = isset($keterangan) && !empty(trim($keterangan ?? '')) ? trim($keterangan) : '';
        @endphp
        const keteranganFromDb = @json($keteranganDb);

        if (keteranganFromDb && keteranganFromDb.length > 0) {
            const firstChar = keteranganFromDb.charAt(0);
            // Cek apakah karakter pertama adalah simbol (bukan huruf atau angka)
            // Simbol adalah karakter yang bukan huruf (a-z, A-Z) dan bukan angka (0-9)
            const isSymbol = !/[a-zA-Z0-9]/.test(firstChar);

            if (isSymbol) {
                Swal.fire({
                    icon: 'error',
                    title: 'Laporan Kerja Tidak Valid',
                    html: 'Laporan kerja tidak boleh dimulai dengan simbol atau karakter khusus.<br><br>Silakan perbaiki laporan kerja Anda dan klik <strong>"Simpan Draft Laporan"</strong> terlebih dahulu.',
                    confirmButtonColor: '#b08d48',
                    confirmButtonText: 'OK'
                });
                return false;
            }
        }

        // Tampilkan konfirmasi dengan keterangan dan bukti kehadiran
        let fileSize = '';
        let fileType = '';
        let fileName = '';

        if (hasExistingFile) {
            // Gunakan data dari database
            fileName = '{{ $existingBukti ? basename($existingBukti) : "" }}';
            fileType = '{{ strtoupper($existingBuktiType ?? "Unknown") }}';
            fileSize = '{{ isset($existingBuktiSize) ? number_format($existingBuktiSize / 1024 / 1024, 2) : "0" }}';
        } else if (buktiFile) {
            // Gunakan data dari file baru yang diupload
            fileSize = (buktiFile.size / 1024 / 1024).toFixed(2);
            fileType = buktiFile.type || 'Unknown';
            fileName = buktiFile.name;
        }

        // Preview file jika gambar (hanya untuk file baru yang diupload)
        let filePreview = '';
        if (buktiFile && buktiFile.type && buktiFile.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function(e) {
                Swal.fire({
                    icon: 'question',
                    title: 'Konfirmasi Checkout Pulang',
                    html: `
                        <div style="text-align: center;">
                            <p><strong>Apakah Anda yakin ingin checkout pulang?</strong></p>

                        </div>
                    `,
                    showCancelButton: true,
                    confirmButtonColor: '#10b981',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: 'Ya, Checkout',
                    cancelButtonText: 'Batal',
                    width: '700px'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Pastikan data sudah disimpan sebelum checkout
                        Swal.fire({
                            icon: 'info',
                            title: 'Memproses Checkout',
                            html: '<div style="text-align: center;"><p><strong>Checkout diproses...</strong></p></div>',
                            showConfirmButton: false,
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                                // Submit form setelah delay singkat
                                setTimeout(() => {
                                    document.getElementById('formPresensiPulang').submit();
                                }, 1000);
                            }
                        });
                    }
                });
            };
            reader.readAsDataURL(buktiFile);
            return false; // Return false karena akan submit setelah preview selesai
        } else {
            // Tampilkan konfirmasi untuk file non-image atau file dari database
            let buktiHtml = '';
            if (hasExistingFile) {
                // File dari database
                const filePath = '{{ $filePath ?? "" }}';
                const isImage = {{ $isImage ? 'true' : 'false' }};

                if (isImage && filePath) {
                    buktiHtml = `
                        <div style="background: #f8fafc; padding: 15px; border-radius: 8px; margin-top: 10px;">
                            <strong>Bukti Kehadiran:</strong><br>
                            <div style="margin-top: 8px;">
                                <img src="${filePath}" style="max-width: 100%; max-height: 200px; border-radius: 8px; border: 2px solid #e2e8f0;">
                                <div style="margin-top: 8px; font-size: 0.9em; color: #64748b;">
                                    <strong>${fileName}</strong><br>
                                    Ukuran: ${fileSize} MB | Tipe: ${fileType}
                                </div>
                            </div>
                        </div>
                    `;
                } else {
                    buktiHtml = `
                        <div style="background: #f8fafc; padding: 15px; border-radius: 8px; margin-top: 10px;">
                            <strong>Bukti Kehadiran:</strong><br>
                            <div style="margin-top: 8px; font-size: 0.9em; color: #64748b;">
                                <i class="ri-file-line"></i> <strong>${fileName}</strong><br>
                                Ukuran: ${fileSize} MB | Tipe: ${fileType}
                            </div>
                        </div>
                    `;
                }
            } else {
                // File baru non-image
                buktiHtml = `
                    <div style="background: #f8fafc; padding: 15px; border-radius: 8px; margin-top: 10px;">
                        <strong>Bukti Kehadiran:</strong><br>
                        <div style="margin-top: 8px; font-size: 0.9em; color: #64748b;">
                            <i class="ri-file-line"></i> <strong>${fileName}</strong><br>
                            Ukuran: ${fileSize} MB | Tipe: ${fileType}
                        </div>
                    </div>
                `;
            }

            Swal.fire({
                icon: 'question',
                title: 'Konfirmasi Checkout Pulang',
                html: `
                    <div style="text-align: center;">
                        <p><strong>Apakah Anda yakin ingin checkout pulang?</strong></p>
                    </div>
                `,
                showCancelButton: true,
                confirmButtonColor: '#10b981',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Checkout',
                cancelButtonText: 'Batal',
                width: '700px'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Pastikan data sudah disimpan sebelum checkout
                    Swal.fire({
                        icon: 'info',
                        title: 'Memproses Checkout',
                        html: '<div style="text-align: center;"><p><strong>Checkout diproses...</strong></p></div>',
                        showConfirmButton: false,
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                            // Submit form setelah delay singkat
                            setTimeout(() => {
                                document.getElementById('formPresensiPulang').submit();
                            }, 1000);
                        }
                    });
                }
            });
        }

        return false;
    }
</script>
@endsection

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@endpush

@push('script')
{{-- <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script> --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
{{-- <script src="{{ asset('js/absensi-map.js') }}"></script> --}}
@endpush
