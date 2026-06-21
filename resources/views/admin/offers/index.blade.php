@extends('layouts.admin')

@section('content')
    <div class="card">
        <div class="row" style="margin-bottom:12px;">
            <h1 class="section-title" style="margin:0;">Offers</h1>
            <a class="btn" href="{{ route('admin.offers.create') }}">Add offer</a>
        </div>
        <div class="admin-table-scroll">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Dates</th>
                        <th>Active</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($offers as $o)
                        <tr>
                            <td><strong>{{ $o->title }}</strong></td>
                            <td>{{ $o->start_date?->format('Y-m-d') ?? '—' }} → {{ $o->end_date?->format('Y-m-d') ?? '—' }}</td>
                            <td>{{ $o->is_active ? 'Yes' : 'No' }}</td>
                            <td class="admin-table-actions">
                                @include('admin.partials.table-actions', [
                                    'edit' => route('admin.offers.edit', $o),
                                    'delete' => route('admin.offers.destroy', $o),
                                    'deleteConfirm' => 'Delete?',
                                ])
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
</div>
@endsection

