@extends('layouts.account')

@section('title', 'Password')

@section('heading', 'Change password')

@section('content')
    <div class="acct-card">
        <h2>Security</h2>
        <form method="post" action="{{ route('account.password.update') }}">
            @csrf
            @method('PUT')
            <label for="current_password">Current password</label>
            <input id="current_password" name="current_password" type="password" required autocomplete="current-password">

            <label for="password">New password</label>
            <input id="password" name="password" type="password" required autocomplete="new-password">

            <label for="password_confirmation">Confirm new password</label>
            <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password">

            <button type="submit" class="btn">Update password</button>
        </form>
    </div>
@endsection
