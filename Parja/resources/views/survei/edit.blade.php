@extends('parja::layouts.app')
@php
    $routePrefix = $routePrefix
        ?? (request()->routeIs('parja.alumni-survei.*')
            ? 'parja.alumni-survei.'
            : (request()->routeIs('alumni.survei.*') ? 'alumni.survei.' : 'parja.survei.'));
@endphp

@section('title', 'Edit Survei')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="main-title mb-0">Edit Survei</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 fs-sm">
                <li class="breadcrumb-item"><a href="{{ route('parja.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route($routePrefix . 'index') }}">Daftar Survei</a></li>
                <li class="breadcrumb-item active">Edit</li>
            </ol>
        </nav>
    </div>
</div>

<div class="card card-one">
    <div class="card-body">
        <form action="{{ route($routePrefix . 'update', $survei->id) }}" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
            @csrf @method('PUT')

            <div class="mb-3">
                <label class="form-label fw-semibold">Judul Survei <span class="text-danger">*</span></label>
                <input type="text" name="judul" class="form-control @error('judul') is-invalid @enderror"
                       value="{{ old('judul', $survei->judul) }}" required>
                @error('judul')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Deskripsi</label>
                <textarea name="deskripsi" rows="3" class="form-control @error('deskripsi') is-invalid @enderror">{{ old('deskripsi', $survei->deskripsi) }}</textarea>
                @error('deskripsi')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Tanggal Mulai <span class="text-danger">*</span></label>
                <input type="date" name="tanggal_mulai" class="form-control @error('tanggal_mulai') is-invalid @enderror"
                       value="{{ old('tanggal_mulai', $survei->tanggal_mulai) }}" required>
                @error('tanggal_mulai')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Tanggal Selesai <span class="text-danger">*</span></label>
                <input type="date" name="tanggal_selesai" class="form-control @error('tanggal_selesai') is-invalid @enderror"
                       value="{{ old('tanggal_selesai', $survei->tanggal_selesai) }}" required>
                @error('tanggal_selesai')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Status Publikasi <span class="text-danger">*</span></label>
                <select name="status_publikasi" class="form-select @error('status_publikasi') is-invalid @enderror" required>
                    <option value="draft" {{ old('status_publikasi', $survei->status_publikasi) === 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="terbit" {{ old('status_publikasi', $survei->status_publikasi) === 'terbit' ? 'selected' : '' }}>Terbit</option>
                    <option value="selesai" {{ old('status_publikasi', $survei->status_publikasi) === 'selesai' ? 'selected' : '' }}>Selesai</option>
                </select>
                @error('status_publikasi')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-4">
                <label class="form-label fw-semibold">Gambar Survei</label>
                @if($survei->file_name)
                    <div class="mb-2">
                        <img src="{{ asset('storage/parja/survei/' . $survei->file_name) }}"
                             alt="gambar survei" class="img-thumbnail" style="max-height:100px;">
                        <small class="d-block text-muted">Gambar saat ini. Upload baru untuk mengganti.</small>
                    </div>
                @endif
                <input type="file" name="gambar" class="form-control @error('gambar') is-invalid @enderror"
                       accept="image/jpg,image/jpeg,image/png,image/gif">
                <small class="text-muted">JPG, PNG, GIF maks. 2MB</small>
                @error('gambar')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-success">Simpan Perubahan</button>
                <a href="{{ route($routePrefix . 'index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection

