@extends('layouts.admin')

@section('content')
    <div class="card" style="max-width:min(900px,100%);">
        <h2 class="section-title" style="font-size:1.1rem;">Flight details</h2>
        <p style="margin:0 0 14px;color:#64748b;font-size:14px;max-width:56ch;">Fields marked in the form are validated and shown on the public flight listing and detail pages.</p>
        <form method="post" action="{{ route('admin.flights.store') }}">
            @include('admin.flights._form')
        </form>
    </div>
@endsection
