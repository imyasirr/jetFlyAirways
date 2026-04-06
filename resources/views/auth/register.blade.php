@extends('layouts.guest')

@section('title', 'Create account')

@section('content')
    <h1>Create account</h1>
    <p>Join Jet Fly to track bookings, e-tickets, and exclusive discounts.</p>

    <form method="post" action="{{ route('register') }}">
        @csrf
        <input type="hidden" name="referral" value="{{ old('referral', request('ref')) }}">
        <label for="name">Full name</label>
        <input id="name" type="text" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

        <label for="email">Email</label>
        <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email">

        <label for="phone">Phone (optional)</label>
        <input id="phone" type="text" name="phone" value="{{ old('phone') }}" autocomplete="tel">

        <label for="password">Password</label>
        <input id="password" type="password" name="password" required autocomplete="new-password">

        <label for="password_confirmation">Confirm password</label>
        <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password">

        <div class="form-actions">
            <button type="submit" class="btn">Register</button>
        </div>
    </form>

    <p class="auth-sep">
        <a href="{{ route('auth.google') }}" class="auth-social">Continue with Google</a>
    </p>

    <p class="auth-link-center" style="margin-top:18px;color:var(--muted);">
        Already have an account? <a href="{{ route('login') }}">Sign in</a>
    </p>
@endsection
