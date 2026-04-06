<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class BookingReportController extends Controller
{
    public function index(): View
    {
        $byModule = Booking::query()
            ->select('module', DB::raw('count(*) as total'))
            ->groupBy('module')
            ->orderByDesc('total')
            ->get();

        $byStatus = Booking::query()
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->orderByDesc('total')
            ->get();

        $latest = Booking::query()->orderByDesc('id')->limit(20)->get();

        return view('admin.reports.bookings', compact('byModule', 'byStatus', 'latest'));
    }
}

