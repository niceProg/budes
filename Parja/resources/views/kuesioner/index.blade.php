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

@section('title', 'Kelola Kuesioner')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="main-title mb-0">Kelola Kuesioner</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 fs-sm">
                <li class="breadcrumb-item"><a href="{{ route($routePrefix . 'index') }}">Daftar Survei</a></li>
                <li class="breadcrumb-item"><a href="{{ route($routePrefix . 'show', $survei->id) }}">{{ Str::limit($survei->judul, 40) }}</a></li>
                <li class="breadcrumb-item active">Kuesioner</li>
            </ol>
        </nav>
    </div>
    <div class="d-flex gap-2">
        @if ($canCreate)
            <a href="{{ route($routePrefix . 'kuesioner.create', $survei->id) }}" class="btn btn-success btn-sm">
                <i class="ri-add-line me-1"></i> Tambah Pertanyaan
            </a>
        @endif
        <a href="{{ route($routePrefix . 'show', $survei->id) }}" class="btn btn-secondary btn-sm">
            <i class="ri-arrow-left-line me-1"></i> Kembali
        </a>
    </div>
</div>

{{-- Stats --}}
<div class="row g-3 mb-4">
    @foreach(['pilihan' => ['Pilihan Ganda','primary'], 'isian' => ['Isian Teks','success'], 'skala' => ['Skala Rating','warning'], 'checkbox' => ['Checkbox','info']] as $key => $val)
    <div class="col-6 col-md-3">
        <div class="card card-one text-center py-3">
            <div class="fs-4 fw-bold text-{{ $val[1] }}">{{ $stats[$key] }}</div>
            <div class="text-muted fs-sm">{{ $val[0] }}</div>
        </div>
    </div>
    @endforeach
</div>

<div class="card card-one">
    <div class="card-body">
        @if($pertanyaan->isEmpty())
            <div class="text-center text-muted py-5">
                <i class="ri-questionnaire-line fs-1 opacity-50"></i>
                <p class="mt-2">Kuesioner masih kosong. Klik "Tambah Pertanyaan" untuk mulai.</p>
            </div>
        @else
        <div class="table-responsive">
            <table id="datatable" class="table table-bordered table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th width="50">No</th>
                        <th>Pertanyaan</th>
                        <th width="130">Tipe Jawaban</th>
                        <th width="120">Opsi Jawaban</th>
                        <th width="130">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pertanyaan as $i => $p)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>
                            <div>{{ $p->pertanyaan }}</div>
                            @if($p->deskripsi)<small class="text-muted">{{ $p->deskripsi }}</small>@endif
                        </td>
                        <td>
                            @php
                                $tipeColor = ['pilihan'=>'primary','isian'=>'success','skala'=>'warning','checkbox'=>'info'];
                                $tipeName  = ['pilihan'=>'Pilihan Ganda','isian'=>'Isian Teks','skala'=>'Skala Rating','checkbox'=>'Checkbox'];
                            @endphp
                            <span class="badge bg-{{ $tipeColor[$p->tipe_jawaban] ?? 'secondary' }}">
                                {{ $tipeName[$p->tipe_jawaban] ?? $p->tipe_jawaban }}
                            </span>
                        </td>
                        <td>
                            @if(in_array($p->tipe_jawaban, ['pilihan','skala','checkbox']))
                                <a href="{{ route($routePrefix . 'kuesioner.opsi.index', [$survei->id, $p->id]) }}"
                                   class="btn btn-outline-primary btn-sm">
                                   Lihat Opsi ({{ $p->opsiJawaban->count() }})
                                </a>
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            @if ($canEdit)
                                <a href="{{ route($routePrefix . 'kuesioner.edit', [$survei->id, $p->id]) }}"
                                   class="btn btn-warning btn-sm">Edit</a>
                            @endif
                            @if ($canDelete)
                                <form action="{{ route($routePrefix . 'kuesioner.destroy', [$survei->id, $p->id]) }}"
                                      method="POST" class="d-inline"
                                      onsubmit="return confirm('Hapus pertanyaan ini?')">
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
@endsection
