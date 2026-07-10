@if(isset($slideshowInfos) && $slideshowInfos->count() > 0)
<div class="feed-post-card p-0 overflow-hidden mb-3">
    <div id="infoSlideshow" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-indicators">
            @foreach($slideshowInfos as $key => $info)
                <button type="button" data-bs-target="#infoSlideshow" data-bs-slide-to="{{ $key }}" class="{{ $key == 0 ? 'active' : '' }}" aria-label="Slide {{ $key + 1 }}"></button>
            @endforeach
        </div>
        <div class="carousel-inner">
            @foreach($slideshowInfos as $key => $info)
                <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                    <a href="{{ route('alumni.kegiatan.show', $info->id) }}" style="display:block; position:relative; width:100%; aspect-ratio:16/6; min-height:180px; max-height:380px; overflow:hidden; background:#1a1a2e;">
                        @if($info->thumbnail)
                            <img src="{{ asset('uploads/alumni-info/' . $info->thumbnail) }}"
                                 class="d-block"
                                 style="position:absolute; inset:0; width:100%; height:100%; object-fit:cover; object-position:center center;"
                                 alt="{{ $info->judul }}">
                        @else
                            <div style="position:absolute; inset:0; display:flex; align-items:center; justify-content:center; background:linear-gradient(135deg, var(--parja-magenta), var(--parja-purple)); color:white;">
                                <i class="ri-megaphone-line" style="font-size:4rem; opacity:0.5;"></i>
                            </div>
                        @endif
                        <div class="carousel-caption d-none d-md-block" style="background:rgba(0,0,0,0.5); border-radius:8px; padding:10px; bottom:20px;">
                            <span class="badge {{ $info->tipe_badge_class }} mb-2">{{ $info->tipe_label }}</span>
                            <h5 class="text-white mb-0" style="text-shadow:1px 1px 2px rgba(0,0,0,0.8);">{{ $info->judul }}</h5>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#infoSlideshow" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#infoSlideshow" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>
</div>
@endif
