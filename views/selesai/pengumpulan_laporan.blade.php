@extends('layouts.app')

@section('title', 'Pengumpulan Laporan | Peserta - SMART Setjen DPR RI')
@section('content')
<link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

<style>
    :root {
        --primary-dark: #0f172a;
        --accent-gold: linear-gradient(135deg, #c5a059 0%, #8e6d2f 100%);
        --gold-solid: #b08d48;
        --gold-soft: rgba(176, 141, 72, 0.1);
        --text-main: #1e293b;
        --text-muted: #64748b;
        --white: #ffffff;
        --transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
    }

    body, .content-wrapper {
        font-family: 'Plus Jakarta Sans', sans-serif;
        background-color: #f8fafc;
        min-height: 100vh;
    }

    .content-wrapper {
        position: relative;
        z-index: 1;
        padding: 2.5rem 1.5rem;
    }

    /* Pattern Batik Latar Belakang */
    .content-wrapper::before {
        content: '';
        position: fixed;
        top: 0; left: 0; width: 100%; height: 100%;
        background-image: url('{{ asset("theme/admin-dashbyte/dist/assets/img/batiknew.png") }}');
        background-size: 500px;
        opacity: 0.04;
        z-index: -1;
        pointer-events: none;
    }

    .page-header {
        margin-bottom: 2.5rem;
        text-align: center;
    }

    .breadcrumb {
        background: transparent;
        padding: 0;
        margin-bottom: 0.5rem;
        justify-content: center;
    }

    .breadcrumb-item {
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1.2px;
    }

    .page-title {
        font-weight: 800;
        font-size: 2.2rem;
        color: var(--primary-dark);
        letter-spacing: -1px;
    }

    /* Modern Card Glassmorphism */
    .modern-card {
        background: rgba(255, 255, 255, 0.9);
        border-radius: 24px;
        padding: 3rem;
        box-shadow: 0 20px 40px rgba(0,0,0,0.04);
        border: 1px solid rgba(255, 255, 255, 0.7);
        position: relative;
        backdrop-filter: blur(10px);
        max-width: 800px;
        margin: 0 auto;
        animation: fadeInUp 0.6s ease-out;
    }

    .modern-card::after {
        content: '';
        position: absolute;
        top: 0; left: 50%;
        transform: translateX(-50%);
        width: 100px; height: 5px;
        background: var(--accent-gold);
        border-radius: 0 0 10px 10px;
    }

    /* Alert Styling */
    .alert {
        border-radius: 16px;
        border: none;
        padding: 1.25rem 1.5rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        font-weight: 600;
    }

    .alert-success { background: #ecfdf5; color: #065f46; }
    .alert-info { background: #f0f9ff; color: #075985; border: 1px solid #e0f2fe; }

    /* Custom Upload Zone */
    .upload-zone {
        border: 2px dashed #e2e8f0;
        border-radius: 20px;
        padding: 3.5rem 2rem;
        text-align: center;
        transition: var(--transition);
        background: #fcfdfe;
        cursor: pointer;
        position: relative;
    }

    .upload-zone:hover {
        border-color: var(--gold-solid);
        background: var(--gold-soft);
    }

    .upload-icon {
        font-size: 3.5rem;
        color: var(--gold-solid);
        margin-bottom: 1rem;
        display: inline-block;
    }

    #laporan {
        position: absolute;
        inset: 0;
        opacity: 0;
        cursor: pointer;
    }

    /* Buttons */
    .btn-submit {
        background: var(--accent-gold);
        color: white !important;
        border: none;
        padding: 1rem 2.5rem;
        border-radius: 12px;
        font-weight: 700;
        transition: var(--transition);
        box-shadow: 0 10px 20px rgba(176, 141, 72, 0.2);
        display: inline-flex;
        align-items: center;
        gap: 0.75rem;
    }

    .btn-submit:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 30px rgba(176, 141, 72, 0.3);
    }

    .btn-view {
        background: white;
        color: var(--primary-dark);
        border: 2px solid #e2e8f0;
        padding: 0.6rem 1.5rem;
        border-radius: 10px;
        font-weight: 700;
        text-decoration: none;
        transition: var(--transition);
    }

    .btn-view:hover {
        border-color: var(--gold-solid);
        color: var(--gold-solid);
    }

    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* === Dark mode: pengumpulan laporan konsisten === */
    html[data-skin="dark"] body,
    html[data-skin="dark"] .content-wrapper {
        background-color: #020617 !important;
        color: #ffffff !important;
    }
    html[data-skin="dark"] .content-wrapper::before { opacity: 0.03 !important; }
    html[data-skin="dark"] .page-title,
    html[data-skin="dark"] .form-label,
    html[data-skin="dark"] .breadcrumb-item,
    html[data-skin="dark"] .breadcrumb-item a { color: #ffffff !important; }
    html[data-skin="dark"] .modern-card {
        background: #0f172a !important;
        border-color: #1f2937 !important;
        color: #ffffff !important;
    }
    html[data-skin="dark"] .upload-zone {
        background: #1e293b !important;
        border-color: #374151 !important;
        color: #ffffff !important;
    }
    html[data-skin="dark"] .upload-zone:hover { border-color: #fbbf24 !important; background: #0f172a !important; }
    html[data-skin="dark"] .upload-icon { color: #fbbf24 !important; }
    html[data-skin="dark"] .upload-zone h5,
    html[data-skin="dark"] .upload-zone p { color: #ffffff !important; }
    html[data-skin="dark"] .btn-view {
        background: #1e293b !important;
        border-color: #475569 !important;
        color: #ffffff !important;
    }
    html[data-skin="dark"] .btn-view:hover { border-color: #fbbf24 !important; color: #fbbf24 !important; }
    html[data-skin="dark"] .p-4.bg-light,
    html[data-skin="dark"] .bg-light { background: #1e293b !important; border-color: #374151 !important; }
    html[data-skin="dark"] .bg-white { background: #0f172a !important; }
    html[data-skin="dark"] .text-dark,
    html[data-skin="dark"] .text-muted { color: #ffffff !important; }
    html[data-skin="dark"] #filePreviewModal .modal-header { background: #0f172a !important; color: #ffffff !important; }
    html[data-skin="dark"] #filePreviewModal .modal-title { color: #ffffff !important; }
</style>

<div class="content-wrapper">
    <div class="container">
        <div class="page-header" style="background: transparent !important;">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">Workspace</li>
                    <li class="breadcrumb-item active">Laporan</li>
                </ol>
            </nav>
            <h1 class="page-title"><i class="ri-article-line"></i> Pengumpulan Laporan</h1>
        </div>

        <div class="modern-card">
            @if(session('success'))
                <div class="alert alert-success mb-4 animate__animated animate__fadeIn" id="success-alert">
                    <i class="ri-checkbox-circle-fill ri-xl"></i>
                    <span>{{ session('success') }}</span>
                </div>
                <script>
                    setTimeout(() => {
                        const alert = document.getElementById('success-alert');
                        if(alert) alert.style.display = 'none';
                    }, 4000);
                </script>
            @endif

            @if($data && $data->laporan)
                <div class="text-center py-4">
                    <div class="alert alert-info mb-5">
                        <i class="ri-information-fill ri-xl"></i>
                        <div class="text-start">
                            <span class="d-block fw-bold">Berkas Laporan Tersimpan</span>
                            <small class="opacity-75">Anda telah berhasil mengunggah dokumen laporan akhir.</small>
                        </div>
                    </div>

                    <div class="p-4 border rounded-4 bg-light d-flex align-items-center justify-content-between mx-auto" style="max-width: 500px;">
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-white p-2 rounded-3 shadow-sm">
                                <i class="ri-file-pdf-fill text-danger ri-2x"></i>
                            </div>
                            <div class="text-start">
                                <h6 class="mb-0 fw-800 text-dark">Laporan_Akhir.pdf</h6>
                                <small class="text-muted">Dokumen PDF</small>
                            </div>
                        </div>
                        <button type="button"
                                class="btn-view js-preview-file"
                                data-file-url="{{ file_url('laporan/' . $data->laporan) }}"
                                data-file-name="Laporan Akhir">
                            <i class="ri-eye-line"></i> Lihat
                        </button>
                    </div>

                    <p class="mt-5 text-muted small fw-600">
                        <i class="ri-lock-line"></i> Hubungi administrator untuk melakukan pembaruan berkas.
                    </p>
                </div>
            @else
                <form action="{{ route('laporan.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group mb-5">
                        <label class="form-label d-block text-center mb-4 fs-5 fw-700">Pilih Berkas Laporan Akhir</label>

                        <div class="upload-zone" id="drop-zone">
                            <i class="ri-upload-cloud-2-line upload-icon"></i>
                            <h5 class="fw-bold mb-1">Klik atau seret file ke sini</h5>
                            <p class="text-muted small mb-0">Format yang diterima: <strong>PDF (Maks. 1 MB)</strong></p>

                            <input type="file" name="laporan" id="laporan" accept=".pdf" required onchange="displayFileName(this)">
                        </div>

                        <div id="file-name-preview" class="mt-3 text-center d-none">
                            <span class="badge bg-success p-2 px-3 rounded-pill fw-bold">
                                <i class="ri-file-check-line"></i> <span id="file-name-text"></span>
                            </span>
                        </div>
                    </div>

                    <div class="d-flex justify-content-center">
                        <button type="submit" class="btn-submit">
                            <i class="ri-send-plane-fill"></i> Unggah Laporan Sekarang
                        </button>
                    </div>
                </form>
            @endif
        </div>
    </div>
</div>

<!-- Modal Preview File (Global) -->
<div class="modal fade" id="filePreviewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content overflow-hidden">
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
    function displayFileName(input) {
        const preview = document.getElementById('file-name-preview');
        const text = document.getElementById('file-name-text');
        const zone = document.getElementById('drop-zone');

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
                preview.classList.add('d-none');
                zone.style.borderColor = '';
                zone.style.background = '';
                return;
            }
            text.innerText = file.name;
            preview.classList.remove('d-none');
            zone.style.borderColor = 'var(--gold-solid)';
            zone.style.background = 'var(--gold-soft)';
        }
    }

    // Preview dokumen di modal
    document.addEventListener('DOMContentLoaded', function () {
        document.addEventListener('show.bs.modal', function (event) {
            const modalEl = event.target;
            if (modalEl && modalEl.parentElement !== document.body) {
                document.body.appendChild(modalEl);
            }
        });

        const buttons = document.querySelectorAll('.js-preview-file');
        const frame = document.getElementById('filePreviewFrame');
        const img = document.getElementById('filePreviewImage');
        const modalEl = document.getElementById('filePreviewModal');
        const labelEl = document.getElementById('filePreviewModalLabel');

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
@endsection
