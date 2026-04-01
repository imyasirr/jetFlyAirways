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

        <button type="submit" class="btn">Sign in</button>
    </form>

    <p style="margin-top:16px;font-size:14px;text-align:center;color:var(--muted);">
        <a href="{{ route('auth.google') }}" style="display:inline-block;width:100%;padding:10px;border-radius:12px;border:1px solid #c9d5ef;font-weight:700;color:var(--primary);">Continue with Google</a>
    </p>
    <p style="margin-top:10px;font-size:14px;text-align:center;">
        <a href="{{ route('login.otp') }}" style="color:var(--primary);font-weight:700;">Sign in with phone OTP</a>
    </p>

    <p style="margin-top:18px;font-size:14px;text-align:center;color:var(--muted);">
        New here? <a href="{{ route('register') }}" style="color:var(--primary);font-weight:700;">Create an account</a>
    </p>
    <p style="font-size:13px;text-align:center;color:#94a3b8;">Staff admin? <a href="{{ route('admin.login') }}" style="color:var(--primary);">Admin sign in</a></p>
@endsection
