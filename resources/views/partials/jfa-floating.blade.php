@php
    $waRaw = config('jetfly.whatsapp.number');
    $waDigits = $waRaw ? preg_replace('/\D+/', '', $waRaw) : '';
    $waText = rawurlencode((string) config('jetfly.whatsapp.message'));
    $chatUrl = $siteSetting?->live_chat_url;
    $tawkEnabled = ($siteSetting ?? null)?->tawkEnabled();
@endphp
<div class="jfa-float-wrap">
    @if($waDigits !== '')
        <a class="jfa-float-btn jfa-float-btn--wa" href="https://wa.me/{{ $waDigits }}?text={{ $waText }}" target="_blank" rel="noopener noreferrer" aria-label="WhatsApp">
            <svg width="26" height="26" viewBox="0 0 32 32" fill="none" aria-hidden="true">
                <path fill-rule="evenodd" clip-rule="evenodd" d="M16 2C8.268 2 2 8.268 2 16c0 2.43.652 4.71 1.79 6.67L2 30l7.53-1.75A13.92 13.92 0 0016 30c7.732 0 14-6.268 14-14S23.732 2 16 2z" fill="white"/>
            </svg>
        </a>
    @endif
    @if($tawkEnabled)
        <a class="jfa-float-btn jfa-float-btn--call" href="tel:{{ preg_replace('/\s+/', '', ($siteSetting ?? null)?->primarySupportPhone() ?? '+911800000000') }}" aria-label="Call support">
            <span class="material-symbols-outlined" style="color:#fff;font-size:26px;">call</span>
        </a>
        <button type="button" class="jfa-float-btn jfa-float-btn--chat" data-open-tawk aria-label="Live chat" onclick="return window.jfaOpenTawkChat && window.jfaOpenTawkChat(event);">
            <span class="material-symbols-outlined" style="color:#fff;font-size:26px;">chat</span>
        </button>
    @elseif(!empty($chatUrl))
        <a class="jfa-float-btn jfa-float-btn--chat" href="{{ $chatUrl }}" target="_blank" rel="noopener noreferrer" aria-label="Live chat">
            <span class="material-symbols-outlined" style="color:#fff;font-size:26px;">chat</span>
        </a>
    @else
        <a class="jfa-float-btn jfa-float-btn--call" href="tel:{{ preg_replace('/\s+/', '', ($siteSetting ?? null)?->primarySupportPhone() ?? '+911800000000') }}" aria-label="Call support">
            <span class="material-symbols-outlined" style="color:#fff;font-size:26px;">call</span>
        </a>
    @endif
</div>
