@extends('layouts.admin')

@section('content')
    <div class="card">
        <div class="row" style="margin-bottom:12px;">
            <h1 class="section-title" style="margin:0;">Visa &amp; insurance catalog</h1>
            <a class="btn" href="{{ route('admin.travel-addons.create') }}">Add plan</a>
        </div>
        <form method="get" action="{{ route('admin.travel-addons.index') }}" style="margin-bottom:14px;display:flex;gap:10px;flex-wrap:wrap;align-items:end;">
            <div>
                <label style="font-size:12px;color:var(--admin-muted);">Filter</label>
                <select name="category" onchange="this.form.submit()" style="width:auto;min-width:160px;">
                    <option value="">All categories</option>
                    @foreach(\App\Models\TravelAddon::categories() as $cat)
                        <option value="{{ $cat }}" {{ request('category') === $cat ? 'selected' : '' }}>{{ ucfirst($cat) }}</option>
                    @endforeach
                </select>
            </div>
        </form>
        <div class="admin-table-scroll">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Category</th>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Order</th>
                        <th>Active</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($addons as $a)
                        <tr>
                            <td>{{ $a->category }}</td>
                            <td>{{ \Illuminate\Support\Str::limit($a->name, 50) }}</td>
                            <td>{{ number_format((float) $a->price, 2) }}</td>
                            <td>{{ $a->sort_order }}</td>
                            <td>{{ $a->is_active ? 'Yes' : 'No' }}</td>
                            <td style="display:flex;gap:8px;flex-wrap:wrap;">
                                <a class="btn secondary" href="{{ route('admin.travel-addons.edit', $a) }}">Edit</a>
                                <form method="post" action="{{ route('admin.travel-addons.destroy', $a) }}" onsubmit="return confirm('Delete?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn" type="submit">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div style="margin-top:12px;">{{ $addons->links() }}</div>
    </div>
@endsection

