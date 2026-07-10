@extends('layouts.app')

@section('title', 'Materi | Peserta - SMART Setjen DPR RI')
@section('content')
<link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
@include('partials.table_skin_lamaran_css')
<style>
    .btn-primary { background: var(--gold-gradient); border: none; padding: 0.7rem 1.5rem; border-radius: 8px; font-weight: 700; color: white; transition: var(--transition); display: inline-flex; align-items: center; gap: 8px; }
    .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 8px 15px rgba(176, 141, 72, 0.3); color: white; }
    .btn-light { border-radius: 8px; font-weight: 800; border: 1px solid #e2e8f0; }

    .materi-actions .btn {
        padding: 0.5rem 0.9rem !important;
        font-size: 0.85rem !important;
        line-height: 1.1;
    }
    .materi-actions .btn i {
        font-size: 1rem;
    }

    .materi-meta { display: flex; flex-wrap: wrap; gap: 10px; align-items: center; }
    .meta-pill { padding: 6px 12px; border-radius: 999px; font-weight: 800; font-size: 0.72rem; letter-spacing: 0.5px; text-transform: uppercase; border: 1px solid #e2e8f0; background: rgba(248,250,252,0.9); color: #334155; }
    .meta-pill i { margin-right: 6px; }
    .pill-publish { border-color: rgba(34,197,94,0.25); background: rgba(34,197,94,0.08); color: #166534; }
    .pill-draft { border-color: rgba(99,102,241,0.25); background: rgba(99,102,241,0.08); color: #3730a3; }
    .pill-aktif { border-color: rgba(34,197,94,0.25); background: rgba(34,197,94,0.08); color: #166534; }
    .pill-nonaktif { border-color: rgba(148,163,184,0.35); background: rgba(148,163,184,0.10); color: #334155; }

    .tiptap-content { line-height: 1.8; color: #0f172a; }
    .tiptap-content h1, .tiptap-content h2, .tiptap-content h3, .tiptap-content h4, .tiptap-content h5, .tiptap-content h6 { color: #0f172a; }
    .tiptap-content a { color: #0284c7; font-weight: 700; }
    .tiptap-content table { width: 100%; border-collapse: collapse; margin: 12px 0; }
    .tiptap-content th, .tiptap-content td { border: 1px solid #e2e8f0; padding: 10px; vertical-align: top; }
    .tiptap-content blockquote { border-left: 4px solid var(--accent-gold); padding-left: 12px; color: #475569; margin: 10px 0; }
    /* YouTube embed (center + responsive) */
    .tiptap-content [data-youtube-video] {
        display: flex;
        justify-content: center;
        margin: 0.85rem 0;
    }
    .tiptap-content [data-youtube-video] iframe {
        width: min(100%, 860px);
        aspect-ratio: 16 / 9;
        height: auto;
        border: 0;
        border-radius: 14px;
    }
    /* Image align */
    .tiptap-content img {
        max-width: 100%;
        height: auto;
        display: block;
    }
    .tiptap-content img.tiptap-image-align-left {
        margin: 0.5rem auto 0.75rem 0;
    }
    .tiptap-content img.tiptap-image-align-center {
        margin: 0.75rem auto;
    }
    .tiptap-content img.tiptap-image-align-right {
        margin: 0.5rem 0 0.75rem auto;
    }

    .file-card { border: 1px solid #e2e8f0; border-radius: 18px; padding: 16px; background: rgba(255,255,255,0.9); }
    .file-card .title { font-weight: 900; color: #0f172a; display: flex; align-items: center; gap: 8px; }
    .file-actions { display: flex; gap: 10px; flex-wrap: wrap; margin-top: 10px; }
    .btn-file { display: inline-flex; align-items: center; gap: 8px; padding: 10px 14px; border-radius: 12px; border: 1px solid #e2e8f0; background: #ffffff; font-weight: 800; text-decoration: none; color: #0f172a; }
    .btn-file:hover { border-color: rgba(176,141,72,0.5); box-shadow: 0 10px 20px rgba(15,23,42,0.08); transform: translateY(-1px); }
    .btn-file.primary { background: rgba(176,141,72,0.12); border-color: rgba(176,141,72,0.45); color: var(--gold-solid); }

    /* Full-screen minimal PDF viewer (like Google Drive) */
    .pdf-viewer-overlay {
        position: fixed;
        inset: 0;
        background: rgba(15,23,42,0.96);
        z-index: 1100;
        display: flex;
        align-items: flex-start;
        justify-content: center;
        padding: 72px 16px 16px;
    }
    .pdf-viewer-shell {
        position: relative;
        width: min(100%, 1024px);
        height: min(82vh, 780px);
        background: transparent;
        border-radius: 18px;
        overflow: hidden;
        box-shadow: 0 24px 60px rgba(0,0,0,0.75);
    }
    .pdf-viewer-iframe {
        width: 100%;
        height: 100%;
        border: 0;
        background: #111827;
    }
    .pdf-viewer-controls {
        position: fixed;
        top: 18px;
        left: 24px;
        right: 24px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        z-index: 1200;
        pointer-events: none;
    }
    .pdf-viewer-btn {
        border-radius: 999px;
        border: 1px solid rgba(148,163,184,0.8);
        background: rgba(15,23,42,0.9);
        color: #e5e7eb;
        font-weight: 700;
        font-size: 0.8rem;
        padding: 6px 14px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        text-decoration: none;
        pointer-events: auto;
    }
    .pdf-viewer-btn.primary {
        border-color: rgba(251,191,36,0.9);
        background: rgba(251,191,36,0.16);
        color: #fbbf24;
    }
    .pdf-viewer-btn:hover {
        text-decoration: none;
        filter: brightness(1.05);
    }

    .pdf-viewer-actions .btn { border-radius: 12px; font-weight: 800; }

    /* Dark mode */
    html[data-skin="dark"] .tiptap-content,
    html[data-skin="dark"] .tiptap-content * { color: #ffffff !important; }
    html[data-skin="dark"] .tiptap-content a { color: #fbbf24 !important; }
    html[data-skin="dark"] .tiptap-content th,
    html[data-skin="dark"] .tiptap-content td { border-color: #374151 !important; }
    html[data-skin="dark"] .tiptap-content table { background: #0b1220 !important; }
    html[data-skin="dark"] .tiptap-content th { background: rgba(30,41,59,0.9) !important; }
    html[data-skin="dark"] .tiptap-content td { background: rgba(15,23,42,0.85) !important; }
    html[data-skin="dark"] .tiptap-content blockquote { color: #e5e7eb !important; border-left-color: #fbbf24 !important; }
    html[data-skin="dark"] .file-card { background: #0f172a !important; border-color: #374151 !important; }
    html[data-skin="dark"] .file-card .title { color: #ffffff !important; }
    html[data-skin="dark"] .meta-pill { background: rgba(15,23,42,0.9) !important; border-color: #374151 !important; color: #e5e7eb !important; }
    html[data-skin="dark"] .btn-file { background: #1e293b !important; border-color: #374151 !important; color: #ffffff !important; }
    html[data-skin="dark"] .btn-file.primary { background: rgba(251,191,36,0.12) !important; border-color: rgba(251,191,36,0.45) !important; color: #fbbf24 !important; }
</style>

<div class="content-wrapper">
    <div class="container-fluid px-md-5">
        <div class="page-title-area d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-1">
                        <li class="breadcrumb-item">Materi</li>
                        <li class="breadcrumb-item active">Detail</li>
                    </ol>
                </nav>
                <h1 class="page-title">Detail Materi</h1>
            </div>
            <div class="materi-actions">
                <a href="{{ route('peserta.index') }}" class="btn btn-light" style="border-radius: 12px;">
                    <i class="ri-arrow-left-s-line"></i> Kembali
                </a>
            </div>
        </div>

        <div class="modern-card p-4">
            <div class="d-flex justify-content-between flex-wrap gap-3 mb-1">
                <h1 class="fw-800 mb-0">{{ $materi->judul }}</h1>
            </div>
            <div class="d-flex justify-content-between align-items-center mt-2 mb-3">
                    <div class="materi-meta">
                    @if(!empty($materi->narasumber))
                        <span class="meta-pill">
                            Narasumber: &nbsp;{{ $materi->narasumber }}
                            </span>
                        @endif
                </div>
                <div class="d-flex align-items-center gap-1 text-muted">
                    <i class="ri-calendar-line"></i>
                    <span>{{ $materi->tanggal_publish->locale('id')->translatedFormat('d F Y') }}</span>
                </div>
            </div>

            <hr class="my-4">

            <div class="mb-4">
                <div class="tiptap-content">
                    {!! clean($materi->isi) !!}
                </div>
            </div>

            <div class="row g-3">
                <div class="col-md-6">
                    <div class="file-card">
                        <div class="title"><i class="ri-file-3-line"></i> File Materi</div>
                        @if($materi->file_materi)
                            <div class="file-actions">
                                <a class="btn-file primary" href="{{ route('materi.peserta.download', [$materi->id, 'file']) }}">
                                    <i class="ri-download-2-line"></i> Download
                                </a>
                            </div>
                        @else
                            <div class="text-muted mt-2">-</div>
                        @endif
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="file-card">
                        <div class="title"><i class="ri-file-pdf-2-line"></i> PDF Materi</div>
                        @if($materi->pdf_materi)
                            <div class="file-actions">
                                <a class="btn-file" href="javascript:void(0)"
                                   onclick="openPdfViewer('{{ route('materi.peserta.pdf.view', $materi->id) }}', '{{ route('materi.peserta.download', [$materi->id, 'pdf']) }}')">
                                    <i class="ri-eye-line"></i> Lihat
                                </a>
                                <a class="btn-file primary" href="{{ route('materi.peserta.download', [$materi->id, 'pdf']) }}">
                                    <i class="ri-download-2-line"></i> Download
                                </a>
                            </div>
                        @else
                            <div class="text-muted mt-2">-</div>
                        @endif
                    </div>
                </div>
            </div>

                <hr class="my-4">
        </div>
    </div>
</div>

{{-- Minimal full-screen PDF viewer --}}
<div id="pdfViewerOverlay" class="pdf-viewer-overlay d-none" onclick="pdfOverlayBackdropClick(event)">
    <div class="pdf-viewer-shell">
        <div class="pdf-viewer-controls">
            <button type="button" class="pdf-viewer-btn" onclick="closePdfViewer()">
                <i class="ri-close-line"></i>
            </button>
            <a id="pdfDownloadButton" href="#" class="pdf-viewer-btn primary">
                            <i class="ri-download-2-line"></i> Download
                        </a>
        </div>
        <iframe id="pdfViewerFrame" class="pdf-viewer-iframe" src="" title="PDF preview"></iframe>
    </div>
</div>

<script>
    // Pastikan overlay dipindah ke body (mirip hack modal Bootstrap di admin-absensi)
    document.addEventListener('DOMContentLoaded', function () {
        const overlay = document.getElementById('pdfViewerOverlay');
        if (overlay && overlay.parentElement !== document.body) {
            document.body.appendChild(overlay);
        }
    });

    function openPdfViewer(viewUrl, downloadUrl) {
        const overlay = document.getElementById('pdfViewerOverlay');
        const frame = document.getElementById('pdfViewerFrame');
        const downloadBtn = document.getElementById('pdfDownloadButton');

        if (!overlay || !frame || !downloadBtn) return;

        frame.src = viewUrl;
        downloadBtn.href = downloadUrl;
        overlay.classList.remove('d-none');
        document.body.style.overflow = 'hidden';
    }

    function closePdfViewer() {
        const overlay = document.getElementById('pdfViewerOverlay');
        const frame = document.getElementById('pdfViewerFrame');

        if (!overlay || !frame) return;

        frame.src = '';
        overlay.classList.add('d-none');
        document.body.style.overflow = '';
    }

    function pdfOverlayBackdropClick(event) {
        const overlay = document.getElementById('pdfViewerOverlay');
        if (event.target === overlay) {
            closePdfViewer();
        }
    }
</script>
@endsection

