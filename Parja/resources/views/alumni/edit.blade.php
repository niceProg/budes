@extends('parja::layouts.app')

@section('title', 'Edit Data Alumni')
@section('page-title', 'Data Akun Alumni')

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <ol class="breadcrumb fs-sm mb-1">
                <li class="breadcrumb-item">Data Akun Alumni</li>
                <li class="breadcrumb-item"><a href="{{ route('parja.alumni.index') }}">Daftar Alumni</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit Data Alumni</li>
            </ol>
            <h4 class="main-title mb-0">Edit Data Alumni</h4>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <form action="{{ route('parja.alumni.update', $alumni->id) }}" method="POST"
                        enctype="multipart/form-data" class="needs-validation" novalidate>
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="email" class="form-label fw-bold">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                                name="email" placeholder="Masukkan email alumni" value="{{ old('email', $alumni->email) }}"
                                required>
                            <div class="invalid-feedback">
                                {{ $errors->first('email') ?: 'Email wajib diisi dan harus valid.' }}</div>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label fw-bold">Password <span
                                    class="text-muted fw-normal">(Opsional — kosongkan jika tidak ingin
                                    mengubah)</span></label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                id="password" name="password" placeholder="Minimal 8 karakter">
                            <div class="invalid-feedback">{{ $errors->first('password') ?: 'Password minimal 8 karakter.' }}
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="nama" class="form-label fw-bold">Nama <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama"
                                name="nama" placeholder="Masukkan nama alumni" value="{{ old('nama', $alumni->nama) }}"
                                required>
                            <div class="invalid-feedback">{{ $errors->first('nama') ?: 'Nama wajib diisi.' }}</div>
                        </div>

                        <div class="mb-3">
                            <label for="asal_sekolah" class="form-label fw-bold">Asal Sekolah Parja (saat Parja) <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('asal_sekolah') is-invalid @enderror"
                                id="asal_sekolah" name="asal_sekolah" placeholder="Masukkan asal sekolah saat mengikuti Parja"
                                value="{{ old('asal_sekolah', $alumni->asal_sekolah) }}" required>
                            <div class="invalid-feedback">
                                {{ $errors->first('asal_sekolah') ?: 'Asal sekolah wajib diisi.' }}</div>
                        </div>

                        <div class="mb-3">
                            <label for="id_dapil" class="form-label fw-bold">Dapil <span
                                    class="text-danger">*</span></label>
                            @php
                                $currentDapilId = old('id_dapil', $alumni->id_dapil);

                                if ($currentDapilId !== null && $currentDapilId !== '') {
                                    $hasSelectedInOptions = $dapils->contains(function ($d) use ($currentDapilId) {
                                        return (string) ($d->id ?? '') === (string) $currentDapilId;
                                    });

                                    if (! $hasSelectedInOptions) {
                                        $currentDapilId = null;
                                    }
                                }

                                if (($currentDapilId === null || $currentDapilId === '') && ! empty($alumni->dapil)) {
                                    $matchedByName = $dapils->first(function ($d) use ($alumni) {
                                        return mb_strtolower(trim((string) ($d->dapil ?? ''))) === mb_strtolower(trim((string) $alumni->dapil));
                                    });

                                    if ($matchedByName) {
                                        $currentDapilId = $matchedByName->id;
                                    }
                                }
                            @endphp
                            <select class="form-select @error('id_dapil') is-invalid @enderror" id="id_dapil"
                                name="id_dapil" required>
                                <option value="">-- Pilih Dapil --</option>
                                @forelse($dapils as $dapil)
                                    <option value="{{ $dapil->id }}" {{ $currentDapilId == $dapil->id ? 'selected' : '' }}>
                                        {{ $dapil->dapil }}
                                    </option>
                                @empty
                                    <option value="" disabled>Tidak ada data Dapil</option>
                                @endforelse
                            </select>
                            <div class="invalid-feedback">{{ $errors->first('id_dapil') ?: 'Dapil wajib dipilih.' }}</div>
                        </div>

                        <div class="mb-3">
                            <label for="tahun_angkatan" class="form-label fw-bold">Tahun Angkatan <span
                                    class="text-danger">*</span></label>
                            @php
                                $selTahun = old('tahun_angkatan', $alumni->tahun_angkatan);
                                $curYear = (int) date('Y');
                            @endphp
                            <select class="form-select @error('tahun_angkatan') is-invalid @enderror"
                                id="tahun_angkatan" name="tahun_angkatan" required>
                                <option value="">-- Pilih Tahun --</option>
                                @for ($y = $curYear; $y >= 2008; $y--)
                                    <option value="{{ $y }}" {{ (string) $selTahun === (string) $y ? 'selected' : '' }}>{{ $y }}</option>
                                @endfor
                            </select>
                            <div class="invalid-feedback">
                                {{ $errors->first('tahun_angkatan') ?: 'Tahun angkatan wajib dipilih.' }}
                            </div>
                        </div>

                        {{-- No Anggota: tidak perlu diisi untuk angkatan di bawah tahun 2018
                             (field disembunyikan otomatis sesuai pilihan Tahun Angkatan). --}}
                        <div class="mb-3" id="no_anggota_wrap">
                            <label for="no_anggota" class="form-label fw-bold">No Anggota <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('no_anggota') is-invalid @enderror"
                                id="no_anggota" name="no_anggota" placeholder="Contoh: 2025/001"
                                value="{{ old('no_anggota', $alumni->no_anggota) }}" maxlength="50">
                            <div class="form-text">Format: [tahun]/[nomor]. Contoh: 2025/001. Tidak perlu diisi untuk angkatan di bawah tahun 2018.</div>
                            <div class="invalid-feedback">
                                {{ $errors->first('no_anggota') ?: 'No anggota wajib diisi. Format: 2025/001' }}</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Bukti Kelulusan (dari proses Sign Up Alumni)</label>
                            @if ($alumni->bukti_alumni)
                                <div>
                                    <a href="{{ route('parja.alumni.downloadBukti', $alumni->id) }}" target="_blank"
                                        class="btn btn-sm btn-outline-info">
                                        <i class="ri-file-download-line"></i> Lihat/Download Dokumen Lampiran
                                    </a>
                                </div>
                            @else
                                <div class="form-text text-muted">Alumni belum/tidak melampirkan berkas bukti saat mendaftar.
                                </div>
                            @endif
                        </div>

                        <div class="mb-3">
                            <label for="approval_status" class="form-label fw-bold">Status Verifikasi Akun <span
                                    class="text-danger">*</span></label>
                            <select class="form-select @error('approval_status') is-invalid @enderror" id="approval_status"
                                name="approval_status" required>
                                <option value="0" {{ old('approval_status', $alumni->approval_status) == 0 ? 'selected' : '' }}>⏳ Pending — Menunggu Verifikasi</option>
                                <option value="1" {{ old('approval_status', $alumni->approval_status) == 1 ? 'selected' : '' }}>✅ Disetujui — Akun Aktif</option>
                                <option value="2" {{ old('approval_status', $alumni->approval_status) == 2 ? 'selected' : '' }}>✗ Ditolak — Akun Nonaktif</option>
                            </select>
                            <div class="form-text">
                                Tinjau dulu <strong>Bukti Kelulusan</strong> di atas. Pilih <strong>Disetujui</strong> →
                                bukti L2 otomatis terverifikasi &amp; akun <strong>langsung aktif</strong>; alumni lalu
                                diminta melengkapi <strong>Profil (Layer 3)</strong> saat masuk portal.
                            </div>
                            <div class="invalid-feedback">
                                {{ $errors->first('approval_status') ?: 'Status verifikasi wajib dipilih.' }}</div>
                        </div>

                        <div class="mb-3" id="catatan-tolak-wrapper"
                            style="{{ old('approval_status', $alumni->approval_status) == 2 ? '' : 'display:none;' }}">
                            <label for="catatan_tolak" class="form-label fw-bold">Catatan Penolakan <span
                                    class="text-danger">*</span></label>
                            <textarea class="form-control @error('catatan_tolak') is-invalid @enderror" id="catatan_tolak"
                                name="catatan_tolak" rows="4"
                                placeholder="Tuliskan alasan penolakan yang akan ditampilkan kepada alumni...">{{ old('catatan_tolak', $alumni->catatan_tolak) }}</textarea>
                            <div class="form-text text-danger">Catatan ini akan ditampilkan langsung kepada alumni saat
                                mereka login.</div>
                            <div class="invalid-feedback">{{ $errors->first('catatan_tolak') }}</div>
                        </div>



                        <div class="mt-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="ri-save-line"></i> Perbarui
                            </button>
                            <a href="{{ route('parja.alumni.index') }}" class="btn btn-secondary ms-2">
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

        document.getElementById('no_anggota').addEventListener('input', function () {
            this.value = this.value.replace(/[^0-9\/]/g, '');
        });

        // Kebijakan: angkatan di bawah tahun 2018 tidak perlu mengisi No Anggota.
        (function () {
            var sel = document.getElementById('tahun_angkatan');
            var wrap = document.getElementById('no_anggota_wrap');
            if (!sel || !wrap) { return; }
            var input = document.getElementById('no_anggota');

            function toggleNoAnggota() {
                var year = parseInt(sel.value, 10);
                if (year && year < 2018) {
                    wrap.style.display = 'none';
                    if (input) { input.required = false; input.value = ''; }
                } else {
                    wrap.style.display = '';
                    if (input) { input.required = true; }
                }
            }

            sel.addEventListener('change', toggleNoAnggota);
            toggleNoAnggota();
        })();

        // Toggle catatan penolakan textarea visibility
        (function () {
            const approvalSelect = document.getElementById('approval_status');
            const catatanWrapper = document.getElementById('catatan-tolak-wrapper');
            const catatanTextarea = document.getElementById('catatan_tolak');

            function toggleCatatan() {
                const isRejected = approvalSelect.value === '2';
                catatanWrapper.style.display = isRejected ? '' : 'none';
                catatanTextarea.required = isRejected;
            }

            approvalSelect.addEventListener('change', toggleCatatan);
            toggleCatatan(); // run on page load
        })();
    </script>
@endpush