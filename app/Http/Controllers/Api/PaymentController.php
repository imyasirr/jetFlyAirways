<?php

namespace App\Http\Controllers\Api;

use App\Contracts\GdsBookingClient;
use App\Http\Controllers\Controller;
use App\Mail\BookingPaidMail;
use App\Models\Booking;
use App\Models\Coupon;
use App\Support\PaymentGatewaySettings;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Razorpay\Api\Api;
use Razorpay\Api\Errors\SignatureVerificationError;

class PaymentController extends Controller
{
    public function createOrder(Request $request, Booking $booking): JsonResponse
    {
        if ($booking->payment_status === 'paid') {
            return response()->json(['message' => 'Booking is already paid.'], 422);
        }

        if ($request->user() && $booking->user_id && $booking->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        $key = PaymentGatewaySettings::key();
        $secret = PaymentGatewaySettings::secret();
        if (! PaymentGatewaySettings::isConfigured() || ! $key || ! $secret) {
            return response()->json(['message' => 'Payment gateway is not configured.'], 503);
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

        return response()->json([
            'booking_id' => $booking->id,
            'razorpay_key' => $key,
            'razorpay_order_id' => $order['id'],
            'amount_paise' => $amountPaise,
            'currency' => 'INR',
            'prefill' => [
                'name' => $booking->contact_name ?? 'Guest',
                'email' => $booking->contact_email ?? '',
                'phone' => $booking->contact_phone ?? '',
            ],
        ]);
    }

    public function verify(Request $request, GdsBookingClient $gds): JsonResponse
    {
        $data = $request->validate([
            'booking_id' => ['required', 'integer', 'exists:bookings,id'],
            'razorpay_order_id' => ['required', 'string'],
            'razorpay_payment_id' => ['required', 'string'],
            'razorpay_signature' => ['required', 'string'],
        ]);

        $booking = Booking::query()->findOrFail($data['booking_id']);

        if ($request->user() && $booking->user_id && $booking->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        if ($booking->payment_status === 'paid') {
            return response()->json([
                'message' => 'Payment already verified.',
                'booking' => app(BookingController::class)->bookingPayload($booking),
            ]);
        }

        if ($booking->razorpay_order_id !== $data['razorpay_order_id']) {
            return response()->json(['message' => 'Invalid payment session.'], 422);
        }

        $key = PaymentGatewaySettings::key();
        $secret = PaymentGatewaySettings::secret();
        if (! PaymentGatewaySettings::isConfigured() || ! $key || ! $secret) {
            return response()->json(['message' => 'Payment gateway not configured.'], 503);
        }

        try {
            $api = new Api($key, $secret);
            $api->utility->verifyPaymentSignature([
                'razorpay_order_id' => $data['razorpay_order_id'],
                'razorpay_payment_id' => $data['razorpay_payment_id'],
                'razorpay_signature' => $data['razorpay_signature'],
            ]);
        } catch (SignatureVerificationError) {
            return response()->json(['message' => 'Payment verification failed.'], 422);
        }

        $booking->update([
            'payment_status' => 'paid',
            'razorpay_payment_id' => $data['razorpay_payment_id'],
        ]);

        if (filled($booking->coupon_code)) {
            Coupon::query()
                ->whereRaw('UPPER(code) = ?', [strtoupper($booking->coupon_code)])
                ->increment('used_count');
        }

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

        return response()->json([
            'message' => 'Payment successful. Your booking is confirmed.',
            'booking' => app(BookingController::class)->bookingPayload($booking->fresh()),
        ]);
    }
}
