<?php

namespace Database\Seeders;

use App\Models\SchoolSetting;
use Illuminate\Database\Seeder;

class PpdbQuotaSeeder extends Seeder
{
    /**
     * Seed the default SPMB quota per level and gender.
     */
    public function run(): void
    {
        foreach (SchoolSetting::PPDB_DEFAULT_QUOTAS as $level => $quota) {
            foreach (['l' => $quota['l'], 'p' => $quota['p']] as $gender => $value) {
                SchoolSetting::updateOrCreate(
                    ['key' => "quota_{$level}_{$gender}"],
                    ['value' => (string) $value],
                );
            }
        }
    }
}
