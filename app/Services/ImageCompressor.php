<?php

namespace App\Services;

use GdImage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Throwable;

/**
 * Compress and resize images using PHP's bundled GD extension.
 *
 * - New uploads are resized proportionally (longest side capped) and stored as WebP (quality 80).
 * - Existing files can be optimized in place while keeping their original name and format.
 * - PNG/WebP transparency is preserved.
 * - Non-image files (e.g. PDF) or unreadable images fall back to a regular upload.
 */
class ImageCompressor
{
    /**
     * Longest side (in pixels) for content, gallery, and slide images.
     */
    public const CONTENT_MAX_DIMENSION = 1400;

    /**
     * Longest side (in pixels) for SPMB payment proofs.
     */
    public const PAYMENT_PROOF_MAX_DIMENSION = 1000;

    /**
     * Longest side (in pixels) for SPMB 3x4 passport photos.
     */
    public const PASSPORT_PHOTO_MAX_DIMENSION = 1000;

    /**
     * Default lossy quality for WebP and JPEG output.
     */
    public const QUALITY = 80;

    /**
     * Store an uploaded file, compressing it first when it is a supported image.
     *
     * Returns the stored path relative to the given disk.
     */
    public function storeUploaded(
        UploadedFile $file,
        string $directory,
        int $maxDimension = self::CONTENT_MAX_DIMENSION,
        string $disk = 'public',
    ): string {
        $sourcePath = $file->getRealPath();
        $encoded = $sourcePath !== false ? $this->encodeForUpload($sourcePath, $maxDimension) : null;

        if ($encoded === null) {
            return $file->store($directory, $disk);
        }

        $path = trim($directory, '/').'/'.Str::random(40).'.'.$encoded['extension'];

        Storage::disk($disk)->put($path, $encoded['contents']);

        return $path;
    }

    /**
     * Compress an existing image file in place, keeping its name and format.
     *
     * Returns null when the file is not a supported image. The file is only
     * rewritten when the result is smaller than the original.
     *
     * @return array{before: int, after: int, optimized: bool, original_width: int, original_height: int, width: int, height: int}|null
     */
    public function compressInPlace(
        string $absolutePath,
        int $maxDimension = self::CONTENT_MAX_DIMENSION,
        int $quality = self::QUALITY,
    ): ?array {
        $loaded = $this->loadImage($absolutePath);

        if ($loaded === null) {
            return null;
        }

        $before = (int) filesize($absolutePath);
        $originalWidth = imagesx($loaded['image']);
        $originalHeight = imagesy($loaded['image']);

        try {
            $image = $this->resizeToFit($loaded['image'], $maxDimension);
            $contents = $this->encode($image, $loaded['type'], $quality);
        } catch (Throwable) {
            return null;
        }

        $result = [
            'before' => $before,
            'after' => $before,
            'optimized' => false,
            'original_width' => $originalWidth,
            'original_height' => $originalHeight,
            'width' => imagesx($image),
            'height' => imagesy($image),
        ];

        if ($contents === null || strlen($contents) >= $before) {
            $result['width'] = $originalWidth;
            $result['height'] = $originalHeight;

            return $result;
        }

        $temporaryPath = $absolutePath.'.tmp-'.Str::random(8);

        if (file_put_contents($temporaryPath, $contents) === false) {
            return $result;
        }

        if (! @rename($temporaryPath, $absolutePath)) {
            @unlink($temporaryPath);

            return $result;
        }

        clearstatcache(true, $absolutePath);

        $result['after'] = (int) filesize($absolutePath);
        $result['optimized'] = true;

        return $result;
    }

    /**
     * Resize and encode an uploaded image, preferring WebP output.
     *
     * @return array{contents: string, extension: string}|null
     */
    private function encodeForUpload(string $sourcePath, int $maxDimension): ?array
    {
        $loaded = $this->loadImage($sourcePath);

        if ($loaded === null) {
            return null;
        }

        try {
            $image = $this->resizeToFit($loaded['image'], $maxDimension);

            if (function_exists('imagewebp')) {
                $contents = $this->encode($image, IMAGETYPE_WEBP, self::QUALITY);
                $extension = 'webp';
            } elseif ($loaded['type'] === IMAGETYPE_PNG) {
                $contents = $this->encode($image, IMAGETYPE_PNG, self::QUALITY);
                $extension = 'png';
            } else {
                $contents = $this->encode($image, IMAGETYPE_JPEG, self::QUALITY);
                $extension = 'jpg';
            }
        } catch (Throwable) {
            return null;
        }

        return $contents !== null ? ['contents' => $contents, 'extension' => $extension] : null;
    }

