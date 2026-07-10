@extends('parja::layouts.app')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <div>
        <ol class="breadcrumb fs-sm mb-1">
            <li class="breadcrumb-item">Data Peserta</li>
            <li class="breadcrumb-item active" aria-current="page">Daftar Penilaian</li>
        </ol>
        <h4 class="main-title mb-0">Daftar Penilaian</h4>
    </div>
    <div>
        @if (rbac_can_create_parja())
            <a href="{{ route('parja.penilaian.add') }}" class="btn btn-success">
                <i class="ri-pencil-line"></i> Tambah Penilaian
            </a>
        @endif
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table id="datatable" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th class="p-1 text-center" style="width: 5%;">No</th>
                        <th class="p-1 text-center">Peserta</th>
                        <th class="p-1 text-center">Dapil</th>
                        <th class="p-1 text-center" style="width: 10%;">Nilai CV</th>
                        <th class="p-1 text-center" style="width: 10%;">Nilai Esai</th>
                        <th class="p-1 text-center" style="width: 10%;">Nilai Video</th>
                        <th class="p-1 text-center" style="width: 10%;">Nilai Total</th>
                        <th class="p-1 text-center" style="width: 20%;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($penilaians as $penilaian)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td>{{ $penilaian->nama ?? '-' }}</td>
                            <td>{{ $penilaian->nama_dapil ?? '-' }}</td>
                            <td class="text-center">{{ $penilaian->nilai_cv ?? '-' }}</td>
                            <td class="text-center">{{ $penilaian->nilai_esai ?? '-' }}</td>
                            <td class="text-center">{{ $penilaian->nilai_video ?? '-' }}</td>
                            <td class="text-center fw-bold">
                                @if ($penilaian->nilai_total !== null)
                                    <span class="badge {{ $penilaian->nilai_total >= 70 ? 'bg-success' : 'bg-danger' }}">
                                        {{ number_format($penilaian->nilai_total, 2) }}
                                    </span>
                                @else
                                    -
                                @endif
                            </td>
                            <td class="text-center">
                                @if (rbac_can_edit_parja())
                                    <a href="{{ route('parja.penilaian.edit', $penilaian->id) }}" class="btn btn-primary btn-sm">
                                        <i class="ri-edit-2-line"></i> Edit
                                    </a>
                                @endif
                                @if (rbac_can_delete_parja())
                                    <form action="{{ route('parja.penilaian.destroy', $penilaian->id) }}" method="POST"
                                        style="display: inline-block" class="form-hapus">
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
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('.btn-hapus').on('click', function(e) {
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
