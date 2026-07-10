@extends('layouts.app')

@section('title', 'Detail Satuan Kerja | Admin - SMART Setjen DPR RI')
@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

    :root {
        --primary-dark: #0f172a;
        --accent-gold: linear-gradient(135deg, #c5a059 0%, #8e6d2f 100%);
        --gold-solid: #b08d48;
        --gold-soft: rgba(176, 141, 72, 0.1);
        --text-main: #1e293b;
        --text-muted: #64748b;
        --white: #ffffff;
        --bg-slate: #f8fafc;
        --transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
    }

    .content-wrapper {
        position: relative;
        background: var(--bg-slate);
        min-height: 100vh;
        padding-bottom: 3rem;
    }

    /* Background Batik */
    .content-wrapper::before {
        content: '';
        position: fixed;
        top: 0; left: 0;
        width: 100%; height: 100%;
        background-image: url('{{ asset("theme/admin-dashbyte/dist/assets/img/batiknew.png") }}');
        background-size: 400px;
        opacity: 0.04;
        z-index: 0;
        pointer-events: none;
    }

    .page-header {
        margin-bottom: 2.5rem;
        position: relative;
        z-index: 1;
    }

    .page-title {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-weight: 800;
        font-size: 2.25rem;
        color: var(--primary-dark);
        letter-spacing: -0.025em;
    }

    .breadcrumb-item { font-size: 0.85rem; }
    .breadcrumb-item a { color: var(--text-muted); text-decoration: none; transition: var(--transition); }
    .breadcrumb-item a:hover { color: var(--gold-solid); }

    /* Modern Card Glassmorphism */
    .modern-card {
        background: rgba(255, 255, 255, 0.85);
        backdrop-filter: blur(20px);
        border-radius: 24px;
        padding: 3rem;
        box-shadow: 0 20px 40px rgba(0,0,0,0.06);
        border: 1px solid rgba(255, 255, 255, 0.7);
        position: relative;
        z-index: 1;
        overflow: hidden;
    }

    .modern-card::before {
        content: '';
        position: absolute;
        top: 0; left: 0;
        width: 100%; height: 6px;
        background: var(--accent-gold);
    }

    /* Layouting */
    .detail-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 3rem;
    }

    .info-container {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1.5rem;
    }

    .info-box {
        background: white;
        padding: 1.5rem;
        border-radius: 16px;
        border: 1px solid #f1f5f9;
        transition: var(--transition);
    }

    .info-box.full-width { grid-column: span 2; }

    .info-box:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.04);
        border-color: var(--gold-soft);
    }

    .info-label {
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: var(--text-muted);
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .info-label i { color: var(--gold-solid); font-size: 1rem; }

    .info-value {
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--primary-dark);
        word-break: break-word;
    }

    .id-badge {
        background: var(--primary-dark);
        color: white;
        padding: 0.2rem 0.6rem;
        border-radius: 6px;
        font-size: 0.8rem;
    }

    .code-badge {
        background: var(--gold-soft);
        color: var(--gold-solid);
        padding: 0.2rem 0.8rem;
        border-radius: 8px;
        font-family: 'Courier New', Courier, monospace;
    }

    .status-badge {
        padding: 6px 16px;
        border-radius: 8px;
        font-weight: 700;
        font-size: 0.85rem;
    }

    .status-active { background: #dcfce7; color: #16a34a; }
    .status-inactive { background: #fee2e2; color: #dc2626; }

    .btn-back {
        display: inline-flex;
        align-items: center;
        gap: 0.75rem;
        background: white;
        color: var(--primary-dark);
        padding: 0.85rem 1.75rem;
        border-radius: 12px;
        border: 2px solid #e2e8f0;
        font-weight: 700;
        transition: var(--transition);
        text-decoration: none;
        margin-top: 2.5rem;
    }

    .btn-back:hover {
        background: var(--primary-dark);
        color: white;
        border-color: var(--primary-dark);
        transform: translateX(-5px);
    }

    .children-list {
        background: #f8fafc;
        border-radius: 12px;
        padding: 1rem;
        margin-top: 0.5rem;
    }

    .children-item {
        padding: 0.5rem 0;
        border-bottom: 1px solid #e2e8f0;
    }

    .children-item:last-child {
        border-bottom: none;
    }

    @media (max-width: 576px) {
        .info-container { grid-template-columns: 1fr; }
        .info-box.full-width { grid-column: span 1; }
    }

    /* Dark mode: konsisten dengan tema gelap */
    html[data-skin="dark"] .content-wrapper {
        background: transparent !important;
    }
    html[data-skin="dark"] .content-wrapper::before {
        background: transparent !important;
        opacity: 0;
    }
    html[data-skin="dark"] .page-title { color: #ffffff !important; }
    html[data-skin="dark"] .breadcrumb-item a { color: #9ca3af !important; }
    html[data-skin="dark"] .breadcrumb-item.active { color: #fbbf24 !important; }
    html[data-skin="dark"] .modern-card {
        background: #0f172a !important;
        border-color: rgba(251, 191, 36, 0.35) !important;
        box-shadow: 0 20px 40px rgba(0,0,0,0.5);
    }
    html[data-skin="dark"] .info-box {
        background: #1e293b !important;
        border-color: #374151 !important;
    }
    html[data-skin="dark"] .info-label { color: #9ca3af !important; }
    html[data-skin="dark"] .info-label i { color: #fbbf24 !important; }
    html[data-skin="dark"] .info-value { color: #ffffff !important; }
    html[data-skin="dark"] .info-value .text-muted { color: #9ca3af !important; }
    html[data-skin="dark"] .id-badge { background: #374151 !important; color: #ffffff !important; }
    html[data-skin="dark"] .code-badge { background: #374151 !important; color: #fbbf24 !important; }
    html[data-skin="dark"] .btn-back {
        background: #1e293b !important;
        border-color: #374151 !important;
        color: #ffffff !important;
    }
    html[data-skin="dark"] .btn-back:hover {
        background: #0f172a !important;
        border-color: #fbbf24 !important;
        color: #fbbf24 !important;
    }
    html[data-skin="dark"] .children-list {
        background: #1e293b !important;
    }
    html[data-skin="dark"] .children-item {
        border-color: #374151 !important;
    }
</style>

<div class="content-wrapper">
    <div class="page-header">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-2">
                <li class="breadcrumb-item"><a href="{{ route('satker.index') }}">Satuan Kerja</a></li>
                <li class="breadcrumb-item active fw-bold" style="color: var(--gold-solid)" aria-current="page">Detail Informasi</li>
            </ol>
        </nav>
        <h1 class="page-title">Informasi Satuan Kerja</h1>
    </div>

    <div class="modern-card">
        <div class="detail-grid">
            <!-- Data Section -->
            <div class="info-section">
                <div class="info-container">
                    <div class="info-box">
                        <div class="info-label"><i class="ri-hashtag"></i> ID Satker</div>
                        <div class="info-value"><span class="id-badge">#{{ $data->id }}</span></div>
                    </div>

                    <div class="info-box">
                        <div class="info-label"><i class="ri-qr-code-line"></i> Kode Satker</div>
                        <div class="info-value"><span class="code-badge">{{ $data->kode_satker }}</span></div>
                    </div>

                    <div class="info-box full-width">
                        <div class="info-label"><i class="ri-community-line"></i> Nama Satuan Kerja</div>
                        <div class="info-value" style="font-size: 1.5rem;">{{ $data->nama_satker }}</div>
                    </div>

                    <div class="info-box full-width">
                        <div class="info-label"><i class="ri-git-branch-line"></i> Parent Unit</div>
                        <div class="info-value">
                            @if($data->parent)
                                <a href="{{ route('satker.show', $data->parent->id) }}" class="text-decoration-none" style="color: var(--gold-solid);">
                                    {{ $data->parent->nama_satker }}
                                </a>
                            @else
                                -
                            @endif
                        </div>
                    </div>

                    <div class="info-box">
                        <div class="info-label"><i class="ri-building-line"></i> Eselon</div>
                        <div class="info-value">{{ $data->eselon ?? '-' }}</div>
                    </div>

                    <div class="info-box">
                        <div class="info-label"><i class="ri-building-2-line"></i> ID Biro</div>
                        <div class="info-value">{{ $data->id_biro ?? '-' }}</div>
                    </div>

                    <div class="info-box">
                        <div class="info-label"><i class="ri-group-line"></i> Jumlah Pegawai</div>
                        <div class="info-value">
                            @if($data->jumlah_pegawai !== null)
                                {{ number_format($data->jumlah_pegawai) }} orang
                            @else
                                <span class="text-muted fw-normal">Tidak tersedia</span>
                            @endif
                        </div>
                    </div>

                    <div class="info-box">
                        <div class="info-label d-flex align-items-center justify-content-between gap-2">
                            <span><i class="ri-team-line"></i> Kuota Peserta Magang</span>
                            <button type="button" class="btn btn-sm btn-outline-warning fw-bold"
                                    onclick="editKuotaMagang({{ $data->id }}, {{ json_encode($data->nama_satker) }}, {{ $data->kuota === null ? 'null' : (int) $data->kuota }})">
                                <i class="ri-edit-line"></i> Edit
                            </button>
                        </div>
                        <div class="info-value" id="kuotaDisplay">
                            @if($data->kuota === null)
                                <span class="badge bg-success bg-opacity-10 text-success fw-bold">Unlimited</span>
                            @elseif($data->kuota === 0)
                                <span class="badge bg-secondary bg-opacity-10 text-secondary fw-bold">Tidak dibuka</span>
                            @else
                                <span class="badge bg-primary bg-opacity-10 text-primary fw-bold">{{ $data->kuota }} orang</span>
                            @endif
                        </div>
                    </div>

                    <div class="info-box">
                        <div class="info-label"><i class="ri-checkbox-circle-line"></i> Status</div>
                        <div class="info-value">
                            @if($data->status == 1)
                                <span class="status-badge status-active">Aktif</span>
                            @else
                                <span class="status-badge status-inactive">Nonaktif</span>
                            @endif
                        </div>
                    </div>

                    @if($data->children->count() > 0)
                    <div class="info-box full-width">
                        <div class="info-label"><i class="ri-node-tree"></i> Child Units ({{ $data->children->count() }})</div>
                        <div class="children-list">
                            @foreach($data->children as $child)
                            <div class="children-item">
                                <a href="{{ route('satker.show', $child->id) }}" class="text-decoration-none">
                                    <span class="badge-code me-2">{{ $child->kode_satker }}</span>
                                    {{ $child->nama_satker }}
                                </a>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <div class="info-box">
                        <div class="info-label"><i class="ri-calendar-line"></i> Terdaftar Sejak</div>
                        <div class="info-value">{{ $data->created_at->locale('id')->translatedFormat('d F Y') }} <span class="small text-muted fw-normal">({{ $data->created_at->format('H:i') }})</span></div>
                    </div>

                    <div class="info-box">
                        <div class="info-label"><i class="ri-history-line"></i> Pembaruan Terakhir</div>
                        <div class="info-value">{{ $data->updated_at->locale('id')->translatedFormat('d F Y') }} <span class="small text-muted fw-normal">({{ $data->updated_at->format('H:i') }})</span></div>
                    </div>
                </div>

                <a href="{{ route('satker.index') }}" class="btn-back">
                    <i class="ri-arrow-left-s-line"></i> Kembali ke Daftar
                </a>
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
</script>
@endsection
