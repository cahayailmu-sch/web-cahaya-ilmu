<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Support\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class AdminUserController extends Controller
{
    /**
     * Display the listing of admin accounts and creation form.
     */
    public function index(): View
    {
        $this->ensureSuperAdmin();

        $users = User::query()
            ->orderByRaw("CASE WHEN role = 'superadmin' THEN 0 ELSE 1 END")
            ->orderBy('level')
            ->orderBy('name')
            ->get();

        return view('admin.users.index', [
            'users' => $users,
            'levels' => ['TK', 'SD', 'SMP', 'SMA'],
        ]);
    }

    /**
     * Store a newly created admin account.
     */
    public function store(Request $request): RedirectResponse
    {
        $this->ensureSuperAdmin();

        if ($request->input('role') === 'superadmin') {
            $request->merge(['level' => null]);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')],
            'password' => ['required', 'string', Password::min(6)],
            'role' => ['required', Rule::in(['superadmin', 'admin_jenjang'])],
            'level' => [
                Rule::requiredIf(fn () => $request->input('role') === 'admin_jenjang'),
                'nullable',
                Rule::in(['TK', 'SD', 'SMP', 'SMA']),
            ],
        ]);

        if ($validated['role'] === 'superadmin') {
            $validated['level'] = null;
        }

        $newUser = User::create($validated);

        $roleLabel = $newUser->isSuperAdmin() ? 'Superadmin' : "Admin {$newUser->level}";

        ActivityLogger::log(
            'create',
            'Kelola Admin',
            "Menambahkan akun admin baru: {$newUser->name} ({$roleLabel})",
            [
                'after' => [
                    'name' => $newUser->name,
                    'email' => $newUser->email,
                    'role' => $newUser->role,
                    'level' => $newUser->level,
                ],
            ],
        );

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Akun admin baru berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified admin account.
     */
    public function edit(User $user): View
    {
        $this->ensureSuperAdmin();

        return view('admin.users.edit', [
            'adminUser' => $user,
            'levels' => ['TK', 'SD', 'SMP', 'SMA'],
        ]);
    }

    /**
     * Update the specified admin account (including optional password reset).
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        $this->ensureSuperAdmin();

        if ($request->input('role') === 'superadmin') {
            $request->merge(['level' => null]);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => ['nullable', 'string', Password::min(6)],
            'role' => ['required', Rule::in(['superadmin', 'admin_jenjang'])],
            'level' => [
                Rule::requiredIf(fn () => $request->input('role') === 'admin_jenjang'),
                'nullable',
                Rule::in(['TK', 'SD', 'SMP', 'SMA']),
            ],
        ]);

        if ($guardResponse = $this->guardAgainstSuperAdminLockout($user, $validated['role'])) {
            return $guardResponse;
        }

        if ($validated['role'] === 'superadmin') {
            $validated['level'] = null;
        }

        $before = [
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
            'level' => $user->level,
        ];

        $passwordReset = ! empty($validated['password']);

        if (! $passwordReset) {
            unset($validated['password']);
        }

        $user->update($validated);

        ActivityLogger::log(
            'update',
            'Kelola Admin',
            "Memperbarui akun admin: {$user->name}".($passwordReset ? ' (termasuk reset password)' : ''),
            [
                'before' => $before,
                'after' => [
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role,
                    'level' => $user->level,
                    'password_reset' => $passwordReset,
                ],
            ],
        );

        return redirect()
            ->route('admin.users.index')
            ->with('success', "Akun {$user->name} berhasil diperbarui.");
    }

    /**
     * Quickly upgrade/downgrade an admin account's role and level from the table.
     */
    public function updateRole(Request $request, User $user): RedirectResponse
    {
        $this->ensureSuperAdmin();

        if ($request->input('role') === 'superadmin') {
            $request->merge(['level' => null]);
        }

        $validated = $request->validate([
            'role' => ['required', Rule::in(['superadmin', 'admin_jenjang'])],
            'level' => [
                Rule::requiredIf(fn () => $request->input('role') === 'admin_jenjang'),
                'nullable',
                Rule::in(['TK', 'SD', 'SMP', 'SMA']),
            ],
        ]);

        if ($guardResponse = $this->guardAgainstSuperAdminLockout($user, $validated['role'])) {
            return $guardResponse;
        }

        $before = [
            'role' => $user->role,
            'level' => $user->level,
        ];

        $newRole = $validated['role'];
        $newLevel = $newRole === 'superadmin' ? null : $validated['level'];

        $user->update([
            'role' => $newRole,
            'level' => $newLevel,
        ]);

        $roleLabel = $newRole === 'superadmin'
            ? 'Superadmin (Semua Jenjang)'
            : "Admin Jenjang {$newLevel}";

        ActivityLogger::log(
            'role_change',
            'Kelola Admin',
            "Mengubah level akses akun {$user->name} menjadi {$roleLabel}",
            [
                'before' => $before,
                'after' => [
                    'role' => $user->role,
                    'level' => $user->level,
                ],
            ],
        );

        return redirect()
            ->route('admin.users.index')
            ->with('success', "Level akses akun {$user->name} berhasil diperbarui menjadi {$roleLabel}.");
    }

    /**
     * Remove the specified admin account.
     */
    public function destroy(User $user): RedirectResponse
    {
        $this->ensureSuperAdmin();

        if ($user->id === auth()->id()) {
            return back()->with('status', 'Anda tidak dapat menghapus akun Superadmin yang sedang digunakan login.');
        }

        if ($user->isSuperAdmin() && User::where('role', 'superadmin')->whereKeyNot($user->id)->count() < 1) {
            return back()->with('status', 'Sistem wajib memiliki minimal 1 akun Superadmin aktif.');
        }

        $snapshot = [
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
            'level' => $user->level,
        ];

        $user->delete();

        ActivityLogger::log(
            'delete',
            'Kelola Admin',
            "Menghapus akun admin: {$snapshot['name']} ({$snapshot['email']})",
            ['before' => $snapshot],
        );

        return redirect()
            ->route('admin.users.index')
            ->with('success', "Akun {$snapshot['name']} berhasil dihapus.");
    }

    /**
     * Prevent self-downgrade or removing the last active Superadmin.
     */
    private function guardAgainstSuperAdminLockout(User $targetUser, string $newRole): ?RedirectResponse
    {
        if ($newRole === 'superadmin') {
            return null;
        }

        if ($targetUser->id === auth()->id()) {
            return back()->with('status', 'Anda tidak dapat menurunkan level (downgrade) akun Superadmin yang sedang digunakan login.');
        }

        if ($targetUser->isSuperAdmin() && User::where('role', 'superadmin')->whereKeyNot($targetUser->id)->count() < 1) {
            return back()->with('status', 'Sistem wajib memiliki minimal 1 akun Superadmin aktif.');
        }

        return null;
    }

    /**
     * Abort with 403 if the current user is not a superadmin.
     */
    private function ensureSuperAdmin(): void
    {
        if (! auth()->user()?->isSuperAdmin()) {
            abort(403, 'Halaman ini khusus untuk Superadmin.');
        }
    }
}
