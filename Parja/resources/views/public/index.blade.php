<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="description"
    content="Parlemen Remaja DPR RI — Platform edukasi politik dan simulasi sidang parlemen untuk pelajar SMA/sederajat se-Indonesia. Daftar sekarang untuk Angkatan 2026!" />
  <title>Parlemen Remaja DPR RI — Suarakan Aspirasimu!</title>

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
    /* Smooth scroll untuk navigasi anchor (#beranda, #about, #timeline, #galeri, #faq).
       scroll-padding-top memberi jarak dari navbar sticky agar judul section tidak ketutup. */
    html { scroll-behavior: smooth; scroll-padding-top: 90px; }
  </style>
</head>

<body>

  <!-- ============================================================
       NAVBAR
  ============================================================ -->
  <nav id="mainNav" class="navbar navbar-expand-lg sticky-top">
    <div class="container">
		<div class="d-flex align-items-center">
				<a class="d-flex align-items-center" style="padding: 10px;" href="{{ route('parja.public.index') }}">
					<img class="light-mode-item" src="{{ asset('parja-landing/images/icons/logo-parja2.png') }}" alt="logo" style="height: 60px;">
				</a>
			</div>

      <!-- Mobile toggler -->
      <button class="navbar-toggler" type="button"
        aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
        <i class="bi bi-list fs-4 text-secondary"></i>
      </button>

      <!-- Nav links -->
      <div class="collapse navbar-collapse" id="navbarContent">
        <ul class="navbar-nav mx-auto gap-1 py-2 py-lg-0">
          <li class="nav-item">
            <a class="nav-link active" href="#beranda">Beranda</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#about">Tentang</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#timeline">Timeline</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#galeri">Galeri</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#faq">FAQ</a>
          </li>
		  <li class="nav-item">
			<a href="{{ route('parja.public.alumni') }}" class="btn btn-sm btn-secondary-pr rounded-pill px-3 py-2 fw-500"
            style="font-size:0.83rem; font-weight:500;">
            <i class="bi bi-people me-1"></i>Alumni
          </a>
		  </li>
        </ul>
        <div class="d-flex align-items-center gap-2 mt-2 mt-lg-0">
          
          <a href="{{ route('parja.public.daftar') }}" class="btn btn-gradient btn-sm rounded-pill px-4 py-2"
            style="font-size:0.83rem; font-weight:600;">
            <i class="bi bi-person-plus-fill me-1"></i>Daftar Sekarang
          </a>
        </div>
      </div>
    </div>
  </nav>

  <!-- ============================================================
       HERO SECTION
  ============================================================ -->
  <section id="beranda">
    <div id="hero">
      <div class="container position-relative" style="z-index: 2;">
        <div class="row align-items-center gy-5 py-5">

          <!-- Hero Text -->
          <div class="col-lg-6">
            <div class="hero-eyebrow animate-fade-up">
              <i class="bi bi-stars"></i>
              Angkatan 2026 Kini Dibuka
            </div>

            <h1 class="hero-headline mb-4 animate-fade-up animate-delay-1">
              Suarakan Aspirasimu, <span>Jadilah Parlemen Muda</span> Indonesia!
            </h1>

            <p class="hero-subtext mb-4 animate-fade-up animate-delay-2">
              Program simulasi sidang parlemen nasional untuk pelajar SMA/sederajat se-Indonesia.
              Pelajari proses legislasi, debat kebijakan, dan kepemimpinan langsung di Gedung DPR RI.
            </p>

            <div class="d-flex flex-wrap gap-3 mb-5 animate-fade-up animate-delay-3">
              <a href="#timeline" class="btn btn-light rounded-pill px-4 py-2 fw-semibold shadow-sm"
                style="font-size:0.9rem; color: #bf0050;">
                <i class="bi bi-calendar3 me-2"></i>Panduan Pendaftaran
              </a>
              <a href="#about" class="btn btn-outline-light-custom rounded-pill px-4 py-2 fw-semibold"
                style="font-size:0.9rem;">
                <i class="bi bi-play-circle me-2"></i>Tonton Video
              </a>
            </div>

            <div class="hero-stats animate-fade-up animate-delay-4">
              <div class="hero-stat-item">
                <div class="hero-stat-number">34+</div>
                <div class="hero-stat-label">Provinsi Peserta</div>
              </div>
              <div class="hero-stat-item">
                <div class="hero-stat-number">500+</div>
                <div class="hero-stat-label">Alumni Aktif</div>
              </div>
              <div class="hero-stat-item">
                <div class="hero-stat-number">12</div>
                <div class="hero-stat-label">Angkatan</div>
              </div>
            </div>
          </div>

          <!-- Hero Visual -->
          <div class="col-lg-6 d-flex justify-content-center justify-content-lg-end animate-fade-up animate-delay-2">
            <div class="position-relative" style="max-width: 480px; width: 100%;">
              <div class="hero-image-card">
                <div class="hero-img-placeholder">
                  <i class="bi bi-image"></i>
                  <span>Foto Kegiatan Peserta Parlemen Remaja<br>Sidang Paripurna Angkatan 2025</span>
                </div>
              </div>
              <!-- Floating badge -->
              <div class="hero-badge-floating" style="max-width: 220px;">
                <div class="badge-icon">
                  <i class="bi bi-trophy-fill"></i>
                </div>
                <div class="badge-text">
                  <strong>Dibuka Kembali!</strong>
                  <span>Pendaftaran Angkatan 2026</span>
                </div>
              </div>
            </div>
          </div>

        </div>
      </div>

      <!-- Wave SVG bottom transition -->
      <div class="hero-wave">
        <svg viewBox="0 0 1440 80" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none">
          <path d="M0,40 C360,80 1080,0 1440,40 L1440,80 L0,80 Z" fill="#ffffff" />
        </svg>
      </div>
    </div>
  </section>

  <!-- ============================================================
       ABOUT SECTION
  ============================================================ -->
  <section id="about" class="py-5" style="padding-top: 80px !important; padding-bottom: 80px !important;">
    <div class="container">
      <div class="row align-items-center gy-5">

        <!-- Text side -->
        <div class="col-lg-6 order-2 order-lg-1">
          <span class="section-badge">Tentang Program</span>
          <h2 class="section-title mb-3">Apa itu <br class="d-none d-sm-block" />Parlemen Remaja?</h2>
          <p class="text-secondary mb-4" style="font-size:0.95rem; line-height:1.8;">
            Parlemen Remaja DPR RI adalah program edukasi kebangsaan yang diselenggarakan oleh Sekretariat Jenderal DPR RI.
            Program ini mengundang pelajar-pelajar terbaik dari seluruh Indonesia untuk merasakan langsung bagaimana proses
            demokrasi dan legislasi berjalan di Gedung DPR/MPR RI, Jakarta.
          </p>

          <div class="about-feature-item">
            <div class="about-feature-icon">
              <i class="bi bi-building-fill"></i>
            </div>
            <div class="about-feature-text">
              <h6>Simulasi Sidang Nyata</h6>
              <p>Peserta mensimulasikan sidang komisi, rapat paripurna, dan proses penyusunan undang-undang di ruang sidang asli DPR RI.</p>
            </div>
          </div>

          <div class="about-feature-item">
            <div class="about-feature-icon">
              <i class="bi bi-people-fill"></i>
            </div>
            <div class="about-feature-text">
              <h6>Jaringan Nasional</h6>
              <p>Bertemu dan berkolaborasi dengan pelajar berprestasi dari 34 provinsi di seluruh Indonesia.</p>
            </div>
          </div>

          <div class="about-feature-item">
            <div class="about-feature-icon">
              <i class="bi bi-award-fill"></i>
            </div>
            <div class="about-feature-text">
              <h6>Gratis &amp; Difasilitasi Penuh</h6>
              <p>Seluruh biaya akomodasi, konsumsi, dan transportasi selama kegiatan di Jakarta ditanggung oleh Setjen DPR RI.</p>
            </div>
          </div>

          <a href="#timeline" class="btn btn-gradient rounded-pill px-4 py-2 mt-2" style="font-size:0.88rem; font-weight:600;">
            Lihat Cara Daftar <i class="bi bi-arrow-right ms-2"></i>
          </a>
        </div>

        <!-- Video / Image side -->
        <div class="col-lg-6 order-1 order-lg-2">
          <div class="about-video-wrapper">
            <video src="https://berkas2.dpr.go.id/parlemenremaja/video/parja2025.mp4" class="w-100 h-100" style="object-fit:cover; border-radius: inherit;" controls poster="https://berkas2.dpr.go.id/parlemenremaja/files/2025/thumbnailvideo_parja2025.jpg">
              Browser Anda tidak mendukung tag video.
            </video>
          </div>
        </div>

      </div>
    </div>
  </section>

  <!-- ============================================================
       TIMELINE / JADWAL PENDAFTARAN
  ============================================================ -->
  <section id="timeline" class="py-5" style="padding-top: 80px !important; padding-bottom: 80px !important;">
    <div class="container">
      <div class="text-center mb-5">
        <span class="section-badge">Alur Kegiatan</span>
        <h2 class="section-title">Timeline &amp; Jadwal Pendaftaran</h2>
        <p class="text-secondary mt-2" style="max-width: 520px; margin: 0 auto; font-size:0.9rem; line-height:1.75;">
          Ikuti 4 fase utama proses seleksi Parlemen Remaja DPR RI Angkatan 2026.
          Pastikan kamu tidak melewatkan satu pun tahapannya!
        </p>
      </div>

      <div class="timeline-wrapper">
        @foreach ($timeline as $t)
        <div class="timeline-item">
          <div class="timeline-dot">
            <i class="bi {{ $t['icon'] }}"></i>
          </div>
          <div class="timeline-card">
            <span class="timeline-phase-badge">{{ $t['fase'] }}</span>
            <h5>{{ $t['judul'] }}</h5>
            <p>{{ $t['deskripsi'] }}</p>
            <div class="timeline-date">
              <i class="bi bi-calendar-event"></i>
              {{ $t['tanggal'] }}
            </div>
          </div>
        </div>
        @endforeach
      </div><!-- /.timeline-wrapper -->

      <div class="text-center mt-4">
        <a href="{{ route('parja.public.daftar') }}" class="btn btn-gradient rounded-pill px-5 py-2" style="font-size:0.88rem; font-weight:600;">
          <i class="bi bi-person-plus-fill me-2"></i>Daftar Sekarang
        </a>
      </div>
    </div>
  </section>

  <!-- ============================================================
       GALERI KEGIATAN
  ============================================================ -->
  <section id="galeri" class="py-5" style="padding-top: 80px !important; padding-bottom: 80px !important;">
    <div class="container">
      <div class="row align-items-end mb-5">
        <div class="col-lg-7">
          <span class="section-badge">Dokumentasi</span>
          <h2 class="section-title mb-2">Berita &amp; Galeri Kegiatan</h2>
          <p class="text-secondary" style="font-size:0.9rem; line-height:1.75; max-width:500px;">
            Lihat bagaimana para pelajar Indonesia belajar, bersuara, dan berdebat layaknya anggota dewan sesungguhnya.
          </p>
        </div>
        <div class="col-lg-5 text-lg-end mt-3 mt-lg-0">
          <a href="{{ route('parja.public.galeri') }}" class="btn btn-outline-secondary rounded-pill px-4 py-2" style="font-size:0.85rem; font-weight:500;">
            Lihat Semua <i class="bi bi-arrow-right ms-1"></i>
          </a>
        </div>
      </div>

      <div class="row g-4">

        @forelse ($galeri as $item)
        <div class="col-md-6 col-lg-3">
          <div class="gallery-card card">
            <div class="gallery-card-img">
              @if (!empty($item->cover_uri))
                <img src="{{ $item->cover_uri }}" alt="{{ $item->judul }}" class="img-fluid w-100 h-100" style="object-fit:cover;">
              @else
                <div class="d-flex align-items-center justify-content-center w-100 h-100" style="background:linear-gradient(135deg,#f5e9f7,#ffe0ec);color:#bf0050;">
                  <i class="bi bi-image" style="font-size:2.4rem;opacity:.4;"></i>
                </div>
              @endif
            </div>
            <div class="card-body">
              <span class="gallery-tag" style="color:#bf0050; background: rgba(191,0,80,0.08);">Publikasi</span>
              <h5 class="card-title">{{ $item->judul }}</h5>
              <p class="card-text">{{ \Illuminate\Support\Str::limit(strip_tags((string) $item->deskripsi), 120) }}</p>
            </div>
            <div class="card-footer">
              <span class="gallery-card-date">
                <i class="bi bi-calendar3"></i> {{ optional(\Illuminate\Support\Carbon::make($item->tanggal_input))->translatedFormat('d M Y') ?? '-' }}
              </span>
              <a href="{{ route('parja.public.galeri.show', $item->id) }}" class="gallery-card-link">
                Baca <i class="bi bi-arrow-right"></i>
              </a>
            </div>
          </div>
        </div>
        @empty
        <div class="col-md-6 col-lg-3">
          <div class="gallery-card card">
            <div class="gallery-card-img">
              <img src="{{ asset('parja-landing/images/galeri-4.jpg') }}" alt="Workshop Debat dan Public Speaking" class="img-fluid w-100 h-100" style="object-fit:cover;">
            </div>
            <div class="card-body">
              <span class="gallery-tag" style="color:#7a2b80; background: rgba(122,43,128,0.08);">Workshop</span>
              <h5 class="card-title">Workshop Debat &amp; Public Speaking Bersama Anggota DPR RI</h5>
              <p class="card-text">Peserta mendapat pelatihan intensif teknik debat parlementer dan cara menyampaikan argumen secara sistematis dan persuasif.</p>
            </div>
            <div class="card-footer">
              <span class="gallery-card-date"><i class="bi bi-calendar3"></i> 16 Juli 2025</span>
              <a href="{{ route('parja.public.galeri') }}" class="gallery-card-link">Baca <i class="bi bi-arrow-right"></i></a>
            </div>
          </div>
        </div>
        <div class="col-md-6 col-lg-3">
          <div class="gallery-card card">
            <div class="gallery-card-img">
              <img src="{{ asset('parja-landing/images/galeri-5.jpg') }}" alt="Rapat Komisi Legislasi" class="img-fluid w-100 h-100" style="object-fit:cover;">
            </div>
            <div class="card-body">
              <span class="gallery-tag" style="color:#1a7a45; background: rgba(26,122,69,0.08);">Simulasi</span>
              <h5 class="card-title">Rapat Komisi Bahas RUU Pendidikan Tinggi Versi Remaja</h5>
              <p class="card-text">Dalam simulasi rapat komisi, peserta menyusun dan membahas Rancangan Undang-Undang tentang Reformasi Pendidikan Tinggi.</p>
            </div>
            <div class="card-footer">
              <span class="gallery-card-date"><i class="bi bi-calendar3"></i> 18 Juli 2025</span>
              <a href="{{ route('parja.public.galeri') }}" class="gallery-card-link">Baca <i class="bi bi-arrow-right"></i></a>
            </div>
          </div>
        </div>
        <div class="col-md-6 col-lg-3">
          <div class="gallery-card card">
            <div class="gallery-card-img">
              <img src="{{ asset('parja-landing/images/galeri-6.jpg') }}" alt="Tur Gedung DPR MPR RI" class="img-fluid w-100 h-100" style="object-fit:cover;">
            </div>
            <div class="card-body">
              <span class="gallery-tag" style="color:#5a3d9a; background: rgba(90,61,154,0.08);">Tur Edukasi</span>
              <h5 class="card-title">Tur Gedung DPR/MPR RI: Mengenal Jantung Demokrasi Indonesia</h5>
              <p class="card-text">Peserta diajak berkeliling kompleks gedung MPR/DPR/DPD RI dan mengenal fungsi setiap ruangan bersejarah di dalamnya.</p>
            </div>
            <div class="card-footer">
              <span class="gallery-card-date"><i class="bi bi-calendar3"></i> 15 Juli 2025</span>
              <a href="{{ route('parja.public.galeri') }}" class="gallery-card-link">Baca <i class="bi bi-arrow-right"></i></a>
            </div>
          </div>
        </div>
        <div class="col-md-6 col-lg-3">
          <div class="gallery-card card">
            <div class="gallery-card-img">
              <img src="{{ asset('parja-landing/images/galeri-1.jpg') }}" alt="Sidang Paripurna Parlemen Remaja" class="img-fluid w-100 h-100" style="object-fit:cover;">
            </div>
            <div class="card-body">
              <span class="gallery-tag" style="color:#bf0050; background: rgba(191,0,80,0.08);">Sidang</span>
              <h5 class="card-title">Sidang Paripurna Parlemen Remaja Angkatan 2025</h5>
              <p class="card-text">Puncak kegiatan berupa sidang paripurna resmi di ruang sidang DPR RI, di mana peserta mempresentasikan hasil kerja komisi.</p>
            </div>
            <div class="card-footer">
              <span class="gallery-card-date"><i class="bi bi-calendar3"></i> 20 Juli 2025</span>
              <a href="{{ route('parja.public.galeri') }}" class="gallery-card-link">Baca <i class="bi bi-arrow-right"></i></a>
            </div>
          </div>
        </div>
        @endforelse
      </div><!-- /.row -->
    </div>
  </section>

  <!-- ============================================================
       FAQ SECTION
  ============================================================ -->
  <section id="faq" class="py-5" style="padding-top: 80px !important; padding-bottom: 80px !important;">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-8 text-center mb-5">
          <span class="section-badge">FAQ</span>
          <h2 class="section-title">Pertanyaan yang Sering Diajukan</h2>
          <p class="text-secondary mt-2" style="font-size:0.9rem; line-height:1.75;">
            Masih punya pertanyaan? Temukan jawabannya di sini atau hubungi tim kami langsung.
          </p>
        </div>
      </div>

      <div class="row justify-content-center">
        <div class="col-lg-8">
          <div class="accordion faq-accordion" id="faqAccordion">

            <div class="accordion-item">
              <h2 class="accordion-header">
                <button class="accordion-button" type="button" data-faq-target="faq1" aria-expanded="true">
                  Siapa saja yang bisa mendaftar Parlemen Remaja?
                </button>
              </h2>
              <div id="faq1" class="accordion-collapse faq-open">
                <div class="accordion-body">
                  Program ini terbuka untuk seluruh pelajar aktif SMA/SMK/MA/sederajat (kelas X, XI, atau XII) dari seluruh wilayah Indonesia.
                  Tidak ada batasan jurusan atau bidang studi. Yang terpenting adalah semangat untuk belajar tentang demokrasi dan kepemimpinan!
                </div>
              </div>
            </div>

            <div class="accordion-item">
              <h2 class="accordion-header">
                <button class="accordion-button collapsed" type="button" data-faq-target="faq2" aria-expanded="false">
                  Apakah ada biaya pendaftaran atau biaya selama kegiatan?
                </button>
              </h2>
              <div id="faq2" class="accordion-collapse">
                <div class="accordion-body">
                  <strong>Tidak ada biaya apapun.</strong> Program Parlemen Remaja DPR RI sepenuhnya <em>gratis</em> tanpa pungutan biaya
                  pendaftaran. Seluruh biaya akomodasi (penginapan), konsumsi, dan kegiatan selama di Jakarta ditanggung
                  penuh oleh Sekretariat Jenderal DPR RI. Peserta hanya perlu menanggung biaya transportasi dari daerah ke Jakarta
                  (dan pulang).
                </div>
              </div>
            </div>

            <div class="accordion-item">
              <h2 class="accordion-header">
                <button class="accordion-button collapsed" type="button" data-faq-target="faq3" aria-expanded="false">
                  Berapa pelajar yang diterima setiap tahunnya?
                </button>
              </h2>
              <div id="faq3" class="accordion-collapse">
                <div class="accordion-body">
                  Setiap angkatan menerima sekitar 136 peserta, yang terdiri dari 4 perwakilan putra dan putri dari setiap
                  provinsi di Indonesia (34 provinsi × 4 peserta). Kuota ini dapat berubah tergantung kebijakan panitia
                  penyelenggara tahun berjalan.
                </div>
              </div>
            </div>

            <div class="accordion-item">
              <h2 class="accordion-header">
                <button class="accordion-button collapsed" type="button" data-faq-target="faq4" aria-expanded="false">
                  Apa saja dokumen yang perlu disiapkan untuk mendaftar?
                </button>
              </h2>
              <div id="faq4" class="accordion-collapse">
                <div class="accordion-body">
                  Dokumen yang umumnya diperlukan antara lain:
                  <ul class="mt-2 mb-0" style="padding-left: 20px; line-height: 2;">
                    <li>Kartu Pelajar / Surat Keterangan Aktif Bersekolah</li>
                    <li>Fotokopi Raport 2 semester terakhir (nilai rata-rata minimal sesuai ketentuan)</li>
                    <li>Surat Rekomendasi dari Kepala Sekolah</li>
                    <li>Essay motivasi (600 - 800 kata, tema ditentukan panitia)</li>
                    <li>Pas foto terbaru (latar merah/biru)</li>
                    <li>Kartu Identitas (KTP/KIA)</li>
                  </ul>
                </div>
              </div>
            </div>

            <div class="accordion-item">
              <h2 class="accordion-header">
                <button class="accordion-button collapsed" type="button" data-faq-target="faq5" aria-expanded="false">
                  Bagaimana proses seleksinya?
                </button>
              </h2>
              <div id="faq5" class="accordion-collapse">
                <div class="accordion-body">
                  Proses seleksi biasanya terdiri dari dua tahap: <strong>(1) Seleksi Administrasi & Essay</strong> —
                  panitia menilai kelengkapan berkas dan kualitas tulisan essay motivasi kandidat;
                  <strong>(2) Wawancara Online</strong> — kandidat yang lolos administrasi akan diwawancara secara
                  daring oleh tim penilai untuk mengukur kemampuan berpikir kritis, komunikasi, dan wawasan kebangsaan.
                </div>
              </div>
            </div>

            <div class="accordion-item">
              <h2 class="accordion-header">
                <button class="accordion-button collapsed" type="button" data-faq-target="faq6" aria-expanded="false">
                  Apa manfaat yang didapat peserta setelah mengikuti program ini?
                </button>
              </h2>
              <div id="faq6" class="accordion-collapse">
                <div class="accordion-body">
                  Peserta akan mendapatkan banyak manfaat, di antaranya: Sertifikat resmi dari Setjen DPR RI,
                  pengalaman simulasi sidang parlemen secara langsung, jejaring (networking) dengan pelajar berprestasi dari seluruh Indonesia,
                  peningkatan kemampuan public speaking dan berpikir kritis, serta wawasan mendalam tentang sistem
                  demokrasi dan pemerintahan Indonesia. Sertifikat ini juga diakui sebagai prestasi non-akademik yang
                  dapat dicantumkan dalam portofolio penerimaan mahasiswa baru.
                </div>
              </div>
            </div>

          </div><!-- /.accordion -->

          <div class="text-center mt-5">
            <p class="text-secondary mb-3" style="font-size:0.88rem;">Masih ada pertanyaan lain?</p>
            <a href="mailto:parlemenremaja@dpr.go.id" class="btn btn-gradient rounded-pill px-5 py-2"
              style="font-size:0.88rem; font-weight:600;">
              <i class="bi bi-envelope-fill me-2"></i>Hubungi Kami
            </a>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ============================================================
       FOOTER
  ============================================================ -->
  <footer id="footer">
    <div class="container py-5">
      <div class="row gy-4">

        <!-- Brand & Description -->
        <div class="col-lg-4 col-md-6">
          <div class="d-flex align-items-center gap-3 mb-3">
            <img src="{{ asset('parja-landing/images/icons/logo-footer.png') }}" alt="Logo Parlemen Remaja DPR RI" style="height: 56px;">
          </div>
          <p style="font-size:0.83rem; line-height:1.8; color: rgba(255,255,255,0.55);">
            Program edukasi kebangsaan dan simulasi sidang parlemen untuk pelajar SMA/sederajat se-Indonesia,
            diselenggarakan oleh Sekretariat Jenderal DPR RI.
          </p>

        <!-- Quick Links -->
        <div class="col-lg-2 col-md-6 col-6">
          <p class="footer-heading">Navigasi</p>
          <ul class="footer-links">
            <li><a href="#beranda">Beranda</a></li>
            <li><a href="#about">Tentang Program</a></li>
            <li><a href="#timeline">Timeline</a></li>
            <li><a href="#galeri">Galeri &amp; Berita</a></li>
            <li><a href="#faq">FAQ</a></li>
          </ul>
        </div>

        <!-- Program Links -->
        <div class="col-lg-2 col-md-6 col-6">
          <p class="footer-heading">Program</p>
          <ul class="footer-links">
            <li><a href="#timeline">Cara Pendaftaran</a></li>
            <li><a href="#faq">Persyaratan</a></li>
            <li><a href="{{ route('parja.public.alumni') }}">Alumni Network</a></li>
            <li><a href="#galeri">Galeri Angkatan</a></li>
          </ul>
        </div>

        <!-- Contact -->
        <div class="col-lg-4 col-md-6">
          <p class="footer-heading">Kontak &amp; Informasi</p>
          <div class="footer-contact-item">
            <i class="bi bi-geo-alt-fill"></i>
            <span>Sekretariat Jenderal DPR RI, Jl. Jenderal Gatot Subroto, Senayan, Jakarta Pusat 10270</span>
          </div>
          <div class="footer-contact-item">
            <i class="bi bi-envelope-fill"></i>
            <a href="mailto:parlemenremaja@dpr.go.id"
              style="color: rgba(255,255,255,0.65); text-decoration: none; transition: color 0.2s;"
              onmouseover="this.style.color='#ff4d85'" onmouseout="this.style.color='rgba(255,255,255,0.65)'">
              parlemenremaja@dpr.go.id
            </a>
          </div>
          <div class="footer-contact-item">
            <i class="bi bi-telephone-fill"></i>
            <span>(021) 5715-408 (Bidang Kehumasan)</span>
          </div>
          <div class="footer-contact-item">
            <i class="bi bi-globe"></i>
            <a href="https://www.dpr.go.id" target="_blank" rel="noopener noreferrer"
              style="color: rgba(255,255,255,0.65); text-decoration: none; transition: color 0.2s;"
              onmouseover="this.style.color='#ff4d85'" onmouseout="this.style.color='rgba(255,255,255,0.65)'">
              www.dpr.go.id
            </a>
          </div>
        </div>

      </div><!-- /.row -->

      <hr class="footer-divider my-4" />

      <div class="row align-items-center">
        <div class="col-md-8">
          <p style="font-size:0.78rem; color: rgba(255,255,255,0.4); margin:0;">
            &copy; 2026 Sekretariat Jenderal DPR RI. Hak Cipta Dilindungi Undang-Undang.<br/>
            Website ini dirancang untuk keperluan edukasi dan program Parlemen Remaja DPR RI.
          </p>
        </div>
        <div class="col-md-4 text-md-end mt-3 mt-md-0">
          <p style="font-size:0.75rem; color: rgba(255,255,255,0.3); margin:0;">
            <a href="{{ route('parja.public.privasi') }}" style="color:rgba(255,255,255,0.35); text-decoration:none;">Kebijakan Privasi</a>
            &nbsp;&middot;&nbsp;
            <a href="{{ route('parja.public.syarat') }}" style="color:rgba(255,255,255,0.35); text-decoration:none;">Syarat &amp; Ketentuan</a>
          </p>
        </div>
      </div>

    </div><!-- /.container -->

    <!-- Footer bottom accent -->
    <div class="footer-bottom py-2">
      <div class="container text-center">
        <small style="font-size:0.7rem; color: rgba(255,255,255,0.2);">
          Dibangun dengan semangat demokrasi untuk generasi penerus bangsa &mdash; Indonesia 🇮🇩
        </small>
      </div>
    </div>
  </footer>

  <!-- Scroll to Top Button -->
  <button id="scrollTopBtn" aria-label="Scroll ke atas">
    <i class="bi bi-chevron-up"></i>
  </button>

  <!-- Bootstrap 5 JS Bundle -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc4s9bIOgUxi8T/jzmUl2gW6sRFoB7aHkKTHRcKJqaQ"
    crossorigin="anonymous"></script>

  <!-- Custom JS -->
  <script src="{{ asset('parja-landing/js/main.js') }}"></script>

</body>
</html>
