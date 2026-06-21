<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PageBanner;
use App\Support\PublicImageStorage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PageBannerController extends Controller
{
    public function index(): View
    {
        $banners = PageBanner::query()->orderBy('label')->get();

        return view('admin.page-banners.index', compact('banners'));
    }

    public function edit(PageBanner $pageBanner): View
    {
        return view('admin.page-banners.edit', compact('pageBanner'));
    }

    public function update(Request $request, PageBanner $pageBanner): RedirectResponse
    {
        $data = $request->validate([
            'subtitle' => ['nullable', 'string', 'max:500'],
            'image_file' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,gif', 'max:10240'],
            'is_active' => ['boolean'],
        ]);

        if ($request->hasFile('image_file')) {
            $data['image'] = PublicImageStorage::storeUpload(
                $request->file('image_file'),
                'page-banners',
                $pageBanner->image
            );
        }

        $pageBanner->update([
            'subtitle' => $data['subtitle'] ?? null,
            'image' => $data['image'] ?? $pageBanner->image,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()
            ->route('admin.page-banners.index')
            ->with('status', 'Page banner updated.');
    }
}
