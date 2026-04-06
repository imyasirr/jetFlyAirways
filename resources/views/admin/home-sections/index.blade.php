@extends('layouts.admin')

@section('content')
    <div class="card">
        <h2 class="section-title" style="font-size:1.1rem;">Section order &amp; visibility</h2>
        <p style="color:var(--admin-muted);margin:0 0 12px;">Drag rows to reorder. Sort values update automatically — save when done.</p>

        <form method="post" action="{{ route('admin.home-sections.update') }}" id="home-sections-form">
            @csrf
            @method('PUT')
            <div class="admin-table-scroll">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th style="width:40px;" aria-hidden="true"></th>
                            <th>Key</th>
                            <th>Admin label</th>
                            <th>Sort</th>
                            <th>Active</th>
                        </tr>
                    </thead>
                    <tbody id="home-sections-sortable">
                        @foreach($sections as $section)
                            <tr data-sort-row data-section-id="{{ $section->id }}">
                                <td>
                                    <button type="button" class="drag-handle" title="Drag to reorder" aria-label="Drag to reorder">⠿</button>
                                </td>
                                <td><code>{{ $section->partial_key }}</code></td>
                                <td>
                                    <input type="hidden" name="sections[{{ $loop->index }}][id]" value="{{ $section->id }}">
                                    <input type="text" name="sections[{{ $loop->index }}][admin_label]" value="{{ old('sections.'.$loop->index.'.admin_label', $section->admin_label) }}" maxlength="120">
                                </td>
                                <td style="max-width:100px;">
                                    <input type="number" class="js-sort-order" name="sections[{{ $loop->index }}][sort_order]" value="{{ old('sections.'.$loop->index.'.sort_order', $section->sort_order) }}" min="0" max="99999">
                                </td>
                                <td>
                                    @include('admin.partials.toggle', [
                                        'name' => 'sections['.$loop->index.'][is_active]',
                                        'label' => 'Section visible',
                                        'checked' => old('sections.'.$loop->index.'.is_active', $section->is_active ? '1' : '0') === '1',
                                        'compact' => true,
                                    ])
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <button type="submit" class="btn" style="margin-top:16px;">Save changes</button>
        </form>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    var tbody = document.getElementById('home-sections-sortable');
    if (!tbody || typeof Sortable === 'undefined') return;

    function reindexRows() {
        var rows = tbody.querySelectorAll('tr[data-sort-row]');
        rows.forEach(function (tr, i) {
            var sortInp = tr.querySelector('.js-sort-order');
            if (sortInp) sortInp.value = String((i + 1) * 10);
            tr.querySelectorAll('[name^="sections["]').forEach(function (el) {
                var n = el.getAttribute('name');
                if (!n) return;
                el.setAttribute('name', n.replace(/sections\[\d+]/, 'sections[' + i + ']'));
            });
        });
    }

    new Sortable(tbody, {
        animation: 150,
        handle: '.drag-handle',
        ghostClass: 'sortable-ghost',
        onEnd: reindexRows
    });
});
</script>
@endpush
