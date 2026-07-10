<div class="sidebar">
    <div class="sidebar-header">
        <a href="{{ route('parja.dashboard') }}" class="sidebar-logo">ePublic</a>
    </div>

    <div id="sidebarMenu" class="sidebar-body">

        {{-- ====================================================== --}}
        {{-- SEKSI: PARJA --}}
        {{-- Visible only when the user's active (or assigned) role --}}
        {{-- grants Parja-side access. Active role takes precedence. --}}
        {{--                                                          --}}
        {{-- HIDDEN per atasan 18-05-2026 #10: seluruh menu Parja     --}}
        {{-- (calon parja) di-hide dulu karena belum waktunya diurus, --}}
        {{-- fokus alumni dulu. Restore: hapus `false &&` di @if.     --}}
        {{-- RE-ENABLED 04-06-2026 untuk keperluan presentasi.        --}}
        {{-- ====================================================== --}}
        @if (rbac_can_view_parja_section())
            <div class="nav-group show">
                <a href="#" class="nav-label">Parja</a>
                <ul class="nav nav-sidebar">

                    {{-- Dashboard --}}
                    <li class="nav-item">
                        <a href="{{ route('parja.dashboard') }}"
                            class="nav-link {{ request()->routeIs('parja.dashboard') ? 'active' : '' }}">
                            <i class="ri-pie-chart-2-line"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>

                    {{-- Data Survei --}}
                    <li class="nav-item {{ request()->routeIs('parja.survei.*') ? 'active' : '' }}">
                        <a href="{{ route('parja.survei.index') }}"
                            class="nav-link {{ request()->routeIs('parja.survei.*') ? 'active' : '' }}">
                            <i class="ri-survey-line"></i>
                            <span>Data Survei</span>
                        </a>
                    </li>

                    {{-- Data Website --}}
                    <li
                        class="nav-item {{ request()->routeIs('parja.konten_statis.*', 'parja.konten_setting.*', 'parja.poster.*', 'parja.album_foto.*', 'parja.publikasi.*', 'parja.jadwal.*') ? 'active' : '' }}">
                        <a href="#"
                            class="nav-link has-sub {{ request()->routeIs('parja.konten_statis.*', 'parja.konten_setting.*', 'parja.poster.*', 'parja.album_foto.*', 'parja.publikasi.*', 'parja.jadwal.*') ? 'active show' : '' }}">
                            <i class="ri-global-line"></i> <span>Data Website</span>
                        </a>
                        <nav
                            class="nav nav-sub {{ request()->routeIs('parja.konten_statis.*', 'parja.konten_setting.*', 'parja.poster.*', 'parja.album_foto.*', 'parja.publikasi.*', 'parja.jadwal.*') ? 'active show' : '' }}">
                            <a href="{{ route('parja.konten_statis.index') }}"
                                class="nav-sub-link {{ request()->routeIs('parja.konten_statis.*') ? 'active' : '' }}">Daftar
                                Konten Statis</a>
                            <a href="{{ route('parja.konten_setting.index') }}"
                                class="nav-sub-link {{ request()->routeIs('parja.konten_setting.*') ? 'active' : '' }}">Daftar
                                Konten Setting</a>
                            <a href="{{ route('parja.poster.index') }}"
                                class="nav-sub-link {{ request()->routeIs('parja.poster.*') ? 'active' : '' }}">Daftar
                                Poster</a>
                            <a href="{{ route('parja.album_foto.index') }}"
                                class="nav-sub-link {{ request()->routeIs('parja.album_foto.*') ? 'active' : '' }}">Daftar
                                Album Foto</a>
                            <a href="{{ route('parja.publikasi.index') }}"
                                class="nav-sub-link {{ request()->routeIs('parja.publikasi.*') ? 'active' : '' }}">Daftar
                                Publikasi</a>
                            <a href="{{ route('parja.jadwal.index') }}"
                                class="nav-sub-link {{ request()->routeIs('parja.jadwal.*') ? 'active' : '' }}">Jadwal
                                Pendaftaran</a>
                        </nav>
                    </li>

                    {{-- Data Skoring --}}
                    <li
                        class="nav-item {{ request()->routeIs('parja.kegiatan.*', 'parja.tingkat.*', 'parja.partisipasi.*', 'parja.kriteria.*', 'parja.skoringcv.*', 'parja.skoring.*') ? 'active' : '' }}">
                        <a href="#"
                            class="nav-link has-sub {{ request()->routeIs('parja.kegiatan.*', 'parja.tingkat.*', 'parja.partisipasi.*', 'parja.kriteria.*', 'parja.skoringcv.*', 'parja.skoring.*') ? 'active show' : '' }}">
                            <i class="ri-bar-chart-line"></i> <span>Data Skoring</span>
                        </a>
                        <nav
                            class="nav nav-sub {{ request()->routeIs('parja.kegiatan.*', 'parja.tingkat.*', 'parja.partisipasi.*', 'parja.kriteria.*', 'parja.skoringcv.*', 'parja.skoring.*') ? 'active show' : '' }}">
                            <a href="{{ route('parja.kegiatan.index') }}"
                                class="nav-sub-link {{ request()->routeIs('parja.kegiatan.*') ? 'active' : '' }}">Daftar
                                Kegiatan</a>
                            <a href="{{ route('parja.tingkat.index') }}"
                                class="nav-sub-link {{ request()->routeIs('parja.tingkat.*') ? 'active' : '' }}">Daftar
                                Tingkat</a>
                            <a href="{{ route('parja.partisipasi.index') }}"
                                class="nav-sub-link {{ request()->routeIs('parja.partisipasi.*') ? 'active' : '' }}">Daftar
                                Partisipasi</a>
                            <a href="{{ route('parja.kriteria.index') }}"
                                class="nav-sub-link {{ request()->routeIs('parja.kriteria.*') ? 'active' : '' }}">Daftar
                                Kriteria</a>
                            <a href="{{ route('parja.skoringcv.index') }}"
                                class="nav-sub-link {{ request()->routeIs('parja.skoringcv.*') ? 'active' : '' }}">Daftar
                                Skoring CV</a>
                            <a href="{{ route('parja.skoring.index') }}"
                                class="nav-sub-link {{ request()->routeIs('parja.skoring.*') ? 'active' : '' }}">Daftar
                                Skoring</a>
                        </nav>
                    </li>

                    {{-- Data Peserta --}}
                    <li
                        class="nav-item {{ request()->routeIs('parja.dapil.*', 'parja.peserta.*', 'parja.penilaian.*', 'parja.hasil_penilaian.*', 'parja.peserta_lolos.*', 'parja.laporan_kustom.*') ? 'active' : '' }}">
                        <a href="#"
                            class="nav-link has-sub {{ request()->routeIs('parja.dapil.*', 'parja.peserta.*', 'parja.penilaian.*', 'parja.hasil_penilaian.*', 'parja.peserta_lolos.*', 'parja.laporan_kustom.*') ? 'active show' : '' }}">
                            <i class="ri-group-line"></i> <span>Data Peserta</span>
                        </a>
                        <nav
                            class="nav nav-sub {{ request()->routeIs('parja.dapil.*', 'parja.peserta.*', 'parja.penilaian.*', 'parja.hasil_penilaian.*', 'parja.peserta_lolos.*', 'parja.laporan_kustom.*') ? 'active show' : '' }}">
                            <a href="{{ route('parja.dapil.index') }}"
                                class="nav-sub-link {{ request()->routeIs('parja.dapil.*') ? 'active' : '' }}">Daftar
                                Dapil</a>
                            <a href="{{ route('parja.peserta.index') }}"
                                class="nav-sub-link {{ request()->routeIs('parja.peserta.*') ? 'active' : '' }}">Daftar
                                Peserta</a>
                            <a href="{{ route('parja.penilaian.index') }}"
                                class="nav-sub-link {{ request()->routeIs('parja.penilaian.*') ? 'active' : '' }}">Daftar
                                Penilaian</a>
                            <a href="{{ route('parja.hasil_penilaian.index') }}"
                                class="nav-sub-link {{ request()->routeIs('parja.hasil_penilaian.*') ? 'active' : '' }}">Daftar
                                Hasil Penilaian</a>
                            <a href="{{ route('parja.peserta_lolos.index') }}"
                                class="nav-sub-link {{ request()->routeIs('parja.peserta_lolos.*') ? 'active' : '' }}">Daftar
                                Peserta (Lolos)</a>
                            <a href="{{ route('parja.peserta_lolos_seleksi.index') }}"
                                class="nav-sub-link {{ request()->routeIs('parja.peserta_lolos_seleksi.*') ? 'active' : '' }}">Daftar
                                Peserta (Lolos Seleksi)</a>
                            <a href="{{ route('parja.laporan_kustom.index') }}"
                                class="nav-sub-link {{ request()->routeIs('parja.laporan_kustom.*') ? 'active' : '' }}">Laporan
                                Kustom</a>
                        </nav>
                    </li>

                    {{-- Data Penilaian CV --}}
                    <li class="nav-item {{ request()->routeIs('parja.penilaian_cv*') ? 'active' : '' }}">
                        <a href="#"
                            class="nav-link has-sub {{ request()->routeIs('parja.penilaian_cv*') ? 'active show' : '' }}">
                            <i class="ri-file-user-line"></i> <span>Data Penilaian CV</span>
                        </a>
                        <nav class="nav nav-sub {{ request()->routeIs('parja.penilaian_cv*') ? 'active show' : '' }}">
                            <a href="{{ route('parja.penilaian_cv_belum.index') }}"
                                class="nav-sub-link {{ request()->routeIs('parja.penilaian_cv_belum.*') ? 'active' : '' }}">Daftar
                                Penilaian CV (Belum)</a>
                            <a href="{{ route('parja.penilaian_cv_sudah.index') }}"
                                class="nav-sub-link {{ request()->routeIs('parja.penilaian_cv_sudah.*', 'parja.penilaian_cv.*') ? 'active' : '' }}">Daftar
                                Penilaian CV (Sudah)</a>
                        </nav>
                    </li>

                    {{-- Data Penilaian Esai --}}
                    <li class="nav-item {{ request()->routeIs('parja.penilaian_esai*') ? 'active' : '' }}">
                        <a href="#"
                            class="nav-link has-sub {{ request()->routeIs('parja.penilaian_esai*') ? 'active show' : '' }}">
                            <i class="ri-file-text-line"></i> <span>Data Penilaian Esai</span>
                        </a>
                        <nav class="nav nav-sub {{ request()->routeIs('parja.penilaian_esai*') ? 'active show' : '' }}">
                            <a href="{{ route('parja.penilaian_esai_belum.index') }}"
                                class="nav-sub-link {{ request()->routeIs('parja.penilaian_esai_belum.*') ? 'active' : '' }}">Daftar
                                Penilaian Esai (Belum)</a>
                            <a href="{{ route('parja.penilaian_esai_sudah.index') }}"
                                class="nav-sub-link {{ request()->routeIs('parja.penilaian_esai_sudah.*', 'parja.penilaian_esai.*') ? 'active' : '' }}">Daftar
                                Penilaian Esai (Sudah)</a>
                        </nav>
                    </li>

                    {{-- Data Penilaian Video --}}
                    <li class="nav-item {{ request()->routeIs('parja.penilaian_video*') ? 'active' : '' }}">
                        <a href="#"
                            class="nav-link has-sub {{ request()->routeIs('parja.penilaian_video*') ? 'active show' : '' }}">
                            <i class="ri-play-circle-line"></i> <span>Data Penilaian Video</span>
                        </a>
                        <nav class="nav nav-sub {{ request()->routeIs('parja.penilaian_video*') ? 'active show' : '' }}">
                            <a href="{{ route('parja.penilaian_video_belum.index') }}"
                                class="nav-sub-link {{ request()->routeIs('parja.penilaian_video_belum.*') ? 'active' : '' }}">Belum
                                Dinilai</a>
                            <a href="{{ route('parja.penilaian_video_sudah.index') }}"
                                class="nav-sub-link {{ request()->routeIs('parja.penilaian_video_sudah.*') ? 'active' : '' }}">Sudah
                                Dinilai</a>
                            @if (rbac_can_create_parja())
                            <a href="{{ route('parja.penilaian_video.add') }}"
                                class="nav-sub-link {{ request()->routeIs('parja.penilaian_video.add') ? 'active' : '' }}">Tambah
                                Penilaian</a>
                            @endif
                        </nav>
                    </li>

                </ul>
            </div>
        @endif

        {{-- ====================================================== --}}
        {{-- SEKSI: PARJA ALUMNI --}}
        {{-- Visible only when the user's active (or assigned) role --}}
        {{-- grants Alumni-side access. --}}
        {{-- ====================================================== --}}
        @if (rbac_can_view_alumni_section())
            <div class="nav-group show">
                <a href="#" class="nav-label">Parja Alumni</a>
                <ul class="nav nav-sidebar">

                    {{-- Dashboard Alumni --}}
                    <li class="nav-item {{ request()->routeIs('parja.alumni-dashboard.*') ? 'active' : '' }}">
                        <a href="{{ route('parja.alumni-dashboard.index') }}"
                            class="nav-link {{ request()->routeIs('parja.alumni-dashboard.*') ? 'active' : '' }}">
                            <i class="ri-dashboard-line"></i> <span>Dashboard Alumni</span>
                        </a>
                    </li>

                    {{-- Data Survei Alumni --}}
                    <li class="nav-item {{ request()->routeIs('parja.alumni-survei.*') ? 'active' : '' }}">
                        <a href="{{ route('parja.alumni-survei.index') }}"
                            class="nav-link {{ request()->routeIs('parja.alumni-survei.*') ? 'active' : '' }}">
                            <i class="ri-survey-line"></i> <span>Data Survei Alumni</span>
                        </a>
                    </li>

                    {{-- Akun Alumni --}}
                    {{-- Defensif: DB alumni (xdb_parja_alumni) bisa belum tersedia di sebagian
                         environment (fallback ke xdb_epublic yg skema `alumni`-nya lama, tanpa
                         kolom approval_status). Jangan biarkan badge ini men-500 seluruh modul Parja. --}}
                    @php
                        try {
                            $pendingAlumniCount = \Modules\Parja\App\Models\AlumniModel::where('approval_status', 0)->where('status', '!=', 9)->count();
                        } catch (\Throwable $e) {
                            $pendingAlumniCount = 0;
                        }
                    @endphp
                    <li class="nav-item {{ request()->routeIs('parja.alumni.*') ? 'active' : '' }}">
                        <a href="#"
                            class="nav-link has-sub {{ request()->routeIs('parja.alumni.*') ? 'active show' : '' }}">
                            <i class="ri-user-star-line"></i>
                            <span>Data Akun Alumni</span>
                            @if ($pendingAlumniCount > 0)
                                <span class="badge bg-danger rounded-pill ms-1">{{ $pendingAlumniCount }}</span>
                            @endif
                        </a>
                        <nav class="nav nav-sub {{ request()->routeIs('parja.alumni.*') ? 'active show' : '' }}">
                            <a href="{{ route('parja.alumni.index') }}"
                                class="nav-sub-link {{ request()->routeIs('parja.alumni.*') ? 'active' : '' }}">Daftar
                                Alumni</a>
                        </nav>
                    </li>

                    {{-- Profil Alumni --}}
                    <li class="nav-item {{ request()->routeIs('parja.alumni-profile.*') ? 'active' : '' }}">
                        <a href="#"
                            class="nav-link has-sub {{ request()->routeIs('parja.alumni-profile.*') ? 'active show' : '' }}">
                            <i class="ri-contacts-line"></i> <span>Data Profil Alumni</span>
                        </a>
                        <nav class="nav nav-sub {{ request()->routeIs('parja.alumni-profile.*') ? 'active show' : '' }}">
                            <a href="{{ route('parja.alumni-profile.index') }}"
                                class="nav-sub-link {{ request()->routeIs('parja.alumni-profile.*') ? 'active' : '' }}">Daftar
                                Profil Alumni</a>
                        </nav>
                    </li>

                    {{-- Tracer Study Digital --}}
                    <li class="nav-item {{ request()->routeIs('parja.tracer.*') ? 'active' : '' }}">
                        <a href="#"
                            class="nav-link has-sub {{ request()->routeIs('parja.tracer.*') ? 'active show' : '' }}">
                            <i class="ri-search-eye-line"></i> <span>Tracer Study Alumni</span>
                        </a>
                        <nav class="nav nav-sub {{ request()->routeIs('parja.tracer.*') ? 'active show' : '' }}">
                            <a href="{{ route('parja.tracer.index') }}"
                                class="nav-sub-link {{ request()->routeIs('parja.tracer.index') ? 'active' : '' }}">Halaman
                                Utama</a>
                            <a href="{{ route('parja.tracer.help') }}"
                                class="nav-sub-link {{ request()->routeIs('parja.tracer.help') ? 'active' : '' }}">Panduan</a>
                        </nav>
                    </li>

                    {{-- Direktori Alumni --}}
                    <li class="nav-item {{ request()->routeIs('parja.direktori-alumni.*') ? 'active' : '' }}">
                        <a href="{{ route('parja.direktori-alumni.index') }}"
                            class="nav-link {{ request()->routeIs('parja.direktori-alumni.*') ? 'active' : '' }}">
                            <i class="ri-contacts-book-line"></i> <span>Data Direktori Alumni</span>
                        </a>
                    </li>

                    {{-- Moderasi Post --}}
                    @php
                        try {
                            $pendingCount = \Modules\Parja\App\Models\AlumniPostModel::pending()->count();
                        } catch (\Throwable $e) {
                            $pendingCount = 0;
                        }
                        try {
                            $hasPrivasiCol = \Illuminate\Support\Facades\Schema::connection('alumni')->hasColumn('alumni_posts', 'privasi_request');
                            $reqPublicCount = $hasPrivasiCol
                                ? \Modules\Parja\App\Models\AlumniPostModel::query()
                                    ->where('status', 1)
                                    ->where('privasi_request', 'public')
                                    ->count()
                                : 0;
                        } catch (\Throwable $e) {
                            $reqPublicCount = 0;
                        }
                        $moderasiAlertCount = $pendingCount + $reqPublicCount;
                    @endphp
                    <li class="nav-item {{ request()->routeIs('parja.moderasi-post.*') ? 'active' : '' }}">
                        <a href="{{ route('parja.moderasi-post.index') }}"
                            class="nav-link {{ request()->routeIs('parja.moderasi-post.*') ? 'active' : '' }}">
                            <i class="ri-shield-check-line"></i>
                            <span>Data Moderasi Post</span>
                            @if ($moderasiAlertCount > 0)
                                <span class="badge bg-danger rounded-pill ms-1">{{ $moderasiAlertCount }}</span>
                            @endif
                        </a>
                    </li>

                    {{-- Moderasi Direktori (kartu consent alumni) --}}
                    @php
                        try {
                            $direktoriPendingCount = \Modules\Parja\App\Models\AlumniProfileModel::query()->direktoriPending()->count();
                        } catch (\Throwable $e) {
                            $direktoriPendingCount = 0;
                        }
                    @endphp
                    <li class="nav-item {{ request()->routeIs('parja.moderasi-direktori.*') ? 'active' : '' }}">
                        <a href="{{ route('parja.moderasi-direktori.index') }}"
                            class="nav-link {{ request()->routeIs('parja.moderasi-direktori.*') ? 'active' : '' }}">
                            <i class="ri-profile-line"></i>
                            <span>Moderasi Direktori</span>
                            @if ($direktoriPendingCount > 0)
                                <span class="badge bg-danger rounded-pill ms-1">{{ $direktoriPendingCount }}</span>
                            @endif
                        </a>
                    </li>

                    {{-- Banner Alumni Beranda (independen dari data kegiatan) --}}
                    <li class="nav-item {{ request()->routeIs('parja.alumni-banner.*') ? 'active' : '' }}">
                        <a href="{{ route('parja.alumni-banner.index') }}"
                            class="nav-link {{ request()->routeIs('parja.alumni-banner.*') ? 'active' : '' }}">
                            <i class="ri-image-2-line"></i>
                            <span>Data Banner Alumni</span>
                        </a>
                    </li>

                    {{-- Info Alumni Parja (Berita / Kegiatan / Pengumuman) --}}
                    <li class="nav-item {{ request()->routeIs('parja.alumni-info.*') ? 'active' : '' }}">
                        <a href="{{ route('parja.alumni-info.index') }}"
                            class="nav-link {{ request()->routeIs('parja.alumni-info.*') ? 'active' : '' }}">
                            <i class="ri-bar-chart-line"></i>
                            <span>Data Kegiatan Alumni</span>
                        </a>
                    </li>

                </ul>
            </div>
        @endif

    </div>

    <div class="sidebar-footer">
        <div class="sidebar-footer-top">
            <div class="sidebar-footer-thumb">
                <img src="{{ asset('template/dist/assets/img/img1.jpg') }}" alt="">
            </div>
            <div class="sidebar-footer-body">
                <h6><a href="#">{{ (auth()->guard('keycloak-external')->user()?->name ?? auth()->guard('keycloak')->user()?->name ?? '') }}</a></h6>
                <p>{{ ucfirst(collect(auth()->guard('keycloak-external')->user()?->roles ?? [])->first() ?? 'User') }}</p>
            </div>
            <a id="sidebarFooterMenu" href="" class="dropdown-link"><i class="ri-arrow-down-s-line"></i></a>
        </div>
        <div class="sidebar-footer-menu">
            <nav class="nav">
                <a href="{{ route('dashboard') }}"><i class="ri-apps-line"></i> Kembali ke Beranda</a>
            </nav>
            <hr>
            <nav class="nav">
                <form method="POST" action="{{ route('logout') }}" id="parja-logout-form">
                    @csrf
                </form>
                <a href="#" onclick="document.getElementById('parja-logout-form').submit();">
                    <i class="ri-logout-box-r-line"></i> Keluar
                </a>
            </nav>
        </div>
    </div>
</div>