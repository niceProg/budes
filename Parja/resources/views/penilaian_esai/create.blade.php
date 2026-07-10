@extends('parja::layouts.app')

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <ol class="breadcrumb fs-sm mb-1">
                <li class="breadcrumb-item">Data Penilaian Esai</li>
                <li class="breadcrumb-item"><a href="{{ route('parja.penilaian_esai_belum.index') }}">Belum Dinilai</a></li>
            </ol>
            <h4 class="main-title mb-0">Tambah Penilaian Esai</h4>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('parja.penilaian_esai.store') }}" method="POST">
                @csrf
                @method('POST')

                <div class="mb-3">
                    @include('components.searchable-select', [
                        'name' => 'peserta_id',
                        'id' => 'peserta_id',
                        'label' => 'Peserta',
                        'labelClass' => 'form-label fw-bold',
                        'containerClass' => 'w-50',
                        'options' => $pesertas->map(fn ($peserta) => ['value' => $peserta->id, 'label' => $peserta->nama])->all(),
                        'value' => old('peserta_id', $selectedPesertaId),
                        'required' => true,
                        'placeholder' => 'Cari dan pilih peserta...',
                        'statusText' => 'Pilih salah satu peserta',
                        'resultsSuffix' => 'peserta ditemukan',
                        'selectionLabel' => 'Peserta terpilih',
                        'errorKey' => 'peserta_id',
                        'errorClass' => 'text-danger small',
                        'requiredMessage' => 'Peserta wajib dipilih.',
                        'invalidSelectionMessage' => 'Pilih peserta dari daftar yang tersedia.',
                    ])
                </div>

                <div class="mb-3">
                    <label for="nilai_esai" class="form-label fw-bold">Nilai Esai</label>
                    <input type="number" step="0.01" min="0" max="100" class="form-control w-25"
                        id="nilai_esai" name="nilai_esai" placeholder="0.00" value="{{ old('nilai_esai') }}">
                    @error('nilai_esai') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label for="catatan_esai" class="form-label fw-bold">Catatan</label>
                    <textarea class="form-control w-50" id="catatan_esai" name="catatan_esai" rows="3"
                        placeholder="Catatan penilaian esai">{{ old('catatan_esai') }}</textarea>
                    @error('catatan_esai') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>

                <input type="submit" value="Simpan" class="btn btn-primary">
                <a href="{{ route('parja.penilaian_esai_belum.index') }}" class="btn btn-secondary ms-2">Batal</a>
            </form>
        </div>
    </div>
@endsection
