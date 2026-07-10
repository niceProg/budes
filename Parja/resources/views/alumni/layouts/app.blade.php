@php
    // Login state portal alumni: guard utama keycloak-external; fallback keycloak
    // (staff DPR preview). Directive auth/guest bawaan Blade pakai guard DEFAULT
    // (keycloak) sehingga alumni keliru dianggap tamu — gunakan flag ini.
    $portalLoggedIn = auth()->guard('keycloak-external')->check() || auth()->guard('keycloak')->check();
    // L3 belum lengkap → kunci semua fitur kecuali "Update Profile"
    // (signal $shouldShowProfileCompletionModal di-share dari EnsureAlumniAccess).
    $l3Locked = $portalLoggedIn && ! empty($shouldShowProfileCompletionModal);
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Parja Alumni') | {{ config('app.name', 'ePublic') }}</title>

    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('template/dist/assets/img/favicon.png') }}">
    <link rel="stylesheet" href="{{ asset('template/dist/lib/remixicon/fonts/remixicon.css') }}">
    <link rel="stylesheet" href="{{ asset('template/dist/assets/css/style.min.css') }}">
    <link rel="stylesheet" href="{{ asset('SweetAlert/sweetalert2.min.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --parja-magenta: #bf0050;
            --parja-purple: #41174b;
            --parja-gold: #fbac18;
            --parja-soft: #f8eef5;
            --parja-white: #ffffff;
            --parja-text: #241a2b;
            --parja-muted: #695a72;
            --parja-border: rgba(65, 23, 75, 0.16);
            --parja-shadow: 0 14px 32px rgba(65, 23, 75, 0.12);
        }

        body {
            font-family: "Plus Jakarta Sans", "Segoe UI", sans-serif;
            background:
                radial-gradient(circle at 8% 10%, rgba(191, 0, 80, 0.18), transparent 28%),
                radial-gradient(circle at 90% 16%, rgba(251, 172, 24, 0.18), transparent 26%),
                linear-gradient(130deg, #fffafb 0%, #fffef8 46%, #f5eef9 100%);
            color: var(--parja-text);
        }

        .sidebar {
            background: linear-gradient(180deg, var(--parja-purple) 0%, var(--parja-magenta) 100%);
        }

        .sidebar-header {
            border-bottom-color: rgba(255, 255, 255, 0.14);
        }

        .sidebar-logo {
            color: var(--parja-white);
            font-weight: 800;
            letter-spacing: 0.3px;
        }

        .nav-label,
        .sidebar-body .nav-sidebar .nav-link,
        .sidebar-footer-body p,
        .sidebar-footer-body h6 a,
        .sidebar-footer-menu .nav a {
            color: rgba(255, 255, 255, 0.88);
        }

        .sidebar-body .nav-sidebar .nav-link.active,
        .sidebar-body .nav-sidebar .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.15);
            color: var(--parja-white);
        }

        /* Menu terkunci selama profil L3 belum lengkap. */
        .sidebar-body .nav-sidebar .nav-link.nav-locked,
        .sidebar-body .nav-sidebar .nav-sub-link.nav-locked {
            opacity: .45;
            pointer-events: none;
            cursor: not-allowed;
        }

        .sidebar-footer {
            border-top-color: rgba(255, 255, 255, 0.14);
        }

        .header-main {
            background: var(--parja-white);
            border-radius: 0;
            border: 1px solid var(--parja-border);
            box-shadow: 0 10px 24px rgba(103, 14, 35, 0.08);
        }

        .text-maroon {
            color: var(--parja-magenta);
        }

        .parja-panel {
            position: relative;
            overflow: hidden;
            border-radius: 22px;
            padding: 28px;
            background: linear-gradient(135deg, #ffffff 0%, #fff9ef 30%, #f8eef5 100%);
            border: 1px solid var(--parja-border);
            box-shadow: var(--parja-shadow);
        }

        .parja-panel::after {
            content: "";
            position: absolute;
            width: 220px;
            height: 220px;
            border-radius: 50%;
            right: -90px;
            top: -90px;
            background: radial-gradient(circle, rgba(191, 0, 80, 0.24) 0%, rgba(191, 0, 80, 0) 72%);
            pointer-events: none;
        }

        .parja-panel h1,
        .parja-panel h2,
        .parja-panel h3 {
            color: var(--parja-purple);
            font-weight: 800;
        }

        .parja-chip {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(251, 172, 24, 0.18);
            color: var(--parja-purple);
            border: 1px solid rgba(251, 172, 24, 0.46);
            border-radius: 999px;
            padding: 8px 14px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .parja-card {
            border-radius: 16px;
            border: 1px solid var(--parja-border);
            background: #fff;
            box-shadow: 0 10px 20px rgba(65, 23, 75, 0.08);
            padding: 20px;
            height: 100%;
        }

        .parja-card h4 {
            margin-bottom: 8px;
            color: var(--parja-purple);
            font-weight: 700;
        }

        .btn-danger {
            background-color: var(--parja-magenta);
            border-color: var(--parja-magenta);
        }

        .btn-danger:hover,
        .btn-danger:focus {
            background-color: #a60046;
            border-color: #a60046;
        }

        .btn-outline-danger {
            color: var(--parja-purple);
            border-color: rgba(65, 23, 75, 0.5);
        }

        .btn-outline-danger:hover,
        .btn-outline-danger:focus {
            color: #fff;
            background-color: var(--parja-purple);
            border-color: var(--parja-purple);
        }

        .bg-danger {
            background-color: var(--parja-magenta) !important;
        }

        .parja-card p {
            margin-bottom: 0;
            color: var(--parja-muted);
        }

        .main-footer {
            color: var(--parja-muted);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            border-top: 1px solid rgba(65, 23, 75, 0.12);
            padding-top: 14px;
            font-size: 0.94rem;
        }

        .main-footer span:last-child {
            font-weight: 600;
        }

        @media (max-width: 575px) {
            .parja-panel {
                padding: 22px;
            }

            .main-footer {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>
    @stack('styles')
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-header">
            <a href="{{ route('alumni.feed.index') }}" class="sidebar-logo">Parja Alumni</a>
        </div>

        <div id="sidebarMenu" class="sidebar-body">
            <div class="nav-group show">
                <a href="#" class="nav-label">Menu Utama</a>
                <ul class="nav nav-sidebar">

                    @if ($portalLoggedIn)
                    {{-- Menu khusus alumni yang sudah login --}}
                    <li class="nav-item">
                        <a href="{{ $l3Locked ? '#' : route('alumni.home') }}"
                           class="nav-link {{ $l3Locked ? 'nav-locked' : (request()->routeIs('alumni.home') ? 'active' : '') }}"
                           @if ($l3Locked) title="Lengkapi profil dulu" aria-disabled="true" @endif>
                            <i class="{{ $l3Locked ? 'ri-lock-2-line' : 'ri-home-8-line' }}"></i>
                            <span>Beranda Parja Alumni</span>
                        </a>
                    </li>
                    @endif



                    <li class="nav-item">
                        <a href="{{ $l3Locked ? '#' : route('alumni.kegiatan.index') }}"
                           class="nav-link {{ $l3Locked ? 'nav-locked' : (request()->routeIs('alumni.kegiatan.*') ? 'active' : '') }}"
                           @if ($l3Locked) title="Lengkapi profil dulu" aria-disabled="true" @endif>
                            <i class="{{ $l3Locked ? 'ri-lock-2-line' : 'ri-calendar-event-line' }}"></i>
                            <span>Kegiatan & Info Alumni</span>
                        </a>
                    </li>

                    @if ($portalLoggedIn)
                    <li class="nav-item">
                        <a href="#" class="nav-link has-sub {{ ($l3Locked || request()->routeIs('alumni.profile', 'alumni.profile.*')) ? 'active show' : '' }}">
                            <i class="ri-user-3-line"></i>
                            <span>Profil Alumni</span>
                        </a>
                        <nav class="nav nav-sub {{ ($l3Locked || request()->routeIs('alumni.profile', 'alumni.profile.*')) ? 'active show' : '' }}">
                            <a href="{{ $l3Locked ? '#' : route('alumni.profile') }}" class="nav-sub-link {{ $l3Locked ? 'nav-locked' : (request()->routeIs('alumni.profile') ? 'active' : '') }}" @if ($l3Locked) title="Lengkapi profil dulu" @endif>My Profile</a>
                            <a href="{{ route('alumni.profile.edit') }}" class="nav-sub-link {{ request()->routeIs('alumni.profile.edit', 'alumni.profile.update') ? 'active' : '' }}">Update Profile @if ($l3Locked)<span class="badge bg-warning text-dark ms-1" style="font-size:.6rem;">Wajib</span>@endif</a>
                        </nav>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link {{ $l3Locked ? 'nav-locked' : 'has-sub' }} {{ request()->routeIs('alumni.directory-tracer', 'alumni.direktori', 'alumni.direktori.view') ? 'active show' : '' }}"
                           @if ($l3Locked) title="Lengkapi profil dulu" aria-disabled="true" @endif>
                            <i class="{{ $l3Locked ? 'ri-lock-2-line' : 'ri-compass-discover-line' }}"></i>
                            <span>Direktori Alumni</span>
                        </a>
                        @unless ($l3Locked)
                        <nav class="nav nav-sub {{ request()->routeIs('alumni.directory-tracer', 'alumni.direktori', 'alumni.direktori.view') ? 'active show' : '' }}">
                            <a href="{{ route('alumni.directory-tracer') }}" class="nav-sub-link {{ request()->routeIs('alumni.directory-tracer') ? 'active' : '' }}">Statistik Alumni</a>
                            <a href="{{ route('alumni.direktori') }}" class="nav-sub-link {{ request()->routeIs('alumni.direktori', 'alumni.direktori.view') ? 'active' : '' }}">Cari Alumni</a>
                        </nav>
                        @endunless
                    </li>
                    {{-- <li class="nav-item">
                        <a href="{{ route('alumni.chat.index') }}" class="nav-link {{ request()->routeIs('alumni.chat.*') ? 'active' : '' }}">
                            <i class="ri-chat-3-line"></i>
                            <span>Grup Chat</span>
                        </a>
                    </li> --}}

                    {{-- Data Survei --}}
                    <li class="nav-item {{ (! $l3Locked && request()->routeIs('alumni.survei.*')) ? 'active' : '' }}">
                        <a href="{{ $l3Locked ? '#' : route('alumni.survei.index') }}"
                            class="nav-link {{ $l3Locked ? 'nav-locked' : (request()->routeIs('alumni.survei.*') ? 'active' : '') }}"
                            @if ($l3Locked) title="Lengkapi profil dulu" aria-disabled="true" @endif>
                            <i class="{{ $l3Locked ? 'ri-lock-2-line' : 'ri-survey-line' }}"></i>
                            <span>Data Survei</span>
                        </a>
                    </li>
                    @endif


                    @if (! $portalLoggedIn)
                    {{-- Menu untuk tamu (belum login) --}}
                    <li class="nav-item" style="margin-top: 16px;">
                        <a href="#" class="nav-label" style="font-size:0.72rem;">AKUN</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('alumni.login') }}" class="nav-link">
                            <i class="ri-login-box-line"></i>
                            <span>Masuk Alumni</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('alumni.register') }}" class="nav-link {{ request()->routeIs('alumni.register') ? 'active' : '' }}">
                            <i class="ri-user-add-line"></i>
                            <span>Daftar Alumni</span>
                        </a>
                    </li>
                    @endif

                </ul>
            </div>
        </div>

        @if ($portalLoggedIn)
        <div class="sidebar-footer">
            <div class="sidebar-footer-top">
                <div class="sidebar-footer-thumb">
                    <img src="{{ asset('template/dist/assets/img/img1.jpg') }}" alt="">
                </div>
                <div class="sidebar-footer-body">
                    <h6><a href="#">{{ (auth()->guard('keycloak-external')->user()?->name ?? auth()->guard('keycloak')->user()?->name ?? '') }}</a></h6>
                    <p>{{ ucfirst(collect(auth()->guard('keycloak-external')->user()?->roles ?? [])->first() ?? 'Alumni') }}</p>
                </div>
                <a id="sidebarFooterMenu" href="" class="dropdown-link"><i class="ri-arrow-down-s-line"></i></a>
            </div>

            <div class="sidebar-footer-menu">
                <nav class="nav">
                    <a href="{{ route('dashboard') }}"><i class="ri-apps-line"></i> Kembali ke Dashboard</a>
                </nav>
                <hr>
                <nav class="nav">
                    <form method="POST" action="{{ route('logout') }}" id="alumni-sidebar-logout-form">
                        @csrf
                    </form>
                    <a href="#" onclick="document.getElementById('alumni-sidebar-logout-form').submit();">
                        <i class="ri-logout-box-r-line"></i> Keluar
                    </a>
                </nav>
            </div>
        </div>
        @endif

        @if (! $portalLoggedIn)
        {{-- Sidebar footer untuk tamu --}}
        <div class="sidebar-footer" style="padding: 16px 20px;">
            <p style="font-size:0.78rem; color:rgba(255,255,255,0.65); margin-bottom:10px; text-align:center;">Bergabunglah dengan komunitas alumni Parja!</p>
            <a href="{{ route('alumni.register') }}" style="display:block; background:rgba(255,255,255,0.18); color:#fff; border-radius:8px; padding:8px 14px; text-align:center; font-size:0.84rem; font-weight:600; margin-bottom:8px; text-decoration:none;">
                <i class="ri-user-add-line"></i> Daftar Alumni
            </a>
            <a href="{{ route('keycloak.login') }}" style="display:block; background:transparent; color:rgba(255,255,255,0.75); border-radius:8px; padding:6px 14px; text-align:center; font-size:0.82rem; text-decoration:none;">
                Staff DPR? Masuk via SSO
            </a>
        </div>
        @endif
    </div>

    <div class="main main-app p-3 p-lg-4">
        <div class="header-main px-3 px-lg-4 mb-4">
            <a id="menuSidebar" href="#" class="menu-link me-3 me-lg-4"><i class="ri-menu-2-fill"></i></a>

            <div class="me-auto">
                <h5 class="mb-0 fw-semibold text-maroon">@yield('page-title', 'Parja Alumni')</h5>
            </div>

            @if ($portalLoggedIn)
            <div class="dropdown dropdown-profile ms-3 ms-xl-4">
                <a href="#" class="dropdown-link" data-bs-toggle="dropdown" data-bs-auto-close="outside">
                    <div class="avatar avatar-online">
                        <span class="avatar-initial rounded-0 bg-danger">{{ strtoupper(substr((auth()->guard('keycloak-external')->user()?->name ?? auth()->guard('keycloak')->user()?->name ?? ''), 0, 1)) }}</span>
                    </div>
                </a>
                <div class="dropdown-menu dropdown-menu-end mt-10-f">
                    <div class="dropdown-menu-body">
                        <h6 class="mb-1">{{ (auth()->guard('keycloak-external')->user()?->name ?? auth()->guard('keycloak')->user()?->name ?? '') }}</h6>
                        <p class="fs-sm text-secondary mb-0">{{ (auth()->guard('keycloak-external')->user()?->email ?? auth()->guard('keycloak')->user()?->email ?? '') }}</p>
                        <hr class="my-3">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-danger btn-sm w-100">
                                <i class="ri-logout-box-r-line me-1"></i> Keluar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endif

            @if (! $portalLoggedIn)
            <div class="d-flex gap-2 ms-3">
                <a href="{{ route('alumni.login') }}" class="btn btn-sm fw-semibold" style="background:var(--parja-magenta);color:#fff;border-radius:50px;padding:6px 18px;font-size:0.84rem;">Masuk Alumni</a>
                <a href="{{ route('alumni.register') }}" class="btn btn-sm fw-semibold" style="background:transparent;color:var(--parja-purple);border:1.5px solid var(--parja-purple);border-radius:50px;padding:6px 18px;font-size:0.84rem;">Daftar Alumni</a>
            </div>
            @endif
        </div>

        @if (session('error'))
            <div class="alert alert-warning mb-3" role="alert">
                {{ session('error') }}
            </div>
        @endif

        @if (session('success'))
            <div class="alert alert-success mb-3" role="alert">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger mb-3" role="alert">
                <strong>Validasi gagal:</strong>
                <ul class="mb-0 mt-2 ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Banner persistent "Lengkapi profile" — di atas konten, hilang otomatis
             setelah profile_completed=1 (signal dari middleware EnsureAlumniAccess). --}}
        @include('parja::alumni._profile-completion-banner')

        @yield('content')

        <div class="main-footer mt-5">
            <span>ePublic - PUSTEKINFO - DPR RI &copy; 2026</span>
        </div>
    </div>

    <script src="{{ asset('template/dist/lib/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('template/dist/lib/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('template/dist/lib/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('template/dist/assets/js/script.js') }}"></script>
    <script src="{{ asset('SweetAlert/sweetalert2.all.min.js') }}"></script>

    {{-- L3 popup modal: auto-show 1x per session jika profile_completed != 1 --}}
    @include('parja::alumni._profile-completion-modal')

    @stack('scripts')
</body>
</html>
