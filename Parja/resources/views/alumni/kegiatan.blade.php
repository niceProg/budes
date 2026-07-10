@extends('parja::alumni.layouts.app')

@section('title', 'Kegiatan & Info Parja')
@section('page-title', 'Kegiatan & Info Parja')

@push('styles')
<style>
    .info-card {
        border-radius: 18px;
        border: 1px solid var(--parja-border);
        background: #fff;
        overflow: hidden;
        box-shadow: 0 6px 14px rgba(65, 23, 75, 0.06);
        transition: transform 0.2s, box-shadow 0.2s;
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .info-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 24px rgba(65, 23, 75, 0.12);
    }

    .info-img-wrapper {
        position: relative;
        height: 200px;
        background: var(--parja-soft);
        overflow: hidden;
    }

    .info-img-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .info-badge {
        position: absolute;
        top: 14px;
        right: 14px;
        z-index: 10;
        font-size: 0.72rem;
        padding: 5px 12px;
        border-radius: 50px;
        font-weight: 700;
        box-shadow: 0 4px 10px rgba(0,0,0,0.15);
    }

    .info-body {
        padding: 20px;
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .info-title {
        font-size: 1.1rem;
        font-weight: 800;
        color: var(--parja-text);
        margin-bottom: 10px;
        line-height: 1.4;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .info-meta {
        font-size: 0.78rem;
        color: var(--parja-muted);
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .info-desc {
        font-size: 0.88rem;
        color: var(--parja-muted);
        line-height: 1.6;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
        margin-bottom: 16px;
    }

    .info-footer {
        margin-top: auto;
    }

    .info-action-bar {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-top: 10px;
        margin-bottom: 10px;
    }

    .info-action-btn {
        border: 1px solid var(--parja-border);
        border-radius: 999px;
        background: #fff;
        padding: 4px 12px;
        font-size: 0.78rem;
        color: var(--parja-muted);
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-weight: 600;
        text-decoration: none;
    }

    .info-action-btn.is-liked {
        border-color: rgba(191, 0, 80, 0.35);
        color: var(--parja-magenta);
        background: rgba(191, 0, 80, 0.06);
    }

    .info-action-closed {
        font-size: 0.73rem;
        color: #9a5a00;
        background: rgba(251, 188, 4, 0.15);
        border: 1px solid rgba(251, 188, 4, 0.35);
        border-radius: 999px;
        padding: 3px 10px;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    .info-reminder-btn {
        border: 1px solid rgba(65,23,75,0.2);
        border-radius: 999px;
        background: #fff;
        color: var(--parja-purple);
        padding: 4px 11px;
        font-size: 0.75rem;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    .info-reminder-btn.is-on {
        border-color: rgba(191,0,80,0.35);
        background: rgba(191,0,80,0.08);
        color: var(--parja-magenta);
    }

    .info-deadline-badge {
        font-size: 0.66rem;
        border-radius: 999px;
        padding: 2px 8px;
        font-weight: 800;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        text-transform: uppercase;
        letter-spacing: 0.02em;
        margin-bottom: 6px;
    }

    .info-deadline-badge.critical {
        background: rgba(220, 53, 69, 0.22);
        border: 1px solid rgba(220, 53, 69, 0.6);
        color: #8f1422;
    }

    .info-deadline-badge.warning {
        background: rgba(255, 140, 0, 0.15);
        border: 1px solid rgba(255, 140, 0, 0.5);
        color: #8a4a00;
    }

    .info-deadline-badge.expired {
        background: rgba(108, 117, 125, 0.16);
        border: 1px solid rgba(108, 117, 125, 0.45);
        color: #4f5962;
    }

    .info-deadline-badge.urgent {
        background: rgba(220, 53, 69, 0.16);
        border: 1px solid rgba(220, 53, 69, 0.45);
        color: #a61d2d;
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

<div class="row g-4 mb-4">
    <div class="col-12">
        <div class="alert alert-info border-0 rounded-4 d-flex align-items-center gap-3" style="background: linear-gradient(135deg,rgba(191,0,80,0.05),rgba(65,23,75,0.05)); border: 1px solid var(--parja-border) !important;">
            <div style="width:48px;height:48px;border-radius:50%;background:rgba(191,0,80,0.12);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <i class="ri-notification-3-line text-maroon" style="font-size: 1.5rem;"></i>
            </div>
            <div>
                <h5 class="mb-1 fw-bold text-maroon">Pusat Informasi Alumni</h5>
                <p class="mb-0 text-muted" style="font-size: 0.88rem;">Informasi resmi, agenda kegiatan, dan pengumuman penting bagi komunitas Alumni Parja ada di sini.</p>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    @forelse($infos as $info)
        <div class="col-md-6 col-lg-4">
            <div class="info-card">
                <div class="info-img-wrapper">
                    <span class="info-badge badge {{ $info->tipe_badge_class }}">{{ $info->tipe_label }}</span>
                    @if($info->is_pinned)
                        <span class="info-badge badge bg-danger" style="right: auto; left: 14px;"><i class="ri-pushpin-2-fill"></i> Pinned</span>
                    @endif
                    
                    @if($info->thumbnail)
                        <img src="{{ asset('uploads/alumni-info/' . $info->thumbnail) }}" alt="{{ $info->judul }}">
                    @else
                        <div class="d-flex align-items-center justify-content-center w-100 h-100" style="background: linear-gradient(135deg, #f8f9fc, #e3e8f8);">
                            <i class="ri-image-placeholder-line" style="font-size: 3rem; color: #a1b0cb;"></i>
                        </div>
                    @endif
                </div>
                <div class="info-body">
                    @php
                        $isOpen = $info->isInteractionOpen();
                        $isLiked = $alumni && isset($likedInfoIds) ? $likedInfoIds->contains($info->id) : false;
                        $isReminded = $alumni && isset($remindedInfoIds) ? $remindedInfoIds->contains($info->id) : false;
                        $daysToDeadline = $info->tanggal_berakhir ? now()->startOfDay()->diffInDays($info->tanggal_berakhir->copy()->startOfDay(), false) : null;
                    @endphp
                    <div class="info-meta">
                        <span><i class="ri-calendar-event-line"></i> {{ $info->tanggal_publikasi->format('d M Y') }}</span>
                    </div>
                    <a href="{{ route('alumni.kegiatan.show', $info->id) }}" class="text-decoration-none">
                        <h4 class="info-title">{{ $info->judul }}</h4>
                    </a>
                    <div class="info-desc">
                        {{ Str::limit(strip_tags($info->konten), 120) }}
                    </div>

                    @if(!is_null($daysToDeadline))
                        @if($daysToDeadline < 0)
                            <div class="info-deadline-badge expired"><i class="ri-history-line"></i> Deadline lewat</div>
                        @elseif($daysToDeadline <= 1)
                            <div class="info-deadline-badge critical"><i class="ri-alarm-warning-line"></i> {{ $daysToDeadline === 0 ? 'Hari ini' : 'H-1' }}</div>
                        @elseif($daysToDeadline <= 3)
                            <div class="info-deadline-badge warning"><i class="ri-time-line"></i> H-{{ $daysToDeadline }}</div>
                        @endif
                    @endif

                    <div class="info-action-bar">
                        @if($alumni)
                                <form action="{{ route('alumni.kegiatan.reminder', $info->id) }}"
                                    method="POST"
                                    class="m-0 js-reminder-toggle-form"
                                    data-reminded="{{ $isReminded ? '1' : '0' }}"
                                    data-agenda-title="{{ e($info->judul) }}">
                                @csrf
                                <button type="submit"
                                        class="info-reminder-btn {{ $isReminded ? 'is-on' : '' }}"
                                        {{ $isOpen ? '' : 'disabled' }}
                                        style="{{ $isOpen ? '' : 'opacity:0.6;cursor:not-allowed;' }}">
                                    <i class="{{ $isReminded ? 'ri-notification-3-fill' : 'ri-notification-3-line' }}"></i>
                                    {{ $isReminded ? 'Diingatkan' : 'Ingatkan Saya' }}
                                </button>
                            </form>
                        @endif

                        @if($alumni)
                            <form action="{{ route('alumni.kegiatan.like', $info->id) }}" method="POST" class="m-0">
                                @csrf
                                <button type="submit"
                                        class="info-action-btn {{ $isLiked ? 'is-liked' : '' }}"
                                        {{ $isOpen ? '' : 'disabled' }}
                                        style="{{ $isOpen ? '' : 'opacity:0.6;cursor:not-allowed;' }}">
                                    <i class="{{ $isLiked ? 'ri-heart-fill' : 'ri-heart-line' }}"></i>
                                    {{ $info->likes_count ?? 0 }}
                                </button>
                            </form>
                        @else
                            <span class="info-action-btn"><i class="ri-heart-line"></i> {{ $info->likes_count ?? 0 }}</span>
                        @endif

                        <a href="{{ route('alumni.kegiatan.show', $info->id) }}#komentar"
                           class="info-action-btn">
                            <i class="ri-chat-1-line"></i>
                            {{ $info->comments_count ?? 0 }}
                        </a>

                        @if(!$isOpen)
                            <span class="info-action-closed"><i class="ri-lock-line"></i> Interaksi ditutup</span>
                        @endif
                    </div>

                    <div class="info-footer mt-auto pt-3 border-top" style="border-color: var(--parja-border) !important;">
                        <a href="{{ route('alumni.kegiatan.show', $info->id) }}" class="btn btn-sm btn-outline-danger rounded-pill fw-semibold w-100">Baca Selengkapnya <i class="ri-arrow-right-s-line"></i></a>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12 text-center py-5">
            <i class="ri-folder-info-line" style="font-size: 4rem; color: var(--parja-border);"></i>
            <h5 class="mt-3 text-muted fw-semibold">Belum ada informasi.</h5>
            <p class="text-muted small">Cek kembali nanti untuk informasi terbaru.</p>
        </div>
    @endforelse
</div>

<div class="d-flex justify-content-center mt-5 mb-4">
    {{ $infos->links() }}
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
