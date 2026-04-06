@extends('layouts.account')

@section('title', 'Saved travellers')

@section('heading', 'Saved travellers')

@section('content')
    <div class="acct-card">
        <h2>Add traveller</h2>
        <form method="post" action="{{ route('account.saved-travellers.store') }}">
            @csrf
            <label>Full name <input type="text" name="full_name" value="{{ old('full_name') }}" required></label>
            <label>Email <input type="email" name="email" value="{{ old('email') }}"></label>
            <label>Phone <input type="text" name="phone" value="{{ old('phone') }}"></label>
            <div class="form-actions">
                <button type="submit" class="btn">Save traveller</button>
            </div>
        </form>
    </div>

    <div class="acct-card">
        <h2>Traveller list</h2>
        @if($savedTravellers->isEmpty())
            <p style="color:var(--acct-muted);margin:0;">No saved travellers yet.</p>
        @else
            <div style="display:grid;gap:12px;">
                @foreach($savedTravellers as $traveller)
                    <form method="post" action="{{ route('account.saved-travellers.update', $traveller) }}" class="ticket" style="border-style:solid;">
                        @csrf
                        @method('put')
                        <label>Full name <input type="text" name="full_name" value="{{ old('full_name', $traveller->full_name) }}" required></label>
                        <label>Email <input type="email" name="email" value="{{ old('email', $traveller->email) }}"></label>
                        <label>Phone <input type="text" name="phone" value="{{ old('phone', $traveller->phone) }}"></label>
                        <div class="form-actions">
                            <button type="submit" class="btn">Update</button>
                        </div>
                    </form>
                    <form method="post" action="{{ route('account.saved-travellers.destroy', $traveller) }}">
                        @csrf
                        @method('delete')
                        <button type="submit" class="btn outline">Remove</button>
                    </form>
                @endforeach
            </div>
            <div class="pagination">{{ $savedTravellers->links() }}</div>
        @endif
    </div>
@endsection

