@extends('layouts.admin')

@section('content')
    <div class="card" style="max-width:900px;">
        <h1 class="section-title">Add Cab Service</h1>
        <form method="post" action="{{ route('admin.cab-services.store') }}">
            @include('admin.cab-services._form', ['service' => null])
        </form>
    </div>
@endsection
