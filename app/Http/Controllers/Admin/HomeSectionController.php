<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomeSection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeSectionController extends Controller
{
    public function index(): View
    {
        $sections = HomeSection::query()->orderBy('sort_order')->orderBy('id')->get();

        return view('admin.home-sections.index', compact('sections'));
    }

    public function update(Request $request): RedirectResponse
    {
        $rows = $request->input('sections', []);
        foreach ($rows as $row) {
            if (empty($row['id'])) {
                continue;
            }
            $section = HomeSection::query()->find($row['id']);
            if (! $section || ! in_array($section->partial_key, HomeSection::allowedPartialKeys(), true)) {
                continue;
            }
            $section->update([
                'admin_label' => is_string($row['admin_label'] ?? null) ? mb_substr($row['admin_label'], 0, 120) : $section->admin_label,
                'sort_order' => max(0, (int) ($row['sort_order'] ?? 0)),
                'is_active' => ! empty($row['is_active']),
            ]);
        }

        return redirect()->route('admin.home-sections.index')->with('status', 'Homepage sections updated.');
    }
}
