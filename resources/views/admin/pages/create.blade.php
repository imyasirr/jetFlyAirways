@extends('layouts.admin')

@section('content')
    <div class="card">
        <h1 class="section-title">New page</h1>
        <form method="post" action="{{ route('admin.pages.store') }}">
            @csrf
            @include('admin.pages._form', ['page' => null])
            <button type="submit" class="btn" style="margin-top:12px;">Create</button>
        </form>
    </div>
@endsection
