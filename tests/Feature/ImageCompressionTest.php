<?php

namespace Tests\Feature;

use App\Services\ImageCompressor;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ImageCompressionTest extends TestCase
{
    private string $workingDirectory;

    protected function setUp(): void
    {
        parent::setUp();

        $this->workingDirectory = storage_path('framework/testing/image-compression-'.uniqid());
        File::ensureDirectoryExists($this->workingDirectory);
    }

    protected function tearDown(): void
    {
        File::deleteDirectory($this->workingDirectory);

        parent::tearDown();
    }

    public function test_large_landscape_upload_is_resized_proportionally_and_stored_as_webp(): void
    {
        Storage::fake('public');

        $path = app(ImageCompressor::class)->storeUploaded(
            UploadedFile::fake()->image('kegiatan.jpg', 3000, 2000),
            'galleries',
        );

        $this->assertStringStartsWith('galleries/', $path);
        $this->assertStringEndsWith('.webp', $path);
        Storage::disk('public')->assertExists($path);

        [$width, $height, $type] = getimagesize(Storage::disk('public')->path($path));

        $this->assertSame(IMAGETYPE_WEBP, $type);
        $this->assertSame(1400, $width);
        $this->assertSame(933, $height);
    }

    public function test_payment_proof_portrait_upload_is_limited_to_1000_pixels(): void
    {
        Storage::fake('public');

        $path = app(ImageCompressor::class)->storeUploaded(
            UploadedFile::fake()->image('bukti.jpg', 1500, 3000),
            'payment-proofs',
            ImageCompressor::PAYMENT_PROOF_MAX_DIMENSION,
        );

        [$width, $height] = getimagesize(Storage::disk('public')->path($path));

        $this->assertSame(500, $width);
        $this->assertSame(1000, $height);
    }

    public function test_small_upload_keeps_its_original_dimensions(): void
    {
        Storage::fake('public');

        $path = app(ImageCompressor::class)->storeUploaded(
            UploadedFile::fake()->image('kecil.jpg', 800, 600),
            'posts',
        );

        [$width, $height] = getimagesize(Storage::disk('public')->path($path));

        $this->assertSame(800, $width);
        $this->assertSame(600, $height);
    }

    public function test_transparent_png_upload_keeps_its_transparency(): void
    {
        Storage::fake('public');

        $image = imagecreatetruecolor(2000, 1000);
        imagealphablending($image, false);
        imagesavealpha($image, true);
        imagefill($image, 0, 0, imagecolorallocatealpha($image, 0, 0, 0, 127));
        imagefilledrectangle($image, 800, 300, 1200, 700, imagecolorallocatealpha($image, 225, 29, 72, 0));

        $sourcePath = $this->workingDirectory.'/logo.png';
        imagepng($image, $sourcePath);

        $path = app(ImageCompressor::class)->storeUploaded(
            new UploadedFile($sourcePath, 'logo.png', 'image/png', null, true),
            'posts',
        );

        $stored = imagecreatefromwebp(Storage::disk('public')->path($path));

        $this->assertSame(1400, imagesx($stored));
        $this->assertSame(127, (imagecolorat($stored, 5, 5) >> 24) & 0x7F);
        $this->assertSame(0, (imagecolorat($stored, 700, 350) >> 24) & 0x7F);
    }

    public function test_non_image_upload_is_stored_without_conversion(): void
    {
        Storage::fake('public');

        $path = app(ImageCompressor::class)->storeUploaded(
            UploadedFile::fake()->create('bukti.pdf', 120, 'application/pdf'),
            'payment-proofs',
            ImageCompressor::PAYMENT_PROOF_MAX_DIMENSION,
        );

        $this->assertStringEndsWith('.pdf', $path);
        Storage::disk('public')->assertExists($path);
    }

    public function test_optimize_command_compresses_large_images_in_place_and_skips_small_ones(): void
    {
        $largeJpeg = $this->workingDirectory.'/gedung-besar.jpg';
        $this->createNoisyJpeg($largeJpeg, 2400, 1600);

        $smallJpeg = $this->workingDirectory.'/logo-kecil.jpg';
        imagejpeg(imagecreatetruecolor(200, 200), $smallJpeg, 90);

        $largeBefore = filesize($largeJpeg);
        $smallBefore = filesize($smallJpeg);

        $this->assertGreaterThan(300 * 1024, $largeBefore);

        $this->artisan('images:optimize', ['--path' => [$this->workingDirectory]])
            ->expectsOutputToContain('gedung-besar.jpg')
            ->assertSuccessful();

        clearstatcache();

        $this->assertFileExists($largeJpeg);
        $this->assertLessThan($largeBefore, filesize($largeJpeg));
        $this->assertSame($smallBefore, filesize($smallJpeg));

        [$width, $height, $type] = getimagesize($largeJpeg);

        $this->assertSame(IMAGETYPE_JPEG, $type);
        $this->assertSame(1400, $width);
        $this->assertSame(933, $height);
    }

    public function test_optimize_command_dry_run_does_not_modify_files(): void
    {
        $largeJpeg = $this->workingDirectory.'/foto-besar.jpg';
        $this->createNoisyJpeg($largeJpeg, 2400, 1600);
        $before = md5_file($largeJpeg);

        $this->artisan('images:optimize', ['--path' => [$this->workingDirectory], '--dry-run' => true])
            ->expectsOutputToContain('foto-besar.jpg')
            ->assertSuccessful();

        $this->assertSame($before, md5_file($largeJpeg));
    }

    /**
     * Create a high-entropy JPEG so the file is large on disk.
     */
    private function createNoisyJpeg(string $path, int $width, int $height): void
    {
        $image = imagecreatetruecolor($width, $height);

        for ($y = 0; $y < $height; $y += 2) {
            for ($x = 0; $x < $width; $x += 2) {
                imagefilledrectangle($image, $x, $y, $x + 1, $y + 1, mt_rand(0, 0xFFFFFF));
            }
        }

        imagejpeg($image, $path, 95);
    }
}
