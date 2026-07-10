@extends('parja::layouts.app')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <div>
        <ol class="breadcrumb fs-sm mb-1">
            <li class="breadcrumb-item">Parja Alumni</li>
            <li class="breadcrumb-item"><a href="{{ route('parja.alumni-info.index') }}">Info Alumni Parja</a></li>
            <li class="breadcrumb-item active" aria-current="page">Tambah Baru</li>
        </ol>
        <h4 class="main-title mb-0">Tambah Info Alumni Parja</h4>
    </div>
</div>

<form action="{{ route('parja.alumni-info.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    @if ($errors->any())
    <div class="alert alert-danger mb-3">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="row g-4">
        {{-- Kolom kiri: konten utama --}}
        <div class="col-lg-8">
            <div class="card mb-3">
                <div class="card-header fw-semibold">Informasi Utama</div>
                <div class="card-body">

                    <div class="mb-3">
                        <label for="judul" class="form-label fw-bold">Judul <span class="text-danger">*</span></label>
                        <input type="text" id="judul" name="judul"
                               class="form-control @error('judul') is-invalid @enderror"
                               placeholder="Masukkan judul berita / kegiatan / pengumuman"
                               value="{{ old('judul') }}" required>
                        @error('judul')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label for="ringkasan" class="form-label fw-bold">Ringkasan / Excerpt</label>
                        <textarea id="ringkasan" name="ringkasan" rows="2"
                                  class="form-control @error('ringkasan') is-invalid @enderror"
                                  placeholder="Deskripsi singkat (maks. 500 karakter, opsional)"
                                  maxlength="500">{{ old('ringkasan') }}</textarea>
                        @error('ringkasan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        <small class="text-muted">Akan ditampilkan sebagai preview di daftar info.</small>
                    </div>

                    <div class="mb-3">
                        <label for="konten" class="form-label fw-bold">Konten / Isi <span class="text-danger">*</span></label>
                        <textarea id="konten" name="konten" rows="12"
                                  class="form-control @error('konten') is-invalid @enderror"
                                  placeholder="Tulis konten lengkap di sini...">{{ old('konten') }}</textarea>
                        @error('konten')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                </div>
            </div>
        </div>

        {{-- Kolom kanan: meta & pengaturan --}}
        <div class="col-lg-4">

            {{-- Tipe & Tanggal --}}
            <div class="card mb-3">
                <div class="card-header fw-semibold">Pengaturan</div>
                <div class="card-body">

                    <div class="mb-3">
                        <label for="tipe" class="form-label fw-bold">Tipe Konten <span class="text-danger">*</span></label>
                        <select id="tipe" name="tipe"
                                class="form-select @error('tipe') is-invalid @enderror" required>
                            <option value="">-- Pilih Tipe --</option>
                            <option value="berita"     {{ old('tipe') == 'berita'     ? 'selected' : '' }}>📰 Berita</option>
                            <option value="kegiatan"   {{ old('tipe') == 'kegiatan'   ? 'selected' : '' }}>📅 Kegiatan</option>
                            <option value="pengumuman" {{ old('tipe') == 'pengumuman' ? 'selected' : '' }}>📢 Pengumuman</option>
                        </select>
                        @error('tipe')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label for="tanggal_publikasi" class="form-label fw-bold">Tanggal Publikasi</label>
                        <input type="date" id="tanggal_publikasi" name="tanggal_publikasi"
                               class="form-control @error('tanggal_publikasi') is-invalid @enderror"
                               value="{{ old('tanggal_publikasi', date('Y-m-d')) }}">
                        @error('tanggal_publikasi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3" id="wrap-tanggal-berakhir">
                        <label for="tanggal_berakhir" class="form-label fw-bold">Tanggal Berakhir
                            <small class="text-muted fw-normal">(opsional — berlaku untuk semua tipe)</small>
                        </label>
                        <input type="date" id="tanggal_berakhir" name="tanggal_berakhir"
                               class="form-control @error('tanggal_berakhir') is-invalid @enderror"
                               value="{{ old('tanggal_berakhir') }}">
                        <div class="form-text">Jika diisi, notifikasi <strong>Deadline Lewat / H-X</strong> akan muncul otomatis di kartu Berita, Kegiatan, maupun Pengumuman.</div>
                        @error('tanggal_berakhir')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                </div>
            </div>

            {{-- Thumbnail --}}
            <div class="card mb-3">
                <div class="card-header fw-semibold">Thumbnail / Foto</div>
                <div class="card-body">
                    <div class="mb-2">
                        <label for="thumbnail" class="form-label fw-bold">Upload Gambar</label>
                        <input type="file" id="thumbnail" name="thumbnail" accept="image/*"
                               class="form-control @error('thumbnail') is-invalid @enderror"
                               onchange="previewThumbnail(this)">
                        @error('thumbnail')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        <small class="text-muted">Format: JPG, PNG, WebP. Maks. 2 MB.</small>
                    </div>
                    <div id="thumbnail-preview" class="mt-2" style="display:none;">
                        <img id="thumbnail-img" src="#" alt="Preview"
                             class="img-fluid rounded border" style="max-height:180px; object-fit:cover; width:100%;">
                    </div>
                </div>
            </div>

            {{-- Opsi tambahan --}}
            <div class="card mb-3">
                <div class="card-header fw-semibold">Opsi Tampilan</div>
                <div class="card-body">

                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" role="switch"
                               id="status_publikasi" name="status_publikasi" value="1"
                               {{ old('status_publikasi') ? 'checked' : '' }}>
                        <label class="form-check-label" for="status_publikasi">
                            <strong>Tayangkan Sekarang</strong>
                            <br><small class="text-muted">Jika aktif, konten langsung tampil di website alumni.</small>
                        </label>
                    </div>

                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" role="switch"
                               id="is_slideshow" name="is_slideshow" value="1"
                               {{ old('is_slideshow') ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_slideshow">
                            <strong>Tampilkan di Slideshow</strong>
                            <br><small class="text-muted">Konten akan masuk carousel/slideshow di halaman utama alumni.</small>
                        </label>
                    </div>

                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch"
                               id="is_pinned" name="is_pinned" value="1"
                               {{ old('is_pinned') ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_pinned">
                            <strong>Sematkan (Pin)</strong>
                            <br><small class="text-muted">Konten akan selalu muncul di urutan teratas.</small>
                        </label>
                    </div>

                </div>
            </div>

            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="ri-save-line"></i> Simpan Info Alumni
                </button>
                <a href="{{ route('parja.alumni-info.index') }}" class="btn btn-outline-secondary">
                    <i class="ri-arrow-left-line"></i> Kembali
                </a>
            </div>

        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
    function previewThumbnail(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = e => {
                document.getElementById('thumbnail-img').src = e.target.result;
                document.getElementById('thumbnail-preview').style.display = 'block';
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endpush
