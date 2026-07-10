@extends('parja::layouts.app')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <div>
        <ol class="breadcrumb fs-sm mb-1">
            <li class="breadcrumb-item">Data Peserta</li>
            <li class="breadcrumb-item active" aria-current="page">Daftar Hasil Penilaian</li>
        </ol>
        <h4 class="main-title mb-0">Daftar Hasil Penilaian</h4>
    </div>
</div>

<div class="card">
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
                    @foreach ($hasilPenilaians as $item)
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
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
