@extends('layouts.app')

@section('title', 'Detail Biodata Peserta | Pembimbing - SMART Setjen DPR RI')
@section('content')
    <style>
        :root {
            --primary-dark: #0f172a;
            --accent-gold: linear-gradient(135deg, #c5a059 0%, #8e6d2f 100%);
            --gold-solid: #b08d48;
            --gold-light: #fdfaf3;
            --text-main: #1e293b;
            --text-muted: #64748b;
            --bg-light: #f8fafc;
            --border-color: #e2e8f0;
        }

        body {
            background-color: var(--bg-light);
            color: var(--text-main);
        }

        /* Card Customization */
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(15, 23, 42, 0.05);
            margin-bottom: 1.5rem;
            overflow: hidden;
            background: #ffffff;
        }

        .card-header {
            background-color: #fff !important;
            border-bottom: 1px solid var(--border-color) !important;
            padding: 1.25rem;
        }

        .card-header h6 {
            color: var(--primary-dark) !important;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 0;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.025em;
        }

        .card-header h6 i {
            color: var(--gold-solid);
            font-size: 1.25rem;
        }

        /* Info Labels & Typography */
        .info-group {
            margin-bottom: 1.25rem;
        }

        .info-label {
            font-size: 0.725rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--text-muted);
            margin-bottom: 0.35rem;
            font-weight: 700;
        }

        .info-value {
            color: var(--primary-dark);
            font-weight: 600;
            font-size: 0.95rem;
            line-height: 1.5;
        }

        .text-gold-solid {
            color: var(--gold-solid) !important;
        }

        /* Buttons & Badges */
        .btn-gold-gradient {
            background: var(--accent-gold);
            border: none;
            color: white;
            font-weight: 600;
            transition: opacity 0.2s;
        }

        .btn-gold-gradient:hover {
            opacity: 0.9;
            color: white;
        }

        .badge {
            padding: 0.5rem 0.8rem;
            border-radius: 6px;
            font-weight: 600;
            font-size: 0.75rem;
        }

        .badge-gold-light {
            background-color: var(--gold-light);
            color: var(--gold-solid);
            border: 1px solid rgba(176, 141, 72, 0.2);
        }

        /* Status Strip */
        .status-strip {
            border-left: 4px solid var(--gold-solid);
        }

        .divider-v {
            width: 1px;
            height: 40px;
            background: var(--border-color);
            margin: 0 1.5rem;
        }

        .main-title {
            font-weight: 800;
            color: var(--primary-dark);
            letter-spacing: -0.02em;
        }

        /* Document Items */
        .doc-item {
            padding: 14px;
            background: var(--bg-light);
            border: 1px solid var(--border-color);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 10px;
            transition: all 0.2s ease-in-out;
        }

        .doc-item:hover {
            transform: translateY(-1px);
        }
        /* Nodin: border merah jika belum terisi, hijau jika sudah terisi */
        .doc-item.nodin-empty {
            border: 2px solid #dc2626;
            background: #fef2f2;
        }
        .doc-item.nodin-empty:hover {
            border-color: #b91c1c;
            background: #fee2e2;
        }
        .doc-item.nodin-filled {
            border: 2px solid #10b981;
            background: #f0fdf4;
        }
        .doc-item.nodin-filled:hover {
            border-color: #059669;
            background: #d1fae5;
        }
        .doc-item:not(.nodin-empty):not(.nodin-filled):hover {
            border-color: var(--gold-solid);
            background: #fff;
        }

        .doc-item .doc-label {
            color: var(--primary-dark);
            font-weight: 600;
            font-size: 0.85rem;
        }

        /* Modal Customization */
        .modal-header.bg-dark {
            background-color: var(--primary-dark) !important;
        }

        #alert-nodin-belum-lengkap:hover { background-color: rgba(255, 193, 7, 0.15); }
        #alert-nodin-belum-lengkap.cursor-pointer { cursor: pointer; }
        .card-header { display: flex; align-items: center; flex-wrap: wrap; gap: 0.5rem; }

        /* Upload Nodin - sama seperti halaman registrasi */
        .nodin-upload-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 25px;
            margin-top: 10px;
        }
        @media (max-width: 768px) {
            .nodin-upload-grid { grid-template-columns: 1fr; }
        }
        .nodin-upload-box {
            position: relative;
            border: 2.5px dashed #cbd5e1;
            padding: 25px 15px;
            border-radius: 25px;
            text-align: center;
            transition: var(--transition);
            background: #f8fafc;
            cursor: pointer;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 200px;
        }
        .nodin-upload-box:hover {
            border-color: var(--gold-solid);
            background: #fffbeb;
            transform: translateY(-3px);
        }
        .nodin-upload-box.has-file {
            border: 2.5px solid #10b981;
            background: #f0fdf4;
        }
        .nodin-upload-box .main-icon {
            font-size: 2.5rem;
            margin-bottom: 15px;
            color: #94a3b8;
            transition: var(--transition);
            display: block;
        }
        .nodin-upload-box.has-file .main-icon { display: none; }
        .nodin-upload-box .success-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            background: #10b981;
            color: white;
            width: 35px;
            height: 35px;
            border-radius: 50%;
            display: none;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            z-index: 5;
            box-shadow: 0 4px 10px rgba(16, 185, 129, 0.3);
        }
        .nodin-upload-box.has-file .success-badge { display: flex; }
        .nodin-upload-box .upload-text {
            font-weight: 700;
            font-size: 0.85rem;
            color: var(--primary-dark);
        }
        .nodin-upload-box .file-info {
            font-size: 0.7rem;
            color: var(--text-muted);
            margin-top: 5px;
        }
        .nodin-upload-box input[type="file"] {
            position: absolute;
            inset: 0;
            opacity: 0;
            z-index: 10;
            cursor: pointer;
        }
        .nodin-upload-box .preview-container {
            display: none;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }
        .nodin-upload-box.has-file .preview-container { display: flex; }
        .nodin-upload-box .preview-container .file-name-text {
            font-size: 0.8rem;
            font-weight: 700;
            color: #10b981;
            word-break: break-all;
            padding: 0 10px;
        }
    </style>

    @if(session('success'))
    <div class="alert alert-success border-0 shadow-sm mb-4" role="alert">
        <i class="ri-checkbox-circle-line me-2"></i>{{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div class="alert alert-danger border-0 shadow-sm mb-4" role="alert">
        <i class="ri-error-warning-line me-2"></i>{{ session('error') }}
    </div>
    @endif

    <style>.btn-kembali-gold { border: 2px solid #b08d48 !important; color: #b08d48 !important; background: transparent !important; transition: all 0.3s; }.btn-kembali-gold:hover { background: var(--accent-gold) !important; color: white !important; border-color: #b08d48 !important; }</style>
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <ol class="breadcrumb fs-sm mb-1">
                <li class="breadcrumb-item"><a href="#" class="text-decoration-none text-muted">Menu</a></li>
                <li class="breadcrumb-item active" aria-current="page">Detail Peserta</li>
            </ol>
            <h4 class="main-title mb-0">Profil Lengkap Peserta</h4>
        </div>
        <a href="{{ route('pembimbing.biodata.index') }}" class="btn btn-sm flex-shrink-0 btn-kembali-gold text-decoration-none">
            <i class="ri-arrow-left-line"></i> Kembali
        </a>
    </div>

    @if(isset($userExists) && !$userExists)
    <div class="alert alert-warning border-0 shadow-sm d-flex align-items-center mb-4" role="alert">
        <i class="ri-alert-line fs-4 me-3 text-warning"></i>
        <div>
            <strong class="text-dark">Akun Dinonaktifkan:</strong> <span class="text-muted">Peserta ini saat ini tidak memiliki akses ke sistem.</span>
        </div>
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="card status-strip">
        <div class="card-body py-3">
            <div class="d-flex align-items-center flex-wrap">
                <div class="me-4">
                    <div class="info-label text-gold-solid">Status Magang</div>
                    <div class="d-flex align-items-center gap-2 flex-wrap">
                        <div>
                            @if($data->status == 1)
                                <span class="badge bg-warning text-dark"><i class="ri-time-line"></i> Belum Mulai</span>
                            @elseif($data->status == 2)
                                <span class="badge bg-primary text-white"><i class="ri-loader-4-line"></i> Aktif Magang</span>
                            @elseif($data->status == 3 || $data->status == 0)
                                <span class="badge bg-success text-white"><i class="ri-checkbox-circle-line"></i> Selesai Magang</span>
                            @elseif($data->status == 9)
                                <span class="badge bg-danger text-white"><i class="ri-prohibited-line"></i> BANNED</span>
                            @else
                                <span class="badge bg-danger">ERROR</span>
                            @endif
                        </div>
                        {{-- Mentor hanya bisa lihat, tidak bisa banned --}}
                    </div>
                </div>

                <div class="divider-v d-none d-md-block"></div>

                <div class="flex-grow-1">
                    <div class="info-label text-gold-solid">Penempatan Satuan Kerja</div>
                    @if($data->status == 0)
                        <span class="text-muted small fw-bold italic"><i class="ri-close-circle-line"></i> Akun dinonaktifkan</span>
                        <div>
                            <div class="d-flex align-items-center gap-2 mb-2">
                            <span class="fw-bold text-dark fs-5">{{ $data->satker->nama ?? '-' }}</span>
                            <span class="badge badge-gold-light">{{ $data->satker->kode ?? '-' }}</span>
                            </div>
                            @if($data->id_mentor && $data->mentor)
                                <div class="mt-2">
                                    <div class="info-label small text-muted"><i class="ri-user-star-line"></i> Mentor:</div>
                                    <div class="info-value small fw-bold">{{ $data->mentor->nama ?? '-' }} <span class="text-muted">({{ $data->mentor->nip ?? '-' }})</span></div>
                                </div>
                            @elseif($data->id_mentor)
                                <div class="mt-2">
                                    <div class="info-label small text-muted"><i class="ri-user-star-line"></i> Mentor:</div>
                                    <div class="info-value small text-danger">Mentor tidak ditemukan (ID: {{ $data->id_mentor }})</div>
                                </div>
                            @endif
                        </div>
                    @elseif(in_array((int) $data->status, [1, 2], true))
                        @if($data->id_satker)
                            <div>
                                <div class="d-flex align-items-center gap-2 mb-2">
                                <span class="fw-bold text-dark fs-5">{{ $data->satker->nama ?? '-' }}</span>
                                <span class="badge badge-gold-light">{{ $data->satker->kode ?? '-' }}</span>
                                    {{-- Mentor hanya bisa lihat, tidak bisa edit --}}
                                </div>
                                @if($data->id_mentor && $data->mentor)
                                    <div class="mt-2">
                                        <div class="info-label small text-muted"><i class="ri-user-star-line"></i> Mentor:</div>
                                        <div class="info-value small fw-bold">{{ $data->mentor->nama ?? '-' }} <span class="text-muted">({{ $data->mentor->nip ?? '-' }})</span></div>
                                    </div>
                                @elseif($data->id_mentor)
                                    <div class="mt-2">
                                        <div class="info-label small text-muted"><i class="ri-user-star-line"></i> Mentor:</div>
                                        <div class="info-value small text-danger">Mentor tidak ditemukan (ID: {{ $data->id_mentor }})</div>
                                    </div>
                                @endif
                            </div>
                        @else
                            {{-- Mentor hanya bisa lihat, tidak bisa edit --}}
                        @endif
                    @else
                        <div>
                            <div class="d-flex align-items-center gap-2 mb-2">
                        <span class="fw-bold text-dark fs-5">{{ $data->satker->nama ?? '-' }} <small class="text-muted">({{ $data->satker->kode ?? '-' }})</small></span>
                            </div>
                            @if($data->id_mentor && $data->mentor)
                                <div class="mt-2">
                                    <div class="info-label small text-muted"><i class="ri-user-star-line"></i> Mentor:</div>
                                    <div class="info-value small fw-bold">{{ $data->mentor->nama ?? '-' }} <span class="text-muted">({{ $data->mentor->nip ?? '-' }})</span></div>
                                </div>
                            @elseif($data->id_mentor)
                                <div class="mt-2">
                                    <div class="info-label small text-muted"><i class="ri-user-star-line"></i> Mentor:</div>
                                    <div class="info-value small text-danger">Mentor tidak ditemukan (ID: {{ $data->id_mentor }})</div>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h6><i class="ri-user-3-line"></i> Informasi Pribadi</h6>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="info-group">
                                <div class="info-label">Nama Lengkap</div>
                                <div class="info-value fs-5 text-gold-solid">{{ ucwords($data->nama) }}</div>
                            </div>
                            <div class="info-group">
                                <div class="info-label">NIK</div>
                                <div class="info-value">{{ $data->nik }}</div>
                            </div>
                            <div class="info-group">
                                @php
                                    $labelNim = (strtoupper($data->lamaran->kategori ?? '') === 'PKL') ? 'NISN' : 'NIM';
                                @endphp
                                <div class="info-label">{{ $labelNim }}</div>
                                <div class="info-value">{{ $data->lamaran->nim_sn ?? $data->nim_sn ?? '-' }}</div>
                            </div>
                            <div class="info-group">
                                <div class="info-label">Email Address</div>
                                <div class="info-value">{{ $data->lamaran->email }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-group">
                                <div class="info-label">Gender</div>
                                <div class="info-value">{{ $data->lamaran->gender == 'L' ? 'Laki-Laki' : 'Perempuan' }}</div>
                            </div>
                            <div class="info-group">
                                <div class="info-label">Tempat & Tanggal Lahir</div>
                                <div class="info-value">
                                    {{ ucwords($data->lamaran->tempat_lahir) }},
                                    {{ \Carbon\Carbon::parse($data->lamaran->tanggal_lahir)->locale('id')->translatedFormat('j F Y') }}
                                </div>
                            </div>
                            <div class="info-group">
                                <div class="info-label">WhatsApp / HP</div>
                                <div class="info-value">{{ $data->lamaran->kontak }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h6><i class="ri-building-line"></i> Akademik & Institusi</h6>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="info-group">
                                <div class="info-label">Kategori</div>
                                <div class="info-value"><span class="badge badge-gold-light">
                                    @if(($data->lamaran->kategori ?? '') === 'Lainnya' && !empty($data->lamaran->kategori_lainnya))
                                        Lainnya - {{ ucwords($data->lamaran->kategori_lainnya) }}
                                    @else
                                        {{ $data->lamaran->kategori ?? '-' }}
                                    @endif
                                </span></div>
                            </div>
                            <div class="info-group">
                                <div class="info-label">Asal Institusi</div>
                                <div class="info-value">{{ ucwords($data->lamaran->instansi) }}</div>
                            </div>
                            <div class="info-group">
                                <div class="info-label">Jenis Lowongan</div>
                                <div class="info-value">
                                    @if($data->lamaran && $data->lamaran->id_lowongan)
                                        <span class="badge" style="background:#fef3c7; color:#92400e; border:1px solid #fde68a; font-weight:700;">{{ $data->lamaran->lowongan->title ?? 'Lowongan Khusus' }}</span>
                                    @else
                                        <span class="badge" style="background:#f1f5f9; color:#475569; border:1px solid #e2e8f0; font-weight:700;">Umum</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-group">
                                <div class="info-label">Periode Magang</div>
                                <div class="info-value">
                                    <span class="text-primary fw-bold">{{ \Carbon\Carbon::parse($data->lamaran->tanggal_mulai)->locale('id')->translatedFormat('d M Y') }}</span>
                                    <span class="mx-1 text-muted">s/d</span>
                                    <span class="text-primary fw-bold">{{ \Carbon\Carbon::parse($data->lamaran->tanggal_selesai)->locale('id')->translatedFormat('d M Y') }}</span>
                                </div>
                            </div>
                            <div class="info-group">
                                <div class="info-label">Program Studi</div>
                                <div class="info-value">
                                    {{ ucwords($data->lamaran->jurusan) }}
                                    ({{ optional($data->lamaran->pendidikan)->strata ?? '-' }})
                                </div>
                            </div>
                            <div class="info-group">
                                <div class="info-label">Minat</div>
                                <div class="info-value">{{ $data->lamaran->minat ?? '-' }}</div>
                            </div>
                            @if(($data->lamaran->kategori ?? '') !== 'PKL')
                                <div class="info-group">
                                    <div class="info-label">Fakultas</div>
                                    <div class="info-value">{{ $data->lamaran->fakultas ?? '-' }}</div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="mt-3 p-3 bg-light rounded-3">
                        <div class="info-label text-gold-solid mb-2"><i class="ri-user-star-line"></i> Pembimbing Lapangan / Guru</div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="info-label" style="font-size: 0.65rem;">Nama</div>
                                <div class="info-value small">{{ ucwords($data->lamaran->nama_guru) }}</div>
                            </div>
                            <div class="col-md-4">
                                <div class="info-label" style="font-size: 0.65rem;">Email</div>
                                <div class="info-value small">{{ $data->lamaran->email_guru }}</div>
                            </div>
                            <div class="col-md-4">
                                <div class="info-label" style="font-size: 0.65rem;">Kontak</div>
                                <div class="info-value small">{{ $data->lamaran->kontak_guru }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-dark">
                    <h6 class="text-white"><i class="ri-medal-line text-warning"></i> Hasil Penilaian</h6>
                </div>
                <div class="card-body text-center py-4">
                    <div class="info-label mb-3">Skor Akhir</div>
                    @if($data->nilai)
                        <div class="display-4 fw-bold text-gold-solid mb-2">{{ $data->nilai }}</div>
                        <div class="badge bg-success-soft text-success"><i class="ri-checkbox-circle-fill"></i> Terverifikasi</div>
                    @else
                        <div class="text-muted">-</div>
                    @endif
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h6><i class="ri-award-fill"></i> Sertifikat Digital</h6>
                </div>
                <div class="card-body text-center py-4">
                    @if($data->sertifikat)
                        <div class="d-grid gap-2">
                            <button type="button" class="btn btn-outline-primary fw-bold btn-preview-file" data-url="{{ file_url('sertifikat/' . $data->sertifikat) }}" data-title="Sertifikat">
                                <i class="ri-eye-line"></i> Lihat File
                            </button>
                            <a href="{{ file_url('sertifikat/' . $data->sertifikat) }}" download class="btn btn-light text-muted small fw-bold">
                                <i class="ri-download-line"></i> Download PDF
                            </a>
                        </div>
                    @else
                        <div class="text-muted">-</div>
                    @endif
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h6><i class="ri-folder-open-line"></i> Berkas Akhir</h6>
                </div>
                <div class="card-body p-3">
                    @php
                        $berkas_list = [
                            ['label' => 'Lembar Nilai', 'file' => $data->file_nilai, 'path' => 'file_nilai'],
                            ['label' => 'Laporan Akhir', 'file' => $data->laporan, 'path' => 'laporan'],
                            ['label' => 'Video/Testimoni', 'file' => $data->file_testimoni, 'path' => 'file_testimoni'],
                        ];
                    @endphp

                    @foreach($berkas_list as $b)
                    <div class="doc-item">
                        <div class="d-flex align-items-center">
                            <i class="ri-file-pdf-fill fs-4 text-danger me-2"></i>
                            <span class="doc-label">{{ $b['label'] }}</span>
                        </div>
                        @if($b['file'])
                            <button type="button" class="btn btn-sm btn-link text-gold-solid p-0 btn-preview-file" data-url="{{ file_url($b['path'].'/' . $b['file']) }}" data-title="{{ $b['label'] }}">
                                <i class="ri-external-link-line fs-5"></i>
                            </button>
                        @else
                            <span class="badge bg-light text-muted border-0">N/A</span>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h6><i class="ri-attachment-line"></i> Dokumen Administrasi Pendaftaran</h6>
        </div>
        <div class="card-body">
            <div class="row">
                @php
                    $docs = [
                        ['label' => 'Pas Foto', 'file' => $data->lamaran->pas_foto],
                        ['label' => 'Curriculum Vitae', 'file' => $data->lamaran->cv],
                        ['label' => 'Surat Pengantar', 'file' => $data->lamaran->surat],
                        ['label' => 'KTM / Kartu Pelajar', 'file' => $data->lamaran->ktm],
                        ['label' => 'Surat Rekomendasi', 'file' => $data->lamaran->surat_rekomendasi],
                        ['label' => 'Motivation Letter', 'file' => $data->lamaran->motivation],
                    ];
                @endphp
                @foreach($docs as $doc)
                <div class="col-md-4 mb-3">
                    <div class="doc-item">
                        <span class="doc-label">{{ $doc['label'] }}</span>
                        <div class="btn-group">
                            @if($doc['file'])
                                <button type="button" class="btn btn-sm btn-outline-primary js-preview-file" data-file-url="{{ file_url($doc['file']) }}" data-file-name="{{ $doc['label'] }}"><i class="ri-eye-line"></i></button>
                                <a href="{{ file_url($doc['file']) }}" download class="btn btn-sm btn-outline-secondary"><i class="ri-download-line"></i></a>
                            @else
                                <button type="button" class="btn btn-sm btn-outline-primary" disabled aria-disabled="true" title="Tidak ada file">
                                    <i class="ri-eye-line"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-secondary" disabled aria-disabled="true" title="Tidak ada file">
                                    <i class="ri-download-line"></i>
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="modal fade" id="filePreviewModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content overflow-hidden border-0 shadow-lg" style="border-radius: 20px;">
                <div class="modal-header border-0 bg-white px-4 py-3">
                    <h5 class="modal-title fw-800" id="filePreviewModalLabel">Pratinjau Dokumen</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0 d-flex justify-content-center align-items-center bg-secondary bg-opacity-10" style="min-height: 75vh;">
                    <iframe id="filePreviewFrame" src="" style="width: 100%; height: 75vh; border: 0; display: none;"></iframe>
                    <img id="filePreviewImage" src="" alt="Preview" style="max-width: 100%; max-height: 75vh; display: none; object-fit: contain;" />
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    // Preview File Script (support js-preview-file dan btn-preview-file)
    document.addEventListener('DOMContentLoaded', function () {
        const buttons = document.querySelectorAll('.js-preview-file, .btn-preview-file');
        const frame = document.getElementById('filePreviewFrame');
        const img = document.getElementById('filePreviewImage');
        const modalEl = document.getElementById('filePreviewModal');
        const labelEl = document.getElementById('filePreviewModalLabel');

        // Pastikan modal selalu berada langsung di body untuk z-index yang rapi
        document.addEventListener('show.bs.modal', function (event) {
            const m = event.target;
            if (m && m.parentElement !== document.body) {
                document.body.appendChild(m);
            }
        });

        buttons.forEach(btn => {
            btn.addEventListener('click', function () {
                // Support both data-file-url (js-preview-file) and data-url (btn-preview-file)
                const fileUrl = (this.getAttribute('data-file-url') || this.getAttribute('data-url') || '').trim();
                const fileName = (this.getAttribute('data-file-name') || this.getAttribute('data-title') || 'File');
                if (!fileUrl) return;

                const lowerUrl = fileUrl.toLowerCase();
                const isImage = /\.(jpg|jpeg|png|gif|webp)(\?|#|$)/.test(lowerUrl);

                if (labelEl) {
                    labelEl.innerHTML = '<i class="ri-file-search-line me-2"></i> ' + fileName;
                }

                if (!frame || !img) {
                    window.open(fileUrl, '_blank');
                    return;
                }

                // Reset state
                frame.src = '';
                frame.style.display = 'none';
                img.src = '';
                img.style.display = 'none';

                if (isImage) {
                    img.onerror = function () { window.open(fileUrl, '_blank'); };
                    img.src = fileUrl;
                    img.style.display = 'block';
                } else {
                    img.onerror = null;
                    frame.src = fileUrl;
                    frame.style.display = 'block';
                }

                if (window.bootstrap && bootstrap.Modal) {
                const modal = new bootstrap.Modal(modalEl);
                modal.show();
                } else {
                    window.open(fileUrl, '_blank');
                }
            });
        });

        if (modalEl) {
            modalEl.addEventListener('hidden.bs.modal', function () {
                if (frame) { frame.src = ''; frame.style.display = 'none'; }
                if (img) { img.src = ''; img.style.display = 'none'; img.onerror = null; }
            });
        }
    });
    </script>
@endsection
