<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
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
    ];

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
