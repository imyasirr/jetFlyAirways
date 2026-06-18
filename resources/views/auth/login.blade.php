@extends('layouts.auth-split')

@section('title', 'Sign in')

@section('brand_title', 'Welcome Back')
@section('brand_desc', 'Sign in to manage bookings, saved travellers and exclusive travel deals on Jet Fly Airways.')

@section('brand_points')
    <span class="brand-point"><span class="material-symbols-outlined">confirmation_number</span> View &amp; manage your bookings</span>
    <span class="brand-point"><span class="material-symbols-outlined">local_offer</span> Exclusive member-only deals</span>
    <span class="brand-point"><span class="material-symbols-outlined">support_agent</span> 24/7 customer support</span>
@endsection

@section('form_content')
    <p class="form-eyebrow">My Account</p>
    <h2>Sign in to continue</h2>
    <p class="form-sub">Enter your email and password to access your account.</p>

    @if ($errors->any())
        <div class="alert-error">{{ $errors->first() }}</div>
    @endif

    <form method="post" action="{{ route('login') }}">
        @csrf
        <label class="field-label" for="email">Email</label>
        <div class="field">
            <span class="material-symbols-outlined" aria-hidden="true">mail</span>
            <input id="email" type="email" name="email" value="{{ old('email') }}" placeholder="you@example.com" required autocomplete="username" autofocus>
        </div>

        <label class="field-label" for="password">Password</label>
        <div class="field">
            <span class="material-symbols-outlined" aria-hidden="true">lock</span>
            <input id="password" type="password" name="password" placeholder="••••••••" required autocomplete="current-password">
        </div>

        <div class="row-between">
            <label class="remember">
                <input type="checkbox" name="remember" value="1" {{ old('remember') ? 'checked' : '' }}>
                Remember me
            </label>
            <a href="{{ route('password.request') }}" class="link-muted">Forgot password?</a>
        </div>

        <button type="submit" class="btn-submit">Sign In</button>
    </form>

    <div class="auth-divider">or continue with</div>

    <a href="{{ route('auth.google') }}" class="btn-secondary">Continue with Google</a>

    <div class="form-foot-bar">
        <a href="{{ route('home') }}" class="auth-back">
            <span class="material-symbols-outlined" aria-hidden="true">arrow_back</span>
            Back to home
        </a>
        <div class="form-foot-bar__rest">
            <p class="form-foot">
                <a href="{{ route('login.otp') }}">Sign in with phone OTP</a><br>
                New here? <a href="{{ route('register') }}">Create an account</a>
            </p>
        </div>
    </div>

    <p class="form-secure"><span class="material-symbols-outlined" aria-hidden="true">lock</span> Secure sign-in — your data is protected</p>
@endsection
