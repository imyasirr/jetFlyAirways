@extends('layouts.app')

@section('title', $career->job_title.' — Careers')

@section('content')
    <div class="card" style="max-width:720px;">
        <p style="margin:0 0 8px;"><a href="{{ route('jobs.index') }}" style="font-size:14px;color:#64748b;">← All roles</a></p>
        <h1 class="section-title">{{ $career->job_title }}</h1>
        <p class="card-meta">
            @if($career->department)<strong>{{ $career->department }}</strong> · @endif
            @if($career->location){{ $career->location }} · @endif
            {{ $career->openings }} opening(s)
            @if($career->salary) · {{ $career->salary }} @endif
        </p>
        @if($career->apply_last_date)
            <p class="card-meta">Last date: {{ $career->apply_last_date->format('d M Y') }}</p>
        @endif
        @if($career->job_description)
            <div style="font-size:15px;line-height:1.6;color:#334155;margin:16px 0;">{!! nl2br(e($career->job_description)) !!}</div>
        @endif
        @if($career->required_skills)
            <h2 class="card-title" style="margin-top:20px;">Skills</h2>
            <div style="font-size:15px;line-height:1.6;color:#334155;">{!! nl2br(e($career->required_skills)) !!}</div>
        @endif

        <h2 class="section-title section-title-spaced">Apply</h2>
        <form method="post" action="{{ route('jobs.apply', $career) }}" enctype="multipart/form-data" style="display:grid;gap:12px;max-width:480px;">
            @csrf
            <label>Full name <input type="text" name="name" value="{{ old('name') }}" required></label>
            <label>Email <input type="email" name="email" value="{{ old('email') }}" required></label>
            <label>Phone <input type="text" name="phone" value="{{ old('phone') }}"></label>
            <label>Cover letter <textarea name="cover_letter" rows="5">{{ old('cover_letter') }}</textarea></label>
            <label>Resume (PDF / DOC, max 5MB) <input type="file" name="resume" accept=".pdf,.doc,.docx"></label>
            <button type="submit" class="btn">Submit application</button>
        </form>
    </div>
@endsection
