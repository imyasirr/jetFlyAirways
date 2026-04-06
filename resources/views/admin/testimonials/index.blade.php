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
                        <th>Rating</th>
                        <th>Active</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($testimonials as $t)
                        <tr>
                            <td>{{ $t->name }}</td>
                            <td>{{ $t->rating }}</td>
                            <td>{{ $t->is_active ? 'Yes' : 'No' }}</td>
                            <td class="admin-table-actions"><div class="admin-table-actions__inner">
                                <a class="btn secondary" href="{{ route('admin.testimonials.edit', $t) }}">Edit</a>
                                <form method="post" action="{{ route('admin.testimonials.destroy', $t) }}" onsubmit="return confirm('Delete?');">
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
        <div style="margin-top:12px;">{{ $testimonials->links() }}</div>
    </div>
@endsection

