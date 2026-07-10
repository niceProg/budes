@extends('parja::layouts.app')
@php
    $routePrefix = $routePrefix
        ?? (request()->routeIs('parja.alumni-survei.*')
            ? 'parja.alumni-survei.'
            : (request()->routeIs('alumni.survei.*') ? 'alumni.survei.' : 'parja.survei.'));
@endphp

@section('title', 'Laporan Hasil Survei')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="main-title mb-0">Laporan Hasil Survei</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 fs-sm">
                <li class="breadcrumb-item"><a href="{{ route($routePrefix . 'index') }}">Daftar Survei</a></li>
                <li class="breadcrumb-item"><a href="{{ route($routePrefix . 'show', $survei->id) }}">{{ Str::limit($survei->judul, 40) }}</a></li>
                <li class="breadcrumb-item active">Laporan</li>
            </ol>
        </nav>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route($routePrefix . 'laporan.export-csv', $survei->id) }}" class="btn btn-success btn-sm">
            <i class="ri-download-line me-1"></i> Export CSV
        </a>
        <a href="{{ route($routePrefix . 'show', $survei->id) }}" class="btn btn-secondary btn-sm">
            <i class="ri-arrow-left-line me-1"></i> Kembali
        </a>
    </div>
</div>

{{-- Info Survei --}}
<div class="card card-one mb-4">
    <div class="card-body">
        <h6 class="fw-bold mb-1">{{ $survei->judul }}</h6>
        <p class="text-muted mb-1 fs-sm">{{ $survei->deskripsi ?: '-' }}</p>
        <span class="text-muted fs-sm">
            Periode: {{ $survei->tanggal_mulai ? \Carbon\Carbon::parse($survei->tanggal_mulai)->format('d/m/Y') : '-' }}
            &mdash; {{ $survei->tanggal_selesai ? \Carbon\Carbon::parse($survei->tanggal_selesai)->format('d/m/Y') : '-' }}
        </span>
        &nbsp;
        @if($survei->status_publikasi === 'terbit')
            <span class="badge bg-success">Terbit</span>
        @elseif($survei->status_publikasi === 'selesai')
            <span class="badge bg-dark">Selesai</span>
        @else
            <span class="badge bg-secondary">Draft</span>
        @endif
    </div>
</div>

{{-- Statistik --}}
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card card-one text-center py-3">
            <div class="fs-2 fw-bold text-primary">{{ $totalTerdaftar }}</div>
            <div class="text-muted fs-sm">Peserta Terdaftar</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card-one text-center py-3">
            <div class="fs-2 fw-bold text-success">{{ $totalSelesai }}</div>
            <div class="text-muted fs-sm">Sudah Mengisi</div>
        </div>
    </div>
    {{-- Tambahkan statistik lain jika perlu --}}
</div>

{{-- Tambahkan konten laporan detail di sini --}}
@endsection

