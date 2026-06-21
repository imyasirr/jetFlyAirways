@extends('layouts.admin')

@section('content')
    <div class="card admin-form-page">
        <h1 class="section-title">Edit trust card</h1>
        <form method="post" action="{{ route('admin.home-trust-cards.update', $card) }}">
            @csrf
            @method('PUT')
            @include('admin.home-trust-cards._form', ['card' => $card])
            <div class="form-actions" style="margin-top:16px;">
                <button type="submit" class="btn">Update</button>
            </div>
        </form>
    </div>
@endsection
