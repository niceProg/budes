@extends('parja::layouts.app')

@section('title', 'Tambah Profil Alumni')
@section('page-title', 'Profil Alumni')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <div>
        <ol class="breadcrumb fs-sm mb-1">
            <li class="breadcrumb-item">Data Akun Alumni</li>
            <li class="breadcrumb-item"><a href="{{ route('parja.alumni-profile.index') }}">Profil Alumni</a></li>
            <li class="breadcrumb-item active" aria-current="page">Tambah Profil</li>
        </ol>
        <h4 class="main-title mb-0">Tambah Profil Alumni</h4>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="row">
        <div class="col-md-6">
        <form action="{{ route('parja.alumni-profile.store') }}" method="POST"
              enctype="multipart/form-data" class="needs-validation" novalidate>
            @csrf

            {{-- Alumni --}}
            <div class="mb-3">
                <label for="alumni_id" class="form-label fw-bold">Alumni <span class="text-danger">*</span></label>
                <select class="form-select @error('alumni_id') is-invalid @enderror"
                        id="alumni_id" name="alumni_id" required>
                    <option value="">-- Pilih Alumni --</option>
                    @foreach ($alumnis as $alumni)
                        <option value="{{ $alumni->id }}" {{ old('alumni_id') == $alumni->id ? 'selected' : '' }}>
                            {{ $alumni->nama }} ({{ $alumni->no_anggota }})
                        </option>
                    @endforeach
                </select>
                <div class="invalid-feedback">{{ $errors->first('alumni_id') ?: 'Alumni wajib dipilih.' }}</div>
            </div>

            {{-- Angkatan --}}
            <div class="mb-3">
                <label for="angkatan" class="form-label fw-bold">Angkatan <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('angkatan') is-invalid @enderror"
                       id="angkatan" name="angkatan" placeholder="Contoh: 2024"
                       value="{{ old('angkatan') }}" maxlength="10"
                       pattern="[0-9]+" inputmode="numeric" required>
                <div class="form-text">Tahun keikutsertaan Parja.</div>
                <div class="invalid-feedback">{{ $errors->first('angkatan') ?: 'Angkatan wajib diisi dan hanya boleh berisi angka.' }}</div>
            </div>

            {{-- Dapil --}}
            <div class="mb-3">
                <label for="id_dapil" class="form-label fw-bold">Dapil <span class="text-danger">*</span></label>
                <select class="form-select @error('id_dapil') is-invalid @enderror" id="id_dapil" name="id_dapil" required>
                    <option value="">-- Pilih Dapil --</option>
                    @forelse($dapils as $dapil)
                        <option value="{{ $dapil->id_dapil }}" {{ old('id_dapil') == $dapil->id_dapil ? 'selected' : '' }}>
                            {{ $dapil->dapil }}
                        </option>
                    @empty
                        <option value="" disabled>Tidak ada data Dapil</option>
                    @endforelse
                </select>
                <div class="invalid-feedback">{{ $errors->first('id_dapil') ?: 'Dapil wajib dipilih.' }}</div>
            </div>

            {{-- Asal Sekolah Parja --}}
            <div class="mb-3">
                <label for="asal_sekolah_parja" class="form-label fw-bold">Asal Sekolah (saat Parja) <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('asal_sekolah_parja') is-invalid @enderror"
                       id="asal_sekolah_parja" name="asal_sekolah_parja"
                       placeholder="Masukkan asal sekolah saat mengikuti Parja"
                       value="{{ old('asal_sekolah_parja') }}" required>
                <div class="invalid-feedback">{{ $errors->first('asal_sekolah_parja') ?: 'Asal sekolah wajib diisi.' }}</div>
            </div>

            {{-- Pendidikan Saat Ini (3 Dropdown) --}}
            <div class="mb-3">
                <label class="form-label fw-bold">Pendidikan Saat Ini <span class="text-danger">*</span></label>
                <div class="row g-2">
                    <div class="col-md-4">
                        <label for="tingkatan_pendidikan" class="form-label small text-muted mb-1">Tingkatan Pendidikan</label>
                        <select id="tingkatan_pendidikan" class="form-select" required>
                            <option value="">-- Pilih Tingkatan --</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="jurusan_kampus" class="form-label small text-muted mb-1">Jurusan Kampus</label>
                        <select id="jurusan_kampus" class="form-select" required disabled>
                            <option value="">-- Pilih Jurusan --</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="kampus_indonesia" class="form-label small text-muted mb-1">Kampus</label>
                        <div id="kampus_select_wrapper">
                            <select id="kampus_indonesia" class="form-select" required disabled>
                                <option value="">-- Pilih Kampus --</option>
                            </select>
                        </div>
                        <input type="text" id="kampus_manual" class="form-control d-none"
                               placeholder="Ketik nama sekolah/kampus" disabled>
                    </div>
                </div>
                <input type="hidden" name="pendidikan_saat_ini" id="pendidikan_saat_ini"
                       class="@error('pendidikan_saat_ini') is-invalid @enderror"
                       value="{{ old('pendidikan_saat_ini') }}" required>
                <div class="invalid-feedback d-block">{{ $errors->first('pendidikan_saat_ini') ?: 'Pendidikan saat ini wajib diisi.' }}</div>
            </div>

            {{-- Pekerjaan Saat Ini --}}
            <div class="mb-3">
                <label for="pekerjaan_saat_ini" class="form-label fw-bold">Pekerjaan Saat Ini <span class="text-muted fw-normal">(Opsional)</span></label>
                <input type="text" class="form-control @error('pekerjaan_saat_ini') is-invalid @enderror"
                       id="pekerjaan_saat_ini" name="pekerjaan_saat_ini"
                       placeholder="Contoh: Software Engineer - PT XYZ"
                       value="{{ old('pekerjaan_saat_ini') }}">
                <div class="invalid-feedback">{{ $errors->first('pekerjaan_saat_ini') ?: 'Pekerjaan saat ini wajib diisi.' }}</div>
            </div>

            {{-- Prestasi Terbaru --}}
            <div class="mb-3">
                <label for="prestasi_terbaru" class="form-label fw-bold">Prestasi Terbaru <span class="text-muted fw-normal">(Opsional)</span></label>
                <textarea class="form-control @error('prestasi_terbaru') is-invalid @enderror"
                          id="prestasi_terbaru" name="prestasi_terbaru"
                          rows="4" placeholder="Tuliskan prestasi terbaru alumni...">{{ old('prestasi_terbaru') }}</textarea>
                <div class="invalid-feedback">{{ $errors->first('prestasi_terbaru') }}</div>
            </div>

            {{-- Foto Profil --}}
            <div class="mb-3">
                <label for="foto_profil" class="form-label fw-bold">Foto Profil</label>
                <input type="file" class="form-control @error('foto_profil') is-invalid @enderror"
                       id="foto_profil" name="foto_profil" accept="image/*">
                <div class="form-text">Format: JPG, JPEG, PNG, WEBP. Maks: 2MB.</div>
                <div class="invalid-feedback">{{ $errors->first('foto_profil') }}</div>
            </div>

            {{-- Akun Media Sosial --}}
            <div class="mb-3">
                <label class="form-label fw-bold">Akun Media Sosial</label>
                <div class="input-group mb-2">
                    <span class="input-group-text"><i class="ri-instagram-line"></i></span>
                    <input type="text" class="form-control" name="medsos_instagram"
                           placeholder="Username Instagram" value="{{ old('medsos_instagram') }}">
                </div>
                <div class="input-group mb-2">
                    <span class="input-group-text"><i class="ri-twitter-x-line"></i></span>
                    <input type="text" class="form-control" name="medsos_twitter"
                           placeholder="Username Twitter / X" value="{{ old('medsos_twitter') }}">
                </div>
                <div class="input-group mb-2">
                    <span class="input-group-text"><i class="ri-linkedin-box-line"></i></span>
                    <input type="text" class="form-control" name="medsos_linkedin"
                           placeholder="Username LinkedIn" value="{{ old('medsos_linkedin') }}">
                </div>
                <div class="input-group mb-2">
                    <span class="input-group-text"><i class="ri-facebook-circle-line"></i></span>
                    <input type="text" class="form-control" name="medsos_facebook"
                           placeholder="Username Facebook" value="{{ old('medsos_facebook') }}">
                </div>
            </div>

            {{-- Ketertarikan Bidang --}}
            <div class="mb-3">
                <label class="form-label fw-bold">Ketertarikan Bidang</label>
                @php
                    $bidangList = ['Kesehatan', 'Pendidikan', 'Teknologi Informasi', 'Hukum', 'Ekonomi',
                                   'Politik', 'Sosial Budaya', 'Lingkungan Hidup', 'Olahraga', 'Seni & Budaya'];
                    $oldBidang = old('ketertarikan_bidang', []);
                @endphp
                <div class="row g-2">
                    @foreach ($bidangList as $bidang)
                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox"
                                       name="ketertarikan_bidang[]"
                                       id="bidang_{{ $loop->index }}"
                                       value="{{ $bidang }}"
                                       {{ in_array($bidang, $oldBidang) ? 'checked' : '' }}>
                                <label class="form-check-label" for="bidang_{{ $loop->index }}">
                                    {{ $bidang }}
                                </label>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Domisili Terakhir — chained dropdown (Provinsi → Kota/Kabupaten)
                 per atasan 18-05-2026 #4: "pakai data inputan peserta (kabupaten dropdown)".
                 Konsisten dengan pola di edit.blade.php. Fallback ke text-input legacy
                 kalau $domisiliGroups tidak di-pass (defensive — route ini saat ini
                 abort(404), tapi view harus tetap konsisten kalau di-enable balik). --}}
            <div class="mb-3">
                <label for="domisili_terakhir" class="form-label fw-bold">Domisili Terakhir <span class="text-danger">*</span></label>
                @php
                    $selectedDomisili = old('domisili_terakhir', '');
                    $selDomParts = $selectedDomisili ? array_map('trim', explode(',', $selectedDomisili, 2)) : [];
                    $selDomCity  = $selDomParts[0] ?? '';
                    $selDomProv  = $selDomParts[1] ?? '';
                    $domisiliGroups = $domisiliGroups ?? [];
                @endphp
                @if (!empty($domisiliGroups))
                    <select id="domisili_provinsi_create" class="form-select mb-2">
                        <option value="">-- Pilih Provinsi --</option>
                        @foreach(array_keys($domisiliGroups) as $prov)
                            <option value="{{ $prov }}" {{ $selDomProv === $prov ? 'selected' : '' }}>{{ $prov }}</option>
                        @endforeach
                    </select>
                    <select id="domisili_kota_create" class="form-select @error('domisili_terakhir') is-invalid @enderror"
                            {{ empty($selDomProv) ? 'disabled' : '' }}>
                        <option value="">-- Pilih Kota/Kabupaten --</option>
                    </select>
                    <input type="hidden" name="domisili_terakhir" id="domisili_terakhir"
                           value="{{ $selectedDomisili }}" required>
                @else
                    {{-- Fallback legacy text input (akan tetap submit text plain) --}}
                    <input type="text" class="form-control @error('domisili_terakhir') is-invalid @enderror"
                           id="domisili_terakhir" name="domisili_terakhir"
                           placeholder="Contoh: Jakarta Selatan, DKI Jakarta"
                           value="{{ $selectedDomisili }}" required>
                @endif
                <div class="invalid-feedback">{{ $errors->first('domisili_terakhir') ?: 'Domisili terakhir wajib diisi.' }}</div>
            </div>

            {{-- Riwayat Pengalaman Organisasi --}}
            <div class="mb-3">
                <label class="form-label fw-bold">Riwayat Pengalaman Organisasi <span class="text-muted fw-normal">(Opsional)</span></label>
                <div id="org-container">
                    @php
                        $oldOrg = old('pengalaman_organisasi', [['organisasi'=>'','periode_mulai_tahun'=>'','periode_selesai_tahun'=>'','jabatan'=>'','ruang_lingkup'=>'']]);
                    @endphp
                    @foreach ($oldOrg as $i => $org)
                    <div class="org-entry border rounded p-3 mb-2">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="fw-semibold text-muted" style="font-size:.85rem;">Pengalaman {{ $i + 1 }}</span>
                            <button type="button" class="btn btn-sm btn-outline-danger btn-hapus-org" {{ $i === 0 ? 'style=display:none' : '' }}>
                                <i class="ri-delete-bin-line"></i> Hapus
                            </button>
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Organisasi</label>
                            <input type="text" class="form-control" name="pengalaman_organisasi[{{ $i }}][organisasi]"
                                   placeholder="Nama organisasi" value="{{ $org['organisasi'] ?? '' }}">
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Periode</label>
                            <div class="d-flex align-items-center gap-2">
                                <input type="text" class="form-control org-year" name="pengalaman_organisasi[{{ $i }}][periode_mulai_tahun]"
                                       placeholder="Tahun mulai" maxlength="4" style="max-width:110px;" value="{{ $org['periode_mulai_tahun'] ?? '' }}">
                                <span class="text-muted">s/d</span>
                                <input type="text" class="form-control org-year" name="pengalaman_organisasi[{{ $i }}][periode_selesai_tahun]"
                                       placeholder="Tahun selesai" maxlength="4" style="max-width:110px;" value="{{ $org['periode_selesai_tahun'] ?? '' }}">
                            </div>
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Jabatan Organisasi</label>
                            <select class="form-select" name="pengalaman_organisasi[{{ $i }}][jabatan]">
                                <option value="">-- Pilih Jabatan --</option>
                                <option value="Ketua" {{ ($org['jabatan'] ?? '') === 'Ketua' ? 'selected' : '' }}>Ketua</option>
                                <option value="Pengurus" {{ ($org['jabatan'] ?? '') === 'Pengurus' ? 'selected' : '' }}>Pengurus</option>
                                <option value="Anggota" {{ ($org['jabatan'] ?? '') === 'Anggota' ? 'selected' : '' }}>Anggota</option>
                            </select>
                        </div>
                        <div class="mb-0">
                            <label class="form-label">Ruang Lingkup</label>
                            <select class="form-select" name="pengalaman_organisasi[{{ $i }}][ruang_lingkup]">
                                <option value="">-- Pilih Ruang Lingkup --</option>
                                <option value="Nasional" {{ ($org['ruang_lingkup'] ?? '') === 'Nasional' ? 'selected' : '' }}>Nasional</option>
                                <option value="Provinsi" {{ ($org['ruang_lingkup'] ?? '') === 'Provinsi' ? 'selected' : '' }}>Provinsi</option>
                                <option value="Kabupaten/Kota" {{ ($org['ruang_lingkup'] ?? '') === 'Kabupaten/Kota' ? 'selected' : '' }}>Kabupaten/Kota</option>
                                <option value="Lainnya" {{ ($org['ruang_lingkup'] ?? '') === 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                            </select>
                        </div>
                    </div>
                    @endforeach
                </div>
                <button type="button" id="btn-tambah-org" class="btn btn-sm btn-outline-primary mt-1">
                    <i class="ri-add-line"></i> Tambah Pengalaman
                </button>
            </div>

            {{-- Status selalu Diterima (1) karena hanya alumni diterima yang dapat membuat profil --}}
            <input type="hidden" name="status" value="1">

            <div class="mt-2">
                <button type="submit" class="btn btn-primary">
                    <i class="ri-save-line"></i> Simpan
                </button>
                <a href="{{ route('parja.alumni-profile.index') }}" class="btn btn-secondary ms-2">
                    <i class="ri-arrow-left-line"></i> Batal
                </a>
            </div>
        </form>
        </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    (function () {
        'use strict';
        document.querySelectorAll('.needs-validation').forEach(function (form) {
            form.addEventListener('submit', function (event) {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    })();

    document.getElementById('angkatan').addEventListener('input', function () {
        this.value = this.value.replace(/[^0-9]/g, '');
    });

    // --- Pendidikan Saat Ini (Cascading Dropdown via internal API) ---
    const educationApi = {
        levels: '{{ route('parja.alumni-profile.education.levels') }}',
        majors: '{{ route('parja.alumni-profile.education.majors') }}',
        campuses: '{{ route('parja.alumni-profile.education.campuses') }}'
    };

    const tingkatanSelect = document.getElementById('tingkatan_pendidikan');
    const jurusanSelect = document.getElementById('jurusan_kampus');
    const kampusSelectWrapper = document.getElementById('kampus_select_wrapper');
    const kampusSelect = document.getElementById('kampus_indonesia');
    const kampusManualInput = document.getElementById('kampus_manual');
    const pendidikanHidden = document.getElementById('pendidikan_saat_ini');

    function isSchoolLevel() {
        return tingkatanSelect.value === 'SMA/SMK Sederajat';
    }

    function setCampusInputMode(useManual) {
        if (useManual) {
            kampusSelectWrapper.classList.add('d-none');
            kampusManualInput.classList.remove('d-none');
            kampusManualInput.disabled = false;
            kampusManualInput.required = true;
            kampusSelect.disabled = true;
            kampusSelect.required = false;
            kampusSelect.value = '';
            return;
        }

        kampusSelectWrapper.classList.remove('d-none');
        kampusManualInput.classList.add('d-none');
        kampusManualInput.disabled = true;
        kampusManualInput.required = false;
        kampusManualInput.value = '';
        kampusSelect.required = true;
    }

    function renderTingkatan(levels) {
        tingkatanSelect.innerHTML = '<option value="">-- Pilih Tingkatan --</option>';
        levels.forEach(function (tingkatan) {
            tingkatanSelect.insertAdjacentHTML('beforeend', '<option value="' + tingkatan + '">' + tingkatan + '</option>');
        });
    }

    function renderJurusan(majors, selectedValue) {
        jurusanSelect.innerHTML = '<option value="">-- Pilih Jurusan --</option>';
        kampusSelect.innerHTML = '<option value="">-- Pilih Kampus --</option>';
        kampusSelect.disabled = true;

        if (!Array.isArray(majors) || majors.length === 0) {
            jurusanSelect.disabled = true;
            return;
        }

        majors.forEach(function (jurusan) {
            jurusanSelect.insertAdjacentHTML(
                'beforeend',
                '<option value="' + jurusan + '" ' + (selectedValue === jurusan ? 'selected' : '') + '>' + jurusan + '</option>'
            );
        });
        jurusanSelect.disabled = false;
    }

    function renderKampus(campuses, selectedValue) {
        kampusSelect.innerHTML = '<option value="">-- Pilih Kampus --</option>';

        if (!Array.isArray(campuses) || campuses.length === 0) {
            kampusSelect.disabled = true;
            return;
        }

        campuses.forEach(function (kampus) {
            kampusSelect.insertAdjacentHTML(
                'beforeend',
                '<option value="' + kampus + '" ' + (selectedValue === kampus ? 'selected' : '') + '>' + kampus + '</option>'
            );
        });
        kampusSelect.disabled = false;
    }

    async function fetchEducation(url, params) {
        const query = new URLSearchParams(params || {});
        const response = await fetch(query.toString() ? (url + '?' + query.toString()) : url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        });

        if (!response.ok) {
            throw new Error('Gagal memuat data pendidikan.');
        }

        const payload = await response.json();
        return Array.isArray(payload.data) ? payload.data : [];
    }

    function syncPendidikanHidden() {
        const kampusValue = isSchoolLevel() ? kampusManualInput.value.trim() : kampusSelect.value;

        if (tingkatanSelect.value && jurusanSelect.value && kampusValue) {
            pendidikanHidden.value = tingkatanSelect.value + ' - ' + jurusanSelect.value + ' - ' + kampusValue;
            return;
        }
        pendidikanHidden.value = '';
    }

    async function hydrateFromSavedValue() {
        const saved = pendidikanHidden.value || '';
        const parts = saved.split(' - ');
        if (parts.length !== 3) {
            return;
        }

        const savedTingkatan = parts[0].trim();
        const savedJurusan = parts[1].trim();
        const savedKampus = parts[2].trim();

        tingkatanSelect.value = savedTingkatan;
        const majors = await fetchEducation(educationApi.majors, { tingkatan: savedTingkatan });
        renderJurusan(majors, savedJurusan);

        if (savedTingkatan === 'SMA/SMK Sederajat') {
            setCampusInputMode(true);
            kampusManualInput.value = savedKampus;
            syncPendidikanHidden();
            return;
        }

        setCampusInputMode(false);
        const campuses = await fetchEducation(educationApi.campuses, { tingkatan: savedTingkatan, jurusan: savedJurusan });
        renderKampus(campuses, savedKampus);
    }

    (async function initPendidikanDropdown() {
        try {
            const levels = await fetchEducation(educationApi.levels);
            renderTingkatan(levels);
            await hydrateFromSavedValue();

            tingkatanSelect.addEventListener('change', async function () {
                renderJurusan([], '');
                renderKampus([], '');
                setCampusInputMode(this.value === 'SMA/SMK Sederajat');
                syncPendidikanHidden();

                if (!this.value) {
                    return;
                }

                const majors = await fetchEducation(educationApi.majors, { tingkatan: this.value });
                renderJurusan(majors, '');
            });

            jurusanSelect.addEventListener('change', async function () {
                renderKampus([], '');
                syncPendidikanHidden();

                if (!tingkatanSelect.value || !this.value) {
                    return;
                }

                if (isSchoolLevel()) {
                    return;
                }

                const campuses = await fetchEducation(educationApi.campuses, {
                    tingkatan: tingkatanSelect.value,
                    jurusan: this.value
                });
                renderKampus(campuses, '');
            });

            kampusSelect.addEventListener('change', syncPendidikanHidden);
            kampusManualInput.addEventListener('input', syncPendidikanHidden);
        } catch (error) {
            tingkatanSelect.disabled = true;
            jurusanSelect.disabled = true;
            kampusSelect.disabled = true;
            kampusManualInput.disabled = true;
            console.error(error);
        }
    })();

    // --- Riwayat Pengalaman Organisasi ---
    const jabatanOptions = `
        <option value="">-- Pilih Jabatan --</option>
        <option value="Ketua">Ketua</option>
        <option value="Pengurus">Pengurus</option>
        <option value="Anggota">Anggota</option>`;

    const ruangOptions = `
        <option value="">-- Pilih Ruang Lingkup --</option>
        <option value="Nasional">Nasional</option>
        <option value="Provinsi">Provinsi</option>
        <option value="Kabupaten/Kota">Kabupaten/Kota</option>
        <option value="Lainnya">Lainnya</option>`;

    function buildOrgEntry(idx) {
        return `<div class="org-entry border rounded p-3 mb-2">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="fw-semibold text-muted" style="font-size:.85rem;">Pengalaman ${idx + 1}</span>
                <button type="button" class="btn btn-sm btn-outline-danger btn-hapus-org">
                    <i class="ri-delete-bin-line"></i> Hapus
                </button>
            </div>
            <div class="mb-2">
                <label class="form-label">Organisasi</label>
                <input type="text" class="form-control" name="pengalaman_organisasi[${idx}][organisasi]" placeholder="Nama organisasi">
            </div>
            <div class="mb-2">
                <label class="form-label">Periode</label>
                <div class="d-flex align-items-center gap-2">
                    <input type="text" class="form-control org-year" name="pengalaman_organisasi[${idx}][periode_mulai_tahun]" placeholder="Tahun mulai" maxlength="4" style="max-width:110px;">
                    <span class="text-muted">s/d</span>
                    <input type="text" class="form-control org-year" name="pengalaman_organisasi[${idx}][periode_selesai_tahun]" placeholder="Tahun selesai" maxlength="4" style="max-width:110px;">
                </div>
            </div>
            <div class="mb-2">
                <label class="form-label">Jabatan Organisasi</label>
                <select class="form-select" name="pengalaman_organisasi[${idx}][jabatan]">${jabatanOptions}</select>
            </div>
            <div class="mb-0">
                <label class="form-label">Ruang Lingkup</label>
                <select class="form-select" name="pengalaman_organisasi[${idx}][ruang_lingkup]">${ruangOptions}</select>
            </div>
        </div>`;
    }

    const orgContainer = document.getElementById('org-container');
    const btnTambah    = document.getElementById('btn-tambah-org');

    function getCount() { return orgContainer.querySelectorAll('.org-entry').length; }
    function reindex() {
        orgContainer.querySelectorAll('.org-entry').forEach(function (el, i) {
            el.querySelectorAll('[name]').forEach(function (field) {
                field.name = field.name.replace(/\[\d+\]/, '[' + i + ']');
            });
            el.querySelector('span').textContent = 'Pengalaman ' + (i + 1);
            el.querySelector('.btn-hapus-org').style.display = i === 0 ? 'none' : '';
        });
    }

    btnTambah.addEventListener('click', function () {
        orgContainer.insertAdjacentHTML('beforeend', buildOrgEntry(getCount()));
        reindex();
        orgContainer.querySelectorAll('.org-year').forEach(function (el) {
            el.addEventListener('input', function () { this.value = this.value.replace(/[^0-9]/g, ''); });
        });
    });

    orgContainer.addEventListener('click', function (e) {
        const btn = e.target.closest('.btn-hapus-org');
        if (!btn) return;
        btn.closest('.org-entry').remove();
        reindex();
    });

    // restrict year inputs on initial entries
    document.querySelectorAll('.org-year').forEach(function (el) {
        el.addEventListener('input', function () { this.value = this.value.replace(/[^0-9]/g, ''); });
    });
</script>
@endpush
