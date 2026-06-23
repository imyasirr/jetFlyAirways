<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\WishlistItem;
use App\Services\Travel\TravelCatalogService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class ModuleController extends Controller
{
    public function __construct(private TravelCatalogService $catalog) {}

    public function index(Request $request, string $module): JsonResponse
    {
        abort_unless($this->catalog->isValidModule($module), 404);

        if (in_array($module, TravelCatalogService::ADDON_MODULES, true)) {
            if (! Schema::hasTable('travel_addons')) {
                return response()->json([
                    'module' => $module,
                    'module_info' => $this->catalog->modules[$module],
                    'items' => [],
                    'meta' => ['current_page' => 1, 'last_page' => 1, 'per_page' => 12, 'total' => 0],
                ]);
            }

            $paginator = \App\Models\TravelAddon::query()
                ->where('category', $module)
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->orderBy('id')
                ->paginate(12);

            return response()->json([
                'module' => $module,
                'module_info' => $this->catalog->modules[$module],
                'items' => $paginator->getCollection()->map(fn ($m) => $this->catalog->mapListingRow($module, $m)),
                'meta' => [
                    'current_page' => $paginator->currentPage(),
                    'last_page' => $paginator->lastPage(),
                    'per_page' => $paginator->perPage(),
                    'total' => $paginator->total(),
                ],
            ]);
        }

        if (! in_array($module, TravelCatalogService::DB_MODULES, true)) {
            return response()->json([
                'module' => $module,
                'module_info' => $this->catalog->modules[$module],
                'items' => [],
                'meta' => ['current_page' => 1, 'last_page' => 1, 'per_page' => 12, 'total' => 0],
            ]);
        }

        $query = $this->catalog->listingQuery($module);
        $this->catalog->applyListingFilters($request, $module, $query);
        $this->catalog->applyListingSort($request, $module, $query);
        $paginator = $query->paginate(12);

        $trainPnrResult = null;
        if ($module === 'trains' && $request->filled('pnr')) {
            $trainPnrResult = $this->catalog->demoTrainPnrLookup($request->string('pnr')->toString());
        }

        return response()->json([
            'module' => $module,
            'module_info' => $this->catalog->modules[$module],
            'items' => $paginator->getCollection()->map(fn ($m) => $this->catalog->mapListingRow($module, $m)),
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
            ],
            'train_pnr_result' => $trainPnrResult,
        ]);
    }

    public function show(Request $request, string $module, string $item): JsonResponse
    {
        abort_unless($this->catalog->isValidModule($module), 404);

        $model = $this->catalog->resolveActiveItem($module, $item);
        abort_if($model === null, 404);

        $inWishlist = false;
        if ($request->user() && Schema::hasTable('wishlist_items')) {
            $inWishlist = WishlistItem::query()
                ->where('user_id', $request->user()->id)
                ->where('module', $module)
                ->where('module_item_id', $model->id)
                ->exists();
        }

        return response()->json([
            'module' => $module,
            'module_info' => $this->catalog->modules[$module],
            'item' => $this->catalog->mapDetailRow($module, $model),
            'unit_price' => $this->catalog->unitPrice($module, $model),
            'in_wishlist' => $inWishlist,
            'bookable' => $this->catalog->isBookableModule($module),
        ]);
    }
}
