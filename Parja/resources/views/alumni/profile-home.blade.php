@extends('parja::alumni.layouts.app')

@section('title', 'My Profile Alumni')
@section('page-title', 'My Profile Alumni')

@push('styles')
<style>
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
    .post-edit-modal-close:hover { background: rgba(65,23,75,0.07); }
    .post-edit-label { font-size: 0.82rem; font-weight: 600; color: var(--parja-muted); margin-bottom: 4px; display: block; }
    .post-edit-input, .post-edit-textarea, .post-edit-select {
        width: 100%; border: 1.5px solid var(--parja-border); border-radius: 10px;
        padding: 9px 12px; font-size: 0.9rem; color: var(--parja-text);
        background: #faf8fc; outline: none; transition: border-color 0.15s;
    }
    .post-edit-input:focus, .post-edit-textarea:focus, .post-edit-select:focus { border-color: var(--parja-purple); }
    .post-edit-textarea { resize: vertical; min-height: 100px; }
    .post-edit-actions { display: flex; justify-content: flex-end; gap: 10px; margin-top: 16px; }
    .post-edit-btn-cancel {
        background: none; border: 1.5px solid var(--parja-border); border-radius: 10px;
        padding: 8px 20px; font-size: 0.86rem; font-weight: 600; color: var(--parja-muted); cursor: pointer;
    }
    .post-edit-btn-save {
        background: var(--parja-magenta); border: none; border-radius: 10px;
        padding: 8px 22px; font-size: 0.86rem; font-weight: 700; color: #fff; cursor: pointer;
    }

    .feed-photo-grid {
        display: grid;
        gap: 8px;
        border-radius: 14px;
        overflow: hidden;
        margin-top: 6px;
    }

    .feed-photo-grid img {
        width: 100%;
        display: block;
        object-fit: cover;
        cursor: pointer;
        transition: opacity 0.15s ease, transform 0.15s ease;
        background: #f8f9fa;
    }

    .feed-photo-grid img:hover {
        opacity: 0.92;
        transform: translateY(-1px);
    }

    .feed-photo-grid.count-1 {
        grid-template-columns: 1fr;
    }

    .feed-photo-grid.count-1 img {
        height: auto;
        max-height: 420px;
        object-fit: contain;
        border: 1px solid var(--parja-border);
        border-radius: 14px;
    }

    .feed-photo-grid.count-2 {
        grid-template-columns: 1fr 1fr;
    }

    .feed-photo-grid.count-2 img {
        height: 280px;
        border-radius: 12px;
    }

    .feed-photo-grid.count-3 {
        grid-template-columns: 1fr 1fr;
    }

    .feed-photo-grid.count-3 img:first-child {
        grid-column: 1 / span 2;
        height: 320px;
        border-radius: 12px;
    }

    .feed-photo-grid.count-3 img:not(:first-child) {
        height: 180px;
        border-radius: 12px;
    }

    .feed-photo-grid.count-4 {
        grid-template-columns: 1fr 1fr;
    }

    .feed-photo-grid.count-4 img {
        height: 220px;
        border-radius: 12px;
    }

    .feed-photo-grid.count-5 {
        grid-template-columns: repeat(6, 1fr);
    }

    .feed-photo-grid.count-5 img:nth-child(1),
    .feed-photo-grid.count-5 img:nth-child(2) {
        grid-column: span 3;
        height: 220px;
        border-radius: 12px;
    }

    .feed-photo-grid.count-5 img:nth-child(3),
    .feed-photo-grid.count-5 img:nth-child(4),
    .feed-photo-grid.count-5 img:nth-child(5) {
        grid-column: span 2;
        height: 170px;
        border-radius: 12px;
    }

    .pf-lightbox {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.88);
        z-index: 2100;
        align-items: center;
        justify-content: center;
        padding: 24px;
    }

    .pf-lightbox.show {
        display: flex;
    }

    .pf-lightbox img {
        max-width: min(92vw, 1200px);
        max-height: 88vh;
        width: auto;
        height: auto;
        object-fit: contain;
        border-radius: 12px;
        box-shadow: 0 16px 48px rgba(0, 0, 0, 0.35);
        background: rgba(255, 255, 255, 0.04);
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

    .profile-cover {
        border-radius: 20px;
        border: 1px solid var(--parja-border);
        background: linear-gradient(135deg, rgba(65, 23, 75, 0.94), rgba(191, 0, 80, 0.9));
        color: #fff;
        overflow: hidden;
        position: relative;
        padding: 24px;
        box-shadow: var(--parja-shadow);
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
        width: 84px;
        height: 84px;
        border-radius: 50%;
        border: 3px solid rgba(255, 255, 255, 0.42);
        object-fit: cover;
        background: rgba(255, 255, 255, 0.18);
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

    .profile-name {
        margin: 0;
        font-weight: 800;
        letter-spacing: 0.2px;
    }

    .profile-role {
        margin: 2px 0 0;
        color: rgba(255, 255, 255, 0.86);
    }

    .profile-dapil {
        margin: 4px 0 0;
        color: rgba(255, 255, 255, 0.74);
        font-size: 0.92rem;
    }

    .timeline-card {
        border-radius: 16px;
        border: 1px solid var(--parja-border);
        background: #fff;
        padding: 18px;
        box-shadow: 0 10px 20px rgba(65, 23, 75, 0.06);
    }

    .timeline-item {
        display: flex;
        gap: 12px;
        padding: 12px 0;
        border-bottom: 1px dashed rgba(65, 23, 75, 0.16);
    }

    .timeline-item:last-child {
        border-bottom: 0;
        padding-bottom: 0;
    }

    .timeline-icon {
        width: 34px;
        height: 34px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: rgba(251, 172, 24, 0.2);
        color: var(--parja-purple);
        flex-shrink: 0;
    }

    .timeline-title {
        margin: 0;
        font-weight: 700;
        color: var(--parja-purple);
    }

    .timeline-desc {
        margin: 2px 0 0;
        color: var(--parja-muted);
        font-size: 0.92rem;
    }

    .timeline-time {
        display: inline-block;
        margin-top: 6px;
        font-size: 0.78rem;
        color: #8f8198;
        background: #f7f4fa;
        border-radius: 999px;
        padding: 3px 9px;
    }

    .social-link-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        text-decoration: none;
        padding: 10px 0;
        border-bottom: 1px solid rgba(65, 23, 75, 0.12);
    }

    .social-link-item:last-child {
        border-bottom: 0;
        padding-bottom: 4px;
    }

    .social-link-meta {
        min-width: 0;
    }

    .social-link-label {
        margin: 0;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: #1f2937;
        font-weight: 600;
    }

    .social-link-handle {
        margin: 2px 0 0;
        color: #7b6d86;
        font-size: 0.84rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 220px;
    }

    .summary-item {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        padding: 10px 0;
        border-bottom: 1px solid rgba(65, 23, 75, 0.10);
    }

    .summary-item:last-child {
        border-bottom: 0;
        padding-bottom: 0;
    }

    .summary-icon {
        width: 34px;
        height: 34px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        background: rgba(65, 23, 75, 0.08);
        color: var(--parja-purple);
        font-size: 1rem;
    }

    .summary-label {
        margin: 0;
        font-size: 0.76rem;
        color: #9e8fab;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.4px;
    }

    .summary-value {
        margin: 2px 0 0;
        color: var(--parja-text);
        font-size: 0.9rem;
        font-weight: 500;
        line-height: 1.4;
    }
</style>
@endpush

@section('content')
    @php
        $profilePhoto = !empty($profile?->foto_profil) ? asset('storage/' . $profile->foto_profil) : null;
    @endphp

    <div class="profile-cover mb-4">
        <div class="profile-meta">
            <div class="d-flex align-items-center gap-3">
                @if ($profilePhoto)
                    <img src="{{ $profilePhoto }}" alt="Foto Profil" class="avatar">
                @else
                    <span class="avatar d-inline-flex align-items-center justify-content-center fw-bold">{{ strtoupper(substr((auth()->guard('keycloak-external')->user()?->name ?? auth()->guard('keycloak')->user()?->name ?? ''), 0, 1)) }}</span>
                @endif

                <div>
                    <h2 class="profile-name">{{ $alumni?->nama ?? (auth()->guard('keycloak-external')->user()?->name ?? auth()->guard('keycloak')->user()?->name ?? '') }}</h2>
                    <p class="profile-role">{{ ucfirst(collect(auth()->guard('keycloak-external')->user()?->roles ?? [])->first() ?? 'Alumni') }}</p>
                    <p class="profile-dapil">{{ $alumni?->dapil ? 'Dapil: ' . $alumni->dapil : 'Dapil belum diatur' }}</p>
                </div>
            </div>

            <a href="{{ route('alumni.profile.edit') }}" class="btn btn-light btn-sm fw-semibold">Update Profile</a>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-lg-8">

            {{-- Cerita Tentang Saya --}}
            <div id="bioCard" style="border-radius:18px; border:1px solid var(--parja-border); background:#fff; padding:20px 22px; box-shadow:0 6px 14px rgba(65,23,75,0.06); margin-bottom:16px;">
                <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:12px;">
                    <p class="mb-0 fw-bold" style="font-size:1rem; color: var(--parja-purple);">
                        <i class="ri-user-heart-line" style="color: var(--parja-magenta);"></i> Cerita Tentang Saya
                    </p>
                    <button type="button" onclick="toggleBioEdit()" id="bioEditBtn"
                        style="background:none; border:1.5px solid var(--parja-border); border-radius:50px; padding:4px 14px; font-size:0.8rem; font-weight:600; color:var(--parja-purple); cursor:pointer; transition:all 0.15s;"
                        onmouseover="this.style.borderColor='var(--parja-magenta)'; this.style.color='var(--parja-magenta)';"
                        onmouseout="this.style.borderColor='var(--parja-border)'; this.style.color='var(--parja-purple)';">
                        <i class="ri-edit-line"></i> Edit
                    </button>
                </div>

                {{-- Tampilan bio --}}
                <div id="bioView">
                    @if (!empty($profile?->bio))
                        <p style="font-size:0.93rem; color:var(--parja-text); line-height:1.7; white-space:pre-wrap; word-break:break-word; margin:0;">{{ $profile->bio }}</p>
                    @else
                        <p style="font-size:0.9rem; color:var(--parja-muted); font-style:italic; margin:0;">
                            Belum ada bio. Klik <strong>Edit</strong> untuk memperkenalkan diri kepada alumni lainnya.
                        </p>
                    @endif
                </div>

                {{-- Form edit bio (tersembunyi) --}}
                <div id="bioForm" style="display:none;">
                    <form action="{{ route('alumni.profile.bio.update') }}" method="POST">
                        @csrf
                        <textarea name="bio" id="bioTextarea" rows="5"
                            style="width:100%; border:1.5px solid var(--parja-border); border-radius:12px; padding:12px 14px; font-size:0.92rem; line-height:1.65; outline:none; resize:vertical; transition:border-color 0.15s; font-family:inherit;"
                            onfocus="this.style.borderColor='var(--parja-magenta)';"
                            onblur="this.style.borderColor='var(--parja-border)';"
                            maxlength="1000"
                            placeholder="Ceritakan sedikit tentang dirimu — pengalaman, passion, atau apa saja yang ingin kamu bagikan kepada sesama alumni...">{{ old('bio', $profile?->bio) }}</textarea>
                        <div style="display:flex; align-items:center; justify-content:space-between; margin-top:8px; flex-wrap:wrap; gap:8px;">
                            <span id="bioCharCount" style="font-size:0.78rem; color:var(--parja-muted);">0 / 1000 karakter</span>
                            <div style="display:flex; gap:8px;">
                                <button type="button" onclick="toggleBioEdit()"
                                    style="border:1.5px solid var(--parja-border); background:#fff; border-radius:50px; padding:5px 16px; font-size:0.82rem; font-weight:600; color:var(--parja-muted); cursor:pointer;">
                                    Batal
                                </button>
                                <button type="submit"
                                    style="background:var(--parja-magenta); border:none; border-radius:50px; padding:5px 18px; font-size:0.82rem; font-weight:600; color:#fff; cursor:pointer;">
                                    <i class="ri-save-line"></i> Simpan
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Prestasi & Penghargaan (profil pribadi — bukan feed/wall publik) --}}
            @php
                $prestasiPenghargaan = is_array($profile?->prestasi_penghargaan) ? $profile->prestasi_penghargaan : [];
            @endphp
            @if (!empty($prestasiPenghargaan))
            <div style="border-radius:18px; border:1px solid var(--parja-border); background:#fff; padding:20px 22px; box-shadow:0 6px 14px rgba(65,23,75,0.06); margin-bottom:16px;">
                <p class="mb-3 fw-bold" style="font-size:1rem; color: var(--parja-purple);">
                    <i class="ri-trophy-line" style="color: var(--parja-magenta);"></i> Prestasi &amp; Penghargaan
                </p>
                <div class="row g-3">
                    @foreach ($prestasiPenghargaan as $pp)
                        @php
                            $ppFoto = !empty($pp['foto']) ? asset('storage/' . $pp['foto']) : '';
                            $ppUrl = trim((string) ($pp['url_penghargaan'] ?? ''));
                        @endphp
                        <div class="col-md-6">
                            <div style="border:1px solid var(--parja-border); border-radius:14px; overflow:hidden; height:100%; display:flex; flex-direction:column;">
                                @if ($ppFoto)
                                    <img src="{{ $ppFoto }}" alt="Foto kegiatan" style="width:100%; height:150px; object-fit:cover;">
                                @endif
                                <div style="padding:12px 14px; display:flex; flex-direction:column; gap:8px; flex:1;">
                                    @if (!empty($pp['deskripsi']))
                                        <p style="font-size:0.9rem; color:var(--parja-text); line-height:1.6; margin:0; white-space:pre-wrap; word-break:break-word;">{{ $pp['deskripsi'] }}</p>
                                    @endif
                                    @if ($ppUrl !== '')
                                        <a href="{{ $ppUrl }}" target="_blank" rel="noopener"
                                           style="margin-top:auto; align-self:flex-start; background:transparent; border:1.5px solid var(--parja-magenta); color:var(--parja-magenta); border-radius:50px; padding:4px 14px; font-size:0.8rem; font-weight:600; text-decoration:none;">
                                            <i class="ri-external-link-line"></i> Lihat Penghargaan
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Form Buat Artikel --}}
            @include('parja::alumni.partials.feed-compose-form')

            {{-- Header Feed --}}
            <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom: 12px; flex-wrap:wrap; gap:8px;">
                <p class="mb-0 fw-bold" style="font-size:1rem; color: var(--parja-purple);">
                    <i class="ri-newspaper-line"></i> Feed Profil Saya
                    @if ($myPosts->isNotEmpty())
                        <span style="font-size:0.78rem; font-weight:400; color:var(--parja-muted);">({{ $myPosts->count() }} post)</span>
                    @endif
                </p>
                <button type="button" onclick="openAlumniComposeForm()"
                   style="font-size:0.8rem; font-weight:600; color: var(--parja-magenta); background:none; border:none; padding:0; cursor:pointer;">
                    <i class="ri-quill-pen-line"></i> Buat Post Baru
                </button>
            </div>

            @if ($myPosts->isEmpty())
                <div style="border-radius:18px; border:1px solid var(--parja-border); background:#fff; padding:40px 24px; text-align:center; color:var(--parja-muted); box-shadow:0 6px 14px rgba(65,23,75,0.06);">
                    <i class="ri-newspaper-line" style="font-size:2.2rem; display:block; margin-bottom:10px; opacity:0.3;"></i>
                    <p class="fw-semibold mb-1" style="color:var(--parja-text);">Postingan saat ini tidak tersedia</p>
                    <p class="small mb-3 text-muted">Bagikan cerita, pengalaman, atau artikel pertamamu!</p>
                    <button type="button" onclick="openAlumniComposeForm()"
                       style="display:inline-block; background:var(--parja-magenta); color:#fff; font-size:0.84rem; font-weight:600; border-radius:50px; padding:7px 22px; border:none; cursor:pointer;">
                        <i class="ri-quill-pen-line"></i> Buat Post
                    </button>
                </div>
            @else
                @php
                    $statusLabels  = [0 => 'Menunggu Review', 1 => 'Disetujui', 2 => 'Ditolak'];
                    $statusStyles  = [
                        0 => 'background:#fff3cd;color:#856404;',
                        1 => 'background:#d1e7dd;color:#0f5132;',
                        2 => 'background:#f8d7da;color:#842029;',
                    ];
                @endphp

                @foreach ($myPosts as $post)
                    <div style="border-radius:18px; border:1px solid var(--parja-border); background:#fff; padding:20px; box-shadow:0 6px 14px rgba(65,23,75,0.06); margin-bottom:14px;">

                        {{-- Post Header --}}
                        <div style="display:flex; align-items:center; gap:12px; margin-bottom:12px; flex-wrap:wrap; position:relative;">
                            @if ($profilePhoto)
                                <img src="{{ $profilePhoto }}" alt="Foto"
                                     style="width:40px; height:40px; border-radius:50%; object-fit:cover; border:2px solid var(--parja-border); flex-shrink:0;">
                            @else
                                <div style="width:40px; height:40px; border-radius:50%; background:var(--parja-soft); display:flex; align-items:center; justify-content:center; font-weight:700; font-size:0.95rem; color:var(--parja-purple); flex-shrink:0;">
                                    {{ strtoupper(substr($alumni?->nama ?? (auth()->guard('keycloak-external')->user()?->name ?? auth()->guard('keycloak')->user()?->name ?? ''), 0, 1)) }}
                                </div>
                            @endif
                            <div style="flex:1; min-width:0;">
                                <p style="margin:0; font-weight:700; font-size:0.92rem; color:var(--parja-purple);">{{ $alumni?->nama ?? (auth()->guard('keycloak-external')->user()?->name ?? auth()->guard('keycloak')->user()?->name ?? '') }}</p>
                                <p style="margin:0; font-size:0.78rem; color:var(--parja-muted);">{{ $post->created_at->diffForHumans() }}</p>
                            </div>
                            {{-- Badges --}}
                            <div style="display:flex; gap:6px; flex-wrap:wrap; flex-shrink:0;">
                                <span style="font-size:0.7rem; padding:2px 9px; border-radius:50px; font-weight:600; background:rgba(65,23,75,0.08); color:var(--parja-purple);">
                                    {{ $post->tipe === 'artikel' ? 'Artikel' : 'Freepost' }}
                                </span>
                                <span style="font-size:0.7rem; padding:2px 9px; border-radius:50px; font-weight:600; {{ $statusStyles[$post->status] ?? '' }}">
                                    {{ $statusLabels[$post->status] ?? '-' }}
                                </span>
                                <span style="font-size:0.7rem; padding:2px 9px; border-radius:50px; font-weight:600;
                                    background: {{ $post->privasi === 'public' ? 'rgba(13,110,253,0.1)' : 'rgba(65,23,75,0.08)' }};
                                    color: {{ $post->privasi === 'public' ? '#084298' : 'var(--parja-purple)' }};">
                                    <i class="{{ $post->privasi === 'public' ? 'ri-earth-line' : 'ri-lock-line' }}"></i>
                                    {{ $post->privasi === 'public' ? 'Public' : 'Parja Alumni' }}
                                </span>
                            </div>
                            {{-- 3-dot Options Button --}}
                            <button type="button" class="post-settings-btn"
                                onclick="togglePostSettings({{ $post->id }}, event)"
                                title="Opsi post">
                                <i class="ri-more-2-fill"></i>
                            </button>
                            <div class="post-settings-dropdown" id="settings-menu-{{ $post->id }}">
                                @if ($post->tipe === 'artikel')
                                    <button type="button" class="post-settings-item"
                                        onclick="openEditPostModal({{ $post->id }}, {{ json_encode($post->judul) }}, {{ json_encode($post->konten) }}, {{ json_encode($post->kategori_artikel) }}, {{ json_encode($post->foto_paths ?? []) }})">
                                        <i class="ri-edit-line"></i> Edit Post
                                    </button>
                                @endif
                                <form action="{{ route('alumni.feed.toggle-privacy', $post->id) }}" method="POST" class="m-0">
                                    @csrf
                                    @if ($post->privasi === 'public')
                                        <button type="submit" class="post-settings-item">
                                            <i class="ri-lock-line"></i> Jadikan Alumni Only
                                        </button>
                                    @elseif (isset($post->privasi_request) && $post->privasi_request === 'public')
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
                        </div>

                        {{-- Judul (artikel) --}}
                        @if ($post->tipe === 'artikel' && $post->judul)
                            <p style="font-weight:700; font-size:1rem; color:var(--parja-text); margin-bottom:6px;">{{ $post->judul }}</p>
                        @endif

                        {{-- Konten --}}
                        <p style="font-size:0.92rem; color:var(--parja-text); line-height:1.65; white-space:pre-wrap; word-break:break-word; margin-bottom: {{ !empty($post->foto_paths) ? '10px' : '0' }};">{{ $post->konten }}</p>

                        {{-- Foto Grid --}}
                        @if (!empty($post->foto_paths))
                            @php $fotoCount = count($post->foto_paths); @endphp
                            <div class="feed-photo-grid count-{{ min($fotoCount, 5) }}">
                                @foreach ($post->foto_paths as $foto)
                                    <img src="{{ asset('storage/' . $foto) }}"
                                         alt="Foto post"
                                         loading="lazy"
                                         decoding="async"
                                         onclick="openLightbox('{{ asset('storage/' . $foto) }}')">
                                @endforeach
                            </div>
                        @endif

                        {{-- Footer: like count + catatan admin --}}
                        <div style="display:flex; align-items:center; gap:14px; margin-top:10px; padding-top:10px; border-top:1px solid var(--parja-border); flex-wrap:wrap;">
                            <span style="font-size:0.82rem; color:var(--parja-muted);">
                                <i class="ri-heart-line"></i> {{ $post->likes->count() }} Suka
                            </span>
                            <a href="{{ route('alumni.feed.index', ['focus_post' => $post->id]) }}"
                               style="font-size:0.82rem; color:var(--parja-muted); text-decoration:none;"
                               title="Lihat & balas komentar di Feed">
                                <i class="ri-chat-1-line"></i> {{ $post->comments_count ?? 0 }} Komentar
                            </a>
                            @if ($post->status === 2 && $post->catatan_admin)
                                <span style="font-size:0.78rem; color:#842029; background:#f8d7da; border-radius:8px; padding:3px 10px;">
                                    <i class="ri-feedback-line"></i> {{ Str::limit($post->catatan_admin, 80) }}
                                </span>
                            @endif
                        </div>
                    </div>
                @endforeach
            @endif

        </div>

        <div class="col-lg-4">
            <x-parja.card title="Ringkasan Profil">
                <div class="summary-item">
                    <span class="summary-icon"><i class="ri-mail-line"></i></span>
                    <div>
                        <p class="summary-label">Email</p>
                        <p class="summary-value">{{ (auth()->guard('keycloak-external')->user()?->email ?? auth()->guard('keycloak')->user()?->email ?? '') }}</p>
                    </div>
                </div>
                <div class="summary-item">
                    <span class="summary-icon"><i class="ri-building-2-line"></i></span>
                    <div>
                        <p class="summary-label">Asal Sekolah</p>
                        <p class="summary-value">{{ $alumni?->asal_sekolah ?? 'Belum diisi' }}</p>
                    </div>
                </div>
                <div class="summary-item">
                    <span class="summary-icon"><i class="ri-calendar-line"></i></span>
                    <div>
                        <p class="summary-label">Tahun Angkatan</p>
                        <p class="summary-value">{{ $alumni?->tahun_angkatan ?? 'Belum diisi' }}</p>
                    </div>
                </div>
                <div class="summary-item">
                    <span class="summary-icon"><i class="ri-book-open-line"></i></span>
                    <div>
                        <p class="summary-label">Pendidikan Saat Ini</p>
                        <p class="summary-value">{{ $profile?->pendidikan_saat_ini ?? 'Belum diisi' }}</p>
                    </div>
                </div>
                <div class="summary-item">
                    <span class="summary-icon"><i class="ri-briefcase-line"></i></span>
                    <div>
                        <p class="summary-label">Pekerjaan</p>
                        @php
                            $pekData = $profile?->pekerjaan_saat_ini;
                            if (is_string($pekData) && !empty($pekData)) {
                                $pekData = json_decode($pekData, true) ?: [['judul' => $pekData]];
                            }
                            $judulPekerjaan = is_array($pekData) && count($pekData) > 0 ? ($pekData[0]['judul'] ?? 'Belum diisi') : 'Belum diisi';
                        @endphp
                        <p class="summary-value">{{ $judulPekerjaan }}</p>
                    </div>
                </div>
            </x-parja.card>

            <x-parja.card title="Media Sosial" class="mt-3">
                @forelse ($socialLinks as $social)
                    <a href="{{ $social['url'] }}" target="_blank" rel="noopener noreferrer" class="social-link-item">
                        <div class="social-link-meta">
                            <span class="social-link-label">
                                <i class="{{ $social['icon'] }}"></i>
                                {{ $social['label'] }}
                            </span>
                            <p class="social-link-handle">{{ $social['handle'] }}</p>
                        </div>
                        <i class="ri-arrow-right-up-line text-muted"></i>
                    </a>
                @empty
                    <p class="mb-0 text-muted">Belum ada akun media sosial yang ditambahkan.</p>
                @endforelse
            </x-parja.card>

            {{-- Status Artikel Saya: daftar status review & permintaan publik tiap artikel --}}
            <x-parja.card title="Status Artikel Saya" class="mt-3">
                @forelse ($myPosts as $post)
                    @php
                        $stMap = [
                            0 => ['Menunggu Review', '#856404', '#fff3cd'],
                            1 => ['Disetujui', '#0f5132', '#d1e7dd'],
                            2 => ['Ditolak', '#842029', '#f8d7da'],
                        ];
                        $st = $stMap[$post->status] ?? ['-', '#41174b', 'rgba(65,23,75,0.08)'];
                    @endphp
                    <div style="padding:9px 0; {{ !$loop->last ? 'border-bottom:1px solid var(--parja-border);' : '' }}">
                        <p style="margin:0 0 5px; font-size:0.85rem; font-weight:600; color:var(--parja-text); word-break:break-word; line-height:1.4;">
                            {{ $post->judul ?: \Illuminate\Support\Str::limit($post->konten, 45) }}
                        </p>
                        <div style="display:flex; gap:6px; flex-wrap:wrap;">
                            <span style="font-size:0.68rem; font-weight:600; padding:2px 8px; border-radius:50px; color:{{ $st[1] }}; background:{{ $st[2] }};">
                                {{ $st[0] }}
                            </span>
                            @if (isset($post->privasi_request) && $post->privasi_request === 'public')
                                <span style="font-size:0.68rem; font-weight:600; padding:2px 8px; border-radius:50px; color:#b45309; background:#fff3cd;">
                                    <i class="ri-time-line"></i> Req. Publik (menunggu)
                                </span>
                            @elseif ($post->privasi === 'public')
                                <span style="font-size:0.68rem; font-weight:600; padding:2px 8px; border-radius:50px; color:#084298; background:rgba(13,110,253,0.1);">
                                    <i class="ri-earth-line"></i> Publik
                                </span>
                            @else
                                <span style="font-size:0.68rem; font-weight:600; padding:2px 8px; border-radius:50px; color:var(--parja-purple); background:rgba(65,23,75,0.08);">
                                    <i class="ri-lock-line"></i> Parja Alumni
                                </span>
                            @endif
                        </div>
                        @if ($post->status === 2 && $post->catatan_admin)
                            <p style="margin:5px 0 0; font-size:0.72rem; color:#842029;">
                                <i class="ri-feedback-line"></i> {{ \Illuminate\Support\Str::limit($post->catatan_admin, 60) }}
                            </p>
                        @endif
                    </div>
                @empty
                    <p class="mb-0 text-muted" style="font-size:0.85rem;">Belum ada artikel. Buat post pertamamu lewat tombol "Buat Post".</p>
                @endforelse
            </x-parja.card>
        </div>
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
                       placeholder="Judul artikel..." maxlength="255">
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

