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
        $banners = Banner::query()->orderBy('sort_order')->orderByDesc('id')->get();

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
            'description' => ['nullable', 'string', 'max:2000'],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['nullable', 'string', 'max:120'],
            'show_tags' => ['boolean'],
            'image_files' => ['required', 'array', 'min:1'],
            'image_files.*' => ['required', 'image', 'mimes:jpeg,png,webp,gif', 'max:10240'],
            'link' => ['nullable', 'string', 'max:500'],
            'button_text' => ['nullable', 'string', 'max:120'],
            'show_button' => ['boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:99999'],
            'is_active' => ['boolean'],
        ]);
        $data = $this->normalizeBannerFields($request, $data);

        $files = $request->file('image_files', []);
        unset($data['image_files']);

        foreach ($files as $index => $file) {
            $path = PublicImageStorage::storeUpload($file, 'banners');
            abort_if($path === null, 500, 'Image upload failed.');

            Banner::create(array_merge($data, [
                'image' => $path,
                'sort_order' => ((int) $data['sort_order']) + $index,
            ]));
        }

        return redirect()->route('admin.banners.index')->with('status', count($files) > 1 ? 'Banners created.' : 'Banner created.');
    }

    public function edit(Banner $banner): View
    {
        return view('admin.banners.edit', compact('banner'));
    }

    public function update(Request $request, Banner $banner): RedirectResponse
    {
        $data = $request->validate([
            'title' => ['nullable', 'string', 'max:200'],
            'description' => ['nullable', 'string', 'max:2000'],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['nullable', 'string', 'max:120'],
            'show_tags' => ['boolean'],
            'image_file' => ['nullable', 'image', 'mimes:jpeg,png,webp,gif', 'max:10240'],
            'link' => ['nullable', 'string', 'max:500'],
            'button_text' => ['nullable', 'string', 'max:120'],
            'show_button' => ['boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:99999'],
            'is_active' => ['boolean'],
        ]);
        $data = $this->normalizeBannerFields($request, $data);

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

    /** @param  array<string, mixed>  $data */
    private function normalizeBannerFields(Request $request, array $data): array
    {
        $data['tags'] = collect($data['tags'] ?? [])
            ->map(fn ($tag) => is_string($tag) ? trim($tag) : '')
            ->filter()
            ->values()
            ->all();
        $data['show_tags'] = $request->boolean('show_tags');
        $data['show_button'] = $request->boolean('show_button');
        $data['is_active'] = $request->boolean('is_active');
        $data['sort_order'] = $data['sort_order'] ?? 0;

        return $data;
    }
}
