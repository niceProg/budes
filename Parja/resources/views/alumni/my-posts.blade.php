@extends('parja::alumni.layouts.app')

@section('title', 'Post Saya')
@section('page-title', 'Post Saya')

@push('styles')
<link rel="stylesheet" href="{{ asset('SweetAlert/sweetalert2.min.css') }}">
<style>
    .swal2-popup.swal-parja {
        border-radius: 20px;
        font-family: "Plus Jakarta Sans", "Segoe UI", sans-serif;
        padding: 2rem 1.5rem;
    }
    .swal2-popup.swal-parja .swal2-title {
        color: #41174b;
        font-size: 1.15rem;
        font-weight: 700;
    }
    .swal2-popup.swal-parja .swal2-html-container {
        color: #695a72;
        font-size: 0.92rem;
    }
    .swal2-popup.swal-parja .swal2-confirm {
        border-radius: 50px !important;
        font-weight: 600;
        padding: 8px 22px;
    }
    .swal2-popup.swal-parja .swal2-cancel {
        border-radius: 50px !important;
        font-weight: 600;
        padding: 8px 22px;
    }
    .swal2-popup.swal-parja .swal2-icon {
        margin-top: 0.5rem;
    }

    .my-post-card {
        border-radius: 18px;
        border: 1px solid var(--parja-border);
        background: #fff;
        padding: 20px;
        box-shadow: 0 6px 14px rgba(65, 23, 75, 0.06);
        margin-bottom: 16px;
    }

    .my-post-card-header {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        margin-bottom: 12px;
        flex-wrap: wrap;
    }

    .tipe-badge {
        font-size: 0.72rem;
        padding: 3px 10px;
        border-radius: 50px;
        font-weight: 600;
    }

    .tipe-badge.freepost {
        background: rgba(191, 0, 80, 0.1);
        color: var(--parja-magenta);
    }

    .tipe-badge.artikel {
        background: rgba(65, 23, 75, 0.1);
        color: var(--parja-purple);
    }

    .status-badge {
        font-size: 0.72rem;
        padding: 3px 12px;
        border-radius: 50px;
        font-weight: 600;
    }

    .status-badge.pending {
        background: #fff3cd;
        color: #856404;
    }

    .status-badge.approved {
        background: #d1e7dd;
        color: #0f5132;
    }

    .status-badge.rejected {
        background: #f8d7da;
        color: #842029;
    }

    .status-badge.pending-public {
        background: rgba(13,110,253,0.1);
        color: #084298;
    }

    .my-post-title {
        font-weight: 700;
        color: var(--parja-text);
        font-size: 1rem;
        margin-bottom: 6px;
    }

    .my-post-content {
        color: var(--parja-text);
        font-size: 0.92rem;
        line-height: 1.6;
        white-space: pre-wrap;
        word-break: break-word;
    }

    .catatan-admin-box {
        background: #fff3cd;
        border-left: 3px solid #ffc107;
        border-radius: 8px;
        padding: 10px 14px;
        margin-top: 12px;
        font-size: 0.88rem;
    }

    .catatan-admin-box strong {
        display: block;
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 4px;
        color: #856404;
    }

    .foto-thumb-row {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
        margin-top: 12px;
    }

    .foto-thumb-row img {
        width: 72px;
        height: 72px;
        object-fit: cover;
        border-radius: 8px;
        border: 1px solid var(--parja-border);
    }

    .empty-state {
        text-align: center;
        padding: 56px 20px;
        color: var(--parja-muted);
    }

    .empty-state > i {
        font-size: 3rem;
        color: var(--parja-border);
        display: block;
        margin-bottom: 12px;
    }
</style>
@endpush

@section('content')

@if (session('success'))
    <div class="alert alert-success rounded-3 mb-3">{{ session('success') }}</div>
@endif
@if (session('error'))
    <div class="alert alert-danger rounded-3 mb-3">{{ session('error') }}</div>
@endif

<div class="row mb-3">
    <div class="col">
        <div class="my-post-card" style="padding: 14px 20px;">
            <div class="d-flex align-items-center justify-content-between gap-2 flex-wrap">
                <div>
                    <p class="mb-0 fw-bold" style="color: var(--parja-purple);">
                        <i class="ri-file-list-3-line"></i> Semua Post Saya
                    </p>
                    <p class="mb-0 text-muted small">Total: {{ $myPosts->total() }} post</p>
                </div>
                <a href="{{ route('alumni.feed.index') }}" class="btn btn-sm fw-semibold"
                    style="background: var(--parja-magenta); color: #fff; border-radius: 50px; padding: 6px 18px;">
                    <i class="ri-newspaper-line"></i> Lihat Feed
                </a>
            </div>
        </div>
    </div>
