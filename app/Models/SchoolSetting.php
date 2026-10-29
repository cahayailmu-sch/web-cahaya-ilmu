<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class SchoolSetting extends Model
{
    /**
     * The SPMB levels supported by the school.
     *
     * @var list<string>
     */
    public const PPDB_LEVELS = ['TK', 'SD', 'SMP', 'SMA'];

    /**
     * Default SPMB quota per level and gender when no setting is stored yet.
     *
     * @var array<string, array{l: int, p: int}>
     */
    public const PPDB_DEFAULT_QUOTAS = [
        'tk' => ['l' => 40, 'p' => 40],
        'sd' => ['l' => 60, 'p' => 60],
        'smp' => ['l' => 40, 'p' => 40],
        'sma' => ['l' => 40, 'p' => 40],
    ];

    /**
     * The KB-TK groups and their dedicated SPMB quota settings.
     *
     * KB-TK admissions are limited per group instead of per gender, while
     * SD, SMP, and SMA keep using the male/female quota settings above.
     *
     * @var array<string, array{label: string, slug: string, setting_key: string, default: int}>
     */
    public const PPDB_TK_GROUPS = [
        'KB' => ['label' => 'KB', 'slug' => 'kb', 'setting_key' => 'quota_tk_kb', 'default' => 20],
        'TK A' => ['label' => 'TK A', 'slug' => 'tk_a', 'setting_key' => 'quota_tk_a', 'default' => 40],
        'TK B' => ['label' => 'TK B', 'slug' => 'tk_b', 'setting_key' => 'quota_tk_b', 'default' => 40],
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'key',
        'value',
    ];

    /**
     * Build the SPMB schedule, status, and payment details for every level.
     *
     * @param  Collection<string, string|null>|null  $settings
     * @return array<string, array<string, mixed>>
     */
    public static function ppdbLevels(?Collection $settings = null): array
    {
        $settings ??= static::pluck('value', 'key');

        $globalStatus = ($settings['status_ppdb'] ?? '') !== '' ? $settings['status_ppdb'] : 'Tutup';
        $today = Carbon::today();
        $levels = [];

        foreach (['TK', 'SD', 'SMP', 'SMA'] as $level) {
            $key = strtolower($level);
            $start = $settings["ppdb_start_{$key}"] ?? null;
            $end = $settings["ppdb_end_{$key}"] ?? null;
            $startDate = $start ? Carbon::parse($start)->startOfDay() : null;
            $endDate = $end ? Carbon::parse($end)->startOfDay() : null;
            $levelStatus = ($settings["ppdb_status_{$key}"] ?? '') !== '' ? $settings["ppdb_status_{$key}"] : $globalStatus;

            $withinPeriod = (! $startDate || $today->greaterThanOrEqualTo($startDate))
                && (! $endDate || $today->lessThanOrEqualTo($endDate));

            $fee = (int) ($settings["biaya_{$key}"] ?? 0);

            $levels[$level] = [
                'level' => $level,
                'status' => $levelStatus,
                'is_open' => $globalStatus === 'Buka' && $levelStatus === 'Buka' && $withinPeriod,
                'start' => $startDate,
                'end' => $endDate,
                'bank' => (string) ($settings["bank_{$key}"] ?? ''),
                'account_number' => (string) ($settings["rek_{$key}"] ?? ''),
                'account_name' => (string) ($settings["rek_nama_{$key}"] ?? ''),
                'fee' => $fee,
                'fee_display' => 'Rp '.number_format($fee, 0, ',', '.'),
            ];
        }

        return $levels;
    }

    /**
     * Build the configured SPMB quota for every level.
     *
     * KB-TK exposes a "groups" key with the quota for KB, TK A, and TK B.
     * SD, SMP, and SMA continue to expose "male" and "female" quotas.
     *
     * @param  Collection<string, string|null>|null  $settings
     * @return array<string, array{level: string, male: int, female: int, groups?: array<string, array{key: string, label: string, slug: string, setting_key: string, quota: int}>}>
     */
    public static function ppdbQuotas(?Collection $settings = null): array
    {
        $settings ??= static::pluck('value', 'key');
        $quotas = [];

        foreach (self::PPDB_LEVELS as $level) {
            $key = strtolower($level);
            $default = self::PPDB_DEFAULT_QUOTAS[$key];

            $quotas[$level] = [
                'level' => $level,
                'male' => max(0, (int) ($settings["quota_{$key}_l"] ?? $default['l'])),
                'female' => max(0, (int) ($settings["quota_{$key}_p"] ?? $default['p'])),
            ];

            if ($level === 'TK') {
                $quotas[$level]['groups'] = static::ppdbTkGroupQuotas($settings);
            }
        }

        return $quotas;
    }

    /**
     * Build the configured SPMB quota for every KB-TK group.
     *
     * @param  Collection<string, string|null>|null  $settings
     * @return array<string, array{key: string, label: string, slug: string, setting_key: string, quota: int}>
     */
    public static function ppdbTkGroupQuotas(?Collection $settings = null): array
    {
        $settings ??= static::pluck('value', 'key');
        $quotas = [];

        foreach (self::PPDB_TK_GROUPS as $groupKey => $group) {
            $quotas[$groupKey] = [
                'key' => $groupKey,
                'label' => $group['label'],
                'slug' => $group['slug'],
                'setting_key' => $group['setting_key'],
                'quota' => max(0, (int) ($settings[$group['setting_key']] ?? $group['default'])),
            ];
        }

        return $quotas;
    }

    /**
     * Build SPMB quota usage, remaining seats, and full-quota flags.
     *
     * KB-TK is summarised per group (KB, TK A, TK B), while SD, SMP, and SMA
     * are summarised per gender. Registrations with the "rejected" or
     * "Ditolak" status are excluded from the usage count.
     *
     * @param  Collection<string, string|null>|null  $settings
     * @param  list<string>|null  $levels
     * @return array<string, array{
     *     level: string,
     *     male: array{label: string, used: int, quota: int, remaining: int, percentage: int, is_full: bool},
     *     female: array{label: string, used: int, quota: int, remaining: int, percentage: int, is_full: bool},
     *     groups?: array<string, array{key: string, label: string, slug: string, setting_key: string, used: int, quota: int, remaining: int, percentage: int, is_full: bool}>,
     *     total_used: int,
     *     total_quota: int,
     *     is_full: bool
     * }>
     */
    public static function ppdbQuotaSummary(?Collection $settings = null, ?array $levels = null): array
    {
        $settings ??= static::pluck('value', 'key');
        $quotas = static::ppdbQuotas($settings);
        $tkGroups = static::ppdbTkGroupQuotaSummary($settings);

        $usage = [];
        $rows = PpdbRegistration::query()
            ->whereNotIn('status', ['rejected', 'Ditolak'])
            ->selectRaw('level, gender, count(*) as total')
            ->groupBy('level', 'gender')
            ->get();

        foreach ($rows as $row) {
            $usage[$row->level][$row->gender] = (int) $row->total;
        }

        $summary = [];

        foreach ($levels ?? self::PPDB_LEVELS as $level) {
            if (! isset($quotas[$level])) {
                continue;
            }

            $male = self::quotaRow('Laki-laki', $usage[$level]['L'] ?? 0, $quotas[$level]['male']);
            $female = self::quotaRow('Perempuan', $usage[$level]['P'] ?? 0, $quotas[$level]['female']);

            if ($level === 'TK') {
                $groups = $tkGroups;
                $groupCount = count($groups);

                $summary[$level] = [
                    'level' => $level,
                    'male' => $male,
                    'female' => $female,
                    'groups' => $groups,
                    'total_used' => array_sum(array_column($groups, 'used')),
                    'total_quota' => array_sum(array_column($groups, 'quota')),
                    'is_full' => $groupCount > 0 && collect($groups)->every(fn (array $group): bool => $group['is_full']),
                ];

                continue;
            }

            $summary[$level] = [
                'level' => $level,
                'male' => $male,
                'female' => $female,
                'total_used' => $male['used'] + $female['used'],
                'total_quota' => $male['quota'] + $female['quota'],
                'is_full' => $male['is_full'] && $female['is_full'],
            ];
        }

        return $summary;
    }

    /**
     * Build SPMB quota usage for each KB-TK group.
     *
     * @param  Collection<string, string|null>|null  $settings
     * @return array<string, array{key: string, label: string, slug: string, setting_key: string, used: int, quota: int, remaining: int, percentage: int, is_full: bool}>
     */
    public static function ppdbTkGroupQuotaSummary(?Collection $settings = null): array
    {
        $quotas = static::ppdbTkGroupQuotas($settings);

        $usage = PpdbRegistration::query()
            ->where('level', 'TK')
            ->whereNotIn('status', ['rejected', 'Ditolak'])
            ->whereNotNull('sub_level')
            ->selectRaw('sub_level, count(*) as total')
            ->groupBy('sub_level')
            ->pluck('total', 'sub_level');

        $summary = [];

        foreach ($quotas as $groupKey => $group) {
            $summary[$groupKey] = self::quotaRow(
                $group['label'],
                (int) ($usage[$groupKey] ?? 0),
                $group['quota'],
            ) + [
                'key' => $groupKey,
                'slug' => $group['slug'],
                'setting_key' => $group['setting_key'],
            ];
        }

        return $summary;
    }

    /**
     * Build a single quota progress row.
     *
     * @return array{label: string, used: int, quota: int, remaining: int, percentage: int, is_full: bool}
     */
    private static function quotaRow(string $label, int $used, int $quota): array
    {
        return [
            'label' => $label,
            'used' => $used,
            'quota' => $quota,
            'remaining' => max(0, $quota - $used),
            'percentage' => $quota > 0 ? min(100, (int) round(($used / $quota) * 100)) : 100,
            'is_full' => $used >= $quota,
        ];
    }

    /**
     * Get the configured official WhatsApp group links per level and gender.
     *
     * @param  Collection<string, string|null>|null  $settings
     * @return array<string, array{l: string, p: string}>
     */
    public static function ppdbWhatsappGroups(?Collection $settings = null): array
    {
        $settings ??= static::pluck('value', 'key');
        $groups = [];

        foreach (self::PPDB_LEVELS as $level) {
            $key = strtolower($level);
            $groups[$key] = [
                'l' => (string) ($settings["wa_group_{$key}_l"] ?? ''),
                'p' => (string) ($settings["wa_group_{$key}_p"] ?? ''),
            ];
        }

        return $groups;
    }

    /**
     * Get the active or target school year label, e.g. "2027/2028".
     *
     * @param  Collection<string, string|null>|null  $settings
     */
    public static function activeSchoolYear(?Collection $settings = null): string
    {
        $settings ??= static::pluck('value', 'key');
        $tahunAjaran = trim((string) ($settings['tahun_ajaran'] ?? ''));

        if (preg_match('/^(\d{4})\s*\/\s*(\d{4})$/', $tahunAjaran, $matches) === 1) {
            return $matches[1].'/'.$matches[2];
        }

        $startYear = now()->month >= 7 ? now()->year : now()->year - 1;

        return $startYear.'/'.($startYear + 1);
    }

    /**
     * Get the first year of the active/target school year, e.g. 2027.
     *
     * @param  Collection<string, string|null>|null  $settings
     */
    public static function activeSchoolYearStart(?Collection $settings = null): int
    {
        $schoolYear = static::activeSchoolYear($settings);

        return (int) strtok($schoolYear, '/');
    }

    /**
     * Get the installment deadline label (June of the school year's first year).
     *
     * @param  Collection<string, string|null>|null  $settings
     */
    public static function installmentDeadlineLabel(?Collection $settings = null): string
    {
        return 'Juni '.static::activeSchoolYearStart($settings);
    }

    /**
     * Get the hero image URL for a given jenjang slug ('tk', 'sd', 'smp', 'sma').
     *
     * Returns the uploaded custom setting if available, or falls back to
     * the configured default in config/jenjang.php.
     *
     * @param  Collection<string, string|null>|null  $settings
     */
    public static function jenjangHeroImage(string $slug, ?Collection $settings = null): string
    {
        $key = strtolower(trim($slug));
        $settings ??= static::pluck('value', 'key');
        $custom = $settings["hero_image_{$key}"] ?? null;

        if (! empty($custom)) {
            if (str_starts_with($custom, 'http') || str_starts_with($custom, '/')) {
                return $custom;
            }

            return Storage::disk('public')->url($custom);
        }

        return (string) config("jenjang.{$key}.hero_image", "/assets/images/jenjang-{$key}.jpg");
    }
}
