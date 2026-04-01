<?php

namespace App\Http\Controllers;

use App\Models\WishlistItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class WishlistController extends Controller
{
    private const MODULES = ['flights', 'hotels', 'packages', 'buses', 'trains', 'cabs', 'visa', 'insurance'];

    public function store(Request $request, string $module, int $id): RedirectResponse
    {
        abort_unless(in_array($module, self::MODULES, true), 404);
        abort_unless(Schema::hasTable('wishlist_items'), 503);

        WishlistItem::query()->firstOrCreate([
            'user_id' => $request->user()->id,
            'module' => $module,
            'module_item_id' => $id,
        ]);

        return back()->with('status', 'Saved to your wishlist.');
    }

    public function destroy(Request $request, string $module, int $id): RedirectResponse
    {
        abort_unless(in_array($module, self::MODULES, true), 404);
        abort_unless(Schema::hasTable('wishlist_items'), 503);

        WishlistItem::query()
            ->where('user_id', $request->user()->id)
            ->where('module', $module)
            ->where('module_item_id', $id)
            ->delete();

        return back()->with('status', 'Removed from wishlist.');
    }
}
