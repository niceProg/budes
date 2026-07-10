@extends('layouts.app')

@section('title', 'Pengumpulan Nilai | Peserta - SMART Setjen DPR RI')
@section('content')
<link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

<style>
    :root {
        --primary-dark: #0f172a;
        --accent-gold: linear-gradient(135deg, #d4af37 0%, #aa8a2e 100%);
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

    /* Batik Pattern Overlay - Dibuat lebih elegan */
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
    }

    .breadcrumb {
        background: transparent;
        padding: 0;
        margin-bottom: 0.5rem;
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
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .page-title i {
        color: var(--gold-solid);
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
        max-width: 850px;
        margin: 0 auto;
    }

    /* Garis aksen emas di atas kartu */
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

    .alert-success {
        background: #ecfdf5;
        color: #065f46;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.1);
    }

    .alert-info {
        background: #f0f9ff;
        color: #075985;
        border: 1px solid #e0f2fe;
    }

    /* Custom File Upload Zone */
    .upload-container {
        position: relative;
        border: 2px dashed #e2e8f0;
        border-radius: 20px;
        padding: 3rem 2rem;
        text-align: center;
        transition: var(--transition);
        background: #fcfdfe;
        cursor: pointer;
    }

    .upload-container:hover {
        border-color: var(--gold-solid);
        background: var(--gold-soft);
    }

    .upload-icon {
        font-size: 3rem;
        color: var(--gold-solid);
        margin-bottom: 1rem;
        display: block;
    }

    .form-control-file {
        position: absolute;
        inset: 0;
        opacity: 0;
        cursor: pointer;
    }

    .file-hint {
        color: var(--text-muted);
        font-size: 0.85rem;
        margin-top: 1rem;
        display: block;
    }

    /* Button Styling */
    .btn-submit {
        background: var(--accent-gold);
        color: white;
        border: none;
        padding: 1rem 2.5rem;
        border-radius: 14px;
        font-weight: 700;
        transition: var(--transition);
        box-shadow: 0 10px 20px rgba(176, 141, 72, 0.2);
    }

    .btn-submit:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 30px rgba(176, 141, 72, 0.3);
        color: white;
    }

    .btn-view-file {
        background: var(--white);
        color: var(--gold-solid);
        border: 2px solid var(--gold-solid);
        padding: 0.5rem 1.25rem;
        border-radius: 10px;
        font-weight: 700;
        text-decoration: none;
        transition: var(--transition);
    }

    .btn-view-file:hover {
        background: var(--gold-solid);
        color: white;
    }

    /* Animation */
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .modern-card {
        animation: fadeInUp 0.6s ease-out;
    }

    /* === Dark mode: pengumpulan nilai konsisten === */
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
    html[data-skin="dark"] .upload-container {
        background: #1e293b !important;
        border-color: #374151 !important;
        color: #ffffff !important;
    }
    html[data-skin="dark"] .upload-container:hover { border-color: #fbbf24 !important; background: #0f172a !important; }
    html[data-skin="dark"] .upload-icon { color: #fbbf24 !important; }
    html[data-skin="dark"] .upload-container h5,
    html[data-skin="dark"] .upload-container p,
    html[data-skin="dark"] .file-hint { color: #ffffff !important; }
    html[data-skin="dark"] .btn-view-file {
        background: #1e293b !important;
        border-color: #475569 !important;
        color: #fbbf24 !important;
    }
    html[data-skin="dark"] .btn-view-file:hover { background: #0f172a !important; color: #ffffff !important; }
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
        <div class="page-header text-center" style="background: transparent !important;">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb justify-content-center">
                    <li class="breadcrumb-item"><a href="#" class="text-muted text-decoration-none">Dashboard</a></li>
                    <li class="breadcrumb-item active">Pengumpulan Nilai</li>
                </ol>
            </nav>
            <h1 class="page-title justify-content-center">
                <i class="ri-file-chart-line"></i> Dokumen Nilai Akhir
            </h1>
        </div>

        <div class="modern-card">
            @if(session('success'))
                <div class="alert alert-success" id="success-alert">
                    <i class="ri-checkbox-circle-fill ri-xl"></i>
                    <span>{{ session('success') }}</span>
                </div>
                <script>
                    setTimeout(function() {
                        const alert = document.getElementById('success-alert');
                        if(alert) alert.style.opacity = '0';
                        setTimeout(() => alert ? alert.remove() : null, 500);
                    }, 4000);
                </script>
            @endif

            @if($data && $data->file_nilai)
                <div class="text-center py-4">
                    <div class="alert alert-info mb-4">
                        <i class="ri-information-fill ri-xl"></i>
                        <div class="text-start">
                            <span class="d-block">Dokumen nilai sudah tersimpan di sistem kami.</span>
                            <small class="opacity-75">Anda dapat meninjau kembali berkas tersebut melalui tombol di bawah.</small>
                        </div>
                    </div>

                    <div class="p-4 border rounded-4 bg-light d-inline-flex align-items-center gap-4">
                        <div class="bg-white p-3 rounded-circle shadow-sm">
                            <i class="ri-file-pdf-2-fill text-danger ri-3x"></i>
                        </div>
                        <div class="text-start">
                            <p class="mb-1 text-muted small fw-bold text-uppercase">Nama Berkas</p>
                            <h6 class="mb-3 fw-800 text-dark">{{ Str::limit($data->file_nilai, 30) }}</h6>
                            <button type="button"
                                    class="btn-view-file js-preview-file"
                                    data-file-url="{{ file_url('file_nilai/' . $data->file_nilai) }}"
                                    data-file-name="Dokumen Nilai Akhir">
                                <i class="ri-eye-line"></i> Lihat Dokumen
                            </button>
                        </div>
                    </div>
                </div>
            @else
                <form action="{{ route('pengumpulan-nilai.store') }}" method="POST" enctype="multipart/form-data" id="uploadForm">
                    @csrf
                    <div class="form-group mb-5">
                        <label class="form-label d-block text-center mb-4 fs-5 fw-700">Pilih Berkas Nilai Anda</label>

                        <div class="upload-container" id="drop-zone">
                            <i class="ri-upload-cloud-2-line upload-icon"></i>
                            <h5 class="fw-bold mb-1">Tarik file ke sini atau klik untuk mencari</h5>
                            <p class="text-muted small mb-0">Hanya mendukung format dokumen PDF</p>

                            <input type="file" name="file_nilai" id="file_nilai" class="form-control-file" accept=".pdf" required>
                        </div>

                        <div id="file-preview" class="mt-3 text-center d-none">
                            <span class="badge bg-primary p-2 px-3 rounded-pill">
                                <i class="ri-file-pdf-line"></i> <span id="file-name"></span>
                            </span>
                        </div>

                        <span class="file-hint text-center">
                            <i class="ri-error-warning-line"></i> Maksimal ukuran file: <strong>1 MB</strong>
                        </span>
                    </div>

                    <div class="text-center">
                        <button type="submit" class="btn-submit">
                            <i class="ri-upload-line"></i> Unggah Sekarang
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
    // Logika tambahan untuk preview nama file yang dipilih
    const fileInput = document.getElementById('file_nilai');
    const fileNameText = document.getElementById('file-name');
    const filePreview = document.getElementById('file-preview');
    const dropZone = document.getElementById('drop-zone');

    if(fileInput) {
        fileInput.addEventListener('change', function() {
            if (this.files && this.files.length > 0) {
                const file = this.files[0];
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
                    this.value = '';
                    filePreview.classList.add('d-none');
                    dropZone.style.borderColor = '';
                    dropZone.style.background = '';
                    return;
                }

                fileNameText.innerText = file.name;
                filePreview.classList.remove('d-none');
                dropZone.style.borderColor = 'var(--gold-solid)';
                dropZone.style.background = 'var(--gold-soft)';
            }
        });
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
