@extends('layouts.admin')

@section('content')
    <div class="card" style="max-width:900px;">
        <h1 class="section-title">Add New Hotel</h1>
        <form method="post" action="{{ route('admin.hotels.store') }}">
            @include('admin.hotels._form')
        </form>
    </div>
@endsection
