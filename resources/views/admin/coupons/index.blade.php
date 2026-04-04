@extends('layouts.admin')

@section('content')
    <div class="card">
        <div class="row" style="margin-bottom:12px;">
            <h1 class="section-title" style="margin:0;">Coupons</h1>
            <a class="btn" href="{{ route('admin.coupons.create') }}">Add coupon</a>
        </div>
        @if(session('status'))
            <p style="color:green;">{{ session('status') }}</p>
        @endif
        <div class="admin-table-scroll">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Type</th>
                        <th>Value</th>
                        <th>Valid</th>
                        <th>Usage</th>
                        <th>Active</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($coupons as $c)
                        <tr>
                            <td><strong>{{ $c->code }}</strong></td>
                            <td>{{ $c->discount_type }}</td>
                            <td>{{ $c->discount_value }}</td>
                            <td>{{ $c->valid_from?->format('Y-m-d') }} → {{ $c->valid_to?->format('Y-m-d') }}</td>
                            <td>{{ $c->used_count }} / {{ $c->max_usage ?? '∞' }}</td>
                            <td>{{ $c->is_active ? 'Yes' : 'No' }}</td>
                            <td style="display:flex;gap:8px;">
                                <a class="btn secondary" href="{{ route('admin.coupons.edit', $c) }}">Edit</a>
                                <form method="post" action="{{ route('admin.coupons.destroy', $c) }}" onsubmit="return confirm('Delete?');">
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
        <div style="margin-top:12px;">{{ $coupons->links() }}</div>
    </div>
@endsection

