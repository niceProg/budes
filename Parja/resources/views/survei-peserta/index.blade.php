@extends('parja::layouts.app')
@php
    $routePrefix = $routePrefix
        ?? (request()->routeIs('parja.alumni-survei.*')
            ? 'parja.alumni-survei.'
            : (request()->routeIs('alumni.survei.*') ? 'alumni.survei.' : 'parja.survei.'));

    $isAlumniScope = str_starts_with($routePrefix, 'parja.alumni-survei.') || str_starts_with($routePrefix, 'alumni.survei.');
    $canCreate = $isAlumniScope ? rbac_can_create_alumni() : rbac_can_create_parja();
    $canDelete = $isAlumniScope ? rbac_can_delete_alumni() : rbac_can_delete_parja();
@endphp

@section('title', 'Peserta Survei')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="main-title mb-0">Peserta Survei</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 fs-sm">
                <li class="breadcrumb-item"><a href="{{ route($routePrefix . 'index') }}">Daftar Survei</a></li>
                <li class="breadcrumb-item"><a href="{{ route($routePrefix . 'show', $survei->id) }}">{{ Str::limit($survei->judul, 40) }}</a></li>
                <li class="breadcrumb-item active">Peserta</li>
            </ol>
        </nav>
    </div>
    <div class="d-flex gap-2">
        @if ($canCreate)
            <a href="{{ route($routePrefix . 'peserta.create', $survei->id) }}" class="btn btn-success btn-sm">
                <i class="ri-user-add-line me-1"></i> Tambah Peserta
            </a>
        @endif
        <a href="{{ route($routePrefix . 'show', $survei->id) }}" class="btn btn-secondary btn-sm">
            <i class="ri-arrow-left-line me-1"></i> Kembali
        </a>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card card-one text-center py-3">
            <div class="fs-4 fw-bold text-secondary">{{ $stats['terdaftar'] }}</div>
            <div class="text-muted fs-sm">Terdaftar</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card card-one text-center py-3">
            <div class="fs-4 fw-bold text-warning">{{ $stats['sedang_mengisi'] }}</div>
            <div class="text-muted fs-sm">Sedang Mengisi</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card card-one text-center py-3">
            <div class="fs-4 fw-bold text-success">{{ $stats['selesai'] }}</div>
            <div class="text-muted fs-sm">Selesai</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card card-one text-center py-3">
            <div class="fs-4 fw-bold text-danger">{{ $stats['batal'] }}</div>
            <div class="text-muted fs-sm">Batal</div>
        </div>
    </div>
</div>

<div class="card card-one">
    <div class="card-body">
        <div class="table-responsive">
            <table id="datatable" class="table table-bordered table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th width="50">No</th>
                        <th>Nama Responden</th>
                        <th width="140">No. Telepon</th>
                        <th width="130">Status</th>
                        <th width="80">Progress</th>
                        <th width="130">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($peserta as $i => $p)
                    @php
                        $statusMap = [
                            'terdaftar' => ['secondary', 'Terdaftar'],
                            'sedang_mengisi' => ['warning', 'Sedang Mengisi'],
                            'selesai' => ['success', 'Selesai'],
                            'batal' => ['danger', 'Batal'],
                        ];
                        $badge = $statusMap[$p->status_partisipasi] ?? ['light', '-'];
                    @endphp
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $p->responden->nama ?? '-' }}</td>
                        <td>{{ $p->responden->handphone ?? $p->responden->nomor_telepon ?? '-' }}</td>
                        <td><span class="badge bg-{{ $badge[0] }}">{{ $badge[1] }}</span></td>
                        <td>{{ number_format($p->persentase_selesai ?? 0, 0) }}%</td>
                        <td>
                            <a href="{{ route($routePrefix . 'peserta.show', [$survei->id, $p->id]) }}" class="btn btn-info btn-sm">Lihat</a>
                            @if ($canDelete)
                                <form action="{{ route($routePrefix . 'peserta.destroy', [$survei->id, $p->id]) }}"
                                      method="POST" class="d-inline" onsubmit="return confirm('Hapus peserta dari survei ini?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-danger btn-sm">Hapus</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center text-muted">Belum ada peserta pada survei ini</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

