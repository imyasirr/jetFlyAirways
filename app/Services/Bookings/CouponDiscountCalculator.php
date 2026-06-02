<?php

namespace App\Services\Bookings;

use App\Models\Coupon;

class CouponDiscountCalculator
{
    /**
     * @return array{subtotal: float, discount: float, total: float, coupon: ?Coupon, error: ?string}
     */
    public function apply(?string $code, float $subtotal): array
    {
        $subtotal = max(0, round($subtotal, 2));

        if ($code === null || trim($code) === '') {
            return [
                'subtotal' => $subtotal,
                'discount' => 0.0,
                'total' => $subtotal,
                'coupon' => null,
                'error' => null,
            ];
        }

        $normalized = strtoupper(trim($code));
        $coupon = Coupon::query()->whereRaw('UPPER(code) = ?', [$normalized])->first();

        if ($coupon === null || ! $coupon->isCurrentlyValid()) {
            return [
                'subtotal' => $subtotal,
                'discount' => 0.0,
                'total' => $subtotal,
                'coupon' => null,
                'error' => 'This coupon code is not valid or has expired.',
            ];
        }

        $discount = 0.0;
        if ($coupon->discount_type === 'percent') {
            $discount = round($subtotal * ((float) $coupon->discount_value / 100), 2);
        } else {
            $discount = round(min((float) $coupon->discount_value, $subtotal), 2);
        }

        $total = round(max(0.0, $subtotal - $discount), 2);
        if ($total > 0 && $total < 1) {
            $total = 1.0;
        }

        return [
            'subtotal' => $subtotal,
            'discount' => $discount,
            'total' => $total,
            'coupon' => $coupon,
            'error' => null,
        ];
    }
}
