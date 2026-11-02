<?php

namespace Tests\Feature;

use App\Models\ActivityLog;
use App\Models\Gallery;
use App\Models\Post;
use App\Models\PpdbRegistration;
use App\Models\SchoolAgenda;
use App\Models\SchoolSetting;
use App\Models\User;
use Database\Seeders\AdminUserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminPanelTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Create the default admin user for testing.
     */
    private function createAdmin(): User
    {
        return User::factory()->create([
            'email' => 'admin@cahayailmu.sch.id',
            'password' => 'password123',
        ]);
    }

    /**
     * Create an SPMB registration for testing.
     */
    private function createRegistration(array $attributes = []): PpdbRegistration
    {
        return PpdbRegistration::create(array_merge([
            'registration_number' => 'SPMB-2026-001',
            'full_name' => 'Ahmad Fauzan',
            'gender' => 'L',
            'birth_place' => 'Balikpapan',
            'birth_date' => now()->subYears(8)->toDateString(),
            'level' => 'SD',
            'previous_school' => 'TK Cahaya Ilmu',
            'parent_name' => 'Budi Santoso',
            'whatsapp' => '081250000001',
            'address' => 'Balikpapan',
            'payment_proof' => 'payment-proofs/bukti-transfer.jpg',
            'status' => 'Baru',
        ], $attributes));
    }

    /**
     * Build the per-level SPMB settings payload.
     *
     * @param  array<string, mixed>  $overrides
     * @return array<string, mixed>
     */
    private function ppdbSettingsPayload(array $overrides = []): array
    {
        $payload = [];

        foreach (['tk', 'sd', 'smp', 'sma'] as $index => $level) {
            $payload["ppdb_status_{$level}"] = 'Buka';
            $payload["ppdb_start_{$level}"] = '2026-10-01';
            $payload["ppdb_end_{$level}"] = '2027-06-30';
            $payload["bank_{$level}"] = 'Bank Syariah Indonesia (BSI)';
            $payload["rek_{$level}"] = '700123456'.($index + 7);
            $payload["rek_nama_{$level}"] = 'Yayasan Ath-Thoifah Al-Manshuroh Balikpapan';
            $payload["biaya_{$level}"] = 250000 * ($index + 1);
        }

        return array_merge($payload, $overrides);
    }

    public function test_guest_is_redirected_to_admin_login(): void
    {
        $this->get(route('admin.dashboard'))->assertRedirect(route('admin.login'));
    }

    public function test_login_page_renders_and_authenticated_admin_is_redirected(): void
    {
        $this->get(route('admin.login'))
            ->assertOk()
            ->assertSee('Tumbuh Kembang di Atas Ilmu dan Amal');

        $admin = $this->createAdmin();

        $this->actingAs($admin)
            ->get(route('admin.login'))
            ->assertRedirect(route('admin.dashboard'));
    }

    public function test_admin_can_login_and_logout(): void
    {
        $admin = $this->createAdmin();

        $this->post(route('admin.login.attempt'), [
            'email' => $admin->email,
            'password' => 'password123',
        ])->assertRedirect(route('admin.dashboard'));

        $this->assertAuthenticatedAs($admin);

        $this->post(route('admin.logout'))->assertRedirect(route('admin.login'));

        $this->assertGuest();
    }

    public function test_invalid_credentials_are_rejected(): void
    {
        $admin = $this->createAdmin();

        $this->from(route('admin.login'))
            ->post(route('admin.login.attempt'), [
                'email' => $admin->email,
                'password' => 'password-salah',
            ])
            ->assertRedirect(route('admin.login'))
            ->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_admin_pages_render_successfully(): void
    {
        $admin = $this->createAdmin();
        $this->createRegistration();

        Post::create([
            'title' => 'Berita Contoh',
            'slug' => 'berita-contoh',
            'category' => 'Berita',
            'level' => 'Umum',
            'content' => 'Isi berita contoh.',
            'is_published' => true,
        ]);

        Gallery::create([
            'title' => 'Foto Contoh',
            'image' => 'galleries/foto-contoh.jpg',
            'category' => 'Kegiatan',
            'level' => 'Umum',
        ]);

        $routes = [
            route('admin.dashboard'),
            route('admin.ppdb.index'),
            route('admin.ppdb-settings.index'),
            route('admin.posts.index'),
            route('admin.posts.create'),
            route('admin.galleries.index'),
            route('admin.settings.index'),
        ];

        foreach ($routes as $route) {
            $this->actingAs($admin)->get($route)->assertOk();
        }
    }

    public function test_admin_layout_keeps_sidebar_fixed_and_header_sticky(): void
    {
        $admin = $this->createAdmin();

        $this->actingAs($admin)
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('id="sidebar"', false)
            ->assertSee('lg:translate-x-0', false)
            ->assertSee('lg:pl-72', false)
            ->assertSee('sticky top-0 z-30', false)
            ->assertSee('min-h-0 flex-1 space-y-6 overflow-y-auto', false);
    }

    public function test_ppdb_level_filter_and_status_update_work(): void
    {
        $admin = $this->createAdmin();
        $registration = $this->createRegistration();

        $this->actingAs($admin)
            ->get(route('admin.ppdb.index', ['level' => 'TK']))
            ->assertOk()
            ->assertDontSee('Ahmad Fauzan');

        $this->actingAs($admin)
            ->patch(route('admin.ppdb.status', $registration), ['status' => 'Diterima'])
            ->assertRedirect();

        $this->assertSame('Diterima', $registration->fresh()->status);
    }

    public function test_admin_can_create_and_delete_a_post_with_thumbnail(): void
    {
        Storage::fake('public');

        $admin = $this->createAdmin();

        $this->actingAs($admin)
            ->post(route('admin.posts.store'), [
                'title' => 'Berita Baru',
                'category' => 'Pengumuman',
                'level' => 'SD',
                'content' => 'Isi berita baru.',
                'is_published' => '1',
                'thumbnail' => UploadedFile::fake()->image('thumbnail.jpg'),
            ])
            ->assertRedirect(route('admin.posts.index'));

        $post = Post::where('slug', 'berita-baru')->firstOrFail();

        $this->assertTrue($post->is_published);
        Storage::disk('public')->assertExists($post->thumbnail);

        $this->actingAs($admin)
            ->delete(route('admin.posts.destroy', $post))
            ->assertRedirect(route('admin.posts.index'));

        $this->assertDatabaseMissing('posts', ['id' => $post->id]);
        Storage::disk('public')->assertMissing($post->thumbnail);
    }

    public function test_admin_can_create_and_delete_a_post_with_slide_images_and_instagram_link(): void
    {
        Storage::fake('public');

        $admin = $this->createAdmin();

        $this->actingAs($admin)
            ->post(route('admin.posts.store'), [
                'title' => 'Kegiatan dengan Carousel',
                'category' => 'Berita',
                'level' => 'TK',
                'content' => 'Dokumentasi kegiatan dengan beberapa foto slide.',
                'is_published' => '1',
                'thumbnail' => UploadedFile::fake()->image('thumbnail.jpg'),
                'images' => [
                    UploadedFile::fake()->image('slide-1.jpg'),
                    UploadedFile::fake()->image('slide-2.jpg'),
                ],
                'instagram_url' => 'https://www.instagram.com/p/ABC123/',
            ])
            ->assertRedirect(route('admin.posts.index'));

        $post = Post::where('slug', 'kegiatan-dengan-carousel')->firstOrFail();

        $this->assertCount(2, $post->images);
        $this->assertSame('https://www.instagram.com/p/ABC123/', $post->instagram_url);

        foreach ($post->images as $image) {
            Storage::disk('public')->assertExists($image);
        }

        $this->actingAs($admin)
            ->get(route('admin.posts.edit', $post))
            ->assertOk()
            ->assertSee('name="images[]"', false)
            ->assertSee('multiple', false)
            ->assertSee('name="remove_images[]"', false)
            ->assertSee('name="instagram_url"', false);

        $this->actingAs($admin)
            ->delete(route('admin.posts.destroy', $post))
            ->assertRedirect(route('admin.posts.index'));

        Storage::disk('public')->assertMissing($post->thumbnail);

        foreach ($post->images as $image) {
            Storage::disk('public')->assertMissing($image);
        }
    }

    public function test_admin_can_update_and_remove_selected_slide_images(): void
    {
        Storage::fake('public');

        $admin = $this->createAdmin();

        Storage::disk('public')->put('posts/slide-1.jpg', 'image');
        Storage::disk('public')->put('posts/slide-2.jpg', 'image');

        $post = Post::create([
            'title' => 'Kegiatan Slide Lama',
            'slug' => 'kegiatan-slide-lama',
            'category' => 'Berita',
            'level' => 'SD',
            'content' => 'Konten lama.',
            'images' => ['posts/slide-1.jpg', 'posts/slide-2.jpg'],
            'is_published' => true,
        ]);

        $this->actingAs($admin)
            ->put(route('admin.posts.update', $post), [
                'title' => 'Kegiatan Slide Baru',
                'category' => 'Berita',
                'level' => 'SD',
                'content' => 'Konten baru.',
                'is_published' => '1',
                'remove_images' => ['posts/slide-1.jpg'],
                'images' => [UploadedFile::fake()->image('slide-3.jpg')],
                'instagram_url' => 'https://www.instagram.com/reel/XYZ789/',
            ])
            ->assertRedirect(route('admin.posts.index'));

        $post->refresh();

        $this->assertNotContains('posts/slide-1.jpg', $post->images);
        $this->assertContains('posts/slide-2.jpg', $post->images);
        $this->assertCount(2, $post->images);
        $this->assertSame('https://www.instagram.com/reel/XYZ789/', $post->instagram_url);

        Storage::disk('public')->assertMissing('posts/slide-1.jpg');
        Storage::disk('public')->assertExists('posts/slide-2.jpg');
    }

    public function test_admin_can_upload_and_delete_a_gallery_photo(): void
    {
        Storage::fake('public');

        $admin = $this->createAdmin();

        $this->actingAs($admin)
            ->post(route('admin.galleries.store'), [
                'title' => 'Kegiatan Sekolah',
                'category' => 'Kegiatan',
                'level' => 'SD',
                'taken_at' => '2026-10-15',
                'image' => UploadedFile::fake()->image('kegiatan.jpg'),
            ])
            ->assertRedirect();

        $gallery = Gallery::where('title', 'Kegiatan Sekolah')->firstOrFail();

        Storage::disk('public')->assertExists($gallery->image);
        $this->assertSame('2026-10-15', $gallery->taken_at?->toDateString());
        $this->assertSame('15 Oktober 2026', $gallery->documentationDateLabel());

        $this->actingAs($admin)
            ->delete(route('admin.galleries.destroy', $gallery))
            ->assertRedirect();

        $this->assertDatabaseMissing('galleries', ['id' => $gallery->id]);
        Storage::disk('public')->assertMissing($gallery->image);
    }

    public function test_admin_can_update_gallery_documentation_date(): void
    {
        Storage::fake('public');

        $admin = $this->createAdmin();

        $gallery = Gallery::create([
            'title' => 'Foto Lama',
            'image' => 'https://example.com/foto-lama.jpg',
            'category' => 'Fasilitas',
            'level' => 'SD',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.galleries.edit', $gallery))
            ->assertOk()
            ->assertSee('Edit Foto Galeri')
            ->assertSee('Tanggal Dokumentasi');

        $this->actingAs($admin)
            ->put(route('admin.galleries.update', $gallery), [
                'title' => 'Foto Diperbarui',
                'category' => 'Kegiatan',
                'level' => 'SD',
                'taken_at' => '2026-11-02',
            ])
            ->assertRedirect(route('admin.galleries.index'));

        $gallery->refresh();

        $this->assertSame('Foto Diperbarui', $gallery->title);
        $this->assertSame('2026-11-02', $gallery->taken_at?->toDateString());
        $this->assertSame('https://example.com/foto-lama.jpg', $gallery->image);
    }

    public function test_gallery_list_orders_by_documentation_date(): void
    {
        $admin = $this->createAdmin();

        Gallery::create([
            'title' => 'Dokumentasi Lebih Baru',
            'image' => 'https://example.com/baru.jpg',
            'category' => 'Kegiatan',
            'level' => 'SD',
            'taken_at' => '2026-11-01',
        ]);

        Gallery::create([
            'title' => 'Dokumentasi Lebih Lama',
            'image' => 'https://example.com/lama.jpg',
            'category' => 'Kegiatan',
            'level' => 'SD',
            'taken_at' => '2026-10-01',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.galleries.index'))
            ->assertOk()
            ->assertSeeInOrder(['Dokumentasi Lebih Baru', 'Dokumentasi Lebih Lama']);
    }

    public function test_admin_ppdb_page_displays_birth_details_age_and_payment_proof_link(): void
    {
        $admin = $this->createAdmin();
        $this->createRegistration();

        $this->actingAs($admin)
            ->get(route('admin.ppdb.index'))
            ->assertOk()
            ->assertSee('Tempat, Tanggal Lahir')
            ->assertSee('Balikpapan')
            ->assertSee('8 tahun')
            ->assertSee('Lihat Bukti Bayar')
            ->assertSee('storage/payment-proofs/bukti-transfer.jpg', false);
    }

    public function test_website_settings_page_only_shows_general_settings(): void
    {
        $admin = $this->createAdmin();

        $this->actingAs($admin)
            ->get(route('admin.settings.index'))
            ->assertOk()
            ->assertSee('Identitas Sekolah')
            ->assertSee('name="nama_sekolah"', false)
            ->assertSee('name="foundation_name"', false)
            ->assertSee('name="motto"', false)
            ->assertSee('name="alamat_sekolah"', false)
            ->assertSee('name="email_sekolah"', false)
            ->assertSee('name="wa_pusat"', false)
            ->assertSee('name="maps_embed"', false)
            ->assertSee('name="instagram_username"', false)
            ->assertSee('name="instagram_url"', false)
            ->assertSee('name="facebook_url"', false)
            ->assertSee('name="tiktok_url"', false)
            ->assertSee('Link Sosial Media per Jenjang')
            ->assertSee('name="instagram_username_tk"', false)
            ->assertSee('name="instagram_url_sma"', false)
            ->assertSee('Link Postingan Instagram per Jenjang')
            ->assertSee('name="instagram_post_tk_1"', false)
            ->assertSee('name="instagram_post_sd_2"', false)
            ->assertSee('name="instagram_post_sma_3"', false)
            ->assertSee('name="youtube_sma"', false)
            ->assertDontSee('name="quota_sd_l"', false)
            ->assertDontSee('name="wa_group_sd_l"', false)
            ->assertDontSee('name="status_ppdb"', false)
            ->assertDontSee('name="tahun_ajaran"', false)
            ->assertDontSee('name="bank_sd"', false)
            ->assertDontSee('name="rek_sd"', false);
    }

    public function test_ppdb_settings_page_only_shows_spmb_settings(): void
    {
        $admin = $this->createAdmin();

        $this->actingAs($admin)
            ->get(route('admin.ppdb-settings.index'))
            ->assertOk()
            ->assertSee('Pengaturan SPMB')
            ->assertSee('name="status_ppdb"', false)
            ->assertSee('name="tahun_ajaran"', false)
            ->assertSee('name="quota_sd_l"', false)
            ->assertSee('name="quota_sd_p"', false)
            ->assertSee('name="wa_group_sd_l"', false)
            ->assertSee('name="wa_group_sd_p"', false)
            ->assertSee('name="wa_admin_sd"', false)
            ->assertSee('name="bank_sd"', false)
            ->assertSee('name="rek_sd"', false)
            ->assertSee('name="rek_nama_sd"', false)
            ->assertSee('name="biaya_sd"', false)
            ->assertSee('name="ppdb_start_sd"', false)
            ->assertSee('name="ppdb_end_sd"', false)
            ->assertDontSee('name="nama_sekolah"', false)
            ->assertDontSee('name="alamat_sekolah"', false)
            ->assertDontSee('name="instagram_username_tk"', false);
    }

    public function test_admin_can_update_website_settings(): void
    {
        $admin = $this->createAdmin();

        $this->actingAs($admin)
            ->put(route('admin.settings.update'), [
                'nama_sekolah' => 'Cahaya Ilmu Islamic School',
                'foundation_name' => 'Yayasan Ath-Thoifah Al-Manshuroh Balikpapan',
                'motto' => 'Tumbuh Kembang di Atas Ilmu dan Amal',
                'alamat_sekolah' => 'Jl. Soekarno-Hatta KM. 8, Balikpapan',
                'email_sekolah' => 'info@cahyailmu.sch.id',
                'wa_pusat' => '628115917772',
                'maps_embed' => '<iframe src="https://maps.google.com/maps/embed"></iframe>',
                'jumlah_siswa' => 900,
                'jumlah_guru' => 70,
                'instagram_username' => '@cahayailmu.sch',
                'instagram_url' => 'https://www.instagram.com/cahayailmu.sch',
                'youtube_channel_name' => 'SD Islam Cahaya Ilmu Balikpapan',
                'youtube_url' => 'https://www.youtube.com/@sdislamcahayailmubalikpapan',
                'facebook_url' => 'https://www.facebook.com/cahayailmu',
                'tiktok_url' => 'https://www.tiktok.com/@cahayailmu',
                'instagram_username_tk' => '@tkislam_cahayailmu',
                'instagram_url_tk' => 'https://www.instagram.com/tkislam_cahayailmu',
                'instagram_username_sd' => 'sdislam_cahayailmu',
                'instagram_url_sd' => 'https://www.instagram.com/sdislam_cahayailmu',
                'instagram_username_smp' => 'smpislam_cahayailmu',
                'instagram_url_smp' => 'https://www.instagram.com/smpislam_cahayailmu',
                'instagram_username_sma' => 'smaislam_cahayailmu',
                'instagram_url_sma' => 'https://www.instagram.com/smaislam_cahayailmu',
                'instagram_post_tk_1' => 'https://www.instagram.com/p/TK111/',
                'instagram_post_tk_2' => 'https://www.instagram.com/p/TK222/',
                'instagram_post_sma_3' => 'https://www.instagram.com/reel/SMA333/',
                'youtube_tk' => 'https://www.youtube.com/@tkislam_cahayailmu',
                'youtube_sd' => 'https://www.youtube.com/@sdislamcahayailmubalikpapan',
                'youtube_smp' => 'https://www.youtube.com/@SMPIslamCahayaIlmu',
                'youtube_sma' => 'https://www.youtube.com/@SMAIslamCahayaIlmu',
                'facebook_sd' => 'https://www.facebook.com/sdislamcahayailmu',
                'tiktok_sd' => 'https://www.tiktok.com/@sdislamcahayailmu',
            ])
            ->assertRedirect()
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('school_settings', [
            'key' => 'nama_sekolah',
            'value' => 'Cahaya Ilmu Islamic School',
        ]);

        $this->assertDatabaseHas('school_settings', [
            'key' => 'motto',
            'value' => 'Tumbuh Kembang di Atas Ilmu dan Amal',
        ]);

        $this->assertDatabaseHas('school_settings', [
            'key' => 'email_sekolah',
            'value' => 'info@cahyailmu.sch.id',
        ]);

        $this->assertDatabaseHas('school_settings', [
            'key' => 'jumlah_siswa',
            'value' => '900',
        ]);

        $this->assertDatabaseHas('school_settings', [
            'key' => 'instagram_username',
            'value' => 'cahayailmu.sch',
        ]);

        $this->assertDatabaseHas('school_settings', [
            'key' => 'instagram_username_tk',
            'value' => 'tkislam_cahayailmu',
        ]);

        $this->assertDatabaseHas('school_settings', [
            'key' => 'instagram_url_sma',
            'value' => 'https://www.instagram.com/smaislam_cahayailmu',
        ]);

        $this->assertDatabaseHas('school_settings', [
            'key' => 'youtube_channel_name',
            'value' => 'SD Islam Cahaya Ilmu Balikpapan',
        ]);

        $this->assertDatabaseHas('school_settings', [
            'key' => 'youtube_url',
            'value' => 'https://www.youtube.com/@sdislamcahayailmubalikpapan',
        ]);

        $this->assertDatabaseHas('school_settings', [
            'key' => 'youtube_tk',
            'value' => 'https://www.youtube.com/@tkislam_cahayailmu',
        ]);

        $this->assertDatabaseHas('school_settings', [
            'key' => 'youtube_sma',
            'value' => 'https://www.youtube.com/@SMAIslamCahayaIlmu',
        ]);

        $this->assertDatabaseHas('school_settings', [
            'key' => 'facebook_sd',
            'value' => 'https://www.facebook.com/sdislamcahayailmu',
        ]);

        $this->assertDatabaseHas('school_settings', [
            'key' => 'tiktok_sd',
            'value' => 'https://www.tiktok.com/@sdislamcahayailmu',
        ]);

        $this->assertDatabaseHas('school_settings', [
            'key' => 'instagram_post_tk_1',
            'value' => 'https://www.instagram.com/p/TK111/',
        ]);

        $this->assertDatabaseHas('school_settings', [
            'key' => 'instagram_post_sma_3',
            'value' => 'https://www.instagram.com/reel/SMA333/',
        ]);

        $this->assertDatabaseHas('activity_logs', [
            'action' => 'update',
            'module' => 'Pengaturan Website',
        ]);

        $this->actingAs($admin)
            ->put(route('admin.settings.update'), [
                'alamat_sekolah' => 'Jl. Syarifuddin Yoes RT. 41',
            ])
            ->assertRedirect()
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('school_settings', [
            'key' => 'alamat_sekolah',
            'value' => 'Jl. Syarifuddin Yoes RT. 41',
        ]);
    }

    public function test_admin_can_update_ppdb_settings(): void
    {
        $admin = $this->createAdmin();

        $this->actingAs($admin)
            ->put(route('admin.ppdb-settings.update'), $this->ppdbSettingsPayload([
                'status_ppdb' => 'Tutup',
                'tahun_ajaran' => '2027/2028',
                'ppdb_status_sma' => 'Tutup',
                'biaya_sma' => 500000,
                'quota_sd_l' => 60,
                'quota_sd_p' => 55,
                'wa_group_sd_l' => 'https://chat.whatsapp.com/ikhwan-sd',
                'wa_group_sd_p' => 'https://chat.whatsapp.com/akhwat-sd',
                'wa_admin_sd' => '6281250000002',
            ]))
            ->assertRedirect()
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('school_settings', [
            'key' => 'status_ppdb',
            'value' => 'Tutup',
        ]);

        $this->assertDatabaseHas('school_settings', [
            'key' => 'tahun_ajaran',
            'value' => '2027/2028',
        ]);

        $this->assertDatabaseHas('school_settings', [
            'key' => 'ppdb_status_sma',
            'value' => 'Tutup',
        ]);

        $this->assertDatabaseHas('school_settings', [
            'key' => 'biaya_sma',
            'value' => '500000',
        ]);

        $this->assertDatabaseHas('school_settings', [
            'key' => 'quota_sd_l',
            'value' => '60',
        ]);

        $this->assertDatabaseHas('school_settings', [
            'key' => 'quota_sd_p',
            'value' => '55',
        ]);

        $this->assertDatabaseHas('school_settings', [
            'key' => 'wa_group_sd_l',
            'value' => 'https://chat.whatsapp.com/ikhwan-sd',
        ]);

        $this->assertDatabaseHas('school_settings', [
            'key' => 'wa_group_sd_p',
            'value' => 'https://chat.whatsapp.com/akhwat-sd',
        ]);

        $this->assertDatabaseHas('school_settings', [
            'key' => 'wa_admin_sd',
            'value' => '6281250000002',
        ]);

        $this->assertDatabaseHas('activity_logs', [
            'action' => 'update',
            'module' => 'Pengaturan SPMB',
        ]);
    }

    public function test_ppdb_settings_rejects_an_end_date_before_the_start_date(): void
    {
        $admin = $this->createAdmin();

        $this->actingAs($admin)
            ->put(route('admin.ppdb-settings.update'), array_merge([
                'status_ppdb' => 'Buka',
                'tahun_ajaran' => '2027/2028',
            ], $this->ppdbSettingsPayload([
                'ppdb_start_tk' => '2026-10-10',
                'ppdb_end_tk' => '2026-10-01',
            ])))
            ->assertSessionHasErrors('ppdb_end_tk');
    }

    public function test_admin_can_store_and_display_arabic_content_with_harakat(): void
    {
        $admin = $this->createAdmin();

        $arabicContent = "بِسْمِ اللَّهِ الرَّحْمَٰنِ الرَّحِيمِ\n"
            ."السَّلَامُ عَلَيْكُمْ وَرَحْمَةُ اللَّهِ وَبَرَكَاتُهُ\n"
            .'كِتَابٌ، قَلَمٌ، مَدْرَسَةٌ';

        $this->actingAs($admin)
            ->post(route('admin.posts.store'), [
                'title' => 'Kabar Bahasa Arab',
                'category' => 'Berita',
                'level' => 'Umum',
                'content' => $arabicContent,
                'is_published' => '1',
            ])
            ->assertRedirect(route('admin.posts.index'));

        $post = Post::where('slug', 'kabar-bahasa-arab')->firstOrFail();

        $this->assertSame($arabicContent, $post->content);

        $this->get(route('info.show', $post->slug))
            ->assertOk()
            ->assertSee('بِسْمِ اللَّهِ الرَّحْمَٰنِ الرَّحِيمِ')
            ->assertSee('السَّلَامُ عَلَيْكُمْ وَرَحْمَةُ اللَّهِ وَبَرَكَاتُهُ')
            ->assertSee('كِتَابٌ، قَلَمٌ، مَدْرَسَةٌ');

        $this->actingAs($admin)
            ->get(route('admin.posts.edit', $post))
            ->assertOk()
            ->assertSee('accept-charset="UTF-8"', false)
            ->assertSee('dir="auto"', false)
            ->assertSee($arabicContent);
    }

    public function test_admin_can_search_filter_status_and_export_ppdb_registrations_to_csv(): void
    {
        $admin = $this->createAdmin();

        $this->createRegistration([
            'registration_number' => 'SPMB-2026-001',
            'full_name' => 'Ahmad Fauzan',
            'level' => 'SD',
            'status' => 'Diterima',
        ]);

        $this->createRegistration([
            'registration_number' => 'SPMB-2026-002',
            'full_name' => 'Zaid bin Tsabit',
            'level' => 'SMP',
            'status' => 'Baru',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.ppdb.index', ['q' => 'Zaid']))
            ->assertOk()
            ->assertSee('Zaid bin Tsabit')
            ->assertDontSee('Ahmad Fauzan');

        $this->actingAs($admin)
            ->get(route('admin.ppdb.index', ['status' => 'Diterima']))
            ->assertOk()
            ->assertSee('Ahmad Fauzan')
            ->assertDontSee('Zaid bin Tsabit');

        $exportResponse = $this->actingAs($admin)
            ->get(route('admin.ppdb.export', ['status' => 'Diterima']));

        $exportResponse->assertOk()
            ->assertHeader('Content-Type', 'text/csv; charset=UTF-8');

        $csvContent = $exportResponse->streamedContent();

        $this->assertStringContainsString('SPMB-2026-001', $csvContent);
        $this->assertStringContainsString('Ahmad Fauzan', $csvContent);
        $this->assertStringNotContainsString('Zaid bin Tsabit', $csvContent);
    }

    public function test_admin_can_manage_school_agendas(): void
    {
        $admin = $this->createAdmin();

        $this->actingAs($admin)
            ->post(route('admin.agendas.store'), [
                'title' => 'Kajian Parenting Akbar',
                'description' => 'Kajian bulanan bersama wali murid.',
                'level' => 'Umum',
                'event_date' => '2026-12-10',
                'time_label' => '08.30 – 11.00 WITA',
                'location' => 'Aula Utama',
                'is_published' => '1',
            ])
            ->assertRedirect(route('admin.agendas.index'));

        $agenda = SchoolAgenda::where('title', 'Kajian Parenting Akbar')->firstOrFail();

        $this->actingAs($admin)
            ->get(route('admin.agendas.index'))
            ->assertOk()
            ->assertSee('Kajian Parenting Akbar');

        $this->actingAs($admin)
            ->put(route('admin.agendas.update', $agenda), [
                'title' => 'Kajian Parenting Akbar Semester 1',
                'description' => 'Deskripsi diperbarui.',
                'level' => 'SD',
                'event_date' => '2026-12-12',
                'time_label' => '09.00 – 11.30 WITA',
                'location' => 'Masjid Sekolah',
                'is_published' => '1',
            ])
            ->assertRedirect(route('admin.agendas.index'));

        $this->assertSame('Kajian Parenting Akbar Semester 1', $agenda->fresh()->title);

        $this->actingAs($admin)
            ->delete(route('admin.agendas.destroy', $agenda))
            ->assertRedirect(route('admin.agendas.index'));

        $this->assertDatabaseMissing('school_agendas', ['id' => $agenda->id]);
    }

    public function test_admin_uploads_allow_up_to_6mb_and_cap_carousel_at_10_photos(): void
    {
        Storage::fake('public');

        $admin = $this->createAdmin();

        $this->actingAs($admin)
            ->post(route('admin.galleries.store'), [
                'title' => 'Foto Galeri 5 MB',
                'category' => 'Kegiatan',
                'level' => 'SD',
                'image' => UploadedFile::fake()->create('galeri-besar.jpg', 5500, 'image/jpeg'),
            ])
            ->assertRedirect()
            ->assertSessionHasNoErrors();

        $this->actingAs($admin)
            ->post(route('admin.galleries.store'), [
                'title' => 'Foto Galeri Terlalu Besar',
                'category' => 'Kegiatan',
                'level' => 'SD',
                'image' => UploadedFile::fake()->create('galeri-terlalu-besar.jpg', 6200, 'image/jpeg'),
            ])
            ->assertSessionHasErrors('image');

        $elevenFiles = [];
        for ($i = 1; $i <= 11; $i++) {
            $elevenFiles[] = UploadedFile::fake()->image("slide-{$i}.jpg");
        }

        $this->actingAs($admin)
            ->post(route('admin.posts.store'), [
                'title' => 'Info Lebih Dari 10 Foto',
                'category' => 'Berita',
                'level' => 'Umum',
                'content' => 'Isi berita.',
                'images' => $elevenFiles,
            ])
            ->assertSessionHasErrors('images');

        $this->actingAs($admin)
            ->post(route('admin.posts.store'), [
                'title' => 'Info Foto 5 MB',
                'category' => 'Berita',
                'level' => 'Umum',
                'content' => 'Isi berita dengan foto besar.',
                'thumbnail' => UploadedFile::fake()->create('thumb-besar.jpg', 5500, 'image/jpeg'),
                'images' => [
                    UploadedFile::fake()->create('slide-besar.jpg', 5500, 'image/jpeg'),
                ],
                'is_published' => '1',
            ])
            ->assertRedirect(route('admin.posts.index'))
            ->assertSessionHasNoErrors();
    }

    public function test_admin_user_seeder_creates_five_official_admin_accounts(): void
    {
        $this->seed(AdminUserSeeder::class);

        $this->assertDatabaseHas('users', [
            'email' => 'superadmin@cahayailmu.sch.id',
            'role' => 'superadmin',
            'level' => null,
        ]);

        foreach (['TK' => 'admintk', 'SD' => 'adminsd', 'SMP' => 'adminsmp', 'SMA' => 'adminsma'] as $level => $prefix) {
            $this->assertDatabaseHas('users', [
                'email' => "{$prefix}@cahayailmu.sch.id",
                'role' => 'admin_jenjang',
                'level' => $level,
            ]);
        }
    }

    public function test_level_admin_is_strictly_scoped_to_their_own_level(): void
    {
        Storage::fake('public');

        $adminSd = User::factory()->forLevel('SD')->create([
            'name' => 'Admin SD Islam Cahaya Ilmu',
            'email' => 'adminsd@cahayailmu.sch.id',
        ]);

        $sdReg = $this->createRegistration([
            'registration_number' => 'SPMB-SD-01',
            'full_name' => 'Murid SD Satu',
            'level' => 'SD',
        ]);

        $smpReg = $this->createRegistration([
            'registration_number' => 'SPMB-SMP-01',
            'full_name' => 'Murid SMP Satu',
            'level' => 'SMP',
        ]);

        $sdPost = Post::create([
            'title' => 'Berita Khusus SD',
            'slug' => 'berita-khusus-sd',
            'category' => 'Berita',
            'level' => 'SD',
            'content' => 'Konten SD.',
            'is_published' => true,
        ]);

        $smpPost = Post::create([
            'title' => 'Berita Khusus SMP',
            'slug' => 'berita-khusus-smp',
            'category' => 'Berita',
            'level' => 'SMP',
            'content' => 'Konten SMP.',
            'is_published' => true,
        ]);

        $smpGallery = Gallery::create([
            'title' => 'Galeri SMP',
            'category' => 'Kegiatan',
            'level' => 'SMP',
            'image' => 'galleries/smp.jpg',
        ]);

        // Dashboard shows badge and only SD registrations
        $this->actingAs($adminSd)
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('Login sebagai: Admin SD')
            ->assertDontSee('Kelola Admin')
            ->assertSee('Murid SD Satu')
            ->assertDontSee('Murid SMP Satu');

        // SPMB index only shows SD registrations even if ?level=SMP is passed
        $this->actingAs($adminSd)
            ->get(route('admin.ppdb.index', ['level' => 'SMP']))
            ->assertOk()
            ->assertSee('Murid SD Satu')
            ->assertDontSee('Murid SMP Satu');

        // Admin SD cannot update or delete SMP registration
        $this->actingAs($adminSd)
            ->patch(route('admin.ppdb.status', $smpReg), ['status' => 'Diterima'])
            ->assertForbidden();

        $this->actingAs($adminSd)
            ->delete(route('admin.ppdb.destroy', $smpReg))
            ->assertForbidden();

        // Admin SD can update SD registration
        $this->actingAs($adminSd)
            ->patch(route('admin.ppdb.status', $sdReg), ['status' => 'Diverifikasi'])
            ->assertRedirect();

        $this->assertSame('Diverifikasi', $sdReg->fresh()->status);

        // Posts index only lists SD posts
        $this->actingAs($adminSd)
            ->get(route('admin.posts.index'))
            ->assertOk()
            ->assertSee('Berita Khusus SD')
            ->assertDontSee('Berita Khusus SMP');

        // Creating a post as Admin SD forces level to SD even if SMP is submitted
        $this->actingAs($adminSd)
            ->post(route('admin.posts.store'), [
                'title' => 'Pengumuman Baru SD',
                'category' => 'Pengumuman',
                'level' => 'SMP',
                'content' => 'Isi pengumuman SD.',
                'is_published' => '1',
            ])
            ->assertRedirect(route('admin.posts.index'));

        $this->assertDatabaseHas('posts', [
            'title' => 'Pengumuman Baru SD',
            'level' => 'SD',
        ]);

        // Admin SD cannot edit, update, or delete SMP post
        $this->actingAs($adminSd)
            ->get(route('admin.posts.edit', $smpPost))
            ->assertForbidden();

        $this->actingAs($adminSd)
            ->put(route('admin.posts.update', $smpPost), [
                'title' => 'Hack SMP',
                'category' => 'Berita',
                'level' => 'SD',
                'content' => 'Hack',
            ])
            ->assertForbidden();

        $this->actingAs($adminSd)
            ->delete(route('admin.posts.destroy', $smpPost))
            ->assertForbidden();

        // Admin SD cannot delete SMP gallery
        $this->actingAs($adminSd)
            ->delete(route('admin.galleries.destroy', $smpGallery))
            ->assertForbidden();

        // Admin SD cannot access /admin/users
        $this->actingAs($adminSd)
            ->get(route('admin.users.index'))
            ->assertForbidden();
    }

    public function test_superadmin_can_manage_admin_accounts(): void
    {
        $superadmin = $this->createAdmin();

        $this->actingAs($superadmin)
            ->get(route('admin.users.index'))
            ->assertOk()
            ->assertSee('Kelola Admin')
            ->assertSee('Login sebagai: Superadmin');

        $this->actingAs($superadmin)
            ->post(route('admin.users.store'), [
                'name' => 'Admin TK Baru',
                'email' => 'tkbaru@cahayailmu.sch.id',
                'password' => 'secret123',
                'role' => 'admin_jenjang',
                'level' => 'TK',
            ])
            ->assertRedirect(route('admin.users.index'));

        $tkAdmin = User::where('email', 'tkbaru@cahayailmu.sch.id')->firstOrFail();
        $this->assertSame('admin_jenjang', $tkAdmin->role);
        $this->assertSame('TK', $tkAdmin->level);

        $this->actingAs($superadmin)
            ->put(route('admin.users.update', $tkAdmin), [
                'name' => 'Admin TK Diperbarui',
                'email' => 'tkbaru@cahayailmu.sch.id',
                'password' => 'newpassword123',
                'role' => 'admin_jenjang',
                'level' => 'SD',
            ])
            ->assertRedirect(route('admin.users.index'));

        $tkAdmin->refresh();
        $this->assertSame('Admin TK Diperbarui', $tkAdmin->name);
        $this->assertSame('SD', $tkAdmin->level);
        $this->assertTrue(Hash::check('newpassword123', $tkAdmin->password));

        // Quick-Upgrade from Admin Jenjang to Superadmin via PATCH /admin/users/{user}/role
        $this->actingAs($superadmin)
            ->patch(route('admin.users.role', $tkAdmin), [
                'role' => 'superadmin',
                'level' => 'SD',
            ])
            ->assertRedirect(route('admin.users.index'))
            ->assertSessionHas('success', "Level akses akun {$tkAdmin->name} berhasil diperbarui menjadi Superadmin (Semua Jenjang).");

        $tkAdmin->refresh();
        $this->assertSame('superadmin', $tkAdmin->role);
        $this->assertNull($tkAdmin->level);

        // Quick-Downgrade / Change back to Admin Jenjang SMP
        $this->actingAs($superadmin)
            ->patch(route('admin.users.role', $tkAdmin), [
                'role' => 'admin_jenjang',
                'level' => 'SMP',
            ])
            ->assertRedirect(route('admin.users.index'))
            ->assertSessionHas('success', "Level akses akun {$tkAdmin->name} berhasil diperbarui menjadi Admin Jenjang SMP.");

        $tkAdmin->refresh();
        $this->assertSame('admin_jenjang', $tkAdmin->role);
        $this->assertSame('SMP', $tkAdmin->level);

        // Anti Lock-Out: Superadmin cannot downgrade their own logged-in account
        $this->actingAs($superadmin)
            ->patch(route('admin.users.role', $superadmin), [
                'role' => 'admin_jenjang',
                'level' => 'TK',
            ])
            ->assertRedirect();

        $superadmin->refresh();
        $this->assertSame('superadmin', $superadmin->role);
        $this->assertNull($superadmin->level);

        // Superadmin cannot delete their own logged-in account
        $this->actingAs($superadmin)
            ->delete(route('admin.users.destroy', $superadmin))
            ->assertRedirect();

        $this->assertDatabaseHas('users', ['id' => $superadmin->id]);

        // Superadmin can delete another admin account
        $this->actingAs($superadmin)
            ->delete(route('admin.users.destroy', $tkAdmin))
            ->assertRedirect(route('admin.users.index'));

        $this->assertDatabaseMissing('users', ['id' => $tkAdmin->id]);
    }

    public function test_activity_logs_record_actions_filter_properly_and_prune_old_entries(): void
    {
        $superadmin = $this->createAdmin();
        $adminSd = User::factory()->forLevel('SD')->create([
            'name' => 'Admin SD Islam Cahaya Ilmu',
            'email' => 'adminsd@cahayailmu.sch.id',
        ]);

        $registration = $this->createRegistration([
            'registration_number' => 'SPMB-LOG-01',
            'full_name' => 'Aisyah Putri',
            'level' => 'SD',
            'status' => 'Baru',
        ]);

        // Admin SD verifies SPMB registration
        $this->actingAs($adminSd)
            ->patch(route('admin.ppdb.status', $registration), ['status' => 'Diverifikasi'])
            ->assertRedirect();

        // Superadmin creates a post
        $this->actingAs($superadmin)
            ->post(route('admin.posts.store'), [
                'title' => 'Kegiatan Manasik Haji TK',
                'category' => 'Berita',
                'level' => 'TK',
                'content' => 'Dokumentasi manasik haji TK.',
                'is_published' => '1',
            ])
            ->assertRedirect(route('admin.posts.index'));

        $this->assertDatabaseHas('activity_logs', [
            'user_id' => $adminSd->id,
            'role_level' => 'Admin SD',
            'action' => 'verify',
            'module' => 'SPMB',
            'description' => 'Mengubah status pendaftaran SPMB Aisyah Putri (SD) menjadi Diverifikasi',
        ]);

        $this->assertDatabaseHas('activity_logs', [
            'user_id' => $superadmin->id,
            'role_level' => 'Superadmin',
            'action' => 'create',
            'module' => 'Info Sekolah',
            'description' => 'Menambahkan Info Sekolah baru: Kegiatan Manasik Haji TK (TK)',
        ]);

        // Dashboard shows "5 Aktivitas Terbaru" widget
        $this->actingAs($superadmin)
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('5 Aktivitas Terbaru')
            ->assertSee('Mengubah status pendaftaran SPMB Aisyah Putri (SD) menjadi Diverifikasi')
            ->assertSee('Menambahkan Info Sekolah baru: Kegiatan Manasik Haji TK (TK)');

        // Superadmin sees all logs on /admin/activity-logs and can filter by module
        $this->actingAs($superadmin)
            ->get(route('admin.activity-logs.index', ['module' => 'SPMB']))
            ->assertOk()
            ->assertSee('Mengubah status pendaftaran SPMB Aisyah Putri (SD) menjadi Diverifikasi')
            ->assertDontSee('Menambahkan Info Sekolah baru: Kegiatan Manasik Haji TK (TK)');

        // Admin SD only sees their own activity logs and cannot prune old logs
        $this->actingAs($adminSd)
            ->get(route('admin.activity-logs.index'))
            ->assertOk()
            ->assertSee('Mengubah status pendaftaran SPMB Aisyah Putri (SD) menjadi Diverifikasi')
            ->assertDontSee('Menambahkan Info Sekolah baru: Kegiatan Manasik Haji TK (TK)');

        $this->actingAs($adminSd)
            ->delete(route('admin.activity-logs.prune'))
            ->assertForbidden();

        // Create an old log (> 90 days) and prune as Superadmin
        $oldLog = ActivityLog::create([
            'user_id' => $superadmin->id,
            'user_name' => $superadmin->name,
            'role_level' => 'Superadmin',
            'action' => 'login',
            'module' => 'Autentikasi',
            'description' => 'Log lama lebih dari 90 hari',
        ]);
        ActivityLog::whereKey($oldLog->id)->update([
            'created_at' => now()->subDays(95),
            'updated_at' => now()->subDays(95),
        ]);

        $this->actingAs($superadmin)
            ->delete(route('admin.activity-logs.prune'))
            ->assertRedirect(route('admin.activity-logs.index'));

        $this->assertDatabaseMissing('activity_logs', ['id' => $oldLog->id]);
    }

    public function test_admin_can_upload_and_remove_jenjang_hero_images(): void
    {
        Storage::fake('public');

        $admin = $this->createAdmin();

        // 1. Initial public page uses default hero image
        $this->get(route('jenjang.show', 'tk'))
            ->assertOk()
            ->assertSee('/assets/images/jenjang-tk.jpg');

        // 2. Admin uploads a custom hero image for TK
        $file = UploadedFile::fake()->image('hero-tk.jpg', 1600, 900);

        $this->actingAs($admin)
            ->put(route('admin.settings.update'), ['hero_image_tk' => $file])
            ->assertRedirect();

        $savedTkSetting = SchoolSetting::where('key', 'hero_image_tk')->first();
        $this->assertNotNull($savedTkSetting);
        $storedPath = $savedTkSetting->value;
        Storage::disk('public')->assertExists($storedPath);

        // 3. Public page now displays the custom hero image
        $customUrl = Storage::url($storedPath);
        $this->get(route('jenjang.show', 'tk'))
            ->assertOk()
            ->assertSee($customUrl);

        // 4. Admin reverts to default hero image
        $this->actingAs($admin)
            ->put(route('admin.settings.update'), ['remove_hero_image_tk' => '1'])
            ->assertRedirect();

        $this->assertDatabaseMissing('school_settings', ['key' => 'hero_image_tk']);
        Storage::disk('public')->assertMissing($storedPath);

        // 5. Public page falls back to default
        $this->get(route('jenjang.show', 'tk'))
            ->assertOk()
            ->assertSee('/assets/images/jenjang-tk.jpg');
    }

    public function test_admin_can_store_and_update_a_post_youtube_url(): void
    {
        $admin = $this->createAdmin();

        $this->actingAs($admin)
            ->post(route('admin.posts.store'), [
                'title' => 'Berita dengan Video',
                'category' => 'Berita',
                'level' => 'SD',
                'content' => 'Isi berita dengan video.',
                'is_published' => '1',
                'youtube_url' => 'https://youtu.be/dQw4w9WgXcQ',
            ])
            ->assertRedirect(route('admin.posts.index'));

        $post = Post::where('slug', 'berita-dengan-video')->firstOrFail();

        $this->assertSame('https://youtu.be/dQw4w9WgXcQ', $post->youtube_url);
        $this->assertSame('https://www.youtube-nocookie.com/embed/dQw4w9WgXcQ?rel=0', $post->youtube_embed_url);

        $this->actingAs($admin)
            ->get(route('admin.posts.edit', $post))
            ->assertOk()
            ->assertSee('name="youtube_url"', false)
            ->assertSee('https://youtu.be/dQw4w9WgXcQ', false);

        $this->actingAs($admin)
            ->put(route('admin.posts.update', $post), [
                'title' => 'Berita dengan Video',
                'category' => 'Berita',
                'level' => 'SD',
                'content' => 'Isi berita dengan video.',
                'is_published' => '1',
                'youtube_url' => 'https://www.youtube.com/watch?v=abcdefghijk',
            ])
            ->assertRedirect(route('admin.posts.index'));

        $this->assertSame(
            'https://www.youtube-nocookie.com/embed/abcdefghijk?rel=0',
            $post->fresh()->youtube_embed_url,
        );
    }

    public function test_admin_rejects_non_youtube_video_links(): void
    {
        $admin = $this->createAdmin();

        $this->actingAs($admin)
            ->post(route('admin.posts.store'), [
                'title' => 'Berita Video Tidak Valid',
                'category' => 'Berita',
                'level' => 'SD',
                'content' => 'Isi berita.',
                'is_published' => '1',
                'youtube_url' => 'https://vimeo.com/123456789',
            ])
            ->assertSessionHasErrors('youtube_url');
    }
}
