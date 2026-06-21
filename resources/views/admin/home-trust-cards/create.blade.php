@extends('layouts.admin')

@section('content')
    <div class="card admin-form-page">
        <h1 class="section-title">Add trust card</h1>
        <form method="post" action="{{ route('admin.home-trust-cards.store') }}">
            @csrf
            @include('admin.home-trust-cards._form', ['card' => null])
            <div class="form-actions" style="margin-top:16px;">
                <button type="submit" class="btn">Save</button>
            </div>
        </form>
    </div>
@endsection
