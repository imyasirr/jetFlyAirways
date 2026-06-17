<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DestinationGuideFeature;
use App\Models\DestinationGuideSetting;
use App\Models\DestinationGuideSpot;
use App\Models\DestinationGuideTip;
use App\Support\PublicImageStorage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DestinationGuideController extends Controller
{
    public function edit(): View
    {
        return view('admin.destination-guide.edit', [
            'settings' => DestinationGuideSetting::current(),
            'features' => DestinationGuideFeature::query()->orderBy('sort_order')->orderBy('id')->get(),
            'spots' => DestinationGuideSpot::query()->orderBy('sort_order')->orderBy('id')->get(),
            'tips' => DestinationGuideTip::query()->orderBy('sort_order')->orderBy('id')->get(),
        ]);
    }

    public function updateSettings(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'intro' => ['nullable', 'string', 'max:5000'],
            'spots_heading' => ['required', 'string', 'max:160'],
            'spots_subheading' => ['nullable', 'string', 'max:1000'],
            'tips_heading' => ['required', 'string', 'max:160'],
            'callout_title' => ['nullable', 'string', 'max:120'],
            'callout_body' => ['nullable', 'string', 'max:2000'],
            'callout_link' => ['nullable', 'string', 'max:500'],
            'callout_link_label' => ['nullable', 'string', 'max:120'],
        ]);

        DestinationGuideSetting::current()->update($data);

        return back()->with('status', 'Destination guide settings saved.');
    }

    public function storeFeature(Request $request): RedirectResponse
    {
        DestinationGuideFeature::create($this->validatedFeature($request));

        return back()->with('status', 'Feature added.');
    }

    public function updateFeature(Request $request, DestinationGuideFeature $feature): RedirectResponse
    {
        $feature->update($this->validatedFeature($request));

        return back()->with('status', 'Feature updated.');
    }

    public function destroyFeature(DestinationGuideFeature $feature): RedirectResponse
    {
        $feature->delete();

        return back()->with('status', 'Feature removed.');
    }

    public function storeSpot(Request $request): RedirectResponse
    {
        DestinationGuideSpot::create($this->validatedSpot($request));

        return back()->with('status', 'Destination added.');
    }

    public function updateSpot(Request $request, DestinationGuideSpot $spot): RedirectResponse
    {
        $spot->update($this->validatedSpot($request, $spot));

        return back()->with('status', 'Destination updated.');
    }

    public function destroySpot(DestinationGuideSpot $spot): RedirectResponse
    {
        $spot->delete();

        return back()->with('status', 'Destination removed.');
    }

    public function storeTip(Request $request): RedirectResponse
    {
        DestinationGuideTip::create($this->validatedTip($request));

        return back()->with('status', 'Tip added.');
    }

    public function updateTip(Request $request, DestinationGuideTip $tip): RedirectResponse
    {
        $tip->update($this->validatedTip($request));

        return back()->with('status', 'Tip updated.');
    }

    public function destroyTip(DestinationGuideTip $tip): RedirectResponse
    {
        $tip->delete();

        return back()->with('status', 'Tip removed.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validatedFeature(Request $request): array
    {
        $data = $request->validate([
            'icon' => ['nullable', 'string', 'max:40'],
            'title' => ['required', 'string', 'max:120'],
            'body' => ['required', 'string', 'max:2000'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:99999'],
            'is_active' => ['boolean'],
        ]);
        $data['is_active'] = $request->boolean('is_active', true);
        $data['sort_order'] = (int) ($data['sort_order'] ?? 0);

        return $data;
    }

    /**
     * @return array<string, mixed>
     */
    private function validatedSpot(Request $request, ?DestinationGuideSpot $spot = null): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:160'],
            'tag_line' => ['nullable', 'string', 'max:120'],
            'best_season' => ['nullable', 'string', 'max:120'],
            'image' => ['nullable', 'string', 'max:2000'],
            'image_file' => ['nullable', 'image', 'mimes:jpeg,png,webp,gif', 'max:10240'],
            'package_destination' => ['nullable', 'string', 'max:120'],
            'link_url' => ['nullable', 'string', 'max:500'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:99999'],
            'is_active' => ['boolean'],
        ]);
        $data['is_active'] = $request->boolean('is_active', true);
        $data['sort_order'] = (int) ($data['sort_order'] ?? 0);

        unset($data['image_file']);
        if ($request->hasFile('image_file')) {
            $path = PublicImageStorage::storeUpload($request->file('image_file'), 'destination-guide', $spot?->image);
            abort_if($path === null, 500, 'Image upload failed.');
            $data['image'] = $path;
        } elseif (! filled($data['image'] ?? null) && $spot) {
            unset($data['image']);
        }

        return $data;
    }

    /**
     * @return array<string, mixed>
     */
    private function validatedTip(Request $request): array
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:160'],
            'body' => ['nullable', 'string', 'max:1000'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:99999'],
            'is_active' => ['boolean'],
        ]);
        $data['is_active'] = $request->boolean('is_active', true);
        $data['sort_order'] = (int) ($data['sort_order'] ?? 0);

        return $data;
    }
}
