@extends('layouts.app')

@section('body_class', 'page-jobs')

@section('title', 'Open roles — Jet Fly Airways')

@section('meta_description', 'Explore open positions at Jet Fly Airways and apply online.')

@section('full')
    @include('partials.jfa-page-hero', [
        'title' => 'Open roles',
        'description' => 'Join our product, operations and customer experience teams. Browse current vacancies and apply in a few minutes.',
        'icon' => 'work',
        'accentColor' => '#003B95',
        'heroClass' => 'jfa-cms-hero jfa-jobs-hero',
        'breadcrumbs' => [
            ['label' => 'Home', 'url' => route('home')],
            ['label' => 'Discover', 'url' => route('welcome')],
            ['label' => 'Open roles'],
        ],
    ])
@endsection

@section('content')
    <div class="jfa-jobs-intro">
        <p>Learn more about Jet Fly on our <a href="{{ route('pages.show', 'about') }}">About us</a> page or read about <a href="{{ route('pages.show', 'careers') }}">working with us</a>.</p>
    </div>

    @if(session('status'))
        <div class="jfa-flash jfa-flash--success" role="status">{{ session('status') }}</div>
    @endif

    <div class="jfa-jobs-grid">
        @forelse($careers as $job)
            <article class="jfa-job-card">
                <div class="jfa-job-card__head">
                    <span class="jfa-job-card__icon" aria-hidden="true"><span class="material-symbols-outlined filled">work</span></span>
                    <div>
                        <h2 class="jfa-job-card__title">{{ $job->job_title }}</h2>
                        <p class="jfa-job-card__meta">
                            @if($job->department)<span>{{ $job->department }}</span>@endif
                            @if($job->location)<span>{{ $job->location }}</span>@endif
                            <span>{{ $job->openings }} opening{{ $job->openings === 1 ? '' : 's' }}</span>
                        </p>
                    </div>
                </div>
                @if($job->apply_last_date)
                    <p class="jfa-job-card__deadline">
                        <span class="material-symbols-outlined" aria-hidden="true">event</span>
                        Apply by {{ $job->apply_last_date->format('d M Y') }}
                    </p>
                @endif
                @if($job->salary)
                    <p class="jfa-job-card__salary">{{ $job->salary }}</p>
                @endif
                <a class="btn jfa-job-card__btn" href="{{ route('jobs.show', $job) }}">
                    View &amp; apply
                    <span class="material-symbols-outlined" aria-hidden="true">arrow_forward</span>
                </a>
            </article>
        @empty
            <div class="jfa-job-empty">
                <span class="material-symbols-outlined filled" aria-hidden="true">work_off</span>
                <h2>No open roles right now</h2>
                <p>Check back soon or reach out through our contact page — we’d still love to hear from you.</p>
                <div class="jfa-job-empty__actions">
                    <a class="btn secondary" href="{{ route('contact.create') }}">Contact us</a>
                    <a class="btn" href="{{ route('pages.show', 'careers') }}">Careers info</a>
                </div>
            </div>
        @endforelse
    </div>
@endsection
