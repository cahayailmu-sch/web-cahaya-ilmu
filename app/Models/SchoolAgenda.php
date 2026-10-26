<?php

namespace App\Models;

use Database\Factories\SchoolAgendaFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolAgenda extends Model
{
    /** @use HasFactory<SchoolAgendaFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'title',
        'description',
        'level',
        'event_date',
        'time_label',
        'location',
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
            'event_date' => 'date',
            'is_published' => 'boolean',
        ];
    }

    /**
     * Determine whether the agenda event date is today or in the future.
     */
    public function isUpcoming(): bool
    {
        return $this->event_date !== null && $this->event_date->greaterThanOrEqualTo(today());
    }
}
