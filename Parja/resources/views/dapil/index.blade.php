@extends('parja::layouts.app')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <div>
        <ol class="breadcrumb fs-sm mb-1">
            <li class="breadcrumb-item">Data Peserta</li>
            <li class="breadcrumb-item active" aria-current="page">Daftar Dapil</li>
        </ol>
        <h4 class="main-title mb-0">Daftar Dapil</h4>
    </div>
</div>

<div class="alert alert-info" role="alert">
    Data dapil bersifat referensi (read-only). Untuk menambah atau mengubah data dapil,
    lakukan langsung melalui seeder atau migrasi database. Gunakan tombol
    <strong>Sinkronisasi</strong> di bawah untuk memperbarui kolom <code>id_dapil</code>
    dan <code>id_provinsi</code> pada semua data alumni berdasarkan nama dapil yang tersimpan.
</div>

@if (rbac_can_edit_parja())
<div class="card mb-3">
    <div class="card-body">
        <h6 class="mb-2">Sinkronisasi Data Alumni dari Tabel Dapil</h6>
        <p class="text-muted mb-3">
            Proses ini akan mencocokkan nama dapil di tabel <strong>alumni</strong> dengan data
            di tabel <strong>dapil</strong>, lalu mengisi otomatis kolom
            <code>id_dapil</code> dan <code>id_provinsi</code> pada setiap record alumni.
        </p>
        <form action="{{ route('parja.dapil.sync') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-primary"
                onclick="return confirm('Jalankan sinkronisasi id_dapil & id_provinsi ke semua data alumni?')">
                <i class="fa fa-sync"></i> Sinkronisasi Data Alumni
            </button>
        </form>
    </div>
</div>
@endif

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table id="datatable" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th class="p-1 text-center" style="width: 5%;">No</th>
                        <th class="p-1 text-center">Nama Dapil</th>
                        <th class="p-1 text-center">Kabupaten</th>
                        <th class="p-1 text-center">Provinsi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($dapils as $dapil)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td>{{ $dapil->dapil ?? '-' }}</td>
                            <td>{{ $dapil->kabupaten ?? '-' }}</td>
                            <td>{{ $dapil->provinsi ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
