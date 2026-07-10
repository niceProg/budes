@php
    $khususEnabled = \Modules\Magang\App\Models\SiteSetting::lowonganKhususEnabled();
    // ajaxPagination = true (default, beranda) -> tombol AJAX; false (halaman Lowongan Khusus) -> link GET biasa.
    $ajaxPagination = $ajaxPagination ?? true;
    $pageUrl = fn ($p) => request()->fullUrlWithQuery(['page' => $p]);
@endphp
@if($lowongan && $lowongan->total() > 0)
<div class="lowongan-stats">
    <span class="lowongan-stat-count">{{ $lowongan->total() }}&nbsp;&nbsp;&nbsp;&nbsp;Lowongan&nbsp;Tersedia</span>
    @if($query)
    <span class="lowongan-stat-divider">•</span>
    <span class="lowongan-stat-count" style="color: var(--gold-solid);">Hasil pencarian: "{{ $query }}"</span>
    @endif
</div>

<div class="lowongan-grid lowongan-grid-magenta">
    @foreach($lowongan as $item)
    @php
        $isNonaktif = $item->status == 9;
    @endphp
    <div class="lowongan-card lowongan-card-magenta {{ $isNonaktif ? 'lowongan-card-nonaktif' : '' }}" style="{{ $isNonaktif ? 'opacity: 0.8; filter: grayscale(0.2); background: linear-gradient(180deg, #f8f9fa 0%, #e9ecef 100%);' : '' }}">
        <div class="lowongan-card-header">
            <div class="lowongan-logo-wrap">
                @if($item->foto)
                <img src="{{ file_url('lowongan/' . $item->foto) }}" alt="{{ $item->title }}" class="lowongan-logo" onerror="this.onerror=null; this.parentElement.classList.add('lowongan-logo-placeholder'); this.style.display='none'; this.parentElement.innerHTML='<i class=\'fa-solid fa-briefcase\'></i>';">
                @else
                <div class="lowongan-logo-placeholder">
                    <i class="fa-solid fa-briefcase"></i>
                </div>
                @endif
            </div>
            @if($item->satker)
            <p class="lowongan-company">{{ $item->satker->nama }}</p>
            @endif
        </div>
        <div class="lowongan-card-body">
            <h3 class="lowongan-title" style="{{ $isNonaktif ? 'color: #64748b;' : '' }}">{{ $item->title }}</h3>
            @php $jenisList = $item->jenisList(); @endphp
            <div class="lowongan-jenis-badges" style="display:flex; gap:6px; flex-wrap:wrap; margin:6px 0 10px;">
                @foreach($jenisList as $jenis)
                    @if($jenis === 'Magang')
                        <span style="display:inline-flex; align-items:center; gap:4px; font-size:0.72rem; font-weight:800; padding:3px 10px; border-radius:999px; background:#eef2ff; color:#4338ca; border:1px solid #c7d2fe;">
                            <i class="fa-solid fa-graduation-cap"></i> Magang
                        </span>
                    @else
                        <span style="display:inline-flex; align-items:center; gap:4px; font-size:0.72rem; font-weight:800; padding:3px 10px; border-radius:999px; background:#ecfdf5; color:#047857; border:1px solid #a7f3d0;">
                            <i class="fa-solid fa-school"></i> PKL
                        </span>
                    @endif
                @endforeach
            </div>
            <div class="lowongan-meta" style="display:flex; align-items:baseline; {{ $isNonaktif ? 'color: #94a3b8;' : '' }}">
                <span style="flex:0 0 96px;">Kuota</span>
                <span style="flex:1;">:&nbsp;@if($item->jumlah_posisi){{ $item->jumlah_posisi }} Peserta @else Tak terbatas @endif</span>
            </div>
            <p class="lowongan-deadline" style="display:flex; align-items:baseline; {{ $isNonaktif ? 'color: #94a3b8;' : '' }}">
                <span style="flex:0 0 96px;">Batas Akhir</span>
                <span style="flex:1;">:&nbsp;<strong>{{ $item->deadline ? $item->deadline->locale('id')->translatedFormat('d F Y') : 'Tidak ada batas waktu' }}</strong></span>
            </p>
            <p class="lowongan-deadline" style="display:flex; align-items:baseline; {{ $isNonaktif ? 'color: #94a3b8;' : '' }}">
                <span style="flex:0 0 96px;">Periode</span>
                <span style="flex:1;">:&nbsp;<strong>@if($item->tanggal_mulai && $item->tanggal_selesai){{ $item->tanggal_mulai->locale('id')->translatedFormat('d M Y') }} &ndash; {{ $item->tanggal_selesai->locale('id')->translatedFormat('d M Y') }}@elseif($item->tanggal_mulai)Mulai {{ $item->tanggal_mulai->locale('id')->translatedFormat('d M Y') }}@else Fleksibel @endif</strong></span>
            </p>
        </div>
        <div class="lowongan-card-footer">
            @if($isNonaktif)
            <a href="#" class="btn-lowongan" style="opacity: 0.6; cursor: not-allowed; pointer-events: none; background: #e2e8f0; border-color: #cbd5e1; color: #94a3b8;" onclick="return false;">
                <span>Lihat Detail</span> <i class="fa-solid fa-lock"></i>
            </a>
            @elseif(!$khususEnabled)
            <a href="#" class="btn-lowongan" style="opacity: 0.7; cursor: not-allowed; background: #e2e8f0; border-color: #cbd5e1; color: #94a3b8;" onclick="pendaftaranKhususTutup(event)" title="Pendaftaran lowongan sedang ditutup sementara">
                <span>Pendaftaran Ditutup</span> <i class="fa-solid fa-lock"></i>
            </a>
            @else
            <a href="{{ route('register2') }}?lowongan={{ $item->id }}" class="btn-lowongan">
                <span>Lihat Detail</span> <i class="fa-solid fa-arrow-right"></i>
            </a>
            @endif
        </div>
    </div>
    @endforeach
