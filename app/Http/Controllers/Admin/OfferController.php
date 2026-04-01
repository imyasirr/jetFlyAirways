<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Offer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OfferController extends Controller
{
    public function index(): View
    {
        $offers = Offer::query()->orderByDesc('id')->paginate(20);

        return view('admin.offers.index', compact('offers'));
    }

    public function create(): View
    {
        return view('admin.offers.create');
    }

    public function store(Request $request): RedirectResponse
    {
        Offer::create($this->validated($request));

        return redirect()->route('admin.offers.index')->with('status', 'Offer created.');
    }

    public function edit(Offer $offer): View
    {
        return view('admin.offers.edit', compact('offer'));
    }

    public function update(Request $request, Offer $offer): RedirectResponse
    {
        $offer->update($this->validated($request));

        return redirect()->route('admin.offers.index')->with('status', 'Offer updated.');
    }

    public function destroy(Offer $offer): RedirectResponse
    {
        $offer->delete();

        return redirect()->route('admin.offers.index')->with('status', 'Offer deleted.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validated(Request $request): array
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:200'],
            'description' => ['nullable', 'string', 'max:2000'],
            'redirect_url' => ['nullable', 'string', 'max:500'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'is_active' => ['boolean'],
        ]);

        $data['is_active'] = $request->boolean('is_active');

        return $data;
    }
}
