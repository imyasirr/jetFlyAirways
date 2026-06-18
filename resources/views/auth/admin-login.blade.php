@extends('layouts.auth-split')

@section('title', 'Admin Sign In')

@section('brand_title', 'Control Center')
@section('brand_desc', 'Manage flights, hotels, packages, bookings and every corner of the Jet Fly Airways platform from one place.')

@section('brand_points')
    <span class="brand-point"><span class="material-symbols-outlined">dashboard</span> Live dashboard &amp; reports</span>
    <span class="brand-point"><span class="material-symbols-outlined">flight</span> Inventory &amp; booking management</span>
    <span class="brand-point"><span class="material-symbols-outlined">shield_lock</span> Secured admin-only access</span>
@endsection

@section('form_content')
    <p class="form-eyebrow">Admin Panel</p>
    <h2>Sign in to continue</h2>
    <p class="form-sub">Enter the credentials for your Jet Fly admin account.</p>

    @if ($errors->any())
        <div class="alert-error">{{ $errors->first() }}</div>
    @endif

    <form method="post" action="{{ route('admin.login') }}">
        @csrf
        <label class="field-label" for="email">Email</label>
        <div class="field">
            <span class="material-symbols-outlined" aria-hidden="true">mail</span>
            <input id="email" type="email" name="email" value="{{ old('email') }}" placeholder="admin@jetflyairways.com" required autocomplete="username" autofocus>
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
        </div>

        <button type="submit" class="btn-submit">Sign In</button>
    </form>

    <div class="form-foot-bar">
        <a href="{{ route('home') }}" class="auth-back">
            <span class="material-symbols-outlined" aria-hidden="true">arrow_back</span>
            Back to home
        </a>
        <div class="form-foot-bar__rest">
            <p class="form-foot">Customer? <a href="{{ route('login') }}">Sign in to your account</a></p>
        </div>
    </div>
    <p class="form-secure"><span class="material-symbols-outlined" aria-hidden="true">lock</span> Protected area — authorised personnel only</p>
@endsection
