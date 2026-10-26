<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Gallery;
use App\Models\Post;
use App\Models\PpdbRegistration;
use App\Models\SchoolAgenda;
use App\Models\SchoolSetting;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Show the admin dashboard summary.
     */
    public function index(): View
    {
        $user = auth()->user();
        $isSuperAdmin = $user->isSuperAdmin();
        $userLevel = $user->getLevel();

        $ppdbQuery = PpdbRegistration::query()
            ->when(! $isSuperAdmin, fn ($query) => $query->where('level', $userLevel));

        $postQuery = Post::query()
            ->when(! $isSuperAdmin, fn ($query) => $query->where('level', $userLevel));

        $galleryQuery = Gallery::query()
            ->when(! $isSuperAdmin, fn ($query) => $query->where('level', $userLevel));

        $agendaQuery = SchoolAgenda::query()
            ->when(! $isSuperAdmin, fn ($query) => $query->where('level', $userLevel));

        $levelCounts = (clone $ppdbQuery)
            ->selectRaw('level, count(*) as total')
            ->groupBy('level')
            ->pluck('total', 'level');

        $statusCounts = (clone $ppdbQuery)
            ->selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $recentActivities = ActivityLog::query()
            ->when(! $isSuperAdmin, fn ($query) => $query->where('user_id', $user->id))
            ->latest()
            ->take(5)
            ->get();

        $levels = $isSuperAdmin ? SchoolSetting::PPDB_LEVELS : array_values(array_filter([$userLevel]));

        return view('admin.dashboard', [
            'totalRegistrations' => (clone $ppdbQuery)->count(),
            'newRegistrations' => (clone $ppdbQuery)->whereIn('status', [PpdbRegistration::STATUS_PENDING, 'Baru'])->count(),
            'levelCounts' => $levelCounts,
            'statusCounts' => $statusCounts,
            'postCount' => (clone $postQuery)->count(),
            'publishedPostCount' => (clone $postQuery)->where('is_published', true)->count(),
            'galleryCount' => (clone $galleryQuery)->count(),
            'agendaCount' => (clone $agendaQuery)->count(),
            'recentRegistrations' => (clone $ppdbQuery)->latest()->take(5)->get(),
            'recentActivities' => $recentActivities,
            'quotaSummary' => SchoolSetting::ppdbQuotaSummary(null, $levels),
            'levels' => $levels,
            'statuses' => array_keys(PpdbRegistration::statusLabels()),
            'statusLabels' => PpdbRegistration::statusLabels(),
        ]);
    }
}
