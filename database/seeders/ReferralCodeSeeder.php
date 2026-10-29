<?php

namespace Database\Seeders;

use App\Support\ReferralCodeCatalog;
use Illuminate\Database\Seeder;

class ReferralCodeSeeder extends Seeder
{
    /**
     * Seed the 10 Event Muslim Vibes referral codes.
     */
    public function run(): void
    {
        ReferralCodeCatalog::ensureEventCodes();
    }
}
