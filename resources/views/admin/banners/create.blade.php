@extends('layouts.admin')

@section('content')
    <div class="card">
        <h1 class="section-title">Add banner</h1>
        <form method="post" action="{{ route('admin.banners.store') }}" enctype="multipart/form-data" class="admin-form-grid">
            @csrf
            @include('admin.banners._form', ['banner' => null])
            <div class="admin-field-full" style="margin-top:4px;">
                <button type="submit" class="btn">Save</button>
            </div>
        </form>
    </div>
@endsection
