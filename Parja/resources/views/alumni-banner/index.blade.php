@extends('parja::layouts.app')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <div>
        <ol class="breadcrumb fs-sm mb-1">
            <li class="breadcrumb-item">Parja Alumni</li>
            <li class="breadcrumb-item active" aria-current="page">Data Banner Alumni</li>
        </ol>
        <h4 class="main-title mb-0">Data Banner Alumni</h4>
        <p class="text-muted mb-0 fs-sm mt-1">Banner ini khusus untuk banner besar di beranda alumni dan tidak terhubung ke Data Kegiatan Alumni.</p>
    </div>
    <div>
        @if (rbac_can_edit_alumni() && $records->count() > 1)
            <button id="btn-save-order" type="button" class="btn btn-primary me-2" style="display:none;">
                <i class="ri-save-line"></i> Simpan Urutan Drag
            </button>
        @endif
        @if (rbac_can_create_alumni())
            <a href="{{ route('parja.alumni-banner.create') }}" class="btn btn-success">
                <i class="ri-add-line"></i> Tambah Banner
            </a>
        @endif
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="alert alert-info py-2 mb-3">
            <strong>Catatan:</strong> Banner besar bersifat readonly di sisi user (tidak bisa diklik dan tidak membuka detail).
        </div>

        <div class="table-responsive">
            <table class="table table-striped table-bordered align-middle">
                <thead>
                    <tr>
                        <th class="text-center" style="width:4%;">Drag</th>
                        <th class="text-center" style="width:5%;">No</th>
                        <th class="text-center" style="width:10%;">Urutan</th>
                        <th style="width:30%;">Judul</th>
                        <th>URL Gambar</th>
                        <th class="text-center" style="width:15%;">Aksi</th>
                    </tr>
                </thead>
                <tbody id="sortable-banner-body">
                    @forelse ($records as $item)
                        <tr data-id="{{ $item->id }}" draggable="true">
                            <td class="text-center text-muted" style="cursor: move;">
                                <i class="ri-drag-move-line"></i>
                            </td>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td class="text-center">{{ $item->banner_order }}</td>
                            <td>{{ $item->banner_title ?: '-' }}</td>
                            <td>
                                <div class="small text-muted">{{ \Illuminate\Support\Str::limit($item->banner_image_url, 90) }}</div>
                                @if(!empty($item->banner_image_url))
                                    <a href="{{ $item->banner_image_url }}" target="_blank" rel="noopener">Preview</a>
                                @endif
                            </td>
                            <td class="text-center">
                                @if (rbac_can_edit_alumni())
                                    <a href="{{ route('parja.alumni-banner.edit', $item->id) }}" class="btn btn-sm btn-primary" title="Edit">
                                        <i class="ri-edit-2-line"></i>
                                    </a>
                                @endif
                                @if (rbac_can_delete_alumni())
                                    <form action="{{ route('parja.alumni-banner.destroy', $item->id) }}" method="POST" style="display:inline-block" class="form-hapus">
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
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="ri-image-line fs-1 d-block mb-2"></i>
                                Belum ada banner alumni.@if (rbac_can_create_alumni()) <a href="{{ route('parja.alumni-banner.create') }}">Tambah sekarang</a>.@endif
                            </td>
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
$(document).ready(function () {
    let orderChanged = false;
    let draggedRow = null;
    const sortableBody = document.getElementById('sortable-banner-body');
    const saveOrderBtn = document.getElementById('btn-save-order');

    if (sortableBody && saveOrderBtn) {
        sortableBody.querySelectorAll('tr[data-id]').forEach((row) => {
            row.addEventListener('dragstart', function () {
                draggedRow = this;
                this.classList.add('table-warning');
            });

            row.addEventListener('dragend', function () {
                this.classList.remove('table-warning');
            });

            row.addEventListener('dragover', function (event) {
                event.preventDefault();
            });

            row.addEventListener('drop', function (event) {
                event.preventDefault();

                if (!draggedRow || draggedRow === this) {
                    return;
                }

                const rows = Array.from(sortableBody.querySelectorAll('tr[data-id]'));
                const draggedIndex = rows.indexOf(draggedRow);
                const targetIndex = rows.indexOf(this);

                if (draggedIndex < targetIndex) {
                    this.after(draggedRow);
                } else {
                    this.before(draggedRow);
                }

                orderChanged = true;
                saveOrderBtn.style.display = 'inline-block';
            });
        });

        saveOrderBtn.addEventListener('click', async function () {
            const ids = Array.from(sortableBody.querySelectorAll('tr[data-id]')).map((row) => Number(row.dataset.id));

            if (ids.length < 2 || !orderChanged) {
                return;
            }

            try {
                const response = await fetch('{{ route('parja.alumni-banner.reorder') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ ordered_ids: ids }),
                });

                const result = await response.json();

                if (!response.ok) {
                    throw new Error(result.message || 'Gagal menyimpan urutan.');
                }

                await Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: result.message || 'Urutan banner berhasil diperbarui.',
                    timer: 1200,
                    showConfirmButton: false,
                });

                window.location.reload();
            } catch (error) {
                Swal.fire('Gagal', error.message || 'Terjadi kesalahan saat menyimpan urutan.', 'error');
            }
        });
    }

    $('.btn-hapus').on('click', function () {
        let form = $(this).closest('form');
        Swal.fire({
            title: 'Hapus Banner Alumni?',
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
