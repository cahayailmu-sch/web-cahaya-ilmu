<?php

namespace Tests\Feature;

use App\Models\Gallery;
use App\Models\Post;
use App\Models\PpdbRegistration;
use App\Models\SchoolAgenda;
use App\Models\SchoolSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class FrontPageTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Seed the school settings used by the public pages.
     */
    private function createSettings(array $overrides = []): void
    {
        $settings = [
            'foundation_name' => 'Yayasan Ath-Thoifah Al-Manshuroh Balikpapan',
            'status_ppdb' => 'Buka',
            'tahun_ajaran' => '2027/2028',
            'wa_admin_tk' => '081250000001',
            'wa_admin_sd' => '081250000002',
            'wa_admin_smp' => '081250000003',
            'wa_admin_sma' => '081250000004',
            'instagram_username_tk' => 'tkislam_cahayailmu',
            'instagram_url_tk' => 'https://www.instagram.com/tkislam_cahayailmu',
            'instagram_username_sd' => 'sdislam_cahayailmu',
            'instagram_url_sd' => 'https://www.instagram.com/sdislam_cahayailmu',
            'instagram_username_smp' => 'smpislam_cahayailmu',
            'instagram_url_smp' => 'https://www.instagram.com/smpislam_cahayailmu',
            'instagram_username_sma' => 'smaislam_cahayailmu',
            'instagram_url_sma' => 'https://www.instagram.com/smaislam_cahayailmu',
            'youtube_tk' => 'https://www.youtube.com/@tkislam_cahayailmu',
            'youtube_sd' => 'https://www.youtube.com/@sdislamcahayailmubalikpapan',
            'youtube_smp' => 'https://www.youtube.com/@SMPIslamCahayaIlmu',
            'youtube_sma' => 'https://www.youtube.com/@SMAIslamCahayaIlmu',
            'youtube_channel_name' => 'SD Islam Cahaya Ilmu Balikpapan',
            'youtube_url' => 'https://www.youtube.com/@sdislamcahayailmubalikpapan',
            'alamat_sekolah' => 'Jl. Pendidikan No. 1, Balikpapan',
            'jumlah_siswa' => '850',
            'jumlah_guru' => '65',
        ];

        foreach (['tk', 'sd', 'smp', 'sma'] as $index => $level) {
            $settings += [
                "ppdb_status_{$level}" => 'Buka',
                "ppdb_start_{$level}" => now()->subMonth()->toDateString(),
                "ppdb_end_{$level}" => now()->addMonth()->toDateString(),
                "bank_{$level}" => 'Bank Syariah Indonesia (BSI)',
                "rek_{$level}" => '700123456'.($index + 7),
                "rek_nama_{$level}" => 'Yayasan Ath-Thoifah Al-Manshuroh Balikpapan',
                "biaya_{$level}" => (string) (250000 * ($index + 1)),
            ];
        }

        $settings = array_merge($settings, $overrides);

        foreach ($settings as $key => $value) {
            SchoolSetting::updateOrCreate(
                ['key' => $key],
                ['value' => (string) $value],
            );
        }
    }

    /**
     * Build valid SPMB form data.
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

    public function test_home_page_displays_database_driven_content(): void
    {
        $this->createSettings();

        Post::create([
            'title' => 'Berita Dinamis Cahaya Ilmu',
            'slug' => 'berita-dinamis-cahaya-ilmu',
            'category' => 'Berita',
            'level' => 'Umum',
            'content' => 'Isi berita dinamis yang tampil di halaman utama.',
            'is_published' => true,
        ]);

        Post::create([
            'title' => 'Berita Draft Tidak Tampil',
            'slug' => 'berita-draft-tidak-tampil',
            'category' => 'Berita',
            'level' => 'Umum',
            'content' => 'Berita ini masih berstatus draft.',
            'is_published' => false,
        ]);

        Gallery::create([
            'title' => 'Foto Galeri Dinamis',
            'image' => 'https://example.com/galeri.jpg',
            'category' => 'Fasilitas',
            'level' => 'Umum',
            'taken_at' => '2026-10-15',
        ]);

        $response = $this->get(route('home'));

        $response->assertOk()
            ->assertSee('data-counter="850"', false)
            ->assertSee('data-counter="65"', false)
            ->assertSee('Berita Dinamis Cahaya Ilmu')
            ->assertSee('Foto Galeri Dinamis')
            ->assertSee('15 Oktober 2026')
            ->assertSee('gallery-caption-date', false)
            ->assertSee('0812-5000-0002')
            ->assertSee('window.CI_WHATSAPP', false)
            ->assertSee('Tumbuh Kembang di Atas Ilmu dan Amal')
            ->assertSee('<title>Cahaya Ilmu Islamic School Balikpapan - Tumbuh Kembang di Atas Ilmu dan Amal</title>', false)
            ->assertSee('Tahfidz Al-Qur')
            ->assertSee('An-Nawawi')
            ->assertSee('Doa Harian')
            ->assertSee('Yayasan Ath-Thoifah Al-Manshuroh Balikpapan')
            ->assertSee('Di bawah naungan Yayasan Ath-Thoifah Al-Manshuroh Balikpapan')
            ->assertDontSee('Berita Draft Tidak Tampil')
            ->assertDontSee('Al-Mansuroh')
            ->assertDontSee('Yayasan Pendidikan Islam Cahaya Ilmu');
    }

    public function test_foundation_identity_is_displayed_across_public_pages(): void
    {
        $this->createSettings();

        $foundation = 'Yayasan Ath-Thoifah Al-Manshuroh Balikpapan';

        foreach ([
            route('home'),
            route('about'),
            route('ppdb'),
            route('jenjang.show', 'sd'),
        ] as $url) {
            $this->get($url)
                ->assertOk()
                ->assertSeeText($foundation)
                ->assertSeeText('Lembaga pendidikan Islam terpadu (TK, SD, SMP, SMA) di bawah naungan '.$foundation.'.');
        }

        $this->get(route('about'))
            ->assertSeeText('Di bawah naungan '.$foundation);

        $this->get(route('ppdb'))
            ->assertSeeText('Penyelenggara: '.$foundation);

        $this->get(route('jenjang.show', 'sd'))
            ->assertSeeText('Penyelenggara: '.$foundation);

        $this->get(route('home'))
            ->assertSeeText('© '.date('Y').' Cahaya Ilmu Islamic School - '.$foundation.'. All rights reserved.');
    }

    public function test_home_contact_section_and_floating_menu_use_each_level_contacts(): void
    {
        $this->createSettings([
            'wa_admin_tk' => '628115917772',
            'wa_admin_sd' => '6285796135987',
            'wa_admin_smp' => '6282254424312',
            'wa_admin_sma' => '6285394999283',
        ]);

        $response = $this->get(route('home'));

        $response->assertOk()
            ->assertSee('Hubungi admin sesuai jenjang pilihan Anda.')
            ->assertSee('0811-5917-772')
            ->assertSee('0857-9613-5987')
            ->assertSee('0822-5442-4312')
            ->assertSee('0853-9499-9283')
            ->assertSee('https://wa.me/628115917772', false)
            ->assertSee('https://wa.me/6285796135987', false)
            ->assertSee('https://wa.me/6282254424312', false)
            ->assertSee('https://wa.me/6285394999283', false)
            ->assertSee('@tkislam_cahayailmu')
            ->assertSee('@sdislam_cahayailmu')
            ->assertSee('@smpislam_cahayailmu')
            ->assertSee('@smaislam_cahayailmu')
            ->assertSee('https://www.instagram.com/tkislam_cahayailmu', false)
            ->assertSee('https://www.instagram.com/sdislam_cahayailmu', false)
            ->assertSee('https://www.instagram.com/smpislam_cahayailmu', false)
            ->assertSee('https://www.instagram.com/smaislam_cahayailmu', false)
            ->assertSee('https://www.youtube.com/@tkislam_cahayailmu', false)
            ->assertSee('https://www.youtube.com/@sdislamcahayailmubalikpapan', false)
            ->assertSee('https://www.youtube.com/@SMPIslamCahayaIlmu', false)
            ->assertSee('https://www.youtube.com/@SMAIslamCahayaIlmu', false)
            ->assertSee('Buka YouTube KB-TK')
            ->assertSee('Buka YouTube SD')
            ->assertSee('Buka YouTube SMP')
            ->assertSee('Buka YouTube SMA')
            ->assertSee('Kunjungi YouTube Kami')
            ->assertSee('https://www.youtube.com/@sdislamcahayailmubalikpapan', false);

        $this->assertSame(4, substr_count($response->getContent(), 'data-wa-jenjang='));
    }

    public function test_jenjang_detail_page_uses_the_level_contact_and_instagram(): void
    {
        $this->createSettings([
            'wa_admin_tk' => '628115917772',
        ]);

        $this->get(route('jenjang.show', 'tk'))
            ->assertOk()
            ->assertSee('0811-5917-772')
            ->assertSee('https://wa.me/628115917772', false)
            ->assertSee('@tkislam_cahayailmu')
            ->assertSee('https://www.youtube.com/@tkislam_cahayailmu', false)
            ->assertSee('YouTube TK')
            ->assertSee('Chat Admin TK');
    }

    public function test_home_links_to_full_info_gallery_and_jenjang_pages(): void
    {
        $this->createSettings();

        $post = Post::create([
            'title' => 'Berita Lengkap di Halaman Utama',
            'slug' => 'berita-lengkap-halaman-utama',
            'category' => 'Berita',
            'level' => 'Umum',
            'content' => 'Isi berita lengkap.',
            'images' => ['https://example.com/foto-1.jpg', 'https://example.com/foto-2.jpg'],
            'is_published' => true,
        ]);

        $this->get(route('home'))
            ->assertOk()
            ->assertSee(route('info.show', $post->slug), false)
            ->assertSee('data-post-carousel', false)
            ->assertSee('data-carousel-prev', false)
            ->assertSee('data-carousel-next', false)
            ->assertSee('data-carousel-dots', false)
            ->assertSee('https://example.com/foto-2.jpg', false)
            ->assertSee('js/adaptive-images.js', false)
            ->assertSee('adaptive-img aspect-[4/3]', false)
            ->assertSee(route('info.index'), false)
            ->assertSee(route('gallery.index'), false)
            ->assertSee(route('jenjang.show', 'tk'), false)
            ->assertSee('Lihat Semua Info Sekolah')
            ->assertSee('Lihat Selengkapnya Galeri Sekolah')
            ->assertSee('Baca Selengkapnya Profil KB-TK');
    }

    public function test_info_catalog_filters_by_category_level_and_title(): void
    {
        $this->createSettings();

        Post::create([
            'title' => 'Kegiatan Market Day TK',
            'slug' => 'kegiatan-market-day-tk',
            'category' => 'Berita',
            'level' => 'TK',
            'content' => 'Kegiatan market day.',
            'is_published' => true,
        ]);

        Post::create([
            'title' => 'Juara Olimpiade Sains SMA',
            'slug' => 'juara-olimpiade-sains-sma',
            'category' => 'Prestasi',
            'level' => 'SMA',
            'content' => 'Prestasi siswa.',
            'is_published' => true,
        ]);

        Post::create([
            'title' => 'Pengumuman Draft',
            'slug' => 'pengumuman-draft',
            'category' => 'Pengumuman',
            'level' => 'Umum',
            'content' => 'Belum dipublikasikan.',
            'is_published' => false,
        ]);

        $this->get(route('info.index'))
            ->assertOk()
            ->assertSee('Kegiatan Market Day TK')
            ->assertSee('Juara Olimpiade Sains SMA')
            ->assertDontSee('Pengumuman Draft');

        $this->get(route('info.index', ['kategori' => 'Prestasi']))
            ->assertOk()
            ->assertSee('Juara Olimpiade Sains SMA')
            ->assertDontSee('Kegiatan Market Day TK');

        $this->get(route('info.index', ['jenjang' => 'TK']))
            ->assertOk()
            ->assertSee('Kegiatan Market Day TK')
            ->assertDontSee('Juara Olimpiade Sains SMA');

        $this->get(route('info.index', ['q' => 'Olimpiade']))
            ->assertOk()
            ->assertSee('Juara Olimpiade Sains SMA')
            ->assertDontSee('Kegiatan Market Day TK');

        $paginator = $this->get(route('info.index'))->viewData('posts');

        $this->assertSame(9, $paginator->perPage());
        $this->assertSame(2, $paginator->total());
    }

    public function test_info_detail_displays_full_content_and_hides_unpublished_posts(): void
    {
        $this->createSettings();

        $post = Post::create([
            'title' => 'Kabar Lengkap Cahaya Ilmu',
            'slug' => 'kabar-lengkap-cahaya-ilmu',
            'category' => 'Berita',
            'level' => 'SD',
            'content' => 'Paragraf pertama berita lengkap. Paragraf kedua berita lengkap.',
            'is_published' => true,
        ]);

        $this->get(route('info.show', $post->slug))
            ->assertOk()
            ->assertSee('Kabar Lengkap Cahaya Ilmu')
            ->assertSee('Paragraf pertama berita lengkap.')
            ->assertSee('Info Sekolah Lainnya')
            ->assertSee('Copy Link')
            ->assertSee('WhatsApp');

        Post::create([
            'title' => 'Berita Belum Tayang',
            'slug' => 'berita-belum-tayang',
            'category' => 'Berita',
            'level' => 'SD',
            'content' => 'Belum tayang.',
            'is_published' => false,
        ]);

        $this->get(route('info.show', 'berita-belum-tayang'))->assertNotFound();
    }

    public function test_info_detail_renders_slide_carousel_and_instagram_embed(): void
    {
        $this->createSettings();

        $post = Post::create([
            'title' => 'Kegiatan Carousel Instagram',
            'slug' => 'kegiatan-carousel-instagram',
            'category' => 'Berita',
            'level' => 'TK',
            'thumbnail' => 'https://example.com/thumbnail.jpg',
            'images' => [
                'https://example.com/slide-1.jpg',
                'https://example.com/slide-2.jpg',
            ],
            'instagram_url' => 'https://www.instagram.com/p/ABC123/',
            'content' => 'Dokumentasi kegiatan dengan foto slide.',
            'is_published' => true,
        ]);

        $this->get(route('info.show', $post->slug))
            ->assertOk()
            ->assertSee('data-post-carousel', false)
            ->assertSee('data-carousel-prev', false)
            ->assertSee('data-carousel-next', false)
            ->assertSee('data-carousel-dots', false)
            ->assertSee('post-carousel-badge', false)
            ->assertSee('https://example.com/slide-2.jpg', false)
            ->assertSee('adaptive-img--contain', false)
            ->assertSee('max-h-[550px]', false)
            ->assertSee('class="instagram-media"', false)
            ->assertSee('https://www.instagram.com/p/ABC123/', false)
            ->assertSee('Lihat Postingan Asli di Instagram')
            ->assertSee('//www.instagram.com/embed.js', false);
    }

    public function test_jenjang_page_shows_instagram_feed_and_configured_embeds(): void
    {
        $this->createSettings([
            'instagram_post_tk_1' => 'https://www.instagram.com/p/TK111/',
            'instagram_post_tk_2' => 'https://www.instagram.com/reel/TK222/',
        ]);

        Post::create([
            'title' => 'Kegiatan Instagram TK',
            'slug' => 'kegiatan-instagram-tk',
            'category' => 'Berita',
            'level' => 'TK',
            'images' => [
                'https://example.com/tk-1.jpg',
                'https://example.com/tk-2.jpg',
            ],
            'instagram_url' => 'https://www.instagram.com/p/TKPOST/',
            'content' => 'Kegiatan TK.',
            'is_published' => true,
        ]);

        $this->get(route('jenjang.show', 'tk'))
            ->assertOk()
            ->assertSee('Update Terbaru Instagram TK')
            ->assertSee('@tkislam_cahayailmu')
            ->assertSee('Postingan Instagram Pilihan')
            ->assertSee('https://www.instagram.com/p/TK111/', false)
            ->assertSee('https://www.instagram.com/reel/TK222/', false)
            ->assertSee('https://www.instagram.com/p/TKPOST/', false)
            ->assertSee('Kegiatan Instagram TK')
            ->assertSee('data-post-carousel', false)
            ->assertSee('//www.instagram.com/embed.js', false);
    }

    public function test_info_catalog_shows_instagram_feed_per_level(): void
    {
        $this->createSettings();

        Post::create([
            'title' => 'Kegiatan Feed Instagram TK',
            'slug' => 'kegiatan-feed-instagram-tk',
            'category' => 'Berita',
            'level' => 'TK',
            'images' => ['https://example.com/feed-tk.jpg'],
            'instagram_url' => 'https://www.instagram.com/p/FEEDTK/',
            'content' => 'Kegiatan feed TK.',
            'is_published' => true,
        ]);

        $this->get(route('info.index'))
            ->assertOk()
            ->assertSee('Update Terbaru Instagram per Jenjang')
            ->assertSee('@tkislam_cahayailmu')
            ->assertSee('Kegiatan Feed Instagram TK')
            ->assertSee(route('jenjang.show', 'tk'), false);
    }

    public function test_gallery_catalog_filters_by_level_and_category(): void
    {
        $this->createSettings();

        Gallery::create([
            'title' => 'Kelas SD Ceria',
            'image' => 'https://example.com/kelas-sd.jpg',
            'category' => 'Fasilitas',
            'level' => 'SD',
            'taken_at' => '2026-10-15',
        ]);

        Gallery::create([
            'title' => 'Kegiatan SMP Bersama',
            'image' => 'https://example.com/kegiatan-smp.jpg',
            'category' => 'Kegiatan',
            'level' => 'SMP',
        ]);

        Gallery::create([
            'title' => 'Halaman Sekolah Umum',
            'image' => 'https://example.com/halaman-sekolah.jpg',
            'category' => 'Fasilitas',
            'level' => 'Umum',
        ]);

        $this->get(route('gallery.index'))
            ->assertOk()
            ->assertSee('break-inside-avoid', false)
            ->assertSee('js/adaptive-images.js', false)
            ->assertSee('data-adaptive-frame', false)
            ->assertSee('adaptive-img', false)
            ->assertSee('Kelas SD Ceria')
            ->assertSee('Kegiatan SMP Bersama')
            ->assertSee('Halaman Sekolah Umum')
            ->assertSee('15 Oktober 2026')
            ->assertSee('data-date="15 Oktober 2026"', false)
            ->assertSee('gallery-caption-date', false);

        $this->get(route('gallery.index', ['jenjang' => 'SD']))
            ->assertOk()
            ->assertSee('Kelas SD Ceria')
            ->assertDontSee('Kegiatan SMP Bersama');

        $this->get(route('gallery.index', ['jenjang' => 'Umum']))
            ->assertOk()
            ->assertSee('Halaman Sekolah Umum')
            ->assertDontSee('Kelas SD Ceria')
            ->assertDontSee('Kegiatan SMP Bersama');

        $this->get(route('gallery.index', ['kategori' => 'Kegiatan']))
            ->assertOk()
            ->assertSee('Kegiatan SMP Bersama')
            ->assertDontSee('Kelas SD Ceria');
    }

    public function test_each_jenjang_profile_page_displays_its_own_content(): void
    {
        $this->createSettings();

        Post::create([
            'title' => 'Kabar Khusus TK',
            'slug' => 'kabar-khusus-tk',
            'category' => 'Berita',
            'level' => 'TK',
            'content' => 'Kabar TK.',
            'is_published' => true,
        ]);

        Gallery::create([
            'title' => 'Foto Khusus TK',
            'image' => 'https://example.com/foto-tk.jpg',
            'category' => 'Kegiatan',
            'level' => 'TK',
        ]);

        foreach (['tk' => 'KB-TK Islam Cahaya Ilmu', 'sd' => 'SD Islam Cahaya Ilmu', 'smp' => 'SMP Islam Cahaya Ilmu', 'sma' => 'SMA Islam Cahaya Ilmu'] as $slug => $name) {
            $this->get(route('jenjang.show', $slug))
                ->assertOk()
                ->assertSee($name)
                ->assertSee('Tumbuh Kembang di Atas Ilmu dan Amal')
                ->assertSee('Daftar SPMB Jenjang Ini')
                ->assertSee('Program Tahfidz Terpadu: Tahfidz Al-Qur')
                ->assertSee('An-Nawawi, dan Doa-Doa Harian.');
        }

        $this->get(route('jenjang.show', 'tk'))
            ->assertOk()
            ->assertSee('Kabar Khusus TK')
            ->assertSee('Foto Khusus TK');

        $this->get(route('jenjang.show', 'tidak-ada'))->assertNotFound();
    }

    public function test_about_and_programs_pages_render_complete_profiles(): void
    {
        $this->createSettings();

        $this->get(route('about'))
            ->assertOk()
            ->assertSee('Sejarah Kami')
            ->assertSee('Visi')
            ->assertSee('Misi')
            ->assertSee('Struktur Organisasi');

        $this->get(route('programs'))
            ->assertOk()
            ->assertSee('Tahfidz Al-Qur')
            ->assertSee('An-Nawawi')
            ->assertSee('Doa Harian')
            ->assertSee('Bahasa Arab & Inggris Aktif', false)
            ->assertSee('Pembinaan Akhlak & Ibadah', false)
            ->assertSee('Ekstrakurikuler Bakat & Olahraga', false);
    }

    public function test_official_visi_and_misi_are_identical_on_every_public_page(): void
    {
        $this->createSettings();

        $visi = config('sekolah.visi');
        $misi = config('sekolah.misi');

        $this->assertIsString($visi);
        $this->assertNotSame('', $visi);
        $this->assertCount(8, $misi);

        $pages = [
            route('home'),
            route('about'),
            route('jenjang.show', 'tk'),
            route('jenjang.show', 'sd'),
            route('jenjang.show', 'smp'),
            route('jenjang.show', 'sma'),
        ];

        foreach ($pages as $url) {
            $response = $this->get($url)
                ->assertOk()
                ->assertSee($visi);

            foreach ($misi as $mission) {
                $response->assertSee($mission);
            }
        }
    }

    public function test_ppdb_page_renders_the_registration_form(): void
    {
        $this->createSettings();

        $this->get(route('ppdb'))
            ->assertOk()
            ->assertSee('Tumbuh Kembang di Atas Ilmu dan Amal')
            ->assertSee('Formulir Pendaftaran Online')
            ->assertSee(route('ppdb.store'), false)
            ->assertSee('name="full_name"', false)
            ->assertSee('name="birth_place"', false)
            ->assertSee('name="birth_date"', false)
            ->assertSee('name="payment_proof"', false)
            ->assertSee('name="referral_code"', false)
            ->assertDontSee('name="referral_alumni_names"', false)
            ->assertDontSee('Kakak Alumni', false)
            ->assertDontSee('referral-alumni-wrap', false)
            ->assertDontSee('name="referral_note"', false)
            ->assertSee('id="check-referral"', false)
            ->assertSee('window.CI_REFERRAL_ENDPOINT', false)
            ->assertSee("window.CI_REFERRAL_ENDPOINT = '/spmb/check-referral'", false)
            ->assertSee('Kode Referral / Voucher Promo KB-TK')
            ->assertSee('method="POST"', false)
            ->assertSee('enctype="multipart/form-data"', false)
            ->assertSee('name="_token"', false)
            ->assertSee('Informasi Rekening Pendaftaran')
            ->assertSee('Pendaftaran Dibuka')
            ->assertSee('SPMB (Sistem Penerimaan Murid Baru)')
            ->assertSee('id="umur-sd-warning"', false)
            ->assertSee('id="umur-sd-valid"', false)
            ->assertSee('name="level"', false)
            ->assertSee('name="persetujuan"', false)
            ->assertSee('js/adaptive-images.js', false);
    }

    public function test_ppdb_page_shows_sma_registration_fee_bank_and_document_requirements(): void
    {
        $this->createSettings([
            'biaya_sma' => '300000',
            'bank_sma' => 'Bank Syariah Indonesia (BSI)',
            'rek_sma' => '7127822446',
            'rek_nama_sma' => 'SMA Islam Cahaya Ilmu',
        ]);

        $this->get(route('ppdb'))
            ->assertOk()
            ->assertSee('Fotokopi Akta Kelahiran (2 lembar)')
            ->assertSee('Fotokopi Kartu Keluarga / KK (2 lembar)')
            ->assertSee('Fotokopi KTP Orang Tua (2 lembar)')
            ->assertSee('Fotokopi Rapor Kelas 8 Semester Genap')
            ->assertSee('Materai 10.000 (3 lembar)')
            ->assertSee('Rp 300.000')
            ->assertSee('7127822446')
            ->assertSee('SMA Islam Cahaya Ilmu');
    }

    public function test_ppdb_page_marks_levels_as_closed_when_status_or_schedule_is_not_open(): void
    {
        $this->createSettings([
            'ppdb_status_tk' => 'Tutup',
            'ppdb_end_sd' => now()->subDay()->toDateString(),
        ]);

        $this->get(route('ppdb'))
            ->assertOk()
            ->assertSee('Pendaftaran Ditutup')
            ->assertSee('— Pendaftaran Ditutup', false);
    }

    public function test_guest_can_submit_a_ppdb_registration_and_receive_a_whatsapp_link(): void
    {
        Storage::fake('public');

        $this->createSettings();

        $response = $this->post(route('ppdb.store'), $this->registrationData());

        $registrationNumber = sprintf('SPMB-%d-001', now()->year);

        $response->assertRedirect(route('ppdb.status', ['nomor' => $registrationNumber]))
            ->assertSessionHas('ppdb_success')
            ->assertSessionHas('ppdb_success.registration_number', $registrationNumber)
            ->assertSessionHas('ppdb_success.level', 'SD');

        $this->assertDatabaseHas('ppdb_registrations', [
            'registration_number' => $registrationNumber,
            'full_name' => 'Ahmad Fauzan',
            'gender' => 'L',
            'birth_place' => 'Balikpapan',
            'level' => 'SD',
            'previous_school' => 'TK Islam Cahaya Ilmu',
            'parent_name' => 'Budi Santoso',
            'whatsapp' => '081250000099',
            'status' => 'pending',
        ]);

        $registration = PpdbRegistration::where('registration_number', $registrationNumber)->firstOrFail();

        $this->assertSame(now()->subYears(8)->toDateString(), $registration->birth_date->toDateString());
        Storage::disk('public')->assertExists($registration->payment_proof);

        $whatsappUrl = session('ppdb_success.whatsapp_url');

        $this->assertIsString($whatsappUrl);
        $this->assertStringStartsWith('https://wa.me/6281250000002?text=', $whatsappUrl);

        $this->get(route('ppdb.status', ['nomor' => $registrationNumber]))
            ->assertOk()
            ->assertSee($registrationNumber)
            ->assertSee('Tahap 1: Menunggu Verifikasi Panitia SPMB');
    }

    public function test_stage_one_accepts_gender_labels_and_optional_address(): void
    {
        Storage::fake('public');
        $this->createSettings();

        $data = $this->registrationData([
            'gender' => 'Laki-laki',
            'full_name' => 'Ahmad Fauzan',
        ]);
        unset($data['address'], $data['previous_school']);

        $this->post(route('ppdb.store'), $data)->assertSessionHasNoErrors();

        $registration = PpdbRegistration::where('full_name', 'Ahmad Fauzan')->firstOrFail();

        $this->assertSame('L', $registration->gender);
        $this->assertSame('', $registration->address);
        $this->assertNull($registration->previous_school);
        $this->assertSame(PpdbRegistration::STATUS_PENDING, $registration->status);
        $this->assertNotEmpty($registration->registration_code);

        $this->post(route('ppdb.store'), $this->registrationData([
            'gender' => 'Perempuan',
            'full_name' => 'Aisyah Humaira',
        ]))->assertSessionHasNoErrors();

        $this->assertDatabaseHas('ppdb_registrations', [
            'full_name' => 'Aisyah Humaira',
            'gender' => 'P',
        ]);
    }

    public function test_stage_one_accepts_pdf_payment_proof_without_image_compression(): void
    {
        Storage::fake('public');
        $this->createSettings();

        $this->post(route('ppdb.store'), $this->registrationData([
            'payment_proof' => UploadedFile::fake()->create('bukti-transfer.pdf', 200, 'application/pdf'),
        ]))->assertSessionHasNoErrors();

        $registration = PpdbRegistration::firstOrFail();

        $this->assertStringStartsWith('ppdb/payments/', $registration->payment_proof);
        Storage::disk('public')->assertExists($registration->payment_proof);
    }

    public function test_stage_one_validation_errors_are_visible_on_the_form(): void
    {
        Storage::fake('public');
        $this->createSettings();

        $this->from(route('ppdb'))
            ->followingRedirects()
            ->post(route('ppdb.store'), [])
            ->assertOk()
            ->assertSee('id="ppdb-form-errors"', false)
            ->assertSee('Periksa kembali data pendaftaran Anda:')
            ->assertSee('Jenis kelamin wajib dipilih.');
    }

    public function test_ppdb_confirmation_whatsapp_uses_the_selected_level_number(): void
    {
        Storage::fake('public');

        $this->createSettings([
            'wa_admin_tk' => '628115917772',
            'wa_admin_sd' => '6285796135987',
            'wa_admin_smp' => '6282254424312',
            'wa_admin_sma' => '6285394999283',
        ]);

        $expectedNumbers = [
            'TK' => '628115917772',
            'SD' => '6285796135987',
            'SMP' => '6282254424312',
            'SMA' => '6285394999283',
        ];

        foreach ($expectedNumbers as $level => $expectedNumber) {
            $this->post(route('ppdb.store'), $this->registrationData([
                'level' => $level,
                'sub_level' => $level === 'TK' ? 'TK A' : null,
                'gender' => $level === 'SMA' ? 'P' : 'L',
            ]))->assertSessionHasNoErrors();

            $whatsappUrl = session('ppdb_success.whatsapp_url');

            $this->assertIsString($whatsappUrl);
            $this->assertStringStartsWith('https://wa.me/'.$expectedNumber.'?text=', $whatsappUrl);
        }

        $this->assertDatabaseCount('ppdb_registrations', 4);
    }

    public function test_sd_registration_rejects_student_born_after_march_31_cutoff(): void
    {
        Storage::fake('public');

        $this->createSettings([
            'tahun_ajaran' => '2027/2028',
        ]);

        $this->post(route('ppdb.store'), $this->registrationData([
            'level' => 'SD',
            'birth_date' => '2021-04-01',
        ]))->assertSessionHasErrors([
            'birth_date' => 'Maaf, batas usia minimal masuk SD adalah kelahiran maksimal 31 Maret (6 tahun 3 bulan per 1 Juli). Kelahiran 1 April ke atas belum dapat mendaftar.',
        ]);

        $this->assertDatabaseCount('ppdb_registrations', 0);
    }

    public function test_sd_registration_accepts_student_born_on_march_31_cutoff(): void
    {
        Storage::fake('public');

        $this->createSettings([
            'tahun_ajaran' => '2027/2028',
        ]);

        $this->post(route('ppdb.store'), $this->registrationData([
            'level' => 'SD',
            'birth_date' => '2021-03-31',
        ]))->assertSessionHasNoErrors();

        $this->assertDatabaseCount('ppdb_registrations', 1);
    }

    public function test_non_sd_registration_is_not_restricted_by_sd_march_31_cutoff(): void
    {
        Storage::fake('public');

        $this->createSettings([
            'tahun_ajaran' => '2027/2028',
        ]);

        $this->post(route('ppdb.store'), $this->registrationData([
            'level' => 'TK',
            'sub_level' => 'TK B',
            'birth_date' => '2021-04-01',
        ]))->assertSessionHasNoErrors();

        $this->assertDatabaseCount('ppdb_registrations', 1);
    }

    public function test_tk_registration_requires_sub_level_and_enforces_group_age_cutoff(): void
    {
        Storage::fake('public');

        $this->createSettings([
            'tahun_ajaran' => '2027/2028',
        ]);

        $this->post(route('ppdb.store'), $this->registrationData([
            'level' => 'TK',
            'birth_date' => '2023-06-01',
        ]))->assertSessionHasErrors('sub_level');

        $this->post(route('ppdb.store'), $this->registrationData([
            'level' => 'TK',
            'sub_level' => 'KB',
            'birth_date' => '2024-01-01',
        ]))->assertSessionHasErrors('birth_date');

        $this->post(route('ppdb.store'), $this->registrationData([
            'level' => 'TK',
            'sub_level' => 'KB',
            'birth_date' => '2023-12-31',
        ]))->assertSessionHasNoErrors();

        $this->assertDatabaseHas('ppdb_registrations', [
            'level' => 'TK',
            'sub_level' => 'KB',
        ]);
    }

    public function test_tk_muslim_vibes_promo_waives_registration_fee_and_payment_proof(): void
    {
        Storage::fake('public');

        $this->createSettings([
            'tahun_ajaran' => '2027/2028',
        ]);

        $this->post(route('ppdb.store'), $this->registrationData([
            'level' => 'TK',
            'sub_level' => 'TK A',
            'birth_date' => '2022-06-01',
            'promo_tk' => 'muslim_vibes',
            'payment_proof' => null,
        ]))->assertSessionHasNoErrors();

        $registration = PpdbRegistration::firstOrFail();

        $this->assertSame(0, $registration->final_fee);
        $this->assertSame(250000, $registration->discount_amount);
        $this->assertSame('muslim_vibes', $registration->referral_code);
    }

    public function test_tk_fee_detail_matches_official_school_year_2027_2028_scheme(): void
    {
        $this->createSettings([
            'tahun_ajaran' => '2027/2028',
        ]);

        foreach ([route('ppdb'), route('jenjang.show', 'tk')] as $url) {
            $this->get($url)
                ->assertOk()
                ->assertSee('Rp 250.000')
                ->assertSee('Rp 4.500.000')
                ->assertSee('Rp 1.600.000')
                ->assertSee('Rp 1.900.000')
                ->assertSee('Rp 8.000.000')
                ->assertSee('Rp 550.000')
                ->assertSee('Rp 150.000')
                ->assertSee('Rp 700.000')
                ->assertSee('Seragam (4 Stel)')
                ->assertSee('Buku Jurnal 7 KAIH')
                ->assertSee('Total biaya masuk belum termasuk SPP dan konsumsi.')
                ->assertSee('Juni 2027')
                ->assertDontSee('Rp 10.000.000')
                ->assertDontSee('Rp 11.000.000')
                ->assertDontSee('Rp 12.000.000');
        }

        $this->get(route('jenjang.show', 'tk'))
            ->assertOk()
            ->assertDontSee('Rp 170.000');
    }

    public function test_sd_fee_detail_matches_official_school_year_2027_2028_scheme(): void
    {
        $this->createSettings([
            'tahun_ajaran' => '2027/2028',
        ]);

        foreach ([route('ppdb'), route('jenjang.show', 'sd')] as $url) {
            $this->get($url)
                ->assertOk()
                ->assertSee('Siswa Baniin (Putra)')
                ->assertSee('Siswa Banaat (Putri)')
                ->assertSee('Rp 7.680.000')
                ->assertSee('Rp 8.680.000')
                ->assertSee('Rp 9.680.000')
                ->assertSee('Rp 7.250.000')
                ->assertSee('Rp 8.250.000')
                ->assertSee('Rp 9.250.000')
                ->assertSee('Rp 2.820.000')
                ->assertSee('Rp 3.250.000')
                ->assertSee('Rp 10.500.000')
                ->assertSee('Rp 11.500.000')
                ->assertSee('Rp 12.500.000')
                ->assertSee('Rp 550.000')
                ->assertSee('Rp 650.000')
                ->assertSee('Rp 750.000')
                ->assertSee('Rp 170.000')
                ->assertSee('Uang Perlengkapan Siswa (B) wajib dilunasi sebagai syarat daftar ulang')
                ->assertSee('Total Uang Masuk Keseluruhan (C) belum termasuk SPP dan Konsumsi (D)')
                ->assertSee('Total Uang Masuk Keseluruhan (C) dilunasi sebelum Tahun Ajaran Baru');
        }
    }

    public function test_kb_tk_information_is_displayed_on_ppdb_and_jenjang_pages(): void
    {
        $this->createSettings([
            'tahun_ajaran' => '2027/2028',
            'biaya_tk' => '250000',
            'bank_tk' => 'Bank Syariah Indonesia (BSI)',
            'rek_tk' => '3115917772',
            'rek_nama_tk' => 'TK Islam Cahaya Ilmu',
        ]);

        foreach ([route('ppdb'), route('jenjang.show', 'tk')] as $url) {
            $this->get($url)
                ->assertOk()
                ->assertSee('KB-TK Islam Cahaya Ilmu')
                ->assertSee('Kurikulum Merdeka &amp; Deep Learning', false)
                ->assertSee('Rp 250.000')
                ->assertSee('3115917772')
                ->assertSee('TK Islam Cahaya Ilmu')
                ->assertSee('0811-5917-772')
                ->assertSee('@tkislam_cahayailmu')
                ->assertSee('3 tahun 6 bulan')
                ->assertSee('4 tahun 6 bulan')
                ->assertSee('5 tahun 6 bulan')
                ->assertSee('Sabtu, 31 Oktober 2026')
                ->assertSee('Foto ananda memakai pakaian bebas muslim/muslimah')
                ->assertSee('Akta Kelahiran ananda');
        }
    }

    public function test_sd_age_requirements_are_displayed_on_ppdb_and_jenjang_pages(): void
    {
        $this->createSettings([
            'tahun_ajaran' => '2027/2028',
        ]);

        $this->get(route('ppdb'))
            ->assertOk()
            ->assertSee('Minimal berusia 6 tahun 3 bulan per 1 Juli (kelahiran maksimal 31 Maret)')
            ->assertSee('data-sd-max="2021-03-31"', false);

        $this->get(route('jenjang.show', 'sd'))
            ->assertOk()
            ->assertSee('Minimal berusia 6 tahun 3 bulan per 1 Juli (kelahiran maksimal 31 Maret)');
    }

    public function test_registration_is_rejected_when_the_level_is_closed_by_schedule(): void
    {
        Storage::fake('public');

        $this->createSettings([
            'ppdb_end_sd' => now()->subDay()->toDateString(),
        ]);

        $this->post(route('ppdb.store'), $this->registrationData())
            ->assertSessionHasErrors('level');

        $this->assertDatabaseCount('ppdb_registrations', 0);
    }

    public function test_registration_is_rejected_when_the_level_status_is_closed(): void
    {
        Storage::fake('public');

        $this->createSettings([
            'ppdb_status_sma' => 'Tutup',
        ]);

        $this->post(route('ppdb.store'), $this->registrationData([
            'level' => 'SMA',
            'previous_school' => 'SMP Islam Cahaya Ilmu',
        ]))->assertSessionHasErrors('level');

        $this->assertDatabaseCount('ppdb_registrations', 0);
    }

    public function test_payment_proof_is_required_and_limited_to_supported_file_types(): void
    {
        Storage::fake('public');

        $this->createSettings();

        $this->post(route('ppdb.store'), $this->registrationData([
            'payment_proof' => UploadedFile::fake()->create('bukti.docx', 100),
        ]))->assertSessionHasErrors('payment_proof');

        $this->assertDatabaseCount('ppdb_registrations', 0);
    }

    public function test_previous_school_is_required_for_smp_and_sma(): void
    {
        $this->createSettings();

        $this->post(route('ppdb.store'), $this->registrationData([
            'level' => 'SMP',
            'previous_school' => null,
        ]))->assertSessionHasErrors('previous_school');

        $this->post(route('ppdb.store'), $this->registrationData([
            'level' => 'SMA',
            'previous_school' => '',
        ]))->assertSessionHasErrors('previous_school');

        $this->assertDatabaseCount('ppdb_registrations', 0);
    }

    public function test_registration_numbers_are_generated_sequentially(): void
    {
        $prefix = 'SPMB-'.now()->year.'-';

        PpdbRegistration::create([
            'registration_number' => $prefix.'001',
            'full_name' => 'Pendaftar Pertama',
            'gender' => 'P',
            'level' => 'TK',
            'parent_name' => 'Orang Tua Pertama',
            'whatsapp' => '081250000010',
            'address' => 'Balikpapan',
            'status' => 'Baru',
        ]);

        $this->assertSame($prefix.'002', PpdbRegistration::generateRegistrationNumber());
    }

    public function test_guest_can_check_ppdb_registration_status_and_print_card(): void
    {
        $this->createSettings();

        $registration = PpdbRegistration::create([
            'registration_number' => 'SPMB-2026-007',
            'full_name' => 'Aisyah Humaira',
            'gender' => 'P',
            'birth_place' => 'Balikpapan',
            'birth_date' => '2018-04-12',
            'level' => 'SD',
            'previous_school' => 'TK Islam Cahaya Ilmu',
            'parent_name' => 'Umar Abdullah',
            'whatsapp' => '081250000777',
            'address' => 'Jl. Soekarno-Hatta KM 8',
            'final_fee' => 300000,
            'status' => 'Diterima',
        ]);

        $this->get(route('ppdb.status'))
            ->assertOk()
            ->assertSee('Cek Status')
            ->assertSee('Belum Ada Nomor Pendaftaran yang Dicari');

        $this->get(route('ppdb.status', ['nomor' => 'spmb-2026-007']))
            ->assertOk()
            ->assertSee('SPMB-2026-007')
            ->assertSee('Aisyah Humaira')
            ->assertSee('Status: Diterima')
            ->assertSee('Selamat! Calon Murid Dinyatakan Diterima')
            ->assertSee(route('ppdb.card', 'SPMB-2026-007'), false);

        $this->get(route('ppdb.status', ['nomor' => 'SPMB-2026-999']))
            ->assertOk()
            ->assertSee('Data Pendaftaran Tidak Ditemukan');

        $this->get(route('ppdb.card', $registration->registration_number))
            ->assertOk()
            ->assertSee('Kartu Bukti Pendaftaran SPMB')
            ->assertSee('SPMB-2026-007')
            ->assertSee('Aisyah Humaira')
            ->assertSee('Umar Abdullah')
            ->assertSee('Cetak / Simpan PDF');
    }

    public function test_public_pages_display_published_agendas(): void
    {
        $this->createSettings();

        SchoolAgenda::create([
            'title' => 'Munaqasyah Tahfidz Juz 30',
            'description' => 'Ujian terbuka hafalan Al-Qur\'an.',
            'level' => 'SD',
            'event_date' => '2026-11-20',
            'time_label' => '08.00 – 11.30 WITA',
            'location' => 'Masjid Sekolah',
            'is_published' => true,
        ]);

        SchoolAgenda::create([
            'title' => 'Agenda Draft Internal',
            'level' => 'Umum',
            'event_date' => '2026-11-25',
            'is_published' => false,
        ]);

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('Agenda Kegiatan Sekolah')
            ->assertSee('Munaqasyah Tahfidz Juz 30')
            ->assertDontSee('Agenda Draft Internal');

        $this->get(route('info.index'))
            ->assertOk()
            ->assertSee('Munaqasyah Tahfidz Juz 30')
            ->assertDontSee('Agenda Draft Internal');
    }

    public function test_info_carousel_is_capped_at_10_photos_with_1500ms_autoplay_and_ppdb_allows_6mb_upload(): void
    {
        Storage::fake('public');
        $this->createSettings();

        $twelveImages = [];
        for ($i = 1; $i <= 12; $i++) {
            $twelveImages[] = "https://example.com/slide-{$i}.jpg";
        }

        $post = Post::create([
            'title' => 'Info Carousel Maksimal 10 Foto',
            'slug' => 'info-carousel-maksimal-10-foto',
            'category' => 'Berita',
            'level' => 'Umum',
            'thumbnail' => 'https://example.com/slide-utama.jpg',
            'images' => $twelveImages,
            'content' => 'Dokumentasi kegiatan sekolah.',
            'is_published' => true,
        ]);

        $this->assertCount(10, $post->allImageUrls());

        $this->get(route('info.show', $post->slug))
            ->assertOk()
            ->assertSee('data-carousel-autoplay="1500"', false)
            ->assertSee('https://example.com/slide-utama.jpg', false)
            ->assertSee('https://example.com/slide-9.jpg', false)
            ->assertDontSee('https://example.com/slide-10.jpg', false);

        $this->post(route('ppdb.store'), $this->registrationData([
            'payment_proof' => UploadedFile::fake()->create('bukti-5mb.jpg', 5500, 'image/jpeg'),
        ]))->assertRedirect()
            ->assertSessionHasNoErrors();

        $this->post(route('ppdb.store'), $this->registrationData([
            'payment_proof' => UploadedFile::fake()->create('bukti-7mb.jpg', 6200, 'image/jpeg'),
        ]))->assertSessionHasErrors('payment_proof');
    }

    public function test_sma_profile_and_ppdb_enforce_banaat_putri_and_multimedia_extracurricular(): void
    {
        Storage::fake('public');
        $this->createSettings();

        $this->assertSame([
            'Futsal',
            'Berkuda',
            'Memanah',
            'Tata Boga',
            'Crafting',
            'Renang',
            'Tenis Meja',
            'Karate Wadokai',
        ], config('jenjang.sd.extracurriculars'));

        $this->assertSame([
            'Berkuda',
            'Memanah',
            'IT Club',
            'Futsal',
            'Berkuda Memanah',
        ], config('jenjang.smp.extracurriculars'));

        $this->assertSame(['Multimedia'], config('jenjang.sma.extracurriculars'));
        $this->assertSame('Rasio guru : siswa 1 : 25', config('jenjang.sd.ratio'));
        $this->assertSame('Rasio guru : siswa 1 : 25', config('jenjang.smp.ratio'));
        $this->assertSame('Rasio guru : siswi 1 : 25', config('jenjang.sma.ratio'));
        $this->assertSame('Kelas 1–3: 07.00–13.00 WITA | Kelas 4–6: 07.00–14.00 WITA', config('jenjang.sd.schedule'));
        $this->assertSame('07.00–15.00 WITA', config('jenjang.smp.schedule'));
        $this->assertSame('07.20–15.00 WITA', config('jenjang.sma.schedule'));

        $this->get(route('jenjang.show', 'sma'))
            ->assertOk()
            ->assertSee('Khusus Banaat / Putri')
            ->assertSee('Rasio guru : siswi 1 : 25')
            ->assertSee('assets/images/logo-tk.png', false)
            ->assertSee('assets/images/logo-sd.png', false)
            ->assertSee('assets/images/logo-smp.png', false)
            ->assertSee('assets/images/logo-sma.png', false)
            ->assertSee('Multimedia')
            ->assertDontSee('Debat Bahasa Inggris');

        $this->post(route('ppdb.store'), $this->registrationData([
            'level' => 'SMA',
            'gender' => 'L',
            'previous_school' => 'SMP Islam Cahaya Ilmu',
        ]))->assertSessionHasErrors('gender');

        $this->post(route('ppdb.store'), $this->registrationData([
            'level' => 'SMA',
            'gender' => 'P',
            'previous_school' => 'SMP Islam Cahaya Ilmu',
        ]))->assertRedirect()
            ->assertSessionHasNoErrors();
    }

    public function test_public_pages_use_fixed_navbar_pt20_and_overflow_x_clip(): void
    {
        $this->createSettings();

        $post = Post::create([
            'title' => 'Artikel Uji Navbar',
            'slug' => 'artikel-uji-navbar',
            'category' => 'Berita',
            'level' => 'Umum',
            'content' => 'Isi artikel uji navbar.',
            'is_published' => true,
        ]);

        $pages = [
            route('home'),
            route('ppdb'),
            route('jenjang.show', 'tk'),
            route('jenjang.show', 'sd'),
            route('jenjang.show', 'smp'),
            route('jenjang.show', 'sma'),
            route('info.index'),
            route('info.show', $post->slug),
            route('gallery.index'),
            route('about'),
            route('programs'),
            route('ppdb.status'),
        ];

        foreach ($pages as $url) {
            $this->get($url)
                ->assertOk()
                ->assertSee('fixed top-0 left-0 right-0 w-full z-50 transition-all duration-300 bg-white/95 backdrop-blur-md shadow-sm border-b border-slate-100', false)
                ->assertSee('<main id="main" class="pt-20 overflow-x-clip">', false)
                ->assertSee('overflow-x-clip', false)
                ->assertSee('overflow-x: clip;', false)
                ->assertSee('nav-dropdown-panel absolute left-0 top-full z-50', false)
                ->assertSee('id="mobile-menu" class="mobile-menu relative z-50 lg:hidden"', false)
                ->assertDontSee('overflow-x-hidden', false);
        }
    }

    public function test_info_pages_render_youtube_embed_and_automatic_thumbnail(): void
    {
        $this->createSettings();

        $post = Post::create([
            'title' => 'Pengumuman dengan Video YouTube',
            'slug' => 'pengumuman-video-youtube',
            'category' => 'Pengumuman',
            'level' => 'Umum',
            'youtube_url' => 'https://youtu.be/dQw4w9WgXcQ',
            'content' => 'Simak video pengumuman berikut.',
            'is_published' => true,
        ]);

        $this->get(route('info.show', $post->slug))
            ->assertOk()
            ->assertSee('https://www.youtube-nocookie.com/embed/dQw4w9WgXcQ?rel=0', false)
            ->assertSee('aspect-video w-full overflow-hidden rounded-2xl shadow-lg', false)
            ->assertSee('allowfullscreen', false)
            ->assertSee('loading="lazy"', false);

        $this->get(route('info.index'))
            ->assertOk()
            ->assertSee('https://img.youtube.com/vi/dQw4w9WgXcQ/hqdefault.jpg', false)
            ->assertSee('fa-solid fa-play', false)
            ->assertSee('Video');

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('https://img.youtube.com/vi/dQw4w9WgXcQ/hqdefault.jpg', false)
            ->assertSee('fa-solid fa-play', false);
    }

    public function test_faq_sections_on_home_and_ppdb_reflect_actual_school_programs_and_remove_placeholders(): void
    {
        $this->createSettings();

        foreach ([route('home'), route('ppdb')] as $url) {
            $this->get($url)
                ->assertOk()
                ->assertSee('Ekstrakurikuler Renang')
                ->assertSee('Panahan')
                ->assertSee('Archery')
                ->assertSee('Futsal')
                ->assertSee('Pramuka / Kepanduan')
                ->assertSee('Crafting / Sains &amp; Kreativitas', false)
                ->assertSee('IT Club')
                ->assertSee('Multimedia')
                ->assertSee('4 unit jenjang pendidikan terpadu')
                ->assertSee('Kurikulum Nasional (Kurikulum Merdeka)')
                ->assertSee('Tumbuh Kembang di Atas Ilmu dan Amal')
                ->assertSee('cahayailmu-bpn.id/ppdb')
                ->assertDontSee('0542-123-4567')
                ->assertDontSee('badminton')
                ->assertDontSee('robotik')
                ->assertDontSee('seni kaligrafi')
                ->assertDontSee('English Club');
        }
    }
}
