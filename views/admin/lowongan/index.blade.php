@extends('layouts.app')

@section('title', 'Lowongan | Admin - SMART Setjen DPR RI')
@section('content')
<link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

@include('partials.table_skin_lamaran_css')
<style>
    .btn-primary { background: var(--gold-gradient); border: none; padding: 0.7rem 1.5rem; border-radius: 12px; font-weight: 700; color: white; transition: var(--transition); display: inline-flex; align-items: center; gap: 8px; }
    .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 8px 15px rgba(176, 141, 72, 0.3); color: white; }
    .badge-status { padding: 6px 14px; border-radius: 10px; font-weight: 700; font-size: 0.75rem; }
    .status-draft { background: #fef3c7; color: #92400e; }
    .status-aktif { background: #d1fae5; color: #065f46; }
    .status-tidak-aktif { background: #fee2e2; color: #991b1b; }
    .action-btn-group { display: flex; gap: 8px; justify-content: center; flex-wrap: wrap; }
    .btn-action { width: 38px; height: 38px; display: inline-flex; align-items: center; justify-content: center; border-radius: 10px; transition: var(--transition); border: 1px solid #e2e8f0; text-decoration: none; }
    .btn-detail { color: #0284c7; background: #f0f9ff; }
    .btn-detail:hover { background: #0284c7; color: white; }
    .btn-edit { color: var(--accent-gold); background: #fffbeb; }
    .btn-edit:hover { background: var(--accent-gold); color: white; }
    .btn-delete { color: #ef4444; background: #fef2f2; }
    .btn-delete:hover { background: #ef4444; color: white; }
    .btn-status { color: #6366f1; background: #eef2ff; }
    .btn-status:hover { background: #6366f1; color: white; }
    .foto-preview { width: 60px; height: 60px; object-fit: cover; border-radius: 8px; border: 2px solid #e2e8f0; }
    .status-change-btn { cursor: pointer; }
    .status-change-btn.active { background: #f0f9ff; color: #0284c7; font-weight: 600; }
    .status-change-btn:not(.active):hover { background: #f8fafc; }
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
                    <li class="breadcrumb-item">Master Data</li>
                    <li class="breadcrumb-item active">Lowongan</li>
                </ol>
            </nav>
            <h1 class="page-title">Manajemen Lowongan</h1>
        </div>

        <div class="modern-card">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4" role="alert" style="border-radius: 12px; border: 0; box-shadow: 0 2px 8px rgba(16, 185, 129, 0.15);">
                    <i class="ri-check-line me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert" style="border-radius: 12px; border: 0; box-shadow: 0 2px 8px rgba(239, 68, 68, 0.15);">
                    <i class="ri-error-warning-line me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="card-toolbar flex-wrap gap-4">
                <div class="d-flex align-items-center gap-3 flex-wrap">
                    <div class="d-flex align-items-center gap-2">
                        <div style="width: 5px; height: 25px; background: var(--gold-gradient); border-radius: 10px;"></div>
                        <h5 class="m-0 fw-800 text-dark">Daftar Lowongan</h5>
                    </div>
                    <a href="{{ route('lowongan.create') }}" class="btn btn-primary">
                        <i class="ri-add-fill"></i> Tambah Lowongan
                    </a>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <form method="GET" action="{{ request()->url() }}" class="search-container" id="searchForm">
                        <div class="input-group">
                            <i class="ri-search-2-line" style="color: var(--accent-gold); font-size: 1.2rem;"></i>
                            <input type="text" name="search" id="search" class="form-control shadow-none"
                                   placeholder="Cari judul atau detail..."
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

            <!-- Publish Filter -->
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
                            <th>Foto</th>
                            <th>Judul Lowongan</th>
                            <th>Satuan Kerja</th>
                            <th>Jumlah Posisi</th>
                            <th>Deadline</th>
                            <th>Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($data as $index => $item)
                        <tr>
                            <td>{{ ($data->currentPage() - 1) * $data->perPage() + $index + 1 }}</td>
                            <td>
                                @if($item->foto)
                                    <img src="{{ file_url('lowongan/' . $item->foto) }}" alt="Foto" class="foto-preview" onerror="this.onerror=null; this.src='data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'60\' height=\'60\'%3E%3Crect fill=\'%23f1f5f9\' width=\'60\' height=\'60\'/%3E%3Ctext x=\'50%25\' y=\'50%25\' text-anchor=\'middle\' dy=\'.3em\' fill=\'%2364748b\' font-size=\'20\'%3E%3F%3C/text%3E%3C/svg%3E';">
                                @else
                                    <div class="foto-preview d-flex align-items-center justify-content-center bg-light text-muted" style="font-size: 0.7rem;">
                                        <i class="ri-image-line"></i>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <div class="applicant-name">{{ $item->title }}</div>
                                <div class="small text-muted" style="max-width: 300px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                    {{ Str::limit(strip_tags($item->detail), 20) }}
                                </div>
                            </td>
                            <td>
                                @if($item->satker)
                                    <span class="badge-code">{{ $item->satker->nama }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($item->jumlah_posisi)
                                    <span class="fw-700">{{ $item->jumlah_posisi }}</span>&nbsp;&nbsp; posisi
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($item->deadline)
                                    {{ \Carbon\Carbon::parse($item->deadline)->locale('id')->translatedFormat('d F Y') }}
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @php
                                    $statusClass = match($item->status) {
                                        1 => 'status-aktif',
                                        9 => 'status-tidak-aktif',
                                        default => 'status-tidak-aktif'
                                    };
                                    $statusText = match($item->status) {
                                        1 => 'Aktif',
                                        9 => 'Tidak Aktif',
                                        default => 'Unknown'
                                    };
                                @endphp
                                <span class="badge-status {{ $statusClass }}">{{ $statusText }}</span>
                            </td>
                            <td>
                                <div class="action-btn-group">
                                    <div class="dropdown d-inline-block">
                                        <button class="btn-action btn-status" type="button" id="publishDropdown{{ $item->id }}" data-bs-toggle="dropdown" aria-expanded="false" title="Ubah Publish" data-current-publish="{{ $item->is_publish ? 1 : 0 }}">
                                            <i class="ri-settings-3-line"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="publishDropdown{{ $item->id }}">
                                            <li>
                                                <a class="dropdown-item publish-change-btn {{ !$item->is_publish ? 'active' : '' }}"
                                                   href="#"
                                                   data-id="{{ $item->id }}"
                                                   data-publish="0"
                                                   data-text="Draft">
                                                    <i class="ri-eye-off-line me-2"></i> Draft
                                                    @if(!$item->is_publish) <i class="ri-check-line ms-auto"></i> @endif
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item publish-change-btn {{ $item->is_publish ? 'active' : '' }}"
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
                                    <a href="{{ route('lowongan.show', $item->id) }}" class="btn-action btn-detail" title="Detail">
                                        <i class="ri-eye-line"></i>
                                    </a>
                                    <a href="{{ route('lowongan.edit', $item->id) }}" class="btn-action btn-edit" title="Edit">
                                        <i class="ri-edit-line"></i>
                                    </a>
                                    <form action="{{ route('lowongan.destroy', $item->id) }}" method="POST" style="display: inline;" class="form-lowongan-delete">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-action btn-delete" title="Hapus">
                                            <i class="ri-delete-bin-line"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <img src="https://illustrations.popsy.co/white/abstract-art-4.svg" style="height: 150px; opacity: 0.5;">
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
        var si = document.getElementById('search');
        if (si) {
            var t = null;
            si.addEventListener('keyup', function() {
                clearTimeout(t);
                t = setTimeout(function() {
                    if (this.value.length > 2 || this.value.length === 0) {
                        document.getElementById('searchForm').submit();
                    }
                }.bind(this), 800);
            });
        }

        // Scroll ke atas saat pindah halaman
        document.querySelectorAll('.page-link').forEach(function(l) {
            l.addEventListener('click', function() {
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });
        });

        // Konfirmasi ubah publish via SweetAlert
        document.querySelectorAll('.publish-change-btn').forEach(function(btn) {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const id = this.getAttribute('data-id');
                const publish = this.getAttribute('data-publish');
                const publishText = this.getAttribute('data-text');
                const dropdownBtn = document.getElementById('publishDropdown' + id);
                const currentPublish = dropdownBtn ? dropdownBtn.getAttribute('data-current-publish') : null;

                // Skip jika publish sama
                if (currentPublish !== null && String(currentPublish) === String(publish)) {
                    return;
                }

                Swal.fire({
                    title: 'Ubah Publish Lowongan?',
                    text: `Apakah Anda yakin ingin mengubah publish lowongan menjadi "${publishText}"?`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Ubah',
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#b08d48',
                    cancelButtonColor: '#6b7280'
                }).then((result) => {
                    if (result.isConfirmed) {
                        updatePublish(id, publish, publishText, btn);
                    }
                });
            });
        });

        // Konfirmasi hapus via SweetAlert
        document.querySelectorAll('.form-lowongan-delete').forEach(function(form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Hapus Lowongan?',
                    text: 'Apakah Anda yakin ingin menghapus lowongan ini? Tindakan ini tidak dapat dibatalkan.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Hapus',
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#b91c1c',
                    cancelButtonColor: '#6b7280'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    });

    function updatePublish(id, publish, publishText, btnElement) {
        const btn = btnElement;
        const originalHtml = btn.innerHTML;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Memproses...';
        btn.style.pointerEvents = 'none';

        fetch(`{{ url('admin/lowongan') }}/${id}/publish`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ is_publish: publish })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    title: 'Berhasil!',
                    text: data.message || 'Publish lowongan berhasil diperbarui.',
                    icon: 'success',
                    timer: 1800,
                    showConfirmButton: false
                }).then(() => location.reload());
            } else {
                Swal.fire('Error!', data.message || 'Terjadi kesalahan saat memperbarui publish.', 'error');
                btn.innerHTML = originalHtml;
                btn.style.pointerEvents = 'auto';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire('Error!', 'Terjadi kesalahan saat memperbarui publish.', 'error');
            btn.innerHTML = originalHtml;
            btn.style.pointerEvents = 'auto';
        });
    }
</script>
@endsection
