@if(isset($banners) && $banners->isNotEmpty())
@php
    $displayBanners = $banners->filter(fn ($b) => (bool) \App\Support\PublicImageStorage::url($b->image))->values();
@endphp
@if($displayBanners->isNotEmpty())
@php $bannerCount = $displayBanners->count(); @endphp
<section class="home-banner-ribbon" aria-label="{{ __('jetfly.banner_aria') }}" aria-roledescription="carousel">
    <div class="home-banner-shell">
        <div class="home-banner-carousel" id="homeBannerCarousel">
            @if($bannerCount > 1)
                <button type="button" class="home-banner-nav home-banner-nav--prev" id="homeBannerPrev" aria-controls="homeBannerTrack" aria-label="Previous slide">
                    <span aria-hidden="true">‹</span>
                </button>
                <button type="button" class="home-banner-nav home-banner-nav--next" id="homeBannerNext" aria-controls="homeBannerTrack" aria-label="Next slide">
                    <span aria-hidden="true">›</span>
                </button>
            @endif
            <div class="home-banner-viewport" id="homeBannerViewport">
                <div class="home-banner-track-inner" id="homeBannerTrack" tabindex="0">
                @foreach($displayBanners as $b)
                    @php
                        $bannerSrc = \App\Support\PublicImageStorage::url($b->image);
                        $bannerLink = $b->link ? trim($b->link) : '';
                        $ctaLabel = $b->button_text ? trim($b->button_text) : ($bannerLink ? 'Explore' : '');
                    @endphp
                    <article class="home-banner-pane" aria-roledescription="slide" aria-label="Slide {{ $loop->iteration }} of {{ $bannerCount }}">
                        <div class="home-banner-slide">
                            <img
                                src="{{ $bannerSrc }}"
                                alt="{{ $b->title ?: __('jetfly.banner_promo') }}"
                                class="home-banner-img"
                                loading="{{ $loop->first ? 'eager' : 'lazy' }}"
                                decoding="async"
                            >
                            <div class="home-banner-gradient" aria-hidden="true"></div>
                            @if($b->title || $b->description || $bannerLink)
                                <div class="home-banner-content">
                                    @if($b->title)
                                        <h2 class="home-banner-heading">{{ $b->title }}</h2>
                                    @endif
                                    @if($b->description)
                                        <p class="home-banner-desc">{!! nl2br(e($b->description)) !!}</p>
                                    @endif
                                    @if($bannerLink && $ctaLabel !== '')
                                        <a href="{{ $bannerLink }}" class="home-banner-cta">{{ $ctaLabel }}</a>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </article>
                @endforeach
                </div>
            </div>
        </div>
        @if($bannerCount > 1)
            <div class="home-banner-dots" role="tablist" aria-label="{{ __('jetfly.banner_slides') }}">
                @foreach($displayBanners as $b)
                    <button type="button" class="home-banner-dot {{ $loop->first ? 'is-active' : '' }}" data-slide="{{ $loop->index }}" aria-label="{{ __('jetfly.slide') }} {{ $loop->iteration }}" aria-selected="{{ $loop->first ? 'true' : 'false' }}"></button>
                @endforeach
            </div>
        @endif
    </div>
</section>
@if($bannerCount > 1)
<script>
(function () {
    var track = document.getElementById('homeBannerTrack');
    if (!track) return;
    var shell = track.closest('.home-banner-shell');
    var carousel = document.getElementById('homeBannerCarousel');
    var dots = shell ? shell.querySelectorAll('.home-banner-dot') : [];
    var panes = track.querySelectorAll('.home-banner-pane');
    var prev = document.getElementById('homeBannerPrev');
    var next = document.getElementById('homeBannerNext');
    var reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    var autoplayMs = 6500;
    var timer = null;
    function scrollOpts() { return { behavior: reduceMotion ? 'auto' : 'smooth', block: 'nearest', inline: 'start' }; }
    function currentIndex() {
        var w = track.offsetWidth;
        if (w < 1) return 0;
        return Math.round(track.scrollLeft / w);
    }
    function setActive(i) {
        dots.forEach(function (d, j) {
            d.classList.toggle('is-active', j === i);
            d.setAttribute('aria-selected', j === i ? 'true' : 'false');
        });
    }
    function goTo(i) {
        var el = panes[i];
        if (el) el.scrollIntoView(scrollOpts());
        setActive(i);
    }
    function go(delta) {
        var idx = currentIndex();
        var n = Math.max(0, Math.min(panes.length - 1, idx + delta));
        goTo(n);
    }
    function goNextLoop() {
        var idx = currentIndex();
        if (idx >= panes.length - 1) goTo(0);
        else go(1);
    }
    function startAutoplay() {
        if (reduceMotion || panes.length < 2) return;
        stopAutoplay();
        timer = setInterval(goNextLoop, autoplayMs);
    }
    function stopAutoplay() {
        if (timer) clearInterval(timer);
        timer = null;
    }
    dots.forEach(function (dot, i) {
        dot.addEventListener('click', function () { stopAutoplay(); goTo(i); startAutoplay(); });
    });
    if (prev) prev.addEventListener('click', function () { stopAutoplay(); go(-1); startAutoplay(); });
    if (next) next.addEventListener('click', function () { stopAutoplay(); goNextLoop(); startAutoplay(); });
    track.addEventListener('scroll', function () {
        var w = track.offsetWidth;
        if (w < 1) return;
        var idx = Math.round(track.scrollLeft / w);
        if (idx >= 0 && idx < dots.length) setActive(idx);
    }, { passive: true });
    track.addEventListener('keydown', function (e) {
        if (e.key === 'ArrowLeft') { e.preventDefault(); stopAutoplay(); go(-1); startAutoplay(); }
        if (e.key === 'ArrowRight') { e.preventDefault(); stopAutoplay(); go(1); startAutoplay(); }
    });
    if (carousel) {
        carousel.addEventListener('mouseenter', stopAutoplay);
        carousel.addEventListener('mouseleave', startAutoplay);
        carousel.addEventListener('focusin', stopAutoplay);
        carousel.addEventListener('focusout', startAutoplay);
    }
    document.addEventListener('visibilitychange', function () {
        if (document.hidden) stopAutoplay();
        else startAutoplay();
    });
    startAutoplay();
})();
</script>
@endif
@endif
@endif
