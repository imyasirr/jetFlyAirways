<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SeoMeta;
use App\Support\PublicImageStorage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SiteSeoController extends Controller
{
    public function edit(): View
    {
        $seo = SeoMeta::query()->firstOrNew([
            'entity_type' => SeoMeta::SITE_ENTITY_TYPE,
            'entity_id' => SeoMeta::SITE_ENTITY_ID,
        ]);

        return view('admin.site-seo.edit', compact('seo'));
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'meta_title' => ['nullable', 'string', 'max:200'],
            'meta_description' => ['nullable', 'string', 'max:500'],
            'keywords' => ['nullable', 'string', 'max:500'],
            'canonical_url' => ['nullable', 'string', 'max:500'],
            'og_title' => ['nullable', 'string', 'max:200'],
            'og_description' => ['nullable', 'string', 'max:500'],
            'og_image_file' => ['nullable', 'image', 'mimes:jpeg,png,webp,gif', 'max:10240'],
            'schema_markup' => ['nullable', 'string', 'max:20000'],
            'robots' => ['nullable', 'string', 'max:120'],
        ]);

        unset($data['og_image_file']);

        $existing = SeoMeta::query()->firstWhere([
            'entity_type' => SeoMeta::SITE_ENTITY_TYPE,
            'entity_id' => SeoMeta::SITE_ENTITY_ID,
        ]);

        if ($request->hasFile('og_image_file')) {
            $path = PublicImageStorage::storeUpload($request->file('og_image_file'), 'site-seo', $existing?->og_image);
            if ($path !== null) {
                $data['og_image'] = $path;
            }
        }

        $data['entity_type'] = SeoMeta::SITE_ENTITY_TYPE;
        $data['entity_id'] = SeoMeta::SITE_ENTITY_ID;

        SeoMeta::query()->updateOrCreate(
            [
                'entity_type' => SeoMeta::SITE_ENTITY_TYPE,
                'entity_id' => SeoMeta::SITE_ENTITY_ID,
            ],
            $data
        );

        return redirect()->route('admin.site-seo.edit')->with('status', 'Site SEO saved.');
    }
}
