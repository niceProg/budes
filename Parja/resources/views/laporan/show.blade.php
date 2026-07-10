@extends('parja::layouts.app')
@php
    $routePrefix = $routePrefix
        ?? (request()->routeIs('parja.alumni-survei.*')
            ? 'parja.alumni-survei.'
            : (request()->routeIs('alumni.survei.*') ? 'alumni.survei.' : 'parja.survei.'));
@endphp

@section('title', 'Detail Laporan Survei')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="main-title mb-0">Detail Laporan Survei</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 fs-sm">
                <li class="breadcrumb-item"><a href="{{ route($routePrefix . 'index') }}">Daftar Survei</a></li>
                <li class="breadcrumb-item"><a href="{{ route($routePrefix . 'show', $survei->id) }}">{{ Str::limit($survei->judul, 40) }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route($routePrefix . 'laporan.index', $survei->id) }}">Laporan</a></li>
                <li class="breadcrumb-item active">Detail</li>
            </ol>
        </nav>
    </div>
    <a href="{{ route($routePrefix . 'laporan.index', $survei->id) }}" class="btn btn-secondary btn-sm">
        <i class="ri-arrow-left-line me-1"></i> Kembali
    </a>
</div>

<div class="card card-one">
    <div class="card-body">
        <h6 class="fw-bold mb-2">{{ $survei->judul }}</h6>
        <p class="text-muted mb-2 fs-sm">{{ $survei->deskripsi ?: '-' }}</p>
        <hr>
        <table class="table table-sm table-borderless mb-0" style="max-width:560px">
            <tr>
                <th width="180" class="text-muted">Nama Peserta</th>
                <td>{{ $peserta->responden->nama ?? '-' }}</td>
            </tr>
            <tr>
                <th class="text-muted">Email</th>
                <td>{{ $peserta->responden->email ?? '-' }}</td>
            </tr>
            <tr>
                <th class="text-muted">No. Telepon</th>
                <td>{{ $peserta->responden->nomor_telepon ?? $peserta->responden->handphone ?? '-' }}</td>
            </tr>
            <tr>
                <th class="text-muted">Status Partisipasi</th>
                <td>{{ $peserta->status_partisipasi ?? '-' }}</td>
            </tr>
            <tr>
                <th class="text-muted">Progress</th>
                <td>{{ number_format($peserta->persentase_selesai ?? 0, 0) }}%</td>
            </tr>
            <tr>
                <th class="text-muted">Jumlah Pertanyaan</th>
                <td>{{ $totalPertanyaan }}</td>
            </tr>
        </table>
    </div>
</div>
@endsection

