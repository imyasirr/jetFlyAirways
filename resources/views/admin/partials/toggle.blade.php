{{-- Boolean field as switch. @include with: name, label, checked, optional hint, value, withHidden, compact --}}
@php
    $withHidden = $withHidden ?? true;
    $hiddenValue = $hiddenValue ?? '0';
    $value = $value ?? '1';
    $compact = $compact ?? false;
    $hint = $hint ?? null;
@endphp
<div class="admin-toggle-field {{ $compact ? 'admin-toggle-field--compact' : '' }}">
    <div class="admin-toggle-field__text">
        <span class="admin-toggle-field__label">{{ $label }}</span>
        @if(filled($hint))
            <span class="admin-toggle-field__hint">{{ $hint }}</span>
        @endif
    </div>
    @if($withHidden)
        <input type="hidden" name="{{ $name }}" value="{{ $hiddenValue }}">
    @endif
    <label class="admin-toggle">
        <input type="checkbox" name="{{ $name }}" value="{{ $value }}" class="admin-toggle-input" aria-label="{{ strip_tags($label) }}" @checked((bool) ($checked ?? false))>
        <span class="admin-toggle-track" aria-hidden="true"><span class="admin-toggle-thumb"></span></span>
    </label>
</div>
