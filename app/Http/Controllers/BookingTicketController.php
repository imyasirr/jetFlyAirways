<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class BookingTicketController extends Controller
{
    public function pdf(Request $request, Booking $booking): Response
    {
        if (! $request->hasValidSignature()) {
            if (! Auth::check() || Auth::id() !== $booking->user_id) {
                abort(403);
            }
        }

        if ($booking->payment_status !== 'paid') {
            abort(403, 'Ticket available after payment is complete.');
        }

        $html = view('pdf.booking-ticket', ['booking' => $booking])->render();

        $options = new Options();
        $options->set('defaultFont', 'DejaVu Sans');
        $options->set('isRemoteEnabled', true);

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $filename = 'JetFly-'.$booking->booking_code.'.pdf';

        return response($dompdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ]);
    }
}
