@extends('layouts.admin')

@section('content')
    <div class="card">
        <h1 class="section-title">Edit vacancy</h1>
        <form method="post" action="{{ route('admin.careers.update', $career) }}">
            @csrf
            @method('PUT')
            @include('admin.careers._form', ['career' => $career])
            <button type="submit" class="btn" style="margin-top:12px;">Update</button>
        </form>
    </div>
@endsection
