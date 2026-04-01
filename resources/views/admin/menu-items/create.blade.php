@extends('layouts.admin')

@section('content')
    <div class="card">
        <h1 class="section-title">Add menu item</h1>
        <form method="post" action="{{ route('admin.menu-items.store') }}">
            @csrf
            @include('admin.menu-items._form', ['menuItem' => null])
            <button type="submit" class="btn" style="margin-top:12px;">Save</button>
        </form>
    </div>
@endsection
