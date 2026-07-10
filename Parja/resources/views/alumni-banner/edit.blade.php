@extends('parja::layouts.app')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <div>
        <ol class="breadcrumb fs-sm mb-1">
            <li class="breadcrumb-item">Parja Alumni</li>
            <li class="breadcrumb-item"><a href="{{ route('parja.alumni-banner.index') }}">Data Banner Alumni</a></li>
            <li class="breadcrumb-item active" aria-current="page">Edit Banner</li>
        </ol>
        <h4 class="main-title mb-0">Edit Data Banner Alumni</h4>
    </div>
</div>

<div class="card">
    <div class="card-body">
        @if ($errors->any())
            <div class="alert alert-danger mb-3">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('parja.alumni-banner.update', $record->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="urutan" class="form-label fw-bold">Urutan Banner <span class="text-danger">*</span></label>
                <input type="number" min="1" max="99" class="form-control @error('urutan') is-invalid @enderror" id="urutan" name="urutan" value="{{ old('urutan', $record->banner_order) }}" placeholder="Contoh: 1" required>
                @error('urutan')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label for="judul" class="form-label fw-bold">Judul Banner <span class="text-danger">*</span></label>
                <input type="text" maxlength="255" class="form-control @error('judul') is-invalid @enderror" id="judul" name="judul" value="{{ old('judul', $record->banner_title) }}" placeholder="Contoh: Program Alumni 2026" required>
                @error('judul')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label for="image_file" class="form-label fw-bold">Upload File Gambar Baru</label>
                <input type="file" accept=".jpg,.jpeg,.png,.webp,image/*" class="form-control @error('image_file') is-invalid @enderror" id="image_file" name="image_file">
                @error('image_file')<div class="invalid-feedback">{{ $message }}</div>@enderror
                <small class="text-muted">Opsional. Jika dipilih, gambar lama otomatis diganti. File otomatis dioptimasi ukuran saat disimpan.</small>
            </div>

            @if($record->banner_image_missing)
            <div class="alert alert-warning py-2">
                Gambar banner saat ini tidak ditemukan. Silakan upload file gambar baru sebelum menyimpan perubahan.
            </div>
            @endif

            @if(!empty($record->banner_image_url))
            <div class="mb-3">
                <label class="form-label fw-bold">Preview Gambar Saat Ini</label>
                <div>
                    <img id="current-image-preview" src="{{ asset(ltrim($record->banner_image_url, '/')) }}" alt="Banner saat ini" style="max-width: 100%; max-height: 220px; border-radius: 8px; border: 1px solid #ddd;">
                </div>
                <div class="mt-2">
                    <button type="button" id="btn-change-image" class="btn btn-sm btn-outline-primary">
                        <i class="ri-image-edit-line"></i> Ganti Gambar
                    </button>
                </div>
            </div>
            @endif

            <div class="mb-3" id="new-preview-wrap" style="display:none;">
                <label class="form-label fw-bold">Preview File Baru</label>
                <div>
                    <img id="new-preview-image" alt="Preview file banner baru" style="max-width: 100%; max-height: 220px; border-radius: 8px; border: 1px solid #ddd;">
                </div>
            </div>

            <div id="upload-notice" class="alert alert-info py-2" style="display:none;">
                <i class="ri-information-line"></i> Upload file gambar baru di bawah untuk mengganti.
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="ri-save-line"></i> Simpan Perubahan
            </button>
            <a href="{{ route('parja.alumni-banner.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const fileInput = document.getElementById('image_file');
    const previewWrap = document.getElementById('new-preview-wrap');
    const previewImage = document.getElementById('new-preview-image');
    const currentImage = document.getElementById('current-image-preview');
    const btnChangeImage = document.getElementById('btn-change-image');
    const uploadNotice = document.getElementById('upload-notice');

    if (!fileInput || !previewWrap || !previewImage) {
        return;
    }

    if (btnChangeImage) {
        btnChangeImage.addEventListener('click', function (event) {
            event.preventDefault();
            fileInput.focus();
            fileInput.scrollIntoView({ behavior: 'smooth', block: 'center' });
            if (uploadNotice) {
                uploadNotice.style.display = 'block';
                setTimeout(() => {
                    uploadNotice.style.display = 'none';
                }, 5000);
            }
        });
    }

    fileInput.addEventListener('change', function () {
        const file = this.files && this.files[0] ? this.files[0] : null;

        if (!file) {
            previewWrap.style.display = 'none';
            previewImage.removeAttribute('src');
            if (currentImage) {
                currentImage.style.display = 'inline-block';
            }
            return;
        }

        const objectUrl = URL.createObjectURL(file);
        previewImage.src = objectUrl;
        previewWrap.style.display = 'block';

        if (currentImage) {
            currentImage.style.display = 'none';
        }
    });
});
</script>
@endpush
