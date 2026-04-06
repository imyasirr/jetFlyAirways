@extends('layouts.app')

@section('title', $page->title.' — Jet Fly Airways')

@section('meta_description', $page->meta_description ?? \Illuminate\Support\Str::limit(strip_tags($page->body), 160))

@section('content')
    <div class="card cms-page {{ $page->slug === 'sitemap' ? 'cms-page--wide' : '' }}">
        <article class="cms-page-prose">
            {!! $page->body !!}
        </article>
    </div>
@endsection
