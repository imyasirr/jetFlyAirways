@extends('layouts.admin')

@section('content')
    <div class="card">
        <h1 class="section-title">Job applications</h1>
        <div class="admin-table-scroll">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Role</th>
                        <th>Applicant</th>
                        <th>Resume</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($applications as $a)
                        <tr>
                            <td>{{ $a->created_at->format('Y-m-d') }}</td>
                            <td>{{ $a->career?->job_title ?? '—' }}</td>
                            <td>{{ $a->name }}<br><small style="color:#64748b;">{{ $a->email }}</small></td>
                            <td>{{ $a->resume_path ? 'Yes' : '—' }}</td>
                            <td class="admin-table-actions"><div class="admin-table-actions__inner">
                                <a class="btn secondary" href="{{ route('admin.career-applications.show', $a) }}">View</a>
                            </div></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div style="margin-top:12px;">{{ $applications->links() }}</div>
    </div>
@endsection

