<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Support\PublicImageStorage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BannerController extends Controller
{
    public function index(): View
    {
        $banners = Banner::query()->orderBy('sort_order')->orderByDesc('id')->paginate(20);

        return view('admin.banners.index', compact('banners'));
    }

    public function create(): View
    {
        return view('admin.banners.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'title' => ['nullable', 'string', 'max:200'],
            'image_file' => ['required', 'image', 'mimes:jpeg,png,webp,gif', 'max:10240'],
            'link' => ['nullable', 'string', 'max:500'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:99999'],
            'is_active' => ['boolean'],
        ]);
        $data['is_active'] = $request->boolean('is_active');
        $data['sort_order'] = $data['sort_order'] ?? 0;

        $path = PublicImageStorage::storeUpload($request->file('image_file'), 'banners');
        abort_if($path === null, 500, 'Image upload failed.');

        unset($data['image_file']);
        $data['image'] = $path;

        Banner::create($data);

        return redirect()->route('admin.banners.index')->with('status', 'Banner created.');
    }

    public function edit(Banner $banner): View
    {
        return view('admin.banners.edit', compact('banner'));
    }

    public function update(Request $request, Banner $banner): RedirectResponse
    {
        $data = $request->validate([
            'title' => ['nullable', 'string', 'max:200'],
            'image_file' => ['nullable', 'image', 'mimes:jpeg,png,webp,gif', 'max:10240'],
            'link' => ['nullable', 'string', 'max:500'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:99999'],
            'is_active' => ['boolean'],
        ]);
        $data['is_active'] = $request->boolean('is_active');
        $data['sort_order'] = $data['sort_order'] ?? 0;

        unset($data['image_file']);
        if ($request->hasFile('image_file')) {
            $path = PublicImageStorage::storeUpload($request->file('image_file'), 'banners', $banner->image);
            if ($path !== null) {
                $data['image'] = $path;
            }
        }

        $banner->update($data);

        return redirect()->route('admin.banners.index')->with('status', 'Banner updated.');
    }

    public function destroy(Banner $banner): RedirectResponse
    {
        PublicImageStorage::deleteIfExists($banner->image);
        $banner->delete();

        return redirect()->route('admin.banners.index')->with('status', 'Banner deleted.');
    }
}
