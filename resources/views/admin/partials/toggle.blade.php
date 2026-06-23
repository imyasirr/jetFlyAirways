{{-- Boolean field as switch. Unchecked checkboxes are omitted; controllers use $request->boolean('name'). --}}
@php
    $toggleOnValue = $onValue ?? '1';
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
    <label class="admin-toggle">
        <input type="checkbox" name="{{ $name }}" value="{{ $toggleOnValue }}" class="admin-toggle-input" aria-label="{{ strip_tags($label) }}" @checked((bool) ($checked ?? false))>
        <span class="admin-toggle-track" aria-hidden="true"><span class="admin-toggle-thumb"></span></span>
    </label>
</div>
