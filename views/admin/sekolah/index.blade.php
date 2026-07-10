@extends('layouts.app')

@section('title', 'Sekolah | Admin - SMART Setjen DPR RI')
@section('content')
<link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

@include('partials.table_skin_lamaran_css')
<style>
    .info-note { background: #fffbeb; border: 1px solid rgba(176, 141, 72, 0.2); border-radius: 14px; padding: 0.9rem 1.1rem; }
    html[data-skin="dark"] .info-note { background: rgba(251, 191, 36, 0.08); border-color: rgba(251, 191, 36, 0.25); }
    .info-note .title { font-weight: 800; color: var(--primary-dark); }
    html[data-skin="dark"] .info-note .title { color: #f8fafc; }
    .info-note .desc { color: var(--text-muted); font-size: 0.85rem; margin-top: 4px; }

    /* Tabel untuk data besar: lebih rapih & compact */
    .dense-table .custom-table { min-width: 1100px; }
    .dense-table .custom-table thead th { padding: 0.85rem 1rem; }
    .dense-table .custom-table tbody td { padding: 1.05rem 0.9rem; }
    .dense-table .col-no { width: 70px; }
    .dense-table .col-name { min-width: 360px; }
    .dense-table .col-npsn { width: 140px; }
    .dense-table .col-grade { width: 110px; }
    .dense-table .col-prov { min-width: 170px; }
    .dense-table .col-reg { min-width: 170px; }
    .dense-table .col-dist { min-width: 170px; }
    .dense-table .name-clip {
        max-width: 480px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
</style>

<div class="content-wrapper">
    <div class="container-fluid px-md-5">
        <div class="page-title-area">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1">
                    <li class="breadcrumb-item">Menu</li>
                    <li class="breadcrumb-item">Master Data</li>
                    <li class="breadcrumb-item active">Sekolah</li>
                </ol>
            </nav>
            <h1 class="page-title">Data Sekolah</h1>
        </div>

        <div class="info-note mb-3">
            <div class="d-flex align-items-start gap-2">
                <i class="ri-information-line" style="margin-top:2px; color: var(--accent-gold);"></i>
                <div>
                    <div class="title">Informasi</div>
                    <div class="desc">
                        Apabila ingin memperbarui data, silakan perbarui di menu <strong>Perbarui Sekolah &amp; Universitas</strong> atau hubungi admin web.
                    </div>
                </div>
            </div>
        </div>

        <div class="modern-card">
            <div class="card-toolbar flex-wrap gap-4">
                <div class="d-flex align-items-center gap-3 flex-wrap">
                    <div class="d-flex align-items-center gap-2">
                        <div style="width: 5px; height: 25px; background: var(--gold-gradient); border-radius: 10px;"></div>
                        <h5 class="m-0 fw-800 text-dark">Daftar Sekolah</h5>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <form method="GET" action="{{ request()->url() }}" class="search-container" id="searchForm">
                        <div class="input-group">
                            <i class="ri-search-2-line" style="color: var(--accent-gold); font-size: 1.2rem;"></i>
                            <input type="text" name="search" id="search" class="form-control shadow-none"
                                   placeholder="Cari nama sekolah / NPSN..."
                                   value="{{ request('search', '') }}">
                            @if(request('search'))
                                <a href="{{ request()->url() }}?{{ http_build_query(request()->except(['search','page'])) }}" class="text-muted ms-2"><i class="ri-close-circle-fill"></i></a>
                            @endif
                        </div>
                        <input type="hidden" name="per_page" value="{{ request('per_page', 25) }}">
                    </form>
                </div>
            </div>

            <div class="px-4 py-3 d-flex align-items-center justify-content-between" style="background: rgba(248, 250, 252, 0.5);">
                <div class="text-muted fw-700" style="font-size: 0.85rem;">
                    Total Entri: <span class="text-dark">{{ $data->total() }}</span>
                </div>
                <form method="GET" action="{{ request()->url() }}" class="d-flex align-items-center gap-2">
                    <span class="fw-700 text-muted" style="font-size: 0.8rem;">Baris:</span>
                    <select name="per_page" class="form-select form-select-sm border-0 fw-800 shadow-sm"
                            style="border-radius: 8px; width: 90px; cursor: pointer;"
                            onchange="this.form.submit()">
                        @foreach([10, 25, 50, 100] as $size)
                            <option value="{{ $size }}" {{ (int) request('per_page', 25) === (int) $size ? 'selected' : '' }}>{{ $size }}</option>
                        @endforeach
                    </select>
                    <input type="hidden" name="search" value="{{ request('search') }}">
                </form>
            </div>

            <div class="table-container table-responsive dense-table">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th class="col-no">No</th>
                            <th>Nama</th>
                            <th class="col-npsn">NPSN</th>
                            <th class="col-grade">Jenjang</th>
                            <th class="col-prov">Provinsi</th>
                            <th class="col-reg">Kab/Kota</th>
                            <th class="col-dist">Kecamatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($data as $index => $item)
                            <tr>
                                <td>{{ ($data->currentPage() - 1) * $data->perPage() + $index + 1 }}</td>
                                <td class="fw-semibold col-name"><div class="name-clip" title="{{ $item->name ?? '' }}">{{ $item->name ?? '-' }}</div></td>
                                <td>{{ $item->npsn ?? '-' }}</td>
                                <td>{{ $item->grade ?? '-' }}</td>
                                <td>{{ $item->province_name ?? '-' }}</td>
                                <td>{{ $item->regency_name ?? '-' }}</td>
                                <td>{{ $item->district_name ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">Data tidak ditemukan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="footer-container">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-4">
                    <div class="text-muted fw-700" style="font-size: 0.85rem;">
                        Menampilkan <span class="text-dark">{{ $data->firstItem() ?? 0 }}</span> - <span class="text-dark">{{ $data->lastItem() ?? 0 }}</span> dari <span class="text-dark">{{ $data->total() }}</span> Data
                    </div>
                    <div class="pagination-wrapper">
                        {{ $data->appends(request()->query())->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

