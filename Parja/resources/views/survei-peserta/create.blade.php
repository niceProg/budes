@extends('parja::layouts.app')
@php
    $routePrefix = $routePrefix
        ?? (request()->routeIs('parja.alumni-survei.*')
            ? 'parja.alumni-survei.'
            : (request()->routeIs('alumni.survei.*') ? 'alumni.survei.' : 'parja.survei.'));
@endphp

@section('title', 'Tambah Peserta Survei')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="main-title mb-0">Tambah Peserta Survei</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 fs-sm">
                <li class="breadcrumb-item"><a href="{{ route($routePrefix . 'peserta.index', $survei->id) }}">Peserta</a></li>
                <li class="breadcrumb-item active">Tambah</li>
            </ol>
        </nav>
    </div>
    <a href="{{ route($routePrefix . 'peserta.index', $survei->id) }}" class="btn btn-secondary btn-sm">
        <i class="ri-arrow-left-line me-1"></i> Batal
    </a>
</div>

<div class="card card-one">
    <div class="card-header">
        <h6 class="card-title mb-0">Survei: {{ $survei->judul }}</h6>
    </div>
    <div class="card-body">
        <form action="{{ route($routePrefix . 'peserta.store', $survei->id) }}" method="POST">
            @csrf

            @if($errors->has('responden_ids'))
                <div class="alert alert-danger">{{ $errors->first('responden_ids') }}</div>
            @endif

            <p class="text-muted fs-sm mb-3">Centang responden yang ingin ditambahkan ke survei ini.</p>

            <div class="table-responsive">
                <table id="datatable" class="table table-bordered table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th width="50">
                                <input type="checkbox" id="checkAll" class="form-check-input">
                            </th>
                            <th>Nama</th>
                            <th width="70">Umur</th>
                            <th width="120">Jenis Kelamin</th>
                            <th>No. Telepon</th>
                            <th>Asal Data</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($respondens as $r)
                        <tr>
                            <td>
                                <input type="checkbox" name="responden_ids[]" value="{{ $r->id }}" class="form-check-input chk-item">
                            </td>
                            <td>{{ $r->nama }}</td>
                            <td>{{ $r->umur ?? '-' }}</td>
                            <td>{{ $r->jenis_kelamin == 1 ? 'Laki-laki' : ($r->jenis_kelamin == 2 ? 'Perempuan' : '-') }}</td>
                            <td>{{ $r->nomor_telepon ?? '-' }}</td>
                            <td>{{ $r->asal_data ?? '-' }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="text-center text-muted">Semua responden sudah terdaftar atau belum ada data</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3 d-flex gap-2">
                <button type="submit" class="btn btn-success">
                    <i class="ri-user-add-line me-1"></i> Tambah Peserta
                </button>
                <a href="{{ route($routePrefix . 'peserta.index', $survei->id) }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const checkAll = document.getElementById('checkAll');
    const items = document.querySelectorAll('.chk-item');

    if (checkAll) {
        checkAll.addEventListener('change', function () {
            items.forEach(function (item) {
                item.checked = checkAll.checked;
            });
        });
    }
});
</script>
@endpush

