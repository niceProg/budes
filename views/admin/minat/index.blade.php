@extends('layouts.app')

@section('title', 'Minat | Admin - SMART Setjen DPR RI')
@section('content')
<link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

@include('partials.table_skin_lamaran_css')
<style>
    .btn-primary { background: var(--gold-gradient); border: none; padding: 0.7rem 1.5rem; border-radius: 12px; font-weight: 700; color: white; transition: var(--transition); display: inline-flex; align-items: center; gap: 8px; }
    .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 8px 15px rgba(176, 141, 72, 0.3); color: white; }
    .badge-status { padding: 6px 14px; border-radius: 10px; font-weight: 800; font-size: 0.7rem; border: 1px solid transparent; }
    .badge-status.active { background: #ecfdf5; color: #047857; border-color: #a7f3d0; }
    .badge-status.inactive { background: #fff1f2; color: #be123c; border-color: #fecdd3; }
    .action-btn-group { display: flex; gap: 8px; justify-content: center; flex-wrap: wrap; }
    .btn-action { width: 38px; height: 38px; display: inline-flex; align-items: center; justify-content: center; border-radius: 10px; transition: var(--transition); border: 1px solid #e2e8f0; text-decoration: none; }
    .btn-detail { color: #0284c7; background: #f0f9ff; }
    .btn-detail:hover { background: #0284c7; color: white; }
    .btn-edit { color: var(--accent-gold); background: #fffbeb; }
    .btn-edit:hover { background: var(--accent-gold); color: white; }
    .btn-delete { color: #ef4444; background: #fef2f2; }
    .btn-delete:hover { background: #ef4444; color: white; }
</style>

<div class="content-wrapper">
    <div class="container-fluid px-md-5">
        <div class="page-title-area">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1">
                    <li class="breadcrumb-item">Menu</li>
                    <li class="breadcrumb-item">Master Data</li>
                    <li class="breadcrumb-item active">Minat</li>
                </ol>
            </nav>
            <h1 class="page-title">Data Minat</h1>
        </div>

        <div class="modern-card">
            <div class="card-toolbar flex-wrap gap-4">
                <div class="d-flex align-items-center gap-3 flex-wrap">
                    <div class="d-flex align-items-center gap-2">
                        <div style="width: 5px; height: 25px; background: var(--gold-gradient); border-radius: 10px;"></div>
                        <h5 class="m-0 fw-800 text-dark">Daftar Minat</h5>
                    </div>
                    <button type="button" class="btn btn-primary" onclick="showAddModal()">
                        <i class="ri-add-fill"></i> Tambah Data
                    </button>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <form method="GET" action="{{ request()->url() }}" class="search-container" id="searchForm">
                        <div class="input-group">
                            <i class="ri-search-2-line" style="color: var(--accent-gold); font-size: 1.2rem;"></i>
                            <input type="text" name="search" id="search" class="form-control shadow-none"
                                   placeholder="Cari nama minat / satuan kerja..."
                                   value="{{ request('search', '') }}">
                            @if(request('search'))
                                <a href="{{ request()->url() }}?{{ http_build_query(request()->except(['search','page'])) }}" class="text-muted ms-2"><i class="ri-close-circle-fill"></i></a>
                            @endif
                        </div>
                        <input type="hidden" name="per_page" value="{{ request('per_page', 10) }}">
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
                            <th>Nama Minat</th>
                            <th>Satuan Kerja Terkait</th>
                            <th>Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($data as $index => $item)
                        <tr>
                            <td>{{ ($data->currentPage() - 1) * $data->perPage() + $index + 1 }}</td>
                            <td class="fw-semibold">{{ $item->nama }}</td>
                            <td>{{ $item->satker?->nama ?? '-' }}</td>
                            <td>
                                <span class="badge-status {{ $item->is_active ? 'active' : 'inactive' }}">
                                    {{ $item->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                            <td>
                                <div class="action-btn-group">
                                    <a href="{{ route('minat.show', $item->id) }}" class="btn-action btn-detail" title="Detail">
                                        <i class="ri-eye-line"></i>
                                    </a>
                                    <button type="button"
                                            class="btn-action btn-edit"
                                            onclick="showEditModal({{ $item->id }}, @js($item->nama), {{ $item->id_satker ? (int) $item->id_satker : 'null' }}, {{ $item->is_active ? 'true' : 'false' }})"
                                            title="Edit">
                                        <i class="ri-edit-line"></i>
                                    </button>
                                    <button type="button" class="btn-action btn-delete" onclick="deleteMinat({{ $item->id }})" title="Hapus">
                                        <i class="ri-delete-bin-line"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
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

<script>
    const minatSatkerOptions = @json(($satker ?? collect())->map(fn($u) => ['id' => (int)$u->id, 'label' => trim(($u->nama_satker ?? $u->nama ?? '') . ' (' . ($u->kode_satker ?? $u->kode ?? '-') . ')')])->values());

    function renderSatkerSelect(selectedId) {
        const normalized = selectedId === null || selectedId === undefined || selectedId === '' ? '' : String(selectedId);
        const options = [
            '<option value="">- Tidak dihubungkan -</option>',
            ...minatSatkerOptions.map((item) => {
                const selected = String(item.id) === normalized ? 'selected' : '';
                return `<option value="${item.id}" ${selected}>${item.label}</option>`;
            })
        ];
        return `<select id="swal-input-id-unit-kerja" class="swal2-input m-0 w-100" style="border-radius:10px">${options.join('')}</select>`;
    }

    function showAddModal() {
        Swal.fire({
            title: 'Tambah Minat',
            html: `
                <div class="text-start">
                    <label class="form-label mb-1 small fw-bold">Nama Minat</label>
                    <input id="swal-input-nama" class="swal2-input m-0 w-100" style="border-radius:10px" placeholder="Contoh: Infrastruktur TI">
                    <label class="form-label mt-3 mb-1 small fw-bold">Satuan Kerja (Opsional)</label>
                    ${renderSatkerSelect('')}
                </div>
            `,
            showCancelButton: true,
            confirmButtonText: 'Simpan',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#b08d48',
            preConfirm: () => {
                const nama = document.getElementById('swal-input-nama').value.trim();
                const idUnitKerjaRaw = document.getElementById('swal-input-id-unit-kerja').value;
                if (!nama) {
                    Swal.showValidationMessage('Nama minat wajib diisi');
                    return false;
                }
                return { nama: nama, id_satker: idUnitKerjaRaw === '' ? null : Number(idUnitKerjaRaw) };
            }
        }).then((result) => {
            if (result.isConfirmed) {
                storeMinat(result.value);
            }
        });
    }

    function showEditModal(id, nama, idUnitKerja, isActive) {
        Swal.fire({
            title: 'Edit Minat',
            html: `
                <div class="text-start">
                    <label class="form-label mb-1 small fw-bold">Nama Minat</label>
                    <input id="swal-input-nama" class="swal2-input m-0 w-100" style="border-radius:10px" value="${nama}">
                    <label class="form-label mt-3 mb-1 small fw-bold">Satuan Kerja (Opsional)</label>
                    ${renderSatkerSelect(idUnitKerja)}
                    <label class="form-label mt-3 mb-1 small fw-bold">Status</label>
                    <select id="swal-input-is-active" class="swal2-input m-0 w-100" style="border-radius:10px">
                        <option value="1" ${isActive ? 'selected' : ''}>Aktif</option>
                        <option value="0" ${!isActive ? 'selected' : ''}>Nonaktif</option>
                    </select>
                </div>
            `,
            showCancelButton: true,
            confirmButtonText: 'Simpan',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#b08d48',
            preConfirm: () => {
                const updatedNama = document.getElementById('swal-input-nama').value.trim();
                const idUnitKerjaRaw = document.getElementById('swal-input-id-unit-kerja').value;
                const isActiveRaw = document.getElementById('swal-input-is-active').value;
                if (!updatedNama) {
                    Swal.showValidationMessage('Nama minat wajib diisi');
                    return false;
                }
                return {
                    nama: updatedNama,
                    id_satker: idUnitKerjaRaw === '' ? null : Number(idUnitKerjaRaw),
                    is_active: isActiveRaw === '1'
                };
            }
        }).then((result) => {
            if (result.isConfirmed) {
                updateMinat(id, result.value);
            }
        });
    }

    function storeMinat(payload) {
        fetch('{{ route("minat.store") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify(payload)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire('Berhasil!', data.message, 'success').then(() => location.reload());
            } else {
                Swal.fire('Error!', data.message || 'Terjadi kesalahan', 'error');
            }
        })
        .catch(() => Swal.fire('Error!', 'Terjadi kesalahan sistem', 'error'));
    }

    function updateMinat(id, payload) {
        fetch(`{{ url('admin/minat') }}/${id}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify(payload)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire('Berhasil!', data.message, 'success').then(() => location.reload());
            } else {
                Swal.fire('Error!', data.message || 'Gagal memperbarui data', 'error');
            }
        })
        .catch(() => Swal.fire('Error!', 'Terjadi kesalahan sistem', 'error'));
    }

    function deleteMinat(id) {
        Swal.fire({
            title: 'Hapus Data?',
            text: 'Data yang dihapus tidak dapat dikembalikan!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`{{ url('admin/minat') }}/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire('Terhapus!', data.message, 'success').then(() => location.reload());
                    } else {
                        Swal.fire('Error!', data.message || 'Gagal menghapus data', 'error');
                    }
                })
                .catch(() => Swal.fire('Error!', 'Gagal menghubungi server', 'error'));
            }
        });
    }
</script>
@endsection
