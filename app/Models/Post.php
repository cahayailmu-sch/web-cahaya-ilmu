<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Post extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'title',
        'slug',
        'category',
        'level',
        'thumbnail',
        'images',
        'instagram_url',
        'youtube_url',
        'content',
        'is_published',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'images' => 'array',
            'is_published' => 'boolean',
        ];
    }

    /**
     * Get the clean YouTube embed URL for the post video.
     */
    protected function youtubeEmbedUrl(): Attribute
    {
        return Attribute::get(function (): ?string {
            $videoId = self::extractYoutubeVideoId($this->youtube_url);

            return $videoId !== null
                ? "https://www.youtube-nocookie.com/embed/{$videoId}?rel=0"
                : null;
        });
    }

    /**
     * Get the automatic YouTube thumbnail for the post video.
     */
    protected function youtubeThumbnailUrl(): Attribute
    {
        return Attribute::get(function (): ?string {
            $videoId = self::extractYoutubeVideoId($this->youtube_url);

            return $videoId !== null
                ? "https://img.youtube.com/vi/{$videoId}/hqdefault.jpg"
                : null;
        });
    }

    /**
     * Determine whether the post has a valid YouTube video.
     */
    public function hasYoutubeVideo(): bool
    {
        return $this->youtube_embed_url !== null;
    }

    /**
     * Extract the YouTube video ID from the supported URL formats.
     */
    public static function extractYoutubeVideoId(?string $url): ?string
    {
        $url = trim((string) $url);

        if ($url === '') {
            return null;
        }

        $patterns = [
            '~youtube\.com/watch\?(?:[^#]*&)?v=([A-Za-z0-9_-]{11})~i',
            '~youtu\.be/([A-Za-z0-9_-]{11})~i',
            '~youtube\.com/shorts/([A-Za-z0-9_-]{11})~i',
            '~youtube\.com/embed/([A-Za-z0-9_-]{11})~i',
            '~youtube\.com/live/([A-Za-z0-9_-]{11})~i',
            '~youtube\.com/v/([A-Za-z0-9_-]{11})~i',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $url, $matches) === 1) {
                return $matches[1];
            }
        }

        return preg_match('/^[A-Za-z0-9_-]{11}$/', $url) === 1 ? $url : null;
    }

    /**
     * Get the public URL of the post thumbnail.
     */
    public function thumbnailUrl(): ?string
    {
        if (! $this->thumbnail) {
            return null;
        }

        return $this->resolveImageUrl($this->thumbnail);
    }

    /**
     * Get the public URLs of the additional slide images (maximum 10 photos).
     *
     * @return list<string>
     */
    public function imageUrls(): array
    {
        $images = $this->images;

        if (! is_array($images)) {
            return [];
        }

        return collect($images)
            ->filter(fn ($image) => is_string($image) && trim($image) !== '')
            ->take(10)
            ->map(fn (string $image) => $this->resolveImageUrl($image))
            ->values()
            ->all();
    }

    /**
     * Resolve one stored image path into a public URL.
     */
    public function imageUrl(string $path): string
    {
        return $this->resolveImageUrl($path);
    }

    /**
     * Get every image that belongs to the post, starting with the thumbnail (maximum 10 photos).
     *
     * @return list<string>
     */
    public function allImageUrls(): array
    {
        $thumbnail = $this->thumbnailUrl();
        $images = $this->imageUrls();

        if ($thumbnail !== null) {
            array_unshift($images, $thumbnail);
        }

        return array_slice(array_values(array_unique($images)), 0, 10);
    }

    /**
     * Get the image used as the primary preview of the post.
     */
    public function primaryImageUrl(): ?string
    {
        return $this->allImageUrls()[0] ?? $this->youtube_thumbnail_url;
    }

    /**
     * Determine whether the post links to an Instagram post or reel.
     */
    public function hasInstagramPost(): bool
    {
        return filled($this->instagram_url);
    }

    /**
     * Resolve a stored image path into a public URL.
     */
    private function resolveImageUrl(string $path): string
    {
        if (preg_match('~^https?://(?:127\.0\.0\.1|localhost)(?::\d+)?(/.*)$~i', $path, $matches) === 1) {
            return $matches[1];
        }

        if (str_starts_with($path, 'http')) {
            return $path;
        }

        if (str_starts_with($path, '/assets/') || str_starts_with($path, 'assets/') || str_starts_with($path, '/storage/')) {
            return '/'.ltrim($path, '/');
        }

        return Storage::disk('public')->url($path);
    }

    /**
     * Get a short plain-text excerpt of the post content.
     */
    public function excerpt(int $limit = 150): string
    {
        return Str::limit(strip_tags($this->content), $limit);
    }

    /**
     * Estimate the reading time of the post in minutes.
     */
    public function readingTime(): int
    {
        return max(1, (int) ceil(str_word_count(strip_tags($this->content)) / 200));
    }
}
