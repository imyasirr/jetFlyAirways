@extends('layouts.admin')

@section('content')
    <div class="card" style="max-width:900px;">
        <h1 class="section-title">Edit Bus Route</h1>
        <form method="post" action="{{ route('admin.bus-routes.update', $route) }}">
            @method('PUT')
            @include('admin.bus-routes._form')
        </form>
    </div>
@endsection
