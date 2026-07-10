@extends('parja::layouts.app')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <div>
        <ol class="breadcrumb fs-sm mb-1">
            <li class="breadcrumb-item">Parja Alumni</li>
            <li class="breadcrumb-item"><a href="{{ route('parja.alumni-info.index') }}">Info Alumni Parja</a></li>
            <li class="breadcrumb-item active" aria-current="page">Edit</li>
        </ol>
        <h4 class="main-title mb-0">Edit Info Alumni Parja</h4>
        <p class="text-muted mb-0 fs-sm mt-1">
            <span class="badge {{ $record->tipe_badge_class }} me-1">{{ $record->tipe_label }}</span>
            {{ $record->judul }}
        </p>
    </div>
</div>

<form action="{{ route('parja.alumni-info.update', $record->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

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
                               value="{{ old('judul', $record->judul) }}" required>
                        @error('judul')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label for="ringkasan" class="form-label fw-bold">Ringkasan / Excerpt</label>
                        <textarea id="ringkasan" name="ringkasan" rows="2"
                                  class="form-control @error('ringkasan') is-invalid @enderror"
                                  maxlength="500">{{ old('ringkasan', $record->ringkasan) }}</textarea>
                        @error('ringkasan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label for="konten" class="form-label fw-bold">Konten / Isi <span class="text-danger">*</span></label>
                        <textarea id="konten" name="konten" rows="12"
                                  class="form-control @error('konten') is-invalid @enderror">{{ old('konten', $record->konten) }}</textarea>
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
                            <option value="berita"     {{ old('tipe', $record->tipe) == 'berita'     ? 'selected' : '' }}>📰 Berita</option>
                            <option value="kegiatan"   {{ old('tipe', $record->tipe) == 'kegiatan'   ? 'selected' : '' }}>📅 Kegiatan</option>
                            <option value="pengumuman" {{ old('tipe', $record->tipe) == 'pengumuman' ? 'selected' : '' }}>📢 Pengumuman</option>
                        </select>
                        @error('tipe')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label for="tanggal_publikasi" class="form-label fw-bold">Tanggal Publikasi</label>
                        <input type="date" id="tanggal_publikasi" name="tanggal_publikasi"
                               class="form-control @error('tanggal_publikasi') is-invalid @enderror"
                               value="{{ old('tanggal_publikasi', $record->tanggal_publikasi?->format('Y-m-d')) }}">
                        @error('tanggal_publikasi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label for="tanggal_berakhir" class="form-label fw-bold">Tanggal Berakhir
                            <small class="text-muted fw-normal">(opsional — berlaku untuk semua tipe)</small>
                        </label>
                        <input type="date" id="tanggal_berakhir" name="tanggal_berakhir"
                               class="form-control @error('tanggal_berakhir') is-invalid @enderror"
                               value="{{ old('tanggal_berakhir', $record->tanggal_berakhir?->format('Y-m-d')) }}">
                        <div class="form-text">Jika diisi, notifikasi <strong>Deadline Lewat / H-X</strong> akan muncul otomatis di kartu Berita, Kegiatan, maupun Pengumuman.</div>
                        @error('tanggal_berakhir')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                </div>
            </div>

            {{-- Thumbnail --}}
            <div class="card mb-3">
                <div class="card-header fw-semibold">Thumbnail / Foto</div>
                <div class="card-body">

                    @if($record->thumbnail)
                    <div class="mb-2">
                        <label class="form-label fw-bold text-muted small">Thumbnail saat ini:</label>
                        <img src="{{ asset('uploads/alumni-info/' . $record->thumbnail) }}"
                             alt="Thumbnail" class="img-fluid rounded border mb-2"
                             style="max-height:160px; object-fit:cover; width:100%;">
                        <small class="text-muted d-block">Upload gambar baru untuk mengganti.</small>
                    </div>
                    @endif

                    <div class="mb-2">
                        <label for="thumbnail" class="form-label fw-bold">
                            {{ $record->thumbnail ? 'Ganti Gambar' : 'Upload Gambar' }}
                        </label>
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

            {{-- Opsi tampilan --}}
            <div class="card mb-3">
                <div class="card-header fw-semibold">Opsi Tampilan</div>
                <div class="card-body">

                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" role="switch"
                               id="status_publikasi" name="status_publikasi" value="1"
                               {{ old('status_publikasi', $record->status_publikasi) ? 'checked' : '' }}>
                        <label class="form-check-label" for="status_publikasi">
                            <strong>Tayangkan Sekarang</strong>
                            <br><small class="text-muted">Konten langsung tampil di website alumni.</small>
                        </label>
                    </div>

                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" role="switch"
                               id="is_slideshow" name="is_slideshow" value="1"
                               {{ old('is_slideshow', $record->is_slideshow) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_slideshow">
                            <strong>Tampilkan di Slideshow</strong>
                            <br><small class="text-muted">Tampil di carousel halaman utama alumni.</small>
                        </label>
                    </div>

                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch"
                               id="is_pinned" name="is_pinned" value="1"
                               {{ old('is_pinned', $record->is_pinned) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_pinned">
                            <strong>Sematkan (Pin)</strong>
                            <br><small class="text-muted">Muncul di urutan teratas.</small>
                        </label>
                    </div>

                </div>
            </div>

            {{-- Info audit --}}
            <div class="card mb-3 border-dashed" style="border-style:dashed!important">
                <div class="card-body py-2 px-3">
                    <small class="text-muted">
                        <i class="ri-history-line me-1"></i>
                        Dibuat oleh <strong>{{ $record->user_input ?? '-' }}</strong>
                        @if($record->tanggal_input)
                            pada {{ $record->tanggal_input->format('d M Y H:i') }}
                        @endif
                        @if($record->user_update)
                            <br>Diperbarui oleh <strong>{{ $record->user_update }}</strong>
                            @if($record->tanggal_update)
                                pada {{ $record->tanggal_update->format('d M Y H:i') }}
                            @endif
                        @endif
                    </small>
                </div>
            </div>

            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="ri-save-line"></i> Simpan Perubahan
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
