<?php

namespace Database\Seeders;

use App\Models\ReferralCode;
use App\Support\ReferralCodeCatalog;
use Illuminate\Database\Seeder;

class ReferralCodeSeeder extends Seeder
{
    /**
     * Seed the 10 Event Muslim Vibes referral codes and a sibling example.
     */
    public function run(): void
    {
        ReferralCodeCatalog::ensureEventCodes();

        ReferralCode::firstOrCreate(
            ['code' => 'ALUMNI26-01'],
            [
                'type' => ReferralCode::TYPE_SIBLING_ALUMNI,
                'discount_amount' => 500000,
                'max_uses' => 10,
                'used_count' => 0,
                'is_active' => true,
                'notes' => 'Contoh kode kakak alumni KB-TK. Dapat diedit atau ditambah dari panel Admin TK.',
            ],
        );
    }
}
