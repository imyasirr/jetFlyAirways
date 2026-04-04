<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use App\Support\PublicImageStorage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SiteSettingController extends Controller
{
    public function edit(): View
    {
        $setting = SiteSetting::query()->firstOrNew([]);

        return view('admin.site-settings.edit', compact('setting'));
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'topstrip_left' => ['nullable', 'string', 'max:600'],
            'support_phone' => ['nullable', 'string', 'max:80'],
            'support_email' => ['nullable', 'string', 'max:160'],
            'brand_name' => ['nullable', 'string', 'max:120'],
            'brand_tagline' => ['nullable', 'string', 'max:180'],
            'footer_about' => ['nullable', 'string', 'max:5000'],
            'footer_badges' => ['nullable', 'string', 'max:600'],
            'footer_copyright_name' => ['nullable', 'string', 'max:160'],
            'social_facebook_url' => ['nullable', 'string', 'max:500'],
            'social_instagram_url' => ['nullable', 'string', 'max:500'],
            'social_linkedin_url' => ['nullable', 'string', 'max:500'],
            'social_twitter_url' => ['nullable', 'string', 'max:500'],
            'hero_image_file' => ['nullable', 'image', 'mimes:jpeg,png,webp,gif', 'max:10240'],
            'clear_hero_image' => ['nullable', 'boolean'],
        ]);

        unset($data['hero_image_file'], $data['clear_hero_image']);

        $row = SiteSetting::query()->firstOrNew([]);
        $row->fill($data);

        if ($request->boolean('clear_hero_image')) {
            PublicImageStorage::deleteIfExists($row->hero_image);
            $row->hero_image = null;
        }

        if ($request->hasFile('hero_image_file')) {
            $path = PublicImageStorage::storeUpload($request->file('hero_image_file'), 'site-hero', $row->hero_image);
            if ($path !== null) {
                $row->hero_image = $path;
            }
        }

        $row->save();

        return redirect()->route('admin.site-settings.edit')->with('status', 'Site header & footer settings saved.');
    }
}
