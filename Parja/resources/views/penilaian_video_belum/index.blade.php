@extends('parja::layouts.app')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <div>
        <ol class="breadcrumb fs-sm mb-1">
            <li class="breadcrumb-item">Data Penilaian Video</li>
            <li class="breadcrumb-item active" aria-current="page">Daftar Penilaian Video (Belum)</li>
        </ol>
        <h4 class="main-title mb-0">Daftar Peserta Belum Dinilai Video</h4>
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
            <table id="datatable" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th class="p-1 text-center" style="width: 5%;">No</th>
                        <th class="p-1 text-center">Nama Peserta</th>
                        <th class="p-1 text-center">Email</th>
                        <th class="p-1 text-center">No HP</th>
                        <th class="p-1 text-center">Dapil</th>
                        <th class="p-1 text-center">Status</th>
                        <th class="p-1 text-center" style="width: 15%;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pesertas as $peserta)
                        <tr>
                            <td class="text-center">{{ $pesertas->firstItem() + $loop->index }}</td>
                            <td>{{ $peserta->nama }}</td>
                            <td>{{ $peserta->email ?? '-' }}</td>
                            <td>{{ $peserta->handphone ?? '-' }}</td>
                            <td>{{ $peserta->nama_dapil ?? '-' }}</td>
                            <td class="text-center">
                                @if ($peserta->status == 1)
                                    <span class="badge bg-primary">Terdaftar</span>
                                @elseif ($peserta->status == 2)
                                    <span class="badge bg-success">Lolos</span>
                                @elseif ($peserta->status == 3)
                                    <span class="badge bg-warning text-dark">Lolos Seleksi</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if (rbac_can_create_parja())
                                    <a href="{{ route('parja.penilaian_video.add', $peserta->id) }}" class="btn btn-success btn-sm">
                                        <i class="ri-add-line"></i> Nilai Video
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
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
