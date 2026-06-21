@extends('layouts.admin')

@section('content')
    <div class="card admin-form-page">
        <h1 class="section-title">Add menu item</h1>
        <form method="post" action="{{ route('admin.menu-items.store') }}">
            @csrf
            @include('admin.menu-items._form', ['menuItem' => null])
            <div class="form-actions">
                <button type="submit" class="btn">Save</button>
                <a class="btn ghost" href="{{ route('admin.menu-items.index') }}">Cancel</a>
            </div>
        </form>
    </div>
@endsection
