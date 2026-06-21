@extends('layouts.admin')

@section('content')
    <div class="card">
        <div class="row" style="margin-bottom:12px;">
            <h1 class="section-title" style="margin:0;">Testimonials</h1>
            <a class="btn" href="{{ route('admin.testimonials.create') }}">Add testimonial</a>
        </div>
        <div class="admin-table-scroll">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Photo</th>
                        <th>Rating</th>
                        <th>Active</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($testimonials as $t)
                        <tr>
                            <td>{{ $t->name }}</td>
                            <td>
                                @if($t->photoUrl())
                                    <img src="{{ $t->photoUrl() }}" alt="" width="40" height="40" style="width:40px;height:40px;border-radius:10px;object-fit:cover;border:1px solid var(--admin-border);">
                                @else
                                    <span style="color:var(--admin-muted);font-size:13px;">—</span>
                                @endif
                            </td>
                            <td>{{ $t->rating }}</td>
                            <td>{{ $t->is_active ? 'Yes' : 'No' }}</td>
                            <td class="admin-table-actions">
                                @include('admin.partials.table-actions', [
                                    'edit' => route('admin.testimonials.edit', $t),
                                    'delete' => route('admin.testimonials.destroy', $t),
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

