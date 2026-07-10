@extends('parja::layouts.app')

@section('title', 'Data Direktori Alumni')
@section('page-title', 'Data Direktori Alumni')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <div>
        <ol class="breadcrumb fs-sm mb-1">
            <li class="breadcrumb-item">Parja Alumni</li>
            <li class="breadcrumb-item active" aria-current="page">Data Direktori Alumni</li>
        </ol>
        <h4 class="main-title mb-0">Data Direktori Alumni</h4>
    </div>
</div>

{{-- ============ KARTU DIREKTORI PUBLIK (consent + disetujui admin) ============
     Hanya alumni yang opt-in (consent) DAN sudah dimoderasi (disetujui) yang
     tampil. Data: foto, pendidikan saat ini, tombol LinkedIn (bisa diklik). --}}
<div class="card mb-4 border-0 shadow-sm">
    <div class="card-body p-4">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h6 class="fw-semibold mb-0"><i class="ri-profile-line text-primary"></i> Direktori Publik Alumni</h6>
            <span class="badge bg-light text-muted">{{ $direktoriCards->count() }} alumni</span>
        </div>

        @if ($direktoriCards->isEmpty())
            <div class="text-center py-4 text-muted">
                <i class="ri-user-search-line fs-3 d-block mb-2"></i>
                Belum ada alumni yang tampil di direktori publik.
            </div>
        @else
            <div class="row g-3">
                @foreach ($direktoriCards as $card)
                    @php
                        $fotoUrl = !empty($card->foto_profil)
                            ? \Illuminate\Support\Facades\Storage::disk('public')->url($card->foto_profil)
                            : null;
                    @endphp
                    <div class="col-6 col-md-4 col-lg-3">
                        <div class="border rounded-3 h-100 p-3 text-center d-flex flex-column align-items-center">
                            @if ($fotoUrl)
                                <img src="{{ $fotoUrl }}" alt="Foto {{ $card->alumni?->nama }}"
                                     class="rounded-circle mb-2" style="width:72px;height:72px;object-fit:cover;">
                            @else
                                <span class="rounded-circle mb-2 d-inline-flex align-items-center justify-content-center bg-light text-muted"
                                      style="width:72px;height:72px;"><i class="ri-user-line fs-3"></i></span>
                            @endif
                            <div class="fw-semibold small">{{ $card->alumni?->nama ?? '-' }}</div>
                            <div class="text-muted small mb-2">{{ $card->pendidikan_saat_ini ?: '-' }}</div>
                            @if ($card->direktori_linkedin_url)
                                <a href="{{ $card->direktori_linkedin_url }}" target="_blank" rel="noopener"
                                   class="btn btn-sm btn-outline-primary mt-auto rounded-pill">
                                    <i class="ri-linkedin-box-fill"></i> LinkedIn
                                </a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

