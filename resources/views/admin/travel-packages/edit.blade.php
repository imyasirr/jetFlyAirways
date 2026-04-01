@extends('layouts.admin')

@section('content')
    <div class="card" style="max-width:900px;">
        <h1 class="section-title">Edit Package</h1>
        <form method="post" action="{{ route('admin.travel-packages.update', $package) }}">
            @method('PUT')
            @include('admin.travel-packages._form')
        </form>
    </div>
@endsection
