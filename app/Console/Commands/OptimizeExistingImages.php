<?php

namespace App\Console\Commands;

use App\Services\ImageCompressor;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Symfony\Component\Finder\SplFileInfo;

class OptimizeExistingImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'images:optimize
                            {--min-size=300 : Only process images larger than this size (in KB)}
                            {--max-dimension=1400 : Longest side in pixels for content images}
                            {--quality=80 : JPEG/WebP quality (1-100)}
                            {--dry-run : List the images that would be optimized without changing them}
                            {--path=* : Custom directories to scan (defaults to public/assets/images and storage/app/public)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Compress and resize existing JPG/PNG images in place (same name and extension) in public/assets/images and storage/app/public';

    /**
     * Image extensions that will be optimized.
     *
     * @var list<string>
     */
    private const EXTENSIONS = ['jpg', 'jpeg', 'png'];

    /**
     * Execute the console command.
     */
    public function handle(ImageCompressor $compressor): int
    {
        $minimumBytes = max(0, (int) $this->option('min-size')) * 1024;
        $maxDimension = max(1, (int) $this->option('max-dimension'));
        $quality = max(1, min(100, (int) $this->option('quality')));
        $isDryRun = (bool) $this->option('dry-run');

        $files = $this->collectImages($minimumBytes);

        if ($files === []) {
            $this->components->info('Tidak ada gambar JPG/PNG di atas '.$this->formatBytes($minimumBytes).' yang perlu dikompres.');

            return self::SUCCESS;
        }

        $this->components->info(sprintf(
            '%s %d gambar di atas %s...',
            $isDryRun ? 'Ditemukan' : 'Mengompres',
            count($files),
            $this->formatBytes($minimumBytes),
        ));

        $totalBefore = 0;
        $totalAfter = 0;
        $optimizedCount = 0;

        foreach ($files as $file) {
            $absolutePath = $file->getRealPath();
            $relativePath = str_replace('\\', '/', ltrim(str_replace(base_path(), '', $absolutePath), '\\/'));
            $limit = $this->maxDimensionFor($absolutePath, $maxDimension);

            if ($isDryRun) {
                $this->components->twoColumnDetail($relativePath, $this->formatBytes($file->getSize()));

                continue;
            }

            $result = $compressor->compressInPlace($absolutePath, $limit, $quality);

            if ($result === null) {
                $this->components->twoColumnDetail($relativePath, '<fg=yellow>DILEWATI (bukan gambar valid)</>');

                continue;
            }

            $totalBefore += $result['before'];
            $totalAfter += $result['after'];

            if (! $result['optimized']) {
                $this->components->twoColumnDetail(
                    $relativePath,
                    sprintf('<fg=gray>%s (sudah optimal)</>', $this->formatBytes($result['before'])),
                );

                continue;
            }

            $optimizedCount++;

            $this->components->twoColumnDetail(
                sprintf(
                    '%s <fg=gray>%dx%d → %dx%d</>',
                    $relativePath,
                    $result['original_width'],
                    $result['original_height'],
                    $result['width'],
                    $result['height'],
                ),
                sprintf(
                    '%s → <fg=green>%s</> (-%s%%)',
                    $this->formatBytes($result['before']),
                    $this->formatBytes($result['after']),
                    $this->savedPercentage($result['before'], $result['after']),
                ),
            );
        }

        if ($isDryRun) {
            return self::SUCCESS;
        }

        $this->newLine();
        $this->components->info(sprintf(
            '%d gambar dikompres. Total: %s → %s (hemat %s, -%s%%).',
            $optimizedCount,
            $this->formatBytes($totalBefore),
            $this->formatBytes($totalAfter),
            $this->formatBytes($totalBefore - $totalAfter),
            $this->savedPercentage($totalBefore, $totalAfter),
        ));

        return self::SUCCESS;
    }

    /**
     * Collect JPG/PNG files above the minimum size from the configured folders.
     *
     * @return list<SplFileInfo>
     */
    private function collectImages(int $minimumBytes): array
    {
        $directories = array_values(array_filter((array) $this->option('path'))) ?: [
            public_path('assets/images'),
            storage_path('app/public'),
        ];

        $files = [];

        foreach ($directories as $directory) {
            if (! File::isDirectory($directory)) {
                continue;
            }

            foreach (File::allFiles($directory) as $file) {
                if (
                    in_array(strtolower($file->getExtension()), self::EXTENSIONS, true)
                    && $file->getSize() > $minimumBytes
                ) {
                    $files[] = $file;
                }
            }
        }

        return $files;
    }

    /**
     * Payment proofs use a smaller maximum dimension than content images.
     */
    private function maxDimensionFor(string $absolutePath, int $defaultMaxDimension): int
    {
        $normalizedPath = str_replace('\\', '/', $absolutePath);

        if (str_contains($normalizedPath, '/payment-proofs/')) {
            return min($defaultMaxDimension, ImageCompressor::PAYMENT_PROOF_MAX_DIMENSION);
        }

        return $defaultMaxDimension;
    }

    /**
     * Format a byte count as a human-readable size.
     */
    private function formatBytes(int $bytes): string
    {
        if ($bytes >= 1024 * 1024) {
            return number_format($bytes / 1024 / 1024, 2).' MB';
        }

        return number_format($bytes / 1024, 1).' KB';
    }

    /**
     * Calculate the saved percentage between two sizes.
     */
    private function savedPercentage(int $before, int $after): string
    {
        if ($before <= 0) {
            return '0';
        }

        return number_format((($before - $after) / $before) * 100, 1);
    }
}
