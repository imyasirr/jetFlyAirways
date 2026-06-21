@extends('layouts.admin')

@section('title', 'Announcements')

@section('content')
    <div class="card">
        <div class="row" style="margin-bottom:12px;align-items:flex-start;gap:12px;flex-wrap:wrap;">
            <div style="flex:1;min-width:240px;">
                <h1 class="section-title" style="margin:0;">Announcements</h1>
                <p style="font-size:14px;color:#64748b;margin:8px 0 0;max-width:72ch;line-height:1.55;">
                    Shown to <strong>logged-in customers</strong> under Account → Notifications.
                    Set a <strong>future</strong> publish date to schedule, or choose now to go live immediately. Past dates are blocked.
                </p>
            </div>
            <a class="btn" href="{{ route('admin.announcements.create') }}" style="flex-shrink:0;">New announcement</a>
        </div>

        <div class="admin-table-scroll">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Status</th>
                        <th>Published</th>
                        <th>Link</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($announcements as $a)
                        @php
                            $isLive = $a->is_active && $a->published_at && $a->published_at->lte(now());
                            $isScheduled = $a->is_active && $a->published_at && $a->published_at->gt(now());
                        @endphp
                        <tr>
                            <td>
                                <strong>{{ \Illuminate\Support\Str::limit($a->title, 70) }}</strong>
                                @if($a->body)
                                    <div style="font-size:12px;color:#64748b;margin-top:4px;">{{ \Illuminate\Support\Str::limit($a->body, 90) }}</div>
                                @endif
                            </td>
                            <td>
                                @if($isLive)
                                    <span style="display:inline-block;padding:4px 10px;border-radius:999px;background:#dcfce7;color:#166534;font-size:12px;font-weight:700;">Live</span>
                                @elseif($isScheduled)
                                    <span style="display:inline-block;padding:4px 10px;border-radius:999px;background:#fef3c7;color:#92400e;font-size:12px;font-weight:700;">Scheduled</span>
                                @else
                                    <span style="display:inline-block;padding:4px 10px;border-radius:999px;background:#f1f5f9;color:#64748b;font-size:12px;font-weight:700;">Draft</span>
                                @endif
                            </td>
                            <td>{{ $a->published_at?->timezone(config('app.timezone'))->format('M j, Y g:i A') ?? '—' }}</td>
                            <td>
                                @if($a->link)
                                    <a href="{{ $a->link }}" target="_blank" rel="noopener" style="font-size:13px;">Open</a>
                                @else
                                    —
                                @endif
                            </td>
                            <td class="admin-table-actions">
                                @include('admin.partials.table-actions', [
                                    'edit' => route('admin.announcements.edit', $a),
                                    'delete' => route('admin.announcements.destroy', $a),
                                    'deleteConfirm' => 'Delete this announcement?',
                                ])
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="color:#64748b;">No announcements yet. Create one to notify logged-in customers.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
