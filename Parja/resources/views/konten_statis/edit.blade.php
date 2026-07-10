@extends('parja::layouts.app')

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <ol class="breadcrumb fs-sm mb-1">
                <li class="breadcrumb-item">Data Website</li>
                <li class="breadcrumb-item"><a href="{{ route('parja.konten_statis.index') }}">Daftar Konten Statis</a></li>
            </ol>
            <h4 class="main-title mb-0">Edit Konten Statis</h4>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('parja.konten_statis.update', $record->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="judul" class="form-label fw-bold">Judul <span class="text-danger">*</span></label>
                    <input required type="text" class="form-control" id="judul" name="judul" placeholder="Masukan Judul" value="{{ $record->judul }}">
                </div>
                <div class="mb-3">
                    <label for="konten" class="form-label fw-bold">Konten</label>
                    <textarea class="form-control" id="konten" name="konten" rows="6" placeholder="Masukan Konten">{{ $record->konten }}</textarea>
                </div>
                <input type="submit" value="Simpan Perubahan" class="btn btn-primary">
                <a href="{{ route('parja.konten_statis.index') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
@endsection
