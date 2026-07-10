@extends('parja::layouts.app')

@section('title', 'Daftar Peserta')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="main-title mb-0">Daftar Peserta</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 fs-sm">
                <li class="breadcrumb-item">Data Peserta</li>
                <li class="breadcrumb-item active">Daftar Peserta</li>
            </ol>
        </nav>
    </div>
    @if (rbac_can_create_parja(auth()->user()))
        <a href="{{ route('parja.peserta.add') }}" class="btn btn-success btn-sm">
            <i class="ri-user-add-line me-1"></i> Tambah Peserta
        </a>
    @endif
</div>

<div class="card card-one">
    <div class="card-body">
        <form method="GET" action="" class="mb-3">
            <div class="input-group">
                <span class="input-group-text bg-white border-end-0"><i class="ri-search-line text-muted"></i></span>
                <input type="text" name="search" class="form-control border-start-0 ps-0"
                       placeholder="Cari nama, NISN, email, asal sekolah..."
                       value="{{ $search ?? '' }}">
                <button class="btn btn-primary" type="submit"><i class="ri-search-line me-1"></i>Cari</button>
                @if(!empty($search ?? ''))
                    <a href="{{ request()->url() }}" class="btn btn-outline-secondary" title="Reset pencarian"><i class="ri-close-line"></i></a>
                @endif
            </div>
        </form>
        <div class="table-responsive">
            <table id="datatable" class="table table-bordered table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th width="50">No</th>
                        <th>Nama</th>
                        <th>NISN</th>
                        <th>Jenis Kelamin</th>
                        <th>Email</th>
                        <th>No. HP</th>
                        <th>Asal Sekolah</th>
                        <th width="120">Tahap</th>
                        <th width="120">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pesertas as $i => $p)
                    <tr>
                        <td>{{ $pesertas->firstItem() + $loop->index }}</td>
                        <td>{{ $p->nama ?? '-' }}</td>
                        <td>{{ $p->nisn ?? '-' }}</td>
                        <td>{{ $p->jenis_kelamin ?? '-' }}</td>
                        <td>{{ $p->email ?? '-' }}</td>
                        <td>{{ $p->handphone ?? '-' }}</td>
                        <td>{{ $p->asal_sekolah ?? '-' }}</td>
                        <td>
                            @switch($p->tahap())
                                @case('aktif')
                                    <span class="badge bg-success-subtle text-success"><i class="ri-shield-check-line"></i> Akun Aktif</span>
                                    @break
                                @case('terpilih')
                                    <span class="badge bg-warning-subtle text-warning"><i class="ri-star-line"></i> Terpilih</span>
                                    @break
                                @default
                                    <span class="badge bg-secondary-subtle text-secondary"><i class="ri-user-line"></i> Pendaftar</span>
                            @endswitch
                        </td>
                        <td>
                            @if (rbac_can_edit_parja(auth()->user()))
                                <a href="{{ route('parja.peserta.edit', $p->id) }}"
                                   class="btn btn-primary btn-sm">Edit</a>
                            @endif
                            @if (rbac_can_delete_parja(auth()->user()))
                                <form action="{{ route('parja.peserta.destroy', $p->id) }}"
                                      method="POST" class="d-inline" onsubmit="return confirm('Hapus peserta ini?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-danger btn-sm">Hapus</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="9" class="text-center text-muted">Belum ada peserta</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($pesertas->hasPages())
        <div class="d-flex justify-content-between align-items-center mt-3 flex-wrap gap-2">
            <p class="text-muted small mb-0">Menampilkan {{ $pesertas->firstItem() }}–{{ $pesertas->lastItem() }} dari {{ $pesertas->total() }} data</p>
            {{ $pesertas->appends(request()->only(['search']))->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
