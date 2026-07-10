@extends('parja::layouts.app')

@section('title', 'Tambah Peserta')

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <ol class="breadcrumb fs-sm mb-1">
                <li class="breadcrumb-item">Data Peserta</li>
                <li class="breadcrumb-item"><a href="{{ route('parja.peserta.index') }}">Daftar Peserta</a></li>
            </ol>
            <h4 class="main-title mb-0">Tambah Peserta</h4>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('parja.peserta.store') }}" method="POST">
                @csrf

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="nama" class="form-label fw-bold">Nama Lengkap <span class="text-danger">*</span></label>
                            <input required type="text" class="form-control" id="nama" name="nama"
                                placeholder="Masukkan Nama Lengkap" value="{{ old('nama') }}">
                            @error('nama') <div class="text-danger small">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label for="nisn" class="form-label fw-bold">NISN</label>
                            <input type="text" class="form-control" id="nisn" name="nisn"
                                placeholder="Masukkan NISN" value="{{ old('nisn') }}" maxlength="20">
                            @error('nisn') <div class="text-danger small">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label for="jenis_kelamin" class="form-label fw-bold">Jenis Kelamin</label>
                            <select class="form-select" id="jenis_kelamin" name="jenis_kelamin">
                                <option value="">-- Pilih --</option>
                                <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                            @error('jenis_kelamin') <div class="text-danger small">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label fw-bold">Email</label>
                            <input type="email" class="form-control" id="email" name="email"
                                placeholder="Masukkan Email" value="{{ old('email') }}">
                            @error('email') <div class="text-danger small">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label for="handphone" class="form-label fw-bold">No HP</label>
                            <input type="text" class="form-control" id="handphone" name="handphone"
                                placeholder="Masukkan No HP" value="{{ old('handphone') }}" maxlength="20">
                            @error('handphone') <div class="text-danger small">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="asal_sekolah" class="form-label fw-bold">Asal Sekolah</label>
                            <input type="text" class="form-control" id="asal_sekolah" name="asal_sekolah"
                                placeholder="Masukkan Asal Sekolah" value="{{ old('asal_sekolah') }}">
                            @error('asal_sekolah') <div class="text-danger small">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label for="id_provinsi" class="form-label fw-bold">ID Provinsi</label>
                            <input type="number" class="form-control" id="id_provinsi" name="id_provinsi"
                                placeholder="Masukkan ID Provinsi" value="{{ old('id_provinsi') }}">
                            @error('id_provinsi') <div class="text-danger small">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label for="id_kabupaten" class="form-label fw-bold">ID Kabupaten/Kota</label>
                            <input type="number" class="form-control" id="id_kabupaten" name="id_kabupaten"
                                placeholder="Masukkan ID Kabupaten/Kota" value="{{ old('id_kabupaten') }}">
                            @error('id_kabupaten') <div class="text-danger small">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label for="id_dapil" class="form-label fw-bold">ID Dapil</label>
                            <input type="number" class="form-control" id="id_dapil" name="id_dapil"
                                placeholder="Masukkan ID Dapil" value="{{ old('id_dapil') }}">
                            @error('id_dapil') <div class="text-danger small">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>

                <input type="submit" value="Simpan" class="btn btn-primary">
                <a href="{{ route('parja.peserta.index') }}" class="btn btn-secondary ms-2">Batal</a>
            </form>
        </div>
    </div>
@endsection
