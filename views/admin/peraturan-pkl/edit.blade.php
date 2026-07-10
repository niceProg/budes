@extends('layouts.app')

@section('title', 'Peraturan Magang | Admin - SMART Setjen DPR RI')
@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
    :root {
        --primary-dark: #0f172a;
        --accent-gold: linear-gradient(135deg, #c5a059 0%, #8e6d2f 100%);
        --gold-solid: #b08d48;
        --text-main: #1e293b;
        --text-muted: #64748b;
        --transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
    }
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
    .btn-gold {
        background: var(--accent-gold);
        color: white;
        border: none;
        padding: 0.7rem 1.5rem;
        border-radius: 12px;
        font-weight: 700;
        transition: var(--transition);
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    .btn-gold:hover { transform: translateY(-2px); box-shadow: 0 8px 15px rgba(176, 141, 72, 0.3); color: white; }
</style>

<div class="content-wrapper">
    <div class="container-fluid px-md-5">
        <div class="page-title-area">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1">
                    <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Admin</a></li>
                    <li class="breadcrumb-item active">Peraturan Magang</li>
                </ol>
            </nav>
            <h1 class="page-title" style="font-size: 1.75rem;">Peraturan Magang</h1>
            <p class="mb-0" style="font-size: 0.95rem; font-weight: 600; color: #1e293b;">Isi peraturan magang yang ditampilkan di halaman peserta (beranda /peserta).</p>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="ri-check-line me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="modern-card my-4">
            <form action="{{ route('peraturan-pkl.update') }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <label for="peraturan_pkl" class="form-label" style="font-size: 1.35rem; font-weight: 800; color: #1e293b;">Isi Peraturan Magang</label>
                    <div id="quill-editor" style="min-height: 350px; background: #fff; border-radius: 12px; border: 1px solid #e2e8f0;"></div>
                    <input type="hidden" name="peraturan_pkl" id="peraturan_pkl" value="{{ old('peraturan_pkl', $peraturanPkl) }}">
                    @error('peraturan_pkl')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
                <div class="d-flex gap-2">
                    <button type="button" id="btnSimpanPeraturan" class="btn btn-gold">
                        <i class="ri-save-line"></i> Simpan Perubahan
                    </button>
                    <a href="{{ route('admin.index') }}" class="btn btn-outline-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('theme/admin-dashbyte/dist/lib/quill/quill.min.js') }}"></script>
@push('script')
<script>
document.addEventListener('DOMContentLoaded', function() {
    var quillEditor = document.getElementById('quill-editor');
    var hiddenInput = document.getElementById('peraturan_pkl');
    var quill;

    if (quillEditor && hiddenInput) {
        quill = new Quill('#quill-editor', {
            theme: 'snow',
            placeholder: 'Masukkan teks peraturan magang di sini. Dapat berupa paragraf, poin-poin, bold, list, dll.',
            modules: {
                toolbar: [
                    [{ 'header': [1, 2, 3, false] }],
                    ['bold', 'italic', 'underline', 'strike'],
                    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                    [{ 'align': [] }],
                    ['link'],
                    ['clean']
                ]
            }
        });

        var initialContent = hiddenInput.value;
        if (initialContent) {
            quill.root.innerHTML = initialContent;
        }
    }

    var form = document.querySelector('form[action*="peraturan-pkl"]');
    var btnSimpan = document.getElementById('btnSimpanPeraturan');
    if (form && btnSimpan) {
        btnSimpan.addEventListener('click', function() {
            if (quill && hiddenInput) {
                hiddenInput.value = quill.root.innerHTML;
            }
            Swal.fire({
                title: 'Simpan Perubahan?',
                text: 'Peraturan magang akan diperbarui. Pastikan data sudah benar.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#b08d48',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Simpan',
                cancelButtonText: 'Batal'
            }).then(function(result) {
                if (result.isConfirmed) {
                    if (quill && hiddenInput) {
                        hiddenInput.value = quill.root.innerHTML;
                    }
                    form.submit();
                }
            });
        });
    }

    form.addEventListener('submit', function() {
        if (quill && hiddenInput) {
            hiddenInput.value = quill.root.innerHTML;
        }
    });
});
</script>
@endpush
@endsection
