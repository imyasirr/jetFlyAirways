@extends('layouts.admin')

@section('content')
    <div class="card">
        <p><a href="{{ route('admin.career-applications.index') }}">← Applications</a></p>
        <h1 class="section-title">{{ $application->name }}</h1>
        <p style="color:#64748b;">{{ $application->email }} @if($application->phone)· {{ $application->phone }}@endif</p>
        <p><strong>Role:</strong> {{ $application->career?->job_title ?? '—' }}</p>
        @if($application->cover_letter)
            <h2 class="section-title" style="margin-top:16px;font-size:1rem;">Cover letter</h2>
            <div style="white-space:pre-wrap;font-size:14px;line-height:1.55;">{{ $application->cover_letter }}</div>
        @endif
        <div style="margin-top:20px;display:flex;gap:10px;flex-wrap:wrap;">
            @if($application->resume_path)
                <a class="btn" href="{{ route('admin.career-applications.resume', $application) }}">Download resume</a>
            @endif
            <form method="post" action="{{ route('admin.career-applications.destroy', $application) }}" onsubmit="return confirm('Delete this application?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn" style="background:#b91c1c;">Delete</button>
            </form>
        </div>
    </div>
@endsection
