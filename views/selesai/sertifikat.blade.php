@extends('layouts.app')

@section('title', 'Sertifikat | Peserta - SMART Setjen DPR RI')
@section('content')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">

<style>
    :root {
        --primary-dark: #0f172a;
        --accent-gold: linear-gradient(135deg, #c5a059 0%, #8e6d2f 100%);
        --gold-solid: #b08d48;
        --gold-soft: rgba(176, 141, 72, 0.08);
        --text-main: #1e293b;
        --text-muted: #64748b;
        --white: #ffffff;
        --transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
    }

    .main-content {
        font-family: 'Plus Jakarta Sans', sans-serif;
        background-color: #f8fafc;
        min-height: 100vh;
        padding: 2.5rem 1.5rem;
    }

    /* Background Batik Subtle */
    .main-content::before {
        content: '';
        position: fixed;
        top: 0; left: 0; width: 100%; height: 100%;
        background-image: url('{{ asset("theme/admin-dashbyte/dist/assets/img/batiknew.png") }}');
        background-size: 500px;
        opacity: 0.04;
        z-index: 0;
        pointer-events: none;
    }

    .page-header { margin-bottom: 2.5rem; text-align: center; position: relative; z-index: 1;}

    .breadcrumb { background: transparent; padding: 0; margin-bottom: 0.5rem; justify-content: center; }
    .breadcrumb-item { font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1.2px; }
    .breadcrumb-item a { color: var(--text-muted); text-decoration: none; }
    .breadcrumb-item.active { color: var(--gold-solid); }

    .page-title {
        font-weight: 800;
        font-size: 2.4rem;
        color: var(--primary-dark);
        letter-spacing: -1px;
    }

    /* Modern Card Styling */
    .modern-card {
        background: rgba(255, 255, 255, 0.9);
        border-radius: 30px;
        border: 1px solid rgba(255, 255, 255, 0.7);
        box-shadow: 0 25px 50px rgba(0,0,0,0.04);
        backdrop-filter: blur(10px);
        overflow: hidden;
        position: relative;
        z-index: 1;
        max-width: 1000px;
        margin: 0 auto;
        animation: fadeInUp 0.8s ease-out;
    }

    .card-accent-bar {
        height: 6px;
        background: var(--accent-gold);
    }

    .card-action-header {
        padding: 2rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: rgba(255, 255, 255, 0.5);
        border-bottom: 1px solid #f1f5f9;
    }

    .card-action-header h5 {
        margin: 0; font-weight: 800; color: var(--primary-dark);
        display: flex; align-items: center; gap: 12px;
    }

    /* Certificate Viewer */
    .certificate-viewer {
        padding: 3rem;
        background: #f8fafc;
        display: flex;
        justify-content: center;
        perspective: 1000px;
    }

    .cert-frame {
        background: white;
        padding: 12px;
        border-radius: 12px;
        box-shadow: 0 30px 60px rgba(15, 23, 42, 0.15);
        width: 100%;
        transition: var(--transition);
        border: 1px solid #e2e8f0;
    }

    .cert-frame:hover {
        transform: translateY(-5px) rotateX(2deg);
    }

    .cert-frame iframe {
        width: 100%; height: 680px; border-radius: 6px; border: none;
    }

    .cert-frame img {
        width: 100%; height: auto; border-radius: 6px;
    }

    /* Buttons */
    .btn-download-premium {
        background: var(--accent-gold);
        color: white !important;
        padding: 14px 28px;
        border-radius: 16px;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 12px;
        border: none;
        transition: var(--transition);
        box-shadow: 0 10px 20px rgba(176, 141, 72, 0.2);
        text-decoration: none;
    }

    .btn-download-premium:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 30px rgba(176, 141, 72, 0.4);
    }

    /* Empty State */
    .empty-state-wrapper {
        padding: 6rem 2rem;
        text-align: center;
    }

    .icon-glow {
        width: 100px; height: 100px;
        background: var(--gold-soft);
        color: var(--gold-solid);
        display: flex; align-items: center; justify-content: center;
        font-size: 3rem; border-radius: 50%;
        margin: 0 auto 2rem;
        position: relative;
    }

    .icon-glow::after {
        content: ''; position: absolute; inset: -10px;
        border: 2px dashed var(--gold-solid);
        border-radius: 50%; opacity: 0.3;
        animation: spin 10s linear infinite;
    }

    @keyframes spin { 100% { transform: rotate(360deg); } }
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .badge-official {
        background: #f1f5f9;
        color: var(--text-muted);
        padding: 10px 20px;
        border-radius: 0 0 30px 30px;
        font-weight: 600;
        font-size: 0.8rem;
    }
</style>

<div class="main-content">
    <div class="page-header" style="background: transparent !important;">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Workspace</a></li>
                <li class="breadcrumb-item active">Sertifikat Digital</li>
            </ol>
        </nav>
        <h1 class="page-title">Sertifikat Magang</h1>
    </div>

    <div class="modern-card">
        <div class="card-accent-bar"></div>

        @if(session('error'))
            <div class="p-4">
                <div class="alert alert-danger border-0 rounded-4 shadow-sm mb-0 d-flex align-items-center gap-3">
                    <i class="ri-error-warning-fill ri-xl"></i>
                    <span class="fw-600">{{ session('error') }}</span>
                </div>
            </div>
        @endif

        @if(!($data && $data->sertifikat))
            <div class="empty-state-wrapper">
                <div class="icon-glow">
                    <i class="ri-medal-line"></i>
                </div>
                <h3 class="empty-state-title fw-800">Sertifikat Sedang Diproses</h3>
                <p class="empty-state-text text-muted mx-auto" style="max-width: 450px;">
                    Sertifikat Anda saat ini masih dalam tahap verifikasi akhir dan proses tanda tangan elektronik. Silakan periksa kembali halaman ini secara berkala.
                </p>
            </div>
        @else
            <div class="card-action-header">
                <h5><i class="ri-verified-badge-line text-primary"></i> Dokumen Sertifikat Tersedia</h5>
                <a href="{{ route('sertifikat.download', ['file' => basename($data->sertifikat)]) }}" class="btn-download-premium">
                    <i class="ri-download-cloud-2-line"></i> Unduh Sertifikat (PDF)
                </a>
            </div>

            <div class="certificate-viewer">
                <div class="cert-frame">
                    @if(str_ends_with($data->sertifikat, '.pdf'))
                        <iframe src="{{ file_url('sertifikat/' . $data->sertifikat) }}#toolbar=0" frameborder="0"></iframe>
                    @else
                        <img src="{{ file_url('sertifikat/' . $data->sertifikat) }}" alt="Sertifikat Magang">
                    @endif
                </div>
            </div>

            <div class="text-center pb-4">
                <div class="badge-official d-inline-block">
                    <i class="ri-shield-check-fill text-success"></i> Sertifikat ini diterbitkan secara sah oleh Sekretariat Jenderal DPR RI
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
