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
     * Timezone used for every SPMB schedule and operational-hour check.
     */
    public const PPDB_TIMEZONE = 'Asia/Makassar';

    /**
     * Public quota transparency modes.
     *
     * @var array<string, string>
     */
    public const PPDB_QUOTA_DISPLAY_MODES = [
        'full' => 'Tampilkan Detail Lengkap',
        'remaining' => 'Hanya Tampilkan Sisa Kuota',
        'status' => 'Sembunyikan Angka (Hanya Status)',
    ];

    /**
     * Registration opening modes controlled by the admin.
     *
     * @var array<string, string>
     */
    public const PPDB_REGISTRATION_MODES = [
        'open' => 'Buka',
        'closed' => 'Tutup Sementara',
        'scheduled' => 'Ikuti Jadwal Otomatis',
    ];

    /**
     * Safe fallback message when the admin leaves the custom message empty.
     */
    public const PPDB_DEFAULT_CLOSED_MESSAGE = 'Pendaftaran SPMB sedang ditutup. Silakan kembali pada jadwal yang telah ditentukan atau hubungi panitia SPMB.';

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

        $globalStatus = ($settings['status_ppdb'] ?? '') !== '' ? $settings['status_ppdb'] : 'Buka';
        $today = Carbon::now(self::PPDB_TIMEZONE)->startOfDay();
        $levels = [];

        foreach (['TK', 'SD', 'SMP', 'SMA'] as $level) {
            $key = strtolower($level);
            $start = $settings["ppdb_start_{$key}"] ?? null;
            $end = $settings["ppdb_end_{$key}"] ?? null;
            $startDate = $start ? Carbon::parse($start, self::PPDB_TIMEZONE)->startOfDay() : null;
            $endDate = $end ? Carbon::parse($end, self::PPDB_TIMEZONE)->startOfDay() : null;
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
        $countMode = static::ppdbQuotaCountMode($settings);

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

            $totalQuota = $male['quota'] + $female['quota'];
            $totalUsed = $male['used'] + $female['used'];

            if ($countMode === 'manual') {
                $overrideKey = 'quota_used_override_'.strtolower($level);
                $override = $settings[$overrideKey] ?? null;

                if ($override !== null && trim((string) $override) !== '') {
                    $totalUsed = max(0, (int) $override);
                }
            }

            $summary[$level] = [
                'level' => $level,
                'male' => $male,
                'female' => $female,
                'total_used' => $totalUsed,
                'total_quota' => $totalQuota,
                'is_full' => $totalUsed >= $totalQuota,
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
        $settings ??= static::pluck('value', 'key');
        $quotas = static::ppdbTkGroupQuotas($settings);
        $countMode = static::ppdbQuotaCountMode($settings);

        $usage = PpdbRegistration::query()
            ->where('level', 'TK')
            ->whereNotIn('status', ['rejected', 'Ditolak'])
            ->whereNotNull('sub_level')
            ->selectRaw('sub_level, count(*) as total')
            ->groupBy('sub_level')
            ->pluck('total', 'sub_level');

        $summary = [];

        foreach ($quotas as $groupKey => $group) {
            $used = (int) ($usage[$groupKey] ?? 0);

            if ($countMode === 'manual') {
                $overrideKey = 'quota_used_override_'.$group['slug'];
                $override = $settings[$overrideKey] ?? null;

                if ($override !== null && trim((string) $override) !== '') {
                    $used = max(0, (int) $override);
                }
            }

            $summary[$groupKey] = self::quotaRow(
                $group['label'],
                $used,
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
     * Get the configured quota-counting mode ("auto" or "manual").
     *
     * @param  Collection<string, string|null>|null  $settings
     */
    public static function ppdbQuotaCountMode(?Collection $settings = null): string
    {
        $settings ??= static::pluck('value', 'key');
        $mode = (string) ($settings['quota_count_mode'] ?? 'auto');

        return in_array($mode, ['auto', 'manual'], true) ? $mode : 'auto';
    }

    /**
     * Build the public quota transparency configuration.
     *
     * Existing installations without these keys fall back to showing the
     * full quota details so the public page never breaks after deployment.
     *
     * @param  Collection<string, string|null>|null  $settings
     * @return array{visible: bool, mode: string, mode_label: string, count_mode: string}
     */
    public static function ppdbQuotaDisplaySettings(?Collection $settings = null): array
    {
        $settings ??= static::pluck('value', 'key');

        $visible = ! $settings->has('quota_public_visible')
            || filter_var((string) $settings['quota_public_visible'], FILTER_VALIDATE_BOOLEAN);

        $mode = (string) ($settings['quota_display_mode'] ?? 'full');

        if (! array_key_exists($mode, self::PPDB_QUOTA_DISPLAY_MODES)) {
            $mode = 'full';
        }

        return [
            'visible' => $visible,
            'mode' => $mode,
            'mode_label' => self::PPDB_QUOTA_DISPLAY_MODES[$mode],
            'count_mode' => static::ppdbQuotaCountMode($settings),
        ];
    }

    /**
     * Build the per-level and per-group quota rows shown on the public page.
     *
     * KB, TK A, and TK B are reported per group. SD, SMP, and SMA are
     * reported as one aggregate row per level.
     *
     * @param  Collection<string, string|null>|null  $settings
     * @return array<string, array{key: string, level: string, slug: string, label: string, used: int, quota: int, remaining: int, percentage: int, is_full: bool}>
     */
    public static function ppdbPublicQuotaSummary(?Collection $settings = null): array
    {
        $settings ??= static::pluck('value', 'key');
        $summary = static::ppdbQuotaSummary($settings);
        $rows = [];

        foreach (self::PPDB_TK_GROUPS as $groupKey => $group) {
            $row = $summary['TK']['groups'][$groupKey] ?? null;

            if (! is_array($row)) {
                continue;
            }

            $rows[$groupKey] = $row + [
                'key' => $groupKey,
                'level' => 'TK',
                'slug' => $group['slug'],
            ];
        }

        foreach (['SD', 'SMP', 'SMA'] as $level) {
            $levelSummary = $summary[$level] ?? null;

            if (! is_array($levelSummary)) {
                continue;
            }

            $rows[$level] = self::quotaRow(
                $level,
                (int) $levelSummary['total_used'],
                (int) $levelSummary['total_quota'],
            ) + [
                'key' => $level,
                'level' => $level,
                'slug' => strtolower($level),
            ];
        }

        return $rows;
    }

    /**
     * Format a quota row according to the configured transparency mode.
     *
     * @param  array{is_full?: bool, remaining?: int, quota?: int}  $row
     * @param  array{visible: bool, mode: string}|null  $display
     */
    public static function ppdbQuotaDisplayText(array $row, ?array $display = null): string
    {
        $display ??= ['visible' => true, 'mode' => 'full'];

        if ((bool) ($row['is_full'] ?? false)) {
            return 'Kuota Penuh';
        }

        if (! ($display['visible'] ?? true) || ($display['mode'] ?? 'full') === 'status') {
            return 'Tersedia';
        }

        $remaining = number_format((int) ($row['remaining'] ?? 0), 0, ',', '.');

        if (($display['mode'] ?? 'full') === 'remaining') {
            return 'Sisa '.$remaining;
        }

        return 'Sisa '.$remaining.' dari '.number_format((int) ($row['quota'] ?? 0), 0, ',', '.');
    }

    /**
     * Determine whether SPMB registration is currently open in WITA.
     *
     * Manual "Buka" is the safe default when the settings are missing.
     * Manual "Tutup Sementara" always closes the form, while "Ikuti Jadwal
     * Otomatis" applies the configured dates and optional daily hours.
     *
     * @param  Collection<string, string|null>|null  $settings
     * @return array{
     *     timezone: string,
     *     mode: string,
     *     mode_label: string,
     *     state: string,
     *     is_open: bool,
     *     message: string,
     *     next_open_at: Carbon|null,
     *     closes_at: Carbon|null,
     *     starts_at: Carbon|null,
     *     ends_at: Carbon|null,
     *     daily_enabled: bool,
     *     daily_open: string,
     *     daily_close: string,
     *     now: Carbon
     * }
     */
    public static function ppdbRegistrationWindow(?Collection $settings = null): array
    {
        $settings ??= static::pluck('value', 'key');
        $now = Carbon::now(self::PPDB_TIMEZONE);

        $mode = (string) ($settings['registration_mode'] ?? 'open');

        if (! array_key_exists($mode, self::PPDB_REGISTRATION_MODES)) {
            $mode = 'open';
        }

        $start = static::parseMakassarDateTime($settings['registration_start_at'] ?? null);
        $end = static::parseMakassarDateTime($settings['registration_end_at'] ?? null);
        $dailyEnabled = filter_var((string) ($settings['registration_daily_hours_enabled'] ?? '0'), FILTER_VALIDATE_BOOLEAN);
        $dailyOpen = static::normalizeTime((string) ($settings['registration_daily_open_time'] ?? '07:00'), '07:00');
        $dailyClose = static::normalizeTime((string) ($settings['registration_daily_close_time'] ?? '21:00'), '21:00');
        $message = trim((string) ($settings['registration_closed_message'] ?? ''));

        if ($message === '') {
            $message = self::PPDB_DEFAULT_CLOSED_MESSAGE;
        }

        $state = 'open';
        $nextOpenAt = null;
        $closesAt = null;

        if ($mode === 'closed') {
            $state = 'manual_closed';
        } elseif ($mode === 'scheduled') {
            if ($start !== null && $now->lt($start)) {
                $state = 'before_start';
                $nextOpenAt = $start->copy();
            } elseif ($end !== null && $now->gt($end)) {
                $state = 'after_end';
            } elseif ($dailyEnabled) {
                [$state, $nextOpenAt, $closesAt] = static::resolveDailyWindow($now, $dailyOpen, $dailyClose);
            } else {
                $closesAt = $end?->copy();
            }
        }

        if ($mode === 'open' && $dailyEnabled) {
            [$state, $nextOpenAt, $closesAt] = static::resolveDailyWindow($now, $dailyOpen, $dailyClose);
        }

        if ($closesAt !== null && $end !== null && $closesAt->gt($end)) {
            $closesAt = $end->copy();
        }

        if ($nextOpenAt !== null && $end !== null && $nextOpenAt->gt($end)) {
            $nextOpenAt = null;
        }

        $isOpen = $state === 'open';

        return [
            'timezone' => self::PPDB_TIMEZONE,
            'mode' => $mode,
            'mode_label' => self::PPDB_REGISTRATION_MODES[$mode],
            'state' => $state,
            'is_open' => $isOpen,
            'message' => $message,
            'next_open_at' => $isOpen ? null : $nextOpenAt,
            'closes_at' => $isOpen ? $closesAt : null,
            'starts_at' => $start,
            'ends_at' => $end,
            'daily_enabled' => $dailyEnabled,
            'daily_open' => $dailyOpen,
            'daily_close' => $dailyClose,
            'now' => $now,
        ];
    }

    /**
     * Resolve the daily operational window, including overnight ranges.
     *
     * @return array{0: string, 1: Carbon|null, 2: Carbon|null}
     */
    private static function resolveDailyWindow(Carbon $now, string $openTime, string $closeTime): array
    {
        $openAt = Carbon::createFromFormat('H:i', $openTime, self::PPDB_TIMEZONE)
            ->setDate($now->year, $now->month, $now->day);
        $closeAt = Carbon::createFromFormat('H:i', $closeTime, self::PPDB_TIMEZONE)
            ->setDate($now->year, $now->month, $now->day);

        // Overnight window, e.g. 21:00 WITA until 07:00 WITA the next day.
        if ($closeTime <= $openTime) {
            if ($now->lt($closeAt)) {
                return ['open', null, $closeAt];
            }

            if ($now->gte($openAt)) {
                return ['open', null, $closeAt->copy()->addDay()];
            }

            return ['outside_hours', $openAt, null];
        }

        if ($now->lt($openAt)) {
            return ['outside_hours', $openAt, null];
        }

        if ($now->gte($closeAt)) {
            return ['outside_hours', $openAt->copy()->addDay(), null];
        }

        return ['open', null, $closeAt];
    }

    /**
     * Parse a stored datetime value as WITA without shifting the wall clock.
     */
    private static function parseMakassarDateTime(mixed $value): ?Carbon
    {
        $value = trim((string) $value);

        if ($value === '') {
            return null;
        }

        try {
            return Carbon::parse($value, self::PPDB_TIMEZONE)->setTimezone(self::PPDB_TIMEZONE);
        } catch (\Throwable) {
            return null;
        }
    }

    /**
     * Normalize an "HH:MM" value, falling back to a safe default.
     */
    private static function normalizeTime(string $value, string $fallback): string
    {
        $value = trim($value);

        return preg_match('/^([01]\d|2[0-3]):[0-5]\d$/', $value) === 1 ? $value : $fallback;
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
