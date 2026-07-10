@extends('layouts.app')

@section('title', 'Broadcast Email | Admin - SMART Setjen DPR RI')
@php
    $routePrefix = $routePrefix ?? 'broadcast-email';
    $channel = $channel ?? 'email';
    $isWebIndex = $channel === 'web';
@endphp

@section('content')
@include('partials.table_skin_lamaran_css')
<style>
    .broadcast-list .btn-primary { background: var(--gold-gradient); border: none; padding: 0.7rem 1.5rem; border-radius: 12px; font-weight: 700; color: white; transition: var(--transition); display: inline-flex; align-items: center; gap: 8px; }
    .broadcast-list .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 8px 15px rgba(176, 141, 72, 0.3); color: white; }
    .broadcast-list .badge-status { padding: 6px 14px; border-radius: 10px; font-weight: 700; font-size: 0.75rem; }
    .broadcast-list .status-queued { background: #eff6ff; color: #1d4ed8; }
    .broadcast-list .status-processing { background: #fff7ed; color: #c2410c; }
    .broadcast-list .status-done { background: #d1fae5; color: #065f46; }
    .broadcast-list .status-failed { background: #fee2e2; color: #b91c1c; }
    .broadcast-list .action-btn-group { display: flex; gap: 8px; justify-content: center; flex-wrap: wrap; }
    .broadcast-list .btn-action { width: 38px; height: 38px; display: inline-flex; align-items: center; justify-content: center; border-radius: 10px; transition: var(--transition); border: 1px solid #e2e8f0; text-decoration: none; cursor: pointer; }
    .broadcast-list .btn-detail { color: #0284c7; background: #f0f9ff; }
    .broadcast-list .btn-detail:hover { background: #0284c7; color: white; }
    .broadcast-list .btn-edit { color: var(--accent-gold); background: #fffbeb; }
    .broadcast-list .btn-edit:hover { background: var(--accent-gold); color: white; }
    .broadcast-list .btn-delete { color: #ef4444; background: #fef2f2; }
    .broadcast-list .btn-delete:hover { background: #ef4444; color: white; }
    .broadcast-list .status-filter-btn { padding: 0.5rem 1rem; border-radius: 8px; font-weight: 600; font-size: 0.85rem; border: 2px solid transparent; transition: all 0.3s; text-decoration: none; display: inline-block; }
    .broadcast-list .status-filter-btn.active { border-color: var(--accent-gold); background: var(--gold-light); color: var(--accent-gold); }
    .broadcast-list .status-filter-btn:not(.active) { background: #f1f5f9; color: #64748b; }
    .broadcast-list .status-filter-btn:not(.active):hover { background: #e2e8f0; }
    html[data-skin="dark"] .broadcast-list .status-filter-btn:not(.active) { background: #1e293b; color: #cbd5e1; }
    html[data-skin="dark"] .broadcast-list .status-filter-btn.active { background: rgba(251, 191, 36, 0.12); color: #fbbf24; border-color: rgba(251, 191, 36, 0.5); }
</style>

<div class="content-wrapper">
    <div class="container-fluid px-md-5">
        <div class="page-title-area">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1">
                    <li class="breadcrumb-item">Menu</li>
                    <li class="breadcrumb-item">Sistem Magang</li>
                    <li class="breadcrumb-item active">{{ $isWebIndex ? 'Pengumuman Web' : 'Broadcast Email' }}</li>
                </ol>
            </nav>
            <h1 class="page-title">{{ $isWebIndex ? 'Riwayat Pengumuman Web' : 'Riwayat Broadcast Email' }}</h1>
            <p class="text-muted mb-0" style="font-size: 0.9rem;">
                @if($isWebIndex)
                    Pengumuman yang diterbitkan ke aplikasi peserta (tanpa email). Tambah baru atau kelola dari tabel di bawah.
                @else
                    Daftar pengumuman yang pernah dikirim lewat email. Tambah baru atau kelola dari tabel di bawah.
                @endif
            </p>
        </div>

        <div class="broadcast-list mb-5">
            <div class="modern-card">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show mx-4 mt-4 mb-0" role="alert" style="border-radius: 12px; border: 0;">
                        <i class="ri-check-line me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show mx-4 mt-4 mb-0" role="alert" style="border-radius: 12px; border: 0;">
                        <i class="ri-error-warning-line me-2"></i>{{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="card-toolbar flex-wrap gap-4">
                    <div class="d-flex align-items-center gap-3 flex-wrap">
                        <div class="d-flex align-items-center gap-2">
                            <div style="width: 5px; height: 25px; background: var(--gold-gradient); border-radius: 10px;"></div>
                            <h5 class="m-0 fw-800 text-dark">{{ $isWebIndex ? 'Riwayat Pengumuman Web' : 'Riwayat Broadcast Email' }}</h5>
                        </div>
                        <a href="{{ route($routePrefix.'.create') }}" class="btn btn-primary">
                            <i class="ri-add-line"></i> Tambah Pengumuman
                        </a>
                    </div>
                    <div class="d-flex align-items-center gap-3">
                        <form method="GET" action="{{ route($routePrefix.'.index') }}" class="search-container" id="searchFormBroadcast">
                            <div class="input-group">
                                <i class="ri-search-2-line" style="color: var(--accent-gold); font-size: 1.2rem;"></i>
                                <input type="text" name="search" id="searchBroadcast" class="form-control shadow-none"
                                       placeholder="Cari subjek / target..."
                                       value="{{ request('search', '') }}">
                                @if(request('search'))
                                    <a href="{{ route($routePrefix.'.index', request()->except(['search', 'page'])) }}" class="text-muted ms-2" title="Hapus pencarian"><i class="ri-close-circle-fill"></i></a>
                                @endif
                            </div>
                            <input type="hidden" name="per_page" value="{{ request('per_page', 10) }}">
                            <input type="hidden" name="status" value="{{ $statusFilter ?? 'all' }}">
                        </form>
                    </div>
                </div>

                <div class="px-4 py-3 d-flex align-items-center gap-3 flex-wrap" style="background: rgba(248, 250, 252, 0.5); border-bottom: 1px solid #e2e8f0;">
                    <span class="fw-700 text-muted" style="font-size: 0.85rem;">Status:</span>
                    @php $sf = $statusFilter ?? 'all'; @endphp
                    <a href="{{ route($routePrefix.'.index', array_merge(request()->except(['status', 'page']), ['status' => 'all'])) }}"
                       class="status-filter-btn {{ $sf === 'all' ? 'active' : '' }}">Semua</a>
                    <a href="{{ route($routePrefix.'.index', array_merge(request()->except(['status', 'page']), ['status' => 'queued'])) }}"
                       class="status-filter-btn {{ $sf === 'queued' ? 'active' : '' }}">Antrian</a>
                    <a href="{{ route($routePrefix.'.index', array_merge(request()->except(['status', 'page']), ['status' => 'processing'])) }}"
                       class="status-filter-btn {{ $sf === 'processing' ? 'active' : '' }}">Diproses</a>
                    <a href="{{ route($routePrefix.'.index', array_merge(request()->except(['status', 'page']), ['status' => 'done'])) }}"
                       class="status-filter-btn {{ $sf === 'done' ? 'active' : '' }}">Selesai</a>
                    <a href="{{ route($routePrefix.'.index', array_merge(request()->except(['status', 'page']), ['status' => 'failed'])) }}"
                       class="status-filter-btn {{ $sf === 'failed' ? 'active' : '' }}">Gagal</a>
                </div>

                <div class="px-4 py-3 d-flex align-items-center justify-content-between" style="background: rgba(248, 250, 252, 0.5);">
                    <div class="text-muted fw-700" style="font-size: 0.85rem;">
                        Total: <span class="text-dark">{{ $broadcasts->total() }}</span>
                    </div>
                    <form method="GET" action="{{ route($routePrefix.'.index') }}" class="d-flex align-items-center gap-2">
                        <span class="fw-700 text-muted" style="font-size: 0.8rem;">Baris:</span>
                        <select name="per_page" class="form-select form-select-sm border-0 fw-800 shadow-sm"
                                style="border-radius: 8px; width: 80px; cursor: pointer;"
                                onchange="this.form.submit()">
                            @foreach([10, 25, 50, 100] as $size)
                                <option value="{{ $size }}" {{ (int) request('per_page', 10) === $size ? 'selected' : '' }}>{{ $size }}</option>
                            @endforeach
                        </select>
                        <input type="hidden" name="search" value="{{ request('search') }}">
                        <input type="hidden" name="status" value="{{ $sf }}">
                    </form>
                </div>

                <div class="table-container table-responsive">
                    <table class="custom-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Subjek</th>
                                <th>Target</th>
                                <th>Status</th>
                                <th>{{ $isWebIndex ? 'Peserta' : 'Penerima' }}</th>
                                <th>Waktu</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($broadcasts as $index => $item)
                                @php
                                    $status = strtolower((string) ($item->process_status ?? 'queued'));
                                    $statusClass = match ($status) {
                                        'done' => 'done',
                                        'failed' => 'failed',
                                        'processing' => 'processing',
                                        default => 'queued',
                                    };
                                    $statusLabel = match ($status) {
                                        'done' => 'Selesai',
                                        'failed' => 'Gagal',
                                        'processing' => 'Diproses',
                                        default => 'Antrian',
                                    };
                                    $targetLabel = ($item->channel ?? 'email') === 'web'
                                        ? 'Semua peserta (aplikasi)'
                                        : ($item->target_type === 'khusus'
                                            ? 'Pengiriman khusus'
                                            : ('Semua peserta' . ($item->target_scope ? ' (' . $item->target_scope . ')' : '')));
                                    $canEdit = (int) $item->sent_recipients === 0;
                                @endphp
                                <tr>
                                    <td>{{ ($broadcasts->currentPage() - 1) * $broadcasts->perPage() + $index + 1 }}</td>
                                    <td>
                                        <div class="applicant-name">{{ \Illuminate\Support\Str::limit($item->subject, 80) }}</div>
                                        <div class="small text-muted">
                                            Dibuat: {{ $item->created_at ? $item->created_at->locale('id')->translatedFormat('d F Y H:i') : '' }}
                                        </div>
                                    </td>
                                    <td><span class="badge-code">{{ $targetLabel }}</span></td>
                                    <td><span class="badge-status status-{{ $statusClass }}">{{ $statusLabel }}</span></td>
                                    <td>
                                        @if($isWebIndex)
                                            <span class="small text-muted">Total {{ (int) $item->total_recipients }}</span><br>
                                            <span class="small text-success">Diterbitkan {{ (int) $item->sent_recipients }}</span>
                                        @else
                                            <span class="small text-muted">Total {{ (int) $item->total_recipients }}</span><br>
                                            <span class="small text-success">OK {{ (int) $item->sent_recipients }}</span>
                                            @if((int) $item->failed_recipients > 0)
                                                <span class="small text-danger"> · Gagal {{ (int) $item->failed_recipients }}</span>
                                            @endif
                                        @endif
                                    </td>
                                    <td>
                                        @if($item->processed_at)
                                            <span class="small">{{ $isWebIndex ? 'Selesai terbit' : 'Selesai kirim' }}<br>{{ $item->processed_at->locale('id')->translatedFormat('d F Y H:i') }}</span>
                                        @else
                                            <span class="text-muted small">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="action-btn-group">
                                            <a href="{{ route($routePrefix.'.show', $item) }}" class="btn-action btn-detail" title="Detail">
                                                <i class="ri-eye-line"></i>
                                            </a>
                                            @if($canEdit)
                                                <a href="{{ route($routePrefix.'.edit', $item) }}" class="btn-action btn-edit" title="Ubah">
                                                    <i class="ri-edit-line"></i>
                                                </a>
                                            @endif
                                            <form action="{{ route($routePrefix.'.destroy', $item) }}" method="POST" class="form-broadcast-delete" style="display:inline;" data-no-global-loader>
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn-action btn-delete" title="Hapus">
                                                    <i class="ri-delete-bin-6-line"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5">
                                        <p class="text-muted mt-3 fw-700">Belum ada data broadcast.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="footer-container">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-4">
                        <div class="text-muted fw-700" style="font-size: 0.85rem;">
                            Menampilkan <span class="text-dark">{{ $broadcasts->firstItem() ?? 0 }}</span> – <span class="text-dark">{{ $broadcasts->lastItem() ?? 0 }}</span> dari <span class="text-dark">{{ $broadcasts->total() }}</span> data
                        </div>
                        <div class="pagination-wrapper">
                            {{ $broadcasts->appends(request()->query())->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('script')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var searchBroadcast = document.getElementById('searchBroadcast');
        if (searchBroadcast) {
            var searchBroadcastTimer = null;
            searchBroadcast.addEventListener('keyup', function () {
                clearTimeout(searchBroadcastTimer);
                searchBroadcastTimer = setTimeout(function () {
                    if (this.value.length > 2 || this.value.length === 0) {
                        document.getElementById('searchFormBroadcast').submit();
                    }
                }.bind(this), 800);
            });
        }

        document.querySelectorAll('.form-broadcast-delete').forEach(function (form) {
            form.addEventListener('submit', function (e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Hapus pengumuman?',
                    text: 'Riwayat broadcast ini akan dihapus beserta daftar penerimanya.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Hapus',
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#b91c1c',
                    cancelButtonColor: '#6b7280'
                }).then(function (result) {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    });
</script>
@endpush
@endsection
