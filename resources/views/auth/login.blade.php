@extends('layouts.guest')

@section('title', 'Sign in')

@section('content')
    <div style="text-align:center;margin-bottom:28px;">
        <h1 style="font-size:1.5rem;margin:0 0 8px;">Welcome Back</h1>
        <p style="margin:0;color:var(--jfa-muted);font-size:14px;">Sign in to access your bookings and account</p>
    </div>

    @if ($errors->any())
        <div style="display:flex;align-items:center;gap:8px;background:#fef2f2;border:1px solid #fecaca;border-radius:12px;padding:12px 16px;margin-bottom:16px;">
            <span class="material-symbols-outlined" style="color:var(--jfa-alert);font-size:18px;">error</span>
            <span style="font-size:14px;color:var(--jfa-alert);">{{ $errors->first() }}</span>
        </div>
    @endif

    <form method="post" action="{{ route('login') }}">
        @csrf
        <div style="margin-bottom:16px;">
            <label class="jfa-label" for="email">Email Address</label>
            <div style="position:relative;">
                <span class="material-symbols-outlined" style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--jfa-muted);font-size:18px;">mail</span>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" autofocus style="padding-left:40px;">
            </div>
        </div>
        <div style="margin-bottom:16px;">
            <label class="jfa-label" for="password">Password</label>
            <div style="position:relative;">
                <span class="material-symbols-outlined" style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--jfa-muted);font-size:18px;">lock</span>
                <input id="password" type="password" name="password" required autocomplete="current-password" style="padding-left:40px;padding-right:40px;">
            </div>
        </div>
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;">
            <label style="display:flex;align-items:center;gap:8px;font-size:13px;color:var(--jfa-muted);cursor:pointer;margin:0;text-transform:none;font-weight:500;">
                <input type="checkbox" name="remember" value="1" {{ old('remember') ? 'checked' : '' }} style="width:auto;margin:0;">
                Remember me
            </label>
            <a href="{{ route('password.request') }}" style="font-size:13px;font-weight:600;color:var(--jfa-booking-blue);">Forgot password?</a>
        </div>
        <button type="submit" class="btn" style="width:100%;padding:14px;">Sign In</button>
    </form>

    <p style="text-align:center;margin:20px 0 12px;font-size:13px;color:var(--jfa-muted);">or continue with</p>
    <a href="{{ route('auth.google') }}" class="btn secondary" style="width:100%;margin-bottom:12px;">Continue with Google</a>
    <p style="text-align:center;margin:12px 0;font-size:14px;">
        <a href="{{ route('login.otp') }}" style="color:var(--jfa-booking-blue);font-weight:600;">Sign in with phone OTP</a>
    </p>
    <p style="text-align:center;margin:16px 0 0;font-size:14px;color:var(--jfa-muted);">
        New here? <a href="{{ route('register') }}" style="color:var(--jfa-booking-blue);font-weight:700;">Create an account</a>
    </p>
    <p style="text-align:center;margin:12px 0 0;font-size:12px;color:var(--jfa-muted);">
        Staff admin? <a href="{{ route('admin.login') }}" style="color:var(--jfa-booking-blue);font-weight:600;">Admin sign in</a>
    </p>
@endsection
