@extends('parja::layouts.app')

@section('title', 'Moderasi Direktori Alumni')
@section('page-title', 'Moderasi Direktori Alumni')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <div>
        <ol class="breadcrumb fs-sm mb-1">
            <li class="breadcrumb-item">Parja Alumni</li>
            <li class="breadcrumb-item active" aria-current="page">Moderasi Direktori</li>
        </ol>
        <h4 class="main-title mb-0">Moderasi Kartu Direktori Alumni</h4>
    </div>
</div>

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

{{-- Tab Filter --}}
<div class="card mb-3">
    <div class="card-body p-2">
        <ul class="nav nav-pills gap-1">
            @foreach (['pending' => 'Pending', 'disetujui' => 'Disetujui', 'ditolak' => 'Ditolak', 'semua' => 'Semua'] as $key => $label)
                @php
                    $isActive = $tab === $key;
                    $url = route('parja.moderasi-direktori.index', ['tab' => $key]);
                    $count = match ($key) {
                        'pending' => \Modules\Parja\App\Models\AlumniProfileModel::query()->direktoriPending()->count(),
                        'disetujui' => \Modules\Parja\App\Models\AlumniProfileModel::query()->direktoriApproved()->count(),
                        'ditolak' => \Modules\Parja\App\Models\AlumniProfileModel::query()->direktoriRejected()->count(),
                        default => \Modules\Parja\App\Models\AlumniProfileModel::query()->where('direktori_consent', 1)->count(),
                    };
                @endphp
                <li class="nav-item">
                    <a href="{{ $url }}" class="nav-link py-1 px-3 {{ $isActive ? 'active' : '' }}"
                       @if ($isActive) aria-current="page" @endif>
                        {{ $label }}
                        <span class="badge rounded-pill ms-1 {{ $isActive ? 'bg-white text-dark' : 'bg-secondary' }}">{{ $count }}</span>
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form method="GET" action="{{ route('parja.moderasi-direktori.index') }}" class="mb-3">
            <input type="hidden" name="tab" value="{{ $tab }}">
            <div class="input-group">
                <span class="input-group-text bg-white border-end-0"><i class="ri-search-line text-muted"></i></span>
                <input type="text" name="search" class="form-control border-start-0 ps-0"
                       placeholder="Cari nama alumni..." value="{{ $search ?? '' }}">
                <button class="btn btn-primary" type="submit"><i class="ri-search-line me-1"></i>Cari</button>
                @if(!empty($search ?? ''))
                    <a href="{{ route('parja.moderasi-direktori.index', ['tab' => $tab]) }}" class="btn btn-outline-secondary" title="Reset"><i class="ri-close-line"></i></a>
                @endif
            </div>
        </form>

        @if ($profiles->isEmpty())
            <div class="text-center py-5 text-muted">
                <i class="ri-inbox-line fs-1 d-block mb-2"></i>
                Tidak ada kartu direktori untuk ditampilkan.
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th class="p-2" style="width:4%">#</th>
                            <th class="p-2 text-center" style="width:8%">Foto</th>
                            <th class="p-2">Alumni</th>
                            <th class="p-2">Pendidikan Saat Ini</th>
                            <th class="p-2">LinkedIn</th>
                            <th class="p-2" style="width:12%">Consent</th>
                            <th class="p-2 text-center" style="width:11%">Status</th>
                            <th class="p-2 text-center" style="width:14%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($profiles as $i => $profile)
                            @php
                                $fotoUrl = !empty($profile->foto_profil)
                                    ? \Illuminate\Support\Facades\Storage::disk('public')->url($profile->foto_profil)
                                    : null;
                                $linkedinRaw = is_array($profile->akun_medsos) ? trim((string) ($profile->akun_medsos['linkedin'] ?? '')) : '';
                                $linkedinUrl = $linkedinRaw === ''
                                    ? null
                                    : (preg_match('/^https?:\/\//i', $linkedinRaw)
                                        ? $linkedinRaw
                                        : 'https://www.linkedin.com/in/'.rawurlencode(strtolower(preg_replace('/\s+/', '', ltrim($linkedinRaw, '@')))));
                            @endphp
                            <tr>
                                <td class="p-2 text-muted small">{{ $profiles->firstItem() + $i }}</td>
                                <td class="p-2 text-center">
                                    @if ($fotoUrl)
                                        <img src="{{ $fotoUrl }}" alt="Foto" class="rounded-circle"
                                             style="width:42px;height:42px;object-fit:cover;">
                                    @else
                                        <span class="text-muted small">-</span>
                                    @endif
                                </td>
                                <td class="p-2">
                                    <span class="fw-semibold d-block">{{ $profile->alumni?->nama ?? '-' }}</span>
                                    <span class="text-muted small">{{ $profile->alumni?->dapil ?? $profile->dapil ?? '-' }}</span>
                                </td>
                                <td class="p-2">
                                    <span class="small">{{ $profile->pendidikan_saat_ini ?: '-' }}</span>
                                </td>
                                <td class="p-2">
                                    @if ($linkedinUrl)
                                        <a href="{{ $linkedinUrl }}" target="_blank" rel="noopener" class="small">
                                            <i class="ri-linkedin-box-fill text-primary"></i> Lihat
                                        </a>
                                    @else
                                        <span class="text-muted small">-</span>
                                    @endif
                                </td>
                                <td class="p-2">
                                    <span class="text-muted small">
                                        {{ optional($profile->direktori_consent_at)->format('d M Y H:i') ?? '-' }}
                                    </span>
                                </td>
                                <td class="p-2 text-center">
                                    @if ((int) $profile->direktori_status === 1)
                                        <span class="badge bg-success">Disetujui</span>
                                    @elseif ((int) $profile->direktori_status === 2)
                                        <span class="badge bg-danger">Ditolak</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Pending</span>
                                    @endif
                                </td>
                                <td class="p-2 text-center">
                                    @if ((int) $profile->direktori_status !== 1)
                                        <form action="{{ route('parja.moderasi-direktori.approve', $profile->id) }}"
                                              method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success" title="Setujui">
                                                <i class="ri-check-line"></i>
                                            </button>
                                        </form>
                                    @endif
                                    @if ((int) $profile->direktori_status !== 2)
                                        <button type="button" class="btn btn-sm btn-danger js-reject-btn" title="Tolak"
                                                data-action="{{ route('parja.moderasi-direktori.reject', $profile->id) }}"
                                                data-nama="{{ $profile->alumni?->nama ?? 'alumni ini' }}">
                                            <i class="ri-close-line"></i>
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $profiles->links() }}
            </div>
        @endif
    </div>
</div>

{{-- Modal Tolak --}}
<div class="modal fade" id="rejectModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" id="rejectForm">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tolak Kartu Direktori</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="small text-muted mb-2">Tolak kartu direktori milik <strong id="rejectNama"></strong>.</p>
                    <label class="form-label">Catatan penolakan <span class="text-danger">*</span></label>
                    <textarea name="direktori_catatan_admin" class="form-control" rows="3" required
                              placeholder="Alasan penolakan (mis. foto/LinkedIn tidak sesuai)..."></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Tolak Kartu</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var rejectModalEl = document.getElementById('rejectModal');
        var rejectForm = document.getElementById('rejectForm');
        var rejectNama = document.getElementById('rejectNama');
        if (!rejectModalEl || typeof bootstrap === 'undefined') return;
        var modal = new bootstrap.Modal(rejectModalEl);

        document.querySelectorAll('.js-reject-btn').forEach(function (btn) {
            btn.addEventListener('click', function () {
                rejectForm.setAttribute('action', this.dataset.action);
                rejectNama.textContent = this.dataset.nama || 'alumni ini';
                modal.show();
            });
        });
    });
</script>
@endsection