{{-- Filter pencarian — per atasan 18-05-2026 #8:
     Urutan: Nama, Provinsi daftar, Dapil daftar, Tahun, Domisili Terakhir, Jurusan, Profesi, Ketertarikan.
     Tahun = dropdown (extended sampai current+2).
     Domisili Terakhir = dropdown kabupaten dari master.
     Provinsi ↔ Dapil cascade via JS.
     Tombol Reset Filter dihilangkan (atasan: "Button terapkan filter, reset, dan Export csv hilangkan saja").
--}}
<div class="card mb-4 border-0 shadow-sm">
    <div class="card-body p-4 p-md-4">
        <h6 class="fw-semibold mb-3">Filter Pencarian Alumni</h6>
        <form method="GET" action="{{ route('parja.direktori-alumni.index') }}" id="search-form">
            <div class="row g-3">
                {{-- 1. Nama --}}
                <div class="col-md-6">
                    <label for="nama" class="form-label fw-semibold mb-1">Nama</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="ri-user-line"></i></span>
                        <input type="text" id="nama" name="nama" class="form-control"
                               value="{{ $nama ?? '' }}"
                               placeholder="Cari nama alumni...">
                    </div>
                </div>

                {{-- 2. Provinsi daftar — opsi dari xdb_minangwan.provinsi --}}
                <div class="col-md-6">
                    <label for="provinsi-daftar" class="form-label fw-semibold mb-1">Provinsi Daftar</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="ri-map-2-line"></i></span>
                        <select id="provinsi-daftar" name="provinsi_daftar" class="form-select">
                            <option value="">-- Semua Provinsi --</option>
                            @foreach($provinsiOptions as $prov)
                                <option value="{{ $prov->name }}" {{ ($provinsiDaftar ?? '') === $prov->name ? 'selected' : '' }}>{{ $prov->name }} ({{ $prov->count }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- 3. Dapil daftar (cascaded by provinsi) --}}
                <div class="col-md-6">
                    <label for="dapil-daftar" class="form-label fw-semibold mb-1">Dapil Daftar</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="ri-map-pin-line"></i></span>
                        <select id="dapil-daftar" name="dapil_daftar" class="form-select">
                            <option value="">-- Semua Dapil --</option>
                            @foreach($dapilOptions as $dapilOption)
                                <option value="{{ $dapilOption->dapil }}"
                                    data-provinsi="{{ $dapilOption->provinsi ?? '' }}"
                                    {{ ($dapilDaftar ?? '') === $dapilOption->dapil ? 'selected' : '' }}>
                                    {{ $dapilOption->dapil }}@if(!empty($dapilOption->provinsi)) — {{ $dapilOption->provinsi }}@endif ({{ $dapilOption->count }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- 4. Tahun — capped at current year --}}
                <div class="col-md-6">
                    <label for="tahun-alumni" class="form-label fw-semibold mb-1">Tahun Alumni</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="ri-calendar-line"></i></span>
                        <select id="tahun-alumni" name="tahun_alumni" class="form-select">
                            <option value="">-- Semua Tahun --</option>
                            @foreach($tahunOptions as $tahun)
                                <option value="{{ $tahun->year }}" {{ (string)($tahunAlumni ?? '') === (string)$tahun->year ? 'selected' : '' }}>{{ $tahun->year }} ({{ $tahun->count }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- 5. Domisili Terakhir — opsi dari xdb_minangwan.kabupaten JOIN provinsi --}}
                <div class="col-md-6">
                    <label for="domisili-terakhir" class="form-label fw-semibold mb-1">Domisili Terakhir</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="ri-home-4-line"></i></span>
                        <select id="domisili-terakhir" name="domisili_terakhir" class="form-select">
                            <option value="">-- Semua Kota/Kabupaten --</option>
                            @foreach($kabupatenOptions as $kab)
                                <option value="{{ $kab->name }}" {{ ($domisiliTerakhir ?? '') === $kab->name ? 'selected' : '' }}>{{ $kab->label }} ({{ $kab->count }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- 6. Jurusan --}}
                <div class="col-md-6">
                    <label for="jurusan-alumni" class="form-label fw-semibold mb-1">Jurusan</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="ri-book-open-line"></i></span>
                        <select id="jurusan-alumni" name="jurusan_alumni" class="form-select">
                            <option value="">-- Semua Jurusan --</option>
                            @foreach($jurusanOptions as $jurusan)
                                <option value="{{ $jurusan->name }}" {{ ($jurusanAlumni ?? '') === $jurusan->name ? 'selected' : '' }}>{{ $jurusan->name }} ({{ $jurusan->count }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- 7. Profesi (free text) --}}
                <div class="col-md-6">
                    <label for="bidang-profesi" class="form-label fw-semibold mb-1">Bidang Profesi</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="ri-briefcase-line"></i></span>
                        <input type="text" id="bidang-profesi" name="bidang_profesi" class="form-control"
                               value="{{ $bidangProfesi ?? '' }}"
                               placeholder="Contoh: IT Engineer, Lawyer...">
                    </div>
                </div>

                {{-- 8. Ketertarikan Bidang --}}
                <div class="col-md-6">
                    <label for="ketertarikan-bidang" class="form-label fw-semibold mb-1">Ketertarikan Bidang</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="ri-focus-line"></i></span>
                        <select id="ketertarikan-bidang" name="ketertarikan_bidang" class="form-select">
                            <option value="">-- Semua Bidang --</option>
                            @foreach($ketertarikanOptions as $bidang)
                                <option value="{{ $bidang->name }}" {{ ($ketertarikanBidang ?? '') === $bidang->name ? 'selected' : '' }}>{{ $bidang->name }} ({{ $bidang->count }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            {{-- Tombol Cari — Reset & Export CSV dihilangkan per atasan 18-05 #8 --}}
            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary px-4">
                    <i class="ri-search-line"></i> Cari Alumni
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Hasil pencarian --}}
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h6 class="mb-0">Hasil Pencarian Alumni</h6>
        <small class="text-muted">{{ $alumnis->total() }} data</small>
    </div>
    <div class="card-body p-0">
        @if($alumnis->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead>
                        <tr>
                            <th class="px-3">No.</th>
                            <th class="px-3">Nama Alumni</th>
                            <th>No. Anggota</th>
                            <th>Tahun</th>
                            <th>Dapil/Provinsi</th>
                            <th>Jurusan</th>
                            <th>Pekerjaan</th>
                            <th>Ketertarikan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($alumnis as $alumni)
                            @php
                                $profile = $alumni->profile;
                                $ketertarikanBidangArr = $profile?->ketertarikan_bidang;

                                if (is_string($ketertarikanBidangArr)) {
                                    $decodedBidang = json_decode($ketertarikanBidangArr, true);
                                    $ketertarikanBidangArr = json_last_error() === JSON_ERROR_NONE
                                        ? $decodedBidang
                                        : [$ketertarikanBidangArr];
                                }

                                $ketertarikanBidangArr = collect($ketertarikanBidangArr ?? [])->filter()->values();
                            @endphp
                            <tr>
                                <td class="px-3">
                                    {{ ($alumnis->currentPage() - 1) * $alumnis->perPage() + $loop->iteration }}
                                </td>
                                <td class="px-3">
                                    <strong>{{ $alumni->nama ?? '-' }}</strong>
                                </td>
                                <td>{{ $alumni->no_anggota ?? '-' }}</td>
                                <td>{{ $alumni->direktori_tahun ?? '-' }}</td>
                                <td>{{ $alumni->direktori_dapil ?? '-' }}</td>
                                <td>{{ $alumni->direktori_jurusan ?? '-' }}</td>
                                <td>{{ $profile?->pekerjaan_utama ?? '-' }}</td>
                                <td>
                                    @if($ketertarikanBidangArr->isNotEmpty())
                                        <small>
                                            @foreach($ketertarikanBidangArr as $bidang)
                                                <span class="badge bg-info-subtle text-info">{{ $bidang }}</span>
                                            @endforeach
                                        </small>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="card-footer bg-white d-flex justify-content-center">
                {{ $alumnis->render() }}
            </div>
        @else
            <div class="text-center py-4 text-muted">
                <i class="ri-search-line fs-3 mb-2 d-block"></i>
                <p>Tidak ada data alumni yang sesuai dengan filter pencarian Anda.</p>
                <a href="{{ route('parja.direktori-alumni.index') }}" class="btn btn-sm btn-outline-primary">
                    Bersihkan Filter
                </a>
            </div>
        @endif
    </div>
</div>

@endsection

@push('scripts')
<script>
    // Cascading Provinsi → Dapil filter (per atasan 18-05 #8).
    // Saat Provinsi dipilih, hide opsi Dapil yang bukan dari provinsi tsb.
    (function () {
        const provSel  = document.getElementById('provinsi-daftar');
        const dapilSel = document.getElementById('dapil-daftar');
        if (!provSel || !dapilSel) return;

        function applyDapilFilter() {
            const currentProv = (provSel.value || '').trim();
            const currentDapilValue = (dapilSel.value || '').trim();

            let dapilStillValid = false;
            for (const opt of dapilSel.options) {
                if (opt.value === '') { opt.hidden = false; continue; }
                const optProv = (opt.getAttribute('data-provinsi') || '').trim();
                const visible = currentProv === '' || optProv === currentProv;
                opt.hidden = !visible;
                if (visible && opt.value === currentDapilValue) {
                    dapilStillValid = true;
                }
            }

            // Kalau dapil yang sudah ke-select bukan di provinsi yang aktif, reset.
            if (currentDapilValue !== '' && !dapilStillValid) {
                dapilSel.value = '';
            }
        }

        provSel.addEventListener('change', applyDapilFilter);
        applyDapilFilter();
    })();
</script>
@endpush
