@extends('parja::alumni.layouts.app')

@section('title', $info->judul)
@section('page-title', 'Detail Informasi')

@push('styles')
<style>
    .info-detail-card {
        border-radius: 18px;
        border: 1px solid var(--parja-border);
        background: #fff;
        padding: 32px;
        box-shadow: 0 8px 24px rgba(65, 23, 75, 0.08);
    }

    .info-header {
        margin-bottom: 24px;
        border-bottom: 1px solid var(--parja-border);
        padding-bottom: 20px;
    }

    .info-meta {
        display: flex;
        gap: 16px;
        align-items: center;
        font-size: 0.88rem;
        color: var(--parja-muted);
        margin-bottom: 16px;
    }

    .info-title {
        font-size: 1.8rem;
        font-weight: 800;
        color: var(--parja-purple);
        line-height: 1.3;
    }

    .info-image {
        width: 100%;
        max-height: 400px;
        object-fit: cover;
        border-radius: 14px;
        margin-bottom: 28px;
    }

    .info-content {
        font-size: 1.05rem;
        line-height: 1.8;
        color: var(--parja-text);
        white-space: pre-wrap;
    }

    .interaction-wrap {
        margin-top: 24px;
        padding-top: 20px;
        border-top: 1px solid var(--parja-border);
    }

    .interaction-summary {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
        margin-bottom: 14px;
    }

    .interaction-pill {
        border: 1px solid var(--parja-border);
        border-radius: 999px;
        padding: 5px 12px;
        font-size: 0.82rem;
        font-weight: 600;
        color: var(--parja-muted);
        background: #fff;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .comment-item {
        border: 1px solid var(--parja-border);
        border-radius: 12px;
        padding: 10px 12px;
        margin-bottom: 10px;
        background: #fff;
    }

    .comment-author {
        font-size: 0.83rem;
        font-weight: 700;
        color: var(--parja-purple);
        margin-bottom: 2px;
    }

    .comment-time {
        font-size: 0.72rem;
        color: var(--parja-muted);
    }

    .comment-text {
        margin-top: 6px;
        font-size: 0.9rem;
        color: var(--parja-text);
        white-space: pre-wrap;
        line-height: 1.55;
    }

    .detail-deadline-badge {
        font-size: 0.7rem;
        border-radius: 999px;
        padding: 3px 10px;
        font-weight: 800;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        text-transform: uppercase;
        letter-spacing: 0.03em;
        margin-bottom: 10px;
        background: rgba(220, 53, 69, 0.16);
        border: 1px solid rgba(220, 53, 69, 0.45);
        color: #a61d2d;
    }

    .detail-deadline-badge.critical {
        background: rgba(220, 53, 69, 0.22);
        border-color: rgba(220, 53, 69, 0.6);
        color: #8f1422;
    }

    .detail-deadline-badge.warning {
        background: rgba(255, 140, 0, 0.15);
        border-color: rgba(255, 140, 0, 0.5);
        color: #8a4a00;
    }

    .detail-deadline-badge.expired {
        background: rgba(108, 117, 125, 0.16);
        border-color: rgba(108, 117, 125, 0.45);
        color: #4f5962;
    }
</style>
@endpush

@section('content')
@if (session('success'))
    <div class="alert alert-success rounded-3 py-2 px-3 small mb-3">{{ session('success') }}</div>
@endif
@if (session('warning'))
    <div class="alert alert-warning rounded-3 py-2 px-3 small mb-3">{{ session('warning') }}</div>
@endif
@if (session('error'))
    <div class="alert alert-danger rounded-3 py-2 px-3 small mb-3">{{ session('error') }}</div>
@endif

