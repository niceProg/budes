@extends('parja::layouts.app')
@php
    $routePrefix = $routePrefix
        ?? (request()->routeIs('parja.alumni-survei.*')
            ? 'parja.alumni-survei.'
            : (request()->routeIs('alumni.survei.*') ? 'alumni.survei.' : 'parja.survei.'));
@endphp

@section('title', 'Detail Survei')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="main-title mb-0">Detail Survei</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 fs-sm">
                <li class="breadcrumb-item"><a href="{{ route('parja.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route($routePrefix . 'index') }}">Daftar Survei</a></li>
                <li class="breadcrumb-item active">Detail</li>
            </ol>
        </nav>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route($routePrefix . 'edit', $survei->id) }}" class="btn btn-warning btn-sm">
            <i class="ri-edit-line me-1"></i> Edit Survei
        </a>
        <a href="{{ route($routePrefix . 'kuesioner.index', $survei->id) }}" class="btn btn-primary btn-sm">
            <i class="ri-questionnaire-line me-1"></i> Kelola Kuesioner
        </a>
        <a href="{{ route($routePrefix . 'peserta.index', $survei->id) }}" class="btn btn-info btn-sm">
            <i class="ri-group-line me-1"></i> Kelola Peserta
        </a>
        <a href="{{ route($routePrefix . 'laporan.index', $survei->id) }}" class="btn btn-secondary btn-sm">
            <i class="ri-bar-chart-line me-1"></i> Laporan
        </a>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-8">
        <div class="card card-one">
            <div class="card-header"><h6 class="card-title mb-0">Informasi Survei</h6></div>
            <div class="card-body">
                @if($survei->file_name)
                    <img src="{{ asset('storage/parja/survei/' . $survei->file_name) }}"
                         alt="gambar" class="img-fluid rounded mb-3" style="max-height:200px;">
                @endif
                <table class="table table-sm table-borderless">
                    <tr>
                        <th width="180" class="text-muted">Judul</th>
                        <td>{{ $survei->judul }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted">Deskripsi</th>
                        <td>{{ $survei->deskripsi ?: '-' }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted">Tanggal Mulai</th>
                        <td>{{ $survei->tanggal_mulai ? \Carbon\Carbon::parse($survei->tanggal_mulai)->translatedFormat('d F Y') : '-' }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted">Tanggal Selesai</th>
                        <td>{{ $survei->tanggal_selesai ? \Carbon\Carbon::parse($survei->tanggal_selesai)->translatedFormat('d F Y') : '-' }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted">Status Publikasi</th>
                        <td>
                            @if($survei->status_publikasi === 'draft')
                                <span class="badge bg-secondary">Draft</span>
                            @elseif($survei->status_publikasi === 'terbit')
                                <span class="badge bg-success">Terbit</span>
                            @else
                                <span class="badge bg-dark">Selesai</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th class="text-muted">Dibuat oleh</th>
                        <td>{{ $survei->user_input ?: '-' }} &mdash; {{ $survei->tanggal_input ? \Carbon\Carbon::parse($survei->tanggal_input)->format('d/m/Y H:i') : '-' }}</td>
                    </tr>
                    @if($survei->user_update)
                    <tr>
                        <th class="text-muted">Diperbarui oleh</th>
                        <td>{{ $survei->user_update }} &mdash; {{ \Carbon\Carbon::parse($survei->tanggal_update)->format('d/m/Y H:i') }}</td>
                    </tr>
                    @endif
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

