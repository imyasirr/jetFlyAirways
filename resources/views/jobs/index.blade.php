@extends('layouts.app')

@section('title', 'Careers & open roles — Jet Fly Airways')

@section('meta_description', 'Explore open positions at Jet Fly Airways and apply online.')

@section('content')
    <h1 class="section-title">Open roles</h1>
    <p style="color:#64748b;margin-top:0;">For company information see our <a href="{{ route('pages.show', 'about') }}">About</a> page. Static careers content may also be on <a href="{{ route('pages.show', 'careers') }}">Careers (CMS)</a>.</p>
    <div class="grid">
        @forelse($careers as $job)
            <div class="card">
                <h2 class="card-title">{{ $job->job_title }}</h2>
                <p class="card-meta">
                    @if($job->department){{ $job->department }} · @endif
                    @if($job->location){{ $job->location }} · @endif
                    {{ $job->openings }} opening(s)
                </p>
                @if($job->apply_last_date)
                    <p class="card-meta">Apply by {{ $job->apply_last_date->format('d M Y') }}</p>
                @endif
                <a class="btn secondary btn-block" href="{{ route('jobs.show', $job) }}">View &amp; apply</a>
            </div>
        @empty
            <p class="card empty-hint" style="grid-column:1/-1;">No vacancies right now — check back later or contact us.</p>
        @endforelse
    </div>
@endsection
