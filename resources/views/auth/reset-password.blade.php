@extends('layouts.guest')

@section('title', 'Reset password')

@section('content')
    <h1>Reset password</h1>
    <p>Set a new password for your Jet Fly account.</p>

    <form method="post" action="{{ route('password.store') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">

        <label for="email">Email</label>
        <input id="email" type="email" name="email" value="{{ old('email', $email) }}" required autocomplete="email">

        <label for="password">New password</label>
        <input id="password" type="password" name="password" required autocomplete="new-password">

        <label for="password_confirmation">Confirm new password</label>
        <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password">

        <div class="form-actions">
            <button type="submit" class="btn">Update password</button>
        </div>
    </form>
@endsection

