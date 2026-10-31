<?php

namespace Tests\Feature;

use App\Models\PpdbRegistration;
use App\Models\SchoolSetting;
use App\Models\User;
use Database\Seeders\PpdbQuotaSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PpdbTransparencyScheduleTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');
        Carbon::setTestNow(Carbon::parse('2026-10-31 10:00:00', SchoolSetting::PPDB_TIMEZONE));
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow();

        parent::tearDown();
    }

    /**
     * Seed the base school settings and default SPMB quotas.
     *
     * @param  array<string, mixed>  $overrides
     */
    private function createSettings(array $overrides = []): void
    {
        $settings = [
            'status_ppdb' => 'Buka',
            'tahun_ajaran' => '2027/2028',
            'wa_admin_sd' => '081250000002',
        ];

        foreach (['tk', 'sd', 'smp', 'sma'] as $level) {
            $settings += [
                "ppdb_status_{$level}" => 'Buka',
                "ppdb_start_{$level}" => now()->subMonth()->toDateString(),
                "ppdb_end_{$level}" => now()->addMonth()->toDateString(),
                "biaya_{$level}" => '0',
            ];
        }

        foreach (array_merge($settings, $overrides) as $key => $value) {
            SchoolSetting::updateOrCreate(['key' => $key], ['value' => (string) $value]);
        }

        $this->seed(PpdbQuotaSeeder::class);

        foreach (SchoolSetting::PPDB_DEFAULT_QUOTAS as $level => $quota) {
            foreach (['l' => $quota['l'], 'p' => $quota['p']] as $gender => $value) {
                $key = "quota_{$level}_{$gender}";

                if (array_key_exists($key, $overrides)) {
                    SchoolSetting::updateOrCreate(['key' => $key], ['value' => (string) $overrides[$key]]);
                }
            }
        }
    }

    /**
     * Build valid SPMB form data.
     *
     * @param  array<string, mixed>  $overrides
     * @return array<string, mixed>
     */
    private function registrationData(array $overrides = []): array
    {
        return array_merge([
            'full_name' => 'Ahmad Fauzan',
            'gender' => 'L',
            'birth_place' => 'Balikpapan',
            'birth_date' => now()->subYears(8)->toDateString(),
            'level' => 'SD',
            'previous_school' => 'TK Islam Cahaya Ilmu',
            'parent_name' => 'Budi Santoso',
            'whatsapp' => '081250000099',
            'address' => 'Jl. Pendidikan No. 1, Balikpapan',
            'payment_proof' => UploadedFile::fake()->image('bukti-pembayaran.jpg'),
            'persetujuan' => '1',
        ], $overrides);
    }

    /**
     * Create an SPMB registration that consumes quota.
     *
     * @param  array<string, mixed>  $attributes
     */
    private function createRegistration(array $attributes = []): PpdbRegistration
    {
        return PpdbRegistration::create(array_merge([
            'registration_number' => 'SPMB-TRANSPARENCY-'.str_pad((string) (PpdbRegistration::count() + 1), 3, '0', STR_PAD_LEFT),
            'full_name' => 'Pendaftar '.fake()->unique()->numerify('###'),
            'gender' => 'L',
            'birth_place' => 'Balikpapan',
            'birth_date' => now()->subYears(8)->toDateString(),
            'level' => 'SD',
            'parent_name' => 'Budi Santoso',
            'whatsapp' => '081250000001',
            'address' => 'Balikpapan',
            'status' => 'Baru',
        ], $attributes));
    }

    public function test_public_page_uses_safe_defaults_without_the_new_settings(): void
    {
        $this->createSettings();

        SchoolSetting::whereIn('key', [
            'quota_public_visible',
            'quota_display_mode',
            'quota_count_mode',
            'registration_mode',
            'registration_daily_hours_enabled',
            'registration_closed_message',
        ])->delete();

        $this->get(route('ppdb'))
            ->assertOk()
            ->assertSee('Sisa 60 dari 60')
            ->assertDontSee('Pendaftaran Sementara Ditutup')
            ->assertDontSee('Di Luar Jam Operasional');
    }

    public function test_manual_closed_mode_shows_custom_message_and_blocks_submission(): void
    {
        $this->createSettings([
            'registration_mode' => 'closed',
            'registration_closed_message' => 'Pendaftaran ditutup sementara untuk pemeliharaan sistem.',
        ]);

        $this->get(route('ppdb'))
            ->assertOk()
            ->assertSee('Pendaftaran Sementara Ditutup')
            ->assertSee('Pendaftaran ditutup sementara untuk pemeliharaan sistem.')
            ->assertSee('id="ppdb-registration-closed"', false)
            ->assertSee('Tombol kirim dikunci sampai pendaftaran dibuka kembali.');

        $this->post(route('ppdb.store'), $this->registrationData())
            ->assertSessionHasErrors('level');

        $this->assertDatabaseCount('ppdb_registrations', 0);
    }

    public function test_scheduled_mode_before_start_shows_countdown_and_blocks_submission(): void
    {
        $this->createSettings([
            'registration_mode' => 'scheduled',
            'registration_start_at' => '2026-11-02 07:00:00',
            'registration_end_at' => '2026-12-31 21:00:00',
        ]);

        $this->get(route('ppdb'))
            ->assertOk()
            ->assertSee('Pendaftaran Belum Dibuka')
            ->assertSee('data-countdown-target="2026-11-02T07:00:00+08:00"', false)
            ->assertSee('Senin, 2 November 2026 pukul 07:00 WITA');

        $this->post(route('ppdb.store'), $this->registrationData())
            ->assertSessionHasErrors('level');

        $this->assertDatabaseCount('ppdb_registrations', 0);
    }

    public function test_scheduled_mode_outside_daily_hours_blocks_submission(): void
    {
        Carbon::setTestNow(Carbon::parse('2026-10-31 22:30:00', SchoolSetting::PPDB_TIMEZONE));

        $this->createSettings([
            'registration_mode' => 'scheduled',
            'registration_start_at' => '2026-10-01 00:00:00',
            'registration_end_at' => '2026-12-31 21:00:00',
            'registration_daily_hours_enabled' => '1',
            'registration_daily_open_time' => '07:00',
            'registration_daily_close_time' => '21:00',
        ]);

        $this->get(route('ppdb'))
            ->assertOk()
            ->assertSee('Di Luar Jam Operasional')
            ->assertSee('data-countdown-target="2026-11-01T07:00:00+08:00"', false);

        $this->post(route('ppdb.store'), $this->registrationData())
            ->assertSessionHasErrors('level');

        $this->assertDatabaseCount('ppdb_registrations', 0);
    }

    public function test_scheduled_mode_inside_daily_hours_shows_closing_countdown(): void
    {
        Carbon::setTestNow(Carbon::parse('2026-10-31 19:00:00', SchoolSetting::PPDB_TIMEZONE));

        $this->createSettings([
            'registration_mode' => 'scheduled',
            'registration_start_at' => '2026-10-01 00:00:00',
            'registration_end_at' => '2026-12-31 21:00:00',
            'registration_daily_hours_enabled' => '1',
            'registration_daily_open_time' => '07:00',
            'registration_daily_close_time' => '21:00',
        ]);

        $this->get(route('ppdb'))
            ->assertOk()
            ->assertSee('Pendaftaran sedang dibuka.')
            ->assertSee('data-countdown-target="2026-10-31T21:00:00+08:00"', false)
            ->assertDontSee('id="ppdb-registration-closed"', false);
    }

    public function test_hidden_quota_numbers_show_status_only(): void
    {
        $this->createSettings(['quota_public_visible' => '0']);

        $this->get(route('ppdb'))
            ->assertOk()
            ->assertSee('Informasi angka kuota disembunyikan.')
            ->assertSee('Total SD')
            ->assertSee('Tersedia')
            ->assertDontSee('Sisa 60 dari 60');
    }

    public function test_remaining_only_mode_hides_the_maximum_quota(): void
    {
        $this->createSettings([
            'quota_tk_kb' => '8',
            'quota_display_mode' => 'remaining',
        ]);

        $this->get(route('ppdb'))
            ->assertOk()
            ->assertSee('Sisa 8')
            ->assertDontSee('Sisa 8 dari 8');
    }

    public function test_status_only_mode_hides_every_quota_number(): void
    {
        $this->createSettings([
            'quota_tk_kb' => '8',
            'quota_display_mode' => 'status',
        ]);

        $this->get(route('ppdb'))
            ->assertOk()
            ->assertSee('Total SD')
            ->assertSee('Tersedia')
            ->assertDontSee('Sisa 8')
            ->assertDontSee('Sisa 60');
    }

    public function test_manual_quota_count_override_marks_the_level_full(): void
    {
        $this->createSettings([
            'quota_count_mode' => 'manual',
            'quota_used_override_sd' => '120',
        ]);

        $this->get(route('ppdb'))
            ->assertOk()
            ->assertSee('Kuota jenjang SD sudah PENUH untuk Laki-laki dan Perempuan.');

        $this->post(route('ppdb.store'), $this->registrationData())
            ->assertSessionHasErrors('level');

        $this->assertStringContainsString('sudah PENUH (120/120)', session('errors')->first('level'));
        $this->assertDatabaseCount('ppdb_registrations', 0);
    }

    public function test_superadmin_can_save_transparency_and_schedule_settings(): void
    {
        $this->createSettings();

        $this->actingAs(User::factory()->create())
            ->put(route('admin.ppdb-settings.update'), [
                'quota_public_visible' => '0',
                'quota_display_mode' => 'remaining',
                'quota_count_mode' => 'manual',
                'registration_mode' => 'scheduled',
                'registration_start_at' => '2026-11-01T07:00',
                'registration_end_at' => '2026-11-30T21:00',
                'registration_daily_hours_enabled' => '1',
                'registration_daily_open_time' => '08:00',
                'registration_daily_close_time' => '20:00',
                'registration_closed_message' => 'Silakan kembali sesuai jadwal.',
            ])
            ->assertRedirect()
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('school_settings', ['key' => 'quota_public_visible', 'value' => '0']);
        $this->assertDatabaseHas('school_settings', ['key' => 'quota_display_mode', 'value' => 'remaining']);
        $this->assertDatabaseHas('school_settings', ['key' => 'quota_count_mode', 'value' => 'manual']);
        $this->assertDatabaseHas('school_settings', ['key' => 'registration_mode', 'value' => 'scheduled']);
        $this->assertDatabaseHas('school_settings', ['key' => 'registration_daily_hours_enabled', 'value' => '1']);
        $this->assertDatabaseHas('school_settings', ['key' => 'registration_daily_open_time', 'value' => '08:00']);
        $this->assertDatabaseHas('school_settings', ['key' => 'registration_daily_close_time', 'value' => '20:00']);
        $this->assertDatabaseHas('school_settings', ['key' => 'registration_closed_message', 'value' => 'Silakan kembali sesuai jadwal.']);
    }

    public function test_level_admin_cannot_change_transparency_or_schedule_settings(): void
    {
        $this->createSettings(['registration_mode' => 'open', 'quota_display_mode' => 'full']);

        $admin = User::factory()->forLevel('SD')->create();

        $this->actingAs($admin)
            ->get(route('admin.ppdb-settings.index'))
            ->assertOk()
            ->assertDontSee('name="quota_public_visible"', false)
            ->assertDontSee('name="registration_mode"', false);

        $this->actingAs($admin)
            ->put(route('admin.ppdb-settings.update'), [
                'quota_public_visible' => '0',
                'quota_display_mode' => 'status',
                'registration_mode' => 'closed',
            ])
            ->assertRedirect()
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('school_settings', ['key' => 'quota_public_visible', 'value' => '1']);
        $this->assertDatabaseHas('school_settings', ['key' => 'quota_display_mode', 'value' => 'full']);
        $this->assertDatabaseHas('school_settings', ['key' => 'registration_mode', 'value' => 'open']);
    }
}
