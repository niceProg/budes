@extends('parja::layouts.app')
@php
    $routePrefix = $routePrefix
        ?? (request()->routeIs('parja.alumni-survei.*')
            ? 'parja.alumni-survei.'
            : (request()->routeIs('alumni.survei.*') ? 'alumni.survei.' : 'parja.survei.'));
@endphp

@section('title', 'Detail Peserta Survei')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="main-title mb-0">Detail Peserta Survei</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 fs-sm">
                <li class="breadcrumb-item"><a href="{{ route($routePrefix . 'peserta.index', $survei->id) }}">Peserta</a></li>
                <li class="breadcrumb-item active">Detail</li>
            </ol>
        </nav>
    </div>
    <a href="{{ route($routePrefix . 'peserta.index', $survei->id) }}" class="btn btn-secondary btn-sm">
        <i class="ri-arrow-left-line me-1"></i> Kembali
    </a>
</div>

<div class="card card-one" style="max-width:560px">
    <div class="card-header"><h6 class="card-title mb-0">Info Peserta</h6></div>
    <div class="card-body">
        <table class="table table-sm table-borderless">
            <tr><th width="160" class="text-muted">Survei</th><td>{{ $survei->judul }}</td></tr>
            <tr><th class="text-muted">Nama Peserta</th><td>{{ $peserta->responden->nama ?? '-' }}</td></tr>
            <tr><th class="text-muted">No. Telepon</th><td>{{ $peserta->responden->handphone ?? $peserta->responden->nomor_telepon ?? '-' }}</td></tr>
            <tr><th class="text-muted">Status</th>
                <td>
                    @php
                        $m = ['terdaftar'=>['secondary','Terdaftar'],'sedang_mengisi'=>['warning','Sedang Mengisi'],'selesai'=>['success','Selesai'],'batal'=>['danger','Batal']];
                        $s = $m[$peserta->status_partisipasi] ?? ['light','-'];
                    @endphp
                    <span class="badge bg-{{ $s[0] }}">{{ $s[1] }}</span>
                </td>
            </tr>
            <tr><th class="text-muted">Progress</th><td>{{ number_format($peserta->persentase_selesai ?? 0, 0) }}%</td></tr>
            <tr><th class="text-muted">Tanggal Daftar</th><td>{{ $peserta->tanggal_daftar ? \Carbon\Carbon::parse($peserta->tanggal_daftar)->format('d/m/Y H:i') : '-' }}</td></tr>
            <tr><th class="text-muted">Mulai Mengisi</th><td>{{ $peserta->tanggal_mulai_mengisi ? \Carbon\Carbon::parse($peserta->tanggal_mulai_mengisi)->format('d/m/Y H:i') : '-' }}</td></tr>
            <tr><th class="text-muted">Selesai</th><td>{{ $peserta->tanggal_selesai ? \Carbon\Carbon::parse($peserta->tanggal_selesai)->format('d/m/Y H:i') : '-' }}</td></tr>
        </table>
    </div>
</div>
@endsection

