@extends('parja::layouts.app')

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <ol class="breadcrumb fs-sm mb-1">
                <li class="breadcrumb-item">Data Website</li>
                <li class="breadcrumb-item"><a href="{{ route('parja.album_foto.index') }}">Daftar Album Foto</a></li>
            </ol>
            <h4 class="main-title mb-0">Tambah Album Foto</h4>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('parja.album_foto.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="tanggal" class="form-label fw-bold">Tanggal <span class="text-danger">*</span></label>
                    <input required type="date" class="form-control" id="tanggal" name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}">
                </div>
                <div class="mb-3">
                    <label for="album_foto" class="form-label fw-bold">Album Foto <small class="text-muted">(JSON array nama file)</small></label>
                    <textarea class="form-control" id="album_foto" name="album_foto" rows="4" placeholder="[]">{{ old('album_foto') }}</textarea>
                </div>
                <input type="submit" value="Simpan" class="btn btn-primary">
                <a href="{{ route('parja.album_foto.index') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
@endsection
