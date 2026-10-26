<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\DatabaseBackupService;
use App\Support\ActivityLogger;
use Symfony\Component\HttpFoundation\StreamedResponse;

class BackupController extends Controller
{
    public function __construct(private DatabaseBackupService $backupService) {}

    /**
     * Download a full SQL dump of the active database.
     *
     * Only superadmins may download the dump because it contains personal data
     * (SPMB registrations, admin accounts, and activity logs).
     */
    public function download(): StreamedResponse
    {
        $user = auth()->user();

        abort_unless(
            $user?->isSuperAdmin(),
            403,
            'Hanya Superadmin yang dapat mengunduh cadangan database.',
        );

        $tableCount = $this->backupService->tableCount();

        ActivityLogger::log(
            'backup',
            'Database',
            'Mengunduh cadangan database website ('.$tableCount.' tabel).',
            [
                'tables' => $tableCount,
                'estimated_size' => $this->backupService->estimatedSize(),
                'driver' => $this->backupService->driver(),
            ],
        );

        $fileName = $this->backupService->fileName();

        return response()->streamDownload(function () use ($user): void {
            if (session()->isStarted()) {
                session()->save();
            }

            ignore_user_abort(true);
            @set_time_limit(0);

            $this->backupService->stream($user);
        }, $fileName, [
            'Content-Type' => 'application/sql; charset=UTF-8',
            'Cache-Control' => 'no-store, no-cache, must-revalidate',
            'Pragma' => 'no-cache',
        ]);
    }
}