<div class="row justify-content-center mb-5">
    <div class="col-lg-10">
        <a href="{{ route('alumni.kegiatan.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill fw-semibold mb-3">
            <i class="ri-arrow-left-line"></i> Kembali ke Daftar Info
        </a>

        <div class="info-detail-card">
            <div class="info-header">
                <div class="info-meta">
                    <span class="badge {{ $info->tipe_badge_class }}">{{ $info->tipe_label }}</span>
                    <span><i class="ri-calendar-event-line"></i> Dipublikasikan: {{ $info->tanggal_publikasi->format('d M Y') }}</span>
                    @if($info->tanggal_berakhir)
                        <span class="text-danger"><i class="ri-timer-line"></i> Hingga: {{ $info->tanggal_berakhir->format('d M Y') }}</span>
                    @endif
                </div>
                <h1 class="info-title">{{ $info->judul }}</h1>
            </div>

            @if($info->thumbnail)
                <img src="{{ asset('uploads/alumni-info/' . $info->thumbnail) }}" alt="{{ $info->judul }}" class="info-image">
            @endif

            <div class="info-content">
                {{ $info->konten }}
            </div>

            <div class="interaction-wrap" id="komentar">
                @php
                    $commentCount = (int) ($info->comments_count ?? 0);
                    $likeCount = (int) ($info->likes_count ?? 0);
                    $daysToDeadline = $info->tanggal_berakhir ? now()->startOfDay()->diffInDays($info->tanggal_berakhir->copy()->startOfDay(), false) : null;
                @endphp

                @if(!is_null($daysToDeadline))
                    @if($daysToDeadline < 0)
                        <div class="detail-deadline-badge expired"><i class="ri-history-line"></i> Deadline lewat</div>
                    @elseif($daysToDeadline <= 1)
                        <div class="detail-deadline-badge critical"><i class="ri-alarm-warning-line"></i> {{ $daysToDeadline === 0 ? 'Hari ini' : 'H-1' }}</div>
                    @elseif($daysToDeadline <= 3)
                        <div class="detail-deadline-badge warning"><i class="ri-time-line"></i> H-{{ $daysToDeadline }}</div>
                    @endif
                @endif

                <div class="interaction-summary">
                    @if($alumni)
                        <form action="{{ route('alumni.kegiatan.reminder', $info->id) }}"
                            method="POST"
                            class="m-0 js-reminder-toggle-form"
                            data-reminded="{{ $isReminded ? '1' : '0' }}"
                            data-agenda-title="{{ e($info->judul) }}">
                            @csrf
                            <button type="submit"
                                    class="interaction-pill"
                                    {{ $isInteractionOpen ? '' : 'disabled' }}
                                    style="{{ $isReminded ? 'color:var(--parja-magenta);border-color:rgba(191,0,80,0.35);background:rgba(191,0,80,0.06);' : '' }} {{ $isInteractionOpen ? '' : 'opacity:0.6;cursor:not-allowed;' }}">
                                <i class="{{ $isReminded ? 'ri-notification-3-fill' : 'ri-notification-3-line' }}"></i>
                                {{ $isReminded ? 'Diingatkan' : 'Ingatkan Saya' }}
                            </button>
                        </form>
                    @endif

                    @if($alumni)
                        <form action="{{ route('alumni.kegiatan.like', $info->id) }}" method="POST" class="m-0">
                            @csrf
                            <button type="submit"
                                    class="interaction-pill"
                                    {{ $isInteractionOpen ? '' : 'disabled' }}
                                    style="{{ $isLiked ? 'color:var(--parja-magenta);border-color:rgba(191,0,80,0.35);background:rgba(191,0,80,0.06);' : '' }} {{ $isInteractionOpen ? '' : 'opacity:0.6;cursor:not-allowed;' }}">
                                <i class="{{ $isLiked ? 'ri-heart-fill' : 'ri-heart-line' }}"></i>
                                {{ $likeCount }} Like
                            </button>
                        </form>
                    @else
                        <span class="interaction-pill"><i class="ri-heart-line"></i> {{ $likeCount }} Like</span>
                    @endif

                    <span class="interaction-pill"><i class="ri-chat-1-line"></i> {{ $commentCount }} Komentar</span>

                    @if(!$isInteractionOpen)
                        <span class="interaction-pill" style="color:#9a5a00;border-color:rgba(251,188,4,0.35);background:rgba(251,188,4,0.15);">
                            <i class="ri-lock-line"></i> Masa interaksi sudah ditutup
                        </span>
                    @endif
                </div>

                @if($alumni)
                    <form action="{{ route('alumni.kegiatan.comment', $info->id) }}" method="POST" class="mb-3">
                        @csrf
                        <div class="mb-2">
                            <textarea name="konten"
                                      rows="3"
                                      maxlength="1000"
                                      class="form-control rounded-3 @error('konten') is-invalid @enderror"
                                      placeholder="Tulis komentar Anda..."
                                      {{ $isInteractionOpen ? '' : 'disabled' }}>{{ old('konten') }}</textarea>
                            @error('konten')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit"
                                class="btn btn-sm fw-semibold text-white rounded-pill"
                                style="background:var(--parja-magenta);"
                                {{ $isInteractionOpen ? '' : 'disabled' }}>
                            <i class="ri-send-plane-line"></i> Kirim Komentar
                        </button>
                    </form>
                @endif

                @if($info->comments->isEmpty())
                    <p class="text-muted small mb-0">Belum ada komentar.</p>
                @else
                    @foreach($info->comments as $comment)
                        @php
                            $commenterName = $comment->alumni?->nama ?? 'Alumni';
                        @endphp
                        <div class="comment-item">
                            <div class="d-flex align-items-center justify-content-between gap-2">
                                <div class="comment-author">{{ $commenterName }}</div>
                                <div class="comment-time">{{ $comment->created_at?->diffForHumans() }}</div>
                            </div>
                            <div class="comment-text">{{ $comment->konten }}</div>
                        </div>
                    @endforeach
                @endif
            </div>
            
            <div class="mt-5 pt-4 border-top">
                <p class="text-muted small text-center mb-0">Informasi ini ditujukan khusus untuk Alumni Parja. Jika ada pertanyaan, hubungi tim Parja Center.</p>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    (function () {
        document.addEventListener('submit', function (event) {
            const form = event.target;
            if (!form || !form.classList || !form.classList.contains('js-reminder-toggle-form')) {
                return;
            }

            const isReminded = String(form.dataset.reminded || '0') === '1';
            if (!isReminded) {
                return;
            }

            event.preventDefault();

            const agendaTitle = form.dataset.agendaTitle || 'agenda ini';

            const proceed = function () {
                form.dataset.reminded = '0';
                form.submit();
            };

            if (typeof Swal === 'undefined') {
                if (confirm('Batalkan pengingat untuk "' + agendaTitle + '"?')) {
                    proceed();
                }
                return;
            }

            Swal.fire({
                icon: 'warning',
                title: 'Batalkan pengingat?',
                text: 'Agenda "' + agendaTitle + '" akan dihapus dari reminder kamu.',
                showCancelButton: true,
                confirmButtonText: 'Ya, batalkan',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#bf0050',
            }).then(function (result) {
                if (result.isConfirmed) {
                    proceed();
                }
            });
        });
    })();
</script>
@endpush
@endsection
