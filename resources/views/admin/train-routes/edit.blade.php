@extends('layouts.admin')

@section('content')
    <div class="card" style="max-width:900px;">
        <h1 class="section-title">Edit Train Route</h1>
        <form method="post" action="{{ route('admin.train-routes.update', $route) }}">
            @method('PUT')
            @include('admin.train-routes._form')
        </form>
    </div>
@endsection
