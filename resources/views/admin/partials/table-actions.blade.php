@php
    $viewLabel = $viewLabel ?? 'View';
    $editLabel = $editLabel ?? 'Edit';
    $deleteLabel = $deleteLabel ?? 'Delete';
    $deleteConfirm = $deleteConfirm ?? 'Delete this item?';
@endphp
<div class="admin-table-actions__inner">
    @if(!empty($view))
        <a href="{{ $view }}" class="admin-icon-btn" title="{{ $viewLabel }}" aria-label="{{ $viewLabel }}">
            <span class="material-symbols-outlined" aria-hidden="true">{{ $viewIcon ?? 'visibility' }}</span>
        </a>
    @endif
    @if(!empty($edit))
        <a href="{{ $edit }}" class="admin-icon-btn" title="{{ $editLabel }}" aria-label="{{ $editLabel }}">
            <span class="material-symbols-outlined" aria-hidden="true">edit</span>
        </a>
    @endif
    @if(!empty($delete))
        <form method="post" action="{{ $delete }}" onsubmit="return confirm(@json($deleteConfirm));">
            @csrf
            @method('DELETE')
            <button type="submit" class="admin-icon-btn admin-icon-btn--danger" title="{{ $deleteLabel }}" aria-label="{{ $deleteLabel }}">
                <span class="material-symbols-outlined" aria-hidden="true">delete</span>
            </button>
        </form>
    @endif
</div>
