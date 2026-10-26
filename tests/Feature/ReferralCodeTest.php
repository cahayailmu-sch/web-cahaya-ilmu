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
                'requires_alumni_names' => false,
                'discount_amount' => 250000,
                'message' => '✅ Kode Event Muslim Vibes Valid! Anda mendapatkan GRATIS Biaya Pendaftaran sebesar Rp 250.000.',
            ]);
    }

    public function test_check_endpoint_returns_sibling_requirements(): void
    {
        $this->createSettings();
        $this->seed(ReferralCodeSeeder::class);

        $this->postJson(route('spmb.check-referral'), ['code' => 'ALUMNI26-01'])
            ->assertOk()
            ->assertJson([
                'valid' => true,
                'code' => 'ALUMNI26-01',
                'type' => ReferralCode::TYPE_SIBLING_ALUMNI,
                'waives_registration_fee' => false,
                'requires_alumni_names' => true,
                'discount_amount' => 500000,
                'message' => '✅ Kode Referral Kakak Alumni Valid! Anda berhak mendapatkan Potongan Harga sebesar Rp 500.000 (Min. 2 Kakak Alumni KB-TK).',
            ]);
    }

    public function test_check_endpoint_rejects_unknown_and_exhausted_codes(): void
    {
        $this->createSettings();
        $this->seed(ReferralCodeSeeder::class);

        ReferralCode::query()->where('code', 'CI26-M9X2')->update([
            'used_count' => 1,
        ]);

        $this->postJson(route('spmb.check-referral'), ['code' => 'TIDAK-ADA'])
            ->assertOk()
            ->assertJson([
                'valid' => false,
                'reason' => 'not_found',
                'message' => 'Kode referral tidak ditemukan. Periksa kembali penulisan kode Anda.',
            ]);

        $this->postJson(route('spmb.check-referral'), ['code' => 'CI26-M9X2'])
            ->assertOk()
            ->assertJson([
                'valid' => false,
                'reason' => 'exhausted',
                'message' => 'Kuota kode referral ini sudah habis terpakai.',
            ]);
    }

    public function test_check_endpoint_reports_inactive_codes_separately(): void
    {
        $this->createSettings();
        $this->seed(ReferralCodeSeeder::class);

        ReferralCode::query()->where('code', 'CI26-Q8L4')->update(['is_active' => false]);

        $this->getJson(route('spmb.check-referral', ['code' => 'CI26-Q8L4']))
            ->assertOk()
            ->assertJson([
                'valid' => false,
                'reason' => 'inactive',
                'message' => 'Kode referral ini sedang dinonaktifkan.',
            ]);
    }

    public function test_check_endpoint_supports_get_relative_alias_and_messy_input(): void
    {
        $this->createSettings();
        $this->seed(ReferralCodeSeeder::class);

        $messyCode = "\u{00A0}ci26\u{2013}k7p4\u{200B}";

        $this->getJson(route('spmb.check-referral', ['code' => $messyCode]))
            ->assertOk()
            ->assertJson([
                'valid' => true,
                'code' => 'CI26-K7P4',
            ]);

        $this->getJson(route('ppdb.check-referral', ['code' => 'alumni26-01']))
            ->assertOk()
            ->assertJson([
                'valid' => true,
                'code' => 'ALUMNI26-01',
                'requires_alumni_names' => true,
            ]);
    }

    public function test_check_endpoint_seeds_event_codes_when_referral_table_is_empty(): void
    {
        $this->createSettings();

        $this->assertSame(0, ReferralCode::query()->count());

        $this->getJson(route('spmb.check-referral', ['code' => 'CI26-K7P4']))
            ->assertOk()
            ->assertJson([
                'valid' => true,
                'code' => 'CI26-K7P4',
                'waives_registration_fee' => true,
            ]);

        $this->assertSame(10, ReferralCode::query()->count());
        $this->assertSame(10, ReferralCode::query()->eventFree()->count());
        $this->assertSame(0, ReferralCode::findByCode('CI26-K7P4')->used_count);
    }

    public function test_event_code_registers_without_payment_proof_and_marks_code_used(): void
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
        $this->assertSame('FREE - KODE REFERRAL: CI26-K7P4', $registration->payment_proof);
        $this->assertTrue($registration->paymentProofIsFree());
        $this->assertNull($registration->paymentProofUrl());

        $code = ReferralCode::findByCode('CI26-K7P4');
        $this->assertSame(1, $code->used_count);
        $this->assertSame($registration->id, $code->used_by_registration_id);
        $this->assertFalse($code->isAvailable());
    }

    public function test_event_code_cannot_be_used_by_a_second_registrant(): void
    {
        $this->createSettings();
        $this->seed(ReferralCodeSeeder::class);

        $this->post(route('ppdb.store'), $this->tkRegistrationData([
            'referral_code' => 'CI26-R5T8',
            'payment_proof' => null,
        ]))->assertSessionHasNoErrors();

        $this->post(route('ppdb.store'), $this->tkRegistrationData([
            'full_name' => 'Pendaftar Kedua',
            'referral_code' => 'CI26-R5T8',
            'payment_proof' => null,
        ]))->assertSessionHasErrors('referral_code');

        $this->assertDatabaseCount('ppdb_registrations', 1);
        $this->assertSame(1, ReferralCode::findByCode('CI26-R5T8')->used_count);
    }

    public function test_sibling_code_requires_alumni_names_and_keeps_normal_fee(): void
    {
        $this->createSettings();
        $this->seed(ReferralCodeSeeder::class);

        $this->post(route('ppdb.store'), $this->tkRegistrationData([
            'referral_code' => 'ALUMNI26-01',
        ]))->assertSessionHasErrors('referral_alumni_names');

        $this->assertDatabaseCount('ppdb_registrations', 0);

        $this->post(route('ppdb.store'), $this->tkRegistrationData([
            'referral_code' => 'ALUMNI26-01',
            'referral_alumni_names' => 'Aisyah (TK B 2021), Fatimah (TK A 2022)',
        ]))->assertSessionHasNoErrors();

        $registration = PpdbRegistration::firstOrFail();

        $this->assertSame(250000, $registration->final_fee);
        $this->assertSame(500000, $registration->discount_amount);
        $this->assertSame('ALUMNI26-01', $registration->referral_code);
        $this->assertSame('Kakak Alumni: Aisyah (TK B 2021), Fatimah (TK A 2022)', $registration->referral_note);
        $this->assertNotNull($registration->paymentProofUrl());

        $code = ReferralCode::findByCode('ALUMNI26-01');
        $this->assertSame(1, $code->used_count);
        $this->assertNull($code->used_by_registration_id);
    }

    public function test_referral_code_is_rejected_for_non_tk_levels(): void
    {
        $this->createSettings();
        $this->seed(ReferralCodeSeeder::class);

        $this->post(route('ppdb.store'), $this->tkRegistrationData([
            'level' => 'SD',
            'sub_level' => null,
            'birth_date' => '2019-06-01',
            'referral_code' => 'CI26-K7P4',
        ]))->assertSessionHasErrors('referral_code');

        $this->assertDatabaseCount('ppdb_registrations', 0);
        $this->assertSame(0, ReferralCode::findByCode('CI26-K7P4')->used_count);
    }

    public function test_tk_admin_can_view_and_manage_referral_codes(): void
    {
        $this->createSettings();
        $this->seed(ReferralCodeSeeder::class);

        $admin = User::factory()->forLevel('TK')->create();

        $this->actingAs($admin)
            ->get(route('admin.ppdb-settings.index'))
            ->assertOk()
            ->assertSee('Kelola Kode Referral')
            ->assertSee('CI26-K7P4')
            ->assertSee('ALUMNI26-01')
            ->assertSee('Tersedia');

        $this->actingAs($admin)
            ->post(route('admin.referral-codes.store'), [
                'code' => 'alumni26-99',
                'max_uses' => 5,
                'notes' => 'Keluarga alumni angkatan 2020',
            ])
            ->assertRedirect()
            ->assertSessionHasNoErrors();

        $created = ReferralCode::findByCode('ALUMNI26-99');
        $this->assertNotNull($created);
        $this->assertSame(ReferralCode::TYPE_SIBLING_ALUMNI, $created->type);
        $this->assertSame(500000, $created->discount_amount);
        $this->assertSame(5, $created->max_uses);

        $this->assertDatabaseHas('activity_logs', [
            'action' => 'create',
            'module' => 'Pengaturan SPMB',
        ]);

        $this->actingAs($admin)
            ->getJson(route('admin.referral-codes.generate'))
            ->assertOk()
            ->assertJsonStructure(['code']);

        $this->actingAs($admin)
            ->patch(route('admin.referral-codes.toggle', $created))
            ->assertRedirect();

        $this->assertFalse($created->fresh()->is_active);

        $this->actingAs($admin)
            ->delete(route('admin.referrals.destroy', $created))
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertNull(ReferralCode::findByCode('ALUMNI26-99'));

        $this->assertDatabaseHas('activity_logs', [
            'action' => 'delete',
            'module' => 'Pengaturan SPMB',
        ]);
    }

    public function test_tk_admin_can_create_event_referral_codes_with_custom_type_and_discount(): void
    {
        $this->createSettings();
        $this->seed(ReferralCodeSeeder::class);

        $admin = User::factory()->forLevel('TK')->create();

        $this->actingAs($admin)
            ->post(route('admin.referral-codes.store'), [
                'code' => 'event-susulan-01',
                'type' => ReferralCode::TYPE_EVENT_FREE_REG,
                'discount_amount' => 250000,
                'max_uses' => 3,
                'notes' => 'Event susulan Muslim Vibes',
                'is_active' => '1',
            ])
            ->assertRedirect()
            ->assertSessionHasNoErrors();

        $created = ReferralCode::findByCode('EVENT-SUSULAN-01');

        $this->assertNotNull($created);
        $this->assertSame(ReferralCode::TYPE_EVENT_FREE_REG, $created->type);
        $this->assertSame(250000, $created->discount_amount);
        $this->assertSame(3, $created->max_uses);
        $this->assertTrue($created->is_active);
    }

    public function test_event_codes_cannot_be_toggled_but_can_be_deleted_and_other_admins_are_forbidden(): void
    {
        $this->createSettings();
        $this->seed(ReferralCodeSeeder::class);

        $eventCode = ReferralCode::findByCode('CI26-K7P4');

        $tkAdmin = User::factory()->forLevel('TK')->create();

        $this->actingAs($tkAdmin)
            ->patch(route('admin.referral-codes.toggle', $eventCode))
            ->assertRedirect()
            ->assertSessionHas('error');

        $this->assertTrue($eventCode->fresh()->is_active);

        $this->actingAs($tkAdmin)
            ->delete(route('admin.referrals.destroy', $eventCode))
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertNull(ReferralCode::findByCode('CI26-K7P4'));

        $this->assertDatabaseHas('activity_logs', [
            'action' => 'delete',
            'module' => 'Pengaturan SPMB',
        ]);

        $sdAdmin = User::factory()->forLevel('SD')->create();
        $sibling = ReferralCode::findByCode('ALUMNI26-01');

        $this->actingAs($sdAdmin)
            ->get(route('admin.ppdb-settings.index'))
            ->assertOk()
            ->assertDontSee('Kelola Kode Referral');

        $this->actingAs($sdAdmin)
            ->post(route('admin.referral-codes.store'), [
                'code' => 'SD-ONLY-01',
                'max_uses' => 1,
            ])
            ->assertForbidden();

        $this->actingAs($sdAdmin)
            ->delete(route('admin.referrals.destroy', $sibling))
            ->assertForbidden();

        $this->assertNotNull($sibling->fresh());
    }

    public function test_admin_list_and_printed_document_show_referral_details(): void
    {
        $this->createSettings();
        $this->seed(ReferralCodeSeeder::class);

        $this->post(route('ppdb.store'), $this->tkRegistrationData([
            'referral_code' => 'CI26-H3N7',
            'payment_proof' => null,
        ]))->assertSessionHasNoErrors();

        $registration = PpdbRegistration::firstOrFail();
        $admin = User::factory()->forLevel('TK')->create();

        $this->actingAs($admin)
            ->get(route('admin.ppdb.index'))
            ->assertOk()
            ->assertSee('CI26-H3N7')
            ->assertSee('GRATIS');

        $this->actingAs($admin)
            ->get(route('admin.ppdb-settings.index'))
            ->assertOk()
            ->assertSee('Sudah Dipakai oleh: Ahmad Fauzan');

        $this->actingAs($admin)
            ->get(route('admin.ppdb.print', $registration))
            ->assertOk()
            ->assertSee('CI26-H3N7')
            ->assertSee('Diskon Rp 250.000')
            ->assertSee('GRATIS');
    }

    public function test_tk_admin_can_edit_referral_codes(): void
    {
        $this->createSettings();
        $this->seed(ReferralCodeSeeder::class);

        $admin = User::factory()->forLevel('TK')->create();
        $code = ReferralCode::findByCode('CI26-K7P4');

        $this->actingAs($admin)
            ->patch(route('admin.referrals.update', $code), [
                'code' => 'ci26-baru01',
                'type' => ReferralCode::TYPE_SIBLING_ALUMNI,
                'discount_amount' => 750000,
                'max_uses' => 4,
                'notes' => 'Keluarga alumni angkatan 2020',
                'is_active' => '1',
            ])
            ->assertRedirect()
            ->assertSessionHasNoErrors();

        $updated = $code->fresh();

        $this->assertSame('CI26-BARU01', $updated->code);
        $this->assertSame(ReferralCode::TYPE_SIBLING_ALUMNI, $updated->type);
        $this->assertSame(750000, $updated->discount_amount);
        $this->assertSame(4, $updated->max_uses);
        $this->assertSame('Keluarga alumni angkatan 2020', $updated->notes);
        $this->assertTrue($updated->is_active);

        $this->assertDatabaseHas('activity_logs', [
            'action' => 'update',
            'module' => 'Pengaturan SPMB',
        ]);
    }

    public function test_referral_edit_validates_unique_code_and_usage_limit(): void
    {
        $this->createSettings();
        $this->seed(ReferralCodeSeeder::class);

        $admin = User::factory()->forLevel('TK')->create();
        $code = ReferralCode::findByCode('CI26-K7P4');

        $this->actingAs($admin)
            ->patch(route('admin.referrals.update', $code), [
                'code' => 'alumni26-01',
                'type' => ReferralCode::TYPE_EVENT_FREE_REG,
                'discount_amount' => 250000,
                'max_uses' => 1,
            ])
            ->assertSessionHasErrors(['code'], null, 'referralEdit');

        $code->update(['used_count' => 2]);

        $this->actingAs($admin)
            ->patch(route('admin.referrals.update', $code), [
                'code' => $code->code,
                'type' => ReferralCode::TYPE_EVENT_FREE_REG,
                'discount_amount' => 250000,
                'max_uses' => 1,
            ])
            ->assertSessionHasErrors(['max_uses'], null, 'referralEdit');

        $this->assertSame('CI26-K7P4', $code->fresh()->code);
    }

    public function test_tk_admin_can_reset_a_used_referral_code(): void
    {
        $this->createSettings();
        $this->seed(ReferralCodeSeeder::class);

        $this->post(route('ppdb.store'), $this->tkRegistrationData([
            'referral_code' => 'CI26-K7P4',
            'payment_proof' => null,
        ]))->assertSessionHasNoErrors();

        $registration = PpdbRegistration::firstOrFail();
        $code = ReferralCode::findByCode('CI26-K7P4');

        $this->assertSame(1, $code->used_count);
        $this->assertSame($registration->id, $code->used_by_registration_id);
        $this->assertFalse($code->isAvailable());

        $admin = User::factory()->forLevel('TK')->create();

        $this->actingAs($admin)
            ->post(route('admin.referrals.reset', $code))
            ->assertRedirect()
            ->assertSessionHas('success');

        $code->refresh();

        $this->assertSame(0, $code->used_count);
        $this->assertNull($code->used_by_registration_id);
        $this->assertTrue($code->is_active);
        $this->assertTrue($code->isAvailable());

        $this->assertDatabaseHas('activity_logs', [
            'action' => 'reset',
            'module' => 'Pengaturan SPMB',
        ]);
    }

    public function test_tk_admin_can_reset_all_event_codes_without_touching_sibling_codes(): void
    {
        $this->createSettings();
        $this->seed(ReferralCodeSeeder::class);

        ReferralCode::query()->eventFree()->update([
            'used_count' => 1,
            'is_active' => false,
        ]);

        $sibling = ReferralCode::findByCode('ALUMNI26-01');
        $sibling->update(['used_count' => 2]);

        $admin = User::factory()->forLevel('TK')->create();

        $this->actingAs($admin)
            ->post(route('admin.referrals.reset-event'))
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertSame(0, ReferralCode::query()->eventFree()->where('used_count', '>', 0)->count());
        $this->assertSame(0, ReferralCode::query()->eventFree()->where('is_active', false)->count());
        $this->assertSame(2, $sibling->fresh()->used_count);
    }

    public function test_rejecting_a_registration_releases_its_referral_quota(): void
    {
        $this->createSettings();
        $this->seed(ReferralCodeSeeder::class);

        $this->post(route('ppdb.store'), $this->tkRegistrationData([
            'referral_code' => 'CI26-K7P4',
            'payment_proof' => null,
        ]))->assertSessionHasNoErrors();

        $registration = PpdbRegistration::firstOrFail();
        $admin = User::factory()->forLevel('TK')->create();

        $this->actingAs($admin)
            ->patch(route('admin.ppdb.status', $registration), ['status' => PpdbRegistration::STATUS_REJECTED])
            ->assertRedirect();

        $code = ReferralCode::findByCode('CI26-K7P4');

        $this->assertSame(0, $code->used_count);
        $this->assertNull($code->used_by_registration_id);
        $this->assertTrue($code->isAvailable());
    }

    public function test_deleting_a_registration_releases_its_referral_quota(): void
    {
        $this->createSettings();
        $this->seed(ReferralCodeSeeder::class);

        $this->post(route('ppdb.store'), $this->tkRegistrationData([
            'referral_code' => 'ALUMNI26-01',
            'referral_alumni_names' => 'Aisyah (TK B 2021), Fatimah (TK A 2022)',
        ]))->assertSessionHasNoErrors();

        $registration = PpdbRegistration::firstOrFail();
        $admin = User::factory()->forLevel('TK')->create();

        $this->assertSame(1, ReferralCode::findByCode('ALUMNI26-01')->used_count);

        $this->actingAs($admin)
            ->delete(route('admin.ppdb.destroy', $registration))
            ->assertRedirect();

        $this->assertSame(0, ReferralCode::findByCode('ALUMNI26-01')->used_count);
        $this->assertDatabaseMissing('ppdb_registrations', ['id' => $registration->id]);
    }

    public function test_non_tk_admins_cannot_edit_or_reset_referral_codes(): void
    {
        $this->createSettings();
        $this->seed(ReferralCodeSeeder::class);

        $code = ReferralCode::findByCode('CI26-K7P4');
        $sdAdmin = User::factory()->forLevel('SD')->create();

        $this->actingAs($sdAdmin)
            ->patch(route('admin.referrals.update', $code), [
                'code' => 'CI26-K7P4',
                'type' => ReferralCode::TYPE_EVENT_FREE_REG,
                'discount_amount' => 250000,
                'max_uses' => 1,
            ])
            ->assertForbidden();

        $this->actingAs($sdAdmin)
            ->post(route('admin.referrals.reset', $code))
            ->assertForbidden();

        $this->actingAs($sdAdmin)
            ->post(route('admin.referrals.reset-event'))
            ->assertForbidden();
    }

    public function test_settings_page_shows_edit_reset_and_reset_all_controls(): void
    {
        $this->createSettings();
        $this->seed(ReferralCodeSeeder::class);

        ReferralCode::findByCode('CI26-K7P4')->update(['used_count' => 1]);

        $admin = User::factory()->forLevel('TK')->create();

        $this->actingAs($admin)
            ->get(route('admin.ppdb-settings.index'))
            ->assertOk()
            ->assertSee('Reset Semua Kode Event')
            ->assertSee('openReferralEditModal(', false)
            ->assertSee('Yakin ingin mereset status pemakaian kode ini agar bisa digunakan kembali?')
            ->assertSee('Edit Kode Referral')
            ->assertSee('Tambah Kode Referral Baru')
            ->assertSee('Apakah Anda yakin ingin menghapus kode referral ini?');
    }
}
