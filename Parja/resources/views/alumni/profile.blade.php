@extends('parja::alumni.layouts.app')

@section('title', 'Update Profile Alumni')
@section('page-title', 'Update Profile Alumni')

@push('styles')
    <style>
        .parja-mini-toast {
            position: fixed;
            right: 18px;
            bottom: 18px;
            z-index: 1080;
            min-width: 260px;
            max-width: 360px;
            border-radius: 12px;
            padding: 10px 14px;
            color: #fff;
            font-size: 0.85rem;
            box-shadow: 0 10px 22px rgba(0, 0, 0, 0.2);
            opacity: 0;
            transform: translateY(10px);
            pointer-events: none;
            transition: opacity 0.2s ease, transform 0.2s ease;
        }

        .parja-mini-toast .toast-content {
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .parja-mini-toast .toast-content i {
            font-size: 1rem;
            line-height: 1;
        }

        .parja-mini-toast.show {
            opacity: 1;
            transform: translateY(0);
        }

        .parja-mini-toast.error {
            background: #b42318;
        }

        .parja-mini-toast.info {
            background: var(--parja-magenta);
        }

        .parja-stepper-wrap {
            border: 1px solid rgba(65, 23, 75, 0.2);
            border-radius: 14px;
            background: #fffaf2;
            padding: 14px;
            margin-bottom: 16px;
        }

        .parja-stepper-head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
            margin-bottom: 10px;
        }

        .parja-stepper-title {
            margin: 0;
            font-weight: 700;
            color: var(--parja-purple);
            font-size: 1rem;
        }

        .parja-stepper-subtitle {
            margin: 2px 0 0;
            color: var(--parja-muted);
            font-size: 0.85rem;
        }

        .parja-stepper-count {
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--parja-purple);
            background: #fff;
            border: 1px solid rgba(65, 23, 75, 0.24);
            border-radius: 999px;
            padding: 4px 10px;
        }

        .parja-stepper-track {
            width: 100%;
            height: 8px;
            border-radius: 999px;
            background: rgba(65, 23, 75, 0.12);
            overflow: hidden;
            margin-bottom: 12px;
        }

        .parja-stepper-track>span {
            display: block;
            height: 100%;
            width: 0;
            border-radius: 999px;
            background: linear-gradient(90deg, var(--parja-purple), var(--parja-magenta));
            transition: width 0.25s ease;
        }

        .parja-stepper-list {
            display: grid;
            grid-template-columns: repeat(5, minmax(120px, 1fr));
            gap: 8px;
        }

        .parja-step-item {
            border: 1px solid rgba(65, 23, 75, 0.18);
            border-radius: 10px;
            padding: 8px;
            background: #fff;
            color: var(--parja-muted);
            cursor: pointer;
            text-align: left;
            transition: all 0.2s ease;
        }

        .parja-step-item small {
            display: block;
            color: #8d7f95;
            font-size: 0.72rem;
        }

        .parja-step-item.active {
            border-color: var(--parja-magenta);
            background: #fff;
            color: var(--parja-magenta);
            box-shadow: 0 3px 10px rgba(65, 23, 75, 0.14);
        }

        .parja-step-item.done {
            border-color: rgba(251, 172, 24, 0.58);
            background: #fff8ea;
            color: var(--parja-purple);
        }

        .parja-step-item.locked {
            border-color: rgba(108, 117, 125, 0.35);
            background: #f8f9fa;
            color: #6c757d;
            cursor: pointer;
        }

        .parja-step-item.locked.active {
            border-color: #6c757d;
            background: #fff;
            color: #495057;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.08);
        }

        .parja-step-item.locked small {
            color: #adb5bd;
        }

        .parja-step-pane {
            display: none;
        }

        .parja-step-pane.active {
            display: block;
            animation: parjaFadeIn 0.18s ease;
        }

        .parja-step-note {
            border-left: 3px solid var(--parja-magenta);
            background: #fff8ef;
            padding: 10px 12px;
            border-radius: 8px;
            color: var(--parja-muted);
            font-size: 0.85rem;
            margin-bottom: 12px;
        }

        .parja-step-actions {
            margin-top: 16px;
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        @keyframes parjaFadeIn {
            from {
                opacity: 0;
                transform: translateY(3px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 991px) {
            .parja-stepper-list {
                grid-template-columns: repeat(2, minmax(120px, 1fr));
            }
        }

        @media (max-width: 575px) {
            .parja-stepper-list {
                grid-template-columns: 1fr;
            }
        }
    </style>
    <link rel="stylesheet" href="{{ asset('template/dist/lib/select2/css/select2.min.css') }}">
@endpush

@section('content')
    <x-parja.panel title="Update Profil Pengguna"
        subtitle="Data profil terhubung langsung ke tabel alumni dan alumni_profiles. Perubahan pada form ini akan tersimpan ke data real.">
        <x-slot:chip>
            <x-parja.chip icon="ri-edit-2-line">Update Profile</x-parja.chip>
        </x-slot:chip>
    </x-parja.panel>

    <x-parja.card title="Edit Profil Alumni">
        @if (!$alumni)
            <p class="mb-0 text-danger">Data alumni Anda belum terhubung. Hubungi admin untuk sinkronisasi user ke tabel alumni.
            </p>
        @else
            @php
                $isApproved = (int) ($alumni->approval_status ?? 0) === 1;
            @endphp
            <form id="alumniProfileForm" method="POST" action="{{ route('alumni.profile.update') }}"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="parja-stepper-wrap">
                    <div class="parja-stepper-head">
                        <div>
                            <p class="parja-stepper-title mb-0">Roadmap Update Profil</p>
                            <p id="parjaStepDescription" class="parja-stepper-subtitle">Lengkapi data dasar terlebih dahulu
                                untuk memulai.</p>
                        </div>
                        <span id="parjaStepCount" class="parja-stepper-count">Tahap 1 dari 5</span>
                    </div>

                    <div class="parja-stepper-track">
                        <span id="parjaStepProgress"></span>
                    </div>

                    <div class="parja-stepper-list" role="tablist" aria-label="Tahapan update profil alumni">
                        <button type="button" class="parja-step-item active" data-step-target="1" aria-current="step">
                            1. Data Dasar
                            <small>Identitas utama</small>
                        </button>
                        <button type="button" class="parja-step-item{{ $isApproved ? ' locked' : '' }}" data-step-target="2" {{ $isApproved ? 'data-step-locked="true"' : '' }}>
                            {{ $isApproved ? '🔒 ' : '' }}2. Keanggotaan
                            <small>{{ $isApproved ? 'Data terkunci' : 'Dapil dan angkatan' }}</small>
                        </button>
                        <button type="button" class="parja-step-item" data-step-target="3">
                            3. Aktivitas
                            <small>Pendidikan, kerja, domisili</small>
                        </button>
                        <button type="button" class="parja-step-item" data-step-target="4">
                            4. Foto Profil
                            <small>Upload dan validasi foto</small>
                        </button>
                        <button type="button" class="parja-step-item" data-step-target="5">
                            5. Media Sosial
                            <small>Akun publik alumni</small>
                        </button>
                    </div>
                </div>

                <div class="parja-step-pane active" data-step="1">
                    <div class="parja-step-note">
                        Tahap 1 fokus pada identitas inti. Nama dan Asal Sekolah (SMA) sudah terkunci dari data pendaftaran.
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nama</label>
                            <input type="text" name="nama" class="form-control"
                                value="{{ $alumni->nama ?? (auth()->guard('keycloak-external')->user()?->name ?? auth()->guard('keycloak')->user()?->name ?? '') }}" readonly style="background:#fafafa;">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email Login</label>
                            <input type="text" class="form-control" value="{{ (auth()->guard('keycloak-external')->user()?->email ?? auth()->guard('keycloak')->user()?->email ?? '') }}" readonly>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Asal Sekolah Parja</label>
                            <input type="text" name="asal_sekolah" class="form-control"
                                value="{{ $alumni->asal_sekolah ?? '' }}" readonly style="background:#fafafa;">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Nomor Telepon / WhatsApp <span class="text-danger">*</span></label>
                            <input type="tel" name="notelp" id="notelp" class="form-control"
                                inputmode="numeric"
                                pattern="[0-9]{6,15}"
                                maxlength="15"
                                minlength="6"
                                placeholder="Contoh: 08123456789"
                                value="{{ old('notelp', $profile?->notelp ?? '') }}"
                                oninput="this.value=this.value.replace(/[^0-9]/g,'')"
                                required>
                            <div class="form-text">Hanya digit angka, 6–15 karakter. Tidak ditampilkan publik.</div>
                        </div>
                    </div>
                </div>

                <div class="parja-step-pane" data-step="2">
                    @if ($isApproved)
                        <div class="parja-step-note" style="border-left-color: #6c757d; background: #f8f9fa; color: #495057;">
                            <i class="ri-lock-line" style="color:#6c757d;"></i>
                            <strong>Data Keanggotaan Terkunci.</strong>
                            Dapil, Tahun Angkatan, dan No Anggota tidak dapat diubah karena akun Anda sudah dirilis/disetujui admin.
                            Hubungi admin jika perlu perubahan.
                        </div>
                    @else
                        <div class="parja-step-note">
                            Tahap 2 mengatur data keanggotaan dan klasifikasi alumni untuk kebutuhan direktori dan tracer.
                        </div>
                    @endif

                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Dapil</label>
                            @if ($isApproved)
                                @php
                                    $dapilLabel = collect($dapils)->firstWhere('id', $selectedDapilId);
                                @endphp
                                <input type="hidden" name="id_dapil" value="{{ $selectedDapilId }}">
                                <input type="text" class="form-control" value="{{ $dapilLabel ? $dapilLabel->id . ' - ' . $dapilLabel->dapil : ($alumni->dapil ?? '-') }}" readonly style="background:#f5f5f5; color:#6c757d; cursor:not-allowed;">
                            @else
                                <select name="id_dapil" class="form-select">
                                    <option value="">Pilih Dapil</option>
                                    @foreach ($dapils as $dapil)
                                        <option value="{{ $dapil->id }}" {{ (string) old('id_dapil', $selectedDapilId) === (string) $dapil->id ? 'selected' : '' }}>
                                            {{ $dapil->id }} - {{ $dapil->dapil }}
                                        </option>
                                    @endforeach
                                </select>
                            @endif
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Tahun Angkatan</label>
                            <input type="text" name="tahun_angkatan" class="form-control"
                                value="{{ old('tahun_angkatan', $alumni->tahun_angkatan ?? '') }}"
                                {{ $isApproved ? 'readonly style="background:#f5f5f5; color:#6c757d; cursor:not-allowed;"' : '' }}>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">No Anggota @if(!$isApproved)<span class="text-danger">*</span>@endif</label>
                            <input type="text" name="no_anggota" class="form-control"
                                value="{{ old('no_anggota', $alumni->no_anggota ?? '') }}"
                                {{ $isApproved ? 'readonly style="background:#f5f5f5; color:#6c757d; cursor:not-allowed;"' : 'required placeholder="Contoh: 2025/001"' }}>
                        </div>
                    </div>
                </div>

                <div class="parja-step-pane" data-step="3">
                    <div class="parja-step-note">
                        Tahap 3 menampilkan kondisi terkini alumni. Data ini dipakai untuk analitik perkembangan alumni.
                    </div>

                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label">Pendidikan Saat Ini <span class="text-danger">*</span></label>
                            <div class="row g-2">
                                <div class="col-md-4">
                                    <label for="tingkatan_pendidikan" class="form-label small text-muted mb-1">Tingkat
                                        Pendidikan</label>
                                    <select id="tingkatan_pendidikan" class="form-select">
                                        <option value="">-- Pilih Tingkat --</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="jurusan_kampus" class="form-label small text-muted mb-1">Jurusan
                                        Pendidikan</label>
                                    <select id="jurusan_kampus" class="form-select" disabled>
                                        <option value="">-- Pilih Jurusan --</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="kampus_indonesia" class="form-label small text-muted mb-1">Nama
                                        Sekolah/Universitas Pendidikan</label>
                                    <div id="kampus_select_wrapper">
                                        <select id="kampus_indonesia" class="form-select" disabled>
                                            <option value="">-- Pilih Sekolah/Kampus --</option>
                                        </select>
                                    </div>
                                    <input type="text" id="kampus_manual" class="form-control d-none"
                                        placeholder="Ketik nama sekolah/kampus" disabled>
                                </div>
                            </div>
                            <input type="hidden" name="pendidikan_saat_ini" id="pendidikan_saat_ini"
                                value="{{ old('pendidikan_saat_ini', $profile?->pendidikan_saat_ini ?? '') }}" required>
                            <div id="pendidikanSaatIniFeedback" class="small text-danger mt-1 d-none">Pendidikan saat ini wajib
                                dipilih melalui dropdown.</div>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label d-flex justify-content-between align-items-center">
                                <span>Pekerjaan Saat Ini</span>
                                <button type="button" class="btn btn-outline-danger btn-sm" onclick="window.addRepeater('pekerjaan', 'pekerjaan_saat_ini', 'Pekerjaan (Contoh: Mahasiswa / Karyawan PT X)')">
                                    <i class="ri-add-line"></i> Tambah
                                </button>
                            </label>
                            <div id="repeater_pekerjaan">
                                @php
                                    $pekData = old('pekerjaan_saat_ini', $profile?->pekerjaan_saat_ini);
                                    if (is_string($pekData) && !empty($pekData)) {
                                        $decoded = json_decode($pekData, true);
                                        $pekData = $decoded ?: [['judul' => $pekData, 'periode' => '']];
                                    }
                                    $pekData = is_array($pekData) && count($pekData) > 0 ? $pekData : [];
                                    $currYear = date('Y');
                                @endphp
                                @foreach($pekData as $i => $item)
                                    @php
                                        $tMulai = $item['tahun_mulai'] ?? '';
                                        $tSelesai = $item['tahun_selesai'] ?? '';
                                        if (empty($tMulai) && empty($tSelesai) && !empty($item['periode'])) {
                                            $parts = explode('-', str_replace(' ', '', $item['periode']));
                                            $tMulai = $parts[0] ?? '';
                                            $tSelesai = $parts[1] ?? '';
                                            if (stripos($tSelesai, 'sekarang') !== false || stripos($tSelesai, 'saatini') !== false) {
                                                $tSelesai = 'Saat Ini';
                                            }
                                        }
                                    @endphp
                                    <div class="d-flex gap-2 mb-2 repeater-item">
                                        <input type="text" name="pekerjaan_saat_ini[{{ $i }}][judul]" class="form-control" value="{{ $item['judul'] ?? '' }}" placeholder="Pekerjaan (Contoh: Mahasiswa / Karyawan PT X)">
                                        <select name="pekerjaan_saat_ini[{{ $i }}][tahun_mulai]" class="form-select" style="width: 130px; flex-shrink: 0;">
                                            <option value="">-- Mulai --</option>
                                            @for($y = $currYear; $y >= 1990; $y--)
                                                <option value="{{ $y }}" {{ $tMulai == $y ? 'selected' : '' }}>{{ $y }}</option>
                                            @endfor
                                        </select>
                                        <div class="d-flex align-items-center">-</div>
                                        <select name="pekerjaan_saat_ini[{{ $i }}][tahun_selesai]" class="form-select" style="width: 130px; flex-shrink: 0;">
                                            <option value="">-- Selesai --</option>
                                            <option value="Saat Ini" {{ $tSelesai == 'Saat Ini' ? 'selected' : '' }}>Saat Ini</option>
                                            @for($y = $currYear; $y >= 1990; $y--)
                                                <option value="{{ $y }}" {{ $tSelesai == $y ? 'selected' : '' }}>{{ $y }}</option>
                                            @endfor
                                        </select>
                                        <button type="button" class="btn btn-outline-danger px-2" onclick="this.closest('.repeater-item').remove()"><i class="ri-subtract-line"></i></button>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label d-flex justify-content-between align-items-center">
                                <span>Pengalaman Organisasi</span>
                                <button type="button" class="btn btn-outline-danger btn-sm" onclick="window.addRepeater('organisasi', 'pengalaman_organisasi', 'Nama Organisasi / Jabatan')">
                                    <i class="ri-add-line"></i> Tambah
                                </button>
                            </label>
                            <div id="repeater_organisasi">
                                @php
                                    $orgData = old('pengalaman_organisasi', $profile?->pengalaman_organisasi);
                                    if (is_string($orgData) && !empty($orgData)) {
                                        $decoded = json_decode($orgData, true);
                                        $orgData = $decoded ?: [['judul' => $orgData, 'periode' => '']];
                                    }
                                    $orgData = is_array($orgData) && count($orgData) > 0 ? $orgData : [];
                                    $currYear = date('Y');
                                @endphp
                                @foreach($orgData as $i => $item)
                                    @php
                                        $tMulai = $item['tahun_mulai'] ?? '';
                                        $tSelesai = $item['tahun_selesai'] ?? '';
                                        if (empty($tMulai) && empty($tSelesai) && !empty($item['periode'])) {
                                            $parts = explode('-', str_replace(' ', '', $item['periode']));
                                            $tMulai = $parts[0] ?? '';
                                            $tSelesai = $parts[1] ?? '';
                                            if (stripos($tSelesai, 'sekarang') !== false || stripos($tSelesai, 'saatini') !== false) {
                                                $tSelesai = 'Saat Ini';
                                            }
                                        }
                                    @endphp
                                    <div class="d-flex gap-2 mb-2 repeater-item">
                                        <input type="text" name="pengalaman_organisasi[{{ $i }}][judul]" class="form-control" value="{{ $item['judul'] ?? '' }}" placeholder="Nama Organisasi / Jabatan">
                                        <select name="pengalaman_organisasi[{{ $i }}][tahun_mulai]" class="form-select" style="width: 130px; flex-shrink: 0;">
                                            <option value="">-- Mulai --</option>
                                            @for($y = $currYear; $y >= 1990; $y--)
                                                <option value="{{ $y }}" {{ $tMulai == $y ? 'selected' : '' }}>{{ $y }}</option>
                                            @endfor
                                        </select>
                                        <div class="d-flex align-items-center">-</div>
                                        <select name="pengalaman_organisasi[{{ $i }}][tahun_selesai]" class="form-select" style="width: 130px; flex-shrink: 0;">
                                            <option value="">-- Selesai --</option>
                                            <option value="Saat Ini" {{ $tSelesai == 'Saat Ini' ? 'selected' : '' }}>Saat Ini</option>
                                            @for($y = $currYear; $y >= 1990; $y--)
                                                <option value="{{ $y }}" {{ $tSelesai == $y ? 'selected' : '' }}>{{ $y }}</option>
                                            @endfor
                                        </select>
                                        <button type="button" class="btn btn-outline-danger px-2" onclick="this.closest('.repeater-item').remove()"><i class="ri-subtract-line"></i></button>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label d-flex justify-content-between align-items-center">
                                <span>Prestasi Terbaru</span>
                                <button type="button" class="btn btn-outline-danger btn-sm" onclick="window.addRepeater('prestasi', 'prestasi_terbaru', 'Penghargaan atau pencapaian')">
                                    <i class="ri-add-line"></i> Tambah
                                </button>
                            </label>
                            <div id="repeater_prestasi">
                                @php
                                    $presData = old('prestasi_terbaru', $profile?->prestasi_terbaru);
                                    if (is_string($presData) && !empty($presData)) {
                                        $decoded = json_decode($presData, true);
                                        $presData = $decoded ?: [['judul' => $presData, 'periode' => '']];
                                    }
                                    $presData = is_array($presData) && count($presData) > 0 ? $presData : [];
                                    $currYear = date('Y');
                                @endphp
                                @foreach($presData as $i => $item)
                                    @php
                                        $tMulai = $item['tahun_mulai'] ?? '';
                                        $tSelesai = $item['tahun_selesai'] ?? '';
                                        if (empty($tMulai) && empty($tSelesai) && !empty($item['periode'])) {
                                            $parts = explode('-', str_replace(' ', '', $item['periode']));
                                            $tMulai = $parts[0] ?? '';
                                            $tSelesai = $parts[1] ?? '';
                                            if (stripos($tSelesai, 'sekarang') !== false || stripos($tSelesai, 'saatini') !== false) {
                                                $tSelesai = 'Saat Ini';
                                            }
                                        }
                                    @endphp
                                    <div class="d-flex gap-2 mb-2 repeater-item">
                                        <input type="text" name="prestasi_terbaru[{{ $i }}][judul]" class="form-control" value="{{ $item['judul'] ?? '' }}" placeholder="Penghargaan atau pencapaian">
                                        <select name="prestasi_terbaru[{{ $i }}][tahun_mulai]" class="form-select" style="width: 130px; flex-shrink: 0;">
                                            <option value="">-- Mulai --</option>
                                            @for($y = $currYear; $y >= 1990; $y--)
                                                <option value="{{ $y }}" {{ $tMulai == $y ? 'selected' : '' }}>{{ $y }}</option>
                                            @endfor
                                        </select>
                                        <div class="d-flex align-items-center">-</div>
                                        <select name="prestasi_terbaru[{{ $i }}][tahun_selesai]" class="form-select" style="width: 130px; flex-shrink: 0;">
                                            <option value="">-- Selesai --</option>
                                            <option value="Saat Ini" {{ $tSelesai == 'Saat Ini' ? 'selected' : '' }}>Saat Ini</option>
                                            @for($y = $currYear; $y >= 1990; $y--)
                                                <option value="{{ $y }}" {{ $tSelesai == $y ? 'selected' : '' }}>{{ $y }}</option>
                                            @endfor
                                        </select>
                                        <button type="button" class="btn btn-outline-danger px-2" onclick="this.closest('.repeater-item').remove()"><i class="ri-subtract-line"></i></button>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- Prestasi & Penghargaan: foto kegiatan + link penghargaan + deskripsi prestasi.
                             Tampil di profil pribadi alumni (bukan di feed/wall publik). --}}
                        <div class="col-md-12">
                            <label class="form-label d-flex justify-content-between align-items-center">
                                <span>Prestasi &amp; Penghargaan
                                    <small class="text-muted fw-normal">(foto kegiatan, link penghargaan, deskripsi)</small></span>
                                <button type="button" class="btn btn-outline-danger btn-sm" onclick="window.addPrestasiPenghargaan()">
                                    <i class="ri-add-line"></i> Tambah
                                </button>
                            </label>
                            <div id="repeater_prestasi_penghargaan">
                                @php
                                    $ppData = old('prestasi_penghargaan', $profile?->prestasi_penghargaan);
                                    $ppData = is_array($ppData) && count($ppData) > 0 ? $ppData : [];
                                @endphp
                                @foreach($ppData as $i => $item)
                                    @php
                                        $ppFoto = $item['foto'] ?? '';
                                        $ppFotoUrl = !empty($ppFoto) ? asset('storage/' . $ppFoto) : '';
                                    @endphp
                                    <div class="border rounded-3 p-2 mb-2 repeater-item">
                                        <div class="row g-2 align-items-start">
                                            <div class="col-md-3 text-center">
                                                <img src="{{ $ppFotoUrl }}" class="img-fluid rounded mb-1 pp-foto-preview"
                                                     style="max-height:90px;object-fit:cover;{{ $ppFotoUrl === '' ? 'display:none;' : '' }}">
                                                <input type="file" name="prestasi_penghargaan[{{ $i }}][foto]"
                                                       class="form-control form-control-sm pp-foto-input" accept=".jpg,.jpeg,.png,.webp">
                                                <input type="hidden" name="prestasi_penghargaan[{{ $i }}][foto_existing]" value="{{ $ppFoto }}">
                                                <small class="text-muted">Foto kegiatan (maks 1MB)</small>
                                            </div>
                                            <div class="col-md-9">
                                                <input type="url" name="prestasi_penghargaan[{{ $i }}][url_penghargaan]"
                                                       class="form-control mb-2" value="{{ $item['url_penghargaan'] ?? '' }}"
                                                       placeholder="Link penghargaan (https://...)">
                                                <textarea name="prestasi_penghargaan[{{ $i }}][deskripsi]" class="form-control" rows="2"
                                                          placeholder="Deskripsi prestasi...">{{ $item['deskripsi'] ?? '' }}</textarea>
                                            </div>
                                        </div>
                                        <div class="text-end mt-1">
                                            <button type="button" class="btn btn-outline-danger btn-sm px-2"
                                                    onclick="this.closest('.repeater-item').remove()">
                                                <i class="ri-subtract-line"></i> Hapus
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            @error('prestasi_penghargaan')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Domisili Terakhir <span class="text-danger">*</span></label>
                            @php
                                $selectedDomisili = old('domisili_terakhir', $profile?->domisili_terakhir ?? '');
                                $selDomParts   = $selectedDomisili ? array_map('trim', explode(',', $selectedDomisili, 2)) : [];
                                $selDomCity    = $selDomParts[0] ?? '';
                                $selDomProv    = $selDomParts[1] ?? '';
                                $domisiliGroups = $domisiliGroups ?? [];
                                $isDomLegacy = !empty($selectedDomisili) && !isset($domisiliGroups[$selDomProv]);
                            @endphp
                            {{-- Province selector --}}
                            <select id="domisili_provinsi_prof" class="form-select mb-2">
                                <option value="">-- Pilih Provinsi --</option>
                                @foreach(array_keys($domisiliGroups) as $prov)
                                    <option value="{{ $prov }}" {{ $selDomProv === $prov ? 'selected' : '' }}>{{ $prov }}</option>
                                @endforeach
                            </select>
                            {{-- City / Regency selector --}}
                            <select id="domisili_kota_prof" class="form-select"
                                    {{ empty($selDomProv) && !$isDomLegacy ? 'disabled' : '' }}>
                                <option value="">-- Pilih Kota/Kabupaten --</option>
                                @if($isDomLegacy && !empty($selectedDomisili))
                                    <option value="{{ $selectedDomisili }}" selected>{{ $selectedDomisili }} (data lama)</option>
                                @endif
                            </select>
                            {{-- Actual submitted value --}}
                            <input type="hidden" name="domisili_terakhir" id="domisili_terakhir"
                                   value="{{ $selectedDomisili }}">
                            @error('domisili_terakhir')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="parja-step-pane" data-step="4">
                    <div class="parja-step-note">
                        Tahap 4 untuk pembaruan foto profil. Sistem akan cek ukuran minimal sebelum upload.
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Foto Profil</label>
                            <input type="file" id="fotoProfilInput" name="foto_profil" class="form-control"
                                accept=".jpg,.jpeg,.png,.webp">
                            <small class="text-muted">Format: JPG, JPEG, PNG, WEBP. Maksimal 2MB. Ukuran ideal minimal 512x512
                                px.</small>
                            <div id="fotoProfilMeta" class="small text-muted mt-1"></div>
                            <button type="button" id="fotoProfilReset" class="btn btn-outline-secondary btn-sm mt-2">Reset
                                Pilihan Foto</button>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label d-block">Preview Foto Saat Ini</label>
                            @php
                                $existingPhoto = !empty($profile?->foto_profil) ? asset('storage/' . $profile->foto_profil) : '';
                            @endphp
                            <img id="fotoProfilPreview" src="{{ $existingPhoto }}" alt="Preview Foto Profil"
                                style="width: 84px; height: 84px; object-fit: cover; border-radius: 14px; border: 1px solid rgba(65, 23, 75, 0.2); {{ $existingPhoto === '' ? 'display:none;' : '' }}">
                            <span id="fotoProfilEmptyText" class="text-muted"
                                style="{{ $existingPhoto !== '' ? 'display:none;' : '' }}">Belum ada foto profil</span>
                        </div>
                    </div>
                </div>

                <div class="parja-step-pane" data-step="5">
                    <div class="parja-step-note">
                        Tahap terakhir. Tambahkan akun media sosial untuk mempermudah jejaring alumni, lalu simpan perubahan.
                    </div>

                    @php
                        $medsos = is_array($profile?->akun_medsos ?? null) ? $profile?->akun_medsos : [];
                    @endphp
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Instagram</label>
                            <input type="text" name="medsos_instagram" class="form-control" placeholder="URL atau @username"
                                value="{{ old('medsos_instagram', $medsos['instagram'] ?? '') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Twitter / X</label>
                            <input type="text" name="medsos_twitter" class="form-control" placeholder="URL atau @username"
                                value="{{ old('medsos_twitter', $medsos['twitter'] ?? '') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">LinkedIn</label>
                            <input type="text" name="medsos_linkedin" class="form-control" placeholder="URL atau username"
                                value="{{ old('medsos_linkedin', $medsos['linkedin'] ?? '') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Facebook</label>
                            <input type="text" name="medsos_facebook" class="form-control" placeholder="URL atau @username"
                                value="{{ old('medsos_facebook', $medsos['facebook'] ?? '') }}">
                        </div>
                    </div>

                    {{-- Section: Tampil di Direktori Alumni (consent + moderasi admin) --}}
                    @php
                        $direktoriConsentChecked = (int) old('direktori_consent', $profile?->direktori_consent ?? 0) === 1;
                        $direktoriStatus = (int) ($profile?->direktori_status ?? 0);
                    @endphp
                    <div class="mt-4 p-3 rounded-3" style="background:#faf5fb;border:1px solid var(--parja-border,#eadcef);">
                        <h6 class="fw-bold mb-1" style="color:var(--parja-purple,#41174b);">
                            <i class="ri-profile-line"></i> Tampil di Direktori Alumni
                        </h6>
                        <p class="text-muted small mb-2">
                            Jika Anda setuju, kartu Anda akan tampil di Direktori Alumni berisi
                            <strong>foto</strong>, <strong>pendidikan saat ini</strong>, dan tombol
                            <strong>LinkedIn</strong> (bisa diklik). Kartu baru tampil setelah disetujui admin.
                        </p>

                        @if ($profile?->direktori_consent && $direktoriStatus === 1)
                            <div class="alert alert-success py-2 px-3 small mb-2">
                                Kartu Anda <strong>disetujui</strong> dan tampil di Direktori Alumni.
                            </div>
                        @elseif ($profile?->direktori_consent && $direktoriStatus === 0)
                            <div class="alert alert-info py-2 px-3 small mb-2">
                                Consent diterima — <strong>menunggu moderasi admin</strong>.
                            </div>
                        @elseif ($profile?->direktori_consent && $direktoriStatus === 2)
                            <div class="alert alert-danger py-2 px-3 small mb-2">
                                Kartu <strong>ditolak</strong> admin.
                                @if (!empty($profile?->direktori_catatan_admin))
                                    <br>Catatan: {{ $profile->direktori_catatan_admin }}
                                @endif
                                <br>Centang ulang lalu simpan untuk mengajukan kembali.
                            </div>
                        @endif

                        <div class="form-check">
                            {{-- hidden=0 dulu agar checkbox tak tercentang tetap mengirim nilai 0 --}}
                            <input type="hidden" name="direktori_consent" value="0">
                            <input class="form-check-input" type="checkbox" name="direktori_consent" value="1"
                                id="direktoriConsent" {{ $direktoriConsentChecked ? 'checked' : '' }}>
                            <label class="form-check-label small" for="direktoriConsent">
                                Saya setuju menampilkan foto, pendidikan saat ini, dan LinkedIn saya di Direktori Alumni.
                            </label>
                        </div>
                        @error('medsos_linkedin')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="parja-step-actions">
                    <button type="button" id="parjaStepPrev" class="btn btn-outline-secondary">Sebelumnya</button>
                    <button type="button" id="parjaStepNext" class="btn btn-danger">Lanjut Tahap Berikutnya</button>
                    <button type="submit" id="parjaStepSubmit" class="btn btn-danger" style="display:none;">Simpan
                        Perubahan</button>
                    <a href="{{ route('alumni.home') }}" class="btn btn-outline-secondary">Kembali</a>
                </div>
            </form>
        @endif
    </x-parja.card>

    <div id="fotoProfilToast" class="parja-mini-toast" role="status" aria-live="polite"></div>
@endsection

@push('scripts')
    <script src="{{ asset('template/dist/lib/select2/js/select2.min.js') }}"></script>
    <script>
        (function () {
            const form = document.getElementById('alumniProfileForm');

            if (!form) {
                return;
            }

            const panes = Array.prototype.slice.call(document.querySelectorAll('.parja-step-pane'));
            const stepButtons = Array.prototype.slice.call(document.querySelectorAll('.parja-step-item'));
            const stepCount = document.getElementById('parjaStepCount');
            const stepDescription = document.getElementById('parjaStepDescription');
            const stepProgress = document.getElementById('parjaStepProgress');
            const stepPrev = document.getElementById('parjaStepPrev');
            const stepNext = document.getElementById('parjaStepNext');
            const stepSubmit = document.getElementById('parjaStepSubmit');
            const tingkatanSelect = document.getElementById('tingkatan_pendidikan');
            const jurusanSelect = document.getElementById('jurusan_kampus');
            const kampusSelectWrapper = document.getElementById('kampus_select_wrapper');
            const kampusSelect = document.getElementById('kampus_indonesia');
            const kampusManualInput = document.getElementById('kampus_manual');
            const pendidikanHidden = document.getElementById('pendidikan_saat_ini');
            const pendidikanFeedback = document.getElementById('pendidikanSaatIniFeedback');

            // ── Chained Domisili (Provinsi → Kota/Kabupaten) ──────────────────
            (function () {
                const groups  = @json($domisiliGroups ?? []);
                const provSel = document.getElementById('domisili_provinsi_prof');
                const kotaSel = document.getElementById('domisili_kota_prof');
                const hidden  = document.getElementById('domisili_terakhir');

                function populateCities(province, selectedVal) {
                    kotaSel.innerHTML = '<option value="">-- Pilih Kota/Kabupaten --</option>';
                    const cities = groups[province] || [];
                    cities.forEach(function (city) {
                        const opt = document.createElement('option');
                        opt.value = city + ', ' + province;
                        opt.textContent = city;
                        if (opt.value === selectedVal) opt.selected = true;
                        kotaSel.appendChild(opt);
                    });
                    kotaSel.disabled = cities.length === 0;
                }

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
            // ─────────────────────────────────────────────────────────────────────

            const totalSteps = panes.length;
            let activeStep = 1;
            const firstErrorField = @json($errors->keys()[0] ?? null);

            const stepMeta = {
                1: 'Lengkapi data dasar terlebih dahulu untuk memulai.',
                2: 'Sesuaikan data keanggotaan agar masuk ke kategori alumni yang tepat.',
                3: 'Tambahkan informasi aktivitas terbaru untuk pelacakan perkembangan alumni.',
                4: 'Unggah foto profil terbaik Anda sesuai ketentuan ukuran.',
                5: 'Periksa kembali data media sosial lalu simpan perubahan.'
            };

            const fieldStepMap = {
                asal_sekolah_parja: 1,
                notelp: 1,
                id_dapil: 2,
                tahun_angkatan: 2,
                no_anggota: 2,
                angkatan: 2,
                pendidikan_saat_ini: 3,
                pekerjaan_saat_ini: 3,
                domisili_terakhir: 3,
                prestasi_terbaru: 3,
                foto_profil: 4,
                medsos_instagram: 5,
                medsos_twitter: 5,
                medsos_linkedin: 5,
                medsos_facebook: 5
            };

            if (firstErrorField && fieldStepMap[firstErrorField]) {
                activeStep = fieldStepMap[firstErrorField];
            }

            const showPendidikanFeedback = function (show) {
                if (!pendidikanFeedback) {
                    return;
                }
                pendidikanFeedback.classList.toggle('d-none', !show);
            };

            const isStepValid = function (step) {
                const pane = panes.find(function (item) {
                    return Number(item.dataset.step) === step;
                });

                if (!pane) {
                    return true;
                }

                const requiredInputs = Array.prototype.slice.call(pane.querySelectorAll('input[required], select[required], textarea[required]'));
                for (let i = 0; i < requiredInputs.length; i += 1) {
                    if (!requiredInputs[i].reportValidity()) {
                        return false;
                    }
                }

                if (step === 3 && pendidikanHidden) {
                    const isFilled = pendidikanHidden.value.trim() !== '';
                    showPendidikanFeedback(!isFilled);

                    if (!isFilled) {
                        if (tingkatanSelect) {
                            tingkatanSelect.setCustomValidity('Pendidikan saat ini wajib dipilih melalui dropdown.');
                            tingkatanSelect.reportValidity();
                            tingkatanSelect.setCustomValidity('');
                        }
                        return false;
                    }
                }

                if (step === 3) {
                    const domHidden = document.getElementById('domisili_terakhir');
                    const kotaSel   = document.getElementById('domisili_kota_prof');
                    if (domHidden && domHidden.value.trim() === '') {
                        if (kotaSel) {
                            kotaSel.setCustomValidity('Domisili terakhir wajib dipilih.');
                            kotaSel.reportValidity();
                            kotaSel.setCustomValidity('');
                        }
                        return false;
                    }
                }

                return true;
            };

            const renderStep = function (step) {
                activeStep = step;

                panes.forEach(function (pane) {
                    pane.classList.toggle('active', Number(pane.dataset.step) === step);
                });

                stepButtons.forEach(function (button) {
                    const buttonStep = Number(button.dataset.stepTarget);
                    button.classList.toggle('active', buttonStep === step);
                    button.classList.toggle('done', buttonStep < step);

                    if (buttonStep === step) {
                        button.setAttribute('aria-current', 'step');
                    } else {
                        button.removeAttribute('aria-current');
                    }
                });

                if (stepCount) {
                    stepCount.textContent = 'Tahap ' + step + ' dari ' + totalSteps;
                }

                if (stepDescription) {
                    stepDescription.textContent = stepMeta[step] || '';
                }

                if (stepProgress) {
                    stepProgress.style.width = ((step / totalSteps) * 100) + '%';
                }

                if (stepPrev) {
                    stepPrev.style.display = step === 1 ? 'none' : 'inline-block';
                }

                if (stepNext && stepSubmit) {
                    const isLast = step === totalSteps;
                    stepNext.style.display = isLast ? 'none' : 'inline-block';
                    stepSubmit.style.display = isLast ? 'inline-block' : 'none';
                }
            };

            if (stepButtons.length && panes.length) {
                renderStep(activeStep);

                stepButtons.forEach(function (button) {
                    button.addEventListener('click', function () {
                        const targetStep = Number(button.dataset.stepTarget || 1);

                        if (targetStep > activeStep && !isStepValid(activeStep)) {
                            return;
                        }

                        renderStep(targetStep);
                    });
                });

                if (stepPrev) {
                    stepPrev.addEventListener('click', function () {
                        renderStep(Math.max(1, activeStep - 1));
                    });
                }

                if (stepNext) {
                    stepNext.addEventListener('click', function () {
                        if (!isStepValid(activeStep)) {
                            return;
                        }

                        renderStep(Math.min(totalSteps, activeStep + 1));
                    });
                }
            }

            const educationApi = {
                levels: '{{ route('alumni.profile.education.levels') }}',
                majors: '{{ route('alumni.profile.education.majors') }}',
                campuses: '{{ route('alumni.profile.education.campuses') }}'
            };

            const isSchoolLevel = function () {
                return tingkatanSelect && tingkatanSelect.value === 'SMA/SMK Sederajat';
            };

            const setCampusInputMode = function (useManual) {
                if (!kampusSelectWrapper || !kampusManualInput || !kampusSelect) {
                    return;
                }

                if (useManual) {
                    kampusSelectWrapper.classList.add('d-none');
                    kampusManualInput.classList.remove('d-none');
                    kampusManualInput.disabled = false;
                    kampusSelect.disabled = true;
                    kampusSelect.value = '';
                    if (window.jQuery && window.jQuery(kampusSelect).hasClass('select2-hidden-accessible')) { window.jQuery(kampusSelect).select2('destroy'); }
                    return;
                }

                kampusSelectWrapper.classList.remove('d-none');
                kampusManualInput.classList.add('d-none');
                kampusManualInput.disabled = true;
                kampusManualInput.value = '';
            };

            const renderTingkatan = function (levels) {
                if (!tingkatanSelect) {
                    return;
                }

                tingkatanSelect.innerHTML = '<option value="">-- Pilih Tingkat --</option>';
                levels.forEach(function (tingkatan) {
                    tingkatanSelect.insertAdjacentHTML('beforeend', '<option value="' + tingkatan + '">' + tingkatan + '</option>');
                });
            };

            const renderJurusan = function (majors, selectedValue) {
                if (!jurusanSelect || !kampusSelect) {
                    return;
                }

                jurusanSelect.innerHTML = '<option value="">-- Pilih Jurusan --</option>';
                kampusSelect.innerHTML = '<option value="">-- Pilih Sekolah/Kampus --</option>';
                kampusSelect.disabled = true;

                if (!Array.isArray(majors) || majors.length === 0) {
                    jurusanSelect.disabled = true;
                    if (window.jQuery && window.jQuery(jurusanSelect).hasClass('select2-hidden-accessible')) { window.jQuery(jurusanSelect).select2('destroy'); }
                    return;
                }

                majors.forEach(function (jurusan) {
                    jurusanSelect.insertAdjacentHTML(
                        'beforeend',
                        '<option value="' + jurusan + '" ' + (selectedValue === jurusan ? 'selected' : '') + '>' + jurusan + '</option>'
                    );
                });
                jurusanSelect.disabled = false;

                if (window.jQuery && window.jQuery(jurusanSelect).select2) {
                    window.jQuery(jurusanSelect).select2({ width: '100%', placeholder: '-- Pilih Jurusan --' });
                }
            };

            const renderKampus = function (campuses, selectedValue) {
                if (!kampusSelect) {
                    return;
                }

                kampusSelect.innerHTML = '<option value="">-- Pilih Sekolah/Kampus --</option>';

                if (!Array.isArray(campuses) || campuses.length === 0) {
                    kampusSelect.disabled = true;
                    if (window.jQuery && window.jQuery(kampusSelect).hasClass('select2-hidden-accessible')) { window.jQuery(kampusSelect).select2('destroy'); }
                    return;
                }

                campuses.forEach(function (kampus) {
                    kampusSelect.insertAdjacentHTML(
                        'beforeend',
                        '<option value="' + kampus + '" ' + (selectedValue === kampus ? 'selected' : '') + '>' + kampus + '</option>'
                    );
                });
                kampusSelect.disabled = false;

                if (window.jQuery && window.jQuery(kampusSelect).select2) {
                    window.jQuery(kampusSelect).select2({ width: '100%', placeholder: '-- Pilih Sekolah/Kampus --' });
                }
            };

            const fetchEducation = async function (url, params) {
                const query = new URLSearchParams(params || {});
                const response = await fetch(query.toString() ? (url + '?' + query.toString()) : url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        Accept: 'application/json'
                    }
                });

                if (!response.ok) {
                    throw new Error('Gagal memuat data pendidikan.');
                }

                const payload = await response.json();
                return Array.isArray(payload.data) ? payload.data : [];
            };

            const syncPendidikanHidden = function () {
                if (!pendidikanHidden || !tingkatanSelect || !jurusanSelect) {
                    return;
                }

                const kampusValue = isSchoolLevel()
                    ? (kampusManualInput ? kampusManualInput.value.trim() : '')
                    : (kampusSelect ? kampusSelect.value : '');

                if (tingkatanSelect.value && jurusanSelect.value && kampusValue) {
                    pendidikanHidden.value = tingkatanSelect.value + ' - ' + jurusanSelect.value + ' - ' + kampusValue;
                    showPendidikanFeedback(false);
                    return;
                }

                pendidikanHidden.value = '';
            };

            const hydrateEducationFromSavedValue = async function () {
                if (!pendidikanHidden || !tingkatanSelect) {
                    return;
                }

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
                    if (kampusManualInput) {
                        kampusManualInput.value = savedKampus;
                    }
                    syncPendidikanHidden();
                    return;
                }

                setCampusInputMode(false);
                const campuses = await fetchEducation(educationApi.campuses, { tingkatan: savedTingkatan, jurusan: savedJurusan });
                renderKampus(campuses, savedKampus);
            };

            (async function initPendidikanDropdown() {
                if (!tingkatanSelect || !jurusanSelect || !kampusSelect || !pendidikanHidden) {
                    return;
                }

                try {
                    const levels = await fetchEducation(educationApi.levels);
                    renderTingkatan(levels);
                    await hydrateEducationFromSavedValue();

                    if (window.jQuery) {
                        window.jQuery(jurusanSelect).on('select2:select', function () { jurusanSelect.dispatchEvent(new Event('change')); });
                        window.jQuery(kampusSelect).on('select2:select', function () { kampusSelect.dispatchEvent(new Event('change')); });
                    }

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
                    showPendidikanFeedback(true);
                    pendidikanFeedback.textContent = 'Data pendidikan tidak dapat dimuat. Coba refresh halaman.';
                    console.error(error);
                }
            })();

            const input = document.getElementById('fotoProfilInput');
            const preview = document.getElementById('fotoProfilPreview');
            const emptyText = document.getElementById('fotoProfilEmptyText');
            const metaText = document.getElementById('fotoProfilMeta');
            const resetButton = document.getElementById('fotoProfilReset');
            const toast = document.getElementById('fotoProfilToast');

            if (!input || !preview || !emptyText || !metaText || !resetButton || !toast) {
                return;
            }

            const initialPreviewSrc = preview.getAttribute('src') || '';
            const hasInitialImage = initialPreviewSrc !== '';
            let selectedPhotoValid = true;
            let toastTimer = null;

            window.addRepeater = function (type, namePrefix, placeholderJudul) {
                var container = document.getElementById('repeater_' + type);
                var index = Date.now();
                var currentYear = new Date().getFullYear();
                var optsMulai = '<option value="">-- Mulai --</option>';
                var optsSelesai = '<option value="">-- Selesai --</option><option value="Saat Ini">Saat Ini</option>';
                for (var y = currentYear; y >= 1990; y--) {
                    optsMulai += '<option value="' + y + '">' + y + '</option>';
                    optsSelesai += '<option value="' + y + '">' + y + '</option>';
                }

                var html = `
                    <div class="d-flex gap-2 mb-2 repeater-item">
                        <input type="text" name="${namePrefix}[${index}][judul]" class="form-control" placeholder="${placeholderJudul}">
                        <select name="${namePrefix}[${index}][tahun_mulai]" class="form-select" style="width: 130px; flex-shrink: 0;">${optsMulai}</select>
                        <div class="d-flex align-items-center">-</div>
                        <select name="${namePrefix}[${index}][tahun_selesai]" class="form-select" style="width: 130px; flex-shrink: 0;">${optsSelesai}</select>
                        <button type="button" class="btn btn-outline-danger px-2" onclick="this.closest('.repeater-item').remove()"><i class="ri-subtract-line"></i></button>
                    </div>
                `;
                container.insertAdjacentHTML('beforeend', html);
            };

            // Repeater Prestasi & Penghargaan (foto + link + deskripsi).
            window.addPrestasiPenghargaan = function () {
                var container = document.getElementById('repeater_prestasi_penghargaan');
                if (!container) return;
                var index = 'new_' + Date.now();
                var html = `
                    <div class="border rounded-3 p-2 mb-2 repeater-item">
                        <div class="row g-2 align-items-start">
                            <div class="col-md-3 text-center">
                                <img class="img-fluid rounded mb-1 pp-foto-preview" style="max-height:90px;object-fit:cover;display:none;">
                                <input type="file" name="prestasi_penghargaan[${index}][foto]" class="form-control form-control-sm pp-foto-input" accept=".jpg,.jpeg,.png,.webp">
                                <input type="hidden" name="prestasi_penghargaan[${index}][foto_existing]" value="">
                                <small class="text-muted">Foto kegiatan (maks 1MB)</small>
                            </div>
                            <div class="col-md-9">
                                <input type="url" name="prestasi_penghargaan[${index}][url_penghargaan]" class="form-control mb-2" placeholder="Link penghargaan (https://...)">
                                <textarea name="prestasi_penghargaan[${index}][deskripsi]" class="form-control" rows="2" placeholder="Deskripsi prestasi..."></textarea>
                            </div>
                        </div>
                        <div class="text-end mt-1">
                            <button type="button" class="btn btn-outline-danger btn-sm px-2" onclick="this.closest('.repeater-item').remove()"><i class="ri-subtract-line"></i> Hapus</button>
                        </div>
                    </div>
                `;
                container.insertAdjacentHTML('beforeend', html);
            };

            // Preview foto kegiatan saat dipilih (delegated).
            document.addEventListener('change', function (e) {
                if (!e.target || !e.target.classList || !e.target.classList.contains('pp-foto-input')) return;
                var file = e.target.files && e.target.files[0];
                var item = e.target.closest('.repeater-item');
                var preview = item ? item.querySelector('.pp-foto-preview') : null;
                if (file && preview) {
                    var reader = new FileReader();
                    reader.onload = function (ev) { preview.src = ev.target.result; preview.style.display = ''; };
                    reader.readAsDataURL(file);
                }
            });

            const showToast = function (message, type) {
                const level = type || 'info';
                const iconClass = level === 'error' ? 'ri-error-warning-line' : 'ri-information-line';
                toast.innerHTML = '<span class="toast-content"><i class="' + iconClass + '"></i><span>' + message + '</span></span>';
                toast.className = 'parja-mini-toast show ' + (type || 'info');

                if (toastTimer) {
                    clearTimeout(toastTimer);
                }

                toastTimer = setTimeout(function () {
                    toast.className = 'parja-mini-toast ' + (type || 'info');
                }, 2200);
            };

            const resetToInitialState = function () {
                input.value = '';
                selectedPhotoValid = true;
                metaText.textContent = '';
                metaText.className = 'small text-muted mt-1';

                if (hasInitialImage) {
                    preview.src = initialPreviewSrc;
                    preview.style.display = 'inline-block';
                    emptyText.style.display = 'none';
                } else {
                    preview.style.display = 'none';
                    preview.removeAttribute('src');
                    emptyText.style.display = 'inline';
                }
            };

            input.addEventListener('change', function (event) {
                const file = event.target.files && event.target.files[0] ? event.target.files[0] : null;

                if (!file) {
                    resetToInitialState();
                    return;
                }

                const objectUrl = URL.createObjectURL(file);
                const image = new Image();

                image.onload = function () {
                    const width = image.naturalWidth || 0;
                    const height = image.naturalHeight || 0;
                    const meetsIdeal = width >= 512 && height >= 512;

                    selectedPhotoValid = meetsIdeal;

                    metaText.textContent = meetsIdeal
                        ? 'Ukuran gambar: ' + width + 'x' + height + ' px (ideal)'
                        : 'Ukuran gambar: ' + width + 'x' + height + ' px. File ditolak karena di bawah 512x512 px.';

                    metaText.className = meetsIdeal ? 'small text-success mt-1' : 'small text-danger mt-1';

                    if (!meetsIdeal) {
                        showToast('File ditolak: ukuran minimal harus 512x512 px.', 'error');
                        input.value = '';
                        selectedPhotoValid = true;
                        preview.style.display = hasInitialImage ? 'inline-block' : 'none';
                        if (hasInitialImage) {
                            preview.src = initialPreviewSrc;
                            emptyText.style.display = 'none';
                        } else {
                            emptyText.style.display = 'inline';
                            preview.removeAttribute('src');
                        }
                    }

                    URL.revokeObjectURL(objectUrl);
                };

                image.onerror = function () {
                    metaText.textContent = 'File tidak dapat dipreview. Pastikan file gambar valid.';
                    metaText.className = 'small text-danger mt-1';
                    showToast('File tidak valid dan tidak dapat diproses.', 'error');
                    input.value = '';
                    selectedPhotoValid = true;
                    preview.style.display = hasInitialImage ? 'inline-block' : 'none';
                    if (hasInitialImage) {
                        preview.src = initialPreviewSrc;
                        emptyText.style.display = 'none';
                    } else {
                        emptyText.style.display = 'inline';
                        preview.removeAttribute('src');
                    }
                    URL.revokeObjectURL(objectUrl);
                };

                image.src = objectUrl;
                preview.src = objectUrl;
                preview.style.display = 'inline-block';
                emptyText.style.display = 'none';
            });

            resetButton.addEventListener('click', function () {
                resetToInitialState();
            });

            form.addEventListener('submit', function (event) {
                if (activeStep < totalSteps) {
                    event.preventDefault();
                    renderStep(activeStep + 1);
                    return;
                }

                if (!selectedPhotoValid) {
                    event.preventDefault();
                    metaText.textContent = 'Upload dibatalkan. Pilih foto dengan ukuran minimal 512x512 px.';
                    metaText.className = 'small text-danger mt-1';
                    showToast('Upload ditolak. Pilih foto minimal 512x512 px.', 'error');
                }
            });
        })();
    </script>
@endpush