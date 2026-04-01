@extends('layouts.admin')

@section('content')
    <div class="card">
        <h1 class="section-title">New announcement</h1>
        <form method="post" action="{{ route('admin.announcements.store') }}">
            @csrf
            @include('admin.announcements._form', ['announcement' => null])
            <button type="submit" class="btn" style="margin-top:12px;">Save</button>
        </form>
    </div>
@endsection
