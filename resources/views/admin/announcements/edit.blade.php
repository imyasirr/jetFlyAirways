@extends('layouts.admin')

@section('content')
    <div class="card">
        <h1 class="section-title">Edit announcement</h1>
        <form method="post" action="{{ route('admin.announcements.update', $announcement) }}">
            @csrf
            @method('PUT')
            @include('admin.announcements._form', ['announcement' => $announcement])
            <button type="submit" class="btn" style="margin-top:12px;">Update</button>
        </form>
    </div>
@endsection
