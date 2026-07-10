<style>
    .header-main {
        font-family: 'Plus Jakarta Sans', sans-serif;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-bottom: 1px solid rgba(0,0,0,0.06);
        box-shadow: 0 2px 10px rgba(0,0,0,0.03);
    }

    .header-main h3 {
        font-weight: 800;
        font-size: 1.3rem;
        color: var(--primary-dark, #0f172a);
        margin: 0;
        letter-spacing: -0.5px;
    }

    .header-main h3::after {
        content: '';
        display: inline-block;
        width: 3px;
        height: 20px;
        background: linear-gradient(135deg, #c5a059 0%, #8e6d2f 100%);
        margin-left: 10px;
        vertical-align: middle;
        border-radius: 2px;
    }

    .menu-link {
        color: var(--primary-dark, #0f172a);
        transition: all 0.3s ease;
    }

    .menu-link:hover {
        color: var(--gold-solid, #b08d48);
        transform: scale(1.1);
    }

    .dropdown-link {
        color: var(--text-main, #1e293b);
        transition: all 0.3s ease;
    }

    .dropdown-link:hover {
        color: var(--gold-solid, #b08d48);
    }

    /* Ikon pengumuman peserta: sama gaya dengan ikon setting skin (warna tema) */
    .peserta-announcement-notif {
        position: relative;
        padding: 0.2rem 0.15rem;
    }

    .peserta-announcement-notif > i {
        font-size: 1.35rem;
        line-height: 1;
        color: var(--text-main, #1e293b);
        transition: color 0.3s ease;
    }

    .peserta-announcement-notif:hover > i,
    .peserta-announcement-notif:focus-visible > i {
        color: var(--gold-solid, #b08d48);
    }

    [data-skin="dark"] .peserta-announcement-notif > i {
        color: #e5e7eb;
    }

    [data-skin="dark"] .peserta-announcement-notif:hover > i,
    [data-skin="dark"] .peserta-announcement-notif:focus-visible > i {
        color: var(--gold-solid, #b08d48);
    }

    .peserta-announcement-notif.peserta-announcement-has-unread > i {
        color: var(--gold-solid, #b08d48);
    }

    .peserta-announcement-badge {
        position: absolute;
        top: -2px;
        right: -6px;
        font-size: 0.6rem;
        min-width: 1.05rem;
        padding: 0.15em 0.4em;
        line-height: 1.2;
        font-weight: 700;
        color: #fff !important;
        background: linear-gradient(135deg, #c5a059 0%, #8e6d2f 100%) !important;
        border: 2px solid rgba(255, 255, 255, 0.95);
        box-shadow: 0 1px 2px rgba(15, 23, 42, 0.12);
    }

    [data-skin="dark"] .peserta-announcement-badge {
        border-color: #0f172a;
    }

    .peserta-announcement-dropdown {
        min-width: 320px;
        max-width: min(100vw - 24px, 400px);
        padding: 0;
        overflow: hidden;
    }

    [data-skin="dark"] .peserta-announcement-dropdown.dropdown-menu {
        background: #1e293b;
        border-color: rgba(255, 255, 255, 0.08);
    }

    .peserta-announcement-dropdown-header {
        background: rgba(176, 141, 72, 0.08);
    }

    [data-skin="dark"] .peserta-announcement-dropdown-header {
        background: rgba(176, 141, 72, 0.12);
        border-color: rgba(255, 255, 255, 0.08) !important;
    }

    .peserta-announcement-list {
        max-height: min(70vh, 380px);
        overflow-y: auto;
    }

    .peserta-announcement-item {
        white-space: normal;
        padding: 0.65rem 1rem;
        border-bottom: 1px solid rgba(0, 0, 0, 0.06);
        cursor: pointer;
    }

    .peserta-announcement-item:last-child {
        border-bottom: none;
    }

    .peserta-announcement-item:hover {
        background: rgba(176, 141, 72, 0.08);
    }

    [data-skin="dark"] .peserta-announcement-item {
        border-color: rgba(255, 255, 255, 0.08);
    }

    [data-skin="dark"] .peserta-announcement-item:hover {
        background: rgba(176, 141, 72, 0.15);
    }

    .peserta-announcement-item-subject {
        font-weight: 600;
        font-size: 0.875rem;
        color: #0f172a;
        line-height: 1.35;
        margin-bottom: 0.25rem;
    }

    [data-skin="dark"] .peserta-announcement-item-subject {
        color: #f1f5f9;
    }

    .peserta-announcement-item-preview {
        font-size: 0.75rem;
        color: #64748b;
        line-height: 1.45;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    [data-skin="dark"] .peserta-announcement-item-preview {
        color: #94a3b8;
    }

    /* Popup pengumuman broadcast (SweetAlert2) — area konten lebih lapang & bisa scroll */
    .swal2-popup.swal2-broadcast-popup {
        padding: 2.85rem 1.65rem 1.45rem;
        border-radius: 18px;
        box-shadow: 0 25px 50px -12px rgba(15, 23, 42, 0.22);
    }

    /* Tombol X di pojok kanan atas, di atas baris badge / SIAP-MAGANG */
    .swal2-broadcast-popup .swal2-close.swal2-broadcast-close {
        position: absolute !important;
        top: 0.65rem !important;
        right: 0.65rem !important;
        margin: 0 !important;
        width: 2.25rem !important;
        height: 2.25rem !important;
        min-width: 2.25rem !important;
        min-height: 2.25rem !important;
        padding: 0 !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        border-radius: 10px !important;
        color: #94a3b8 !important;
        background: rgba(241, 245, 249, 0.95) !important;
        box-shadow: 0 1px 2px rgba(15, 23, 42, 0.06);
        z-index: 20;
        line-height: 1 !important;
    }

    .swal2-broadcast-popup .swal2-close.swal2-broadcast-close:hover {
        color: #475569 !important;
        background: #e2e8f0 !important;
    }

    [data-skin="dark"] .swal2-broadcast-popup .swal2-close.swal2-broadcast-close {
        color: #cbd5e1 !important;
        background: rgba(30, 41, 59, 0.95) !important;
    }

    [data-skin="dark"] .swal2-broadcast-popup .swal2-close.swal2-broadcast-close:hover {
        color: #f1f5f9 !important;
        background: #334155 !important;
    }

    .swal2-broadcast-popup .swal2-html-container.swal2-broadcast-html-container {
        margin: 0 !important;
        padding: 0 !important;
        overflow: visible;
    }

    .peserta-broadcast-modal__top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        margin-bottom: 14px;
        padding-right: 0.25rem;
        /* Beri ruang agar teks kanan tidak nabrak area tombol tutup */
        max-width: calc(100% - 0.25rem);
    }

    .peserta-broadcast-modal__badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 6px 12px;
        border-radius: 999px;
        background: linear-gradient(135deg, #eef2ff 0%, #e0e7ff 100%);
        color: #3730a3;
        font-size: 12px;
        font-weight: 700;
        box-shadow: 0 1px 2px rgba(15, 23, 42, 0.06);
    }

    .peserta-broadcast-modal__brand {
        font-size: 12px;
        color: #64748b;
        font-weight: 600;
        letter-spacing: 0.02em;
    }

    .peserta-broadcast-modal__title {
        margin: 0 0 14px;
        font-size: 1.35rem;
        line-height: 1.35;
        color: #0f172a;
        font-weight: 800;
        letter-spacing: -0.02em;
    }

    [data-skin="dark"] .peserta-broadcast-modal__title {
        color: #f8fafc;
    }

    .peserta-broadcast-modal__body {
        min-height: 320px;
        max-height: min(56vh, 520px);
        overflow-x: hidden;
        overflow-y: auto;
        padding: 1.35rem 1.4rem;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        background: linear-gradient(180deg, #ffffff 0%, #f8fafc 55%, #f1f5f9 100%);
        box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.9);
        line-height: 1.75;
        color: #334155;
        font-size: 15px;
        -webkit-overflow-scrolling: touch;
    }

    .peserta-broadcast-modal__body p {
        margin: 0 0 0.85em;
    }

    .peserta-broadcast-modal__body p:last-child {
        margin-bottom: 0;
    }

    .peserta-broadcast-modal__body img {
        max-width: 100%;
        height: auto;
        border-radius: 10px;
    }

    .peserta-broadcast-modal__body table {
        width: 100%;
        border-collapse: collapse;
    }

    .peserta-broadcast-modal__body th,
    .peserta-broadcast-modal__body td {
        border: 1px solid #e2e8f0;
        padding: 8px 10px;
        vertical-align: top;
    }

    [data-skin="dark"] .peserta-broadcast-modal__body {
        background: linear-gradient(180deg, #1e293b 0%, #0f172a 100%);
        border-color: rgba(255, 255, 255, 0.12);
        color: #e2e8f0;
        box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.04);
    }

    [data-skin="dark"] .peserta-broadcast-modal__body th,
    [data-skin="dark"] .peserta-broadcast-modal__body td {
        border-color: rgba(255, 255, 255, 0.12);
    }

    [data-skin="dark"] .peserta-broadcast-modal__badge {
        background: rgba(99, 102, 241, 0.2);
        color: #c7d2fe;
    }

    [data-skin="dark"] .peserta-broadcast-modal__brand {
        color: #94a3b8;
    }

    .peserta-broadcast-modal__hint {
        margin: 14px 0 0;
        font-size: 12px;
        color: #64748b;
        line-height: 1.55;
    }

    [data-skin="dark"] .peserta-broadcast-modal__hint {
        color: #94a3b8;
    }

    .swal2-broadcast-popup .swal2-confirm {
        border-radius: 12px !important;
        padding: 0.65rem 1.35rem !important;
        font-weight: 700 !important;
    }

    .dropdown-profile-name {
        font-size: 0.8125rem;
        line-height: 1.3;
    }

    .avatar {
        position: relative;
        overflow: hidden;
    }

    .avatar img {
        border: 2px solid var(--gold-solid, #b08d48);
        transition: all 0.3s ease;
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 50%;
    }

    .avatar:hover img {
        border-color: var(--primary-dark, #0f172a);
        transform: scale(1.05);
    }

    /* Pastikan avatar tetap bulat di semua ukuran */
    .avatar,
    .avatar.online,
    .avatar-xl {
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .avatar img,
    .avatar.online img,
    .avatar-xl img {
        border-radius: 50%;
        display: block;
    }

    /* === RESPONSIVE HEADER === */
    @media (max-width: 991px) {
        .header-main {
            flex-wrap: wrap;
            padding: 0.75rem 1rem !important;
        }

        .header-main .d-flex {
            flex-wrap: wrap;
            gap: 0.5rem;
        }

        .header-main img {
            height: 50px !important;
        }

        .header-main h3 {
            font-size: 1rem;
            margin-left: 5px !important;
        }

        .header-main h3::after {
            width: 2px;
            height: 15px;
            margin-left: 5px;
        }

        /* Pastikan avatar tetap bulat di mobile */
        .avatar,
        .avatar.online {
            border-radius: 50% !important;
            overflow: hidden;
        }

        .avatar img,
        .avatar.online img {
            border-radius: 50% !important;
            width: 100% !important;
            height: 100% !important;
            object-fit: cover;
        }
    }

    @media (max-width: 576px) {
        .header-main {
            padding: 0.5rem 0.75rem !important;
        }

        .header-main img {
            height: 40px !important;
        }

        .header-main h3 {
            font-size: 0.85rem;
            display: none;
        }

        .dropdown-profile,
        .dropdown-skin {
            margin-left: 0.5rem !important;
        }

        /* Pastikan avatar tetap bulat di mobile kecil */
        .avatar,
        .avatar.online,
        .avatar-xl {
            border-radius: 50% !important;
            overflow: hidden;
        }

        .avatar img,
        .avatar.online img,
        .avatar-xl img {
            border-radius: 50% !important;
            width: 100% !important;
            height: 100% !important;
            object-fit: cover;
        }
    }
        .header-brand .header-title {
            margin-left: 10px;
            font-weight: 800;
            color: #0f172a;
        }

        [data-skin="dark"] .header-brand .header-title {
            color: #e5e7eb;
        }
    </style>

<div class="header-main px-3 px-lg-4">
    <a id="menuSidebar" href="#" class="menu-link me-3 me-lg-4"><i class="ri-menu-2-fill"></i></a>

    <!-- form-search -->
    <div class="me-auto d-flex align-items-center header-brand">
        <img src="{{ asset('theme/admin-dashbyte/dist/assets/img/SMART.png') }}" alt="Logo {{ config('app.name') }}" style="height: 50px; margin-right: 0;">
        <img src="{{ asset('theme/admin-dashbyte/dist/assets/img/logo.png') }}" alt="DPR RI" style="height: 50px; margin-right: 5px;">
        <h3 class="header-title">{{ config('app.name') }} DPR RI</h3>
    </div>
    <!-- form-search -->

    @php
        $isPegawai = session('auth_type') === 'pegawai';
        $displayName = $isPegawai
            ? ((session('pegawai') ?? [])['nama'] ?? (session('role_as') ?? [])['nama'] ?? session('pegawai_username', 'Pegawai'))
            : (auth()->user()->nama ?? auth()->user()->name ?? auth()->user()->email ?? 'Pengguna');

        $pegawaiHasRole = false;
        if ($isPegawai) {
            $pegawai = session('pegawai');
            $usernameFromSession = session('pegawai_username');
            $usernameFromPegawai = null;
            if (is_array($pegawai)) {
                $usernameFromPegawai = $pegawai['pengguna'] ?? null;
            } elseif (is_object($pegawai)) {
                $usernameFromPegawai = $pegawai->pengguna ?? null;
            }
            $username = $usernameFromSession ?: $usernameFromPegawai;
            if ($username) {
                $roleAsHeader = \Modules\Magang\App\Models\Smart\RoleAs::where('username', $username)
                    ->where('status', 1)
                    ->with('rolesInternal')
                    ->first();
                $pegawaiHasRole = $roleAsHeader
                    && $roleAsHeader->rolesInternal
                    && $roleAsHeader->rolesInternal->count() > 0;
            }
        }

        $pesertaAnnouncementUnread = 0;
        if (! $isPegawai && auth()->check() && auth()->user()->roles === 'peserta') {
            $pesertaIdForAnnounce = \Modules\Magang\App\Models\Magang\Peserta::query()->where('id_user', auth()->id())->value('id');
            if ($pesertaIdForAnnounce) {
                $pesertaAnnouncementUnread = \Modules\Magang\App\Models\BroadcastAnnouncementRecipient::query()
                    ->where('peserta_id', $pesertaIdForAnnounce)
                    ->whereNull('read_at')
                    ->count();
            }
        }
    @endphp

    @if(auth()->check() && ! $isPegawai && auth()->user()->roles === 'peserta')
        <div class="dropdown dropdown-peserta-announcements me-2 me-xl-3">
            <a href="#"
               id="pesertaAnnouncementNotifBtn"
               class="dropdown-link peserta-announcement-notif d-inline-flex align-items-center justify-content-center text-decoration-none {{ $pesertaAnnouncementUnread > 0 ? 'peserta-announcement-has-unread' : '' }}"
               data-bs-toggle="dropdown"
               data-bs-auto-close="outside"
               title="Pengumuman"
               aria-label="Pengumuman"
               aria-expanded="false"
               role="button">
                <i class="ri-notification-3-line"></i>
                <span id="pesertaAnnouncementBadge"
                      class="badge rounded-pill peserta-announcement-badge"
                      style="{{ $pesertaAnnouncementUnread < 1 ? 'display:none;' : '' }}">{{ $pesertaAnnouncementUnread > 99 ? '99+' : $pesertaAnnouncementUnread }}</span>
            </a>
            <div class="dropdown-menu dropdown-menu-end mt-10-f peserta-announcement-dropdown shadow-sm" id="pesertaAnnouncementDropdownMenu">
                <div class="px-3 py-2 border-bottom peserta-announcement-dropdown-header d-flex align-items-center justify-content-between">
                    <span class="fw-semibold small mb-0">Pengumuman</span>
                </div>
                <div id="pesertaAnnouncementList" class="peserta-announcement-list">
                    <div class="px-3 py-4 text-center text-muted small" id="pesertaAnnouncementListPlaceholder">Memuat…</div>
                </div>
            </div>
        </div>
    @endif

    <div class="dropdown dropdown-skin">
        <a href="" class="dropdown-link" data-bs-toggle="dropdown" data-bs-auto-close="outside"><i
                class="ri-settings-3-line"></i></a>
        <div class="dropdown-menu dropdown-menu-end mt-10-f">
            <label>Skin Mode</label>
            <nav id="skinMode" class="nav nav-skin">
                <a href="" class="nav-link active">Light</a>
                <a href="" class="nav-link">Dark</a>
            </nav>
            <hr>
            <label>Sidebar Skin</label>
            <nav id="sidebarSkin" class="nav nav-skin">
                <a href="" class="nav-link active">Default</a>
                <a href="" class="nav-link">Prime</a>
                <a href="" class="nav-link">Dark</a>
            </nav>
            <hr>
            <label>Ukuran</label>
            <nav id="ukuranwebsite" class="nav nav-skin">
                <a href="" class="nav-link active">Kecil</a>
                <a href="" class="nav-link">Sedang</a>
                <a href="" class="nav-link">Besar</a>
            </nav>

            {{-- <label>Direction</label>
            <nav id="layoutDirection" class="nav nav-skin">
                <a href="" class="nav-link active">LTR</a>
                <a href="" class="nav-link">RTL</a>
            </nav> --}}
        </div><!-- dropdown-menu -->
    </div><!-- dropdown -->

    <div class="dropdown dropdown-profile ms-3 ms-xl-4">
        <a href="" class="dropdown-link d-flex align-items-center gap-2 text-decoration-none" data-bs-toggle="dropdown" data-bs-auto-close="outside">
            <div class="avatar online">
                @php
                    $photoUrl = null;
                    $photoError = false;

                    // Cek jika user adalah peserta (BUKAN pegawai) dan punya pas_foto
                    if (!$isPegawai && auth()->check() && auth()->user()->roles == 'peserta') {
                        $peserta = \Modules\Magang\App\Models\Magang\Peserta::where('id_user', auth()->id())->with('lamaran')->first();
                        if ($peserta && $peserta->lamaran && $peserta->lamaran->pas_foto) {
                            $photoUrl = file_url($peserta->lamaran->pas_foto);
                        }
                    }

                    // Fallback ke informal_photo jika ada
                    if (!$photoUrl && session('informal_photo_name')) {
                        $photoUrl = 'https://berkas.dpr.go.id/portal/photos/' . session('informal_photo_name');
                    }

                    // Default photo
                    $defaultPhoto = asset('theme/admin-dashbyte/dist/assets/img/user.png');
                @endphp
                @if ($photoUrl)
                    <img src="{{ $photoUrl }}"
                         alt="Foto Profil"
                         onerror="this.onerror=null; this.src='{{ $defaultPhoto }}';">
                    @else
                    <img src="{{ $defaultPhoto }}" alt="Foto Profil">
                    @endif
            </div>
        </a>
        <div class="dropdown-menu dropdown-menu-end mt-10-f">
            <div class="dropdown-menu-body">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="avatar avatar-xl online flex-shrink-0">
                        @php
                            $photoUrl = null;
                            $isPegawai = session('auth_type') === 'pegawai';

                            // Cek jika user adalah peserta (BUKAN pegawai) dan punya pas_foto
                            if (!$isPegawai && auth()->check() && auth()->user()->roles == 'peserta') {
                                $peserta = \Modules\Magang\App\Models\Magang\Peserta::where('id_user', auth()->id())->with('lamaran')->first();
                                if ($peserta && $peserta->lamaran && $peserta->lamaran->pas_foto) {
                                    $photoUrl = file_url($peserta->lamaran->pas_foto);
                                }
                            }

                            // Fallback ke informal_photo jika ada
                            if (!$photoUrl && session('informal_photo_name')) {
                                $photoUrl = 'https://berkas.dpr.go.id/portal/photos/' . session('informal_photo_name');
                            }

                            // Default photo
                            $defaultPhoto = asset('theme/admin-dashbyte/dist/assets/img/user.png');
                        @endphp
                        @if ($photoUrl)
                            <img src="{{ $photoUrl }}"
                                 alt="Foto Profil"
                                 onerror="this.onerror=null; this.src='{{ $defaultPhoto }}';">
                        @else
                            <img src="{{ $defaultPhoto }}" alt="Foto Profil">
                        @endif
                    </div>
                    <div class="min-w-0">
                        <span class="dropdown-profile-name text-dark fw-semibold">{{ $displayName }}</span>
                        @php
                            $roleLabel = null;
                            if ($isPegawai) {
                                $roleLabel = data_get(session('role_as'), 'nama') ?: 'Pegawai';
                            } elseif (auth()->check()) {
                                $roleLabel = ucfirst(str_replace('_', ' ', auth()->user()->roles ?? 'Pengguna'));
                            }
                        @endphp
                        @if($roleLabel)
                            <div class="text-muted small">{{ $roleLabel }}</div>
                        @endif
                    </div>
                </div>

                <hr>
                @php
                    $changePasswordUrl = null;
                    if (!$isPegawai && auth()->check()) {
                        if (auth()->user()->roles === 'peserta') {
                            $changePasswordUrl = route('peserta.index', ['open_password' => 1]);
                        } elseif (auth()->user()->roles === 'admin') {
                            $changePasswordUrl = route('admin.index', ['open_password' => 1]);
                        } elseif (auth()->user()->roles === 'guru') {
                            $changePasswordUrl = route('pembimbing.index', ['open_password' => 1]);
                        }
                    }
                @endphp
                <nav class="nav">
                    @if(
                        (auth()->check() && !$isPegawai && in_array(auth()->user()->roles, ['admin','peserta','guru'], true))
                        || ($isPegawai && $pegawaiHasRole)
                    )
                    <a href="{{ $isPegawai ? route('profile.index') : (auth()->check() && in_array(auth()->user()->roles, ['peserta','guru']) ? route('user.profile.index') : route('profile.index')) }}"><i class="ri-user-settings-line"></i>Profile</a>
                    @endif
                    @if($changePasswordUrl)
                        <a href="{{ $changePasswordUrl }}"><i class="ri-lock-password-line"></i> Ganti Password</a>
                    @endif
                    <a href="{{ route('logout') }}"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="ri-logout-box-r-line"></i> Keluar
                    </a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </nav>
            </div><!-- dropdown-menu-body -->
        </div><!-- dropdown-menu -->
    </div><!-- dropdown -->
</div><!-- header-main -->
