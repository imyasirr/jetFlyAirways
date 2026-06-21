@extends('layouts.admin')

@section('title', 'New announcement')

@section('content')
    <div class="card">
        <div style="margin-bottom:16px;">
            <a href="{{ route('admin.announcements.index') }}" style="font-size:13px;color:#64748b;text-decoration:none;">← Back to announcements</a>
            <h1 class="section-title" style="margin:8px 0 0;">New announcement</h1>
            <p style="font-size:14px;color:#64748b;margin:8px 0 0;max-width:72ch;line-height:1.55;">
                Choose publish time (now or future). Past dates are not allowed.
            </p>
        </div>
        <form method="post" action="{{ route('admin.announcements.store') }}">
            @csrf
            @include('admin.announcements._form', ['announcement' => null])
            <div style="display:flex;gap:10px;flex-wrap:wrap;margin-top:16px;">
                <button type="submit" class="btn">Save</button>
                <a class="btn secondary" href="{{ route('admin.announcements.index') }}">Cancel</a>
            </div>
        </form>
    </div>
@endsection
