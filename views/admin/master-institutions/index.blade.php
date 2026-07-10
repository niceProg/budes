@extends('layouts.app')

@section('title', 'Perbarui Sekolah & Universitas | Admin - SMART Setjen DPR RI')
@section('content')
<link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
@include('partials.table_skin_lamaran_css')
<style>
    .sync-card {
        border: 1px solid rgba(15, 23, 42, 0.08);
        border-radius: 16px;
        background: #fff;
    }
    .sync-header {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 1.25rem;
    }
    .sync-header-line {
        width: 5px;
        min-height: 48px;
        background: var(--gold-gradient);
        border-radius: 10px;
    }
    .sync-title {
        font-size: 1.05rem;
        font-weight: 800;
        color: var(--primary-dark, #0f172a);
        margin: 0;
    }
    .btn-gold {
        background: var(--gold-gradient);
        color: #fff;
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: 12px;
        font-weight: 700;
        transition: var(--transition);
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    .btn-gold:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 8px 15px rgba(176, 141, 72, 0.3);
        color: #fff;
    }
    .btn-gold:disabled { opacity: 0.65; cursor: not-allowed; }
    .stat-box {
        background: rgba(248, 250, 252, 0.9);
        border-radius: 14px;
        padding: 1rem 1.25rem;
        border: 1px solid rgba(0, 0, 0, 0.06);
    }
    html[data-skin="dark"] .sync-card {
        background: rgba(2, 6, 23, 0.85);
        border-color: #334155;
    }
    html[data-skin="dark"] .sync-title { color: #f8fafc; }
    html[data-skin="dark"] .stat-box {
        background: rgba(15, 23, 42, 0.6);
        border-color: #334155;
    }
    .diff-section-title {
        font-size: 0.95rem;
        font-weight: 800;
        color: var(--primary-dark, #0f172a);
        margin: 1.25rem 0 0.5rem;
    }
    html[data-skin="dark"] .diff-section-title { color: #f8fafc; }
    .diff-table-wrap { overflow-x: auto; border-radius: 12px; border: 1px solid rgba(0,0,0,.06); }
    html[data-skin="dark"] .diff-table-wrap { border-color: #334155; }
    .diff-table { width: 100%; font-size: 0.875rem; margin: 0; }
    .diff-table th {
        background: rgba(248, 250, 252, 0.95);
        font-weight: 700;
        padding: 0.6rem 0.75rem;
        border-bottom: 1px solid rgba(0,0,0,.08);
    }
    html[data-skin="dark"] .diff-table th { background: rgba(30, 41, 59, 0.9); border-color: #334155; color: #e2e8f0; }
    .diff-table td { padding: 0.5rem 0.75rem; vertical-align: middle; border-bottom: 1px solid rgba(0,0,0,.05); }
    html[data-skin="dark"] .diff-table td { border-color: #334155; color: #e2e8f0; }
    .diff-row-add { background: rgba(34, 197, 94, 0.14); }
    .diff-row-remove { background: rgba(239, 68, 68, 0.14); }
    .diff-badge { font-size: 0.75rem; font-weight: 800; padding: 0.2rem 0.5rem; border-radius: 8px; }
    .diff-badge-add { background: rgba(34, 197, 94, 0.25); color: #15803d; }
    .diff-badge-remove { background: rgba(239, 68, 68, 0.22); color: #b91c1c; }
    html[data-skin="dark"] .diff-badge-add { color: #86efac; }
    html[data-skin="dark"] .diff-badge-remove { color: #fca5a5; }
</style>

<div class="content-wrapper">
    <div class="container-fluid px-md-5">
        <div class="page-title-area">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1">
                    <li class="breadcrumb-item">Menu</li>
                    <li class="breadcrumb-item">Master Data</li>
                    <li class="breadcrumb-item active">Sekolah &amp; Universitas</li>
                </ol>
            </nav>
            <h1 class="page-title">Sekolah &amp; Universitas</h1>
        </div>

        @if (session('success'))
            <div class="alert alert-success border-0 shadow-sm mb-3">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger border-0 shadow-sm mb-3">{{ session('error') }}</div>
        @endif

        <div class="modern-card sync-card mb-4">
            <div class="p-4">
                <div class="sync-header">
                    <div class="sync-header-line"></div>
                    <div>
                        <h5 class="sync-title">Perbarui data sekolah dan universitas</h5>
                        <p class="text-muted mb-0 mt-2" style="font-size: 0.9rem; max-width: 52rem;">
                            Data dari API dimuat ke tabel sementara terlebih dahulu. Jika ada perbedaan dengan data saat ini,
                            Anda akan melihat ringkasan penambahan (hijau) dan pengurangan (merah) sebelum menerapkan ke tabel utama.
                        </p>
                    </div>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <div class="stat-box">
                            <div class="text-muted small fw-700">Universitas</div>
                            <div class="fs-4 fw-800 text-dark">{{ number_format($countUniv) }}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="stat-box">
                            <div class="text-muted small fw-700">Sekolah</div>
                            <div class="fs-4 fw-800 text-dark">{{ number_format($countSekolah) }}</div>
                        </div>
                    </div>
                </div>

                @if (! $hasApiKey)
                    <div class="alert alert-warning border-0 shadow-sm">
                        <i class="ri-error-warning-line me-2"></i>
                        Set <code>API_CO_ID_KEY</code> di <code>.env</code> agar sinkron bisa jalan.
                    </div>
                @elseif ($syncState['pending_review'] ?? false)
                    <div class="alert alert-info border-0 shadow-sm mb-3">
                        <i class="ri-eye-line me-2"></i>
                        <strong>Menunggu persetujuan.</strong> Tinjau perubahan di bawah. <strong>Terima</strong> untuk memindahkan ke tabel utama,
                        <strong>Batal</strong> untuk membuang data sementara, atau <strong>Muat ulang</strong> untuk mengambil ulang dari API.
                    </div>

                    <form method="GET" action="{{ url()->current() }}" class="d-flex flex-wrap align-items-center gap-2 mb-3">
                        @foreach(request()->except('per_page') as $key => $value)
                            @if(is_array($value))
                                @foreach($value as $v)
                                    <input type="hidden" name="{{ $key }}[]" value="{{ $v }}">
                                @endforeach
                            @else
                                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                            @endif
                        @endforeach
                        <span class="text-muted small fw-700">Baris per tabel:</span>
                        <select name="per_page" class="form-select form-select-sm border-0 fw-800 shadow-sm"
                                style="border-radius: 8px; width: 88px; cursor: pointer;"
                                onchange="this.form.submit()">
                            @foreach([10, 25, 50, 100] as $size)
                                <option value="{{ $size }}" {{ (int) ($perPage ?? 25) === $size ? 'selected' : '' }}>{{ $size }}</option>
                            @endforeach
                        </select>
                    </form>

                    @php
                        $u = $diffPreview['universities'] ?? null;
                        $s = $diffPreview['schools'] ?? null;
                        $scope = $diffPreview['scope'] ?? 'all';
                    @endphp

                    @if ($scope === 'all' || $scope === 'universities')
                        <h6 class="diff-section-title"><i class="ri-building-line me-1"></i> Universitas</h6>
                        <div class="row g-3 mb-2">
                            <div class="col-md-6">
                                <span class="diff-badge diff-badge-add">+ Penambahan: {{ number_format($u['add_count'] ?? 0) }}</span>
                            </div>
                            <div class="col-md-6">
                                <span class="diff-badge diff-badge-remove">− Pengurangan: {{ number_format($u['remove_count'] ?? 0) }}</span>
                            </div>
                        </div>
                        <p class="text-muted small mb-2">Gunakan paginasi di bawah setiap tabel untuk melihat semua baris.</p>
                        <div class="mb-4">
                            <div class="fw-700 small text-success mb-1">Penambahan (ada di API, belum di tabel utama)</div>
                            <div class="diff-table-wrap mb-3">
                                <table class="diff-table table mb-0">
                                    <thead><tr><th>ID</th><th>Nama</th><th>Provinsi</th></tr></thead>
                                    <tbody>
                                        @forelse (($u['add'] ?? collect()) as $row)
                                            <tr class="diff-row-add"><td>{{ $row->id }}</td><td>{{ $row->name }}</td><td>{{ $row->province ?? '—' }}</td></tr>
                                        @empty
                                            <tr><td colspan="3" class="text-muted">Tidak ada</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            @if(isset($u['add']) && $u['add']->total() > 0)
                                <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-2 mt-2 mb-3">
                                    <div class="text-muted small">{{ $u['add']->firstItem() ?? 0 }}–{{ $u['add']->lastItem() ?? 0 }} dari {{ number_format($u['add']->total()) }}</div>
                                    <div class="pagination-wrapper mb-0">{{ $u['add']->links('pagination::bootstrap-4') }}</div>
                                </div>
                            @endif
                            <div class="fw-700 small text-danger mb-1">Pengurangan (ada di tabel utama, tidak ada di hasil API saat ini)</div>
                            <div class="diff-table-wrap">
                                <table class="diff-table table mb-0">
                                    <thead><tr><th>ID</th><th>Nama</th><th>Provinsi</th></tr></thead>
                                    <tbody>
                                        @forelse (($u['remove'] ?? collect()) as $row)
                                            <tr class="diff-row-remove"><td>{{ $row->id }}</td><td>{{ $row->name }}</td><td>{{ $row->province ?? '—' }}</td></tr>
                                        @empty
                                            <tr><td colspan="3" class="text-muted">Tidak ada</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            @if(isset($u['remove']) && $u['remove']->total() > 0)
                                <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-2 mt-2">
                                    <div class="text-muted small">{{ $u['remove']->firstItem() ?? 0 }}–{{ $u['remove']->lastItem() ?? 0 }} dari {{ number_format($u['remove']->total()) }}</div>
                                    <div class="pagination-wrapper mb-0">{{ $u['remove']->links('pagination::bootstrap-4') }}</div>
                                </div>
                            @endif
                        </div>
                    @endif

                    @if ($scope === 'all' || $scope === 'schools')
                        <h6 class="diff-section-title"><i class="ri-school-line me-1"></i> Sekolah</h6>
                        <div class="row g-3 mb-2">
                            <div class="col-md-6">
                                <span class="diff-badge diff-badge-add">+ Penambahan: {{ number_format($s['add_count'] ?? 0) }}</span>
                            </div>
                            <div class="col-md-6">
                                <span class="diff-badge diff-badge-remove">− Pengurangan: {{ number_format($s['remove_count'] ?? 0) }}</span>
                            </div>
                        </div>
                        <p class="text-muted small mb-2">Gunakan paginasi di bawah setiap tabel untuk melihat semua baris.</p>
                        <div class="mb-4">
                            <div class="fw-700 small text-success mb-1">Penambahan</div>
                            <div class="diff-table-wrap mb-3">
                                <table class="diff-table table mb-0">
                                    <thead><tr><th>ID</th><th>NPSN</th><th>Nama</th><th>Jenjang</th><th>Provinsi</th></tr></thead>
                                    <tbody>
                                        @forelse (($s['add'] ?? collect()) as $row)
                                            <tr class="diff-row-add">
                                                <td>{{ $row->id }}</td>
                                                <td>{{ $row->npsn ?? '—' }}</td>
                                                <td>{{ $row->name }}</td>
                                                <td>{{ $row->grade ?? '—' }}</td>
                                                <td>{{ $row->province_name ?? '—' }}</td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="5" class="text-muted">Tidak ada</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            @if(isset($s['add']) && $s['add']->total() > 0)
                                <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-2 mt-2 mb-3">
                                    <div class="text-muted small">{{ $s['add']->firstItem() ?? 0 }}–{{ $s['add']->lastItem() ?? 0 }} dari {{ number_format($s['add']->total()) }}</div>
                                    <div class="pagination-wrapper mb-0">{{ $s['add']->links('pagination::bootstrap-4') }}</div>
                                </div>
                            @endif
                            <div class="fw-700 small text-danger mb-1">Pengurangan</div>
                            <div class="diff-table-wrap">
                                <table class="diff-table table mb-0">
                                    <thead><tr><th>ID</th><th>NPSN</th><th>Nama</th><th>Jenjang</th><th>Provinsi</th></tr></thead>
                                    <tbody>
                                        @forelse (($s['remove'] ?? collect()) as $row)
                                            <tr class="diff-row-remove">
                                                <td>{{ $row->id }}</td>
                                                <td>{{ $row->npsn ?? '—' }}</td>
                                                <td>{{ $row->name }}</td>
                                                <td>{{ $row->grade ?? '—' }}</td>
                                                <td>{{ $row->province_name ?? '—' }}</td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="5" class="text-muted">Tidak ada</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            @if(isset($s['remove']) && $s['remove']->total() > 0)
                                <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-2 mt-2">
                                    <div class="text-muted small">{{ $s['remove']->firstItem() ?? 0 }}–{{ $s['remove']->lastItem() ?? 0 }} dari {{ number_format($s['remove']->total()) }}</div>
                                    <div class="pagination-wrapper mb-0">{{ $s['remove']->links('pagination::bootstrap-4') }}</div>
                                </div>
                            @endif
                        </div>
                    @endif

                    <div class="d-flex flex-wrap gap-2 align-items-center">
                        <form method="POST" action="{{ route('master.institutions.apply') }}" class="d-inline" id="formMasterInstApply">
                            @csrf
                            <button type="button" class="btn btn-success fw-700" id="btnMasterInstApply">
                                <i class="ri-check-line"></i> Terima &amp; terapkan
                            </button>
                        </form>
                        <form method="POST" action="{{ route('master.institutions.discard') }}" class="d-inline" id="formMasterInstDiscard">
                            @csrf
                            <button type="button" class="btn btn-outline-danger fw-700" id="btnMasterInstDiscard">
                                <i class="ri-close-line"></i> Batal
                            </button>
                        </form>
                        <form method="POST" action="{{ route('master.institutions.sync') }}" class="d-inline"
                              onsubmit="document.getElementById('btnReloadInst').disabled=true; document.getElementById('btnReloadInst').innerHTML='<span class=\'spinner-border spinner-border-sm\'></span> Memuat…';">
                            @csrf
                            <input type="hidden" name="size" value="50">
                            <button type="submit" class="btn btn-gold" id="btnReloadInst">
                                <i class="ri-refresh-line"></i> Muat ulang dari API
                            </button>
                        </form>
                    </div>
                @else
                    <form method="POST" action="{{ route('master.institutions.sync') }}" id="formInstitutionSync"
                          onsubmit="document.getElementById('btnSyncInst').disabled=true; document.getElementById('btnSyncInst').innerHTML='<span class=\'spinner-border spinner-border-sm\'></span> Memproses…';">
                        @csrf
                        <input type="hidden" name="size" value="50">
                        <div class="alert alert-warning border-0 shadow-sm mb-3">
                            <i class="ri-time-line me-2"></i>
                            Proses pembaruan dapat memakan waktu <strong>sangat lama</strong> (perkiraan <strong>10–45 menit</strong>). Mohon <strong>jangan refresh</strong> halaman atau <strong>menutup tab</strong> sampai selesai.
                        </div>
                        <button type="submit" class="btn btn-gold" id="btnSyncInst">
                            <i class="ri-refresh-line"></i> Perbarui data sekolah dan universitas
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>

@if ($syncState['pending_review'] ?? false)
<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (typeof Swal === 'undefined') return;

        var formApply = document.getElementById('formMasterInstApply');
        var btnApply = document.getElementById('btnMasterInstApply');
        if (formApply && btnApply) {
            btnApply.addEventListener('click', function () {
                Swal.fire({
                    icon: 'question',
                    title: 'Terima data dari API?',
                    html: 'Semua perubahan hasil sinkron akan <strong>diterapkan</strong> ke tabel <strong>universitas</strong> dan <strong>sekolah</strong>. Data utama akan disesuaikan dengan hasil API.<br><br>Lanjutkan?',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, terima & terapkan',
                    cancelButtonText: 'Kembali',
                    confirmButtonColor: '#198754',
                    cancelButtonColor: '#64748b',
                    focusCancel: true,
                    reverseButtons: true
                }).then(function (r) {
                    if (r.isConfirmed) formApply.submit();
                });
            });
        }

        var formDiscard = document.getElementById('formMasterInstDiscard');
        var btnDiscard = document.getElementById('btnMasterInstDiscard');
        if (formDiscard && btnDiscard) {
            btnDiscard.addEventListener('click', function () {
                Swal.fire({
                    icon: 'warning',
                    title: 'Batalkan data sementara?',
                    html: 'Data staging dari API akan <strong>dibuang</strong>. Tabel utama <strong>tidak berubah</strong>.',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, batalkan',
                    cancelButtonText: 'Kembali',
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#64748b',
                    focusCancel: true,
                    reverseButtons: true
                }).then(function (r) {
                    if (r.isConfirmed) formDiscard.submit();
                });
            });
        }
    });
</script>
@endif

@if (session('sync_no_changes'))
<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'info',
                title: 'Tidak ada perubahan',
                text: 'Tidak ada penambahan atau pengurangan dibanding data saat ini. Data sudah selaras dengan hasil API.',
                confirmButtonText: 'Mengerti'
            });
        }
    });
</script>
@endif
@endsection
