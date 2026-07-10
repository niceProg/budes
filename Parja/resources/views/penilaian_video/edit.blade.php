@extends('parja::layouts.app')

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <ol class="breadcrumb fs-sm mb-1">
                <li class="breadcrumb-item">Data Penilaian Video</li>
                <li class="breadcrumb-item"><a href="{{ route('parja.penilaian_video_sudah.index') }}">Sudah Dinilai</a></li>
            </ol>
            <h4 class="main-title mb-0">Edit Penilaian Video</h4>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('parja.penilaian_video.update', $penilaian->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label fw-bold">Peserta</label>
                    <input type="text" class="form-control w-50" value="{{ $penilaian->nama }}" readonly disabled>
                </div>

                <div class="mb-3">
                    <label for="nilai_video" class="form-label fw-bold">Nilai Video</label>
                    <input type="number" step="0.01" min="0" max="100" class="form-control w-25"
                        id="nilai_video" name="nilai_video" placeholder="0.00"
                        value="{{ old('nilai_video', $penilaian->nilai_video) }}">
                    @error('nilai_video') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label for="catatan_video" class="form-label fw-bold">Catatan</label>
                    <textarea class="form-control w-50" id="catatan_video" name="catatan_video" rows="3"
                        placeholder="Catatan penilaian video">{{ old('catatan_video', $penilaian->catatan_video) }}</textarea>
                    @error('catatan_video') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>

                <input type="submit" value="Simpan Perubahan" class="btn btn-primary">
                <a href="{{ route('parja.penilaian_video_sudah.index') }}" class="btn btn-secondary ms-2">Batal</a>
            </form>
        </div>
    </div>
@endsection
