@extends('layouts.app')

@section('title', 'Agama | Admin - SMART Setjen DPR RI')
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
</style>

<div class="content-wrapper">
    <div class="container-fluid px-md-5">
        <div class="page-title-area">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1">
                    <li class="breadcrumb-item">Menu</li>
                    <li class="breadcrumb-item">Master Data</li>
                    <li class="breadcrumb-item active">Agama</li>
                </ol>
            </nav>
            <h1 class="page-title">Manajemen Agama</h1>
        </div>

        <div class="modern-card">
            <div class="card-toolbar flex-wrap gap-4">
                <div class="d-flex align-items-center gap-3 flex-wrap">
                    <div class="d-flex align-items-center gap-2">
                        <div style="width: 5px; height: 25px; background: var(--gold-gradient); border-radius: 10px;"></div>
                        <h5 class="m-0 fw-800 text-dark">Daftar Agama</h5>
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
                                   placeholder="Cari nama agama..."
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
                            <th>Nama Agama</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($data as $index => $item)
                        <tr>
                            <td>{{ ($data->currentPage() - 1) * $data->perPage() + $index + 1 }}</td>
                            <td class="fw-semibold">{{ ucwords(strtolower($item->agama)) }}</td>
                            <td class="text-center">
                                <a href="{{ route('agama.show', $item->id) }}" class="action-btn btn-view" title="Detail">
                                    <i class="ri-eye-line"></i>
                                </a>
                                <button type="button" class="action-btn btn-edit" onclick="showEditModal({{ $item->id }}, '{{ addslashes($item->agama) }}')" title="Edit">
                                    <i class="ri-edit-line"></i>
                                </button>
                                <button type="button" class="action-btn btn-delete" onclick="deleteAgama({{ $item->id }})" title="Hapus">
                                    <i class="ri-delete-bin-line"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center py-5">
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
    // Logika JavaScript tetap dipertahankan sama persis dengan kode asli
    function showAddModal() {
        Swal.fire({
            title: 'Tambah Agama Baru',
            text: 'Masukkan nama agama secara lengkap',
            input: 'text',
            inputPlaceholder: 'Contoh: Islam, Kristen, dsb...',
            inputAttributes: { id: 'swal-input-agama' },
            showCancelButton: true,
            confirmButtonText: 'Simpan Data',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#b08d48',
            preConfirm: (value) => {
                if (!value) {
                    Swal.showValidationMessage('Nama agama wajib diisi');
                }
                return { agama: value };
            }
        }).then((result) => {
            if (result.isConfirmed) {
                storeAgama(result.value.agama);
            }
        });
    }

    function showEditModal(id, agama) {
        Swal.fire({
            title: 'Perbarui Data',
            input: 'text',
            inputValue: agama,
            inputAttributes: { id: 'swal-input-agama' },
            showCancelButton: true,
            confirmButtonText: 'Simpan Perubahan',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#b08d48',
            preConfirm: (value) => {
                if (!value) {
                    Swal.showValidationMessage('Nama agama wajib diisi');
                }
                return { agama: value };
            }
        }).then((result) => {
            if (result.isConfirmed) {
                updateAgama(id, result.value.agama);
            }
        });
    }

    // Fungsi Fetch API tetap sama
    function storeAgama(agama) {
        fetch('{{ route("agama.store") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ agama: agama })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire('Berhasil!', data.message, 'success').then(() => location.reload());
            } else {
                Swal.fire('Error!', data.message || 'Terjadi kesalahan', 'error');
            }
        });
    }

    function updateAgama(id, agama) {
        fetch(`{{ url('admin/agama') }}/${id}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ agama: agama })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire('Berhasil!', data.message, 'success').then(() => location.reload());
            } else {
                Swal.fire('Error!', data.message || 'Gagal memperbarui data', 'error');
            }
        });
    }

    function deleteAgama(id) {
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
                fetch(`{{ url('admin/agama') }}/${id}`, {
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