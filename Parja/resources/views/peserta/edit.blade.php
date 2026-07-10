@extends('parja::layouts.app')

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <ol class="breadcrumb fs-sm mb-1">
                <li class="breadcrumb-item">Data Peserta</li>
                <li class="breadcrumb-item"><a href="{{ route('parja.peserta.index') }}">Daftar Peserta</a></li>
            </ol>
            <h4 class="main-title mb-0">Edit Peserta</h4>
        </div>
    </div>

    @if (rbac_can_create_parja())
        <div class="card mb-3">
            <div class="card-body">
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                    <div>
                        <h6 class="mb-1 fw-semibold">Status &amp; Akun Login Peserta</h6>
                        <div class="d-flex align-items-center gap-2 flex-wrap">
                            @if (!empty($isAlumni))
                                <span class="badge bg-info-subtle text-info"><i class="ri-graduation-cap-line"></i> Sudah jadi Alumni</span>
                                <small class="text-secondary">Peserta ini sudah diluluskan & berstatus alumni.</small>
                            @elseif ($peserta->tahap() === 'aktif')
                                <span class="badge bg-success-subtle text-success"><i class="ri-shield-check-line"></i> Akun Aktif</span>
                                <small class="text-secondary">Akun login sudah dibuat — peserta bisa masuk ke portal peserta.</small>
                            @elseif ($peserta->tahap() === 'terpilih')
                                <span class="badge bg-warning-subtle text-warning"><i class="ri-star-line"></i> Terpilih</span>
                                <small class="text-secondary">Sudah lolos seleksi — siapkan akun login agar peserta bisa masuk.</small>
                            @else
                                <span class="badge bg-secondary-subtle text-secondary"><i class="ri-user-line"></i> Pendaftar</span>
                                <small class="text-secondary">Baru mendaftar. Belum perlu akun login sampai terpilih.</small>
                            @endif
                            @if ((int) ($peserta->status_lolos ?? 0) === 2)
                                <span class="badge bg-primary-subtle text-primary"><i class="ri-medal-line"></i> Lolos Seleksi</span>
                            @endif
                        </div>
                    </div>
                    <div class="d-flex flex-wrap gap-2 justify-content-end">
                        @if (empty($isAlumni))
                            @if ((int) ($peserta->status_lolos ?? 0) < 1)
                                <form action="{{ route('parja.peserta.tetapkan-lolos', $peserta->id) }}" method="POST" class="mb-0"
                                    onsubmit="return confirm('Tetapkan peserta ini LOLOS? Akun login peserta akan disiapkan otomatis & peserta dapat email aktivasi.');">
                                    @csrf
                                    <input type="hidden" name="tingkat" value="1">
                                    <button type="submit" class="btn btn-primary btn-sm">
                                        <i class="ri-checkbox-circle-line"></i> Tetapkan Lolos
                                    </button>
                                </form>
                                <form action="{{ route('parja.peserta.tetapkan-lolos', $peserta->id) }}" method="POST" class="mb-0"
                                    onsubmit="return confirm('Tetapkan peserta ini LOLOS SELEKSI (final)? Akun login peserta akan disiapkan otomatis.');">
                                    @csrf
                                    <input type="hidden" name="tingkat" value="2">
                                    <button type="submit" class="btn btn-outline-primary btn-sm">
                                        <i class="ri-medal-line"></i> Lolos Seleksi (final)
                                    </button>
                                </form>
                            @else
                                @if ((int) ($peserta->status_lolos ?? 0) === 1)
                                    <form action="{{ route('parja.peserta.tetapkan-lolos', $peserta->id) }}" method="POST" class="mb-0"
                                        onsubmit="return confirm('Naikkan peserta ke LOLOS SELEKSI (final)?');">
                                        @csrf
                                        <input type="hidden" name="tingkat" value="2">
                                        <button type="submit" class="btn btn-outline-primary btn-sm">
                                            <i class="ri-medal-line"></i> Naikkan ke Lolos Seleksi
                                        </button>
                                    </form>
                                @endif
                                @if (empty($peserta->keycloak_id))
                                    <form action="{{ route('parja.peserta.provision', $peserta->id) }}" method="POST" class="mb-0"
                                        onsubmit="return confirm('Siapkan akun login untuk peserta ini? Sistem membuat akun & mengirim email aktivasi ke peserta.');">
                                        @csrf
                                        <button type="submit" class="btn btn-primary btn-sm">
                                            <i class="ri-user-add-line"></i> Siapkan Akun Peserta
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route('parja.peserta.luluskan', $peserta->id) }}" method="POST" class="mb-0"
                                        onsubmit="return confirm('Luluskan peserta ini menjadi alumni? Akun & datanya dipindahkan ke status alumni.');">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-success btn-sm">
                                            <i class="ri-graduation-cap-line"></i> Luluskan jadi Alumni
                                        </button>
                                    </form>
                                @endif
                                <form action="{{ route('parja.peserta.diskualifikasi', $peserta->id) }}" method="POST" class="mb-0"
                                    onsubmit="return confirm('Diskualifikasi peserta ini? Status lolos direset & akun login Keycloak dinonaktifkan (tidak bisa masuk portal). Bisa dipulihkan lewat Tetapkan Lolos.');">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-danger btn-sm">
                                        <i class="ri-forbid-line"></i> Diskualifikasi
                                    </button>
                                </form>
                            @endif
                        @endif
                    </div>
                </div>

                <hr class="my-2">
                <small class="text-muted">
                    <i class="ri-information-line"></i>
                    Alur: <b>Pendaftar</b> → (seleksi) <b>Terpilih</b> → <b>Siapkan Akun</b> (peserta dapat email aktivasi & set password) → login portal → <b>Luluskan jadi Alumni</b>.
                </small>
            </div>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <form action="{{ route('parja.peserta.update', $peserta->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="nama" class="form-label fw-bold">Nama Lengkap <span class="text-danger">*</span></label>
                            <input required type="text" class="form-control" id="nama" name="nama"
                                placeholder="Masukkan Nama Lengkap" value="{{ old('nama', $peserta->nama) }}">
                            @error('nama') <div class="text-danger small">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label for="nisn" class="form-label fw-bold">NISN</label>
                            <input type="text" class="form-control" id="nisn" name="nisn"
                                placeholder="Masukkan NISN" value="{{ old('nisn', $peserta->nisn) }}" maxlength="20">
                        </div>
                        <div class="mb-3">
                            <label for="jenis_kelamin" class="form-label fw-bold">Jenis Kelamin</label>
                            <select class="form-select" id="jenis_kelamin" name="jenis_kelamin">
                                <option value="">-- Pilih --</option>
                                <option value="L" {{ old('jenis_kelamin', $peserta->jenis_kelamin) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ old('jenis_kelamin', $peserta->jenis_kelamin) == 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label fw-bold">Email</label>
                            <input type="email" class="form-control" id="email" name="email"
                                placeholder="Masukkan Email" value="{{ old('email', $peserta->email) }}">
                        </div>
                        <div class="mb-3">
                            <label for="handphone" class="form-label fw-bold">No HP</label>
                            <input type="text" class="form-control" id="handphone" name="handphone"
                                placeholder="Masukkan No HP" value="{{ old('handphone', $peserta->handphone) }}" maxlength="20">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="asal_sekolah" class="form-label fw-bold">Asal Sekolah</label>
                            <input type="text" class="form-control" id="asal_sekolah" name="asal_sekolah"
                                placeholder="Masukkan Asal Sekolah" value="{{ old('asal_sekolah', $peserta->asal_sekolah) }}">
                        </div>
                        <div class="mb-3">
                            <label for="nama_provinsi" class="form-label fw-bold">Provinsi</label>
                            <input type="text" class="form-control" id="nama_provinsi" name="nama_provinsi"
                                placeholder="Masukkan Provinsi" value="{{ old('nama_provinsi', $peserta->nama_provinsi) }}">
                        </div>
                        <div class="mb-3">
                            <label for="nama_kabupaten" class="form-label fw-bold">Kabupaten/Kota</label>
                            <input type="text" class="form-control" id="nama_kabupaten" name="nama_kabupaten"
                                placeholder="Masukkan Kabupaten/Kota" value="{{ old('nama_kabupaten', $peserta->nama_kabupaten) }}">
                        </div>
                        <div class="mb-3">
                            <label for="nama_dapil" class="form-label fw-bold">Dapil</label>
                            <input type="text" class="form-control" id="nama_dapil" name="nama_dapil"
                                placeholder="Masukkan Dapil" value="{{ old('nama_dapil', $peserta->nama_dapil) }}">
                        </div>
                        <div class="mb-3">
                            <label for="alamat_rumah" class="form-label fw-bold">Alamat Rumah</label>
                            <textarea class="form-control" id="alamat_rumah" name="alamat_rumah" rows="3"
                                placeholder="Masukkan Alamat">{{ old('alamat_rumah', $peserta->alamat_rumah) }}</textarea>
                        </div>
                    </div>
                </div>

                <input type="submit" value="Simpan Perubahan" class="btn btn-primary">
                <a href="{{ route('parja.peserta.index') }}" class="btn btn-secondary ms-2">Batal</a>
            </form>
        </div>
    </div>
@endsection
