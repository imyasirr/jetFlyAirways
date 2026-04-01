@extends('layouts.admin')

@section('content')
    <div class="card" style="max-width:900px;">
        <h1 class="section-title">Add Package</h1>
        <form method="post" action="{{ route('admin.travel-packages.store') }}">
            @include('admin.travel-packages._form', ['package' => null])
        </form>
    </div>
@endsection
