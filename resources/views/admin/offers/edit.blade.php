@extends('layouts.admin')

@section('content')
    <div class="card">
        <h1 class="section-title">Edit offer</h1>
        <form method="post" action="{{ route('admin.offers.update', $offer) }}">
            @csrf
            @method('PUT')
            @include('admin.offers._form', ['offer' => $offer])
            <button type="submit" class="btn" style="margin-top:12px;">Update</button>
        </form>
    </div>
@endsection
