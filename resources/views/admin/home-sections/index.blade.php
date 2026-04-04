@extends('layouts.admin')

@section('content')
    <div class="card">
        <h2 class="section-title" style="font-size:1.1rem;">Section order &amp; visibility</h2>
        <p style="color:var(--admin-muted);margin:0 0 12px;">Sort order controls display order. Each row maps to a fixed block on the public home page.</p>

        <form method="post" action="{{ route('admin.home-sections.update') }}">
            @csrf
            @method('PUT')
            <div class="admin-table-scroll">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Key</th>
                            <th>Admin label</th>
                            <th>Sort</th>
                            <th>Active</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sections as $section)
                            <tr>
                                <td><code>{{ $section->partial_key }}</code></td>
                                <td>
                                    <input type="hidden" name="sections[{{ $loop->index }}][id]" value="{{ $section->id }}">
                                    <input type="text" name="sections[{{ $loop->index }}][admin_label]" value="{{ old('sections.'.$loop->index.'.admin_label', $section->admin_label) }}" maxlength="120">
                                </td>
                                <td style="max-width:100px;">
                                    <input type="number" name="sections[{{ $loop->index }}][sort_order]" value="{{ old('sections.'.$loop->index.'.sort_order', $section->sort_order) }}" min="0" max="99999">
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
