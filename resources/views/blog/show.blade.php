@extends('layouts.app')

@section('body_class', 'page-blog-post')

@section('title', ($blog->meta_title ?: $blog->title).' — Jet Fly Airways')

@section('meta_description', $blog->meta_description ?: \Illuminate\Support\Str::limit(strip_tags($blog->excerpt), 160))

@section('full')
@php
    $hasCover = (bool) $blog->cover_url;
@endphp
<header class="blog-post-hero {{ $hasCover ? 'blog-post-hero--photo' : '' }}" aria-label="Article header">
    @if($hasCover)
        <img src="{{ $blog->cover_url }}" alt="" class="blog-post-hero__img" fetchpriority="high" decoding="async">
        <div class="blog-post-hero__scrim" aria-hidden="true"></div>
    @endif
    <div class="container blog-post-hero__inner">
        <h1 class="blog-post-hero__title">{{ $blog->title }}</h1>
        <div class="blog-post-hero__meta">
            @if($blog->publish_at)
                <span class="blog-post-meta-pill">{{ $blog->publish_at->format('d M Y') }}</span>
            @endif
            @if($blog->author_name)
                <span class="blog-post-meta-pill">By {{ $blog->author_name }}</span>
            @endif
            @if($blog->tags)
                <span class="blog-post-meta-pill blog-post-meta-pill--muted">{{ $blog->tags }}</span>
            @endif
        </div>
    </div>
</header>
@endsection

@section('content')
<article class="blog-post-article">
    <div class="blog-post-prose blog-body">
        {!! $blog->rendered_content !!}
    </div>
    <footer class="blog-post-footer">
        <a href="{{ route('blog.index') }}" class="btn secondary blog-post-back">← All articles</a>
    </footer>
</article>
@endsection