</div>

{{-- Custom Pagination --}}
@if($lowongan->hasPages())
<div class="lowongan-pagination-wrapper">
    <div class="lowongan-pagination">
        {{-- Previous Button --}}
        @if($lowongan->onFirstPage())
            <span class="pagination-btn pagination-btn-disabled">
                <i class="fa-solid fa-chevron-left"></i>
            </span>
        @else
            <a href="{{ $ajaxPagination ? '#' : $pageUrl($lowongan->currentPage() - 1) }}" class="pagination-btn {{ $ajaxPagination ? 'pagination-btn-ajax' : '' }}" @if($ajaxPagination) data-page="{{ $lowongan->currentPage() - 1 }}" @endif>
                <i class="fa-solid fa-chevron-left"></i>
            </a>
        @endif

        {{-- Page Numbers (Smart pagination: hanya tampilkan 5 halaman di sekitar current page) --}}
        @php
            $currentPage = $lowongan->currentPage();
            $lastPage = $lowongan->lastPage();
            $startPage = max(1, $currentPage - 2);
            $endPage = min($lastPage, $currentPage + 2);

            // Jika di awal, tampilkan 1-5
            if ($currentPage <= 3) {
                $startPage = 1;
                $endPage = min(5, $lastPage);
            }
            // Jika di akhir, tampilkan 5 terakhir
            if ($currentPage >= $lastPage - 2) {
                $startPage = max(1, $lastPage - 4);
                $endPage = $lastPage;
            }
        @endphp

        {{-- Tampilkan halaman pertama jika tidak di range --}}
        @if($startPage > 1)
            <a href="{{ $ajaxPagination ? '#' : $pageUrl(1) }}" class="pagination-btn {{ $ajaxPagination ? 'pagination-btn-ajax' : '' }}" @if($ajaxPagination) data-page="1" @endif>1</a>
            @if($startPage > 2)
                <span class="pagination-btn pagination-btn-disabled">...</span>
            @endif
        @endif

        {{-- Tampilkan range halaman --}}
        @for($page = $startPage; $page <= $endPage; $page++)
            @if($page == $currentPage)
                <span class="pagination-btn pagination-btn-active">{{ $page }}</span>
        @else
            <a href="{{ $ajaxPagination ? '#' : $pageUrl($page) }}" class="pagination-btn {{ $ajaxPagination ? 'pagination-btn-ajax' : '' }}" @if($ajaxPagination) data-page="{{ $page }}" @endif>{{ $page }}</a>
            @endif
        @endfor

        {{-- Tampilkan halaman terakhir jika tidak di range --}}
        @if($endPage < $lastPage)
            @if($endPage < $lastPage - 1)
                <span class="pagination-btn pagination-btn-disabled">...</span>
            @endif
            <a href="{{ $ajaxPagination ? '#' : $pageUrl($lastPage) }}" class="pagination-btn {{ $ajaxPagination ? 'pagination-btn-ajax' : '' }}" @if($ajaxPagination) data-page="{{ $lastPage }}" @endif>{{ $lastPage }}</a>
        @endif

        {{-- Next Button --}}
        @if($lowongan->hasMorePages())
            <a href="{{ $ajaxPagination ? '#' : $pageUrl($lowongan->currentPage() + 1) }}" class="pagination-btn {{ $ajaxPagination ? 'pagination-btn-ajax' : '' }}" @if($ajaxPagination) data-page="{{ $lowongan->currentPage() + 1 }}" @endif>
                <i class="fa-solid fa-chevron-right"></i>
            </a>
        @else
            <span class="pagination-btn pagination-btn-disabled">
                <i class="fa-solid fa-chevron-right"></i>
            </span>
        @endif
    </div>
    <div class="pagination-info">
        Menampilkan {{ $lowongan->firstItem() ?? 0 }} - {{ $lowongan->lastItem() ?? 0 }} dari {{ $lowongan->total() }} lowongan
    </div>
</div>
@endif
@else
<div class="lowongan-empty">
    <i class="fa-solid fa-inbox"></i>
    @if($query)
        <p>Tidak ada lowongan yang ditemukan untuk "{{ $query }}". Silakan coba kata kunci lain.</p>
    @else
        <p>Belum ada lowongan tersedia saat ini. Silakan cek kembali nanti.</p>
    @endif
</div>
@endif
