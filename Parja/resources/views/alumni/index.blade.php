@extends('parja::layouts.app')

@section('title', 'Register Akun Alumni')
@section('page-title', 'Data Akun Alumni')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <div>
        <ol class="breadcrumb fs-sm mb-1">
            <li class="breadcrumb-item">Data Akun Alumni</li>
            <li class="breadcrumb-item active" aria-current="page">Daftar Alumni</li>
        </ol>
        <h4 class="main-title mb-0">Daftar Alumni</h4>
    </div>
    <div>
        @if (rbac_can_create_alumni(auth()->user()))
            <a href="{{ route('parja.alumni.create') }}" class="btn btn-success">
                <i class="ri-user-add-line"></i> Tambah Alumni
            </a>
        @endif
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form method="GET" action="" class="mb-3">
            <div class="input-group">
                <span class="input-group-text bg-white border-end-0"><i class="ri-search-line text-muted"></i></span>
                <input type="text" name="search" class="form-control border-start-0 ps-0"
                       placeholder="Cari nama, no anggota, email, dapil, angkatan..."
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
                        <th class="p-1 text-center">No Anggota</th>
                        <th class="p-1 text-center">Nama</th>
                        <th class="p-1 text-center">Asal Sekolah</th>
                        <th class="p-1 text-center">Dapil</th>
                        <th class="p-1 text-center">Tahun Angkatan</th>
                        <th class="p-1 text-center">Status</th>
                        <th class="p-1 text-center" style="width: 18%;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($alumnis as $alumni)
                        <tr>
                            <td class="text-center">{{ $alumnis->firstItem() + $loop->index }}</td>
                            <td class="text-center">{{ $alumni->no_anggota ?? '-' }}</td>
                            <td class="text-center">{{ $alumni->nama }}</td>
                            <td class="text-center">{{ $alumni->asal_sekolah ?? '-' }}</td>
                            <td class="text-center">{{ $alumni->dapil ?? '-' }}</td>
                            <td class="text-center">{{ $alumni->tahun_angkatan ?? '-' }}</td>
                            <td class="text-center">
                                @php $alumniStatus = \App\Enums\AlumniApprovalStatus::tryFrom((int) $alumni->approval_status); @endphp
                                @if ($alumniStatus?->isPending())
                                    <span class="badge bg-warning text-dark"><i class="ri-timer-line"></i> Pending</span>
                                @elseif ($alumniStatus?->isApproved())
                                    <span class="badge bg-success"><i class="ri-check-line"></i> Disetujui</span>
                                @elseif ($alumniStatus?->isRejected())
                                    <span class="badge bg-danger"><i class="ri-close-line"></i> Ditolak</span>
                                @else
                                    <span class="badge bg-secondary">Unknown</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if (rbac_can_edit_alumni(auth()->user()))
                                    <a href="{{ route('parja.alumni.edit', $alumni->id) }}"
                                        class="btn btn-primary btn-sm">
                                        <i class="ri-edit-2-line"></i> Edit
                                    </a>
                                @endif
                                @if (rbac_can_delete_alumni(auth()->user()))
                                    <form action="{{ route('parja.alumni.destroy', $alumni->id) }}" method="POST"
                                        style="display: inline-block;" class="form-hapus">
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
        @if($alumnis->hasPages())
        <div class="d-flex justify-content-between align-items-center mt-3 flex-wrap gap-2">
            <p class="text-muted small mb-0">Menampilkan {{ $alumnis->firstItem() }}–{{ $alumnis->lastItem() }} dari {{ $alumnis->total() }} data</p>
            {{ $alumnis->appends(request()->only(['search']))->links() }}
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function () {
        $('.btn-hapus').on('click', function (e) {
            e.preventDefault();
            let form = $(this).closest('form');
            Swal.fire({
                title: 'Yakin ingin menghapus?',
                text: 'Data yang dihapus tidak dapat dikembalikan!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>
@endpush
