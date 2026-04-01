@extends('layouts.admin')

@section('content')
    <div class="card">
        <h1 class="section-title">Edit menu item</h1>
        <form method="post" action="{{ route('admin.menu-items.update', $menuItem) }}">
            @csrf
            @method('PUT')
            @include('admin.menu-items._form', ['menuItem' => $menuItem])
            <button type="submit" class="btn" style="margin-top:12px;">Update</button>
        </form>
    </div>
@endsection
