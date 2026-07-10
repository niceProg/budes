@extends('parja::alumni.layouts.app')

@section('title', 'Data Survei Alumni')
@section('page-title', 'Data Survei')

@push('styles')
<style>
    .survey-shell {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
        gap: 16px;
    }

    .survey-card {
        background: #fff;
        border: 1px solid var(--parja-border);
        border-radius: 16px;
        padding: 18px;
        box-shadow: 0 6px 14px rgba(65, 23, 75, 0.06);
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .survey-badge {
        display: inline-flex;
        align-items: center;
        padding: 4px 10px;
        border-radius: 999px;
        font-size: 0.74rem;
        font-weight: 700;
        letter-spacing: 0.01em;
    }

    .survey-badge.ready {
        background: rgba(2, 122, 72, 0.14);
        color: #027a48;
    }

    .survey-badge.progress {
        background: rgba(245, 158, 11, 0.15);
        color: #b54708;
    }

    .survey-badge.done {
        background: rgba(38, 131, 255, 0.14);
        color: #175cd3;
    }

    .survey-title {
        margin: 0;
        font-size: 1.02rem;
        font-weight: 700;
        color: var(--parja-text);
    }

    .survey-desc {
        margin: 0;
        color: var(--parja-muted);
        font-size: 0.88rem;
        line-height: 1.6;
    }

    .survey-meta {
        margin: 0;
        font-size: 0.8rem;
        color: var(--parja-muted);
    }

    .survey-cta {
        margin-top: auto;
        display: inline-flex;
        justify-content: center;
        align-items: center;
        width: 100%;
        border-radius: 10px;
        padding: 10px 14px;
        font-weight: 700;
        text-decoration: none;
    }

    .survey-empty {
        background: #fff;
        border: 1px dashed var(--parja-border);
        border-radius: 16px;
        padding: 30px 18px;
        text-align: center;
        color: var(--parja-muted);
    }
</style>
@endpush

@section('content')
<div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
    <div>
        <h5 class="mb-1 fw-bold text-maroon">Survei Aktif Untuk Anda</h5>
        <p class="mb-0 text-muted">Silakan isi survei yang tersedia. Anda hanya melihat survei untuk pengisian user.</p>
    </div>
    <a href="{{ route('alumni.survei.history') }}" class="btn btn-outline-secondary btn-sm">
        <i class="ri-history-line"></i> Lihat Riwayat
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

@if($surveiList->isEmpty())
    <div class="survey-empty">
        <i class="ri-survey-line" style="font-size: 2.2rem; display:block; margin-bottom:8px;"></i>
        <h6 class="mb-1">Belum Ada Survei Aktif</h6>
        <p class="mb-0">Saat ini belum ada survei terbit yang bisa diisi.</p>
    </div>
@else
    <div class="survey-shell">
        @foreach($surveiList as $item)
            @php
                $peserta = $pesertaMap->get($item->id);
                $status = $peserta->status_partisipasi ?? 'terdaftar';
                $badgeClass = $status === 'selesai' ? 'done' : ($status === 'sedang_mengisi' ? 'progress' : 'ready');
                $badgeLabel = $status === 'selesai' ? 'Selesai Diisi' : ($status === 'sedang_mengisi' ? 'Sedang Diisi' : 'Siap Diisi');
                $buttonLabel = $status === 'selesai' ? 'Lihat Hasil' : 'Isi Survei';
                $targetUrl = $status === 'selesai'
                    ? route('alumni.survei.result', $item->id)
                    : route('alumni.survei.show', $item->id);
            @endphp
            <article class="survey-card">
                <span class="survey-badge {{ $badgeClass }}">{{ $badgeLabel }}</span>

                <h6 class="survey-title">{{ $item->judul }}</h6>
                <p class="survey-desc">{{ $item->deskripsi ?: 'Survei ini belum memiliki deskripsi tambahan.' }}</p>

                <p class="survey-meta mb-0">
                    <i class="ri-question-answer-line"></i>
                    {{ (int) $item->total_pertanyaan }} pertanyaan
                </p>

                <p class="survey-meta">
                    <i class="ri-calendar-2-line"></i>
                    Batas isi:
                    {{ $item->tanggal_selesai ? \Carbon\Carbon::parse($item->tanggal_selesai)->translatedFormat('d M Y') : 'Tidak dibatasi' }}
                </p>

                <a href="{{ $targetUrl }}" class="btn btn-maroon survey-cta">
                    {{ $buttonLabel }}
                </a>
            </article>
        @endforeach
    </div>
@endif
@endsection
