@extends('admin.layouts.app')

@section('title', 'Edit Akun Admin')
@section('subtitle', 'Perbarui informasi akun, role, jenjang, atau reset password')

@section('content')
    <div class="mx-auto max-w-2xl space-y-6">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">Edit Akun Admin</h1>
                <p class="mt-1 text-sm text-slate-500">Perbarui nama, email, role, jenjang, atau ganti password untuk {{ $adminUser->name }}.</p>
            </div>
            <a href="{{ route('admin.users.index') }}"
               class="inline-flex items-center gap-1.5 rounded-xl border border-slate-200 bg-white px-3.5 py-2 text-xs font-semibold text-slate-700 transition hover:bg-slate-50">
                Kembali
            </a>
        </div>

        <form method="POST" action="{{ route('admin.users.update', $adminUser) }}" class="space-y-4 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
            @csrf
            @method('PUT')

            <div>
                <label for="name" class="block text-sm font-medium text-slate-700">Nama Lengkap <span class="text-rose-600">*</span></label>
                <input id="name"
                       type="text"
                       name="name"
                       value="{{ old('name', $adminUser->name) }}"
                       required
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
                       value="{{ old('email', $adminUser->email) }}"
                       required
                       class="mt-1.5 w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-900 shadow-xs outline-none transition focus:border-rose-400 focus:ring-2 focus:ring-rose-100">
                @error('email')
                    <p class="mt-1.5 text-xs font-medium text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label for="role" class="block text-sm font-medium text-slate-700">Role <span class="text-rose-600">*</span></label>
                    @if ($adminUser->id === auth()->id())
                        <input type="hidden" name="role" value="superadmin">
                        <input id="role"
                               type="text"
                               value="Superadmin (Akses Penuh)"
                               disabled
                               class="mt-1.5 w-full cursor-not-allowed rounded-xl border border-slate-200 bg-slate-100 px-3.5 py-2.5 text-sm font-semibold text-slate-600 shadow-xs">
                        <p class="mt-1 text-[11px] text-slate-400">Role akun Anda sendiri dikunci sebagai Superadmin (anti lock-out).</p>
                    @else
                        <select id="role"
                                name="role"
                                required
                                onchange="toggleEditLevelSelect(this.value)"
                                class="mt-1.5 w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-900 shadow-xs outline-none transition focus:border-rose-400 focus:ring-2 focus:ring-rose-100">
                            <option value="admin_jenjang" @selected(old('role', $adminUser->role) === 'admin_jenjang')>Admin Jenjang</option>
                            <option value="superadmin" @selected(old('role', $adminUser->role) === 'superadmin')>Superadmin (Akses Penuh)</option>
                        </select>
                    @endif
                    @error('role')
                        <p class="mt-1.5 text-xs font-medium text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div id="edit-level-wrapper" @class(['hidden' => old('role', $adminUser->role) === 'superadmin' || $adminUser->id === auth()->id()])>
                    <label for="level" class="block text-sm font-medium text-slate-700">Pilih Jenjang <span class="text-rose-600">*</span></label>
                    <select id="level"
                            name="level"
                            @disabled(old('role', $adminUser->role) === 'superadmin' || $adminUser->id === auth()->id())
                            class="mt-1.5 w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-900 shadow-xs outline-none transition focus:border-rose-400 focus:ring-2 focus:ring-rose-100">
                        <option value="" disabled @selected(! old('level', $adminUser->level))>-- Pilih Jenjang --</option>
                        @foreach ($levels as $level)
                            <option value="{{ $level }}" @selected(old('level', $adminUser->level ?? 'TK') === $level)>Jenjang {{ $level }}</option>
                        @endforeach
                    </select>
                    @error('level')
                        <p class="mt-1.5 text-xs font-medium text-rose-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="border-t border-slate-100 pt-4">
                <label for="password" class="block text-sm font-medium text-slate-700">
                    Password Baru <span class="font-normal text-slate-400">(opsional - kosongkan jika tidak ingin mengubah password)</span>
                </label>
                <input id="password"
                       type="password"
                       name="password"
                       minlength="6"
                       placeholder="Ketik password baru untuk mereset password akun ini..."
                       class="mt-1.5 w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-900 shadow-xs outline-none transition focus:border-rose-400 focus:ring-2 focus:ring-rose-100">
                @error('password')
                    <p class="mt-1.5 text-xs font-medium text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex flex-col-reverse gap-2 pt-2 sm:flex-row sm:justify-end">
                <a href="{{ route('admin.users.index') }}"
                   class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                    Batal
                </a>
                <button type="submit"
                        class="inline-flex items-center justify-center rounded-xl bg-rose-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-rose-700">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

    @push('scripts')
        <script>
            function toggleEditLevelSelect(roleValue) {
                const wrapper = document.getElementById('edit-level-wrapper');
                const select = document.getElementById('level');
                if (!wrapper || !select) return;

                if (roleValue === 'superadmin') {
                    wrapper.classList.add('hidden');
                    select.disabled = true;
                    select.required = false;
                    select.value = '';
                } else {
                    wrapper.classList.remove('hidden');
                    select.disabled = false;
                    select.required = true;
                    if (!select.value) {
                        select.value = 'TK';
                    }
                }
            }

            document.addEventListener('DOMContentLoaded', () => {
                const roleSelect = document.getElementById('role');
                if (roleSelect && roleSelect.tagName === 'SELECT') {
                    toggleEditLevelSelect(roleSelect.value);
                }
            });
        </script>
    @endpush
@endsection
