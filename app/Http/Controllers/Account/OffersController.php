<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\View\View;

class OffersController extends Controller
{
    public function index(): View
    {
        $coupons = Coupon::query()
            ->where('is_active', true)
            ->orderByDesc('id')
            ->get();

        return view('account.offers', [
            'coupons' => $coupons,
        ]);
    }
}
