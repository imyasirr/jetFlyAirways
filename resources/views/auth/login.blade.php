@extends('layouts.guest')

@section('title', 'Sign in')

@section('content')
    <h1>Sign in</h1>
    <p>Use your Jet Fly account to manage bookings, offers, and profile.</p>

    <form method="post" action="{{ route('login') }}">
        @csrf
        <label for="email">Email</label>
        <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" autofocus>

        <label for="password">Password</label>
        <input id="password" type="password" name="password" required autocomplete="current-password">

        <label class="remember">
            <input type="checkbox" name="remember" value="1" {{ old('remember') ? 'checked' : '' }}>
            Remember me
        </label>

        <div class="form-actions">
            <button type="submit" class="btn">Sign in</button>
        </div>
    </form>

    <p class="auth-sep">
        <a href="{{ route('auth.google') }}" class="auth-social">Continue with Google</a>
    </p>
    <p class="auth-link-center">
        <a href="{{ route('login.otp') }}">Sign in with phone OTP</a>
    </p>
    <p class="auth-link-center">
        <a href="{{ route('password.request') }}">Forgot password?</a>
    </p>

    <p class="auth-link-center" style="margin-top:18px;color:var(--muted);">
        New here? <a href="{{ route('register') }}">Create an account</a>
    </p>
    <p style="font-size:13px;text-align:center;color:#94a3b8;">Staff admin? <a href="{{ route('admin.login') }}" style="color:var(--primary);font-weight:600;">Admin sign in</a></p>
@endsection
