@extends('layouts.app')

@section('title', 'Buat Broadcast Email | Admin - SMART Setjen DPR RI')
@push('styles')
    @if(($channel ?? 'email') === 'email')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
    @endif
@endpush

@php
    $isWeb = ($channel ?? 'email') === 'web';
    $rp = $routePrefix ?? 'broadcast-email';
@endphp

@section('content')
@include('partials.table_skin_lamaran_css')
@include('admin.broadcast-email._form_styles')

<div class="content-wrapper">
    <div class="container-fluid px-md-5">
        <div class="page-title-area">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1">
                    <li class="breadcrumb-item">Menu</li>
                    <li class="breadcrumb-item">Sistem Magang</li>
                    <li class="breadcrumb-item"><a href="{{ route($rp.'.index') }}">{{ $isWeb ? 'Pengumuman Web' : 'Broadcast Email' }}</a></li>
                    <li class="breadcrumb-item active">Tambah</li>
                </ol>
            </nav>
            <h1 class="page-title">{{ $isWeb ? 'Tambah Pengumuman Web' : 'Tambah Broadcast Email' }}</h1>
        </div>

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert" style="border-radius: 12px; border: 0;">
                <i class="ri-error-warning-line me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="broadcast-card mb-5">
            <div class="broadcast-card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
                <div>
                    <h5 class="m-0 fw-800 text-dark">{{ $isWeb ? 'Form pengumuman aplikasi' : 'Form broadcast email' }}</h5>
                    <div class="text-muted" style="font-size:.86rem;">
                        @if($isWeb)
                            Terbitkan ke semua peserta magang di aplikasi (tanpa email).
                        @else
                            Buat pengumuman dan kirim langsung ke email peserta sesuai target.
                        @endif
                    </div>
                </div>
                @if(! $isWeb)
                <div class="broadcast-meta">
                    <i class="ri-mail-send-line text-warning"></i>
                    <span>Total data peserta (beremail): {{ count($pesertaOptions ?? []) }}</span>
                </div>
                @endif
            </div>

            <div class="broadcast-body">
                <form method="POST" action="{{ route($rp.'.store') }}" id="broadcast-form" data-no-global-loader>
                    @csrf
                    @include('admin.broadcast-email._form_fields', [
                        'broadcast' => null,
                        'pesertaOptions' => $pesertaOptions,
                        'submitLabel' => $isWeb ? 'Terbitkan pengumuman' : 'Kirim Pengumuman',
                        'channel' => $channel ?? 'email',
                        'routePrefix' => $rp,
                    ])
                </form>

                @include('admin.broadcast-email._table_size_modal')
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
    @if($isWeb)
        @include('admin.broadcast-email._form_scripts_web')
    @else
        @include('admin.broadcast-email._form_scripts')
    @endif
@endpush
