<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="description" content="{{ \Illuminate\Support\Str::limit(strip_tags((string) $item->deskripsi), 150) }}" />
  <title>{{ $item->judul }} — Parlemen Remaja DPR RI</title>

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
    body { font-family: 'Inter', sans-serif; }
    #detail-hero {
      background: linear-gradient(140deg, #41174B 0%, #940040 55%, #ff4d85 100%);
      padding-top: 100px;
      padding-bottom: 60px;
      color: #fff;
    }
    #detail-hero h1 { font-family: 'Poppins', sans-serif; font-weight: 700; font-size: 1.9rem; line-height: 1.3; }
    .detail-cover { border-radius: 18px; overflow: hidden; box-shadow: 0 18px 40px rgba(65,23,75,.18); margin-top: -40px; background:#fff; }
    .detail-cover img { width: 100%; max-height: 460px; object-fit: cover; }
    .detail-body { font-size: 1rem; line-height: 1.9; color: #3a3a3a; }
    .detail-meta { font-size: .85rem; color: rgba(255,255,255,.85); }
    .related-card { border: 1px solid #efe6ee; border-radius: 14px; overflow: hidden; height: 100%; text-decoration: none; color: inherit; display: block; transition: transform .2s ease, box-shadow .2s ease; }
    .related-card:hover { transform: translateY(-3px); box-shadow: 0 12px 26px rgba(148,0,64,.12); }
    .related-card-img { height: 140px; overflow: hidden; }
    .related-card h6 { font-family: 'Poppins', sans-serif; font-size: .92rem; font-weight: 600; }
  </style>
</head>

<body>
  <nav id="mainNav" class="navbar navbar-expand-lg sticky-top bg-white shadow-sm">
    <div class="container">
      <div class="d-flex align-items-center">
        <a class="d-flex align-items-center" style="padding: 10px;" href="{{ route('parja.public.index') }}">
          <img src="{{ asset('parja-landing/images/icons/logo-parja2.png') }}" alt="logo" style="height: 60px;">
        </a>
      </div>
      <div class="d-flex align-items-center gap-2">
        <a href="{{ route('parja.public.galeri') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3 py-2" style="font-size:0.83rem;">
          <i class="bi bi-arrow-left me-1"></i>Semua Berita
        </a>
      </div>
    </div>
  </nav>

  <section id="detail-hero">
    <div class="container">
      <div style="max-width: 820px;">
        <span class="badge rounded-pill px-3 py-2 mb-3" style="background: rgba(255,255,255,.15);">Publikasi</span>
        <h1 class="mb-3">{{ $item->judul }}</h1>
        <div class="detail-meta">
          <i class="bi bi-calendar3 me-1"></i>
          {{ optional(\Illuminate\Support\Carbon::make($item->tanggal_input))->translatedFormat('d F Y') ?? '-' }}
        </div>
      </div>
    </div>
  </section>

  <section class="pb-5">
    <div class="container" style="max-width: 880px;">
      @if (!empty($item->cover_uri))
      <div class="detail-cover">
        <img src="{{ $item->cover_uri }}" alt="{{ $item->judul }}">
      </div>
      @endif

      <div class="detail-body mt-4">
        @if (filled($item->deskripsi))
          {!! nl2br(e($item->deskripsi)) !!}
        @else
          <p class="text-secondary fst-italic">Belum ada deskripsi untuk publikasi ini.</p>
        @endif
      </div>

      @if (!empty($item->file_publikasi_uri))
      <div class="mt-4">
        <a href="{{ $item->file_publikasi_uri }}" target="_blank" rel="noopener noreferrer"
          class="btn btn-primary rounded-pill px-4 py-2" style="background:#bf0050; border-color:#bf0050; font-size:.9rem;">
          <i class="bi bi-box-arrow-up-right me-1"></i> Buka Dokumen / Lampiran
        </a>
      </div>
      @endif
    </div>
  </section>

  @if ($lainnya->isNotEmpty())
  <section class="py-5" style="background:#faf6f9;">
    <div class="container" style="max-width: 1040px;">
      <h5 class="mb-4" style="font-family:'Poppins',sans-serif; font-weight:600;">Berita Lainnya</h5>
      <div class="row g-4">
        @foreach ($lainnya as $rel)
        <div class="col-md-4">
          <a href="{{ route('parja.public.galeri.show', $rel->id) }}" class="related-card">
            <div class="related-card-img">
              @if (!empty($rel->cover_uri))
                <img src="{{ $rel->cover_uri }}" alt="{{ $rel->judul }}" class="img-fluid w-100 h-100" style="object-fit:cover;">
              @else
                <div class="d-flex align-items-center justify-content-center w-100 h-100" style="background:linear-gradient(135deg,#f5e9f7,#ffe0ec);color:#bf0050;">
                  <i class="bi bi-image" style="font-size:2rem;opacity:.4;"></i>
                </div>
              @endif
            </div>
            <div class="p-3">
              <h6 class="mb-1">{{ \Illuminate\Support\Str::limit($rel->judul, 60) }}</h6>
              <small class="text-secondary">
                <i class="bi bi-calendar3"></i> {{ optional(\Illuminate\Support\Carbon::make($rel->tanggal_input))->translatedFormat('d M Y') ?? '-' }}
              </small>
            </div>
          </a>
        </div>
        @endforeach
      </div>
    </div>
  </section>
  @endif

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
