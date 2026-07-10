@extends('parja::layouts.app')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <div>
        <ol class="breadcrumb fs-sm mb-1">
            <li class="breadcrumb-item">Parja Alumni</li>
            <li class="breadcrumb-item active" aria-current="page">Info Alumni Parja</li>
        </ol>
        <h4 class="main-title mb-0">Info Alumni Parja</h4>
        <p class="text-muted mb-0 fs-sm mt-1">Kelola berita, kegiatan, dan pengumuman untuk alumni Parja</p>
    </div>
    <div>
        @if (rbac_can_create_alumni())
            <a href="{{ route('parja.alumni-info.create') }}" class="btn btn-success">
                <i class="ri-add-line"></i> Tambah Info Baru
            </a>
        @endif
    </div>
</div>

{{-- Statistik ringkas --}}
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="card card-one h-100">
            <div class="card-body p-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="card-icon bg-primary bg-opacity-10 rounded-3 p-3">
                        <i class="ri-newspaper-line fs-4 text-primary"></i>
                    </div>
                    <div>
                        <h6 class="card-value mb-0">{{ $countBerita }}</h6>
                        <small class="text-muted">Berita</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card card-one h-100">
            <div class="card-body p-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="card-icon bg-success bg-opacity-10 rounded-3 p-3">
                        <i class="ri-calendar-event-line fs-4 text-success"></i>
                    </div>
                    <div>
                        <h6 class="card-value mb-0">{{ $countKegiatan }}</h6>
                        <small class="text-muted">Kegiatan</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card card-one h-100">
            <div class="card-body p-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="card-icon bg-warning bg-opacity-10 rounded-3 p-3">
                        <i class="ri-notification-3-line fs-4 text-warning"></i>
                    </div>
                    <div>
                        <h6 class="card-value mb-0">{{ $countPengumuman }}</h6>
                        <small class="text-muted">Pengumuman</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card card-one h-100">
            <div class="card-body p-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="card-icon bg-info bg-opacity-10 rounded-3 p-3">
                        <i class="ri-slideshow-line fs-4 text-info"></i>
                    </div>
                    <div>
                        <h6 class="card-value mb-0">{{ $countSlideshow }}</h6>
                        <small class="text-muted">Di Slideshow</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form method="GET" action="" class="mb-3">
            <div class="input-group">
                <span class="input-group-text bg-white border-end-0"><i class="ri-search-line text-muted"></i></span>
                <input type="text" name="search" class="form-control border-start-0 ps-0"
                       placeholder="Cari judul, deskripsi, atau tipe info..."
                       value="{{ $search ?? '' }}">
                <button class="btn btn-primary" type="submit"><i class="ri-search-line me-1"></i>Cari</button>
                @if(!empty($search ?? ''))
                    <a href="{{ request()->url() }}" class="btn btn-outline-secondary" title="Reset"><i class="ri-close-line"></i></a>
                @endif
            </div>
        </form>
        <div class="table-responsive">
            <table id="datatable" class="table table-striped table-bordered align-middle">
                <thead>
                    <tr>
                        <th class="text-center" style="width:4%;">No</th>
                        <th style="width:8%;">Tipe</th>
                        <th>Judul</th>
                        <th class="text-center" style="width:8%;">Slideshow</th>
                        <th class="text-center" style="width:8%;">Disematkan</th>
                        <th class="text-center" style="width:10%;">Status</th>
                        <th class="text-center" style="width:10%;">Tgl. Publikasi</th>
                        <th class="text-center" style="width:12%;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($records as $item)
                    <tr>
                        <td class="text-center">{{ $records->firstItem() + $loop->index }}</td>
                        <td>
                            <span class="badge {{ $item->tipe_badge_class }}">
                                {{ $item->tipe_label }}
                            </span>
                        </td>
                        <td>
                            <div class="fw-semibold">{{ $item->judul }}</div>
                            @if($item->ringkasan)
                                <small class="text-muted">{{ Str::limit($item->ringkasan, 80) }}</small>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($item->is_slideshow)
                                <span class="badge bg-info"><i class="ri-check-line"></i> Ya</span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($item->is_pinned)
                                <span class="badge bg-orange text-white" style="background:#fd7e14!important">
                                    <i class="ri-pushpin-line"></i> Pin
                                </span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($item->status_publikasi)
                                <span class="badge bg-success"><i class="ri-checkbox-circle-line"></i> Tayang</span>
                            @else
                                <span class="badge bg-secondary">Draft</span>
                            @endif
                        </td>
                        <td class="text-center">
                            {{ $item->tanggal_publikasi ? $item->tanggal_publikasi->format('d M Y') : '-' }}
                        </td>
                        <td class="text-center">
                            @if (rbac_can_edit_alumni())
                                <a href="{{ route('parja.alumni-info.edit', $item->id) }}"
                                   class="btn btn-sm btn-primary" title="Edit">
                                    <i class="ri-edit-2-line"></i>
                                </a>
                            @endif
                            @if (rbac_can_delete_alumni())
                                <form action="{{ route('parja.alumni-info.destroy', $item->id) }}"
                                      method="POST" style="display:inline-block" class="form-hapus">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-sm btn-danger btn-hapus" title="Hapus">
                                        <i class="ri-delete-bin-line"></i>
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">
                            <i class="ri-inbox-line fs-1 d-block mb-2"></i>
                            Belum ada info alumni.@if (rbac_can_create_alumni()) <a href="{{ route('parja.alumni-info.create') }}">Tambah sekarang</a>.@endif
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($records->hasPages())
        <div class="d-flex justify-content-between align-items-center mt-3 flex-wrap gap-2">
            <p class="text-muted small mb-0">Menampilkan {{ $records->firstItem() }}–{{ $records->lastItem() }} dari {{ $records->total() }} data</p>
            {{ $records->appends(request()->only(['search']))->links() }}
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function () {
    $('.btn-hapus').on('click', function () {
        let form = $(this).closest('form');
        Swal.fire({
            title: 'Hapus Info Alumni?',
            text: 'Data yang dihapus tidak dapat dikembalikan!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) form.submit();
        });
    });
});
</script>
@endpush
