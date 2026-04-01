<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\AnnouncementRead;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class AnnouncementInboxController extends Controller
{
    public function index(Request $request): View
    {
        $announcements = collect();
        if (Schema::hasTable('announcements')) {
            $announcements = Announcement::query()->published()->orderByDesc('published_at')->get();
        }

        $readIds = collect();
        if (Schema::hasTable('announcement_reads')) {
            $readIds = AnnouncementRead::query()
                ->where('user_id', $request->user()->id)
                ->pluck('announcement_id');
        }

        return view('account.announcements.index', compact('announcements', 'readIds'));
    }

    public function markRead(Request $request, Announcement $announcement): RedirectResponse
    {
        abort_unless($announcement->is_active && $announcement->published_at && $announcement->published_at->lte(now()), 404);
        abort_unless(Schema::hasTable('announcement_reads'), 503);

        AnnouncementRead::query()->firstOrCreate(
            [
                'user_id' => $request->user()->id,
                'announcement_id' => $announcement->id,
            ],
            ['read_at' => now()]
        );

        if ($announcement->link) {
            return redirect()->away($announcement->link);
        }

        return back();
    }
}
