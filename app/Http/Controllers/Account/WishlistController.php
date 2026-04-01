<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\BusRoute;
use App\Models\CabService;
use App\Models\Flight;
use App\Models\Hotel;
use App\Models\TrainRoute;
use App\Models\TravelAddon;
use App\Models\TravelPackage;
use App\Models\WishlistItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class WishlistController extends Controller
{
    public function index(Request $request): View
    {
        $rows = collect();
        if (Schema::hasTable('wishlist_items')) {
            $items = WishlistItem::query()
                ->where('user_id', $request->user()->id)
                ->orderByDesc('id')
                ->get();

            $rows = $items->map(fn (WishlistItem $w) => $this->mapWishlistRow($w));
        }

        return view('account.wishlist.index', compact('rows'));
    }

    /**
     * @return array{module: string, module_item_id: int, title: string, url: string|null}
     */
    private function mapWishlistRow(WishlistItem $w): array
    {
        $model = match ($w->module) {
            'flights' => Flight::query()->whereKey($w->module_item_id)->where('is_active', true)->first(),
            'hotels' => Hotel::query()->whereKey($w->module_item_id)->where('is_active', true)->first(),
            'packages' => TravelPackage::query()->whereKey($w->module_item_id)->where('is_published', true)->first(),
            'buses' => BusRoute::query()->whereKey($w->module_item_id)->where('is_active', true)->first(),
            'trains' => TrainRoute::query()->whereKey($w->module_item_id)->where('is_active', true)->first(),
            'cabs' => CabService::query()->whereKey($w->module_item_id)->where('is_active', true)->first(),
            'visa', 'insurance' => TravelAddon::query()
                ->where('category', $w->module)
                ->whereKey($w->module_item_id)
                ->where('is_active', true)
                ->first(),
            default => null,
        };

        $title = ucfirst($w->module).' #'.$w->module_item_id;
        $url = null;

        if ($model !== null) {
            $url = route('module.show', ['module' => $w->module, 'id' => $w->module_item_id]);
            $title = match ($w->module) {
                'flights' => $model->airline.' '.$model->flight_number,
                'hotels' => $model->name,
                'packages' => $model->name,
                'buses' => $model->operator_name.' · '.$model->from_city.' → '.$model->to_city,
                'trains' => $model->train_number.' · '.$model->from_city.' → '.$model->to_city,
                'cabs' => $model->service_type.' · '.$model->from_location,
                'visa', 'insurance' => $model->name,
                default => $title,
            };
        }

        return [
            'module' => $w->module,
            'module_item_id' => (int) $w->module_item_id,
            'title' => $title,
            'url' => $url,
        ];
    }
}
