<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BusRoute;
use App\Models\CabService;
use App\Models\Flight;
use App\Models\Hotel;
use App\Models\TrainRoute;
use App\Models\TravelPackage;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BookingController extends Controller
{
    public function index(Request $request): View
    {
        $query = Booking::query()->latest();

        if ($request->filled('module')) {
            $query->where('module', $request->string('module'));
        }

        if ($request->filled('code')) {
            $query->where('booking_code', 'like', '%'.$request->string('code').'%');
        }

        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->string('payment_status'));
        }

        $bookings = $query->paginate(25)->withQueryString();

        return view('admin.bookings.index', compact('bookings'));
    }

    public function show(Booking $booking): View
    {
        return view('admin.bookings.show', [
            'booking' => $booking,
            'itemTitle' => $this->resolveItemTitle($booking),
        ]);
    }

    private function resolveItemTitle(Booking $booking): string
    {
        $id = $booking->module_item_id;

        return match ($booking->module) {
            'flights' => ($m = Flight::find($id))
                ? $m->airline.' '.$m->flight_number.' ('.$m->from_city.' → '.$m->to_city.')'
                : 'Flight #'.$id,
            'hotels' => ($m = Hotel::find($id))
                ? $m->name.' · '.$m->city
                : 'Hotel #'.$id,
            'packages' => ($m = TravelPackage::find($id))
                ? $m->name.' · '.$m->destination
                : 'Package #'.$id,
            'buses' => ($m = BusRoute::find($id))
                ? $m->operator_name.' ('.$m->from_city.' → '.$m->to_city.')'
                : 'Bus #'.$id,
            'trains' => ($m = TrainRoute::find($id))
                ? $m->train_name.' ('.$m->train_number.')'
                : 'Train #'.$id,
            'cabs' => ($m = CabService::find($id))
                ? $m->service_type.' · '.$m->from_location
                : 'Cab #'.$id,
            default => 'Item #'.$id,
        };
    }
}
