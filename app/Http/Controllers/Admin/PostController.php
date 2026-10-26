<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Services\ImageCompressor;
use App\Support\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PostController extends Controller
{
    public function __construct(private ImageCompressor $imageCompressor) {}

    /**
     * Display a listing of the posts.
     */
    public function index(): View
    {
        $user = auth()->user();

        $posts = Post::query()
            ->when(! $user->isSuperAdmin(), fn ($query) => $query->where('level', $user->getLevel()))
            ->latest()
            ->paginate(10);

        return view('admin.posts.index', [
            'posts' => $posts,
        ]);
    }

    /**
     * Show the form for creating a new post.
     */
    public function create(): View
    {
        return view('admin.posts.create');
    }

    /**
     * Store a newly created post.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validatePost($request);

        $validated['slug'] = $this->uniqueSlug($validated['slug'] ?? '', $validated['title']);
        $validated['is_published'] = $request->boolean('is_published');
        $validated['instagram_url'] = $this->normalizeInstagramUrl($validated['instagram_url'] ?? null);
        $validated['youtube_url'] = $this->normalizeYoutubeUrl($validated['youtube_url'] ?? null);

        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail'] = $this->imageCompressor->storeUploaded($request->file('thumbnail'), 'posts');
        }

        $imagePaths = $this->storeImages($request);

        if ($imagePaths !== []) {
            $validated['images'] = $imagePaths;
        } else {
            unset($validated['images']);
        }

        $post = Post::create($validated);

        ActivityLogger::log(
            'create',
            'Info Sekolah',
            "Menambahkan Info Sekolah baru: {$post->title} ({$post->level})",
            [
                'after' => [
                    'title' => $post->title,
                    'category' => $post->category,
                    'level' => $post->level,
                    'is_published' => $post->is_published,
                ],
            ],
        );

        return redirect()
            ->route('admin.posts.index')
            ->with('success', 'Berita berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the given post.
     */
    public function edit(Post $post): View
    {
        $this->authorizePostAccess($post);

        return view('admin.posts.edit', [
            'post' => $post,
        ]);
    }

    /**
     * Update the given post.
     */
    public function update(Request $request, Post $post): RedirectResponse
    {
        $this->authorizePostAccess($post);

        $before = [
            'title' => $post->title,
            'category' => $post->category,
            'level' => $post->level,
            'is_published' => $post->is_published,
        ];

        $validated = $this->validatePost($request);

        $validated['slug'] = $this->uniqueSlug($validated['slug'] ?? '', $validated['title'], $post->id);
        $validated['is_published'] = $request->boolean('is_published');
        $validated['instagram_url'] = $this->normalizeInstagramUrl($validated['instagram_url'] ?? null);
        $validated['youtube_url'] = $this->normalizeYoutubeUrl($validated['youtube_url'] ?? null);

        if ($request->boolean('remove_thumbnail') && $post->thumbnail) {
            $this->deleteStoredFile($post->thumbnail);
            $validated['thumbnail'] = null;
        }

        if ($request->hasFile('thumbnail')) {
            $this->deleteStoredFile($post->thumbnail);
            $validated['thumbnail'] = $this->imageCompressor->storeUploaded($request->file('thumbnail'), 'posts');
        }

        $existingImages = is_array($post->images) ? $post->images : [];
        $removedImages = collect($request->input('remove_images', []))
            ->filter(fn ($path) => is_string($path) && in_array($path, $existingImages, true))
            ->values()
            ->all();

        foreach ($removedImages as $path) {
            $this->deleteStoredFile($path);
        }

        $remainingImages = array_values(array_diff($existingImages, $removedImages));
        $mergedImages = array_slice(
            array_values(array_merge($remainingImages, $this->storeImages($request))),
            0,
            10,
        );
        $validated['images'] = $mergedImages !== [] ? $mergedImages : null;

        $post->update($validated);

        ActivityLogger::log(
            'update',
            'Info Sekolah',
            "Memperbarui Info Sekolah: {$post->title} ({$post->level})",
            [
                'before' => $before,
                'after' => [
                    'title' => $post->title,
                    'category' => $post->category,
                    'level' => $post->level,
                    'is_published' => $post->is_published,
                ],
            ],
        );

        return redirect()
            ->route('admin.posts.index')
            ->with('success', 'Berita berhasil diperbarui.');
    }

    /**
     * Delete the given post.
     */
    public function destroy(Post $post): RedirectResponse
    {
        $this->authorizePostAccess($post);

        $snapshot = [
            'title' => $post->title,
            'category' => $post->category,
            'level' => $post->level,
        ];

        $this->deleteStoredFile($post->thumbnail);

        foreach (is_array($post->images) ? $post->images : [] as $image) {
            $this->deleteStoredFile($image);
        }

        $post->delete();

        ActivityLogger::log(
            'delete',
            'Info Sekolah',
            "Menghapus Info Sekolah: {$snapshot['title']} ({$snapshot['level']})",
            ['before' => $snapshot],
        );

        return redirect()
            ->route('admin.posts.index')
            ->with('success', 'Berita berhasil dihapus.');
    }

    /**
     * Ensure level-scoped admins can only access posts belonging to their level.
     */
    private function authorizePostAccess(Post $post): void
    {
        $user = auth()->user();

        if (! $user->isSuperAdmin() && $post->level !== $user->getLevel()) {
            abort(403, 'Anda tidak memiliki akses ke info sekolah jenjang ini.');
        }
    }

    /**
     * Validate the post request.
     *
     * @return array<string, mixed>
     */
    private function validatePost(Request $request): array
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
            'slug' => ['nullable', 'string', 'max:255'],
            'category' => ['required', Rule::in(['Berita', 'Pengumuman', 'Prestasi'])],
            'level' => ['required', Rule::in($allowedLevels)],
            'thumbnail' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:6144'],
            'images' => ['nullable', 'array', 'max:10'],
            'images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:6144'],
            'remove_images' => ['nullable', 'array'],
            'remove_images.*' => ['string'],
            'instagram_url' => ['nullable', 'url', 'max:255'],
            'youtube_url' => [
                'nullable',
                'string',
                'max:255',
                function (string $attribute, mixed $value, \Closure $fail): void {
                    if (filled($value) && Post::extractYoutubeVideoId((string) $value) === null) {
                        $fail('Link YouTube tidak valid. Gunakan format seperti https://www.youtube.com/watch?v=... atau https://youtu.be/...');
                    }
                },
            ],
            'content' => ['required', 'string'],
        ]);
    }

    /**
     * Store the additional slide images.
     *
     * @return list<string>
     */
    private function storeImages(Request $request): array
    {
        if (! $request->hasFile('images')) {
            return [];
        }

        return collect($request->file('images'))
            ->filter()
            ->map(fn ($file) => $this->imageCompressor->storeUploaded($file, 'posts'))
            ->values()
            ->all();
    }

    /**
     * Normalize an optional Instagram URL.
     */
    private function normalizeInstagramUrl(?string $url): ?string
    {
        $url = trim((string) $url);

        return $url !== '' ? $url : null;
    }

    /**
     * Normalize an optional YouTube URL.
     */
    private function normalizeYoutubeUrl(?string $url): ?string
    {
        $url = trim((string) $url);

        if ($url === '') {
            return null;
        }

        if (! preg_match('~^https?://~i', $url)) {
            $url = 'https://'.ltrim($url, '/');
        }

        return $url;
    }

    /**
     * Delete a local file from the public disk.
     */
    private function deleteStoredFile(?string $path): void
    {
        if (! $path || str_starts_with($path, 'http')) {
            return;
        }

        Storage::disk('public')->delete($path);
    }

    /**
     * Build a unique slug for the post.
     */
    private function uniqueSlug(string $slug, string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($slug !== '' ? $slug : $title);
        $candidate = $base;
        $counter = 2;

        while (
            Post::query()
                ->where('slug', $candidate)
                ->when($ignoreId, fn ($query) => $query->whereKeyNot($ignoreId))
                ->exists()
        ) {
            $candidate = "{$base}-{$counter}";
            $counter++;
        }

        return $candidate;
    }
}
