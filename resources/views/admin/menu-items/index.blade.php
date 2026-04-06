@extends('layouts.admin')

@section('content')
    <div class="card">
        <div class="row" style="margin-bottom:12px;">
            <h1 class="section-title" style="margin:0;">Website menu</h1>
            <a class="btn" href="{{ route('admin.menu-items.create') }}">Add item</a>
        </div>
        <p style="color:#64748b;font-size:14px;max-width:70ch;">Header uses mega dropdowns for top-level items with children. Footer uses grouped columns. Leave <strong>href</strong> empty for parent-only labels.</p>
        @if(session('status'))
            <p style="color:green;">{{ session('status') }}</p>
        @endif
        <div class="admin-table-scroll">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Label</th>
                        <th>Location</th>
                        <th>Parent</th>
                        <th>Href</th>
                        <th>Sort</th>
                        <th>Active</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($items as $item)
                        <tr>
                            <td>{{ $item->label }}</td>
                            <td>{{ $item->location }}</td>
                            <td>{{ $item->parent?->label ?? '—' }}</td>
                            <td style="max-width:200px;overflow:hidden;text-overflow:ellipsis;">{{ $item->href ?? '—' }}</td>
                            <td>{{ $item->sort_order }}</td>
                            <td>{{ $item->is_active ? 'Yes' : 'No' }}</td>
                            <td class="admin-table-actions"><div class="admin-table-actions__inner">
                                <a class="btn secondary" href="{{ route('admin.menu-items.edit', $item) }}">Edit</a>
                                <form method="post" action="{{ route('admin.menu-items.destroy', $item) }}" onsubmit="return confirm('Delete this item?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn" type="submit">Delete</button>
                                </form>
                            </div></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div style="margin-top:12px;">{{ $items->links() }}</div>
    </div>
@endsection

