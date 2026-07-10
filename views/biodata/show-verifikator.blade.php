@extends('layouts.app')

@section('title', 'Detail Biodata Peserta | Verifikator - SMART Setjen DPR RI')
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
        /* Upload Box untuk Nota Dinas Selesai */
        .upload-box {
            border: 2px dashed #cbd5e1;
            border-radius: 12px;
            padding: 2rem;
            text-align: center;
            background: #f8fafc;
            transition: all 0.3s;
            cursor: pointer;
        }
        .upload-box:hover {
            border-color: var(--gold-solid);
            background: var(--gold-light);
            transform: translateY(-2px);
        }
        .upload-box i {
            font-size: 2.5rem;
            color: var(--gold-solid);
            margin-bottom: 0.5rem;
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

    <style>.btn-kembali-gold { background: #ffffff !important; border: 1.5px solid #b08d48 !important; color: #b08d48 !important; font-weight: 600; border-radius: 12px; padding: 0.5rem 1.25rem; display: inline-flex; align-items: center; gap: 0.5rem; transition: all 0.25s ease; box-shadow: 0 1px 3px rgba(0,0,0,0.04); }.btn-kembali-gold:hover { background: #fdf6e9 !important; color: #8e6d2f !important; border-color: #b08d48 !important; transform: translateX(-3px); }.btn-kembali-gold:focus, .btn-kembali-gold:active { outline: none !important; box-shadow: 0 0 0 2px rgba(176,141,72,0.35) !important; color: #8e6d2f !important; border-color: #b08d48 !important; }</style>
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <ol class="breadcrumb fs-sm mb-1">
                <li class="breadcrumb-item"><a href="#" class="text-decoration-none text-muted">Menu</a></li>
                <li class="breadcrumb-item active" aria-current="page">Detail Peserta</li>
            </ol>
            <h4 class="main-title mb-0">Profil Lengkap Peserta</h4>
        </div>
        @php
            $verifikatorBackUrl = request('from') === 'lulus' ? route('verifikator.biodata.lulus.index') : route('verifikator.biodata.index');
        @endphp
        <a href="{{ $verifikatorBackUrl }}" class="btn btn-sm flex-shrink-0 btn-kembali-gold text-decoration-none">
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

    @php
        $nodinIncomplete = ! $data->praMagangComplete();
    @endphp
    @if($nodinIncomplete)
    <div class="alert alert-warning border-0 shadow-sm d-flex align-items-center mb-4 cursor-pointer align-items-center" id="alert-nodin-belum-lengkap" role="button" tabindex="0" title="Klik untuk menuju ke bagian melengkapi file">
        <i class="ri-alert-line fs-4 me-3 text-warning"></i>
        <div class="flex-grow-1">
            <strong class="text-dark">Berkas Pra-Magang Belum Lengkap:</strong>
            <span class="text-muted">Masih ada berkas pra-magang yang belum diunggah atau belum disetujui.</span>
            <span class="d-block mt-1 small text-dark fw-600"><i class="ri-arrow-down-line me-1"></i> Klik alert ini untuk menuju ke bagian melengkapi file.</span>
        </div>
        <i class="ri-arrow-down-s-line fs-4 text-warning ms-2"></i>
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
                    </div>
                </div>

                <div class="divider-v d-none d-md-block"></div>

                <div class="flex-grow-1">
                    <div class="info-label text-gold-solid">Penempatan Satuan Kerja</div>
                    @if($data->status == 0)
                        <span class="text-muted small fw-bold italic"><i class="ri-close-circle-line"></i> Akun dinonaktifkan</span>
                        <div>
                            <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
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
                                <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                                <span class="fw-bold text-dark fs-5">{{ $data->satker->nama ?? '-' }}</span>
                                <span class="badge badge-gold-light">{{ $data->satker->kode ?? '-' }}</span>
                                    <button type="button" class="btn btn-sm btn-outline-primary rounded-pill ms-2 btn-edit-satker" data-peserta-id="{{ $data->id }}" data-current-unit="{{ $data->id_satker }}" data-current-mentor="{{ $data->id_mentor }}">
                                    <i class="ri-edit-line"></i> Ubah Unit
                                </button>
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
                            <div class="d-flex gap-2 align-items-center">
                                <button type="button" class="btn btn-gold-gradient btn-sm px-3 btn-edit-satker" data-can="1">Simpan Satuan Kerja</button>
                            </div>
                        @endif
                    @else
                        <div>
                            <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
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
        <div class="col-12">
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

    <div class="card" id="section-lengkapi-nodin">
        <div class="card-header">
            <h6><i class="ri-folder-shield-2-line"></i> Berkas Internal</h6>
        </div>
        <div class="card-body">
            @php
                $praDocs = $data->praMagangDocs();
                $pascaUnlocked = $data->pascaUnlocked();
                $tglSelesai = $data->tanggalSelesaiMagang();
                $canVerify = true; // halaman verifikator

                $urlNotaSelesai = $data->nota_dinas_selesai ? file_url('nota_dinas_selesai/' . $data->nota_dinas_selesai) : null;
                $urlLaporan = $data->laporan ? file_url('laporan/' . $data->laporan) : null;
                $urlNilai = $data->file_nilai ? file_url('file_nilai/' . $data->file_nilai) : null;
                $urlTestimoni = $data->file_testimoni ? file_url('file_testimoni/' . $data->file_testimoni) : null;
                $urlSertifikat = $data->sertifikat ? file_url('sertifikat/' . $data->sertifikat) : null;

                $canUploadSertifikat = $data->status != 1 && $data->status != 9 && $data->file_nilai && $data->laporan;
                $sertifikatError = '';
                if ($data->status == 1) { $sertifikatError = 'Status peserta masih belum mulai magang'; }
                elseif ($data->status == 9) { $sertifikatError = 'Peserta telah di-banned'; }
                elseif (! $data->file_nilai) { $sertifikatError = 'File nilai belum diunggah'; }
                elseif (! $data->laporan) { $sertifikatError = 'File laporan belum diunggah'; }
            @endphp

            {{-- Hidden forms (upload, generate, verify) --}}
            <form id="formUploadNodin" action="{{ route('biodata.upload.nodin', $data->id) }}" method="post" enctype="multipart/form-data" class="d-none">
                @csrf
                <input type="file" name="nodin_permohonan" id="inputNodinPermohonan" accept="application/pdf">
                <input type="file" name="nodin_2" id="inputNodin2" accept="application/pdf">
                <input type="file" name="nodin_1" id="inputNodin1" accept="application/pdf">
            </form>
            <form id="formGenerateNodin1" action="{{ route('biodata.generate.nodin1', $data->id) }}" method="post" class="d-none">@csrf</form>
            <form id="formGenerateNodin2" action="{{ route('biodata.generate.nodin2', $data->id) }}" method="post" class="d-none">@csrf</form>
            <form id="formGenerateNodin3" action="{{ route('biodata.generate.nodin3', $data->id) }}" method="post" class="d-none">@csrf</form>
            <form id="formVerifyNodin" action="{{ route('biodata.verify.nodin', $data->id) }}" method="post" class="d-none">
                @csrf
                <input type="hidden" name="type" id="verifyNodinType">
                <input type="hidden" name="action" id="verifyNodinAction">
                <input type="hidden" name="catatan" id="verifyNodinCatatan">
            </form>
            <form id="formUploadNotaSelesai" action="{{ route('verifikator.biodata.upload-nota-dinas-selesai', $data->id) }}" method="post" enctype="multipart/form-data" class="d-none">
                @csrf
                <input type="file" name="nota_dinas_selesai" id="inputNotaSelesai" accept="application/pdf">
            </form>
            <form id="formGenerateSertifikat" action="{{ route('biodata.generate.sertifikat', $data->id) }}" method="post" class="d-none">@csrf</form>

            <!-- Modal Preview Nodin 1 -->
            <div class="modal fade" id="nodin1PreviewModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-xl modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header bg-white">
                            <h5 class="modal-title fw-800"><i class="ri-file-search-line me-2"></i>Pratinjau Nodin ke Universitas/Instansi</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body p-0 bg-secondary bg-opacity-10" style="min-height:75vh;">
                            <iframe id="nodin1PreviewFrame" src="" style="width:100%; height:75vh; border:0;"></iframe>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tolak</button>
                            <button type="button" class="btn btn-gold-gradient" id="btnApproveNodin1"><i class="ri-check-line me-1"></i>Setujui & Simpan</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Preview Nodin 2 -->
            <div class="modal fade" id="nodin2PreviewModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-xl modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header bg-white">
                            <h5 class="modal-title fw-800"><i class="ri-file-search-line me-2"></i>Pratinjau Nodin Satuan Kerja</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body p-0 bg-secondary bg-opacity-10" style="min-height:75vh;">
                            <iframe id="nodin2PreviewFrame" src="" style="width:100%; height:75vh; border:0;"></iframe>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tolak</button>
                            <button type="button" class="btn btn-gold-gradient" id="btnApproveNodin2"><i class="ri-check-line me-1"></i>Setujui & Simpan</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Preview Nodin 3 (ke Universitas/Instansi) -->
            <div class="modal fade" id="nodin3PreviewModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-xl modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header bg-white">
                            <h5 class="modal-title fw-800"><i class="ri-file-search-line me-2"></i>Pratinjau Nodin ke Universitas/Instansi</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body p-0 bg-secondary bg-opacity-10" style="min-height:75vh;">
                            <iframe id="nodin3PreviewFrame" src="" style="width:100%; height:75vh; border:0;"></iframe>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tolak</button>
                            <button type="button" class="btn btn-gold-gradient" id="btnApproveNodin3"><i class="ri-check-line me-1"></i>Setujui & Simpan</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Preview Sertifikat -->
            <div class="modal fade" id="sertifikatPreviewModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-xl modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header bg-white">
                            <h5 class="modal-title fw-800"><i class="ri-file-search-line me-2"></i> Pratinjau Sertifikat</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body p-0 bg-secondary bg-opacity-10" style="min-height:75vh;">
                            <iframe id="sertifikatPreviewFrame" src="" style="width:100%; height:75vh; border:0;"></iframe>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tolak</button>
                            <button type="button" class="btn btn-gold-gradient" id="btnApproveSertifikat"><i class="ri-check-line me-1"></i>Setujui & Simpan</button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ============ PRA-MAGANG ============ --}}
            <div class="mb-3 d-flex align-items-center gap-2 flex-wrap">
                <span class="badge bg-dark">PRA-MAGANG <span class="text-warning">*</span></span>
                <span class="text-muted small">Wajib dilengkapi. Berkas yang diunggah peserta perlu diverifikasi.</span>
            </div>
            <div class="row">
                @foreach($praDocs as $doc)
                    @php
                        $key = $doc['key'];
                        $file = $doc['file'];
                        $url = $file ? (str_contains($file, $doc['storage_dir'].'/') ? file_url($file) : file_url($doc['storage_dir'].'/'.$file)) : null;
                        $meta = \Modules\Magang\App\Models\Magang\Peserta::praMagangStatusMeta($doc['status']);
                        $inputId = $key === 'nodin_permohonan' ? 'inputNodinPermohonan' : ($key === 'nodin_2' ? 'inputNodin2' : 'inputNodin1');
                        $genBtnId = $key === 'nodin_2' ? 'btnGenerateNodin2' : ($key === 'nodin_1' ? 'btnGenerateNodin1' : ($key === 'nodin_permohonan' ? 'btnGenerateNodin3' : null));
                    @endphp
                    <div class="col-md-4 mb-3">
                        <div class="doc-item flex-column align-items-stretch {{ $url ? 'nodin-filled' : 'nodin-empty' }}">
                            <div class="d-flex justify-content-between align-items-start mb-2 gap-2">
                                <span class="doc-label">{{ $doc['no'] }}. {{ $doc['label'] }}</span>
                                <span class="badge {{ $meta['class'] }}">{{ $meta['text'] }}</span>
                            </div>
                            <div class="btn-group">
                                @if($doc['can_generate'] && $genBtnId)
                                    <button type="button" class="btn btn-sm btn-outline-primary" id="{{ $genBtnId }}" title="Generate otomatis"><i class="ri-file-add-line"></i></button>
                                @endif
                                <button type="button" class="btn btn-sm btn-outline-success btn-upload-nodin" data-target-input="{{ $inputId }}" title="Unggah"><i class="ri-upload-cloud-line"></i></button>
                                @if($url)
                                    <button type="button" class="btn btn-sm btn-outline-primary btn-preview-file" data-url="{{ $url }}" data-title="{{ $doc['label'] }}"><i class="ri-eye-line"></i></button>
                                    <button type="button" class="btn btn-sm btn-outline-danger btn-delete-nodin" data-target-form="formDelete_{{ $key }}" title="Hapus"><i class="ri-delete-bin-line"></i></button>
                                    <form id="formDelete_{{ $key }}" action="{{ route('biodata.nodin.destroy', ['id' => $data->id, 'type' => $key]) }}" method="POST" class="d-none">@csrf @method('DELETE')</form>
                                @else
                                    <span class="text-muted small ms-2">File Tidak Tersedia</span>
                                @endif
                            </div>
                            @if($doc['uploaded_by'])
                                <div class="text-muted mt-2" style="font-size:0.72rem;"><i class="ri-user-line"></i> Diunggah: {{ $doc['uploaded_by'] === 'peserta' ? 'Peserta' : 'Verifikator' }}</div>
                            @endif
                            @if($doc['status'] === 9 && $doc['catatan'])
                                <div class="text-danger small mt-1"><i class="ri-information-line"></i> {{ $doc['catatan'] }}</div>
                            @endif
                            @if($canVerify && $doc['status'] === 1)
                                <div class="d-flex gap-2 mt-2">
                                    <button type="button" class="btn btn-sm btn-success flex-fill btn-verify-nodin" data-type="{{ $key }}" data-action="approve"><i class="ri-check-line"></i> Setujui</button>
                                    <button type="button" class="btn btn-sm btn-danger flex-fill btn-verify-nodin" data-type="{{ $key }}" data-action="reject"><i class="ri-close-line"></i> Tolak</button>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <hr class="my-4">

            {{-- ============ PASCA MAGANG ============ --}}
            <div class="mb-3 d-flex align-items-center gap-2 flex-wrap">
                <span class="badge bg-dark">PASCA MAGANG</span>
                @if($pascaUnlocked)
                    <span class="text-muted small">Berkas akhir magang.</span>
                @else
                    <span class="badge bg-secondary"><i class="ri-lock-2-line"></i> Terkunci — tersedia mulai H-7 sebelum tanggal selesai{{ $tglSelesai ? ' ('.$tglSelesai->copy()->subDays(7)->locale('id')->translatedFormat('d M Y').')' : '' }}</span>
                @endif
            </div>

            <div class="{{ $pascaUnlocked ? '' : 'opacity-50' }}" @if(! $pascaUnlocked) style="pointer-events:none;" aria-disabled="true" @endif>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <div class="doc-item {{ $urlNotaSelesai ? 'nodin-filled' : 'nodin-empty' }}">
                            <span class="doc-label">4. Nota Dinas Selesai</span>
                            <div class="btn-group">
                                <button type="button" class="btn btn-sm btn-outline-success btn-upload-nodin" data-target-input="inputNotaSelesai" title="Unggah"><i class="ri-upload-cloud-line"></i></button>
                                @if($urlNotaSelesai)
                                    <button type="button" class="btn btn-sm btn-outline-primary btn-preview-file" data-url="{{ $urlNotaSelesai }}" data-title="Nota Dinas Selesai"><i class="ri-eye-line"></i></button>
                                @else
                                    <span class="text-muted small ms-2">Belum diunggah</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="doc-item {{ $urlLaporan ? 'nodin-filled' : 'nodin-empty' }}">
                            <span class="doc-label">5. Laporan Akhir</span>
                            <div class="btn-group">
                                @if($urlLaporan)
                                    <button type="button" class="btn btn-sm btn-outline-primary btn-preview-file" data-url="{{ $urlLaporan }}" data-title="Laporan Akhir"><i class="ri-eye-line"></i></button>
                                @else
                                    <span class="text-muted small ms-2">Belum diunggah peserta</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="doc-item {{ $urlNilai ? 'nodin-filled' : 'nodin-empty' }}">
                            <span class="doc-label">6. Lembar Nilai</span>
                            <div class="btn-group">
                                @if($urlNilai)
                                    <button type="button" class="btn btn-sm btn-outline-primary btn-preview-file" data-url="{{ $urlNilai }}" data-title="Lembar Nilai"><i class="ri-eye-line"></i></button>
                                @else
                                    <span class="text-muted small ms-2">Belum diunggah peserta</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="doc-item {{ $urlTestimoni ? 'nodin-filled' : 'nodin-empty' }}">
                            <span class="doc-label">7. Testimoni</span>
                            <div class="btn-group">
                                @if($urlTestimoni)
                                    <button type="button" class="btn btn-sm btn-outline-primary btn-preview-file" data-url="{{ $urlTestimoni }}" data-title="Testimoni"><i class="ri-eye-line"></i></button>
                                @else
                                    <span class="text-muted small ms-2">Belum diunggah peserta</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="doc-item flex-column align-items-stretch {{ $urlSertifikat ? 'nodin-filled' : 'nodin-empty' }}">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="doc-label">8. Sertifikat</span>
                                @if($urlSertifikat)<span class="badge bg-success">Tersedia</span>@endif
                            </div>
                            @if($urlSertifikat)
                                <div class="d-flex gap-2">
                                    <button type="button" class="btn btn-sm btn-outline-primary btn-preview-file" data-url="{{ $urlSertifikat }}" data-title="Sertifikat"><i class="ri-eye-line"></i> Lihat</button>
                                    <a href="{{ $urlSertifikat }}" download class="btn btn-sm btn-light text-muted"><i class="ri-download-line"></i> Download</a>
                                </div>
                            @else
                                <div class="d-flex flex-wrap gap-2 align-items-center">
                                    <button type="button" class="btn btn-sm btn-gold-gradient btn-generate-sertifikat" data-can="{{ $canUploadSertifikat ? 1 : 0 }}" data-error-message="{{ $sertifikatError }}"><i class="ri-magic-line"></i> Generate</button>
                                    <input type="file" name="sertifikat" id="sertifikat" class="form-control form-control-sm" style="max-width:240px;" accept="application/pdf,.pdf" @if(! $canUploadSertifikat) disabled @endif>
                                    <button type="button" class="btn btn-sm btn-gold-gradient btn-unggah-sertifikat" data-can="{{ $canUploadSertifikat ? 1 : 0 }}" data-error-message="{{ $sertifikatError }}"><i class="ri-upload-cloud-line"></i> Unggah</button>
                                </div>
                                @if(! $canUploadSertifikat && $sertifikatError)
                                    <div class="text-danger small mt-2"><i class="ri-error-warning-line"></i> {{ $sertifikatError }}</div>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Hasil Penilaian (read-only untuk verifikator) --}}
                <div class="mt-2 p-3 bg-light rounded-3">
                    <div class="info-label text-gold-solid mb-1"><i class="ri-medal-line"></i> Hasil Penilaian (Skor Akhir)</div>
                    @if($data->nilai)
                        <div class="d-flex align-items-center gap-3">
                            <span class="display-6 fw-bold text-gold-solid mb-0">{{ $data->nilai }}</span>
                            <span class="badge bg-success"><i class="ri-checkbox-circle-fill"></i> Terverifikasi</span>
                        </div>
                    @else
                        <div class="text-muted">Belum dinilai.</div>
                    @endif
                </div>
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

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    $(document).ready(function() {
        const pesertaId = {{ $data->id }};
        const hasSatker = {{ !empty($data->id_satker) ? 'true' : 'false' }};

        // Klik alert nodin belum lengkap → scroll ke bagian melengkapi file
        $('#alert-nodin-belum-lengkap').on('click keydown', function(e) {
            if (e.type === 'keydown' && e.key !== 'Enter' && e.key !== ' ') return;
            e.preventDefault();
            var section = document.getElementById('section-lengkapi-nodin');
            if (section) {
                section.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });

        // Pilih file nodin → konfirmasi Unggah atau Batal (batal = clear file, tidak submit)
        function confirmNodinUpload(input, label) {
            if (!input.files || !input.files.length) return;
            const file = input.files[0];
            const maxMb = 1;
            const sizeMb = file.size / 1024 / 1024;
            if (sizeMb > maxMb) {
                const message = 'Ukuran file maksimal ' + maxMb + ' MB. File ini: ' + sizeMb.toFixed(2) + ' MB.';
                Swal.fire('File terlalu besar', message, 'error');
                input.value = '';
                return;
            }
            const fileName = file.name;
            Swal.fire({
                title: 'File dipilih',
                html: label + ': <strong>' + fileName + '</strong><br>Unggah file ini?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Unggah',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#10b981'
            }).then(function(result) {
                if (result.isConfirmed) {
                    document.getElementById('formUploadNodin').submit();
                } else {
                    input.value = '';
                }
            });
        }
        $('#inputNodinPermohonan').on('change', function() {
            confirmNodinUpload(this, 'Nodin Pusbangkom');
        });
        $('#inputNodin2').on('change', function() {
            confirmNodinUpload(this, 'Nodin Satuan Kerja');
        });
        $('#inputNodin1').on('change', function() {
            confirmNodinUpload(this, 'Nodin ke Universitas/Instansi');
        });

        // Verifikasi berkas pra-magang (setujui / tolak)
        $(document).on('click', '.btn-verify-nodin', function(e) {
            e.preventDefault();
            const type = $(this).data('type');
            const action = $(this).data('action');
            const form = document.getElementById('formVerifyNodin');
            if (!form) return;

            if (action === 'approve') {
                Swal.fire({
                    title: 'Setujui berkas ini?',
                    text: 'Berkas akan ditandai sebagai disetujui.',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, setujui',
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#10b981'
                }).then(function(result) {
                    if (result.isConfirmed) {
                        document.getElementById('verifyNodinType').value = type;
                        document.getElementById('verifyNodinAction').value = 'approve';
                        document.getElementById('verifyNodinCatatan').value = '';
                        form.submit();
                    }
                });
            } else {
                Swal.fire({
                    title: 'Tolak berkas ini?',
                    input: 'textarea',
                    inputLabel: 'Alasan penolakan (opsional)',
                    inputPlaceholder: 'Tuliskan alasan agar peserta dapat memperbaiki...',
                    showCancelButton: true,
                    confirmButtonText: 'Tolak Berkas',
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#dc2626'
                }).then(function(result) {
                    if (result.isConfirmed) {
                        document.getElementById('verifyNodinType').value = type;
                        document.getElementById('verifyNodinAction').value = 'reject';
                        document.getElementById('verifyNodinCatatan').value = result.value || '';
                        form.submit();
                    }
                });
            }
        });

        $('#inputNotaSelesai').on('change', function() {
            if (!this.files || !this.files.length) return;
            const file = this.files[0];
            const maxMb = 1;
            const sizeMb = file.size / 1024 / 1024;
            if (sizeMb > maxMb) {
                const message = 'Ukuran file maksimal ' + maxMb + ' MB. File ini: ' + sizeMb.toFixed(2) + ' MB.';
                Swal.fire('File terlalu besar', message, 'error');
                this.value = '';
                return;
            }
            const fileName = file.name;
            Swal.fire({
                title: 'File dipilih',
                html: 'Nota Dinas Selesai: <strong>' + fileName + '</strong><br>Unggah file ini?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Unggah',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#10b981'
            }).then(function(result) {
                if (result.isConfirmed) {
                    document.getElementById('formUploadNotaSelesai').submit();
                } else {
                    document.getElementById('inputNotaSelesai').value = '';
                }
            });
        });

        // Klik tombol unggah → buka file picker
        $(document).on('click', '.btn-upload-nodin', function(e) {
            e.preventDefault();
            const targetInputId = $(this).data('target-input');
            const input = document.getElementById(targetInputId);
            if (input) input.click();
        });
        // Generate Nodin 1 otomatis (preview modal)
        $('#btnGenerateNodin1').on('click', function(e) {
            e.preventDefault();
            if (!hasSatker) {
                return Swal.fire('Gagal', 'Anda harus memasukkan satuan kerja terlebih dahulu.', 'error');
            }
            const frame = document.getElementById('nodin1PreviewFrame');
            if (frame) {
                frame.src = '{{ route('biodata.preview.nodin1', $data->id) }}';
            }
            if (window.bootstrap && bootstrap.Modal) {
                const m = new bootstrap.Modal(document.getElementById('nodin1PreviewModal'));
                m.show();
            }
        });
        // Approve & simpan dari modal preview
        $('#btnApproveNodin1').on('click', function() {
            document.getElementById('formGenerateNodin1').submit();
        });

        // Generate Nodin 2 otomatis (preview modal)
        $('#btnGenerateNodin2').on('click', function(e) {
            e.preventDefault();
            if (!hasSatker) {
                return Swal.fire('Gagal', 'Anda harus memasukkan satuan kerja terlebih dahulu.', 'error');
            }
            const frame = document.getElementById('nodin2PreviewFrame');
            if (frame) {
                frame.src = '{{ route('biodata.preview.nodin2', $data->id) }}';
            }
            if (window.bootstrap && bootstrap.Modal) {
                const m = new bootstrap.Modal(document.getElementById('nodin2PreviewModal'));
                m.show();
            }
        });
        // Approve & simpan dari modal preview
        $('#btnApproveNodin2').on('click', function() {
            document.getElementById('formGenerateNodin2').submit();
        });

        // Generate Nodin 3 (ke Universitas/Instansi) otomatis (preview modal)
        $('#btnGenerateNodin3').on('click', function(e) {
            e.preventDefault();
            const frame = document.getElementById('nodin3PreviewFrame');
            if (frame) {
                frame.src = '{{ route('biodata.preview.nodin3', $data->id) }}';
            }
            if (window.bootstrap && bootstrap.Modal) {
                const m = new bootstrap.Modal(document.getElementById('nodin3PreviewModal'));
                m.show();
            }
        });
        // Approve & simpan dari modal preview
        $('#btnApproveNodin3').on('click', function() {
            document.getElementById('formGenerateNodin3').submit();
        });

        // Hapus nodin (kosongkan kembali setelah ter-upload)
        $(document).on('click', '.btn-delete-nodin', function(e) {
            e.preventDefault();
            const formId = $(this).data('target-form');
            Swal.fire({
                title: 'Hapus file nota dinas?',
                text: 'File akan dihapus dan status Nodin menjadi belum lengkap.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, hapus',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#dc2626'
            }).then(function(result) {
                if (result.isConfirmed) {
                    const form = document.getElementById(formId);
                    if (form) form.submit();
                }
            });
        });


        // Logic Tetap Sesuai Aslinya
        $(document).on('click', '.btn-edit-satker', function(e) {
            e.preventDefault();
            const currentUnitId = $(this).data('current-unit');
            const currentMentorId = $(this).data('current-mentor') || null;
            const satkerData = @json($satker);
            const satkerLabel = (uk) => `${uk.nama_satker || uk.nama || '-'} (${uk.kode_satker || uk.kode || '-'})`;

            // VERIFIKATOR: satuan kerja BISA diganti, bahkan jika sudah ditetapkan.
            // Jadi dropdown TIDAK pernah di-disable di sini.
            const hasSatker = currentUnitId && currentUnitId !== '';

            let satkerOptions = '<div class="mb-3"><label class="form-label fw-bold">Satuan Kerja</label><select id="swal-unit-kerja-select" class="form-select" style="width: 100%;">';
            satkerOptions += '<option value="">-- Pilih Satuan Kerja --</option>';
            satkerData.forEach(uk => {
                satkerOptions += `<option value="${uk.id}" ${uk.id == currentUnitId ? 'selected' : ''}>${satkerLabel(uk)}</option>`;
            });
            satkerOptions += '</select></div>';

            let mentorOptions = '<div class="mb-2"><label class="form-label fw-bold">Mentor</label><select id="swal-mentor-select" class="form-select" style="width: 100%;" disabled>';
            mentorOptions += '<option value="">-- Pilih Satuan Kerja terlebih dahulu --</option>';
            mentorOptions += '</select></div>';

            Swal.fire({
                title: 'Ubah Penempatan Unit / Mentor',
                html: '<div class="swal-unit-mentor">' + satkerOptions + mentorOptions + '</div>',
                width: '640px',
                showCancelButton: true,
                confirmButtonText: 'Simpan Perubahan',
                confirmButtonColor: '#b08d48',
                didOpen: () => {
                    const $popup = $(Swal.getPopup());
                    const unitSelect = document.getElementById('swal-unit-kerja-select');
                    const mentorSelect = document.getElementById('swal-mentor-select');

                    // Fix Select2 search inside SweetAlert2
                    $(document).on('select2:open', function() {
                        setTimeout(function() {
                            var field = document.querySelector('.select2-container--open .select2-search__field');
                            if (field) field.focus();
                        }, 0);
                    });

                    if (typeof $.fn.select2 !== 'undefined') {
                        $('#swal-unit-kerja-select').select2({
                            placeholder: 'Cari satuan kerja...',
                            allowClear: true,
                            dropdownParent: $popup
                        });
                    }

                    if (currentUnitId) {
                        loadMentors(currentUnitId, currentMentorId);
                    }

                    $('#swal-unit-kerja-select').on('change', function() {
                        const selectedUnitId = $(this).val();
                        if (selectedUnitId) {
                            loadMentors(selectedUnitId, null);
                        } else {
                            if ($('#swal-mentor-select').data('select2')) {
                                $('#swal-mentor-select').select2('destroy');
                            }
                            mentorSelect.innerHTML = '<option value="">-- Pilih Satuan Kerja terlebih dahulu --</option>';
                            mentorSelect.disabled = true;
                        }
                    });
                },
                preConfirm: () => {
                    const unitId = $('#swal-unit-kerja-select').val();
                    const mentorId = $('#swal-mentor-select').val();

                    if (!unitId) {
                        Swal.showValidationMessage('Satuan kerja harus dipilih');
                        return false;
                    }

                    if (!mentorId) {
                        Swal.showValidationMessage('Mentor wajib dipilih');
                        return false;
                    }

                    return { unitId: unitId, mentorId: mentorId };
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    $.post('{{ route('biodata.update.satker', $data->id) }}', {
                        _token: '{{ csrf_token() }}',
                        id_satker: result.value.unitId,
                        id_mentor: result.value.mentorId
                    }).done(() => location.reload());
                }
            });

            function loadMentors(unitKerjaId, selectedMentorId) {
                const mentorSelect = document.getElementById('swal-mentor-select');
                if (!mentorSelect) return;

                const $mentorSelect = $('#swal-mentor-select');
                if ($mentorSelect.data('select2')) {
                    $mentorSelect.select2('destroy');
                }

                mentorSelect.disabled = true;
                mentorSelect.innerHTML = '<option value="">Memuat mentor...</option>';

                $.ajax({
                    url: '{{ route('biodata.get.mentor') }}',
                    method: 'GET',
                    data: { id_satker: unitKerjaId },
                    headers: { 'X-Requested-With': 'XMLHttpRequest' },
                    success: function(response) {
                        if (response && response.success) {
                            mentorSelect.innerHTML = '<option value="">-- Pilih Mentor --</option>';
                            if (response.data && response.data.length > 0) {
                                response.data.forEach(function(mentor) {
                                    const sel = mentor.id == selectedMentorId ? 'selected' : '';
                                    mentorSelect.innerHTML += `<option value="${mentor.id}" ${sel}>${mentor.nama} (${mentor.nip})</option>`;
                                });
                            } else {
                                mentorSelect.innerHTML += '<option value="" disabled>Tidak ada mentor tersedia</option>';
                            }
                            mentorSelect.disabled = false;
                            if (typeof $.fn.select2 !== 'undefined') {
                                $mentorSelect.select2({
                                    placeholder: 'Cari mentor...',
                                    allowClear: true,
                                    dropdownParent: $(Swal.getPopup())
                                });
                            }
                        } else {
                            mentorSelect.innerHTML = '<option value="" disabled>Gagal memuat mentor</option>';
                            mentorSelect.disabled = false;
                        }
                    },
                    error: function(xhr) {
                        mentorSelect.innerHTML = '<option value="">Gagal memuat mentor (Error: ' + (xhr.status || 'Unknown') + ')</option>';
                        mentorSelect.disabled = false;
                    }
                });
            }
        });

        $('.btn-unggah-sertifikat').on('click', function() {
            const can = $(this).data('can');
            const errorMessage = $(this).data('error-message') || 'Laporan dan Nilai harus lengkap sebelum mengunggah sertifikat!';
            if(can == 0) return Swal.fire('Gagal', errorMessage, 'error');

            const file = $('#sertifikat')[0].files[0];
            if(!file) return Swal.fire('Peringatan', 'Pilih file sertifikat terlebih dahulu!', 'warning');

            const formData = new FormData();
            formData.append('_token', '{{ csrf_token() }}');
            formData.append('sertifikat', file);

            $.ajax({
                url: `/biodata/${pesertaId}/update-sertifikat`,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.success) {
                        Swal.fire('Berhasil', response.message || 'Sertifikat berhasil diunggah', 'success').then(() => location.reload());
                    } else {
                        Swal.fire('Gagal', response.message || 'Terjadi kesalahan saat unggah', 'error');
                    }
                },
                error: function(xhr) {
                    const message = xhr.responseJSON?.message || 'Terjadi kesalahan saat mengunggah sertifikat';
                    Swal.fire('Gagal', message, 'error');
                }
            });
        });

        // Generate Sertifikat (preview dulu, simpan setelah approve)
        $('.btn-generate-sertifikat').on('click', function() {
            const can = $(this).data('can');
            const errorMessage = $(this).data('error-message') || 'Tidak dapat generate sertifikat.';
            if (can == 0) return Swal.fire('Gagal', errorMessage, 'error');
            if (!hasSatker) {
                return Swal.fire('Gagal', 'Anda harus memasukkan satuan kerja terlebih dahulu.', 'error');
            }

            const frame = document.getElementById('sertifikatPreviewFrame');
            if (frame) frame.src = '{{ route('biodata.preview.sertifikat', $data->id) }}';

            if (window.bootstrap && bootstrap.Modal) {
                const m = new bootstrap.Modal(document.getElementById('sertifikatPreviewModal'));
                m.show();
            }
        });

        // Approve & simpan dari modal preview
        $('#btnApproveSertifikat').on('click', function() {
            const form = document.getElementById('formGenerateSertifikat');
            if (form) form.submit();
        });
    });

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
