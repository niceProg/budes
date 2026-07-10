@extends('layouts.app')

@section('title', 'Edit Lowongan | Admin - SMART Setjen DPR RI')
@section('content')
@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
@endpush
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
        padding: 3rem;
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
    .form-label {
        font-weight: 700;
        color: var(--text-main);
        margin-bottom: 0.5rem;
    }
    .form-control, .form-select {
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        padding: 0.75rem 1rem;
    }
    .form-control:focus, .form-select:focus {
        border-color: var(--gold-solid);
        box-shadow: 0 0 0 0.2rem rgba(176, 141, 72, 0.25);
    }
    .foto-preview {
        max-width: 200px;
        border-radius: 12px;
        margin-top: 0.5rem;
    }
</style>

<div class="content-wrapper">
    <div class="container-fluid px-md-5">
        <div class="page-title-area">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1">
                    <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Admin</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('lowongan.index') }}">Lowongan</a></li>
                    <li class="breadcrumb-item active">Edit Lowongan</li>
                </ol>
            </nav>
            <h1 class="page-title" style="font-size: 1.75rem;">Edit Lowongan</h1>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="ri-check-line me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="ri-error-warning-line me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="modern-card my-4">
            <form action="{{ route('lowongan.update', $data->id) }}" method="POST" enctype="multipart/form-data" id="form-lowongan-edit">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-8">
                        <div class="mb-4">
                            <label for="title" class="form-label">Judul Lowongan <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror"
                                   id="title" name="title" value="{{ old('title', $data->title) }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="detail" class="form-label">Detail Lowongan <span class="text-danger">*</span></label>
                            <div id="quill-editor" style="min-height: 350px; background: #fff; border-radius: 12px; border: 1px solid #e2e8f0;"></div>
                            <input type="hidden" name="detail" id="detail" value="{{ old('detail', $data->detail) }}">
                            @error('detail')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-4">
                            <label for="lowongan_satker_select" class="form-label">Satuan Kerja</label>
                            <select class="form-select @error('id_satker') is-invalid @enderror"
                                    id="lowongan_satker_select" name="id_satker"
                                    {{ (!empty($lockSatker) && !empty($lockedSatkerId)) ? 'disabled' : '' }}>
                                <option value="">Pilih Satuan Kerja (Opsional)</option>
                                @foreach($satker as $uk)
                                    <option value="{{ $uk->id }}" {{ old('id_satker', (!empty($lockSatker) && !empty($lockedSatkerId)) ? $lockedSatkerId : $data->id_satker) == $uk->id ? 'selected' : '' }}>
                                        {{ $uk->nama }}
                                    </option>
                                @endforeach
                            </select>
                            @if(!empty($lockSatker) && !empty($lockedSatkerId))
                                {{-- select disabled tidak terkirim, jadi pakai hidden input --}}
                                <input type="hidden" name="id_satker" value="{{ $lockedSatkerId }}">
                                <small class="text-muted d-block mt-1">Satuan kerja dikunci sesuai satuan kerja Anda.</small>
                            @endif
                            @error('id_satker')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="foto" class="form-label">Foto (JPG, min 200KB - maks 1MB)</label>
                            @if($data->foto)
                                <div class="mb-2">
                                    <img src="{{ file_url('lowongan/' . $data->foto) }}" alt="Foto" class="foto-preview" onerror="this.onerror=null; this.style.display='none'; this.nextElementSibling.style.display='block';">
                                    <div class="foto-preview d-flex align-items-center justify-content-center bg-light text-muted" style="font-size: 0.7rem; display: none;">
                                        <i class="ri-image-line"></i>
                                    </div>
                                    <p class="small text-muted mt-1">Foto saat ini</p>
                                </div>
                            @endif
                            <input type="file" class="form-control @error('foto') is-invalid @enderror"
                                   id="foto" name="foto" accept="image/jpeg,image/jpg,image/png" data-max-size-mb="1" data-min-size-kb="200">
                            <small class="text-muted">Kosongkan jika tidak ingin mengubah foto</small>
                            @error('foto')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="jumlah_posisi" class="form-label">Jumlah Posisi</label>
                            <input type="number" class="form-control @error('jumlah_posisi') is-invalid @enderror"
                                   id="jumlah_posisi" name="jumlah_posisi" value="{{ old('jumlah_posisi', $data->jumlah_posisi) }}" min="1">
                            @error('jumlah_posisi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        @php $oldJenis = (array) old('jenis_pendidikan', $data->jenisList()); @endphp
                        <div class="mb-4">
                            <label class="form-label">Jenis Pendidikan <span class="text-danger">*</span></label>
                            <div id="jenis-pendidikan-group" class="d-flex flex-column gap-2 p-3" style="border:1px solid #e2e8f0; border-radius:12px;">
                                <label class="d-flex align-items-start gap-2 m-0" style="cursor:pointer;">
                                    <input type="checkbox" class="form-check-input jenis-pendidikan-check" name="jenis_pendidikan[]" value="Magang" {{ in_array('Magang', $oldJenis) ? 'checked' : '' }} style="width:18px;height:18px;margin-top:2px;">
                                    <span><strong>Magang</strong><br><small class="text-muted">Mahasiswa (D3/D4/S1 ke atas) yang bisa melamar.</small></span>
                                </label>
                                <label class="d-flex align-items-start gap-2 m-0" style="cursor:pointer;">
                                    <input type="checkbox" class="form-check-input jenis-pendidikan-check" name="jenis_pendidikan[]" value="PKL" {{ in_array('PKL', $oldJenis) ? 'checked' : '' }} style="width:18px;height:18px;margin-top:2px;">
                                    <span><strong>PKL</strong><br><small class="text-muted">Hanya siswa SMK yang bisa melamar.</small></span>
                                </label>
                            </div>
                            <small class="text-muted d-block mt-1">Pilih minimal 1. Centang keduanya jika SMK & mahasiswa boleh melamar.</small>
                            @error('jenis_pendidikan')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="deadline" class="form-label">Deadline</label>
                            <input type="date" class="form-control @error('deadline') is-invalid @enderror"
                                   id="deadline" name="deadline" value="{{ old('deadline', $data->deadline ? \Carbon\Carbon::parse($data->deadline)->format('Y-m-d') : '') }}">
                            @error('deadline')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="tanggal_mulai" class="form-label">Tanggal Mulai Magang</label>
                            <input type="date" class="form-control @error('tanggal_mulai') is-invalid @enderror"
                                   id="tanggal_mulai" name="tanggal_mulai" value="{{ old('tanggal_mulai', $data->tanggal_mulai ? \Carbon\Carbon::parse($data->tanggal_mulai)->format('Y-m-d') : '') }}">
                            @error('tanggal_mulai')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted d-block mt-1">Opsional. Jika diisi, peserta tidak bisa memilih tanggal mulai sendiri.</small>
                        </div>

                        <div class="mb-4">
                            <label for="tanggal_selesai" class="form-label">Tanggal Selesai Magang</label>
                            <input type="date" class="form-control @error('tanggal_selesai') is-invalid @enderror"
                                   id="tanggal_selesai" name="tanggal_selesai" value="{{ old('tanggal_selesai', $data->tanggal_selesai ? \Carbon\Carbon::parse($data->tanggal_selesai)->format('Y-m-d') : '') }}">
                            @error('tanggal_selesai')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted d-block mt-1">Opsional. Jika diisi, peserta tidak bisa memilih tanggal selesai sendiri.</small>
                        </div>

                        <div class="mb-4">
                            <label for="is_publish" class="form-label d-flex align-items-center gap-2">
                                <input type="checkbox"
                                       class="form-check-input @error('is_publish') is-invalid @enderror"
                                       id="is_publish"
                                       name="is_publish"
                                       value="1"
                                       {{ old('is_publish', $data->is_publish) ? 'checked' : '' }}
                                       style="width: 20px; height: 20px; cursor: pointer;">
                                <span>Publish (Tampilkan di Halaman Awal)</span>
                            </label>
                            @error('is_publish')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <small class="text-muted d-block mt-1">Centang untuk menampilkan lowongan di halaman awal</small>
                        </div>

                        <div class="mb-4">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select @error('status') is-invalid @enderror"
                                    id="status" name="status">
                                <option value="1" {{ old('status', $data->status) == 1 ? 'selected' : '' }}>Aktif</option>
                                <option value="9" {{ old('status', $data->status) == 9 ? 'selected' : '' }}>Tidak Aktif</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted d-block mt-1">Aktif: bisa daftar | Tidak Aktif: tidak bisa daftar (tapi tetap muncul jika di-publish)</small>
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-2 mt-4">
                    <button type="submit" class="btn btn-gold">
                        <i class="ri-save-line"></i> Update Lowongan
                    </button>
                    <a href="{{ route('lowongan.index') }}" class="btn btn-outline-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="{{ asset('theme/admin-dashbyte/dist/lib/quill/quill.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@push('script')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var quillEditor = document.getElementById('quill-editor');
    var hiddenInput = document.getElementById('detail');
    var quill;

    if (quillEditor && hiddenInput) {
        quill = new Quill('#quill-editor', {
            theme: 'snow',
            placeholder: 'Masukkan detail lowongan di sini. Dapat berupa paragraf, poin-poin, bold, list, dll.',
            modules: {
                toolbar: [
                    [{ 'header': [1, 2, 3, false] }],
                    ['bold', 'italic', 'underline', 'strike'],
                    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                    [{ 'align': [] }],
                    ['link'],
                    ['clean']
                ]
            }
        });

        var initialContent = hiddenInput.value;
        if (initialContent) {
            quill.root.innerHTML = initialContent;
        }
    }

    // Validasi ukuran file foto (min 200 KB, maks 1 MB)
    const fotoInput = document.getElementById('foto');
    if (fotoInput) {
        const showRulePopupOnce = function () {
            if (fotoInput.dataset.rulePopupShown === '1') return;
            fotoInput.dataset.rulePopupShown = '1';
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'info',
                    title: 'Aturan Upload Foto',
                    html: 'Format file: <strong>JPG/JPEG/PNG</strong><br>Ukuran: <strong>minimal 200 KB</strong> dan <strong>maksimal 1 MB</strong>.',
                    confirmButtonText: 'Mengerti'
                });
            }
        };

        fotoInput.addEventListener('click', showRulePopupOnce);
        fotoInput.addEventListener('focus', showRulePopupOnce);

        fotoInput.addEventListener('change', function () {
            if (!this.files || !this.files[0]) return;
            const maxMb = parseFloat(this.getAttribute('data-max-size-mb') || '1');
            const minKb = parseFloat(this.getAttribute('data-min-size-kb') || '0');
            const sizeMb = this.files[0].size / 1024 / 1024;
            const sizeKb = this.files[0].size / 1024;
            if (minKb && sizeKb < minKb) {
                const message = 'Ukuran foto minimal ' + minKb + ' KB. File ini: ' + sizeKb.toFixed(0) + ' KB.';
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'error',
                        title: 'File terlalu kecil',
                        text: message
                    });
                }
                this.value = '';
                return;
            }
            if (sizeMb > maxMb) {
                const message = 'Ukuran foto maksimal ' + maxMb + ' MB. File ini: ' + sizeMb.toFixed(2) + ' MB.';
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'error',
                        title: 'File terlalu besar',
                        text: message
                    });
                } else {
                    alert(message);
                }
                this.value = '';
            }
        });
    }

    var form = document.querySelector('form[action*="lowongan"]');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            if (quill && hiddenInput) {
                hiddenInput.value = quill.root.innerHTML;
            }

            // Wajib pilih minimal 1 jenis pendidikan
            var jenisChecked = document.querySelectorAll('.jenis-pendidikan-check:checked').length;
            if (jenisChecked < 1) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Jenis Pendidikan Wajib Dipilih',
                    text: 'Pilih minimal 1 jenis pendidikan (Magang/PKL).',
                    confirmButtonColor: '#b08d48'
                });
                return;
            }

            Swal.fire({
                title: 'Simpan Perubahan?',
                text: 'Apakah Anda yakin ingin menyimpan perubahan pada lowongan ini?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Simpan',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#b08d48',
                cancelButtonColor: '#6b7280'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    }

    // Buka kalender saat field deadline, tanggal_mulai, tanggal_selesai diklik
    ['deadline', 'tanggal_mulai', 'tanggal_selesai'].forEach(function(id) {
        var input = document.getElementById(id);
        if (input && input.showPicker) {
            input.addEventListener('click', function() {
                this.showPicker();
            });
        }
    });

    // Validasi tanggal_selesai >= tanggal_mulai di sisi klien
    var tglMulai = document.getElementById('tanggal_mulai');
    var tglSelesai = document.getElementById('tanggal_selesai');
    if (tglMulai && tglSelesai) {
        tglMulai.addEventListener('change', function() {
            if (this.value) {
                tglSelesai.min = this.value;
                if (tglSelesai.value && tglSelesai.value < this.value) {
                    tglSelesai.value = '';
                }
            } else {
                tglSelesai.removeAttribute('min');
            }
        });
    }

    // Pencarian Satuan Kerja dengan Select2 (sama seperti di Materi)
    if (window.jQuery && $('#lowongan_satker_select').length && $.fn.select2) {
        $('#lowongan_satker_select').select2({
            placeholder: 'Pilih Satuan Kerja (Opsional)',
            allowClear: true,
            width: '100%'
        });
    }
});
</script>
@endpush
@endsection
