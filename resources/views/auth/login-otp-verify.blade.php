@extends('layouts.guest')

@section('title', 'Enter OTP')

@section('content')
    <h1>Enter code</h1>
    <p>Type the 6-digit code we sent to your phone.</p>

    <form method="post" action="{{ route('login.otp.verify.submit') }}">
        @csrf
        <label for="code">One-time code</label>
        <input id="code" type="text" name="code" inputmode="numeric" maxlength="6" pattern="[0-9]{6}" required autocomplete="one-time-code" autofocus>

        <button type="submit" class="btn">Verify &amp; sign in</button>
    </form>

    <p style="margin-top:18px;font-size:14px;text-align:center;color:var(--muted);">
        <a href="{{ route('login.otp') }}" style="color:var(--primary);font-weight:700;">Use a different number</a>
    </p>
@endsection
