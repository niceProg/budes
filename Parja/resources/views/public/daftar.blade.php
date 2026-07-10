<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <meta name="description" content="Formulir pendaftaran peserta Parlemen Remaja DPR RI." />
  <title>Pendaftaran Peserta — Parlemen Remaja DPR RI</title>

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link
    href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&family=Inter:wght@300;400;500;600&display=swap"
    rel="stylesheet" />

  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous" />

  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />

  <!-- Custom CSS -->
  <link rel="stylesheet" href="{{ asset('parja-landing/css/style.css') }}" />

  <style>
    /* Smooth scroll untuk anchor in-page. scroll-padding-top memberi jarak dari
       navbar sticky agar judul section tidak ketutup. */
    html { scroll-behavior: smooth; scroll-padding-top: 90px; }

    #daftar-hero {
      background: linear-gradient(140deg, #41174B 0%, #940040 55%, #ff4d85 100%);
      padding-top: 110px;
      padding-bottom: 90px;
      color: #fff;
      position: relative;
      overflow: hidden;
    }
    #daftar-hero::before {
      content: '';
      position: absolute;
      top: -160px; right: -160px;
      width: 560px; height: 560px;
      border-radius: 50%;
      background: rgba(255, 255, 255, 0.05);
      pointer-events: none;
    }
    .daftar-card {
      background: #fff;
      border-radius: 22px;
      box-shadow: 0 24px 60px rgba(65, 23, 75, 0.18);
      margin-top: -56px;
      position: relative;
      z-index: 3;
      padding: 36px;
    }
    @media (max-width: 575px) { .daftar-card { padding: 24px; } }
    .daftar-card h4 { font-family: 'Poppins', sans-serif; font-weight: 700; color: #41174B; }
    .form-label.fw-bold { color: #41174B; font-size: 0.86rem; }
    .form-control, .form-select { border-radius: 10px; padding: 10px 14px; font-size: 0.9rem; }
    .form-control:focus, .form-select:focus {
      border-color: #bf0050;
      box-shadow: 0 0 0 0.2rem rgba(191, 0, 80, 0.12);
    }
    .form-select:disabled { background-color: #f1f3f5; cursor: not-allowed; }
    .btn-gradient-submit {
      background: linear-gradient(135deg, #bf0050 0%, #41174B 100%);
      color: #fff; border: none; font-weight: 600;
      border-radius: 50px; padding: 12px 36px; font-size: 0.92rem;
      transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .btn-gradient-submit:hover { transform: translateY(-2px); box-shadow: 0 10px 26px rgba(191, 0, 80, 0.28); color: #fff; }
    .req { color: #bf0050; }
  </style>
</head>

<body style="background:#f8f9fa;">

  <!-- NAVBAR -->
  <nav id="mainNav" class="navbar navbar-expand-lg sticky-top">
    <div class="container">
      <div class="d-flex align-items-center">
        <a class="d-flex align-items-center" style="padding: 10px;" href="{{ route('parja.public.index') }}">
          <img class="light-mode-item" src="{{ asset('parja-landing/images/icons/logo-parja2.png') }}" alt="logo" style="height: 60px;">
        </a>
      </div>
      <div class="d-flex align-items-center gap-2">
        <a href="{{ route('parja.public.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3 py-2" style="font-size:0.83rem;">
          <i class="bi bi-arrow-left me-1"></i>Kembali
        </a>
      </div>
    </div>
  </nav>

  <!-- HERO -->
  <section id="daftar-hero">
    <div class="container position-relative" style="z-index:2;">
      <div class="text-center" style="max-width:640px; margin:0 auto;">
        <div class="hero-eyebrow d-inline-flex"><i class="bi bi-person-plus-fill"></i> Pendaftaran Angkatan 2026</div>
        <h1 class="mt-3" style="font-family:'Poppins',sans-serif; font-weight:800; font-size:clamp(1.8rem,4.4vw,2.8rem);">Formulir Pendaftaran Peserta</h1>
        <p style="color:rgba(255,255,255,0.85); line-height:1.75; font-size:0.95rem;">
          Lengkapi data dirimu dengan benar. Data ini langsung tercatat di sistem panitia Parlemen Remaja DPR RI.
        </p>
      </div>
    </div>
  </section>

  <!-- FORM -->
  <section class="pb-5">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-9 col-xl-8">
          <div class="daftar-card">

            @if (session('success'))
              <div class="alert alert-success d-flex align-items-center" role="alert">
                <i class="bi bi-check-circle-fill me-2 fs-5"></i>
                <div>{{ session('success') }}</div>
              </div>
            @endif

            @if ($errors->any())
              <div class="alert alert-danger" role="alert">
                <strong><i class="bi bi-exclamation-triangle-fill me-1"></i> Periksa kembali isian berikut:</strong>
                <ul class="mb-0 mt-2 ps-3">
                  @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                  @endforeach
                </ul>
              </div>
            @endif

            <h4 class="mb-1">Data Diri Peserta</h4>
            <p class="text-secondary mb-4" style="font-size:0.85rem;">Kolom bertanda <span class="req">*</span> wajib diisi.</p>

            <form action="{{ route('parja.public.daftar.store') }}" method="POST">
              @csrf

              <div class="row">
                <div class="col-md-6 mb-3">
                  <label for="nama" class="form-label fw-bold">Nama Lengkap <span class="req">*</span></label>
                  <input required type="text" class="form-control" id="nama" name="nama"
                    placeholder="Nama sesuai identitas" value="{{ old('nama') }}" maxlength="255">
                </div>
                <div class="col-md-6 mb-3">
                  <label for="nisn" class="form-label fw-bold">NISN <span class="req">*</span></label>
                  <input required type="text" class="form-control" id="nisn" name="nisn"
                    placeholder="Nomor Induk Siswa Nasional" value="{{ old('nisn') }}" maxlength="20">
                </div>

                <div class="col-md-6 mb-3">
                  <label for="jenis_kelamin" class="form-label fw-bold">Jenis Kelamin <span class="req">*</span></label>
                  <select required class="form-select" id="jenis_kelamin" name="jenis_kelamin">
                    <option value="">-- Pilih --</option>
                    <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                    <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                  </select>
                </div>
                <div class="col-md-6 mb-3">
                  <label for="email" class="form-label fw-bold">Email <span class="req">*</span></label>
                  <input required type="email" class="form-control" id="email" name="email"
                    placeholder="email@contoh.com" value="{{ old('email') }}" maxlength="100">
                </div>

                <div class="col-md-6 mb-3">
                  <label for="handphone" class="form-label fw-bold">No. HP / WhatsApp <span class="req">*</span></label>
                  <input required type="text" class="form-control" id="handphone" name="handphone"
                    placeholder="08xxxxxxxxxx" value="{{ old('handphone') }}" maxlength="20">
                </div>
                <div class="col-md-6 mb-3">
                  <label for="asal_sekolah" class="form-label fw-bold">Asal Sekolah <span class="req">*</span></label>
                  <input required type="text" class="form-control" id="asal_sekolah" name="asal_sekolah"
                    placeholder="Nama sekolah (SMA/SMK/MA/sederajat)" value="{{ old('asal_sekolah') }}" maxlength="255">
                </div>

                <!-- ===== Cascade wilayah: Provinsi → Kabupaten → Dapil ===== -->
                <div class="col-md-4 mb-3">
                  <label for="id_provinsi" class="form-label fw-bold">Provinsi <span class="req">*</span></label>
                  <select required class="form-select" id="id_provinsi" name="id_provinsi"
                    data-old="{{ old('id_provinsi') }}">
                    <option value="">-- Pilih Provinsi --</option>
                    @foreach ($provinsis as $prov)
                      <option value="{{ $prov->id_provinsi }}" {{ (string) old('id_provinsi') === (string) $prov->id_provinsi ? 'selected' : '' }}>
                        {{ $prov->provinsi }}
                      </option>
                    @endforeach
                  </select>
                </div>
                <div class="col-md-4 mb-3">
                  <label for="id_kabupaten" class="form-label fw-bold">Kabupaten/Kota <span class="req">*</span></label>
                  <select required disabled class="form-select" id="id_kabupaten" name="id_kabupaten"
                    data-old="{{ old('id_kabupaten') }}">
                    <option value="">-- Pilih Provinsi dulu --</option>
                  </select>
                </div>
                <div class="col-md-4 mb-3">
                  <label for="id_dapil" class="form-label fw-bold">Dapil <span class="req">*</span></label>
                  <select required disabled class="form-select" id="id_dapil" name="id_dapil"
                    data-old="{{ old('id_dapil') }}">
                    <option value="">-- Pilih Kabupaten dulu --</option>
                  </select>
                </div>

                <div class="col-md-12 mb-3">
                  <label for="alamat_rumah" class="form-label fw-bold">Alamat Rumah</label>
                  <textarea class="form-control" id="alamat_rumah" name="alamat_rumah" rows="2"
                    placeholder="Alamat tempat tinggal saat ini">{{ old('alamat_rumah') }}</textarea>
                </div>
              </div>

              <div class="d-flex align-items-center gap-3 mt-3">
                <button type="submit" class="btn-gradient-submit">
                  <i class="bi bi-send-fill me-1"></i> Kirim Pendaftaran
                </button>
                <a href="{{ route('parja.public.index') }}" class="text-decoration-none text-secondary" style="font-size:0.88rem;">Batal</a>
              </div>
            </form>

          </div>
        </div>
      </div>
    </div>
  </section>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc4s9bIOgUxi8T/jzmUl2gW6sRFoB7aHkKTHRcKJqaQ"
    crossorigin="anonymous"></script>

  <!-- Cascade wilayah Provinsi → Kabupaten → Dapil -->
  <script>
    (function () {
      const URL_KAB = "{{ route('parja.public.wilayah.kabupaten') }}";
      const URL_DAPIL = "{{ route('parja.public.wilayah.dapil') }}";

      const elProv = document.getElementById('id_provinsi');
      const elKab = document.getElementById('id_kabupaten');
      const elDapil = document.getElementById('id_dapil');

      function resetSelect(el, placeholder) {
        el.innerHTML = '<option value="">' + placeholder + '</option>';
        el.disabled = true;
      }

      function fill(el, rows, valueKey, labelKey, placeholder, selectedVal) {
        el.innerHTML = '<option value="">' + placeholder + '</option>';
        rows.forEach(function (r) {
          const opt = document.createElement('option');
          opt.value = r[valueKey];
          opt.textContent = r[labelKey];
          if (selectedVal && String(selectedVal) === String(r[valueKey])) opt.selected = true;
          el.appendChild(opt);
        });
        el.disabled = rows.length === 0;
      }

      function loadKabupaten(idProvinsi, selectedVal) {
        resetSelect(elKab, 'Memuat…');
        resetSelect(elDapil, '-- Pilih Kabupaten dulu --');
        if (!idProvinsi) { resetSelect(elKab, '-- Pilih Provinsi dulu --'); return Promise.resolve(); }
        return fetch(URL_KAB + '?provinsi=' + encodeURIComponent(idProvinsi), { headers: { 'Accept': 'application/json' } })
          .then(function (r) { return r.json(); })
          .then(function (rows) { fill(elKab, rows, 'id_kabupaten', 'kabupaten', '-- Pilih Kabupaten/Kota --', selectedVal); })
          .catch(function () { resetSelect(elKab, 'Gagal memuat'); });
      }

      function loadDapil(idKabupaten, selectedVal) {
        resetSelect(elDapil, 'Memuat…');
        if (!idKabupaten) { resetSelect(elDapil, '-- Pilih Kabupaten dulu --'); return Promise.resolve(); }
        return fetch(URL_DAPIL + '?kabupaten=' + encodeURIComponent(idKabupaten), { headers: { 'Accept': 'application/json' } })
          .then(function (r) { return r.json(); })
          .then(function (rows) { fill(elDapil, rows, 'id_dapil', 'dapil', '-- Pilih Dapil --', selectedVal); })
          .catch(function () { resetSelect(elDapil, 'Gagal memuat'); });
      }

      elProv.addEventListener('change', function () { loadKabupaten(this.value, null); });
      elKab.addEventListener('change', function () { loadDapil(this.value, null); });

      // Repopulasi setelah validasi gagal (old values).
      const oldProv = elProv.getAttribute('data-old');
      const oldKab = elKab.getAttribute('data-old');
      const oldDapil = elDapil.getAttribute('data-old');
      if (oldProv) {
        loadKabupaten(oldProv, oldKab).then(function () {
          if (oldKab) loadDapil(oldKab, oldDapil);
        });
      }
    })();
  </script>
</body>
</html>