    /**
     * Load a JPEG, PNG, or WebP image into a true-color GD image with alpha preserved.
     *
     * @return array{image: GdImage, type: int}|null
     */
    private function loadImage(string $path): ?array
    {
        if (! extension_loaded('gd') || ! is_file($path)) {
            return null;
        }

        try {
            $info = @getimagesize($path);

            if ($info === false) {
                return null;
            }

            [$width, $height, $type] = $info;

            $this->ensureMemoryFor((int) $width, (int) $height);

            $image = match ($type) {
                IMAGETYPE_JPEG => @imagecreatefromjpeg($path),
                IMAGETYPE_PNG => @imagecreatefrompng($path),
                IMAGETYPE_WEBP => function_exists('imagecreatefromwebp') ? @imagecreatefromwebp($path) : false,
                default => false,
            };

            if (! $image instanceof GdImage) {
                return null;
            }

            if ($type === IMAGETYPE_JPEG) {
                $image = $this->applyExifOrientation($image, $path);
            }

            if (! imageistruecolor($image)) {
                imagepalettetotruecolor($image);
            }

            imagealphablending($image, false);
            imagesavealpha($image, true);

            return ['image' => $image, 'type' => $type];
        } catch (Throwable) {
            return null;
        }
    }

    /**
     * Proportionally shrink the image so its longest side fits the maximum dimension.
     */
    private function resizeToFit(GdImage $image, int $maxDimension): GdImage
    {
        $width = imagesx($image);
        $height = imagesy($image);
        $longestSide = max($width, $height);

        if ($maxDimension <= 0 || $longestSide <= $maxDimension) {
            return $image;
        }

        $ratio = $maxDimension / $longestSide;
        $targetWidth = max(1, (int) round($width * $ratio));
        $targetHeight = max(1, (int) round($height * $ratio));

        $resized = imagecreatetruecolor($targetWidth, $targetHeight);

        imagealphablending($resized, false);
        imagesavealpha($resized, true);
        imagefill($resized, 0, 0, imagecolorallocatealpha($resized, 0, 0, 0, 127));
        imagecopyresampled($resized, $image, 0, 0, 0, 0, $targetWidth, $targetHeight, $width, $height);

        return $resized;
    }

    /**
     * Encode a GD image to the given image type and return the binary contents.
     */
    private function encode(GdImage $image, int $type, int $quality): ?string
    {
        $quality = max(1, min(100, $quality));

        ob_start();

        try {
            $encoded = match ($type) {
                IMAGETYPE_WEBP => imagewebp($image, null, $quality),
                IMAGETYPE_PNG => imagepng($image, null, 9),
                default => $this->encodeJpeg($image, $quality),
            };
        } finally {
            $contents = (string) ob_get_clean();
        }

        return $encoded && $contents !== '' ? $contents : null;
    }

    /**
     * Encode a progressive JPEG, flattening any transparency onto a white background.
     */
    private function encodeJpeg(GdImage $image, int $quality): bool
    {
        $width = imagesx($image);
        $height = imagesy($image);
        $canvas = imagecreatetruecolor($width, $height);

        imagefill($canvas, 0, 0, imagecolorallocate($canvas, 255, 255, 255));
        imagealphablending($canvas, true);
        imagecopy($canvas, $image, 0, 0, 0, 0, $width, $height);
        imageinterlace($canvas, true);

        return imagejpeg($canvas, null, $quality);
    }

    /**
     * Rotate/flip a JPEG according to its EXIF orientation so phone photos stay upright.
     */
    private function applyExifOrientation(GdImage $image, string $path): GdImage
    {
        if (! function_exists('exif_read_data')) {
            return $image;
        }

        $exif = @exif_read_data($path);
        $orientation = is_array($exif) ? (int) ($exif['Orientation'] ?? 1) : 1;

        if (in_array($orientation, [2, 4, 5, 7], true)) {
            imageflip($image, $orientation === 4 ? IMG_FLIP_VERTICAL : IMG_FLIP_HORIZONTAL);
        }

        $angle = match ($orientation) {
            3 => 180,
            5, 8 => 90,
            6, 7 => -90,
            default => 0,
        };

        if ($angle === 0) {
            return $image;
        }

        $rotated = imagerotate($image, $angle, 0);

        return $rotated instanceof GdImage ? $rotated : $image;
    }

    /**
     * Raise the memory limit when decoding a very large image would exceed it.
     */
    private function ensureMemoryFor(int $width, int $height): void
    {
        $required = (int) ($width * $height * 4 * 2.5) + memory_get_usage() + (32 * 1024 * 1024);
        $limit = $this->memoryLimitInBytes();

        if ($limit !== -1 && $limit < $required) {
            @ini_set('memory_limit', (string) (int) ceil($required / 1024 / 1024).'M');
        }
    }

    /**
     * Get the current PHP memory limit in bytes (-1 for unlimited).
     */
    private function memoryLimitInBytes(): int
    {
        $limit = trim((string) ini_get('memory_limit'));

        if ($limit === '' || $limit === '-1') {
            return -1;
        }

        $value = (int) $limit;

        return match (strtolower(substr($limit, -1))) {
            'g' => $value * 1024 * 1024 * 1024,
            'm' => $value * 1024 * 1024,
            'k' => $value * 1024,
            default => $value,
        };
    }
}
