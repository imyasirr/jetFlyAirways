<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MenuItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MenuItemController extends Controller
{
    public function index(): View
    {
        $items = MenuItem::query()
            ->with('parent')
            ->orderBy('location')
            ->orderBy('parent_id')
            ->orderBy('sort_order')
            ->paginate(40);

        return view('admin.menu-items.index', compact('items'));
    }

    public function create(): View
    {
        return view('admin.menu-items.create', [
            'parents' => MenuItem::query()->orderBy('label')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        MenuItem::create($this->validated($request));

        return redirect()->route('admin.menu-items.index')->with('status', 'Menu item created.');
    }

    public function edit(MenuItem $menu_item): View
    {
        return view('admin.menu-items.edit', [
            'menuItem' => $menu_item,
            'parents' => MenuItem::query()->where('id', '!=', $menu_item->id)->orderBy('label')->get(),
        ]);
    }

    public function update(Request $request, MenuItem $menu_item): RedirectResponse
    {
        $data = $this->validated($request);
        if (! empty($data['parent_id']) && (int) $data['parent_id'] === (int) $menu_item->id) {
            return back()->withErrors(['parent_id' => 'Item cannot be its own parent.'])->withInput();
        }
        $menu_item->update($data);

        return redirect()->route('admin.menu-items.index')->with('status', 'Menu item updated.');
    }

    public function destroy(MenuItem $menu_item): RedirectResponse
    {
        $menu_item->delete();

        return redirect()->route('admin.menu-items.index')->with('status', 'Menu item removed.');
    }

    private function validated(Request $request): array
    {
        $rules = [
            'parent_id' => ['nullable', 'integer', 'exists:menu_items,id'],
            'location' => ['required', 'in:header,footer'],
            'label' => ['required', 'string', 'max:120'],
            'href' => ['nullable', 'string', 'max:500'],
            'sort_order' => ['required', 'integer', 'min:0', 'max:65535'],
            'is_active' => ['boolean'],
            'open_new_tab' => ['boolean'],
            'requires_auth' => ['boolean'],
        ];

        return $request->validate($rules) + [
            'is_active' => $request->boolean('is_active'),
            'open_new_tab' => $request->boolean('open_new_tab'),
            'requires_auth' => $request->boolean('requires_auth'),
        ];
    }
}
