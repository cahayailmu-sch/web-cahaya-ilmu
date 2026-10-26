<?php

namespace App\Support;

use App\Models\ReferralCode;

class ReferralCodeCatalog
{
    /**
     * The ten Event Muslim Vibes referral codes that must always exist.
     *
     * @var list<string>
     */
    public const EVENT_CODES = [
        'CI26-K7P4',
        'CI26-M9X2',
        'CI26-R5T8',
        'CI26-H3N7',
        'CI26-Q8L4',
        'CI26-W6F9',
        'CI26-B4Y8',
        'CI26-P7D3',
        'CI26-Z5K6',
        'CI26-T9M4',
    ];

    public const EVENT_DISCOUNT_AMOUNT = 250000;

    /**
     * Ensure every Event Muslim Vibes code exists without overwriting usage data.
     */
    public static function ensureEventCodes(): int
    {
        foreach (self::EVENT_CODES as $index => $code) {
            ReferralCode::firstOrCreate(
                ['code' => $code],
                [
                    'type' => ReferralCode::TYPE_EVENT_FREE_REG,
                    'discount_amount' => self::EVENT_DISCOUNT_AMOUNT,
                    'max_uses' => 1,
                    'used_count' => 0,
                    'is_active' => true,
                    'notes' => 'Event Muslim Vibes 31 Oktober - 1 November 2026 (kode #'.($index + 1).').',
                ],
            );
        }

        return count(self::EVENT_CODES);
    }
}
