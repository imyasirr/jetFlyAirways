@extends('layouts.admin')

@section('content')
    <div class="flxf-head">
        <a href="{{ route('admin.flights.index') }}" class="flx-iconbtn" title="Back to flights">
            <span class="material-symbols-outlined" aria-hidden="true">arrow_back</span>
        </a>
        <span class="flx-avatar"><span class="material-symbols-outlined" aria-hidden="true">flight</span></span>
        <div class="flxf-head-text">
            <h1 class="section-title flxf-head-title">Add new flight</h1>
            <p class="flxf-head-sub">Details below appear on the public flight listing and detail pages.</p>
        </div>
    </div>

    <div class="card flxf-card">
        <form method="post" action="{{ route('admin.flights.store') }}">
            @include('admin.flights._form')
        </form>
    </div>
@endsection
