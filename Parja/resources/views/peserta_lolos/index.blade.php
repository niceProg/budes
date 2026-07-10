@extends('parja::layouts.app')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <div>
        <ol class="breadcrumb fs-sm mb-1">
            <li class="breadcrumb-item">Data Peserta</li>
            <li class="breadcrumb-item active" aria-current="page">Daftar Peserta (Lolos)</li>
        </ol>
        <h4 class="main-title mb-0">Daftar Peserta Lolos</h4>
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
                        <th class="p-1 text-center">Email</th>
                        <th class="p-1 text-center">No HP</th>
                        <th class="p-1 text-center">Dapil</th>
                        <th class="p-1 text-center">Didaftarkan Oleh</th>
                        <th class="p-1 text-center">Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pesertas as $peserta)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td>{{ $peserta->nama }}</td>
                            <td>{{ $peserta->email ?? '-' }}</td>
                            <td>{{ $peserta->no_hp ?? '-' }}</td>
                            <td>{{ optional($peserta->dapil)->nama_dapil ?? '-' }}</td>
                            <td>{{ $peserta->user_input ?? '-' }}</td>
                            <td>{{ $peserta->tanggal_input ? \Carbon\Carbon::parse($peserta->tanggal_input)->format('d/m/Y') : '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
