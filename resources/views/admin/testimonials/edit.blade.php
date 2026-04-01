@extends('layouts.admin')

@section('content')
    <div class="card">
        <h1 class="section-title">Edit testimonial</h1>
        <form method="post" action="{{ route('admin.testimonials.update', $testimonial) }}">
            @csrf
            @method('PUT')
            @include('admin.testimonials._form', ['testimonial' => $testimonial])
            <button type="submit" class="btn" style="margin-top:12px;">Update</button>
        </form>
    </div>
@endsection
