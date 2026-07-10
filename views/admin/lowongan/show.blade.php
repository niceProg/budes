@extends('layouts.app')

@section('title', 'Detail Lowongan | Admin - SMART Setjen DPR RI')
@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

    :root {
        --primary-dark: #0f172a;
        --accent-gold: linear-gradient(135deg, #c5a059 0%, #8e6d2f 100%);
        --gold-solid: #b08d48;
        --gold-light: #fdfaf3;
        --text-main: #334155;
        --text-muted: #64748b;
        --bg-slate: #f8fafc;
        --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .content-wrapper {
        position: relative;
        background: var(--bg-slate);
        min-height: 100vh;
        padding: 2rem 1rem 4rem;
    }

    /* Background Pattern */
    .content-wrapper::before {
        content: '';
        position: fixed;
        top: 0; left: 0; width: 100%; height: 100%;
        background-image: url('{{ asset("theme/admin-dashbyte/dist/assets/img/batiknew.png") }}');
        background-size: 400px;
        opacity: 0.03;
        z-index: 0;
        pointer-events: none;
    }

    .page-header {
        position: relative;
        z-index: 1;
        margin-bottom: 2rem;
    }

    .page-title {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-weight: 800;
        font-size: 2rem;
        color: var(--primary-dark);
        letter-spacing: -0.025em;
    }

    .modern-card {
        background: white;
        border-radius: 24px;
        padding: 2.5rem;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.04), 0 8px 10px -6px rgba(0, 0, 0, 0.04);
        border: 1px solid rgba(226, 232, 240, 0.8);
        position: relative;
        z-index: 1;
    }

    /* Top Layout Section */
    .lowongan-header-grid {
        display: grid;
        grid-template-columns: auto 1fr auto;
        gap: 2.5rem;
        align-items: start;
    }

    .img-container {
        width: 160px;
        height: 160px;
        border-radius: 20px;
        overflow: hidden;
        border: 1px solid #e2e8f0;
        background: #f8fafc;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 12px rgba(0,0,0,0.03);
    }

    .img-container img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .placeholder-icon {
        font-size: 3.5rem;
        color: var(--gold-solid);
        opacity: 0.5;
    }

    /* Middle Info */
    .info-main h2 {
        font-weight: 800;
        color: var(--primary-dark);
        font-size: 1.75rem;
        margin-bottom: 0.75rem;
        line-height: 1.2;
    }

    .meta-item {
        display: flex;
        align-items: center;
        gap: 0.6rem;
        color: var(--text-muted);
        margin-bottom: 0.5rem;
        font-weight: 500;
    }

    .meta-item i {
        color: var(--gold-solid);
        font-size: 1.1rem;
    }

    /* Right Info (Badges) */
    .info-side {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
        min-width: 220px;
    }

    .status-badge {
        background: var(--gold-light);
        border: 1px solid rgba(176, 141, 72, 0.2);
        padding: 1rem;
        border-radius: 16px;
    }

    .status-label {
        display: block;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: var(--gold-solid);
        font-weight: 700;
        margin-bottom: 0.25rem;
    }

    .status-value {
        display: block;
        font-weight: 700;
        color: var(--primary-dark);
        font-size: 0.95rem;
    }

    /* Content Area */
    .content-divider {
        height: 5px;
        background: var(--gold-solid);
        margin: 2.5rem 0;
        border: none;
    }

    .detail-section-label {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--primary-dark);
        margin-bottom: 1.25rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .detail-section-label::before {
        content: '';
        width: 4px;
        height: 24px;
        background: var(--accent-gold);
        border-radius: 4px;
    }

    .rich-content {
        color: var(--text-main);
        line-height: 1.8;
        font-size: 1.05rem;
    }

    .rich-content ul, .rich-content ol {
        padding-left: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .rich-content li {
        margin-bottom: 0.5rem;
    }

    /* Button */
    .btn-back {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1.25rem;
        background: #ffffff;
        color: #334155;
        border: 1.5px solid #e2e8f0;
        border-radius: 12px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s;
        box-shadow: 0 1px 3px rgba(0,0,0,0.04);
    }

    .btn-back:hover {
        background: #0f172a;
        color: #ffffff;
        border-color: #0f172a;
        transform: translateX(-3px);
    }

    /* Responsive */
    @media (max-width: 992px) {
        .lowongan-header-grid {
            grid-template-columns: 1fr;
            text-align: center;
        }
        .img-container { margin: 0 auto; }
        .meta-item { justify-content: center; }
        .info-side { align-items: center; }
        .detail-section-label { justify-content: center; }
    }
</style>

<div class="content-wrapper">
    <div class="container-fluid">
        <!-- Breadcrumb & Title -->
        <div class="page-header">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-2">
                    <li class="breadcrumb-item"><a href="{{ route('lowongan.index') }}">Lowongan</a></li>
                    <li class="breadcrumb-item active fw-bold" style="color: var(--gold-solid)" aria-current="page">Detail</li>
                </ol>
            </nav>
            <h1 class="page-title">Detail Lowongan</h1>
        </div>

        <div class="modern-card">
            <!-- Header Grid -->
            <div class="lowongan-header-grid">
                <!-- 1. Foto -->
                <div class="img-container">
                    @if($data->foto)
                        <img src="{{ file_url('lowongan/' . $data->foto) }}" alt="{{ $data->title }}">
                    @else
                        <div class="placeholder-icon">
                            <i class="ri-briefcase-line"></i>
                        </div>
                    @endif
                </div>

                <!-- 2. Info Utama -->
                <div class="info-main">
                    <h2>{{ $data->title }}</h2>

                    <div class="meta-item">
                        <i class="ri-building-line"></i>
                        <span>Satuan Kerja : {{ $data->satker->nama ?? 'Umum' }}</span>
                    </div>

                    <div class="meta-item">
                        <i class="ri-group-line"></i>
                        <span>Kebutuhan : {{ $data->jumlah_posisi ? $data->jumlah_posisi . ' Posisi' : '-' }} </span>
                    </div>

                    <div class="meta-item">
                        <i class="ri-graduation-cap-line"></i>
                        <span class="d-inline-flex flex-wrap gap-2 align-items-center">
                            Jenis Pendidikan :
                            @foreach($data->jenisList() as $jenis)
                                @if($jenis === 'Magang')
                                    <span style="font-size:0.74rem; font-weight:800; padding:3px 10px; border-radius:999px; background:#eef2ff; color:#4338ca; border:1px solid #c7d2fe;">Magang</span>
                                @else
                                    <span style="font-size:0.74rem; font-weight:800; padding:3px 10px; border-radius:999px; background:#ecfdf5; color:#047857; border:1px solid #a7f3d0;">PKL</span>
                                @endif
                            @endforeach
                        </span>
                    </div>
                </div>

                <!-- 3. Deadline & Created -->
                <div class="info-side">
                    <div class="status-badge">
                        <span class="status-label">Batas Lamaran</span>
                        <span class="status-value text-danger">
                            <i class="ri-calendar-event-line me-1"></i>
                            {{ \Carbon\Carbon::parse($data->deadline)->locale('id')->translatedFormat('d F Y') ?? 'Tidak Terbatas' }}
                        </span>
                    </div>

                    @if($data->tanggal_mulai || $data->tanggal_selesai)
                        <div class="status-badge" style="background: #f0fdf4; border-color: #bbf7d0;">
                            <span class="status-label" style="color: #16a34a;">Periode Magang</span>
                            <span class="status-value" style="font-size: 0.85rem;">
                                <i class="ri-calendar-check-line me-1"></i>
                                {{ $data->tanggal_mulai ? \Carbon\Carbon::parse($data->tanggal_mulai)->locale('id')->translatedFormat('d M Y') : '-' }}
                                &nbsp;s/d&nbsp;
                                {{ $data->tanggal_selesai ? \Carbon\Carbon::parse($data->tanggal_selesai)->locale('id')->translatedFormat('d M Y') : '-' }}
                            </span>
                        </div>
                    @endif

                    <div class="status-badge" style="background: #f1f5f9; border-color: #e2e8f0;">
                        <span class="status-label" style="color: var(--text-muted);">Dipublikasikan</span>
                        <span class="status-value">
                            <i class="ri-time-line me-1"></i>
                            {{ $data->created_at->locale('id')->translatedFormat('d F Y') }}
                        </span>
                    </div>
                </div>
            </div>

            <hr class="content-divider">

            <!-- Detail Section -->
            <div class="lowongan-detail-section">
                <div class="detail-section-label">Deskripsi & Kualifikasi Pekerjaan</div>
                <div class="rich-content">
                    {!! clean($data->detail) !!}
                </div>
            </div>

            <!-- Footer Action -->
            <div class="d-flex justify-content-between align-items-center">
                <a href="{{ route('lowongan.index') }}" class="btn-back">
                    <i class="ri-arrow-left-line"></i> Kembali ke Daftar
                </a>


            </div>
        </div>
    </div>
</div>
@endsection
