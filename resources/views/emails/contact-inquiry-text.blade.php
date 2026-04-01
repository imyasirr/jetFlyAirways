New message on {{ config('app.name') }}

From: {{ $inquiry->name }} <{{ $inquiry->email }}>
@if($inquiry->phone)Phone: {{ $inquiry->phone }}
@endif
Subject: {{ $inquiry->subject ?? '—' }}

{{ $inquiry->message }}
