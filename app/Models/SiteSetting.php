<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    protected $fillable = [
        'topstrip_left',
        'support_phone',
        'support_email',
        'support_emails',
        'support_phones',
        'office_address_label',
        'office_address',
        'office_addresses',
        'brand_name',
        'brand_tagline',
        'logo_image',
        'footer_about',
        'footer_badges',
        'footer_copyright_name',
        'social_facebook_url',
        'social_instagram_url',
        'social_linkedin_url',
        'social_twitter_url',
        'hero_image',
        'live_chat_url',
        'tawk_property_id',
        'tawk_widget_id',
    ];

    protected function casts(): array
    {
        return [
            'support_emails' => 'array',
            'support_phones' => 'array',
            'office_addresses' => 'array',
        ];
    }

    /** @return array<int, array{label: string, email: string}> */
    public function supportEmailList(): array
    {
        $rows = $this->normalizeLabeledRows($this->support_emails ?? [], 'email', 'Support');

        if ($rows !== []) {
            return $rows;
        }

        if (filled($this->support_email)) {
            return [['label' => 'Support', 'email' => $this->support_email]];
        }

        return [['label' => 'Support', 'email' => 'support@jetflyairways.com']];
    }

    /** @return array<int, array{label: string, phone: string}> */
    public function supportPhoneList(): array
    {
        $rows = $this->normalizeLabeledRows($this->support_phones ?? [], 'phone', 'Support');

        if ($rows !== []) {
            return $rows;
        }

        if (filled($this->support_phone)) {
            return [['label' => 'Support', 'phone' => $this->support_phone]];
        }

        return [['label' => 'Support', 'phone' => '+91 1800-000-0000']];
    }

    /** @return array<int, array{label: string, address: string}> */
    public function officeAddressList(): array
    {
        $rows = collect($this->office_addresses ?? [])
            ->map(function ($row) {
                if (is_string($row)) {
                    return ['label' => 'Registered Office', 'address' => trim($row)];
                }

                return [
                    'label' => trim((string) ($row['label'] ?? '')) ?: 'Registered Office',
                    'address' => trim((string) ($row['address'] ?? '')),
                ];
            })
            ->filter(fn (array $row) => filled($row['address']))
            ->values()
            ->all();

        if ($rows !== []) {
            return $rows;
        }

        if (filled($this->office_address)) {
            return [[
                'label' => filled($this->office_address_label)
                    ? $this->office_address_label
                    : 'Registered Office',
                'address' => $this->office_address,
            ]];
        }

        return [];
    }

    public function primarySupportEmail(): string
    {
        return $this->supportEmailList()[0]['email'] ?? 'support@jetflyairways.com';
    }

    public function primarySupportPhone(): string
    {
        return $this->supportPhoneList()[0]['phone'] ?? '+91 1800-000-0000';
    }

    public function tawkEnabled(): bool
    {
        return filled($this->tawk_property_id) && filled($this->tawk_widget_id);
    }

    public function tawkPropertyId(): ?string
    {
        return filled($this->tawk_property_id) ? trim($this->tawk_property_id) : null;
    }

    public function tawkWidgetId(): ?string
    {
        return filled($this->tawk_widget_id) ? trim($this->tawk_widget_id) : null;
    }

    /** @return array<int, array{label: string, email?: string, phone?: string, address?: string}> */
    private function normalizeLabeledRows(array $rows, string $valueKey, string $defaultLabel): array
    {
        return collect($rows)
            ->map(function ($row) use ($valueKey, $defaultLabel) {
                if (is_string($row)) {
                    return ['label' => $defaultLabel, $valueKey => trim($row)];
                }

                return [
                    'label' => trim((string) ($row['label'] ?? '')) ?: $defaultLabel,
                    $valueKey => trim((string) ($row[$valueKey] ?? '')),
                ];
            })
            ->filter(fn (array $row) => filled($row[$valueKey]))
            ->values()
            ->all();
    }
}
