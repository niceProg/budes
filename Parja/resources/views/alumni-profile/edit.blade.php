@extends('parja::layouts.app')

@section('title', 'Edit Profil Alumni')
@section('page-title', 'Profil Alumni')

@push('styles')
<link rel="stylesheet" href="{{ asset('template/dist/lib/select2/css/select2.min.css') }}">
<style>
    /* ── Layout ── */
    .profile-edit-page { max-width: 1020px; }
    .profile-section-card {
        background: #fff;
        border: 1px solid #e9ecef;
        border-radius: 12px;
        padding: 24px 24px 18px;
        margin-bottom: 20px;
    }
    .profile-section-title {
        font-size: 0.82rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .06em;
        color: #6c757d;
        margin-bottom: 18px;
        padding-bottom: 10px;
        border-bottom: 1px solid #f0f0f5;
        display: flex;
        align-items: center;
        gap: 7px;
    }
    .profile-section-title i { font-size: 1rem; color: #0d6efd; }

    /* ── Form controls ── */
    .form-label.fw-bold { font-size: 0.875rem; margin-bottom: 5px; }
    .form-control, .form-select {
        font-size: 0.875rem;
        border-radius: 8px;
    }
    .form-control:focus, .form-select:focus {
        border-color: #86b7fe;
        box-shadow: 0 0 0 0.2rem rgba(13,110,253,.15);
    }
    .input-group-text { border-radius: 8px 0 0 8px !important; background: #f8f9fa; color: #6c757d; }
    .input-group .form-control { border-radius: 0 8px 8px 0 !important; }

    /* ── Select2 ── */
    .select2-container--default .select2-selection--single {
        height: 38px;
        border: 1px solid #ced4da;
        border-radius: 8px;
        display: flex;
        align-items: center;
        padding: 0 10px;
        transition: border-color .15s, box-shadow .15s;
    }
    .select2-container--default.select2-container--focus .select2-selection--single,
    .select2-container--default.select2-container--open .select2-selection--single {
        border-color: #86b7fe;
        box-shadow: 0 0 0 0.2rem rgba(13,110,253,.15);
        outline: none;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: normal;
        color: #212529;
        padding-left: 2px;
        font-size: 0.875rem;
    }
    .select2-container--default .select2-selection--single .select2-selection__placeholder {
        color: #adb5bd;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 36px;
        right: 8px;
    }
    .select2-dropdown {
        border-color: #dee2e6;
        border-radius: 10px;
        box-shadow: 0 6px 20px rgba(0,0,0,.1);
        overflow: hidden;
    }
    .select2-container--default .select2-search--dropdown {
        padding: 8px 10px;
        border-bottom: 1px solid #f0f0f5;
    }
    .select2-container--default .select2-search--dropdown .select2-search__field {
        border-radius: 6px;
        border: 1px solid #ced4da;
        padding: 5px 10px;
        font-size: 0.85rem;
        width: 100%;
    }
    .select2-container--default .select2-search--dropdown .select2-search__field:focus { outline: none; border-color: #86b7fe; }
    .select2-results__option { font-size: 0.875rem; padding: 7px 12px; }
    .select2-container--default .select2-results__option--highlighted[aria-selected] { background-color: #0d6efd; }
    .select2-container--default .select2-results__option[aria-selected=true] { background-color: #e8f0fe; color: #0d47a1; }

    /* ── Foto preview ── */
    .foto-preview-wrap { position: relative; display: inline-block; }
    .foto-preview-wrap img { border: 2px solid #e9ecef; transition: border-color .2s; }
    .foto-preview-wrap:hover img { border-color: #86b7fe; }
    .foto-preview-label {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        cursor: pointer;
        padding: 7px 16px;
        border: 1.5px dashed #ced4da;
        border-radius: 8px;
        font-size: 0.82rem;
        color: #6c757d;
        margin-top: 8px;
        transition: border-color .2s, color .2s;
    }
    .foto-preview-label:hover { border-color: #0d6efd; color: #0d6efd; }

    /* ── Ketertarikan bidang chips ── */
    .bidang-chips { display: flex; flex-wrap: wrap; gap: 8px; }
    .bidang-chip { display: none; }
    .bidang-chip + label {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 5px 13px;
        border: 1.5px solid #dee2e6;
        border-radius: 50px;
        font-size: 0.8rem;
        font-weight: 500;
        color: #495057;
        cursor: pointer;
        transition: all .15s;
        user-select: none;
    }
    .bidang-chip + label:hover { border-color: #0d6efd; color: #0d6efd; background: rgba(13,110,253,.04); }
    .bidang-chip:checked + label { background: #0d6efd; border-color: #0d6efd; color: #fff; }

    /* ── Org entry ── */
    .org-entry {
        border: 1px solid #e9ecef !important;
        border-radius: 10px !important;
        background: #fafafa;
    }
    .org-entry .org-num {
        font-size: 0.75rem;
        font-weight: 700;
        color: #6c757d;
        text-transform: uppercase;
        letter-spacing: .04em;
    }

    /* ── Submit bar ── */
    .submit-bar {
        background: #fff;
        border: 1px solid #e9ecef;
        border-radius: 12px;
        padding: 16px 24px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .btn-save-profile { border-radius: 8px; padding: 8px 24px; font-weight: 600; }
    .btn-cancel-profile { border-radius: 8px; padding: 8px 20px; font-weight: 500; }

    /* ── Disabled select styled ── */
    .select2-container--default .select2-selection--single[aria-disabled=true],
    select:disabled + .select2-container .select2-selection--single {
        background: #e9ecef;
    }
</style>
@endpush

@section('content')
<div class="profile-edit-page">
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <div>
        <ol class="breadcrumb fs-sm mb-1">
            <li class="breadcrumb-item">Data Akun Alumni</li>
            <li class="breadcrumb-item"><a href="{{ route('parja.alumni-profile.index') }}">Profil Alumni</a></li>
            <li class="breadcrumb-item active" aria-current="page">Edit Profil</li>
        </ol>
        <h4 class="main-title mb-0">Edit Profil Alumni</h4>
    </div>
</div>

<form action="{{ route('parja.alumni-profile.update', $profile->id) }}" method="POST"
      enctype="multipart/form-data" class="needs-validation" novalidate>
    @csrf
    @method('PUT')

    {{-- ── Section 1: Data Dasar ── --}}
    <div class="profile-section-card">
        <div class="profile-section-title">
            <i class="ri-user-line"></i> Data Dasar Alumni
        </div>
        <div class="row g-3">
            <div class="col-md-6">
                <label for="alumni_id" class="form-label fw-bold">Alumni <span class="text-danger">*</span></label>
                <select class="form-select @error('alumni_id') is-invalid @enderror"
                        id="alumni_id" name="alumni_id" required>
                    <option value="">-- Pilih Alumni --</option>
                    @foreach ($alumnis as $alumni)
                        <option value="{{ $alumni->id }}"
                            {{ old('alumni_id', $profile->alumni_id) == $alumni->id ? 'selected' : '' }}>
                            {{ $alumni->nama }} ({{ $alumni->no_anggota }})
                        </option>
                    @endforeach
                </select>
                <div class="invalid-feedback">{{ $errors->first('alumni_id') ?: 'Alumni wajib dipilih.' }}</div>
            </div>
            <div class="col-md-3">
                <label for="angkatan" class="form-label fw-bold">Angkatan <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('angkatan') is-invalid @enderror"
                       id="angkatan" name="angkatan" placeholder="Contoh: 2024"
                       value="{{ old('angkatan', $profile->angkatan) }}" maxlength="10"
                       pattern="[0-9]+" inputmode="numeric" required>
                <div class="form-text text-muted" style="font-size:.78rem;">Tahun keikutsertaan Parja.</div>
                <div class="invalid-feedback">{{ $errors->first('angkatan') ?: 'Angkatan wajib diisi.' }}</div>
            </div>
            <div class="col-md-3">
                <label for="id_dapil" class="form-label fw-bold">Dapil <span class="text-danger">*</span></label>
                @php
                    $currentDapilId = null;
                    $currentDapilName = $profile->alumni->dapil ?? $profile->dapil;
                    if ($currentDapilName) {
                        $currentDapilObj = $dapils->where('dapil', $currentDapilName)->first();
                        $currentDapilId = $currentDapilObj ? $currentDapilObj->id_dapil : null;
                    }
                @endphp
                <select class="form-select @error('id_dapil') is-invalid @enderror" id="id_dapil" name="id_dapil" required>
                    <option value="">-- Pilih Dapil --</option>
                    @forelse($dapils as $dapil)
                        <option value="{{ $dapil->id_dapil }}"
                            {{ old('id_dapil', $currentDapilId) == $dapil->id_dapil ? 'selected' : '' }}>
                            {{ $dapil->dapil }}
                        </option>
                    @empty
                        <option value="" disabled>Tidak ada data Dapil</option>
                    @endforelse
                </select>
                <div class="invalid-feedback">{{ $errors->first('id_dapil') ?: 'Dapil wajib dipilih.' }}</div>
            </div>
            <div class="col-md-6">
                <label for="asal_sekolah_parja" class="form-label fw-bold">Asal Sekolah (saat Parja) <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('asal_sekolah_parja') is-invalid @enderror"
                       id="asal_sekolah_parja" name="asal_sekolah_parja"
                       placeholder="Masukkan asal sekolah saat mengikuti Parja"
                       value="{{ old('asal_sekolah_parja', $profile->asal_sekolah_parja) }}" required>
                <div class="invalid-feedback">{{ $errors->first('asal_sekolah_parja') ?: 'Asal sekolah wajib diisi.' }}</div>
            </div>
            <div class="col-md-6">
                <label for="domisili_terakhir" class="form-label fw-bold">Domisili Terakhir <span class="text-danger">*</span></label>
                @php
                    $selectedDomisili = old('domisili_terakhir', $profile->domisili_terakhir);
                    // Parse "Kota, Provinsi" into components for chained selects
                    $selDomParts   = $selectedDomisili ? array_map('trim', explode(',', $selectedDomisili, 2)) : [];
                    $selDomCity    = $selDomParts[0] ?? '';
                    $selDomProv    = $selDomParts[1] ?? '';
                    $domisiliGroups = $domisiliGroups ?? [];
                    // If saved value isn't canonical, show it as a legacy option
                    $isLegacy = !empty($selectedDomisili) && !isset($domisiliGroups[$selDomProv]);
                @endphp
                {{-- Province selector --}}
                <select id="domisili_provinsi_edit" class="form-select mb-2">
                    <option value="">-- Pilih Provinsi --</option>
                    @foreach(array_keys($domisiliGroups) as $prov)
                        <option value="{{ $prov }}" {{ $selDomProv === $prov ? 'selected' : '' }}>{{ $prov }}</option>
                    @endforeach
                </select>
                {{-- City / Regency selector --}}
                <select id="domisili_kota_edit" class="form-select @error('domisili_terakhir') is-invalid @enderror"
                        {{ empty($selDomProv) && !$isLegacy ? 'disabled' : '' }}>
                    <option value="">-- Pilih Kota/Kabupaten --</option>
                    @if($isLegacy && !empty($selectedDomisili))
                        <option value="{{ $selectedDomisili }}" selected>{{ $selectedDomisili }} (data lama)</option>
                    @endif
                </select>
                {{-- Actual submitted value --}}
                <input type="hidden" name="domisili_terakhir" id="domisili_terakhir"
                       value="{{ $selectedDomisili }}">
                <div class="invalid-feedback">{{ $errors->first('domisili_terakhir') ?: 'Domisili terakhir wajib diisi.' }}</div>
                @error('domisili_terakhir')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>

    {{-- ── Section 2: Pendidikan & Karier ── --}}
    <div class="profile-section-card">
        <div class="profile-section-title">
            <i class="ri-graduation-cap-line"></i> Pendidikan & Karier
        </div>
        <div class="row g-3">
            {{-- Pendidikan Saat Ini --}}
            <div class="col-12">
                <label class="form-label fw-bold">Pendidikan Saat Ini <span class="text-danger">*</span></label>
                <div class="row g-2">
                    <div class="col-md-4">
                        <label for="tingkatan_pendidikan" class="form-label small text-muted mb-1">Tingkatan Pendidikan</label>
                        <select id="tingkatan_pendidikan" class="form-select" required>
                            <option value="">-- Pilih Tingkatan --</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="jurusan_kampus" class="form-label small text-muted mb-1">Jurusan / Bidang</label>
                        <select id="jurusan_kampus" class="form-select" required disabled>
                            <option value="">-- Pilih Jurusan --</option>
                        </select>
                        <div class="form-text text-muted" style="font-size:.75rem;" id="jurusan_hint">Pilih tingkatan dahulu.</div>
                    </div>
                    <div class="col-md-4">
                        <label for="kampus_indonesia" class="form-label small text-muted mb-1">Sekolah / Kampus</label>
                        <div id="kampus_select_wrapper">
                            <select id="kampus_indonesia" class="form-select" required disabled>
                                <option value="">-- Pilih Kampus --</option>
                            </select>
                        </div>
                        <input type="text" id="kampus_manual" class="form-control d-none"
                               placeholder="Ketik nama sekolah" disabled>
                        <div class="form-text text-muted" style="font-size:.75rem;" id="kampus_hint">Pilih jurusan dahulu.</div>
                    </div>
                </div>
                <input type="hidden" name="pendidikan_saat_ini" id="pendidikan_saat_ini"
                       class="@error('pendidikan_saat_ini') is-invalid @enderror"
                       value="{{ old('pendidikan_saat_ini', $profile->pendidikan_saat_ini) }}" required>
                <div class="invalid-feedback d-block">{{ $errors->first('pendidikan_saat_ini') ?: 'Pendidikan saat ini wajib diisi.' }}</div>
            </div>

            {{-- Pekerjaan --}}
            <div class="col-md-6">
                <label for="pekerjaan_saat_ini" class="form-label fw-bold">Pekerjaan Saat Ini <span class="text-muted fw-normal">(Opsional)</span></label>
                <input type="text" class="form-control @error('pekerjaan_saat_ini') is-invalid @enderror"
                       id="pekerjaan_saat_ini" name="pekerjaan_saat_ini"
                       placeholder="Contoh: Software Engineer - PT XYZ"
                       value="{{ old('pekerjaan_saat_ini', $profile->pekerjaan_utama) }}">
                <div class="invalid-feedback">{{ $errors->first('pekerjaan_saat_ini') }}</div>
            </div>

            {{-- Prestasi --}}
            <div class="col-md-6">
                <label for="prestasi_terbaru" class="form-label fw-bold">Prestasi Terbaru <span class="text-muted fw-normal">(Opsional)</span></label>
                <textarea class="form-control @error('prestasi_terbaru') is-invalid @enderror"
                          id="prestasi_terbaru" name="prestasi_terbaru"
                          rows="3" placeholder="Tuliskan prestasi terbaru alumni...">{{ old('prestasi_terbaru', $profile->prestasi_terbaru) }}</textarea>
                <div class="invalid-feedback">{{ $errors->first('prestasi_terbaru') }}</div>
            </div>
        </div>
    </div>

    {{-- ── Section 3: Foto & Media Sosial ── --}}
    <div class="profile-section-card">
        <div class="profile-section-title">
            <i class="ri-image-line"></i> Foto & Media Sosial
        </div>
        <div class="row g-3">
            <div class="col-md-3">
                <label class="form-label fw-bold">Foto Profil</label>
                <div>
                    @if ($profile->foto_profil)
                        <div class="foto-preview-wrap mb-2">
                            <img src="{{ Storage::disk('public')->url($profile->foto_profil) }}" alt="Foto Profil"
                                 id="fotoPreviewImg"
                                 class="rounded" style="height:90px;width:90px;object-fit:cover;">
                        </div>
                    @else
                        <div class="foto-preview-wrap mb-2" id="fotoPreviewWrap" style="display:none;">
                            <img id="fotoPreviewImg" src="" alt="Preview"
                                 class="rounded" style="height:90px;width:90px;object-fit:cover;">
                        </div>
                    @endif
                    <label class="foto-preview-label" for="foto_profil">
                        <i class="ri-upload-cloud-line"></i>
                        {{ $profile->foto_profil ? 'Ganti Foto' : 'Unggah Foto' }}
                    </label>
                    <input type="file" class="d-none @error('foto_profil') is-invalid @enderror"
                           id="foto_profil" name="foto_profil" accept="image/*">
                    <div class="form-text text-muted mt-1" style="font-size:.75rem;">JPG/PNG/WEBP • Maks 2MB</div>
                    <div class="invalid-feedback">{{ $errors->first('foto_profil') }}</div>
                </div>
            </div>
            <div class="col-md-9">
                <label class="form-label fw-bold">Akun Media Sosial</label>
                @php $medsos = $profile->akun_medsos ?? []; @endphp
                <div class="row g-2">
                    <div class="col-md-6">
                        <div class="input-group">
                            <span class="input-group-text"><i class="ri-instagram-line"></i></span>
                            <input type="text" class="form-control" name="medsos_instagram"
                                   placeholder="Instagram" value="{{ old('medsos_instagram', $medsos['instagram'] ?? '') }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="input-group">
                            <span class="input-group-text"><i class="ri-twitter-x-line"></i></span>
                            <input type="text" class="form-control" name="medsos_twitter"
                                   placeholder="Twitter / X" value="{{ old('medsos_twitter', $medsos['twitter'] ?? '') }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="input-group">
                            <span class="input-group-text"><i class="ri-linkedin-box-line"></i></span>
                            <input type="text" class="form-control" name="medsos_linkedin"
                                   placeholder="LinkedIn" value="{{ old('medsos_linkedin', $medsos['linkedin'] ?? '') }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="input-group">
                            <span class="input-group-text"><i class="ri-facebook-circle-line"></i></span>
                            <input type="text" class="form-control" name="medsos_facebook"
                                   placeholder="Facebook" value="{{ old('medsos_facebook', $medsos['facebook'] ?? '') }}">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Section 4: Ketertarikan Bidang ── --}}
    <div class="profile-section-card">
        <div class="profile-section-title">
            <i class="ri-price-tag-3-line"></i> Ketertarikan Bidang
        </div>
        @php
            $bidangList = ['Kesehatan', 'Pendidikan', 'Teknologi Informasi', 'Hukum', 'Ekonomi',
                           'Politik', 'Sosial Budaya', 'Lingkungan Hidup', 'Olahraga', 'Seni & Budaya'];
            $savedBidang = old('ketertarikan_bidang', $profile->ketertarikan_bidang ?? []);
        @endphp
        <div class="bidang-chips">
            @foreach ($bidangList as $bidang)
                <input class="bidang-chip" type="checkbox"
                       name="ketertarikan_bidang[]"
                       id="bidang_{{ $loop->index }}"
                       value="{{ $bidang }}"
                       {{ in_array($bidang, $savedBidang) ? 'checked' : '' }}>
                <label for="bidang_{{ $loop->index }}">{{ $bidang }}</label>
            @endforeach
        </div>
    </div>

    {{-- ── Section 5: Pengalaman Organisasi ── --}}
    <div class="profile-section-card">
        <div class="profile-section-title">
            <i class="ri-team-line"></i> Riwayat Pengalaman Organisasi
            <span class="ms-auto text-muted fw-normal" style="text-transform:none;letter-spacing:0;font-size:.78rem;">Opsional</span>
        </div>
        <div id="org-container">
            @php
                $defaultOrg = [['organisasi'=>'','periode_mulai_tahun'=>'','periode_selesai_tahun'=>'','jabatan'=>'','ruang_lingkup'=>'']];
                $oldOrg = old('pengalaman_organisasi', $profile->pengalaman_organisasi ?? $defaultOrg);
                if (empty($oldOrg)) $oldOrg = $defaultOrg;
            @endphp
            @foreach ($oldOrg as $i => $org)
            <div class="org-entry p-3 mb-2">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="org-num">Pengalaman {{ $i + 1 }}</span>
                    <button type="button" class="btn btn-sm btn-outline-danger btn-hapus-org py-1 px-2"
                            style="font-size:.78rem;" {{ $i === 0 ? 'style=display:none' : '' }}>
                        <i class="ri-delete-bin-line"></i> Hapus
                    </button>
                </div>
                <div class="row g-2">
                    <div class="col-md-12">
                        <label class="form-label small fw-semibold">Nama Organisasi</label>
                        <input type="text" class="form-control" name="pengalaman_organisasi[{{ $i }}][organisasi]"
                               placeholder="Nama organisasi" value="{{ $org['organisasi'] ?? '' }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Tahun Mulai</label>
                        <input type="text" class="form-control org-year" name="pengalaman_organisasi[{{ $i }}][periode_mulai_tahun]"
                               placeholder="2020" maxlength="4" value="{{ $org['periode_mulai_tahun'] ?? '' }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Tahun Selesai</label>
                        <input type="text" class="form-control org-year" name="pengalaman_organisasi[{{ $i }}][periode_selesai_tahun]"
                               placeholder="2022" maxlength="4" value="{{ $org['periode_selesai_tahun'] ?? '' }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Jabatan</label>
                        <select class="form-select" name="pengalaman_organisasi[{{ $i }}][jabatan]">
                            <option value="">-- Jabatan --</option>
                            <option value="Ketua" {{ ($org['jabatan'] ?? '') === 'Ketua' ? 'selected' : '' }}>Ketua</option>
                            <option value="Pengurus" {{ ($org['jabatan'] ?? '') === 'Pengurus' ? 'selected' : '' }}>Pengurus</option>
                            <option value="Anggota" {{ ($org['jabatan'] ?? '') === 'Anggota' ? 'selected' : '' }}>Anggota</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Ruang Lingkup</label>
                        <select class="form-select" name="pengalaman_organisasi[{{ $i }}][ruang_lingkup]">
                            <option value="">-- Ruang Lingkup --</option>
                            <option value="Nasional" {{ ($org['ruang_lingkup'] ?? '') === 'Nasional' ? 'selected' : '' }}>Nasional</option>
                            <option value="Provinsi" {{ ($org['ruang_lingkup'] ?? '') === 'Provinsi' ? 'selected' : '' }}>Provinsi</option>
                            <option value="Kabupaten/Kota" {{ ($org['ruang_lingkup'] ?? '') === 'Kabupaten/Kota' ? 'selected' : '' }}>Kabupaten/Kota</option>
                            <option value="Lainnya" {{ ($org['ruang_lingkup'] ?? '') === 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <button type="button" id="btn-tambah-org" class="btn btn-sm btn-outline-primary mt-1" style="border-radius:8px;">
            <i class="ri-add-line"></i> Tambah Pengalaman
        </button>
    </div>

    {{-- Status hidden --}}
    <input type="hidden" name="status" value="1">

    {{-- ── Submit Bar ── --}}
    <div class="submit-bar">
        <button type="submit" class="btn btn-primary btn-save-profile">
            <i class="ri-save-line"></i> Simpan Perubahan
        </button>
        <a href="{{ route('parja.alumni-profile.index') }}" class="btn btn-light btn-cancel-profile">
            <i class="ri-arrow-left-line"></i> Batal
        </a>
    </div>
</form>
</div>
@endsection

@push('scripts')
<script src="{{ asset('template/dist/lib/select2/js/select2.min.js') }}"></script>
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

    // Foto preview live
    document.getElementById('foto_profil').addEventListener('change', function () {
        if (!this.files || !this.files[0]) return;
        const reader = new FileReader();
        reader.onload = function (e) {
            let img = document.getElementById('fotoPreviewImg');
            let wrap = document.getElementById('fotoPreviewWrap');
            if (!img) {
                // create if not existing
                wrap = document.createElement('div');
                wrap.id = 'fotoPreviewWrap';
                wrap.className = 'foto-preview-wrap mb-2';
                img = document.createElement('img');
                img.id = 'fotoPreviewImg';
                img.alt = 'Preview';
                img.className = 'rounded';
                img.style.cssText = 'height:90px;width:90px;object-fit:cover;';
                wrap.appendChild(img);
                document.getElementById('foto_profil').closest('div').prepend(wrap);
            }
            if (wrap) wrap.style.display = '';
            img.src = e.target.result;
        };
        reader.readAsDataURL(this.files[0]);
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

    // ── Chained Domisili (Provinsi → Kota/Kabupaten) ──────────────────────────
    (function () {
        const groups  = @json($domisiliGroups ?? []);
        const provSel = document.getElementById('domisili_provinsi_edit');
        const kotaSel = document.getElementById('domisili_kota_edit');
        const hidden  = document.getElementById('domisili_terakhir');

        function populateCities(province, selectedCity) {
            kotaSel.innerHTML = '<option value="">-- Pilih Kota/Kabupaten --</option>';
            const cities = groups[province] || [];
            cities.forEach(function (city) {
                const opt = document.createElement('option');
                opt.value = city + ', ' + province;
                opt.textContent = city;
                if (opt.value === selectedCity) opt.selected = true;
                kotaSel.appendChild(opt);
            });
            kotaSel.disabled = cities.length === 0;
        }

        // Initialise from hidden value on page load
        const initVal = hidden ? hidden.value : '';
        if (initVal && provSel) {
            const parts = initVal.split(', ');
            const prov  = parts.length > 1 ? parts[parts.length - 1] : '';
            if (prov && groups[prov]) {
                provSel.value = prov;
                populateCities(prov, initVal);
            }
        }

        if (provSel) {
            provSel.addEventListener('change', function () {
                populateCities(this.value, '');
                if (hidden) hidden.value = '';
            });
        }

        if (kotaSel) {
            kotaSel.addEventListener('change', function () {
                if (hidden) hidden.value = this.value;
            });
        }
    })();

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
            if (window.jQuery && window.jQuery(kampusSelect).hasClass('select2-hidden-accessible')) {
                window.jQuery(kampusSelect).select2('destroy');
            }
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
        const jurusanHint = document.getElementById('jurusan_hint');
        const kampusHint = document.getElementById('kampus_hint');

        if (!Array.isArray(majors) || majors.length === 0) {
            jurusanSelect.disabled = true;
            if (jurusanHint) jurusanHint.textContent = 'Tingkatan ini tidak memiliki jurusan.';
            if (window.jQuery && window.jQuery(jurusanSelect).hasClass('select2-hidden-accessible')) {
                window.jQuery(jurusanSelect).select2('destroy');
            }
            return;
        }

        majors.forEach(function (jurusan) {
            jurusanSelect.insertAdjacentHTML(
                'beforeend',
                '<option value="' + jurusan + '" ' + (selectedValue === jurusan ? 'selected' : '') + '>' + jurusan + '</option>'
            );
        });
        jurusanSelect.disabled = false;
        if (jurusanHint) jurusanHint.textContent = majors.length + ' pilihan tersedia.';
        if (kampusHint) kampusHint.textContent = 'Pilih jurusan dahulu.';

        if (window.jQuery && window.jQuery.fn.select2) {
            window.jQuery(jurusanSelect).select2({ width: '100%', placeholder: '-- Pilih Jurusan --' });
        }
    }

    function renderKampus(campuses, selectedValue) {
        kampusSelect.innerHTML = '<option value="">-- Pilih Kampus --</option>';
        const kampusHint = document.getElementById('kampus_hint');

        if (!Array.isArray(campuses) || campuses.length === 0) {
            kampusSelect.disabled = true;
            if (kampusHint) kampusHint.textContent = 'Tidak ada data kampus.';
            if (window.jQuery && window.jQuery(kampusSelect).hasClass('select2-hidden-accessible')) {
                window.jQuery(kampusSelect).select2('destroy');
            }
            return;
        }

        campuses.forEach(function (kampus) {
            kampusSelect.insertAdjacentHTML(
                'beforeend',
                '<option value="' + kampus + '" ' + (selectedValue === kampus ? 'selected' : '') + '>' + kampus + '</option>'
            );
        });
        kampusSelect.disabled = false;
        if (kampusHint) kampusHint.textContent = campuses.length + ' kampus tersedia. Ketik untuk mencari.';

        if (window.jQuery && window.jQuery.fn.select2) {
            window.jQuery(kampusSelect).select2({ width: '100%', placeholder: '-- Pilih Kampus --' });
        }
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
