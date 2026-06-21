@extends('layouts.app')

@section('body_class', 'page-jobs page-jobs-detail')

@section('title', $career->job_title.' — Careers')

@section('full')
    @include('partials.jfa-page-hero', [
        'title' => $career->job_title,
        'description' => collect([$career->department, $career->location, $career->openings.' opening'.($career->openings === 1 ? '' : 's')])->filter()->implode(' · '),
        'icon' => 'work',
        'accentColor' => '#003B95',
        'heroClass' => 'jfa-cms-hero jfa-jobs-hero',
        'breadcrumbs' => [
            ['label' => 'Home', 'url' => route('home')],
            ['label' => 'Open roles', 'url' => route('jobs.index')],
            ['label' => $career->job_title],
        ],
    ])
@endsection

@section('content')
    <div class="jfa-job-detail">
        <aside class="jfa-job-detail__aside">
            <div class="jfa-job-detail__facts">
                @if($career->department)
                    <div class="jfa-job-fact">
                        <span class="material-symbols-outlined filled">corporate_fare</span>
                        <div>
                            <small>Department</small>
                            <strong>{{ $career->department }}</strong>
                        </div>
                    </div>
                @endif
                @if($career->location)
                    <div class="jfa-job-fact">
                        <span class="material-symbols-outlined filled">location_on</span>
                        <div>
                            <small>Location</small>
                            <strong>{{ $career->location }}</strong>
                        </div>
                    </div>
                @endif
                <div class="jfa-job-fact">
                    <span class="material-symbols-outlined filled">group</span>
                    <div>
                        <small>Openings</small>
                        <strong>{{ $career->openings }}</strong>
                    </div>
                </div>
                @if($career->salary)
                    <div class="jfa-job-fact">
                        <span class="material-symbols-outlined filled">payments</span>
                        <div>
                            <small>Compensation</small>
                            <strong>{{ $career->salary }}</strong>
                        </div>
                    </div>
                @endif
                @if($career->apply_last_date)
                    <div class="jfa-job-fact">
                        <span class="material-symbols-outlined filled">event</span>
                        <div>
                            <small>Apply by</small>
                            <strong>{{ $career->apply_last_date->format('d M Y') }}</strong>
                        </div>
                    </div>
                @endif
            </div>
            <a class="jfa-job-back" href="{{ route('jobs.index') }}">
                <span class="material-symbols-outlined" aria-hidden="true">arrow_back</span>
                All open roles
            </a>
        </aside>

        <div class="jfa-job-detail__main">
            @if($career->job_description)
                <section class="jfa-job-block">
                    <h2>About the role</h2>
                    <div class="jfa-job-prose">{!! nl2br(e($career->job_description)) !!}</div>
                </section>
            @endif

            @if($career->required_skills)
                <section class="jfa-job-block">
                    <h2>Skills &amp; requirements</h2>
                    <div class="jfa-job-prose">{!! nl2br(e($career->required_skills)) !!}</div>
                </section>
            @endif

            <section class="jfa-job-apply">
                <h2>Apply for this role</h2>
                <p>Fill in your details below. Our HR team will review your application and get back to you.</p>
                <form method="post" action="{{ route('jobs.apply', $career) }}" enctype="multipart/form-data" class="jfa-job-apply__form">
                    @csrf
                    <div class="jfa-form-grid jfa-form-grid--2">
                        <label>Full name
                            <input type="text" name="name" value="{{ old('name') }}" required autocomplete="name">
                        </label>
                        <label>Email
                            <input type="email" name="email" value="{{ old('email') }}" required autocomplete="email">
                        </label>
                        <label>Phone
                            <input type="text" name="phone" value="{{ old('phone') }}" autocomplete="tel">
                        </label>
                        <label>Resume (PDF / DOC, max 5MB)
                            <input type="file" name="resume" accept=".pdf,.doc,.docx">
                        </label>
                    </div>
                    <label class="jfa-field-full">Cover letter
                        <textarea name="cover_letter" rows="5" placeholder="Tell us why you’re a great fit…">{{ old('cover_letter') }}</textarea>
                    </label>
                    <div class="form-actions">
                        <button type="submit" class="btn">Submit application</button>
                    </div>
                </form>
            </section>
        </div>
    </div>
@endsection
