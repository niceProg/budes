@extends('parja::layouts.app')

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <ol class="breadcrumb fs-sm mb-1">
                <li class="breadcrumb-item">Data Peserta</li>
                <li class="breadcrumb-item"><a href="{{ route('parja.penilaian.index') }}">Daftar Penilaian</a></li>
            </ol>
            <h4 class="main-title mb-0">Edit Penilaian</h4>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('parja.penilaian.update', $penilaian->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label fw-bold">Peserta</label>
                    <input type="text" class="form-control w-50" value="{{ $penilaian->nama }}" readonly disabled>
                </div>

                <div class="mb-3">
                    <label for="nilai_total" class="form-label fw-bold">Nilai Total</label>
                    <input type="number" step="0.01" min="0" max="100" class="form-control w-25"
                        id="nilai_total" name="nilai_total" placeholder="0.00"
                        value="{{ old('nilai_total', $penilaian->nilai_total) }}">
                    @error('nilai_total') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label for="keterangan" class="form-label fw-bold">Keterangan</label>
                    <textarea class="form-control w-50" id="keterangan" name="keterangan" rows="3"
                        placeholder="Keterangan tambahan">{{ old('keterangan', $penilaian->keterangan) }}</textarea>
                    @error('keterangan') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>

                <input type="submit" value="Simpan Perubahan" class="btn btn-primary">
                <a href="{{ route('parja.penilaian.index') }}" class="btn btn-secondary ms-2">Batal</a>
            </form>
        </div>
    </div>
@endsection
