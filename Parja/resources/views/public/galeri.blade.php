<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="description" content="Berita & galeri kegiatan Parlemen Remaja DPR RI." />
  <title>Berita &amp; Galeri Kegiatan — Parlemen Remaja DPR RI</title>

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
    html { scroll-behavior: smooth; }
    body { font-family: 'Inter', sans-serif; }
    #galeri-hero {
      background: linear-gradient(140deg, #41174B 0%, #940040 55%, #ff4d85 100%);
      padding-top: 110px;
      padding-bottom: 70px;
      color: #fff;
    }
    #galeri-hero h1 { font-family: 'Poppins', sans-serif; font-weight: 700; }
    .gallery-card { border: 1px solid #efe6ee; border-radius: 16px; overflow: hidden; height: 100%; transition: transform .2s ease, box-shadow .2s ease; }
    .gallery-card:hover { transform: translateY(-4px); box-shadow: 0 14px 30px rgba(148,0,64,.12); }
    .gallery-card-img { height: 180px; overflow: hidden; }
    .gallery-card .card-title { font-family: 'Poppins', sans-serif; font-size: 1rem; font-weight: 600; }
    .gallery-card .card-text { font-size: .85rem; color: #6c757d; }
    .gallery-tag { display: inline-block; font-size: .72rem; font-weight: 600; padding: .2rem .6rem; border-radius: 999px; margin-bottom: .5rem; }
    .gallery-card-date { font-size: .78rem; color: #9aa0a6; }
    .gallery-card-link { font-size: .82rem; font-weight: 600; color: #bf0050; text-decoration: none; }
    .gallery-card-link:hover { text-decoration: underline; }
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
        <a href="{{ route('parja.public.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3 py-2" style="font-size:0.83rem;">
          <i class="bi bi-arrow-left me-1"></i>Kembali
        </a>
      </div>
    </div>
  </nav>

  <section id="galeri-hero">
    <div class="container">
      <span class="badge rounded-pill px-3 py-2 mb-3" style="background: rgba(255,255,255,.15);">Dokumentasi</span>
      <h1 class="mb-2">Berita &amp; Galeri Kegiatan</h1>
      <p class="mb-0" style="max-width: 560px; opacity: .9; font-size: .95rem;">
        Lihat bagaimana para pelajar Indonesia belajar, bersuara, dan berdebat layaknya anggota dewan sesungguhnya.
      </p>
    </div>
  </section>

  <section class="py-5">
    <div class="container">
      <div class="row g-4">
        @forelse ($publikasi as $item)
        <div class="col-md-6 col-lg-3">
          <div class="gallery-card card">
            <a href="{{ route('parja.public.galeri.show', $item->id) }}" class="gallery-card-img d-block">
              @if (!empty($item->cover_uri))
                <img src="{{ $item->cover_uri }}" alt="{{ $item->judul }}" class="img-fluid w-100 h-100" style="object-fit:cover;">
              @else
                <div class="d-flex align-items-center justify-content-center w-100 h-100" style="background:linear-gradient(135deg,#f5e9f7,#ffe0ec);color:#bf0050;">
                  <i class="bi bi-image" style="font-size:2.4rem;opacity:.4;"></i>
                </div>
              @endif
            </a>
            <div class="card-body">
              <span class="gallery-tag" style="color:#bf0050; background: rgba(191,0,80,0.08);">Publikasi</span>
              <h5 class="card-title">{{ $item->judul }}</h5>
              <p class="card-text">{{ \Illuminate\Support\Str::limit(strip_tags((string) $item->deskripsi), 110) }}</p>
            </div>
            <div class="card-footer d-flex align-items-center justify-content-between bg-white border-0 pb-3">
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
        <div class="col-12">
          <div class="text-center text-secondary py-5">
            <i class="bi bi-journal-text" style="font-size: 2.6rem; opacity:.3;"></i>
            <p class="mt-3 mb-0">Belum ada berita atau publikasi yang dipublikasikan.</p>
          </div>
        </div>
        @endforelse
      </div>

      @if ($publikasi->hasPages())
      <nav class="d-flex justify-content-center mt-5">
        <ul class="pagination">
          <li class="page-item {{ $publikasi->onFirstPage() ? 'disabled' : '' }}">
            <a class="page-link" href="{{ $publikasi->previousPageUrl() ?? '#' }}">
              <i class="bi bi-chevron-left"></i>
            </a>
          </li>
          <li class="page-item disabled">
            <span class="page-link">Halaman {{ $publikasi->currentPage() }} / {{ $publikasi->lastPage() }}</span>
          </li>
          <li class="page-item {{ $publikasi->hasMorePages() ? '' : 'disabled' }}">
            <a class="page-link" href="{{ $publikasi->nextPageUrl() ?? '#' }}">
              <i class="bi bi-chevron-right"></i>
            </a>
          </li>
        </ul>
      </nav>
      @endif
    </div>
  </section>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
