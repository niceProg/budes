@extends('layouts.app')

@section('title', 'Pengaturan Keamanan | Admin - SMART Setjen DPR RI')
@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
    :root {
        --primary-dark: #0f172a;
        --accent-gold: linear-gradient(135deg, #c5a059 0%, #8e6d2f 100%);
        --gold-solid: #b08d48;
        --text-main: #1e293b;
        --text-muted: #64748b;
        --transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
    }
    .modern-card {
        background: rgba(255, 255, 255, 0.85);
        backdrop-filter: blur(20px);
        border-radius: 24px;
        padding: 2.5rem;
        box-shadow: 0 20px 40px rgba(0,0,0,0.06);
        border: 1px solid rgba(255, 255, 255, 0.7);
        position: relative;
        z-index: 1;
        overflow: hidden;
    }
    .modern-card::before {
        content: '';
        position: absolute;
        top: 0; left: 0;
        width: 100%; height: 6px;
        background: var(--accent-gold);
    }
    .btn-gold {
        background: var(--accent-gold);
        color: white;
        border: none;
        padding: 0.7rem 1.5rem;
        border-radius: 12px;
        font-weight: 700;
        transition: var(--transition);
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    .btn-gold:hover { transform: translateY(-2px); box-shadow: 0 8px 15px rgba(176, 141, 72, 0.3); color: white; }

    .mfa-toggle-box {
        display: flex;
        align-items: flex-start;
        gap: 16px;
        padding: 1.25rem 1.5rem;
        border: 1.5px solid #e2e8f0;
        border-radius: 16px;
        background: #f8fafc;
        transition: var(--transition);
    }
    .mfa-toggle-box.is-on {
        border-color: var(--gold-solid);
        background: #fffbeb;
    }
    .mfa-toggle-icon { font-size: 1.6rem; color: var(--gold-solid); margin-top: 2px; }
    .mfa-toggle-title { font-weight: 800; color: var(--primary-dark); font-size: 1.05rem; }
    .mfa-toggle-desc { color: var(--text-muted); font-size: 0.9rem; line-height: 1.55; margin-top: 4px; }

    /* Switch */
    .switch { position: relative; display: inline-block; width: 56px; height: 30px; flex-shrink: 0; }
    .switch input { opacity: 0; width: 0; height: 0; }
    .slider {
        position: absolute; cursor: pointer; inset: 0; background: #cbd5e1;
        transition: .3s; border-radius: 999px;
    }
    .slider:before {
        position: absolute; content: ""; height: 22px; width: 22px; left: 4px; bottom: 4px;
        background: white; transition: .3s; border-radius: 50%;
    }
    .switch input:checked + .slider { background: var(--gold-solid); }
    .switch input:checked + .slider:before { transform: translateX(26px); }

    html[data-skin="dark"] .modern-card { background: #0f172a; border-color: #1f2937; }
    html[data-skin="dark"] .mfa-toggle-box { background: #020617; border-color: #374151; }
    html[data-skin="dark"] .mfa-toggle-box.is-on { border-color: var(--gold-solid); background: #1f1605; }
    html[data-skin="dark"] .mfa-toggle-title { color: #ffffff; }
    html[data-skin="dark"] .mfa-toggle-desc { color: #cbd5e1; }
    html[data-skin="dark"] .page-title, html[data-skin="dark"] .breadcrumb-item.active { color: #ffffff; }
</style>

<div class="content-wrapper">
    <div class="container-fluid px-md-5">
        <div class="page-title-area">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1">
                    <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Admin</a></li>
                    <li class="breadcrumb-item active">Pengaturan</li>
                </ol>
            </nav>
            <h1 class="page-title" style="font-size: 1.75rem;">Pengaturan Sistem</h1>
            <p class="mb-0" style="font-size: 0.95rem; font-weight: 600; color: #1e293b;">Kelola pengaturan umum: buka/tutup pendaftaran &amp; kebijakan keamanan login internal.</p>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="ri-check-line me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="modern-card my-4">
            <form action="{{ route('security-settings.update') }}" method="POST" id="securityForm">
                @csrf
                @method('PUT')

                <h5 class="fw-800 mb-3" style="color: var(--primary-dark);"><i class="ri-door-open-line me-1"></i> Pendaftaran Magang</h5>

                <div class="mfa-toggle-box {{ $lowonganUmumEnabled ? 'is-on' : '' }} mb-3" id="umumBox">
                    <i class="ri-file-list-3-line mfa-toggle-icon"></i>
                    <div class="flex-grow-1">
                        <div class="mfa-toggle-title">Buka Pendaftaran Formulir Umum</div>
                        <div class="mfa-toggle-desc">
                            Jika dinonaktifkan, calon peserta <strong>tidak dapat mendaftar lewat "Formulir Umum"</strong>
                            (pendaftaran tanpa memilih lowongan tertentu) untuk sementara. Tautan "Formulir Umum" disembunyikan
                            dan akses langsung ke formulirnya ditolak.
                        </div>
                    </div>
                    <label class="switch">
                        <input type="checkbox" class="setting-toggle" data-box="umumBox" name="lowongan_umum_enabled" value="1" {{ $lowonganUmumEnabled ? 'checked' : '' }}>
                        <span class="slider"></span>
                    </label>
                </div>

                <div class="mfa-toggle-box {{ $lowonganKhususEnabled ? 'is-on' : '' }} mb-4" id="khususBox">
                    <i class="ri-briefcase-4-line mfa-toggle-icon"></i>
                    <div class="flex-grow-1">
                        <div class="mfa-toggle-title">Buka Pendaftaran Lowongan Khusus</div>
                        <div class="mfa-toggle-desc">
                            Jika dinonaktifkan, calon peserta <strong>tidak dapat melamar lowongan tertentu</strong> dari daftar
                            lowongan untuk sementara. Tombol "Lihat Detail" pada kartu lowongan dinonaktifkan dan akses langsung
                            ke formulir lowongan ditolak.
                        </div>
                    </div>
                    <label class="switch">
                        <input type="checkbox" class="setting-toggle" data-box="khususBox" name="lowongan_khusus_enabled" value="1" {{ $lowonganKhususEnabled ? 'checked' : '' }}>
                        <span class="slider"></span>
                    </label>
                </div>

                <h5 class="fw-800 mb-3 mt-2" style="color: var(--primary-dark);"><i class="ri-briefcase-line me-1"></i> Akses Menu</h5>

                <div class="mfa-toggle-box {{ $satkerLowonganAccessEnabled ? 'is-on' : '' }} mb-4" id="satkerLowonganBox">
                    <i class="ri-government-line mfa-toggle-icon"></i>
                    <div class="flex-grow-1">
                        <div class="mfa-toggle-title">Izinkan Satuan Kerja Mengakses Menu Lowongan</div>
                        <div class="mfa-toggle-desc">
                            Jika dinonaktifkan, role satuan kerja (<strong>admin, verifikator, dan atasan</strong> internal)
                            <strong>tidak dapat membuka menu Lowongan</strong> — menu disembunyikan dari sidebar dan akses
                            langsung ke halaman/aksi lowongan ditolak. <em>Superadmin tidak terpengaruh dan tetap dapat
                            mengelola lowongan.</em>
                        </div>
                    </div>
                    <label class="switch">
                        <input type="checkbox" class="setting-toggle" data-box="satkerLowonganBox" name="satker_lowongan_access_enabled" value="1" {{ $satkerLowonganAccessEnabled ? 'checked' : '' }}>
                        <span class="slider"></span>
                    </label>
                </div>

                @if (is_rootadmin())
                <h5 class="fw-800 mb-3 mt-2" style="color: var(--primary-dark);"><i class="ri-shield-keyhole-line me-1"></i> Keamanan</h5>

                <div class="mfa-toggle-box {{ $mfaRequired ? 'is-on' : '' }}" id="mfaBox">
                    <i class="ri-shield-keyhole-line mfa-toggle-icon"></i>
                    <div class="flex-grow-1">
                        <div class="mfa-toggle-title">Wajibkan MFA Authenticator untuk pengguna internal</div>
                        <div class="mfa-toggle-desc">
                            Jika diaktifkan, seluruh <strong>pengguna internal</strong> (admin login email &amp; pegawai SSO)
                            wajib melewati verifikasi saat login dan tidak dapat memakai opsi "ingat perangkat 30 hari" untuk melewati verifikasi.
                            <ul class="mb-0 mt-2">
                                <li>Admin email: tetap diminta verifikasi (email/authenticator) setiap login.</li>
                                <li>Pegawai SSO: jika belum mengaktifkan authenticator, akan diarahkan untuk mengaktifkannya lebih dulu sebelum bisa masuk.</li>
                            </ul>
                            <div class="mt-2"><em>Peserta dan guru tidak terpengaruh oleh pengaturan ini.</em></div>
                        </div>
                    </div>
                    <label class="switch">
                        <input type="checkbox" class="setting-toggle" data-box="mfaBox" id="mfaToggle" name="mfa_required_internal" value="1" {{ $mfaRequired ? 'checked' : '' }}>
                        <span class="slider"></span>
                    </label>
                </div>
                @endif

                <h5 class="fw-800 mb-2 mt-2" style="color: var(--primary-dark);"><i class="ri-links-line me-1"></i> Tautan Template Berkas Pendukung</h5>
                <p class="mb-3" style="font-size: 0.9rem; color: var(--text-muted);">
                    Tempelkan tautan (URL) template/contoh berkas yang akan ditampilkan di form pendaftaran pada bagian
                    <strong>"3. Berkas Pendukung"</strong>. <strong>Kosongkan</strong> bila tidak ingin menampilkan tautan pada berkas tersebut.
                </p>

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label for="template_cv_url" class="form-label fw-bold" style="color: var(--primary-dark);">Template Curriculum Vitae (CV)</label>
                        <input type="url" class="form-control" id="template_cv_url" name="template_cv_url"
                               value="{{ old('template_cv_url', $templateLinks['cv'] ?? '') }}"
                               placeholder="https://contoh.com/template-cv.pdf">
                        @error('template_cv_url')<small class="text-danger d-block mt-1">{{ $message }}</small>@enderror
                    </div>
                    <div class="col-md-6">
                        <label for="template_surat_pengantar_url" class="form-label fw-bold" style="color: var(--primary-dark);">Contoh Surat Pengantar Institusi</label>
                        <input type="url" class="form-control" id="template_surat_pengantar_url" name="template_surat_pengantar_url"
                               value="{{ old('template_surat_pengantar_url', $templateLinks['surat_pengantar'] ?? '') }}"
                               placeholder="https://contoh.com/surat-pengantar.pdf">
                        @error('template_surat_pengantar_url')<small class="text-danger d-block mt-1">{{ $message }}</small>@enderror
                    </div>
                    <div class="col-md-6">
                        <label for="template_surat_rekomendasi_url" class="form-label fw-bold" style="color: var(--primary-dark);">Contoh Surat Rekomendasi</label>
                        <input type="url" class="form-control" id="template_surat_rekomendasi_url" name="template_surat_rekomendasi_url"
                               value="{{ old('template_surat_rekomendasi_url', $templateLinks['surat_rekomendasi'] ?? '') }}"
                               placeholder="https://contoh.com/surat-rekomendasi.pdf">
                        @error('template_surat_rekomendasi_url')<small class="text-danger d-block mt-1">{{ $message }}</small>@enderror
                    </div>
                    <div class="col-md-6">
                        <label for="template_proposal_url" class="form-label fw-bold" style="color: var(--primary-dark);">Contoh Proposal Magang / Motivation Letter</label>
                        <input type="url" class="form-control" id="template_proposal_url" name="template_proposal_url"
                               value="{{ old('template_proposal_url', $templateLinks['proposal'] ?? '') }}"
                               placeholder="https://contoh.com/proposal-magang.pdf">
                        @error('template_proposal_url')<small class="text-danger d-block mt-1">{{ $message }}</small>@enderror
                    </div>
                </div>

                <h5 class="fw-800 mb-2 mt-2" style="color: var(--primary-dark);"><i class="ri-quill-pen-line me-1"></i> Penandatangan Nota Dinas ke Universitas/Instansi</h5>
                <p class="mb-3" style="font-size: 0.9rem; color: var(--text-muted);">
                    Nama & jabatan penanda tangan elektronik (TTE) pada dokumen <strong>"Nodin ke Universitas/Instansi"</strong> yang digenerate otomatis.
                    <strong>Kosongkan</strong> untuk mengembalikan ke nilai default.
                </p>

                <div class="row g-3 mb-4">
                    <div class="col-md-8">
                        <label for="nodin3_signer_title" class="form-label fw-bold" style="color: var(--primary-dark);">Jabatan Penandatangan</label>
                        <input type="text" class="form-control" id="nodin3_signer_title" name="nodin3_signer_title"
                               value="{{ old('nodin3_signer_title', $nodin3Signer['title'] ?? '') }}"
                               placeholder="{{ \Modules\Magang\App\Models\SiteSetting::DEFAULT_NODIN3_SIGNER_TITLE }}">
                        @error('nodin3_signer_title')<small class="text-danger d-block mt-1">{{ $message }}</small>@enderror
                    </div>
                    <div class="col-md-4">
                        <label for="nodin3_signer_name" class="form-label fw-bold" style="color: var(--primary-dark);">Nama Penandatangan</label>
                        <input type="text" class="form-control" id="nodin3_signer_name" name="nodin3_signer_name"
                               value="{{ old('nodin3_signer_name', $nodin3Signer['name'] ?? '') }}"
                               placeholder="{{ \Modules\Magang\App\Models\SiteSetting::DEFAULT_NODIN3_SIGNER_NAME }}">
                        @error('nodin3_signer_name')<small class="text-danger d-block mt-1">{{ $message }}</small>@enderror
                    </div>
                </div>

                <div class="d-flex gap-2 mt-4">
                    <button type="button" id="btnSimpanSecurity" class="btn btn-gold">
                        <i class="ri-save-line"></i> Simpan Perubahan
                    </button>
                    <a href="{{ route('admin.index') }}" class="btn btn-outline-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>

@push('script')
<script>
document.addEventListener('DOMContentLoaded', function() {
    var form = document.getElementById('securityForm');
    var btn = document.getElementById('btnSimpanSecurity');

    // Sinkronkan tampilan kotak (highlight) dengan status setiap toggle
    document.querySelectorAll('.setting-toggle').forEach(function(toggle) {
        var box = document.getElementById(toggle.getAttribute('data-box'));
        if (!box) return;
        toggle.addEventListener('change', function() {
            box.classList.toggle('is-on', toggle.checked);
        });
    });

    if (btn && form) {
        btn.addEventListener('click', function() {
            Swal.fire({
                title: 'Simpan Pengaturan?',
                text: 'Perubahan pengaturan sistem akan langsung diterapkan.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#b08d48',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Simpan',
                cancelButtonText: 'Batal'
            }).then(function(result) {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    }
});
</script>
@endpush
@endsection
