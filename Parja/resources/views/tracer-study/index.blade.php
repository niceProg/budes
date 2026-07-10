@extends('parja::layouts.app')

@section('title', 'Tracer Study Digital')
@section('page-title', 'Tracer Study Digital')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <div>
        <ol class="breadcrumb fs-sm mb-1">
            <li class="breadcrumb-item">Parja Alumni</li>
            <li class="breadcrumb-item active" aria-current="page">Tracer Study Digital</li>
        </ol>
        <h4 class="main-title mb-0">Tracer Study Digital</h4>
    </div>
    <a href="{{ route('parja.tracer.help') }}" class="btn btn-outline-primary">
        <i class="ri-book-open-line me-1"></i> Panduan
    </a>
</div>

<div class="alert alert-info border-0 shadow-sm mb-4">
    Gunakan halaman ini untuk mencari alumni terlebih dahulu, lalu simpan riwayat Pendidikan Lanjutan, Pekerjaan, Organisasi, atau Prestasi melalui kartu di bawah.
</div>

<div class="card mb-4 border-0 shadow-sm">
    <div class="card-body p-4 p-md-5">
        <label for="tracer-search" class="form-label fw-semibold mb-2">Cari Data Alumni</label>
        <form method="GET" action="{{ route('parja.tracer.index') }}">
            <div class="input-group input-group-lg">
                <span class="input-group-text bg-white"><i class="ri-search-line"></i></span>
                <input type="text" id="tracer-search" name="q" class="form-control"
                       value="{{ $q ?? '' }}"
                       placeholder="Cari nama alumni, nomor anggota, angkatan, atau domisili...">
                <button type="submit" class="btn btn-primary px-4">Cari</button>
            </div>
        </form>
        <div class="form-text mt-2">Pencarian cepat ala PDDIKTI untuk menelusuri data tracer alumni.</div>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-6 col-xl-3">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body d-flex flex-column">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <span class="badge bg-primary-subtle text-primary">Tracer</span>
                    <i class="ri-graduation-cap-line fs-4 text-primary"></i>
                </div>
                <h6 class="fw-bold mb-2">Pendidikan Lanjutan</h6>
                <p class="text-muted small mb-4">Catat riwayat studi alumni setelah menyelesaikan Parja.</p>
                @if (rbac_can_create_alumni())
                    <button type="button" class="btn btn-outline-primary mt-auto" data-bs-toggle="modal" data-bs-target="#modalPendidikan">
                        <i class="ri-add-line"></i> Tambah Data
                    </button>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-6 col-xl-3">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body d-flex flex-column">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <span class="badge bg-success-subtle text-success">Tracer</span>
                    <i class="ri-briefcase-4-line fs-4 text-success"></i>
                </div>
                <h6 class="fw-bold mb-2">Pekerjaan</h6>
                <p class="text-muted small mb-4">Simpan data pekerjaan terkini alumni dan jenjang kariernya.</p>
                @if (rbac_can_create_alumni())
                    <button type="button" class="btn btn-outline-success mt-auto" data-bs-toggle="modal" data-bs-target="#modalPekerjaan">
                        <i class="ri-add-line"></i> Tambah Data
                    </button>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-6 col-xl-3">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body d-flex flex-column">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <span class="badge bg-warning-subtle text-warning">Tracer</span>
                    <i class="ri-team-line fs-4 text-warning"></i>
                </div>
                <h6 class="fw-bold mb-2">Organisasi</h6>
                <p class="text-muted small mb-4">Dokumentasikan pengalaman organisasi alumni di berbagai level.</p>
                @if (rbac_can_create_alumni())
                    <button type="button" class="btn btn-outline-warning mt-auto" data-bs-toggle="modal" data-bs-target="#modalOrganisasi">
                        <i class="ri-add-line"></i> Tambah Data
                    </button>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-6 col-xl-3">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body d-flex flex-column">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <span class="badge bg-danger-subtle text-danger">Tracer</span>
                    <i class="ri-award-line fs-4 text-danger"></i>
                </div>
                <h6 class="fw-bold mb-2">Prestasi</h6>
                <p class="text-muted small mb-4">Catat prestasi akademik maupun non-akademik yang diraih alumni.</p>
                @if (rbac_can_create_alumni())
                    <button type="button" class="btn btn-outline-danger mt-auto" data-bs-toggle="modal" data-bs-target="#modalPrestasi">
                        <i class="ri-add-line"></i> Tambah Data
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm mt-4">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h6 class="mb-0">Hasil Pencarian Alumni</h6>
        <small class="text-muted">{{ count($profiles ?? []) }} data</small>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead>
                    <tr>
                        <th class="px-3">Alumni</th>
                        <th>No. Anggota</th>
                        <th>Angkatan</th>
                        <th>Pendidikan</th>
                        <th class="text-center">Ketertarikan Bidang</th>
                        <th>Pekerjaan</th>
                        <th>Domisili</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($profiles as $profile)
                        @php
                            $ketertarikanBidang = $profile->ketertarikan_bidang;

                            if (is_string($ketertarikanBidang)) {
                                $decodedBidang = json_decode($ketertarikanBidang, true);
                                $ketertarikanBidang = json_last_error() === JSON_ERROR_NONE
                                    ? $decodedBidang
                                    : [$ketertarikanBidang];
                            }

                            $ketertarikanBidang = collect($ketertarikanBidang ?? [])->filter()->values();
                        @endphp
                        <tr>
                            <td class="px-3">{{ $profile->alumni->nama ?? '-' }}</td>
                            <td>{{ $profile->alumni->no_anggota ?? '-' }}</td>
                            <td>{{ $profile->angkatan ?? '-' }}</td>
                            <td>{{ $profile->pendidikan_saat_ini ?? '-' }}</td>
                            <td class="text-center">
                                @if ($ketertarikanBidang->isNotEmpty())
                                    {{ $ketertarikanBidang->implode(', ') }}
                                @else
                                    -
                                @endif
                            </td>
                            <td>{{ $profile->pekerjaan_utama ?? '-' }}</td>
                            <td>{{ $profile->domisili_terakhir ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">Belum ada data yang sesuai pencarian.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="modalPendidikan" tabindex="-1" aria-labelledby="modalPendidikanLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalPendidikanLabel">Tambah Data Pendidikan Lanjutan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('parja.tracer.pendidikan.store') }}">
                    @csrf
                    <input type="hidden" name="_modal" value="modalPendidikan">
                    <div class="mb-3">
                        @include('components.searchable-select', [
                            'name' => 'alumni_id',
                            'id' => 'tracer_pendidikan_alumni_id',
                            'label' => 'Pilih Alumni',
                            'options' => $selectedAlumniOption ? [$selectedAlumniOption] : [],
                            'value' => old('alumni_id'),
                            'required' => true,
                            'placeholder' => 'Cari alumni berdasarkan nama, no anggota, atau dapil...',
                            'helperClass' => 'form-text',
                            'helper' => 'Ketik minimal 2 karakter untuk mencari alumni.',
                            'statusText' => 'Cari alumni yang akan diupdate',
                            'resultsSuffix' => 'alumni ditemukan',
                            'selectionLabel' => 'Alumni terpilih',
                            'errorKey' => 'alumni_id',
                            'errorClass' => 'text-danger small',
                            'requiredMessage' => 'Alumni wajib dipilih.',
                            'invalidSelectionMessage' => 'Pilih alumni dari daftar hasil pencarian.',
                            'asyncUrl' => route('parja.tracer.alumni.search'),
                            'minChars' => 2,
                            'minCharsMessage' => 'Ketik minimal 2 karakter untuk mencari alumni.',
                            'loadingText' => 'Mencari alumni...',
                        ])
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Pendidikan Lanjutan</label>
                        <input type="text" class="form-control" name="pendidikan_saat_ini" placeholder="Contoh: S2 Manajemen - Universitas Indonesia" required>
                    </div>
                    <div class="mb-0 text-end">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalPekerjaan" tabindex="-1" aria-labelledby="modalPekerjaanLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalPekerjaanLabel">Tambah Data Pekerjaan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('parja.tracer.pekerjaan.store') }}">
                    @csrf
                    <input type="hidden" name="_modal" value="modalPekerjaan">
                    <div class="mb-3">
                        @include('components.searchable-select', [
                            'name' => 'alumni_id',
                            'id' => 'tracer_pekerjaan_alumni_id',
                            'label' => 'Pilih Alumni',
                            'options' => $selectedAlumniOption ? [$selectedAlumniOption] : [],
                            'value' => old('alumni_id'),
                            'required' => true,
                            'placeholder' => 'Cari alumni berdasarkan nama, no anggota, atau dapil...',
                            'helperClass' => 'form-text',
                            'helper' => 'Ketik minimal 2 karakter untuk mencari alumni.',
                            'statusText' => 'Cari alumni yang akan diupdate',
                            'resultsSuffix' => 'alumni ditemukan',
                            'selectionLabel' => 'Alumni terpilih',
                            'errorKey' => 'alumni_id',
                            'errorClass' => 'text-danger small',
                            'requiredMessage' => 'Alumni wajib dipilih.',
                            'invalidSelectionMessage' => 'Pilih alumni dari daftar hasil pencarian.',
                            'asyncUrl' => route('parja.tracer.alumni.search'),
                            'minChars' => 2,
                            'minCharsMessage' => 'Ketik minimal 2 karakter untuk mencari alumni.',
                            'loadingText' => 'Mencari alumni...',
                        ])
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Perusahaan / Instansi</label>
                        <input type="text" class="form-control" name="pekerjaan_utama" placeholder="Masukkan perusahaan atau instansi" required>
                    </div>
                    <div class="mb-0 text-end">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalOrganisasi" tabindex="-1" aria-labelledby="modalOrganisasiLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalOrganisasiLabel">Tambah Data Organisasi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('parja.tracer.organisasi.store') }}">
                    @csrf
                    <input type="hidden" name="_modal" value="modalOrganisasi">
                    <div class="mb-3">
                        @include('components.searchable-select', [
                            'name' => 'alumni_id',
                            'id' => 'tracer_organisasi_alumni_id',
                            'label' => 'Pilih Alumni',
                            'options' => $selectedAlumniOption ? [$selectedAlumniOption] : [],
                            'value' => old('alumni_id'),
                            'required' => true,
                            'placeholder' => 'Cari alumni berdasarkan nama, no anggota, atau dapil...',
                            'helperClass' => 'form-text',
                            'helper' => 'Ketik minimal 2 karakter untuk mencari alumni.',
                            'statusText' => 'Cari alumni yang akan diupdate',
                            'resultsSuffix' => 'alumni ditemukan',
                            'selectionLabel' => 'Alumni terpilih',
                            'errorKey' => 'alumni_id',
                            'errorClass' => 'text-danger small',
                            'requiredMessage' => 'Alumni wajib dipilih.',
                            'invalidSelectionMessage' => 'Pilih alumni dari daftar hasil pencarian.',
                            'asyncUrl' => route('parja.tracer.alumni.search'),
                            'minChars' => 2,
                            'minCharsMessage' => 'Ketik minimal 2 karakter untuk mencari alumni.',
                            'loadingText' => 'Mencari alumni...',
                        ])
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama Organisasi</label>
                        <input type="text" class="form-control" name="organisasi" placeholder="Masukkan nama organisasi" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Jabatan (Opsional)</label>
                        <input type="text" class="form-control" name="jabatan" placeholder="Contoh: Ketua">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Ruang Lingkup (Opsional)</label>
                        <input type="text" class="form-control" name="ruang_lingkup" placeholder="Contoh: Nasional">
                    </div>
                    <div class="mb-0 text-end">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-warning">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalPrestasi" tabindex="-1" aria-labelledby="modalPrestasiLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalPrestasiLabel">Tambah Data Prestasi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('parja.tracer.prestasi.store') }}">
                    @csrf
                    <input type="hidden" name="_modal" value="modalPrestasi">
                    <div class="mb-3">
                        @include('components.searchable-select', [
                            'name' => 'alumni_id',
                            'id' => 'tracer_prestasi_alumni_id',
                            'label' => 'Pilih Alumni',
                            'options' => $selectedAlumniOption ? [$selectedAlumniOption] : [],
                            'value' => old('alumni_id'),
                            'required' => true,
                            'placeholder' => 'Cari alumni berdasarkan nama, no anggota, atau dapil...',
                            'helperClass' => 'form-text',
                            'helper' => 'Ketik minimal 2 karakter untuk mencari alumni.',
                            'statusText' => 'Cari alumni yang akan diupdate',
                            'resultsSuffix' => 'alumni ditemukan',
                            'selectionLabel' => 'Alumni terpilih',
                            'errorKey' => 'alumni_id',
                            'errorClass' => 'text-danger small',
                            'requiredMessage' => 'Alumni wajib dipilih.',
                            'invalidSelectionMessage' => 'Pilih alumni dari daftar hasil pencarian.',
                            'asyncUrl' => route('parja.tracer.alumni.search'),
                            'minChars' => 2,
                            'minCharsMessage' => 'Ketik minimal 2 karakter untuk mencari alumni.',
                            'loadingText' => 'Mencari alumni...',
                        ])
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Prestasi <span class="text-muted fw-normal">(Opsional)</span></label>
                        <textarea class="form-control" rows="3" name="prestasi_terbaru" placeholder="Masukkan deskripsi prestasi"></textarea>
                    </div>
                    <div class="mb-0 text-end">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    (function () {
        const activeModalId = '{{ old('_modal') }}';
        if (!activeModalId) {
            return;
        }

        const modalEl = document.getElementById(activeModalId);
        if (!modalEl || typeof bootstrap === 'undefined') {
            return;
        }

        const modal = new bootstrap.Modal(modalEl);
        modal.show();
    })();
</script>
@endpush
@endsection
