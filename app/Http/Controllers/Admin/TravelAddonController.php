<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TravelAddon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TravelAddonController extends Controller
{
    public function index(Request $request): View
    {
        $query = TravelAddon::query()->orderBy('category')->orderBy('sort_order')->orderByDesc('id');
        if ($request->filled('category') && in_array($request->string('category'), TravelAddon::categories(), true)) {
            $query->where('category', $request->string('category'));
        }
        $addons = $query->paginate(24)->withQueryString();

        return view('admin.travel-addons.index', compact('addons'));
    }

    public function create(): View
    {
        return view('admin.travel-addons.create');
    }

    public function store(Request $request): RedirectResponse
    {
        TravelAddon::create($this->validated($request));

        return redirect()->route('admin.travel-addons.index')->with('status', 'Add-on created.');
    }

    public function edit(TravelAddon $travel_addon): View
    {
        return view('admin.travel-addons.edit', ['addon' => $travel_addon]);
    }

    public function update(Request $request, TravelAddon $travel_addon): RedirectResponse
    {
        $travel_addon->update($this->validated($request));

        return redirect()->route('admin.travel-addons.index')->with('status', 'Add-on updated.');
    }

    public function destroy(TravelAddon $travel_addon): RedirectResponse
    {
        $travel_addon->delete();

        return redirect()->route('admin.travel-addons.index')->with('status', 'Add-on deleted.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validated(Request $request): array
    {
        $data = $request->validate([
            'category' => ['required', 'string', 'in:visa,insurance'],
            'name' => ['required', 'string', 'max:200'],
            'summary' => ['nullable', 'string', 'max:500'],
            'description' => ['nullable', 'string', 'max:10000'],
            'price' => ['required', 'numeric', 'min:0', 'max:99999999'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:99999'],
            'is_active' => ['boolean'],
        ]);
        $data['is_active'] = $request->boolean('is_active');
        $data['sort_order'] = $data['sort_order'] ?? 0;

        return $data;
    }
}
