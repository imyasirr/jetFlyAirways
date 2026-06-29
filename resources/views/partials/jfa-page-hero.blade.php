@php
    $hasImage = filled($bannerImage ?? null);
    $heroStyle = '--jfa-page-accent: '.($accentColor ?? 'var(--jfa-booking-blue)').';';
@endphp

<section
    class="jfa-page-hero {{ $hasImage ? 'jfa-page-hero--has-image' : '' }} {{ $heroClass ?? '' }}"
    style="{{ $heroStyle }}"
    aria-label="{{ $title }}"
>
    @if($hasImage)
        <div class="jfa-page-hero__bg" aria-hidden="true">
            <img src="{{ e($bannerImage) }}" alt="">
        </div>
    @endif
    <div class="jfa-page-hero__overlay" aria-hidden="true"></div>
    <div class="jfa-container jfa-page-hero__inner">
        @if(!empty($breadcrumbs))
            <nav class="jfa-breadcrumb jfa-breadcrumb--light" aria-label="Breadcrumb">
                @foreach($breadcrumbs as $crumb)
                    @if(!empty($crumb['url']))
                        <a href="{{ $crumb['url'] }}">{{ $crumb['label'] }}</a>
                        <span class="material-symbols-outlined" aria-hidden="true">chevron_right</span>
                    @else
                        <span aria-current="page">{{ $crumb['label'] }}</span>
                    @endif
                @endforeach
            </nav>
        @endif

        @if(!empty($tagLine))
            <p class="jfa-page-hero__badge">{{ $tagLine }}</p>
        @endif

        @if(!empty($icon))
            <div class="jfa-page-hero__head">
                <span class="material-symbols-outlined filled jfa-page-hero__icon">{{ $icon }}</span>
                <h1>{{ $title }}</h1>
            </div>
        @else
            <h1>{{ $title }}</h1>
        @endif

        @if(filled($description ?? null))
            <p class="jfa-page-hero__desc">{{ $description }}</p>
        @endif
    </div>
</section>
