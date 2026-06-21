@php
    use App\Support\AdminMaterialIcons;

    $fieldName = $name ?? 'icon';
    $selected = old($fieldName, $value ?? 'verified');
    $icons = AdminMaterialIcons::trustPicker();
    $pickerId = 'icon-picker-' . md5($fieldName . ($selected ?? ''));
@endphp
<div class="admin-icon-picker admin-field-full" data-icon-picker id="{{ $pickerId }}">
    @if(!empty($label))
        <span class="admin-icon-picker__label">{{ $label }}</span>
    @endif

    <div class="admin-icon-picker__selected">
        <span class="admin-icon-picker__preview" aria-hidden="true">
            <span class="material-symbols-outlined filled" data-icon-preview>{{ $selected ?: 'verified' }}</span>
        </span>
        <div class="admin-icon-picker__selected-meta">
            <strong data-icon-label>{{ $selected ?: 'verified' }}</strong>
            <span>Selected icon</span>
        </div>
    </div>

    <input type="hidden" name="{{ $fieldName }}" value="{{ $selected ?: 'verified' }}" data-icon-input>

    <label class="admin-icon-picker__search-wrap">
        <span class="material-symbols-outlined" aria-hidden="true">search</span>
        <input type="search" class="admin-icon-picker__search" placeholder="Search icons…" autocomplete="off" data-icon-search>
    </label>

    <div class="admin-icon-picker__grid" role="listbox" aria-label="Choose an icon">
        @foreach($icons as $icon)
            <button
                type="button"
                class="admin-icon-picker__item{{ ($selected === $icon) ? ' is-selected' : '' }}"
                data-icon-option="{{ $icon }}"
                data-icon-label="{{ $icon }}"
                title="{{ $icon }}"
                role="option"
                aria-selected="{{ ($selected === $icon) ? 'true' : 'false' }}"
            >
                <span class="material-symbols-outlined filled" aria-hidden="true">{{ $icon }}</span>
                <span class="admin-icon-picker__item-name">{{ $icon }}</span>
            </button>
        @endforeach
    </div>
    <p class="admin-icon-picker__hint">Pick an icon from the grid. Names match <a href="https://fonts.google.com/icons" target="_blank" rel="noopener">Google Material Symbols</a>.</p>
</div>

@once
    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('[data-icon-picker]').forEach(function (picker) {
            var input = picker.querySelector('[data-icon-input]');
            var preview = picker.querySelector('[data-icon-preview]');
            var label = picker.querySelector('[data-icon-label]');
            var search = picker.querySelector('[data-icon-search]');
            var items = picker.querySelectorAll('[data-icon-option]');

            function selectIcon(icon) {
                if (!input || !icon) return;
                input.value = icon;
                if (preview) preview.textContent = icon;
                if (label) label.textContent = icon;
                items.forEach(function (btn) {
                    var active = btn.getAttribute('data-icon-option') === icon;
                    btn.classList.toggle('is-selected', active);
                    btn.setAttribute('aria-selected', active ? 'true' : 'false');
                });
            }

            picker.addEventListener('click', function (event) {
                var btn = event.target.closest('[data-icon-option]');
                if (!btn) return;
                selectIcon(btn.getAttribute('data-icon-option'));
            });

            if (search) {
                search.addEventListener('input', function () {
                    var q = search.value.trim().toLowerCase();
                    items.forEach(function (btn) {
                        var name = (btn.getAttribute('data-icon-option') || '').toLowerCase();
                        btn.hidden = q !== '' && !name.includes(q);
                    });
                });
            }
        });
    });
    </script>
    @endpush
@endonce
