@extends('layouts.admin')

@section('content')
    <div class="card">
        <h1 class="section-title">Edit blog post</h1>
        @if($blog->publish_at && $blog->publish_at->lte(now()))
            <p style="font-size:14px;color:#64748b;">Public URL: <a href="{{ route('blog.show', $blog) }}" target="_blank" rel="noopener">{{ route('blog.show', $blog) }}</a></p>
        @endif
        <form method="post" action="{{ route('admin.blogs.update', $blog) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            @include('admin.blogs._form', ['blog' => $blog])
            <button type="submit" class="btn" style="margin-top:12px;">Update</button>
        </form>
    </div>
@endsection
