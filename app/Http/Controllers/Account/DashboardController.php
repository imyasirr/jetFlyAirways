<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();

        $bookings = $user->bookings()->orderByDesc('id')->limit(5)->get();

        $stats = [
            'total_bookings' => $user->bookings()->count(),
            'upcoming' => $user->bookings()->where('travel_date', '>=', now()->toDateString())->count(),
            'total_spent' => (float) $user->bookings()->sum('total_amount'),
        ];

        $activeCoupons = Coupon::query()->where('is_active', true)->count();

        $referralsCount = $user->referredUsers()->count();
        $referralShareUrl = $user->referral_code
            ? route('register', ['ref' => $user->referral_code], absolute: true)
            : null;

        return view('account.dashboard', [
            'bookings' => $bookings,
            'stats' => $stats,
            'activeCoupons' => $activeCoupons,
            'referralsCount' => $referralsCount,
            'referralShareUrl' => $referralShareUrl,
        ]);
    }
}
