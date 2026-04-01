@extends('layouts.app')

@section('title', 'Contact us — Jet Fly Airways')

@section('meta_description', 'Reach Jet Fly Airways for bookings, support and corporate queries.')

@section('content')
    <div class="card" style="max-width:560px;">
        <h1 class="section-title">Contact us</h1>
        <p style="color:#64748b;font-size:14px;">We usually respond within one business day. For urgent booking changes, mention your booking code.</p>
        <form method="post" action="{{ route('contact.store') }}" style="display:grid;gap:12px;margin-top:16px;">
            @csrf
            <label>Name <input type="text" name="name" value="{{ old('name') }}" required></label>
            <label>Email <input type="email" name="email" value="{{ old('email') }}" required></label>
            <label>Phone <input type="text" name="phone" value="{{ old('phone') }}"></label>
            <label>Subject <input type="text" name="subject" value="{{ old('subject') }}"></label>
            <label>Message <textarea name="message" rows="6" required>{{ old('message') }}</textarea></label>
            <button type="submit" class="btn">Send message</button>
        </form>
    </div>
@endsection
