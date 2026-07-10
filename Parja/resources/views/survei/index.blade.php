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

@section('title', 'Daftar Survei')
@section('page-title', 'Daftar Survei')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="main-title mb-0">Daftar Survei</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 fs-sm">
                <li class="breadcrumb-item"><a href="{{ route('parja.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Daftar Survei</li>
            </ol>
        </nav>
    </div>
    @if ($canCreate)
        <a href="{{ route($routePrefix . 'create') }}" class="btn btn-success btn-sm">
            <i class="ri-add-line me-1"></i> Tambah Survei
        </a>
    @endif
</div>

<div class="card card-one">
    <div class="card-body">
        <form method="GET" action="" class="mb-3">
            <div class="input-group">
                <span class="input-group-text bg-white border-end-0"><i class="ri-search-line text-muted"></i></span>
                <input type="text" name="search" class="form-control border-start-0 ps-0"
                       placeholder="Cari judul atau deskripsi survei..."
                       value="{{ $search ?? '' }}">
                <button class="btn btn-primary" type="submit"><i class="ri-search-line me-1"></i>Cari</button>
                @if(!empty($search ?? ''))
                    <a href="{{ request()->url() }}" class="btn btn-outline-secondary" title="Reset"><i class="ri-close-line"></i></a>
                @endif
            </div>
        </form>
        <div class="table-responsive">
            <table id="datatable" class="table table-bordered table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th width="50">No</th>
                        <th>Judul Survei</th>
                        <th width="110">Mulai</th>
                        <th width="110">Selesai</th>
                        <th width="110">Status</th>
                        <th width="200">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($survei as $i => $s)
                    <tr>
                        <td>{{ $survei->firstItem() + $loop->index }}</td>
                        <td>
                            <div class="fw-semibold">{{ $s->judul }}</div>
                            @if($s->deskripsi)
                                <small class="text-muted">{{ Str::limit($s->deskripsi, 80) }}</small>
                            @endif
                        </td>
                        <td>{{ $s->tanggal_mulai ? \Carbon\Carbon::parse($s->tanggal_mulai)->format('d/m/Y') : '-' }}</td>
                        <td>{{ $s->tanggal_selesai ? \Carbon\Carbon::parse($s->tanggal_selesai)->format('d/m/Y') : '-' }}</td>
                        <td>
                            @if($s->status_publikasi === 'draft')
                                <span class="badge bg-secondary">Draft</span>
                            @elseif($s->status_publikasi === 'terbit')
                                <span class="badge bg-success">Terbit</span>
                            @else
                                <span class="badge bg-dark">Selesai</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route($routePrefix . 'show', $s->id) }}" class="btn btn-info btn-sm">Lihat</a>
                            <a href="{{ route($routePrefix . 'kuesioner.index', $s->id) }}" class="btn btn-primary btn-sm">Kuesioner</a>
                            <a href="{{ route($routePrefix . 'peserta.index', $s->id) }}" class="btn btn-warning btn-sm">Peserta</a>
                            @if ($canDelete)
                                <form action="{{ route($routePrefix . 'destroy', $s->id) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('Hapus survei ini?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-danger btn-sm">Hapus</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center text-muted">Belum ada data survei</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($survei->hasPages())
        <div class="d-flex justify-content-between align-items-center mt-3 flex-wrap gap-2">
            <p class="text-muted small mb-0">Menampilkan {{ $survei->firstItem() }}–{{ $survei->lastItem() }} dari {{ $survei->total() }} data</p>
            {{ $survei->appends(request()->only(['search']))->links() }}
        </div>
        @endif
    </div>
</div>
@endsection

