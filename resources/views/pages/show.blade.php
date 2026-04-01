@extends('layouts.app')

@section('title', $page->title.' — Jet Fly Airways')

@section('meta_description', $page->meta_description ?? \Illuminate\Support\Str::limit(strip_tags($page->body), 160))

@section('content')
    <div class="card cms-page" style="max-width:820px;">
        {!! $page->body !!}
    </div>
@endsection
