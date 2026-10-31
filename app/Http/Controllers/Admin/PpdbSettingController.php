<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ReferralCode;
use App\Models\SchoolSetting;
use App\Support\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PpdbSettingController extends Controller
{
    /**
     * Level labels keyed by their lowercase setting suffix.
     *
     * @var array<string, string>
     */
    private const LEVEL_LABELS = ['tk' => 'TK', 'sd' => 'SD', 'smp' => 'SMP', 'sma' => 'SMA'];

    /**
     * Show the SPMB-specific settings page.
     */
    public function index(): View
    {
        $settings = SchoolSetting::pluck('value', 'key');
        $editableLevels = $this->editableLevels();
        $canManageReferralCodes = $this->canManageReferralCodes();
        $referralCodes = $canManageReferralCodes
            ? ReferralCode::query()
                ->with('usedByRegistration:id,full_name,registration_number')
                ->orderBy('code')
                ->get()
            : collect();

        return view('admin.ppdb-settings.index', [
            'settings' => $settings,
            'ppdbLevels' => SchoolSetting::ppdbLevels($settings),
            'quotaSummary' => SchoolSetting::ppdbQuotaSummary($settings, array_values($editableLevels)),
            'quotaLevels' => $editableLevels,
            'quotaDisplay' => SchoolSetting::ppdbQuotaDisplaySettings($settings),
            'registrationWindow' => SchoolSetting::ppdbRegistrationWindow($settings),
            'whatsappGroups' => SchoolSetting::ppdbWhatsappGroups($settings),
            'isSuperAdmin' => (bool) auth()->user()?->isSuperAdmin(),
            'canManageReferralCodes' => $canManageReferralCodes,
            'referralCodes' => $referralCodes,
            'eventReferralCodes' => $referralCodes
                ->where('type', ReferralCode::TYPE_EVENT_FREE_REG)
                ->values(),
        ]);
    }

    /**
     * Update the SPMB settings the authenticated admin is allowed to manage.
     */
    public function update(Request $request): RedirectResponse
    {
        $isSuperAdmin = (bool) auth()->user()?->isSuperAdmin();
        $editableLevels = $this->editableLevels();

        $rules = [];
        $messages = [];

        if ($isSuperAdmin) {
            $rules['status_ppdb'] = ['nullable', Rule::in(['Buka', 'Tutup'])];
            $rules['tahun_ajaran'] = ['nullable', 'string', 'max:20'];
            $rules['quota_public_visible'] = ['nullable', 'boolean'];
            $rules['quota_display_mode'] = ['nullable', Rule::in(array_keys(SchoolSetting::PPDB_QUOTA_DISPLAY_MODES))];
            $rules['quota_count_mode'] = ['nullable', Rule::in(['auto', 'manual'])];
            $rules['registration_mode'] = ['nullable', Rule::in(array_keys(SchoolSetting::PPDB_REGISTRATION_MODES))];
            $rules['registration_start_at'] = ['nullable', 'date'];
            $rules['registration_end_at'] = [
                'nullable',
                'date',
                ...($request->filled('registration_start_at') ? ['after_or_equal:registration_start_at'] : []),
            ];
            $rules['registration_daily_hours_enabled'] = ['nullable', 'boolean'];
            $rules['registration_daily_open_time'] = ['nullable', 'date_format:H:i'];
            $rules['registration_daily_close_time'] = ['nullable', 'date_format:H:i'];
            $rules['registration_closed_message'] = ['nullable', 'string', 'max:2000'];

            $messages['status_ppdb.in'] = 'Status SPMB tidak valid.';
            $messages['tahun_ajaran.max'] = 'Tahun ajaran terlalu panjang.';
            $messages['quota_display_mode.in'] = 'Mode transparansi kuota tidak valid.';
            $messages['quota_count_mode.in'] = 'Mode penghitungan kuota tidak valid.';
            $messages['registration_mode.in'] = 'Status pendaftaran manual tidak valid.';
            $messages['registration_end_at.after_or_equal'] = 'Jadwal tutup pendaftaran tidak boleh lebih awal dari jadwal buka.';
            $messages['registration_daily_open_time.date_format'] = 'Jam buka harian harus berformat HH:MM (contoh 07:00).';
            $messages['registration_daily_close_time.date_format'] = 'Jam tutup harian harus berformat HH:MM (contoh 21:00).';
        }

        foreach ($editableLevels as $key => $label) {
            if ($key === 'tk') {
                foreach (SchoolSetting::PPDB_TK_GROUPS as $group) {
                    $settingKey = $group['setting_key'];
                    $overrideKey = 'quota_used_override_'.$group['slug'];

                    $rules[$settingKey] = ['nullable', 'integer', 'min:0', 'max:10000'];
                    $rules[$overrideKey] = ['nullable', 'integer', 'min:0', 'max:10000'];
                    $messages["{$settingKey}.integer"] = "Kuota kelompok {$group['label']} harus berupa angka.";
                    $messages["{$settingKey}.min"] = "Kuota kelompok {$group['label']} tidak boleh negatif.";
                    $messages["{$settingKey}.max"] = "Kuota kelompok {$group['label']} maksimal 10.000.";
                    $messages["{$overrideKey}.integer"] = "Kuota terisi manual kelompok {$group['label']} harus berupa angka.";
                    $messages["{$overrideKey}.min"] = "Kuota terisi manual kelompok {$group['label']} tidak boleh negatif.";
                    $messages["{$overrideKey}.max"] = "Kuota terisi manual kelompok {$group['label']} maksimal 10.000.";
                }

                // Keep accepting the previous TK gender keys so legacy payloads
                // and integrations do not break after the switch to group quotas.
                $rules['quota_tk_l'] = ['nullable', 'integer', 'min:0', 'max:10000'];
                $rules['quota_tk_p'] = ['nullable', 'integer', 'min:0', 'max:10000'];
            } else {
                $overrideKey = 'quota_used_override_'.$key;

                $rules["quota_{$key}_l"] = ['nullable', 'integer', 'min:0', 'max:10000'];
                $rules["quota_{$key}_p"] = ['nullable', 'integer', 'min:0', 'max:10000'];
                $rules[$overrideKey] = ['nullable', 'integer', 'min:0', 'max:10000'];

                $messages["quota_{$key}_l.integer"] = "Kuota laki-laki {$label} harus berupa angka.";
                $messages["quota_{$key}_l.min"] = "Kuota laki-laki {$label} tidak boleh negatif.";
                $messages["quota_{$key}_l.max"] = "Kuota laki-laki {$label} maksimal 10.000.";
                $messages["quota_{$key}_p.integer"] = "Kuota perempuan {$label} harus berupa angka.";
                $messages["quota_{$key}_p.min"] = "Kuota perempuan {$label} tidak boleh negatif.";
                $messages["quota_{$key}_p.max"] = "Kuota perempuan {$label} maksimal 10.000.";
                $messages["{$overrideKey}.integer"] = "Kuota terisi manual {$label} harus berupa angka.";
                $messages["{$overrideKey}.min"] = "Kuota terisi manual {$label} tidak boleh negatif.";
                $messages["{$overrideKey}.max"] = "Kuota terisi manual {$label} maksimal 10.000.";
            }

            $rules["wa_group_{$key}_l"] = ['nullable', 'url', 'max:255'];
            $rules["wa_group_{$key}_p"] = ['nullable', 'url', 'max:255'];
            $rules["wa_admin_{$key}"] = ['nullable', 'string', 'max:20', 'regex:/^[0-9+\-\s]+$/'];

            $messages["wa_group_{$key}_l.url"] = "Link grup WhatsApp laki-laki {$label} harus berupa URL yang valid.";
            $messages["wa_group_{$key}_p.url"] = "Link grup WhatsApp perempuan {$label} harus berupa URL yang valid.";
            $messages["wa_admin_{$key}.regex"] = "Nomor WhatsApp panitia {$label} tidak valid.";

            if (! $isSuperAdmin) {
                continue;
            }

            $rules["ppdb_status_{$key}"] = ['nullable', Rule::in(['Buka', 'Tutup'])];
            $rules["ppdb_start_{$key}"] = ['nullable', 'date'];
            $rules["ppdb_end_{$key}"] = [
                'nullable',
                'date',
                ...($request->filled("ppdb_start_{$key}") ? ['after_or_equal:ppdb_start_'.$key] : []),
            ];
            $rules["bank_{$key}"] = ['nullable', 'string', 'max:100'];
            $rules["rek_{$key}"] = ['nullable', 'string', 'max:50'];
            $rules["rek_nama_{$key}"] = ['nullable', 'string', 'max:255'];
            $rules["biaya_{$key}"] = ['nullable', 'integer', 'min:0'];

            $messages["ppdb_status_{$key}.in"] = "Status SPMB {$label} tidak valid.";
            $messages["ppdb_end_{$key}.after_or_equal"] = "Tanggal selesai SPMB {$label} tidak boleh lebih awal dari tanggal mulai.";
            $messages["biaya_{$key}.integer"] = "Biaya pendaftaran {$label} harus berupa angka.";
        }

        $validated = $request->validate($rules, $messages);

        $existingSettings = SchoolSetting::pluck('value', 'key');
        $beforeChanges = [];
        $afterChanges = [];

        foreach ($validated as $key => $value) {
            if ($value === null && str_starts_with($key, 'quota_')) {
                continue;
            }

            $normalizedValue = (string) $value;
            $previousValue = (string) ($existingSettings[$key] ?? '');

            if ($previousValue !== $normalizedValue) {
                $beforeChanges[$key] = $previousValue;
                $afterChanges[$key] = $normalizedValue;
            }

            SchoolSetting::updateOrCreate(
                ['key' => $key],
                ['value' => $normalizedValue],
            );
        }

        ActivityLogger::log(
            'update',
            'Pengaturan SPMB',
            $isSuperAdmin
                ? 'Memperbarui pengaturan SPMB untuk seluruh jenjang (status, tahun ajaran, transparansi kuota, jadwal & jam operasional, kuota, grup WhatsApp, biaya, rekening, dan kontak panitia).'
                : 'Memperbarui pengaturan SPMB jenjang '.($editableLevels[array_key_first($editableLevels)] ?? '-').' (kuota, grup WhatsApp, dan kontak panitia).',
            $afterChanges !== [] ? ['before' => $beforeChanges, 'after' => $afterChanges] : null,
        );

        return back()->with('success', 'Pengaturan SPMB berhasil disimpan.');
    }

    /**
     * Determine whether the authenticated admin may manage KB-TK referral codes.
     */
    private function canManageReferralCodes(): bool
    {
        $user = auth()->user();

        return (bool) $user?->isSuperAdmin()
            || strtolower((string) $user?->getLevel()) === 'tk';
    }

    /**
     * Get the levels the authenticated admin is allowed to manage.
     *
     * @return array<string, string>
     */
    private function editableLevels(): array
    {
        $user = auth()->user();

        if ($user?->isSuperAdmin()) {
            return self::LEVEL_LABELS;
        }

        $level = strtolower((string) $user?->getLevel());

        return isset(self::LEVEL_LABELS[$level]) ? [$level => self::LEVEL_LABELS[$level]] : [];
    }
}
