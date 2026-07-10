@extends('parja::alumni.layouts.app')

@section('title', 'Feed Alumni')
@section('page-title', 'Feed Alumni')

@push('styles')
<style>
    .feed-compose-card {
        border-radius: 18px;
        border: 1px solid var(--parja-border);
        background: #fff;
        padding: 20px;
        box-shadow: 0 8px 18px rgba(65, 23, 75, 0.07);
        margin-bottom: 20px;
    }

    .feed-compose-title {
        font-weight: 700;
        color: var(--parja-purple);
        font-size: 1rem;
        margin-bottom: 14px;
        display: flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        user-select: none;
    }

    .feed-compose-title i.toggle-icon {
        margin-left: auto;
        transition: transform 0.25s;
        color: var(--parja-muted);
    }

    .feed-compose-title.collapsed i.toggle-icon {
        transform: rotate(-90deg);
    }

    .tipe-toggle {
        display: flex;
        gap: 8px;
        margin-bottom: 14px;
    }

    .tipe-btn {
        padding: 6px 18px;
        border-radius: 50px;
        border: 1.5px solid var(--parja-border);
        background: #fff;
        color: var(--parja-muted);
        font-weight: 600;
        font-size: 0.84rem;
        cursor: pointer;
        transition: all 0.18s;
    }

    .tipe-btn.active {
        background: var(--parja-magenta);
        border-color: var(--parja-magenta);
        color: #fff;
    }

    .feed-post-card {
        border-radius: 18px;
        border: 1px solid var(--parja-border);
        background: #fff;
        padding: 20px;
        box-shadow: 0 6px 14px rgba(65, 23, 75, 0.06);
        margin-bottom: 16px;
    }

    .feed-post-card.focused-post {
        border-color: rgba(191, 0, 80, 0.45);
        box-shadow: 0 10px 24px rgba(191, 0, 80, 0.16);
        animation: focusPostPulse 1.2s ease;
    }

    @keyframes focusPostPulse {
        0% {
            transform: translateY(4px) scale(0.99);
            opacity: 0.75;
        }
        100% {
            transform: translateY(0) scale(1);
            opacity: 1;
        }
    }

    .feed-post-header {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 14px;
        position: relative;
    }

    .feed-post-avatar {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid var(--parja-border);
        background: var(--parja-soft);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-weight: 700;
        font-size: 1rem;
        color: var(--parja-purple);
    }

    .feed-post-meta .name {
        font-weight: 700;
        color: var(--parja-purple);
        font-size: 0.95rem;
        margin: 0;
    }

    .feed-post-meta .sub {
        font-size: 0.8rem;
        color: var(--parja-muted);
        margin: 0;
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

    .kategori-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        font-size: 0.72rem;
        padding: 3px 10px;
        border-radius: 50px;
        font-weight: 600;
        background: rgba(191, 0, 80, 0.08);
        color: var(--parja-magenta);
        border: 1px solid rgba(191, 0, 80, 0.18);
        margin-top: 6px;
    }

    .feed-post-title {
        font-weight: 700;
        color: var(--parja-text);
        font-size: 1.05rem;
        margin-bottom: 8px;
    }

    .feed-post-content {
        color: var(--parja-text);
        font-size: 0.93rem;
        line-height: 1.6;
        white-space: pre-wrap;
        word-break: break-word;
    }

    .feed-photo-grid {
        display: grid;
        gap: 4px;
        margin-top: 14px;
        border-radius: 12px;
        overflow: hidden;
        border: 1px solid var(--parja-border);
    }
    .feed-photo-grid.count-1 { grid-template-columns: 1fr; border: none; border-radius: 0; }
    .feed-photo-grid.count-2 { grid-template-columns: 1fr 1fr; }
    .feed-photo-grid.count-3 { grid-template-columns: 1fr 1fr; }
    .feed-photo-grid.count-4 { grid-template-columns: 1fr 1fr; }
    .feed-photo-grid.count-5 { grid-template-columns: repeat(6, 1fr); }

    .feed-photo-grid img {
        width: 100%;
        display: block;
        object-fit: cover;
        cursor: pointer;
        transition: opacity 0.15s;
    }
    .feed-photo-grid img:hover { opacity: 0.9; }

    .feed-photo-grid.count-1 img {
        height: auto;
        max-height: 550px;
        border-radius: 12px;
        object-fit: contain;
        background: #f8f9fa;
        border: 1px solid var(--parja-border);
    }
    .feed-photo-grid.count-2 img { height: 300px; }
    .feed-photo-grid.count-3 img:first-child { grid-column: 1 / span 2; height: 350px; }
    .feed-photo-grid.count-3 img:not(:first-child) { height: 180px; }
    .feed-photo-grid.count-4 img { height: 250px; }
    .feed-photo-grid.count-5 img:nth-child(1),
    .feed-photo-grid.count-5 img:nth-child(2) { grid-column: span 3; height: 250px; }
    .feed-photo-grid.count-5 img:nth-child(3),
    .feed-photo-grid.count-5 img:nth-child(4),
    .feed-photo-grid.count-5 img:nth-child(5) { grid-column: span 2; height: 180px; }

    /* Lightbox */
    .pf-lightbox {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.88);
        z-index: 2000;
        align-items: center;
        justify-content: center;
    }

    .pf-lightbox.show { display: flex; }

    .pf-lightbox img {
        max-width: 90vw;
        max-height: 88vh;
        border-radius: 10px;
        box-shadow: 0 20px 60px rgba(0,0,0,0.4);
    }

    .pf-lightbox-close {
        position: absolute;
        top: 18px;
        right: 22px;
        color: #fff;
        font-size: 2rem;
        cursor: pointer;
        line-height: 1;
    }

    .foto-upload-preview {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
        margin-top: 10px;
    }

    .foto-upload-preview img {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border-radius: 8px;
        border: 1px solid var(--parja-border);
    }

    .empty-feed {
        text-align: center;
        padding: 48px 20px;
        color: var(--parja-muted);
    }

    .empty-feed i {
        font-size: 3rem;
        color: var(--parja-border);
        display: block;
        margin-bottom: 12px;
    }

    /* Like & Komentar */
    .post-action-bar {
        display: flex;
        align-items: center;
        gap: 16px;
        margin-top: 14px;
        padding-top: 12px;
        border-top: 1px solid var(--parja-border);
    }

    .post-action-btn {
        background: none;
        border: none;
        padding: 0;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-size: 0.84rem;
        font-weight: 600;
        color: var(--parja-muted);
        transition: color 0.15s;
    }

    .post-action-btn:hover { color: var(--parja-magenta); }
    .post-action-btn.liked  { color: var(--parja-magenta); }
    .post-action-btn i { font-size: 1rem; }

    .comment-section {
        margin-top: 12px;
        border-top: 1px dashed var(--parja-border);
        padding-top: 10px;
    }

    .comment-item {
        display: flex;
        gap: 10px;
        margin-bottom: 10px;
        align-items: flex-start;
    }

    .comment-avatar {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        background: var(--parja-soft);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.75rem;
        font-weight: 700;
        color: var(--parja-purple);
        flex-shrink: 0;
        object-fit: cover;
    }

    .comment-bubble {
        background: #f8f4fc;
        border-radius: 12px;
        padding: 8px 12px;
        flex: 1;
        min-width: 0;
    }

    .comment-name {
        font-size: 0.78rem;
        font-weight: 700;
        color: var(--parja-purple);
        margin: 0 0 2px;
    }

    .comment-text {
        font-size: 0.85rem;
        color: var(--parja-text);
        margin: 0;
        word-break: break-word;
    }

    .comment-meta {
        font-size: 0.72rem;
        color: var(--parja-muted);
        margin-top: 3px;
    }

    .comment-form {
        display: flex;
        gap: 8px;
        align-items: center;
        margin-top: 10px;
    }

    .comment-form input {
        flex: 1;
        border: 1px solid var(--parja-border);
        border-radius: 50px;
        padding: 6px 14px;
        font-size: 0.84rem;
        outline: none;
        transition: border-color 0.15s;
    }

    .comment-form input:focus {
        border-color: var(--parja-magenta);
    }

    .comment-form button {
        background: var(--parja-magenta);
        border: none;
        color: #fff;
        border-radius: 50px;
        padding: 6px 14px;
        font-size: 0.82rem;
        font-weight: 600;
        cursor: pointer;
        flex-shrink: 0;
    }

    /* ── Post Settings Dropdown ── */
    .post-settings-btn {
        background: none;
        border: none;
        padding: 4px 8px;
        border-radius: 8px;
        color: var(--parja-muted);
        cursor: pointer;
        font-size: 1.15rem;
        line-height: 1;
        transition: background 0.15s, color 0.15s;
        flex-shrink: 0;
    }
    .post-settings-btn:hover { background: rgba(65,23,75,0.07); color: var(--parja-purple); }

    .post-settings-dropdown {
        position: absolute;
        top: calc(100% + 4px);
        right: 0;
        min-width: 190px;
        background: #fff;
        border: 1px solid var(--parja-border);
        border-radius: 14px;
        box-shadow: 0 8px 24px rgba(65,23,75,0.14);
        z-index: 200;
        overflow: hidden;
        display: none;
    }
    .post-settings-dropdown.open { display: block; }

    .post-settings-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 16px;
        font-size: 0.85rem;
        font-weight: 500;
        color: var(--parja-text);
        cursor: pointer;
        border: none;
        background: none;
        width: 100%;
        text-align: left;
        transition: background 0.12s;
    }
    .post-settings-item:hover { background: rgba(65,23,75,0.05); }
    .post-settings-item.danger { color: #c0392b; }
    .post-settings-item.danger:hover { background: rgba(192,57,43,0.06); }
    .post-settings-item i { font-size: 1rem; width: 18px; text-align: center; }
    .post-settings-divider { height: 1px; background: var(--parja-border); margin: 4px 0; }

    /* ── Edit Post Modal ── */
    .post-edit-modal-backdrop {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(30,10,40,0.38);
        z-index: 1100;
        align-items: center;
        justify-content: center;
    }
    .post-edit-modal-backdrop.open { display: flex; }
    .post-edit-modal {
        background: #fff;
        border-radius: 20px;
        box-shadow: 0 16px 48px rgba(65,23,75,0.18);
        width: 100%;
        max-width: 560px;
        max-height: 90vh;
        overflow-y: auto;
        padding: 28px 28px 20px;
        position: relative;
    }
    .post-edit-modal-title {
        font-weight: 700;
        font-size: 1rem;
        color: var(--parja-purple);
        margin-bottom: 18px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .post-edit-modal-close {
        position: absolute;
        top: 16px;
        right: 18px;
        background: none;
        border: none;
        font-size: 1.4rem;
        color: var(--parja-muted);
        cursor: pointer;
        line-height: 1;
        padding: 4px;
        border-radius: 50%;
        transition: background 0.15s;
    }
    .post-edit-modal-close:hover { background: rgba(65,23,75,0.08); }
    .post-edit-label {
        font-size: 0.82rem;
        font-weight: 600;
        color: var(--parja-purple);
        margin-bottom: 5px;
        display: block;
    }
    .post-edit-input, .post-edit-textarea, .post-edit-select {
        width: 100%;
        border: 1.5px solid var(--parja-border);
        border-radius: 10px;
        padding: 9px 13px;
        font-size: 0.88rem;
        color: var(--parja-text);
        background: #fafafd;
        transition: border-color 0.15s;
        outline: none;
        resize: none;
    }
    .post-edit-input:focus, .post-edit-textarea:focus, .post-edit-select:focus {
        border-color: var(--parja-magenta);
        background: #fff;
    }
    .post-edit-textarea { min-height: 110px; }
    .post-edit-actions {
        display: flex;
        gap: 10px;
        justify-content: flex-end;
        margin-top: 18px;
    }
    .post-edit-btn-cancel {
        background: none;
        border: 1.5px solid var(--parja-border);
        border-radius: 50px;
        padding: 7px 20px;
        font-size: 0.85rem;
        font-weight: 600;
        color: var(--parja-muted);
        cursor: pointer;
    }
    .post-edit-btn-save {
        background: var(--parja-magenta);
        border: none;
        border-radius: 50px;
        padding: 7px 22px;
        font-size: 0.85rem;
        font-weight: 600;
        color: #fff;
        cursor: pointer;
        transition: opacity 0.15s;
    }
    .post-edit-btn-save:hover { opacity: 0.88; }
</style>
@endpush

@section('content')

{{-- Banner untuk tamu yang belum login --}}
@guest('keycloak-external')
<div class="alert d-flex align-items-center gap-3 mb-4" style="background:linear-gradient(135deg,#fff5f9,#fff9f0);border:1.5px solid rgba(191,0,80,0.2);border-radius:16px;padding:18px 22px;">
    <div style="width:44px;height:44px;border-radius:50%;background:rgba(191,0,80,0.12);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
        <i class="ri-eye-line" style="font-size:1.4rem;color:var(--parja-magenta);"></i>
    </div>
    <div class="flex-grow-1">
        <p class="mb-0 fw-semibold" style="color:var(--parja-purple);font-size:0.93rem;">Anda melihat sebagai <strong>Tamu</strong> &mdash; hanya artikel publik yang ditampilkan.</p>
        <p class="mb-0" style="font-size:0.82rem;color:var(--parja-muted);margin-top:2px;">Masuk atau daftar untuk melihat semua artikel dan berinteraksi dengan sesama alumni.</p>
    </div>
    <div class="d-flex gap-2 flex-shrink-0">
        <a href="{{ route('alumni.login') }}" class="btn btn-sm fw-semibold" style="background:var(--parja-magenta);color:#fff;border-radius:50px;white-space:nowrap;">Masuk Alumni</a>
        <a href="{{ route('alumni.register') }}" class="btn btn-sm fw-semibold" style="background:transparent;color:var(--parja-purple);border:1.5px solid var(--parja-purple);border-radius:50px;white-space:nowrap;">Daftar Alumni</a>
    </div>
</div>
@endguest

<div class="row g-3">
    {{-- Kolom Utama --}}
    <div class="col-lg-8">
        @include('parja::alumni.partials.slideshow')

        {{-- Form Buat Artikel (hanya untuk yang sudah login) --}}
        @include('parja::alumni.partials.feed-compose-form')
        @if ($posts->isEmpty())
            <div class="feed-post-card">
                <div class="empty-feed">
                    <i class="ri-newspaper-line"></i>
                    <p class="mb-0 fw-semibold">Belum ada artikel yang dipublikasikan.</p>
                    <p class="small text-muted mt-1">Jadilah yang pertama berbagi cerita!</p>
                </div>
            </div>
        @else
            @foreach ($posts as $post)
                @php
                    $namaAlumni = $post->alumni?->nama ?? 'Alumni';
                    $inisial    = strtoupper(substr($namaAlumni, 0, 1));
                    $dapil      = $post->alumni?->dapil ?? '-';
                    $profil     = $post->alumni?->profile;
                    $fotoProfil = $profil?->foto_profil
                        ? asset('storage/' . $profil->foto_profil)
                        : null;
                @endphp
                 <div id="post-{{ $post->id }}"
                     class="feed-post-card {{ isset($focusPostId) && (int) $focusPostId === (int) $post->id ? 'focused-post' : '' }}"
                     data-post-card-id="{{ $post->id }}">
                    <div class="feed-post-header">
                        @if ($fotoProfil)
                            <img src="{{ $fotoProfil }}" alt="{{ $namaAlumni }}" class="feed-post-avatar">
                        @else
                            <div class="feed-post-avatar">{{ $inisial }}</div>
                        @endif
                        <div class="feed-post-meta flex-grow-1">
                            <p class="name">{{ $namaAlumni }}</p>
                            <p class="sub">Dapil: {{ $dapil }} &bull; {{ $post->created_at->diffForHumans() }}</p>
                        </div>
                        <span class="tipe-badge artikel">Artikel</span>
                        <span class="tipe-badge" style="background: {{ $post->privasi === 'public' ? 'rgba(13,110,253,0.1)' : 'rgba(65,23,75,0.1)' }}; color: {{ $post->privasi === 'public' ? '#084298' : 'var(--parja-purple)' }};">
                            <i class="{{ $post->privasi === 'public' ? 'ri-earth-line' : 'ri-lock-line' }}"></i>
                            {{ $post->privasi === 'public' ? 'Public' : 'Parja Alumni' }}
                        </span>
                        @auth('keycloak-external')
                            @if ($alumni && $post->alumni_id === $alumni->id)
                                <button type="button" class="post-settings-btn"
                                    onclick="togglePostSettings({{ $post->id }}, event)"
                                    title="Pengaturan post">
                                    <i class="ri-more-2-fill"></i>
                                </button>
                                <div class="post-settings-dropdown" id="settings-menu-{{ $post->id }}">
                                    {{-- Edit Post --}}
                                    <button type="button" class="post-settings-item"
                                        onclick="openEditPostModal({{ $post->id }}, {{ json_encode($post->judul) }}, {{ json_encode($post->konten) }}, {{ json_encode($post->kategori_artikel) }}, {{ json_encode($post->foto_paths ?? []) }})">
                                        <i class="ri-edit-line"></i> Edit Post
                                    </button>
                                    {{-- Toggle Privasi --}}
                                    <form action="{{ route('alumni.feed.toggle-privacy', $post->id) }}" method="POST" class="m-0">
                                        @csrf
                                        @if ($post->privasi === 'public')
                                            <button type="submit" class="post-settings-item">
                                                <i class="ri-lock-line"></i> Jadikan Alumni Only
                                            </button>
                                        @elseif ($post->privasi_request === 'public')
                                            <button type="submit" class="post-settings-item" style="color:#b45309;">
                                                <i class="ri-time-line"></i> Batalkan Request Publik
                                            </button>
                                        @else
                                            <button type="submit" class="post-settings-item">
                                                <i class="ri-earth-line"></i> Ajukan Jadikan Publik
                                            </button>
                                        @endif
                                    </form>
                                    <div class="post-settings-divider"></div>
                                    {{-- Hapus Post --}}
                                    <form action="{{ route('alumni.feed.destroy', $post->id) }}" method="POST" class="m-0"
                                          id="delete-post-form-{{ $post->id }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="post-settings-item danger"
                                            onclick="confirmDeletePost({{ $post->id }})">
                                            <i class="ri-delete-bin-line"></i> Hapus Post
                                        </button>
                                    </form>
                                </div>
                            @endif
                        @endauth
                    </div>

                    @if ($post->tipe === 'artikel' && $post->judul)
                        <div class="feed-post-title">{{ $post->judul }}</div>
                        @if (!empty($post->kategori_artikel))
                            <div>
                                <span class="kategori-badge">
                                    <i class="ri-price-tag-3-line"></i>
                                    {{ $post->kategori_artikel }}
                                </span>
                            </div>
                        @endif
                    @endif

                    <div class="feed-post-content">{{ $post->konten }}</div>

                    @if (!empty($post->foto_paths))
                        @php $fotoCount = count($post->foto_paths); @endphp
                        <div class="feed-photo-grid count-{{ min($fotoCount, 5) }}">
                            @foreach ($post->foto_paths as $foto)
                                <img src="{{ asset('storage/' . $foto) }}"
                                     alt="Foto post"
                                     onclick="openLightbox('{{ asset('storage/' . $foto) }}')">
                            @endforeach
                        </div>
                    @endif

                    {{-- Like & Komentar Bar --}}
                    @php
                        $likeCount    = $post->likes->count();
                        $isLiked      = $likedPostIds->contains($post->id);
                        $commentCount = $post->comments->count();
                        $previewComments = $post->comments->take(3);
                    @endphp

                    <div class="post-action-bar">
                        @auth('keycloak-external')
                            <button type="button"
                                class="post-action-btn like-btn {{ $isLiked ? 'liked' : '' }}"
                                data-post-id="{{ $post->id }}"
                                data-liked="{{ $isLiked ? '1' : '0' }}"
                                data-url="{{ route('alumni.feed.like', $post->id) }}">
                                <i class="{{ $isLiked ? 'ri-heart-fill' : 'ri-heart-line' }}"></i>
                                <span class="like-count">{{ $likeCount }}</span>
                                <span>Suka</span>
                            </button>
                            <button type="button"
                                class="post-action-btn"
                                onclick="toggleCommentForm({{ $post->id }})">
                                <i class="ri-chat-1-line"></i>
                                {{ $commentCount }} Komentar
                            </button>
                        @else
                            <span class="post-action-btn" style="cursor:default;">
                                <i class="ri-heart-line"></i> {{ $likeCount }} Suka
                            </span>
                            <span class="post-action-btn" style="cursor:default;">
                                <i class="ri-chat-1-line"></i> {{ $commentCount }} Komentar
                            </span>
                        @endauth
                    </div>

                    {{-- Komentar Section --}}
                    <div class="comment-section" id="comments-{{ $post->id }}" style="display:none;">
                        @foreach ($previewComments as $comment)
                            @php
                                $cNama    = $comment->alumni?->nama ?? 'Alumni';
                                $cInisial = strtoupper(substr($cNama, 0, 1));
                                $cFoto    = $comment->alumni?->profile?->foto_profil
                                    ? asset('storage/' . $comment->alumni->profile->foto_profil)
                                    : null;
                            @endphp
                            <div class="comment-item">
                                @if ($cFoto)
                                    <img src="{{ $cFoto }}" class="comment-avatar" alt="{{ $cNama }}">
                                @else
                                    <div class="comment-avatar">{{ $cInisial }}</div>
                                @endif
                                <div class="comment-bubble" style="flex:1;">
                                    <p class="comment-name">{{ $cNama }}</p>
                                    <p class="comment-text">{{ $comment->konten }}</p>
                                    <div class="comment-meta d-flex align-items-center gap-2">
                                        <span>{{ $comment->created_at->diffForHumans() }}</span>
                                        @auth('keycloak-external')
                                            @if ($alumni && $comment->alumni_id === $alumni->id)
                                                <form action="{{ route('alumni.feed.comment.delete', $comment->id) }}"
                                                      method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        style="background:none;border:none;padding:0;font-size:0.72rem;color:#842029;cursor:pointer;"
                                                        onclick="return confirm('Hapus komentar ini?')">
                                                        Hapus
                                                    </button>
                                                </form>
                                            @endif
                                        @endauth
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        @if ($commentCount > 3)
                            <p class="text-center mb-2" style="font-size:0.8rem;">
                                <a href="#" style="color: var(--parja-magenta); font-weight:600;">
                                    Lihat semua {{ $commentCount }} komentar
                                </a>
                            </p>
                        @endif

                        @auth('keycloak-external')
                            <form action="{{ route('alumni.feed.comment', $post->id) }}" method="POST"
                                  class="comment-form">
                                @csrf
                                <input type="text" name="konten"
                                       placeholder="Tulis komentar..." maxlength="1000" required>
                                <button type="submit"><i class="ri-send-plane-fill"></i></button>
                            </form>
                        @endauth
                    </div>
                </div>
            @endforeach

            <div class="d-flex justify-content-center mt-2">
                {{ $posts->links() }}
            </div>
        @endif

    </div>

    {{-- Kolom Samping --}}
    <div class="col-lg-4">

        @auth('keycloak-external')
        {{-- Panel Aktivitas untuk Alumni Login --}}
        <div class="feed-post-card">
            <p class="fw-bold mb-3" style="color: var(--parja-purple);">Aktivitas Saya</p>
            <div>
                <a href="{{ route('alumni.feed.my') }}" class="btn btn-outline-secondary btn-sm w-100 mb-2 rounded-pill">
                    <i class="ri-file-list-3-line"></i> Lihat Post Saya
                </a>
                <a href="{{ route('alumni.profile') }}" class="btn btn-outline-secondary btn-sm w-100 rounded-pill">
                    <i class="ri-user-3-line"></i> My Profile
                </a>
            </div>

            @if ($alumni)
                <hr style="border-color: var(--parja-border);">
                <p class="fw-semibold mb-2" style="color: var(--parja-muted); font-size: 0.82rem; text-transform: uppercase; letter-spacing: 0.6px;">Profil Saya</p>
                <p class="mb-1" style="font-size:0.88rem;"><span class="text-muted">Nama:</span> <strong>{{ $alumni->nama ?? (auth()->guard('keycloak-external')->user()?->name ?? auth()->guard('keycloak')->user()?->name ?? '') }}</strong></p>
                <p class="mb-1" style="font-size:0.88rem;"><span class="text-muted">Dapil:</span> {{ $alumni->dapil ?? '-' }}</p>
                <p class="mb-0" style="font-size:0.88rem;"><span class="text-muted">Angkatan:</span> {{ $alumni->tahun_angkatan ?? '-' }}</p>
            @endif
        </div>
        @endauth

        @guest('keycloak-external')
        {{-- Panel CTA untuk Tamu --}}
        <div class="feed-post-card" style="text-align:center; padding: 28px 20px;">
            <div style="width:64px;height:64px;border-radius:50%;background:linear-gradient(135deg,#f8eef5,#fff5ed);border:2px solid rgba(191,0,80,0.18);display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
                <i class="ri-group-line" style="font-size:1.8rem;color:var(--parja-magenta);"></i>
            </div>
            <p class="fw-bold mb-1" style="color:var(--parja-purple);font-size:1rem;">Bergabung dengan Komunitas</p>
            <p class="text-muted mb-4" style="font-size:0.84rem;line-height:1.5;">Daftar sebagai alumni Parja untuk membuat artikel, berinteraksi, dan terhubung dengan sesama alumni.</p>
            <a href="{{ route('alumni.register') }}" class="btn w-100 fw-semibold mb-2" style="background:var(--parja-magenta);color:#fff;border-radius:50px;">
                <i class="ri-user-add-line"></i> Daftar Alumni
            </a>
            <a href="{{ route('alumni.login') }}" class="btn w-100 fw-semibold" style="background:transparent;color:var(--parja-purple);border:1.5px solid rgba(65,23,75,0.35);border-radius:50px;">
                <i class="ri-login-box-line"></i> Masuk sebagai Alumni
            </a>

            <hr style="border-color:var(--parja-border);margin:20px 0 14px;">
            <p class="fw-semibold mb-2" style="color:var(--parja-muted);font-size:0.78rem;text-transform:uppercase;letter-spacing:0.6px;">Keuntungan Bergabung</p>
            <ul style="list-style:none;padding:0;margin:0;text-align:left;">
                <li style="font-size:0.84rem;color:var(--parja-text);padding:5px 0;display:flex;align-items:center;gap:8px;">
                    <i class="ri-edit-line" style="color:var(--parja-magenta);"></i> Tulis &amp; publikasikan artikel
                </li>
                <li style="font-size:0.84rem;color:var(--parja-text);padding:5px 0;display:flex;align-items:center;gap:8px;">
                    <i class="ri-heart-line" style="color:var(--parja-magenta);"></i> Like &amp; komentar postingan
                </li>
                {{-- Disembunyikan sementara: fitur Grup Chat belum diperlukan.
                <li style="font-size:0.84rem;color:var(--parja-text);padding:5px 0;display:flex;align-items:center;gap:8px;">
                    <i class="ri-chat-3-line" style="color:var(--parja-magenta);"></i> Bergabung di Grup Chat
                </li>
                --}}
                <li style="font-size:0.84rem;color:var(--parja-text);padding:5px 0;display:flex;align-items:center;gap:8px;">
                    <i class="ri-compass-discover-line" style="color:var(--parja-magenta);"></i> Temukan direktori alumni
                </li>
            </ul>
        </div>
        @endguest

        {{-- Widget Info Kegiatan Alumni --}}
        @include('parja::alumni.partials.sidebar-kegiatan')
    </div>
</div>

{{-- Lightbox --}}
<div class="pf-lightbox" id="pfLightbox" onclick="closeLightbox()">
    <span class="pf-lightbox-close" onclick="closeLightbox()">&times;</span>
    <img src="" id="pfLightboxImg" alt="Preview Foto">
</div>

{{-- Edit Post Modal --}}
<div class="post-edit-modal-backdrop" id="editPostBackdrop" onclick="closeEditPostModal(event)">
    <div class="post-edit-modal" onclick="event.stopPropagation()">
        <button type="button" class="post-edit-modal-close" onclick="closeEditPostModalDirect()">
            <i class="ri-close-line"></i>
        </button>
        <div class="post-edit-modal-title">
            <i class="ri-edit-box-line"></i> Edit Post
        </div>
        <form id="editPostForm" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label class="post-edit-label">Judul Artikel</label>
                <input type="text" name="judul" id="editJudul" class="post-edit-input"
                       placeholder="Judul artikel..." maxlength="255" required>
            </div>
            <div class="mb-3">
                <label class="post-edit-label">Konten</label>
                <textarea name="konten" id="editKonten" class="post-edit-textarea"
                          placeholder="Tulis konten..." maxlength="5000" required></textarea>
            </div>
            <div class="mb-3">
                <label class="post-edit-label">Kategori (opsional)</label>
                <select name="kategori_artikel" id="editKategori" class="post-edit-select">
                    <option value="">-- Tidak ada kategori --</option>
                    @foreach(['Kesehatan','Pendidikan','Teknologi Informasi','Hukum','Ekonomi','Politik','Sosial Budaya','Lingkungan Hidup','Olahraga','Seni & Budaya'] as $kat)
                        <option value="{{ $kat }}">{{ $kat }}</option>
                    @endforeach
                </select>
            </div>
            {{-- Existing photos --}}
            <div class="mb-2" id="editExistingPhotos" style="display:none;">
                <label class="post-edit-label">Foto Saat Ini</label>
                <div id="editExistingPhotosGrid" style="display:flex;gap:8px;flex-wrap:wrap;"></div>
                <input type="hidden" name="remove_foto_indexes" id="removeIndexesInput" value="">
            </div>
            <div class="mb-3">
                <label class="post-edit-label">Tambah Foto Baru (maks 5 total)</label>
                <input type="file" name="foto[]" id="editFotoInput" class="post-edit-input"
                       accept="image/jpeg,image/png,image/webp" multiple style="padding:7px 10px;">
            </div>
            <div class="post-edit-actions">
                <button type="button" class="post-edit-btn-cancel" onclick="closeEditPostModalDirect()">Batal</button>
                <button type="submit" class="post-edit-btn-save">
                    <i class="ri-save-line"></i> Simpan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    (function () {
        const focusedId = {{ isset($focusPostId) && $focusPostId ? (int) $focusPostId : 'null' }};
        if (!focusedId) return;

        const target = document.getElementById('post-' + focusedId);
        if (!target) return;

        window.setTimeout(function () {
            target.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }, 180);

        window.setTimeout(function () {
            target.classList.remove('focused-post');
        }, 4200);
    })();

    // Compose toggle (hanya untuk user yang sudah login)
    const composeToggle = document.getElementById('composeToggle');
    const composeBody   = document.getElementById('composeBody');
    let composeOpen     = {{ $errors->any() ? 'true' : 'true' }};

    function setCompose(open) {
        if (!composeBody || !composeToggle) return;
        composeOpen = open;
        composeBody.style.display = open ? 'block' : 'none';
        composeToggle.classList.toggle('collapsed', !open);
    }

    if (composeToggle) {
        composeToggle.addEventListener('click', () => setCompose(!composeOpen));
        setCompose(composeOpen);
    }

    // ---- Foto add / remove ----
    const MAX_FOTO = 5;
    const fotoFiles = []; // array of File objects in order

    const btnAddFoto          = document.getElementById('btnAddFoto');
    const fotoTrigger         = document.getElementById('fotoTrigger');
    const fotoPreviewGrid     = document.getElementById('fotoPreviewGrid');
    const fotoInputsContainer = document.getElementById('fotoInputsContainer');
    const fotoCountLabel      = document.getElementById('fotoCountLabel');
    const artikelForm         = document.getElementById('artikelForm');

    // Real multi file input that will be submitted
    const realFotoInput = document.createElement('input');
    realFotoInput.type     = 'file';
    realFotoInput.name     = 'foto[]';
    realFotoInput.multiple = true;
    realFotoInput.style.display = 'none';
    if (fotoInputsContainer) fotoInputsContainer.appendChild(realFotoInput);

    function updateFotoCount() {
        if (!fotoCountLabel) return;
        fotoCountLabel.textContent = fotoFiles.length + ' / ' + MAX_FOTO + ' foto';
        if (btnAddFoto) btnAddFoto.disabled = fotoFiles.length >= MAX_FOTO;
    }

    function renderPreviews() {
        if (!fotoPreviewGrid) return;
        fotoPreviewGrid.innerHTML = '';

        fotoFiles.forEach(function (file, idx) {
            const wrap = document.createElement('div');
            wrap.style.cssText = 'position:relative;display:inline-block;';

            const img = document.createElement('img');
            img.style.cssText = 'width:80px;height:80px;object-fit:cover;border-radius:8px;border:1px solid rgba(65,23,75,0.16);display:block;';
            const reader = new FileReader();
            reader.onload = function (e) { img.src = e.target.result; };
            reader.readAsDataURL(file);

            const removeBtn = document.createElement('button');
            removeBtn.type = 'button';
            removeBtn.title = 'Hapus foto ini';
            removeBtn.style.cssText = 'position:absolute;top:-6px;right:-6px;width:20px;height:20px;border-radius:50%;background:var(--parja-magenta);color:#fff;border:none;font-size:0.82rem;line-height:1;cursor:pointer;display:flex;align-items:center;justify-content:center;padding:0;';
            removeBtn.innerHTML = '&times;';
            removeBtn.addEventListener('click', function () {
                fotoFiles.splice(idx, 1);
                renderPreviews();
                updateFotoCount();
            });

            wrap.appendChild(img);
            wrap.appendChild(removeBtn);
            fotoPreviewGrid.appendChild(wrap);
        });
    }

    // On form submit — inject all fotoFiles into realFotoInput via DataTransfer
    if (artikelForm) {
        artikelForm.addEventListener('submit', function () {
            if (fotoFiles.length > 0 && typeof DataTransfer !== 'undefined') {
                const dt = new DataTransfer();
                fotoFiles.forEach(function (f) { dt.items.add(f); });
                realFotoInput.files = dt.files;
            }
        });
    }

    if (btnAddFoto) {
        btnAddFoto.addEventListener('click', function () {
            if (fotoFiles.length >= MAX_FOTO) return;
            fotoTrigger.value = '';
            fotoTrigger.click();
        });
    }

    if (fotoTrigger) {
        fotoTrigger.addEventListener('change', function () {
            const remaining = MAX_FOTO - fotoFiles.length;
            Array.from(this.files).slice(0, remaining).forEach(function (f) { fotoFiles.push(f); });
            renderPreviews();
            updateFotoCount();
            this.value = '';
        });
    }

    // Lightbox
    function openLightbox(src) {
        document.getElementById('pfLightboxImg').src = src;
        document.getElementById('pfLightbox').classList.add('show');
    }

    function closeLightbox() {
        document.getElementById('pfLightbox').classList.remove('show');
    }

    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') closeLightbox();
    });

    // Toggle komentar section
    function toggleCommentForm(postId) {
        const el = document.getElementById('comments-' + postId);
        if (!el) return;
        el.style.display = el.style.display === 'none' ? 'block' : 'none';
    }

    // Like via AJAX
    document.querySelectorAll('.like-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const postId = this.dataset.postId;
            const url    = this.dataset.url;
            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

            fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                },
            })
            .then(res => res.json())
            .then(data => {
                const countEl = this.querySelector('.like-count');
                const iconEl  = this.querySelector('i');
                if (data.liked) {
                    this.classList.add('liked');
                    iconEl.className = 'ri-heart-fill';
                } else {
                    this.classList.remove('liked');
                    iconEl.className = 'ri-heart-line';
                }
                if (countEl) countEl.textContent = data.count;
            })
            .catch(() => {});
        });
    });

    /* ── Post Settings Dropdown ── */
    function togglePostSettings(postId, e) {
        e.stopPropagation();
        const target = document.getElementById('settings-menu-' + postId);
        const isOpen = target && target.classList.contains('open');
        // close all
        document.querySelectorAll('.post-settings-dropdown.open').forEach(m => m.classList.remove('open'));
        if (!isOpen && target) target.classList.add('open');
    }
    document.addEventListener('click', function () {
        document.querySelectorAll('.post-settings-dropdown.open').forEach(m => m.classList.remove('open'));
    });

    /* ── Edit Post Modal ── */
    var _removeFotoIndexes = [];
    var _editUpdateUrl = '';

    function openEditPostModal(id, judul, konten, kategori, fotos) {
        _removeFotoIndexes = [];
        _editUpdateUrl = '{{ url("alumniparja/feed") }}/' + id;

        document.getElementById('editJudul').value = judul || '';
        document.getElementById('editKonten').value = konten || '';
        document.getElementById('editKategori').value = kategori || '';
        document.getElementById('editFotoInput').value = '';
        document.getElementById('removeIndexesInput').value = '';

        // Populate existing photos
        const grid = document.getElementById('editExistingPhotosGrid');
        const wrap = document.getElementById('editExistingPhotos');
        grid.innerHTML = '';
        if (fotos && fotos.length > 0) {
            fotos.forEach(function (path, idx) {
                const div = document.createElement('div');
                div.style.cssText = 'position:relative;display:inline-block;';
                div.innerHTML = '<img src="{{ asset("storage") }}/' + path + '" style="width:72px;height:72px;object-fit:cover;border-radius:10px;border:1.5px solid var(--parja-border);">'
                    + '<button type="button" onclick="removeFotoByIndex(' + idx + ', this.parentElement)" '
                    + 'style="position:absolute;top:-7px;right:-7px;background:#c0392b;border:none;color:#fff;border-radius:50%;width:20px;height:20px;font-size:0.75rem;line-height:20px;text-align:center;cursor:pointer;padding:0;">&times;</button>';
                div.dataset.fotoIdx = idx;
                grid.appendChild(div);
            });
            wrap.style.display = '';
        } else {
            wrap.style.display = 'none';
        }

        // Update form action
        const form = document.getElementById('editPostForm');
        form.action = _editUpdateUrl;

        // Close dropdown then open modal
        document.querySelectorAll('.post-settings-dropdown.open').forEach(m => m.classList.remove('open'));
        document.getElementById('editPostBackdrop').classList.add('open');
    }

    function removeFotoByIndex(idx, el) {
        if (!_removeFotoIndexes.includes(idx)) _removeFotoIndexes.push(idx);
        document.getElementById('removeIndexesInput').value = _removeFotoIndexes.join(',');
        if (el) el.remove();
    }

    function closeEditPostModal(e) {
        if (e.target === document.getElementById('editPostBackdrop')) {
            document.getElementById('editPostBackdrop').classList.remove('open');
        }
    }
    function closeEditPostModalDirect() {
        document.getElementById('editPostBackdrop').classList.remove('open');
    }

    /* ── SweetAlert Confirm Delete ── */
    function confirmDeletePost(postId) {
        document.querySelectorAll('.post-settings-dropdown.open').forEach(m => m.classList.remove('open'));
        Swal.fire({
            title: 'Hapus Post?',
            text: 'Post yang dihapus tidak bisa dikembalikan.',
            icon: 'warning',
            iconColor: '#c0392b',
            showCancelButton: true,
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#c0392b',
            cancelButtonColor: '#6c757d',
            reverseButtons: true,
            focusCancel: true,
            customClass: {
                popup: 'rounded-4',
                confirmButton: 'fw-semibold px-4',
                cancelButton: 'fw-semibold px-4',
            }
        }).then(function(result) {
            if (result.isConfirmed) {
                document.getElementById('delete-post-form-' + postId).submit();
            }
        });
    }
</script>
@endpush
