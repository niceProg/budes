@if(isset($homeBanners) && $homeBanners->count() > 0)
<div class="home-hero-banner">
    <div id="homeHeroBanner" class="carousel slide" data-bs-ride="carousel">
        @if($homeBanners->count() > 1)
            <div class="carousel-indicators">
                @foreach($homeBanners as $key => $banner)
                    <button type="button" data-bs-target="#homeHeroBanner" data-bs-slide-to="{{ $key }}" class="{{ $key === 0 ? 'active' : '' }}" aria-label="Slide {{ $key + 1 }}"></button>
                @endforeach
            </div>
        @endif

        <div class="carousel-inner">
            @foreach($homeBanners as $key => $banner)
                <div class="carousel-item {{ $key === 0 ? 'active' : '' }}">
                    <img src="{{ $banner['image_url'] }}" class="d-block w-100" alt="{{ $banner['title'] ?? 'Banner Informasi' }}">

                    @if(!empty($banner['title']))
                        <div class="carousel-caption d-none d-md-block">
                            <h5>{{ $banner['title'] }}</h5>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>

        @if($homeBanners->count() > 1)
            <button class="carousel-control-prev" type="button" data-bs-target="#homeHeroBanner" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#homeHeroBanner" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        @endif
    </div>
</div>
@endif
