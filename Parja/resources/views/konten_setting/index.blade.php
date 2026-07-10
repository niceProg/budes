@extends('parja::layouts.app')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <div>
        <ol class="breadcrumb fs-sm mb-1">
            <li class="breadcrumb-item">Data Website</li>
            <li class="breadcrumb-item active" aria-current="page">Daftar Konten Setting</li>
        </ol>
        <h4 class="main-title mb-0">Daftar Konten Setting</h4>
    </div>
    <div>
        @if (rbac_can_create_parja())
            <a href="{{ route('parja.konten_setting.add') }}" class="btn btn-success">
                <i class="ri-pencil-line"></i> Tambah Konten Setting
            </a>
        @endif
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="alert alert-info py-2">
            Pengelolaan banner besar beranda alumni sudah dipisah ke menu <strong>Data Banner Alumni</strong>.
        </div>
        <div class="table-responsive">
            <table id="datatable" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th class="p-1 text-center" style="width: 5%;">No</th>
                        <th class="p-1 text-center" style="width: 25%;">Nama</th>
                        <th class="p-1 text-center" style="width: 40%;">Nilai</th>
                        <th class="p-1 text-center" style="width: 10%;">Tipe</th>
                        <th class="p-1 text-center" style="width: 20%;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($records as $item)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td>{{ $item->nama }}</td>
                            <td>{{ Str::limit($item->nilai, 80) }}</td>
                            <td class="text-center">{{ $item->tipe }}</td>
                            <td class="text-center">
                                @if (rbac_can_edit_parja())
                                    <a href="{{ route('parja.konten_setting.edit', $item->id) }}" class="btn btn-primary">
                                        <i class="ri-edit-2-line"></i> Edit
                                    </a>
                                @endif
                                @if (rbac_can_delete_parja())
                                    <form action="{{ route('parja.konten_setting.destroy', $item->id) }}" method="POST" style="display: inline-block" class="form-hapus">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-danger btn-hapus">
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
