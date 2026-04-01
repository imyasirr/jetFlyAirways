@extends('layouts.account')

@section('title', 'Notifications')
@section('heading', 'Notifications')

@section('content')
    <div class="acct-card">
        <h2>Updates from Jet Fly</h2>
        @if($announcements->isEmpty())
            <p style="color:var(--acct-muted);margin:0;">No announcements right now.</p>
        @else
            <ul style="list-style:none;margin:0;padding:0;">
                @foreach($announcements as $a)
                    @php $isRead = $readIds->contains($a->id); @endphp
                    <li style="border-bottom:1px solid var(--acct-border);padding:14px 0;">
                        <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:12px;flex-wrap:wrap;">
                            <div>
                                @if(!$isRead)
                                    <span class="badge" style="background:#fef3c7;color:#92400e;margin-right:8px;">New</span>
                                @endif
                                <strong style="color:var(--acct-primary);">{{ $a->title }}</strong>
                                @if($a->published_at)
                                    <div style="font-size:12px;color:var(--acct-muted);margin-top:4px;">{{ $a->published_at->timezone(config('app.timezone'))->format('M j, Y g:i A') }}</div>
                                @endif
                                @if($a->body)
                                    <p style="margin:8px 0 0;font-size:14px;line-height:1.5;">{{ $a->body }}</p>
                                @endif
                            </div>
                            <div>
                                <form method="post" action="{{ route('account.announcements.read', $a) }}" style="margin:0;">
                                    @csrf
                                    <button type="submit" class="btn" style="white-space:nowrap;">
                                        @if($a->link) Open link @else Mark read @endif
                                    </button>
                                </form>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
@endsection
