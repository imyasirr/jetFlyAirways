@extends('layouts.admin')

@section('content')
    <div class="card">
        <h1 class="section-title">Homepage sections</h1>
        <p style="color:var(--admin-muted);margin-top:0;">Drag order is controlled by sort order. Each block maps to a fixed layout partial on the public home page.</p>

        <form method="post" action="{{ route('admin.home-sections.update') }}">
            @csrf
            @method('PUT')
            <div style="overflow:auto;">
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
                                    <input type="hidden" name="sections[{{ $loop->index }}][is_active]" value="0">
                                    <input type="checkbox" name="sections[{{ $loop->index }}][is_active]" value="1" {{ old('sections.'.$loop->index.'.is_active', $section->is_active ? '1' : '0') === '1' ? 'checked' : '' }}>
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
