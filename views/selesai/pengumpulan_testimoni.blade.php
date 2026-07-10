@extends('layouts.app')

@section('title', 'Testimoni | Peserta - SMART Setjen DPR RI')
@section('content')
<link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

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

    /* Batik Pattern Overlay */
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

    .page-header { margin-bottom: 2.5rem; text-align: center; }
    .breadcrumb { background: transparent; padding: 0; margin-bottom: 0.5rem; justify-content: center; }
    .breadcrumb-item { font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1.2px; }
    .page-title { font-weight: 800; font-size: 2.2rem; color: var(--primary-dark); letter-spacing: -1px; }

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
        animation: fadeInUp 0.6s ease-out;
    }

    .modern-card::after {
        content: '';
        position: absolute;
        top: 0; left: 50%; transform: translateX(-50%);
        width: 100px; height: 5px;
        background: var(--accent-gold);
        border-radius: 0 0 10px 10px;
    }

    /* Form Styling */
    .form-label { font-weight: 700; color: var(--primary-dark); font-size: 0.95rem; margin-bottom: 0.7rem; }
    .form-control {
        border: 1.5px solid #e2e8f0;
        border-radius: 12px;
        padding: 1rem 1.25rem;
        font-size: 0.95rem;
        transition: var(--transition);
        background: #fcfdfe;
    }
    .form-control:focus {
        border-color: var(--gold-solid);
        box-shadow: 0 0 0 4px var(--gold-soft);
        background: white;
    }

    textarea.form-control { resize: none; line-height: 1.6; }

    /* File Upload Box */
    .upload-box {
        border: 2px dashed #e2e8f0;
        border-radius: 15px;
        padding: 2.5rem;
        text-align: center;
        transition: var(--transition);
        cursor: pointer;
        background: #f8fafc;
        position: relative;
    }
    .upload-box:hover { border-color: var(--gold-solid); background: var(--gold-soft); }
    .upload-box i { font-size: 2.5rem; color: var(--gold-solid); }

    /* Alerts */
    .alert { border-radius: 15px; border: none; font-weight: 600; display: flex; align-items: center; gap: 12px; padding: 1.25rem; }
    .alert-success { background: #ecfdf5; color: #065f46; }
    .alert-danger { background: #fff1f2; color: #991b1b; }
    .alert-info { background: #f0f9ff; color: #075985; border-left: 5px solid #0ea5e9; }

    /* Info Block for Existing Data */
    .testimonial-preview {
        background: white;
        padding: 2rem;
        border-radius: 16px;
        border: 1px solid #eef2f6;
        box-shadow: inset 0 2px 4px rgba(0,0,0,0.02);
        margin: 1.5rem 0;
    }

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
    .btn-submit:hover { transform: translateY(-3px); box-shadow: 0 15px 30px rgba(176, 141, 72, 0.3); }

    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* === Dark mode: pengumpulan testimoni konsisten === */
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
    html[data-skin="dark"] .form-control {
        background: #1e293b !important;
        border-color: #374151 !important;
        color: #ffffff !important;
    }
    html[data-skin="dark"] .form-control::placeholder { color: #9ca3af !important; }
    html[data-skin="dark"] .form-control:focus {
        background: #0f172a !important;
        border-color: #fbbf24 !important;
    }
    html[data-skin="dark"] .upload-box {
        background: #1e293b !important;
        border-color: #374151 !important;
        color: #ffffff !important;
    }
    html[data-skin="dark"] .upload-box:hover {
        border-color: #fbbf24 !important;
        background: #0f172a !important;
    }
    html[data-skin="dark"] .upload-box i,
    html[data-skin="dark"] .upload-box h6 { color: #fbbf24 !important; }
    html[data-skin="dark"] .upload-box p,
    html[data-skin="dark"] .upload-box .text-muted { color: #9ca3af !important; }
    html[data-skin="dark"] .testimonial-preview {
        background: #1e293b !important;
        border-color: #374151 !important;
        color: #ffffff !important;
    }
    html[data-skin="dark"] .testimonial-preview .text-dark { color: #ffffff !important; }
    html[data-skin="dark"] .p-4.bg-light,
    html[data-skin="dark"] .bg-light { background: #1e293b !important; border-color: #374151 !important; }
    html[data-skin="dark"] .bg-white { background: #0f172a !important; }
    html[data-skin="dark"] .text-dark,
    html[data-skin="dark"] .text-muted { color: #ffffff !important; }
    html[data-skin="dark"] .btn-outline-dark {
        background: #1e293b !important;
        border-color: #475569 !important;
        color: #fbbf24 !important;
    }
    html[data-skin="dark"] .btn-outline-dark:hover {
        background: #0f172a !important;
        border-color: #fbbf24 !important;
        color: #ffffff !important;
    }
    html[data-skin="dark"] #filePreviewModal .modal-header { background: #0f172a !important; color: #ffffff !important; }
    html[data-skin="dark"] #filePreviewModal .modal-title { color: #ffffff !important; }
</style>

<div class="content-wrapper">
    <div class="container">
        <div class="page-header" style="background: transparent !important;">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">Menu</li>
                    <li class="breadcrumb-item active">Testimoni</li>
                </ol>
            </nav>
            <h1 class="page-title"><i class="ri-double-quotes-l text-warning"></i> Testimoni Pengalaman</h1>
        </div>

        <div class="modern-card">
            @if(session('success'))
                <div class="alert alert-success mb-4" id="success-alert">
                    <i class="ri-checkbox-circle-fill ri-lg"></i> {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger mb-4" id="error-alert">
                    <i class="ri-error-warning-fill ri-lg"></i> {{ session('error') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger mb-4" id="validation-alert">
                    <i class="ri-alert-fill ri-lg"></i>
                    <ul class="mb-0 list-unstyled">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if($data && $data->file_testimoni)
                <div class="alert alert-info">
                    <i class="ri-information-fill ri-xl"></i>
                    <div>
                        <span class="d-block fw-800">Testimoni Anda Telah Terkirim</span>
                        <small>Terima kasih telah berbagi pengalaman Anda bersama kami.</small>
                    </div>
                </div>

                @if($data->testimoni)
                    <div class="testimonial-preview">
                        <i class="ri-chat-quote-line ri-2x text-muted opacity-25 d-block mb-2"></i>
                        <p class="fst-italic text-dark fs-6" style="line-height: 1.8;">"{{ $data->testimoni }}"</p>
                    </div>
                @endif

                <div class="p-4 border rounded-4 bg-light d-flex align-items-center justify-content-between mt-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="p-3 bg-white rounded-circle shadow-sm">
                            <i class="ri-file-pdf-2-fill text-danger ri-2x"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 fw-800 text-dark">Berkas_Testimoni.pdf</h6>
                            <small class="text-muted">Lampiran Dokumen</small>
                        </div>
                    </div>
                    <button type="button"
                            class="btn btn-outline-dark fw-bold rounded-pill px-4 js-preview-file"
                            data-file-url="{{ file_url('file_testimoni/' . $data->file_testimoni) }}"
                            data-file-name="File Testimoni">
                        <i class="ri-eye-line"></i> Lihat File
                    </button>
                </div>
            @else
                <form action="{{ route('testimoni.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="form-group mb-4">
                        <label for="testimoni" class="form-label">Bagaimana Pengalaman Anda? (Opsional)</label>
                        <textarea name="testimoni" id="testimoni" class="form-control" rows="5" placeholder="Tuliskan kesan dan pesan atau catatan singkat mengenai pengalaman Anda...">{{ $data->testimoni ?? '' }}</textarea>
                    </div>

                    <div class="form-group mb-5">
                        <label class="form-label">Unggah Dokumen Testimoni</label>
                        <div class="upload-box" onclick="document.getElementById('file_testimoni').click()">
                            <i class="ri-file-upload-line"></i>
                            <h6 class="mt-3 fw-800">Klik untuk memilih file PDF</h6>
                            <p class="text-muted small mb-0">Format: <strong>PDF</strong> (Maksimal 1 MB)</p>
                            <input type="file" name="file_testimoni" id="file_testimoni" class="d-none" accept=".pdf" required onchange="showFileName(this)">
                        </div>
                        <div id="file-chosen" class="mt-3 text-center d-none">
                            <span class="badge bg-success-subtle text-success p-2 px-3 border border-success-subtle rounded-pill">
                                <i class="ri-checkbox-circle-line"></i> <span id="file-name-display"></span>
                            </span>
                        </div>
                    </div>

                    <div class="d-flex justify-content-center">
                        <button type="submit" class="btn-submit">
                            <i class="ri-save-3-line"></i> Simpan Testimoni
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
    // JS to show selected file name + validate size (maks 1 MB)
    function showFileName(input) {
        const display = document.getElementById('file-chosen');
        const text = document.getElementById('file-name-display');
        if (input.files && input.files.length > 0) {
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
                display.classList.add('d-none');
                const box = document.querySelector('.upload-box');
                if (box) {
                    box.style.borderColor = '';
                    box.style.background = '';
                }
                return;
            }
            text.innerText = file.name;
            display.classList.remove('d-none');

            // Visual feedback on box
            const box = document.querySelector('.upload-box');
            box.style.borderColor = 'var(--gold-solid)';
            box.style.background = 'var(--gold-soft)';
        }
    }

    // Auto-hide alerts
    setTimeout(function() {
        ['success-alert', 'error-alert', 'validation-alert'].forEach(id => {
            const el = document.getElementById(id);
            if(el) {
                el.style.transition = '0.5s';
                el.style.opacity = '0';
                setTimeout(() => el.remove(), 500);
            }
        });
    }, 4000);

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