<div class="pf-lightbox" id="pfLightbox" onclick="closeLightbox()">
    <span class="pf-lightbox-close" onclick="closeLightbox()">&times;</span>
    <img src="" id="pfLightboxImg" alt="Preview Foto" onclick="event.stopPropagation()">
</div>

@push('scripts')
<script>
    function toggleBioEdit() {
        const view    = document.getElementById('bioView');
        const form    = document.getElementById('bioForm');
        const editBtn = document.getElementById('bioEditBtn');
        const isEditing = form.style.display === 'none';

        view.style.display    = isEditing ? 'none'  : 'block';
        form.style.display    = isEditing ? 'block' : 'none';
        editBtn.style.display = isEditing ? 'none'  : 'inline-flex';

        if (isEditing) {
            const ta = document.getElementById('bioTextarea');
            ta.focus();
            updateCharCount(ta);
        }
    }

    function updateCharCount(ta) {
        const el = document.getElementById('bioCharCount');
        if (el) el.textContent = ta.value.length + ' / 1000 karakter';
    }

    function openLightbox(src) {
        const img = document.getElementById('pfLightboxImg');
        const box = document.getElementById('pfLightbox');
        if (!img || !box) return;

        img.src = src;
        box.classList.add('show');
    }

    function closeLightbox() {
        const img = document.getElementById('pfLightboxImg');
        const box = document.getElementById('pfLightbox');
        if (!img || !box) return;

        box.classList.remove('show');
        img.src = '';
    }

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            closeLightbox();
        }
    });

    const bioTa = document.getElementById('bioTextarea');
    if (bioTa) {
        bioTa.addEventListener('input', () => updateCharCount(bioTa));
        updateCharCount(bioTa);
    }

    // Buka otomatis jika ada error validasi bio
    @if ($errors->has('bio'))
        toggleBioEdit();
    @endif

    /* ── Post Settings Dropdown ── */
    function togglePostSettings(postId, e) {
        e.stopPropagation();
        const target = document.getElementById('settings-menu-' + postId);
        const isOpen = target && target.classList.contains('open');
        document.querySelectorAll('.post-settings-dropdown.open').forEach(m => m.classList.remove('open'));
        if (!isOpen && target) target.classList.add('open');
    }
    document.addEventListener('click', function () {
        document.querySelectorAll('.post-settings-dropdown.open').forEach(m => m.classList.remove('open'));
    });

    /* ── Edit Post Modal ── */
    var _removeFotoIndexes = [];

    function openEditPostModal(id, judul, konten, kategori, fotos) {
        _removeFotoIndexes = [];
        const baseUrl = '{{ url("alumniparja/feed") }}';

        document.getElementById('editJudul').value = judul || '';
        document.getElementById('editKonten').value = konten || '';
        document.getElementById('editKategori').value = kategori || '';
        document.getElementById('editFotoInput').value = '';
        document.getElementById('removeIndexesInput').value = '';

        const grid = document.getElementById('editExistingPhotosGrid');
        const wrap = document.getElementById('editExistingPhotos');
        grid.innerHTML = '';
        if (fotos && fotos.length > 0) {
            fotos.forEach(function (path, idx) {
                const div = document.createElement('div');
                div.style.cssText = 'position:relative;display:inline-block;';
                div.innerHTML = '<img src="{{ asset("storage") }}/'+path+'" style="width:72px;height:72px;object-fit:cover;border-radius:10px;border:1.5px solid var(--parja-border);">'
                    + '<button type="button" onclick="removeFotoByIndex('+idx+', this.parentElement)" '
                    + 'style="position:absolute;top:-7px;right:-7px;background:#c0392b;border:none;color:#fff;border-radius:50%;width:20px;height:20px;font-size:0.75rem;line-height:20px;text-align:center;cursor:pointer;padding:0;">&times;</button>';
                div.dataset.fotoIdx = idx;
                grid.appendChild(div);
            });
            wrap.style.display = '';
        } else {
            wrap.style.display = 'none';
        }

        document.getElementById('editPostForm').action = baseUrl + '/' + id;
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
