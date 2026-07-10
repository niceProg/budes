@extends('layouts.app')

@section('title', 'Guru | Admin - SMART Setjen DPR RI')
@section('content')
<link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

@include('partials.table_skin_lamaran_css')
<style>
    .btn-force-nonaktif { background: linear-gradient(135deg, #f59e0b 0%, #b45309 100%); border: none; padding: 0.7rem 1.5rem; border-radius: 12px; font-weight: 700; color: white; transition: var(--transition); display: inline-flex; align-items: center; gap: 8px; cursor: pointer; }
    .btn-force-nonaktif:hover { transform: translateY(-2px); box-shadow: 0 8px 15px rgba(180, 83, 9, 0.3); color: white; }
    .btn-force-nonaktif:disabled { opacity: 0.6; cursor: not-allowed; transform: none; }
    .status-badge { display: inline-flex; align-items: center; gap: 6px; padding: 5px 13px; border-radius: 999px; font-weight: 800; font-size: 0.75rem; line-height: 1; border: 1px solid transparent; }
    .status-badge .status-dot { width: 8px; height: 8px; border-radius: 50%; display: inline-block; flex: 0 0 8px; }
    .status-active { background: #dcfce7; color: #15803d; border-color: #bbf7d0; }
    .status-active .status-dot { background: #22c55e; box-shadow: 0 0 0 3px rgba(34,197,94,.18); }
    .status-inactive { background: #fee2e2; color: #b91c1c; border-color: #fecaca; }
    .status-inactive .status-dot { background: #ef4444; box-shadow: 0 0 0 3px rgba(239,68,68,.18); }
    .status-expired-hint { display: block; margin-top: 3px; font-size: 0.65rem; font-weight: 700; color: #b45309; }
    .pchip-status { font-size: 0.65rem; font-weight: 800; padding: 2px 10px; border-radius: 999px; white-space: nowrap; }
    .pchip-1 { background: #fff7ed; color: #c2410c; }
    .pchip-2 { background: #ecfeff; color: #0e7490; }
    .pchip-3 { background: #f0fdf4; color: #16a34a; }
    .pchip-9 { background: #fef2f2; color: #dc2626; }
    @keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }

    /* Tombol aksi (grup ikon) */
    .aksi-group { display: inline-flex; gap: 8px; justify-content: center; }
    .btn-aksi { width: 38px; height: 38px; display: inline-flex; align-items: center; justify-content: center; border-radius: 12px; border: 1.5px solid #e2e8f0; background: #fff; color: #475569; font-size: 1.15rem; text-decoration: none; transition: all .18s ease; cursor: pointer; }
    .btn-aksi:hover { transform: translateY(-2px); box-shadow: 0 8px 16px rgba(15,23,42,.12); }
    .btn-aksi--view:hover { border-color: var(--accent-gold, #b08d48); color: var(--accent-gold, #b08d48); background: #fffbf3; }
    .btn-aksi--email:hover { border-color: #6366f1; color: #6366f1; background: #f5f5ff; }
    .btn-aksi--wa:hover { border-color: #22c55e; color: #16a34a; background: #f0fdf4; }
    .btn-aksi--extend:hover { border-color: #0ea5e9; color: #0284c7; background: #f0f9ff; }
    .btn-aksi--resend:hover { border-color: #8b5cf6; color: #7c3aed; background: #f5f3ff; }
    .btn-aksi--on:hover { border-color: #ef4444; color: #dc2626; background: #fef2f2; }   /* aktif → klik untuk nonaktifkan */
    .btn-aksi--off:hover { border-color: #22c55e; color: #16a34a; background: #f0fdf4; }   /* nonaktif → klik untuk aktifkan */
    .btn-aksi.is-disabled { opacity: .4; pointer-events: none; }

    /* Modal peserta dibimbing */
    .guru-peserta-modal .modal-content { border: none; border-radius: 20px; overflow: hidden; box-shadow: 0 25px 50px -12px rgba(0,0,0,.25); }
    .guru-peserta-modal .modal-header { border: none; padding: 1.35rem 1.5rem; background: linear-gradient(135deg, #1e293b 0%, #334155 100%); color: #fff; align-items: flex-start; }
    .guru-peserta-modal .modal-header .modal-title { color: #fff; font-weight: 800; }
    .guru-peserta-modal .modal-header .btn-close { filter: invert(1) grayscale(1) brightness(1.6); opacity: .8; }
    .guru-peserta-modal .modal-body { padding: 1rem 1.25rem 1.25rem; background: #f8fafc; }
    .peserta-row { display: flex; align-items: center; gap: 14px; padding: 12px 14px; background: #fff; border: 1px solid #eef2f7; border-radius: 14px; margin-bottom: 10px; transition: box-shadow .15s ease; }
    .peserta-row:last-child { margin-bottom: 0; }
    .peserta-row:hover { box-shadow: 0 6px 16px rgba(15,23,42,.06); }
    .peserta-avatar { flex: 0 0 42px; width: 42px; height: 42px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 1rem; color: #fff; background: linear-gradient(135deg, #b08d48 0%, #8a6d34 100%); }
    .peserta-idx { flex: 0 0 26px; text-align: center; font-weight: 800; color: #94a3b8; font-size: .85rem; }
    .peserta-empty { text-align: center; padding: 2.5rem 1rem; color: #94a3b8; }
</style>

@php
    $statusPeserta = [1 => 'Belum Mulai', 2 => 'Aktif', 3 => 'Selesai', 9 => 'Banned'];
@endphp

<div class="content-wrapper">
    <div class="container-fluid px-md-5">
        <div class="page-title-area">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1">
                    <li class="breadcrumb-item">Manajemen</li>
                    <li class="breadcrumb-item">Data Peserta</li>
                    <li class="breadcrumb-item active">Guru</li>
                </ol>
            </nav>
            <h1 class="page-title">Data Guru / Pembimbing</h1>
        </div>

        <div class="modern-card">
            <div class="card-toolbar flex-wrap gap-4">
                <div class="d-flex align-items-center gap-3 flex-wrap">
                    <div class="d-flex align-items-center gap-2">
                        <div style="width: 5px; height: 25px; background: var(--gold-gradient); border-radius: 10px;"></div>
                        <h5 class="m-0 fw-800 text-dark">Daftar Guru &amp; Anak Magang</h5>
                    </div>
                    <button type="button" class="btn-force-nonaktif" id="btnForceNonaktif" onclick="forceNonaktifkanGuru()">
                        <i class="ri-user-unfollow-line" id="forceIcon"></i>
                        <span id="forceLabel">Nonaktifkan Akun Guru</span>
                    </button>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <form method="GET" action="{{ request()->url() }}" class="search-container" id="searchForm">
                        <div class="input-group">
                            <i class="ri-search-2-line" style="color: var(--accent-gold); font-size: 1.2rem;"></i>
                            <input type="text" name="search" id="search" class="form-control shadow-none"
                                   placeholder="Cari nama / email / kontak guru..."
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
                    Total Guru: <span class="text-dark">{{ $data->total() }}</span>
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
                            <th>Guru / Pembimbing</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Berlaku s/d</th>
                            <th class="text-center">Jumlah</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($data as $index => $guru)
                        <tr>
                            <td>{{ ($data->currentPage() - 1) * $data->perPage() + $index + 1 }}</td>
                            <td>
                                <div class="applicant-name">{{ $guru->nama }}</div>
                                <div class="small text-muted">
                                    @if($guru->email)<span><i class="ri-mail-line"></i> {{ $guru->email }}</span>@endif
                                    @if($guru->kontak)<span class="ms-2"><i class="ri-phone-line"></i> {{ $guru->kontak }}</span>@endif
                                </div>
                            </td>
                            <td class="text-center">
                                @if($guru->is_active)
                                    <span class="status-badge status-active"><span class="status-dot"></span> Aktif</span>
                                @else
                                    <span class="status-badge status-inactive"><span class="status-dot"></span> Nonaktif</span>
                                    @if($guru->active_until && \Carbon\Carbon::parse($guru->active_until)->isPast())
                                        <span class="status-expired-hint"><i class="ri-time-line"></i> Berakhir {{ \Carbon\Carbon::parse($guru->active_until)->translatedFormat('d M Y') }}</span>
                                    @endif
                                @endif
                            </td>
                            <td class="text-center">
                                <span class="text-muted" style="font-size: 0.85rem;">
                                    {{ $guru->active_until ? \Carbon\Carbon::parse($guru->active_until)->translatedFormat('d M Y') : '-' }}
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-primary bg-opacity-10 text-primary fw-bold" style="font-size: 0.75rem;">
                                    {{ $guru->peserta_count }}
                                </span>
                            </td>
                            <td class="text-center">
                                @php
                                    $waNumber = preg_replace('/\D/', '', (string) $guru->kontak);
                                    if (str_starts_with($waNumber, '0')) {
                                        $waNumber = '62' . substr($waNumber, 1);
                                    }
                                @endphp
                                <div class="aksi-group">
                                    <button type="button" class="btn-aksi btn-aksi--view" data-bs-toggle="modal"
                                            data-bs-target="#pesertaModal-{{ $guru->id }}"
                                            title="Lihat peserta yang dibimbing">
                                        <i class="ri-eye-line"></i>
                                    </button>
                                    <a href="{{ $guru->email ? 'mailto:'.$guru->email : '#' }}"
                                       class="btn-aksi btn-aksi--email {{ $guru->email ? '' : 'is-disabled' }}"
                                       title="{{ $guru->email ? 'Kirim email ke '.$guru->email : 'Email tidak tersedia' }}">
                                        <i class="ri-mail-send-line"></i>
                                    </a>
                                    <a href="{{ $waNumber ? 'https://wa.me/'.$waNumber : '#' }}"
                                       target="_blank" rel="noopener"
                                       class="btn-aksi btn-aksi--wa {{ $waNumber ? '' : 'is-disabled' }}"
                                       title="{{ $waNumber ? 'Hubungi via WhatsApp' : 'Kontak tidak tersedia' }}">
                                        <i class="ri-whatsapp-line"></i>
                                    </a>
                                    <button type="button"
                                            class="btn-aksi js-guru-extend btn-aksi--extend"
                                            data-id="{{ $guru->id }}"
                                            data-nama="{{ $guru->nama }}"
                                            data-active-until="{{ $guru->active_until ? \Carbon\Carbon::parse($guru->active_until)->format('Y-m-d') : '' }}"
                                            title="Perpanjang masa berlaku">
                                        <i class="ri-calendar-2-line"></i>
                                    </button>
                                    <button type="button"
                                            class="btn-aksi js-guru-resend btn-aksi--resend {{ $guru->email ? '' : 'is-disabled' }}"
                                            data-id="{{ $guru->id }}"
                                            data-nama="{{ $guru->nama }}"
                                            data-email="{{ $guru->email }}"
                                            title="{{ $guru->email ? 'Kirim ulang kredensial login' : 'Email tidak tersedia' }}">
                                        <i class="ri-key-2-line"></i>
                                    </button>
                                    <button type="button"
                                            class="btn-aksi js-guru-toggle {{ $guru->is_active ? 'btn-aksi--on' : 'btn-aksi--off' }}"
                                            data-id="{{ $guru->id }}"
                                            data-nama="{{ $guru->nama }}"
                                            data-active="{{ $guru->is_active ? 1 : 0 }}"
                                            title="{{ $guru->is_active ? 'Nonaktifkan guru' : 'Aktifkan guru' }}">
                                        <i class="{{ $guru->is_active ? 'ri-toggle-fill' : 'ri-toggle-line' }}"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <img src="https://illustrations.popsy.co/white/abstract-art-4.svg" style="height: 150px; opacity: 0.5;">
                                <p class="text-muted mt-3 fw-700">Tidak ada data guru yang ditemukan.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- ============ Modal Peserta Dibimbing (per guru) ============ --}}
            @foreach($data as $guru)
            <div class="modal fade guru-peserta-modal" id="pesertaModal-{{ $guru->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <div class="d-flex align-items-center gap-3">
                                <div class="peserta-avatar" style="flex:0 0 44px;width:44px;height:44px;">{{ strtoupper(mb_substr($guru->nama, 0, 1)) }}</div>
                                <div>
                                    <h5 class="modal-title mb-0">{{ $guru->nama }}</h5>
                                    <div class="small" style="opacity:.85;"><i class="ri-group-line"></i> {{ $guru->peserta_count }} peserta dibimbing</div>
                                </div>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            @forelse($guru->peserta as $i => $p)
                                <div class="peserta-row">
                                    <div class="peserta-idx">{{ $i + 1 }}</div>
                                    <div class="peserta-avatar">{{ strtoupper(mb_substr($p->nama, 0, 1)) }}</div>
                                    <div class="flex-grow-1">
                                        <div class="fw-700 text-dark">{{ $p->nama }}</div>
                                        @if($p->satker)
                                            <div class="small text-muted"><i class="ri-building-2-line"></i> {{ $p->satker->nama }}</div>
                                        @else
                                            <div class="small text-muted"><i class="ri-building-2-line"></i> Belum ada satuan kerja</div>
                                        @endif
                                    </div>
                                    <span class="pchip-status pchip-{{ (int) $p->status }}">{{ $statusPeserta[(int) $p->status] ?? '-' }}</span>
                                </div>
                            @empty
                                <div class="peserta-empty">
                                    <i class="ri-user-search-line" style="font-size: 2.5rem;"></i>
                                    <p class="mt-2 mb-0 fw-700">Belum ada peserta yang dibimbing.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
            @endforeach

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
    function forceNonaktifkanGuru() {
        Swal.fire({
            title: 'Nonaktifkan Akun Guru?',
            html: 'Menjalankan proses <strong>guru:nonaktifkan-akun</strong> sekarang (tanpa menunggu cron).<br><br>Akun user guru yang sudah tidak aktif lebih dari 3 bulan akan dilepas &amp; dihapus permanen agar email bisa dipakai lagi.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Jalankan',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#b45309',
            cancelButtonColor: '#64748b',
        }).then((result) => {
            if (!result.isConfirmed) return;

            const btn = document.getElementById('btnForceNonaktif');
            const icon = document.getElementById('forceIcon');
            const label = document.getElementById('forceLabel');
            btn.disabled = true;
            icon.style.animation = 'spin 1s linear infinite';
            label.textContent = 'Memproses...';

            Swal.fire({
                title: 'Sedang Memproses...',
                text: 'Mohon tunggu sebentar.',
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => { Swal.showLoading(); }
            });

            fetch('{{ route("guru.nonaktifkan-akun.run") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                btn.disabled = false;
                icon.style.animation = '';
                label.textContent = 'Nonaktifkan Akun Guru';

                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        html: (data.message || 'Proses selesai.') + (data.output ? '<pre style="text-align:left;white-space:pre-wrap;font-size:12px;margin-top:10px;background:#f8fafc;padding:10px;border-radius:8px;max-height:200px;overflow:auto;">' + data.output.replace(/</g,'&lt;') + '</pre>' : ''),
                        confirmButtonColor: '#b08d48'
                    }).then(() => location.reload());
                } else {
                    Swal.fire({ icon: 'error', title: 'Gagal', text: data.message || 'Proses gagal.', confirmButtonColor: '#ef4444' });
                }
            })
            .catch(() => {
                btn.disabled = false;
                icon.style.animation = '';
                label.textContent = 'Nonaktifkan Akun Guru';
                Swal.fire({ icon: 'error', title: 'Koneksi Gagal', text: 'Tidak dapat menghubungi server.', confirmButtonColor: '#ef4444' });
            });
        });
    }

    // Pindahkan modal ke <body> saat dibuka agar tidak terpotong stacking context sidebar/tabel.
    document.addEventListener('show.bs.modal', function(event) {
        const modalEl = event.target;
        if (modalEl && modalEl.parentElement !== document.body) {
            document.body.appendChild(modalEl);
        }
    });

    // Helper POST JSON + CSRF untuk aksi per-guru.
    function guruPost(url, body) {
        return fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: JSON.stringify(body || {})
        }).then(async (res) => {
            const data = await res.json().catch(() => ({}));
            return { ok: res.ok, data };
        });
    }

    function guruShowLoading() {
        Swal.fire({ title: 'Memproses...', allowOutsideClick: false, allowEscapeKey: false, didOpen: () => Swal.showLoading() });
    }

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

        // ===== Aktifkan / Nonaktifkan guru =====
        document.querySelectorAll('.js-guru-toggle').forEach(function(btn) {
            btn.addEventListener('click', function() {
                const id = this.dataset.id;
                const nama = this.dataset.nama;
                const isActive = this.dataset.active === '1';
                Swal.fire({
                    title: isActive ? 'Nonaktifkan Guru?' : 'Aktifkan Guru?',
                    html: isActive
                        ? 'Guru <strong>' + nama + '</strong> akan dinonaktifkan dan akun login-nya diblokir.'
                        : 'Guru <strong>' + nama + '</strong> akan diaktifkan kembali beserta akun login-nya.',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: isActive ? 'Ya, Nonaktifkan' : 'Ya, Aktifkan',
                    cancelButtonText: 'Batal',
                    confirmButtonColor: isActive ? '#dc2626' : '#16a34a',
                    cancelButtonColor: '#64748b',
                }).then((r) => {
                    if (!r.isConfirmed) return;
                    guruShowLoading();
                    guruPost("{{ route('guru.toggle-active', ['id' => 'GURU_ID']) }}".replace('GURU_ID', id))
                        .then(({ ok, data }) => {
                            if (ok && data.success) {
                                Swal.fire({ icon: 'success', title: 'Berhasil', text: data.message, confirmButtonColor: '#b08d48' }).then(() => location.reload());
                            } else {
                                Swal.fire({ icon: 'error', title: 'Gagal', text: data.message || 'Proses gagal.', confirmButtonColor: '#ef4444' });
                            }
                        })
                        .catch(() => Swal.fire({ icon: 'error', title: 'Koneksi Gagal', text: 'Tidak dapat menghubungi server.', confirmButtonColor: '#ef4444' }));
                });
            });
        });

        // ===== Perpanjang masa berlaku =====
        document.querySelectorAll('.js-guru-extend').forEach(function(btn) {
            btn.addEventListener('click', function() {
                const id = this.dataset.id;
                const nama = this.dataset.nama;
                const current = this.dataset.activeUntil || '';
                const today = new Date().toISOString().slice(0, 10);
                Swal.fire({
                    title: 'Perpanjang Masa Berlaku',
                    html: 'Guru <strong>' + nama + '</strong>' + (current ? '<br><span class="text-muted">Berlaku saat ini s/d ' + current + '</span>' : ''),
                    input: 'date',
                    inputValue: current || today,
                    inputAttributes: { min: today },
                    showCancelButton: true,
                    confirmButtonText: 'Simpan',
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#0284c7',
                    cancelButtonColor: '#64748b',
                    inputValidator: (value) => {
                        if (!value) return 'Silakan pilih tanggal.';
                        if (value < today) return 'Tanggal minimal hari ini.';
                    }
                }).then((r) => {
                    if (!r.isConfirmed) return;
                    guruShowLoading();
                    guruPost("{{ route('guru.extend', ['id' => 'GURU_ID']) }}".replace('GURU_ID', id), { active_until: r.value })
                        .then(({ ok, data }) => {
                            if (ok && data.success) {
                                Swal.fire({ icon: 'success', title: 'Berhasil', text: data.message, confirmButtonColor: '#b08d48' }).then(() => location.reload());
                            } else {
                                Swal.fire({ icon: 'error', title: 'Gagal', text: data.message || 'Proses gagal.', confirmButtonColor: '#ef4444' });
                            }
                        })
                        .catch(() => Swal.fire({ icon: 'error', title: 'Koneksi Gagal', text: 'Tidak dapat menghubungi server.', confirmButtonColor: '#ef4444' }));
                });
            });
        });

        // ===== Kirim ulang kredensial =====
        document.querySelectorAll('.js-guru-resend').forEach(function(btn) {
            btn.addEventListener('click', function() {
                const id = this.dataset.id;
                const nama = this.dataset.nama;
                const email = this.dataset.email;
                Swal.fire({
                    title: 'Kirim Ulang Kredensial?',
                    html: 'Password login <strong>' + nama + '</strong> akan direset dan kredensial baru dikirim ke:<br><strong>' + email + '</strong>',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Kirim',
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#7c3aed',
                    cancelButtonColor: '#64748b',
                }).then((r) => {
                    if (!r.isConfirmed) return;
                    guruShowLoading();
                    guruPost("{{ route('guru.resend-credentials', ['id' => 'GURU_ID']) }}".replace('GURU_ID', id))
                        .then(({ ok, data }) => {
                            if (ok && data.success) {
                                Swal.fire({ icon: 'success', title: 'Terkirim', text: data.message, confirmButtonColor: '#b08d48' });
                            } else {
                                Swal.fire({ icon: 'error', title: 'Gagal', text: data.message || 'Proses gagal.', confirmButtonColor: '#ef4444' });
                            }
                        })
                        .catch(() => Swal.fire({ icon: 'error', title: 'Koneksi Gagal', text: 'Tidak dapat menghubungi server.', confirmButtonColor: '#ef4444' }));
                });
            });
        });
    });
</script>
@endsection
