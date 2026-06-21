@extends('layouts.admin')

@section('content')
    <div class="card">
        <div class="row" style="margin-bottom:12px;">
            <div>
                <h1 class="section-title" style="margin:0;">Trust highlights</h1>
                <p style="margin:6px 0 0;color:var(--admin-muted);font-size:14px;">Three-column cards on the homepage (Secure payments, Best fares, etc.).</p>
            </div>
            <a class="btn" href="{{ route('admin.home-trust-cards.create') }}">Add card</a>
        </div>
        <div class="admin-table-scroll">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Icon</th>
                        <th>Title</th>
                        <th>Sort</th>
                        <th>Active</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($cards as $card)
                        <tr>
                            <td>
                                <span class="material-symbols-outlined filled" style="font-size:22px;color:var(--admin-accent);" aria-hidden="true">{{ $card->icon ?: 'verified' }}</span>
                            </td>
                            <td>
                                <strong>{{ $card->title }}</strong>
                                <div style="font-size:13px;color:var(--admin-muted);max-width:36ch;">{{ Str::limit($card->description, 80) }}</div>
                            </td>
                            <td>{{ $card->sort_order }}</td>
                            <td>{{ $card->is_active ? 'Yes' : 'No' }}</td>
                            <td class="admin-table-actions">
                                @include('admin.partials.table-actions', [
                                    'edit' => route('admin.home-trust-cards.edit', $card),
                                    'delete' => route('admin.home-trust-cards.destroy', $card),
                                    'deleteConfirm' => 'Delete this trust card?',
                                ])
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="color:var(--admin-muted);">No trust cards yet. Add at least three for the homepage row.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div style="margin-top:12px;">{{ $cards->links() }}</div>
    </div>
@endsection
