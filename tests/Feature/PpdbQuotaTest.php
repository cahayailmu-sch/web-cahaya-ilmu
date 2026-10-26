<?php

namespace Tests\Feature;

use App\Models\PpdbRegistration;
use App\Models\SchoolSetting;
use App\Models\User;
use Database\Seeders\PpdbQuotaSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PpdbQuotaTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');
    }

    /**
     * Seed the school settings and default SPMB quotas.
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

        foreach ($overrides as $key => $value) {
            if (str_starts_with($key, 'quota_')) {
                SchoolSetting::updateOrCreate(['key' => $key], ['value' => (string) $value]);
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
            'registration_number' => 'SPMB-QUOTA-'.str_pad((string) (PpdbRegistration::count() + 1), 3, '0', STR_PAD_LEFT),
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

    public function test_public_ppdb_page_shows_quota_availability_and_full_badge(): void
    {
        $this->createSettings(['quota_sd_l' => '1']);
        $this->createRegistration(['gender' => 'L', 'level' => 'SD']);

        $this->get(route('ppdb'))
            ->assertOk()
            ->assertSee('Ketersediaan Kuota SPMB')
            ->assertSee('Laki-laki')
            ->assertSee('Perempuan')
            ->assertSee('PENUH')
            ->assertSee('Sisa')
            ->assertSee('window.CI_PPDB_QUOTA', false);
    }

    public function test_public_ppdb_page_shows_both_gender_full_banner_when_both_quotas_are_full(): void
    {
        $this->createSettings(['quota_sd_l' => '1', 'quota_sd_p' => '1']);
        $this->createRegistration(['gender' => 'L', 'level' => 'SD']);
        $this->createRegistration(['gender' => 'P', 'level' => 'SD']);

        $this->get(route('ppdb'))
            ->assertOk()
            ->assertSee('Kuota jenjang SD sudah PENUH untuk Laki-laki dan Perempuan.')
            ->assertSee('id="quota-both-full-banner"', false);
    }

    public function test_registration_is_rejected_when_selected_gender_quota_is_full(): void
    {
        $this->createSettings(['quota_sd_l' => '1']);
        $this->createRegistration(['gender' => 'L', 'level' => 'SD']);

        $response = $this->from(route('ppdb'))
            ->post(route('ppdb.store'), $this->registrationData(['gender' => 'L', 'level' => 'SD']));

        $response->assertRedirect(route('ppdb'))
            ->assertSessionHasErrors('gender');

        $this->assertStringContainsString('sudah PENUH (1/1)', session('errors')->first('gender'));
        $this->assertDatabaseCount('ppdb_registrations', 1);
    }

    public function test_opposite_gender_can_still_register_when_one_gender_is_full(): void
    {
        $this->createSettings(['quota_sd_l' => '1']);
        $this->createRegistration(['gender' => 'L', 'level' => 'SD']);

        $this->post(route('ppdb.store'), $this->registrationData(['gender' => 'P', 'level' => 'SD']))
            ->assertRedirect()
            ->assertSessionHasNoErrors()
            ->assertSessionHas('ppdb_success');

        $this->assertDatabaseHas('ppdb_registrations', [
            'level' => 'SD',
            'gender' => 'P',
        ]);
    }

    public function test_rejected_registrations_do_not_consume_quota(): void
    {
        $this->createSettings(['quota_sd_l' => '1']);
        $this->createRegistration(['gender' => 'L', 'level' => 'SD', 'status' => 'Ditolak']);

        $this->post(route('ppdb.store'), $this->registrationData(['gender' => 'L', 'level' => 'SD']))
            ->assertRedirect()
            ->assertSessionHasNoErrors();

        $this->assertDatabaseCount('ppdb_registrations', 2);
    }

    public function test_superadmin_can_update_quotas_for_every_level(): void
    {
        $this->createSettings();

        $this->actingAs(User::factory()->create())
            ->put(route('admin.ppdb-settings.update'), [
                'quota_sd_l' => 60,
                'quota_sd_p' => 55,
                'quota_tk_l' => 30,
                'quota_tk_p' => 35,
            ])
            ->assertRedirect()
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('school_settings', ['key' => 'quota_sd_l', 'value' => '60']);
        $this->assertDatabaseHas('school_settings', ['key' => 'quota_sd_p', 'value' => '55']);
        $this->assertDatabaseHas('school_settings', ['key' => 'quota_tk_l', 'value' => '30']);
        $this->assertDatabaseHas('school_settings', ['key' => 'quota_tk_p', 'value' => '35']);
    }

    public function test_superadmin_can_update_kb_tk_group_quotas(): void
    {
        $this->createSettings();

        $this->actingAs(User::factory()->create())
            ->put(route('admin.ppdb-settings.update'), [
                'quota_tk_kb' => 25,
                'quota_tk_a' => 45,
                'quota_tk_b' => 55,
            ])
            ->assertRedirect()
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('school_settings', ['key' => 'quota_tk_kb', 'value' => '25']);
        $this->assertDatabaseHas('school_settings', ['key' => 'quota_tk_a', 'value' => '45']);
        $this->assertDatabaseHas('school_settings', ['key' => 'quota_tk_b', 'value' => '55']);
    }

    public function test_tk_admin_can_update_kb_tk_group_quotas(): void
    {
        $this->createSettings();

        $tkAdmin = User::factory()->forLevel('TK')->create();

        $this->actingAs($tkAdmin)
            ->get(route('admin.ppdb-settings.index'))
            ->assertOk()
            ->assertSee('name="quota_tk_kb"', false)
            ->assertSee('name="quota_tk_a"', false)
            ->assertSee('name="quota_tk_b"', false)
            ->assertDontSee('name="quota_tk_l"', false)
            ->assertDontSee('name="quota_sd_l"', false);

        $this->actingAs($tkAdmin)
            ->put(route('admin.ppdb-settings.update'), [
                'quota_tk_kb' => 21,
                'quota_tk_a' => 41,
                'quota_tk_b' => 42,
            ])
            ->assertRedirect()
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('school_settings', ['key' => 'quota_tk_kb', 'value' => '21']);
        $this->assertDatabaseHas('school_settings', ['key' => 'quota_tk_a', 'value' => '41']);
        $this->assertDatabaseHas('school_settings', ['key' => 'quota_tk_b', 'value' => '42']);
    }

    public function test_tk_quota_is_counted_per_group_not_per_gender(): void
    {
        $this->createSettings([
            'quota_tk_kb' => '1',
            'quota_tk_a' => '1',
            'quota_tk_b' => '1',
            'quota_tk_l' => '0',
            'quota_tk_p' => '0',
        ]);

        $this->createRegistration([
            'gender' => 'L',
            'level' => 'TK',
            'sub_level' => 'TK A',
        ]);

        $this->post(route('ppdb.store'), $this->registrationData([
            'level' => 'TK',
            'sub_level' => 'TK A',
            'gender' => 'P',
            'birth_date' => now()->subYears(5)->toDateString(),
        ]))->assertSessionHasErrors('sub_level');

        $this->post(route('ppdb.store'), $this->registrationData([
            'level' => 'TK',
            'sub_level' => 'KB',
            'gender' => 'P',
            'birth_date' => now()->subYears(4)->toDateString(),
        ]))->assertSessionHasNoErrors();

        $this->assertDatabaseHas('ppdb_registrations', [
            'level' => 'TK',
            'sub_level' => 'KB',
            'gender' => 'P',
        ]);
    }

    public function test_tk_registration_is_rejected_when_selected_group_is_full(): void
    {
        $this->createSettings(['quota_tk_a' => '1']);

        $this->createRegistration([
            'gender' => 'L',
            'level' => 'TK',
            'sub_level' => 'TK A',
        ]);

        $response = $this->from(route('ppdb'))
            ->post(route('ppdb.store'), $this->registrationData([
                'level' => 'TK',
                'sub_level' => 'TK A',
                'gender' => 'L',
                'birth_date' => now()->subYears(5)->toDateString(),
            ]));

        $response->assertRedirect(route('ppdb'))
            ->assertSessionHasErrors('sub_level');

        $this->assertStringContainsString('PENUH (1/1)', session('errors')->first('sub_level'));
        $this->assertDatabaseCount('ppdb_registrations', 1);
    }

    public function test_public_ppdb_page_shows_kb_tk_group_quotas(): void
    {
        $this->createSettings([
            'quota_tk_kb' => '5',
            'quota_tk_a' => '6',
            'quota_tk_b' => '7',
        ]);

        $this->get(route('ppdb'))
            ->assertOk()
            ->assertSee('Kelompok KB')
            ->assertSee('Kelompok TK A')
            ->assertSee('Kelompok TK B')
            ->assertSee('Sisa 5 dari 5')
            ->assertSee('Sisa 6 dari 6')
            ->assertSee('Sisa 7 dari 7')
            ->assertSee('quota-group-kb-count', false)
            ->assertSee('quota-group-tk_a-count', false)
            ->assertSee('quota-group-tk_b-count', false);
    }

    public function test_level_admin_can_only_update_their_own_level_quota(): void
    {
        $this->createSettings(['quota_tk_l' => '10', 'quota_sd_l' => '20']);

        $admin = User::factory()->forLevel('SD')->create();

        $this->actingAs($admin)
            ->get(route('admin.ppdb-settings.index'))
            ->assertOk()
            ->assertSee('name="quota_sd_l"', false)
            ->assertDontSee('name="quota_tk_l"', false);

        $this->actingAs($admin)
            ->put(route('admin.ppdb-settings.update'), [
                'quota_sd_l' => 45,
                'quota_sd_p' => 50,
                'quota_tk_l' => 999,
            ])
            ->assertRedirect()
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('school_settings', ['key' => 'quota_sd_l', 'value' => '45']);
        $this->assertDatabaseHas('school_settings', ['key' => 'quota_sd_p', 'value' => '50']);
        $this->assertDatabaseHas('school_settings', ['key' => 'quota_tk_l', 'value' => '10']);
    }

    public function test_level_admin_cannot_change_other_level_wa_groups_or_contact(): void
    {
        $this->createSettings([
            'wa_group_tk_l' => 'https://chat.whatsapp.com/grup-tk-lama',
            'wa_admin_tk' => '628111111111',
        ]);

        $admin = User::factory()->forLevel('SD')->create();

        $this->actingAs($admin)
            ->get(route('admin.ppdb-settings.index'))
            ->assertOk()
            ->assertSee('name="wa_group_sd_l"', false)
            ->assertSee('name="wa_admin_sd"', false)
            ->assertDontSee('name="wa_group_tk_l"', false)
            ->assertDontSee('name="wa_admin_tk"', false)
            ->assertDontSee('name="status_ppdb"', false)
            ->assertDontSee('name="bank_tk"', false);

        $this->actingAs($admin)
            ->put(route('admin.ppdb-settings.update'), [
                'wa_group_tk_l' => 'https://chat.whatsapp.com/grup-tk-baru',
                'wa_admin_tk' => '628999999999',
                'quota_tk_l' => 999,
            ])
            ->assertRedirect()
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('school_settings', ['key' => 'wa_group_tk_l', 'value' => 'https://chat.whatsapp.com/grup-tk-lama']);
        $this->assertDatabaseHas('school_settings', ['key' => 'wa_admin_tk', 'value' => '628111111111']);
        $this->assertDatabaseMissing('school_settings', ['key' => 'quota_tk_l', 'value' => '999']);
    }

    public function test_dashboard_displays_quota_progress_for_each_level(): void
    {
        $this->createSettings(['quota_sd_l' => '1', 'quota_sd_p' => '2']);
        $this->createRegistration(['gender' => 'L', 'level' => 'SD']);

        $this->actingAs(User::factory()->create())
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('Progress Kuota SPMB')
            ->assertSee('Terisi: 1 / 1 Laki-laki')
            ->assertSee('Terisi: 0 / 2 Perempuan');
    }
}
