@extends('layouts.admin')

@section('content')
    <div class="card">
        <h1 class="section-title">Add blog post</h1>
        <form method="post" action="{{ route('admin.blogs.store') }}" enctype="multipart/form-data">
            @csrf
            @include('admin.blogs._form', ['blog' => null])
            <button type="submit" class="btn" style="margin-top:12px;">Save</button>
        </form>
    </div>
@endsection
