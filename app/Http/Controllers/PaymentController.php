<?php

namespace App\Http\Controllers;

use App\Contracts\GdsBookingClient;
use App\Mail\BookingPaidMail;
use App\Models\Booking;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\View\View;
use Razorpay\Api\Api;
use Razorpay\Api\Errors\SignatureVerificationError;

class PaymentController extends Controller
{
    public function checkout(Request $request, Booking $booking): View|RedirectResponse
    {
        if ($booking->payment_status === 'paid') {
            return redirect()->to(URL::temporarySignedRoute('booking.thanks', now()->addHour(), ['booking' => $booking->id]));
        }

        $key = config('services.razorpay.key');
        $secret = config('services.razorpay.secret');
        if (! $key || ! $secret) {
            abort(503, 'Payment gateway is not configured.');
        }

        $api = new Api($key, $secret);
        $amountPaise = (int) round((float) $booking->total_amount * 100);
        if ($amountPaise < 100) {
            $amountPaise = 100;
        }

        $order = $api->order->create([
            'receipt' => $booking->booking_code,
            'amount' => $amountPaise,
            'currency' => 'INR',
            'payment_capture' => 1,
        ]);

        $booking->update(['razorpay_order_id' => $order['id']]);

        return view('payments.checkout', [
            'booking' => $booking,
            'razorpayKey' => $key,
            'razorpayOrderId' => $order['id'],
            'amountPaise' => $amountPaise,
            'prefillName' => $booking->contact_name ?? 'Guest',
            'prefillEmail' => $booking->contact_email ?? '',
            'prefillPhone' => $booking->contact_phone ?? '',
        ]);
    }

    public function verify(Request $request, GdsBookingClient $gds): RedirectResponse
    {
        $data = $request->validate([
            'booking_id' => ['required', 'integer', 'exists:bookings,id'],
            'razorpay_order_id' => ['required', 'string'],
            'razorpay_payment_id' => ['required', 'string'],
            'razorpay_signature' => ['required', 'string'],
        ]);

        $booking = Booking::query()->findOrFail($data['booking_id']);

        if ($booking->payment_status === 'paid') {
            return redirect()->to(URL::temporarySignedRoute('booking.thanks', now()->addHour(), ['booking' => $booking->id]));
        }

        if ($booking->razorpay_order_id !== $data['razorpay_order_id']) {
            return redirect()->route('home')->with('error', 'Invalid payment session.');
        }

        $key = config('services.razorpay.key');
        $secret = config('services.razorpay.secret');
        if (! $key || ! $secret) {
            return redirect()->route('home')->with('error', 'Payment gateway not configured.');
        }

        try {
            $api = new Api($key, $secret);
            $api->utility->verifyPaymentSignature([
                'razorpay_order_id' => $data['razorpay_order_id'],
                'razorpay_payment_id' => $data['razorpay_payment_id'],
                'razorpay_signature' => $data['razorpay_signature'],
            ]);
        } catch (SignatureVerificationError) {
            return redirect()->route('home')->with('error', 'Payment verification failed.');
        }

        $booking->update([
            'payment_status' => 'paid',
            'razorpay_payment_id' => $data['razorpay_payment_id'],
        ]);

        try {
            $gds->recordPaymentCaptured($booking);
        } catch (\Throwable) {
            //
        }

        if ($booking->contact_email && filter_var($booking->contact_email, FILTER_VALIDATE_EMAIL)) {
            try {
                Mail::to($booking->contact_email)->send(new BookingPaidMail($booking));
            } catch (\Throwable) {
                //
            }
        }

        return redirect()->to(URL::temporarySignedRoute('booking.thanks', now()->addHour(), ['booking' => $booking->id]))
            ->with('status', 'Payment successful. Your booking is confirmed.');
    }
}
