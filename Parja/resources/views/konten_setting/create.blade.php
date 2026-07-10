@extends('parja::layouts.app')

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <ol class="breadcrumb fs-sm mb-1">
                <li class="breadcrumb-item">Data Website</li>
                <li class="breadcrumb-item"><a href="{{ route('parja.konten_setting.index') }}">Daftar Konten Setting</a></li>
            </ol>
            <h4 class="main-title mb-0">Tambah Konten Setting</h4>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="alert alert-info py-2">
                Untuk banner besar beranda alumni, gunakan menu <strong>Data Banner Alumni</strong> agar pengelolaan terpisah dari konten setting umum.
            </div>
            <form action="{{ route('parja.konten_setting.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="nama" class="form-label fw-bold">Nama <span class="text-danger">*</span></label>
                    <input required type="text" class="form-control" id="nama" name="nama" placeholder="Contoh: alumni_home_banner_1_image" value="{{ old('nama') }}">
                </div>
                <div class="mb-3">
                    <label for="nilai" class="form-label fw-bold">Nilai</label>
                    <textarea class="form-control" id="nilai" name="nilai" rows="4" placeholder="URL gambar / judul / link sesuai nama slot">{{ old('nilai') }}</textarea>
                </div>
                <div class="mb-3">
                    <label for="tipe" class="form-label fw-bold">Tipe</label>
                    <input type="text" class="form-control" id="tipe" name="tipe" placeholder="text / image / url / dll" value="{{ old('tipe') }}" maxlength="20">
                </div>
                <input type="submit" value="Simpan" class="btn btn-primary">
                <a href="{{ route('parja.konten_setting.index') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
@endsection
