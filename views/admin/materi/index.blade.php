@extends('layouts.app')

@section('title', 'Materi | Admin - SMART Setjen DPR RI')
@section('content')
<link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
@include('partials.table_skin_lamaran_css')

<style>
    .btn-primary { background: var(--gold-gradient); border: none; padding: 0.7rem 1.5rem; border-radius: 12px; font-weight: 700; color: white; transition: var(--transition); display: inline-flex; align-items: center; gap: 8px; }
    .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 8px 15px rgba(176, 141, 72, 0.3); color: white; }
    .badge-status { padding: 6px 14px; border-radius: 10px; font-weight: 700; font-size: 0.75rem; }
    .status-nonaktif { background: #e2e8f0; color: #334155; }
    .status-aktif { background: #d1fae5; color: #065f46; }
    html[data-skin="dark"] .status-nonaktif { background: #334155; color: #e5e7eb; }
    .action-btn-group { display: flex; gap: 8px; justify-content: center; flex-wrap: wrap; }
    .btn-action { width: 38px; height: 38px; display: inline-flex; align-items: center; justify-content: center; border-radius: 10px; transition: var(--transition); border: 1px solid #e2e8f0; text-decoration: none; cursor: pointer; }
    .btn-detail { color: #0284c7; background: #f0f9ff; }
    .btn-detail:hover { background: #0284c7; color: white; }
    .btn-edit { color: var(--accent-gold); background: #fffbeb; }
    .btn-edit:hover { background: var(--accent-gold); color: white; }
    .btn-delete { color: #ef4444; background: #fef2f2; }
    .btn-delete:hover { background: #ef4444; color: white; }
    .btn-status { color: #6366f1; background: #eef2ff; }
    .btn-status:hover { background: #6366f1; color: white; }
    .status-filter-btn { padding: 0.5rem 1rem; border-radius: 8px; font-weight: 600; font-size: 0.85rem; border: 2px solid transparent; transition: all 0.3s; text-decoration: none; display: inline-block; }
    .status-filter-btn.active { border-color: var(--accent-gold); background: var(--gold-light); color: var(--accent-gold); }
    .status-filter-btn:not(.active) { background: #f1f5f9; color: #64748b; }
    .status-filter-btn:not(.active):hover { background: #e2e8f0; }
</style>

<div class="content-wrapper">
    <div class="container-fluid px-md-5">
        <div class="page-title-area">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1">
                    <li class="breadcrumb-item">Menu</li>
                    <li class="breadcrumb-item active">Materi</li>
                </ol>
            </nav>
            <h1 class="page-title">Manajemen Materi</h1>
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
                        <h5 class="m-0 fw-800 text-dark">Daftar Materi</h5>
                    </div>
                    <a href="{{ route('materi.create') }}" class="btn btn-primary">
                        <i class="ri-add-fill"></i> Tambah Materi
                    </a>
                    <a href="{{ route('materi.trash') }}" class="btn btn-sm" style="font-weight: 700; padding: 0.5rem; border-radius: 8px; width: 40px; height: 40px; display: inline-flex; align-items: center; justify-content: center; background-color: #dc2626 !important; border-color: #dc2626 !important; color: white !important;">
                        <i class="ri-delete-bin-6-line" title="Tempat Sampah" style="font-size: 1.2rem; color: white !important;"></i>
                    </a>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <form method="GET" action="{{ request()->url() }}" class="search-container" id="searchFormMateri">
                        <div class="input-group">
                            <i class="ri-search-2-line" style="color: var(--accent-gold); font-size: 1.2rem;"></i>
                            <input type="text" name="search" id="searchMateri" class="form-control shadow-none"
                                   placeholder="Cari judul / menu / narasumber..."
                                   value="{{ request('search', '') }}">
                            @if(request('search'))
                                <a href="{{ request()->url() }}?{{ http_build_query(request()->except(['search','page'])) }}" class="text-muted ms-2"><i class="ri-close-circle-fill"></i></a>
                            @endif
                        </div>
                        <input type="hidden" name="per_page" value="{{ request('per_page', 10) }}">
                        <input type="hidden" name="publish" value="{{ request('publish', 'published') }}">
                    </form>
                </div>
            </div>

            <div class="px-4 py-3 d-flex align-items-center gap-3 flex-wrap" style="background: rgba(248, 250, 252, 0.5); border-bottom: 1px solid #e2e8f0;">
                <span class="fw-700 text-muted" style="font-size: 0.85rem;">Filter:</span>
                <a href="{{ request()->url() }}?{{ http_build_query(array_merge(request()->except(['publish', 'page']), ['publish' => 'draft'])) }}"
                   class="status-filter-btn {{ request('publish', 'published') == 'draft' ? 'active' : '' }}">
                    Draft
                </a>
                <a href="{{ request()->url() }}?{{ http_build_query(array_merge(request()->except(['publish', 'page']), ['publish' => 'published'])) }}"
                   class="status-filter-btn {{ request('publish', 'published') == 'published' ? 'active' : '' }}">
                    Published
                </a>
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
                    <input type="hidden" name="publish" value="{{ request('publish', 'published') }}">
                </form>
            </div>

            <div class="table-container table-responsive">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Judul</th>
                            <th>Menu</th>
                            <th>Satuan Kerja</th>
                            <th>Narasumber</th>
                            <th>Status</th>
                            <th>Publish</th>
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
                                <td>{{ $item->narasumber ?? '-' }}</td>
                                <td>
                                    @php
                                        $statusClass = $item->status == 1 ? 'status-aktif' : 'status-nonaktif';
                                        $statusText = $item->status == 1 ? 'Aktif' : 'Nonaktif';
                                    @endphp
                                    <span class="badge-status {{ $statusClass }}">{{ $statusText }}</span>
                                </td>
                                <td>
                                    <span class="badge-status {{ $item->is_publish ? 'status-aktif' : 'status-nonaktif' }}">
                                        {{ $item->is_publish ? 'Published' : 'Draft' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="action-btn-group">
                                        <div class="dropdown d-inline-block">
                                            <button class="btn-action btn-status" type="button" id="publishDropdownMateri{{ $item->id }}"
                                                    data-bs-toggle="dropdown" aria-expanded="false" title="Ubah Publish"
                                                    data-current-publish="{{ $item->is_publish ? 1 : 0 }}">
                                                <i class="ri-settings-3-line"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="publishDropdownMateri{{ $item->id }}">
                                                <li>
                                                    <a class="dropdown-item materi-publish-change-btn {{ !$item->is_publish ? 'active' : '' }}"
                                                       href="#"
                                                       data-id="{{ $item->id }}"
                                                       data-publish="0"
                                                       data-text="Draft">
                                                        <i class="ri-eye-off-line me-2"></i> Draft
                                                        @if(!$item->is_publish) <i class="ri-check-line ms-auto"></i> @endif
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item materi-publish-change-btn {{ $item->is_publish ? 'active' : '' }}"
                                                       href="#"
                                                       data-id="{{ $item->id }}"
                                                       data-publish="1"
                                                       data-text="Published">
                                                        <i class="ri-global-line me-2"></i> Published
                                                        @if($item->is_publish) <i class="ri-check-line ms-auto"></i> @endif
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>

                                        <a href="{{ route('materi.show', $item->id) }}" class="btn-action btn-detail" title="Detail">
                                            <i class="ri-eye-line"></i>
                                        </a>
                                        <a href="{{ route('materi.edit', $item->id) }}" class="btn-action btn-edit" title="Edit">
                                            <i class="ri-edit-line"></i>
                                        </a>
                                        <form action="{{ route('materi.destroy', $item->id) }}" method="POST" style="display: inline;" class="form-materi-delete">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-action btn-delete" title="Pindah ke Tempat Sampah">
                                                <i class="ri-delete-bin-6-line"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5">
                                    <p class="text-muted mt-3 fw-700">Tidak ada data yang ditemukan.</p>
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
        var si = document.getElementById('searchMateri');
        if (si) {
            var t = null;
            si.addEventListener('keyup', function() {
                clearTimeout(t);
                t = setTimeout(function() {
                    if (this.value.length > 2 || this.value.length === 0) {
                        document.getElementById('searchFormMateri').submit();
                    }
                }.bind(this), 800);
            });
        }

        // Publish dropdown
        document.querySelectorAll('.materi-publish-change-btn').forEach(function(btn) {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const id = this.getAttribute('data-id');
                const publish = this.getAttribute('data-publish');
                const publishText = this.getAttribute('data-text');
                const dropdownBtn = document.getElementById('publishDropdownMateri' + id);
                const currentPublish = dropdownBtn ? dropdownBtn.getAttribute('data-current-publish') : null;

                if (currentPublish !== null && String(currentPublish) === String(publish)) return;

                Swal.fire({
                    title: 'Ubah Publish Materi?',
                    text: `Apakah Anda yakin ingin mengubah publish materi menjadi "${publishText}"?`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Ubah',
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#b08d48',
                    cancelButtonColor: '#6b7280'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch(`{{ url('admin/materi') }}/${id}/publish`, {
                            method: 'PATCH',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({ is_publish: publish })
                        })
                        .then(r => r.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire({ title: 'Berhasil!', text: data.message || 'Publish materi diperbarui.', icon: 'success', timer: 1500, showConfirmButton: false })
                                    .then(() => location.reload());
                            } else {
                                Swal.fire('Error!', data.message || 'Gagal memperbarui publish.', 'error');
                            }
                        })
                        .catch(() => Swal.fire('Error!', 'Terjadi kesalahan saat memperbarui publish.', 'error'));
                    }
                });
            });
        });

        // Delete confirmation
        document.querySelectorAll('.form-materi-delete').forEach(function(form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Hapus Materi?',
                    text: 'Tindakan ini tidak dapat dibatalkan.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Hapus',
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#b91c1c',
                    cancelButtonColor: '#6b7280'
                }).then((result) => {
                    if (result.isConfirmed) form.submit();
                });
            });
        });
    });
</script>
@endsection

