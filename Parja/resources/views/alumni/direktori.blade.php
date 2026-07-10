@extends('parja::alumni.layouts.app')

@section('title', 'Cari Alumni')
@section('page-title', 'Cari Alumni')

@push('styles')
<style>
    .search-card {
        border-radius: 18px;
        border: 1px solid var(--parja-border);
        background: #fff;
        padding: 20px 24px;
        box-shadow: 0 6px 14px rgba(65, 23, 75, 0.06);
        margin-bottom: 20px;
    }

    .search-card .form-control,
    .search-card .form-select {
        border-radius: 10px;
        border: 1.5px solid var(--parja-border);
        font-size: 0.9rem;
        padding: 8px 14px;
        transition: border-color 0.15s;
    }

    .search-card .form-control:focus,
    .search-card .form-select:focus {
        border-color: var(--parja-magenta);
        box-shadow: none;
    }

    .alumni-card {
        border-radius: 18px;
        border: 1px solid var(--parja-border);
        background: #fff;
        padding: 20px;
        box-shadow: 0 6px 14px rgba(65, 23, 75, 0.06);
        height: 100%;
        display: flex;
        flex-direction: column;
        transition: box-shadow 0.2s, transform 0.2s;
    }

    .alumni-card:hover {
        box-shadow: 0 12px 28px rgba(65, 23, 75, 0.13);
        transform: translateY(-2px);
    }

    .alumni-card-avatar {
        width: 64px;
        height: 64px;
        border-radius: 50%;
        object-fit: cover;
        border: 2.5px solid var(--parja-border);
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, var(--parja-magenta), var(--parja-purple));
        color: #fff;
        font-weight: 800;
        font-size: 1.4rem;
        margin: 0 auto 12px;
    }

    .alumni-card-name {
        font-weight: 700;
        color: var(--parja-purple);
        font-size: 0.97rem;
        text-align: center;
        margin-bottom: 4px;
    }

    .alumni-card-sub {
        font-size: 0.79rem;
        color: var(--parja-muted);
        text-align: center;
        margin-bottom: 0;
        line-height: 1.5;
    }

    .alumni-card-info {
        margin: 10px 0;
        flex: 1;
    }

    .alumni-info-row {
        display: flex;
        align-items: flex-start;
        gap: 8px;
        font-size: 0.82rem;
        color: var(--parja-text);
        padding: 5px 0;
        border-bottom: 1px solid rgba(65, 23, 75, 0.07);
    }

    .alumni-info-row:last-child {
        border-bottom: 0;
    }

    .alumni-info-row i {
        color: var(--parja-magenta);
        font-size: 0.95rem;
        margin-top: 1px;
        flex-shrink: 0;
    }

    .alumni-info-row .alumni-icon-fallback {
        color: var(--parja-magenta);
        font-size: 0.95rem;
        line-height: 1;
        margin-top: 1px;
        flex-shrink: 0;
        width: 0.95rem;
        display: inline-flex;
        justify-content: center;
    }

    .btn-lihat-profil {
        display: block;
        width: 100%;
        text-align: center;
        background: var(--parja-magenta);
        color: #fff;
        font-weight: 600;
        font-size: 0.84rem;
        border-radius: 50px;
        padding: 8px 14px;
        text-decoration: none;
        margin-top: 12px;
        transition: background 0.15s;
    }

    .btn-lihat-profil:hover {
        background: #a60046;
        color: #fff;
    }

    .empty-hasil {
        text-align: center;
        padding: 60px 20px;
        color: var(--parja-muted);
    }

    .empty-hasil i {
        font-size: 3rem;
        color: var(--parja-border);
        display: block;
        margin-bottom: 12px;
    }

    .result-count-bar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 14px;
        flex-wrap: wrap;
        gap: 8px;
    }
</style>
@endpush

@section('content')

<x-parja.panel
    title="Cari Alumni"
    subtitle="Temukan sesama alumni Parja dan lihat profil mereka."
>
    <x-slot:chip>
        <x-parja.chip icon="ri-search-line">Direktori Alumni</x-parja.chip>
    </x-slot:chip>
</x-parja.panel>

