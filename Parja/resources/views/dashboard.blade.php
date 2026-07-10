@extends('parja::layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard Parja')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="main-title mb-0">Dashboard</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 fs-sm">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Beranda</a></li>
                <li class="breadcrumb-item active" aria-current="page">Parja</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row g-3">

    {{-- Welcome Banner --}}
    <div class="col-12">
        <div class="card card-one border-0" style="background: linear-gradient(135deg, #1a5276 0%, #1f618d 100%);">
            <div class="card-body py-4 px-4 d-flex align-items-center gap-3">
                <div class="flex-shrink-0">
                    <span class="avatar avatar-lg rounded-circle bg-white bg-opacity-25">
                        <i class="ri-money-dollar-circle-line fs-4 text-white"></i>
                    </span>
                </div>
                <div>
                    <h5 class="fw-bold text-white mb-1">Selamat Datang di Parja</h5>
                    <p class="text-white-50 mb-0 fs-sm">Modul Parja &mdash; ePublic DPR RI</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Coming Soon --}}
    <div class="col-12">
        <div class="card card-one">
            <div class="card-body text-center py-5">
                <i class="ri-tools-line fs-1 text-muted mb-3 d-block"></i>
                <h5 class="fw-semibold text-dark mb-2">Modul Sedang Dikembangkan</h5>
                <p class="text-muted mb-0 fs-sm">Fitur-fitur modul Parja akan segera tersedia. Terima kasih atas kesabaran Anda.</p>
            </div>
        </div>
    </div>

</div>
@endsection
