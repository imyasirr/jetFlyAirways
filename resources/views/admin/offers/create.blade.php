@extends('layouts.admin')

@section('content')
    <div class="card">
        <h1 class="section-title">Add offer</h1>
        <form method="post" action="{{ route('admin.offers.store') }}">
            @csrf
            @include('admin.offers._form', ['offer' => null])
            <button type="submit" class="btn" style="margin-top:12px;">Save</button>
        </form>
    </div>
@endsection
