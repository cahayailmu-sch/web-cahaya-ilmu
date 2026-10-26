<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use App\Support\ActivityLogger;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ActivityLogController extends Controller
{
    /**
     * Display the activity log listing with filters.
     */
    public function index(Request $request): View
    {
        $user = auth()->user();
        $isSuperAdmin = $user->isSuperAdmin();

        $modules = ['SPMB', 'Info Sekolah', 'Galeri', 'Pengaturan', 'Kelola Admin', 'Autentikasi'];
        $actions = [
            'create' => 'Tambah',
            'update' => 'Edit / Update',
            'verify' => 'Verifikasi',
            'complete' => 'Formulir Lengkap',
            'print' => 'Cetak Dokumen',
            'role_change' => 'Ubah Akses',
            'delete' => 'Hapus',
            'login' => 'Login',
            'logout' => 'Logout',
        ];

        $activeModule = $request->string('module')->toString();
        $activeAction = $request->string('action')->toString();
        $activeUserId = $request->integer('user_id');
        $dateFrom = $request->string('date_from')->toString();
        $dateTo = $request->string('date_to')->toString();
        $search = trim($request->string('q')->toString());

        $baseQuery = ActivityLog::query()
            ->when(! $isSuperAdmin, fn (Builder $query) => $query->where('user_id', $user->id));

        $logs = (clone $baseQuery)
            ->when(in_array($activeModule, $modules, true), fn (Builder $query) => $query->where('module', $activeModule))
            ->when(array_key_exists($activeAction, $actions), fn (Builder $query) => $query->where('action', $activeAction))
            ->when($isSuperAdmin && $activeUserId > 0, fn (Builder $query) => $query->where('user_id', $activeUserId))
            ->when($dateFrom !== '', fn (Builder $query) => $query->whereDate('created_at', '>=', $dateFrom))
            ->when($dateTo !== '', fn (Builder $query) => $query->whereDate('created_at', '<=', $dateTo))
            ->when($search !== '', function (Builder $query) use ($search): void {
                $like = '%'.$search.'%';
                $query->where(function (Builder $subQuery) use ($like): void {
                    $subQuery->where('description', 'like', $like)
                        ->orWhere('user_name', 'like', $like)
                        ->orWhere('module', 'like', $like);
                });
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $adminUsers = $isSuperAdmin
            ? User::query()->orderBy('name')->get(['id', 'name', 'role', 'level'])
            : collect();

        return view('admin.activity-logs.index', [
            'logs' => $logs,
            'modules' => $modules,
            'actions' => $actions,
            'adminUsers' => $adminUsers,
            'activeModule' => in_array($activeModule, $modules, true) ? $activeModule : null,
            'activeAction' => array_key_exists($activeAction, $actions) ? $activeAction : null,
            'activeUserId' => $isSuperAdmin && $activeUserId > 0 ? $activeUserId : null,
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
            'search' => $search,
            'totalLogs' => (clone $baseQuery)->count(),
            'oldLogsCount' => $isSuperAdmin ? ActivityLog::where('created_at', '<', now()->subDays(90))->count() : 0,
        ]);
    }

    /**
     * Prune activity logs older than 90 days (Superadmin only).
     */
    public function prune(): RedirectResponse
    {
        if (! auth()->user()?->isSuperAdmin()) {
            abort(403, 'Hanya Superadmin yang dapat membersihkan log aktivitas lama.');
        }

        $deletedCount = ActivityLog::query()
            ->where('created_at', '<', now()->subDays(90))
            ->delete();

        ActivityLogger::log(
            'delete',
            'Pengaturan',
            "Membersihkan {$deletedCount} riwayat aktivitas yang berusia lebih dari 90 hari.",
        );

        return redirect()
            ->route('admin.activity-logs.index')
            ->with('success', "Berhasil membersihkan {$deletedCount} log aktivitas yang berusia lebih dari 90 hari.");
    }
}
