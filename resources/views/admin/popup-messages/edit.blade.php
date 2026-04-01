@extends('layouts.admin')

@section('content')
    <div class="card">
        <h1 class="section-title">Edit popup</h1>
        <form method="post" action="{{ route('admin.popup-messages.update', $popup) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            @include('admin.popup-messages._form', ['popup' => $popup])
            <button type="submit" class="btn" style="margin-top:12px;">Update</button>
        </form>
    </div>
@endsection
