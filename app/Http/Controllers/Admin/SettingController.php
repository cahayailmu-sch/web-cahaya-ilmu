<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PpdbRegistration;
use App\Models\SchoolSetting;
use App\Services\DatabaseBackupService;
use App\Services\ImageCompressor;
use App\Support\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class SettingController extends Controller
{
    /**
     * The image setting keys whose uploaded files are managed here.
     *
     * @var list<string>
     */
    private const IMAGE_KEYS = ['banner_image', 'profile_image'];

    /**
     * Show the general website settings form.
     */
    public function index(DatabaseBackupService $backupService): View
    {
        $isSuperAdmin = (bool) auth()->user()?->isSuperAdmin();

        return view('admin.settings.index', [
            'settings' => SchoolSetting::pluck('value', 'key'),
            'backupSummary' => $isSuperAdmin ? [
                'tables' => $backupService->tableCount(),
                'size' => $backupService->estimatedSize(),
                'registrations' => PpdbRegistration::count(),
                'driver' => $backupService->driver(),
            ] : null,
        ]);
    }

    /**
     * Update the general website settings.
     */
    public function update(Request $request, ImageCompressor $imageCompressor): RedirectResponse
    {
        $rules = [
            'nama_sekolah' => ['nullable', 'string', 'max:255'],
            'foundation_name' => ['nullable', 'string', 'max:255'],
            'motto' => ['nullable', 'string', 'max:255'],
            'alamat_sekolah' => ['nullable', 'string', 'max:500'],
            'email_sekolah' => ['nullable', 'email', 'max:255'],
            'wa_pusat' => ['nullable', 'string', 'max:20', 'regex:/^[0-9+\-\s]+$/'],
            'maps_embed' => ['nullable', 'string', 'max:2000'],
            'jumlah_siswa' => ['nullable', 'integer', 'min:0'],
            'jumlah_guru' => ['nullable', 'integer', 'min:0'],
            'instagram_username' => ['nullable', 'string', 'max:50', 'regex:/^@?[A-Za-z0-9._]+$/'],
            'instagram_url' => ['nullable', 'url', 'max:255'],
            'youtube_channel_name' => ['nullable', 'string', 'max:255'],
            'youtube_url' => ['nullable', 'url', 'max:255'],
            'facebook_url' => ['nullable', 'url', 'max:255'],
            'tiktok_url' => ['nullable', 'url', 'max:255'],
            'banner_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:6144'],
            'profile_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:6144'],
            'remove_banner_image' => ['nullable', 'boolean'],
            'remove_profile_image' => ['nullable', 'boolean'],
        ];

        $messages = [
            'email_sekolah.email' => 'Email resmi sekolah harus berupa alamat email yang valid.',
            'wa_pusat.regex' => 'Nomor WhatsApp pusat tidak valid.',
            'instagram_username.regex' => 'Username Instagram hanya boleh berisi huruf, angka, titik, dan garis bawah.',
        ];

        foreach (['tk' => 'TK', 'sd' => 'SD', 'smp' => 'SMP', 'sma' => 'SMA'] as $key => $label) {
            $rules["hero_image_{$key}"] = ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:6144'];
            $rules["remove_hero_image_{$key}"] = ['nullable', 'boolean'];
            $rules["instagram_username_{$key}"] = ['nullable', 'string', 'max:50', 'regex:/^@?[A-Za-z0-9._]+$/'];
            $rules["instagram_url_{$key}"] = ['nullable', 'url', 'max:255'];
            $rules["youtube_{$key}"] = ['nullable', 'url', 'max:255'];
            $rules["facebook_{$key}"] = ['nullable', 'url', 'max:255'];
            $rules["tiktok_{$key}"] = ['nullable', 'url', 'max:255'];

            for ($index = 1; $index <= 3; $index++) {
                $rules["instagram_post_{$key}_{$index}"] = ['nullable', 'url', 'max:255'];
                $messages["instagram_post_{$key}_{$index}.url"] = "Link postingan Instagram {$label} {$index} harus berupa URL yang valid.";
            }

            $messages["instagram_username_{$key}.regex"] = "Username Instagram {$label} hanya boleh berisi huruf, angka, titik, dan garis bawah.";
            $messages["instagram_url_{$key}.url"] = "Link Instagram {$label} harus berupa URL yang valid.";
            $messages["youtube_{$key}.url"] = "Link YouTube {$label} harus berupa URL yang valid.";
            $messages["facebook_{$key}.url"] = "Link Facebook {$label} harus berupa URL yang valid.";
            $messages["tiktok_{$key}.url"] = "Link TikTok {$label} harus berupa URL yang valid.";
        }

        $validated = $request->validate($rules, $messages);

        $existingSettings = SchoolSetting::pluck('value', 'key');
        $beforeChanges = [];
        $afterChanges = [];

        foreach (['tk', 'sd', 'smp', 'sma'] as $key) {
            if ($request->boolean("remove_hero_image_{$key}")) {
                $this->deleteImageSetting("hero_image_{$key}", $existingSettings, $beforeChanges, $afterChanges);
                unset($validated["hero_image_{$key}"]);
            }

            unset($validated["remove_hero_image_{$key}"]);
        }

        if ($request->boolean('remove_banner_image')) {
            $this->deleteImageSetting('banner_image', $existingSettings, $beforeChanges, $afterChanges);
            unset($validated['banner_image']);
        }

        if ($request->boolean('remove_profile_image')) {
            $this->deleteImageSetting('profile_image', $existingSettings, $beforeChanges, $afterChanges);
            unset($validated['profile_image']);
        }

        unset($validated['remove_banner_image'], $validated['remove_profile_image']);

        foreach ($validated as $key => $value) {
            if ($value === null && $this->isImageKey($key)) {
                continue;
            }

            if ($value instanceof UploadedFile) {
                $this->deleteStoredImage($existingSettings[$key] ?? null);
                $value = $imageCompressor->storeUploaded($value, 'settings');
            } elseif (str_starts_with($key, 'instagram_username')) {
                $value = ltrim(trim((string) $value), '@');
            }

            $normalizedValue = (string) $value;
            $previousValue = (string) ($existingSettings[$key] ?? '');

            if ($previousValue !== $normalizedValue) {
                $beforeChanges[$key] = $previousValue;
                $afterChanges[$key] = $normalizedValue;
            }

            SchoolSetting::updateOrCreate(
                ['key' => $key],
                ['value' => $normalizedValue],
            );
        }

        ActivityLogger::log(
            'update',
            'Pengaturan Website',
            'Memperbarui pengaturan website, identitas sekolah, kontak umum, media sosial, dan gambar hero/banner.',
            $afterChanges !== [] ? ['before' => $beforeChanges, 'after' => $afterChanges] : null,
        );

        return back()->with('success', 'Pengaturan website berhasil disimpan.');
    }

    /**
     * Determine whether a setting key stores an uploaded image path.
     */
    private function isImageKey(string $key): bool
    {
        return str_starts_with($key, 'hero_image_') || in_array($key, self::IMAGE_KEYS, true);
    }

    /**
     * Delete an uploaded image setting and record the change.
     *
     * @param  Collection<string, string|null>  $existingSettings
     * @param  array<string, string>  $beforeChanges
     * @param  array<string, string>  $afterChanges
     */
    private function deleteImageSetting(string $key, Collection $existingSettings, array &$beforeChanges, array &$afterChanges): void
    {
        $oldPath = $existingSettings[$key] ?? null;
        $this->deleteStoredImage($oldPath);

        if (! empty($oldPath)) {
            $beforeChanges[$key] = (string) $oldPath;
            $afterChanges[$key] = '';
        }

        SchoolSetting::where('key', $key)->delete();
    }

    /**
     * Delete a previously stored public image, ignoring seeded or external paths.
     */
    private function deleteStoredImage(?string $path): void
    {
        if (! $path || str_starts_with($path, 'http') || str_starts_with($path, '/assets/') || str_starts_with($path, '/storage/')) {
            return;
        }

        Storage::disk('public')->delete($path);
    }
}
