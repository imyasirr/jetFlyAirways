@extends('layouts.auth-split')

@section('title', 'Create account')

@section('brand_title', 'Join Jet Fly')
@section('brand_desc', 'Create your account to track bookings, e-tickets, saved travellers and exclusive travel discounts.')

@section('brand_points')
    <span class="brand-point"><span class="material-symbols-outlined">confirmation_number</span> Manage all your bookings in one place</span>
    <span class="brand-point"><span class="material-symbols-outlined">local_offer</span> Unlock member-only deals &amp; offers</span>
    <span class="brand-point"><span class="material-symbols-outlined">verified_user</span> Secure account with encrypted data</span>
@endsection

@push('styles')
<style>
    .shell:has(.auth-register) { min-height: auto; align-items: stretch; }
    .shell:has(.auth-register) .form-pane {
        justify-content: flex-start;
        padding-top: 36px;
        padding-bottom: 36px;
    }
    .auth-register .field-grid-2 {
        display: grid;
        grid-template-columns: 1fr;
        gap: 0 16px;
    }
    @media (min-width: 520px) {
        .auth-register .field-grid-2 { grid-template-columns: 1fr 1fr; }
    }
</style>
@endpush

@section('form_content')
    <div class="auth-register">
        <p class="form-eyebrow">Get Started</p>
        <h2>Create your account</h2>
        <p class="form-sub">Fill in your details below to join Jet Fly Airways.</p>

        @if ($errors->any())
            <div class="alert-error">{{ $errors->first() }}</div>
        @endif

        <form method="post" action="{{ route('register') }}">
            @csrf
            <input type="hidden" name="referral" value="{{ old('referral', request('ref')) }}">

            <label class="field-label" for="name">Full name</label>
            <div class="field">
                <span class="material-symbols-outlined" aria-hidden="true">person</span>
                <input id="name" type="text" name="name" value="{{ old('name') }}" placeholder="Your full name" required autocomplete="name" autofocus>
            </div>

            <label class="field-label" for="email">Email</label>
            <div class="field">
                <span class="material-symbols-outlined" aria-hidden="true">mail</span>
                <input id="email" type="email" name="email" value="{{ old('email') }}" placeholder="you@example.com" required autocomplete="email">
            </div>

            <label class="field-label" for="phone">Phone <span style="font-weight:500;text-transform:none;letter-spacing:0;">(optional)</span></label>
            <div class="field">
                <span class="material-symbols-outlined" aria-hidden="true">phone</span>
                <input id="phone" type="text" name="phone" value="{{ old('phone') }}" placeholder="+91 98765 43210" autocomplete="tel">
            </div>

            <div class="field-grid-2">
                <div>
                    <label class="field-label" for="password">Password</label>
                    <div class="field">
                        <span class="material-symbols-outlined" aria-hidden="true">lock</span>
                        <input id="password" type="password" name="password" placeholder="••••••••" required autocomplete="new-password">
                    </div>
                </div>
                <div>
                    <label class="field-label" for="password_confirmation">Confirm password</label>
                    <div class="field">
                        <span class="material-symbols-outlined" aria-hidden="true">lock</span>
                        <input id="password_confirmation" type="password" name="password_confirmation" placeholder="••••••••" required autocomplete="new-password">
                    </div>
                </div>
            </div>

            <button type="submit" class="btn-submit" style="margin-top:4px;">Create Account</button>
        </form>

        <div class="auth-divider">or continue with</div>

        <a href="{{ route('auth.google') }}" class="btn-secondary">Continue with Google</a>

        <div class="form-foot-bar">
            <a href="{{ route('home') }}" class="auth-back">
                <span class="material-symbols-outlined" aria-hidden="true">arrow_back</span>
                Back to home
            </a>
            <div class="form-foot-bar__rest">
                <p class="form-foot">Already have an account? <a href="{{ route('login') }}">Sign in</a></p>
            </div>
        </div>

        <p class="form-secure"><span class="material-symbols-outlined" aria-hidden="true">lock</span> Secure registration — your data is protected</p>
    </div>
@endsection
