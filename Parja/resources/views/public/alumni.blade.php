<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="description"
    content="Alumni Parlemen Remaja DPR RI — Jaringan alumni program simulasi sidang parlemen untuk pelajar se-Indonesia. Bergabunglah bersama ribuan alumni yang telah berkontribusi untuk bangsa." />
  <title>Alumni — Parlemen Remaja DPR RI</title>

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
    /* Smooth scroll untuk anchor in-page (mis. tombol "Pelajari Lebih Lanjut" → #al-about).
       scroll-padding-top memberi jarak dari navbar sticky agar judul section tidak ketutup. */
    html { scroll-behavior: smooth; scroll-padding-top: 90px; }

    /* ============================================================
       ALUMNI PAGE — HERO
    ============================================================ */
    #alumni-hero {
      min-height: 100vh;
      background: linear-gradient(140deg, #41174B 0%, #940040 55%, #ff4d85 100%);
      position: relative;
      overflow: hidden;
      display: flex;
      flex-direction: column;
      justify-content: center;
      padding-top: 90px;
      padding-bottom: 70px;
    }

    #alumni-hero::before {
      content: '';
      position: absolute;
      top: -160px;
      right: -160px;
      width: 640px;
      height: 640px;
      border-radius: 50%;
      background: rgba(255, 255, 255, 0.05);
      pointer-events: none;
    }

    #alumni-hero::after {
      content: '';
      position: absolute;
      bottom: 60px;
      left: -120px;
      width: 440px;
      height: 440px;
      border-radius: 50%;
      background: rgba(255, 255, 255, 0.04);
      pointer-events: none;
    }

    .hero-geo-sq {
      position: absolute;
      top: 28%;
      right: 6%;
      width: 180px;
      height: 180px;
      border: 2px solid rgba(255, 255, 255, 0.07);
      border-radius: 28px;
      transform: rotate(18deg);
      pointer-events: none;
    }

    .hero-geo-circle {
      position: absolute;
      bottom: 22%;
      right: 20%;
      width: 90px;
      height: 90px;
      border: 2px solid rgba(255, 255, 255, 0.06);
      border-radius: 50%;
      pointer-events: none;
    }

    .alumni-hero-headline {
      font-family: 'Poppins', sans-serif;
      font-weight: 800;
      font-size: clamp(2.1rem, 5.2vw, 3.7rem);
      line-height: 1.18;
      color: #fff;
      text-shadow: 0 2px 20px rgba(0, 0, 0, 0.12);
    }

    .alumni-hero-headline span {
      color: #ffe0ec;
    }

    .alumni-hero-sub {
      font-family: 'Inter', sans-serif;
      font-size: clamp(0.94rem, 1.9vw, 1.1rem);
      color: rgba(255, 255, 255, 0.86);
      line-height: 1.78;
      max-width: 560px;
    }

    .alumni-hero-img-wrap {
      border-radius: 24px;
      background: rgba(255, 255, 255, 0.10);
      border: 1px solid rgba(255, 255, 255, 0.18);
      backdrop-filter: blur(8px);
      -webkit-backdrop-filter: blur(8px);
      overflow: hidden;
      box-shadow: 0 24px 64px rgba(0, 0, 0, 0.22);
      aspect-ratio: 4 / 3;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .alumni-hero-img-placeholder {
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 12px;
      color: rgba(255, 255, 255, 0.65);
      font-size: 0.83rem;
      text-align: center;
      padding: 20px;
    }

    .alumni-hero-img-placeholder i {
      font-size: 4.5rem;
      opacity: 0.45;
    }

    .hero-wave-alumni {
      position: absolute;
      bottom: -2px;
      left: 0;
      width: 100%;
      line-height: 0;
      z-index: 1;
    }

    .hero-wave-alumni svg {
      display: block;
      width: 100%;
    }

    .stat-float-card {
      background: #fff;
      border-radius: 14px;
      padding: 12px 16px;
      box-shadow: 0 8px 24px rgba(0, 0, 0, 0.13);
      display: flex;
      align-items: center;
      gap: 10px;
      flex: 1;
    }

    .stat-float-icon {
      width: 38px;
      height: 38px;
      background: var(--pr-gradient);
      border-radius: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
      color: #fff;
      font-size: 1.1rem;
      flex-shrink: 0;
    }

    .stat-float-icon.secondary {
      background: var(--pr-secondary-gradient);
    }

    .stat-float-text strong {
      font-family: 'Poppins', sans-serif;
      font-size: 0.84rem;
      color: #212529;
      display: block;
      line-height: 1.2;
    }

    .stat-float-text span {
      font-size: 0.7rem;
      color: #6c757d;
    }

    /* ============================================================
       ABOUT SECTION
    ============================================================ */
    #al-about {
      background: #fff;
    }

    .about-collage {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 12px;
      border-radius: 20px;
      overflow: hidden;
    }

    .collage-cell {
      border-radius: 14px;
      overflow: hidden;
      background: linear-gradient(135deg, #f5e9f7 0%, #ffe0ec 100%);
      display: flex;
      align-items: center;
      justify-content: center;
      color: #bf0050;
      position: relative;
    }

    .collage-cell.span-row {
      grid-row: span 2;
      min-height: 260px;
    }

    .collage-cell:not(.span-row) {
      aspect-ratio: 4 / 3;
    }

    .collage-cell.alt {
      background: linear-gradient(135deg, #edf0f7 0%, #d8e0f5 100%);
      color: #41174B;
    }

    .collage-cell i {
      font-size: 2.8rem;
      opacity: 0.32;
    }

    .collage-label {
      position: absolute;
      bottom: 8px;
      left: 10px;
      font-family: 'Poppins', sans-serif;
      font-size: 0.66rem;
      font-weight: 700;
      background: rgba(255, 255, 255, 0.88);
      color: #bf0050;
      padding: 3px 10px;
      border-radius: 50px;
    }

    .visi-misi-box {
      background: linear-gradient(135deg, rgba(191, 0, 80, 0.05) 0%, rgba(65, 23, 75, 0.05) 100%);
      border-radius: 14px;
      padding: 18px 20px;
      border-left: 4px solid #bf0050;
      margin-bottom: 14px;
    }

    .visi-misi-box.misi {
      border-left-color: #41174B;
    }

    .visi-misi-box h6 {
      font-family: 'Poppins', sans-serif;
      font-size: 0.72rem;
      font-weight: 700;
      letter-spacing: 0.09em;
      text-transform: uppercase;
      color: #bf0050;
      margin-bottom: 6px;
    }

    .visi-misi-box.misi h6 {
      color: #41174B;
    }

    .visi-misi-box p {
      font-size: 0.87rem;
      color: #495057;
      margin: 0;
      line-height: 1.68;
    }

    /* ============================================================
       CORE VALUES
    ============================================================ */
    #al-values {
      background: #f8f9fa;
    }

    .value-card {
      background: #fff;
      border-radius: 20px;
      padding: 36px 26px 30px;
      text-align: center;
      box-shadow: 0 2px 16px rgba(33, 37, 41, 0.07);
      transition: all 0.35s ease;
      height: 100%;
      border-top: 3px solid transparent;
      position: relative;
      overflow: hidden;
    }

    .value-card::before {
      content: '';
      position: absolute;
      inset: 0;
      background: var(--pr-gradient);
      opacity: 0;
      transition: opacity 0.35s ease;
      border-radius: 20px;
    }

    .value-card:hover {
      box-shadow: 0 14px 40px rgba(191, 0, 80, 0.14);
      transform: translateY(-7px);
      border-top-color: #bf0050;
    }

    .value-card-inner {
      position: relative;
      z-index: 1;
    }

    .value-icon-wrap {
      width: 72px;
      height: 72px;
      margin: 0 auto 20px;
      background: var(--pr-gradient-soft);
      border-radius: 20px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.8rem;
      color: #bf0050;
      transition: all 0.35s ease;
    }

    .value-card:hover .value-icon-wrap {
      background: rgba(255, 255, 255, 0.2);
      color: #fff;
    }

    .value-card h5 {
      font-family: 'Poppins', sans-serif;
      font-weight: 700;
      font-size: 1rem;
      color: #212529;
      margin-bottom: 10px;
      transition: color 0.3s;
    }

    .value-card:hover h5 {
      color: #fff;
    }

    .value-card p {
      font-size: 0.83rem;
      color: #6c757d;
      line-height: 1.65;
      margin: 0;
      display: -webkit-box;
      -webkit-line-clamp: 3;
      -webkit-box-orient: vertical;
      overflow: hidden;
      transition: color 0.3s;
    }

    .value-card:hover p {
      color: rgba(255, 255, 255, 0.82);
    }

    .value-card:hover::before {
      opacity: 1;
    }

    /* ============================================================
       PROGRAMS
    ============================================================ */
    #al-programs {
      background: #fff;
    }

    .program-card {
      border: none;
      border-radius: 20px;
      overflow: hidden;
      box-shadow: 0 2px 16px rgba(33, 37, 41, 0.07);
      transition: all 0.35s ease;
      height: 100%;
    }

    .program-card:hover {
      box-shadow: 0 14px 44px rgba(33, 37, 41, 0.16);
      transform: translateY(-7px);
    }

    .program-card-thumb {
      width: 100%;
      aspect-ratio: 16 / 9;
      background: linear-gradient(135deg, #f0e0e8, #ffe0ec);
      position: relative;
      display: flex;
      align-items: center;
      justify-content: center;
      overflow: hidden;
    }

    .program-card-thumb i {
      font-size: 3rem;
      color: #bf0050;
      opacity: 0.32;
    }

    .program-card .card-body {
      padding: 22px 22px 14px;
    }

    .program-card .card-footer {
      background: transparent;
      border-top: 1px solid #f0f0f0;
      padding: 14px 22px;
    }

    .program-card h5 {
      font-family: 'Poppins', sans-serif;
      font-weight: 700;
      font-size: 0.95rem;
      color: #212529;
      margin-bottom: 8px;
      line-height: 1.42;
    }

    .program-card p {
      font-size: 0.82rem;
      color: #6c757d;
      line-height: 1.65;
      margin: 0;
    }

    .read-more-arrow {
      font-family: 'Poppins', sans-serif;
      font-size: 0.82rem;
      font-weight: 600;
      color: #bf0050;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 6px;
      transition: gap 0.22s ease;
    }

    .read-more-arrow:hover {
      gap: 12px;
      color: #bf0050;
    }

    /* ============================================================
       STATS / IMPACT
    ============================================================ */
    #al-stats {
      position: relative;
      background: var(--pr-secondary-gradient);
      overflow: hidden;
    }

    .stats-deco-ring {
      position: absolute;
      border-radius: 50%;
      pointer-events: none;
    }

    .stats-deco-ring.ring-1 {
      top: -80px;
      left: -80px;
      width: 380px;
      height: 380px;
      border: 44px solid rgba(255, 255, 255, 0.045);
    }

    .stats-deco-ring.ring-2 {
      bottom: -100px;
      right: -100px;
      width: 440px;
      height: 440px;
      border: 44px solid rgba(255, 255, 255, 0.035);
    }

    .stats-deco-square {
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%) rotate(28deg);
      width: 580px;
      height: 580px;
      border: 2px solid rgba(255, 255, 255, 0.04);
      border-radius: 60px;
      pointer-events: none;
    }

    .stat-box {
      text-align: center;
      padding: 24px 10px;
    }

    .stat-icon-wrap {
      width: 66px;
      height: 66px;
      background: rgba(255, 255, 255, 0.12);
      border: 1px solid rgba(255, 255, 255, 0.18);
      border-radius: 18px;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 18px;
      font-size: 1.7rem;
      color: #fff;
      transition: all 0.3s ease;
    }

    .stat-box:hover .stat-icon-wrap {
      background: rgba(255, 255, 255, 0.22);
      transform: translateY(-3px);
    }

    .stat-number {
      font-family: 'Poppins', sans-serif;
      font-weight: 800;
      font-size: clamp(2.2rem, 4vw, 3.2rem);
      color: #fff;
      line-height: 1;
      display: block;
    }

    .stat-label {
      font-family: 'Inter', sans-serif;
      font-size: 0.84rem;
      color: rgba(255, 255, 255, 0.72);
      margin-top: 8px;
      line-height: 1.45;
    }

    .stat-divider-v {
      width: 1px;
      background: rgba(255, 255, 255, 0.14);
    }

    /* ============================================================
       TESTIMONIALS
    ============================================================ */
    #al-testimoni {
      background: #f8f9fa;
    }

    .testi-slider-outer {
      overflow: hidden;
      border-radius: 24px;
    }

    .testi-track {
      display: flex;
      transition: transform 0.52s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .testi-slide {
      min-width: 100%;
      display: flex;
      justify-content: center;
      padding: 4px 8px 16px;
    }

    .testi-card {
      background: #fff;
      border-radius: 24px;
      padding: 42px 48px;
      box-shadow: 0 4px 28px rgba(33, 37, 41, 0.08);
      position: relative;
      max-width: 800px;
      width: 100%;
    }

    @media (max-width: 576px) {
      .testi-card {
        padding: 28px 22px;
      }
    }

    .quote-mark {
      position: absolute;
      top: 14px;
      left: 22px;
      font-family: Georgia, 'Times New Roman', serif;
      font-size: 11rem;
      line-height: 1;
      color: rgba(191, 0, 80, 0.055);
      pointer-events: none;
      user-select: none;
      font-weight: 700;
    }

    .testi-stars {
      color: #f59e0b;
      font-size: 0.87rem;
      margin-bottom: 14px;
    }

    .testi-text {
      font-family: 'Inter', sans-serif;
      font-size: 1rem;
      color: #343a40;
      line-height: 1.82;
      font-style: italic;
      position: relative;
      z-index: 1;
      margin-bottom: 30px;
    }

    .testi-author {
      display: flex;
      align-items: center;
      gap: 16px;
    }

    .testi-avatar {
      width: 58px;
      height: 58px;
      border-radius: 50%;
      background: var(--pr-gradient);
      display: flex;
      align-items: center;
      justify-content: center;
      color: #fff;
      font-family: 'Poppins', sans-serif;
      font-size: 1.1rem;
      font-weight: 700;
      flex-shrink: 0;
      border: 3px solid #fff;
      box-shadow: 0 4px 14px rgba(191, 0, 80, 0.22);
      overflow: hidden;
    }

    .testi-avatar img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    .testi-name {
      font-family: 'Poppins', sans-serif;
      font-weight: 700;
      font-size: 0.9rem;
      color: #212529;
      margin-bottom: 3px;
    }

    .testi-origin {
      font-size: 0.78rem;
      color: #6c757d;
    }

    .testi-nav-row {
      display: flex;
      justify-content: center;
      align-items: center;
      gap: 12px;
      margin-top: 28px;
    }

    .testi-btn {
      width: 44px;
      height: 44px;
      border-radius: 12px;
      background: #fff;
      border: 2px solid #e2e6ea;
      color: #495057;
      font-size: 1rem;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      transition: all 0.25s ease;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    .testi-btn:hover {
      background: var(--pr-gradient);
      border-color: transparent;
      color: #fff;
      box-shadow: var(--pr-shadow-sm);
    }

    .testi-dots {
      display: flex;
      gap: 7px;
      align-items: center;
    }

    .testi-dot {
      width: 8px;
      height: 8px;
      border-radius: 50%;
      background: #ced4da;
      transition: all 0.3s ease;
      cursor: pointer;
    }

    .testi-dot.active {
      width: 26px;
      border-radius: 4px;
      background: #bf0050;
    }

    /* ============================================================
       NEWS / BLOG
    ============================================================ */
    #al-news {
      background: #fff;
    }

    .news-card {
      border: none;
      border-radius: 18px;
      overflow: hidden;
      box-shadow: 0 2px 16px rgba(33, 37, 41, 0.07);
      transition: all 0.35s ease;
      height: 100%;
    }

    .news-card:hover {
      box-shadow: 0 10px 38px rgba(33, 37, 41, 0.14);
      transform: translateY(-6px);
    }

    .news-thumb {
      position: relative;
      aspect-ratio: 16 / 9;
      overflow: hidden;
      background: linear-gradient(135deg, #f5e9f7, #ffe0ec);
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .news-thumb img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      position: absolute;
      inset: 0;
    }

    .news-thumb i {
      font-size: 3rem;
      color: #bf0050;
      opacity: 0.28;
    }

    .news-cat {
      position: absolute;
      top: 12px;
      left: 12px;
      font-family: 'Poppins', sans-serif;
      font-size: 0.67rem;
      font-weight: 700;
      letter-spacing: 0.06em;
      text-transform: uppercase;
      padding: 4px 12px;
      border-radius: 50px;
      background: #bf0050;
      color: #fff;
      z-index: 2;
    }

    .news-cat.opini {
      background: #41174B;
    }

    .news-cat.kegiatan {
      background: #1a7a45;
    }

    .news-card .card-body {
      padding: 20px 22px 22px;
    }

    .news-card h5 {
      font-family: 'Poppins', sans-serif;
      font-weight: 700;
      font-size: 0.94rem;
      color: #212529;
      line-height: 1.45;
      margin-bottom: 8px;
    }

    .news-date {
      font-size: 0.72rem;
      color: #adb5bd;
      display: flex;
      align-items: center;
      gap: 5px;
      margin-bottom: 10px;
    }

    .news-excerpt {
      font-size: 0.8rem;
      color: #6c757d;
      line-height: 1.65;
      margin: 0;
      display: -webkit-box;
      -webkit-line-clamp: 3;
      -webkit-box-orient: vertical;
      overflow: hidden;
    }

    /* ============================================================
       PARTNERS — INFINITE MARQUEE
    ============================================================ */
    #al-partners {
      background: #f8f9fa;
    }

    .marquee-outer {
      overflow: hidden;
      position: relative;
    }

    .marquee-outer::before,
    .marquee-outer::after {
      content: '';
      position: absolute;
      top: 0;
      bottom: 0;
      width: 130px;
      z-index: 2;
      pointer-events: none;
    }

    .marquee-outer::before {
      left: 0;
      background: linear-gradient(to right, #f8f9fa 0%, transparent 100%);
    }

    .marquee-outer::after {
      right: 0;
      background: linear-gradient(to left, #f8f9fa 0%, transparent 100%);
    }

    .marquee-track {
      display: flex;
      align-items: center;
      gap: 20px;
      width: max-content;
      animation: infiniteScroll 30s linear infinite;
    }

    .marquee-track.reverse {
      animation: infiniteScrollReverse 28s linear infinite;
    }

    .marquee-outer:hover .marquee-track {
      animation-play-state: paused;
    }

    @keyframes infiniteScroll {
      0% {
        transform: translateX(0);
      }

      100% {
        transform: translateX(-50%);
      }
    }

    @keyframes infiniteScrollReverse {
      0% {
        transform: translateX(-50%);
      }

      100% {
        transform: translateX(0);
      }
    }

    .partner-chip {
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      min-width: 150px;
      height: 76px;
      background: #fff;
      border-radius: 14px;
      padding: 12px 22px;
      box-shadow: 0 1px 8px rgba(0, 0, 0, 0.055);
      filter: grayscale(100%) opacity(0.52);
      transition: all 0.3s ease;
      flex-shrink: 0;
      gap: 4px;
    }

    .partner-chip:hover {
      filter: grayscale(0%) opacity(1);
      box-shadow: 0 4px 18px rgba(191, 0, 80, 0.12);
      transform: translateY(-3px);
    }

    .partner-chip i {
      font-size: 1.5rem;
      color: #666;
    }

    .partner-chip span {
      font-family: 'Poppins', sans-serif;
      font-size: 0.75rem;
      font-weight: 700;
      color: #555;
      text-align: center;
      line-height: 1.2;
    }

    /* ============================================================
       CTA SECTION
    ============================================================ */
    #al-cta {
      background: linear-gradient(140deg, #41174B 0%, #bf0050 100%);
      position: relative;
      overflow: hidden;
    }

    #al-cta::before {
      content: '';
      position: absolute;
      top: -100px;
      left: -100px;
      width: 520px;
      height: 520px;
      border-radius: 50%;
      background: rgba(255, 255, 255, 0.04);
      pointer-events: none;
    }

    #al-cta::after {
      content: '';
      position: absolute;
      bottom: -80px;
      right: -80px;
      width: 400px;
      height: 400px;
      border-radius: 50%;
      background: rgba(255, 255, 255, 0.05);
      pointer-events: none;
    }

    .cta-headline {
      font-family: 'Poppins', sans-serif;
      font-weight: 800;
      font-size: clamp(1.9rem, 4.2vw, 3.1rem);
      color: #fff;
      line-height: 1.22;
    }

    .cta-sub {
      font-family: 'Inter', sans-serif;
      font-size: clamp(0.92rem, 1.8vw, 1.05rem);
      color: rgba(255, 255, 255, 0.8);
      line-height: 1.75;
      max-width: 580px;
      margin: 0 auto;
    }

    .btn-cta-white {
      background: #fff;
      color: #bf0050;
      font-family: 'Poppins', sans-serif;
      font-weight: 700;
      font-size: 1.05rem;
      padding: 16px 46px;
      border-radius: 50px;
      border: none;
      transition: all 0.3s ease;
      box-shadow: 0 8px 30px rgba(0, 0, 0, 0.2);
      text-decoration: none;
      display: inline-block;
    }

    .btn-cta-white:hover {
      transform: translateY(-4px);
      box-shadow: 0 16px 40px rgba(0, 0, 0, 0.28);
      color: #a8003f;
    }

    /* ============================================================
       SCROLL REVEAL UTILITY
    ============================================================ */
    .reveal {
      opacity: 0;
      transform: translateY(28px);
      transition: opacity 0.62s ease, transform 0.62s ease;
    }

    .reveal.visible {
      opacity: 1;
      transform: translateY(0);
    }

    .reveal-d1 {
      transition-delay: 0.1s;
    }

    .reveal-d2 {
      transition-delay: 0.2s;
    }

    .reveal-d3 {
      transition-delay: 0.3s;
    }

    .reveal-d4 {
      transition-delay: 0.4s;
    }

    /* ============================================================
       HALL OF FAME
    ============================================================ */
    #al-hof {
      background: #f8f9fa;
      overflow: hidden;
    }

    .hof-slider-outer {
      overflow: hidden;
      padding: 4px 2px 8px;
    }

    .hof-track {
      display: flex;
      gap: 1.5rem;
      transition: transform 0.52s cubic-bezier(0.4, 0, 0.2, 1);
      will-change: transform;
    }

    .hof-slide {
      flex: 0 0 calc((100% - 3rem) / 3);
    }

    @media (max-width: 991px) {
      .hof-slide {
        flex: 0 0 calc((100% - 1.5rem) / 2);
      }
    }

    @media (max-width: 575px) {
      .hof-slide {
        flex: 0 0 100%;
      }
    }

    .hof-card {
      background: #fff;
      border-radius: 22px;
      padding: 32px 24px 26px;
      box-shadow: var(--pr-card-shadow);
      transition: box-shadow 0.35s ease, transform 0.35s ease;
      border: 1px solid rgba(0, 0, 0, 0.05);
      position: relative;
      overflow: hidden;
      height: 100%;
    }

    .hof-card::after {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 4px;
      background: var(--pr-gradient);
      border-radius: 22px 22px 0 0;
    }

    .hof-card:hover {
      box-shadow: var(--pr-card-shadow-hover);
      transform: translateY(-7px);
    }

    .hof-rank-badge {
      position: absolute;
      top: 20px;
      right: 20px;
      width: 34px;
      height: 34px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-family: 'Poppins', sans-serif;
      font-weight: 800;
      font-size: 0.68rem;
      letter-spacing: 0.01em;
      z-index: 1;
    }

    .hof-avatar {
      width: 92px;
      height: 92px;
      border-radius: 50%;
      background: var(--pr-gradient);
      display: flex;
      align-items: center;
      justify-content: center;
      font-family: 'Poppins', sans-serif;
      font-weight: 800;
      font-size: 1.7rem;
      color: #fff;
      margin: 0 auto 14px;
      border: 4px solid #fff;
      box-shadow: 0 4px 18px rgba(191, 0, 80, 0.22);
      overflow: hidden;
      flex-shrink: 0;
    }

    .hof-avatar.alt {
      background: var(--pr-secondary-gradient);
      box-shadow: 0 4px 18px rgba(65, 23, 75, 0.22);
    }

    .hof-avatar img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    .hof-name {
      font-family: 'Poppins', sans-serif;
      font-weight: 700;
      font-size: 1rem;
      color: #212529;
      margin-bottom: 8px;
      line-height: 1.3;
    }

    .hof-social-link {
      display: inline-flex;
      align-items: center;
      gap: 5px;
      font-size: 0.77rem;
      font-weight: 500;
      color: #bf0050;
      text-decoration: none;
      transition: color 0.2s ease, background 0.2s ease;
      padding: 4px 12px;
      background: var(--pr-gradient-soft);
      border-radius: 50px;
    }

    .hof-social-link:hover {
      color: #a8003f;
      background: rgba(191, 0, 80, 0.13);
    }

    .hof-divider {
      height: 1px;
      background: #f0f0f0;
      margin: 18px 0;
    }

    .hof-achievement-label {
      font-family: 'Poppins', sans-serif;
      font-size: 0.64rem;
      font-weight: 700;
      letter-spacing: 0.1em;
      text-transform: uppercase;
      color: #bf0050;
      display: flex;
      align-items: center;
      gap: 5px;
      margin-bottom: 8px;
    }

    .hof-achievement-title {
      font-family: 'Inter', sans-serif;
      font-weight: 500;
      font-size: 0.84rem;
      color: #343a40;
      line-height: 1.55;
      margin-bottom: 14px;
      display: -webkit-box;
      -webkit-line-clamp: 3;
      -webkit-box-orient: vertical;
      overflow: hidden;
    }

    .hof-meta {
      display: flex;
      align-items: center;
      gap: 8px;
      flex-wrap: wrap;
    }

    .hof-meta-badge {
      display: inline-flex;
      align-items: center;
      gap: 4px;
      font-size: 0.69rem;
      font-weight: 600;
      padding: 3px 10px;
      border-radius: 50px;
    }

    .hof-meta-badge.year {
      background: #f1f3f5;
      color: #6c757d;
    }

    .hof-meta-badge.angkatan {
      background: var(--pr-secondary-gradient-soft);
      color: #41174B;
    }

    /* Nav controls */
    .hof-nav-row {
      display: flex;
      justify-content: center;
      align-items: center;
      gap: 12px;
      margin-top: 36px;
    }

    .hof-btn {
      width: 44px;
      height: 44px;
      border-radius: 12px;
      background: #fff;
      border: 2px solid #e2e6ea;
      color: #495057;
      font-size: 1rem;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      transition: all 0.25s ease;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    .hof-btn:hover {
      background: var(--pr-gradient);
      border-color: transparent;
      color: #fff;
      box-shadow: var(--pr-shadow-sm);
    }

    .hof-btn:disabled {
      opacity: 0.35;
      cursor: not-allowed;
      pointer-events: none;
    }

    .hof-dots {
      display: flex;
      gap: 7px;
      align-items: center;
    }

    .hof-dot {
      width: 8px;
      height: 8px;
      border-radius: 50%;
      background: #ced4da;
      transition: all 0.3s ease;
      cursor: pointer;
      border: none;
      padding: 0;
    }

    .hof-dot.active {
      width: 26px;
      border-radius: 4px;
      background: #bf0050;
    }
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
          <a href="{{ route('alumni.feed.index') }}" class="btn btn-gradient btn-sm rounded-pill px-4 py-2"
            style="font-size:0.83rem; font-weight:600;">
            <i class="bi bi-person-plus-fill me-1"></i>Daftar Sekarang
          </a>
        </div>
      </div>

    </div>
  </nav>


  <!-- ============================================================
       1. HERO SECTION
  ============================================================ -->
  <section id="al-hero">
    <div id="alumni-hero">
      <div class="hero-geo-sq"></div>
      <div class="hero-geo-circle"></div>

      <div class="container position-relative" style="z-index: 2;">
        <div class="row align-items-center gy-5 py-4">

          <!-- Text -->
          <div class="col-lg-6">
            <div class="hero-eyebrow animate-fade-up">
              <i class="bi bi-people-fill"></i>
              Jaringan Alumni Parlemen Remaja DPR RI
            </div>

            <h1 class="alumni-hero-headline mb-4 animate-fade-up animate-delay-1">
              Bergerak Bersama, <span>Wujudkan Indonesia</span> yang Lebih Baik!
            </h1>

            <p class="alumni-hero-sub mb-5 animate-fade-up animate-delay-2">
              Bergabunglah dengan ribuan alumni Parlemen Remaja yang tersebar di seluruh penjuru Indonesia —
              generasi muda pemimpin masa depan yang berkomitmen untuk berkontribusi nyata bagi bangsa.
            </p>

            <div class="d-flex flex-wrap gap-3 animate-fade-up animate-delay-3">
              <a href="{{ route('alumni.feed.index') }}" class="btn btn-light rounded-pill px-5 py-3 fw-bold shadow-sm"
                style="font-size:0.95rem; color:#bf0050;">
                <i class="bi bi-person-plus-fill me-2"></i>Daftar Sekarang
              </a>
              <a href="#al-about" class="btn btn-outline-light-custom rounded-pill px-5 py-3 fw-semibold"
                style="font-size:0.95rem;">
                <i class="bi bi-info-circle me-2"></i>Pelajari Lebih Lanjut
              </a>
            </div>
          </div>

          <!-- Visual -->
          <div class="col-lg-6 d-flex justify-content-center justify-content-lg-end animate-fade-up animate-delay-2">
            <div style="max-width:476px; width:100%;">
              <div class="alumni-hero-img-wrap mb-3">
                <div class="alumni-hero-img-placeholder">
                  <i class="bi bi-people"></i>
                  <span>Foto Alumni Parlemen Remaja DPR RI<br>Berkolaborasi &amp; Berjejaring Nasional</span>
                </div>
              </div>
              <div class="d-flex gap-3">
                <div class="stat-float-card">
                  <div class="stat-float-icon"><i class="bi bi-people-fill"></i></div>
                  <div class="stat-float-text">
                    <strong>2.500+ Alumni</strong>
                    <span>Aktif Berkontribusi</span>
                  </div>
                </div>
                <div class="stat-float-card">
                  <div class="stat-float-icon secondary"><i class="bi bi-geo-alt-fill"></i></div>
                  <div class="stat-float-text">
                    <strong>34 Provinsi</strong>
                    <span>Terjangkau</span>
                  </div>
                </div>
              </div>
            </div>
          </div>

        </div>
      </div>

      <!-- Wave bottom -->
      <div class="hero-wave-alumni">
        <svg viewBox="0 0 1440 80" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none">
          <path d="M0,40 C360,80 1080,0 1440,40 L1440,80 L0,80 Z" fill="#ffffff" />
        </svg>
      </div>
    </div>
  </section>

  <!-- ============================================================
       HALL OF FAME ALUMNI
  ============================================================ -->
  <section id="al-hof" class="py-5" style="padding-top:90px !important; padding-bottom:90px !important;">
    <div class="container">

      <!-- Header -->
      <div class="text-center mb-5 reveal">
        <span class="section-badge"><i class="bi bi-trophy-fill me-1"></i>Hall of Fame</span>
        <h2 class="section-title mt-1">Alumni Berprestasi</h2>
        <p class="text-secondary mt-2" style="max-width:480px; margin:0 auto; font-size:0.9rem; line-height:1.75;">
          Mengenal para alumni terbaik yang telah mengharumkan nama Parlemen Remaja DPR RI
          di kancah nasional dan internasional.
        </p>
      </div>

      <!-- Slider -->
      <div class="hof-slider-outer reveal">
        <div class="hof-track" id="hofTrack">
          @forelse ($hallOfFame as $i => $c)
          <div class="hof-slide">
            <div class="hof-card">
              <div class="text-center">
                <div class="hof-avatar {{ $i % 2 ? 'alt' : '' }}">
                  @if (!empty($c['foto_url']))
                    <img src="{{ $c['foto_url'] }}" alt="{{ $c['nama'] }}">
                  @else
                    {{ strtoupper(\Illuminate\Support\Str::substr($c['nama'], 0, 1)) }}
                  @endif
                </div>
                <h5 class="hof-name">{{ $c['nama'] }}</h5>
                @if (!empty($c['instagram_url']))
                <a href="{{ $c['instagram_url'] }}" target="_blank" rel="noopener noreferrer" class="hof-social-link">
                  <i class="bi bi-instagram"></i>{{ '@'.$c['instagram_user'] }}
                </a>
                @endif
              </div>
              <div class="hof-divider"></div>
              <p class="hof-achievement-label"><i class="bi bi-star-fill"></i>Prestasi Terbaru</p>
              <p class="hof-achievement-title">{{ $c['prestasi'] }}</p>
              <div class="hof-meta">
                @if (!empty($c['angkatan']))
                <span class="hof-meta-badge angkatan"><i class="bi bi-award-fill"></i>Angk. {{ $c['angkatan'] }}</span>
                @endif
              </div>
            </div>
          </div>
          @empty
          {{-- Fallback statis bila belum ada alumni di direktori publik (consent + disetujui) --}}

          <!-- Card 1 -->
          <div class="hof-slide">
            <div class="hof-card">
              
              <div class="text-center">
                <div class="hof-avatar">
                  <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="Aditya Prasetyo">
                </div>
                <h5 class="hof-name">Aditya Prasetyo</h5>
                <a href="https://instagram.com/aditya.pras" target="_blank" rel="noopener noreferrer" class="hof-social-link">
                  <i class="bi bi-instagram"></i>@aditya.pras
                </a>
              </div>
              <div class="hof-divider"></div>
              <p class="hof-achievement-label"><i class="bi bi-star-fill"></i>Prestasi Terbaru</p>
              <p class="hof-achievement-title">Delegasi Indonesia di ASEAN Youth Parliament Forum, Singapura 2025</p>
              <div class="hof-meta">
                <span class="hof-meta-badge year"><i class="bi bi-calendar3"></i>2025</span>
                <span class="hof-meta-badge angkatan"><i class="bi bi-award-fill"></i>Angk. XII</span>
              </div>
            </div>
          </div>

          <!-- Card 2 -->
          <div class="hof-slide">
            <div class="hof-card">
              
              <div class="text-center">
                <div class="hof-avatar alt">
                  <img src="https://randomuser.me/api/portraits/women/44.jpg" alt="Nadhira Salsabila">
                </div>
                <h5 class="hof-name">Nadhira Salsabila</h5>
                <a href="https://instagram.com/nadhirasalsa" target="_blank" rel="noopener noreferrer" class="hof-social-link">
                  <i class="bi bi-instagram"></i>@nadhirasalsa
                </a>
              </div>
              <div class="hof-divider"></div>
              <p class="hof-achievement-label"><i class="bi bi-star-fill"></i>Prestasi Terbaru</p>
              <p class="hof-achievement-title">Peraih SATU Indonesia Award 2025 Bidang Pendidikan & Pemberdayaan Sosial</p>
              <div class="hof-meta">
                <span class="hof-meta-badge year"><i class="bi bi-calendar3"></i>2025</span>
                <span class="hof-meta-badge angkatan"><i class="bi bi-award-fill"></i>Angk. XI</span>
              </div>
            </div>
          </div>

          <!-- Card 3 -->
          <div class="hof-slide">
            <div class="hof-card">
              
              <div class="text-center">
                <div class="hof-avatar">
                  <img src="https://randomuser.me/api/portraits/men/67.jpg" alt="Rifqi Muhammad">
                </div>
                <h5 class="hof-name">Rifqi Muhammad</h5>
                <a href="https://instagram.com/rifqimhmd" target="_blank" rel="noopener noreferrer" class="hof-social-link">
                  <i class="bi bi-instagram"></i>@rifqimhmd
                </a>
              </div>
              <div class="hof-divider"></div>
              <p class="hof-achievement-label"><i class="bi bi-star-fill"></i>Prestasi Terbaru</p>
              <p class="hof-achievement-title">Anggota Termuda DPRD Provinsi Jawa Tengah Periode 2024–2029</p>
              <div class="hof-meta">
                <span class="hof-meta-badge year"><i class="bi bi-calendar3"></i>2024</span>
                <span class="hof-meta-badge angkatan"><i class="bi bi-award-fill"></i>Angk. X</span>
              </div>
            </div>
          </div>

          <!-- Card 4 -->
          <div class="hof-slide">
            <div class="hof-card">
              
              <div class="text-center">
                <div class="hof-avatar alt">
                  <img src="https://randomuser.me/api/portraits/women/28.jpg" alt="Khairunnisa Putri">
                </div>
                <h5 class="hof-name">Khairunnisa Putri</h5>
                <a href="https://instagram.com/nisa.putri_" target="_blank" rel="noopener noreferrer" class="hof-social-link">
                  <i class="bi bi-instagram"></i>@nisa.putri_
                </a>
              </div>
              <div class="hof-divider"></div>
              <p class="hof-achievement-label"><i class="bi bi-star-fill"></i>Prestasi Terbaru</p>
              <p class="hof-achievement-title">UNESCO Youth Advocate for Education Indonesia 2025</p>
              <div class="hof-meta">
                <span class="hof-meta-badge year"><i class="bi bi-calendar3"></i>2025</span>
                <span class="hof-meta-badge angkatan"><i class="bi bi-award-fill"></i>Angk. XIII</span>
              </div>
            </div>
          </div>

          <!-- Card 5 -->
          <div class="hof-slide">
            <div class="hof-card">
          
              <div class="text-center">
                <div class="hof-avatar">
                  <img src="https://randomuser.me/api/portraits/men/15.jpg" alt="Bagas Satria W.">
                </div>
                <h5 class="hof-name">Bagas Satria W.</h5>
                <a href="https://instagram.com/bagassatriaw" target="_blank" rel="noopener noreferrer" class="hof-social-link">
                  <i class="bi bi-instagram"></i>@bagassatriaw
                </a>
              </div>
              <div class="hof-divider"></div>
              <p class="hof-achievement-label"><i class="bi bi-star-fill"></i>Prestasi Terbaru</p>
              <p class="hof-achievement-title">Juara 1 Kompetisi Debat Hukum Nasional Mahkamah Konstitusi RI 2025</p>
              <div class="hof-meta">
                <span class="hof-meta-badge year"><i class="bi bi-calendar3"></i>2025</span>
                <span class="hof-meta-badge angkatan"><i class="bi bi-award-fill"></i>Angk. IX</span>
              </div>
            </div>
          </div>

          @endforelse
        </div><!-- /.hof-track -->
      </div><!-- /.hof-slider-outer -->

      <!-- Navigation -->
      <div class="hof-nav-row">
        <button class="hof-btn" id="hofPrev" aria-label="Sebelumnya">
          <i class="bi bi-chevron-left"></i>
        </button>
        <div class="hof-dots" id="hofDots"></div>
        <button class="hof-btn" id="hofNext" aria-label="Selanjutnya">
          <i class="bi bi-chevron-right"></i>
        </button>
      </div>

    </div>
  </section>


  <!-- ============================================================
       2. TENTANG KAMI
  ============================================================ -->
  <section id="al-about" class="py-5" style="padding-top:90px !important; padding-bottom:90px !important;">
    <div class="container">
      <div class="row align-items-center gy-5">

        <!-- Text -->
        <div class="col-lg-6 order-2 order-lg-1 reveal">
          <span class="section-badge">Tentang Komunitas</span>
          <h2 class="section-title mb-3">Membangun Karakter Pemuda<br class="d-none d-md-block" />untuk Indonesia
            Unggul</h2>
          <p class="text-secondary mb-4" style="font-size:0.94rem; line-height:1.82;">
            Forum Alumni Parlemen Remaja DPR RI lahir sebagai wadah bagi para pemuda terbaik Indonesia yang telah
            merasakan pengalaman simulasi sidang parlemen langsung di Gedung DPR/MPR RI. Sejak angkatan pertama,
            jaringan alumni kami terus berkembang menjadi komunitas penggerak perubahan yang solid dan berdampak nyata
            di berbagai sektor kehidupan bangsa.
          </p>

          <div class="visi-misi-box reveal reveal-d1">
            <h6>Visi</h6>
            <p>Menjadi komunitas alumni yang aktif, berdampak, dan menjadi garda terdepan dalam mewujudkan demokrasi
              yang sehat dan kepemimpinan berintegritas di Indonesia.</p>
          </div>

          <div class="visi-misi-box misi reveal reveal-d2">
            <h6>Misi</h6>
            <p>Mempererat silaturahmi antaralumni, mendorong kontribusi aktif di bidang sosial, politik, dan
              pendidikan, serta menjadi jembatan antara generasi muda dan pemangku kebijakan nasional.</p>
          </div>

          <a href="#al-programs" class="btn btn-gradient rounded-pill px-4 py-2 mt-3 reveal reveal-d3"
            style="font-size:0.88rem; font-weight:600;">
            Lihat Program Kami <i class="bi bi-arrow-right ms-2"></i>
          </a>
        </div>

        <!-- Collage -->
        <div class="col-lg-6 order-1 order-lg-2 reveal reveal-d1">
          <div class="about-collage">
            <div class="collage-cell span-row">
              <i class="bi bi-camera-video"></i>
              <span class="collage-label">Sidang Paripurna</span>
            </div>
            <div class="collage-cell">
              <i class="bi bi-mic"></i>
              <span class="collage-label">Pidato &amp; Debat</span>
            </div>
            <div class="collage-cell alt">
              <i class="bi bi-people"></i>
              <span class="collage-label" style="color:#41174B;">Diskusi Komisi</span>
            </div>
          </div>
        </div>

      </div>
    </div>
  </section>


  <!-- ============================================================
       3. NILAI-NILAI UTAMA
  ============================================================ -->
  <section id="al-values" class="py-5" style="padding-top:90px !important; padding-bottom:90px !important;">
    <div class="container">
      <div class="text-center mb-5 reveal">
        <span class="section-badge">Landasan Organisasi</span>
        <h2 class="section-title">Nilai-Nilai Utama Kami</h2>
        <p class="text-secondary mt-2" style="max-width:480px; margin:0 auto; font-size:0.9rem; line-height:1.75;">
          Empat pilar yang menjadi fondasi setiap langkah dan keputusan para alumni Parlemen Remaja.
        </p>
      </div>

      <div class="row g-4">

        <div class="col-sm-6 col-lg-3 reveal reveal-d1">
          <div class="value-card">
            <div class="value-card-inner">
              <div class="value-icon-wrap"><i class="bi bi-award-fill"></i></div>
              <h5>Kepemimpinan</h5>
              <p>Berani memimpin, memberi inspirasi, dan mengambil keputusan terbaik demi kemajuan bersama dengan penuh
                tanggung jawab.</p>
            </div>
          </div>
        </div>

        <div class="col-sm-6 col-lg-3 reveal reveal-d2">
          <div class="value-card">
            <div class="value-card-inner">
              <div class="value-icon-wrap"><i class="bi bi-diagram-3-fill"></i></div>
              <h5>Kolaborasi</h5>
              <p>Memperkuat sinergi lintas daerah, disiplin, dan generasi untuk menciptakan solusi yang lebih besar dan
                bermakna bagi bangsa.</p>
            </div>
          </div>
        </div>

        <div class="col-sm-6 col-lg-3 reveal reveal-d3">
          <div class="value-card">
            <div class="value-card-inner">
              <div class="value-icon-wrap"><i class="bi bi-shield-check-fill"></i></div>
              <h5>Integritas</h5>
              <p>Menjunjung tinggi kejujuran, transparansi, dan etika dalam setiap tindakan sebagai representasi pemuda
                yang bisa dipercaya.</p>
            </div>
          </div>
        </div>

        <div class="col-sm-6 col-lg-3 reveal reveal-d4">
          <div class="value-card">
            <div class="value-card-inner">
              <div class="value-icon-wrap"><i class="bi bi-lightbulb-fill"></i></div>
              <h5>Inovasi</h5>
              <p>Mendorong lahirnya gagasan-gagasan segar dan solusi kreatif dalam menjawab tantangan bangsa di era
                modern.</p>
            </div>
          </div>
        </div>

      </div>
    </div>
  </section>


  <!-- ============================================================
       4. PROGRAM UNGGULAN
  ============================================================ -->
  <section id="al-programs" class="py-5" style="padding-top:90px !important; padding-bottom:90px !important;">
    <div class="container">
      <div class="text-center mb-5 reveal">
        <span class="section-badge">Inisiatif &amp; Kegiatan</span>
        <h2 class="section-title">Program Unggulan Alumni</h2>
        <p class="text-secondary mt-2" style="max-width:480px; margin:0 auto; font-size:0.9rem; line-height:1.75;">
          Ragam program yang dirancang untuk memaksimalkan potensi dan dampak kontribusi alumni terhadap masyarakat.
        </p>
      </div>

      <div class="row g-4">

        <!-- Card 1 -->
        <div class="col-md-6 col-lg-4 reveal reveal-d1">
          <div class="program-card card">
            <div class="program-card-thumb"><i class="bi bi-people-fill"></i></div>
            <div class="card-body">
              <span class="gallery-tag" style="color:#bf0050; background:rgba(191,0,80,0.08);">Networking</span>
              <h5 class="mt-2">Forum Nasional Alumni</h5>
              <p>Pertemuan tahunan alumni dari seluruh Indonesia untuk berbagi pengalaman, membangun jejaring, dan
                merumuskan agenda kontribusi kolektif kepada bangsa.</p>
            </div>
            <div class="card-footer">
              <a href="{{ route('parja.public.program') }}?id=forum-nasional" class="read-more-arrow">Baca Selengkapnya <i class="bi bi-arrow-right"></i></a>
            </div>
          </div>
        </div>

        <!-- Card 2 -->
        <div class="col-md-6 col-lg-4 reveal reveal-d2">
          <div class="program-card card">
            <div class="program-card-thumb" style="background:linear-gradient(135deg,#e0e8f5,#d0deff);">
              <i class="bi bi-mortarboard-fill" style="color:#41174B;"></i>
            </div>
            <div class="card-body">
              <span class="gallery-tag" style="color:#41174B; background:rgba(65,23,75,0.08);">Pendidikan</span>
              <h5 class="mt-2">Beasiswa &amp; Mentoring</h5>
              <p>Program pendampingan alumni berprestasi untuk adik-adik peserta baru: bimbingan karier, persiapan
                perguruan tinggi, dan pendampingan kepemimpinan.</p>
            </div>
            <div class="card-footer">
              <a href="{{ route('parja.public.program') }}?id=beasiswa-mentoring" class="read-more-arrow">Baca Selengkapnya <i class="bi bi-arrow-right"></i></a>
            </div>
          </div>
        </div>

        <!-- Card 3 -->
        <div class="col-md-6 col-lg-4 reveal reveal-d3">
          <div class="program-card card">
            <div class="program-card-thumb" style="background:linear-gradient(135deg,#e0f5e8,#c8f0d0);">
              <i class="bi bi-megaphone-fill" style="color:#1a7a45;"></i>
            </div>
            <div class="card-body">
              <span class="gallery-tag" style="color:#1a7a45; background:rgba(26,122,69,0.08);">Advokasi</span>
              <h5 class="mt-2">Youth Policy Lab</h5>
              <p>Ruang riset dan diskusi kebijakan publik yang melibatkan alumni dalam proses penyusunan rekomendasi
                kebijakan untuk disampaikan kepada pembuat keputusan.</p>
            </div>
            <div class="card-footer">
              <a href="{{ route('parja.public.program') }}?id=youth-policy-lab" class="read-more-arrow">Baca Selengkapnya <i class="bi bi-arrow-right"></i></a>
            </div>
          </div>
        </div>

        <!-- Card 4 -->
        <div class="col-md-6 col-lg-4 reveal reveal-d1">
          <div class="program-card card">
            <div class="program-card-thumb" style="background:linear-gradient(135deg,#fff5e0,#ffe8c0);">
              <i class="bi bi-camera-video-fill" style="color:#d97706;"></i>
            </div>
            <div class="card-body">
              <span class="gallery-tag" style="color:#d97706; background:rgba(217,119,6,0.08);">Media</span>
              <h5 class="mt-2">Alumni Speaks: Podcast Series</h5>
              <p>Serial podcast inspiratif yang menampilkan cerita perjalanan alumni Parlemen Remaja yang kini berkarier
                di berbagai bidang strategis bangsa.</p>
            </div>
            <div class="card-footer">
              <a href="{{ route('parja.public.program') }}?id=podcast-series" class="read-more-arrow">Baca Selengkapnya <i class="bi bi-arrow-right"></i></a>
            </div>
          </div>
        </div>

        <!-- Card 5 -->
        <div class="col-md-6 col-lg-4 reveal reveal-d2">
          <div class="program-card card">
            <div class="program-card-thumb" style="background:linear-gradient(135deg,#f0e0f5,#e8c0f0);">
              <i class="bi bi-globe2" style="color:#7a3a8a;"></i>
            </div>
            <div class="card-body">
              <span class="gallery-tag" style="color:#7a3a8a; background:rgba(122,58,138,0.08);">Global</span>
              <h5 class="mt-2">Program Pertukaran Internasional</h5>
              <p>Fasilitasi keterlibatan alumni dalam forum-forum parlemen muda tingkat regional dan internasional untuk
                memperluas perspektif global.</p>
            </div>
            <div class="card-footer">
              <a href="{{ route('parja.public.program') }}?id=pertukaran-internasional" class="read-more-arrow">Baca Selengkapnya <i class="bi bi-arrow-right"></i></a>
            </div>
          </div>
        </div>

        <!-- Card 6 -->
        <div class="col-md-6 col-lg-4 reveal reveal-d3">
          <div class="program-card card">
            <div class="program-card-thumb" style="background:linear-gradient(135deg,#ffe0e0,#ffc8c8);">
              <i class="bi bi-heart-fill" style="color:#dc2626;"></i>
            </div>
            <div class="card-body">
              <span class="gallery-tag" style="color:#dc2626; background:rgba(220,38,38,0.08);">Sosial</span>
              <h5 class="mt-2">Gerakan Alumni Peduli</h5>
              <p>Pengabdian masyarakat kolaboratif: literasi demokrasi di sekolah-sekolah, bakti sosial, dan kampanye
                kepemudaan di daerah terpencil.</p>
            </div>
            <div class="card-footer">
              <a href="{{ route('parja.public.program') }}?id=alumni-peduli" class="read-more-arrow">Baca Selengkapnya <i class="bi bi-arrow-right"></i></a>
            </div>
          </div>
        </div>

      </div>
    </div>
  </section>


  <!-- ============================================================
       5. DAMPAK & STATISTIK
  ============================================================ -->
  <section id="al-stats" class="py-5" style="padding-top:90px !important; padding-bottom:90px !important;">
    <div class="stats-deco-ring ring-1"></div>
    <div class="stats-deco-ring ring-2"></div>
    <div class="stats-deco-square"></div>

    <div class="container position-relative" style="z-index:2;">
      <div class="text-center mb-5 reveal">
        <span class="section-badge">Dampak Nyata</span>
        <h2 class="section-title mt-2 mb-2" style="color:#fff;">Jejak Langkah Alumni<br class="d-none d-md-block" />
          Parlemen Remaja</h2>
        <p style="color:rgba(255,255,255,0.7); font-size:0.9rem; max-width:460px; margin:0 auto;">
          Angka-angka ini mencerminkan dedikasi dan semangat ribuan pemuda Indonesia yang telah melewati tempaan
          Parlemen Remaja.
        </p>
      </div>

      <div class="row justify-content-center g-0">

        <div class="col-6 col-lg-3 reveal reveal-d1">
          <div class="stat-box">
            <div class="stat-icon-wrap"><i class="bi bi-people-fill"></i></div>
            <span class="stat-number" data-count="2500" data-suffix="+">0</span>
            <p class="stat-label">Jumlah Alumni<br>Nasional</p>
          </div>
        </div>

        <div class="col-6 col-lg-3 reveal reveal-d2">
          <div class="stat-box">
            <div class="stat-icon-wrap"><i class="bi bi-geo-alt-fill"></i></div>
            <span class="stat-number" data-count="34" data-suffix="">0</span>
            <p class="stat-label">Provinsi<br>Terjangkau</p>
          </div>
        </div>

        <div class="col-6 col-lg-3 reveal reveal-d3">
          <div class="stat-box">
            <div class="stat-icon-wrap"><i class="bi bi-trophy-fill"></i></div>
            <span class="stat-number" data-count="12" data-suffix="">0</span>
            <p class="stat-label">Total Angkatan<br>Program</p>
          </div>
        </div>

        <div class="col-6 col-lg-3 reveal reveal-d4">
          <div class="stat-box">
            <div class="stat-icon-wrap"><i class="bi bi-building-fill"></i></div>
            <span class="stat-number" data-count="150" data-suffix="+">0</span>
            <p class="stat-label">Mitra<br>Kolaborasi</p>
          </div>
        </div>

      </div>
    </div>
  </section>


  <!-- ============================================================
       6. TESTIMONI ALUMNI
  ============================================================ -->
  <section id="al-testimoni" class="py-5" style="padding-top:90px !important; padding-bottom:90px !important;">
    <div class="container">
      <div class="text-center mb-5 reveal">
        <span class="section-badge">Suara Alumni</span>
        <h2 class="section-title">Apa Kata Mereka?</h2>
        <p class="text-secondary mt-2" style="max-width:460px; margin:0 auto; font-size:0.9rem; line-height:1.75;">
          Kisah nyata dari alumni yang telah merasakan manfaat luar biasa program Parlemen Remaja DPR RI.
        </p>
      </div>

      <div class="testi-slider-outer reveal">
        <div class="testi-track" id="testiTrack">

          <!-- Slide 1 -->
          <div class="testi-slide">
            <div class="testi-card">
              <div class="quote-mark">"</div>
              <div class="testi-stars">
                <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                  class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
              </div>
              <p class="testi-text">
                "Parlemen Remaja mengubah cara pandang saya tentang politik dan kepemimpinan. Saya tidak hanya belajar
                proses legislasi, tapi juga bagaimana menjadi pendengar yang baik, berpikir kritis, dan berkolaborasi
                dengan orang-orang dari latar belakang yang berbeda. Pengalaman ini menjadi fondasi karier saya di
                bidang kebijakan publik hari ini."
              </p>
              <div class="testi-author">
                <div class="testi-avatar">AR</div>
                <div>
                  <p class="testi-name">Anisa Rahmadhani</p>
                  <p class="testi-origin"><i class="bi bi-geo-alt me-1"></i>Angkatan 2022 &middot; Universitas
                    Indonesia, Jakarta</p>
                </div>
              </div>
            </div>
          </div>

          <!-- Slide 2 -->
          <div class="testi-slide">
            <div class="testi-card">
              <div class="quote-mark">"</div>
              <div class="testi-stars">
                <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                  class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
              </div>
              <p class="testi-text">
                "Saya datang dari pelosok Kalimantan Timur dengan rasa tidak percaya diri. Tapi Parlemen Remaja
                mengajarkan bahwa suara dari timur Indonesia sama pentingnya dengan suara dari pusat. Saya pulang
                membawa kepercayaan diri dan jaringan teman-teman luar biasa dari seluruh nusantara. Kini saya aktif
                mengadvokasi isu-isu pendidikan di daerah saya."
              </p>
              <div class="testi-author">
                <div class="testi-avatar" style="background:var(--pr-secondary-gradient);">BF</div>
                <div>
                  <p class="testi-name">Bagas Firmansyah</p>
                  <p class="testi-origin"><i class="bi bi-geo-alt me-1"></i>Angkatan 2023 &middot; Universitas
                    Mulawarman, Kalimantan Timur</p>
                </div>
              </div>
            </div>
          </div>

          <!-- Slide 3 -->
          <div class="testi-slide">
            <div class="testi-card">
              <div class="quote-mark">"</div>
              <div class="testi-stars">
                <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                  class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
              </div>
              <p class="testi-text">
                "Bagi saya, Parlemen Remaja adalah pintu gerbang untuk memahami betapa kompleks dan beratnya tanggung
                jawab para pemimpin bangsa. Melalui simulasi sidang yang begitu nyata, saya belajar berargumen dengan
                data, menghormati perbedaan pendapat, dan bahwa kepentingan rakyat harus selalu menjadi prioritas utama.
                Pengalaman itu tidak akan pernah saya lupakan."
              </p>
              <div class="testi-author">
                <div class="testi-avatar" style="background:linear-gradient(135deg,#1a7a45,#2daa60);">SP</div>
                <div>
                  <p class="testi-name">Siti Permata Sari</p>
                  <p class="testi-origin"><i class="bi bi-geo-alt me-1"></i>Angkatan 2021 &middot; Universitas Gadjah
                    Mada, Yogyakarta</p>
                </div>
              </div>
            </div>
          </div>

          <!-- Slide 4 -->
          <div class="testi-slide">
            <div class="testi-card">
              <div class="quote-mark">"</div>
              <div class="testi-stars">
                <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                  class="bi bi-star-fill"></i><i class="bi bi-star-half"></i>
              </div>
              <p class="testi-text">
                "Yang paling berkesan adalah saat teman-teman dari Papua, NTT, dan Aceh duduk berdampingan,
                memperjuangkan aspirasi yang sama — Indonesia yang maju dan berkeadilan. Parlemen Remaja bukan hanya
                program, ini adalah pengalaman yang mendekatkan kita pada makna sesungguhnya menjadi satu bangsa yang
                berani bermimpi besar."
              </p>
              <div class="testi-author">
                <div class="testi-avatar" style="background:linear-gradient(135deg,#d97706,#f59e0b);">RP</div>
                <div>
                  <p class="testi-name">Rizky Pratama</p>
                  <p class="testi-origin"><i class="bi bi-geo-alt me-1"></i>Angkatan 2024 &middot; Universitas
                    Brawijaya, Jawa Timur</p>
                </div>
              </div>
            </div>
          </div>

        </div><!-- /.testi-track -->
      </div>

      <!-- Slider controls -->
      <div class="testi-nav-row">
        <button class="testi-btn" id="testiPrev" aria-label="Testimonial sebelumnya">
          <i class="bi bi-chevron-left"></i>
        </button>
        <div class="testi-dots" id="testiDots">
          <div class="testi-dot active" data-idx="0"></div>
          <div class="testi-dot" data-idx="1"></div>
          <div class="testi-dot" data-idx="2"></div>
          <div class="testi-dot" data-idx="3"></div>
        </div>
        <button class="testi-btn" id="testiNext" aria-label="Testimonial berikutnya">
          <i class="bi bi-chevron-right"></i>
        </button>
      </div>

    </div>
  </section>


  <!-- ============================================================
       7. BERITA & ARTIKEL
  ============================================================ -->
  <section id="al-news" class="py-5" style="padding-top:90px !important; padding-bottom:90px !important;">
    <div class="container">

      <!-- Section header with button aligned top-right -->
      <div class="row align-items-end mb-5 reveal">
        <div class="col-lg-7">
          <span class="section-badge">Update Terkini</span>
          <h2 class="section-title mb-2">Berita &amp; Artikel Alumni</h2>
          <p class="text-secondary" style="font-size:0.9rem; line-height:1.75; max-width:460px;">
            Ikuti perkembangan terbaru dari komunitas alumni Parlemen Remaja DPR RI.
          </p>
        </div>
        <div class="col-lg-5 text-lg-end mt-3 mt-lg-0">
          <a href="#" class="btn btn-outline-secondary rounded-pill px-4 py-2"
            style="font-size:0.85rem; font-weight:500;">
            Lihat Semua Artikel <i class="bi bi-arrow-right ms-1"></i>
          </a>
        </div>
      </div>

      <div class="row g-4">

        <!-- Article 1 -->
        <div class="col-md-6 col-lg-4 reveal reveal-d1">
          <div class="news-card card">
            <div class="news-thumb">
              <i class="bi bi-newspaper"></i>
              <span class="news-cat">Berita</span>
            </div>
            <div class="card-body">
              <div class="news-date"><i class="bi bi-calendar3"></i> 2 April 2026</div>
              <h5>Forum Alumni Parlemen Remaja 2026 Resmi Dibuka, Dihadiri 800 Alumni dari 34 Provinsi</h5>
              <p class="news-excerpt">Forum tahunan yang paling ditunggu-tunggu kembali hadir. Tahun ini, lebih dari 800
                alumni berkumpul di Jakarta untuk merumuskan agenda kepemudaan nasional dan memperkuat jaringan lintas
                generasi...</p>
            </div>
          </div>
        </div>

        <!-- Article 2 -->
        <div class="col-md-6 col-lg-4 reveal reveal-d2">
          <div class="news-card card">
            <div class="news-thumb" style="background:linear-gradient(135deg,#e8e0f5,#d8c8f0);">
              <i class="bi bi-pencil-square" style="color:#41174B;"></i>
              <span class="news-cat opini">Opini</span>
            </div>
            <div class="card-body">
              <div class="news-date"><i class="bi bi-calendar3"></i> 25 Maret 2026</div>
              <h5>Refleksi Alumni tentang Kualitas Partisipasi Pemuda dalam Politik Modern Indonesia</h5>
              <p class="news-excerpt">Oleh Anisa Rahmadhani, Alumni Angkatan 2022. Keterlibatan pemuda dalam proses
                politik bukan lagi sekedar wacana. Data menunjukkan tren yang menggembirakan, namun masih banyak
                pekerjaan rumah yang harus diselesaikan bersama...</p>
            </div>
          </div>
        </div>

        <!-- Article 3 -->
        <div class="col-md-6 col-lg-4 reveal reveal-d3">
          <div class="news-card card">
            <div class="news-thumb" style="background:linear-gradient(135deg,#e0f5e8,#c8f0d8);">
              <i class="bi bi-calendar-event" style="color:#1a7a45;"></i>
              <span class="news-cat kegiatan">Kegiatan</span>
            </div>
            <div class="card-body">
              <div class="news-date"><i class="bi bi-calendar3"></i> 15 Maret 2026</div>
              <h5>Youth Policy Lab Gelar Workshop Riset: 50 Alumni Hasilkan Rekomendasi untuk Kemendikbud</h5>
              <p class="news-excerpt">Sebanyak 50 alumni terbaik dari berbagai angkatan berkumpul selama dua hari penuh
                di Gedung DPR RI untuk menyusun rekomendasi kebijakan pendidikan yang akan disampaikan langsung kepada
                Kementerian Pendidikan...</p>
            </div>
          </div>
        </div>

      </div>
    </div>
  </section>


  <!-- ============================================================
       8. MITRA & KOLABORASI
  ============================================================ -->
  <section id="al-partners" class="py-5" style="padding-top:80px !important; padding-bottom:80px !important;">
    <div class="container">
      <div class="text-center mb-5 reveal">
        <span class="section-badge">Ekosistem Kolaborasi</span>
        <h2 class="section-title">Mitra &amp; Kolaborasi</h2>
        <p class="text-secondary mt-2" style="max-width:460px; margin:0 auto; font-size:0.9rem; line-height:1.75;">
          Institusi, kementerian, universitas, dan organisasi yang bersama-sama mendukung misi kami.
        </p>
      </div>
    </div>

    <!-- Row 1 — left to right -->
    <div class="marquee-outer mb-4 reveal">
      <div class="marquee-track">
        <div class="partner-chip"><i class="bi bi-building-fill-gear"></i><span>DPR RI</span></div>
        <div class="partner-chip"><i class="bi bi-building-fill"></i><span>Kemendikbud</span></div>
        <div class="partner-chip"><i class="bi bi-bank2"></i><span>BPIP</span></div>
        <div class="partner-chip"><i class="bi bi-mortarboard-fill"></i><span>Universitas Indonesia</span></div>
        <div class="partner-chip"><i class="bi bi-mortarboard-fill"></i><span>UGM</span></div>
        <div class="partner-chip"><i class="bi bi-mortarboard-fill"></i><span>ITB</span></div>
        <div class="partner-chip"><i class="bi bi-globe2"></i><span>UNDP Indonesia</span></div>
        <div class="partner-chip"><i class="bi bi-buildings-fill"></i><span>USAID</span></div>
        <!-- duplicate set for seamless loop -->
        <div class="partner-chip"><i class="bi bi-building-fill-gear"></i><span>DPR RI</span></div>
        <div class="partner-chip"><i class="bi bi-building-fill"></i><span>Kemendikbud</span></div>
        <div class="partner-chip"><i class="bi bi-bank2"></i><span>BPIP</span></div>
        <div class="partner-chip"><i class="bi bi-mortarboard-fill"></i><span>Universitas Indonesia</span></div>
        <div class="partner-chip"><i class="bi bi-mortarboard-fill"></i><span>UGM</span></div>
        <div class="partner-chip"><i class="bi bi-mortarboard-fill"></i><span>ITB</span></div>
        <div class="partner-chip"><i class="bi bi-globe2"></i><span>UNDP Indonesia</span></div>
        <div class="partner-chip"><i class="bi bi-buildings-fill"></i><span>USAID</span></div>
      </div>
    </div>

    <!-- Row 2 — right to left -->
    <div class="marquee-outer reveal">
      <div class="marquee-track reverse">
        <div class="partner-chip"><i class="bi bi-mortarboard-fill"></i><span>Unair</span></div>
        <div class="partner-chip"><i class="bi bi-mortarboard-fill"></i><span>Unpad</span></div>
        <div class="partner-chip"><i class="bi bi-mortarboard-fill"></i><span>Undip</span></div>
        <div class="partner-chip"><i class="bi bi-building-fill"></i><span>Kemlu RI</span></div>
        <div class="partner-chip"><i class="bi bi-building-fill"></i><span>KPU RI</span></div>
        <div class="partner-chip"><i class="bi bi-newspaper"></i><span>Kompas</span></div>
        <div class="partner-chip"><i class="bi bi-people-fill"></i><span>KPPOD</span></div>
        <div class="partner-chip"><i class="bi bi-globe2"></i><span>IPU Youth</span></div>
        <!-- duplicate set -->
        <div class="partner-chip"><i class="bi bi-mortarboard-fill"></i><span>Unair</span></div>
        <div class="partner-chip"><i class="bi bi-mortarboard-fill"></i><span>Unpad</span></div>
        <div class="partner-chip"><i class="bi bi-mortarboard-fill"></i><span>Undip</span></div>
        <div class="partner-chip"><i class="bi bi-building-fill"></i><span>Kemlu RI</span></div>
        <div class="partner-chip"><i class="bi bi-building-fill"></i><span>KPU RI</span></div>
        <div class="partner-chip"><i class="bi bi-newspaper"></i><span>Kompas</span></div>
        <div class="partner-chip"><i class="bi bi-people-fill"></i><span>KPPOD</span></div>
        <div class="partner-chip"><i class="bi bi-globe2"></i><span>IPU Youth</span></div>
      </div>
    </div>

  </section>


  <!-- ============================================================
       9. CALL-TO-ACTION PENUTUP
  ============================================================ -->
  <section id="al-cta" class="py-5" style="padding-top:100px !important; padding-bottom:100px !important;">
    <div class="container position-relative" style="z-index:2;">
      <div class="text-center reveal">

        <div class="hero-eyebrow d-inline-flex mb-4"
          style="background:rgba(255,255,255,0.15); border-color:rgba(255,255,255,0.3);">
          <i class="bi bi-rocket-takeoff-fill me-2"></i>Mulai Perjalananmu Hari Ini
        </div>

        <h2 class="cta-headline mb-4">
          Siap Berkontribusi untuk<br class="d-none d-sm-block" />
          Masa Depan Indonesia?
        </h2>

        <p class="cta-sub mb-5">
          Ribuan alumni telah membuktikan — satu langkah berani hari ini bisa mengubah arah perjalanan bangsa esok
          hari. Daftarkan dirimu sekarang dan jadilah bagian dari gerakan pemuda paling berpengaruh di Indonesia!
        </p>

        <div class="d-flex flex-wrap justify-content-center gap-3">
          <a href="{{ route('alumni.feed.index') }}" class="btn-cta-white">
            <i class="bi bi-person-plus-fill me-2"></i>Daftar Sebagai Anggota Alumni
          </a>
          <a href="{{ route('alumni.login') }}" class="btn btn-outline-light-custom rounded-pill px-5 py-3 fw-semibold"
            style="font-size:1rem;">
            <i class="bi bi-box-arrow-in-right me-2"></i>Masuk Portal Alumni
          </a>
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

        <!-- Brand -->
        <div class="col-lg-4 col-md-6">
          <div class="d-flex align-items-center gap-3 mb-3">
            <img src="{{ asset('parja-landing/images/icons/logo-footer.png') }}" alt="Logo Parlemen Remaja DPR RI" style="height:56px;">
          </div>
          <p style="font-size:0.83rem; line-height:1.8; color:rgba(255,255,255,0.55);">
            Program edukasi kebangsaan dan simulasi sidang parlemen untuk pelajar SMA/sederajat se-Indonesia,
            diselenggarakan oleh Sekretariat Jenderal DPR RI.
          </p>
        </div>

        <!-- Nav -->
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

        <!-- Alumni -->
        <div class="col-lg-2 col-md-6 col-6">
          <p class="footer-heading">Alumni</p>
          <ul class="footer-links">
            <li><a href="{{ route('parja.public.alumni') }}#al-about">Tentang Kami</a></li>
            <li><a href="{{ route('parja.public.alumni') }}#al-programs">Program</a></li>
            <li><a href="{{ route('parja.public.alumni') }}#al-news">Berita</a></li>
            <li><a href="{{ route('parja.public.alumni') }}#al-partners">Mitra</a></li>
            <li><a href="{{ route('alumni.feed.index') }}">Bergabung</a></li>
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
              style="color:rgba(255,255,255,0.65); text-decoration:none;">parlemenremaja@dpr.go.id</a>
          </div>
          <div class="footer-contact-item">
            <i class="bi bi-telephone-fill"></i>
            <span>(021) 5715-408 (Bidang Kehumasan)</span>
          </div>
          <div class="footer-contact-item">
            <i class="bi bi-globe"></i>
            <a href="https://www.dpr.go.id" target="_blank" rel="noopener noreferrer"
              style="color:rgba(255,255,255,0.65); text-decoration:none;">www.dpr.go.id</a>
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

  <!-- Scroll to Top -->
  <button id="scrollTopBtn" aria-label="Scroll ke atas">
    <i class="bi bi-chevron-up"></i>
  </button>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc4s9bIOgUxi8T/jzmUl2gW6sRFoB7aHkKTHRcKJqaQ"
    crossorigin="anonymous"></script>

  <!-- Shared custom JS (navbar scroll, scroll-to-top) -->
  <script src="{{ asset('parja-landing/js/main.js') }}"></script>

  <script>
    // ============================================================
    // SCROLL REVEAL
    // ============================================================
    const revealEls = document.querySelectorAll('.reveal');
    const revealObs = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('visible');
          revealObs.unobserve(entry.target);
        }
      });
    }, { threshold: 0.1 });
    revealEls.forEach(el => revealObs.observe(el));

    // ============================================================
    // NUMBER COUNTER ANIMATION
    // ============================================================
    const counters = document.querySelectorAll('.stat-number[data-count]');
    const counterObs = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (!entry.isIntersecting) return;
        const el = entry.target;
        const target = parseInt(el.dataset.count, 10);
        const suffix = el.dataset.suffix || '';
        const duration = 1800;
        const frameRate = 16;
        const totalFrames = Math.round(duration / frameRate);
        let frame = 0;
        const timer = setInterval(() => {
          frame++;
          const progress = frame / totalFrames;
          const eased = 1 - Math.pow(1 - progress, 3); // ease-out cubic
          const current = Math.round(eased * target);
          el.textContent = current.toLocaleString('id-ID') + suffix;
          if (frame >= totalFrames) {
            el.textContent = target.toLocaleString('id-ID') + suffix;
            clearInterval(timer);
          }
        }, frameRate);
        counterObs.unobserve(el);
      });
    }, { threshold: 0.4 });
    counters.forEach(c => counterObs.observe(c));

    // ============================================================
    // TESTIMONIAL SLIDER
    // ============================================================
    (function () {
      const track = document.getElementById('testiTrack');
      const allDots = document.querySelectorAll('.testi-dot');
      const slides = document.querySelectorAll('.testi-slide');
      const total = slides.length;
      let current = 0;
      let autoTimer;

      function goTo(idx) {
        current = ((idx % total) + total) % total;
        track.style.transform = `translateX(-${current * 100}%)`;
        allDots.forEach((d, i) => d.classList.toggle('active', i === current));
      }

      function startAuto() {
        autoTimer = setInterval(() => goTo(current + 1), 5200);
      }

      function stopAuto() {
        clearInterval(autoTimer);
      }

      document.getElementById('testiNext').addEventListener('click', () => { stopAuto(); goTo(current + 1); startAuto(); });
      document.getElementById('testiPrev').addEventListener('click', () => { stopAuto(); goTo(current - 1); startAuto(); });
      allDots.forEach(dot => dot.addEventListener('click', () => { stopAuto(); goTo(parseInt(dot.dataset.idx, 10)); startAuto(); }));

      const sliderWrap = document.querySelector('.testi-slider-outer');
      sliderWrap.addEventListener('mouseenter', stopAuto);
      sliderWrap.addEventListener('mouseleave', startAuto);

      // Touch / swipe support
      let touchStartX = 0;
      track.addEventListener('touchstart', e => { touchStartX = e.touches[0].clientX; }, { passive: true });
      track.addEventListener('touchend', e => {
        const diff = touchStartX - e.changedTouches[0].clientX;
        if (Math.abs(diff) > 40) { stopAuto(); goTo(current + (diff > 0 ? 1 : -1)); startAuto(); }
      }, { passive: true });

      startAuto();
    })();

    // ============================================================
    // HALL OF FAME SLIDER — infinite auto-loop
    // ============================================================
    (function () {
      const track = document.getElementById('hofTrack');
      if (!track) return;
      const dotsEl = document.getElementById('hofDots');

      // --- Clone slides for seamless infinite loop ---
      const origSlides = Array.from(track.querySelectorAll('.hof-slide'));
      const total = origSlides.length;
      // Prepend clones of last N slides, append clones of first N slides
      const clonesBefore = origSlides.map(s => s.cloneNode(true));
      const clonesAfter  = origSlides.map(s => s.cloneNode(true));
      clonesBefore.reverse().forEach(c => track.prepend(c));
      clonesAfter.forEach(c => track.appendChild(c));

      // current index is now offset by total (clones prepended)
      let current = total; // points to first real slide
      let autoTimer = null;
      let isTransitioning = false;

      function getPerView() {
        if (window.innerWidth >= 992) return 3;
        if (window.innerWidth >= 576) return 2;
        return 1;
      }

      function getSlideWidth() {
        const allSlides = track.querySelectorAll('.hof-slide');
        return allSlides[0].offsetWidth;
      }

      function getGap() {
        return parseFloat(getComputedStyle(track).columnGap) || 24;
      }

      function setTransform(idx, animate) {
        track.style.transition = animate
          ? 'transform 0.52s cubic-bezier(0.4, 0, 0.2, 1)'
          : 'none';
        const offset = idx * (getSlideWidth() + getGap());
        track.style.transform = `translateX(-${offset}px)`;
      }

      function buildDots() {
        dotsEl.innerHTML = '';
        for (let i = 0; i < total; i++) {
          const btn = document.createElement('button');
          btn.className = 'hof-dot' + (i === (current - total) ? ' active' : '');
          btn.setAttribute('aria-label', 'Slide ' + (i + 1));
          btn.addEventListener('click', () => { stopAuto(); goTo(i + total); startAuto(); });
          dotsEl.appendChild(btn);
        }
      }

      function updateDots() {
        const realIdx = ((current - total) % total + total) % total;
        dotsEl.querySelectorAll('.hof-dot').forEach((d, i) => d.classList.toggle('active', i === realIdx));
      }

      function updateNavBtns() {
        // Infinite loop — never disable
        document.getElementById('hofPrev').disabled = false;
        document.getElementById('hofNext').disabled = false;
      }

      function goTo(idx, animate = true) {
        current = idx;
        setTransform(current, animate);
        updateDots();
        updateNavBtns();
      }

      // After transition ends, silently jump if we're on a clone
      track.addEventListener('transitionend', () => {
        isTransitioning = false;
        const allCount = track.querySelectorAll('.hof-slide').length; // total * 3
        if (current >= total * 2) {
          // jumped past last real slide into clones-after
          current = current - total;
          setTransform(current, false);
        } else if (current < total) {
          // jumped before first real slide into clones-before
          current = current + total;
          setTransform(current, false);
        }
        updateDots();
      });

      function advance() {
        if (isTransitioning) return;
        isTransitioning = true;
        goTo(current + 1);
      }

      function startAuto() {
        stopAuto();
        autoTimer = setInterval(advance, 3200);
      }

      function stopAuto() {
        clearInterval(autoTimer);
      }

      document.getElementById('hofPrev').addEventListener('click', () => {
        stopAuto(); goTo(current - 1); startAuto();
      });
      document.getElementById('hofNext').addEventListener('click', () => {
        stopAuto(); goTo(current + 1); startAuto();
      });

      // Pause on hover
      const outer = document.querySelector('.hof-slider-outer');
      outer.addEventListener('mouseenter', stopAuto);
      outer.addEventListener('mouseleave', startAuto);

      // Touch / swipe support
      let touchStartX = 0;
      track.addEventListener('touchstart', e => { touchStartX = e.touches[0].clientX; stopAuto(); }, { passive: true });
      track.addEventListener('touchend', e => {
        const diff = touchStartX - e.changedTouches[0].clientX;
        if (Math.abs(diff) > 40) goTo(current + (diff > 0 ? 1 : -1));
        startAuto();
      }, { passive: true });

      let resizeTimer;
      window.addEventListener('resize', () => {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(() => {
          buildDots();
          setTransform(current, false);
        }, 150);
      });

      // Init
      setTransform(current, false);
      buildDots();
      updateNavBtns();
      startAuto();
    })();

    // ============================================================
    // MOBILE NAV — close on link click
    // ============================================================
    document.querySelectorAll('#navbarContent .nav-link').forEach(link => {
      link.addEventListener('click', () => {
        const nav = document.getElementById('navbarContent');
        if (typeof bootstrap !== 'undefined') {
          bootstrap.Collapse.getOrCreateInstance(nav).hide();
        } else {
          nav.classList.remove('show');
          const tog = document.querySelector('#mainNav .navbar-toggler');
          if (tog) { tog.setAttribute('aria-expanded', 'false'); tog.classList.add('collapsed'); }
        }
      });
    });
  </script>

</body>

</html>
