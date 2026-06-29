<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PopularDestination;
use App\Models\PopularDestinationGallery;
use App\Support\PublicImageStorage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PopularDestinationController extends Controller
{
    public function index(): View
    {
        $destinations = PopularDestination::query()->withCount('gallery')->orderBy('sort_order')->orderBy('id')->get();

        return view('admin.popular-destinations.index', compact('destinations'));
    }

    public function create(): View
    {
        return view('admin.popular-destinations.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'banner_file' => ['required', 'image', 'mimes:jpeg,png,webp,gif', 'max:10240'],
        ]);

        $data = $this->validated($request);
        $data['slug'] = $this->uniqueSlug($data['name']);

        $bannerPath = PublicImageStorage::storeUpload($request->file('banner_file'), 'popular-destinations');
        abort_if($bannerPath === null, 422, 'Banner image is required.');
        $data['banner'] = $bannerPath;

        $destination = PopularDestination::create($data);
        $this->storeGalleryUploads($request, $destination);

        return redirect()->route('admin.popular-destinations.index')->with('status', 'Destination created.');
    }

    public function edit(PopularDestination $popular_destination): View
    {
        $popular_destination->load('gallery');

        return view('admin.popular-destinations.edit', ['destination' => $popular_destination]);
    }

    public function update(Request $request, PopularDestination $popular_destination): RedirectResponse
    {
        $data = $this->validated($request);
        $data['slug'] = $this->uniqueSlug($data['name'], $popular_destination->id);

        if ($request->boolean('clear_banner')) {
            PublicImageStorage::deleteIfExists($popular_destination->banner);
            $data['banner'] = null;
        } elseif ($request->hasFile('banner_file')) {
            $path = PublicImageStorage::storeUpload($request->file('banner_file'), 'popular-destinations', $popular_destination->banner);
            if ($path !== null) {
                $data['banner'] = $path;
            }
        }

        unset($data['clear_banner']);
        $popular_destination->update($data);

        $this->deleteGalleryItems($request, $popular_destination);
        $this->storeGalleryUploads($request, $popular_destination);

        return redirect()->route('admin.popular-destinations.index')->with('status', 'Destination updated.');
    }

    public function destroy(PopularDestination $popular_destination): RedirectResponse
    {
        PublicImageStorage::deleteIfExists($popular_destination->banner);
        foreach ($popular_destination->gallery as $image) {
            PublicImageStorage::deleteIfExists($image->image);
        }
        $popular_destination->delete();

        return redirect()->route('admin.popular-destinations.index')->with('status', 'Destination deleted.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validated(Request $request): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'description' => ['nullable', 'string', 'max:200000'],
            'details' => ['nullable', 'string', 'max:200000'],
            'tag_line' => ['nullable', 'string', 'max:120'],
            'best_season' => ['nullable', 'string', 'max:120'],
            'package_destination' => ['nullable', 'string', 'max:120'],
            'banner_file' => ['nullable', 'image', 'mimes:jpeg,png,webp,gif', 'max:10240'],
            'clear_banner' => ['nullable', 'boolean'],
            'gallery_files' => ['nullable', 'array'],
            'gallery_files.*' => ['image', 'mimes:jpeg,png,webp,gif', 'max:10240'],
            'gallery_delete_ids' => ['nullable', 'array'],
            'gallery_delete_ids.*' => ['integer'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:99999'],
            'is_active' => ['boolean'],
        ]);

        $data['is_active'] = $request->boolean('is_active');
        $data['sort_order'] = (int) ($data['sort_order'] ?? 0);
        unset($data['banner_file'], $data['gallery_files'], $data['gallery_delete_ids']);

        return $data;
    }

    private function uniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name) ?: 'destination';
        $slug = $base;
        $i = 2;

        while (PopularDestination::query()
            ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
            ->where('slug', $slug)
            ->exists()) {
            $slug = $base.'-'.$i;
            $i++;
        }

        return $slug;
    }

    private function storeGalleryUploads(Request $request, PopularDestination $destination): void
    {
        $files = $request->file('gallery_files', []);
        $start = (int) $destination->gallery()->max('sort_order');

        foreach ($files as $offset => $file) {
            $path = PublicImageStorage::storeUpload($file, 'popular-destinations/gallery');
            if ($path === null) {
                continue;
            }

            PopularDestinationGallery::create([
                'popular_destination_id' => $destination->id,
                'image' => $path,
                'sort_order' => $start + $offset + 1,
            ]);
        }
    }

    private function deleteGalleryItems(Request $request, PopularDestination $destination): void
    {
        $ids = collect($request->input('gallery_delete_ids', []))->map(fn ($id) => (int) $id)->filter()->all();
        if ($ids === []) {
            return;
        }

        $destination->gallery()->whereIn('id', $ids)->get()->each(function (PopularDestinationGallery $image) {
            PublicImageStorage::deleteIfExists($image->image);
            $image->delete();
        });
    }
}
