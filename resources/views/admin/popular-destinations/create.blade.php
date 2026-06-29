@extends('layouts.admin')

@section('content')
    <div class="card">
        <h1 class="section-title">Add popular destination</h1>
        <form method="post" action="{{ route('admin.popular-destinations.store') }}" enctype="multipart/form-data">
            @csrf
            @include('admin.popular-destinations._form', ['destination' => null])
            <button type="submit" class="btn" style="margin-top:12px;">Save destination</button>
        </form>
    </div>
@endsection
