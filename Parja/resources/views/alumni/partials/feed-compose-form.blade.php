<style>
    .feed-compose-card {
        border-radius: 18px;
        border: 1px solid var(--parja-border);
        background: #fff;
        padding: 20px;
        box-shadow: 0 8px 18px rgba(65, 23, 75, 0.07);
        margin-bottom: 20px;
    }

    .feed-compose-title {
        font-weight: 700;
        color: var(--parja-purple);
        font-size: 1rem;
        margin-bottom: 14px;
        display: flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        user-select: none;
    }

    .feed-compose-title i.toggle-icon {
        margin-left: auto;
        transition: transform 0.25s;
        color: var(--parja-muted);
    }

    .feed-compose-title.collapsed i.toggle-icon {
        transform: rotate(-90deg);
    }

    .foto-upload-preview {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
        margin-top: 10px;
    }

    .foto-upload-preview img {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border-radius: 8px;
        border: 1px solid var(--parja-border);
    }
</style>

{{-- Form Buat Artikel — alumni login lewat guard keycloak-external (BUKAN
     guard default keycloak), jadi gating harus pakai guard itu agar form
     compose muncul di profil/home/feed alumni. --}}
@auth('keycloak-external')
@php
    // Batas posting harian (gabungan internal + public). Nilai limit ambil dari
    // controller agar tetap satu sumber kebenaran dengan validasi di store().
    $__postLimit  = \Modules\Parja\App\Http\Controllers\AlumniFeedController::DAILY_POST_LIMIT;
    $__alumniId   = $alumni->id
        ?? \Modules\Parja\App\Models\AlumniModel::where('keycloak_id', auth()->guard('keycloak-external')->id())->value('id');
    $__postUsed   = $__alumniId
        ? \Modules\Parja\App\Models\AlumniPostModel::where('alumni_id', $__alumniId)->whereDate('created_at', today())->count()
        : 0;
    $__postSisa     = max(0, $__postLimit - $__postUsed);
    $__limitReached = $__postSisa <= 0;
