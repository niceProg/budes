@extends('parja::layouts.app')

@section('title', 'Daftar Pengumuman Hasil')
@section('page-title', 'Daftar Pengumuman Hasil')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="main-title mb-0">Daftar Pengumuman Hasil</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 fs-sm">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Beranda</a></li>
                <li class="breadcrumb-item"><a href="{{ route('parja.dashboard') }}">Parja</a></li>
                <li class="breadcrumb-item active" aria-current="page">Daftar Pengumuman Hasil</li>
            </ol>
        </nav>
    </div>
</div>

<div class="card card-one">
    <div class="card-header">
        <h6 class="card-title fw-semibold mb-0">
            <i class="ri-megaphone-line me-2 text-primary"></i>Pengumuman Hasil
        </h6>
    </div>
    <div class="card-body">
        <p class="text-muted">Belum ada data. Fitur ini sedang dalam pengembangan.</p>
    </div>
</div>
@endsection
