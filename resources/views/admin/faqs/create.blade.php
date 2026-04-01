@extends('layouts.admin')

@section('content')
    <div class="card">
        <h1 class="section-title">Add FAQ</h1>
        <form method="post" action="{{ route('admin.faqs.store') }}">
            @csrf
            @include('admin.faqs._form', ['faq' => null])
            <button type="submit" class="btn" style="margin-top:12px;">Save</button>
        </form>
    </div>
@endsection
