<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use App\Services\ImageCompressor;
use App\Support\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class GalleryController extends Controller
{
    /**
     * Display the gallery listing and upload form.
     */
    public function index(Request $request): View
    {
        $user = auth()->user();
        $isSuperAdmin = $user->isSuperAdmin();
        $userLevel = $user->getLevel();

        $levels = $isSuperAdmin ? ['Umum', 'TK', 'SD', 'SMP', 'SMA'] : array_values(array_filter([$userLevel]));
        $activeLevel = $isSuperAdmin ? $request->string('level')->toString() : (string) $userLevel;
        $isLevelFilterActive = in_array($activeLevel, $levels, true);

        $baseScopeQuery = Gallery::query()
            ->when(! $isSuperAdmin, fn ($query) => $query->where('level', $userLevel));

        $galleries = (clone $baseScopeQuery)
            ->when($isSuperAdmin && $isLevelFilterActive, fn ($query) => $query->where('level', $activeLevel))
            ->latest()
            ->paginate(12)
            ->withQueryString();

        $levelCounts = (clone $baseScopeQuery)
            ->selectRaw('level, count(*) as total')
            ->groupBy('level')
            ->pluck('total', 'level');

        return view('admin.galleries.index', [
            'galleries' => $galleries,
            'levels' => $levels,
            'categories' => ['Fasilitas', 'Kegiatan', 'Ekstrakurikuler', 'Prestasi'],
            'activeLevel' => $isLevelFilterActive ? $activeLevel : null,
            'levelCounts' => $levelCounts,
            'totalGalleries' => (clone $baseScopeQuery)->count(),
        ]);
    }

    /**
     * Store a new gallery photo.
     */
    public function store(Request $request, ImageCompressor $imageCompressor): RedirectResponse
    {
        $user = auth()->user();

        if (! $user->isSuperAdmin()) {
            $request->merge(['level' => $user->getLevel()]);
        }

        $allowedLevels = $user->isSuperAdmin()
            ? ['Umum', 'TK', 'SD', 'SMP', 'SMA']
            : array_values(array_filter([$user->getLevel()]));

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:100'],
            'level' => ['required', Rule::in($allowedLevels)],
            'image' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:6144'],
        ]);

        $validated['image'] = $imageCompressor->storeUploaded($request->file('image'), 'galleries');

        $gallery = Gallery::create($validated);

        ActivityLogger::log(
            'create',
            'Galeri',
            "Menambahkan foto galeri baru: {$gallery->title} ({$gallery->level})",
            [
                'after' => [
                    'title' => $gallery->title,
                    'category' => $gallery->category,
                    'level' => $gallery->level,
                ],
            ],
        );

        return back()->with('success', 'Foto dokumentasi berhasil diunggah.');
    }

    /**
     * Delete the given gallery photo.
     */
    public function destroy(Gallery $gallery): RedirectResponse
    {
        $user = auth()->user();

        if (! $user->isSuperAdmin() && $gallery->level !== $user->getLevel()) {
            abort(403, 'Anda tidak memiliki akses ke foto galeri jenjang ini.');
        }

        $snapshot = [
            'title' => $gallery->title,
            'category' => $gallery->category,
            'level' => $gallery->level,
        ];

        Storage::disk('public')->delete($gallery->image);

        $gallery->delete();

        ActivityLogger::log(
            'delete',
            'Galeri',
            "Menghapus foto galeri: {$snapshot['title']} ({$snapshot['level']})",
            ['before' => $snapshot],
        );

        return back()->with('success', 'Foto dokumentasi berhasil dihapus.');
    }
}
