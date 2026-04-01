@extends('layouts.admin')

@section('content')
    <div class="card">
        <p><a href="{{ route('admin.contact-inquiries.index') }}">← Inquiries</a></p>
        <h1 class="section-title">{{ $inquiry->subject ?: 'Contact message' }}</h1>
        <p style="color:#64748b;font-size:14px;">{{ $inquiry->created_at->format('l, d M Y H:i') }} · {{ $inquiry->email }} @if($inquiry->phone)· {{ $inquiry->phone }}@endif</p>
        <p><strong>{{ $inquiry->name }}</strong></p>
        <div style="white-space:pre-wrap;font-size:15px;line-height:1.55;border:1px solid var(--admin-border);border-radius:10px;padding:16px;background:#f8fafc;">{{ $inquiry->message }}</div>

        <h2 class="section-title" style="margin-top:24px;font-size:1rem;">Status</h2>
        <form method="post" action="{{ route('admin.contact-inquiries.update', $inquiry) }}" style="display:flex;gap:10px;align-items:center;flex-wrap:wrap;">
            @csrf
            @method('PATCH')
            <select name="status">
                @foreach(['new', 'read', 'replied', 'closed'] as $s)
                    <option value="{{ $s }}" @selected(old('status', $inquiry->status) === $s)>{{ $s }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn">Update</button>
        </form>
    </div>
@endsection
