@extends('parja::layouts.app')
@php
    $routePrefix = $routePrefix
        ?? (request()->routeIs('parja.alumni-survei.*')
            ? 'parja.alumni-survei.'
            : (request()->routeIs('alumni.survei.*') ? 'alumni.survei.' : 'parja.survei.'));
@endphp

@section('title', 'Tambah Pertanyaan')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="main-title mb-0">Tambah Pertanyaan</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 fs-sm">
                <li class="breadcrumb-item"><a href="{{ route($routePrefix . 'index') }}">Daftar Survei</a></li>
                <li class="breadcrumb-item"><a href="{{ route($routePrefix . 'kuesioner.index', $survei->id) }}">Kuesioner</a></li>
                <li class="breadcrumb-item active">Tambah</li>
            </ol>
        </nav>
    </div>
</div>

<div class="card card-one">
    <div class="card-header"><h6 class="card-title mb-0">Survei: {{ $survei->judul }}</h6></div>
    <div class="card-body">
        <form action="{{ route($routePrefix . 'kuesioner.store', $survei->id) }}" method="POST"
              class="needs-validation" novalidate>
            @csrf

            <div class="mb-3">
                <label class="form-label fw-semibold">Pertanyaan <span class="text-danger">*</span></label>
                <textarea name="pertanyaan" rows="3" class="form-control @error('pertanyaan') is-invalid @enderror"
                          required>{{ old('pertanyaan') }}</textarea>
                @error('pertanyaan')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Tipe Jawaban <span class="text-danger">*</span></label>
                <select name="tipe_jawaban" class="form-select @error('tipe_jawaban') is-invalid @enderror" required>
                    <option value="">-- Pilih Tipe --</option>
                    <option value="pilihan" {{ old('tipe_jawaban') === 'pilihan' ? 'selected' : '' }}>Pilihan Ganda</option>
                    <option value="isian" {{ old('tipe_jawaban') === 'isian' ? 'selected' : '' }}>Isian Teks</option>
                    <option value="skala" {{ old('tipe_jawaban') === 'skala' ? 'selected' : '' }}>Skala Rating</option>
                    <option value="checkbox" {{ old('tipe_jawaban') === 'checkbox' ? 'selected' : '' }}>Checkbox</option>
                </select>
                @error('tipe_jawaban')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-4">
                <label class="form-label fw-semibold">Deskripsi / Keterangan</label>
                <textarea name="deskripsi" rows="2" class="form-control @error('deskripsi') is-invalid @enderror">{{ old('deskripsi') }}</textarea>
                @error('deskripsi')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-success">Tambah Pertanyaan</button>
                <a href="{{ route($routePrefix . 'kuesioner.index', $survei->id) }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function() {
    'use strict';
    document.querySelectorAll('.needs-validation').forEach(function(form) {
        form.addEventListener('submit', function(event) {
            if (!form.checkValidity()) { event.preventDefault(); event.stopPropagation(); }
            form.classList.add('was-validated');
        }, false);
    });
})();
</script>
@endpush

