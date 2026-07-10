@extends('layouts.app')

@section('title', 'Roles Internal | Admin - SMART Setjen DPR RI')
@section('content')
<link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

@include('partials.table_skin_lamaran_css')
<style>
    .btn-gold { background: var(--gold-gradient); color: white; border: none; padding: 0.7rem 1.5rem; border-radius: 12px; font-weight: 700; transition: var(--transition); display: inline-flex; align-items: center; gap: 8px; }
    .btn-gold:hover { transform: translateY(-2px); box-shadow: 0 8px 15px rgba(176, 141, 72, 0.3); color: white; }
    .action-btn { width: 38px; height: 38px; display: inline-flex; align-items: center; justify-content: center; border-radius: 10px; transition: var(--transition); margin: 0 4px; border: 1px solid transparent; text-decoration: none; }
    .btn-view { background: #e0f2fe; color: #0369a1; }
    .btn-view:hover { background: #0369a1; color: white; }
    .btn-edit { background: #fef3c7; color: #b45309; }
    .btn-edit:hover { background: #b45309; color: white; }
    .btn-delete { background: #fee2e2; color: #b91c1c; }
    .btn-delete:hover { background: #b91c1c; color: white; }
    .swal2-input { border-radius: 12px !important; font-family: 'Plus Jakarta Sans', sans-serif !important; }
    .status-badge { padding: 0.25rem 0.75rem; border-radius: 8px; font-size: 0.75rem; font-weight: 700; }
    .status-active { background: #d1fae5; color: #065f46; }
    .status-inactive { background: #fee2e2; color: #991b1b; }
</style>

<div class="content-wrapper">
    <div class="container-fluid px-md-5">
        <div class="page-title-area">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1">
                    <li class="breadcrumb-item">Menu</li>
                    <li class="breadcrumb-item">Master Data</li>
                    <li class="breadcrumb-item active">Roles Internal</li>
                </ol>
            </nav>
            <h1 class="page-title">Manajemen Roles Internal</h1>
        </div>

        <div class="modern-card">
            <div class="card-toolbar flex-wrap gap-4">
                <div class="d-flex align-items-center gap-3 flex-wrap">
                    <div class="d-flex align-items-center gap-2">
                        <div style="width: 5px; height: 25px; background: var(--gold-gradient); border-radius: 10px;"></div>
                        <h5 class="m-0 fw-800 text-dark">Daftar Roles Internal</h5>
                    </div>
                    <button type="button" class="btn btn-gold" onclick="showAddModal()">
                        <i class="ri-add-circle-line"></i> Tambah Data
                    </button>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <form method="GET" action="{{ request()->url() }}" class="search-container" id="searchForm">
                        <div class="input-group">
                            <i class="ri-search-2-line" style="color: var(--accent-gold); font-size: 1.2rem;"></i>
                            <input type="text" name="search" id="search" class="form-control shadow-none"
                                   placeholder="Cari nama role internal..."
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
                            <th>Nama Role Internal</th>
                            <th>Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($data as $index => $item)
                        <tr>
                            <td>{{ ($data->currentPage() - 1) * $data->perPage() + $index + 1 }}</td>
                            <td class="fw-semibold">{{ ucwords(strtolower($item->nama)) }}</td>
                            <td>
                                <span class="status-badge {{ $item->status == 1 ? 'status-active' : 'status-inactive' }}">
                                    {{ $item->status == 1 ? 'Aktif' : 'Tidak Aktif' }}
                                </span>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('roles-internal.show', $item->id) }}" class="action-btn btn-view" title="Detail">
                                    <i class="ri-eye-line"></i>
                                </a>
                                <button type="button" class="action-btn btn-edit" onclick="showEditModal({{ $item->id }}, '{{ addslashes($item->nama) }}', {{ $item->status }})" title="Edit">
                                    <i class="ri-edit-line"></i>
                                </button>
                                <button type="button" class="action-btn btn-delete" onclick="deleteRolesInternal({{ $item->id }})" title="Hapus">
                                    <i class="ri-delete-bin-line"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-5">
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
    document.addEventListener('DOMContentLoaded', function() {
        var si = document.getElementById('search');
        if (si) {
            var t = null;
            si.addEventListener('keyup', function() {
                clearTimeout(t);
                t = setTimeout(function() {
                    if (this.value.length > 2 || this.value.length === 0) document.getElementById('searchForm').submit();
                }.bind(this), 800);
            });
        }
        document.querySelectorAll('.page-link').forEach(function(l) { l.addEventListener('click', function() { window.scrollTo({ top: 0, behavior: 'smooth' }); }); });
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function showAddModal() {
        Swal.fire({
            title: 'Tambah Role Internal Baru',
            input: 'text',
            inputPlaceholder: 'Masukkan nama role internal',
            inputAttributes: { id: 'swal-input-nama' },
            showCancelButton: true,
            confirmButtonText: 'Simpan Data',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#b08d48',
            preConfirm: (value) => {
                if (!value) {
                    Swal.showValidationMessage('Nama role internal wajib diisi');
                    return false;
                }
                return { nama: value };
            }
        }).then((result) => {
            if (result.isConfirmed) {
                storeRolesInternal(result.value.nama);
            }
        });
    }

    function showEditModal(id, nama, status) {
        Swal.fire({
            title: 'Perbarui Data',
            html: `
                <input id="swal-input-nama" class="swal2-input" placeholder="Nama Role Internal" value="${nama}" required>
                <select id="swal-input-status" class="swal2-input" style="margin-top: 10px;">
                    <option value="1" ${status == 1 ? 'selected' : ''}>Aktif</option>
                    <option value="0" ${status == 0 ? 'selected' : ''}>Tidak Aktif</option>
                </select>
            `,
            showCancelButton: true,
            confirmButtonText: 'Simpan Perubahan',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#b08d48',
            preConfirm: () => {
                const nama = document.getElementById('swal-input-nama').value;
                const status = document.getElementById('swal-input-status').value;
                if (!nama) {
                    Swal.showValidationMessage('Nama role internal wajib diisi');
                    return false;
                }
                return { nama: nama, status: parseInt(status) };
            }
        }).then((result) => {
            if (result.isConfirmed) {
                updateRolesInternal(id, result.value.nama, result.value.status);
            }
        });
    }

    function storeRolesInternal(nama) {
        fetch('{{ route("roles-internal.store") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ nama: nama })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire('Berhasil!', data.message, 'success').then(() => location.reload());
            } else {
                let errorMsg = data.message || 'Terjadi kesalahan';
                if (data.errors) {
                    errorMsg = Object.values(data.errors).flat().join(', ');
                }
                Swal.fire('Error!', errorMsg, 'error');
            }
        })
        .catch(error => {
            Swal.fire('Error!', 'Terjadi kesalahan saat menyimpan data', 'error');
        });
    }

    function updateRolesInternal(id, nama, status) {
        fetch(`{{ url('admin/roles-internal') }}/${id}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ nama: nama, status: status })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire('Berhasil!', data.message, 'success').then(() => location.reload());
            } else {
                let errorMsg = data.message || 'Gagal memperbarui data';
                if (data.errors) {
                    errorMsg = Object.values(data.errors).flat().join(', ');
                }
                Swal.fire('Error!', errorMsg, 'error');
            }
        })
        .catch(error => {
            Swal.fire('Error!', 'Terjadi kesalahan saat memperbarui data', 'error');
        });
    }

    function deleteRolesInternal(id) {
        Swal.fire({
            title: 'Konfirmasi Hapus',
            text: 'Apakah Anda yakin ingin menghapus data ini?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#b91c1c',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`{{ url('admin/roles-internal') }}/${id}`, {
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
                        Swal.fire('Error!', 'Gagal menghapus data', 'error');
                    }
                });
            }
        });
    }
</script>
@endsection
