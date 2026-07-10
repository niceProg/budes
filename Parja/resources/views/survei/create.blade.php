@extends('parja::layouts.app')
@php
    $routePrefix = $routePrefix
        ?? (request()->routeIs('parja.alumni-survei.*')
            ? 'parja.alumni-survei.'
            : (request()->routeIs('alumni.survei.*') ? 'alumni.survei.' : 'parja.survei.'));
@endphp

@section('title', 'Tambah Survei')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="main-title mb-0">Tambah Survei</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 fs-sm">
                <li class="breadcrumb-item"><a href="{{ route('parja.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route($routePrefix . 'index') }}">Daftar Survei</a></li>
                <li class="breadcrumb-item active">Tambah</li>
            </ol>
        </nav>
    </div>
</div>

<div class="card card-one">
    <div class="card-body">
        <form action="{{ route($routePrefix . 'store') }}" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
            @csrf

            <div class="mb-3">
                <label class="form-label fw-semibold">Judul Survei <span class="text-danger">*</span></label>
                <input type="text" name="judul" class="form-control @error('judul') is-invalid @enderror"
                       value="{{ old('judul') }}" required>
                @error('judul')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Deskripsi</label>
                <textarea name="deskripsi" rows="3" class="form-control @error('deskripsi') is-invalid @enderror">{{ old('deskripsi') }}</textarea>
                @error('deskripsi')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Tanggal Mulai <span class="text-danger">*</span></label>
                <input type="date" name="tanggal_mulai" class="form-control @error('tanggal_mulai') is-invalid @enderror"
                       value="{{ old('tanggal_mulai') }}" required>
                @error('tanggal_mulai')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Tanggal Selesai <span class="text-danger">*</span></label>
                <input type="date" name="tanggal_selesai" class="form-control @error('tanggal_selesai') is-invalid @enderror"
                       value="{{ old('tanggal_selesai') }}" required>
                @error('tanggal_selesai')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Status Publikasi <span class="text-danger">*</span></label>
                <select name="status_publikasi" class="form-select @error('status_publikasi') is-invalid @enderror" required>
                    <option value="">-- Pilih Status --</option>
                    <option value="draft" {{ old('status_publikasi') === 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="terbit" {{ old('status_publikasi') === 'terbit' ? 'selected' : '' }}>Terbit</option>
                    <option value="selesai" {{ old('status_publikasi') === 'selesai' ? 'selected' : '' }}>Selesai</option>
                </select>
                @error('status_publikasi')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-4">
                <label class="form-label fw-semibold">Gambar Survei</label>
                <input type="file" name="gambar" class="form-control @error('gambar') is-invalid @enderror"
                       accept="image/jpg,image/jpeg,image/png,image/gif">
                <small class="text-muted">JPG, PNG, GIF maks. 2MB</small>
                @error('gambar')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-success">Simpan</button>
                <a href="{{ route($routePrefix . 'index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
// ... tambahkan script jika ada ...
</script>
@endpush

