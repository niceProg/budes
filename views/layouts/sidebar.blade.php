<style>
    .sidebar {
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    .sidebar-header {
        background: var(--primary-dark, #0f172a);
        padding: 1.5rem;
        border-bottom: 1px solid rgba(255,255,255,0.1);
    }

    .sidebar-logo {
        font-weight: 800;
        font-size: 1.3rem;
        color: var(--gold-solid, #b08d48);
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .sidebar-logo::before {
        content: '';
        width: 4px;
        height: 30px;
        background: linear-gradient(135deg, #c5a059 0%, #8e6d2f 100%);
        border-radius: 2px;
    }

    .nav-label {
        font-weight: 700;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: var(--text-muted, #64748b);
        padding: 0.75rem 1.5rem;
    }

    .nav-link {
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .nav-link:hover {
        background: rgba(176, 141, 72, 0.1);
        color: var(--gold-solid, #b08d48);
    }

    .nav-link.active {
        background: linear-gradient(90deg, rgba(197, 160, 89, 0.15) 0%, rgba(197, 160, 89, 0.05) 100%);
        color: var(--gold-solid, #b08d48);
        border-left: 3px solid var(--gold-solid, #b08d48);
    }

    .sidebar-footer {
        border-top: 1px solid rgba(0,0,0,0.1);
        padding: 1rem;
    }

    .sidebar-footer-top h6 {
        font-weight: 700;
        color: var(--primary-dark, #0f172a);
    }

    .sidebar-footer-top p {
        font-size: 0.85rem;
        color: var(--text-muted, #64748b);
        text-transform: capitalize;
    }

    /* === RESPONSIVE SIDEBAR ===
       Tampil/sembunyi dikendalikan SEPENUHNYA oleh mekanisme tema
       (body.sidebar-show + .main-backdrop di script.js). Jangan menambah
       transform/handler sendiri di sini agar tidak desync dengan tema. */
    @media (max-width: 991px) {
        .sidebar-header {
            padding: 1rem;
        }

        .sidebar-logo {
            font-size: 1.1rem;
        }

        .nav-label {
            padding: 0.5rem 1rem;
            font-size: 0.7rem;
        }

        .nav-link {
            padding: 0.75rem 1rem;
            font-size: 0.9rem;
        }

        .sidebar-footer {
            padding: 0.75rem;
        }
    }

    @media (max-width: 576px) {
        .sidebar {
            width: 100%;
            /* tema menyembunyikan dengan left:-290px; untuk drawer full-width
               perlu -100% agar benar-benar keluar layar saat tertutup */
            left: -100%;
            z-index: 1050;
        }
        body.sidebar-show .sidebar {
            left: 0;
        }

        .sidebar-logo {
            font-size: 1rem;
        }

        .nav-link span {
            font-size: 0.85rem;
        }
    }
</style>

<div class="sidebar">
    <div class="sidebar-header">
        @php
            $isPegawai = session('auth_type') === 'pegawai';
            $pegawaiHasRole = false;

            if ($isPegawai) {
                $pegawai = session('pegawai');

                // Ambil username dari session (utama) atau dari data pegawai
                $usernameFromSession = session('pegawai_username');
                $usernameFromPegawai = null;
                if (is_array($pegawai)) {
                    $usernameFromPegawai = $pegawai['pengguna'] ?? null;
                } else {
                    $usernameFromPegawai = $pegawai->pengguna ?? null;
                }
                $username = $usernameFromSession ?: $usernameFromPegawai;

                if ($username) {
                    $roleAs = \Modules\Magang\App\Models\Smart\RoleAs::where('username', $username)
                        ->where('status', 1)
                        ->with('rolesInternal')
                        ->first();
                    $pegawaiHasRole = $roleAs && $roleAs->rolesInternal && $roleAs->rolesInternal->count() > 0;
                }
            }
        @endphp
        @if($isPegawai && $pegawaiHasRole)
            <a href="{{ route('pegawai.dashboard') }}" class="sidebar-logo">{{ config('app.name') }}</a>
        @elseif(auth()->check() && !$isPegawai && in_array(auth()->user()->roles, ['admin', 'superadmin'], true))
            <a href="{{ route('admin.index') }}" class="sidebar-logo">{{ config('app.name') }}</a>
        @elseif(auth()->check() && !$isPegawai && auth()->user()->roles == 'peserta')
            <a href="{{ route('peserta.index') }}" class="sidebar-logo">{{ config('app.name') }}</a>
        @else
            <a href="{{ route('login') }}" class="sidebar-logo">{{ config('app.name') }}</a>
        @endif
    </div>
    <div id="sidebarMenu" class="sidebar-body">
        @php
            $isPegawai = session('auth_type') === 'pegawai';
            $pegawaiHasRole = false;
            $roleAs = null;
            $pegawaiRoles = [];
            $pegawaiRolesNormalized = [];
            $pegawaiIsAdminInternal = false;
            $pegawaiIsSuperadminInternal = false;
            $pegawaiIsAtasan = false;
            $pegawaiIsMentor = false;
            $pegawaiIsVerifikator = false;

            if ($isPegawai) {
                // Cek langsung dari database: apakah username ada di role_as dan ada relasi di pivot
                $pegawai = session('pegawai');

                $usernameFromSession = session('pegawai_username');
                $usernameFromPegawai = null;
                if (is_array($pegawai)) {
                    $usernameFromPegawai = $pegawai['pengguna'] ?? null;
                } else {
                    $usernameFromPegawai = $pegawai->pengguna ?? null;
                }
                $username = $usernameFromSession ?: $usernameFromPegawai;
                
                if ($username) {
                    $roleAs = \Modules\Magang\App\Models\Smart\RoleAs::where('username', $username)
                        ->where('status', 1)
                        ->with('rolesInternal')
                        ->first();
                    
                    if ($roleAs && $roleAs->rolesInternal && $roleAs->rolesInternal->count() > 0) {
                        $pegawaiHasRole = true;
                        $pegawaiRoles = $roleAs->rolesInternal->pluck('nama')->toArray();
                        $pegawaiRolesNormalized = array_map(function ($name) {
                            return preg_replace('/[^a-z0-9]/', '', strtolower(trim((string) $name)));
                        }, $pegawaiRoles);

                        // 'rootadmin' = akses tertinggi: tampilkan SEMUA menu (semua role,
                        // semua satuan kerja). Perlakukan seolah memiliki seluruh role internal.
                        $pegawaiIsRootadmin = in_array('rootadmin', $pegawaiRolesNormalized, true);

                        $pegawaiIsAdminInternal = $pegawaiIsRootadmin || in_array('admin', $pegawaiRolesNormalized, true);
                        $pegawaiIsSuperadminInternal = $pegawaiIsRootadmin || in_array('superadmin', $pegawaiRolesNormalized, true);
                        $pegawaiIsAtasan = $pegawaiIsRootadmin || in_array('atasan', $pegawaiRolesNormalized, true);
                        $pegawaiIsMentor = $pegawaiIsRootadmin || in_array('mentor', $pegawaiRolesNormalized, true);
                        $pegawaiIsVerifikator = $pegawaiIsRootadmin || in_array('verifikator', $pegawaiRolesNormalized, true);
                    }
                }
            }

            // Akses menu Lowongan untuk role satuan kerja (admin/verifikator/atasan internal):
            // dikontrol toggle di Pengaturan Sistem. Superadmin tidak terpengaruh.
            $satkerLowonganAccess = \Modules\Magang\App\Models\SiteSetting::satkerLowonganAccessEnabled();
            $isSatkerLockedUser = $isPegawai && $pegawaiHasRole && ! $pegawaiIsSuperadminInternal
                && ($pegawaiIsAdminInternal || $pegawaiIsAtasan || $pegawaiIsVerifikator);
            $canSeeLowonganMenu = ! $isSatkerLockedUser || $satkerLowonganAccess;
        @endphp

        @if(auth()->check() && !$isPegawai && in_array(auth()->user()->roles, ['admin', 'superadmin'], true))
            <div class="nav-group show">
                <a href="#" class="nav-label">Dashboard</a>
                <ul class="nav nav-sidebar">
                    <li class="nav-item">
                        <a href="{{ route('admin.index') }}" class="nav-link {{ request()->is('admin/dashboard*') ? ' active' : '' }}"><i
                                class="ri-dashboard-3-line"></i> <span>Dashboard</span></a>
                    </li>
                </ul>
            </div>
        @endif

        @if($isPegawai && $pegawaiHasRole)
            <div class="nav-group show">
                <a href="#" class="nav-label">Dashboard</a>
                <ul class="nav nav-sidebar">
                    <li class="nav-item">
                        <a href="{{ route('pegawai.dashboard') }}" class="nav-link {{ request()->is('admin/dashboard-pegawai') && !request()->is('admin/dashboard-pegawai/*') ? ' active' : '' }}"><i
                                class="ri-dashboard-3-line"></i> <span>Dashboard</span></a>
                    </li>
                </ul>
            </div>
        @endif

        @if(auth()->check() && !$isPegawai && auth()->user()->roles == 'peserta')
            <div class="nav-group show">
                <a href="#" class="nav-label">Dashboard</a>
                <ul class="nav nav-sidebar">
                    <li class="nav-item">
                        <a href="{{ url('/user/peserta') }}" class="nav-link {{ request()->is('user/peserta*') ? ' active' : '' }}"><i
                                class="ri-user-line"></i> <span>Dashboard</span></a>
                    </li>
                </ul>
            </div>
        @endif

        @if(auth()->check() && !$isPegawai && auth()->user()->roles == 'guru')
            <div class="nav-group show">
                <a href="#" class="nav-label">Dashboard</a>
                <ul class="nav nav-sidebar">
                    <li class="nav-item">
                        <a href="{{ route('pembimbing.index') }}" class="nav-link {{ request()->is('user/pembimbing*') ? ' active' : '' }}">
                            <i class="ri-dashboard-line"></i> <span>Dashboard</span>
                        </a>
                    </li>
                </ul>
            </div>
        @endif

        @if(
            (auth()->check() && !$isPegawai && in_array(auth()->user()->roles, ['admin','peserta','guru'], true))
            || ($isPegawai && $pegawaiHasRole)
        )
            <div class="nav-group show">
                <a href="#" class="nav-label">Akun</a>
                <ul class="nav nav-sidebar">
                    <li class="nav-item">
                        <a href="{{ $isPegawai ? route('profile.index') : (auth()->check() && in_array(auth()->user()->roles, ['peserta','guru']) ? route('user.profile.index') : route('profile.index')) }}" class="nav-link {{ request()->is('admin/profile') || request()->is('user/profile') ? ' active' : '' }}">
                            <i class="ri-user-settings-line"></i> <span>Profile</span>
                        </a>
                    </li>
                </ul>
            </div>
        @endif

        @if(auth()->check() && !$isPegawai && auth()->user()->roles == 'guru')
            <div class="nav-group show">
                <a href="#" class="nav-label">Menu Pembimbing</a>
                <ul class="nav nav-sidebar">
                    <li class="nav-item">
                        <a href="{{ route('pembimbing.absensi.index') }}" class="nav-link {{ request()->is('user/absensi-pembimbing*') ? ' active' : '' }}">
                            <i class="ri-calendar-check-line"></i> <span>Absensi</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('pembimbing.biodata.index') }}" class="nav-link {{ request()->is('user/biodata-peserta-pembimbing*') ? ' active' : '' }}">
                            <i class="ri-team-line"></i> <span>Biodata Peserta</span>
                        </a>
                    </li>
                </ul>
            </div>
        @endif

        @if(
            (auth()->check() && !$isPegawai && in_array(auth()->user()->roles, ['admin', 'superadmin'], true))
            ||
            ($isPegawai && $pegawaiHasRole && ($pegawaiIsAdminInternal || $pegawaiIsSuperadminInternal))
        )
            <div class="nav-group show">
                <a href="#" class="nav-label">Data Peserta</a>
                <ul class="nav nav-sidebar">

                    <li class="nav-item">
                        <a href="{{ url('/admin/lamaran') }}"
                        class="nav-link {{ request()->is('admin/lamaran*') ? ' active' : '' }}">
                            <i class="ri-file-list-3-line"></i> <span>Lamaran Magang</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ url('/admin/absensi') }}"
                        class="nav-link {{ request()->is('admin/absensi*') ? ' active' : '' }}">
                            <i class="ri-calendar-check-line"></i> <span>Rekap Absensi</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ url('/admin/biodata-peserta') }}"
                        class="nav-link {{ request()->is('admin/biodata-peserta') ? ' active' : '' }}">
                            <i class="ri-team-line"></i> <span>Biodata Peserta</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('biodata.lulus.index') }}"
                        class="nav-link {{ request()->is('admin/biodata-peserta-lulus*') ? ' active' : '' }}">
                            <i class="ri-graduation-cap-line"></i> <span>Biodata Peserta Lulus</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('biodata.banned.index') }}"
                        class="nav-link {{ request()->is('admin/biodata-peserta-banned*') ? ' active' : '' }}">
                            <i class="ri-user-forbid-line"></i> <span>Biodata Peserta Banned</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('guru.index') }}"
                        class="nav-link {{ request()->routeIs('guru.index') ? ' active' : '' }}">
                            <i class="ri-parent-line"></i> <span>Guru</span>
                        </a>
                    </li>
                </ul>
            </div>

            <div class="nav-group show">
                <a href="#" class="nav-label">Pegawai</a>
                <ul class="nav nav-sidebar">
                    <li class="nav-item">
                        <a href="{{ route('pegawai.internal.index') }}" class="nav-link {{ request()->routeIs('pegawai.internal.index') ? ' active' : '' }}">
                            <i class="ri-group-line"></i> <span>Pegawai</span>
                        </a>
                    </li>
                </ul>
            </div>

            <div class="nav-group show">
                <a href="#" class="nav-label">SMART (Sistem Magang Administratif, Responsif, dan Terintegrasi)</a>
                <ul class="nav nav-sidebar">
                    @if($canSeeLowonganMenu)
                    <li class="nav-item">
                        <a href="{{ route('lowongan.index') }}"
                        class="nav-link {{ request()->is('admin/lowongan*') ? ' active' : '' }}">
                            <i class="ri-briefcase-line"></i> <span>Lowongan</span>
                        </a>
                    </li>
                    @endif
                    <li class="nav-item">
                        <a href="{{ route('materi.index') }}"
                        class="nav-link {{ request()->is('admin/materi*') ? ' active' : '' }}">
                            <i class="ri-book-2-line"></i> <span>Materi</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('peraturan-pkl.edit') }}"
                        class="nav-link {{ request()->is('admin/peraturan-pkl*') ? ' active' : '' }}">
                            <i class="ri-file-text-line"></i> <span>Peraturan Magang</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('broadcast-email.index') }}"
                        class="nav-link {{ request()->is('admin/broadcast-email*') ? ' active' : '' }}">
                            <i class="ri-mail-send-line"></i> <span>Broadcast Email</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('broadcast-web.index') }}"
                        class="nav-link {{ request()->is('admin/broadcast-web*') ? ' active' : '' }}">
                            <i class="ri-notification-3-line"></i> <span>Pengumuman Web</span>
                        </a>
                    </li>
                    @if(
                        (!$isPegawai && auth()->check() && in_array(auth()->user()->roles, ['admin', 'superadmin'], true))
                        || ($isPegawai && $pegawaiIsSuperadminInternal)
                    )
                        <li class="nav-item">
                            <a href="{{ route('faq.index') }}"
                            class="nav-link {{ request()->is('admin/faq*') ? ' active' : '' }}">
                                <i class="ri-question-answer-line"></i> <span>FAQ</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('security-settings.edit') }}"
                            class="nav-link {{ request()->is('admin/pengaturan-keamanan*') ? ' active' : '' }}">
                                <i class="ri-settings-4-line"></i> <span>Pengaturan</span>
                            </a>
                        </li>
                    @endif
                </ul>
            </div>

            <div class="nav-group show">
                <a href="#" class="nav-label">Master Data</a>
                <ul class="nav nav-sidebar">
                    <li class="nav-item">
                        <a href="{{ route('agama.index') }}"
                        class="nav-link {{ request()->is('admin/agama*') ? ' active' : '' }}">
                            <i class="ri-book-open-line"></i> <span>Agama</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('pendidikan.index') }}"
                        class="nav-link {{ request()->is('admin/pendidikan*') ? ' active' : '' }}">
                            <i class="ri-school-line"></i> <span>Pendidikan</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('minat.index') }}"
                        class="nav-link {{ request()->is('admin/minat*') ? ' active' : '' }}">
                            <i class="ri-lightbulb-flash-line"></i> <span>Minat</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('satker.index') }}"
                        class="nav-link {{ request()->is('admin/satker*') ? ' active' : '' }}">
                            <i class="ri-building-line"></i> <span>Satuan Kerja</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('roles-internal.index') }}"
                        class="nav-link {{ request()->is('admin/roles-internal*') ? ' active' : '' }}">
                            <i class="ri-user-settings-line"></i> <span>Roles Internal</span>
                        </a>
                    </li>
                    @if(! $isPegawai || $pegawaiIsSuperadminInternal)
                        <li class="nav-item">
                            <a href="{{ route('master.universitas.index') }}"
                            class="nav-link {{ request()->is('admin/master/universitas') ? ' active' : '' }}">
                                <i class="ri-bank-line"></i> <span>Universitas</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('master.sekolah.index') }}"
                            class="nav-link {{ request()->is('admin/master/sekolah') ? ' active' : '' }}">
                                <i class="ri-school-line"></i> <span>Sekolah</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('master.institutions.index') }}"
                            class="nav-link {{ request()->is('admin/master/sekolah-universitas*') ? ' active' : '' }}">
                                <i class="ri-refresh-line"></i> <span>Perbarui Sekolah &amp; Universitas</span>
                            </a>
                        </li>
                    @endif
                </ul>
            </div>
        @endif

        @if($isPegawai && $pegawaiHasRole && $pegawaiIsAtasan)
            <div class="nav-group show">
                <a href="#" class="nav-label">Menu Atasan</a>
                <ul class="nav nav-sidebar">
                    <li class="nav-item">
                        <a href="{{ route('atasan.lamaran.index') }}"
                        class="nav-link {{ request()->is('admin/atasan-lamaran*') ? ' active' : '' }}">
                            <i class="ri-file-list-3-line"></i> <span>Lamaran Magang</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('absensi.atasan.index') }}" class="nav-link {{ request()->is('admin/atasan-absensi*') ? ' active' : '' }}">
                            <i class="ri-calendar-check-line"></i> <span>Data Absensi</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('atasan.biodata.index') }}" class="nav-link {{ request()->is('admin/atasan/biodata-peserta*') ? ' active' : '' }}">
                            <i class="ri-team-line"></i> <span>Biodata Peserta</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('atasan.biodata.lulus.index') }}" class="nav-link {{ request()->is('admin/atasan/biodata-peserta-lulus*') ? ' active' : '' }}">
                            <i class="ri-graduation-cap-line"></i> <span>Biodata Peserta Lulus</span>
                        </a>
                    </li>
                    @if($satkerLowonganAccess)
                    <li class="nav-item">
                        <a href="{{ route('lowongan.index') }}" class="nav-link {{ request()->is('admin/lowongan*') ? ' active' : '' }}">
                            <i class="ri-briefcase-line"></i> <span>Lowongan</span>
                        </a>
                    </li>
                    @endif
                </ul>
            </div>
        @endif

        @if($isPegawai && $pegawaiHasRole && $pegawaiIsMentor)
            <div class="nav-group show">
                <a href="#" class="nav-label">Menu Mentor</a>
                <ul class="nav nav-sidebar">
                    <li class="nav-item">
                        <a href="{{ url('/admin/mentor-absensi') }}" class="nav-link {{ request()->is('admin/mentor-absensi*') ? ' active' : '' }}">
                            <i class="ri-calendar-check-line"></i> <span>Data Absensi</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('mentor.biodata.index') }}" class="nav-link {{ request()->is('admin/mentor/biodata-peserta*') ? ' active' : '' }}">
                            <i class="ri-team-line"></i> <span>Biodata Peserta</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('mentor.biodata.lulus.index') }}" class="nav-link {{ request()->is('admin/mentor/biodata-peserta-lulus*') ? ' active' : '' }}">
                            <i class="ri-graduation-cap-line"></i> <span>Biodata Peserta Lulus</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('materi.index') }}"
                        class="nav-link {{ request()->is('admin/materi*') ? ' active' : '' }}">
                            <i class="ri-book-2-line"></i> <span>Materi</span>
                        </a>
                    </li>
                </ul>
            </div>
        @endif

        @if($isPegawai && $pegawaiHasRole && $pegawaiIsVerifikator)
            <div class="nav-group show">
                <a href="#" class="nav-label">Menu Verifikator</a>
                <ul class="nav nav-sidebar">
                    <li class="nav-item">
                        <a href="{{ route('verifikator.biodata.index') }}" class="nav-link {{ request()->is('admin/verifikator/biodata-peserta*') ? ' active' : '' }}">
                            <i class="ri-team-line"></i> <span>Biodata Peserta</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('verifikator.biodata.lulus.index') }}" class="nav-link {{ request()->is('admin/verifikator/biodata-peserta-lulus*') ? ' active' : '' }}">
                            <i class="ri-graduation-cap-line"></i> <span>Biodata Peserta Lulus</span>
                        </a>
                    </li>
                    @if($satkerLowonganAccess)
                    <li class="nav-item">
                        <a href="{{ route('lowongan.index') }}" class="nav-link {{ request()->is('admin/lowongan*') ? ' active' : '' }}">
                            <i class="ri-briefcase-line"></i> <span>Lowongan</span>
                        </a>
                    </li>
                    @endif
                    <li class="nav-item">
                        <a href="{{ route('materi.index') }}"
                        class="nav-link {{ request()->is('admin/materi*') ? ' active' : '' }}">
                            <i class="ri-book-2-line"></i> <span>Materi</span>
                        </a>
                    </li>
                </ul>
            </div>
        @endif



        @if(auth()->check() && !$isPegawai && auth()->user()->roles == 'peserta')
            @php
                $peserta = \Modules\Magang\App\Models\Magang\Peserta::where('id_user', auth()->user()->id)->first();
                $showAbsensiMenu = $peserta ? true : false; // Tampilkan menu untuk semua status peserta
            @endphp
            @if($showAbsensiMenu)
                <div class="nav-group show">
                    <a href="#" class="nav-label">ABSENSI</a>
                    <ul class="nav nav-sidebar">
                        <li class="nav-item">
                            <a href="{{ url('/user/absensi') }}"
                            class="nav-link {{ request()->is('user/absensi*') ? ' active' : '' }}">
                                <i class="ri-calendar-check-line"></i> <span>Absensi</span>
                            </a>
                        </li>


                        <li class="nav-item">
                            <a href="{{ url('/user/peserta-absensi') }}"
                            class="nav-link {{ request()->is('user/peserta-absensi*') ? ' active' : '' }}">
                                <i class="ri-file-list-line"></i> <span>Rekap Absensi</span>
                            </a>
                        </li>
                    </ul>
                </div>
            @endif
        @endif

        @if(auth()->check() && !$isPegawai && auth()->user()->roles == 'peserta')
            @php
                $peserta = $peserta ?? \Modules\Magang\App\Models\Magang\Peserta::where('id_user', auth()->user()->id)->first();
                $pesertaUnitId = $peserta?->id_satker;

                $collection = \Modules\Magang\App\Models\Materi::query()
                    ->where('status', 1)
                    ->where('is_publish', true)
                    ->where(function ($q) use ($pesertaUnitId) {
                        $q->whereNull('id_satker');
                        if ($pesertaUnitId) {
                            $q->orWhere('id_satker', $pesertaUnitId);
                        }
                    })
                    ->orderBy('tanggal_publish')
                    ->get(['id', 'judul', 'navbar_nama', 'navbar_icon', 'id_satker', 'tanggal_publish']);
            @endphp

            @if($collection->count())
                <div class="nav-group show">
                    <a href="#" class="nav-label">MATERI</a>
                    <ul class="nav nav-sidebar">
                        @foreach ($collection as $item)
                            <li class="nav-item">
                                <a href="{{ route('materi.peserta.show', $item->id) }}"
                                   class="nav-link {{ request()->is('user/materi/peserta/'.$item->id) ? ' active' : '' }}">
                                    <i class="{{ $item->navbar_icon ?: 'ri-book-line' }}"></i>
                                    <span>{{ $item->navbar_nama ?: $item->judul }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        @endif 

        @if(auth()->check() && !$isPegawai && auth()->user()->roles == 'peserta')
            <div class="nav-group show">
                <a href="#" class="nav-label">SELESAI</a>
                <ul class="nav nav-sidebar">
                    <li class="nav-item">
                        <a href="{{ route('pengumpulan-nilai.index') }}"
                        class="nav-link {{ request()->is('user/pengumpulan-nilai*') ? ' active' : '' }}">
                            <i class="ri-star-line"></i> <span>Pengumpulan Nilai</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('laporan.index') }}"
                        class="nav-link {{ request()->is('user/pengumpulan-laporan*') ? ' active' : '' }}">
                            <i class="ri-book-line"></i> <span>Pengumpulan Laporan</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('testimoni.index') }}"
                            class="nav-link {{ request()->is('user/pengumpulan-testimoni*') ? ' active' : '' }}">
                            <i class="ri-chat-quote-line"></i> <span>Testimoni</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('sertifikat.index') }}" class="nav-link {{ request()->is('user/sertifikat*') ? ' active' : '' }}">
                            <i class="ri-award-line"></i> <span>Sertifikat</span>
                        </a>
                    </li>
                </ul>
            </div>
        @endif
    </div>
</div>
