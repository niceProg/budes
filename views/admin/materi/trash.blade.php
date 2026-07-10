@extends('layouts.app')

@section('title', 'Sampah Materi | Admin - SMART Setjen DPR RI')
@section('content')
@include('partials.table_skin_lamaran_css')
<style>
    .btn-primary { background: var(--gold-gradient); border: none; padding: 0.7rem 1.5rem; border-radius: 12px; font-weight: 700; color: white; transition: var(--transition); display: inline-flex; align-items: center; gap: 8px; }
    .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 8px 15px rgba(176, 141, 72, 0.3); color: white; }
    .action-btn-group { display: flex; gap: 8px; justify-content: center; flex-wrap: wrap; }
    .btn-action { width: 38px; height: 38px; display: inline-flex; align-items: center; justify-content: center; border-radius: 10px; transition: var(--transition); border: 1px solid #e2e8f0; text-decoration: none; cursor: pointer; }
    .btn-restore { color: #16a34a; background: #dcfce7; }
    .btn-restore:hover { background: #16a34a; color: #fff; }
    .btn-force { color: #ef4444; background: #fef2f2; }
    .btn-force:hover { background: #ef4444; color: #fff; }
</style>

<div class="content-wrapper">
    <div class="container-fluid px-md-5">
        <div class="page-title-area">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1">
                    <li class="breadcrumb-item">Materi</li>
                    <li class="breadcrumb-item active">Trash</li>
                </ol>
            </nav>
            <h1 class="page-title">Trash Materi</h1>
        </div>

        <div class="modern-card">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4" role="alert" style="border-radius: 12px; border: 0;">
                    <i class="ri-check-line me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert" style="border-radius: 12px; border: 0;">
                    <i class="ri-error-warning-line me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="card-toolbar flex-wrap gap-4">
                <div class="d-flex align-items-center gap-3 flex-wrap">
                    <div class="d-flex align-items-center gap-2">
                        <div style="width: 5px; height: 25px; background: var(--gold-gradient); border-radius: 10px;"></div>
                        <h5 class="m-0 fw-800 text-dark">Materi di Trash</h5>
                    </div>
                    <a href="{{ route('materi.index') }}" class="btn btn-light" style="border-radius: 12px; font-weight: 800;">
                        <i class="ri-arrow-left-line"></i> Kembali
                    </a>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <form method="GET" action="{{ request()->url() }}" class="search-container" id="searchFormMateriTrash">
                        <div class="input-group">
                            <i class="ri-search-2-line" style="color: var(--accent-gold); font-size: 1.2rem;"></i>
                            <input type="text" name="search" id="searchMateriTrash" class="form-control shadow-none"
                                   placeholder="Cari judul / navbar / narasumber..."
                                   value="{{ request('search', '') }}">
                        </div>
                        <input type="hidden" name="per_page" value="{{ request('per_page', 10) }}">
                    </form>
                </div>
            </div>

            <div class="px-4 py-3 d-flex align-items-center justify-content-between" style="background: rgba(248, 250, 252, 0.5);">
                <div class="text-muted fw-700" style="font-size: 0.85rem;">
                    Total Trash: <span class="text-dark">{{ $data->total() }}</span>
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
                </form>
            </div>

            <div class="table-container table-responsive">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Judul</th>
                            <th>Navbar</th>
                            <th>Satuan Kerja</th>
                            <th>Dihapus</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($data as $index => $item)
                            <tr>
                                <td>{{ ($data->currentPage() - 1) * $data->perPage() + $index + 1 }}</td>
                                <td>
                                    <div class="applicant-name">{{ $item->judul }}</div>
                                    <div class="small text-muted">
                                        Update: {{ $item->updated_at ? $item->updated_at->locale('id')->translatedFormat('d F Y H:i') : '' }}
                                    </div>
                                </td>
                                <td>
                                    <span class="badge-code">
                                        @if($item->navbar_icon)
                                            <i class="{{ $item->navbar_icon }}"></i>
                                        @endif
                                        {{ $item->navbar_nama ?? '-' }}
                                    </span>
                                </td>
                                <td>
                                    @if($item->satker)
                                        <span class="badge-code">{{ $item->satker->nama }}</span>
                                    @else
                                        <span class="text-muted">Semua Unit</span>
                                    @endif
                                </td>
                                <td>{{ $item->updated_at ? $item->updated_at->locale('id')->translatedFormat('d F Y H:i') : '-' }}</td>
                                <td>
                                    <div class="action-btn-group">
                                        <form action="{{ route('materi.restore', $item->id) }}" method="POST" class="form-materi-restore">
                                            @csrf
                                            <button type="submit" class="btn-action btn-restore" title="Restore">
                                                <i class="ri-arrow-go-back-line"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('materi.force-destroy', $item->id) }}" method="POST" class="form-materi-force">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-action btn-force" title="Hapus Permanen">
                                                <i class="ri-delete-bin-7-line"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <p class="text-muted mt-3 fw-700">Trash kosong.</p>
                                </td>
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

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // confirm restore
        document.querySelectorAll('.form-materi-restore').forEach(function(form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Restore Materi?',
                    text: 'Materi akan dikembalikan dari Trash.',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Restore',
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#16a34a',
                    cancelButtonColor: '#6b7280'
                }).then((r) => { if (r.isConfirmed) form.submit(); });
            });
        });

        // confirm force delete
        document.querySelectorAll('.form-materi-force').forEach(function(form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Hapus Permanen?',
                    text: 'Materi akan dihapus permanen dan tidak bisa dikembalikan.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Hapus',
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#b91c1c',
                    cancelButtonColor: '#6b7280'
                }).then((r) => { if (r.isConfirmed) form.submit(); });
            });
        });
    });
</script>
@endsection

