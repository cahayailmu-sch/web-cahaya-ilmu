@extends('admin.layouts.guest')

@section('title', 'Admin Panel - Cahaya Ilmu Islamic School Balikpapan - '.config('sekolah.motto'))

@section('content')
    <div>
        <p class="text-[11px] font-semibold uppercase tracking-[0.16em] text-rose-600">Admin Panel - Cahaya Ilmu Islamic School</p>
        <h2 class="mt-2 text-2xl font-bold text-slate-900">Masuk ke Dashboard</h2>
        <p class="mt-2 text-sm text-slate-500">Gunakan email dan password admin yang terdaftar.</p>
        <p class="mt-3 text-xs font-medium italic leading-6 text-amber-700">&ldquo;{{ config('sekolah.motto') }}&rdquo;</p>
    </div>

    @if (session('status'))
        <div class="mt-6 rounded-xl border border-amber-200 bg-amber-50 p-3 text-sm text-amber-800">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('admin.login.attempt') }}" class="mt-8 space-y-5">
        @csrf

        <div>
            <label for="email" class="block text-sm font-medium text-slate-700">Email</label>
            <input id="email"
                   name="email"
                   type="email"
                   value="{{ old('email') }}"
                   required
                   autofocus
                   autocomplete="username"
                   placeholder="admin@cahayailmu.sch.id"
                   @class([
                       'mt-1.5 block w-full rounded-xl border bg-white px-3.5 py-2.5 text-sm shadow-sm outline-none transition placeholder:text-slate-400 focus:ring-4',
                       'border-rose-300 focus:border-rose-400 focus:ring-rose-100' => $errors->has('email'),
                       'border-slate-200 focus:border-rose-400 focus:ring-rose-100' => ! $errors->has('email'),
                   ])>
            @error('email')
                <p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password" class="block text-sm font-medium text-slate-700">Password</label>
            <div class="relative mt-1.5">
                <input id="password"
                       name="password"
                       type="password"
                       required
                       autocomplete="current-password"
                       placeholder="Masukkan password"
                       @class([
                           'block w-full rounded-xl border bg-white px-3.5 py-2.5 pr-11 text-sm shadow-sm outline-none transition placeholder:text-slate-400 focus:ring-4',
                           'border-rose-300 focus:border-rose-400 focus:ring-rose-100' => $errors->has('password'),
                           'border-slate-200 focus:border-rose-400 focus:ring-rose-100' => ! $errors->has('password'),
                       ])>

                <button type="button"
                        id="toggle-password"
                        class="absolute inset-y-0 right-0 grid w-11 place-items-center text-slate-400 transition hover:text-slate-600"
                        aria-label="Tampilkan password">
                    <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                    </svg>
                </button>
            </div>
            @error('password')
                <p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>

        <label class="flex items-center gap-2.5 text-sm text-slate-600">
            <input type="checkbox"
                   name="remember"
                   value="1"
                   class="size-4 rounded border-slate-300 text-rose-600 focus:ring-rose-500">
            Ingat saya di perangkat ini
        </label>

        <button type="submit"
                class="flex w-full items-center justify-center gap-2 rounded-xl bg-rose-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-rose-700 focus:outline-none focus:ring-4 focus:ring-rose-200">
            Masuk
            <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
            </svg>
        </button>
    </form>

    <div class="mt-8 flex items-center justify-between border-t border-slate-100 pt-5 text-xs">
        <a href="{{ url('/') }}" class="font-medium text-slate-500 transition hover:text-rose-600">&larr; Kembali ke website</a>
        <span class="text-slate-400">Cahaya Ilmu Islamic School</span>
    </div>
@endsection

@push('scripts')
    <script>
        const togglePassword = document.getElementById('toggle-password');
        const passwordInput = document.getElementById('password');

        togglePassword?.addEventListener('click', () => {
            const isHidden = passwordInput.type === 'password';
            passwordInput.type = isHidden ? 'text' : 'password';
            togglePassword.setAttribute('aria-label', isHidden ? 'Sembunyikan password' : 'Tampilkan password');
        });
    </script>
@endpush
