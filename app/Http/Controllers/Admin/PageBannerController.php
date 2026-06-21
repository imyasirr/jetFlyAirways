<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\PageBanner;
use App\Support\PublicImageStorage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PageBannerController extends Controller
{
    public function index(): View
    {
        PageBanner::syncCatalog();
        PageBanner::syncCmsPages();

        $banners = PageBanner::query()->orderBy('label')->get();

        return view('admin.page-banners.index', compact('banners'));
    }

    public function create(): View
    {
        return view('admin.page-banners.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:200'],
            'slug' => [
                'required',
                'string',
                'max:80',
                'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
                Rule::unique('cms_pages', 'slug'),
                Rule::notIn(Page::reservedSlugs()),
            ],
            'subtitle' => ['nullable', 'string', 'max:500'],
            'image_file' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,gif', 'max:10240'],
            'is_active' => ['boolean'],
        ]);

        $pageKey = PageBanner::cmsPageKey($data['slug']);
        abort_if(
            PageBanner::query()->where('page_key', $pageKey)->exists(),
            422,
            'A banner for this slug already exists.'
        );

        $imagePath = null;
        if ($request->hasFile('image_file')) {
            $imagePath = PublicImageStorage::storeUpload(
                $request->file('image_file'),
                'page-banners'
            );
        }

        $page = Page::create([
            'slug' => $data['slug'],
            'title' => $data['title'],
            'body' => '<p>'.e($data['title']).' — edit this content under CMS pages.</p>',
            'meta_description' => $data['subtitle'] ?? null,
            'hero_image' => $imagePath,
            'is_active' => $request->boolean('is_active'),
        ]);

        PageBanner::query()->create([
            'page_key' => $pageKey,
            'label' => $data['title'].' (/p/'.$data['slug'].')',
            'subtitle' => $data['subtitle'] ?? null,
            'image' => $imagePath,
            'is_active' => $request->boolean('is_active'),
            'is_system' => false,
        ]);

        return redirect()
            ->route('admin.page-banners.index')
            ->with('status', 'Page created at /p/'.$page->slug.'. You can edit the text under CMS pages.');
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

        if ($cmsPage = $pageBanner->linkedCmsPage()) {
            $cmsUpdates = ['is_active' => $request->boolean('is_active')];
            if (isset($data['image'])) {
                $cmsUpdates['hero_image'] = $data['image'];
            }
            $cmsPage->update($cmsUpdates);
        }

        return redirect()
            ->route('admin.page-banners.index')
            ->with('status', 'Page banner updated.');
    }

    public function destroy(PageBanner $pageBanner): RedirectResponse
    {
        abort_if($pageBanner->is_system, 403, 'Built-in module banners cannot be deleted.');

        $slug = PageBanner::cmsSlugFromKey($pageBanner->page_key);
        if ($slug !== null) {
            Page::query()->where('slug', $slug)->delete();
        }

        if ($pageBanner->image) {
            PublicImageStorage::deleteIfExists($pageBanner->image);
        }

        $pageBanner->delete();

        return redirect()
            ->route('admin.page-banners.index')
            ->with('status', 'Page and banner removed.');
    }
}
