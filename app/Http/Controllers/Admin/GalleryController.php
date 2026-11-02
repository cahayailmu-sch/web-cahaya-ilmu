<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use App\Services\ImageCompressor;
use App\Support\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Carbon;
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
            ->orderByRaw('COALESCE(taken_at, created_at) DESC')
            ->orderByDesc('id')
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
        $validated = $this->validateGallery($request, imageRequired: true);

        $validated['taken_at'] = $this->resolveTakenAt($request, $validated['taken_at'] ?? null);
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
                    'taken_at' => $gallery->taken_at?->toDateString(),
                ],
            ],
        );

        return back()->with('success', 'Foto dokumentasi berhasil diunggah.');
    }

    /**
     * Show the form for editing the given gallery photo.
     */
    public function edit(Gallery $gallery): View
    {
        $this->authorizeGalleryAccess($gallery);

        $user = auth()->user();

        return view('admin.galleries.edit', [
            'gallery' => $gallery,
            'levels' => $user->isSuperAdmin()
                ? ['Umum', 'TK', 'SD', 'SMP', 'SMA']
                : array_values(array_filter([$user->getLevel()])),
            'categories' => ['Fasilitas', 'Kegiatan', 'Ekstrakurikuler', 'Prestasi'],
        ]);
    }

    /**
     * Update the given gallery photo.
     */
    public function update(Request $request, Gallery $gallery, ImageCompressor $imageCompressor): RedirectResponse
    {
        $this->authorizeGalleryAccess($gallery);

        $before = [
            'title' => $gallery->title,
            'category' => $gallery->category,
            'level' => $gallery->level,
            'taken_at' => $gallery->taken_at?->toDateString(),
        ];

        $validated = $this->validateGallery($request);

        $validated['taken_at'] = $this->resolveTakenAt($request, $validated['taken_at'] ?? null);

        if ($request->hasFile('image')) {
            Storage::disk('public')->delete($gallery->image);
            $validated['image'] = $imageCompressor->storeUploaded($request->file('image'), 'galleries');
        }

        $gallery->update($validated);

        ActivityLogger::log(
            'update',
            'Galeri',
            "Memperbarui foto galeri: {$gallery->title} ({$gallery->level})",
            [
                'before' => $before,
                'after' => [
                    'title' => $gallery->title,
                    'category' => $gallery->category,
                    'level' => $gallery->level,
                    'taken_at' => $gallery->taken_at?->toDateString(),
                ],
            ],
        );

        return redirect()
            ->route('admin.galleries.index')
            ->with('success', 'Foto dokumentasi berhasil diperbarui.');
    }

    /**
     * Delete the given gallery photo.
     */
    public function destroy(Gallery $gallery): RedirectResponse
    {
        $this->authorizeGalleryAccess($gallery);

        $snapshot = [
            'title' => $gallery->title,
            'category' => $gallery->category,
            'level' => $gallery->level,
            'taken_at' => $gallery->taken_at?->toDateString(),
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

    /**
     * Ensure level-scoped admins can only access photos belonging to their level.
     */
    private function authorizeGalleryAccess(Gallery $gallery): void
    {
        $user = auth()->user();

        if (! $user->isSuperAdmin() && $gallery->level !== $user->getLevel()) {
            abort(403, 'Anda tidak memiliki akses ke foto galeri jenjang ini.');
        }
    }

    /**
     * Validate the gallery request.
     *
     * @return array<string, mixed>
     */
    private function validateGallery(Request $request, bool $imageRequired = false): array
    {
        $user = auth()->user();

        if (! $user->isSuperAdmin()) {
            $request->merge(['level' => $user->getLevel()]);
        }

        $allowedLevels = $user->isSuperAdmin()
            ? ['Umum', 'TK', 'SD', 'SMP', 'SMA']
            : array_values(array_filter([$user->getLevel()]));

        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:100'],
            'level' => ['required', Rule::in($allowedLevels)],
            'taken_at' => ['nullable', 'date'],
            'image' => [
                $imageRequired ? 'required' : 'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:6144',
            ],
        ]);
    }

    /**
     * Resolve the documentation date from the request or the photo EXIF metadata.
     */
    private function resolveTakenAt(Request $request, ?string $takenAt): ?string
    {
        $takenAt = filled($takenAt) ? Carbon::parse($takenAt)->toDateString() : null;

        if (! $request->hasFile('image') || (filled($takenAt) && $takenAt !== today()->toDateString())) {
            return $takenAt;
        }

        return $this->extractTakenAtFromExif($request->file('image')) ?? $takenAt;
    }

    /**
     * Read the DateTimeOriginal EXIF value from an uploaded JPEG photo.
     */
    private function extractTakenAtFromExif(UploadedFile $file): ?string
    {
        if (! function_exists('exif_read_data')) {
            return null;
        }

        if (! in_array(strtolower($file->getClientOriginalExtension()), ['jpg', 'jpeg'], true)) {
            return null;
        }

        $path = $file->getRealPath();

        if ($path === false) {
            return null;
        }

        $exif = @exif_read_data($path, 'EXIF', true);

        if (! is_array($exif)) {
            return null;
        }

        $raw = $exif['EXIF']['DateTimeOriginal'] ?? $exif['DateTimeOriginal'] ?? null;

        if (! is_string($raw) || preg_match('/^(\d{4}):(\d{2}):(\d{2})/', trim($raw), $matches) !== 1) {
            return null;
        }

        [, $year, $month, $day] = array_map('intval', $matches);

        if (! checkdate($month, $day, $year) || $year < 1900) {
            return null;
        }

        return sprintf('%04d-%02d-%02d', $year, $month, $day);
    }
}
