@extends('layouts.account')

@section('title', 'Password')
@section('heading', 'Change password')

@section('content')
    <div class="jfa-acct-card jfa-acct-card--profile">
        <div class="jfa-profile-head">
            <span class="jfa-profile-head__avatar jfa-profile-head__avatar--security" aria-hidden="true">
                <span class="material-symbols-outlined filled">lock</span>
            </span>
            <div>
                <p class="jfa-profile-head__name">Account security</p>
                <p class="jfa-profile-head__meta">Use a strong password you do not reuse on other sites.</p>
            </div>
        </div>

        @if(session('status'))
            <div class="jfa-form-success" role="status" style="margin-bottom:16px;">
                <span class="material-symbols-outlined filled">check_circle</span>
                {{ session('status') }}
            </div>
        @endif

        <form method="post" action="{{ route('account.password.update') }}" class="jfa-profile-form">
            @csrf
            @method('PUT')

            <div>
                <label class="jfa-label" for="current_password">Current password</label>
                <input id="current_password" name="current_password" type="password" required autocomplete="current-password" @class(['is-invalid' => $errors->has('current_password')])>
                @error('current_password')<span class="jfa-field-error">{{ $message }}</span>@enderror
            </div>

            <div>
                <label class="jfa-label" for="password">New password</label>
                <input id="password" name="password" type="password" required autocomplete="new-password" @class(['is-invalid' => $errors->has('password')])>
                @error('password')<span class="jfa-field-error">{{ $message }}</span>@enderror
            </div>

            <div>
                <label class="jfa-label" for="password_confirmation">Confirm new password</label>
                <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password">
            </div>

            <button type="submit" class="btn jfa-profile-save">Update password</button>
        </form>
    </div>
@endsection
