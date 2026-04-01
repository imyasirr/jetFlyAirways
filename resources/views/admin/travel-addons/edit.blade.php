@extends('layouts.admin')

@section('content')
    <div class="card">
        <h1 class="section-title">Edit add-on</h1>
        <form method="post" action="{{ route('admin.travel-addons.update', $addon) }}">
            @csrf
            @method('PUT')
            @include('admin.travel-addons._form', ['addon' => $addon])
            <button type="submit" class="btn" style="margin-top:12px;">Update</button>
        </form>
    </div>
@endsection
