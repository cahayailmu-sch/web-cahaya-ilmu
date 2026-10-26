@extends('admin.layouts.app')

@section('title', 'Kelola Admin')
@section('subtitle', 'Manajemen akun Superadmin dan Admin per Jenjang')

@section('content')
    <div class="space-y-6">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">Manajemen Akun Admin</h1>
                <p class="mt-1 text-sm text-slate-500">
                    Total {{ number_format($users->count()) }} akun terdaftar &middot; Akses eksklusif Superadmin
                </p>
            </div>
        </div>

        <div class="space-y-6">
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
                <div class="flex items-center gap-2.5">
                    <span class="grid size-9 place-items-center rounded-xl bg-rose-50 text-rose-600">
                        <svg class="size-4.5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM3 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 9.374 21c-2.331 0-4.512-.645-6.374-1.766Z" />
                        </svg>
                    </span>
                    <div>
                        <h2 class="text-base font-semibold text-slate-900">Tambah Akun Admin</h2>
                        <p class="text-xs text-slate-500">Buat akun Superadmin atau Admin Jenjang baru.</p>
                    </div>
                </div>

                <form method="POST" action="{{ route('admin.users.store') }}" class="mt-5 space-y-4">
                    @csrf

                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label for="name" class="block text-sm font-medium text-slate-700">Nama Lengkap <span class="text-rose-600">*</span></label>
                            <input id="name"
                                   type="text"
                                   name="name"
                                   value="{{ old('name') }}"
                                   required
                                   placeholder="Contoh: Admin SD Islam Cahaya Ilmu"
                                   class="mt-1.5 w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-900 shadow-xs outline-none transition focus:border-rose-400 focus:ring-2 focus:ring-rose-100">
                            @error('name')
                                <p class="mt-1.5 text-xs font-medium text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-slate-700">Email Login <span class="text-rose-600">*</span></label>
                            <input id="email"
                                   type="email"
                                   name="email"
                                   value="{{ old('email') }}"
                                   required
                                   placeholder="Contoh: adminsd@cahayailmu.sch.id"
                                   class="mt-1.5 w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-900 shadow-xs outline-none transition focus:border-rose-400 focus:ring-2 focus:ring-rose-100">
                            @error('email')
                                <p class="mt-1.5 text-xs font-medium text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-3">
                        <div>
                            <label for="role" class="block text-sm font-medium text-slate-700">Role <span class="text-rose-600">*</span></label>
                            <select id="role"
                                    name="role"
                                    required
                                    onchange="toggleLevelSelect(this.value)"
                                    class="mt-1.5 w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-900 shadow-xs outline-none transition focus:border-rose-400 focus:ring-2 focus:ring-rose-100">
                                <option value="admin_jenjang" @selected(old('role', 'admin_jenjang') === 'admin_jenjang')>Admin Jenjang</option>
                                <option value="superadmin" @selected(old('role') === 'superadmin')>Superadmin</option>
                            </select>
                            @error('role')
                                <p class="mt-1.5 text-xs font-medium text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div id="level-field-wrapper">
                            <label for="level" class="block text-sm font-medium text-slate-700">Jenjang <span class="text-rose-600">*</span></label>
                            <select id="level"
                                    name="level"
                                    class="mt-1.5 w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-900 shadow-xs outline-none transition focus:border-rose-400 focus:ring-2 focus:ring-rose-100">
                                @foreach ($levels as $level)
                                    <option value="{{ $level }}" @selected(old('level', 'TK') === $level)>{{ $level }}</option>
                                @endforeach
                            </select>
                            @error('level')
                                <p class="mt-1.5 text-xs font-medium text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password" class="block text-sm font-medium text-slate-700">Password <span class="text-rose-600">*</span></label>
                            <input id="password"
                                   type="password"
                                   name="password"
                                   required
                                   minlength="6"
                                   placeholder="Minimal 6 karakter"
                                   class="mt-1.5 w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-900 shadow-xs outline-none transition focus:border-rose-400 focus:ring-2 focus:ring-rose-100">
                            @error('password')
                                <p class="mt-1.5 text-xs font-medium text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex justify-end pt-1">
                        <button type="submit"
                                class="w-full rounded-xl bg-rose-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-rose-700 sm:w-auto">
                            Tambah Akun Admin
                        </button>
                    </div>
                </form>
            </div>

            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-200 px-5 py-4">
                    <h2 class="text-base font-semibold text-slate-900">Daftar Akun Admin</h2>
                    <p class="mt-0.5 text-xs text-slate-500">Kelola hak akses, jenjang, dan reset password akun admin.</p>
                </div>

                <div class="divide-y divide-slate-200 md:hidden">
                    @foreach ($users as $adminUser)
                        <div class="space-y-3 p-4 sm:p-5">
                            <div class="flex items-start justify-between gap-3">
                                <div class="min-w-0">
                                    <div class="flex flex-wrap items-center gap-2">
                                        @if ($adminUser->isSuperAdmin())
                                            <span class="rounded-md bg-rose-50 px-2 py-0.5 text-xs font-bold text-rose-700 ring-1 ring-inset ring-rose-600/20">Superadmin</span>
                                            <span class="rounded-md bg-slate-100 px-2 py-0.5 text-xs font-semibold text-slate-600">Semua Jenjang</span>
                                        @else
                                            <span class="rounded-md bg-amber-50 px-2 py-0.5 text-xs font-semibold text-amber-700 ring-1 ring-inset ring-amber-600/20">Admin Jenjang</span>
                                            <span class="rounded-md bg-slate-900 px-2 py-0.5 text-xs font-bold text-white">Jenjang {{ $adminUser->level }}</span>
                                        @endif
                                    </div>
                                    <p class="mt-2 font-semibold text-slate-900">{{ $adminUser->name }}</p>
                                    <p class="mt-0.5 text-xs text-slate-500">{{ $adminUser->email }}</p>
                                </div>
                            </div>

                            <div class="flex items-center justify-end gap-2 pt-1">
                                <a href="{{ route('admin.users.edit', $adminUser) }}"
                                   class="rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-semibold text-slate-700 transition hover:bg-slate-50">
                                    Edit / Reset Password
                                </a>

                                @if ($adminUser->id !== auth()->id())
                                    <form method="POST"
                                          action="{{ route('admin.users.destroy', $adminUser) }}"
                                          onsubmit='return confirm(@js("Hapus akun admin {$adminUser->name}?"))'>
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="rounded-lg bg-rose-50 px-3 py-1.5 text-xs font-semibold text-rose-600 transition hover:bg-rose-100">
                                            Hapus
                                        </button>
                                    </form>
                                @else
                                    <span class="rounded-lg bg-slate-100 px-2.5 py-1.5 text-[11px] font-semibold text-slate-500">Akun Anda</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="hidden overflow-x-auto md:block">
                    <table class="min-w-full divide-y divide-slate-200 text-sm">
                        <thead class="bg-slate-50 text-left text-[11px] font-semibold uppercase tracking-wider text-slate-500">
                            <tr>
                                <th class="px-5 py-3">Nama &amp; Email</th>
                                <th class="px-5 py-3">Role</th>
                                <th class="px-5 py-3">Hak Akses Jenjang</th>
                                <th class="px-5 py-3 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach ($users as $adminUser)
                                <tr class="align-middle transition hover:bg-slate-50/70">
                                    <td class="px-5 py-4">
                                        <p class="font-semibold text-slate-900">{{ $adminUser->name }}</p>
                                        <p class="mt-0.5 text-xs text-slate-500">{{ $adminUser->email }}</p>
                                    </td>
                                    <td class="whitespace-nowrap px-5 py-4">
                                        @if ($adminUser->isSuperAdmin())
                                            <span class="inline-flex rounded-full bg-rose-50 px-2.5 py-0.5 text-xs font-bold text-rose-700 ring-1 ring-inset ring-rose-600/20">
                                                Superadmin
                                            </span>
                                        @else
                                            <span class="inline-flex rounded-full bg-amber-50 px-2.5 py-0.5 text-xs font-semibold text-amber-700 ring-1 ring-inset ring-amber-600/20">
                                                Admin Jenjang
                                            </span>
                                        @endif
                                    </td>
                                    <td class="whitespace-nowrap px-5 py-4">
                                        @if ($adminUser->isSuperAdmin())
                                            <span class="inline-block rounded-md bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-700">Semua Jenjang (Umum, TK, SD, SMP, SMA)</span>
                                        @else
                                            <span class="inline-block rounded-md bg-slate-900 px-2.5 py-1 text-xs font-bold text-white">Khusus {{ $adminUser->level }}</span>
                                        @endif
                                    </td>
                                    <td class="whitespace-nowrap px-5 py-4">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('admin.users.edit', $adminUser) }}"
                                               class="rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-semibold text-slate-700 transition hover:bg-slate-50">
                                                Edit / Reset Password
                                            </a>

                                            @if ($adminUser->id !== auth()->id())
                                                <form method="POST"
                                                      action="{{ route('admin.users.destroy', $adminUser) }}"
                                                      onsubmit='return confirm(@js("Hapus akun admin {$adminUser->name}?"))'>
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="rounded-lg bg-rose-50 px-3 py-1.5 text-xs font-semibold text-rose-600 transition hover:bg-rose-100">
                                                        Hapus
                                                    </button>
                                                </form>
                                            @else
                                                <span class="rounded-lg bg-slate-100 px-2.5 py-1.5 text-[11px] font-semibold text-slate-500">Sedang Login</span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function toggleLevelSelect(roleValue) {
                const wrapper = document.getElementById('level-field-wrapper');
                const select = document.getElementById('level');
                if (!wrapper || !select) return;

                if (roleValue === 'superadmin') {
                    wrapper.classList.add('opacity-50');
                    select.disabled = true;
                } else {
                    wrapper.classList.remove('opacity-50');
                    select.disabled = false;
                }
            }

            document.addEventListener('DOMContentLoaded', () => {
                const roleSelect = document.getElementById('role');
                if (roleSelect) {
                    toggleLevelSelect(roleSelect.value);
                }
            });
        </script>
    @endpush
@endsection
