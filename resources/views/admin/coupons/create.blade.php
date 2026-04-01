@extends('layouts.admin')

@section('content')
    <div class="card">
        <h1 class="section-title">Add coupon</h1>
        <form method="post" action="{{ route('admin.coupons.store') }}">
            @csrf
            @include('admin.coupons._form', ['coupon' => null])
            <button type="submit" class="btn" style="margin-top:12px;">Save</button>
        </form>
    </div>
@endsection
