<?php

namespace Tests\Feature;

use App\Models\PpdbRegistration;
use App\Models\ReferralCode;
use App\Models\SchoolSetting;
use App\Models\User;
use Database\Seeders\PpdbQuotaSeeder;
use Database\Seeders\ReferralCodeSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ReferralCodeTest extends TestCase
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
            'foundation_name' => 'Yayasan Ath-Thoifah Al-Manshuroh Balikpapan',
            'alamat_sekolah' => 'Jl. Soekarno Hatta Km. 8 Balikpapan',
            'wa_admin_tk' => '081250000001',
        ];

        foreach (['tk', 'sd', 'smp', 'sma'] as $level) {
            $settings += [
                "ppdb_status_{$level}" => 'Buka',
                "ppdb_start_{$level}" => now()->subMonth()->toDateString(),
                "ppdb_end_{$level}" => now()->addMonth()->toDateString(),
                "biaya_{$level}" => $level === 'tk' ? '250000' : '0',
                "bank_{$level}" => 'Bank Syariah Indonesia (BSI)',
                "rek_{$level}" => '3115917772',
                "rek_nama_{$level}" => 'TK Islam Cahaya Ilmu',
            ];
        }

        foreach (array_merge($settings, $overrides) as $key => $value) {
            SchoolSetting::updateOrCreate(['key' => $key], ['value' => (string) $value]);
        }

        $this->seed(PpdbQuotaSeeder::class);
    }

    /**
     * Build valid KB-TK registration form data.
     *
     * @param  array<string, mixed>  $overrides
     * @return array<string, mixed>
     */
    private function tkRegistrationData(array $overrides = []): array
    {
        return array_merge([
            'full_name' => 'Ahmad Fauzan',
            'gender' => 'L',
            'birth_place' => 'Balikpapan',
            'birth_date' => '2022-06-01',
            'level' => 'TK',
            'sub_level' => 'TK A',
            'previous_school' => 'KB Islam Cahaya Ilmu',
            'parent_name' => 'Budi Santoso',
            'whatsapp' => '081250000099',
            'address' => 'Jl. Pendidikan No. 1, Balikpapan',
            'payment_proof' => UploadedFile::fake()->image('bukti-pembayaran.jpg'),
            'persetujuan' => '1',
        ], $overrides);
    }

    private function makeLegacySiblingCode(): ReferralCode
    {
        return ReferralCode::create([
            'code' => 'ALUMNI26-01',
            'type' => ReferralCode::TYPE_SIBLING_ALUMNI,
            'discount_amount' => 500000,
            'max_uses' => 10,
            'used_count' => 0,
            'is_active' => true,
            'notes' => 'Legacy sibling code',
        ]);
    }

    public function test_check_endpoint_returns_event_free_registration_details(): void
    {
        $this->createSettings();
        $this->seed(ReferralCodeSeeder::class);

        $this->postJson(route('spmb.check-referral'), ['code' => 'ci26-k7p4'])
            ->assertOk()
            ->assertJson([
                'valid' => true,
                'code' => 'CI26-K7P4',
                'type' => ReferralCode::TYPE_EVENT_FREE_REG,
                'waives_registration_fee' => true,
                'discount_amount' => 250000,
                'message' => '✅ Kode Event Muslim Vibes Valid! Anda mendapatkan GRATIS Biaya Pendaftaran sebesar Rp 250.000.',
            ])
            ->assertJsonMissingPath('requires_alumni_names');
    }

    public function test_check_endpoint_rejects_legacy_sibling_codes(): void
    {
        $this->createSettings();
        $this->makeLegacySiblingCode();

        $this->postJson(route('spmb.check-referral'), ['code' => 'ALUMNI26-01'])
            ->assertOk()
            ->assertJson([
                'valid' => false,
                'reason' => 'inactive',
            ]);
    }

    public function test_check_endpoint_rejects_unknown_exhausted_and_inactive_codes(): void
    {
        $this->createSettings();
        $this->seed(ReferralCodeSeeder::class);

        ReferralCode::query()->where('code', 'CI26-M9X2')->update([
            'used_count' => 1,
        ]);

        ReferralCode::query()->where('code', 'CI26-R5T8')->update([
            'is_active' => false,
        ]);

        $this->postJson(route('spmb.check-referral'), ['code' => 'TIDAK-ADA'])
            ->assertOk()
            ->assertJson([
                'valid' => false,
                'reason' => 'not_found',
            ]);

        $this->postJson(route('spmb.check-referral'), ['code' => 'CI26-M9X2'])
            ->assertOk()
            ->assertJson([
                'valid' => false,
                'reason' => 'exhausted',
            ]);

        $this->postJson(route('spmb.check-referral'), ['code' => 'CI26-R5T8'])
            ->assertOk()
            ->assertJson([
                'valid' => false,
                'reason' => 'inactive',
            ]);
    }

    public function test_get_endpoint_normalizes_lowercase_codes(): void
    {
        $this->createSettings();
        $this->seed(ReferralCodeSeeder::class);

        $this->getJson(route('ppdb.check-referral', ['code' => 'ci26-h3n7']))
            ->assertOk()
            ->assertJson([
                'valid' => true,
                'code' => 'CI26-H3N7',
                'waives_registration_fee' => true,
            ]);
    }

    public function test_tk_registration_with_event_code_is_free(): void
    {
        $this->createSettings();
        $this->seed(ReferralCodeSeeder::class);

        $this->post(route('ppdb.store'), $this->tkRegistrationData([
            'referral_code' => 'CI26-K7P4',
            'payment_proof' => null,
        ]))->assertSessionHasNoErrors();

        $registration = PpdbRegistration::firstOrFail();

        $this->assertSame(0, $registration->final_fee);
        $this->assertSame(250000, $registration->discount_amount);
        $this->assertSame('CI26-K7P4', $registration->referral_code);
        $this->assertSame(1, ReferralCode::findByCode('CI26-K7P4')->used_count);
    }

    public function test_tk_registration_rejects_legacy_sibling_code(): void
    {
        $this->createSettings();
        $this->makeLegacySiblingCode();

        $this->post(route('ppdb.store'), $this->tkRegistrationData([
            'referral_code' => 'ALUMNI26-01',
        ]))->assertSessionHasErrors('referral_code');
    }

    public function test_admin_can_generate_event_code(): void
    {
        $admin = User::factory()->forLevel('TK')->create();

        $response = $this->actingAs($admin)
            ->getJson(route('admin.referral-codes.generate'))
            ->assertOk();

        $this->assertStringStartsWith('CI26-', (string) $response->json('code'));
    }

    public function test_admin_store_always_creates_event_code(): void
    {
        $admin = User::factory()->forLevel('TK')->create();

        $this->actingAs($admin)
            ->post(route('admin.referral-codes.store'), [
                'code' => 'ci26-test',
                'type' => ReferralCode::TYPE_SIBLING_ALUMNI,
                'discount_amount' => 250000,
                'max_uses' => 1,
                'notes' => 'Voucher event baru',
                'is_active' => '1',
            ])
            ->assertSessionHasNoErrors();

        $created = ReferralCode::findByCode('CI26-TEST');

        $this->assertNotNull($created);
        $this->assertSame(ReferralCode::TYPE_EVENT_FREE_REG, $created->type);
        $this->assertSame(250000, $created->discount_amount);
    }

    public function test_admin_update_and_reset_event_codes(): void
    {
        $this->createSettings();
        $this->seed(ReferralCodeSeeder::class);
        $admin = User::factory()->forLevel('TK')->create();

        $code = ReferralCode::findByCode('CI26-K7P4');
        $code->update(['used_count' => 1, 'used_by_registration_id' => null]);

        $this->actingAs($admin)
            ->put(route('admin.referrals.update', $code), [
                'code' => 'CI26-K7P4',
                'type' => ReferralCode::TYPE_EVENT_FREE_REG,
                'discount_amount' => 250000,
                'max_uses' => 5,
                'notes' => 'Diperbarui',
                'is_active' => '1',
            ])
            ->assertSessionHasNoErrors();

        $this->assertSame(5, $code->fresh()->max_uses);

        $this->actingAs($admin)
            ->post(route('admin.referrals.reset-event'))
            ->assertSessionHasNoErrors();

        $this->assertSame(0, $code->fresh()->used_count);
    }

    public function test_admin_can_delete_referral_code(): void
    {
        $this->createSettings();
        $this->seed(ReferralCodeSeeder::class);
        $admin = User::factory()->forLevel('TK')->create();

        $code = ReferralCode::findByCode('CI26-Z5K6');

        $this->actingAs($admin)
            ->delete(route('admin.referrals.destroy', $code))
            ->assertSessionHasNoErrors();

        $this->assertNull(ReferralCode::findByCode('CI26-Z5K6'));
    }

    public function test_sd_admin_cannot_manage_referral_codes(): void
    {
        $sdAdmin = User::factory()->forLevel('SD')->create();

        $this->actingAs($sdAdmin)
            ->post(route('admin.referral-codes.store'), [
                'code' => 'CI26-NOPE',
                'type' => ReferralCode::TYPE_EVENT_FREE_REG,
                'discount_amount' => 250000,
                'max_uses' => 1,
            ])
            ->assertForbidden();
    }
}
