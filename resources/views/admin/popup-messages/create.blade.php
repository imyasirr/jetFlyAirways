@extends('layouts.admin')

@section('content')
    <div class="card">
        <h1 class="section-title">Add popup</h1>
        <form method="post" action="{{ route('admin.popup-messages.store') }}" enctype="multipart/form-data">
            @csrf
            @include('admin.popup-messages._form', ['popup' => null])
            <button type="submit" class="btn" style="margin-top:12px;">Save</button>
        </form>
    </div>
@endsection