</div>

@if ($myPosts->isEmpty())
    <div class="my-post-card">
        <div class="empty-state">
            <i class="ri-file-add-line"></i>
            <p class="fw-semibold mb-1">Kamu belum punya artikel.</p>
            <p class="small text-muted">Tulis artikel pertamamu di Feed Alumni!</p>
            <a href="{{ route('alumni.feed.index') }}" class="btn btn-sm mt-2 fw-semibold text-white"
                style="background: var(--parja-magenta); border-radius: 50px;">
                <i class="ri-article-line"></i> Tulis Artikel
            </a>
        </div>
    </div>
@else
    @foreach ($myPosts as $post)
        <div class="my-post-card">
            <div class="my-post-card-header">
                <div class="flex-grow-1">
                    <div class="d-flex align-items-center gap-2 flex-wrap mb-1">
                        <span class="tipe-badge artikel">Artikel</span>
                        @php
                            $statusClass = match ($post->status) {
                                0 => 'pending',
                                1 => 'approved',
                                2 => 'rejected',
                                default => 'pending',
                            };
                        @endphp
                        <span class="status-badge {{ $statusClass }}">
                            @if ($post->status === 0)
                                <i class="ri-time-line"></i> Menunggu Review
                            @elseif ($post->status === 1)
                                <i class="ri-checkbox-circle-line"></i> Terbit
                            @else
                                <i class="ri-close-circle-line"></i> Ditolak
                            @endif
                        </span>
                        {{-- Badge Privasi --}}
                        @if ($post->status === 1)
                            @if ($post->privasi === 'public')
                                <span style="font-size:0.72rem; padding:3px 10px; border-radius:50px; font-weight:600;
                                    background: rgba(13,110,253,0.1); color: #084298;">
                                    <i class="ri-earth-line"></i> Public
                                </span>
                            @elseif ($post->privasi_request === 'public')
                                <span class="status-badge pending-public">
                                    <i class="ri-send-plane-line"></i> Menunggu Acc Public
                                </span>
                            @else
                                <span style="font-size:0.72rem; padding:3px 10px; border-radius:50px; font-weight:600;
                                    background: rgba(65,23,75,0.1); color: var(--parja-purple);">
                                    <i class="ri-lock-line"></i> Parja Alumni
                                </span>
                            @endif
                        @endif
                    </div>
                    <p class="text-muted mb-0" style="font-size: 0.8rem;">
                        Dikirim {{ $post->created_at->diffForHumans() }}
                        @if ($post->approved_at)
                            &bull; Ditinjau {{ \Carbon\Carbon::parse($post->approved_at)->diffForHumans() }}
                        @endif
                    </p>
                </div>

                {{-- Tombol Privasi: hanya untuk post disetujui --}}
                @if ($post->status === 1)
                    @if ($post->privasi === 'public')
                        {{-- Sudah public, bisa balik ke alumni --}}
                        <form action="{{ route('alumni.feed.toggle-privacy', $post->id) }}" method="POST" style="flex-shrink:0;">
                            @csrf
                            <button type="button" class="btn btn-sm btn-outline-secondary btn-toggle-privacy rounded-pill"
                                data-target="Parja Alumni"
                                data-current="public"
                                data-mode="to-alumni">
                                <i class="ri-lock-line"></i> Balik ke Alumni
                            </button>
                        </form>
                    @elseif ($post->privasi_request === 'public')
                        {{-- Ada permintaan pending ke public --}}
                        <form action="{{ route('alumni.feed.toggle-privacy', $post->id) }}" method="POST" style="flex-shrink:0;">
                            @csrf
                            <button type="button" class="btn btn-sm btn-outline-warning btn-toggle-privacy rounded-pill"
                                data-target="batalkan"
                                data-current="pending"
                                data-mode="cancel-request">
                                <i class="ri-time-line"></i> Menunggu Acc Admin
                            </button>
                        </form>
                    @else
                        {{-- Masih alumni, bisa minta ke public --}}
                        <form action="{{ route('alumni.feed.toggle-privacy', $post->id) }}" method="POST" style="flex-shrink:0;">
                            @csrf
                            <button type="button" class="btn btn-sm btn-outline-primary btn-toggle-privacy rounded-pill"
                                data-target="Public"
                                data-current="alumni"
                                data-mode="to-public">
                                <i class="ri-earth-line"></i> Minta Publish Public
                            </button>
                        </form>
                    @endif
                @endif

                {{-- Tombol Hapus: selalu tersedia untuk semua post milik alumni --}}
                <form action="{{ route('alumni.feed.destroy', $post->id) }}" method="POST"
                      class="form-hapus" style="flex-shrink:0;">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-sm btn-outline-danger btn-hapus-post rounded-pill">
                        <i class="ri-delete-bin-line"></i> Hapus
                    </button>
                </form>
            </div>

            @if ($post->tipe === 'artikel' && $post->judul)
                <div class="my-post-title">{{ $post->judul }}</div>
            @endif

            <div class="my-post-content">{{ $post->konten }}</div>

            @if (!empty($post->foto_paths))
                <div class="foto-thumb-row">
                    @foreach ($post->foto_paths as $foto)
                        <img src="{{ asset('storage/' . $foto) }}" alt="Foto">
                    @endforeach
                    <small class="align-self-center text-muted ms-1">{{ count($post->foto_paths) }} foto</small>
                </div>
            @endif

            @if ($post->status === 2 && $post->catatan_admin)
                <div class="catatan-admin-box">
                    <strong><i class="ri-feedback-line"></i> Catatan Admin:</strong>
                    {{ $post->catatan_admin }}
                </div>
            @endif
        </div>
    @endforeach

    <div class="d-flex justify-content-center mt-2">
        {{ $myPosts->links() }}
    </div>
