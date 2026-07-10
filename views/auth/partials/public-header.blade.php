@php
    $umumEnabled = \Modules\Magang\App\Models\SiteSetting::lowonganUmumEnabled();
    $khususEnabled = \Modules\Magang\App\Models\SiteSetting::lowonganKhususEnabled();
@endphp
<header>
    <button type="button" class="mobile-menu-btn" id="btnOpenMobileMenu" aria-label="Buka menu">
        <i class="fas fa-bars"></i>
    </button>

    <a href="{{ route('Halaman awal') }}" class="logo-container">
        <img src="{{ asset('theme/admin-dashbyte/dist/assets/img/SMART.png') }}" alt="Logo {{ config('app.name') }}" style="height: 50px;">
        <img src="{{ asset('theme/admin-dashbyte/dist/assets/img/logo.png') }}" alt="DPR RI" style="height: 50px; margin-right: 5px;">
        <div class="logo-text">
            {{ config('app.name') }}
            <span>Setjen DPR RI</span>
        </div>
    </a>

    <nav>
        <div class="nav-links">
            <a href="https://www.dpr.go.id/" target="_blank">DPR RI</a>
            <a href="https://pusbangkom.dpr.go.id/" target="_blank">PUSBANGKOM</a>
            <a href="https://pusbangkom.dpr.go.id/kontak/index" target="_blank">Hubungi Kami</a>
            <a href="{{ route('lowongan.khusus') }}">Lowongan</a>
            <a href="{{ route('faq') }}">FAQ</a>
        </div>

        <div class="auth-buttons">
            @if (!Auth::check())
            <div class="dropdown">
                <a href="#" class="btn-action btn-outline">Pendaftaran <i class="fa-solid fa-chevron-down" style="font-size: 10px;"></i></a>
                <div class="dropdown-content">
                    @if($umumEnabled)
                    <a href="{{ route('register2') }}">Formulir Umum</a>
                    @else
                    <a href="#" onclick="pendaftaranUmumTutup(event)" style="opacity:0.55; cursor:not-allowed;">Formulir Umum</a>
                    @endif
                    <a href="{{ route('status') }}">Cek Status</a>
                </div>
            </div>
            @endif
            @if (Auth::check())
                @php
                    $user = Auth::user();
                    $isPegawai = session('auth_type') === 'pegawai';
                @endphp
                @if ($isPegawai)
                <a href="{{ route('pegawai.no-access') }}" class="btn-action btn-fill">Dashboard</a>
                @elseif ($user && isset($user->roles) && $user->roles == 'admin')
                <a href="{{ route('admin.index') }}" class="btn-action btn-fill">Dashboard</a>
                @elseif ($user && isset($user->roles) && $user->roles == 'guru')
                <a href="{{ route('admin.index') }}" class="btn-action btn-fill">Dashboard</a>
                @else
                <a href="{{ route('peserta.index') }}" class="btn-action btn-fill">Dashboard</a>
                @endif
            @else
                <div style="display:flex; align-items:center; gap:10px;">
                <a href="{{ route('login') }}" class="btn-action btn-fill">Masuk</a>
                </div>
            @endif
            <button type="button" id="skinToggleBtn" class="skin-toggle" onclick="toggleSkinMode()" aria-label="Ubah mode tampilan">
                <i class="ri-sun-fill"></i>
            </button>
        </div>
    </nav>

    <!-- Sidebar Overlay -->
    <div class="sidebar-overlay" id="mobileMenuOverlay" aria-hidden="true"></div>

    <!-- Mobile Menu -->
    <div class="mobile-menu" id="mobileMenu">
        <div class="mobile-menu-header">
            <h4>Menu</h4>
            <div style="display:flex; align-items:center; gap:8px;">
                <button type="button" class="skin-toggle" onclick="toggleSkinMode()" aria-label="Ubah mode tampilan (mobile)">
                    <i class="ri-sun-fill"></i>
                </button>
                <button type="button" class="mobile-menu-btn" id="btnCloseMobileMenu" aria-label="Tutup menu">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
        <div class="mobile-menu-content">
            <div class="mobile-nav-links">
                <a href="https://www.dpr.go.id/" target="_blank">
                    <i class="fas fa-building"></i>
                    <span>DPR RI</span>
                </a>
                <a href="https://pusbangkom.dpr.go.id/" target="_blank">
                    <i class="fas fa-graduation-cap"></i>
                    <span>PUSBANGKOM</span>
                </a>
                <a href="https://pusbangkom.dpr.go.id/kontak/index" target="_blank">
                    <i class="fas fa-envelope"></i>
                    <span>Hubungi Kami</span>
                </a>
                <a href="{{ route('lowongan.khusus') }}">
                    <i class="fas fa-briefcase"></i>
                    <span>Lowongan</span>
                </a>
                <a href="{{ route('faq') }}">
                    <i class="fas fa-circle-question"></i>
                    <span>FAQ</span>
                </a>
            </div>

            <div class="mobile-auth-buttons">
                @if (!Auth::check())
                <div style="display: flex; flex-direction: column; gap: 10px;">
                    @if($umumEnabled)
                    <a href="{{ route('register2') }}" class="btn-action btn-outline">
                        <i class="fas fa-file-signature"></i>
                        <span>Formulir Umum</span>
                    </a>
                    @else
                    <a href="#" onclick="pendaftaranUmumTutup(event)" class="btn-action btn-outline" style="opacity:0.55; cursor:not-allowed;">
                        <i class="fas fa-file-signature"></i>
                        <span>Formulir Umum</span>
                    </a>
                    @endif
                    <a href="{{ route('status') }}" class="btn-action btn-outline">
                        <i class="fas fa-search"></i>
                        <span>Cek Status</span>
                    </a>
                </div>
                @endif
                @if (Auth::check())
                    @php
                        $user = Auth::user();
                        $isPegawai = session('auth_type') === 'pegawai';
                        $pegawaiHasRole = false;

                        if ($isPegawai) {
                            // Cek langsung dari database: apakah ada di role_as dan ada relasi di pivot
                            $pegawai = session('pegawai');
                            $nip = $pegawai->nip ?? null;

                            if ($nip) {
                                $roleAs = \Modules\Magang\App\Models\Smart\RoleAs::where('nip', $nip)
                                    ->where('status', 1)
                                    ->with('rolesInternal')
                                    ->first();

                                // Cek apakah ada di role_as DAN ada relasi di pivot (roles_internal tidak kosong)
                                if ($roleAs && $roleAs->rolesInternal && $roleAs->rolesInternal->count() > 0) {
                                    $pegawaiHasRole = true;
                                }
                            }
                        }
                    @endphp
                    @if ($isPegawai)
                        @if ($pegawaiHasRole)
                        <a href="{{ route('pegawai.dashboard') }}" class="btn-action btn-fill">
                            <span>Dashboard</span>
                        </a>
                        @else
                        <a href="{{ route('pegawai.no-access') }}" class="btn-action btn-fill">
                            <span>Dashboard</span>
                        </a>
                        @endif
                        @elseif ($user && isset($user->roles) && $user->roles == 'admin')
                        <a href="{{ route('admin.index') }}" class="btn-action btn-fill">
                            <span>Dashboard</span>
                        </a>
                        @elseif ($user && isset($user->roles) && $user->roles == 'guru')
                        <a href="{{ route('pembimbing.index') }}" class="btn-action btn-fill">
                            <span>Dashboard</span>
                        </a>
                    @else
                    <a href="{{ route('peserta.index') }}" class="btn-action btn-fill">
                        <span>Dashboard</span>
                    </a>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="btn-action btn-fill">
                        <i class="fas fa-sign-in-alt"></i>
                        <span>Masuk</span>
                    </a>
                @endif
            </div>
        </div>
    </div>
</header>

@if(! $umumEnabled || ! $khususEnabled)
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function __pendaftaranTutup(pesan) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'info',
                title: 'Pendaftaran Ditutup',
                text: pesan,
                confirmButtonText: 'Mengerti',
                confirmButtonColor: '#b08d48'
            });
        } else {
            alert(pesan);
        }
    }
    function pendaftaranUmumTutup(e) {
        if (e) e.preventDefault();
        __pendaftaranTutup('Pendaftaran Umum ditutup sementara. Tunggu informasi selanjutnya.');
    }
    function pendaftaranKhususTutup(e) {
        if (e) e.preventDefault();
        __pendaftaranTutup('Pendaftaran Lowongan Khusus ditutup sementara. Tunggu informasi selanjutnya.');
    }
</script>
@endif
