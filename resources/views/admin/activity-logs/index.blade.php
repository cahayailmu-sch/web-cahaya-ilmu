@extends('admin.layouts.app')

@section('title', 'Riwayat Aktivitas')
@section('subtitle', 'Audit log perubahan data, autentikasi, dan aktivitas seluruh modul')

@section('content')
    <div class="space-y-6">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">Riwayat Aktivitas (Activity Log)</h1>
                <p class="mt-1 text-sm text-slate-500">
                    Total {{ number_format($totalLogs) }} aktivitas tercatat
                    @if ($activeModule)
                        &middot; modul {{ $activeModule }}
                    @endif
                    @if ($activeAction)
                        &middot; aksi {{ $actions[$activeAction] ?? $activeAction }}
                    @endif
                </p>
            </div>

            @if (auth()->user()->isSuperAdmin())
                <form method="POST"
                      action="{{ route('admin.activity-logs.prune') }}"
                      onsubmit='return confirm("Bersihkan seluruh log aktivitas yang berusia lebih dari 90 hari?")'>
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="inline-flex items-center gap-2 rounded-xl border border-rose-200 bg-rose-50 px-4 py-2.5 text-xs sm:text-sm font-semibold text-rose-700 shadow-xs transition hover:bg-rose-100">
                        <svg class="size-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                        </svg>
                        Bersihkan Log Lama (&gt; 90 Hari)
                        @if ($oldLogsCount > 0)
                            <span class="rounded-md bg-rose-600 px-1.5 py-0.5 text-[10px] font-bold text-white">{{ number_format($oldLogsCount) }}</span>
                        @endif
                    </button>
                </form>
            @endif
        </div>

        <form method="GET" action="{{ route('admin.activity-logs.index') }}" class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:p-5">
            <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-6">
                <div class="sm:col-span-2">
                    <label for="q" class="block text-xs font-semibold text-slate-600">Cari Aktivitas</label>
                    <input id="q"
                           type="search"
                           name="q"
                           value="{{ $search }}"
                           placeholder="Cari deskripsi perubahan, nama pelaku, modul..."
                           class="mt-1 w-full rounded-xl border border-slate-200 bg-slate-50/60 px-3.5 py-2 text-sm text-slate-900 placeholder:text-slate-400 outline-none transition focus:border-rose-400 focus:bg-white focus:ring-2 focus:ring-rose-100">
                </div>

                <div>
                    <label for="module" class="block text-xs font-semibold text-slate-600">Modul</label>
                    <select id="module"
                            name="module"
                            class="mt-1 w-full rounded-xl border border-slate-200 bg-slate-50/60 px-3 py-2 text-sm font-medium text-slate-700 outline-none transition focus:border-rose-400 focus:bg-white focus:ring-2 focus:ring-rose-100">
                        <option value="">Semua Modul</option>
                        @foreach ($modules as $moduleOption)
                            <option value="{{ $moduleOption }}" @selected($activeModule === $moduleOption)>{{ $moduleOption }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="action" class="block text-xs font-semibold text-slate-600">Jenis Aksi</label>
                    <select id="action"
                            name="action"
                            class="mt-1 w-full rounded-xl border border-slate-200 bg-slate-50/60 px-3 py-2 text-sm font-medium text-slate-700 outline-none transition focus:border-rose-400 focus:bg-white focus:ring-2 focus:ring-rose-100">
                        <option value="">Semua Aksi</option>
                        @foreach ($actions as $actionKey => $actionLabel)
                            <option value="{{ $actionKey }}" @selected($activeAction === $actionKey)>{{ $actionLabel }}</option>
                        @endforeach
                    </select>
                </div>

                @if (auth()->user()->isSuperAdmin())
                    <div>
                        <label for="user_id" class="block text-xs font-semibold text-slate-600">Akun Admin</label>
                        <select id="user_id"
                                name="user_id"
                                class="mt-1 w-full rounded-xl border border-slate-200 bg-slate-50/60 px-3 py-2 text-sm font-medium text-slate-700 outline-none transition focus:border-rose-400 focus:bg-white focus:ring-2 focus:ring-rose-100">
                            <option value="">Semua Pelaku</option>
                            @foreach ($adminUsers as $adminAccount)
                                <option value="{{ $adminAccount->id }}" @selected($activeUserId === $adminAccount->id)>
                                    {{ $adminAccount->name }} ({{ $adminAccount->isSuperAdmin() ? 'Superadmin' : $adminAccount->level }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <div class="grid grid-cols-2 gap-2 sm:col-span-2 lg:col-span-1">
                    <div>
                        <label for="date_from" class="block text-xs font-semibold text-slate-600">Dari Tanggal</label>
                        <input id="date_from"
                               type="date"
                               name="date_from"
                               value="{{ $dateFrom }}"
                               class="mt-1 w-full rounded-xl border border-slate-200 bg-slate-50/60 px-2.5 py-2 text-xs text-slate-900 outline-none transition focus:border-rose-400 focus:bg-white focus:ring-2 focus:ring-rose-100">
                    </div>
                    <div>
                        <label for="date_to" class="block text-xs font-semibold text-slate-600">Sampai</label>
                        <input id="date_to"
                               type="date"
                               name="date_to"
                               value="{{ $dateTo }}"
                               class="mt-1 w-full rounded-xl border border-slate-200 bg-slate-50/60 px-2.5 py-2 text-xs text-slate-900 outline-none transition focus:border-rose-400 focus:bg-white focus:ring-2 focus:ring-rose-100">
                    </div>
                </div>
            </div>

            <div class="mt-3 flex flex-wrap items-center justify-end gap-2 border-t border-slate-100 pt-3">
                @if ($activeModule || $activeAction || $activeUserId || $dateFrom !== '' || $dateTo !== '' || $search !== '')
                    <a href="{{ route('admin.activity-logs.index') }}"
                       class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-3.5 py-2 text-xs font-semibold text-slate-600 transition hover:bg-slate-50">
                        Reset Filter
                    </a>
                @endif
                <button type="submit"
                        class="inline-flex items-center justify-center rounded-xl bg-slate-900 px-4 py-2 text-xs font-semibold text-white transition hover:bg-slate-800">
                    Terapkan Filter
                </button>
            </div>
        </form>

        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="divide-y divide-slate-200">
                @forelse ($logs as $log)
                    <div class="p-4 transition hover:bg-slate-50/70 sm:p-5">
                        <div class="flex flex-col gap-3 lg:flex-row lg:items-start lg:justify-between">
                            <div class="min-w-0 flex-1 space-y-2">
                                <div class="flex flex-wrap items-center gap-2">
                                    <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $log->actionBadgeClass() }}">
                                        {{ $log->actionLabel() }}
                                    </span>

                                    <span class="rounded-md bg-slate-100 px-2.5 py-0.5 text-xs font-semibold text-slate-700">
                                        {{ $log->module }}
                                    </span>

                                    <span class="text-sm font-semibold text-slate-900">
                                        {{ $log->user_name }}
                                    </span>

                                    @if ($log->role_level)
                                        <span @class([
                                            'rounded-md px-2 py-0.5 text-[11px] font-bold',
                                            'bg-rose-50 text-rose-700 ring-1 ring-inset ring-rose-600/20' => $log->role_level === 'Superadmin',
                                            'bg-amber-50 text-amber-700 ring-1 ring-inset ring-amber-600/20' => str_starts_with($log->role_level, 'Admin'),
                                            'bg-slate-100 text-slate-600' => $log->role_level === 'Publik',
                                        ])>
                                            {{ $log->role_level }}
                                        </span>
                                    @endif

                                    @if ($log->ip_address)
                                        <span class="font-mono text-[11px] text-slate-400">
                                            IP: {{ $log->ip_address }}
                                        </span>
                                    @endif
                                </div>

                                <p class="text-sm leading-relaxed text-slate-800">
                                    {{ $log->description }}
                                </p>

                                @if (! empty($log->changes))
                                    <details class="group mt-2 rounded-xl border border-slate-200 bg-slate-50/80 p-3 text-xs">
                                        <summary class="cursor-pointer select-none font-semibold text-rose-700 transition hover:text-rose-800">
                                            Lihat Detail Perubahan
                                        </summary>
                                        <div class="mt-3 grid gap-3 sm:grid-cols-2">
                                            @if (isset($log->changes['before']))
                                                <div class="rounded-lg border border-slate-200 bg-white p-2.5">
                                                    <p class="mb-1.5 text-[11px] font-bold uppercase tracking-wider text-slate-400">Data Sebelum (Before)</p>
                                                    <pre class="overflow-x-auto font-mono text-[11px] leading-5 text-slate-700">{{ json_encode($log->changes['before'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                                </div>
                                            @endif
                                            @if (isset($log->changes['after']))
                                                <div class="rounded-lg border border-slate-200 bg-white p-2.5">
                                                    <p class="mb-1.5 text-[11px] font-bold uppercase tracking-wider text-emerald-600">Data Sesudah (After)</p>
                                                    <pre class="overflow-x-auto font-mono text-[11px] leading-5 text-slate-700">{{ json_encode($log->changes['after'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                                </div>
                                            @endif
                                        </div>
                                    </details>
                                @endif
                            </div>

                            <div class="shrink-0 text-xs text-slate-500 lg:text-right">
                                <p class="font-semibold text-slate-700">
                                    {{ $log->created_at?->timezone('Asia/Makassar')->format('d M Y, H:i') }} WITA
                                </p>
                                <p class="mt-0.5 text-slate-400">
                                    {{ $log->created_at?->diffForHumans() }}
                                </p>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-16 text-center">
                        <p class="text-sm font-semibold text-slate-900">Belum ada riwayat aktivitas</p>
                        <p class="mt-1 text-xs text-slate-500">Seluruh aktivitas login, pendaftaran SPMB, info sekolah, galeri, dan perubahan akun akan tercatat otomatis di sini.</p>
                    </div>
                @endforelse
            </div>

            @if ($logs->hasPages())
                <div class="border-t border-slate-200 px-5 py-4">
                    {{ $logs->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
