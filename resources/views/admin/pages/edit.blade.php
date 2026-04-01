@extends('layouts.admin')

@section('content')
    <div class="card">
        <h1 class="section-title">Edit page</h1>
        <form method="post" action="{{ route('admin.pages.update', $page) }}">
            @csrf
            @method('PUT')
            @include('admin.pages._form', ['page' => $page])
            <button type="submit" class="btn" style="margin-top:12px;">Save</button>
        </form>
    </div>
@endsection
