@php
    $waRaw = config('jetfly.whatsapp.number');
    $waDigits = $waRaw ? preg_replace('/\D+/', '', $waRaw) : '';
    $waText = rawurlencode((string) config('jetfly.whatsapp.message'));
@endphp
@if($waDigits !== '')
    <a class="wa-float" href="https://wa.me/{{ $waDigits }}?text={{ $waText }}" target="_blank" rel="noopener noreferrer" title="WhatsApp us" aria-label="WhatsApp chat">&#128172;</a>
@endif
