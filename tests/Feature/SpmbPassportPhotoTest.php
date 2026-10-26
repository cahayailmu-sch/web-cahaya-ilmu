<?php

namespace Tests\Feature;

use App\Models\PpdbRegistration;
use App\Models\SchoolSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Tests\TestCase;

class SpmbPassportPhotoTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');
    }

    /**
     * Seed the school settings used by the public and admin pages.
     */
    private function createSettings(): void
    {
        $settings = [
            'foundation_name' => 'Yayasan Ath-Thoifah Al-Manshuroh Balikpapan',
            'status_ppdb' => 'Buka',
            'tahun_ajaran' => '2027/2028',
            'wa_admin_tk' => '081250000001',
            'alamat_sekolah' => 'Jl. Soekarno Hatta Km. 8 Balikpapan',
        ];

        foreach ($settings as $key => $value) {
            SchoolSetting::updateOrCreate(['key' => $key], ['value' => (string) $value]);
        }
    }

    /**
     * Create a verified registration that may fill the stage-two form.
     *
     * @param  array<string, mixed>  $overrides
     */
    private function makeRegistration(array $overrides = []): PpdbRegistration
    {
        return PpdbRegistration::create(array_merge([
            'registration_number' => 'SPMB-2027-'.str_pad((string) (PpdbRegistration::count() + 1), 3, '0', STR_PAD_LEFT),
            'registration_code' => Str::random(40),
            'full_name' => 'Aisyah Humaira',
            'gender' => 'P',
            'birth_place' => 'Balikpapan',
            'birth_date' => '2022-06-01',
            'level' => 'TK',
            'sub_level' => 'TK A',
            'parent_name' => 'Budi Santoso',
            'whatsapp' => '081250000099',
            'address' => 'Jl. Pendidikan No. 1, Balikpapan',
            'status' => PpdbRegistration::STATUS_VERIFIED,
            'verified_at' => now(),
        ], $overrides));
    }

    /**
     * Build the minimal valid stage-two payload.
     *
     * @param  array<string, mixed>  $overrides
     * @return array<string, mixed>
     */
    private function stageTwoData(array $overrides = []): array
    {
        return array_merge([
            'form' => [
                'nik' => '6471019999990009',
                'ayah_nama' => 'Umar Abdullah',
                'ayah_hp' => '081234567890',
                'ibu_nama' => 'Khadijah',
            ],
            'fee' => [
                'pilihan_uang_masuk' => '1',
                'cara_bayar' => 'Cash',
                'pilihan_spp' => '1',
            ],
            'agreements' => [
                'setuju_biaya' => '1',
                'setuju_keputusan' => '1',
                'setuju_parenting' => '1',
                'setuju_data_benar' => '1',
            ],
        ], $overrides);
    }

    public function test_tk_full_form_shows_the_passport_photo_upload_with_preview(): void
    {
        $this->createSettings();
        $registration = $this->makeRegistration();

        $this->get(route('spmb.full-form', $registration->registration_code))
            ->assertOk()
            ->assertSee('enctype="multipart/form-data"', false)
            ->assertSee('Upload Pas Foto Ananda Ukuran 3×4')
            ->assertSee('name="pas_foto_3x4"', false)
            ->assertSee('accept="image/jpeg,image/png,image/jpg,image/webp"', false)
            ->assertSee('id="pas-foto-preview"', false)
            ->assertSee('Rasio 3 &times; 4', false);
    }

    public function test_non_tk_full_form_does_not_show_the_passport_photo_upload(): void
    {
        $this->createSettings();
        $registration = $this->makeRegistration([
            'level' => 'SD',
            'sub_level' => null,
            'birth_date' => '2019-06-01',
        ]);

        $this->get(route('spmb.full-form', $registration->registration_code))
            ->assertOk()
            ->assertDontSee('name="pas_foto_3x4"', false)
            ->assertDontSee('Upload Pas Foto Ananda Ukuran 3×4');
    }

    public function test_passport_photo_is_required_for_tk_on_the_first_submission(): void
    {
        $this->createSettings();
        $registration = $this->makeRegistration();

        $this->post(route('spmb.full-form.store', $registration->registration_code), $this->stageTwoData())
            ->assertSessionHasErrors('pas_foto_3x4');
    }

    public function test_tk_passport_photo_is_compressed_and_saved_in_full_form_data(): void
    {
        $this->createSettings();
        $registration = $this->makeRegistration();

        $this->post(route('spmb.full-form.store', $registration->registration_code), $this->stageTwoData([
            'pas_foto_3x4' => UploadedFile::fake()->image('pas-foto-ananda.jpg', 900, 1200),
        ]))->assertSessionHasNoErrors();

        $refreshed = $registration->fresh();
        $path = $refreshed->full_form_data['pas_foto_3x4'] ?? null;

        $this->assertNotNull($refreshed->completed_at);
        $this->assertIsString($path);
        $this->assertStringStartsWith('ppdb/photos/', $path);
        Storage::disk('public')->assertExists($path);
        $this->assertNotNull($refreshed->passportPhotoUrl());
    }

    public function test_existing_passport_photo_is_shown_when_reopening_the_form(): void
    {
        $this->createSettings();
        $registration = $this->makeRegistration([
            'full_form_data' => ['pas_foto_3x4' => 'ppdb/photos/ananda-lama.webp'],
        ]);

        $this->get(route('spmb.full-form', $registration->registration_code))
            ->assertOk()
            ->assertSee('Lihat pas foto yang sudah diunggah')
            ->assertSee(Storage::disk('public')->url('ppdb/photos/ananda-lama.webp'), false);
    }

    public function test_admin_detail_and_printed_document_show_the_passport_photo(): void
    {
        $this->createSettings();
        $registration = $this->makeRegistration([
            'status' => PpdbRegistration::STATUS_COMPLETED,
            'completed_at' => now(),
            'full_form_data' => [
                'pas_foto_3x4' => 'ppdb/photos/ananda.webp',
                'nik' => '6471019999990009',
            ],
            'fee_agreement_data' => ['pilihan_uang_masuk' => '1', 'pilihan_spp' => '1'],
        ]);

        $admin = User::factory()->forLevel('TK')->create();
        $photoUrl = Storage::disk('public')->url('ppdb/photos/ananda.webp');

        $this->actingAs($admin)
            ->get(route('admin.ppdb.show', $registration))
            ->assertOk()
            ->assertSee('Pas Foto Ananda Ukuran 3&times;4', false)
            ->assertSee('Lihat / Download Pas Foto 3&times;4', false)
            ->assertSee($photoUrl, false);

        $this->actingAs($admin)
            ->get(route('admin.ppdb.print', $registration))
            ->assertOk()
            ->assertSee('Pas Foto 3')
            ->assertSee('object-fit: cover', false)
            ->assertSee($photoUrl, false);
    }
}
