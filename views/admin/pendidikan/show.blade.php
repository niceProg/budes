@extends('layouts.app')

@section('title', 'Detail Pendidikan | Admin - SMART Setjen DPR RI')
@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

    :root {
        --primary-dark: #0f172a;
        --accent-gold: linear-gradient(135deg, #c5a059 0%, #8e6d2f 100%);
        --gold-solid: #b08d48;
        --gold-soft: rgba(176, 141, 72, 0.1);
        --text-main: #1e293b;
        --text-muted: #64748b;
        --white: #ffffff;
        --bg-slate: #f8fafc;
        --transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
    }

    .content-wrapper {
        position: relative;
        background: var(--bg-slate);
        min-height: 100vh;
        padding-bottom: 3rem;
    }

    /* Background Batik Eksklusif */
    .content-wrapper::before {
        content: '';
        position: fixed;
        top: 0; left: 0;
        width: 100%; height: 100%;
        background-image: url('{{ asset("theme/admin-dashbyte/dist/assets/img/batiknew.png") }}');
        background-size: 400px;
        opacity: 0.04;
        z-index: 0;
        pointer-events: none;
    }

    .page-header {
        margin-bottom: 2.5rem;
        position: relative;
        z-index: 1;
    }

    .page-title {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-weight: 800;
        font-size: 2.25rem;
        color: var(--primary-dark);
        letter-spacing: -0.025em;
    }

    .breadcrumb-item { font-size: 0.85rem; }
    .breadcrumb-item a { color: var(--text-muted); text-decoration: none; transition: var(--transition); }
    .breadcrumb-item a:hover { color: var(--gold-solid); }

    /* Modern Detail Card Glassmorphism */
    .modern-card {
        background: rgba(255, 255, 255, 0.85);
        backdrop-filter: blur(20px);
        border-radius: 24px;
        padding: 3rem;
        box-shadow: 0 20px 40px rgba(0,0,0,0.06);
        border: 1px solid rgba(255, 255, 255, 0.7);
        position: relative;
        z-index: 1;
        overflow: hidden;
    }

    .modern-card::before {
        content: '';
        position: absolute;
        top: 0; left: 0;
        width: 100%; height: 6px;
        background: var(--accent-gold);
    }

    /* Info Grid Layout */
    .detail-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 2rem;
    }

    .info-box {
        background: white;
        padding: 1.5rem;
        border-radius: 16px;
        border: 1px solid #f1f5f9;
        transition: var(--transition);
        display: flex;
        align-items: center;
        gap: 1.25rem;
    }

    .info-box:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.04);
        border-color: var(--gold-soft);
    }

    .info-icon {
        width: 54px;
        height: 54px;
        background: var(--gold-soft);
        color: var(--gold-solid);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }

    .info-content {
        display: flex;
        flex-direction: column;
    }

    .info-label {
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: var(--text-muted);
        margin-bottom: 0.25rem;
    }

    .info-value {
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--primary-dark);
    }

    /* Specific Styles */
    .id-badge {
        background: var(--primary-dark);
        color: white;
        padding: 0.25rem 0.75rem;
        border-radius: 6px;
        font-size: 0.8rem;
        font-weight: 700;
    }

    .strata-badge {
        background: var(--accent-gold);
        color: white;
        padding: 0.2rem 0.8rem;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 700;
    }

    .btn-back {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: #ffffff;
        color: #334155;
        padding: 0.5rem 1.25rem;
        border-radius: 12px;
        border: 1.5px solid #e2e8f0;
        font-weight: 600;
        transition: all 0.3s;
        text-decoration: none;
        box-shadow: 0 1px 3px rgba(0,0,0,0.04);
    }

    .btn-back:hover {
        background: #0f172a;
        color: #ffffff;
        border-color: #0f172a;
        transform: translateX(-3px);
    }

    @media (max-width: 768px) {
        .modern-card { padding: 1.5rem; }
        .page-title { font-size: 1.75rem; }
        .detail-container { grid-template-columns: 1fr; }
    }
</style>

<div class="content-wrapper">
    <div class="page-header">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-2">
                <li class="breadcrumb-item"><a href="{{ route('pendidikan.index') }}">Pendidikan</a></li>
                <li class="breadcrumb-item active fw-bold" style="color: var(--gold-solid)" aria-current="page">Detail Informasi</li>
            </ol>
        </nav>
        <h1 class="page-title">Profil Data Pendidikan</h1>
    </div>

    <div class="modern-card">
        <div class="detail-container">
            <!-- ID Box -->
            <div class="info-box">
                <div class="info-icon">
                    <i class="ri-hashtag"></i>
                </div>
                <div class="info-content">
                    <div class="info-label">Identification ID</div>
                    <div class="info-value">
                        <span class="id-badge">#{{ $data->id }}</span>
                    </div>
                </div>
            </div>

            <!-- Strata Box -->
            <div class="info-box">
                <div class="info-icon">
                    <i class="ri-graduation-cap-line"></i>
                </div>
                <div class="info-content">
                    <div class="info-label">Strata Pendidikan</div>
                    <div class="info-value">
                        <span class="strata-badge">{{ $data->strata }}</span>
                    </div>
                </div>
            </div>

            <!-- Nama Pendidikan Box -->
            <div class="info-box" style="grid-column: span 1;">
                <div class="info-icon">
                    <i class="ri-bank-line"></i>
                </div>
                <div class="info-content">
                    <div class="info-label">Program / Nama Pendidikan</div>
                    <div class="info-value">{{ $data->pendidikan }}</div>
                </div>
            </div>

            <!-- Timestamps Row -->
            <div class="info-box">
                <div class="info-icon">
                    <i class="ri-calendar-event-line"></i>
                </div>
                <div class="info-content">
                    <div class="info-label">Dibuat Pada</div>
                    <div class="info-value">{{ $data->created_at->locale('id')->translatedFormat('d M Y') }} <span style="font-weight: 400; font-size: 0.8rem; color: var(--text-muted)">({{ $data->created_at->format('H:i') }})</span></div>
                </div>
            </div>

            <div class="info-box">
                <div class="info-icon">
                    <i class="ri-history-line"></i>
                </div>
                <div class="info-content">
                    <div class="info-label">Terakhir Diperbarui</div>
                    <div class="info-value">{{ $data->updated_at->locale('id')->translatedFormat('d M Y') }} <span style="font-weight: 400; font-size: 0.8rem; color: var(--text-muted)">({{ $data->updated_at->format('H:i') }})</span></div>
                </div>
            </div>
        </div>

        <div class="d-flex align-items-center">
            <a href="{{ route('pendidikan.index') }}" class="btn-back">
                <i class="ri-arrow-left-s-line"></i> Kembali ke Daftar
            </a>
        </div>
    </div>
</div>
@endsection