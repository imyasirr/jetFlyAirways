@extends('layouts.admin')

@section('content')
    <div class="card">
        <h1 class="section-title">Add visa / insurance plan</h1>
        <form method="post" action="{{ route('admin.travel-addons.store') }}">
            @csrf
            @include('admin.travel-addons._form', ['addon' => null])
            <button type="submit" class="btn" style="margin-top:12px;">Save</button>
        </form>
    </div>
@endsection
