@extends('layouts.app')

@section('title', 'Satuan Kerja | Admin - SMART Setjen DPR RI')
@section('content')
<link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

@include('partials.table_skin_lamaran_css')
<style>
    .btn-primary { background: var(--gold-gradient); border: none; padding: 0.7rem 1.5rem; border-radius: 12px; font-weight: 700; color: white; transition: var(--transition); display: inline-flex; align-items: center; gap: 8px; }
    .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 8px 15px rgba(176, 141, 72, 0.3); color: white; }
    .badge-code { background: var(--accent-gold); color: white; padding: 6px 14px; border-radius: 10px; font-weight: 800; font-size: 0.7rem; border: 1px solid rgba(176, 141, 72, 0.2); font-family: 'Courier New', monospace; }
    .action-btn-group { display: flex; gap: 8px; justify-content: center; flex-wrap: wrap; }
    .btn-action { width: 38px; height: 38px; display: inline-flex; align-items: center; justify-content: center; border-radius: 10px; transition: var(--transition); border: 1px solid #e2e8f0; text-decoration: none; cursor: pointer; position: relative; z-index: 1; }
    .btn-detail { color: #0284c7; background: #f0f9ff; }
    .btn-detail:hover { background: #0284c7; color: white; }
    .btn-edit-kuota { color: #b08d48; background: #fdfaf3; }
    .btn-edit-kuota:hover { background: #b08d48; color: white; }
    .btn-sync { background: linear-gradient(135deg, #0ea5e9 0%, #0369a1 100%); border: none; padding: 0.7rem 1.5rem; border-radius: 12px; font-weight: 700; color: white; transition: var(--transition); display: inline-flex; align-items: center; gap: 8px; cursor: pointer; }
    .btn-sync:hover { transform: translateY(-2px); box-shadow: 0 8px 15px rgba(3, 105, 161, 0.3); color: white; }
    .btn-sync:disabled { opacity: 0.6; cursor: not-allowed; transform: none; }
    .btn-kuota-global { background: var(--gold-gradient); border: none; padding: 0.7rem 1.5rem; border-radius: 12px; font-weight: 700; color: white; transition: var(--transition); display: inline-flex; align-items: center; gap: 8px; cursor: pointer; }
    .btn-kuota-global:hover { transform: translateY(-2px); box-shadow: 0 8px 15px rgba(176, 141, 72, 0.3); color: white; }
    .status-badge { padding: 4px 12px; border-radius: 8px; font-weight: 700; font-size: 0.75rem; }
    .status-active { background: #dcfce7; color: #16a34a; }
    .status-inactive { background: #fee2e2; color: #dc2626; }
</style>

<div class="content-wrapper">
    <div class="container-fluid px-md-5">
        <div class="page-title-area">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1">
                    <li class="breadcrumb-item">Menu</li>
                    <li class="breadcrumb-item">Master Data</li>
                    <li class="breadcrumb-item active">Satuan Kerja</li>
                </ol>
            </nav>
            <h1 class="page-title">Satuan Kerja Instansi</h1>
        </div>

        <div class="modern-card">
            <div class="card-toolbar flex-wrap gap-4">
                <div class="d-flex align-items-center gap-3 flex-wrap">
                    <div class="d-flex align-items-center gap-2">
                        <div style="width: 5px; height: 25px; background: var(--gold-gradient); border-radius: 10px;"></div>
                        <h5 class="m-0 fw-800 text-dark">Daftar Satuan Kerja</h5>
                    </div>
                    @if($isSuperadmin)
                    <button type="button" class="btn-sync" id="btnSync" onclick="syncSatker()">
                        <i class="ri-refresh-line" id="syncIcon"></i>
                        <span id="syncLabel">Sinkron Satker</span>
                    </button>
                    <button type="button" class="btn-kuota-global" onclick="setKuotaGlobal()">
                        <i class="ri-stack-line"></i>
                        <span>Set Kuota Global</span>
                    </button>
                    @endif
                </div>
                <div class="d-flex align-items-center gap-3">
                    <form method="GET" action="{{ request()->url() }}" class="search-container" id="searchForm">
                        <div class="input-group">
                            <i class="ri-search-2-line" style="color: var(--accent-gold); font-size: 1.2rem;"></i>
                            <input type="text" name="search" id="search" class="form-control shadow-none"
                                   placeholder="Cari nama / kode satuan kerja..."
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
                            <th>Nama Satuan Kerja</th>
                            <th>Parent Unit</th>
                            <th class="text-center">Jumlah Pegawai</th>
                            <th class="text-center">Kuota Magang</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($data as $index => $item)
                        <tr>
                            <td>{{ ($data->currentPage() - 1) * $data->perPage() + $index + 1 }}</td>
                            <td>
                                <div class="applicant-name">{{ $item->nama_satker }}</div>
                                @if($item->eselon || $item->id_biro)
                                    <div class="small text-muted">
                                        @if($item->eselon) Eselon: {{ $item->eselon }} @endif
                                        @if($item->id_biro) · Biro: {{ $item->id_biro }} @endif
                                    </div>
                                @endif
                            </td>
                            <td>
                                @if($item->parent)
                                    <span class="text-muted" style="font-size: 0.85rem;">{{ $item->parent->nama_satker }}</span>
                                @else
                                    <span class="text-muted" style="font-size: 0.85rem;">-</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($item->jumlah_pegawai !== null)
                                    <span class="badge bg-info bg-opacity-10 text-info fw-bold" style="font-size: 0.75rem;">
                                        {{ number_format($item->jumlah_pegawai) }}
                                    </span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($item->kuota === null)
                                    <span class="badge bg-success bg-opacity-10 text-success fw-bold" style="font-size: 0.75rem;">Unlimited</span>
                                @elseif($item->kuota === 0)
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary fw-bold" style="font-size: 0.75rem;">Tidak dibuka</span>
                                @else
                                    <span class="badge bg-primary bg-opacity-10 text-primary fw-bold" style="font-size: 0.75rem;">
                                        {{ $item->kuota }}
                                    </span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($item->status == 1)
                                    <span class="status-badge status-active">Aktif</span>
                                @else
                                    <span class="status-badge status-inactive">Nonaktif</span>
                                @endif
                            </td>
                            <td>
                                <div class="action-btn-group">
                                    <button type="button"
                                            class="btn-action btn-edit-kuota"
                                            title="Edit Kuota Magang"
                                            onclick="editKuotaMagang({{ $item->id }}, {{ json_encode($item->nama_satker) }}, {{ $item->kuota === null ? 'null' : (int) $item->kuota }})">
                                        <i class="ri-edit-line"></i>
                                    </button>
                                    <a href="{{ route('satker.show', $item->id) }}" class="btn-action btn-detail" title="Detail">
                                        <i class="ri-eye-line"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
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
    function editKuotaMagang(id, nama, kuota) {
        Swal.fire({
            title: 'Edit Kuota Magang',
            html: `
                <div class="text-start mb-3">
                    <div class="text-muted small mb-1">Satuan Kerja</div>
                    <div class="fw-bold">${nama}</div>
                </div>
                <div class="text-start">
                    <label for="kuotaInput" class="form-label fw-bold small">Kuota Magang</label>
                    <input type="number" id="kuotaInput" class="form-control" min="0" max="9999"
                           placeholder="Kosongkan untuk unlimited"
                           value="${kuota === null ? '' : kuota}">
                    <div class="form-text mt-2">
                        Kosong = unlimited · 0 = tidak dibuka · Angka &gt; 0 = batas kuota
                    </div>
                </div>
            `,
            showCancelButton: true,
            confirmButtonText: 'Simpan',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#b08d48',
            cancelButtonColor: '#64748b',
            focusConfirm: false,
            preConfirm: () => {
                const input = document.getElementById('kuotaInput');
                const raw = input.value.trim();
                if (raw === '') {
                    return { kuota: null };
                }
                const value = parseInt(raw, 10);
                if (Number.isNaN(value) || value < 0 || value > 9999) {
                    Swal.showValidationMessage('Kuota harus berupa angka 0–9999 atau dikosongkan.');
                    return false;
                }
                return { kuota: value };
            }
        }).then((result) => {
            if (!result.isConfirmed) return;

            fetch(`{{ url('/admin/satker') }}/${id}/kuota`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(result.value)
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: data.message || 'Kuota magang diperbarui.',
                        confirmButtonColor: '#b08d48'
                    }).then(() => location.reload());
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: data.message || 'Kuota magang gagal diperbarui.',
                        confirmButtonColor: '#ef4444'
                    });
                }
            })
            .catch(() => {
                Swal.fire({
                    icon: 'error',
                    title: 'Koneksi Gagal',
                    text: 'Tidak dapat menghubungi server.',
                    confirmButtonColor: '#ef4444'
                });
            });
        });
    }

    @if($isSuperadmin)
    function setKuotaGlobal() {
        Swal.fire({
            title: 'Set Kuota Magang Global',
            html: `
                <div class="text-start mb-2">
                    <div class="alert alert-warning py-2 px-3 small mb-3" style="border-radius:10px;">
                        <i class="ri-error-warning-line"></i> Nilai ini akan <strong>menimpa kuota SEMUA satuan kerja</strong>.
                    </div>
                    <label for="kuotaGlobalInput" class="form-label fw-bold small">Kuota Magang (berlaku untuk semua satker)</label>
                    <input type="number" id="kuotaGlobalInput" class="form-control" min="0" max="9999"
                           placeholder="Kosongkan untuk unlimited">
                    <div class="form-text mt-2">
                        Kosong = unlimited &middot; 0 = tidak dibuka &middot; Angka &gt; 0 = batas kuota
                    </div>
                </div>
            `,
            showCancelButton: true,
            confirmButtonText: 'Lanjut',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#b08d48',
            cancelButtonColor: '#64748b',
            focusConfirm: false,
            preConfirm: () => {
                const input = document.getElementById('kuotaGlobalInput');
                const raw = input.value.trim();
                if (raw === '') {
                    return { kuota: null };
                }
                const value = parseInt(raw, 10);
                if (Number.isNaN(value) || value < 0 || value > 9999) {
                    Swal.showValidationMessage('Kuota harus berupa angka 0–9999 atau dikosongkan.');
                    return false;
                }
                return { kuota: value };
            }
        }).then((result) => {
            if (!result.isConfirmed) return;

            const labelKuota = result.value.kuota === null
                ? 'Unlimited'
                : (result.value.kuota === 0 ? 'Tidak dibuka (0)' : result.value.kuota);

            // Konfirmasi kedua karena dampaknya menyentuh semua satker
            Swal.fire({
                title: 'Terapkan ke semua satker?',
                html: `Kuota magang <strong>seluruh satuan kerja</strong> akan diset menjadi <strong>${labelKuota}</strong>.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Terapkan',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#b08d48',
                cancelButtonColor: '#64748b',
            }).then((confirm) => {
                if (!confirm.isConfirmed) return;

                Swal.fire({
                    title: 'Menyimpan...',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    didOpen: () => { Swal.showLoading(); }
                });

                fetch('{{ route("satker.update.kuota.global") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(result.value)
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: data.message || 'Kuota global diperbarui.',
                            confirmButtonColor: '#b08d48'
                        }).then(() => location.reload());
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: data.message || 'Kuota global gagal diperbarui.',
                            confirmButtonColor: '#ef4444'
                        });
                    }
                })
                .catch(() => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Koneksi Gagal',
                        text: 'Tidak dapat menghubungi server.',
                        confirmButtonColor: '#ef4444'
                    });
                });
            });
        });
    }

    function syncSatker() {
        Swal.fire({
            title: 'Sinkronisasi Satker?',
            text: 'Data satuan kerja akan diperbarui sesuai sumber data terbaru.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Sinkronkan',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#0369a1',
            cancelButtonColor: '#64748b',
        }).then((result) => {
            if (!result.isConfirmed) return;

            const btn = document.getElementById('btnSync');
            const icon = document.getElementById('syncIcon');
            const label = document.getElementById('syncLabel');

            btn.disabled = true;
            icon.className = 'ri-loader-4-line';
            icon.style.animation = 'spin 1s linear infinite';
            label.textContent = 'Sedang sinkron...';

            Swal.fire({
                title: 'Sedang Sinkronisasi...',
                text: 'Mohon tunggu sebentar.',
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => { Swal.showLoading(); }
            });

            fetch('{{ route("satker.sync") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(res => res.json())
            .then(data => {
                btn.disabled = false;
                icon.className = 'ri-refresh-line';
                icon.style.animation = '';
                label.textContent = 'Sinkron Satker';

                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Sinkronisasi Berhasil!',
                        text: `Total data satker: ${data.total_satker ?? 0}`,
                        confirmButtonColor: '#0369a1',
                        confirmButtonText: 'OK'
                    }).then(() => location.reload());
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Sinkronisasi Gagal',
                        text: data.message || 'Terjadi kesalahan, cek log untuk detail.',
                        confirmButtonColor: '#ef4444'
                    });
                }
            })
            .catch(err => {
                btn.disabled = false;
                icon.className = 'ri-refresh-line';
                icon.style.animation = '';
                label.textContent = 'Sinkron Satker';

                Swal.fire({
                    icon: 'error',
                    title: 'Koneksi Gagal',
                    text: 'Tidak dapat menghubungi server. Periksa koneksi internet.',
                    confirmButtonColor: '#ef4444'
                });
            });
        });
    }
    @endif

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
<style>
    @keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
</style>
@endsection
