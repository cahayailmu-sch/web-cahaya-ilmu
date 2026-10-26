<?php

namespace Tests\Feature;

use App\Models\PpdbRegistration;
use App\Models\SchoolSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class TwoStagePpdbTest extends TestCase
{
    use RefreshDatabase;

    private function createSettings(array $overrides = []): void
    {
        $defaults = [
            'status_ppdb' => 'Buka',
            'tahun_ajaran' => '2026/2027',
            'foundation_name' => 'Yayasan Ath-Thoifah Al-Manshuroh Balikpapan',
            'alamat_sekolah' => 'Jl. Soekarno Hatta Km. 8 Balikpapan',
            'ppdb_status_sd' => 'Buka',
            'biaya_sd' => '250000',
            'bank_sd' => 'Bank Syariah Indonesia (BSI)',
            'rek_sd' => '7123456789',
            'rek_nama_sd' => 'Yayasan Ath-Thoifah Al-Manshuroh',
            'quota_sd_l' => 50,
            'quota_sd_p' => 50,
            'wa_admin_sd' => '6285796135987',
            'wa_group_sd_l' => 'https://chat.whatsapp.com/SD-Ikhwan-Official-Link',
            'wa_group_sd_p' => 'https://chat.whatsapp.com/SD-Akhwat-Official-Link',
        ];

        foreach (array_merge($defaults, $overrides) as $key => $value) {
            SchoolSetting::create(['key' => $key, 'value' => (string) $value]);
        }
    }

    private function stageOneData(array $overrides = []): array
    {
        return array_merge([
            'full_name' => 'Fatih Al-Faruq',
            'gender' => 'L',
            'birth_place' => 'Balikpapan',
            'birth_date' => now()->subYears(7)->toDateString(),
            'level' => 'SD',
            'previous_school' => 'TK Islam Cahaya Ilmu',
            'parent_name' => 'Umar Abdullah',
            'whatsapp' => '081234567890',
            'address' => 'Jl. Marsma Iswahyudi No. 10 Balikpapan',
            'payment_proof' => UploadedFile::fake()->image('bukti.jpg', 600, 600),
            'persetujuan' => '1',
        ], $overrides);
    }

    private function stageTwoData(array $overrides = []): array
    {
        return array_merge([
            'form' => [
                'nama_panggilan' => 'Fatih',
                'nik' => '6471012345670001',
                'nisn' => '0123456789',
                'no_kk' => '6471019876540001',
                'agama' => 'Islam',
                'anak_ke' => 1,
                'jumlah_saudara' => 3,
                'tinggi_badan' => 120,
                'berat_badan' => 22,
                'golongan_darah' => 'O',
                'riwayat_sekolah' => 'TK Islam Cahaya Ilmu',
                'alamat_lengkap' => 'Jl. Marsma Iswahyudi No. 10',
                'rt_rw' => '05/02',
                'kelurahan' => 'Sepinggan',
                'kecamatan' => 'Balikpapan Selatan',
                'kota' => 'Balikpapan',
                'kode_pos' => '76115',
                'tinggal_bersama' => 'Orang Tua',
                'transportasi' => 'Sepeda Motor',
                'ayah_nama' => 'Umar Abdullah',
                'ayah_nik' => '6471011111110001',
                'ayah_tempat_lahir' => 'Balikpapan',
                'ayah_tanggal_lahir' => '1985-05-10',
                'ayah_pendidikan' => 'S1/D4',
                'ayah_pekerjaan' => 'Wiraswasta',
                'ayah_penghasilan' => 'Rp 5.000.000 - Rp 10.000.000',
                'ayah_hp' => '081234567890',
                'ibu_nama' => 'Khadijah',
                'ibu_nik' => '6471012222220002',
                'ibu_tempat_lahir' => 'Samarinda',
                'ibu_tanggal_lahir' => '1988-08-15',
                'ibu_pendidikan' => 'S1/D4',
                'ibu_pekerjaan' => 'Ibu Rumah Tangga',
                'ibu_penghasilan' => 'Tidak Berpenghasilan',
                'ibu_hp' => '081298765432',
                'kriteria_sekolah' => 'Sekolah bermanhaj salaf dan fokus tahfidz.',
                'sumber_informasi' => 'Website dan rekomendasi kerabat.',
                'alasan_memilih' => 'Ingin ananda memiliki dasar akidah dan akhlak yang kuat.',
                'pandangan_paradigma' => 'Pendidikan agama harus menjadi pondasi utama anak.',
                'harapan_target' => 'Hafal juz 30 dan mampu membaca Al-Qur\'an dengan tartil.',
                'kesiapan_program' => 'Siap aktif mengikuti parenting dan kajian rutin.',
                'peraturan_rumah' => 'Shalat berjamaah di masjid dan membatasi gadget.',
                'pernah_melanggar' => 'Tidak',
                'tindakan_saat_salah' => 'Dinasihati dengan lemah lembut dan diajak berdiskusi.',
                'hukuman_fisik' => 'Tidak',
                'pendapat_sanksi' => 'Setuju dan mendukung penegakan aturan sekolah.',
                'mengambil_uang' => 'Tidak Pernah',
                'game_musik' => 'Tidak bermain game online maupun mendengarkan musik.',
                'suka_tv' => 'Tidak',
                'tidur_ditemani' => 'Tidak',
                'mandiri_makan' => 'Ya',
                'alergi' => 'Tidak',
                'pengaruh_terbesar' => 'Lainnya',
                'pengaruh_terbesar_lainnya' => 'Buku cerita anak muslim',
                'sosok_berpengaruh' => 'Ayah',
                'teman_dekat' => 'Teman mengaji di TPA terdekat.',
                'kegemaran' => 'Membaca dan bersepeda.',
                'kelebihan_akademik' => 'Cepat menghafal kosakata dan surat-surat pendek.',
                'kelebihan_sikap' => 'Santun dan patuh pada orang tua.',
                'kelebihan_kreativitas' => 'Suka menggambar pemandangan dan merakit balok.',
                'perilaku_diperbaiki' => 'Tidak Ada',
                'hubungan_ortu' => 'Rukun/Serumah',
                'penanggung_jawab' => 'Ayah',
                'riwayat_penyakit' => 'Tidak Ada',
            ],
            'pickup' => [
                [
                    'nama' => 'Abdullah Rahman',
                    'usia' => '40',
                    'hubungan' => 'Paman',
                    'no_identitas' => '6471013333330003',
                    'telp' => '081344445555',
                ],
                [
                    'nama' => 'Fatimah Az-Zahra',
                    'usia' => '32',
                    'hubungan' => 'Bibi',
                    'no_identitas' => '6471014444440004',
                    'telp' => '081366667777',
                ],
            ],
            'fee' => [
                'penilaian_biaya' => 'Sedang',
                'alasan_penilaian' => 'Sangat sebanding dengan kualitas pendidikan dan fasilitas.',
                'pilihan_uang_masuk' => '2',
                'cara_bayar' => 'Cash',
                'pilihan_spp' => '2',
                'pj_nama' => 'Umar Abdullah',
                'pj_pekerjaan' => 'Wiraswasta',
                'pj_alamat' => 'Jl. Marsma Iswahyudi No. 10 Balikpapan',
                'pj_telp' => '0542123456',
                'pj_hp' => '081234567890',
                'ttd_ayah' => 'Umar Abdullah',
                'ttd_ibu' => 'Khadijah',
                'kota_ttd' => 'Balikpapan',
            ],
            'agreements' => [
                'setuju_biaya' => '1',
                'setuju_keputusan' => '1',
                'setuju_parenting' => '1',
                'setuju_data_benar' => '1',
            ],
        ], $overrides);
    }

    public function test_stage_one_submission_creates_pending_registration_and_generates_code(): void
    {
        Storage::fake('public');
        $this->createSettings();

        $response = $this->post(route('ppdb.store'), $this->stageOneData());

        $registration = PpdbRegistration::where('full_name', 'Fatih Al-Faruq')->firstOrFail();

        $this->assertSame(PpdbRegistration::STATUS_PENDING, $registration->status);
        $this->assertNotEmpty($registration->registration_code);
        $this->assertSame(40, strlen($registration->registration_code));
        $this->assertNull($registration->verified_at);
        $this->assertNull($registration->completed_at);

        $response->assertRedirect(route('ppdb.status', ['nomor' => $registration->registration_number]));
        $this->assertFalse($registration->canFillFullForm());
    }

    public function test_stage_two_form_is_forbidden_while_status_is_pending(): void
    {
        Storage::fake('public');
        $this->createSettings();

        $this->post(route('ppdb.store'), $this->stageOneData());
        $registration = PpdbRegistration::where('full_name', 'Fatih Al-Faruq')->firstOrFail();

        $response = $this->post(
            route('spmb.full-form.store', $registration->registration_code),
            $this->stageTwoData(),
        );

        $response->assertRedirect(route('spmb.full-form', $registration->registration_code));
        $this->assertSame(PpdbRegistration::STATUS_PENDING, $registration->fresh()->status);
        $this->assertNull($registration->fresh()->completed_at);
    }

    public function test_admin_can_verify_registration_and_unlock_stage_two(): void
    {
        Storage::fake('public');
        $this->createSettings();

        $this->post(route('ppdb.store'), $this->stageOneData());
        $registration = PpdbRegistration::where('full_name', 'Fatih Al-Faruq')->firstOrFail();

        $admin = User::factory()->create(['level' => 'SD', 'role' => 'admin']);

        $this->actingAs($admin)
            ->patch(route('admin.ppdb.status', $registration), ['status' => PpdbRegistration::STATUS_VERIFIED])
            ->assertRedirect();

        $refreshed = $registration->fresh();
        $this->assertSame(PpdbRegistration::STATUS_VERIFIED, $refreshed->status);
        $this->assertNotNull($refreshed->verified_at);
        $this->assertTrue($refreshed->canFillFullForm());

        $this->get(route('spmb.full-form', $refreshed->registration_code))
            ->assertOk()
            ->assertSee('Fatih Al-Faruq')
            ->assertSee('Kesanggupan Biaya')
            ->assertSee('Orang Yang Diberi Wewenang Menjemput');
    }

    public function test_verified_registrant_can_submit_stage_two_and_mark_completed(): void
    {
        Storage::fake('public');
        $this->createSettings();

        $this->post(route('ppdb.store'), $this->stageOneData());
        $registration = PpdbRegistration::where('full_name', 'Fatih Al-Faruq')->firstOrFail();
        $registration->update([
            'status' => PpdbRegistration::STATUS_VERIFIED,
            'verified_at' => now(),
        ]);

        $response = $this->post(
            route('spmb.full-form.store', $registration->registration_code),
            $this->stageTwoData(),
        );

        $response->assertRedirect(route('spmb.full-form.success', $registration->registration_code));

        $refreshed = $registration->fresh();
        $this->assertSame(PpdbRegistration::STATUS_COMPLETED, $refreshed->status);
        $this->assertNotNull($refreshed->completed_at);
        $this->assertTrue($refreshed->hasCompletedFullForm());
        $this->assertSame('6471012345670001', $refreshed->full_form_data['nik']);
        $this->assertSame('2', $refreshed->fee_agreement_data['pilihan_uang_masuk']);
        $this->assertCount(2, $refreshed->full_form_data['penjemput']);

        $successResponse = $this->get(route('spmb.full-form.success', $registration->registration_code));
        $successResponse->assertOk()
            ->assertSee('Alhamdulillah, Formulir Lengkap')
            ->assertSee('Gabung Grup WhatsApp SD (Laki-laki)')
            ->assertSee('https://chat.whatsapp.com/SD-Ikhwan-Official-Link', false);
    }

    public function test_female_student_gets_akhwat_whatsapp_group_link(): void
    {
        Storage::fake('public');
        $this->createSettings();

        $this->post(route('ppdb.store'), $this->stageOneData([
            'full_name' => 'Aisyah Humaira',
            'gender' => 'P',
        ]));
        $registration = PpdbRegistration::where('full_name', 'Aisyah Humaira')->firstOrFail();
        $registration->update([
            'status' => PpdbRegistration::STATUS_VERIFIED,
            'verified_at' => now(),
        ]);

        $this->post(
            route('spmb.full-form.store', $registration->registration_code),
            $this->stageTwoData(),
        )->assertRedirect(route('spmb.full-form.success', $registration->registration_code));

        $successResponse = $this->get(route('spmb.full-form.success', $registration->registration_code));
        $successResponse->assertOk()
            ->assertSee('Gabung Grup WhatsApp SD (Perempuan)')
            ->assertSee('https://chat.whatsapp.com/SD-Akhwat-Official-Link', false);
    }

    public function test_print_and_pdf_export_work_for_completed_registrations(): void
    {
        Storage::fake('public');
        $this->createSettings();

        $this->post(route('ppdb.store'), $this->stageOneData());
        $registration = PpdbRegistration::where('full_name', 'Fatih Al-Faruq')->firstOrFail();
        $registration->update([
            'status' => PpdbRegistration::STATUS_VERIFIED,
            'verified_at' => now(),
        ]);

        $this->post(
            route('spmb.full-form.store', $registration->registration_code),
            $this->stageTwoData(),
        );

        $printResponse = $this->get(route('spmb.full-form.print', $registration->registration_code));
        $printResponse->assertOk()
            ->assertSee('FORMULIR PENDAFTARAN PESERTA DIDIK BARU')
            ->assertSee('SURAT PERNYATAAN KESANGGUPAN BIAYA PENDIDIKAN')
            ->assertSee('Yayasan Ath-Thoifah Al-Manshuroh Balikpapan')
            ->assertSee('SD ISLAM CAHAYA ILMU')
            ->assertSee('Umar Abdullah')
            ->assertSee('Abdullah Rahman')
            ->assertSee('6471012345670001')
            ->assertSee('Sepinggan')
            ->assertSee('Rp 11.000.000')
            ->assertSee('Buku cerita anak muslim')
            ->assertSee('Cepat menghafal kosakata')
            ->assertSee('Sangat sebanding dengan kualitas pendidikan')
            ->assertSee('Samarinda')
            ->assertSee('0542123456')
            ->assertSee('Telp. / HP / WhatsApp');

        $pdfResponse = $this->get(route('spmb.full-form.pdf', $registration->registration_code));
        $pdfResponse->assertOk();
        $this->assertSame('application/pdf', $pdfResponse->headers->get('Content-Type'));

        $admin = User::factory()->create(['level' => 'SD', 'role' => 'admin']);

        $adminPrint = $this->actingAs($admin)->get(route('admin.ppdb.print', $registration));
        $adminPrint->assertOk()
            ->assertSee('FORMULIR PENDAFTARAN PESERTA DIDIK BARU');

        $adminPdf = $this->actingAs($admin)->get(route('admin.ppdb.pdf', $registration));
        $adminPdf->assertOk();
        $this->assertSame('application/pdf', $adminPdf->headers->get('Content-Type'));

        $this->assertDatabaseHas('activity_logs', [
            'action' => 'print',
            'module' => 'SPMB',
        ]);
    }

    public function test_verified_registrant_can_submit_stage_two_with_minimal_required_fields_and_no_pickups(): void
    {
        Storage::fake('public');
        $this->createSettings();

        $this->post(route('ppdb.store'), $this->stageOneData());
        $registration = PpdbRegistration::where('full_name', 'Fatih Al-Faruq')->firstOrFail();
        $registration->update([
            'status' => PpdbRegistration::STATUS_VERIFIED,
            'verified_at' => now(),
        ]);

        $minimalData = [
            'form' => [
                'nama_panggilan' => 'Fatih',
                'nik' => '6471012345670001',
                'no_kk' => '6471019876540001',
                'agama' => 'Islam',
                'anak_ke' => 1,
                'jumlah_saudara' => 2,
                'alamat_lengkap' => 'Jl. Marsma Iswahyudi No. 10',
                'kelurahan' => 'Sepinggan',
                'kecamatan' => 'Balikpapan Selatan',
                'kota' => 'Balikpapan',
                'tinggal_bersama' => 'Orang Tua',
                'ayah_nama' => 'Umar Abdullah',
                'ayah_hp' => '081234567890',
                'ibu_nama' => 'Khadijah',
            ],
            'fee' => [
                'pilihan_uang_masuk' => '1',
                'cara_bayar' => 'Cash',
                'pilihan_spp' => '1',
                'pj_nama' => 'Umar Abdullah',
                'pj_hp' => '081234567890',
                'ttd_ayah' => 'Umar Abdullah',
                'kota_ttd' => 'Balikpapan',
            ],
            'agreements' => [
                'setuju_biaya' => '1',
                'setuju_keputusan' => '1',
                'setuju_parenting' => '1',
                'setuju_data_benar' => '1',
            ],
        ];

        $response = $this->post(
            route('spmb.full-form.store', $registration->registration_code),
            $minimalData,
        );

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('spmb.full-form.success', $registration->registration_code));

        $refreshed = $registration->fresh();
        $this->assertSame(PpdbRegistration::STATUS_COMPLETED, $refreshed->status);
        $this->assertSame('Fatih', $refreshed->full_form_data['nama_panggilan']);
        $this->assertEmpty($refreshed->full_form_data['penjemput']);
    }

    public function test_stage_two_handles_optional_file_uploads(): void
    {
        Storage::fake('public');
        $this->createSettings();

        $this->post(route('ppdb.store'), $this->stageOneData());
        $registration = PpdbRegistration::where('full_name', 'Fatih Al-Faruq')->firstOrFail();
        $registration->update([
            'status' => PpdbRegistration::STATUS_VERIFIED,
            'verified_at' => now(),
        ]);

        $dataWithFiles = array_merge($this->stageTwoData(), [
            'berkas_kk' => UploadedFile::fake()->image('kartu_keluarga.jpg', 1200, 800),
            'berkas_akta' => UploadedFile::fake()->create('akta_kelahiran.pdf', 500, 'application/pdf'),
        ]);

        $response = $this->post(
            route('spmb.full-form.store', $registration->registration_code),
            $dataWithFiles,
        );

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('spmb.full-form.success', $registration->registration_code));

        $refreshed = $registration->fresh();
        $this->assertNotNull($refreshed->documentUrl('berkas_kk'));
        $this->assertNotNull($refreshed->documentUrl('berkas_akta'));
        Storage::disk('public')->assertExists($refreshed->full_form_data['berkas_kk']);
        Storage::disk('public')->assertExists($refreshed->full_form_data['berkas_akta']);
    }

    public function test_verified_registrant_cannot_submit_completely_blank_stage_two(): void
    {
        Storage::fake('public');
        $this->createSettings();

        $this->post(route('ppdb.store'), $this->stageOneData());
        $registration = PpdbRegistration::where('full_name', 'Fatih Al-Faruq')->firstOrFail();
        $registration->update([
            'status' => PpdbRegistration::STATUS_VERIFIED,
            'verified_at' => now(),
        ]);

        $response = $this->post(
            route('spmb.full-form.store', $registration->registration_code),
            [],
        );

        $response->assertSessionHasErrors([
            'form.nik',
            'form.ayah_nama',
            'form.ayah_hp',
            'form.ibu_nama',
            'fee.pilihan_uang_masuk',
            'fee.cara_bayar',
            'fee.pilihan_spp',
            'agreements.setuju_biaya',
        ]);

        $refreshed = $registration->fresh();
        $this->assertSame(PpdbRegistration::STATUS_VERIFIED, $refreshed->status);
        $this->assertNull($refreshed->completed_at);
        $this->assertFalse($refreshed->hasCompletedFullForm());
    }

    public function test_stage_two_form_uses_bracket_input_names_so_php_keeps_nested_arrays(): void
    {
        Storage::fake('public');
        $this->createSettings();

        $this->post(route('ppdb.store'), $this->stageOneData());
        $registration = PpdbRegistration::where('full_name', 'Fatih Al-Faruq')->firstOrFail();
        $registration->update([
            'status' => PpdbRegistration::STATUS_VERIFIED,
            'verified_at' => now(),
        ]);

        $response = $this->get(route('spmb.full-form', $registration->registration_code));

        $response->assertOk()
            ->assertSee('name="form[nama_panggilan]"', false)
            ->assertSee('name="form[nik]"', false)
            ->assertSee('name="form[ayah_nama]"', false)
            ->assertSee('name="fee[pilihan_uang_masuk]"', false)
            ->assertDontSee('name="form.nama_panggilan"', false)
            ->assertDontSee('name="fee.pilihan_uang_masuk"', false);
    }

    public function test_sma_full_form_and_print_use_sma_fee_options_and_consumption(): void
    {
        Storage::fake('public');
        $this->createSettings([
            'ppdb_status_sma' => 'Buka',
            'biaya_sma' => '300000',
            'bank_sma' => 'Bank Syariah Indonesia (BSI)',
            'rek_sma' => '7127822446',
            'rek_nama_sma' => 'SMA Islam Cahaya Ilmu',
            'quota_sma_p' => 30,
            'quota_sma_l' => 0,
        ]);

        $this->post(route('ppdb.store'), $this->stageOneData([
            'full_name' => 'Aisyah Humaira',
            'gender' => 'P',
            'level' => 'SMA',
            'previous_school' => 'SMP Islam Cahaya Ilmu',
        ]))->assertSessionHasNoErrors();

        $registration = PpdbRegistration::where('full_name', 'Aisyah Humaira')->firstOrFail();
        $registration->update([
            'status' => PpdbRegistration::STATUS_VERIFIED,
            'verified_at' => now(),
        ]);

        $this->get(route('spmb.full-form', $registration->registration_code))
            ->assertOk()
            ->assertSee('Pilihan 1 — Rp 7.500.000')
            ->assertSee('Pilihan 2 — Rp 8.500.000')
            ->assertSee('Pilihan 3 — Rp 9.500.000')
            ->assertSee('Pilihan 1 — Rp 550.000 / bulan')
            ->assertSee('Pilihan 2 — Rp 650.000 / bulan')
            ->assertSee('Pilihan 3 — Rp 750.000 / bulan')
            ->assertSee('+ Konsumsi : Rp 200.000 / bulan');

        $stageTwo = $this->stageTwoData();
        $stageTwo['fee']['pilihan_uang_masuk'] = '2';
        $stageTwo['fee']['pilihan_spp'] = '3';

        $this->post(route('spmb.full-form.store', $registration->registration_code), $stageTwo)
            ->assertRedirect(route('spmb.full-form.success', $registration->registration_code));

        $this->get(route('spmb.full-form.print', $registration->registration_code))
            ->assertOk()
            ->assertSee('Rp 8.500.000')
            ->assertSee('Rp 750.000')
            ->assertSee('Rp 200.000')
            ->assertSee('+ Konsumsi : Iuran Makan Siang (SMA)')
            ->assertDontSee('Iuran Konsumsi / Makan Siang (SD)');
    }
}
