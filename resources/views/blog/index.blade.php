@extends('layouts.app')

@section('body_class', 'page-blog')

@section('title', 'Travel blog — Jet Fly Airways')

@section('full')
    @include('partials.jfa-page-hero', [
        'title' => 'Travel blog',
        'description' => $pageBanner?->subtitle ?: 'Stories, tips and inspiration for your next trip with Jet Fly Airways.',
        'icon' => 'article',
        'accentColor' => '#003B95',
        'bannerImage' => $pageBanner?->imageUrl(),
        'breadcrumbs' => [
            ['label' => 'Home', 'url' => route('home')],
            ['label' => 'Travel blog'],
        ],
    ])
@endsection

@section('content')
    <div class="jfa-grid jfa-grid--3" style="margin-top:8px;">
        @forelse($blogs as $blog)
            <article class="jfa-listing-card">
                @if($blog->cover_url)
                    <div class="jfa-listing-card__img">
                        <img src="{{ $blog->cover_url }}" alt="" loading="lazy" decoding="async">
                    </div>
                @endif
                <div class="jfa-listing-card__body">
                    <p class="jfa-listing-card__sub" style="margin-bottom:8px;">{{ $blog->publish_at?->format('d M Y') }}@if($blog->category) · {{ $blog->category }}@endif</p>
                    <h3 class="jfa-listing-card__title"><a href="{{ route('blog.show', $blog) }}">{{ $blog->title }}</a></h3>
                    <p class="jfa-listing-card__sub">{{ $blog->excerpt }}</p>
                    <a class="btn secondary" href="{{ route('blog.show', $blog) }}" style="margin-top:12px;display:inline-flex;">Read article</a>
                </div>
            </article>
        @empty
            <p style="grid-column:1/-1;color:var(--jfa-muted);">No posts yet — add from Admin → Blogs.</p>
        @endforelse
    </div>
    <div style="margin-top:24px;">{{ $blogs->links() }}</div>
@endsection
