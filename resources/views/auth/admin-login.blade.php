@extends('layouts.guest')

@section('title', 'Admin sign in')

@section('content')
    <h1>Admin sign in</h1>
    <p>Enter the credentials for your Jet Fly admin account.</p>

    <form method="post" action="{{ route('admin.login') }}">
        @csrf
        <label for="email">Email</label>
        <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" autofocus>

        <label for="password">Password</label>
        <input id="password" type="password" name="password" required autocomplete="current-password">

        <label class="remember">
            <input type="checkbox" name="remember" value="1" {{ old('remember') ? 'checked' : '' }}>
            Remember
        </label>

        <button type="submit" class="btn">Sign in</button>
    </form>
    <p style="margin-top:18px;font-size:13px;text-align:center;color:#94a3b8;">Customer? <a href="{{ route('login') }}" style="color:var(--primary);font-weight:600;">Sign in to your account</a></p>
@endsection
