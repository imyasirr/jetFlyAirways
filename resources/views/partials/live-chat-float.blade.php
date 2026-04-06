@php
    $chatUrl = $siteSetting?->live_chat_url;
@endphp
@if(!empty($chatUrl))
    <a class="chat-float" href="{{ $chatUrl }}" target="_blank" rel="noopener noreferrer" title="Live chat support" aria-label="Live chat support">💬</a>
@endif

