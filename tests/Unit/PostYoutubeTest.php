<?php

namespace Tests\Unit;

use App\Models\Post;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class PostYoutubeTest extends TestCase
{
    #[DataProvider('youtubeUrls')]
    public function test_it_builds_clean_embed_and_thumbnail_urls(?string $url, ?string $expectedVideoId): void
    {
        $post = new Post(['youtube_url' => $url]);

        if ($expectedVideoId === null) {
            $this->assertNull($post->youtube_embed_url);
            $this->assertNull($post->youtube_thumbnail_url);
            $this->assertFalse($post->hasYoutubeVideo());

            return;
        }

        $this->assertSame(
            "https://www.youtube-nocookie.com/embed/{$expectedVideoId}?rel=0",
            $post->youtube_embed_url,
        );
        $this->assertSame(
            "https://img.youtube.com/vi/{$expectedVideoId}/hqdefault.jpg",
            $post->youtube_thumbnail_url,
        );
        $this->assertTrue($post->hasYoutubeVideo());
    }

    /**
     * @return array<string, array{0: string|null, 1: string|null}>
     */
    public static function youtubeUrls(): array
    {
        return [
            'standard watch url' => ['https://www.youtube.com/watch?v=dQw4w9WgXcQ', 'dQw4w9WgXcQ'],
            'watch url with extra params' => ['https://www.youtube.com/watch?feature=share&v=dQw4w9WgXcQ&t=30', 'dQw4w9WgXcQ'],
            'shortlink' => ['https://youtu.be/dQw4w9WgXcQ', 'dQw4w9WgXcQ'],
            'shorts' => ['https://www.youtube.com/shorts/dQw4w9WgXcQ', 'dQw4w9WgXcQ'],
            'embed' => ['https://www.youtube.com/embed/dQw4w9WgXcQ', 'dQw4w9WgXcQ'],
            'bare video id' => ['dQw4w9WgXcQ', 'dQw4w9WgXcQ'],
            'empty url' => ['', null],
            'null url' => [null, null],
            'non youtube url' => ['https://vimeo.com/123456789', null],
        ];
    }
}
