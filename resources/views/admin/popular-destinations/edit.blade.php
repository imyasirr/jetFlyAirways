@extends('layouts.admin')

@section('content')
    <div class="card">
        <h1 class="section-title">Edit destination</h1>
        <form method="post" action="{{ route('admin.popular-destinations.update', $destination) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            @include('admin.popular-destinations._form', ['destination' => $destination])
            <button type="submit" class="btn" style="margin-top:12px;">Update destination</button>
        </form>
    </div>
@endsection