@endphp
<div class="feed-compose-card mt-3 mb-4">
    <div class="feed-compose-title" id="composeToggle">
        <i class="ri-article-line" style="color: var(--parja-magenta);"></i>
        Tulis Artikel Alumni
        <span class="badge rounded-pill ms-2 fw-semibold"
            style="font-size:0.72rem;background:{{ $__limitReached ? 'rgba(220,53,69,0.12)' : 'rgba(65,23,75,0.08)' }};color:{{ $__limitReached ? '#dc3545' : 'var(--parja-purple)' }};">
            Sisa {{ $__postSisa }}/{{ $__postLimit }} hari ini
        </span>
        <i class="ri-arrow-down-s-line toggle-icon"></i>
    </div>
    <div id="composeBody">
        <form action="{{ route('alumni.feed.store') }}" method="POST" enctype="multipart/form-data" id="artikelForm">
            @csrf
            <input type="hidden" name="tipe" value="artikel">

            {{-- Judul --}}
            <div class="mb-3">
                <label class="form-label fw-semibold" style="color: var(--parja-purple);">Judul Artikel <span class="text-danger">*</span></label>
                <input type="text" name="judul" class="form-control @error('judul') is-invalid @enderror"
                    placeholder="Masukkan judul artikel..." value="{{ old('judul') }}" required>
                @error('judul')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Topik Artikel --}}
            <div class="mb-3">
                <label class="form-label fw-semibold" style="color: var(--parja-purple);">Topik Artikel <span class="text-muted fw-normal">(Opsional)</span></label>
                <select name="kategori_artikel" id="kategori_artikel"
                    class="form-select @error('kategori_artikel') is-invalid @enderror">
                    <option value="">-- Pilih Topik / Kategori --</option>
                    @php
                        $bidangList = ['Kesehatan', 'Pendidikan', 'Teknologi Informasi', 'Hukum', 'Ekonomi',
                                       'Politik', 'Sosial Budaya', 'Lingkungan Hidup', 'Olahraga', 'Seni & Budaya'];
                    @endphp
                    @foreach ($bidangList as $bidang)
                        <option value="{{ $bidang }}" {{ old('kategori_artikel') === $bidang ? 'selected' : '' }}>
                            {{ $bidang }}
                        </option>
                    @endforeach
                </select>
                @error('kategori_artikel')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <small class="text-muted">Sesuai bidang ketertarikan yang terdaftar di profil alumni.</small>
            </div>

            {{-- Konten --}}
            <div class="mb-3">
                <label class="form-label fw-semibold" style="color: var(--parja-purple);">Isi Artikel <span class="text-danger">*</span></label>
                <textarea name="konten" rows="5" class="form-control @error('konten') is-invalid @enderror"
                    placeholder="Tulis isi artikel di sini...">{{ old('konten') }}</textarea>
                @error('konten')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <small class="text-muted">Maks. 5.000 karakter</small>
            </div>

            {{-- Upload Foto --}}
            <div class="mb-3">
                <label class="form-label fw-semibold" style="color: var(--parja-purple);">Foto Artikel <small class="text-muted fw-normal">(opsional, maks. 5 foto, @1MB/foto)</small></label>
                @error('foto')
                    <div class="text-danger small mb-1">{{ $message }}</div>
                @enderror
                @error('foto.*')
                    <div class="text-danger small mb-1">{{ $message }}</div>
                @enderror

                <div id="fotoInputsContainer"></div>
                <div id="fotoPreviewGrid" class="foto-upload-preview"></div>
                <button type="button" id="btnAddFoto" class="btn btn-sm btn-outline-secondary mt-2 rounded-pill">
                    <i class="ri-image-add-line"></i> Tambah Foto
                </button>
                <span id="fotoCountLabel" class="text-muted small ms-2">0 / 5 foto</span>
                <input type="file" id="fotoTrigger" accept="image/*" style="display:none;">
            </div>

            @if ($errors->any())
                <div class="alert alert-danger rounded-3 py-2 px-3 mb-3" style="font-size:0.88rem;">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if ($__limitReached)
                <div class="alert alert-warning rounded-3 py-2 px-3 mb-3" style="font-size:0.88rem;">
                    <i class="ri-time-line"></i> Batas posting tercapai: maksimal {{ $__postLimit }} postingan per hari. Silakan lanjutkan besok.
                </div>
            @endif

            <div class="d-flex align-items-center gap-2 mt-2">
                <button type="submit" class="btn btn-sm fw-semibold text-white px-4"
                    style="background: var(--parja-magenta); border-radius: 50px;"
                    {{ $__limitReached ? 'disabled' : '' }}>
                    <i class="ri-send-plane-line"></i> Publikasikan ke Feed Alumni
                </button>
                <small class="text-muted">Artikel langsung terbit ke Parja Alumni. Pengaturan privasi ke Public bisa dilakukan di menu My Profile.</small>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const composeToggle = document.getElementById('composeToggle');
        const composeBody   = document.getElementById('composeBody');
        let composeOpen     = {{ $errors->any() ? 'true' : 'true' }};

        function setCompose(open) {
            if (!composeBody || !composeToggle) return;
            composeOpen = open;
            composeBody.style.display = open ? 'block' : 'none';
            composeToggle.classList.toggle('collapsed', !open);
        }

        if (composeToggle) {
            composeToggle.addEventListener('click', () => setCompose(!composeOpen));
            setCompose(composeOpen);
        }

        // Helper global: dipakai tombol "Buat Post" untuk membuka form compose
        // inline (di profil), lalu scroll & fokus ke judul — bukan pindah halaman.
        window.openAlumniComposeForm = function () {
            setCompose(true);
            var card = document.querySelector('.feed-compose-card');
            if (card) {
                card.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
            var judul = document.querySelector('#artikelForm [name="judul"]');
            if (judul) {
                setTimeout(function () { judul.focus(); }, 350);
            }
        };

        const MAX_FOTO = 5;
        const fotoFiles = []; 
        const btnAddFoto          = document.getElementById('btnAddFoto');
        const fotoTrigger         = document.getElementById('fotoTrigger');
        const fotoPreviewGrid     = document.getElementById('fotoPreviewGrid');
        const fotoInputsContainer = document.getElementById('fotoInputsContainer');
        const fotoCountLabel      = document.getElementById('fotoCountLabel');
        const artikelForm         = document.getElementById('artikelForm');

        if (!fotoInputsContainer) return;

        const realFotoInput = document.createElement('input');
        realFotoInput.type     = 'file';
        realFotoInput.name     = 'foto[]';
        realFotoInput.multiple = true;
        realFotoInput.style.display = 'none';
        fotoInputsContainer.appendChild(realFotoInput);

        function updateFotoCount() {
            if (!fotoCountLabel) return;
            fotoCountLabel.textContent = fotoFiles.length + ' / ' + MAX_FOTO + ' foto';
            if (btnAddFoto) btnAddFoto.disabled = fotoFiles.length >= MAX_FOTO;
        }

        function renderPreviews() {
            if (!fotoPreviewGrid) return;
            fotoPreviewGrid.innerHTML = '';

            fotoFiles.forEach(function (file, idx) {
                const wrap = document.createElement('div');
                wrap.style.cssText = 'position:relative;display:inline-block;';

                const img = document.createElement('img');
                img.style.cssText = 'width:80px;height:80px;object-fit:cover;border-radius:8px;border:1px solid rgba(65,23,75,0.16);display:block;';
                const reader = new FileReader();
                reader.onload = function (e) { img.src = e.target.result; };
                reader.readAsDataURL(file);

                const removeBtn = document.createElement('button');
                removeBtn.type = 'button';
                removeBtn.title = 'Hapus foto ini';
                removeBtn.style.cssText = 'position:absolute;top:-6px;right:-6px;width:20px;height:20px;border-radius:50%;background:var(--parja-magenta);color:#fff;border:none;font-size:0.82rem;line-height:1;cursor:pointer;display:flex;align-items:center;justify-content:center;padding:0;';
                removeBtn.innerHTML = '&times;';
                removeBtn.addEventListener('click', function () {
                    fotoFiles.splice(idx, 1);
                    renderPreviews();
                    updateFotoCount();
                });

                wrap.appendChild(img);
                wrap.appendChild(removeBtn);
                fotoPreviewGrid.appendChild(wrap);
            });
        }

        if (artikelForm) {
            artikelForm.addEventListener('submit', function () {
                if (fotoFiles.length > 0 && typeof DataTransfer !== 'undefined') {
                    const dt = new DataTransfer();
                    fotoFiles.forEach(function (f) { dt.items.add(f); });
                    realFotoInput.files = dt.files;
                }
            });
        }

        if (btnAddFoto) {
            btnAddFoto.addEventListener('click', function () {
                if (fotoFiles.length >= MAX_FOTO) return;
                fotoTrigger.value = '';
                fotoTrigger.click();
            });
        }

        if (fotoTrigger) {
            fotoTrigger.addEventListener('change', function () {
                const remaining = MAX_FOTO - fotoFiles.length;
                Array.from(this.files).slice(0, remaining).forEach(function (f) { fotoFiles.push(f); });
                renderPreviews();
                updateFotoCount();
                this.value = '';
            });
        }
    });
</script>
@endauth
