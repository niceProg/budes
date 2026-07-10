@extends('parja::layouts.app')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <div>
        <ol class="breadcrumb fs-sm mb-1">
            <li class="breadcrumb-item">Data Penilaian Esai</li>
            <li class="breadcrumb-item active" aria-current="page">Daftar Penilaian Esai (Sudah)</li>
        </ol>
        <h4 class="main-title mb-0">Daftar Peserta Sudah Dinilai Esai</h4>
    </div>
    <div>
        @if (rbac_can_create_parja())
            <a href="{{ route('parja.penilaian_esai.add') }}" class="btn btn-success">
                <i class="ri-pencil-line"></i> Tambah Penilaian Esai
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
                       placeholder="Cari nama, email, asal sekolah..."
                       value="{{ $search ?? '' }}">
                <button class="btn btn-primary" type="submit"><i class="ri-search-line me-1"></i>Cari</button>
                @if(!empty($search ?? ''))
                    <a href="{{ request()->url() }}" class="btn btn-outline-secondary" title="Reset"><i class="ri-close-line"></i></a>
                @endif
            </div>
        </form>
        <div class="table-responsive">
                </thead>
                <tbody>
                    @foreach ($penilaians as $penilaian)
                        <tr>
                            <td class="text-center">{{ $penilaians->firstItem() + $loop->index }}</td>
                            <td>{{ $penilaian->nama ?? '-' }}</td>
                            <td>{{ $penilaian->nama_dapil ?? '-' }}</td>
                            <td class="text-center fw-bold">
                                @if ($penilaian->nilai_esai !== null)
                                    <span class="badge {{ $penilaian->nilai_esai >= 70 ? 'bg-success' : 'bg-danger' }}">
                                        {{ number_format($penilaian->nilai_esai, 2) }}
                                    </span>
                                @else
                                    -
                                @endif
                            </td>
                            <td>{{ $penilaian->catatan_esai ?? '-' }}</td>
                            <td>{{ $penilaian->updated_by ?? '-' }}</td>
                            <td>{{ $penilaian->updated_at ? \Carbon\Carbon::parse($penilaian->updated_at)->format('d/m/Y') : '-' }}</td>
                            <td class="text-center">
                                @if (rbac_can_edit_parja())
                                    <a href="{{ route('parja.penilaian_esai.edit', $penilaian->id) }}" class="btn btn-primary btn-sm">
                                        <i class="ri-edit-2-line"></i> Edit
                                    </a>
                                @endif
                                @if (rbac_can_delete_parja())
                                    <form action="{{ route('parja.penilaian_esai.destroy', $penilaian->id) }}" method="POST"
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
        @if($penilaians->hasPages())
        <div class="d-flex justify-content-between align-items-center mt-3 flex-wrap gap-2">
            <p class="text-muted small mb-0">Menampilkan {{ $penilaians->firstItem() }}–{{ $penilaians->lastItem() }} dari {{ $penilaians->total() }} data</p>
            {{ $penilaians->appends(request()->only(['search']))->links() }}
        </div>
        @endif
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
                text: 'Data penilaian esai yang dihapus tidak dapat dikembalikan!',
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
