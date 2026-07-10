@extends('parja::layouts.app')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <div>
        <ol class="breadcrumb fs-sm mb-1">
            <li class="breadcrumb-item">Data Website</li>
            <li class="breadcrumb-item active" aria-current="page">Jadwal Pendaftaran</li>
        </ol>
        <h4 class="main-title mb-0">Jadwal / Timeline Pendaftaran</h4>
        <p class="text-secondary mb-0" style="font-size:.85rem;">Fase yang tampil di section Timeline pada landing publik Parlemen Remaja.</p>
    </div>
    <div>
        @if (rbac_can_create_parja())
            <a href="{{ route('parja.jadwal.add') }}" class="btn btn-success">
                <i class="ri-pencil-line"></i> Tambah Fase
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
                        <th class="p-1 text-center" style="width: 6%;">Urutan</th>
                        <th class="p-1 text-center" style="width: 12%;">Fase</th>
                        <th class="p-1 text-center" style="width: 22%;">Judul</th>
                        <th class="p-1 text-center" style="width: 30%;">Deskripsi</th>
                        <th class="p-1 text-center" style="width: 13%;">Tanggal</th>
                        <th class="p-1 text-center" style="width: 17%;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($records as $item)
                        <tr>
                            <td class="text-center">{{ $item->urutan }}</td>
                            <td>{{ $item->fase }}</td>
                            <td>
                                <i class="bi {{ $item->icon }}"></i> {{ $item->judul }}
                            </td>
                            <td>{{ Str::limit($item->deskripsi, 90) }}</td>
                            <td class="text-center">{{ $item->tanggal_label }}</td>
                            <td class="text-center">
                                @if (rbac_can_edit_parja())
                                    <a href="{{ route('parja.jadwal.edit', $item->id) }}" class="btn btn-primary">
                                        <i class="ri-edit-2-line"></i> Edit
                                    </a>
                                @endif
                                @if (rbac_can_delete_parja())
                                    <form action="{{ route('parja.jadwal.destroy', $item->id) }}" method="POST" style="display: inline-block" class="form-hapus">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-danger btn-hapus">
                                            <i class="ri-delete-bin-line"></i> Hapus
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-secondary py-4">Belum ada fase. Klik "Tambah Fase" untuk menambah.</td>
                        </tr>
                    @endforelse
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
