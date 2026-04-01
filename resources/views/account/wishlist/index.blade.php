@extends('layouts.account')

@section('title', 'Wishlist')
@section('heading', 'Wishlist')

@section('content')
    <div class="acct-card">
        <h2>Saved items</h2>
        @if($rows->isEmpty())
            <p style="color:var(--acct-muted);margin:0;">Nothing saved yet. Open a flight, hotel, or package and tap “Save to wishlist”.</p>
        @else
            <table class="acct-table">
                <thead>
                    <tr>
                        <th>Item</th>
                        <th>Type</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($rows as $row)
                        <tr>
                            <td>
                                @if($row['url'])
                                    <a href="{{ $row['url'] }}" style="color:var(--acct-primary);font-weight:700;">{{ $row['title'] }}</a>
                                @else
                                    {{ $row['title'] }}
                                    <span style="color:var(--acct-muted);font-size:12px;"> (no longer listed)</span>
                                @endif
                            </td>
                            <td>{{ ucfirst($row['module']) }}</td>
                            <td>
                                @if($row['url'])
                                    <form method="post" action="{{ route('wishlist.destroy', ['module' => $row['module'], 'id' => $row['module_item_id']]) }}" style="margin:0;display:inline;" onsubmit="return confirm('Remove from wishlist?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn outline" style="padding:6px 12px;font-size:13px;">Remove</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection
