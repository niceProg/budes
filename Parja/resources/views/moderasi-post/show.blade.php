@extends('parja::layouts.app')

@section('title', 'Detail Post Alumni')
@section('page-title', 'Detail Post Alumni')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <div>
        <ol class="breadcrumb fs-sm mb-1">
            <li class="breadcrumb-item"><a href="{{ route('parja.moderasi-post.index') }}">Moderasi Post</a></li>
            <li class="breadcrumb-item active" aria-current="page">Detail Post</li>
        </ol>
        <h4 class="main-title mb-0">Detail Post Alumni</h4>
    </div>
    <a href="{{ route('parja.moderasi-post.index') }}" class="btn btn-secondary btn-sm mt-2 mt-sm-0">
        <i class="ri-arrow-left-line"></i> Kembali
    </a>
</div>

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row g-3">
    {{-- Konten Post --}}
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex align-items-center gap-2">
                <span class="fw-semibold flex-grow-1">Konten Post</span>
                @if ($post->status === 0)
                    <span class="badge bg-warning text-dark">Menunggu Review</span>
                @elseif ($post->status === 1)
                    <span class="badge bg-success">Disetujui</span>
                @else
                    <span class="badge bg-danger">Ditolak</span>
                @endif
                <span class="badge {{ $post->tipe === 'artikel' ? 'bg-primary' : 'bg-secondary' }}">
                    {{ $post->tipe === 'artikel' ? 'Artikel' : 'Freepost' }}
                </span>
            </div>
            <div class="card-body">
                @if ($post->tipe === 'artikel' && $post->judul)
                    <h5 class="fw-bold">{{ $post->judul }}</h5>
                    <hr>
                @endif

                <p style="white-space: pre-wrap; word-break: break-word; line-height: 1.7;">{{ $post->konten }}</p>

                @if (!empty($post->foto_paths))
                    <hr>
                    <p class="fw-semibold text-muted small text-uppercase mb-2">Lampiran Foto ({{ count($post->foto_paths) }})</p>
                    <div class="row g-2">
                        @foreach ($post->foto_paths as $foto)
                            <div class="col-6 col-md-4">
                                <a href="{{ asset('storage/' . $foto) }}" target="_blank">
                                    <img src="{{ asset('storage/' . $foto) }}" alt="Foto"
                                         class="img-fluid rounded" style="height: 160px; width: 100%; object-fit: cover;">
                                </a>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Panel Info & Aksi --}}
    <div class="col-lg-4">
        {{-- Info Alumni --}}
        <div class="card mb-3">
            <div class="card-header fw-semibold">Informasi Alumni</div>
            <div class="card-body">
                <div class="mb-2">
                    <label class="text-muted small text-uppercase">Nama</label>
                    <p class="mb-0 fw-semibold">{{ $post->alumni?->nama ?? '-' }}</p>
                </div>
                <div class="mb-2">
                    <label class="text-muted small text-uppercase">Dapil</label>
                    <p class="mb-0">{{ $post->alumni?->dapil ?? '-' }}</p>
                </div>
                <div class="mb-2">
                    <label class="text-muted small text-uppercase">Asal Sekolah</label>
                    <p class="mb-0">{{ $post->alumni?->asal_sekolah ?? '-' }}</p>
                </div>
                <div class="mb-2">
                    <label class="text-muted small text-uppercase">Angkatan</label>
                    <p class="mb-0">{{ $post->alumni?->tahun_angkatan ?? '-' }}</p>
                </div>
                <div>
                    <label class="text-muted small text-uppercase">Pekerjaan</label>
                    <p class="mb-0">{{ $post->alumni?->profile?->pekerjaan_utama ?? '-' }}</p>
                </div>
            </div>
        </div>

        {{-- Info Post --}}
        <div class="card mb-3">
            <div class="card-header fw-semibold">Informasi Post</div>
            <div class="card-body">
                <div class="mb-2">
                    <label class="text-muted small text-uppercase">Dikirim pada</label>
                    <p class="mb-0">{{ $post->created_at->format('d M Y, H:i') }}</p>
                </div>
                @if ($post->approved_at)
                    <div class="mb-2">
                        <label class="text-muted small text-uppercase">Ditinjau pada</label>
                        <p class="mb-0">{{ \Carbon\Carbon::parse($post->approved_at)->format('d M Y, H:i') }}</p>
                    </div>
                    <div>
                        <label class="text-muted small text-uppercase">Ditinjau oleh</label>
                        <p class="mb-0">{{ $approvedBy?->name ?? '-' }}</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- Aksi Moderasi (hanya jika masih pending & punya hak moderasi/approver+) --}}
        @if ($post->status === 0 && rbac_can_moderate_parja())
            <div class="card border-warning">
                <div class="card-header fw-semibold text-warning-emphasis bg-warning-subtle">Tindakan Moderasi</div>
                <div class="card-body">
                    {{-- Setujui --}}
                    <form action="{{ route('parja.moderasi-post.approve', $post->id) }}" method="POST" class="mb-3">
                        @csrf
                        <button type="submit" class="btn btn-success w-100"
                                onclick="return confirm('Setujui dan publikasikan post ini?')">
                            <i class="ri-checkbox-circle-line"></i> Setujui &amp; Publikasikan
                        </button>
                    </form>

                    <hr>

                    {{-- Tolak --}}
                    <form action="{{ route('parja.moderasi-post.reject', $post->id) }}" method="POST"
                          id="formTolak">
                        @csrf
                        <div class="mb-2">
                            <label class="form-label fw-semibold">Catatan Penolakan <span class="text-danger">*</span></label>
                            <textarea name="catatan_admin" class="form-control @error('catatan_admin') is-invalid @enderror"
                                      rows="3" placeholder="Jelaskan alasan penolakan kepada alumni..."
                                      required>{{ old('catatan_admin') }}</textarea>
                            @error('catatan_admin')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-danger w-100"
                                onclick="return confirm('Tolak post ini?')">
                            <i class="ri-close-circle-line"></i> Tolak Post
                        </button>
                    </form>
                </div>
            </div>
        @endif

        {{-- Aksi Permintaan Publish Public (saat ada privasi_request='public' & punya hak moderasi/approver+) --}}
        @if ($post->status === 1 && $post->privasi_request === 'public' && rbac_can_moderate_parja())
            <div class="card border-primary mt-3">
                <div class="card-header fw-semibold text-primary bg-primary-subtle">
                    <i class="ri-earth-line me-1"></i> Permintaan Publikasi ke Public
                </div>
                <div class="card-body">
                    <p class="small text-muted mb-3">
                        Alumni meminta agar artikel ini dapat dilihat oleh <strong>semua orang (public)</strong>,
                        tidak hanya sesama alumni.
                    </p>

                    {{-- Setujui Public --}}
                    <form action="{{ route('parja.moderasi-post.approve-public', $post->id) }}" method="POST" class="mb-3">
                        @csrf
                        <button type="submit"
                                class="btn btn-primary w-100 js-approve-public">
                            <i class="ri-earth-line"></i> Setujui &rarr; Publish Public
                        </button>
                    </form>

                    <hr>

                    {{-- Tolak Public --}}
                    <form action="{{ route('parja.moderasi-post.reject-public', $post->id) }}" method="POST">
                        @csrf
                        <div class="mb-2">
                            <label class="form-label fw-semibold">Catatan Penolakan <span class="text-muted fw-normal">(Opsional)</span></label>
                            <textarea name="catatan_admin" class="form-control"
                                      rows="2" placeholder="Alasan penolakan (opsional)..."></textarea>
                        </div>
                        <button type="submit" class="btn btn-outline-danger w-100 js-reject-public">
                            <i class="ri-close-circle-line"></i> Tolak Permintaan
                        </button>
                    </form>
                </div>
            </div>
        @endif

        {{-- Tampilkan catatan jika sudah ditolak --}}
        @if ($post->status === 2 && $post->catatan_admin)
            <div class="card border-danger">
                <div class="card-header fw-semibold text-danger bg-danger-subtle">Catatan Penolakan</div>
                <div class="card-body">
                    <p class="mb-0">{{ $post->catatan_admin }}</p>
                </div>
            </div>
        @endif

        {{-- Hapus Post (admin) - hanya untuk role yang punya akses delete --}}
        @if (rbac_can_delete_parja())
        <div class="card border-danger mt-3">
            <div class="card-header fw-semibold text-danger bg-danger-subtle">
                <i class="ri-delete-bin-line me-1"></i> Hapus Permanen
            </div>
            <div class="card-body">
                <p class="small text-muted mb-3">
                    Hapus post ini secara permanen jika melanggar standar konten. Tindakan ini tidak dapat dibatalkan.
                </p>
                <form action="{{ route('parja.moderasi-post.destroy', $post->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger w-100 js-delete-post">
                        <i class="ri-delete-bin-line"></i> Hapus Post Ini
                    </button>
                </form>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const approveButtons = document.querySelectorAll('.js-approve-public');
        const rejectPublicButtons = document.querySelectorAll('.js-reject-public');
        const deleteButtons = document.querySelectorAll('.js-delete-post');

        approveButtons.forEach(function (button) {
            button.addEventListener('click', function (event) {
                event.preventDefault();

                const form = button.closest('form');
                if (!form) {
                    return;
                }

                Swal.fire({
                    icon: 'question',
                    title: 'Publish ke Public?',
                    text: 'Artikel ini akan dapat dilihat oleh semua orang.',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Publish',
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#0d6efd',
                    cancelButtonColor: '#6c757d',
                    reverseButtons: true,
                    focusCancel: true,
                    buttonsStyling: true,
                }).then(function (result) {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });

        rejectPublicButtons.forEach(function (button) {
            button.addEventListener('click', function (event) {
                event.preventDefault();

                const form = button.closest('form');
                if (!form) {
                    return;
                }

                Swal.fire({
                    icon: 'warning',
                    title: 'Tolak permintaan public?',
                    text: 'Alumni akan menerima status penolakan untuk permintaan publikasi ini.',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Tolak',
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                    reverseButtons: true,
                    focusCancel: true,
                }).then(function (result) {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });

        deleteButtons.forEach(function (button) {
            button.addEventListener('click', function (event) {
                event.preventDefault();

                const form = button.closest('form');
                if (!form) {
                    return;
                }

                Swal.fire({
                    icon: 'error',
                    title: 'Hapus post ini?',
                    text: 'Tindakan ini permanen dan tidak bisa dibatalkan.',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Hapus',
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                    reverseButtons: true,
                    focusCancel: true,
                }).then(function (result) {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    });
</script>
@endpush
