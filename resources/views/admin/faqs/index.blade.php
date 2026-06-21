@extends('layouts.admin')

@section('content')
    <div class="card">
        <div class="row" style="margin-bottom:12px;">
            <h1 class="section-title" style="margin:0;">FAQs</h1>
            <a class="btn" href="{{ route('admin.faqs.create') }}">Add FAQ</a>
        </div>
        <div class="admin-table-scroll">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Question</th>
                        <th>Active</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($faqs as $faq)
                        <tr>
                            <td>{{ \Illuminate\Support\Str::limit($faq->question, 80) }}</td>
                            <td>{{ $faq->is_active ? 'Yes' : 'No' }}</td>
                            <td class="admin-table-actions">
                                @include('admin.partials.table-actions', [
                                    'edit' => route('admin.faqs.edit', $faq),
                                    'delete' => route('admin.faqs.destroy', $faq),
                                    'deleteConfirm' => 'Delete?',
                                ])
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div style="margin-top:12px;">{{ $faqs->links() }}</div>
    </div>
@endsection

