<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AnnouncementController extends Controller
{
    public function index(): View
    {
        $announcements = Announcement::query()->orderByDesc('id')->paginate(20);

        return view('admin.announcements.index', compact('announcements'));
    }

    public function create(): View
    {
        return view('admin.announcements.create');
    }

    public function store(Request $request): RedirectResponse
    {
        Announcement::create($this->validated($request));

        return redirect()->route('admin.announcements.index')->with('status', 'Announcement saved.');
    }

    public function edit(Announcement $announcement): View
    {
        return view('admin.announcements.edit', compact('announcement'));
    }

    public function update(Request $request, Announcement $announcement): RedirectResponse
    {
        $announcement->update($this->validated($request, $announcement));

        return redirect()->route('admin.announcements.index')->with('status', 'Announcement updated.');
    }

    public function destroy(Announcement $announcement): RedirectResponse
    {
        $announcement->delete();

        return redirect()->route('admin.announcements.index')->with('status', 'Announcement deleted.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validated(Request $request, ?Announcement $existing = null): array
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:200'],
            'body' => ['nullable', 'string', 'max:10000'],
            'link' => ['nullable', 'string', 'max:500'],
            'published_at' => ['required', 'date'],
            'is_active' => ['boolean'],
        ]);
        $data['is_active'] = $request->boolean('is_active');

        $publishedAt = Carbon::parse($data['published_at'])->timezone(config('app.timezone'));
        $existingAt = $existing?->published_at?->copy()->timezone(config('app.timezone'));
        $unchangedExisting = $existingAt && $publishedAt->equalTo($existingAt);

        if ($publishedAt->lt(now()) && ! $unchangedExisting) {
            throw ValidationException::withMessages([
                'published_at' => 'Publish date cannot be in the past. Choose now or a future date.',
            ]);
        }

        $data['published_at'] = $publishedAt;

        if (! $data['is_active']) {
            // Keep scheduled time while inactive; customers still won't see it until active + published.
        }

        return $data;
    }
}
