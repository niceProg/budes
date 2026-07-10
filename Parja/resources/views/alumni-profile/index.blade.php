@extends('parja::layouts.app')

@section('title', 'Profil Alumni')
@section('page-title', 'Profil Alumni')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <div>
        <ol class="breadcrumb fs-sm mb-1">
            <li class="breadcrumb-item">Data Akun Alumni</li>
            <li class="breadcrumb-item active" aria-current="page">Profil Alumni</li>
        </ol>
        <h4 class="main-title mb-0">Daftar Profil Alumni</h4>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form method="GET" action="" class="mb-3">
            <div class="input-group">
                <span class="input-group-text bg-white border-end-0"><i class="ri-search-line text-muted"></i></span>
                <input type="text" name="search" class="form-control border-start-0 ps-0"
                       placeholder="Cari nama, angkatan, dapil, domisili..."
                       value="{{ $search ?? '' }}">
                <button class="btn btn-primary" type="submit"><i class="ri-search-line me-1"></i>Cari</button>
                @if(!empty($search ?? ''))
                    <a href="{{ request()->url() }}" class="btn btn-outline-secondary" title="Reset"><i class="ri-close-line"></i></a>
                @endif
            </div>
        </form>
        <div class="table-responsive">
            <table id="datatable" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th class="p-1 text-center" style="width: 5%;">No</th>
                        <th class="p-1 text-center">Nama Alumni</th>
                        <th class="p-1 text-center">Angkatan</th>
                        <th class="p-1 text-center">Dapil</th>
                        <th class="p-1 text-center">Pendidikan Saat Ini</th>
                        <th class="p-1 text-center">Pekerjaan Saat Ini</th>
                        <th class="p-1 text-center">Ketertarikan Bidang</th>
                        <th class="p-1 text-center">Domisili</th>
                        <th class="p-1 text-center">Foto</th>
                        <th class="p-1 text-center">Status</th>
                        <th class="p-1 text-center" style="width: 15%;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($profiles as $profile)
                        <tr>
                            <td class="text-center">{{ $profiles->firstItem() + $loop->index }}</td>
                            <td class="text-center">{{ $profile->alumni->nama ?? '-' }}</td>
                            <td class="text-center">{{ $profile->angkatan ?? '-' }}</td>
                            <td class="text-center">{{ $profile->alumni->dapil ?? '-' }}</td>
                            <td class="text-center">{{ $profile->pendidikan_saat_ini ?? '-' }}</td>
                            <td class="text-center">{{ $profile->pekerjaan_utama ?? '-' }}</td>
                            <td class="text-center">
                                @php
                                    $ketertarikanBidang = $profile->ketertarikan_bidang;

                                    if (is_string($ketertarikanBidang)) {
                                        $decodedBidang = json_decode($ketertarikanBidang, true);
                                        $ketertarikanBidang = json_last_error() === JSON_ERROR_NONE
                                            ? $decodedBidang
                                            : [$ketertarikanBidang];
                                    }

                                    $ketertarikanBidang = collect($ketertarikanBidang ?? [])->filter()->values();
                                @endphp

                                @if ($ketertarikanBidang->isNotEmpty())
                                    {{ $ketertarikanBidang->implode(', ') }}
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="text-center">{{ $profile->domisili_terakhir ?? '-' }}</td>
                            <td class="text-center">
                                @if ($profile->foto_profil)
                                    <img src="{{ Storage::disk('public')->url($profile->foto_profil) }}"
                                         alt="Foto" class="rounded-circle"
                                         style="width:40px;height:40px;object-fit:cover;">
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <span class="badge bg-success">Diterima</span>
                            </td>
                            <td class="text-center">
                                @if (rbac_can_edit_alumni(auth()->user()))
                                    <a href="{{ route('parja.alumni-profile.edit', $profile->id) }}"
                                        class="btn btn-primary btn-sm">
                                        <i class="ri-edit-2-line"></i> Edit
                                    </a>
                                @endif
                                @if (rbac_can_delete_alumni(auth()->user()))
                                    <form action="{{ route('parja.alumni-profile.destroy', $profile->id) }}"
                                        method="POST" style="display:inline-block;" class="form-hapus">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-danger btn-sm btn-hapus">
                                            <i class="ri-delete-bin-line"></i> Hapus
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if($profiles->hasPages())
        <div class="d-flex justify-content-between align-items-center mt-3 flex-wrap gap-2">
            <p class="text-muted small mb-0">Menampilkan {{ $profiles->firstItem() }}–{{ $profiles->lastItem() }} dari {{ $profiles->total() }} data</p>
            {{ $profiles->appends(request()->only(['search']))->links() }}
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.querySelectorAll('.btn-hapus').forEach(function (btn) {
        btn.addEventListener('click', function () {
            Swal.fire({
                title: 'Hapus Profil?',
                text: 'Data profil akan dihapus secara permanen.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
            }).then(function (result) {
                if (result.isConfirmed) {
                    btn.closest('form').submit();
                }
            });
        });
    });
</script>
@endpush
