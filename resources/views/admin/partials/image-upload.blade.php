@php
    $fieldName = $name ?? 'image_file';
    $imgUrl = isset($currentPath) ? \App\Support\PublicImageStorage::url($currentPath) : null;
@endphp
<div class="admin-image-upload">
    <label class="admin-image-upload__label">{{ $label }}</label>
    @if(!empty($hint))
        <p class="admin-image-upload__hint">{{ $hint }}</p>
    @endif
    @if($imgUrl)
        <div class="admin-image-upload__preview">
            <img src="{{ $imgUrl }}" alt="{{ $label }} current preview">
            <div>
                <span class="admin-image-upload__eyebrow">Current uploaded image</span>
                <a href="{{ $imgUrl }}" target="_blank" rel="noopener noreferrer">Open image</a>
                @if(!empty($currentPath))
                    <code>{{ $currentPath }}</code>
                @endif
            </div>
        </div>
    @else
        <div class="admin-image-upload__empty">No image uploaded yet.</div>
    @endif
    <input type="file" name="{{ $fieldName }}" accept="image/jpeg,image/png,image/webp,image/gif" {{ !empty($required) ? 'required' : '' }}>
</div>