@endif

@endsection

@push('scripts')
<script src="{{ asset('SweetAlert/sweetalert2.all.min.js') }}"></script>
<script>
    // Ubah Privasi
    document.querySelectorAll('.btn-toggle-privacy').forEach(btn => {
        btn.addEventListener('click', function () {
            const form   = this.closest('form');
            const mode   = this.dataset.mode;
            const target = this.dataset.target;

            let title, html, confirmText, icon, confirmColor;

            if (mode === 'to-public') {
                title       = 'Minta Publikasi ke Public?';
                html        = `Artikel akan dikirim ke admin untuk ditinjau.<br>Setelah disetujui, artikel dapat dilihat oleh <b>semua orang</b>.`;
                confirmText = 'Kirim Permintaan';
                icon        = 'question';
                confirmColor = '#0d6efd';
            } else if (mode === 'cancel-request') {
                title       = 'Batalkan Permintaan?';
                html        = `Permintaan publikasi ke Public akan dibatalkan. Artikel kembali hanya terlihat oleh sesama alumni.`;
                confirmText = 'Ya, Batalkan';
                icon        = 'warning';
                confirmColor = '#fd7e14';
            } else {
                // to-alumni
                title       = 'Kembalikan ke Parja Alumni?';
                html        = `Artikel tidak lagi terlihat oleh publik umum.`;
                confirmText = `Ubah ke <b>${target}</b>`;
                icon        = 'question';
                confirmColor = '#bf0050';
            }

            Swal.fire({
                customClass: { popup: 'swal-parja' },
                title,
                html,
                icon,
                iconColor: confirmColor,
                showCancelButton: true,
                confirmButtonColor: confirmColor,
                cancelButtonColor: '#6c757d',
                confirmButtonText: confirmText,
                cancelButtonText: 'Batal',
                reverseButtons: true,
            }).then((result) => {
                if (result.isConfirmed) { form.submit(); }
            });
        });
    });

    // Hapus Post
    document.querySelectorAll('.btn-hapus-post').forEach(btn => {
        btn.addEventListener('click', function () {
            const form = this.closest('form');
            Swal.fire({
                customClass: { popup: 'swal-parja' },
                title: 'Hapus Post Ini?',
                text: 'Post yang sudah dihapus tidak dapat dikembalikan.',
                icon: 'warning',
                iconColor: '#dc3545',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal',
                reverseButtons: true,
            }).then((result) => {
                if (result.isConfirmed) { form.submit(); }
            });
        });
    });
</script>
@endpush
