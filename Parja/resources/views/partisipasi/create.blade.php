@extends('parja::layouts.app')

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <ol class="breadcrumb fs-sm mb-1">
                <li class="breadcrumb-item">Data Administrasi</li>
                <li class="breadcrumb-item"><a href="{{ route('parja.partisipasi.index') }}">Daftar Partisipasi</a></li>
            </ol>
            <h4 class="main-title mb-0">Tambah Partisipasi</h4>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="mb-3">
                <form action="{{ route('parja.partisipasi.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('POST')

                    <div class="mb-3">
                        <label for="partisipasi" class="form-label fw-bold">Partisipasi</label>
                        <div class="d-flex flex-row gap-2">
                            <input required type="text" class="form-control w-50" id="partisipasi" name="partisipasi"
                                placeholder="Masukan Partisipasi" value="">
                            <font style="color: red; display: flex; align-items: center; padding: 0;">*</font>
                        </div>
                    </div>

                    <input type="submit" value="Simpan" class="btn btn-primary">
                </form>
            </div>
        </div>
    </div>
@endsection