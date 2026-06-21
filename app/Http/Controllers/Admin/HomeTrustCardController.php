<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomeTrustCard;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeTrustCardController extends Controller
{
    public function index(): View
    {
        $cards = HomeTrustCard::query()->orderBy('sort_order')->orderBy('id')->get();

        return view('admin.home-trust-cards.index', compact('cards'));
    }

    public function create(): View
    {
        return view('admin.home-trust-cards.create');
    }

    public function store(Request $request): RedirectResponse
    {
        HomeTrustCard::create($this->validated($request));

        return redirect()->route('admin.home-trust-cards.index')->with('status', 'Trust card created.');
    }

    public function edit(HomeTrustCard $home_trust_card): View
    {
        return view('admin.home-trust-cards.edit', ['card' => $home_trust_card]);
    }

    public function update(Request $request, HomeTrustCard $home_trust_card): RedirectResponse
    {
        $home_trust_card->update($this->validated($request));

        return redirect()->route('admin.home-trust-cards.index')->with('status', 'Trust card updated.');
    }

    public function destroy(HomeTrustCard $home_trust_card): RedirectResponse
    {
        $home_trust_card->delete();

        return redirect()->route('admin.home-trust-cards.index')->with('status', 'Trust card deleted.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validated(Request $request): array
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:120'],
            'description' => ['required', 'string', 'max:600'],
            'icon' => ['nullable', 'string', 'max:80', 'regex:/^[a-z0-9_]+$/i'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
            'is_active' => ['boolean'],
        ]);
        $data['is_active'] = $request->boolean('is_active');
        $data['icon'] = filled($data['icon'] ?? null) ? strtolower((string) $data['icon']) : 'verified';
        $data['sort_order'] = (int) ($data['sort_order'] ?? 10);

        return $data;
    }
}
