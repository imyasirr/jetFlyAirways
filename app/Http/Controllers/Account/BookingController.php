<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\View\View;

class BookingController extends Controller
{
    public function index(Request $request): View
    {
        $bookings = $request->user()
            ->bookings()
            ->orderByDesc('travel_date')
            ->paginate(12)
            ->withQueryString();

        return view('account.bookings.index', compact('bookings'));
    }

    public function show(Request $request, Booking $booking): View
    {
        abort_unless($booking->user_id === $request->user()->id, 403);

        $ticketPdfUrl = $booking->payment_status === 'paid'
            ? URL::temporarySignedRoute('bookings.ticket.pdf', now()->addHours(48), ['booking' => $booking->id])
            : null;

        return view('account.bookings.show', compact('booking', 'ticketPdfUrl'));
    }
}
