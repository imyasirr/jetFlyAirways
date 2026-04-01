@extends('layouts.admin')

@section('content')
    <div class="card">
        <h1 class="section-title">Add vacancy</h1>
        <form method="post" action="{{ route('admin.careers.store') }}">
            @csrf
            @include('admin.careers._form', ['career' => null])
            <button type="submit" class="btn" style="margin-top:12px;">Save</button>
        </form>
    </div>
@endsection
