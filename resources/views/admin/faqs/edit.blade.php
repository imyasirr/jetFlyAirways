@extends('layouts.admin')

@section('content')
    <div class="card">
        <h1 class="section-title">Edit FAQ</h1>
        <form method="post" action="{{ route('admin.faqs.update', $faq) }}">
            @csrf
            @method('PUT')
            @include('admin.faqs._form', ['faq' => $faq])
            <button type="submit" class="btn" style="margin-top:12px;">Update</button>
        </form>
    </div>
@endsection
