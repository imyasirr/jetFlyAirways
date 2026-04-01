@extends('layouts.admin')

@section('content')
    <div class="card" style="max-width:900px;">
        <h1 class="section-title">Edit Flight</h1>
        <form method="post" action="{{ route('admin.flights.update', $flight) }}">
            @method('PUT')
            @include('admin.flights._form')
        </form>
    </div>
@endsection
