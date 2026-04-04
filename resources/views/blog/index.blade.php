@extends('layouts.app')

@section('title', 'Travel blog — Jet Fly Airways')

@section('content')
    <h1 class="section-title">Travel blog</h1>
    <div class="grid">
        @forelse($blogs as $blog)
            <article class="card">
                @if($blog->cover_url)
                    <div class="blog-card-cover">
                        <img src="{{ $blog->cover_url }}" alt="" loading="lazy" decoding="async">
                    </div>
                @endif
                <p style="font-size:12px;color:#64748b;margin:0 0 6px;">{{ $blog->publish_at?->format('d M Y') }} @if($blog->category) · {{ $blog->category }} @endif</p>
                <h2 class="card-title"><a href="{{ route('blog.show', $blog) }}">{{ $blog->title }}</a></h2>
                <p class="card-meta">{{ $blog->excerpt }}</p>
                <a class="btn secondary btn-block" href="{{ route('blog.show', $blog) }}">Read</a>
            </article>
        @empty
            <p class="card empty-hint" style="grid-column:1/-1;">No posts yet — add from Admin → Blogs.</p>
        @endforelse
    </div>
    <div style="margin-top:16px;">{{ $blogs->links() }}</div>
@endsection
