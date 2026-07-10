@extends('parja::layouts.app')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <div>
        <ol class="breadcrumb fs-sm mb-1">
            <li class="breadcrumb-item">Parja Alumni</li>
            <li class="breadcrumb-item"><a href="{{ route('parja.alumni-banner.index') }}">Data Banner Alumni</a></li>
            <li class="breadcrumb-item active" aria-current="page">Tambah Banner</li>
        </ol>
        <h4 class="main-title mb-0">Tambah Data Banner Alumni</h4>
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

        <form action="{{ route('parja.alumni-banner.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label for="urutan" class="form-label fw-bold">Urutan Banner <span class="text-danger">*</span></label>
                <input type="number" min="1" max="99" class="form-control @error('urutan') is-invalid @enderror" id="urutan" name="urutan" value="{{ old('urutan') }}" placeholder="Contoh: 1" required>
                @error('urutan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                <small class="text-muted">Semakin kecil angka urutan, semakin awal tampil pada banner besar beranda.</small>
            </div>

            <div class="mb-3">
                <label for="judul" class="form-label fw-bold">Judul Banner <span class="text-danger">*</span></label>
                <input type="text" maxlength="255" class="form-control @error('judul') is-invalid @enderror" id="judul" name="judul" value="{{ old('judul') }}" placeholder="Contoh: Program Alumni 2026" required>
                @error('judul')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label for="image_file" class="form-label fw-bold">Upload File Gambar <span class="text-danger">*</span></label>
                <input type="file" accept=".jpg,.jpeg,.png,.webp,image/*" class="form-control @error('image_file') is-invalid @enderror" id="image_file" name="image_file" required>
                @error('image_file')<div class="invalid-feedback">{{ $message }}</div>@enderror
                <small class="text-muted">Wajib upload gambar. File otomatis dioptimasi ukuran saat disimpan.</small>
            </div>

            <div class="mb-3" id="preview-wrap" style="display:none;">
                <label class="form-label fw-bold">Preview File Terpilih</label>
                <div>
                    <img id="preview-image" alt="Preview file banner" style="max-width: 100%; max-height: 220px; border-radius: 8px; border: 1px solid #ddd;">
                </div>
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="ri-save-line"></i> Simpan Banner
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
    const previewWrap = document.getElementById('preview-wrap');
    const previewImage = document.getElementById('preview-image');

    if (!fileInput || !previewWrap || !previewImage) {
        return;
    }

    fileInput.addEventListener('change', function () {
        const file = this.files && this.files[0] ? this.files[0] : null;

        if (!file) {
            previewWrap.style.display = 'none';
            previewImage.removeAttribute('src');
            return;
        }

        const objectUrl = URL.createObjectURL(file);
        previewImage.src = objectUrl;
        previewWrap.style.display = 'block';
    });
});
</script>
@endpush
