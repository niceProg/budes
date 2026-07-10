@extends('layouts.app')

@section('title', 'FAQ | Admin - SMART Setjen DPR RI')
@section('content')
<link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

@include('partials.table_skin_lamaran_css')
<style>
    .btn-gold { background: var(--gold-gradient); color: white; border: none; padding: 0.7rem 1.5rem; border-radius: 12px; font-weight: 700; transition: var(--transition); display: inline-flex; align-items: center; gap: 8px; }
    .btn-gold:hover { transform: translateY(-2px); box-shadow: 0 8px 15px rgba(176, 141, 72, 0.3); color: white; }
    .action-btn { width: 38px; height: 38px; display: inline-flex; align-items: center; justify-content: center; border-radius: 10px; transition: var(--transition); margin: 0 4px; border: 1px solid transparent; text-decoration: none; cursor: pointer; }
    .btn-edit { background: #fef3c7; color: #b45309; }
    .btn-edit:hover { background: #b45309; color: white; }
    .btn-delete { background: #fee2e2; color: #b91c1c; }
    .btn-delete:hover { background: #b91c1c; color: white; }
    .faq-q { font-weight: 700; color: var(--primary-dark, #0f172a); }
    .faq-a { color: #64748b; font-size: 0.85rem; max-width: 480px; }
    .badge-status { padding: 5px 12px; border-radius: 999px; font-size: 0.72rem; font-weight: 800; }
    .badge-on { background: #ecfdf5; color: #059669; }
    .badge-off { background: #f1f5f9; color: #64748b; }
    .faq-form label { font-weight: 700; font-size: 0.85rem; color: var(--primary-dark, #0f172a); margin-bottom: 6px; }
    .faq-form .form-control, .faq-form .form-select { border-radius: 12px; }
</style>

<div class="content-wrapper">
    <div class="container-fluid px-md-5">
        <div class="page-title-area">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1">
                    <li class="breadcrumb-item">Menu</li>
                    <li class="breadcrumb-item">SMART</li>
                    <li class="breadcrumb-item active">FAQ</li>
                </ol>
            </nav>
            <h1 class="page-title">Manajemen FAQ (Pertanyaan yang Sering Diajukan)</h1>
        </div>

        <div class="modern-card">
            <div class="card-toolbar flex-wrap gap-4">
                <div class="d-flex align-items-center gap-3 flex-wrap">
                    <div class="d-flex align-items-center gap-2">
                        <div style="width: 5px; height: 25px; background: var(--gold-gradient); border-radius: 10px;"></div>
                        <h5 class="m-0 fw-800 text-dark">Daftar FAQ</h5>
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
                                   placeholder="Cari pertanyaan / jawaban..."
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
                            <th>Urutan</th>
                            <th>Pertanyaan &amp; Jawaban</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($data as $index => $item)
                        <tr>
                            <td>{{ ($data->currentPage() - 1) * $data->perPage() + $index + 1 }}</td>
                            <td class="fw-bold text-center">{{ $item->urutan }}</td>
                            <td>
                                <div class="faq-q">{{ $item->pertanyaan }}</div>
                                <div class="faq-a">{{ \Illuminate\Support\Str::limit($item->jawaban, 140) }}</div>
                            </td>
                            <td class="text-center">
                                @if($item->is_active)
                                    <span class="badge-status badge-on">Aktif</span>
                                @else
                                    <span class="badge-status badge-off">Nonaktif</span>
                                @endif
                            </td>
                            <td class="text-center text-nowrap">
                                <button type="button" class="action-btn btn-edit"
                                        onclick='showEditModal(@json($item))' title="Edit">
                                    <i class="ri-edit-line"></i>
                                </button>
                                <button type="button" class="action-btn btn-delete" onclick="deleteFaq({{ $item->id }})" title="Hapus">
                                    <i class="ri-delete-bin-line"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <img src="https://illustrations.popsy.co/white/abstract-art-4.svg" style="height: 150px; opacity: 0.5;">
                                <p class="text-muted mt-3 fw-700">Belum ada FAQ. Klik "Tambah Data" untuk menambahkan.</p>
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

<!-- Modal Form FAQ -->
<div class="modal fade" id="faqModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0" style="border-radius: 20px; overflow: hidden;">
            <div class="modal-header" style="background: #0f172a;">
                <h5 class="modal-title fw-800 text-white" id="faqModalLabel">Tambah FAQ</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 faq-form">
                <form id="faqForm" onsubmit="return false;">
                    <input type="hidden" id="faq-id">
                    <div class="mb-3">
                        <label for="faq-pertanyaan">Pertanyaan <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="faq-pertanyaan" maxlength="255" placeholder="Contoh: Bagaimana cara mendaftar magang?">
                        <div class="invalid-feedback d-block text-danger small" id="err-pertanyaan"></div>
                    </div>
                    <div class="mb-3">
                        <label for="faq-jawaban">Jawaban <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="faq-jawaban" rows="5" maxlength="5000" placeholder="Tuliskan jawaban yang jelas dan lengkap..."></textarea>
                        <div class="invalid-feedback d-block text-danger small" id="err-jawaban"></div>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="faq-urutan">Urutan Tampil</label>
                            <input type="number" class="form-control" id="faq-urutan" min="0" max="9999" value="0">
                            <small class="text-muted">Angka kecil tampil lebih dulu.</small>
                        </div>
                        <div class="col-md-6 d-flex align-items-center">
                            <div class="form-check form-switch mt-3">
                                <input class="form-check-input" type="checkbox" id="faq-is-active" checked style="cursor:pointer;">
                                <label class="form-check-label" for="faq-is-active">Tampilkan di halaman publik (Aktif)</label>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-gold" id="faqSaveBtn" onclick="submitFaq()">
                    <i class="ri-save-3-line"></i> Simpan
                </button>
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
    const FAQ_STORE_URL = '{{ route("faq.store") }}';
    const FAQ_BASE_URL = '{{ url("admin/faq") }}';
    const CSRF_TOKEN = '{{ csrf_token() }}';
    let faqModalInstance = null;

    // Pindahkan modal ke <body> sebelum tampil agar lepas dari stacking context
    // .main-bg (position:relative; z-index:1) yang membuat .modal-backdrop menutupi
    // modal sehingga layar terlihat "freeze" / tidak bisa diklik.
    document.addEventListener('show.bs.modal', function (event) {
        const m = event.target;
        if (m && m.parentElement !== document.body) {
            document.body.appendChild(m);
        }
    });

    function getModal() {
        if (!faqModalInstance) {
            faqModalInstance = new bootstrap.Modal(document.getElementById('faqModal'));
        }
        return faqModalInstance;
    }

    function clearErrors() {
        document.getElementById('err-pertanyaan').textContent = '';
        document.getElementById('err-jawaban').textContent = '';
    }

    function showAddModal() {
        clearErrors();
        document.getElementById('faqModalLabel').textContent = 'Tambah FAQ';
        document.getElementById('faq-id').value = '';
        document.getElementById('faq-pertanyaan').value = '';
        document.getElementById('faq-jawaban').value = '';
        document.getElementById('faq-urutan').value = '0';
        document.getElementById('faq-is-active').checked = true;
        getModal().show();
    }

    function showEditModal(item) {
        clearErrors();
        document.getElementById('faqModalLabel').textContent = 'Edit FAQ';
        document.getElementById('faq-id').value = item.id;
        document.getElementById('faq-pertanyaan').value = item.pertanyaan ?? '';
        document.getElementById('faq-jawaban').value = item.jawaban ?? '';
        document.getElementById('faq-urutan').value = item.urutan ?? 0;
        document.getElementById('faq-is-active').checked = !!item.is_active;
        getModal().show();
    }

    function submitFaq() {
        clearErrors();
        const id = document.getElementById('faq-id').value;
        const payload = {
            pertanyaan: document.getElementById('faq-pertanyaan').value.trim(),
            jawaban: document.getElementById('faq-jawaban').value.trim(),
            urutan: parseInt(document.getElementById('faq-urutan').value || '0', 10),
            is_active: document.getElementById('faq-is-active').checked ? 1 : 0,
        };

        const isEdit = id !== '';
        const url = isEdit ? `${FAQ_BASE_URL}/${id}` : FAQ_STORE_URL;
        const method = isEdit ? 'PUT' : 'POST';

        const btn = document.getElementById('faqSaveBtn');
        btn.disabled = true;

        fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': CSRF_TOKEN,
                'Accept': 'application/json',
            },
            body: JSON.stringify(payload),
        })
        .then(async (response) => {
            const data = await response.json();
            return { ok: response.ok, status: response.status, data };
        })
        .then(({ ok, status, data }) => {
            btn.disabled = false;
            if (ok && data.success) {
                getModal().hide();
                Swal.fire('Berhasil!', data.message, 'success').then(() => location.reload());
            } else if (status === 422 && data.errors) {
                if (data.errors.pertanyaan) document.getElementById('err-pertanyaan').textContent = data.errors.pertanyaan[0];
                if (data.errors.jawaban) document.getElementById('err-jawaban').textContent = data.errors.jawaban[0];
            } else {
                Swal.fire('Error!', data.message || 'Terjadi kesalahan', 'error');
            }
        })
        .catch(() => {
            btn.disabled = false;
            Swal.fire('Error!', 'Tidak dapat terhubung ke server.', 'error');
        });
    }

    function deleteFaq(id) {
        Swal.fire({
            title: 'Konfirmasi Hapus',
            text: 'Apakah Anda yakin ingin menghapus FAQ ini?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#b91c1c',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`${FAQ_BASE_URL}/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': CSRF_TOKEN,
                        'Accept': 'application/json',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire('Terhapus!', data.message, 'success').then(() => location.reload());
                    } else {
                        Swal.fire('Error!', data.message || 'Gagal menghapus data', 'error');
                    }
                });
            }
        });
    }
</script>
@endsection
