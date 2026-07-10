<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="description" content="Detail Program Alumni — Parlemen Remaja DPR RI" />
  <title id="pageTitle">Detail Program — Parlemen Remaja DPR RI</title>

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet" />

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

    /* ============================================================
       PROGRAM DETAIL — HERO
    ============================================================ */
    #pd-hero {
      padding-top: 100px;
      padding-bottom: 72px;
      position: relative;
      overflow: hidden;
    }

    #pd-hero::before {
      content: '';
      position: absolute;
      inset: 0;
      z-index: 0;
    }

    #pd-hero .container {
      position: relative;
      z-index: 1;
    }

    .pd-breadcrumb {
      display: flex;
      align-items: center;
      gap: 6px;
      font-size: 0.8rem;
      margin-bottom: 22px;
      flex-wrap: wrap;
    }

    .pd-breadcrumb a {
      color: rgba(255, 255, 255, 0.75);
      text-decoration: none;
      transition: color 0.2s;
    }

    .pd-breadcrumb a:hover {
      color: #fff;
    }

    .pd-breadcrumb span {
      color: rgba(255, 255, 255, 0.45);
    }

    .pd-breadcrumb .current {
      color: rgba(255, 255, 255, 0.95);
      font-weight: 500;
    }

    .pd-hero-tag {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      font-family: 'Poppins', sans-serif;
      font-size: 0.75rem;
      font-weight: 700;
      letter-spacing: 0.08em;
      text-transform: uppercase;
      padding: 5px 16px;
      border-radius: 50px;
      background: rgba(255, 255, 255, 0.18);
      border: 1px solid rgba(255, 255, 255, 0.3);
      color: #fff;
      margin-bottom: 16px;
    }

    .pd-hero-title {
      font-family: 'Poppins', sans-serif;
      font-weight: 800;
      font-size: clamp(1.9rem, 4.5vw, 3rem);
      line-height: 1.18;
      color: #fff;
      margin-bottom: 16px;
      text-shadow: 0 2px 20px rgba(0, 0, 0, 0.12);
    }

    .pd-hero-sub {
      font-family: 'Inter', sans-serif;
      font-size: 1.05rem;
      color: rgba(255, 255, 255, 0.85);
      line-height: 1.75;
      max-width: 580px;
      margin-bottom: 0;
    }

    .pd-hero-meta {
      display: flex;
      flex-wrap: wrap;
      gap: 20px;
      margin-top: 32px;
    }

    .pd-meta-item {
      display: flex;
      align-items: center;
      gap: 8px;
      background: rgba(255, 255, 255, 0.12);
      border: 1px solid rgba(255, 255, 255, 0.2);
      border-radius: 10px;
      padding: 8px 16px;
      color: #fff;
      font-size: 0.83rem;
    }

    .pd-meta-item i {
      font-size: 1rem;
      opacity: 0.8;
    }

    .pd-hero-icon-box {
      width: 100%;
      max-width: 380px;
      aspect-ratio: 1 / 1;
      border-radius: 28px;
      background: rgba(255, 255, 255, 0.1);
      border: 1px solid rgba(255, 255, 255, 0.18);
      backdrop-filter: blur(10px);
      -webkit-backdrop-filter: blur(10px);
      display: flex;
      align-items: center;
      justify-content: center;
      box-shadow: 0 24px 64px rgba(0, 0, 0, 0.18);
      margin-left: auto;
    }

    .pd-hero-icon-box i {
      font-size: 7rem;
      color: rgba(255, 255, 255, 0.35);
    }

    .pd-wave {
      position: absolute;
      bottom: -2px;
      left: 0;
      width: 100%;
      line-height: 0;
      z-index: 1;
    }

    .pd-wave svg {
      display: block;
      width: 100%;
    }

    /* ============================================================
       CONTENT AREA
    ============================================================ */
    #pd-content {
      background: #fff;
    }

    .pd-section-label {
      font-family: 'Poppins', sans-serif;
      font-size: 0.68rem;
      font-weight: 700;
      letter-spacing: 0.12em;
      text-transform: uppercase;
      color: #bf0050;
      margin-bottom: 10px;
      display: flex;
      align-items: center;
      gap: 6px;
    }

    .pd-section-label::before {
      content: '';
      width: 18px;
      height: 3px;
      background: var(--pr-gradient);
      border-radius: 2px;
      display: inline-block;
    }

    .pd-heading {
      font-family: 'Poppins', sans-serif;
      font-weight: 700;
      font-size: 1.35rem;
      color: #212529;
      margin-bottom: 16px;
      line-height: 1.35;
    }

    .pd-body-text {
      font-family: 'Inter', sans-serif;
      font-size: 0.94rem;
      color: #495057;
      line-height: 1.82;
    }

    /* Objectives list */
    .pd-objective-list {
      list-style: none;
      padding: 0;
      margin: 0;
    }

    .pd-objective-list li {
      display: flex;
      align-items: flex-start;
      gap: 12px;
      padding: 12px 0;
      border-bottom: 1px solid #f5f5f5;
      font-size: 0.9rem;
      color: #343a40;
      line-height: 1.6;
    }

    .pd-objective-list li:last-child {
      border-bottom: none;
    }

    .pd-objective-list .obj-icon {
      width: 30px;
      height: 30px;
      border-radius: 8px;
      background: var(--pr-gradient-soft);
      display: flex;
      align-items: center;
      justify-content: center;
      color: #bf0050;
      font-size: 0.85rem;
      flex-shrink: 0;
      margin-top: 1px;
    }

    /* Activity cards */
    .pd-activity-card {
      background: #f8f9fa;
      border-radius: 16px;
      padding: 22px 20px;
      border-left: 4px solid #bf0050;
      margin-bottom: 14px;
      transition: box-shadow 0.3s ease;
    }

    .pd-activity-card:hover {
      box-shadow: 0 4px 20px rgba(191, 0, 80, 0.1);
    }

    .pd-activity-card h6 {
      font-family: 'Poppins', sans-serif;
      font-weight: 700;
      font-size: 0.9rem;
      color: #212529;
      margin-bottom: 6px;
    }

    .pd-activity-card p {
      font-size: 0.83rem;
      color: #6c757d;
      margin: 0;
      line-height: 1.65;
    }

    /* Stats row */
    .pd-stat-row {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 14px;
    }

    @media (max-width: 575px) {
      .pd-stat-row {
        grid-template-columns: repeat(2, 1fr);
      }
    }

    .pd-stat-box {
      background: #fff;
      border-radius: 16px;
      padding: 20px 14px;
      text-align: center;
      box-shadow: var(--pr-card-shadow);
      border-top: 3px solid transparent;
    }

    .pd-stat-box.primary { border-top-color: #bf0050; }
    .pd-stat-box.secondary { border-top-color: #41174B; }
    .pd-stat-box.success { border-top-color: #1a7a45; }

    .pd-stat-number {
      font-family: 'Poppins', sans-serif;
      font-weight: 800;
      font-size: 1.7rem;
      line-height: 1;
      display: block;
      margin-bottom: 4px;
    }

    .pd-stat-number.primary { color: #bf0050; }
    .pd-stat-number.secondary { color: #41174B; }
    .pd-stat-number.success { color: #1a7a45; }

    .pd-stat-label {
      font-size: 0.72rem;
      color: #6c757d;
      line-height: 1.4;
    }

    /* Sidebar sticky card */
    .pd-sidebar-card {
      background: #fff;
      border-radius: 20px;
      box-shadow: var(--pr-card-shadow);
      overflow: hidden;
      position: sticky;
      top: 86px;
    }

    .pd-sidebar-header {
      background: var(--pr-gradient);
      padding: 22px 24px;
    }

    .pd-sidebar-header h5 {
      font-family: 'Poppins', sans-serif;
      font-weight: 700;
      font-size: 1rem;
      color: #fff;
      margin: 0;
    }

    .pd-sidebar-header p {
      font-size: 0.78rem;
      color: rgba(255, 255, 255, 0.8);
      margin: 4px 0 0;
    }

    .pd-sidebar-body {
      padding: 22px 24px;
    }

    .pd-info-row {
      display: flex;
      align-items: flex-start;
      gap: 12px;
      padding: 10px 0;
      border-bottom: 1px solid #f0f0f0;
    }

    .pd-info-row:last-of-type {
      border-bottom: none;
    }

    .pd-info-icon {
      width: 34px;
      height: 34px;
      border-radius: 8px;
      background: var(--pr-gradient-soft);
      display: flex;
      align-items: center;
      justify-content: center;
      color: #bf0050;
      font-size: 0.9rem;
      flex-shrink: 0;
    }

    .pd-info-label {
      font-family: 'Poppins', sans-serif;
      font-size: 0.7rem;
      font-weight: 600;
      letter-spacing: 0.06em;
      text-transform: uppercase;
      color: #adb5bd;
      display: block;
      line-height: 1;
      margin-bottom: 3px;
    }

    .pd-info-value {
      font-size: 0.85rem;
      font-weight: 500;
      color: #212529;
    }

    /* Other programs */
    .pd-other-card {
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 12px;
      border-radius: 12px;
      background: #f8f9fa;
      text-decoration: none;
      transition: all 0.25s ease;
      margin-bottom: 10px;
    }

    .pd-other-card:hover {
      background: var(--pr-gradient-soft);
      transform: translateX(4px);
    }

    .pd-other-thumb {
      width: 46px;
      height: 46px;
      border-radius: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.2rem;
      flex-shrink: 0;
    }

    .pd-other-card h6 {
      font-family: 'Poppins', sans-serif;
      font-size: 0.8rem;
      font-weight: 600;
      color: #212529;
      margin-bottom: 2px;
      line-height: 1.3;
    }

    .pd-other-card span {
      font-size: 0.71rem;
      color: #6c757d;
    }

    /* CTA inline */
    .pd-cta-box {
      background: linear-gradient(135deg, #41174B 0%, #bf0050 100%);
      border-radius: 20px;
      padding: 36px 32px;
      text-align: center;
      position: relative;
      overflow: hidden;
    }

    .pd-cta-box::before {
      content: '';
      position: absolute;
      top: -50px;
      right: -50px;
      width: 200px;
      height: 200px;
      border-radius: 50%;
      background: rgba(255, 255, 255, 0.06);
      pointer-events: none;
    }

    .pd-cta-box h4 {
      font-family: 'Poppins', sans-serif;
      font-weight: 800;
      font-size: 1.3rem;
      color: #fff;
      margin-bottom: 10px;
    }

    .pd-cta-box p {
      font-size: 0.85rem;
      color: rgba(255, 255, 255, 0.8);
      margin-bottom: 22px;
      line-height: 1.65;
    }

    /* Scroll reveal */
    .reveal {
      opacity: 0;
      transform: translateY(24px);
      transition: opacity 0.6s ease, transform 0.6s ease;
    }

    .reveal.visible {
      opacity: 1;
      transform: translateY(0);
    }

    .reveal-d1 { transition-delay: 0.1s; }
    .reveal-d2 { transition-delay: 0.2s; }
    .reveal-d3 { transition-delay: 0.3s; }
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

      <button class="navbar-toggler" type="button"
        data-bs-toggle="collapse" data-bs-target="#navbarContent"
        aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
        <i class="bi bi-list fs-4 text-secondary"></i>
      </button>

      <div class="collapse navbar-collapse" id="navbarContent">
        <ul class="navbar-nav mx-auto gap-1 py-2 py-lg-0">
          <li class="nav-item"><a class="nav-link" href="{{ route('parja.public.index') }}#beranda">Beranda</a></li>
          <li class="nav-item"><a class="nav-link" href="{{ route('parja.public.index') }}#about">Tentang</a></li>
          <li class="nav-item"><a class="nav-link" href="{{ route('parja.public.index') }}#timeline">Timeline</a></li>
          <li class="nav-item"><a class="nav-link" href="{{ route('parja.public.index') }}#galeri">Galeri</a></li>
          <li class="nav-item"><a class="nav-link" href="{{ route('parja.public.index') }}#faq">FAQ</a></li>
          <li class="nav-item">
            <a href="{{ route('parja.public.alumni') }}" class="btn btn-sm btn-secondary-pr rounded-pill px-3 py-2"
              style="font-size:0.83rem; font-weight:500;">
              <i class="bi bi-people me-1"></i>Alumni
            </a>
          </li>
        </ul>
        <div class="d-flex align-items-center gap-2 mt-2 mt-lg-0">
          <a href="{{ route('parja.public.alumni') }}#al-cta" class="btn btn-gradient btn-sm rounded-pill px-4 py-2"
            style="font-size:0.83rem; font-weight:600;">
            <i class="bi bi-person-plus-fill me-1"></i>Daftar Sekarang
          </a>
        </div>
      </div>
    </div>
  </nav>


  <!-- ============================================================
       HERO — rendered by JS
  ============================================================ -->
  <section id="pd-hero">
    <div class="container">
      <div class="row align-items-center gy-5">

        <div class="col-lg-7">
          <!-- breadcrumb -->
          <nav class="pd-breadcrumb" aria-label="breadcrumb">
            <a href="{{ route('parja.public.index') }}"><i class="bi bi-house-fill me-1"></i>Beranda</a>
            <span>/</span>
            <a href="{{ route('parja.public.alumni') }}#al-programs">Program Alumni</a>
            <span>/</span>
            <span class="current" id="breadcrumbCurrent">Detail Program</span>
          </nav>

          <div id="heroTag" class="pd-hero-tag"><i class="bi bi-tag-fill"></i> Kategori</div>
          <h1 class="pd-hero-title" id="heroTitle">Judul Program</h1>
          <p class="pd-hero-sub" id="heroSub">Deskripsi singkat program.</p>

          <div class="pd-hero-meta" id="heroMeta"></div>
        </div>

        <div class="col-lg-5 d-flex justify-content-center justify-content-lg-end">
          <div class="pd-hero-icon-box">
            <i id="heroIcon" class="bi bi-people-fill"></i>
          </div>
        </div>

      </div>
    </div>

    <div class="pd-wave">
      <svg viewBox="0 0 1440 70" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none">
        <path d="M0,35 C360,70 1080,0 1440,35 L1440,70 L0,70 Z" fill="#ffffff" />
      </svg>
    </div>
  </section>


  <!-- ============================================================
       MAIN CONTENT
  ============================================================ -->
  <section id="pd-content" class="py-5" style="padding-top:72px !important; padding-bottom:80px !important;">
    <div class="container">
      <div class="row gy-5 gx-lg-5">

        <!-- Left — main content -->
        <div class="col-lg-8">

          <!-- About -->
          <div class="mb-5 reveal">
            <p class="pd-section-label"><span>Tentang Program</span></p>
            <h2 class="pd-heading" id="aboutHeading">Apa Itu Program Ini?</h2>
            <div class="pd-body-text" id="aboutBody"></div>
          </div>

          <!-- Objectives -->
          <div class="mb-5 reveal reveal-d1">
            <p class="pd-section-label"><span>Tujuan & Sasaran</span></p>
            <h2 class="pd-heading">Apa yang Ingin Kami Capai?</h2>
            <ul class="pd-objective-list" id="objectiveList"></ul>
          </div>

          <!-- Stats -->
          <div class="mb-5 reveal reveal-d2">
            <p class="pd-section-label"><span>Dampak Program</span></p>
            <h2 class="pd-heading">Program dalam Angka</h2>
            <div class="pd-stat-row" id="statRow"></div>
          </div>

          <!-- Activities -->
          <div class="mb-5 reveal reveal-d1">
            <p class="pd-section-label"><span>Kegiatan Utama</span></p>
            <h2 class="pd-heading">Apa Saja Kegiatannya?</h2>
            <div id="activityList"></div>
          </div>

          <!-- Eligibility -->
          <div class="mb-5 reveal reveal-d2">
            <p class="pd-section-label"><span>Syarat & Ketentuan</span></p>
            <h2 class="pd-heading">Siapa yang Bisa Bergabung?</h2>
            <div class="pd-body-text" id="eligibilityBody"></div>
          </div>

          <!-- CTA box -->
          <div class="pd-cta-box reveal">
            <h4>Tertarik Bergabung?</h4>
            <p id="ctaDesc">Daftarkan dirimu dan jadilah bagian dari program ini bersama ribuan alumni Parlemen Remaja DPR RI.</p>
            <div class="d-flex flex-wrap justify-content-center gap-3">
              <a href="{{ route('parja.public.alumni') }}#al-cta" class="btn btn-light rounded-pill px-5 py-2 fw-bold" style="color:#bf0050; font-size:0.92rem;">
                <i class="bi bi-person-plus-fill me-2"></i>Daftar Sekarang
              </a>
              <a href="{{ route('parja.public.alumni') }}#al-programs" class="btn btn-outline-light-custom rounded-pill px-5 py-2 fw-semibold" style="font-size:0.92rem;">
                <i class="bi bi-grid-3x3-gap me-2"></i>Program Lainnya
              </a>
            </div>
          </div>
		  <!-- Other programs -->
          <div class="mt-4 reveal reveal-d2">
            <p class="pd-section-label mb-3"><span>Program Lainnya</span></p>
            <div id="otherPrograms"></div>
          </div>

        </div>

        <!-- Right — sidebar -->
        <div class="col-lg-4">
          <div class="pd-sidebar-card reveal">
            <div class="pd-sidebar-header">
              <h5>Info Program</h5>
              <p>Detail dan jadwal pelaksanaan</p>
            </div>
            <div class="pd-sidebar-body">
              <div id="sidebarInfo"></div>

              <a href="{{ route('parja.public.alumni') }}#al-cta" class="btn btn-gradient rounded-pill w-100 py-2 mt-3 fw-semibold" style="font-size:0.88rem;">
                <i class="bi bi-person-plus-fill me-2"></i>Daftar Program
              </a>
              <a href="{{ route('parja.public.alumni') }}#al-programs" class="btn btn-outline-secondary rounded-pill w-100 py-2 mt-2" style="font-size:0.83rem;">
                <i class="bi bi-arrow-left me-2"></i>Kembali ke Program
              </a>
            </div>
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

        <div class="col-lg-4 col-md-6">
          <div class="d-flex align-items-center gap-3 mb-3">
            <img src="{{ asset('parja-landing/images/icons/logo-footer.png') }}" alt="Logo Parlemen Remaja DPR RI" style="height:56px;">
          </div>
          <p style="font-size:0.83rem; line-height:1.8; color:rgba(255,255,255,0.55);">
            Program edukasi kebangsaan dan simulasi sidang parlemen untuk pelajar SMA/sederajat se-Indonesia,
            diselenggarakan oleh Sekretariat Jenderal DPR RI.
          </p>
        </div>

        <div class="col-lg-2 col-md-6 col-6">
          <p class="footer-heading">Navigasi</p>
          <ul class="footer-links">
            <li><a href="{{ route('parja.public.index') }}#beranda">Beranda</a></li>
            <li><a href="{{ route('parja.public.index') }}#about">Tentang Program</a></li>
            <li><a href="{{ route('parja.public.index') }}#timeline">Timeline</a></li>
            <li><a href="{{ route('parja.public.index') }}#galeri">Galeri &amp; Berita</a></li>
            <li><a href="{{ route('parja.public.index') }}#faq">FAQ</a></li>
          </ul>
        </div>

        <div class="col-lg-2 col-md-6 col-6">
          <p class="footer-heading">Alumni</p>
          <ul class="footer-links">
            <li><a href="{{ route('parja.public.alumni') }}#al-about">Tentang Kami</a></li>
            <li><a href="{{ route('parja.public.alumni') }}#al-programs">Program</a></li>
            <li><a href="{{ route('parja.public.alumni') }}#al-news">Berita</a></li>
            <li><a href="{{ route('parja.public.alumni') }}#al-partners">Mitra</a></li>
            <li><a href="{{ route('parja.public.alumni') }}#al-cta">Bergabung</a></li>
          </ul>
        </div>

        <div class="col-lg-4 col-md-6">
          <p class="footer-heading">Kontak &amp; Informasi</p>
          <div class="footer-contact-item">
            <i class="bi bi-geo-alt-fill"></i>
            <span>Sekretariat Jenderal DPR RI, Jl. Jenderal Gatot Subroto, Senayan, Jakarta Pusat 10270</span>
          </div>
          <div class="footer-contact-item">
            <i class="bi bi-envelope-fill"></i>
            <a href="mailto:parlemenremaja@dpr.go.id" style="color:rgba(255,255,255,0.65); text-decoration:none;">parlemenremaja@dpr.go.id</a>
          </div>
          <div class="footer-contact-item">
            <i class="bi bi-telephone-fill"></i>
            <span>(021) 5715-408 (Bidang Kehumasan)</span>
          </div>
          <div class="footer-contact-item">
            <i class="bi bi-globe"></i>
            <a href="https://www.dpr.go.id" target="_blank" rel="noopener noreferrer" style="color:rgba(255,255,255,0.65); text-decoration:none;">www.dpr.go.id</a>
          </div>
        </div>

      </div>

      <hr class="footer-divider my-4" />

      <div class="row align-items-center">
        <div class="col-md-8">
          <p style="font-size:0.78rem; color:rgba(255,255,255,0.4); margin:0;">
            &copy; 2026 Sekretariat Jenderal DPR RI. Hak Cipta Dilindungi Undang-Undang.<br />
            Website ini dirancang untuk keperluan edukasi dan program Parlemen Remaja DPR RI.
          </p>
        </div>
        <div class="col-md-4 text-md-end mt-3 mt-md-0">
          <p style="font-size:0.75rem; color:rgba(255,255,255,0.3); margin:0;">
            <a href="{{ route('parja.public.privasi') }}" style="color:rgba(255,255,255,0.35); text-decoration:none;">Kebijakan Privasi</a>
            &nbsp;&middot;&nbsp;
            <a href="{{ route('parja.public.syarat') }}" style="color:rgba(255,255,255,0.35); text-decoration:none;">Syarat &amp; Ketentuan</a>
          </p>
        </div>
      </div>
    </div>

    <div class="footer-bottom py-2">
      <div class="container text-center">
        <small style="font-size:0.7rem; color:rgba(255,255,255,0.2);">
          Dibangun dengan semangat demokrasi untuk generasi penerus bangsa &mdash; Indonesia 🇮🇩
        </small>
      </div>
    </div>
  </footer>

  <button id="scrollTopBtn" aria-label="Scroll ke atas">
    <i class="bi bi-chevron-up"></i>
  </button>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc4s9bIOgUxi8T/jzmUl2gW6sRFoB7aHkKTHRcKJqaQ"
    crossorigin="anonymous"></script>

  <script src="{{ asset('parja-landing/js/main.js') }}"></script>

  <script>
    // ============================================================
    // PROGRAM DATA
    // ============================================================
    const programs = {
      'forum-nasional': {
        id: 'forum-nasional',
        category: 'Networking',
        categoryColor: '#bf0050',
        categoryBg: 'rgba(191,0,80,0.08)',
        heroGradient: 'linear-gradient(140deg, #bf0050 0%, #ff4d85 100%)',
        icon: 'bi bi-people-fill',
        iconThumbBg: 'linear-gradient(135deg,#f0e0e8,#ffe0ec)',
        title: 'Forum Nasional Alumni',
        subtitle: 'Pertemuan tahunan yang mempertemukan ribuan alumni dari 34 provinsi untuk merumuskan agenda kepemudaan nasional.',
        meta: [
          { icon: 'bi bi-calendar-event-fill', label: 'Jadwal: April setiap tahun' },
          { icon: 'bi bi-geo-alt-fill', label: 'Jakarta, DPR/MPR RI' },
          { icon: 'bi bi-people-fill', label: '800+ Peserta' },
        ],
        about: `<p>Forum Nasional Alumni Parlemen Remaja DPR RI adalah pertemuan tahunan paling bergengsi bagi seluruh alumni program Parlemen Remaja dari seluruh penjuru Indonesia. Sejak pertama kali diselenggarakan, forum ini telah menjadi wadah strategis untuk mempererat persaudaraan, berbagi pengalaman lintas angkatan, serta merumuskan agenda kontribusi kolektif alumni kepada bangsa.</p>
        <p class="mt-3">Melalui serangkaian sesi pleno, diskusi panel, dan lokakarya tematik, para alumni diundang untuk aktif menyuarakan gagasan segar tentang isu-isu krusial yang dihadapi Indonesia — mulai dari pendidikan, demokrasi, lingkungan hidup, hingga ekonomi kreatif pemuda.</p>`,
        objectives: [
          { icon: 'bi bi-people-fill', text: 'Memperkuat silaturahmi dan jaringan antaralumni dari seluruh Indonesia lintas angkatan dan daerah.' },
          { icon: 'bi bi-chat-square-dots-fill', text: 'Memfasilitasi pertukaran gagasan dan pengalaman terbaik dalam kepemimpinan dan pengabdian masyarakat.' },
          { icon: 'bi bi-file-earmark-text-fill', text: 'Merumuskan rekomendasi kebijakan kepemudaan yang akan disampaikan kepada DPR RI dan pemerintah.' },
          { icon: 'bi bi-trophy-fill', text: 'Memberikan penghargaan kepada alumni berprestasi dalam berbagai bidang pengabdian.' },
          { icon: 'bi bi-lightbulb-fill', text: 'Merancang agenda kolaborasi program alumni untuk satu tahun ke depan.' },
        ],
        stats: [
          { number: '800+', label: 'Alumni Hadir', colorClass: 'primary' },
          { number: '34', label: 'Provinsi', colorClass: 'secondary' },
          { number: '3', label: 'Hari Penuh', colorClass: 'success' },
        ],
        activities: [
          { title: 'Sidang Pleno & Keynote', desc: 'Sesi pembukaan berskala besar menghadirkan tokoh nasional, anggota DPR RI, dan alumni inspiratif sebagai pembicara utama.' },
          { title: 'Workshop Tematik', desc: 'Lokakarya paralel yang membahas isu strategis: kebijakan publik, kepemimpinan pemuda, kewirausahaan sosial, dan diplomasi.' },
          { title: 'Alumni Awards Night', desc: 'Malam penghargaan untuk mengapresiasi alumni terbaik di berbagai kategori: kepemimpinan, advokasi, inovasi, dan pengabdian.' },
          { title: 'Networking Gala', desc: 'Sesi networking semi-formal untuk mempertemukan alumni dengan calon mitra, mentor, dan sesama pemimpin muda Indonesia.' },
        ],
        eligibility: `<p>Forum Nasional Alumni terbuka untuk <strong>seluruh alumni</strong> program Parlemen Remaja DPR RI dari semua angkatan. Peserta dapat mendaftar secara mandiri maupun melalui koordinator alumni provinsi masing-masing.</p>
        <ul class="mt-3" style="font-size:0.9rem; color:#495057; line-height:2;">
          <li>Pernah mengikuti program Parlemen Remaja DPR RI (angkatan apapun)</li>
          <li>Berkomitmen hadir selama minimal 2 dari 3 hari penyelenggaraan</li>
          <li>Mendaftarkan diri melalui portal alumni resmi paling lambat 30 hari sebelum acara</li>
          <li>Bersedia aktif berkontribusi dalam sesi-sesi yang diikuti</li>
        </ul>`,
        ctaDesc: 'Daftar sekarang untuk mendapatkan konfirmasi kehadiran di Forum Nasional Alumni tahun ini!',
        sidebarInfo: [
          { icon: 'bi bi-calendar3', label: 'Jadwal', value: 'April 2027 (akan diumumkan)' },
          { icon: 'bi bi-geo-alt-fill', label: 'Lokasi', value: 'Gedung DPR/MPR RI, Jakarta Pusat' },
          { icon: 'bi bi-people-fill', label: 'Kuota', value: '1.000 alumni' },
          { icon: 'bi bi-cash-coin', label: 'Biaya', value: 'Gratis untuk alumni terdaftar' },
          { icon: 'bi bi-clock-fill', label: 'Durasi', value: '3 hari (Jumat–Minggu)' },
          { icon: 'bi bi-envelope-fill', label: 'Kontak', value: 'alumni@parlemenremaja.id' },
        ],
      },

      'beasiswa-mentoring': {
        id: 'beasiswa-mentoring',
        category: 'Pendidikan',
        categoryColor: '#41174B',
        categoryBg: 'rgba(65,23,75,0.08)',
        heroGradient: 'linear-gradient(140deg, #41174B 0%, #7a3a8a 100%)',
        icon: 'bi bi-mortarboard-fill',
        iconThumbBg: 'linear-gradient(135deg,#e0e8f5,#d0deff)',
        title: 'Beasiswa & Mentoring',
        subtitle: 'Program pendampingan intensif alumni berprestasi untuk generasi berikutnya — mulai dari bimbingan karier hingga persiapan perguruan tinggi terbaik.',
        meta: [
          { icon: 'bi bi-calendar-event-fill', label: 'Buka: Januari & Juli' },
          { icon: 'bi bi-person-check-fill', label: '50 Mentee / Batch' },
          { icon: 'bi bi-clock-fill', label: 'Durasi: 6 Bulan' },
        ],
        about: `<p>Program Beasiswa & Mentoring adalah inisiatif unggulan Forum Alumni yang dirancang untuk mendampingi peserta muda Parlemen Remaja — khususnya mereka yang baru saja menyelesaikan program — dalam menavigasi langkah selanjutnya: persiapan kuliah, pemilihan jurusan, pengembangan karier, hingga pengembangan kepemimpinan.</p>
        <p class="mt-3">Setiap mentee akan dipasangkan dengan seorang alumni senior yang telah berpengalaman dan relevan di bidang yang diminati. Proses mentoring dilakukan secara daring maupun tatap muka selama 6 bulan, dilengkapi dengan sesi kelompok, webinar eksklusif, dan akses ke jaringan alumni nasional.</p>`,
        objectives: [
          { icon: 'bi bi-mortarboard-fill', text: 'Mendukung persiapan akademik peserta untuk masuk ke perguruan tinggi terbaik di dalam dan luar negeri.' },
          { icon: 'bi bi-briefcase-fill', text: 'Memberikan panduan karier berbasis pengalaman nyata alumni di berbagai sektor profesional.' },
          { icon: 'bi bi-diagram-3-fill', text: 'Memperluas jaringan sosial dan profesional mentee melalui ekosistem alumni.' },
          { icon: 'bi bi-award-fill', text: 'Mengidentifikasi dan mendukung potensi kepemimpinan yang dimiliki setiap mentee.' },
        ],
        stats: [
          { number: '150+', label: 'Alumni Mentor', colorClass: 'secondary' },
          { number: '500+', label: 'Mentee Dilayani', colorClass: 'primary' },
          { number: '87%', label: 'Diterima PTN/Luar Negeri', colorClass: 'success' },
        ],
        activities: [
          { title: 'One-on-One Mentoring Session', desc: 'Sesi pendampingan personal 2x per bulan antara mentee dan mentor yang relevan dengan bidang tujuan mentee.' },
          { title: 'Group Learning Circle', desc: 'Diskusi kelompok 10–15 mentee setiap 2 minggu untuk saling berbagi pengalaman dan pelajaran bersama fasilitator.' },
          { title: 'Career & Campus Talk', desc: 'Webinar dan talkshow eksklusif menghadirkan alumni yang kini berkarier atau studi di lembaga bergengsi nasional/internasional.' },
          { title: 'Alumni Networking Access', desc: 'Akses ke direktori alumni dan forum eksklusif untuk membangun relasi profesional sejak dini.' },
        ],
        eligibility: `<p>Program ini diperuntukkan bagi <strong>peserta Parlemen Remaja yang baru lulus SMA</strong> serta alumni yang ingin melanjutkan pendampingan karier. Seleksi dilakukan dua kali setahun.</p>
        <ul class="mt-3" style="font-size:0.9rem; color:#495057; line-height:2;">
          <li>Alumni Parlemen Remaja DPR RI (diutamakan 3 angkatan terakhir)</li>
          <li>Memiliki motivasi kuat dan komitmen hadir dalam seluruh sesi selama 6 bulan</li>
          <li>Mengisi formulir pendaftaran dan esai motivasi</li>
          <li>Lulus seleksi administrasi dan wawancara singkat</li>
        </ul>`,
        ctaDesc: 'Daftarkan diri sebagai mentee atau mentor untuk batch berikutnya.',
        sidebarInfo: [
          { icon: 'bi bi-calendar3', label: 'Pendaftaran', value: 'Januari & Juli setiap tahun' },
          { icon: 'bi bi-people-fill', label: 'Kuota', value: '50 mentee per batch' },
          { icon: 'bi bi-clock-fill', label: 'Durasi', value: '6 bulan per batch' },
          { icon: 'bi bi-cash-coin', label: 'Biaya', value: 'Gratis (seleksi ketat)' },
          { icon: 'bi bi-laptop-fill', label: 'Format', value: 'Hybrid (daring & tatap muka)' },
          { icon: 'bi bi-envelope-fill', label: 'Kontak', value: 'mentoring@parlemenremaja.id' },
        ],
      },

      'youth-policy-lab': {
        id: 'youth-policy-lab',
        category: 'Advokasi',
        categoryColor: '#1a7a45',
        categoryBg: 'rgba(26,122,69,0.08)',
        heroGradient: 'linear-gradient(140deg, #1a7a45 0%, #2daa60 100%)',
        icon: 'bi bi-megaphone-fill',
        iconThumbBg: 'linear-gradient(135deg,#e0f5e8,#c8f0d0)',
        title: 'Youth Policy Lab',
        subtitle: 'Ruang riset dan advokasi kebijakan publik yang melibatkan alumni dalam menyusun rekomendasi nyata untuk para pembuat keputusan nasional.',
        meta: [
          { icon: 'bi bi-calendar-event-fill', label: 'Berjalan sepanjang tahun' },
          { icon: 'bi bi-file-earmark-check-fill', label: '12+ Policy Brief/Tahun' },
          { icon: 'bi bi-building-fill', label: 'Mitra: DPR, Kementerian' },
        ],
        about: `<p>Youth Policy Lab (YPL) adalah laboratorium kebijakan publik yang dikelola alumni Parlemen Remaja DPR RI. YPL hadir sebagai respons atas kebutuhan akan ruang yang memungkinkan generasi muda untuk tidak sekadar mengkritik kebijakan, tetapi aktif menyumbangkan solusi berbasis riset kepada para pemangku kepentingan.</p>
        <p class="mt-3">Setiap tahun, YPL menghasilkan puluhan policy brief yang disusun oleh tim alumni peneliti lintas disiplin ilmu — dari hukum, ekonomi, ilmu politik, hingga teknologi. Rekomendasi tersebut kemudian dipresentasikan langsung kepada anggota DPR RI, kementerian terkait, dan organisasi internasional.</p>`,
        objectives: [
          { icon: 'bi bi-search', text: 'Menghasilkan analisis kebijakan publik berbasis data yang relevan dengan isu-isu aktual bangsa.' },
          { icon: 'bi bi-megaphone-fill', text: 'Memastikan suara dan perspektif pemuda terwakili dalam proses pembuatan kebijakan nasional.' },
          { icon: 'bi bi-diagram-3-fill', text: 'Membangun kapasitas riset dan advokasi para alumni melalui pelatihan intensif.' },
          { icon: 'bi bi-globe2', text: 'Menjalin kemitraan dengan lembaga riset nasional dan internasional untuk memperkuat kualitas analisis.' },
        ],
        stats: [
          { number: '48', label: 'Policy Brief Diterbitkan', colorClass: 'success' },
          { number: '12', label: 'Kementerian Mitra', colorClass: 'secondary' },
          { number: '200+', label: 'Peneliti Alumni', colorClass: 'primary' },
        ],
        activities: [
          { title: 'Policy Research Sprint', desc: 'Riset intensif 4–6 minggu dalam kelompok kecil untuk menghasilkan policy brief tentang isu prioritas yang ditetapkan.' },
          { title: 'Expert Discussion Panel', desc: 'Diskusi tematik menghadirkan pakar, akademisi, dan praktisi sebagai narasumber untuk memperkaya perspektif tim riset.' },
          { title: 'Policy Presentation to DPR', desc: 'Presentasi hasil riset dan rekomendasi langsung kepada Komisi DPR RI yang relevan, dihadiri anggota dewan dan staf ahli.' },
          { title: 'Annual Policy Report', desc: 'Penerbitan laporan tahunan komprehensif yang merangkum seluruh rekomendasi kebijakan yang dihasilkan sepanjang tahun.' },
        ],
        eligibility: `<p>Youth Policy Lab membuka kesempatan bagi alumni yang memiliki minat kuat di bidang riset, kebijakan publik, hukum, atau ilmu sosial. Tidak ada batas angkatan, selama memiliki komitmen penuh.</p>
        <ul class="mt-3" style="font-size:0.9rem; color:#495057; line-height:2;">
          <li>Alumni Parlemen Remaja DPR RI dari semua angkatan</li>
          <li>Mahasiswa aktif atau fresh graduate di bidang relevan (diutamakan)</li>
          <li>Memiliki kemampuan menulis dan berpikir analitis yang baik</li>
          <li>Berkomitmen menyelesaikan satu siklus riset (4–6 minggu) penuh</li>
          <li>Lulus seleksi proposal topik riset singkat</li>
        </ul>`,
        ctaDesc: 'Bergabunglah sebagai peneliti muda dan jadikan suaramu bagian dari kebijakan nyata.',
        sidebarInfo: [
          { icon: 'bi bi-calendar3', label: 'Sprint Berikutnya', value: 'Agustus 2026' },
          { icon: 'bi bi-people-fill', label: 'Tim per Topik', value: '5–8 orang' },
          { icon: 'bi bi-clock-fill', label: 'Durasi Sprint', value: '4–6 minggu' },
          { icon: 'bi bi-cash-coin', label: 'Biaya', value: 'Gratis' },
          { icon: 'bi bi-laptop-fill', label: 'Format', value: 'Hybrid' },
          { icon: 'bi bi-envelope-fill', label: 'Kontak', value: 'ypl@parlemenremaja.id' },
        ],
      },

      'podcast-series': {
        id: 'podcast-series',
        category: 'Media',
        categoryColor: '#d97706',
        categoryBg: 'rgba(217,119,6,0.08)',
        heroGradient: 'linear-gradient(140deg, #b45309 0%, #f59e0b 100%)',
        icon: 'bi bi-camera-video-fill',
        iconThumbBg: 'linear-gradient(135deg,#fff5e0,#ffe8c0)',
        title: 'Alumni Speaks: Podcast Series',
        subtitle: 'Serial konten audio-visual inspiratif yang menampilkan kisah nyata alumni Parlemen Remaja yang kini berkarier di berbagai bidang strategis.',
        meta: [
          { icon: 'bi bi-mic-fill', label: 'Rilis: 2x/bulan' },
          { icon: 'bi bi-play-circle-fill', label: '50.000+ pendengar' },
          { icon: 'bi bi-spotify', label: 'Tersedia di Spotify & YouTube' },
        ],
        about: `<p>Alumni Speaks adalah platform podcast dan konten video yang dikelola oleh Forum Alumni Parlemen Remaja DPR RI. Setiap episode menghadirkan seorang alumni sebagai bintang tamu untuk berbagi cerita perjalanan, tantangan, dan pelajaran hidupnya — dari masa ikut Parlemen Remaja hingga pencapaian yang diraihnya saat ini.</p>
        <p class="mt-3">Konten diproduksi secara profesional dan didistribusikan melalui berbagai platform digital, menjangkau ribuan pendengar muda di seluruh Indonesia. Podcast ini bukan hanya media hiburan, tetapi juga sumber inspirasi dan panduan praktis bagi generasi muda yang ingin mengikuti jejak para alumni.</p>`,
        objectives: [
          { icon: 'bi bi-broadcast-pin', text: 'Membangun platform media yang menampilkan narasi positif dan inspiratif dari kalangan pemuda Indonesia.' },
          { icon: 'bi bi-heart-fill', text: 'Menginspirasi generasi muda untuk berani bermimpi besar dan berkontribusi nyata bagi bangsa.' },
          { icon: 'bi bi-share-fill', text: 'Memperluas jangkauan dan visibilitas program Parlemen Remaja kepada masyarakat yang lebih luas.' },
          { icon: 'bi bi-camera-video-fill', text: 'Mendokumentasikan perjalanan dan prestasi alumni sebagai warisan sejarah gerakan kepemudaan Indonesia.' },
        ],
        stats: [
          { number: '60+', label: 'Episode Tayang', colorClass: 'primary' },
          { number: '50K+', label: 'Total Pendengar', colorClass: 'secondary' },
          { number: '4.8', label: 'Rating Rata-rata', colorClass: 'success' },
        ],
        activities: [
          { title: 'Produksi Episode Reguler', desc: 'Rekaman podcast dan video 2x per bulan di studio profesional, menampilkan alumni dari berbagai angkatan dan latar belakang.' },
          { title: 'Live Session & Q&A', desc: 'Sesi live streaming interaktif di Instagram dan YouTube setiap akhir bulan, memungkinkan pendengar bertanya langsung.' },
          { title: 'Episode Kolaborasi', desc: 'Episode khusus bersama mitra media nasional, universitas, dan tokoh nasional yang terinspirasi gerakan alumni.' },
          { title: 'Podcast Camp', desc: 'Workshop produksi podcast dan konten kreatif untuk alumni yang ingin belajar memproduksi konten digital profesional.' },
        ],
        eligibility: `<p>Program ini terbuka untuk <strong>semua alumni</strong> yang ingin berbagi kisah inspirasinya, serta alumni yang berminat terlibat di balik layar dalam produksi konten.</p>
        <ul class="mt-3" style="font-size:0.9rem; color:#495057; line-height:2;">
          <li>Menjadi narasumber: alumni dari semua angkatan dengan prestasi atau cerita inspiratif</li>
          <li>Tim produksi: alumni dengan minat di bidang media, audio, video, atau konten digital</li>
          <li>Tidak ada syarat khusus selain kemauan untuk berbagi dan berkolaborasi</li>
          <li>Pengajuan diri melalui formulir online atau rekomendasi dari koordinator alumni provinsi</li>
        </ul>`,
        ctaDesc: 'Mau berbagi ceritamu di Alumni Speaks? Daftar sebagai narasumber atau bergabung dengan tim produksi.',
        sidebarInfo: [
          { icon: 'bi bi-calendar3', label: 'Rilis', value: '2 episode per bulan' },
          { icon: 'bi bi-geo-alt-fill', label: 'Rekaman', value: 'Studio Jakarta & Remote' },
          { icon: 'bi bi-clock-fill', label: 'Durasi Ep.', value: '30–60 menit' },
          { icon: 'bi bi-play-circle-fill', label: 'Platform', value: 'Spotify, YouTube, Apple Podcast' },
          { icon: 'bi bi-cash-coin', label: 'Biaya', value: 'Gratis ditanggung program' },
          { icon: 'bi bi-envelope-fill', label: 'Kontak', value: 'media@parlemenremaja.id' },
        ],
      },

      'pertukaran-internasional': {
        id: 'pertukaran-internasional',
        category: 'Global',
        categoryColor: '#7a3a8a',
        categoryBg: 'rgba(122,58,138,0.08)',
        heroGradient: 'linear-gradient(140deg, #41174B 0%, #7a3a8a 60%, #9b5ea6 100%)',
        icon: 'bi bi-globe2',
        iconThumbBg: 'linear-gradient(135deg,#f0e0f5,#e8c0f0)',
        title: 'Program Pertukaran Internasional',
        subtitle: 'Fasilitasi keterlibatan alumni dalam forum parlemen muda tingkat regional dan global untuk memperluas wawasan dan membangun relasi diplomatik internasional.',
        meta: [
          { icon: 'bi bi-globe2', label: '15+ Negara Mitra' },
          { icon: 'bi bi-calendar-event-fill', label: 'Seleksi: Februari & Agustus' },
          { icon: 'bi bi-person-badge-fill', label: '30 Delegasi/Tahun' },
        ],
        about: `<p>Program Pertukaran Internasional adalah jembatan yang menghubungkan alumni Parlemen Remaja DPR RI dengan forum-forum parlemen muda bergengsi di tingkat ASEAN, Asia-Pasifik, dan dunia. Program ini lahir dari keyakinan bahwa pemimpin muda Indonesia harus memiliki perspektif global untuk mampu berkontribusi secara efektif di era yang semakin terhubung.</p>
        <p class="mt-3">Setiap tahun, alumni terpilih dikirim sebagai delegasi resmi Indonesia ke berbagai konferensi parlemen muda internasional, program pertukaran bilateral, dan forum kepemimpinan global. Mereka kembali membawa wawasan, jaringan, dan semangat baru untuk terus berkontribusi di tanah air.</p>`,
        objectives: [
          { icon: 'bi bi-globe2', text: 'Memperluas perspektif alumni tentang isu-isu global dan praktik demokrasi terbaik dari berbagai negara.' },
          { icon: 'bi bi-flag-fill', text: 'Menempatkan alumni sebagai representasi pemuda Indonesia yang berkualitas di panggung internasional.' },
          { icon: 'bi bi-diagram-3-fill', text: 'Membangun jaringan diplomatik pemuda lintas negara yang berkelanjutan dan berdampak.' },
          { icon: 'bi bi-translate', text: 'Meningkatkan kemampuan komunikasi lintas budaya dan bahasa asing para alumni.' },
        ],
        stats: [
          { number: '15+', label: 'Negara Mitra', colorClass: 'secondary' },
          { number: '120+', label: 'Delegasi Dikirim', colorClass: 'primary' },
          { number: '8', label: 'Forum Internasional/Tahun', colorClass: 'success' },
        ],
        activities: [
          { title: 'Seleksi & Persiapan Delegasi', desc: 'Proses seleksi ketat diikuti pelatihan intensif diplomatik, bahasa, dan protokol internasional selama 2 minggu sebelum keberangkatan.' },
          { title: 'Partisipasi Forum Internasional', desc: 'Keterlibatan aktif di forum seperti ASEAN Youth Parliament, IPU Young Parliamentarians, dan Commonwealth Youth Parliament.' },
          { title: 'Cultural Exchange Program', desc: 'Program pertukaran budaya bilateral dengan parlemen muda dari negara-negara sahabat melalui kunjungan dan hosting.' },
          { title: 'Post-Exchange Sharing', desc: 'Sesi berbagi wawasan dan pengalaman oleh delegasi yang telah kembali, disebarluaskan kepada seluruh komunitas alumni.' },
        ],
        eligibility: `<p>Program ini diperuntukkan bagi alumni yang memiliki kemampuan bahasa Inggris yang baik, rekam jejak kepemimpinan yang kuat, dan komitmen untuk merepresentasikan Indonesia dengan terbaik.</p>
        <ul class="mt-3" style="font-size:0.9rem; color:#495057; line-height:2;">
          <li>Alumni Parlemen Remaja DPR RI dari semua angkatan</li>
          <li>Berusia antara 18–30 tahun pada saat keberangkatan</li>
          <li>Kemampuan bahasa Inggris aktif (lisan dan tulisan); nilai TOEFL 550+ atau setara</li>
          <li>Tidak sedang atau pernah mewakili organisasi lain dalam forum serupa dalam 1 tahun terakhir</li>
          <li>Lulus seleksi berkas, tes kompetensi, dan wawancara panel</li>
        </ul>`,
        ctaDesc: 'Representasikan Indonesia di panggung dunia. Daftar seleksi delegasi sekarang!',
        sidebarInfo: [
          { icon: 'bi bi-calendar3', label: 'Pendaftaran', value: 'Februari & Agustus' },
          { icon: 'bi bi-people-fill', label: 'Kuota', value: '15 delegasi per periode' },
          { icon: 'bi bi-clock-fill', label: 'Durasi', value: '1–2 minggu per forum' },
          { icon: 'bi bi-cash-coin', label: 'Biaya', value: 'Ditanggung penuh program' },
          { icon: 'bi bi-translate', label: 'Bahasa', value: 'Inggris (wajib aktif)' },
          { icon: 'bi bi-envelope-fill', label: 'Kontak', value: 'global@parlemenremaja.id' },
        ],
      },

      'alumni-peduli': {
        id: 'alumni-peduli',
        category: 'Sosial',
        categoryColor: '#dc2626',
        categoryBg: 'rgba(220,38,38,0.08)',
        heroGradient: 'linear-gradient(140deg, #991b1b 0%, #dc2626 60%, #f87171 100%)',
        icon: 'bi bi-heart-fill',
        iconThumbBg: 'linear-gradient(135deg,#ffe0e0,#ffc8c8)',
        title: 'Gerakan Alumni Peduli',
        subtitle: 'Pengabdian masyarakat kolaboratif yang membawa literasi demokrasi, kepemimpinan, dan kepedulian sosial ke daerah-daerah yang paling membutuhkan.',
        meta: [
          { icon: 'bi bi-geo-alt-fill', label: 'Nasional (34 Provinsi)' },
          { icon: 'bi bi-calendar-event-fill', label: 'Berlangsung sepanjang tahun' },
          { icon: 'bi bi-heart-fill', label: '10.000+ Penerima Manfaat' },
        ],
        about: `<p>Gerakan Alumni Peduli adalah program pengabdian masyarakat terpadu yang melibatkan alumni Parlemen Remaja DPR RI dari seluruh Indonesia. Bergerak melalui jaringan koordinator provinsi, program ini menghadirkan berbagai kegiatan sosial yang berdampak langsung pada masyarakat — terutama di daerah terpencil dan tertinggal.</p>
        <p class="mt-3">Dari kampanye literasi demokrasi di sekolah-sekolah pelosok, bakti sosial kesehatan, pelatihan kepemimpinan remaja daerah, hingga advokasi isu lingkungan — setiap alumnus didorong untuk menjadi agen perubahan nyata di komunitas masing-masing.</p>`,
        objectives: [
          { icon: 'bi bi-book-fill', text: 'Meningkatkan literasi demokrasi dan pemahaman hak-hak sipil di kalangan pelajar dan masyarakat umum.' },
          { icon: 'bi bi-people-fill', text: 'Memperkuat keterlibatan alumni dalam kegiatan pengabdian masyarakat yang terstruktur dan berdampak terukur.' },
          { icon: 'bi bi-heart-fill', text: 'Menjangkau daerah-daerah 3T (terdepan, terluar, tertinggal) yang selama ini kurang mendapatkan perhatian.' },
          { icon: 'bi bi-recycle', text: 'Mendorong gerakan kepemudaan peduli lingkungan dan keberlanjutan di tingkat lokal.' },
        ],
        stats: [
          { number: '300+', label: 'Kegiatan/Tahun', colorClass: 'primary' },
          { number: '10K+', label: 'Penerima Manfaat', colorClass: 'secondary' },
          { number: '34', label: 'Provinsi Aktif', colorClass: 'success' },
        ],
        activities: [
          { title: 'Jelajah Demokrasi', desc: 'Roadshow literasi demokrasi ke sekolah-sekolah SMA/sederajat di daerah terpencil, dibawakan oleh alumni dalam format yang menarik dan interaktif.' },
          { title: 'Bakti Sosial Terpadu', desc: 'Kegiatan bakti sosial multisektoral: pemeriksaan kesehatan gratis, donasi buku dan perlengkapan sekolah, serta bedah rumah layak huni.' },
          { title: 'Youth Leadership Camp Daerah', desc: 'Pelatihan kepemimpinan intensif 3 hari untuk pelajar SMA di daerah yang diinisiasi oleh koordinator alumni provinsi setempat.' },
          { title: 'Kampanye Lingkungan Hidup', desc: 'Aksi nyata peduli lingkungan: penanaman pohon, pembersihan sungai, edukasi pengelolaan sampah, dan kampanye net-zero pemuda.' },
        ],
        eligibility: `<p>Gerakan Alumni Peduli terbuka seluas-luasnya bagi <strong>semua alumni</strong> yang ingin berkontribusi nyata kepada masyarakat. Tidak ada persyaratan khusus — semua bentuk partisipasi, besar maupun kecil, sangat berarti.</p>
        <ul class="mt-3" style="font-size:0.9rem; color:#495057; line-height:2;">
          <li>Semua alumni Parlemen Remaja DPR RI dari semua angkatan</li>
          <li>Bisa berpartisipasi secara individual maupun melalui koordinator alumni provinsi</li>
          <li>Bersedia mendedikasikan waktu minimal untuk satu kegiatan dalam setahun</li>
          <li>Untuk menginisiasi kegiatan baru: koordinasi dengan pengurus alumni provinsi setempat</li>
        </ul>`,
        ctaDesc: 'Jadilah bagian dari gerakan alumni yang peduli dan berdampak. Satu aksi kecilmu berarti besar bagi mereka.',
        sidebarInfo: [
          { icon: 'bi bi-calendar3', label: 'Jadwal', value: 'Sepanjang tahun (terjadwal)' },
          { icon: 'bi bi-geo-alt-fill', label: 'Cakupan', value: '34 Provinsi Indonesia' },
          { icon: 'bi bi-people-fill', label: 'Relawan', value: '500+ aktif per tahun' },
          { icon: 'bi bi-cash-coin', label: 'Biaya', value: 'Donasi sukarela / didukung sponsor' },
          { icon: 'bi bi-phone-fill', label: 'Koordinasi', value: 'Via koordinator provinsi' },
          { icon: 'bi bi-envelope-fill', label: 'Kontak', value: 'peduli@parlemenremaja.id' },
        ],
      },
    };

    // ============================================================
    // RENDER PAGE
    // ============================================================
    const params = new URLSearchParams(window.location.search);
    const programId = params.get('id') || 'forum-nasional';
    const data = programs[programId] || programs['forum-nasional'];

    // Page title
    document.title = data.title + ' — Parlemen Remaja DPR RI';
    document.getElementById('pageTitle').textContent = data.title + ' — Parlemen Remaja DPR RI';

    // Hero gradient
    document.getElementById('pd-hero').style.background = data.heroGradient;

    // Breadcrumb
    document.getElementById('breadcrumbCurrent').textContent = data.title;

    // Hero tag
    const tagEl = document.getElementById('heroTag');
    tagEl.innerHTML = `<i class="bi bi-tag-fill"></i> ${data.category}`;

    // Hero title & sub
    document.getElementById('heroTitle').textContent = data.title;
    document.getElementById('heroSub').textContent = data.subtitle;

    // Hero icon
    document.getElementById('heroIcon').className = data.icon;

    // Hero meta
    const metaEl = document.getElementById('heroMeta');
    data.meta.forEach(m => {
      const div = document.createElement('div');
      div.className = 'pd-meta-item';
      div.innerHTML = `<i class="${m.icon}"></i> ${m.label}`;
      metaEl.appendChild(div);
    });

    // About
    document.getElementById('aboutHeading').textContent = 'Tentang ' + data.title;
    document.getElementById('aboutBody').innerHTML = data.about;

    // Objectives
    const objEl = document.getElementById('objectiveList');
    data.objectives.forEach(o => {
      const li = document.createElement('li');
      li.innerHTML = `<span class="obj-icon"><i class="${o.icon}"></i></span><span>${o.text}</span>`;
      objEl.appendChild(li);
    });

    // Stats
    const statEl = document.getElementById('statRow');
    data.stats.forEach(s => {
      const div = document.createElement('div');
      div.className = `pd-stat-box ${s.colorClass}`;
      div.innerHTML = `<span class="pd-stat-number ${s.colorClass}">${s.number}</span><div class="pd-stat-label">${s.label}</div>`;
      statEl.appendChild(div);
    });

    // Activities
    const actEl = document.getElementById('activityList');
    data.activities.forEach(a => {
      const div = document.createElement('div');
      div.className = 'pd-activity-card';
      div.innerHTML = `<h6><i class="bi bi-check2-circle me-2" style="color:#bf0050;"></i>${a.title}</h6><p>${a.desc}</p>`;
      actEl.appendChild(div);
    });

    // Eligibility
    document.getElementById('eligibilityBody').innerHTML = data.eligibility;

    // CTA desc
    document.getElementById('ctaDesc').textContent = data.ctaDesc;

    // Sidebar info
    const sbEl = document.getElementById('sidebarInfo');
    data.sidebarInfo.forEach(r => {
      const div = document.createElement('div');
      div.className = 'pd-info-row';
      div.innerHTML = `
        <div class="pd-info-icon"><i class="${r.icon}"></i></div>
        <div>
          <span class="pd-info-label">${r.label}</span>
          <span class="pd-info-value">${r.value}</span>
        </div>`;
      sbEl.appendChild(div);
    });

    // Other programs
    const otherEl = document.getElementById('otherPrograms');
    const others = Object.values(programs).filter(p => p.id !== data.id);
    others.slice(0, 4).forEach(p => {
      const a = document.createElement('a');
      a.href = `{{ route('parja.public.program') }}?id=${p.id}`;
      a.className = 'pd-other-card';
      a.innerHTML = `
        <div class="pd-other-thumb" style="background:${p.iconThumbBg};">
          <i class="${p.icon}" style="color:${p.categoryColor};"></i>
        </div>
        <div>
          <h6>${p.title}</h6>
          <span style="color:${p.categoryColor}; background:${p.categoryBg}; padding:2px 8px; border-radius:50px; font-size:0.68rem; font-weight:700;">${p.category}</span>
        </div>`;
      otherEl.appendChild(a);
    });

    // ============================================================
    // SCROLL REVEAL
    // ============================================================
    const revealEls = document.querySelectorAll('.reveal');
    const revealObs = new IntersectionObserver(entries => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('visible');
          revealObs.unobserve(entry.target);
        }
      });
    }, { threshold: 0.1 });
    revealEls.forEach(el => revealObs.observe(el));

    // ============================================================
    // MOBILE NAV
    // ============================================================
    document.querySelectorAll('#navbarContent .nav-link').forEach(link => {
      link.addEventListener('click', () => {
        const nav = document.getElementById('navbarContent');
        if (typeof bootstrap !== 'undefined') {
          bootstrap.Collapse.getOrCreateInstance(nav).hide();
        }
      });
    });
  </script>

</body>
</html>
