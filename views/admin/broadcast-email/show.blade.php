@extends('layouts.app')

@section('title', 'Detail Broadcast Email | Admin - SMART Setjen DPR RI')
@php
    $routePrefix = $routePrefix ?? 'broadcast-email';
    $isWeb = ($broadcast->channel ?? 'email') === 'web';
@endphp

@section('content')
@include('partials.table_skin_lamaran_css')
<style>
    .tiptap-content { line-height: 1.8; color: #0f172a; }
    .tiptap-content h1, .tiptap-content h2, .tiptap-content h3, .tiptap-content h4 { color: #0f172a; }
    .tiptap-content a { color: #0284c7; font-weight: 700; }
    .tiptap-content table { width: 100%; border-collapse: collapse; margin: 12px 0; }
    .tiptap-content th, .tiptap-content td { border: 1px solid #e2e8f0; padding: 10px; vertical-align: top; }
    .tiptap-content img { max-width: 100%; height: auto; }
    html[data-skin="dark"] .tiptap-content,
    html[data-skin="dark"] .tiptap-content h1,
    html[data-skin="dark"] .tiptap-content h2,
    html[data-skin="dark"] .tiptap-content h3,
    html[data-skin="dark"] .tiptap-content h4 { color: #e5e7eb !important; }
    html[data-skin="dark"] .tiptap-content th,
    html[data-skin="dark"] .tiptap-content td { border-color: #374151 !important; }
</style>

<div class="content-wrapper">
    <div class="container-fluid px-md-5">
        <div class="page-title-area">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1">
                    <li class="breadcrumb-item">Menu</li>
                    <li class="breadcrumb-item">Sistem Magang</li>
                    <li class="breadcrumb-item"><a href="{{ route($routePrefix.'.index') }}">{{ $isWeb ? 'Pengumuman Web' : 'Broadcast Email' }}</a></li>
                    <li class="breadcrumb-item active">Detail</li>
                </ol>
            </nav>
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <h1 class="page-title mb-0">{{ $isWeb ? 'Detail Pengumuman Web' : 'Detail Broadcast Email' }}</h1>
                <div class="d-flex flex-wrap gap-2">
                    @if((int) $broadcast->sent_recipients === 0)
                        <a href="{{ route($routePrefix.'.edit', $broadcast) }}" class="btn btn-primary fw-700" style="border-radius: 12px;">
                            <i class="ri-edit-line"></i> Ubah
                        </a>
                    @endif
                    <a href="{{ route($routePrefix.'.index') }}" class="btn btn-light fw-700" style="border-radius: 12px;">
                        <i class="ri-arrow-left-line"></i> Kembali ke daftar
                    </a>
                </div>
            </div>
        </div>

        <div class="modern-card">
            <div class="px-4 py-3 border-bottom" style="border-color: #e2e8f0;">
                <h5 class="m-0 fw-800 text-dark">{{ $broadcast->subject }}</h5>
                <div class="text-muted small mt-2">
                    @php
                        $st = strtolower((string) ($broadcast->process_status ?? 'queued'));
                        $stLabel = match ($st) {
                            'done' => 'Selesai',
                            'failed' => 'Gagal',
                            'processing' => 'Diproses',
                            default => 'Antrian',
                        };
                    @endphp
                    Status: <strong>{{ $stLabel }}</strong>
                    &middot; Dibuat: {{ $broadcast->created_at ? $broadcast->created_at->locale('id')->translatedFormat('d F Y H:i') : '' }}
                    @if($broadcast->processed_at)
                        &middot; Diproses: {{ $broadcast->processed_at->locale('id')->translatedFormat('d F Y H:i') }}
                    @endif
                    @if($broadcast->creator)
                        &middot; Oleh: {{ $broadcast->creator->nama ?? $broadcast->creator->email ?? '—' }}
                    @endif
                </div>
                <div class="small mt-2">
                    @if($isWeb)
                        <span class="badge-code">Target: Semua peserta (aplikasi)</span>
                        &nbsp;| Peserta: {{ (int) $broadcast->total_recipients }}
                        &middot; Diterbitkan: {{ (int) $broadcast->sent_recipients }}
                    @else
                        <span class="badge-code">Target: {{ $broadcast->target_type === 'khusus' ? 'Khusus' : 'Semua peserta' }}</span>
                        @if($broadcast->target_type === 'semua' && $broadcast->target_scope)
                            <span class="badge-code ms-1">{{ $broadcast->target_scope }}</span>
                        @endif
                        &nbsp;| Total: {{ (int) $broadcast->total_recipients }}
                        &middot; Berhasil: {{ (int) $broadcast->sent_recipients }}
                        &middot; Gagal: {{ (int) $broadcast->failed_recipients }}
                    @endif
                </div>
                @if($broadcast->error_message)
                    <div class="alert alert-danger mt-3 mb-0 small" style="border-radius: 12px;">
                        {{ $broadcast->error_message }}
                    </div>
                @endif
            </div>
            <div class="p-4">
                <label class="form-label fw-700">Isi pengumuman</label>
                <div class="tiptap-content border rounded-3 p-3" style="background: #fafafa; min-height: 200px;">
                    {!! clean($broadcast->content_html) !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
