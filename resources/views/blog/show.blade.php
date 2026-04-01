@extends('layouts.app')

@section('title', ($blog->meta_title ?: $blog->title).' — Jet Fly Airways')

@section('content')
    <article class="card" style="max-width:800px;">
        <p style="font-size:13px;color:#64748b;margin:0 0 8px;">{{ $blog->publish_at?->format('l, d F Y') }} @if($blog->category) · {{ $blog->category }} @endif @if($blog->author_name) · {{ $blog->author_name }} @endif</p>
        <h1 class="section-title" style="margin-bottom:12px;">{{ $blog->title }}</h1>
        @php $coverUrl = \App\Support\PublicImageStorage::url($blog->cover_image); @endphp
        @if($coverUrl)
            <div style="margin:0 0 16px;border-radius:12px;overflow:hidden;">
                <img src="{{ $coverUrl }}" alt="" style="width:100%;display:block;">
            </div>
        @endif
        <div class="blog-body" style="font-size:15px;line-height:1.65;color:#334155;">
            {!! $blog->content !!}
        </div>
        <p style="margin-top:24px;"><a href="{{ route('blog.index') }}" class="btn secondary">← All posts</a></p>
    </article>
@endsection
