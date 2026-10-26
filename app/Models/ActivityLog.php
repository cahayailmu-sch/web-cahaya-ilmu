<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'user_name',
        'role_level',
        'action',
        'module',
        'description',
        'changes',
        'ip_address',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'changes' => 'array',
        ];
    }

    /**
     * Get the admin user who performed the activity (if still present).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Human-readable label for the action type.
     */
    public function actionLabel(): string
    {
        return match ($this->action) {
            'create' => 'Tambah',
            'update' => 'Edit / Update',
            'delete' => 'Hapus',
            'verify' => 'Verifikasi',
            'complete' => 'Formulir Lengkap',
            'print' => 'Cetak Dokumen',
            'login' => 'Login',
            'logout' => 'Logout',
            'role_change' => 'Ubah Akses',
            default => ucfirst($this->action),
        };
    }

    /**
     * Tailwind badge classes for the action type.
     */
    public function actionBadgeClass(): string
    {
        return match ($this->action) {
            'create', 'complete' => 'bg-emerald-50 text-emerald-700 ring-1 ring-inset ring-emerald-600/20',
            'update', 'verify' => 'bg-amber-50 text-amber-700 ring-1 ring-inset ring-amber-600/20',
            'role_change', 'print' => 'bg-blue-50 text-blue-700 ring-1 ring-inset ring-blue-600/20',
            'delete' => 'bg-rose-50 text-rose-700 ring-1 ring-inset ring-rose-600/20',
            'login', 'logout' => 'bg-slate-100 text-slate-700 ring-1 ring-inset ring-slate-500/20',
            default => 'bg-slate-100 text-slate-700',
        };
    }
}
