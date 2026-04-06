@extends('layouts.app')

@section('title', 'Refer & Earn — Jet Fly Airways')

@section('content')
    <div class="card" style="max-width:760px;">
        <h1 class="section-title">Refer &amp; Earn</h1>
        <p style="color:#64748b;margin-top:0;">Invite friends to Jet Fly. When they sign up using your referral link, they are linked to your account.</p>

        @auth
            <div class="card" style="padding:14px;border:1px dashed #cbd5e1;box-shadow:none;background:#f8fafc;">
                <p style="margin:0 0 8px;"><strong>Your referral code:</strong> <span style="background:#eef2ff;padding:4px 10px;border-radius:8px;">{{ auth()->user()->referral_code }}</span></p>
                @if($shareUrl)
                    <p style="margin:0 0 8px;"><strong>Your referral link:</strong></p>
                    <p style="margin:0;word-break:break-all;"><a href="{{ $shareUrl }}">{{ $shareUrl }}</a></p>
                @endif
                <p style="margin:10px 0 0;color:#475569;">Friends joined: <strong>{{ $referralsCount }}</strong></p>
            </div>
        @else
            <p style="margin:12px 0 0;">
                <a href="{{ route('login') }}" class="btn">Login to get referral link</a>
            </p>
        @endauth
    </div>
@endsection

