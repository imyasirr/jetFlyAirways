@extends('layouts.account')

@section('title', 'Profile')

@section('heading', 'Profile')

@section('content')
    <div class="acct-card">
        <h2>Your details</h2>
        <form method="post" action="{{ route('account.profile.update') }}">
            @csrf
            @method('PUT')
            <label for="name">Full name</label>
            <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required autocomplete="name">

            <label for="email">Email</label>
            <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required autocomplete="email">

            <label for="phone">Phone</label>
            <input id="phone" name="phone" type="text" value="{{ old('phone', $user->phone) }}" autocomplete="tel">

            <button type="submit" class="btn">Save changes</button>
        </form>
    </div>
@endsection
