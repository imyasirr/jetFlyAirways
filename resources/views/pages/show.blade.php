@extends('layouts.app')

@section('body_class', 'page-cms')

@section('title', $page->title.' — Jet Fly Airways')

@section('meta_description', $page->meta_description ?? \Illuminate\Support\Str::limit(strip_tags($page->body), 160))

@section('full')
    <div class="jfa-cms-hero">
        <div class="jfa-container">
            <nav class="jfa-breadcrumb jfa-breadcrumb--light" aria-label="Breadcrumb">
                <a href="{{ route('home') }}">Home</a>
                <span class="material-symbols-outlined" aria-hidden="true">chevron_right</span>
                <span aria-current="page">{{ $page->title }}</span>
            </nav>
            <h1>{{ $page->title }}</h1>
            @if(filled($page->meta_description))
                <p class="jfa-cms-hero__desc">{{ $page->meta_description }}</p>
            @endif
        </div>
    </div>
@endsection

@section('content')
    <div class="jfa-cms-card cms-page {{ $page->slug === 'sitemap' ? 'cms-page--wide' : '' }}">
        <article class="cms-page-prose">
            {!! $page->body !!}
        </article>
    </div>
@endsection
