@extends('parja::alumni.layouts.app')

@section('title', 'Profil ' . ($targetAlumni->nama ?? 'Alumni'))
@section('page-title', 'Profil Alumni')

@push('styles')
<style>
    .profile-cover {
        border-radius: 20px;
        border: 1px solid var(--parja-border);
        background: linear-gradient(135deg, rgba(65, 23, 75, 0.94), rgba(191, 0, 80, 0.9));
        color: #fff;
        overflow: hidden;
        position: relative;
        padding: 24px;
        box-shadow: var(--parja-shadow);
        margin-bottom: 20px;
    }

    .profile-cover::after {
        content: "";
        position: absolute;
        width: 280px;
        height: 280px;
        border-radius: 50%;
        right: -110px;
        top: -120px;
        background: radial-gradient(circle, rgba(251, 172, 24, 0.42), rgba(251, 172, 24, 0));
        pointer-events: none;
    }

    .profile-cover .avatar {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        border: 3px solid rgba(255, 255, 255, 0.42);
        object-fit: cover;
        background: rgba(255, 255, 255, 0.18);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 1.6rem;
        color: #fff;
        flex-shrink: 0;
    }

    .profile-meta {
        position: relative;
        z-index: 1;
        display: flex;
        gap: 16px;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
    }

    .feed-post-card {
        border-radius: 18px;
        border: 1px solid var(--parja-border);
        background: #fff;
        padding: 20px;
        box-shadow: 0 6px 14px rgba(65, 23, 75, 0.06);
        margin-bottom: 16px;
    }

    .feed-post-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid var(--parja-border);
        background: var(--parja-soft);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.95rem;
        color: var(--parja-purple);
        flex-shrink: 0;
    }

    .tipe-badge {
        font-size: 0.7rem;
        padding: 2px 9px;
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

    .feed-photo-grid {
        display: grid;
        gap: 4px;
        margin-top: 12px;
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
        width: 28px;
        height: 28px;
        border-radius: 50%;
        background: var(--parja-soft);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.72rem;
        font-weight: 700;
        color: var(--parja-purple);
        flex-shrink: 0;
        object-fit: cover;
    }

    .comment-bubble {
        background: #f8f4fc;
        border-radius: 12px;
        padding: 7px 11px;
        flex: 1;
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

    .comment-form input:focus { border-color: var(--parja-magenta); }

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

    .sidebar-info-card {
        border-radius: 18px;
        border: 1px solid var(--parja-border);
        background: #fff;
        padding: 20px;
        box-shadow: 0 6px 14px rgba(65, 23, 75, 0.06);
        margin-bottom: 16px;
    }

    .summary-item {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        padding: 10px 0;
        border-bottom: 1px solid rgba(65, 23, 75, 0.08);
    }

    .summary-item:last-child {
        border-bottom: 0;
        padding-bottom: 0;
    }

    .summary-icon {
        width: 32px;
        height: 32px;
        border-radius: 9px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        background: rgba(65, 23, 75, 0.08);
        color: var(--parja-purple);
        font-size: 0.95rem;
    }

    .summary-label {
        margin: 0;
        font-size: 0.74rem;
        color: #9e8fab;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.4px;
    }

    .summary-value {
        margin: 2px 0 0;
        color: var(--parja-text);
        font-size: 0.88rem;
        font-weight: 500;
        line-height: 1.4;
    }

    .social-link-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        text-decoration: none;
        padding: 9px 0;
        border-bottom: 1px solid rgba(65, 23, 75, 0.1);
    }

    .social-link-item:last-child { border-bottom: 0; }

    .empty-posts {
        text-align: center;
        padding: 40px 20px;
        color: var(--parja-muted);
    }

    .empty-posts i {
        font-size: 2.5rem;
        color: var(--parja-border);
        display: block;
        margin-bottom: 10px;
    }
</style>
@endpush

@section('content')

@php
    $foto = $targetProfile?->foto_profil ? asset('storage/' . $targetProfile->foto_profil) : null;
    $inisial = strtoupper(substr($targetAlumni->nama ?? 'A', 0, 1));
@endphp

{{-- Back button --}}
<div class="mb-3">
    <a href="{{ url()->previous() === url()->current() ? route('alumni.direktori') : url()->previous() }}"
       class="btn btn-sm btn-outline-secondary rounded-pill fw-semibold">
        <i class="ri-arrow-left-line"></i> Kembali
    </a>
</div>

{{-- Cover Banner --}}
<div class="profile-cover">
    <div class="profile-meta">
        <div class="d-flex align-items-center gap-3">
            @if ($foto)
                <img src="{{ $foto }}" alt="{{ $targetAlumni->nama }}" class="avatar">
            @else
                <div class="avatar">{{ $inisial }}</div>
            @endif
            <div>
                <h2 style="margin:0; font-weight:800; letter-spacing:0.2px;">{{ $targetAlumni->nama ?? '-' }}</h2>
                <p style="margin:2px 0 0; color: rgba(255,255,255,0.86);">Alumni</p>
                @if ($targetAlumni->dapil)
                    <p style="margin:4px 0 0; color: rgba(255,255,255,0.74); font-size:0.9rem;">Dapil: {{ $targetAlumni->dapil }}</p>
                @endif
            </div>
        </div>
        @if ($targetAlumni->tahun_angkatan)
            <div style="text-align:right; position:relative; z-index:1;">
                <p style="margin:0; font-size:0.8rem; color:rgba(255,255,255,0.7);">Angkatan</p>
                <p style="margin:0; font-weight:800; font-size:1.6rem; line-height:1.2;">{{ $targetAlumni->tahun_angkatan }}</p>
            </div>
        @endif
    </div>
</div>

<div class="row g-3">

    {{-- Kiri: Posts --}}
    <div class="col-lg-8">
        {{-- Bio / Cerita Tentang Saya --}}
        @if (!empty($targetProfile?->bio))
            <div style="border-radius:18px; border:1px solid var(--parja-border); background:#fff; padding:20px 22px; box-shadow:0 6px 14px rgba(65,23,75,0.06); margin-bottom:16px;">
                <p class="fw-bold mb-2" style="font-size:0.95rem; color: var(--parja-purple);">
                    <i class="ri-user-heart-line me-1" style="color: var(--parja-magenta);"></i> Cerita Tentang Saya
                </p>
                <p style="font-size:0.93rem; color:var(--parja-text); line-height:1.7; white-space:pre-wrap; word-break:break-word; margin:0;">{{ $targetProfile->bio }}</p>
            </div>
        @endif

        <p class="fw-bold mb-3" style="color: var(--parja-purple); font-size:0.95rem;">
            <i class="ri-newspaper-line me-1" style="color: var(--parja-magenta);"></i>
            Post dari {{ $targetAlumni->nama ?? 'Alumni ini' }}
            <span style="font-size:0.78rem; font-weight:400; color: var(--parja-muted);">({{ $theirPosts->count() }} post)</span>
        </p>

        @if ($theirPosts->isEmpty())
            <div class="feed-post-card">
                <div class="empty-posts">
                    <i class="ri-newspaper-line"></i>
                    <p class="fw-semibold mb-1">Belum ada post yang dipublikasikan.</p>
                </div>
            </div>
        @else
            @foreach ($theirPosts as $post)
                @php
                    $likeCount       = $post->likes->count();
                    $isLiked         = $likedPostIds->contains($post->id);
                    $commentCount    = $post->comments->count();
                    $previewComments = $post->comments->take(3);
                @endphp
                <div class="feed-post-card">
                    {{-- Header --}}
                    <div style="display:flex; align-items:center; gap:12px; margin-bottom:12px; flex-wrap:wrap;">
                        @if ($foto)
                            <img src="{{ $foto }}" alt="{{ $targetAlumni->nama }}" class="feed-post-avatar">
                        @else
                            <div class="feed-post-avatar">{{ $inisial }}</div>
                        @endif
                        <div style="flex:1; min-width:0;">
                            <p style="margin:0; font-weight:700; font-size:0.92rem; color: var(--parja-purple);">{{ $targetAlumni->nama }}</p>
                            <p style="margin:0; font-size:0.78rem; color: var(--parja-muted);">{{ $post->created_at->diffForHumans() }}</p>
                        </div>
                        <span class="tipe-badge {{ $post->tipe }}">
                            {{ $post->tipe === 'artikel' ? 'Artikel' : 'Freepost' }}
                        </span>
                    </div>

                    @if ($post->tipe === 'artikel' && $post->judul)
                        <p style="font-weight:700; font-size:1rem; color:var(--parja-text); margin-bottom:6px;">{{ $post->judul }}</p>
                    @endif

                    <p style="font-size:0.92rem; color:var(--parja-text); line-height:1.65; white-space:pre-wrap; word-break:break-word; margin-bottom:0;">{{ $post->konten }}</p>

                    @if (!empty($post->foto_paths))
                        @php $fotoCount = count($post->foto_paths); @endphp
                        <div class="feed-photo-grid count-{{ min($fotoCount, 5) }}">
                            @foreach ($post->foto_paths as $f)
                                <img src="{{ asset('storage/' . $f) }}"
                                     alt="Foto post"
                                     onclick="openLightbox('{{ asset('storage/' . $f) }}')">
                            @endforeach
                        </div>
                    @endif

                    {{-- Action Bar --}}
                    <div class="post-action-bar">
                        @auth('keycloak-external')
                            <button type="button"
                                class="post-action-btn like-btn {{ $isLiked ? 'liked' : '' }}"
                                data-post-id="{{ $post->id }}"
                                data-url="{{ route('alumni.feed.like', $post->id) }}">
                                <i class="{{ $isLiked ? 'ri-heart-fill' : 'ri-heart-line' }}"></i>
                                <span class="like-count">{{ $likeCount }}</span>
                                <span>Suka</span>
                            </button>
                            <button type="button"
                                class="post-action-btn"
                                onclick="toggleComments({{ $post->id }})">
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

                    {{-- Komentar --}}
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
                                <div class="comment-bubble">
                                    <p style="font-size:0.78rem; font-weight:700; color:var(--parja-purple); margin:0 0 2px;">{{ $cNama }}</p>
                                    <p style="font-size:0.84rem; color:var(--parja-text); margin:0; word-break:break-word;">{{ $comment->konten }}</p>
                                    <p style="font-size:0.72rem; color:var(--parja-muted); margin:3px 0 0;">{{ $comment->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        @endforeach
                        @if ($commentCount > 3)
                            <p class="text-center mb-2" style="font-size:0.8rem;">
                                <span style="color:var(--parja-magenta); font-weight:600;">+{{ $commentCount - 3 }} komentar lainnya</span>
                            </p>
                        @endif
                        @auth('keycloak-external')
                            <form action="{{ route('alumni.feed.comment', $post->id) }}" method="POST" class="comment-form">
                                @csrf
                                <input type="text" name="konten" placeholder="Tulis komentar..." maxlength="1000" required>
                                <button type="submit"><i class="ri-send-plane-fill"></i></button>
                            </form>
                        @endauth
                    </div>
                </div>
            @endforeach
        @endif
    </div>

    {{-- Kanan: Info Profil --}}
    <div class="col-lg-4">

        {{-- Info Alumni — SATU kolom info terpadu, tanpa tab/pembeda kolom.
             Per atasan 18-05-2026 #4 (klarifikasi user): "jadi 1 kolom info saja
             digabung secara presisi dan rapih sesuai kapasitasnya bahkan hilangkan
             semua tab/pembeda kolomnya."

             Sebelumnya: 3 card terpisah (Ringkasan Profil + Media Sosial + Info Lainnya).
             Sekarang: 1 card dengan daftar plain text rata kiri.
        --}}
        @php
            $infoRows = [];
            if (!empty($targetAlumni->dapil)) {
                // Per atasan 18-05-2026 #6: dapil tidak berubah, tampilkan supplemental
                // info (kabupaten/kota, Provinsi) dari master dapil. Format akhir:
                //   "ACEH I (Kab. A, Kab. B, ... — Aceh)"  — dapil utama tetap utuh.
                $dapilLabel = (string) $targetAlumni->dapil;
                $kabList = $dapilKabupatenList ?? [];
                $prov    = $dapilProvinsi ?? null;
                if (!empty($kabList) || !empty($prov)) {
                    $supplemental = '';
                    if (!empty($kabList)) {
                        // Batasi ke 3 kabupaten pertama biar gak terlalu panjang
                        $shown = array_slice($kabList, 0, 3);
                        $supplemental .= implode(', ', $shown);
                        if (count($kabList) > 3) {
                            $supplemental .= ', dst.';
                        }
                    }
                    if (!empty($prov)) {
                        $supplemental .= ($supplemental !== '' ? ' — ' : '') . $prov;
                    }
                    $dapilLabel .= ' (' . $supplemental . ')';
                }
                $infoRows[] = ['label' => 'Dapil', 'value' => $dapilLabel];
            }
            if (!empty($targetAlumni->asal_sekolah)) {
                $infoRows[] = ['label' => 'Asal Sekolah', 'value' => $targetAlumni->asal_sekolah];
            }
            if (!empty($targetProfile?->pendidikan_saat_ini)) {
                $infoRows[] = ['label' => 'Pendidikan', 'value' => $targetProfile->pendidikan_saat_ini];
            }
            if (!empty($targetProfile?->pekerjaan_utama)) {
                $infoRows[] = ['label' => 'Pekerjaan', 'value' => $targetProfile->pekerjaan_utama];
            }
            if (!empty($targetProfile?->domisili_terakhir)) {
                $infoRows[] = ['label' => 'Domisili Terakhir', 'value' => $targetProfile->domisili_terakhir];
            }
            $orgListView = (array) ($targetProfile?->pengalaman_organisasi ?? []);
            if (!empty($orgListView)) {
                $orgParts = [];
                foreach ($orgListView as $org) {
                    $line = trim((string) ($org['organisasi'] ?? '-'));
                    if (!empty($org['jabatan'])) {
                        $line .= ' (' . trim((string) $org['jabatan']) . ')';
                    }
                    if (!empty($org['tahun'])) {
                        $line .= ' — ' . trim((string) $org['tahun']);
                    }
                    $orgParts[] = $line;
                }
                $infoRows[] = ['label' => 'Organisasi', 'value' => implode('; ', $orgParts)];
            }
            $ketertarikanList = (array) ($targetProfile?->ketertarikan_bidang ?? []);
            $ketertarikanList = array_values(array_filter(array_map('trim', $ketertarikanList)));
            $hasInfoAny = !empty($infoRows) || !empty($ketertarikanList) || (isset($socialLinks) && $socialLinks->isNotEmpty());
        @endphp

        @if ($hasInfoAny)
            <div class="sidebar-info-card">
                <p class="fw-bold mb-3" style="color: var(--parja-purple); font-size:0.95rem;">
                    <i class="ri-user-3-line me-1" style="color: var(--parja-magenta);"></i>
                    Info Alumni
                </p>

                <ul class="list-unstyled mb-0" style="font-size:0.88rem; line-height:1.7;">
                    @foreach ($infoRows as $row)
                        <li style="margin-bottom:8px;">
                            <strong style="color: var(--parja-purple); display:inline-block; min-width:130px;">{{ $row['label'] }}:</strong>
                            <span style="color: var(--parja-text);">{{ $row['value'] }}</span>
                        </li>
                    @endforeach

                    @if (!empty($ketertarikanList))
                        <li style="margin-bottom:8px;">
                            <strong style="color: var(--parja-purple); display:inline-block; min-width:130px;">Ketertarikan:</strong>
                            <span style="color: var(--parja-text);">
                                @foreach ($ketertarikanList as $idx => $k)
                                    {{ $k }}{{ $idx < count($ketertarikanList) - 1 ? ', ' : '' }}
                                @endforeach
                            </span>
                        </li>
                    @endif

                    @if (isset($socialLinks) && $socialLinks->isNotEmpty())
                        <li style="margin-bottom:8px;">
                            <strong style="color: var(--parja-purple); display:inline-block; min-width:130px;">Media Sosial:</strong>
                            <span style="color: var(--parja-text);">
                                @foreach ($socialLinks as $idx => $sl)
                                    <a href="{{ $sl['url'] }}" target="_blank" rel="noopener" style="color: var(--parja-magenta); text-decoration:none;">{{ $sl['label'] }}</a>@if($idx < $socialLinks->count() - 1), @endif
                                @endforeach
                            </span>
                        </li>
                    @endif
                </ul>
            </div>
        @endif

        {{-- Prestasi & Penghargaan (bagian profil pribadi) --}}
        @php
            $ppView = is_array($targetProfile?->prestasi_penghargaan) ? $targetProfile->prestasi_penghargaan : [];
        @endphp
        @if (!empty($ppView))
            <div class="sidebar-info-card mt-3">
                <p class="fw-bold mb-3" style="color: var(--parja-purple); font-size:0.95rem;">
                    <i class="ri-trophy-line me-1" style="color: var(--parja-magenta);"></i>
                    Prestasi &amp; Penghargaan
                </p>
                @foreach ($ppView as $pp)
                    @php
                        $ppFoto = !empty($pp['foto']) ? asset('storage/' . $pp['foto']) : '';
                        $ppUrl = trim((string) ($pp['url_penghargaan'] ?? ''));
                    @endphp
                    <div style="border:1px solid var(--parja-border); border-radius:12px; overflow:hidden; margin-bottom:10px;">
                        @if ($ppFoto)
                            <img src="{{ $ppFoto }}" alt="Foto kegiatan" onclick="openLightbox('{{ $ppFoto }}')"
                                 style="width:100%; height:120px; object-fit:cover; cursor:pointer;">
                        @endif
                        <div style="padding:10px 12px;">
                            @if (!empty($pp['deskripsi']))
                                <p style="font-size:0.85rem; color:var(--parja-text); line-height:1.55; margin:0 0 6px; white-space:pre-wrap; word-break:break-word;">{{ $pp['deskripsi'] }}</p>
                            @endif
                            @if ($ppUrl !== '')
                                <a href="{{ $ppUrl }}" target="_blank" rel="noopener" style="font-size:0.8rem; color:var(--parja-magenta); text-decoration:none; font-weight:600;">
                                    <i class="ri-external-link-line"></i> Lihat Penghargaan
                                </a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

    </div>
</div>

{{-- Lightbox --}}
<div class="pf-lightbox" id="pfLightbox" onclick="closeLightbox()">
    <span class="pf-lightbox-close" onclick="closeLightbox()">&times;</span>
    <img src="" id="pfLightboxImg" alt="Preview Foto">
</div>

@endsection

@push('scripts')
<script>
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

    function toggleComments(postId) {
        const el = document.getElementById('comments-' + postId);
        if (!el) return;
        el.style.display = el.style.display === 'none' ? 'block' : 'none';
    }

    // Like via AJAX
    document.querySelectorAll('.like-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const url = this.dataset.url;
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
</script>
@endpush
