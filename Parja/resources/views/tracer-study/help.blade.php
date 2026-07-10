`   @extends('parja::layouts.app')

@section('title', 'Panduan Tracer Study Digital')
@section('page-title', 'Panduan Tracer Study Digital')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <div>
        <ol class="breadcrumb fs-sm mb-1">
            <li class="breadcrumb-item">Parja Alumni</li>
            <li class="breadcrumb-item">Tracer Study Digital</li>
            <li class="breadcrumb-item active" aria-current="page">Panduan</li>
        </ol>
        <h4 class="main-title mb-0">Panduan Tracer Study Digital</h4>
    </div>
    <a href="{{ route('parja.tracer.index') }}" class="btn btn-primary">
        <i class="ri-arrow-left-line me-1"></i> Kembali ke Menu
    </a>
</div>

<div class="row g-3 mb-3">
    <div class="col-12 col-lg-8">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body p-4 p-md-5">
                <div class="badge bg-primary-subtle text-primary mb-3">Ringkasan</div>
                <h5 class="fw-bold mb-3">Apa fungsi menu ini?</h5>
                <p class="text-muted mb-0">
                    Tracer Study Digital dipakai admin Parja untuk menelusuri alumni lalu memperbarui riwayat Pendidikan Lanjutan,
                    Pekerjaan, Organisasi, dan Prestasi. Alur utamanya adalah cari alumni dulu, pilih data yang sesuai,
                    lalu simpan hasilnya.
                </p>
            </div>
        </div>
    </div>
    <div class="col-12 col-lg-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body p-4">
                <div class="badge bg-success-subtle text-success mb-3">Akses Cepat</div>
                <ul class="list-unstyled mb-0 text-muted">
                    <li class="mb-2"><i class="ri-search-line text-success me-2"></i>Cari alumni</li>
                    <li class="mb-2"><i class="ri-graduation-cap-line text-success me-2"></i>Pendidikan</li>
                    <li class="mb-2"><i class="ri-briefcase-4-line text-success me-2"></i>Pekerjaan</li>
                    <li class="mb-2"><i class="ri-team-line text-success me-2"></i>Organisasi</li>
                    <li><i class="ri-award-line text-success me-2"></i>Prestasi</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-12 col-xl-4">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <span class="badge bg-primary-subtle text-primary">Langkah 1</span>
                    <i class="ri-search-eye-line fs-4 text-primary"></i>
                </div>
                <h5 class="fw-bold mb-2">Cari alumni terlebih dahulu</h5>
                <p class="text-muted mb-0">
                    Gunakan kolom pencarian untuk mencari alumni berdasarkan nama, nomor anggota, angkatan,
                    atau domisili. Pilih data yang paling sesuai sebelum mengisi tracer.
                </p>
            </div>
        </div>
    </div>
    <div class="col-12 col-xl-4">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <span class="badge bg-success-subtle text-success">Langkah 2</span>
                    <i class="ri-database-2-line fs-4 text-success"></i>
                </div>
                <h5 class="fw-bold mb-2">Pilih jenis data yang diisi</h5>
                <p class="text-muted mb-0">
                    Pilih kartu Pendidikan Lanjutan, Pekerjaan, Organisasi, atau Prestasi sesuai data yang ingin diperbarui.
                    Setiap kartu membuka form input yang berbeda.
                </p>
            </div>
        </div>
    </div>
    <div class="col-12 col-xl-4">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <span class="badge bg-warning-subtle text-warning">Langkah 3</span>
                    <i class="ri-save-3-line fs-4 text-warning"></i>
                </div>
                <h5 class="fw-bold mb-2">Simpan dan verifikasi</h5>
                <p class="text-muted mb-0">
                    Setelah disimpan, data akan tampil di tabel hasil pencarian. Gunakan tabel tersebut untuk memastikan
                    hasil input sudah benar.
                </p>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-12 col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-0 pt-4 px-4 pb-0">
                <h5 class="mb-0">Panduan per Menu</h5>
            </div>
            <div class="card-body px-4 pb-4">
                <div class="mb-3">
                    <h6 class="fw-bold mb-1">Pendidikan Lanjutan</h6>
                    <p class="text-muted mb-0">Isi ketika alumni melanjutkan studi, misalnya S2 atau S3.</p>
                </div>
                <div class="mb-3">
                    <h6 class="fw-bold mb-1">Pekerjaan</h6>
                    <p class="text-muted mb-0">Isi untuk menyimpan perusahaan, instansi, atau jenjang karier terbaru.</p>
                </div>
                <div class="mb-3">
                    <h6 class="fw-bold mb-1">Organisasi</h6>
                    <p class="text-muted mb-0">Isi pengalaman organisasi alumni. Jabatan dan ruang lingkup bersifat opsional.</p>
                </div>
                <div>
                    <h6 class="fw-bold mb-1">Prestasi</h6>
                    <p class="text-muted mb-0">Gunakan untuk mendokumentasikan prestasi akademik maupun non-akademik.</p>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-0 pt-4 px-4 pb-0">
                <h5 class="mb-0">Catatan Penting</h5>
            </div>
            <div class="card-body px-4 pb-4">
                <ul class="mb-0 text-muted ps-3">
                    <li class="mb-2">Hanya alumni aktif yang bisa dipilih saat input data.</li>
                    <li class="mb-2">Jika profil tracer belum ada, sistem akan membuat profil dasar otomatis saat simpan pertama kali.</li>
                    <li class="mb-2">Gunakan format data yang konsisten agar rekap pencarian tetap rapi.</li>
                    <li class="mb-2">Field pencarian alumni minimal 2 karakter jika memakai pencarian internal pada form modal.</li>
                    <li>Jika data tidak muncul, pastikan alumni sudah memiliki status aktif dan kata kunci pencarian sudah tepat.</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
