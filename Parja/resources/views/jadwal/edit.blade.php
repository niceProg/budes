@extends('parja::layouts.app')

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <ol class="breadcrumb fs-sm mb-1">
                <li class="breadcrumb-item">Data Website</li>
                <li class="breadcrumb-item"><a href="{{ route('parja.jadwal.index') }}">Jadwal Pendaftaran</a></li>
            </ol>
            <h4 class="main-title mb-0">Edit Fase</h4>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('parja.jadwal.update', $record->id) }}" method="POST">
                @csrf
                @method('PUT')
                @include('parja::jadwal._form', ['record' => $record])
                <input type="submit" value="Perbarui" class="btn btn-primary">
                <a href="{{ route('parja.jadwal.index') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
@endsection
