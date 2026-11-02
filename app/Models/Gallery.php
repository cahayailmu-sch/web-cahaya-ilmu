<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

class Gallery extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'title',
        'image',
        'category',
        'level',
        'taken_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'taken_at' => 'date',
        ];
    }

    /**
     * Get the documentation date, falling back to the upload date for older photos.
     */
    public function documentationDate(): ?Carbon
    {
        return $this->taken_at ?? $this->created_at;
    }

    /**
     * Format the documentation date in Indonesian.
     */
    public function documentationDateLabel(): string
    {
        return $this->documentationDate()?->locale('id')->translatedFormat('d F Y') ?? '-';
    }

    /**
     * Get the public URL of the gallery image.
     */
    public function imageUrl(): string
    {
        if (preg_match('~^https?://(?:127\.0\.0\.1|localhost)(?::\d+)?(/.*)$~i', (string) $this->image, $matches) === 1) {
            return $matches[1];
        }

        if (str_starts_with($this->image, 'http')) {
            return $this->image;
        }

        if (str_starts_with($this->image, '/assets/') || str_starts_with($this->image, 'assets/') || str_starts_with($this->image, '/storage/')) {
            return '/'.ltrim($this->image, '/');
        }

        return Storage::disk('public')->url($this->image);
    }
}
