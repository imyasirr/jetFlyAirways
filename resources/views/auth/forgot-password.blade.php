@extends('layouts.guest')

@section('title', 'Forgot password')

@section('content')
    <h1>Forgot password</h1>
    <p>Enter your account email and we will send a password reset link.</p>

    <form method="post" action="{{ route('password.email') }}">
        @csrf
        <label for="email">Email</label>
        <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

        <div class="form-actions">
            <button type="submit" class="btn">Send reset link</button>
        </div>
    </form>

    <p class="auth-link-center" style="margin-top:16px;color:var(--muted);">
        Remembered it? <a href="{{ route('login') }}">Back to sign in</a>
    </p>
@endsection

