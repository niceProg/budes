@extends('layouts.app')

@section('title', 'Biodata Peserta | Admin - SMART Setjen DPR RI')
@section('content')
<link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

@include('partials.table_skin_lamaran_css')
<style>
    .participant-name { font-weight: 700; color: var(--primary-dark); font-size: 1.05rem; }
    .score-badge { background: #f8fafc; padding: 8px 12px; border-radius: 12px; font-size: 0.85rem; font-weight: 700; color: var(--primary-dark); border: 1px solid #e2e8f0; }
    .status-badge { padding: 8px 16px; border-radius: 12px; font-weight: 800; font-size: 0.75rem; display: inline-flex; align-items: center; gap: 8px; letter-spacing: 0.5px; }
    .status-belum-mulai { background: #fffbeb; color: #d97706; }
    .status-aktif { background: #eff6ff; color: #2563eb; }
    .status-selesai { background: #ecfdf5; color: #059669; }

    /* Avatar Styling */
    .participant-avatar {
        position: relative;
        width: 48px; height: 48px;
        background: var(--gold-gradient);
        border-radius: 16px;
        display: flex; align-items: center; justify-content: center;
        color: white; font-weight: 800; font-size: 1rem;
        box-shadow: 0 8px 16px rgba(176, 141, 72, 0.25);
        cursor: pointer;
        overflow: hidden;
    }

    .participant-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 16px;
        display: block;
    }

    .avatar-initials {
        position: absolute;
        inset: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 1rem;
        color: #ffffff;
        text-transform: uppercase;
        pointer-events: none;
    }

    /* Hide initials when image is loaded successfully */
    .participant-avatar:has(img:not([style*="display: none"])) .avatar-initials {
        display: none;
    }

    /* Icon peringatan nodin di tabel utama */
    .btn-nodin-warning {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 36px;
        height: 36px;
        border: none;
        background: transparent;
        color: #b45309;
        border-radius: 10px;
        cursor: pointer;
        transition: var(--transition);
    }
    .btn-nodin-warning:hover {
        background: transparent;
        color: #92400e;
        transform: scale(1.08);
    }
    .popover-nodin .popover-body { padding: 0.75rem 1rem; font-size: 0.875rem; }
    .popover-nodin .popover-body ul { margin: 0; padding-left: 1.1rem; }

</style>

<div class="content-wrapper">
    <div class="container-fluid px-md-5">
        <div class="page-title-area">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1">
                    <li class="breadcrumb-item">Manajemen</li>
                    <li class="breadcrumb-item active">Biodata Peserta</li>
                </ol>
            </nav>
            <h1 class="page-title">Monitoring Partisipan Magang</h1>
        </div>

        <div class="modern-card">
            <div class="card-toolbar flex-wrap gap-4">
                <div class="d-flex align-items-center gap-2">
                    <div style="width: 5px; height: 25px; background: var(--gold-gradient); border-radius: 10px;"></div>
                    @php
                        $isVerifikatorView = isset($biodataRoutePrefix) && $biodataRoutePrefix === 'verifikator.biodata';
                        $isShowAllUnits = $isVerifikatorView && request('show_all_units') == '1';
                    @endphp
                    <h5 class="m-0 fw-800 text-dark">
                        Daftar Biodata Peserta
                        @if($isShowAllUnits)
                            <span class="text-muted" style="font-weight:600; font-size:0.9rem;">
                                (semua satuan kerja)
                            </span>
                        @endif
                    </h5>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <form method="GET" action="{{ request()->url() }}" class="search-container" id="searchForm">
                        <div class="input-group">
                            <i class="ri-search-2-line" style="color: var(--accent-gold); font-size: 1.2rem;"></i>
                            <input type="text" name="search" id="search" class="form-control shadow-none"
                                   placeholder="Cari nama / email / intitusi / jurusan..."
                                   value="{{ request('search', '') }}">
                            @if(request('search'))
                                <a href="{{ request()->url() }}?{{ http_build_query(request()->except(['search','page'])) }}" class="text-muted ms-2"><i class="ri-close-circle-fill"></i></a>
                            @endif
                        </div>
                        <input type="hidden" name="per_page" value="{{ request('per_page', 10) }}">
                    </form>
                    @php
                        // Default route export untuk ADMIN
                        $biodataExportRouteName = $biodataExportRoute ?? 'biodata.export';
                    @endphp
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-secondary rounded-pill px-3 dropdown-toggle"
                                type="button"
                                data-bs-toggle="dropdown"
                                aria-expanded="false">
                            <i class="ri-download-2-line me-1"></i> Export Data
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><h6 class="dropdown-header">Semua Data</h6></li>
                            <li>
                                <a class="dropdown-item" href="{{ route($biodataExportRouteName, array_merge(request()->query(), ['scope' => 'all', 'format' => 'pdf'])) }}">
                                    PDF (Semua)
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href "{{ route($biodataExportRouteName, array_merge(request()->query(), ['scope' => 'all', 'format' => 'xlsx'])) }}">
                                    Excel (Semua)
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li><h6 class="dropdown-header">Sesuai Pagination</h6></li>
                            <li>
                                <a class="dropdown-item" href="{{ route($biodataExportRouteName, array_merge(request()->query(), ['scope' => 'page', 'page' => request('page', 1), 'format' => 'pdf'])) }}">
                                    PDF (Halaman ini)
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route($biodataExportRouteName, array_merge(request()->query(), ['scope' => 'page', 'page' => request('page', 1), 'format' => 'xlsx'])) }}">
                                    Excel (Halaman ini)
                                </a>
                            </li>
                        </ul>
                    </div>
                    @if(request()->routeIs('biodata.index'))
                    <button type="button" class="btn btn-sm rounded-pill px-3" id="btnUpdateStatusPeserta"
                            onclick="runUpdatePesertaStatus()"
                            style="background: var(--gold-gradient, linear-gradient(135deg,#c5a059,#8e6d2f)); color:#fff; font-weight:700; border:none; display:inline-flex; align-items:center; gap:6px;"
                            title="Perbarui status peserta sesuai tanggal magang, tanpa menunggu cron">
                        <i class="ri-refresh-line" id="updateStatusIcon"></i> <span id="updateStatusLabel">Update Status Peserta</span>
                    </button>
                    @endif
                </div>
            </div>

            <!-- Filter Section -->
            <div class="filter-section d-flex flex-wrap align-items-center gap-3">
                <form method="GET" action="{{ request()->url() }}" id="filterForm" class="d-flex flex-wrap align-items-center gap-3 flex-grow-1">
                    <!-- Filter Kategori -->
                    <div class="dropdown filter-dropdown">
                        <button class="btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="ri-filter-3-line"></i> Kategori
                            @if(request()->has('filter_kategori'))
                                <span class="badge filter-badge">{{ count((array)request('filter_kategori')) }}</span>
                            @endif
                        </button>
                        <ul class="dropdown-menu p-2" style="min-width: 200px;">
                            @foreach($kategoris ?? [] as $kat)
                                <li>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="filter_kategori[]" value="{{ $kat }}" id="kat_{{ $loop->index }}"
                                               {{ in_array($kat, (array)request('filter_kategori', [])) ? 'checked' : '' }}>
                                        <label class="form-check-label w-100" for="kat_{{ $loop->index }}">{{ ucwords($kat) }}</label>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <!-- Filter Status -->
                    <div class="dropdown filter-dropdown">
                        <button class="btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="ri-flag-line"></i> Status
                            @if(request()->has('filter_status'))
                                <span class="badge filter-badge">{{ count((array)request('filter_status')) }}</span>
                            @endif
                        </button>
                        <ul class="dropdown-menu p-2" style="min-width: 180px;">
                            <li>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="filter_status[]" value="1" id="stat_1"
                                           {{ in_array('1', (array)request('filter_status', [])) ? 'checked' : '' }}>
                                    <label class="form-check-label w-100" for="stat_1">Belum Mulai</label>
                                </div>
                            </li>
                            <li>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="filter_status[]" value="2" id="stat_2"
                                           {{ in_array('2', (array)request('filter_status', [])) ? 'checked' : '' }}>
                                    <label class="form-check-label w-100" for="stat_2">Aktif Magang</label>
                                </div>
                            </li>
                        </ul>
                    </div>

                    @if(($isSuperadmin ?? false) || (isset($satkerList) && count($satkerList) > 0))
                        <!-- Filter Lowongan -->
                        <div class="dropdown filter-dropdown">
                            <button class="btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="ri-briefcase-4-line"></i> Lowongan
                                @if(request()->has('filter_lowongan'))
                                    <span class="badge filter-badge">{{ count((array)request('filter_lowongan')) }}</span>
                                @endif
                            </button>
                            <ul class="dropdown-menu p-2" style="min-width: 240px; max-height: 300px; overflow-y: auto;">
                                <li>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="filter_lowongan[]" value="umum" id="low_umum"
                                               {{ in_array('umum', (array)request('filter_lowongan', [])) ? 'checked' : '' }}>
                                        <label class="form-check-label w-100" for="low_umum">Umum</label>
                                    </div>
                                </li>
                                @foreach($lowonganList ?? [] as $low)
                                    <li>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="filter_lowongan[]" value="{{ $low->id }}" id="low_{{ $low->id }}"
                                                   {{ in_array((string) $low->id, array_map('strval', (array)request('filter_lowongan', []))) ? 'checked' : '' }}>
                                            <label class="form-check-label w-100" for="low_{{ $low->id }}">{{ $low->title }}</label>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        <!-- Filter Satuan Kerja -->
                        <div class="dropdown filter-dropdown">
                            <button class="btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="ri-building-line"></i> Satuan Kerja
                                @if(request()->has('filter_satker') || !($isSuperadmin ?? false))
                                    <span class="badge filter-badge">1</span>
                                @endif
                            </button>
                            <ul class="dropdown-menu p-2" style="min-width: 260px; max-height: 300px; overflow-y: auto;">
                                @forelse($satkerList ?? [] as $sk)
                                    <li>
                                        <div class="form-check">
                                            @if(!($isSuperadmin ?? false))
                                                <!-- If not superadmin, lock to their satker by forcing check, disabling input, and providing hidden input -->
                                                <input class="form-check-input" type="checkbox" value="{{ $sk->id }}" id="sk_{{ $sk->id }}" checked disabled>
                                                <input type="hidden" name="filter_satker[]" value="{{ $sk->id }}">
                                            @else
                                                <input class="form-check-input" type="checkbox" name="filter_satker[]" value="{{ $sk->id }}" id="sk_{{ $sk->id }}"
                                                       {{ in_array((string) $sk->id, array_map('strval', (array)request('filter_satker', []))) ? 'checked' : '' }}>
                                            @endif
                                            <label class="form-check-label w-100" for="sk_{{ $sk->id }}">{{ $sk->nama_satker }} <span class="text-muted">({{ $sk->kode_satker }})</span></label>
                                        </div>
                                    </li>
                                @empty
                                    <li><span class="dropdown-item-text text-muted small">Belum ada satuan kerja</span></li>
                                @endforelse
                            </ul>
                        </div>
                    @endif

                    <button type="submit" class="btn-filter-apply">
                        <i class="ri-filter-line"></i> Terapkan
                    </button>
                    @php
                        $hasActiveFilters = request()->has('filter_kategori') || 
                                           request()->has('filter_status') || 
                                           request()->has('filter_lowongan') || 
                                           (($isSuperadmin ?? false) && request()->has('filter_satker'));
                    @endphp
                    @if($hasActiveFilters)
                        <a href="{{ request()->url() }}?{{ http_build_query(request()->except(['filter_kategori', 'filter_status', 'filter_lowongan', 'filter_satker', 'page'])) }}" class="btn-filter-reset">
                            <i class="ri-close-line"></i> Reset
                        </a>
                    @endif
                    <input type="hidden" name="search" value="{{ request('search') }}">
                    <input type="hidden" name="per_page" value="{{ request('per_page', 10) }}">
                </form>

                @php
                    // Toggle khusus untuk verifikator: lihat semua satuan kerja vs hanya satuan kerja sendiri + null
                    $isVerifikatorView = isset($biodataRoutePrefix) && $biodataRoutePrefix === 'verifikator.biodata';
                    $showAllUnitsToggle = $isVerifikatorView ? (request('show_all_units') == '1') : false; // true = SEDANG mode "lihat semua"

                    // Bangun ulang query string untuk tombol toggle
                    $toggleParams = request()->except(['show_all_units']);
                    $toggleParams['show_all_units'] = $showAllUnitsToggle ? '0' : '1';
                    $toggleUrl = request()->url() . '?' . http_build_query($toggleParams);

                    // Styling & title sesuai mode:
                    // - Jika TIDAK aktif (default): tombol hanya border (outline)
                    // - Jika AKTIF (show_all): tombol solid dan title menjelaskan sedang pakai mode lihat semua data
                    if ($showAllUnitsToggle) {
                        $toggleStyle = 'font-weight:700;padding:0.5rem;border-radius:8px;width:40px;height:40px;display:inline-flex;align-items:center;justify-content:center;background-color:#0ea5e9 !important;border:2px solid #0ea5e9 !important;color:white !important;';
                        $toggleTitle = 'Sedang mode: Lihat Semua Satuan Kerja (klik untuk kembali ke satuan kerja Anda saja)';
                        $iconColor = 'white';
                    } else {
                        $toggleStyle = 'font-weight:700;padding:0.5rem;border-radius:8px;width:40px;height:40px;display:inline-flex;align-items:center;justify-content:center;background-color:transparent;border:2px solid #0ea5e9 !important;color:#0ea5e9 !important;';
                        $toggleTitle = 'Mode: Satuan Kerja Saya (klik untuk lihat semua satuan kerja)';
                        $iconColor = '#0ea5e9';
                    }
                @endphp

                {{-- Tombol "lihat semua satuan kerja" dihapus: verifikator dibatasi ke unit kerjanya
                     sendiri demi isolasi data lintas satker (server mengabaikan param show_all_units). --}}
            </div>

            <div class="px-4 py-3 d-flex align-items-center justify-content-between" style="background: rgba(248, 250, 252, 0.5);">
                <div class="text-muted fw-700" style="font-size: 0.85rem;">
                    Total Entri: <span class="text-dark">{{ $data->total() }}</span>
                </div>
                <form method="GET" action="{{ request()->url() }}" class="d-flex align-items-center gap-2">
                    <span class="fw-700 text-muted" style="font-size: 0.8rem;">Baris:</span>
                    <select name="per_page" class="form-select form-select-sm border-0 fw-800 shadow-sm"
                            style="border-radius: 8px; width: 80px; cursor: pointer;"
                            onchange="this.form.submit()">
                        @foreach([10, 25, 50, 100] as $size)
                            <option value="{{ $size }}" {{ request('per_page') == $size ? 'selected' : '' }}>{{ $size }}</option>
                        @endforeach
                    </select>
                    <input type="hidden" name="search" value="{{ request('search') }}">
                    @foreach((array)request('filter_kategori', []) as $kat)
                        <input type="hidden" name="filter_kategori[]" value="{{ $kat }}">
                    @endforeach
                    @foreach((array)request('filter_status', []) as $stat)
                        <input type="hidden" name="filter_status[]" value="{{ $stat }}">
                    @endforeach
                    @foreach((array)request('filter_lowongan', []) as $low)
                        <input type="hidden" name="filter_lowongan[]" value="{{ $low }}">
                    @endforeach
                    @foreach((array)request('filter_satker', []) as $sk)
                        <input type="hidden" name="filter_satker[]" value="{{ $sk }}">
                    @endforeach
                </form>
            </div>

            <div class="table-container table-responsive">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Peserta</th>
                            <th>Kategori</th>
                            <th class="d-none d-lg-table-cell">Jenis Lowongan</th>
                            <th>Asal Institusi</th>
                            <th>Satuan Kerja</th>
                            <th class="text-center">Nilai</th>
                            <th class="text-center" style="width: 48px;" title="Peringatan Nodin"></th>
                            <th class="text-center">Status Aktivitas</th>
                            <th class="text-end">Detail</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($data as $index => $val)
                        @php
                            $words = explode(" ", $val->nama);
                            $initials = (count($words) > 1) ? $words[0][0].$words[1][0] : substr($val->nama, 0, 2);
                            $pasFoto = $val->lamaran->pas_foto ?? null;
                            $isKhusus = $val->lamaran && $val->lamaran->id_lowongan;
                            $jenisLowongan = $isKhusus ? ($val->lamaran->lowongan->title ?? 'Lowongan Khusus') : 'Umum';
                        @endphp
                        <tr>
                            <td>{{ ($data->currentPage() - 1) * $data->perPage() + $index + 1 }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="participant-avatar js-preview-file"
                                         @if($pasFoto)
                                             data-file-url="{{ file_url($pasFoto) }}"
                                             data-file-name="Pas Foto - {{ $val->nama }}"
                                         @endif
                                    >
                                        <span class="avatar-initials">{{ strtoupper($initials) }}</span>
                                        @if($pasFoto)
                                            <img src="{{ file_url($pasFoto) }}"
                                                 alt="{{ $val->nama }}"
                                                 onload="this.parentElement.querySelector('.avatar-initials').style.display='none';"
                                                 onerror="this.style.display='none'; this.parentElement.querySelector('.avatar-initials').style.display='flex';">
                                        @endif
                                    </div>
                                    <div>
                                        <span class="participant-name">{{ ucwords(strtolower($val->nama)) }}</span>
                                    <div class="d-md-none mt-1">
                                        <span class="category-badge" style="font-size: 0.7rem; padding: 4px 10px;">{{ ucwords((string) $val->kategori) }}</span>
                                    </div>
                                    <div class="d-lg-none mt-1">
                                        <span class="small fw-700" style="color: {{ $isKhusus ? '#92400e' : '#475569' }};"><i class="ri-briefcase-4-line"></i> {{ $jenisLowongan }}</span>
                                    </div>
                                    <div class="d-lg-none mt-1">
                                        <div class="d-flex align-items-center gap-2">
                                            <i class="ri-bank-line text-muted" style="font-size: 0.8rem;"></i>
                                            <span class="small fw-600 text-muted">{{ ucwords(strtolower(Str::limit($val->lamaran->instansi ?? '-', 25))) }}</span>
                                        </div>
                                    </div>
                                    <div class="d-xl-none mt-1">
                                        <span class="small fw-700 text-dark">Unit: {{ strtoupper($val->satker->kode ?? '-') }}</span>
                                    </div>
                                    <div class="d-sm-none mt-1">
                                        <span class="score-badge" style="font-size: 0.75rem; padding: 4px 8px;">Nilai: {{ $val->nilai ?? '0.00' }}</span>
                                    </div>
                                    </div>
                                </div>
                            </td>
                            <td class="d-none d-md-table-cell">
                                <span class="category-badge">{{ ucwords((string) $val->kategori) }}</span>
                            </td>
                            <td class="d-none d-lg-table-cell">
                                @if($isKhusus)
                                    <span class="badge" style="background:#fef3c7; color:#92400e; border:1px solid #fde68a; font-weight:700; white-space:normal; text-align:left;">{{ $jenisLowongan }}</span>
                                @else
                                    <span class="badge" style="background:#f1f5f9; color:#475569; border:1px solid #e2e8f0; font-weight:700;">Umum</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <i class="ri-bank-line text-muted"></i>
                                    <span class="small fw-600 text-muted">{{ ucwords($val->lamaran->instansi ?? '-') }}</span>
                                </div>
                            </td>
                            <td class="d-none d-xl-table-cell">
                                <span class="small fw-700 text-dark">{{ strtoupper($val->satker->kode ?? '-') }}</span>
                            </td>
                            <td class="text-center">
                                <span class="score-badge">{{ $val->nilai ?? '0.00' }}</span>
                            </td>
                            <td class="text-center">
                                @php
                                    $nodinKurang = [];
                                    if (empty($val->nodin_1)) $nodinKurang[] = 'Nodin Pusbangkom';
                                    if (empty($val->nodin_2)) $nodinKurang[] = 'Nodin Satuan Kerja';
                                    if (empty($val->nodin_permohonan)) $nodinKurang[] = 'Nodin ke Universitas/Instansi';
                                @endphp
                                @if(count($nodinKurang) > 0)
                                    <button type="button"
                                            class="btn-nodin-warning js-nodin-popover"
                                            data-bs-toggle="popover"
                                            data-bs-placement="left"
                                            data-bs-trigger="focus"
                                            data-bs-html="true"
                                            data-bs-title="Data Nodin Belum Lengkap"
                                            data-bs-content="<strong>Yang belum dilengkapi:</strong><ul class='mt-1 mb-0'><li>{{ implode('</li><li>', $nodinKurang) }}</li></ul>"
                                            title="Klik untuk melihat yang belum lengkap">
                                        <i class="ri-error-warning-fill" style="font-size: 1.1rem;"></i>
                                    </button>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($val->status == 1)
                                    <span class="status-badge status-belum-mulai">Belum Mulai</span>
                                @elseif($val->status == 2)
                                    <span class="status-badge status-aktif">Aktif Magang</span>
                                @elseif($val->status == 3)
                                    <span class="status-badge status-selesai">Selesai Magang</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <a href="{{ route(($biodataRoutePrefix ?? 'biodata') . '.show', $val->id) }}" class="btn-action" title="Detail Peserta">
                                    <i class="ri-eye-line"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="text-center py-5">
                                <img src="https://illustrations.popsy.co/white/abstract-art-4.svg" style="height: 150px; opacity: 0.5;">
                                <p class="text-muted mt-3 fw-700">Tidak ada data peserta ditemukan.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="footer-container">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-4">
                    <div class="text-muted fw-700" style="font-size: 0.85rem;">
                        Menampilkan <span class="text-dark">{{ $data->firstItem() ?? 0 }}</span> - <span class="text-dark">{{ $data->lastItem() ?? 0 }}</span> dari <span class="text-dark">{{ $data->total() }}</span> Peserta
                    </div>
                    <div class="pagination-wrapper">
                        {{ $data->appends(request()->query())->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Preview File (Global) -->
<div class="modal fade" id="filePreviewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content overflow-hidden border-0 shadow-lg" style="border-radius: 20px;">
            <div class="modal-header border-0 bg-white px-4 py-3">
                <h5 class="modal-title fw-800" id="filePreviewModalLabel">Pratinjau Dokumen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0 d-flex justify-content-center align-items-center bg-secondary bg-opacity-10" style="min-height: 75vh;">
                <iframe id="filePreviewFrame" src="" style="width: 100%; height: 75vh; border: 0; display: none;"></iframe>
                <img id="filePreviewImage" src="" alt="Preview" style="max-width: 100%; max-height: 75vh; display: none; object-fit: contain;" />
            </div>
        </div>
    </div>
</div>

<script>
    function runUpdatePesertaStatus() {
        if (typeof Swal === 'undefined') return;
        Swal.fire({
            title: 'Update Status Peserta?',
            html: 'Menjalankan <strong>peserta:update-status</strong> sekarang (tanpa menunggu cron).<br><br>Status peserta akan disesuaikan: Belum&nbsp;Mulai&nbsp;→&nbsp;Aktif (jika sudah masuk tanggal mulai) dan Aktif&nbsp;→&nbsp;Selesai (jika sudah lewat tanggal selesai).',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Jalankan',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#b08d48',
            cancelButtonColor: '#64748b',
        }).then((result) => {
            if (!result.isConfirmed) return;

            var btn = document.getElementById('btnUpdateStatusPeserta');
            var icon = document.getElementById('updateStatusIcon');
            var label = document.getElementById('updateStatusLabel');
            if (btn) btn.disabled = true;
            if (icon) icon.style.animation = 'spin 1s linear infinite';
            if (label) label.textContent = 'Memproses...';

            Swal.fire({
                title: 'Sedang Memproses...',
                text: 'Mohon tunggu sebentar.',
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => { Swal.showLoading(); }
            });

            var resetBtn = function() {
                if (btn) btn.disabled = false;
                if (icon) icon.style.animation = '';
                if (label) label.textContent = 'Update Status Peserta';
            };

            try {
                var csrfMeta = document.querySelector('meta[name="csrf-token"]');
                var csrfToken = csrfMeta ? csrfMeta.content : '{{ csrf_token() }}';

                fetch('{{ route("peserta.update-status.run") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                })
                .then(res => {
                    if (!res.ok) {
                        return res.text().then(text => {
                            try { return JSON.parse(text); } catch(e) {}
                            throw new Error('Server error: ' + res.status);
                        });
                    }
                    return res.json();
                })
                .then(data => {
                    resetBtn();

                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            html: (data.message || 'Status peserta diperbarui.') + (data.output ? '<pre style="text-align:left;white-space:pre-wrap;font-size:12px;margin-top:10px;background:#f8fafc;padding:10px;border-radius:8px;max-height:200px;overflow:auto;">' + data.output.replace(/</g,'&lt;') + '</pre>' : ''),
                            confirmButtonColor: '#b08d48'
                        }).then(() => location.reload());
                    } else {
                        Swal.fire({ icon: 'error', title: 'Gagal', text: data.message || 'Gagal memperbarui status.', confirmButtonColor: '#ef4444' });
                    }
                })
                .catch((err) => {
                    resetBtn();
                    Swal.fire({ icon: 'error', title: 'Koneksi Gagal', text: err.message || 'Tidak dapat menghubungi server.', confirmButtonColor: '#ef4444' });
                });
            } catch (e) {
                resetBtn();
                Swal.fire({ icon: 'error', title: 'Error', text: e.message || 'Terjadi kesalahan. Silakan muat ulang halaman.', confirmButtonColor: '#ef4444' });
            }
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        var searchInput = document.getElementById('search');
        if (searchInput) {
            var timeout = null;
            searchInput.addEventListener('keyup', function() {
                clearTimeout(timeout);
                timeout = setTimeout(function() {
                    // Kirim pencarian untuk 1+ karakter atau ketika dikosongkan
                    if (this.value.length >= 1 || this.value.length === 0) {
                        document.getElementById('searchForm').submit();
                    }
                }.bind(this), 600);
            });
        }
        document.querySelectorAll('.page-link').forEach(function(link) {
            link.addEventListener('click', function() { window.scrollTo({ top: 0, behavior: 'smooth' }); });
        });
        var alert = document.getElementById('success-alert');
        if (alert) {
            setTimeout(function() {
                alert.style.transition = "opacity 0.6s ease";
                alert.style.opacity = "0";
                setTimeout(function() { alert.remove(); }, 600);
            }, 3000);
        }

        // Prevent dropdown from closing when clicking inside
        document.querySelectorAll('.filter-dropdown .dropdown-menu').forEach(menu => {
            menu.addEventListener('click', function(e) {
                e.stopPropagation();
            });
        });

        // Inisialisasi popover peringatan nodin (klik icon → tampil yang belum lengkap)
        document.querySelectorAll('.js-nodin-popover').forEach(function(btn) {
            if (typeof bootstrap !== 'undefined' && bootstrap.Popover) {
                new bootstrap.Popover(btn, { container: 'body', customClass: 'popover-nodin' });
            }
        });

        // Preview Script (gunakan modal Bootstrap 5, support gambar & PDF)
        const buttons = document.querySelectorAll('.js-preview-file');
        const frame = document.getElementById('filePreviewFrame');
        const img = document.getElementById('filePreviewImage');
        const modalEl = document.getElementById('filePreviewModal');
        const labelEl = document.getElementById('filePreviewModalLabel');

        // Pastikan modal selalu berada langsung di body untuk z-index yang rapi
        document.addEventListener('show.bs.modal', function (event) {
            const m = event.target;
            if (m && m.parentElement !== document.body) {
                document.body.appendChild(m);
            }
        });

        buttons.forEach(btn => {
            btn.addEventListener('click', function () {
                const fileUrl = (this.getAttribute('data-file-url') || '').trim();
                const fileName = this.getAttribute('data-file-name') || 'File';
                if (!fileUrl) return;

                const lowerUrl = fileUrl.toLowerCase();
                const isImage = /\.(jpg|jpeg|png|gif|webp)(\?|#|$)/.test(lowerUrl);

                if (labelEl) {
                    labelEl.innerHTML = '<i class="ri-file-search-line me-2"></i> ' + fileName;
                }

                if (!frame || !img) {
                    window.open(fileUrl, '_blank');
                    return;
                }

                // Reset state
                frame.src = '';
                frame.style.display = 'none';
                img.src = '';
                img.style.display = 'none';

                if (isImage) {
                    img.onerror = function () { window.open(fileUrl, '_blank'); };
                    img.src = fileUrl;
                    img.style.display = 'block';
                } else {
                    img.onerror = null;
                    frame.src = fileUrl;
                    frame.style.display = 'block';
                }

                if (window.bootstrap && bootstrap.Modal) {
                    const modal = new bootstrap.Modal(modalEl);
                    modal.show();
                } else {
                    window.open(fileUrl, '_blank');
                }
            });
        });

        if (modalEl) {
            modalEl.addEventListener('hidden.bs.modal', function () {
                if (frame) { frame.src = ''; frame.style.display = 'none'; }
                if (img) { img.src = ''; img.style.display = 'none'; img.onerror = null; }
            });
        }
    });
</script>
@endsection
