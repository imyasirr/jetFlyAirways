<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RefundController extends Controller
{
    public function index(Request $request): View
    {
        $refundBookings = $request->user()
            ->bookings()
            ->where(function ($query) {
                $query
                    ->whereIn('payment_status', ['refund_initiated', 'refund_processed'])
                    ->orWhere('status', 'cancelled');
            })
            ->orderByDesc('updated_at')
            ->paginate(12)
            ->withQueryString();

        return view('account.refunds.index', compact('refundBookings'));
    }
}

