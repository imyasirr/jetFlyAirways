<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        return view('admin.dashboard', [
            'stats' => [
                ['key' => 'flights', 'label' => 'Flights', 'count' => DB::table('flights')->count(), 'route' => 'admin.flights.index', 'icon' => '✈'],
                ['key' => 'hotels', 'label' => 'Hotels', 'count' => DB::table('hotels')->count(), 'route' => 'admin.hotels.index', 'icon' => '🏨'],
                ['key' => 'packages', 'label' => 'Packages', 'count' => DB::table('travel_packages')->count(), 'route' => 'admin.travel-packages.index', 'icon' => '🌍'],
                ['key' => 'buses', 'label' => 'Bus routes', 'count' => DB::table('bus_routes')->count(), 'route' => 'admin.bus-routes.index', 'icon' => '🚌'],
                ['key' => 'trains', 'label' => 'Train routes', 'count' => DB::table('train_routes')->count(), 'route' => 'admin.train-routes.index', 'icon' => '🚆'],
                ['key' => 'cabs', 'label' => 'Cab services', 'count' => DB::table('cab_services')->count(), 'route' => 'admin.cab-services.index', 'icon' => '🚖'],
                ['key' => 'bookings', 'label' => 'Bookings', 'count' => DB::table('bookings')->count(), 'route' => 'admin.bookings.index', 'icon' => '📋'],
                ['key' => 'users', 'label' => 'Users', 'count' => DB::table('users')->count(), 'route' => null, 'icon' => '👤'],
                ['key' => 'blogs', 'label' => 'Blogs', 'count' => DB::table('blogs')->count(), 'route' => null, 'icon' => '📝'],
            ],
            'recentBookings' => DB::table('bookings')->orderByDesc('id')->limit(8)->get(),
        ]);
    }
}
