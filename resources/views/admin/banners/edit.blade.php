@extends('layouts.admin')

@section('content')
    <div class="card">
        <h1 class="section-title">Edit banner</h1>
        <form method="post" action="{{ route('admin.banners.update', $banner) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            @include('admin.banners._form', ['banner' => $banner])
            <button type="submit" class="btn" style="margin-top:12px;">Update</button>
        </form>
    </div>
@endsection
