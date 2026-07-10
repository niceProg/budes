@extends('parja::layouts.app')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <div>
        <ol class="breadcrumb fs-sm mb-1">
            <li class="breadcrumb-item">Data Peserta</li>
            <li class="breadcrumb-item active" aria-current="page">Laporan Kustom</li>
        </ol>
        <h4 class="main-title mb-0">Laporan Kustom</h4>
    </div>
</div>

<!-- Filter Form -->
<div class="card mb-4">
    <div class="card-header fw-bold">Filter Laporan</div>
    <div class="card-body">
        <form action="{{ route('parja.laporan_kustom.index') }}" method="GET">
            <div class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label for="dapil_id" class="form-label fw-bold">Filter Dapil</label>
                    <select class="form-select" id="dapil_id" name="dapil_id">
                        <option value="">-- Semua Dapil --</option>
                        @foreach ($dapils as $dapil)
                            <option value="{{ $dapil->id_dapil }}" {{ $dapilId == $dapil->id_dapil ? 'selected' : '' }}>
                                {{ $dapil->nama_dapil }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="kegiatan_id" class="form-label fw-bold">Filter Kegiatan</label>
                    <select class="form-select" id="kegiatan_id" name="kegiatan_id">
                        <option value="">-- Semua Kegiatan --</option>
                        @foreach ($kegiatans as $kegiatan)
                            <option value="{{ $kegiatan->id }}" {{ $kegiatanId == $kegiatan->id ? 'selected' : '' }}>
                                {{ $kegiatan->kegiatan }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="ri-search-line"></i> Tampilkan
                    </button>
                    <a href="{{ route('parja.laporan_kustom.index') }}" class="btn btn-secondary ms-2">
                        <i class="ri-refresh-line"></i> Reset
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Result Table -->
@if ($results !== null)
<div class="card">
    <div class="card-header fw-bold">Hasil Laporan ({{ $results->count() }} data)</div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="datatable" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th class="p-1 text-center" style="width: 5%;">No</th>
                        <th class="p-1 text-center">Nama Peserta</th>
                        <th class="p-1 text-center">Dapil</th>
                        <th class="p-1 text-center" style="width: 10%;">Nilai CV</th>
                        <th class="p-1 text-center" style="width: 10%;">Nilai Esai</th>
                        <th class="p-1 text-center" style="width: 10%;">Nilai Video</th>
                        <th class="p-1 text-center" style="width: 10%;">Nilai Total</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($results as $item)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td>{{ $item->nama ?? '-' }}</td>
                            <td>{{ $item->nama_dapil ?? '-' }}</td>
                            <td class="text-center">{{ $item->nilai_cv !== null ? number_format($item->nilai_cv, 2) : '-' }}</td>
                            <td class="text-center">{{ $item->nilai_esai !== null ? number_format($item->nilai_esai, 2) : '-' }}</td>
                            <td class="text-center">{{ $item->nilai_video !== null ? number_format($item->nilai_video, 2) : '-' }}</td>
                            <td class="text-center fw-bold">
                                @if ($item->nilai_total !== null)
                                    <span class="badge {{ $item->nilai_total >= 70 ? 'bg-success' : 'bg-danger' }}">
                                        {{ number_format($item->nilai_total, 2) }}
                                    </span>
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">Tidak ada data ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif
@endsection
