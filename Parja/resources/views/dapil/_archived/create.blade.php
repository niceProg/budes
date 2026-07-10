@extends('parja::layouts.app')

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <ol class="breadcrumb fs-sm mb-1">
                <li class="breadcrumb-item">Data Peserta</li>
                <li class="breadcrumb-item"><a href="{{ route('parja.dapil.index') }}">Daftar Dapil</a></li>
            </ol>
            <h4 class="main-title mb-0">Tambah Dapil</h4>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('parja.dapil.store') }}" method="POST">
                @csrf
                @method('POST')

                <div class="mb-3">
                    <label for="nama_dapil" class="form-label fw-bold">Nama Dapil</label>
                    <div class="d-flex flex-row gap-2">
                        <input required type="text" class="form-control w-50" id="nama_dapil" name="nama_dapil"
                            placeholder="Masukkan Nama Dapil" value="{{ old('nama_dapil') }}">
                        <font style="color: red; display: flex; align-items: center; padding: 0;">*</font>
                    </div>
                    @error('nama_dapil')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>

                <input type="submit" value="Simpan" class="btn btn-primary">
                <a href="{{ route('parja.dapil.index') }}" class="btn btn-secondary ms-2">Batal</a>
            </form>
        </div>
    </div>
@endsection
