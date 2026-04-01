@extends('layouts.admin')

@section('content')
    <div class="card">
        <h1 class="section-title">Add banner</h1>
        <form method="post" action="{{ route('admin.banners.store') }}" enctype="multipart/form-data">
            @csrf
            @include('admin.banners._form', ['banner' => null])
            <button type="submit" class="btn" style="margin-top:12px;">Save</button>
        </form>
    </div>
@endsection
