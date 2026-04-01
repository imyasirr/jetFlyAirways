@extends('layouts.admin')

@section('content')
    <div class="card">
        <h1 class="section-title">Edit coupon</h1>
        <form method="post" action="{{ route('admin.coupons.update', $coupon) }}">
            @csrf
            @method('PUT')
            @include('admin.coupons._form', ['coupon' => $coupon])
            <button type="submit" class="btn" style="margin-top:12px;">Update</button>
        </form>
    </div>
@endsection
