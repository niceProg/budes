@extends('layouts.app')

@section('title', 'Rekap Absensi | Admin - SMART Setjen DPR RI')
@section('content')
<!-- Import Fonts & Icons -->
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">

@include('partials.table_skin_lamaran_css')
<style>
    .filter-box { background: rgba(255, 255, 255, 0.5); border-radius: 16px; padding: 1.5rem; border: 1px solid #e2e8f0; margin-bottom: 1.5rem; }
    .form-control-custom { border-radius: 10px; border: 1.5px solid #e2e8f0; padding: 0.6rem 1rem; font-weight: 500; transition: var(--transition); }
    .form-control-custom:focus { border-color: var(--accent-gold); box-shadow: 0 0 0 4px rgba(176, 141, 72, 0.1); }
    .pill-wfo { background: #eff6ff; color: #2563eb; }
    .pill-wfh { background: #f0fdf4; color: #16a34a; }
    .pill-izin { background: #fff7ed; color: #ea580c; }
    .pill-sakit { background: #fef2f2; color: #dc2626; }
    .late-tag { background: #fef2f2; color: #dc2626; padding: 2px 8px; border-radius: 6px; font-size: 0.7rem; font-weight: 800; text-transform: uppercase; display: inline-block; margin-bottom: 4px; }
    .btn-gold { background: var(--gold-gradient); color: white; border: none; font-weight: 700; padding: 0.7rem 1.5rem; border-radius: 10px; transition: var(--transition); }
    .btn-gold:hover { transform: translateY(-2px); box-shadow: 0 8px 15px rgba(142, 109, 47, 0.3); color: white; }
    .btn-outline-gold { border: 1.5px solid var(--accent-gold); color: var(--accent-gold); font-weight: 700; border-radius: 8px; transition: var(--transition); }
    .btn-outline-gold:hover { background: var(--accent-gold); color: white; }
    .modal-content { border-radius: 20px; border: none; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25); }
    .modal-header { border-bottom: 1px solid #f1f5f9; padding: 1.5rem; }
    .modal-title { font-weight: 800; color: var(--primary-dark); }
</style>

<div class="content-wrapper">
    <div class="container-fluid px-md-5">
        <div class="page-title-area">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1">
                    <li class="breadcrumb-item">Monitoring</li>
                    <li class="breadcrumb-item active">{{ isset($isMentor) && $isMentor ? 'Mentor Absensi' : 'Admin Absensi' }}</li>
                </ol>
            </nav>
            <h1 class="page-title">Data Kehadiran Peserta</h1>
        </div>

<div class="modern-card">
    @if (session('success'))
        <div class="alert alert-success d-flex align-items-center border-0 shadow-sm" id="success-alert">
            <i class="ri-checkbox-circle-fill me-2 ri-lg"></i> <div>{{ session('success') }}</div>
        </div>
        <script>setTimeout(() => { document.getElementById('success-alert').remove(); }, 3000);</script>
    @endif

            <div class="card-toolbar flex-wrap gap-4">
                <div class="d-flex align-items-center gap-2">
                    <div style="width: 5px; height: 25px; background: var(--gold-gradient); border-radius: 10px;"></div>
                    <h5 class="m-0 fw-800 text-dark">Daftar Absensi</h5>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <form method="GET" action="{{ request()->url() }}" class="search-container" id="searchForm">
                        <div class="input-group">
                            <i class="ri-search-2-line" style="color: var(--accent-gold); font-size: 1.2rem;"></i>
                            <input type="text" name="search" id="search" class="form-control shadow-none"
                                   placeholder="Cari nama peserta / jenis kehadiran..."
                                   value="{{ request('search', '') }}">
                            @if(request('search'))
                                <a href="{{ request()->url() }}?{{ http_build_query(request()->except(['search','page'])) }}" class="text-muted ms-2"><i class="ri-close-circle-fill"></i></a>
                            @endif
                        </div>
                        <input type="hidden" name="per_page" value="{{ request('per_page', 10) }}">
                        <input type="hidden" name="start_date" value="{{ request('start_date') }}">
                        <input type="hidden" name="end_date" value="{{ request('end_date') }}">
                        @foreach((array)request('filter_kategori', []) as $kat)
                            <input type="hidden" name="filter_kategori[]" value="{{ $kat }}">
                        @endforeach
                        @foreach((array)request('filter_jenis_kehadiran', []) as $jk)
                            <input type="hidden" name="filter_jenis_kehadiran[]" value="{{ $jk }}">
                        @endforeach
                    </form>
                    @if(!isset($isMentor) || !$isMentor)
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
                                <a class="dropdown-item" href="{{ route('absensi.admin.export', array_merge(request()->query(), ['scope' => 'all', 'format' => 'pdf'])) }}">
                                    PDF (Semua)
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('absensi.admin.export', array_merge(request()->query(), ['scope' => 'all', 'format' => 'xlsx'])) }}">
                                    Excel (Semua)
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li><h6 class="dropdown-header">Sesuai Pagination</h6></li>
                            <li>
                                <a class="dropdown-item" href="{{ route('absensi.admin.export', array_merge(request()->query(), ['scope' => 'page', 'page' => request('page', 1), 'format' => 'pdf'])) }}">
                                    PDF (Halaman ini)
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('absensi.admin.export', array_merge(request()->query(), ['scope' => 'page', 'page' => request('page', 1), 'format' => 'xlsx'])) }}">
                                    Excel (Halaman ini)
                                </a>
                            </li>
                        </ul>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Filter Section -->
            <div class="filter-section">
                <form method="GET" action="{{ isset($isMentor) && $isMentor ? route('absensi.mentor.index') : route('absensi.admin.index') }}" id="filterForm">
                    <div class="row g-3 align-items-end">
                        <!-- Row 1: Filter Tanggal -->
                        <div class="col-12 col-md-4">
                            <label class="form-label fw-bold small text-muted mb-0"><i class="ri-calendar-line"></i> Tanggal Mulai</label>
                            <input type="date" name="start_date" class="form-control form-control-custom mt-2"
                                   value="{{ request('start_date') }}" onclick="this.showPicker()">
                        </div>
                        <div class="col-12 col-md-4">
                            <label class="form-label fw-bold small text-muted mb-0"><i class="ri-calendar-check-line"></i> Tanggal Selesai</label>
                            <input type="date" name="end_date" class="form-control form-control-custom mt-2"
                                   value="{{ request('end_date') }}" onclick="this.showPicker()">
                        </div>
                        <div class="col-12 col-md-4 d-flex gap-2">
                            <button type="submit" name="filter_date" value="1" class="btn-filter-apply flex-grow-1">
                                <i class="ri-calendar-check-line"></i> Terapkan Tanggal
                            </button>
                            @if(request()->has('start_date') || request()->has('end_date'))
                                <a href="{{ request()->url() }}?{{ http_build_query(request()->except(['start_date', 'end_date', 'page'])) }}" class="btn-filter-reset">
                                    <i class="ri-close-line"></i> Reset
                                </a>
                            @endif
                        </div>

                        <!-- Row 2: Filter Kategori + Jenis Kehadiran (sejajar) -->
                        <div class="col-12 col-md-4">
                            <div class="dropdown filter-dropdown w-100">
                                <button class="btn dropdown-toggle w-100 justify-content-between" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <span>
                                        <i class="ri-filter-3-line"></i> Kategori
                                        @if(request()->has('filter_kategori'))
                                            <span class="badge filter-badge">{{ count((array)request('filter_kategori')) }}</span>
                                        @endif
                                    </span>
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
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="dropdown filter-dropdown w-100">
                                <button class="btn dropdown-toggle w-100 justify-content-between" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <span>
                                        <i class="ri-calendar-check-line"></i> Jenis Kehadiran
                                        @if(request()->has('filter_jenis_kehadiran'))
                                            <span class="badge filter-badge">{{ count((array)request('filter_jenis_kehadiran')) }}</span>
                                        @endif
                                    </span>
                                </button>
                                <ul class="dropdown-menu p-2" style="min-width: 180px;">
                                    <li>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="filter_jenis_kehadiran[]" value="WFO" id="jk_wfo"
                                                   {{ in_array('WFO', (array)request('filter_jenis_kehadiran', [])) ? 'checked' : '' }}>
                                            <label class="form-check-label w-100" for="jk_wfo">WFO</label>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="filter_jenis_kehadiran[]" value="WFH" id="jk_wfh"
                                                   {{ in_array('WFH', (array)request('filter_jenis_kehadiran', [])) ? 'checked' : '' }}>
                                            <label class="form-check-label w-100" for="jk_wfh">WFH</label>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="filter_jenis_kehadiran[]" value="Izin" id="jk_izin"
                                                   {{ in_array('Izin', (array)request('filter_jenis_kehadiran', [])) ? 'checked' : '' }}>
                                            <label class="form-check-label w-100" for="jk_izin">Izin</label>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="filter_jenis_kehadiran[]" value="Sakit" id="jk_sakit"
                                                   {{ in_array('Sakit', (array)request('filter_jenis_kehadiran', [])) ? 'checked' : '' }}>
                                            <label class="form-check-label w-100" for="jk_sakit">Sakit</label>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-12 col-md-4 d-flex gap-2">
                            <button type="submit" name="filter_category" value="1" class="btn-filter-apply flex-grow-1">
                                <i class="ri-filter-3-line"></i> Terapkan Filter
                            </button>
                            @if(request()->has('filter_kategori') || request()->has('filter_jenis_kehadiran'))
                                <a href="{{ request()->url() }}?{{ http_build_query(request()->except(['filter_kategori', 'filter_jenis_kehadiran', 'page'])) }}" class="btn-filter-reset">
                                    <i class="ri-close-line"></i> Reset
                                </a>
                            @endif
                        </div>

                        <input type="hidden" name="search" value="{{ request('search') }}">
                        <input type="hidden" name="per_page" value="{{ request('per_page', 10) }}">
                    </div>
                </form>
            </div>

            <div class="px-4 py-3 d-flex align-items-center justify-content-between" style="background: rgba(248, 250, 252, 0.5);">
                <div class="d-flex align-items-center gap-3">
                    <div class="text-muted fw-700" style="font-size: 0.85rem;">
                        Total Entri: <span class="text-dark">{{ $data->total() }}</span>
                    </div>
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-sm btn-success rounded-pill px-3" id="btnApproveSelected" disabled>
                            <i class="ri-checkbox-circle-line me-1"></i> Setujui Terpilih
                        </button>
                        <button type="button" class="btn btn-sm btn-danger rounded-pill px-3" id="btnRejectSelected" disabled>
                            <i class="ri-close-circle-line me-1"></i> Tolak Terpilih
                        </button>
                    </div>
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
                    <input type="hidden" name="start_date" value="{{ request('start_date') }}">
                    <input type="hidden" name="end_date" value="{{ request('end_date') }}">
                </form>
            </div>

            <div class="table-container table-responsive">
                <table class="custom-table">
            <thead>
                <tr>
                    <th style="width: 50px;">
                        <input type="checkbox" id="checkAll" class="form-check-input" title="Pilih Semua">
                    </th>
                    <th>No</th>
                    <th class="text-center">Tanggal</th>
                    <th>Nama Peserta</th>
                    <th>Jenis Magang</th>
                    <th class="text-center">Status</th>
                    <th class="text-center">Waktu Masuk</th>
                    <th class="text-center">Waktu Keluar</th>
                    <th class="text-center">Laporan/Bukti</th>
                    <th style="max-width:200px;">Catatan Harian</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                        @php use Carbon\Carbon; use Illuminate\Support\Str; @endphp
                        @forelse ($data as $index => $val)
                @php
                    $isWfoWfh = in_array($val->jenis_kehadiran, ['WFO', 'WFH']);
                    $isIzin = $val->jenis_kehadiran === 'Izin';
                    $isSakit = $val->jenis_kehadiran === 'Sakit';
                    $waktuMasukLocal = $val->waktu_masuk ? Carbon::parse($val->waktu_masuk) : null;
                    $waktuKeluarLocal = $val->waktu_keluar ? Carbon::parse($val->waktu_keluar) : null;
                    $checkInHour = $waktuMasukLocal ? $waktuMasukLocal->hour : null;
                    $isTerlambat = $isWfoWfh && $checkInHour !== null && $checkInHour >= 10;
                    $tanggalAbsensi = $waktuMasukLocal ? $waktuMasukLocal->locale('id')->translatedFormat('d F Y') : '-';
                    $jamMasuk = $waktuMasukLocal ? $waktuMasukLocal->format('H:i') : '-';
                    $jamKeluar = $waktuKeluarLocal ? $waktuKeluarLocal->format('H:i') : '-';
                @endphp
                <tr>
                    <td>
                        @if($val->status == 3)
                            <span class="text-muted small">
                                <i class="ri-checkbox-circle-fill text-success"></i> Disetujui
                            </span>
                        @elseif($val->status == 9)
                            <span class="text-muted small">
                                <i class="ri-close-circle-fill text-danger"></i> Ditolak
                            </span>
                        @else
                            <input type="checkbox" class="form-check-input presensi-checkbox" value="{{ $val->id }}" data-presensi-id="{{ $val->id }}" data-status="{{ $val->status }}">
                        @endif
                    </td>
                    <td>{{ ($data->currentPage() - 1) * $data->perPage() + $index + 1 }}</td>
                    <td class="text-center text-muted fw-700">{{ $tanggalAbsensi }}</td>
                    <td>
                        <div class="fw-bold text-dark">{{ $val->peserta->nama ?? '-' }}</div>
                        @if($val->peserta && $val->peserta->satker)
                            <small class="text-muted d-block mt-1">
                                <i class="ri-building-line"></i>{{ $val->peserta->satker->kode }}
                            </small>
                        @endif
                    </td>
                    <td>
                        @if($val->peserta && $val->peserta->lamaran)
                            <span class="category-badge">{{ ucwords($val->peserta->lamaran->kategori) }}</span>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td class="text-center">
                        <span class="status-pill pill-{{ strtolower($val->jenis_kehadiran) }}">
                            <i class="ri-{{ $isWfoWfh ? 'building-line' : 'file-list-3-line' }}"></i> {{ $val->jenis_kehadiran }}
                        </span>
                    </td>
                    <td class="text-center">
                        @if($isTerlambat) <span class="late-tag"><i class="ri-alarm-warning-line"></i> Terlambat</span><br> @endif
                        <span class="{{ $isTerlambat ? 'text-danger fw-bold' : 'text-muted' }}">
                            {{ $jamMasuk }}
                        </span>
                    </td>
                    <td class="text-center text-muted">{{ $jamKeluar }}</td>
                    <td class="text-center">
                        @if($val->bukti_kehadiran)
                            <button type="button" class="btn btn-sm btn-outline-gold js-preview-file"
                                data-file-url="{{ file_url($val->bukti_kehadiran) }}"
                                data-file-name="Bukti {{ $val->jenis_kehadiran }}">
                                <i class="ri-eye-line"></i> Lihat File
                            </button>
                        @else
                            <span class="text-muted small">Tidak ada bukti</span>
                        @endif
                    </td>
                    <td>
                        @if($val->keterangan)
                            <div class="small text-muted mb-1">{{ Str::words(strip_tags($val->keterangan), 8, '...') }}</div>
                            <a href="javascript:void(0)" class="fw-bold small text-decoration-none" style="color:var(--accent-gold);"
                                data-bs-toggle="modal" data-bs-target="#keteranganModal-{{ $val->id }}">
                                <i class="ri-book-open-line"></i> Selengkapnya
                            </a>
                        @else
                            <span class="text-muted small">-</span>
                        @endif
                    </td>
                    <td class="text-center">
                        @if($val->status != 3 && $val->status != 9)
                        <div class="btn-group btn-group-sm" role="group">
                            <button type="button" class="btn btn-success btn-sm approve-single" data-presensi-id="{{ $val->id }}" title="Setujui">
                                <i class="ri-check-line"></i>
                            </button>
                            <button type="button" class="btn btn-danger btn-sm reject-single" data-presensi-id="{{ $val->id }}" title="Tolak">
                                <i class="ri-close-line"></i>
                            </button>
                        </div>
                        @endif
                    </td>
                </tr>
                        @empty
                        <tr>
                            <td colspan="11" class="text-center py-5">
                                <img src="https://illustrations.popsy.co/white/abstract-art-4.svg" style="height: 150px; opacity: 0.5;">
                                <p class="text-muted mt-3 fw-700">Tidak ada data ditemukan.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="footer-container">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-4">
                    <div class="text-muted fw-700" style="font-size: 0.85rem;">
                        Menampilkan <span class="text-dark">{{ $data->firstItem() ?? 0 }}</span> - <span class="text-dark">{{ $data->lastItem() ?? 0 }}</span> dari <span class="text-dark">{{ $data->total() }}</span> Entri
                    </div>
                    <div class="pagination-wrapper">
                        {{ $data->appends(request()->query())->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Laporan Harian -->
@foreach ($data as $val)
    @if($val->keterangan)
    <div class="modal fade" id="keteranganModal-{{ $val->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="ri-chat-history-line text-warning"></i> Detail Laporan ({{ $val->jenis_kehadiran }})</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="fw-bold mb-2 text-dark">{{ $val->peserta->nama }}</div>
                    <div style="white-space: pre-wrap; color: var(--text-main); line-height: 1.6;">{!! nl2br(e($val->keterangan)) !!}</div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
    @endif
@endforeach

<!-- Modal Preview File (Global) -->
<div class="modal fade" id="filePreviewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content overflow-hidden">
            <div class="modal-header bg-light">
                <h5 class="modal-title" id="filePreviewModalLabel">Pratinjau Dokumen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0 d-flex justify-content-center align-items-center bg-dark" style="min-height: 70vh;">
                <iframe id="filePreviewFrame" src="" style="width: 100%; height: 80vh; border: 0; display: none;"></iframe>
                <img id="filePreviewImage" src="" alt="Preview" style="max-width: 100%; max-height: 80vh; display: none; object-fit: contain;" />
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Debounce Search (sama /lamaran)
        var searchInput = document.getElementById('search');
        if (searchInput) {
            var timeout = null;
            searchInput.addEventListener('keyup', function() {
                clearTimeout(timeout);
                timeout = setTimeout(function() {
                    if (this.value.length > 2 || this.value.length === 0) {
                        document.getElementById('searchForm').submit();
                    }
                }.bind(this), 800);
            });
        }
        // Scroll to top on pagination
        document.querySelectorAll('.page-link').forEach(function(link) {
            link.addEventListener('click', function() { window.scrollTo({ top: 0, behavior: 'smooth' }); });
        });

        // Prevent dropdown from closing when clicking inside
        document.querySelectorAll('.filter-dropdown .dropdown-menu').forEach(menu => {
            menu.addEventListener('click', function(e) {
                e.stopPropagation();
            });
        });

        // Fix for Bootstrap Modals Z-Index
        document.addEventListener('show.bs.modal', function(event) {
            const modalEl = event.target;
            if (modalEl && modalEl.parentElement !== document.body) {
                document.body.appendChild(modalEl);
            }
        });

        // File Preview Logic
        document.querySelectorAll('.js-preview-file').forEach(button => {
            button.addEventListener('click', function() {
                const fileUrl = this.getAttribute('data-file-url');
                const fileName = this.getAttribute('data-file-name');
                const lowerUrl = String(fileUrl || '').toLowerCase();
                // Cek ekstensi pada bagian path saja — abaikan query string presigned URL S3 (mis. ?X-Amz-Signature=...)
                const isImage = lowerUrl.match(/\.(jpg|jpeg|png|gif|webp)(\?|#|$)/);

                document.getElementById('filePreviewModalLabel').innerHTML = `<i class="ri-file-search-line me-2"></i> ${fileName}`;

                const frame = document.getElementById('filePreviewFrame');
                const img = document.getElementById('filePreviewImage');

                if (isImage) {
                    frame.style.display = 'none';
                    frame.src = '';
                    img.src = fileUrl;
                    img.style.display = 'block';
                } else {
                    img.style.display = 'none';
                    img.src = '';
                    frame.src = fileUrl;
                    frame.style.display = 'block';
                }

                const previewModal = new bootstrap.Modal(document.getElementById('filePreviewModal'));
                previewModal.show();
            });
        });

        // Clear preview on modal close
        document.getElementById('filePreviewModal').addEventListener('hidden.bs.modal', function () {
            document.getElementById('filePreviewFrame').src = '';
            document.getElementById('filePreviewImage').src = '';
        });

        // ===== Verifikasi Absensi =====
        // Check All functionality
        const checkAll = document.getElementById('checkAll');
        const btnApproveSelected = document.getElementById('btnApproveSelected');
        const btnRejectSelected = document.getElementById('btnRejectSelected');

        function getAllPresensiCheckboxes() {
            // Ambil semua checkbox dari tbody yang belum disetujui (status != 3)
            const tbody = document.querySelector('.table-container tbody');
            let checkboxes;
            if (tbody) {
                checkboxes = tbody.querySelectorAll('.presensi-checkbox');
            } else {
                checkboxes = document.querySelectorAll('.presensi-checkbox');
            }
            // Filter hanya yang belum disetujui
            return Array.from(checkboxes).filter(cb => {
                const status = parseInt(cb.getAttribute('data-status') || '0');
                return status !== 3; // Hanya ambil yang belum disetujui
            });
        }

        function updateButtonStates() {
            const checked = getAllPresensiCheckboxes();
            const checkedCount = checked.filter(cb => cb.checked).length;
            const hasChecked = checkedCount > 0;
            if (btnApproveSelected) btnApproveSelected.disabled = !hasChecked;
            if (btnRejectSelected) btnRejectSelected.disabled = !hasChecked;
        }

        if (checkAll) {
            checkAll.addEventListener('change', function(e) {
                e.stopPropagation();
                const presensiCheckboxes = getAllPresensiCheckboxes();
                const isChecked = this.checked;
                presensiCheckboxes.forEach(cb => {
                    const status = parseInt(cb.getAttribute('data-status') || '0');
                    if (status !== 3) { // Hanya centang yang belum disetujui
                        cb.checked = isChecked;
                    }
                });
                updateButtonStates();
            });
        }

        // Event delegation untuk checkbox individual
        const tableContainer = document.querySelector('.table-container');
        if (tableContainer) {
            tableContainer.addEventListener('change', function(e) {
                    if (e.target && e.target.classList.contains('presensi-checkbox')) {
                        const presensiCheckboxes = getAllPresensiCheckboxes();
                        if (checkAll && presensiCheckboxes.length > 0) {
                            const allChecked = presensiCheckboxes.every(c => c.checked);
                            const someChecked = presensiCheckboxes.some(c => c.checked);
                            checkAll.checked = allChecked;
                            checkAll.indeterminate = someChecked && !allChecked;
                        }
                        updateButtonStates();
                    }
            });
        }

        // Verifikasi bulk
        function verifikasiBulk(action, presensiIdsParam = null) {
            let presensiIds;

            if (presensiIdsParam && presensiIdsParam.length > 0) {
                presensiIds = presensiIdsParam;
            } else {
                const checked = getAllPresensiCheckboxes().filter(cb => cb.checked);
                if (checked.length === 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Peringatan',
                        text: 'Pilih minimal satu absensi untuk diverifikasi.',
                    });
                    return;
                }
                presensiIds = checked.map(cb => parseInt(cb.value));
            }
            const actionText = action === 'approve' ? 'menyetujui' : 'menolak';
            const confirmText = action === 'approve'
                ? `Apakah Anda yakin ingin menyetujui ${presensiIds.length} absensi?`
                : `Apakah Anda yakin ingin menolak ${presensiIds.length} absensi?`;

            Swal.fire({
                title: 'Konfirmasi Verifikasi',
                text: confirmText,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: action === 'approve' ? '#28a745' : '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: action === 'approve' ? 'Ya, Setujui' : 'Ya, Tolak',
                cancelButtonText: 'Batal',
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch('{{ route("absensi.verifikasi") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        },
                        body: JSON.stringify({
                            presensi_ids: presensiIds,
                            action: action,
                        }),
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: data.message,
                                timer: 2000,
                                showConfirmButton: false,
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: data.message || 'Terjadi kesalahan saat memverifikasi absensi.',
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Terjadi kesalahan saat memverifikasi absensi.',
                        });
                    });
                }
            });
        }

        btnApproveSelected.addEventListener('click', () => verifikasiBulk('approve'));
        btnRejectSelected.addEventListener('click', () => verifikasiBulk('reject'));

        // Verifikasi single
        document.querySelectorAll('.approve-single').forEach(btn => {
            btn.addEventListener('click', function() {
                const presensiId = parseInt(this.getAttribute('data-presensi-id'));
                verifikasiBulk('approve', [presensiId]);
            });
        });

        document.querySelectorAll('.reject-single').forEach(btn => {
            btn.addEventListener('click', function() {
                const presensiId = parseInt(this.getAttribute('data-presensi-id'));
                verifikasiBulk('reject', [presensiId]);
            });
        });

        // Update button states on load
        updateButtonStates();
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection
