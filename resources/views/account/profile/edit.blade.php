@extends('layouts.account')

@section('title', 'Profile')
@section('heading', 'Edit profile')

@section('content')
    @php
        $initials = collect(explode(' ', (string) $user->name))->filter()->map(fn ($w) => mb_strtoupper(mb_substr($w, 0, 1)))->take(2)->implode('') ?: 'U';
    @endphp

    <div class="jfa-acct-card jfa-acct-card--profile">
        <div class="jfa-profile-head">
            <span class="jfa-profile-head__avatar" aria-hidden="true">{{ $initials }}</span>
            <div>
                <p class="jfa-profile-head__name">{{ $user->name }}</p>
                <p class="jfa-profile-head__meta">Member since {{ $user->created_at?->format('M Y') ?? '—' }}</p>
            </div>
        </div>

        @if(session('status'))
            <div class="jfa-form-success" role="status">
                <span class="material-symbols-outlined filled">check_circle</span>
                {{ session('status') }}
            </div>
        @endif

        <form method="post" action="{{ route('account.profile.update') }}" class="jfa-profile-form">
            @csrf
            @method('PUT')

            <div>
                <label class="jfa-label" for="name">Full name</label>
                <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required autocomplete="name" @class(['is-invalid' => $errors->has('name')])>
                @error('name')<span class="jfa-field-error">{{ $message }}</span>@enderror
            </div>

            <div>
                <label class="jfa-label" for="email">Email address</label>
                <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required autocomplete="email" @class(['is-invalid' => $errors->has('email')])>
                @error('email')<span class="jfa-field-error">{{ $message }}</span>@enderror
            </div>

            <div>
                <label class="jfa-label" for="phone">Phone number</label>
                <input id="phone" name="phone" type="text" value="{{ old('phone', $user->phone) }}" autocomplete="tel" placeholder="+91 98765 43210" @class(['is-invalid' => $errors->has('phone')])>
                @error('phone')<span class="jfa-field-error">{{ $message }}</span>@enderror
            </div>

            <button type="submit" class="btn jfa-profile-save">Save changes</button>
        </form>
    </div>

    <div class="jfa-acct-card">
        <h2>Security</h2>
        <p style="margin:0 0 16px;color:var(--jfa-muted);font-size:14px;">Update your password regularly to keep your account secure.</p>
        <a href="{{ route('account.password.edit') }}" class="btn secondary">Change password</a>
    </div>
@endsection
