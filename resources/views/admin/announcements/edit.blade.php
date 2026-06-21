@extends('layouts.admin')

@section('title', 'Edit announcement')

@section('content')
    <div class="card">
        <div style="margin-bottom:16px;">
            <a href="{{ route('admin.announcements.index') }}" style="font-size:13px;color:#64748b;text-decoration:none;">← Back to announcements</a>
            <h1 class="section-title" style="margin:8px 0 0;">Edit announcement</h1>
        </div>
        <form method="post" action="{{ route('admin.announcements.update', $announcement) }}">
            @csrf
            @method('PUT')
            @include('admin.announcements._form', ['announcement' => $announcement])
            <div style="display:flex;gap:10px;flex-wrap:wrap;margin-top:16px;">
                <button type="submit" class="btn">Update</button>
                <a class="btn secondary" href="{{ route('admin.announcements.index') }}">Cancel</a>
            </div>
        </form>
    </div>
@endsection
