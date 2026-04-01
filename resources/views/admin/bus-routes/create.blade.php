@extends('layouts.admin')

@section('content')
    <div class="card" style="max-width:900px;">
        <h1 class="section-title">Add Bus Route</h1>
        <form method="post" action="{{ route('admin.bus-routes.store') }}">
            @include('admin.bus-routes._form', ['route' => null])
        </form>
    </div>
@endsection
