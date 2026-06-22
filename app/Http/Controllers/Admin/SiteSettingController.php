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
            'support_emails' => ['nullable', 'array'],
            'support_emails.*.label' => ['nullable', 'string', 'max:80'],
            'support_emails.*.email' => ['nullable', 'email', 'max:160'],
            'support_phones' => ['nullable', 'array'],
            'support_phones.*.label' => ['nullable', 'string', 'max:80'],
            'support_phones.*.phone' => ['nullable', 'string', 'max:80'],
            'office_addresses' => ['nullable', 'array'],
            'office_addresses.*.label' => ['nullable', 'string', 'max:120'],
            'office_addresses.*.address' => ['nullable', 'string', 'max:2000'],
            'brand_name' => ['nullable', 'string', 'max:120'],
            'brand_tagline' => ['nullable', 'string', 'max:180'],
            'logo_image_file' => ['nullable', 'image', 'mimes:jpeg,png,webp,gif', 'max:4096'],
            'clear_logo_image' => ['nullable', 'boolean'],
            'footer_about' => ['nullable', 'string', 'max:5000'],
            'footer_badges' => ['nullable', 'string', 'max:600'],
            'footer_copyright_name' => ['nullable', 'string', 'max:160'],
            'social_facebook_url' => ['nullable', 'string', 'max:500'],
            'social_instagram_url' => ['nullable', 'string', 'max:500'],
            'social_linkedin_url' => ['nullable', 'string', 'max:500'],
            'social_twitter_url' => ['nullable', 'string', 'max:500'],
            'live_chat_url' => ['nullable', 'string', 'max:500'],
            'tawk_property_id' => ['nullable', 'string', 'max:64'],
            'tawk_widget_id' => ['nullable', 'string', 'max:64'],
            'tawk_embed_url' => ['nullable', 'string', 'max:500'],
            'hero_image_file' => ['nullable', 'image', 'mimes:jpeg,png,webp,gif', 'max:10240'],
            'clear_hero_image' => ['nullable', 'boolean'],
        ]);

        unset($data['logo_image_file'], $data['clear_logo_image'], $data['hero_image_file'], $data['clear_hero_image'], $data['tawk_embed_url']);

        if (filled($request->input('tawk_embed_url'))) {
            if (preg_match('#embed\.tawk\.to/([A-Za-z0-9]+)/([A-Za-z0-9]+)#', (string) $request->input('tawk_embed_url'), $matches)) {
                $data['tawk_property_id'] = $matches[1];
                $data['tawk_widget_id'] = $matches[2];
            }
        }

        if (! filled($data['tawk_property_id'] ?? null)) {
            $data['tawk_property_id'] = null;
        }
        if (! filled($data['tawk_widget_id'] ?? null)) {
            $data['tawk_widget_id'] = null;
        }

        $supportEmails = $this->normalizeContactRows($data['support_emails'] ?? [], 'email', 'Support');
        $supportPhones = $this->normalizeContactRows($data['support_phones'] ?? [], 'phone', 'Support');
        $officeAddresses = $this->normalizeContactRows($data['office_addresses'] ?? [], 'address', 'Registered Office');

        $data['support_emails'] = $supportEmails;
        $data['support_phones'] = $supportPhones;
        $data['office_addresses'] = $officeAddresses;
        $data['support_email'] = $supportEmails[0]['email'] ?? null;
        $data['support_phone'] = $supportPhones[0]['phone'] ?? null;
        $data['office_address_label'] = $officeAddresses[0]['label'] ?? null;
        $data['office_address'] = $officeAddresses[0]['address'] ?? null;

        $row = SiteSetting::query()->firstOrNew([]);
        $row->fill($data);

        if ($request->boolean('clear_logo_image')) {
            PublicImageStorage::deleteIfExists($row->logo_image);
            $row->logo_image = null;
        }

        if ($request->hasFile('logo_image_file')) {
            $path = PublicImageStorage::storeUpload($request->file('logo_image_file'), 'site-logo', $row->logo_image);
            if ($path !== null) {
                $row->logo_image = $path;
            }
        }

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

    /** @return array<int, array<string, string>> */
    private function normalizeContactRows(array $rows, string $valueKey, string $defaultLabel): array
    {
        return collect($rows)
            ->map(fn (array $row) => [
                'label' => trim((string) ($row['label'] ?? '')) ?: $defaultLabel,
                $valueKey => trim((string) ($row[$valueKey] ?? '')),
            ])
            ->filter(fn (array $row) => filled($row[$valueKey]))
            ->values()
            ->all();
    }
}
