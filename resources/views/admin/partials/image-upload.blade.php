@php
    $fieldName = $name ?? 'image_file';
    $imgUrl = isset($currentPath) ? \App\Support\PublicImageStorage::url($currentPath) : null;
@endphp
<div style="margin-bottom:14px;">
    <label style="display:block;font-weight:600;margin-bottom:6px;">{{ $label }}</label>
    @if(!empty($hint))
        <p style="font-size:12px;color:#64748b;margin:0 0 8px;">{{ $hint }}</p>
    @endif
    @if($imgUrl)
        <div style="margin:0 0 10px;">
            <img src="{{ $imgUrl }}" alt="" style="max-height:140px;max-width:100%;border-radius:10px;border:1px solid var(--admin-border);object-fit:contain;background:#f8fafc;">
        </div>
    @endif
    <input type="file" name="{{ $fieldName }}" accept="image/jpeg,image/png,image/webp,image/gif" {{ !empty($required) ? 'required' : '' }} style="font-size:14px;">
</div>
