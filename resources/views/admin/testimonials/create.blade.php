@extends('layouts.admin')

@section('content')
    <div class="card">
        <h1 class="section-title">Add testimonial</h1>
        <form method="post" action="{{ route('admin.testimonials.store') }}">
            @csrf
            @include('admin.testimonials._form', ['testimonial' => null])
            <button type="submit" class="btn" style="margin-top:12px;">Save</button>
        </form>
    </div>
@endsection
