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

        <button type="submit" class="btn">Register</button>
    </form>

    <p style="margin-top:16px;font-size:14px;text-align:center;color:var(--muted);">
        <a href="{{ route('auth.google') }}" style="display:inline-block;width:100%;padding:10px;border-radius:12px;border:1px solid #c9d5ef;font-weight:700;color:var(--primary);">Continue with Google</a>
    </p>

    <p style="margin-top:18px;font-size:14px;text-align:center;color:var(--muted);">
        Already have an account? <a href="{{ route('login') }}" style="color:var(--primary);font-weight:700;">Sign in</a>
    </p>
@endsection
