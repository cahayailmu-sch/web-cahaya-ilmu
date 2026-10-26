<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReferralCode extends Model
{
    public const TYPE_EVENT_FREE_REG = 'event_free_reg';

    public const TYPE_SIBLING_ALUMNI = 'sibling_alumni';

    /**
     * Human-readable metadata for every referral type.
     *
     * @var array<string, array{label: string, short_label: string, discount_amount: int}>
     */
    public const TYPES = [
        self::TYPE_EVENT_FREE_REG => [
            'label' => 'Event Muslim Vibes — Gratis Biaya Pendaftaran',
            'short_label' => 'Gratis Biaya Pendaftaran',
            'discount_amount' => 250000,
        ],
        self::TYPE_SIBLING_ALUMNI => [
            'label' => 'Kakak Alumni — Potongan Rp 500.000',
            'short_label' => 'Potongan Kakak Alumni',
            'discount_amount' => 500000,
        ],
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'code',
        'type',
        'discount_amount',
        'max_uses',
        'used_count',
        'is_active',
        'used_by_registration_id',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'discount_amount' => 'integer',
            'max_uses' => 'integer',
            'used_count' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get the registration that claimed a single-use code.
     */
    public function usedByRegistration(): BelongsTo
    {
        return $this->belongsTo(PpdbRegistration::class, 'used_by_registration_id');
    }

    /**
     * Normalize a referral code to the stored uppercase format.
     *
     * Removes invisible characters and converts typographic dashes so codes
     * copied from WhatsApp, PDFs, or mobile keyboards still match.
     */
    public static function normalize(string $code): string
    {
        $code = str_replace(
            ["\u{00A0}", "\u{2007}", "\u{200B}", "\u{200C}", "\u{200D}", "\u{202F}", "\u{2060}", "\u{FEFF}"],
            '',
            $code,
        );

        $code = str_replace(
            ["\u{2010}", "\u{2011}", "\u{2012}", "\u{2013}", "\u{2014}", "\u{2015}", "\u{2212}", "\u{FF0D}"],
            '-',
            $code,
        );

        $code = preg_replace('/\s+/u', '', $code) ?? $code;

        return strtoupper(trim($code));
    }

    /**
     * Find a referral code using its normalized uppercase value.
     */
    public static function findByCode(string $code): ?self
    {
        return static::query()
            ->where('code', static::normalize($code))
            ->first();
    }

    /**
     * Determine whether the code can no longer be used.
     */
    public function isExhausted(): bool
    {
        return $this->used_count >= $this->max_uses;
    }

    /**
     * Determine whether the code is active and still has remaining uses.
     */
    public function isAvailable(): bool
    {
        return $this->is_active && ! $this->isExhausted();
    }

    /**
     * Get the number of remaining uses for the code.
     */
    public function remainingUses(): int
    {
        return max(0, $this->max_uses - $this->used_count);
    }

    /**
     * Get the human-readable type label for the code.
     */
    public function typeLabel(): string
    {
        return (string) (self::TYPES[$this->type]['label'] ?? $this->type);
    }

    /**
     * Get the short description shown on registrations and documents.
     */
    public function descriptionLabel(): string
    {
        return (string) (self::TYPES[$this->type]['short_label'] ?? $this->type);
    }

    /**
     * Get the formatted discount amount for the code.
     */
    public function discountDisplay(): string
    {
        return 'Rp '.number_format($this->discount_amount, 0, ',', '.');
    }

    /**
     * Get the current availability status of the code.
     */
    public function statusLabel(): string
    {
        if (! $this->is_active) {
            return 'Nonaktif';
        }

        if (! $this->isExhausted()) {
            return 'Tersedia';
        }

        if ($this->usedByRegistration !== null) {
            return 'Sudah Dipakai oleh: '.$this->usedByRegistration->full_name;
        }

        return 'Habis';
    }

    /**
     * Register a new use and lock single-use codes to the registrant.
     */
    public function markUsed(PpdbRegistration $registration): void
    {
        $this->used_count++;

        if ($this->max_uses <= 1) {
            $this->used_by_registration_id = $registration->id;
        }

        $this->save();
    }

    /**
     * Scope a query to only active referral codes.
     *
     * @param  Builder<ReferralCode>  $query
     */
    public function scopeActive(Builder $query): void
    {
        $query->where('is_active', true);
    }

    /**
     * Scope a query to event (free registration) codes.
     *
     * @param  Builder<ReferralCode>  $query
     */
    public function scopeEventFree(Builder $query): void
    {
        $query->where('type', self::TYPE_EVENT_FREE_REG);
    }

    /**
     * Scope a query to sibling / alumni codes.
     *
     * @param  Builder<ReferralCode>  $query
     */
    public function scopeSibling(Builder $query): void
    {
        $query->where('type', self::TYPE_SIBLING_ALUMNI);
    }
}
