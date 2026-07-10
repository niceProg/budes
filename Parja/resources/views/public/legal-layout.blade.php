<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="description" content="@yield('legal-title') — Parlemen Remaja DPR RI" />
  <title>@yield('legal-title') — Parlemen Remaja DPR RI</title>

  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link
    href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&family=Inter:wght@300;400;500;600&display=swap"
    rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
  <link rel="stylesheet" href="{{ asset('parja-landing/css/style.css') }}" />

  <style>
    html { scroll-behavior: smooth; scroll-padding-top: 90px; }
    #legal-hero {
      background: linear-gradient(140deg, #41174B 0%, #940040 55%, #ff4d85 100%);
      padding-top: 110px; padding-bottom: 80px; color: #fff;
      position: relative; overflow: hidden;
    }
    #legal-hero::before {
      content: ''; position: absolute; top: -160px; right: -160px;
      width: 560px; height: 560px; border-radius: 50%;
      background: rgba(255, 255, 255, 0.05); pointer-events: none;
    }
    .legal-card {
      background: #fff; border-radius: 22px;
      box-shadow: 0 24px 60px rgba(65, 23, 75, 0.12);
      margin-top: -56px; position: relative; z-index: 3; padding: 40px;
    }
    @media (max-width: 575px) { .legal-card { padding: 26px; } }
    .legal-card h2 {
      font-family: 'Poppins', sans-serif; font-weight: 700;
      color: #41174B; font-size: 1.15rem; margin-top: 26px; margin-bottom: 12px;
    }
    .legal-card h2:first-of-type { margin-top: 0; }
    .legal-card p, .legal-card li { font-size: 0.92rem; line-height: 1.8; color: #4a4150; }
    .legal-card ul { padding-left: 20px; }
    .legal-card a { color: #bf0050; }
    .legal-updated {
      display: inline-flex; align-items: center; gap: 8px;
      background: #f8eef5; color: #bf0050; border-radius: 999px;
      padding: 6px 16px; font-size: 0.82rem; font-weight: 600; margin-bottom: 22px;
    }
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
          <i class="bi bi-arrow-left me-1"></i>Kembali ke Beranda
        </a>
      </div>
    </div>
  </nav>

  <!-- HERO -->
  <section id="legal-hero">
    <div class="container position-relative" style="z-index:2;">
      <div style="max-width:760px;">
        <h1 style="font-family:'Poppins',sans-serif; font-weight:800; font-size:clamp(1.8rem,4.4vw,2.6rem);">@yield('legal-title')</h1>
        <p style="color:rgba(255,255,255,0.85); line-height:1.7; font-size:0.95rem; margin:0;">@yield('legal-subtitle')</p>
      </div>
    </div>
  </section>

  <!-- CONTENT -->
  <section class="pb-5">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-10 col-xl-9">
          <div class="legal-card">
            <span class="legal-updated"><i class="bi bi-clock-history"></i> Terakhir diperbarui: @yield('legal-updated', 'Juni 2026')</span>
            @yield('legal-content')

            <hr class="my-4">
            <p class="mb-0" style="font-size:0.86rem; color:#695a72;">
              Ada pertanyaan? Hubungi kami di
              <a href="mailto:parlemenremaja@dpr.go.id">parlemenremaja@dpr.go.id</a>.
            </p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- FOOTER -->
  <footer style="background:#2a0f31; color:rgba(255,255,255,0.6); padding:22px 0; text-align:center; font-size:0.8rem;">
    <div class="container">
      &copy; {{ date('Y') }} Sekretariat Jenderal DPR RI — Parlemen Remaja DPR RI.
      <span class="mx-2">&middot;</span>
      <a href="{{ route('parja.public.privasi') }}" style="color:rgba(255,255,255,0.6);">Kebijakan Privasi</a>
      <span class="mx-2">&middot;</span>
      <a href="{{ route('parja.public.syarat') }}" style="color:rgba(255,255,255,0.6);">Syarat &amp; Ketentuan</a>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc4s9bIOgUxi8T/jzmUl2gW6sRFoB7aHkKTHRcKJqaQ"
    crossorigin="anonymous"></script>
</body>
</html>
