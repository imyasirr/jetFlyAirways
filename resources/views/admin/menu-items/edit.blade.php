@extends('layouts.admin')

@section('content')
    <div class="card admin-form-page">
        <h1 class="section-title">Edit menu item</h1>
        <form method="post" action="{{ route('admin.menu-items.update', $menuItem) }}">
            @csrf
            @method('PUT')
            @include('admin.menu-items._form', ['menuItem' => $menuItem])
            <div class="form-actions">
                <button type="submit" class="btn">Update</button>
                <a class="btn ghost" href="{{ route('admin.menu-items.index') }}">Cancel</a>
            </div>
        </form>
    </div>
@endsection
