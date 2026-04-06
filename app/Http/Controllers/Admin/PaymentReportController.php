<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PaymentReportController extends Controller
{
    public function index(Request $request): View
    {
        $from = $request->date('from');
        $to = $request->date('to');

        $query = Booking::query()->orderByDesc('id');
        if ($from) {
            $query->whereDate('created_at', '>=', $from);
        }
        if ($to) {
            $query->whereDate('created_at', '<=', $to);
        }

        $rows = $query->paginate(20)->withQueryString();

        $totals = [
            'paid' => (clone $query)->where('payment_status', 'paid')->sum('total_amount'),
            'pending' => (clone $query)->where('payment_status', 'pending')->sum('total_amount'),
            'refund_initiated' => (clone $query)->where('payment_status', 'refund_initiated')->sum('total_amount'),
        ];

        return view('admin.reports.payments', compact('rows', 'totals', 'from', 'to'));
    }
}