{{-- Search & Filter --}}
<div class="search-card mt-3">
    <form action="{{ route('alumni.direktori') }}" method="GET">
        <div class="row g-3 align-items-end">
            <div class="col-lg-4 col-md-6">
                <label class="form-label fw-semibold mb-1" style="color: var(--parja-purple); font-size:0.85rem;">Cari Nama</label>
                <div class="input-group">
                    <span class="input-group-text" style="border-radius:10px 0 0 10px; border:1.5px solid var(--parja-border); border-right:0; background:#fff; color: var(--parja-magenta);">
                        <i class="ri-search-line"></i>
                    </span>
                    <input type="text" name="nama" value="{{ $filters['nama'] ?? '' }}"
                           class="form-control" style="border-left:0; border-radius:0 10px 10px 0;"
                           placeholder="Nama alumni...">
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <label class="form-label fw-semibold mb-1" style="color: var(--parja-purple); font-size:0.85rem;">Filter Dapil</label>
                <select name="dapil" class="form-select">
                    <option value="">Semua Dapil</option>
                    @foreach ($dapils as $d)
                        <option value="{{ $d }}" {{ ($filters['dapil'] ?? '') === $d ? 'selected' : '' }}>
                            {{ $d }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-2 col-md-6">
                <label class="form-label fw-semibold mb-1" style="color: var(--parja-purple); font-size:0.85rem;">Angkatan</label>
                <select name="tahun_angkatan" class="form-select">
                    <option value="">Semua</option>
                    @foreach ($angkatans as $a)
                        <option value="{{ $a }}" {{ ($filters['tahun_angkatan'] ?? '') == $a ? 'selected' : '' }}>
                            {{ $a }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-3 col-md-6">
                <label class="form-label fw-semibold mb-1" style="color: var(--parja-purple); font-size:0.85rem;">Asal Sekolah</label>
                <input type="text" name="asal_sekolah" value="{{ $filters['asal_sekolah'] ?? '' }}"
                       class="form-control" placeholder="Nama sekolah...">
            </div>
        </div>
        <div class="d-flex gap-2 mt-3">
            <button type="submit" class="btn btn-sm fw-semibold text-white px-4"
                    style="background: var(--parja-magenta); border-radius: 50px;">
                <i class="ri-search-line"></i> Cari
            </button>
            @if (array_filter($filters))
                <a href="{{ route('alumni.direktori') }}" class="btn btn-sm btn-outline-secondary fw-semibold px-4 rounded-pill">
                    <i class="ri-refresh-line"></i> Reset
                </a>
            @endif
        </div>
    </form>
</div>

{{-- Hasil --}}
<div class="result-count-bar">
    <p class="mb-0 fw-semibold" style="color: var(--parja-purple); font-size:0.9rem;">
        <i class="ri-group-line me-1" style="color: var(--parja-magenta);"></i>
        {{ $results->total() }} alumni ditemukan
        @if (array_filter($filters))
            <span class="text-muted fw-normal">(difilter)</span>
        @endif
    </p>
    <p class="mb-0 small text-muted">Halaman {{ $results->currentPage() }} dari {{ $results->lastPage() }}</p>
</div>

@if ($results->isEmpty())
    <div class="search-card">
        <div class="empty-hasil">
            <i class="ri-user-search-line"></i>
            <p class="fw-semibold mb-1">Tidak ada alumni yang ditemukan.</p>
            <p class="small text-muted">Coba ubah kata kunci atau filter pencarian.</p>
        </div>
    </div>
@else
    <div class="row g-3">
        @foreach ($results as $al)
            @php
                $foto   = $al->profile?->foto_profil ? asset('storage/' . $al->profile->foto_profil) : null;
                $inisial = strtoupper(substr($al->nama ?? 'A', 0, 1));
            @endphp
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="alumni-card">
                    {{-- Avatar --}}
                    @if ($foto)
                        <img src="{{ $foto }}" alt="{{ $al->nama }}" class="alumni-card-avatar" style="width:64px;height:64px;border-radius:50%;object-fit:cover;display:block;margin:0 auto 12px;">
                    @else
                        <div class="alumni-card-avatar">{{ $inisial }}</div>
                    @endif

                    <p class="alumni-card-name">{{ $al->nama ?? '-' }}</p>
                    <p class="alumni-card-sub">{{ $al->dapil ?? 'Dapil belum diisi' }}</p>

                    <div class="alumni-card-info">
                        @if ($al->tahun_angkatan)
                            <div class="alumni-info-row">
                                <i class="ri-calendar-line"></i>
                                <span>Angkatan {{ $al->tahun_angkatan }}</span>
                            </div>
                        @endif
                        @if ($al->asal_sekolah)
                            <div class="alumni-info-row">
                                <span class="alumni-icon-fallback" aria-hidden="true">🎓</span>
                                <span>{{ Str::limit($al->asal_sekolah, 28) }}</span>
                            </div>
                        @endif
                        @if ($al->profile?->pekerjaan_utama)
                            <div class="alumni-info-row">
                                <i class="ri-briefcase-line"></i>
                                <span>{{ Str::limit($al->profile->pekerjaan_utama, 32) }}</span>
                            </div>
                        @endif
                        @if ($al->profile?->domisili_terakhir)
                            <div class="alumni-info-row">
                                <i class="ri-map-pin-line"></i>
                                <span>{{ Str::limit($al->profile->domisili_terakhir, 28) }}</span>
                            </div>
                        @endif
                    </div>

                    <a href="{{ route('alumni.direktori.view', $al->id) }}" class="btn-lihat-profil">
                        <i class="ri-user-3-line"></i> Lihat Profil
                    </a>
                </div>
            </div>
        @endforeach
    </div>

    <div class="d-flex justify-content-center mt-4">
        {{ $results->links() }}
    </div>
@endif

@endsection
