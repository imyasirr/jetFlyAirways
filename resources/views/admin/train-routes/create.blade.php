@extends('layouts.admin')

@section('content')
    <div class="card" style="max-width:900px;">
        <h1 class="section-title">Add Train Route</h1>
        <form method="post" action="{{ route('admin.train-routes.store') }}">
            @include('admin.train-routes._form', ['route' => null])
        </form>
    </div>
@endsection
