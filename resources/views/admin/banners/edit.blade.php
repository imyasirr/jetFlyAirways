@extends('layouts.admin')

@section('content')
    <div class="card">
        <h1 class="section-title">Edit banner</h1>
        <form method="post" action="{{ route('admin.banners.update', $banner) }}" enctype="multipart/form-data" class="admin-form-grid">
            @csrf
            @method('PUT')
            @include('admin.banners._form', ['banner' => $banner])
            <div class="admin-field-full" style="margin-top:4px;">
                <button type="submit" class="btn">Update</button>
            </div>
        </form>
    </div>
@endsection
