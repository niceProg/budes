@extends('parja::layouts.app')
@php
    $routePrefix = $routePrefix
        ?? (request()->routeIs('parja.alumni-survei.*')
            ? 'parja.alumni-survei.'
            : (request()->routeIs('alumni.survei.*') ? 'alumni.survei.' : 'parja.survei.'));
@endphp

@section('title', 'Edit Opsi Jawaban')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="main-title mb-0">Edit Opsi Jawaban</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 fs-sm">
                <li class="breadcrumb-item"><a href="{{ route($routePrefix . 'kuesioner.opsi.index', [$survei->id, $kuesioner->id]) }}">Opsi Jawaban</a></li>
                <li class="breadcrumb-item active">Edit</li>
            </ol>
        </nav>
    </div>
</div>

<div class="card card-one" style="max-width:480px">
    <div class="card-header"><h6 class="card-title mb-0">Update Opsi</h6></div>
    <div class="card-body">
        <form action="{{ route($routePrefix . 'kuesioner.opsi.update', [$survei->id, $kuesioner->id, $opsi->id]) }}"
              method="POST" class="needs-validation" novalidate>
            @csrf @method('PUT')

            <div class="mb-3">
                <label class="form-label fw-semibold">Teks Opsi <span class="text-danger">*</span></label>
                <input type="text" name="teks_opsi" class="form-control @error('teks_opsi') is-invalid @enderror"
                       value="{{ old('teks_opsi', $opsi->teks_opsi) }}" required>
                @error('teks_opsi')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-4">
                <label class="form-label fw-semibold">Nilai / Bobot</label>
                <input type="number" name="nilai_opsi" class="form-control"
                       value="{{ old('nilai_opsi', $opsi->nilai_opsi) }}" placeholder="Opsional">
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-success">Update Opsi</button>
                <a href="{{ route($routePrefix . 'kuesioner.opsi.index', [$survei->id, $kuesioner->id]) }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection

