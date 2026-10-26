<?php

namespace Database\Seeders;

use App\Models\SchoolSetting;
use Illuminate\Database\Seeder;

class PpdbTkGroupQuotaSeeder extends Seeder
{
    /**
     * Seed the default SPMB quota for each KB-TK group.
     */
    public function run(): void
    {
        foreach (SchoolSetting::PPDB_TK_GROUPS as $group) {
            SchoolSetting::updateOrCreate(
                ['key' => $group['setting_key']],
                ['value' => (string) $group['default']],
            );
        }
    }
}
