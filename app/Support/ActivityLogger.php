<?php

namespace App\Support;

use App\Models\ActivityLog;
use App\Models\User;

class ActivityLogger
{
    /**
     * Record a new activity log entry.
     *
     * @param  array<string, mixed>|null  $changes
     */
    public static function log(
        string $action,
        string $module,
        string $description,
        ?array $changes = null,
        ?User $actor = null,
    ): ActivityLog {
        /** @var User|null $user */
        $user = $actor ?? auth()->user();

        if ($user instanceof User) {
            $userId = $user->id;
            $userName = $user->name;
            $roleLevel = $user->isSuperAdmin()
                ? 'Superadmin'
                : 'Admin '.($user->getLevel() ?: 'Jenjang');
        } else {
            $userId = null;
            $userName = 'Sistem / Publik';
            $roleLevel = 'Publik';
        }

        return ActivityLog::create([
            'user_id' => $userId,
            'user_name' => $userName,
            'role_level' => $roleLevel,
            'action' => $action,
            'module' => $module,
            'description' => $description,
            'changes' => $changes !== [] ? $changes : null,
            'ip_address' => request()?->ip(),
        ]);
    }
}
