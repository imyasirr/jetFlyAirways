<?php

namespace App\Http\Controllers;

use App\Models\DestinationGuideFeature;
use App\Models\DestinationGuideSetting;
use App\Models\DestinationGuideSpot;
use App\Models\DestinationGuideTip;
use App\Models\Page;
use Illuminate\View\View;

class DestinationGuideController extends Controller
{
    public function show(): View
    {
        $page = Page::query()
            ->where('slug', 'destination-guide')
            ->where('is_active', true)
            ->firstOrFail();

        $settings = DestinationGuideSetting::current();
        $features = DestinationGuideFeature::query()->active()->orderBy('sort_order')->orderBy('id')->get();
        $spots = DestinationGuideSpot::query()->active()->orderBy('sort_order')->orderBy('id')->get();
        $tips = DestinationGuideTip::query()->active()->orderBy('sort_order')->orderBy('id')->get();

        return view('destination-guide.show', compact('page', 'settings', 'features', 'spots', 'tips'));
    }
}
