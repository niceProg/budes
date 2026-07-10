@extends('parja::alumni.layouts.app')

@section('title', $channel->nama . ' — Chat')
@section('page-title', $channel->nama)

@push('styles')
<style>
    .chat-container {
        display: flex;
        gap: 0;
        height: calc(100vh - 180px);
        min-height: 400px;
        border-radius: 20px;
        border: 1px solid var(--parja-border);
        overflow: hidden;
        box-shadow: 0 10px 28px rgba(65,23,75,0.1);
        background: #fff;
    }

    /* ── Panel Anggota (kiri) ── */
    .member-panel {
        width: 240px;
        flex-shrink: 0;
        border-right: 1px solid var(--parja-border);
        display: flex;
        flex-direction: column;
        background: #faf7fc;
    }
    .member-panel-header {
        padding: 14px 16px 10px;
        border-bottom: 1px solid var(--parja-border);
    }
    .member-panel-header h6 {
        font-size: 0.8rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: var(--parja-muted);
        margin: 0;
    }
    .member-list { flex: 1; overflow-y: auto; padding: 10px 8px; }
    .member-item {
        display: flex;
        align-items: center;
        gap: 9px;
        padding: 7px 8px;
        border-radius: 10px;
        cursor: default;
    }
    .member-item:hover { background: rgba(191,0,80,0.06); }
    .member-avatar-sm {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--parja-magenta), var(--parja-purple));
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-weight: 700;
        font-size: 0.75rem;
        flex-shrink: 0;
        object-fit: cover;
    }
    .member-name-sm { font-size: 0.82rem; font-weight: 600; color: var(--parja-purple); margin: 0; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .member-role-badge {
        font-size: 0.65rem;
        padding: 1px 7px;
        border-radius: 50px;
        font-weight: 700;
        text-transform: uppercase;
    }
    .badge-owner { background: rgba(251,172,24,0.2); color: #b07800; }
    .badge-admin { background: rgba(191,0,80,0.12); color: var(--parja-magenta); }
    .badge-member { background: rgba(65,23,75,0.07); color: var(--parja-muted); }

    /* ── Area Chat (tengah) ── */
    .chat-main {
        flex: 1;
        display: flex;
        flex-direction: column;
        min-width: 0;
    }
    .chat-header {
        padding: 14px 20px;
        border-bottom: 1px solid var(--parja-border);
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: #fff;
    }
    .chat-header-info h6 { font-weight: 800; color: var(--parja-purple); margin: 0; font-size: 1rem; }
    .chat-header-info p { font-size: 0.78rem; color: var(--parja-muted); margin: 0; }

    .channel-avatar-circle {
        width: 40px;
        height: 40px;
        border-radius: 12px;
        background: linear-gradient(135deg, var(--parja-magenta), var(--parja-purple));
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-weight: 800;
        font-size: 1rem;
        flex-shrink: 0;
        object-fit: cover;
    }

    /* 3-dot kebab menu */
    .kebab-btn {
        background: none;
        border: none;
        width: 34px;
        height: 34px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--parja-muted);
        font-size: 1.1rem;
        cursor: pointer;
        transition: background 0.15s;
        flex-shrink: 0;
    }
    .kebab-btn:hover { background: rgba(65,23,75,0.07); color: var(--parja-purple); }

    /* Preview avatar upload */
    .avatar-upload-preview {
        width: 72px;
        height: 72px;
        border-radius: 16px;
        object-fit: cover;
        border: 2px solid var(--parja-border);
        background: var(--parja-soft);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        font-weight: 800;
        color: var(--parja-purple);
        overflow: hidden;
        cursor: pointer;
    }

    .chat-messages {
        flex: 1;
        overflow-y: auto;
        padding: 16px 20px;
        display: flex;
        flex-direction: column;
        gap: 4px;
        background: #fdfaff;
    }

    .msg-date-divider {
        text-align: center;
        font-size: 0.72rem;
        color: var(--parja-muted);
        font-weight: 600;
        margin: 10px 0 6px;
    }

    .msg-row { display: flex; align-items: flex-end; gap: 8px; }
    .msg-row.mine { flex-direction: row-reverse; }

    .msg-avatar {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--parja-magenta), var(--parja-purple));
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-weight: 700;
        font-size: 0.65rem;
        flex-shrink: 0;
        object-fit: cover;
    }

    .msg-bubble-wrap { max-width: 68%; display: flex; flex-direction: column; }
    .msg-row.mine .msg-bubble-wrap { align-items: flex-end; }
    .msg-sender { font-size: 0.72rem; font-weight: 700; color: var(--parja-purple); margin-bottom: 2px; }

    .msg-bubble {
        padding: 9px 13px;
        border-radius: 14px;
        font-size: 0.88rem;
        line-height: 1.5;
        word-break: break-word;
        white-space: pre-wrap;
        position: relative;
    }
    .msg-bubble.theirs {
        background: #fff;
        border: 1px solid var(--parja-border);
        border-bottom-left-radius: 4px;
        color: var(--parja-text);
    }
    .msg-bubble.mine {
        background: var(--parja-magenta);
        color: #fff;
        border-bottom-right-radius: 4px;
    }

    .msg-time {
        font-size: 0.68rem;
        color: var(--parja-muted);
        margin-top: 3px;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .msg-row.mine .msg-time { flex-direction: row-reverse; }
    .msg-delete-btn {
        background: none;
        border: none;
        color: rgba(191,0,80,0.5);
        cursor: pointer;
        font-size: 0.7rem;
        padding: 0;
        line-height: 1;
    }
    .msg-delete-btn:hover { color: var(--parja-magenta); }

    /* ── Input Chat ── */
    .chat-input-bar {
        padding: 12px 16px;
        border-top: 1px solid var(--parja-border);
        display: flex;
        align-items: center;
        gap: 10px;
        background: #fff;
    }
    .chat-input {
        flex: 1;
        border: 1px solid var(--parja-border);
        border-radius: 50px;
        padding: 9px 16px;
        font-size: 0.88rem;
        outline: none;
        transition: border-color 0.15s;
        resize: none;
        overflow: hidden;
        line-height: 1.4;
        max-height: 120px;
    }
    .chat-input:focus { border-color: var(--parja-magenta); }
    .chat-send-btn {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        background: var(--parja-magenta);
        color: #fff;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        cursor: pointer;
        flex-shrink: 0;
        transition: background 0.15s;
    }
    .chat-send-btn:hover { background: #a60046; }
    .chat-send-btn:disabled { opacity: 0.5; cursor: default; }

    .not-member-bar {
        padding: 14px 20px;
        background: #fffbef;
        border-top: 1px solid var(--parja-border);
        text-align: center;
        font-size: 0.88rem;
        color: var(--parja-muted);
    }

    /* ── Panel Admin (kanan) ── */
    .admin-panel {
        width: 220px;
        flex-shrink: 0;
        border-left: 1px solid var(--parja-border);
        background: #faf7fc;
        overflow-y: auto;
        padding: 14px;
        display: flex;
        flex-direction: column;
        gap: 12px;
    }
    @media (max-width: 900px) {
        .member-panel, .admin-panel { display: none; }
    }

    .admin-section-title {
        font-size: 0.72rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: var(--parja-muted);
        margin-bottom: 6px;
    }
    .admin-action-btn {
        display: block;
        width: 100%;
        text-align: left;
        padding: 6px 10px;
        border-radius: 8px;
        font-size: 0.81rem;
        font-weight: 600;
        border: 1px solid var(--parja-border);
        background: #fff;
        color: var(--parja-purple);
        cursor: pointer;
        margin-bottom: 6px;
        text-decoration: none;
    }
    .admin-action-btn:hover { background: rgba(191,0,80,0.06); color: var(--parja-magenta); }
    .admin-action-btn.danger { color: #c0392b; border-color: rgba(192,57,43,0.3); }
    .admin-action-btn.danger:hover { background: rgba(192,57,43,0.07); }

    /* Scrollbar */
    .chat-messages::-webkit-scrollbar, .member-list::-webkit-scrollbar { width: 4px; }
    .chat-messages::-webkit-scrollbar-thumb, .member-list::-webkit-scrollbar-thumb {
        background: rgba(191,0,80,0.2); border-radius: 10px;
    }
</style>
@endpush

@section('content')

@php
    $myRole       = $member?->role;
    $isAdmin      = in_array($myRole, ['admin', 'owner']);
    $isOwner      = $myRole === 'owner';
    $isMember     = (bool)$member;
    $isPrivatePreview = $isPrivatePreview ?? false;
    $lastDate     = null;
@endphp

<div class="mb-3 d-flex align-items-center gap-2">
    <a href="{{ route('alumni.chat.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill">
        <i class="ri-arrow-left-line"></i> Kembali
    </a>
    @if (!$channel->is_public)
        <span class="badge" style="background:rgba(65,23,75,0.1);color:var(--parja-purple);font-size:0.72rem;">
            <i class="ri-lock-line"></i> Privat
        </span>
    @endif
</div>

@if (session('success'))
    <div class="alert alert-success rounded-3 py-2 px-3 small mb-3">{{ session('success') }}</div>
@endif
@if (session('error'))
    <div class="alert alert-danger rounded-3 py-2 px-3 small mb-3">{{ session('error') }}</div>
@endif
@if (session('warning'))
    <div class="alert alert-warning rounded-3 py-2 px-3 small mb-3">{{ session('warning') }}</div>
@endif

<div class="chat-container">

    {{-- Daftar Anggota --}}
    <div class="member-panel">
        <div class="member-panel-header">
            <h6><i class="ri-group-line me-1"></i> Anggota ({{ $channel->members_count }})</h6>
        </div>
        <div class="member-list">
            @if ($isPrivatePreview)
                <div class="small" style="color:var(--parja-muted); padding:8px 10px;">
                    Kanal private hanya menampilkan statistik untuk non-anggota.
                    Kirim permintaan gabung agar dapat melihat daftar anggota dan isi obrolan.
                </div>
            @else
                @foreach ($members as $m)
                    @php
                        $mNama    = $m->alumni?->nama ?? '(Akun dihapus)';
                        $mInisial = strtoupper(substr($mNama, 0, 1));
                        $mFoto    = $m->alumni?->profile?->foto_profil
                            ? asset('storage/' . $m->alumni->profile->foto_profil) : null;
                        $isGhost  = is_null($m->alumni);
                    @endphp
                    <div class="member-item {{ $isGhost ? 'opacity-50' : '' }}" data-alumni-id="{{ $m->alumni_id }}" data-role="{{ $m->role }}">
                        @if ($mFoto)
                            <img src="{{ $mFoto }}" class="member-avatar-sm" alt="{{ $mNama }}">
                        @else
                            <div class="member-avatar-sm">{{ $mInisial }}</div>
                        @endif
                        <div style="flex:1; min-width:0;">
                            <p class="member-name-sm">{{ $mNama }}</p>
                            <span class="member-role-badge badge-{{ $m->role }}">{{ $m->role }}</span>
                        </div>
                        @if ($isAdmin && !$isGhost && $m->alumni_id !== $alumni->id && $m->role !== 'owner')
                            <div class="dropdown">
                                <button style="background:none;border:none;color:var(--parja-muted);cursor:pointer;padding:0 2px;"
                                        data-bs-toggle="dropdown"><i class="ri-more-2-fill" style="font-size:0.8rem;"></i></button>
                                <ul class="dropdown-menu dropdown-menu-end" style="font-size:0.8rem; min-width:160px;">
                                    @if ($isOwner && $m->role === 'member')
                                        <li>
                                            <form action="{{ route('alumni.chat.member.promote', [$channel->id, $m->alumni_id]) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="dropdown-item">
                                                    <i class="ri-shield-star-line me-1"></i> Jadikan Admin
                                                </button>
                                            </form>
                                        </li>
                                    @endif
                                    @if ($isOwner && $m->role === 'admin')
                                        <li>
                                            <form action="{{ route('alumni.chat.member.demote', [$channel->id, $m->alumni_id]) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="dropdown-item">
                                                    <i class="ri-shield-line me-1"></i> Turunkan ke Member
                                                </button>
                                            </form>
                                        </li>
                                    @endif
                                    <li>
                                        <form action="{{ route('alumni.chat.member.remove', [$channel->id, $m->alumni_id]) }}"
                                              method="POST"
                                              onsubmit="return confirm('Hapus anggota ini dari kanal?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dropdown-item text-danger">
                                                <i class="ri-user-unfollow-line me-1"></i> Keluarkan
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        @endif
                    </div>
                @endforeach
            @endif
        </div>

        {{-- Tambah Anggota (admin) --}}
        @if ($isAdmin)
            <div style="padding: 10px 8px; border-top: 1px solid var(--parja-border);">
                <button class="admin-action-btn" style="font-size:0.77rem;"
                        data-bs-toggle="modal" data-bs-target="#modalTambahAnggota">
                    <i class="ri-user-add-line me-1"></i> Tambah Anggota
                </button>

                @if ($pendingJoinRequests->isNotEmpty())
                    <div class="admin-section-title mt-2">Permintaan Gabung</div>
                    @foreach ($pendingJoinRequests as $req)
                        <div style="background:#fff;border:1px solid var(--parja-border);border-radius:10px;padding:8px;margin-bottom:7px;">
                            <div class="small fw-semibold" style="color:var(--parja-purple);">
                                {{ $req->alumni?->nama ?? 'Alumni' }}
                            </div>
                            <div class="small text-muted mb-2">{{ $req->created_at?->diffForHumans() }}</div>
                            <div class="d-flex gap-1">
                                <form action="{{ route('alumni.chat.join-request.approve', [$channel->id, $req->id]) }}" method="POST" style="flex:1;">
                                    @csrf
                                    <button type="submit" class="btn btn-sm w-100" style="font-size:0.72rem;background:var(--parja-magenta);color:#fff;">Approve</button>
                                </form>
                                <form action="{{ route('alumni.chat.join-request.reject', [$channel->id, $req->id]) }}" method="POST" style="flex:1;">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-secondary w-100" style="font-size:0.72rem;">Tolak</button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        @endif
    </div>

    {{-- Area Chat Utama --}}
    <div class="chat-main">
        <div class="chat-header">
            {{-- Avatar + Info --}}
            <div class="d-flex align-items-center gap-3">
                @if ($channel->avatar)
                    <img src="{{ asset('storage/' . $channel->avatar) }}"
                         class="channel-avatar-circle" alt="{{ $channel->nama }}">
                @else
                    <div class="channel-avatar-circle">{{ strtoupper(substr($channel->nama, 0, 1)) }}</div>
                @endif
                <div class="chat-header-info">
                    <h6>{{ $channel->nama }}</h6>
                    <p>{{ $channel->deskripsi ?: ($channel->members_count . ' anggota') }}</p>
                </div>
            </div>

            {{-- Aksi --}}
            <div class="d-flex align-items-center gap-2">
                @if (!$isMember)
                    @if ($channel->is_public)
                        <form action="{{ route('alumni.chat.join', $channel->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-sm fw-semibold text-white rounded-pill"
                                    style="background:var(--parja-magenta); font-size:0.78rem;">
                                <i class="ri-login-box-line"></i> Gabung Kanal
                            </button>
                        </form>
                    @elseif ($pendingJoinRequest)
                        <button type="button" class="btn btn-sm rounded-pill"
                                style="font-size:0.78rem;background:#b9b0bf;color:#fff;cursor:not-allowed;" disabled>
                            <i class="ri-time-line"></i> Menunggu Approval
                        </button>
                    @else
                        <form action="{{ route('alumni.chat.join', $channel->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-sm fw-semibold text-white rounded-pill"
                                    style="background:var(--parja-purple); font-size:0.78rem;">
                                <i class="ri-mail-send-line"></i> Ajukan Bergabung
                            </button>
                        </form>
                    @endif
                @endif

                @if ($isMember && !$isOwner)
                    {{-- Tombol Keluar untuk member/admin biasa --}}
                    <form action="{{ route('alumni.chat.leave', $channel->id) }}" method="POST"
                          onsubmit="return confirm('Keluar dari kanal ini?')">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-outline-secondary rounded-pill" style="font-size:0.78rem;">
                            <i class="ri-logout-box-line"></i> Keluar
                        </button>
                    </form>
                @endif

                @if ($isOwner)
                    {{-- Owner juga bisa keluar — ownership dialihkan otomatis --}}
                    <form action="{{ route('alumni.chat.leave', $channel->id) }}" method="POST"
                          onsubmit="return confirm('Keluar dari kanal? Kepemilikan akan dialihkan ke admin/anggota lain secara otomatis. Jika Anda satu-satunya anggota, kanal akan dihapus.')">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-outline-secondary rounded-pill" style="font-size:0.78rem;">
                            <i class="ri-logout-box-line"></i> Keluar
                        </button>
                    </form>
                    {{-- 3-dot kebab hanya untuk owner --}}
                    <div class="dropdown">
                        <button class="kebab-btn" data-bs-toggle="dropdown" aria-expanded="false" title="Pengaturan Kanal">
                            <i class="ri-more-2-fill"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" style="font-size:0.84rem; min-width:190px; border-radius:14px; border:1px solid var(--parja-border); box-shadow:0 8px 24px rgba(65,23,75,0.12);">
                            <li>
                                <button class="dropdown-item d-flex align-items-center gap-2 py-2"
                                        data-bs-toggle="modal" data-bs-target="#modalEditKanal">
                                    <i class="ri-edit-2-line" style="color:var(--parja-magenta);"></i> Edit Profil Kanal
                                </button>
                            </li>
                            <li><hr class="dropdown-divider my-1"></li>
                            <li>
                                <form action="{{ route('alumni.chat.destroy', $channel->id) }}" method="POST"
                                      onsubmit="return confirm('Hapus kanal ini beserta semua pesannya? Tindakan ini tidak dapat dibatalkan.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="dropdown-item d-flex align-items-center gap-2 py-2 text-danger">
                                        <i class="ri-delete-bin-line"></i> Hapus Kanal
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @endif
            </div>
        </div>

        {{-- Pesan --}}
        <div class="chat-messages" id="chatMessages">
            @if ($isPrivatePreview)
                <div style="max-width:560px;margin:18px auto;background:#fff;border:1px solid var(--parja-border);border-radius:14px;padding:16px 18px;">
                    <div class="fw-bold mb-1" style="color:var(--parja-purple);">Kanal Private</div>
                    <div class="small text-muted mb-2">Anda belum menjadi anggota kanal ini.</div>
                    <ul class="small mb-0" style="color:var(--parja-muted);padding-left:18px;">
                        <li>{{ $channel->members_count }} anggota</li>
                        <li>{{ $channel->messages_count }} total obrolan</li>
                        <li>Isi chat dan daftar anggota hanya terlihat setelah disetujui.</li>
                    </ul>
                </div>
            @else
            @foreach ($messages as $msg)
                @php
                    $isMine    = $msg->alumni_id === $alumni->id;
                    $mNama     = $msg->alumni?->nama ?? '(Akun dihapus)';
                    $mInisial  = strtoupper(substr($mNama, 0, 1));
                    $mFoto     = $msg->alumni?->profile?->foto_profil
                        ? asset('storage/' . $msg->alumni->profile->foto_profil) : null;
                    $msgDate   = $msg->created_at->format('d M Y');
                    $canDelete = $isMine || $isAdmin;
                @endphp

                @if ($msgDate !== $lastDate)
                    <div class="msg-date-divider">{{ $msgDate }}</div>
                    @php $lastDate = $msgDate; @endphp
                @endif

                <div class="msg-row {{ $isMine ? 'mine' : '' }}" id="msgRow{{ $msg->id }}">
                    @if ($mFoto)
                        <img src="{{ $mFoto }}" class="msg-avatar" alt="{{ $mNama }}">
                    @else
                        <div class="msg-avatar">{{ $mInisial }}</div>
                    @endif
                    <div class="msg-bubble-wrap">
                        @if (!$isMine)
                            <span class="msg-sender">{{ $mNama }}</span>
                        @endif
                        <div class="msg-bubble {{ $isMine ? 'mine' : 'theirs' }}">{{ $msg->pesan }}</div>
                        <div class="msg-time">
                            <span>{{ $msg->created_at->format('H:i') }}</span>
                            @if ($canDelete)
                                <button class="msg-delete-btn"
                                        onclick="deleteMessage({{ $channel->id }}, {{ $msg->id }})">
                                    <i class="ri-delete-bin-line"></i>
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
            @endif
        </div>

        {{-- Input / Not-member bar --}}
        @if ($isMember)
            <div class="chat-input-bar">
                <textarea id="chatInput" class="chat-input" rows="1"
                          placeholder="Tulis pesan..." maxlength="2000"
                          onkeydown="handleEnter(event)"></textarea>
                <button class="chat-send-btn" id="sendBtn" onclick="sendMessage()">
                    <i class="ri-send-plane-fill"></i>
                </button>
            </div>
        @else
            <div class="not-member-bar">
                Kamu belum bergabung ke kanal ini.
                @if ($channel->is_public)
                    <form action="{{ route('alumni.chat.join', $channel->id) }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" style="background:none;border:none;color:var(--parja-magenta);font-weight:700;cursor:pointer;font-size:0.88rem;">
                            Gabung sekarang
                        </button>
                    </form>
                @elseif ($pendingJoinRequest)
                    <span style="color:var(--parja-purple);font-weight:700;">Permintaan gabung sedang menunggu approval.</span>
                @else
                    <form action="{{ route('alumni.chat.join', $channel->id) }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" style="background:none;border:none;color:var(--parja-purple);font-weight:700;cursor:pointer;font-size:0.88rem;">
                            Ajukan bergabung
                        </button>
                    </form>
                @endif
            </div>
        @endif
    </div>

</div>

@if ($isAdmin)
<div class="modal fade" id="modalTambahAnggota" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:20px; border:1px solid var(--parja-border);">
            <div class="modal-header" style="border-bottom:1px solid var(--parja-border);">
                <h6 class="modal-title fw-bold" style="color:var(--parja-purple);">
                    <i class="ri-user-add-line me-2" style="color:var(--parja-magenta);"></i>Tambah Anggota
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('alumni.chat.member.add', $channel->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <label class="small fw-bold mb-2" style="color:var(--parja-purple);">Cari alumni berdasarkan nama</label>
                    <input type="text" id="searchAlumniInput" class="form-control rounded-3 mb-2"
                           placeholder="Ketik nama alumni..." autocomplete="off">
                    <div id="searchAlumniResult" style="max-height:200px; overflow-y:auto; border:1px solid var(--parja-border); border-radius:10px; display:none;"></div>
                    <input type="hidden" name="alumni_id" id="selectedAlumniId">
                    <p class="small text-muted mt-2 mb-0" id="selectedAlumniName"></p>
                </div>
                <div class="modal-footer" style="border-top:1px solid var(--parja-border);">
                    <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm fw-semibold text-white rounded-pill"
                            style="background:var(--parja-magenta);">
                        <i class="ri-user-add-line"></i> Tambahkan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

{{-- Modal Edit Profil Kanal (hanya owner) --}}
@if ($isOwner)
<div class="modal fade" id="modalEditKanal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:20px; border:1px solid var(--parja-border);">
            <div class="modal-header" style="border-bottom:1px solid var(--parja-border);">
                <h6 class="modal-title fw-bold" style="color:var(--parja-purple);">
                    <i class="ri-edit-2-line me-2" style="color:var(--parja-magenta);"></i>Edit Profil Kanal
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('alumni.chat.update', $channel->id) }}" method="POST"
                  enctype="multipart/form-data">
                @csrf
                <div class="modal-body">

                    {{-- Preview & Upload Foto Kanal --}}
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <div id="avatarPreviewWrap" class="avatar-upload-preview" onclick="document.getElementById('avatarInput').click()">
                            @if ($channel->avatar)
                                <img id="avatarPreviewImg" src="{{ asset('storage/' . $channel->avatar) }}"
                                     style="width:100%;height:100%;object-fit:cover;border-radius:14px;" alt="avatar">
                            @else
                                <span id="avatarPreviewLetter"
                                      style="font-size:1.6rem;font-weight:800;color:var(--parja-purple);">
                                    {{ strtoupper(substr($channel->nama, 0, 1)) }}
                                </span>
                            @endif
                        </div>
                        <div>
                            <p class="mb-1 fw-semibold small" style="color:var(--parja-purple);">Foto Kanal</p>
                            <p class="mb-2 text-muted" style="font-size:0.75rem;">JPG, PNG, WEBP maks 2MB. Klik gambar untuk ganti.</p>
                            <input type="file" name="avatar" id="avatarInput"
                                   accept="image/jpg,image/jpeg,image/png,image/webp"
                                   style="display:none;" onchange="previewAvatar(event)">
                            <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill"
                                    style="font-size:0.77rem;"
                                    onclick="document.getElementById('avatarInput').click()">
                                <i class="ri-image-edit-line"></i> Pilih Foto
                            </button>
                        </div>
                    </div>

                    {{-- Nama Kanal --}}
                    <div class="mb-3">
                        <label class="small fw-bold mb-1 d-block" style="color:var(--parja-purple);">
                            Nama Kanal <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="nama" class="form-control rounded-3"
                               value="{{ old('nama', $channel->nama) }}"
                               maxlength="150" required>
                    </div>

                    {{-- Deskripsi --}}
                    <div class="mb-3">
                        <label class="small fw-bold mb-1 d-block" style="color:var(--parja-purple);">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control rounded-3" rows="3"
                                  maxlength="500" placeholder="Tentang kanal ini...">{{ old('deskripsi', $channel->deskripsi) }}</textarea>
                    </div>

                    {{-- Visibilitas --}}
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="is_public" value="1"
                               id="editCheckPublic" {{ $channel->is_public ? 'checked' : '' }}>
                        <label class="form-check-label small fw-semibold" for="editCheckPublic"
                               style="color:var(--parja-purple);">
                            Kanal Publik (centang: auto-join; tidak dicentang: private, tetap tampil di Temukan Kanal dengan approval admin/owner)
                        </label>
                    </div>
                </div>
                <div class="modal-footer" style="border-top:1px solid var(--parja-border);">
                    <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill"
                            data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm fw-semibold text-white rounded-pill"
                            style="background:var(--parja-magenta);">
                        <i class="ri-save-line"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

@endsection

@push('scripts')
<script>
    const CHANNEL_ID  = {{ $channel->id }};
    const MY_ALUMNI_ID = {{ $alumni->id }};
    const IS_MEMBER   = {{ $isMember ? 'true' : 'false' }};
    const IS_ADMIN    = {{ $isAdmin ? 'true' : 'false' }};
    const CSRF        = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const SEND_URL    = "{{ route('alumni.chat.message.send', $channel->id) }}";
    const POLL_URL    = "{{ route('alumni.chat.message.poll', $channel->id) }}";
    const DEL_BASE    = "{{ url('/alumniparja/chat/' . $channel->id . '/messages') }}";

    let lastMsgId = {{ $messages->isNotEmpty() ? $messages->last()->id : 0 }};
    let lastDate  = "{{ $messages->isNotEmpty() ? $messages->last()->created_at->format('d M Y') : '' }}";

    const chatBox = document.getElementById('chatMessages');

    // Preview foto kanal sebelum upload
    function previewAvatar(event) {
        const file = event.target.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = function (e) {
            const wrap = document.getElementById('avatarPreviewWrap');
            wrap.innerHTML = `<img src="${e.target.result}" style="width:100%;height:100%;object-fit:cover;border-radius:14px;" alt="preview">`;
        };
        reader.readAsDataURL(file);
    }

    // Scroll ke bawah saat halaman dimuat
    chatBox.scrollTop = chatBox.scrollHeight;

    function handleEnter(e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            sendMessage();
        }
    }

    function sendMessage() {
        const input = document.getElementById('chatInput');
        const text  = input.value.trim();
        if (!text) return;

        const btn = document.getElementById('sendBtn');
        btn.disabled = true;

        fetch(SEND_URL, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': CSRF,
                'Accept': 'application/json',
            },
            body: JSON.stringify({ pesan: text }),
        })
        .then(r => r.json())
        .then(msg => {
            input.value = '';
            input.style.height = 'auto';
            appendMessage(msg, true);
            lastMsgId = msg.id;
        })
        .catch(() => alert('Gagal mengirim pesan. Coba lagi.'))
        .finally(() => { btn.disabled = false; });
    }

    function appendMessage(msg, isMine) {
        // Date divider
        if (msg.tanggal !== lastDate) {
            const div = document.createElement('div');
            div.className = 'msg-date-divider';
            div.textContent = msg.tanggal;
            chatBox.appendChild(div);
            lastDate = msg.tanggal;
        }

        const row = document.createElement('div');
        row.className = 'msg-row' + (isMine ? ' mine' : '');
        row.id = 'msgRow' + msg.id;

        const avatarHtml = msg.foto
            ? `<img src="${msg.foto}" class="msg-avatar" alt="${msg.nama}">`
            : `<div class="msg-avatar">${msg.inisial}</div>`;

        const senderHtml = !isMine ? `<span class="msg-sender">${msg.nama}</span>` : '';

        const canDelete = isMine || IS_ADMIN;
        const delBtn = canDelete
            ? `<button class="msg-delete-btn" onclick="deleteMessage(${CHANNEL_ID}, ${msg.id})"><i class="ri-delete-bin-line"></i></button>`
            : '';

        row.innerHTML = `
            ${avatarHtml}
            <div class="msg-bubble-wrap">
                ${senderHtml}
                <div class="msg-bubble ${isMine ? 'mine' : 'theirs'}">${msg.pesan}</div>
                <div class="msg-time"><span>${msg.waktu}</span>${delBtn}</div>
            </div>`;

        chatBox.appendChild(row);
        chatBox.scrollTop = chatBox.scrollHeight;
    }

    function deleteMessage(channelId, messageId) {
        if (!confirm('Hapus pesan ini?')) return;
        fetch(`${DEL_BASE}/${messageId}`, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
        })
        .then(r => r.json())
        .then(() => {
            const el = document.getElementById('msgRow' + messageId);
            if (el) el.remove();
        })
        .catch(() => alert('Gagal menghapus pesan.'));
    }

    // Polling pesan baru setiap 3 detik
    if (IS_MEMBER) {
        setInterval(function () {
            fetch(`${POLL_URL}?since=${lastMsgId}`, {
                headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF },
            })
            .then(r => r.json())
            .then(messages => {
                messages.forEach(msg => {
                    if (msg.alumni_id !== MY_ALUMNI_ID) {
                        appendMessage(msg, false);
                    }
                    if (msg.id > lastMsgId) lastMsgId = msg.id;
                });
            });
        }, 3000);
    }

    // Auto-resize textarea
    const chatInput = document.getElementById('chatInput');
    if (chatInput) {
        chatInput.addEventListener('input', function () {
            this.style.height = 'auto';
            this.style.height = Math.min(this.scrollHeight, 120) + 'px';
        });
    }

    // Pencarian alumni untuk tambah anggota
    const searchInput = document.getElementById('searchAlumniInput');
    const searchResult = document.getElementById('searchAlumniResult');
    const selectedId   = document.getElementById('selectedAlumniId');
    const selectedName = document.getElementById('selectedAlumniName');

    if (searchInput) {
        let searchTimer;
        searchInput.addEventListener('input', function () {
            clearTimeout(searchTimer);
            const q = this.value.trim();
            if (q.length < 2) { searchResult.style.display = 'none'; return; }
            searchTimer = setTimeout(() => {
                fetch(`/alumniparja/chat/${CHANNEL_ID}/search-alumni?q=${encodeURIComponent(q)}`, {
                    headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF },
                })
                .then(r => r.json())
                .then(data => {
                    searchResult.innerHTML = '';
                    if (data.length === 0) {
                        searchResult.innerHTML = '<div style="padding:10px 14px; font-size:0.82rem; color:var(--parja-muted);">Tidak ditemukan</div>';
                    } else {
                        data.forEach(a => {
                            const item = document.createElement('div');
                            item.style.cssText = 'padding:8px 14px; cursor:pointer; font-size:0.84rem; border-bottom:1px solid var(--parja-border);';
                            item.innerHTML = `<strong style="color:var(--parja-purple)">${a.nama}</strong> <span style="color:var(--parja-muted); font-size:0.78rem;">${a.dapil ?? ''}</span>`;
                            item.addEventListener('click', () => {
                                selectedId.value = a.id;
                                selectedName.textContent = 'Dipilih: ' + a.nama;
                                searchInput.value = a.nama;
                                searchResult.style.display = 'none';
                            });
                            searchResult.appendChild(item);
                        });
                    }
                    searchResult.style.display = 'block';
                });
            }, 350);
        });
    }
</script>
@endpush
