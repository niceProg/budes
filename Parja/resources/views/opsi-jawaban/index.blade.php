@extends('parja::layouts.app')
@php
    $routePrefix = $routePrefix
        ?? (request()->routeIs('parja.alumni-survei.*')
            ? 'parja.alumni-survei.'
            : (request()->routeIs('alumni.survei.*') ? 'alumni.survei.' : 'parja.survei.'));

    $isAlumniScope = str_starts_with($routePrefix, 'parja.alumni-survei.') || str_starts_with($routePrefix, 'alumni.survei.');
    $canCreate = $isAlumniScope ? rbac_can_create_alumni() : rbac_can_create_parja();
    $canEdit   = $isAlumniScope ? rbac_can_edit_alumni()   : rbac_can_edit_parja();
    $canDelete = $isAlumniScope ? rbac_can_delete_alumni() : rbac_can_delete_parja();
@endphp

@section('title', 'Kelola Opsi Jawaban')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="main-title mb-0">Kelola Opsi Jawaban</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 fs-sm">
                <li class="breadcrumb-item"><a href="{{ route($routePrefix . 'index') }}">Daftar Survei</a></li>
                <li class="breadcrumb-item"><a href="{{ route($routePrefix . 'kuesioner.index', $survei->id) }}">Kuesioner</a></li>
                <li class="breadcrumb-item active">Opsi Jawaban</li>
            </ol>
        </nav>
    </div>
    <a href="{{ route($routePrefix . 'kuesioner.index', $survei->id) }}" class="btn btn-secondary btn-sm">
        <i class="ri-arrow-left-line me-1"></i> Kembali
    </a>
</div>

<div class="card card-one mb-4">
    <div class="card-header"><h6 class="card-title mb-0">Informasi Pertanyaan</h6></div>
    <div class="card-body">
        <p class="mb-1"><strong>Survei:</strong> {{ $survei->judul }}</p>
        <p class="mb-1"><strong>Pertanyaan:</strong> {{ $kuesioner->pertanyaan }}</p>
        <p class="mb-0">
            <strong>Tipe:</strong>
            @php $nm = ['pilihan'=>'Pilihan Ganda','isian'=>'Isian Teks','skala'=>'Skala Rating','checkbox'=>'Checkbox']; @endphp
            <span class="badge bg-primary">{{ $nm[$kuesioner->tipe_jawaban] ?? $kuesioner->tipe_jawaban }}</span>
        </p>
    </div>
</div>

<div class="row g-3">
    {{-- Form tambah opsi --}}
    @if ($canCreate)
        <div class="col-md-4">
            <div class="card card-one">
                <div class="card-header"><h6 class="card-title mb-0">Tambah Opsi Jawaban</h6></div>
                <div class="card-body">
                    <form action="{{ route($routePrefix . 'kuesioner.opsi.store', [$survei->id, $kuesioner->id]) }}"
                          method="POST" class="needs-validation" novalidate>
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Teks Opsi <span class="text-danger">*</span></label>
                            <input type="text" name="teks_opsi" class="form-control @error('teks_opsi') is-invalid @enderror"
                                   value="{{ old('teks_opsi') }}" required>
                            @error('teks_opsi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nilai / Bobot</label>
                            <input type="number" name="nilai_opsi" class="form-control @error('nilai_opsi') is-invalid @enderror"
                                   value="{{ old('nilai_opsi') }}" placeholder="Opsional">
                            @error('nilai_opsi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <button type="submit" class="btn btn-success w-100">Tambah Opsi</button>
                    </form>
                </div>
            </div>
        </div>
    @endif

    {{-- Daftar opsi --}}
    <div class="{{ $canCreate ? 'col-md-8' : 'col-md-12' }}">
        <div class="card card-one">
            <div class="card-header"><h6 class="card-title mb-0">Daftar Opsi Jawaban</h6></div>
            <div class="card-body">
                @if($opsi->isEmpty())
                    <p class="text-center text-muted py-3">Belum ada opsi jawaban.</p>
                @else
                <div class="table-responsive">
                    <table class="table table-bordered table-sm align-middle">
                        <thead class="table-light">
                            <tr>
                                <th width="40">No</th>
                                <th>Teks Opsi</th>
                                <th width="90">Nilai</th>
                                <th width="100">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($opsi as $i => $o)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $o->teks_opsi }}</td>
                            <td>{{ $o->nilai_opsi ?? '-' }}</td>
                            <td>
                                @if ($canEdit)
                                    <a href="{{ route($routePrefix . 'kuesioner.opsi.edit', [$survei->id, $kuesioner->id, $o->id]) }}"
                                       class="btn btn-warning btn-sm">Edit</a>
                                @endif
                                @if ($canDelete)
                                    <form action="{{ route($routePrefix . 'kuesioner.opsi.destroy', [$survei->id, $kuesioner->id, $o->id]) }}"
                                          method="POST" class="d-inline"
                                          onsubmit="return confirm('Hapus opsi ini?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-danger btn-sm">Hapus</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

