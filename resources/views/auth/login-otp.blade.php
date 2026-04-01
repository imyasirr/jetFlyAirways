@extends('layouts.guest')

@section('title', 'Sign in with OTP')

@section('content')
    <h1>Phone OTP</h1>
    <p>We’ll send a one-time code to the phone number on your account.</p>

    <form method="post" action="{{ route('login.otp.send') }}">
        @csrf
        <label for="phone">Phone number</label>
        <input id="phone" type="tel" name="phone" value="{{ old('phone') }}" required autocomplete="tel" autofocus>

        <button type="submit" class="btn">Send code</button>
    </form>

    <p style="margin-top:18px;font-size:14px;text-align:center;color:var(--muted);">
        <a href="{{ route('login') }}" style="color:var(--primary);font-weight:700;">Email &amp; password sign in</a>
    </p>
@endsection
