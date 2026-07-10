@extends('parja::layouts.app')

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <ol class="breadcrumb fs-sm mb-1">
                <li class="breadcrumb-item">Data Website</li>
                <li class="breadcrumb-item"><a href="{{ route('parja.publikasi.index') }}">Daftar Publikasi</a></li>
            </ol>
            <h4 class="main-title mb-0">Edit Publikasi</h4>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('parja.publikasi.update', $record->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="judul" class="form-label fw-bold">Judul <span class="text-danger">*</span></label>
                    <input required type="text" class="form-control" id="judul" name="judul" placeholder="Masukan Judul" value="{{ $record->judul }}">
                </div>
                <div class="mb-3">
                    <label for="deskripsi" class="form-label fw-bold">Deskripsi</label>
                    <textarea class="form-control" id="deskripsi" name="deskripsi" rows="6" placeholder="Masukan Deskripsi">{{ $record->deskripsi }}</textarea>
                </div>
                <div class="mb-3">
                    <label for="file_publikasi_uri" class="form-label fw-bold">URL File Publikasi</label>
                    <input type="text" class="form-control" id="file_publikasi_uri" name="file_publikasi_uri" placeholder="https://..." value="{{ $record->file_publikasi_uri }}">
                </div>
                <div class="mb-3">
                    <label for="cover_uri" class="form-label fw-bold">URL Cover</label>
                    <input type="text" class="form-control" id="cover_uri" name="cover_uri" placeholder="https://..." value="{{ $record->cover_uri }}">
                </div>
                <div class="mb-3">
                    <label for="status_publikasi" class="form-label fw-bold">Status Publikasi</label>
                    <select class="form-select" id="status_publikasi" name="status_publikasi">
                        <option value="0" {{ $record->status_publikasi == 0 ? 'selected' : '' }}>Draft</option>
                        <option value="1" {{ $record->status_publikasi == 1 ? 'selected' : '' }}>Dipublikasikan</option>
                    </select>
                </div>
                <input type="submit" value="Simpan Perubahan" class="btn btn-primary">
                <a href="{{ route('parja.publikasi.index') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
@endsection
