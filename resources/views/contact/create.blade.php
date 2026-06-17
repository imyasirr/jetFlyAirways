@extends('layouts.app')

@section('title', 'Contact us — Jet Fly Airways')

@section('content')
    <div class="jfa-page-head">
        <h1>Contact us</h1>
        <p class="page-muted">We usually respond within one business day. For urgent booking changes, mention your booking code.</p>
    </div>
    <article class="jfa-card page-card">
        <form method="post" action="{{ route('contact.store') }}">
            @csrf
            <div class="jfa-search__grid">
                <div><label class="jfa-label">Name</label><input type="text" name="name" value="{{ old('name') }}" required></div>
                <div><label class="jfa-label">Email</label><input type="email" name="email" value="{{ old('email') }}" required></div>
                <div><label class="jfa-label">Phone</label><input type="text" name="phone" value="{{ old('phone') }}"></div>
                <div><label class="jfa-label">Subject</label><input type="text" name="subject" value="{{ old('subject') }}"></div>
                <div style="grid-column:1/-1;"><label class="jfa-label">Message</label><textarea name="message" rows="6" required>{{ old('message') }}</textarea></div>
            </div>
            <div class="form-actions"><button type="submit" class="btn">Send message</button></div>
        </form>
    </article>
@endsection
