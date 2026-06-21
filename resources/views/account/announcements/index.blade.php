@extends('layouts.account')

@section('title', 'Notifications')
@section('heading', 'Notifications')

@section('content')
    <div class="acct-card jfa-notif-page">
        <h2>Updates from Jet Fly</h2>
        <p class="jfa-notif-page__intro">Official announcements and alerts for your account.</p>

        @if($announcements->isEmpty())
            <div class="jfa-notif-empty">
                <span class="material-symbols-outlined" aria-hidden="true">notifications_off</span>
                <p>No announcements right now. Check back later for offers, policy updates, and travel alerts.</p>
            </div>
        @else
            <ul class="jfa-notif-list">
                @foreach($announcements as $a)
                    @php $isRead = $readIds->contains($a->id); @endphp
                    <li class="jfa-notif-item {{ $isRead ? 'is-read' : 'is-unread' }}">
                        <div class="jfa-notif-item__icon" aria-hidden="true">
                            <span class="material-symbols-outlined">{{ $isRead ? 'mark_email_read' : 'campaign' }}</span>
                        </div>
                        <div class="jfa-notif-item__body">
                            <div class="jfa-notif-item__head">
                                @if(!$isRead)
                                    <span class="jfa-notif-badge">New</span>
                                @endif
                                <h3>{{ $a->title }}</h3>
                            </div>
                            @if($a->published_at)
                                <time class="jfa-notif-item__time" datetime="{{ $a->published_at->toIso8601String() }}">
                                    {{ $a->published_at->timezone(config('app.timezone'))->format('M j, Y · g:i A') }}
                                </time>
                            @endif
                            @if($a->body)
                                <p class="jfa-notif-item__text">{{ $a->body }}</p>
                            @endif
                        </div>
                        <div class="jfa-notif-item__action">
                            <form method="post" action="{{ route('account.announcements.read', $a) }}">
                                @csrf
                                <button type="submit" class="btn {{ $a->link ? '' : 'outline' }}">
                                    @if($a->link)
                                        Open link
                                    @else
                                        Mark read
                                    @endif
                                </button>
                            </form>
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
@endsection
