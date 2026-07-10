@extends('parja::alumni.layouts.app')

@section('title', 'Grup Chat Alumni')
@section('page-title', 'Grup Chat Alumni')

@push('styles')
<style>
    .channel-card {
        border-radius: 16px;
        border: 1px solid var(--parja-border);
        background: #fff;
        padding: 16px 18px;
        box-shadow: 0 4px 12px rgba(65,23,75,0.06);
        transition: box-shadow 0.15s, transform 0.15s;
        cursor: pointer;
        text-decoration: none;
        color: inherit;
        display: flex;
        align-items: center;
        gap: 14px;
        margin-bottom: 12px;
    }
    .channel-card:hover {
        box-shadow: 0 8px 20px rgba(191,0,80,0.13);
        transform: translateY(-1px);
        color: inherit;
    }
    .channel-avatar {
        width: 48px;
        height: 48px;
        border-radius: 14px;
        background: linear-gradient(135deg, var(--parja-magenta), var(--parja-purple));
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-weight: 800;
        font-size: 1.1rem;
        flex-shrink: 0;
    }
    .channel-info { flex: 1; min-width: 0; }
    .channel-name { font-weight: 700; color: var(--parja-purple); margin: 0; font-size: 0.97rem; }
    .channel-last-msg { font-size: 0.82rem; color: var(--parja-muted); margin: 2px 0 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .channel-meta { text-align: right; flex-shrink: 0; }
    .channel-time { font-size: 0.75rem; color: var(--parja-muted); }
    .channel-member-count { font-size: 0.72rem; color: var(--parja-muted); margin-top: 4px; }

    .section-title {
        font-size: 0.82rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: var(--parja-muted);
        margin-bottom: 10px;
    }

    .discover-card {
        border-radius: 14px;
        border: 1px solid var(--parja-border);
        background: #fff;
        padding: 14px 16px;
        box-shadow: 0 3px 8px rgba(65,23,75,0.05);
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 10px;
    }
    .discover-info { flex: 1; min-width: 0; }
    .discover-name { font-weight: 700; color: var(--parja-purple); font-size: 0.92rem; margin: 0; }
    .discover-desc { font-size: 0.78rem; color: var(--parja-muted); margin: 2px 0 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }

    .btn-join {
        background: var(--parja-magenta);
        color: #fff;
        border: none;
        border-radius: 50px;
        padding: 5px 16px;
        font-size: 0.8rem;
        font-weight: 600;
        cursor: pointer;
        flex-shrink: 0;
        text-decoration: none;
        display: inline-block;
    }
    .btn-join:hover { background: #a60046; color: #fff; }

    .modal-label { font-size: 0.85rem; font-weight: 600; color: var(--parja-purple); margin-bottom: 4px; }
    .form-control:focus { border-color: var(--parja-magenta); box-shadow: 0 0 0 3px rgba(191,0,80,0.12); }
</style>
@endpush

@section('content')
<div class="row g-3">

    {{-- Kolom Kiri: Kanal saya --}}
    <div class="col-lg-8">

        {{-- Header --}}
        <div class="d-flex align-items-center justify-content-between mb-3">
            <div>
                <h5 class="mb-0 fw-bold" style="color: var(--parja-purple);">
                    <i class="ri-chat-3-line me-2" style="color: var(--parja-magenta);"></i>Kanal Chat Saya
                </h5>
                <p class="mb-0 small text-muted">Grup chat yang kamu ikuti</p>
            </div>
            <button class="btn btn-sm fw-semibold text-white rounded-pill"
                    style="background: var(--parja-magenta);"
                    data-bs-toggle="modal" data-bs-target="#modalBuatKanal">
                <i class="ri-add-line"></i> Buat Kanal
            </button>
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

        @if ($myChannels->isEmpty())
            <div class="text-center py-5" style="color: var(--parja-muted);">
                <i class="ri-chat-3-line" style="font-size:3rem; color: var(--parja-border); display:block; margin-bottom:10px;"></i>
                <p class="mb-1 fw-semibold">Kamu belum bergabung ke kanal apapun.</p>
                <p class="small mb-0">Buat kanal baru atau bergabung ke kanal yang tersedia di sebelah kanan.</p>
            </div>
        @else
            <p class="section-title">Kanal Saya ({{ $myChannels->count() }})</p>
            @foreach ($myChannels as $ch)
                @php
                    $inisialKanal = strtoupper(substr($ch->nama, 0, 1));
                    $lastMsg      = $ch->latestMessage;
                @endphp
                <a href="{{ route('alumni.chat.show', $ch->id) }}" class="channel-card">
                    @if ($ch->avatar)
                        <img src="{{ asset('storage/' . $ch->avatar) }}"
                             class="channel-avatar" alt="{{ $ch->nama }}"
                             style="object-fit:cover;">
                    @else
                        <div class="channel-avatar">{{ $inisialKanal }}</div>
                    @endif
                    <div class="channel-info">
                        <p class="channel-name">{{ $ch->nama }}</p>
                        <p class="channel-last-msg">
                            @if ($lastMsg)
                                <span class="fw-semibold" style="color:var(--parja-purple)">{{ $lastMsg->alumni?->nama ?? 'Alumni' }}:</span>
                                {{ Str::limit($lastMsg->pesan, 55) }}
                            @else
                                <em>Belum ada pesan</em>
                            @endif
                        </p>
                    </div>
                    <div class="channel-meta">
                        <div class="channel-time">
                            @if ($lastMsg)
                                {{ $lastMsg->created_at->diffForHumans(null, true, true) }}
                            @endif
                        </div>
                        <div class="channel-member-count">
                            <i class="ri-group-line"></i> {{ $ch->members_count }}
                            @if (!$ch->is_public) <i class="ri-lock-line ms-1"></i> @endif
                        </div>
                    </div>
                </a>
            @endforeach
        @endif
    </div>

    {{-- Kolom Kanan: Temukan kanal --}}
    <div class="col-lg-4">
        <div style="border-radius:18px; border:1px solid var(--parja-border); background:#fff; padding:20px; box-shadow:0 6px 14px rgba(65,23,75,0.06);">
            <p class="fw-bold mb-3" style="color: var(--parja-purple); font-size:0.9rem;">
                <i class="ri-compass-discover-line me-1" style="color:var(--parja-magenta);"></i>
                Temukan Kanal
            </p>
            @if ($discoverChannels->isEmpty())
                <p class="small text-muted mb-0">Tidak ada kanal lain yang tersedia saat ini.</p>
            @else
                @foreach ($discoverChannels as $ch)
                    @php
                        $inisialKanal = strtoupper(substr($ch->nama, 0, 1));
                        $isPendingJoin = in_array((int) $ch->id, $pendingRequestChannelIds ?? [], true);
                    @endphp
                    <div class="discover-card">
                        @if ($ch->avatar)
                            <img src="{{ asset('storage/' . $ch->avatar) }}"
                                 style="width:40px;height:40px;border-radius:10px;object-fit:cover;flex-shrink:0;"
                                 alt="{{ $ch->nama }}">
                        @else
                            <div class="channel-avatar" style="width:40px;height:40px;border-radius:10px;font-size:0.9rem;">{{ $inisialKanal }}</div>
                        @endif
                        <div class="discover-info">
                            <p class="discover-name">{{ $ch->nama }}</p>
                            @if ($ch->is_public)
                                <p class="discover-desc">{{ $ch->deskripsi ?: $ch->members_count . ' anggota' }}</p>
                            @else
                                <p class="discover-desc">Private • {{ $ch->members_count }} anggota • {{ $ch->messages_count }} obrolan</p>
                            @endif
                        </div>
                        <form action="{{ route('alumni.chat.join', $ch->id) }}" method="POST">
                            @csrf
                            @if ($isPendingJoin)
                                <button type="button" class="btn-join" style="background:#b9b0bf;cursor:not-allowed;">Menunggu</button>
                            @elseif ($ch->is_public)
                                <button type="submit" class="btn-join">Gabung</button>
                            @else
                                <button type="submit" class="btn-join" style="background:var(--parja-purple);">Ajukan</button>
                            @endif
                        </form>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</div>

{{-- Modal Buat Kanal --}}
<div class="modal fade" id="modalBuatKanal" tabindex="-1" aria-labelledby="modalBuatKanalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:20px; border:1px solid var(--parja-border);">
            <div class="modal-header" style="border-bottom:1px solid var(--parja-border);">
                <h6 class="modal-title fw-bold" id="modalBuatKanalLabel" style="color:var(--parja-purple);">
                    <i class="ri-add-circle-line me-2" style="color:var(--parja-magenta);"></i>Buat Kanal Baru
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="createChannelForm" action="{{ route('alumni.chat.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">

                    {{-- Avatar Kanal --}}
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <div id="createAvatarWrap"
                             onclick="document.getElementById('createAvatarInput').click()"
                             style="width:68px;height:68px;border-radius:16px;background:linear-gradient(135deg,var(--parja-magenta),var(--parja-purple));display:flex;align-items:center;justify-content:center;color:#fff;font-weight:800;font-size:1.4rem;cursor:pointer;overflow:hidden;flex-shrink:0;border:2px solid var(--parja-border);">
                            <i class="ri-image-add-line" id="createAvatarIcon"></i>
                        </div>
                        <div>
                            <p class="mb-1 fw-semibold small" style="color:var(--parja-purple);">Foto Kanal</p>
                            <p class="mb-2 text-muted" style="font-size:0.75rem;">JPG, PNG, WEBP maks 2MB. Opsional.</p>
                            <input type="file" name="avatar" id="createAvatarInput"
                                   accept="image/jpg,image/jpeg,image/png,image/webp"
                                   style="display:none;" onchange="previewCreateAvatar(event)">
                            <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill"
                                    style="font-size:0.77rem;"
                                    onclick="document.getElementById('createAvatarInput').click()">
                                <i class="ri-image-edit-line"></i> Pilih Foto
                            </button>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="modal-label">Nama Kanal <span class="text-danger">*</span></label>
                        <input type="text" name="nama" id="createNamaInput" class="form-control rounded-3"
                               placeholder="Contoh: Alumni Angkatan 2022" maxlength="150" required
                               oninput="updateCreateAvatarLetter(this.value)">
                    </div>
                    <div class="mb-3">
                        <label class="modal-label">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control rounded-3" rows="3"
                                  placeholder="Tentang kanal ini..." maxlength="500"></textarea>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="is_public" value="1" id="checkPublic" checked>
                        <label class="form-check-label small fw-semibold" for="checkPublic" style="color:var(--parja-purple);">
                            Kanal Publik (centang: auto-join; tidak dicentang: private, tetap tampil di Temukan Kanal dengan approval admin/owner)
                        </label>
                    </div>
                </div>
                <div class="modal-footer" style="border-top:1px solid var(--parja-border);">
                    <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill" data-bs-dismiss="modal">Batal</button>
                    <button id="createChannelSubmitBtn" type="submit" class="btn btn-sm fw-semibold text-white rounded-pill"
                            style="background:var(--parja-magenta);">
                        <i class="ri-send-plane-line"></i> Buat Kanal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const createChannelForm = document.getElementById('createChannelForm');
    const createChannelSubmitBtn = document.getElementById('createChannelSubmitBtn');

    if (createChannelForm && createChannelSubmitBtn) {
        createChannelForm.addEventListener('submit', function (event) {
            if (createChannelSubmitBtn.disabled) {
                event.preventDefault();
                return false;
            }

            event.preventDefault();
            createChannelSubmitBtn.disabled = true;

            let remaining = 3;
            createChannelSubmitBtn.innerHTML = `<i class="ri-time-line"></i> Tunggu ${remaining}s...`;

            const timer = setInterval(() => {
                remaining -= 1;

                if (remaining > 0) {
                    createChannelSubmitBtn.innerHTML = `<i class="ri-time-line"></i> Tunggu ${remaining}s...`;
                    return;
                }

                clearInterval(timer);
                createChannelSubmitBtn.innerHTML = '<i class="ri-loader-4-line"></i> Memproses...';
                createChannelForm.submit();
            }, 1000);
        });
    }

    // Preview foto kanal sebelum upload (modal buat kanal)
    function previewCreateAvatar(event) {
        const file = event.target.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = function (e) {
            const wrap = document.getElementById('createAvatarWrap');
            wrap.innerHTML = `<img src="${e.target.result}" style="width:100%;height:100%;object-fit:cover;border-radius:14px;" alt="preview">`;
        };
        reader.readAsDataURL(file);
    }

    // Tampilkan huruf awal nama kanal di avatar jika belum ada foto
    function updateCreateAvatarLetter(value) {
        const wrap = document.getElementById('createAvatarWrap');
        // Kalau sudah ada img preview, jangan timpa
        if (wrap.querySelector('img')) return;
        const icon = document.getElementById('createAvatarIcon');
        if (value.trim()) {
            wrap.innerHTML = `<span style="font-size:1.6rem;font-weight:800;">${value.trim()[0].toUpperCase()}</span>`;
        } else {
            wrap.innerHTML = `<i class="ri-image-add-line" id="createAvatarIcon"></i>`;
        }
    }
</script>
@endpush
