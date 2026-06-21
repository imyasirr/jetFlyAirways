<?php

namespace App\Support;

use App\Models\ApiIntegration;
use Illuminate\Support\Facades\Schema;

final class PaymentGatewaySettings
{
    /**
     * @return array{key: ?string, secret: ?string, source: string, provider: string}
     */
    public static function credentials(): array
    {
        $fromAdmin = self::fromAdmin();
        if ($fromAdmin !== null) {
            return $fromAdmin;
        }

        return [
            'key' => self::normalize(config('services.razorpay.key')),
            'secret' => self::normalize(config('services.razorpay.secret')),
            'source' => 'env',
            'provider' => 'razorpay',
        ];
    }

    public static function isConfigured(): bool
    {
        $credentials = self::credentials();

        return filled($credentials['key']) && filled($credentials['secret']);
    }

    public static function key(): ?string
    {
        return self::credentials()['key'];
    }

    public static function secret(): ?string
    {
        return self::credentials()['secret'];
    }

    public static function sourceLabel(): string
    {
        return match (self::credentials()['source']) {
            'admin' => 'Admin panel',
            default => '.env file',
        };
    }

    /**
     * @return array{configured: bool, source: string, provider: string}
     */
    public static function statusSummary(): array
    {
        $credentials = self::credentials();

        return [
            'configured' => filled($credentials['key']) && filled($credentials['secret']),
            'source' => $credentials['source'],
            'provider' => $credentials['provider'],
        ];
    }

    /**
     * @return array{key: string, secret: string, source: string, provider: string}|null
     */
    private static function fromAdmin(): ?array
    {
        if (! Schema::hasTable('api_integrations')) {
            return null;
        }

        $row = ApiIntegration::query()
            ->where('service', 'payment_gateway')
            ->where('is_active', true)
            ->first();

        if (! $row || ! filled($row->api_key) || ! filled($row->api_secret)) {
            return null;
        }

        return [
            'key' => self::normalize($row->api_key),
            'secret' => self::normalize($row->api_secret),
            'source' => 'admin',
            'provider' => 'razorpay',
        ];
    }

    private static function normalize(mixed $value): ?string
    {
        if (! is_string($value)) {
            return null;
        }

        $value = trim($value);

        return $value === '' ? null : $value;
    }
}
