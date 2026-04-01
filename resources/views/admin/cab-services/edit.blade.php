@extends('layouts.admin')

@section('content')
    <div class="card" style="max-width:900px;">
        <h1 class="section-title">Edit Cab Service</h1>
        <form method="post" action="{{ route('admin.cab-services.update', $service) }}">
            @method('PUT')
            @include('admin.cab-services._form')
        </form>
    </div>
@endsection
